<?php
namespace Controller;

class CoordinatorController extends BaseController {

    private function getCoordinatorDept($db, $userId) {
        $stmt = $db->prepare("SELECT department FROM coordinators WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    private function getCoordinatorShift($db, $userId) {
        $stmt = $db->prepare("SELECT shift FROM coordinators WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() ?: 'Morning';
    }

    private function getCoordinatorName($db, $userId) {
        $stmt = $db->prepare("SELECT name FROM coordinators WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    private function getHodNameForDept($db, $dept) {
        // Query the HOD for the department
        $stmt = $db->prepare("SELECT name FROM hods WHERE department LIKE ? OR ? LIKE CONCAT('%', department, '%') LIMIT 1");
        $stmt->execute(['%' . $dept . '%', $dept]);
        return $stmt->fetchColumn() ?: 'HOD';
    }

    public function dashboard() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getCoordinatorDept($db, $userId);
        $shift = $this->getCoordinatorShift($db, $userId);
        
        $shiftFilter = ($shift !== 'All') ? " AND s.shift = '$shift'" : "";
        
        $stats = [];
        // Pending student approvals in department & shift
        $stmt = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ?$shiftFilter");
        $stmt->execute([$dept]);
        $stats['pending_approvals'] = $stmt->fetchColumn();
        
        // Active students in department & shift
        $stmt = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'approved' AND s.department = ?$shiftFilter");
        $stmt->execute([$dept]);
        $stats['total_students'] = $stmt->fetchColumn();

        // Notices generated
        $stmt = $db->prepare("SELECT COUNT(*) FROM notices WHERE sender_id = ?");
        $stmt->execute([$userId]);
        $stats['total_notices'] = $stmt->fetchColumn();

        // Department meetings awaiting verification (Completed status, active batch only)
        $stmtMeetings = $db->prepare("SELECT COUNT(*) FROM meetings m
            JOIN `groups` g ON m.group_id = g.id
            JOIN academic_batches b ON g.batch_id = b.id
            JOIN students s ON g.created_by = s.user_id
            WHERE s.department = ? AND m.status = 'Completed' AND b.is_active = 1$shiftFilter");
        $stmtMeetings->execute([$dept]);
        $stats['pending_meetings'] = $stmtMeetings->fetchColumn();

        // Unverified / Pending Proposals count (Active batch only)
        $stmtPendingCount = $db->prepare("SELECT COUNT(*) FROM proposals pr
            JOIN `groups` g ON pr.group_id = g.id
            JOIN academic_batches b ON g.batch_id = b.id
            JOIN students s ON g.created_by = s.user_id
            WHERE s.department = ? AND pr.status IN ('Supervisor Approved', 'Submitted', 'Under Review', 'Revision Requested') AND b.is_active = 1$shiftFilter");
        $stmtPendingCount->execute([$dept]);
        $stats['pending_proposals'] = $stmtPendingCount->fetchColumn();

        // Fetch unverified proposals for the department & shift (active batch only)
        $stmtProposals = $db->prepare("SELECT pr.*, g.group_code, g.created_by, p.id as project_id, p.title as project_title, p.supervisor_id, p.thesis_file, sup.name as supervisor_name 
            FROM proposals pr
            JOIN `groups` g ON pr.group_id = g.id
            JOIN academic_batches b ON g.batch_id = b.id
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? AND pr.status IN ('Supervisor Approved', 'Submitted', 'Under Review', 'Revision Requested') AND b.is_active = 1$shiftFilter
            ORDER BY 
                CASE 
                    WHEN pr.status = 'Supervisor Approved' THEN 1 
                    WHEN pr.status = 'Submitted' THEN 2 
                    WHEN pr.status = 'Under Review' THEN 3
                    WHEN pr.status = 'Revision Requested' THEN 4
                    ELSE 5 
                END, 
                pr.submitted_at DESC");
        $stmtProposals->execute([$dept]);
        $pendingProposals = $stmtProposals->fetchAll();

        // Fetch members for each proposal group
        foreach ($pendingProposals as &$pr) {
            $stmtM = $db->prepare("SELECT s_m.student_id as roll_no, s_m.name as student_name, s_m.avatar FROM group_members gm 
                JOIN students s_m ON gm.student_id = s_m.user_id 
                JOIN users u_m ON s_m.user_id = u_m.id 
                WHERE gm.group_id = ?");
            $stmtM->execute([$pr['group_id']]);
            $pr['members'] = $stmtM->fetchAll();
        }

        // Fetch departmental supervisors for re-assignment inside review modal
        $stmtSups = $db->prepare("SELECT s.user_id, s.name, s.designation 
            FROM supervisors s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.department = ? AND u.status = 'approved' 
            ORDER BY s.name ASC");
        $stmtSups->execute([$dept]);
        $supervisors = $stmtSups->fetchAll();

        $this->render('coordinator/dashboard', [
            'stats' => $stats,
            'pendingProposals' => $pendingProposals,
            'supervisors' => $supervisors,
            'department' => $dept,
            'shift' => $shift
        ]);
    }

    public function verifyStudents() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);
        $shift = $this->getCoordinatorShift($db, $_SESSION['user_id'] ?? 0);
        $shiftFilter = ($shift !== 'All') ? " AND s.shift = '$shift'" : "";

        $stmt = $db->prepare("SELECT s.*, u.email, u.status FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ?$shiftFilter ORDER BY u.created_at DESC");
        $stmt->execute([$dept]);
        $students = $stmt->fetchAll();

        $this->render('coordinator/verify_students', [
            'students' => $students,
            'shift' => $shift
        ]);
    }

    public function approveStudent() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

            // Check student department
            $stmtCheck = $db->prepare("SELECT department FROM students WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            $studentDept = $stmtCheck->fetchColumn();

            if ($studentDept === $dept) {
                $stmt = $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
                $stmt->execute([$id]);

                // Fetch student details for email
                $stmtUser = $db->prepare("
                    SELECT u.email, s.student_id 
                    FROM users u 
                    JOIN students s ON u.id = s.user_id 
                    WHERE u.id = ?
                ");
                $stmtUser->execute([$id]);
                $user = $stmtUser->fetch();

                if ($user) {
                    $this->addNotification($id, 'Account Approved', 'Your registration has been approved! You can now log in.');
                    
                    $subject = "Your Account has been Approved";
                    $identifierStr = "Roll Number: " . $user['student_id'] . "\nPassword: (The password you chose during registration)";
                    
                    $message = "Hello,\n\nYour account on the FYP Management Portal has been approved by your Coordinator.\n\n"
                             . "Your Login Credentials:\n"
                             . $identifierStr . "\n\n"
                             . "You can now log in to the portal.\n\nRegards,\nFYP Management Team";
                             
                    $this->sendEmail($user['email'], $subject, $message);
                }

                $this->flash('success', 'Student account approved successfully.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/coordinator/users');
    }

    public function rejectStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $id = $_POST['id'] ?? null;
            $reason = trim($_POST['reason'] ?? '');
            
            if ($id && $reason) {
                $db = \Database::getInstance()->getConnection();
                $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);
    
                // Check student department
                $stmtCheck = $db->prepare("SELECT department, avatar FROM students WHERE user_id = ?");
                $stmtCheck->execute([$id]);
                $student = $stmtCheck->fetch();
    
                if ($student && $student['department'] === $dept) {
                    $avatarFile = $student['avatar'];
                    if ($avatarFile && $avatarFile !== 'default_avatar.svg' && $avatarFile !== 'default_avatar.png') {
                        $filePath = __DIR__ . '/../../public/uploads/avatars/' . $avatarFile;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    
                    $stmtUser = $db->prepare("SELECT email FROM users WHERE id = ?");
                    $stmtUser->execute([$id]);
                    $userEmail = $stmtUser->fetchColumn();
                    
                    if ($userEmail) {
                        $subject = "Your Registration has been Rejected";
                        $message = "Hello,\n\nUnfortunately, your registration for the FYP Management Portal has been rejected by your Coordinator.\n\n"
                                 . "Reason for rejection:\n$reason\n\n"
                                 . "Please correct the issues mentioned above and create a new account, or contact your department if you believe this was a mistake.\n\n"
                                 . "Regards,\nFYP Management Team";
                        $this->sendEmail($userEmail, $subject, $message);
                    }
    
                    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$id]);
                    $this->flash('success', 'Student registration rejected and deleted.');
                } else {
                    $this->flash('error', 'Unauthorized: Student is not in your department.');
                }
            } else {
                $this->flash('error', 'Rejection reason is required.');
            }
        }
        redirect('/coordinator/users');
    }

    public function notice() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        
        $stmt = $db->prepare("SELECT * FROM notices WHERE sender_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $notices = $stmt->fetchAll();

        $this->render('coordinator/notice', [
            'notices' => $notices
        ]);
    }

    public function createNotice() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $body = trim($_POST['body'] ?? '');
            $notice_date = $_POST['notice_date'] ?? date('Y-m-d');
            $ref_no = trim($_POST['ref_no'] ?? '');
            $target_audiences = $_POST['target_audiences'] ?? [];
            $is_public = isset($_POST['is_public']) ? 1 : 0;

            if (empty($subject) || empty($body) || empty($notice_date)) {
                $this->flash('error', 'Subject, Date and Body are required.');
                redirect('/coordinator/notice');
            }

            if (empty($target_audiences)) {
                $this->flash('error', 'Please select at least one Target Audience group.');
                redirect('/coordinator/notice');
            }

            $target_audience = implode(',', $target_audiences);

            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            $dept = $this->getCoordinatorDept($db, $userId);

            try {
                $db->beginTransaction();

                $stmt = $db->prepare("INSERT INTO notices (sender_id, subject, body, notice_date, ref_no, target_audience, department, is_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $subject, $body, $notice_date, $ref_no ?: null, $target_audience, $dept, $is_public]);
                $noticeId = $db->lastInsertId();

                // Send notifications to target audience in the department
                $recipients = [];
                if (in_array('students', $target_audiences)) {
                    $stmtStudents = $db->prepare("SELECT user_id FROM students WHERE department = ?");
                    $stmtStudents->execute([$dept]);
                    $recipients = array_merge($recipients, $stmtStudents->fetchAll(\PDO::FETCH_COLUMN));
                }
                if (in_array('supervisors', $target_audiences)) {
                    $stmtSups = $db->prepare("SELECT user_id FROM supervisors WHERE department = ?");
                    $stmtSups->execute([$dept]);
                    $recipients = array_merge($recipients, $stmtSups->fetchAll(\PDO::FETCH_COLUMN));
                }
                if (in_array('committee', $target_audiences)) {
                    $stmtComm = $db->prepare("SELECT user_id FROM committees WHERE department = ?");
                    $stmtComm->execute([$dept]);
                    $recipients = array_merge($recipients, $stmtComm->fetchAll(\PDO::FETCH_COLUMN));
                }
                if (in_array('hod', $target_audiences)) {
                    $stmtHod = $db->prepare("SELECT user_id FROM hods WHERE department LIKE ? OR ? LIKE CONCAT('%', department, '%')");
                    $stmtHod->execute(['%' . $dept . '%', $dept]);
                    $recipients = array_merge($recipients, $stmtHod->fetchAll(\PDO::FETCH_COLUMN));
                }

                $recipients = array_unique($recipients);
                
                foreach ($recipients as $recId) {
                    $this->addNotification($recId, 'New Department Notice', "Notice: $subject. Click to view.", '/notice/view?id=' . $noticeId);
                }

                $db->commit();
                $this->flash('success', 'Notice generated and broadcasted successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error generating notice: ' . $e->getMessage());
            }
        }
        redirect('/coordinator/notice');
    }
    public function toggleNoticeVisibility() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            
            $stmt = $db->prepare("SELECT is_hidden FROM notices WHERE id = ? AND sender_id = ?");
            $stmt->execute([$id, $userId]);
            $currentStatus = $stmt->fetchColumn();
            
            if ($currentStatus !== false) {
                $newStatus = $currentStatus ? 0 : 1;
                $updateStmt = $db->prepare("UPDATE notices SET is_hidden = ? WHERE id = ?");
                $updateStmt->execute([$newStatus, $id]);
                $this->flash('success', $newStatus ? 'Notice hidden successfully.' : 'Notice is now visible.');
            }
        }
        redirect('/coordinator/notice');
    }

