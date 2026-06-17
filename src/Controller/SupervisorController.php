<?php
namespace Controller;

class SupervisorController extends BaseController {

    public function dashboard() {
        $supervisorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Get count of assigned groups
        $stmt = $db->prepare("SELECT COUNT(*) FROM projects WHERE supervisor_id = ?");
        $stmt->execute([$supervisorId]);
        $groupCount = $stmt->fetchColumn();

        // Get count of pending proposals under review
        $stmt = $db->prepare("SELECT COUNT(*) FROM proposals pr
            JOIN projects p ON pr.group_id = p.group_id
            WHERE p.supervisor_id = ? AND pr.status = 'Submitted'");
        $stmt->execute([$supervisorId]);
        $pendingProposals = $stmt->fetchColumn();

        // Get count of documents needing feedback
        $stmt = $db->prepare("SELECT COUNT(*) FROM documents d
            JOIN projects p ON d.group_id = p.group_id
            WHERE p.supervisor_id = ? AND d.feedback IS NULL");
        $stmt->execute([$supervisorId]);
        $pendingDocs = $stmt->fetchColumn();

        // Fetch assigned groups
        $stmt = $db->prepare("SELECT g.*, p.title as project_title, p.status as project_status 
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            WHERE p.supervisor_id = ? ORDER BY g.created_at DESC");
        $stmt->execute([$supervisorId]);
        $groups = $stmt->fetchAll();

        $this->render('supervisor/dashboard', [
            'groupCount' => $groupCount,
            'pendingProposals' => $pendingProposals,
            'pendingDocs' => $pendingDocs,
            'groups' => $groups
        ]);
    }

    public function groups() {
        $supervisorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch all supervised groups with grades
        $stmt = $db->prepare("SELECT g.*, p.title as project_title, p.description as project_description, p.status as project_status,
            gr.supervision_marks, gr.total_marks
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            LEFT JOIN grades gr ON g.id = gr.group_id
            WHERE p.supervisor_id = ? ORDER BY g.created_at DESC");
        $stmt->execute([$supervisorId]);
        $groups = $stmt->fetchAll();

        // Fetch members for each group
        foreach ($groups as &$group) {
            $stmt = $db->prepare("SELECT s.*, u.email FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                JOIN users u ON s.user_id = u.id 
                WHERE gm.group_id = ?");
            $stmt->execute([$group['id']]);
            $group['members'] = $stmt->fetchAll();
        }

        $this->render('supervisor/groups', [
            'groups' => $groups
        ]);
    }

    public function gradeGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supervisorId = $_SESSION['user_id'];
            $groupId = $_POST['group_id'] ?? null;
            
            $supervision_marks = (float)($_POST['supervision_marks'] ?? 0);
            
            if ($groupId) {
                $db = \Database::getInstance()->getConnection();
                
                // Verify supervisor owns this group
                $stmt = $db->prepare("SELECT id FROM projects WHERE group_id = ? AND supervisor_id = ?");
                $stmt->execute([$groupId, $supervisorId]);
                if (!$stmt->fetch()) {
                    $this->flash('error', 'Unauthorized access.');
                    redirect('/supervisor/groups');
                }
                
                try {
                    $db->beginTransaction();
                    
                    // Update grades
                    $stmt = $db->prepare("UPDATE grades SET supervision_marks = ? WHERE group_id = ?");
                    $stmt->execute([$supervision_marks, $groupId]);
                    
                    // Recalculate overall grades
                    $stmtGrades = $db->prepare("SELECT * FROM grades WHERE group_id = ?");
                    $stmtGrades->execute([$groupId]);
                    $gData = $stmtGrades->fetch();
                    
                    if ($gData) {
                        $total = (float)$gData['proposal_marks'] + 
                                 (float)$gData['proposal_defense_marks'] + 
                                 (float)$gData['progress_presentation_marks'] + 
                                 (float)$gData['final_presentation_marks'] + 
                                 (float)$gData['supervision_marks'];
                        
                        $percentage = ($total / 200.0) * 100.0;
                        
                        // Grade scale (University of Sindh standard semester system)
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
                        
                        $stmtUpdateGrades = $db->prepare("UPDATE grades SET total_marks = ?, percentage = ?, grade = ?, status = ? WHERE group_id = ?");
                        $stmtUpdateGrades->execute([$total, $percentage, $grade, $status, $groupId]);
                    }
                    
                    // Update progress stage to Final Grading Completed only if it is currently at Final Presentation Completed
                    $stmtGroup = $db->prepare("SELECT progress_stage FROM groups WHERE id = ?");
                    $stmtGroup->execute([$groupId]);
                    $currentStage = $stmtGroup->fetchColumn();
                    if ($currentStage === 'Final Presentation Completed') {
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'Final Grading Completed' WHERE id = ?");
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
            JOIN groups g ON pr.group_id = g.id
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

        // Fetch documents for assigned groups
        $stmt = $db->prepare("SELECT d.*, g.group_code, p.title as project_title, s.name as student_name
            FROM documents d
            JOIN groups g ON d.group_id = g.id
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON d.uploaded_by = s.user_id
            WHERE p.supervisor_id = ? ORDER BY d.uploaded_at DESC");
        $stmt->execute([$supervisorId]);
        $documents = $stmt->fetchAll();

        $this->render('supervisor/reviews', [
            'proposals' => $proposals,
            'documents' => $documents
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
                            $stmtSlots = $db->prepare("SELECT COUNT(*) FROM projects WHERE supervisor_id = ? AND status = 'Approved'");
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
                        }
                        
                        $stmt = $db->prepare("UPDATE groups SET progress_stage = ? WHERE id = ?");
                        $stmt->execute([$stage, $proposal['group_id']]);

                        // Seed Grade table proposal marks if approved
                        if ($status === 'Approved') {
                            $stmt = $db->prepare("UPDATE grades SET proposal_marks = 10.00 WHERE group_id = ?");
                            $stmt->execute([$proposal['group_id']]);
                        }

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

    public function documentFeedback() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $docId = $_POST['doc_id'] ?? null;
            $feedback = trim($_POST['feedback'] ?? '');
            $supervisorId = $_SESSION['user_id'];

            if ($docId && !empty($feedback)) {
                $db = \Database::getInstance()->getConnection();

                $stmt = $db->prepare("UPDATE documents SET feedback = ?, feedback_by = ?, feedback_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$feedback, $supervisorId, $docId]);

                // Find group_id to notify students
                $stmtGroup = $db->prepare("SELECT group_id, doc_type, stage FROM documents WHERE id = ?");
                $stmtGroup->execute([$docId]);
                $doc = $stmtGroup->fetch();

                if ($doc) {
                    // Notify group members
                    $mStmt = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                    $mStmt->execute([$doc['group_id']]);
                    $members = $mStmt->fetchAll();
                    
                    foreach ($members as $m) {
                        $this->addNotification($m['student_id'], 'Document Feedback Received', "Your supervisor commented on your {$doc['doc_type']} under {$doc['stage']}.");
                    }
                }

                $this->flash('success', 'Document feedback saved successfully.');
            }
        }
        redirect('/supervisor/reviews');
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch supervisor details
        $stmt = $db->prepare("SELECT s.name, u.email FROM supervisors s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
        $stmt->execute([$userId]);
        $supervisor = $stmt->fetch();
        if (!$supervisor) {
            die("Supervisor profile not found.");
        }

        // Get existing profile info
        $stmt = $db->prepare("SELECT * FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        $isLocked = !empty($profile) && !empty($profile['home_address']) && $profile['home_address'] !== 'Not Provided Yet';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($isLocked) {
                $this->flash('error', 'Your profile is locked and cannot be edited after submission.');
                redirect('/supervisor/profile');
            }
            $errors = [];
            $prefix = trim($_POST['prefix'] ?? '');
            $father_name = !empty($_POST['father_name']) ? trim($_POST['father_name']) : null;
            $dob = trim($_POST['dob'] ?? '');
            $gender = trim($_POST['gender'] ?? '');
            $cnic_expiry = !empty($_POST['cnic_expiry']) ? $_POST['cnic_expiry'] : null;
            $blood_group = !empty($_POST['blood_group']) ? trim($_POST['blood_group']) : null;
            $place_of_birth = !empty($_POST['place_of_birth']) ? trim($_POST['place_of_birth']) : null;
            $country = !empty($_POST['country']) ? trim($_POST['country']) : null;
            $province_state = !empty($_POST['province_state']) ? trim($_POST['province_state']) : null;
            $district = !empty($_POST['district']) ? trim($_POST['district']) : null;
            $city = !empty($_POST['city']) ? trim($_POST['city']) : null;
            $zip_code = !empty($_POST['zip_code']) ? trim($_POST['zip_code']) : null;
            $home_address = trim($_POST['home_address'] ?? '');
            $permanent_address = !empty($_POST['permanent_address']) ? trim($_POST['permanent_address']) : null;

            if (empty($prefix)) $errors[] = "Prefix is required.";
            if (empty($dob)) $errors[] = "Date Of Birth is required.";
            if (empty($gender)) $errors[] = "Gender is required.";
            if (empty($home_address) || $home_address === 'Not Provided Yet') $errors[] = "Home Address is required.";

            if (empty($errors)) {
                try {
                    $stmt = $db->prepare("UPDATE profiles SET prefix = ?, father_name = ?, dob = ?, gender = ?, cnic_expiry = ?, blood_group = ?, place_of_birth = ?, country = ?, province_state = ?, district = ?, city = ?, zip_code = ?, home_address = ?, permanent_address = ? WHERE user_id = ?");
                    $stmt->execute([$prefix, $father_name, $dob, $gender, $cnic_expiry, $blood_group, $place_of_birth, $country, $province_state, $district, $city, $zip_code, $home_address, $permanent_address, $userId]);
                    
                    $this->flash('success', 'Profile updated successfully.');
                    redirect('/supervisor/profile');
                } catch (\Exception $e) {
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
}
