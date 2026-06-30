<?php
namespace Controller;

class SupervisorController extends BaseController {

    public function chat() {
        $db = \Database::getInstance()->getConnection();
        $supervisorId = $_SESSION['user_id'];
        
        // Fetch all group leaders for approved projects assigned to this supervisor
        $stmt = $db->prepare("
            SELECT g.created_by as leader_id, s.name as leader_name, u.email as leader_email, p.title as project_title, g.group_code, s.avatar as leader_avatar
            FROM projects p
            JOIN ``groups`` g ON p.group_id = g.id
            JOIN students s ON g.created_by = s.user_id
            JOIN users u ON s.user_id = u.id
            WHERE p.supervisor_id = ? AND p.status = 'Approved'
        ");
        $stmt->execute([$supervisorId]);
        $leaders = $stmt->fetchAll();

        $this->render('supervisor/chat', [
            'leaders' => $leaders,
            'supervisorId' => $supervisorId
        ]);
    }

    public function dashboard() {


        $supervisorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Get count of assigned groups
        $stmt = $db->prepare("SELECT COUNT(*) FROM projects p JOIN ``groups`` g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND b.is_active = 1");
        $stmt->execute([$supervisorId]);
        $groupCount = $stmt->fetchColumn();

        // Get count of pending proposals under review
        $stmt = $db->prepare("SELECT COUNT(*) FROM proposals pr
            JOIN projects p ON pr.group_id = p.group_id
            WHERE p.supervisor_id = ? AND pr.status = 'Submitted'");
        $stmt->execute([$supervisorId]);
        $pendingProposals = $stmt->fetchColumn();

        // Fetch assigned groups
        $stmt = $db->prepare("SELECT g.*, p.title as project_title, p.status as project_status FROM ``groups`` g JOIN projects p ON g.id = p.group_id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND b.is_active = 1 ORDER BY g.created_at DESC");
        $stmt->execute([$supervisorId]);
        $groups = $stmt->fetchAll();

        $this->render('supervisor/dashboard', [
            'groupCount' => $groupCount,
            'pendingProposals' => $pendingProposals,
            'groups' => $groups
        ]);
    }

    public function groups() {
        $supervisorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch all supervised groups with grades
        $stmt = $db->prepare("SELECT g.*, p.title as project_title, p.description as project_description, p.status as project_status
            FROM ``groups`` g
            JOIN projects p ON g.id = p.group_id
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE p.supervisor_id = ? AND b.is_active = 1 ORDER BY g.created_at DESC");
        $stmt->execute([$supervisorId]);
        $groups = $stmt->fetchAll();

        // Fetch members for each group
        foreach ($groups as &$group) {
            $stmt = $db->prepare("SELECT s.*, u.email FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                JOIN users u ON s.user_id = u.id 
                WHERE gm.group_id = ?");
            $stmt->execute([$group['id']]);
            $members = $stmt->fetchAll();
            
            $groupSupervisionMarks = null;
            $groupShowSupervision = 0;
            
            foreach ($members as &$m) {
                $stmtG = $db->prepare("SELECT supervision_marks, show_supervision_to_student FROM grades WHERE student_id = ?");
                $stmtG->execute([$m['user_id']]);
                $grade = $stmtG->fetch();
                if ($grade) {
                    $m['supervision_marks'] = $grade['supervision_marks'];
                    if ($groupSupervisionMarks === null && $grade['supervision_marks'] !== null) {
                        $groupSupervisionMarks = true;
                    }
                    if ($grade['show_supervision_to_student'] == 1) {
                        $groupShowSupervision = 1;
                    }
                } else {
                    $m['supervision_marks'] = null;
                }
            }
            
            $group['members'] = $members;
            $group['supervision_marks'] = $groupSupervisionMarks;
            $group['show_supervision_to_student'] = $groupShowSupervision;
        }

        $this->render('supervisor/groups', [
            'groups' => $groups
        ]);
    }

    public function gradeGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supervisorId = $_SESSION['user_id'];
            $groupId = $_POST['group_id'] ?? null;
            
            $marksArray = $_POST['marks'] ?? [];
            
            if ($groupId) {
                $db = \Database::getInstance()->getConnection();
                
                // Inherit supervisor's global visibility status from other groups, defaulting to 0
                $stmtVis = $db->prepare("SELECT show_supervision_to_student FROM grades g JOIN projects p ON g.group_id = p.group_id WHERE p.supervisor_id = ? ORDER BY g.calculated_at DESC LIMIT 1");
                $stmtVis->execute([$supervisorId]);
                $lastVis = $stmtVis->fetchColumn();
                $show_supervision_to_student = ($lastVis !== false) ? (int)$lastVis : 0;
                
                // Verify supervisor owns this group
                $stmt = $db->prepare("SELECT id FROM projects WHERE group_id = ? AND supervisor_id = ?");
                $stmt->execute([$groupId, $supervisorId]);
                if (!$stmt->fetch()) {
                    $this->flash('error', 'Unauthorized access.');
                    redirect('/supervisor/groups');
                }
                
                try {
                    $db->beginTransaction();
                    
                    // Update grades per student
                    foreach ($marksArray as $studentId => $markData) {
                        $supervisionMarks = isset($markData['supervision']) ? round((float)$markData['supervision']) : null;
                        
                        $stmtCheck = $db->prepare("SELECT student_id FROM grades WHERE student_id = ?");
                        $stmtCheck->execute([$studentId]);
                        
                        if ($stmtCheck->fetch()) {
                            $stmtUpdate = $db->prepare("UPDATE grades SET supervision_marks = ?, show_supervision_to_student = ? WHERE student_id = ?");
                            $stmtUpdate->execute([$supervisionMarks, $show_supervision_to_student, $studentId]);
                        } else {
                            $stmtInsert = $db->prepare("INSERT INTO grades (group_id, student_id, supervision_marks, show_supervision_to_student) VALUES (?, ?, ?, ?)");
                            $stmtInsert->execute([$groupId, $studentId, $supervisionMarks, $show_supervision_to_student]);
                        }
                    }
                    
                    // Recalculate overall grades per student
                    $stmtGrades = $db->prepare("SELECT * FROM grades WHERE group_id = ?");
                    $stmtGrades->execute([$groupId]);
                    $studentsGrades = $stmtGrades->fetchAll();
                    
                    foreach ($studentsGrades as $gData) {
                        $total = round(
                            (float)$gData['proposal_defense_marks'] + 
                            (float)$gData['progress_presentation_marks'] + 
                            (float)$gData['final_presentation_marks'] + 
                            (float)$gData['supervision_marks']
                        );
                        
                        $percentage = round(($total / 200.0) * 100.0);
                        
                        // Grade scale
                        $grade = 'F';
                        if ($percentage >= 85) $grade = 'A+';
                        else if ($percentage >= 80) $grade = 'A';
                        else if ($percentage >= 75) $grade = 'B+';
                        else if ($percentage >= 70) $grade = 'B';
                        else if ($percentage >= 65) $grade = 'C+';
                        else if ($percentage >= 60) $grade = 'C';
                        else if ($percentage >= 55) $grade = 'D+';
                        else if ($percentage >= 50) $grade = 'D';
                        
                        $status = ($percentage >= 50) ? 'Pass' : 'Fail';
                        
                        $stmtUpdateGrades = $db->prepare("UPDATE grades SET total_marks = ?, percentage = ?, grade = ?, status = ? WHERE student_id = ?");
                        $stmtUpdateGrades->execute([$total, $percentage, $grade, $status, $gData['student_id']]);
                    }
                    
                    // Update progress stage to Final Grading Completed only if it is currently at Final Presentation Completed
                    $stmtGroup = $db->prepare("SELECT progress_stage FROM ``groups`` WHERE id = ?");
                    $stmtGroup->execute([$groupId]);
                    $currentStage = $stmtGroup->fetchColumn();
                    if ($currentStage === 'Final Presentation Completed') {
                        $stmtStage = $db->prepare("UPDATE ``groups`` SET progress_stage = 'Final Grading Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    }
                    
                    $db->commit();
                    
                    // Notify students
                    $stmtM = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                    $stmtM->execute([$groupId]);
                    $members = $stmtM->fetchAll();
                    foreach ($members as $m) {
                        $this->addNotification($m['student_id'], 'Supervisor Marks Updated', 'Your supervisor has updated your manual evaluation marks.');
                    }
                    
                    $this->flash('success', 'Marks updated successfully!');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Error saving marks: ' . $e->getMessage());
                }
            }
        }
        redirect('/supervisor/groups');
    }

    public function reviews() {
        $supervisorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch proposals for assigned groups
        $stmt = $db->prepare("SELECT pr.*, g.group_code, g.created_by, p.title as project_title 
            FROM proposals pr
            JOIN ``groups`` g ON pr.group_id = g.id
            JOIN projects p ON g.id = p.group_id
            WHERE p.supervisor_id = ? ORDER BY pr.submitted_at DESC");
        $stmt->execute([$supervisorId]);
        $proposals = $stmt->fetchAll();

        // Fetch members for each proposal group
        foreach ($proposals as &$pr) {
            $stmt = $db->prepare("SELECT s.*, u.email FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                JOIN users u ON s.user_id = u.id 
                WHERE gm.group_id = ?");
            $stmt->execute([$pr['group_id']]);
            $pr['members'] = $stmt->fetchAll();
        }

        $this->render('supervisor/reviews', [
            'proposals' => $proposals
        ]);
    }

    public function proposalAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proposalId = $_POST['proposal_id'] ?? null;
            $status = $_POST['status'] ?? ''; // Approved, Rejected, Revision Requested
            $feedback = trim($_POST['feedback'] ?? '');

            if ($proposalId && $status) {
                $db = \Database::getInstance()->getConnection();

                // Get proposal details
                $stmt = $db->prepare("SELECT * FROM proposals WHERE id = ?");
                $stmt->execute([$proposalId]);
                $proposal = $stmt->fetch();

                if ($proposal) {
                    try {
                        $db->beginTransaction();

                        if ($status === 'Approved') {
                            $stmtSlots = $db->prepare("SELECT COUNT(*) FROM projects p JOIN ``groups`` g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND p.status = 'Approved' AND b.is_active = 1");
                            $stmtSlots->execute([$_SESSION['user_id']]);
                            $slotsUsed = (int)$stmtSlots->fetchColumn();
                            if ($slotsUsed >= 8) {
                                throw new \Exception("Approval failed: You have already reached the maximum limit of 8 approved projects.");
                            }
                        }

                        // Update proposal
                        $stmt = $db->prepare("UPDATE proposals SET status = ?, feedback = ? WHERE id = ?");
                        $stmt->execute([$status, $feedback, $proposalId]);

                        // Update project status
                        $stmt = $db->prepare("UPDATE projects SET status = ? WHERE group_id = ?");
                        $stmt->execute([$status, $proposal['group_id']]);

                        // Update group stage if approved
                        $stage = 'Proposal Submitted';
                        if ($status === 'Approved') {
                            $stage = 'Proposal Approved';
                            
                            // Check if group_code is already assigned
                            $stmtCodeCheck = $db->prepare("SELECT group_code, created_by FROM ``groups`` WHERE id = ?");
                            $stmtCodeCheck->execute([$proposal['group_id']]);
                            $groupData = $stmtCodeCheck->fetch();
                            
                            if (empty($groupData['group_code'])) {
                                // Fetch group leader info
                                $stmtLeader = $db->prepare("SELECT student_id, department, shift FROM students WHERE user_id = ?");
                                $stmtLeader->execute([$groupData['created_by']]);
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
                                
                                $stmtCount = $db->prepare("SELECT COUNT(*) FROM ``groups`` WHERE group_code LIKE ?");
                                $stmtCount->execute([$prefix . '%']);
                                $count = (int)$stmtCount->fetchColumn();
                                $nextNumber = $count + 1;
                                $groupCode = $prefix . $nextNumber;
                                
                                // Update group_code in DB
                                $stmtUpdateCode = $db->prepare("UPDATE ``groups`` SET group_code = ? WHERE id = ?");
                                $stmtUpdateCode->execute([$groupCode, $proposal['group_id']]);
                            }
                        }
                        
                        $stmt = $db->prepare("UPDATE ``groups`` SET progress_stage = ? WHERE id = ?");
                        $stmt->execute([$stage, $proposal['group_id']]);

                        $db->commit();

                        // Notify group members
                        $mStmt = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                        $mStmt->execute([$proposal['group_id']]);
                        $members = $mStmt->fetchAll();
                        
                        foreach ($members as $m) {
                            $this->addNotification($m['student_id'], 'Proposal Reviewed', "Your project proposal has been $status by your supervisor.");
                        }

                        $this->flash('success', "Proposal status updated to '$status'.");
                    } catch (\Exception $e) {
                        $db->rollBack();
                        $this->flash('error', 'Failed to update proposal: ' . $e->getMessage());
                    }
                }
            }
        }
        redirect('/supervisor/reviews');
    }



    public function profile() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch supervisor details
        $stmt = $db->prepare("SELECT s.name, s.designation, s.department, s.research_interest, u.email, u.cnic FROM supervisors s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
        $stmt->execute([$userId]);
        $supervisor = $stmt->fetch();
        if (!$supervisor) {
            die("Supervisor profile not found.");
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
            $hasCnicInDb = !empty($supervisor['cnic']);
            $cnicToSave = $supervisor['cnic'];

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
                    redirect('/supervisor/profile');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Database error: ' . $e->getMessage());
                }
            } else {
                $this->flash('error', implode(" ", $errors));
            }
        }

        $this->render('supervisor/profile', [
            'supervisor' => $supervisor,
            'profile' => $profile
        ]);
    }

    public function toggleVisibility() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supervisorId = $_SESSION['user_id'];
            $groupId = $_POST['group_id'] ?? null;
            $show = isset($_POST['show']) ? (int)$_POST['show'] : 0;

            if ($groupId) {
                $db = \Database::getInstance()->getConnection();
                
                // Verify supervisor owns this group
                $stmt = $db->prepare("SELECT id FROM projects WHERE group_id = ? AND supervisor_id = ?");
                $stmt->execute([$groupId, $supervisorId]);
                if (!$stmt->fetch()) {
                    $this->flash('error', 'Unauthorized access.');
                    redirect('/supervisor/groups');
                }

                $stmt = $db->prepare("UPDATE grades SET show_supervision_to_student = ? WHERE group_id = ?");
                $stmt->execute([$show, $groupId]);

                $this->flash('success', $show ? 'Supervision marks are now visible to students.' : 'Supervision marks are now hidden from students.');
            } else {
                $db = \Database::getInstance()->getConnection();
                
                // Global toggle for all groups supervised by this supervisor
                $stmt = $db->prepare("UPDATE grades SET show_supervision_to_student = ? WHERE group_id IN (SELECT group_id FROM projects WHERE supervisor_id = ?)");
                $stmt->execute([$show, $supervisorId]);

                $this->flash('success', $show ? 'All supervision marks are now visible to students.' : 'All supervision marks are now hidden from students.');
            }
        }
        redirect('/supervisor/groups');
    }
}