    public function deleteNotice() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            
            $stmt = $db->prepare("DELETE FROM notices WHERE id = ? AND sender_id = ?");
            $stmt->execute([$id, $userId]);
            $this->flash('success', 'Notice deleted successfully.');
        }
        redirect('/coordinator/notice');
    }

    public function externalAssessment() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);
        
        $this->render('coordinator/external_assessment', [
            'department' => $dept
        ]);
    }

    public function generateExternalAssessment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attrNames = $_POST['attr_names'] ?? [];
            $attrMarks = $_POST['attr_marks'] ?? [];
            
            if (empty($attrNames) || count($attrNames) !== count($attrMarks)) {
                $this->flash('error', 'Invalid attributes configuration.');
                redirect('/coordinator/assessment');
            }

            $total = 0;
            $attributes = [];
            for ($i = 0; $i < count($attrNames); $i++) {
                $name = trim($attrNames[$i]);
                $marks = (int)$attrMarks[$i];
                if (!empty($name) && $marks > 0) {
                    $attributes[] = ['name' => $name, 'marks' => $marks];
                    $total += $marks;
                }
            }

            if ($total !== 50) {
                $this->flash('error', "Total marks must exactly equal 50. Currently: $total");
                redirect('/coordinator/assessment');
            }

            $shift = $_POST['shift'] ?? 'Combined';

            $db = \Database::getInstance()->getConnection();
            $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

            $query = "
                SELECT g.id as group_id, g.group_code, p.title as project_title, 
                       u_stu.name as student_name, u_stu.student_id as roll_no,
                       sup.name as supervisor_name
                FROM `groups` g
                LEFT JOIN projects p ON p.group_id = g.id
                LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
                JOIN group_members gm ON gm.group_id = g.id
                JOIN students u_stu ON gm.student_id = u_stu.user_id
                LEFT JOIN students s ON s.user_id = g.created_by
                WHERE s.department = ? AND p.status = 'Approved'
            ";

            $params = [$dept];
            if ($shift === 'Morning' || $shift === 'Evening') {
                $query .= " AND u_stu.shift = ?";
                $params[] = $shift;
            }

            $query .= " ORDER BY g.group_code ASC, u_stu.student_id ASC";

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $students = $stmt->fetchAll();

            // Group the students by group_id
            $grouped = [];
            foreach ($students as $s) {
                $gid = $s['group_id'];
                if (!isset($grouped[$gid])) {
                    $grouped[$gid] = [];
                }
                $grouped[$gid][] = $s;
            }

            $this->render('coordinator/assessment_report', [
                'attributes' => $attributes,
                'grouped' => $grouped,
                'shift' => $shift,
                'department' => $dept
            ]);
            exit;
        }
        redirect('/coordinator/assessment');
    }

    public function proposals() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);
        $shift = $this->getCoordinatorShift($db, $_SESSION['user_id'] ?? 0);
        $shiftFilter = ($shift !== 'All') ? " AND s.shift = '$shift'" : "";

        // Fetch proposals for groups where the group creator is a student in the coordinator's department & shift (active batch only)
        $stmt = $db->prepare("SELECT pr.*, g.group_code, g.created_by, p.id as project_id, p.title as project_title, p.supervisor_id, p.thesis_file, sup.name as supervisor_name 
            FROM proposals pr
            JOIN `groups` g ON pr.group_id = g.id
            JOIN academic_batches b ON g.batch_id = b.id
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? AND b.is_active = 1$shiftFilter 
            ORDER BY pr.submitted_at DESC");
        $stmt->execute([$dept]);
        $proposals = $stmt->fetchAll();

        // Fetch departmental supervisors for re-assignment
        $stmtSups = $db->prepare("SELECT s.user_id, s.name, s.designation 
            FROM supervisors s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.department = ? AND u.status = 'approved' 
            ORDER BY s.name ASC");
        $stmtSups->execute([$dept]);
        $supervisors = $stmtSups->fetchAll();

        // Fetch members for each proposal group
        foreach ($proposals as &$pr) {
            $stmtM = $db->prepare("SELECT s_m.student_id as roll_no, s_m.name as student_name, s_m.avatar FROM group_members gm 
                JOIN students s_m ON gm.student_id = s_m.user_id 
                JOIN users u_m ON s_m.user_id = u_m.id 
                WHERE gm.group_id = ?");
            $stmtM->execute([$pr['group_id']]);
            $pr['members'] = $stmtM->fetchAll();
        }

        $this->render('coordinator/proposals', [
            'proposals' => $proposals,
            'supervisors' => $supervisors,
            'shift' => $shift
        ]);
    }

    public function reviewProposal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/coordinator/proposals');
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            $this->flash('error', 'Invalid security token.');
            redirect('/coordinator/proposals');
        }

        $proposalId = (int)($_POST['proposal_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $supervisorId = !empty($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : null;
        $groupCode = trim($_POST['group_code'] ?? '');
        $remarks = trim($_POST['remarks'] ?? '');

        $allowedStatuses = ['Approved', 'Supervisor Approved', 'Revision Requested', 'Rejected', 'Submitted'];
        if (!$proposalId || !in_array($status, $allowedStatuses)) {
            $this->flash('error', 'Invalid proposal review data.');
            redirect('/coordinator/proposals');
        }

        $db = \Database::getInstance()->getConnection();
        $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

        try {
            $db->beginTransaction();

            // Fetch proposal & verify department
            $stmt = $db->prepare("SELECT pr.*, g.id as group_id, g.group_code as current_group_code, g.created_by, p.id as project_id, p.title as project_title, p.supervisor_id as current_supervisor_id 
                FROM proposals pr
                JOIN `groups` g ON pr.group_id = g.id
                JOIN projects p ON g.id = p.group_id
                JOIN students s ON g.created_by = s.user_id
                WHERE pr.id = ? AND s.department = ?");
            $stmt->execute([$proposalId, $dept]);
            $prop = $stmt->fetch();

            if (!$prop) {
                $db->rollBack();
                $this->flash('error', 'Proposal not found or access denied.');
                redirect('/coordinator/proposals');
            }

            $groupId = $prop['group_id'];
            $projectId = $prop['project_id'];

            // 1. Update proposals table
            $stmtPr = $db->prepare("UPDATE proposals SET status = ?, feedback = ? WHERE id = ?");
            $stmtPr->execute([$status, $remarks, $proposalId]);

            // 2. Update projects table (status and supervisor)
            $newSupervisorId = $supervisorId ?: $prop['current_supervisor_id'];
            $stmtP = $db->prepare("UPDATE projects SET status = ?, supervisor_id = ? WHERE id = ?");
            $stmtP->execute([$status, $newSupervisorId, $projectId]);

            // 3. Update groups table & Auto-assign Group Code
            if ($status === 'Approved') {
                $stage = 'Proposal Approved';
                // Auto-generate group code if not already assigned
                if (empty($prop['current_group_code'])) {
                    $stmtLeader = $db->prepare("SELECT student_id, department, shift FROM students WHERE user_id = ?");
                    $stmtLeader->execute([$prop['created_by']]);
                    $studentInfo = $stmtLeader->fetch();
                    
                    $rollNo = $studentInfo['student_id'] ?? '';
                    $parts = explode('/', $rollNo);
                    $year = !empty($parts[0]) ? trim($parts[0]) : '2k23';
                    
                    $deptMap = [
                        'Software Engineering' => 'SWE',
                        'Information Technology' => 'IT',
                        'Data Science' => 'DS',
                        'Electronic Engineering' => 'EL',
                        'Telecommunication Engineering' => 'TL'
                    ];
                    $deptCode = $deptMap[$studentInfo['department'] ?? ''] ?? 'GEN';
                    $shiftLetter = (($studentInfo['shift'] ?? '') === 'Evening') ? 'E' : 'M';
                    
                    $prefix = $year . '-' . $deptCode . $shiftLetter . '-';
                    
                    $stmtCount = $db->prepare("SELECT COUNT(*) FROM `groups` WHERE group_code LIKE ?");
                    $stmtCount->execute([$prefix . '%']);
                    $count = (int)$stmtCount->fetchColumn();
                    $nextNumber = $count + 1;
                    $autoGroupCode = $prefix . $nextNumber;
                    
                    $stmtUpdateCode = $db->prepare("UPDATE `groups` SET group_code = ? WHERE id = ?");
                    $stmtUpdateCode->execute([$autoGroupCode, $groupId]);
                    $groupCode = $autoGroupCode;
                }
                
                $stmtProg = $db->prepare("UPDATE `groups` SET progress_stage = ? WHERE id = ?");
                $stmtProg->execute([$stage, $groupId]);
            } else {
                $stmtProg = $db->prepare("UPDATE `groups` SET progress_stage = 'Proposal Submitted' WHERE id = ?");
                $stmtProg->execute([$groupId]);
            }

            // 4. Send Notifications
            $displayCode = !empty($groupCode) ? $groupCode : (!empty($prop['current_group_code']) ? $prop['current_group_code'] : 'Proposal #' . $proposalId);
            
            // Notify student group members
            $stmtMembers = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
            $stmtMembers->execute([$groupId]);
            $memberIds = $stmtMembers->fetchAll(\PDO::FETCH_COLUMN);

            $notifMsg = "Your project proposal has been reviewed by the Department Coordinator. Status: $status." . (!empty($remarks) ? " Remarks: $remarks" : "");
            foreach ($memberIds as $mId) {
                $this->addNotification($mId, 'Proposal Reviewed by Coordinator', $notifMsg);
            }

            // Notify supervisor
            if ($newSupervisorId) {
                $supNotifMsg = "Group ($displayCode) proposal has been marked as '$status' by the Coordinator." . (!empty($remarks) ? " Remarks: $remarks" : "");
                if ($newSupervisorId != $prop['current_supervisor_id']) {
                    $supNotifMsg = "Group ($displayCode) has been assigned to you as supervisor by the Coordinator.";
                }
                $this->addNotification($newSupervisorId, 'Coordinator Proposal Update', $supNotifMsg);
            }

            $db->commit();
            $this->flash('success', "Proposal successfully updated to '$status'.");
        } catch (\Exception $e) {
            $db->rollBack();
            $this->flash('error', 'Error updating proposal. Please try again.');
        }

        redirect('/coordinator/proposals');
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch coordinator details
        $stmt = $db->prepare("SELECT c.name, c.department, u.email, u.cnic FROM coordinators c JOIN users u ON c.user_id = u.id WHERE c.user_id = ?");
        $stmt->execute([$userId]);
        $coordinator = $stmt->fetch();
        if (!$coordinator) {
            die("Coordinator profile not found.");
        }

        // Get existing profile info
        $stmt = $db->prepare("SELECT * FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $prefix = trim($_POST['prefix'] ?? '');
            $mobile_code = trim($_POST['mobile_code'] ?? '');
            $mobile_no = trim($_POST['mobile_no'] ?? '');
            $home_address = trim($_POST['home_address'] ?? '');
            
            // Check if CNIC was missing and is now submitted
            $cnic = trim($_POST['cnic'] ?? '');
            $hasCnicInDb = !empty($coordinator['cnic']);
            $cnicToSave = $coordinator['cnic'];

            if (empty($prefix)) $errors[] = "Prefix is required.";
            if (empty($mobile_code)) $errors[] = "Mobile Code is required.";
            if (empty($mobile_no)) $errors[] = "Mobile Number is required.";
            if (empty($home_address) || $home_address === 'Not Provided Yet') $errors[] = "Home/Office Address is required.";

            if (!$hasCnicInDb) {
                if (empty($cnic)) {
                    $errors[] = "CNIC is required.";
                } else {
                    $cnic = str_replace('-', '', $cnic);
                    if (!preg_match('/^[0-9]+$/', $cnic)) {
                        $errors[] = "CNIC must contain numbers only.";
                    } else {
                        // Check uniqueness
                        $stmtCheck = $db->prepare("SELECT id FROM users WHERE cnic = ? AND id != ?");
                        $stmtCheck->execute([$cnic, $userId]);
                        if ($stmtCheck->fetch()) {
                            $errors[] = "This CNIC is already registered.";
                        } else {
                            $cnicToSave = $cnic;
                        }
                    }
                }
            }

            // Check if Surname was missing and is now submitted
            $surname = trim($_POST['surname'] ?? '');
            $hasSurnameInDb = !empty($profile['surname']);
            $surnameToSave = $profile['surname'] ?? '';
            if (!$hasSurnameInDb) {
                if (empty($surname)) {
                    $errors[] = "Surname is required.";
                } else {
                    $surnameToSave = $surname;
                }
            }

            if (empty($errors)) {
                try {
                    $db->beginTransaction();

                    // Update profiles table
                    $stmt = $db->prepare("UPDATE profiles SET prefix = ?, mobile_code = ?, mobile_no = ?, home_address = ?, cnic = ?, surname = ? WHERE user_id = ?");
                    $stmt->execute([$prefix, $mobile_code, $mobile_no, $home_address, $cnicToSave, $surnameToSave, $userId]);

                    // Update users table cnic if it was updated
                    if (!$hasCnicInDb) {
                        $stmt = $db->prepare("UPDATE users SET cnic = ? WHERE id = ?");
                        $stmt->execute([$cnicToSave, $userId]);
                    }

                    $db->commit();
                    $this->flash('success', 'Profile updated successfully.');
                    redirect('/coordinator/profile');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Database error: ' . $e->getMessage());
                }
            } else {
                $this->flash('error', implode(" ", $errors));
            }
        }

        $this->render('coordinator/profile', [
            'coordinator' => $coordinator,
            'profile' => $profile
        ]);
    }

    private function sendEmail($toEmail, $subject, $message) {
        $mailConfig = require __DIR__ . '/../../config/mail.php';

        if (isset($mailConfig['smtp_username']) && $mailConfig['smtp_username'] !== 'your_email@gmail.com' && !empty($mailConfig['smtp_password'])) {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $mailConfig['smtp_host'];
                $mail->SMTPAuth   = $mailConfig['smtp_auth'];
                $mail->Username   = $mailConfig['smtp_username'];
                $mail->Password   = $mailConfig['smtp_password'];
                $mail->SMTPSecure = ($mailConfig['smtp_secure'] === 'ssl') ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $mailConfig['smtp_port'];

                $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
                $mail->addAddress($toEmail);

                $mail->isHTML(false);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
            } catch (\Exception $e) {
                error_log("PHPMailer failed in CoordinatorController: " . $mail->ErrorInfo);
            }
        }
    }

    public function meetings() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getCoordinatorDept($db, $userId);

        $stmt = $db->prepare("
            SELECT m.*, p.title as project_title, g.group_code, s.name as group_leader_name, sup.name as supervisor_name
            FROM meetings m
            JOIN `groups` g ON m.group_id = g.id
            JOIN academic_batches b ON g.batch_id = b.id
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            JOIN supervisors sup ON m.supervisor_id = sup.user_id
            WHERE s.department = ? AND m.status IN ('Completed', 'Verified') AND b.is_active = 1
            ORDER BY m.meeting_date DESC
        ");
        $stmt->execute([$dept]);
        $meetings = $stmt->fetchAll();

        $this->render('coordinator/meetings', [
            'meetings' => $meetings
        ]);
    }

    public function verifyMeeting() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $meetingId = $_POST['meeting_id'] ?? null;
            $status = $_POST['status'] ?? ''; 

            if ($meetingId && $status === 'Verified') {
                $db = \Database::getInstance()->getConnection();
                
                $userId = $_SESSION['user_id'];
                $dept = $this->getCoordinatorDept($db, $userId);

                $stmt = $db->prepare("
                    SELECT m.id 
                    FROM meetings m
                    JOIN `groups` g ON m.group_id = g.id
                    JOIN students s ON g.created_by = s.user_id
                    WHERE m.id = ? AND s.department = ?
                ");
                $stmt->execute([$meetingId, $dept]);
                $isValid = $stmt->fetchColumn();

                if ($isValid) {
                    $stmtUpdate = $db->prepare("UPDATE meetings SET status = 'Verified' WHERE id = ?");
                    $stmtUpdate->execute([$meetingId]);
                    $this->flash('success', 'Meeting successfully verified.');
                } else {
                    $this->flash('error', 'Unauthorized action.');
                }
            }
        }
        redirect('/coordinator/meetings');
    }

    public function committees() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getCoordinatorDept($db, $userId);
        $shift = $this->getCoordinatorShift($db, $userId);

        // Fetch department settings for number of committees
        $stmtDept = $db->prepare("SELECT num_committees FROM department_settings WHERE department = ?");
        $stmtDept->execute([$dept]);
        $numCommittees = (int)($stmtDept->fetchColumn() ?: 2);

        // Fetch active committee evaluators
        $stmtComm = $db->prepare("
            SELECT c.*, u.email 
            FROM committees c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.department = ? 
            ORDER BY c.committee_number ASC, c.name ASC
        ");
        $stmtComm->execute([$dept]);
        $allCommitteeMembers = $stmtComm->fetchAll();

        $committeeMembers = [];
        for ($i = 1; $i <= $numCommittees; $i++) {
            $committeeMembers[$i] = array_values(array_filter($allCommitteeMembers, fn($m) => (int)($m['committee_number'] ?? 1) === $i));
        }

        // Fetch active approved groups in coordinator's shift & department
        $shiftSql = ($shift !== 'All') ? " AND s.shift = ?" : "";
        $params = [$dept];
        if ($shift !== 'All') {
            $params[] = $shift;
        }

        $stmtGroups = $db->prepare("
            SELECT g.id, g.group_code, g.committee_number, g.progress_stage, g.created_at,
                   p.id as project_id, p.title as project_title, p.status as project_status,
                   sup.name as supervisor_name, sup.designation as supervisor_designation,
                   s.shift as student_shift
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE s.department = ? AND p.status = 'Approved' AND b.is_active = 1 $shiftSql
            ORDER BY g.group_code ASC, g.id ASC
        ");
        $stmtGroups->execute($params);
        $groups = $stmtGroups->fetchAll();

        // Fetch members for each group
        foreach ($groups as &$grp) {
            $stmtM = $db->prepare("
                SELECT s_m.student_id as roll_no, s_m.name as student_name, s_m.avatar 
                FROM group_members gm 
                JOIN students s_m ON gm.student_id = s_m.user_id 
                WHERE gm.group_id = ?
            ");
            $stmtM->execute([$grp['id']]);
            $grp['members'] = $stmtM->fetchAll();
        }

        // Calculate distribution stats
        $committeeCounts = [];
        for ($i = 1; $i <= $numCommittees; $i++) {
            $committeeCounts[$i] = 0;
        }
        $unassignedCount = 0;

        foreach ($groups as $g) {
            $cNum = $g['committee_number'];
            if ($cNum && isset($committeeCounts[(int)$cNum])) {
                $committeeCounts[(int)$cNum]++;
            } else {
                $unassignedCount++;
            }
        }

        $this->render('coordinator/committee_allocation', [
            'department' => $dept,
            'shift' => $shift,
            'numCommittees' => $numCommittees,
            'committeeMembers' => $committeeMembers,
            'groups' => $groups,
            'committeeCounts' => $committeeCounts,
            'unassignedCount' => $unassignedCount,
            'totalGroups' => count($groups)
        ]);
    }

    public function distributeCommittees() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            $dept = $this->getCoordinatorDept($db, $userId);
            $shift = $this->getCoordinatorShift($db, $userId);

            $capacities = $_POST['capacity'] ?? [];

            // Fetch active approved groups in coordinator's department & shift ordered sequentially
            $shiftSql = ($shift !== 'All') ? " AND s.shift = ?" : "";
            $params = [$dept];
            if ($shift !== 'All') {
                $params[] = $shift;
            }

            $stmtGroups = $db->prepare("
                SELECT g.id, g.group_code
                FROM `groups` g
                JOIN projects p ON g.id = p.group_id
                JOIN students s ON g.created_by = s.user_id
                JOIN academic_batches b ON g.batch_id = b.id
                WHERE s.department = ? AND p.status = 'Approved' AND b.is_active = 1 $shiftSql
                ORDER BY g.group_code ASC, g.id ASC
            ");
            $stmtGroups->execute($params);
            $groups = $stmtGroups->fetchAll();

            $totalGroups = count($groups);
            if ($totalGroups === 0) {
                $this->flash('error', 'No approved project groups found to allocate.');
                redirect('/coordinator/committees');
            }

            try {
                $db->beginTransaction();

                $assignedIndex = 0;
                $summaryParts = [];

                foreach ($capacities as $commNum => $cap) {
                    $commNum = (int)$commNum;
                    $cap = max(0, (int)$cap);
                    $assignedToThis = 0;

                    for ($i = 0; $i < $cap && $assignedIndex < $totalGroups; $i++) {
                        $grpId = $groups[$assignedIndex]['id'];
                        $stmtUp = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                        $stmtUp->execute([$commNum, $grpId]);
                        $assignedIndex++;
                        $assignedToThis++;
                    }

                    if ($assignedToThis > 0) {
                        $summaryParts[] = "$assignedToThis groups to Committee $commNum";
                    }
                }

                // Any remaining overflow groups go to the last committee
                if ($assignedIndex < $totalGroups) {
                    $lastComm = count($capacities) > 0 ? max(array_keys($capacities)) : 1;
                    $overflowCount = 0;
                    while ($assignedIndex < $totalGroups) {
                        $grpId = $groups[$assignedIndex]['id'];
                        $stmtUp = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                        $stmtUp->execute([$lastComm, $grpId]);
                        $assignedIndex++;
                        $overflowCount++;
                    }
                    if ($overflowCount > 0) {
                        $summaryParts[] = "$overflowCount extra groups to Committee $lastComm";
                    }
                }

                $db->commit();
                $this->flash('success', "Sequential distribution complete: " . implode(', ', $summaryParts) . " ($totalGroups total groups).");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error applying committee distribution. Please try again.');
            }
        }
        redirect('/coordinator/committees');
    }

    public function reassignGroupCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $groupId = (int)($_POST['group_id'] ?? 0);
            $committeeNumber = max(1, (int)($_POST['committee_number'] ?? 1));

            if ($groupId > 0) {
                $stmt = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                $stmt->execute([$committeeNumber, $groupId]);
                $this->flash('success', "Group successfully reallocated to Committee $committeeNumber.");
            } else {
                $this->flash('error', 'Invalid group selection.');
            }
        }
        redirect('/coordinator/committees');
    }

    public function deadlines() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getCoordinatorDept($db, $userId) ?: 'Software Engineering';
        $coordShift = $this->getCoordinatorShift($db, $userId) ?: 'Morning';

        if ($coordShift !== 'All') {
            $selectedShift = $coordShift;
            $stmt = $db->prepare("SELECT * FROM deadlines WHERE department = ? AND (shift = ? OR shift = 'All') ORDER BY deadline_date ASC");
            $stmt->execute([$dept, $coordShift]);
        } else {
            $selectedShift = $_GET['shift'] ?? 'All';
            if (!in_array($selectedShift, ['Morning', 'Evening', 'All'])) {
                $selectedShift = 'All';
            }
            if ($selectedShift === 'All') {
                $stmt = $db->prepare("SELECT * FROM deadlines WHERE department = ? ORDER BY deadline_date ASC");
                $stmt->execute([$dept]);
            } else {
                $stmt = $db->prepare("SELECT * FROM deadlines WHERE department = ? AND (shift = ? OR shift = 'All') ORDER BY deadline_date ASC");
                $stmt->execute([$dept, $selectedShift]);
            }
        }
        $deadlines = $stmt->fetchAll();

        $stmtAll = $db->prepare("SELECT COUNT(*) FROM deadlines WHERE department = ? AND status = 'Active' AND deadline_date >= NOW()");
        $stmtAll->execute([$dept]);
        $upcomingCount = (int)$stmtAll->fetchColumn();

        $this->render('coordinator/deadlines', [
            'department' => $dept,
            'coordinatorShift' => $coordShift,
            'selectedShift' => $selectedShift,
            'deadlines' => $deadlines,
            'upcomingCount' => $upcomingCount
        ]);
    }

    public function saveDeadline() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            $dept = $this->getCoordinatorDept($db, $userId) ?: 'Software Engineering';
            $coordShift = $this->getCoordinatorShift($db, $userId) ?: 'Morning';

            $id = (int)($_POST['id'] ?? 0);
            $stage = trim($_POST['stage'] ?? '');
            
            // If coordinator is assigned to Morning or Evening only, force to their shift
            if ($coordShift !== 'All') {
                $shift = $coordShift;
            } else {
                $shift = trim($_POST['shift'] ?? 'All');
                if (!in_array($shift, ['Morning', 'Evening', 'All'])) {
                    $shift = 'All';
                }
            }

            $deadlineDate = trim($_POST['deadline_date'] ?? '');
            $status = trim($_POST['status'] ?? 'Active');

            $allowedStages = [
                'Proposal Submission',
                'Proposal Defence Presentation',
                'FYP Progress Presentation',
                'Final Presentation'
            ];
            $allowedStatuses = ['Active', 'Inactive'];

            if (!in_array($stage, $allowedStages) || empty($deadlineDate)) {
                $this->flash('error', 'Please select a valid stage and provide a valid deadline date & time.');
                redirect('/coordinator/deadlines');
            }

            if (!in_array($status, $allowedStatuses)) {
                $status = 'Active';
            }

            $formattedDate = date('Y-m-d H:i:s', strtotime($deadlineDate));

            if ($id > 0) {
                $stmt = $db->prepare("UPDATE deadlines SET stage = ?, shift = ?, deadline_date = ?, status = ? WHERE id = ? AND department = ?");
                $stmt->execute([$stage, $shift, $formattedDate, $status, $id, $dept]);
                $this->flash('success', "Deadline for '$stage' (" . ($shift === 'All' ? 'All Shifts' : "$shift Shift") . ") updated successfully.");
            } else {
                $stmtCheck = $db->prepare("SELECT id FROM deadlines WHERE stage = ? AND department = ? AND shift = ?");
                $stmtCheck->execute([$stage, $dept, $shift]);
                $existingId = $stmtCheck->fetchColumn();

                if ($existingId) {
                    $stmtUpdate = $db->prepare("UPDATE deadlines SET deadline_date = ?, status = ? WHERE id = ?");
                    $stmtUpdate->execute([$formattedDate, $status, $existingId]);
                    $this->flash('success', "Existing deadline for '$stage' (" . ($shift === 'All' ? 'All Shifts' : "$shift Shift") . ") updated with new schedule.");
                } else {
                    $stmtInsert = $db->prepare("INSERT INTO deadlines (stage, department, shift, deadline_date, status) VALUES (?, ?, ?, ?, ?)");
                    $stmtInsert->execute([$stage, $dept, $shift, $formattedDate, $status]);
                    $this->flash('success', "New deadline for '$stage' (" . ($shift === 'All' ? 'All Shifts' : "$shift Shift") . ") published successfully.");
                }
            }
        }
        redirect('/coordinator/deadlines');
    }

    public function deleteDeadline() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            $dept = $this->getCoordinatorDept($db, $userId) ?: 'Software Engineering';
            $coordShift = $this->getCoordinatorShift($db, $userId) ?: 'Morning';

            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                if ($coordShift !== 'All') {
                    $stmt = $db->prepare("DELETE FROM deadlines WHERE id = ? AND department = ? AND (shift = ? OR shift = 'All')");
                    $stmt->execute([$id, $dept, $coordShift]);
                } else {
                    $stmt = $db->prepare("DELETE FROM deadlines WHERE id = ? AND department = ?");
                    $stmt->execute([$id, $dept]);
                }
                $this->flash('success', 'Deadline removed successfully.');
            } else {
                $this->flash('error', 'Invalid deadline ID.');
            }
        }
        redirect('/coordinator/deadlines');
    }

    public function batches() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getCoordinatorDept($db, $userId) ?: 'Software Engineering';
        $shift = $this->getCoordinatorShift($db, $userId) ?: 'Morning';

        if ($shift === 'All') {
            $stmt = $db->prepare("
                SELECT b.*, 
                       COUNT(DISTINCT g.id) as group_count,
                       COUNT(DISTINCT CASE WHEN p.status = 'Approved' THEN p.id END) as approved_projects_count
                FROM academic_batches b
                LEFT JOIN `groups` g ON b.id = g.batch_id
                LEFT JOIN projects p ON g.id = p.group_id
                WHERE b.department = ?
                GROUP BY b.id
                ORDER BY b.created_at DESC
            ");
            $stmt->execute([$dept]);
        } else {
            $stmt = $db->prepare("
                SELECT b.*, 
                       COUNT(DISTINCT g.id) as group_count,
                       COUNT(DISTINCT CASE WHEN p.status = 'Approved' THEN p.id END) as approved_projects_count
                FROM academic_batches b
                LEFT JOIN `groups` g ON b.id = g.batch_id
                LEFT JOIN projects p ON g.id = p.group_id
                WHERE b.department = ? AND (b.shift = ? OR b.shift = 'All')
                GROUP BY b.id
                ORDER BY b.created_at DESC
            ");
            $stmt->execute([$dept, $shift]);
        }
        $batches = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('coordinator/batches', [
            'batches' => $batches,
            'department' => $dept,
            'shift' => $shift
        ]);
    }

    public function createBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $userId = $_SESSION['user_id'] ?? 0;
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getCoordinatorDept($db, $userId) ?: 'Software Engineering';
            $coordShift = $this->getCoordinatorShift($db, $userId) ?: 'Morning';

            $name = trim($_POST['name'] ?? '');
            $shift = ($coordShift === 'All') ? ($_POST['shift'] ?? 'Morning') : $coordShift;
            $activateNow = !empty($_POST['activate_now']);

            if (empty($name)) {
                $this->flash('error', 'Batch name is required.');
                redirect('/coordinator/batches');
            }

            try {
                $db->beginTransaction();

                if ($activateNow) {
                    // Find currently active batch for this department & shift
                    $stmtActive = $db->prepare("SELECT id FROM academic_batches WHERE department = ? AND (shift = ? OR shift = 'All') AND is_active = 1");
                    $stmtActive->execute([$dept, $shift]);
                    $oldBatchIds = $stmtActive->fetchAll(\PDO::FETCH_COLUMN);

                    // Deactivate and close registration for prior active batch
                    $stmtDeact = $db->prepare("UPDATE academic_batches SET is_active = 0, is_registration_open = 0 WHERE department = ? AND (shift = ? OR shift = 'All')");
                    $stmtDeact->execute([$dept, $shift]);

                    // Insert new active batch
                    $stmt = $db->prepare("INSERT INTO academic_batches (name, department, shift, is_active, is_registration_open) VALUES (?, ?, ?, 1, 1)");
                    $stmt->execute([$name, $dept, $shift]);
                    $newBatchId = $db->lastInsertId();

                    $db->commit();

                    // Cleanup chat attachments and notifications for archived batch(es)
                    foreach ($oldBatchIds as $oldId) {
                        $this->cleanupArchivedBatchChat($db, $oldId);
                    }

                    $this->flash('success', "Batch '$name' created and activated! Prior batch projects moved to Previous Projects and chat storage cleaned.");
                } else {
                    $stmt = $db->prepare("INSERT INTO academic_batches (name, department, shift, is_active, is_registration_open) VALUES (?, ?, ?, 0, 0)");
                    $stmt->execute([$name, $dept, $shift]);
                    $db->commit();
                    $this->flash('success', "Batch '$name' created successfully (draft/inactive).");
                }
            } catch (\Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $this->flash('error', 'Failed to create batch: ' . $e->getMessage());
            }
        }
        redirect('/coordinator/batches');
    }

    public function toggleBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $userId = $_SESSION['user_id'] ?? 0;
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getCoordinatorDept($db, $userId) ?: 'Software Engineering';
            $coordShift = $this->getCoordinatorShift($db, $userId) ?: 'Morning';

            $id = (int)($_POST['batch_id'] ?? 0);
            $action = $_POST['action'] ?? '';

            // Verify this batch belongs to coordinator's department & shift
            if ($coordShift === 'All') {
                $stmtCheck = $db->prepare("SELECT * FROM academic_batches WHERE id = ? AND department = ?");
                $stmtCheck->execute([$id, $dept]);
            } else {
                $stmtCheck = $db->prepare("SELECT * FROM academic_batches WHERE id = ? AND department = ? AND (shift = ? OR shift = 'All')");
                $stmtCheck->execute([$id, $dept, $coordShift]);
            }
            $targetBatch = $stmtCheck->fetch(\PDO::FETCH_ASSOC);

            if (!$targetBatch) {
                $this->flash('error', 'Batch not found or access denied.');
                redirect('/coordinator/batches');
            }

            $shift = $targetBatch['shift'];

            try {
                if ($action === 'set_registration') {
                    // Only one batch can have registration open for this (department, shift)
                    $db->beginTransaction();

                    $stmtDeact = $db->prepare("UPDATE academic_batches SET is_registration_open = 0 WHERE department = ? AND (shift = ? OR shift = 'All')");
                    $stmtDeact->execute([$dept, $shift]);

                    $stmt = $db->prepare("UPDATE academic_batches SET is_registration_open = 1, is_active = 1 WHERE id = ?");
                    $stmt->execute([$id]);

                    $db->commit();
                    $this->flash('success', "Batch '{$targetBatch['name']}' is now open for new student registrations.");
                } elseif ($action === 'toggle_active') {
                    $newActive = $targetBatch['is_active'] ? 0 : 1;

                    if ($newActive === 1) {
                        // Activating this batch -> archive other batches for this department & shift
                        $db->beginTransaction();

                        $stmtActive = $db->prepare("SELECT id FROM academic_batches WHERE department = ? AND (shift = ? OR shift = 'All') AND is_active = 1 AND id != ?");
                        $stmtActive->execute([$dept, $shift, $id]);
                        $oldBatchIds = $stmtActive->fetchAll(\PDO::FETCH_COLUMN);

                        $stmtDeact = $db->prepare("UPDATE academic_batches SET is_active = 0, is_registration_open = 0 WHERE department = ? AND (shift = ? OR shift = 'All') AND id != ?");
                        $stmtDeact->execute([$dept, $shift, $id]);

                        $stmt = $db->prepare("UPDATE academic_batches SET is_active = 1, is_registration_open = 1 WHERE id = ?");
                        $stmt->execute([$id]);

                        $db->commit();

                        foreach ($oldBatchIds as $oldId) {
                            $this->cleanupArchivedBatchChat($db, $oldId);
                        }

                        $this->flash('success', "Batch '{$targetBatch['name']}' activated. Prior batch moved to Previous Projects and chat storage cleaned.");
                    } else {
                        // Deactivating / Archiving this batch
                        $db->beginTransaction();
                        $stmt = $db->prepare("UPDATE academic_batches SET is_active = 0, is_registration_open = 0 WHERE id = ?");
                        $stmt->execute([$id]);
                        $db->commit();

                        $this->cleanupArchivedBatchChat($db, $id);

                        $this->flash('success', "Batch '{$targetBatch['name']}' concluded. Its projects moved to Previous Projects and chat storage cleaned.");
                    }
                }
            } catch (\Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $this->flash('error', 'Operation failed: ' . $e->getMessage());
            }
        }
        redirect('/coordinator/batches');
    }

    private function cleanupArchivedBatchChat($db, $batchId) {
        $batchId = (int)$batchId;
        if ($batchId <= 0) return;

        try {
            // Find all groups in this archived batch
            $stmt = $db->prepare("SELECT id, created_by FROM `groups` WHERE batch_id = ?");
            $stmt->execute([$batchId]);
            $groups = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($groups)) {
                $groupIds = array_column($groups, 'id');
                $leaderIds = array_filter(array_column($groups, 'created_by'));

                // Find all student members
                $inGroupIds = implode(',', array_map('intval', $groupIds));
                $stmtM = $db->query("SELECT DISTINCT student_id FROM group_members WHERE group_id IN ($inGroupIds)");
                $memberIds = $stmtM ? $stmtM->fetchAll(\PDO::FETCH_COLUMN) : [];

                $allStudentUserIds = array_unique(array_merge($leaderIds, $memberIds));

                // 1. Delete chat notifications for these groups/users
                if (!empty($allStudentUserIds)) {
                    $inUsers = implode(',', array_map('intval', $allStudentUserIds));
                    $db->exec("DELETE FROM notifications WHERE redirect_url LIKE '%/chat%' AND user_id IN ($inUsers)");
                    foreach ($allStudentUserIds as $sUid) {
                        $db->prepare("DELETE FROM notifications WHERE redirect_url LIKE ? OR redirect_url LIKE ?")
                           ->execute(["%/supervisor/chat?user=$sUid", "%/supervisor/chat?user_id=$sUid"]);
                    }
                }

                // 2. Delete tracked files in chat_attachments for this batch or groups
                $stmtFiles = $db->prepare("SELECT file_path FROM chat_attachments WHERE batch_id = ? OR group_id IN ($inGroupIds)");
                $stmtFiles->execute([$batchId]);
                $files = $stmtFiles->fetchAll(\PDO::FETCH_COLUMN);

                $uploadDir = __DIR__ . '/../../public/uploads/chat_files/';
                foreach ($files as $fPath) {
                    $cleanName = basename($fPath);
                    $fullPath = $uploadDir . $cleanName;
                    if (file_exists($fullPath) && is_file($fullPath)) {
                        @unlink($fullPath);
                    }
                }

                // Delete records from chat_attachments
                $db->prepare("DELETE FROM chat_attachments WHERE batch_id = ? OR group_id IN ($inGroupIds)")->execute([$batchId]);
            }
        } catch (\Exception $e) {
            error_log("cleanupArchivedBatchChat error: " . $e->getMessage());
        }
    }
}


