<?php
namespace Controller;

class CoordinatorController extends BaseController {

    private function getCoordinatorDept($db, $userId) {
        $stmt = $db->prepare("SELECT department FROM coordinators WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
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
        
        $stats = [];
        // Pending student approvals in department
        $stmt = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ?");
        $stmt->execute([$dept]);
        $stats['pending_approvals'] = $stmt->fetchColumn();
        
        // Active students in department
        $stmt = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'approved' AND s.department = ?");
        $stmt->execute([$dept]);
        $stats['total_students'] = $stmt->fetchColumn();

        // Notices generated
        $stmt = $db->prepare("SELECT COUNT(*) FROM notices WHERE sender_id = ?");
        $stmt->execute([$userId]);
        $stats['total_notices'] = $stmt->fetchColumn();

        // Fetch recent notices
        $stmt = $db->prepare("SELECT * FROM notices WHERE sender_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$userId]);
        $recentNotices = $stmt->fetchAll();

        $this->render('coordinator/dashboard', [
            'stats' => $stats,
            'recentNotices' => $recentNotices,
            'department' => $dept
        ]);
    }

    public function verifyStudents() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

        $stmt = $db->prepare("SELECT s.*, u.email, u.status FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ? ORDER BY u.created_at DESC");
        $stmt->execute([$dept]);
        $students = $stmt->fetchAll();

        $this->render('coordinator/verify_students', [
            'students' => $students
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

                $this->addNotification($id, 'Account Approved', 'Your registration has been approved! You can now log in.');
                $this->flash('success', 'Student account approved successfully.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/coordinator/users');
    }

    public function rejectStudent() {
        $id = $_GET['id'] ?? null;
        if ($id) {
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

                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Student registration rejected and deleted.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
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

                $stmt = $db->prepare("INSERT INTO notices (sender_id, subject, body, notice_date, ref_no, target_audience) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $subject, $body, $notice_date, $ref_no ?: null, $target_audience]);
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

    public function viewNotice() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("Notice ID is required.");
        }

        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM notices WHERE id = ?");
        $stmt->execute([$id]);
        $notice = $stmt->fetch();

        if (!$notice) {
            die("Notice not found.");
        }

        // Get Coordinator and HOD signatures
        $coordName = $this->getCoordinatorName($db, $notice['sender_id']);
        $coordDept = $this->getCoordinatorDept($db, $notice['sender_id']);
        $hodName = $this->getHodNameForDept($db, $coordDept);

        // Standalone clean view for letters
        $this->render('coordinator/view_notice', [
            'notice' => $notice,
            'coordName' => $coordName,
            'coordDept' => $coordDept,
            'hodName' => $hodName
        ]);
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

        // Fetch proposals for groups where the group creator is a student in the coordinator's department
        $stmt = $db->prepare("SELECT pr.*, g.group_code, p.title as project_title, sup.name as supervisor_name 
            FROM proposals pr
            JOIN `groups` g ON pr.group_id = g.id
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? 
            ORDER BY pr.submitted_at DESC");
        $stmt->execute([$dept]);
        $proposals = $stmt->fetchAll();

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
            'proposals' => $proposals
        ]);
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
}
