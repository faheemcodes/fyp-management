<?php
namespace Controller;

class CommitteeController extends BaseController {

    public function dashboard() {
        $evaluatorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Count assigned evaluations (groups who have approved projects)
        $stmt = $db->query("SELECT COUNT(*) FROM groups g JOIN projects p ON g.id = p.group_id WHERE p.status = 'Approved'");
        $totalGroups = $stmt->fetchColumn();

        // Graded evaluations count
        $stmt = $db->prepare("SELECT COUNT(*) FROM evaluations WHERE evaluator_id = ? AND total_marks > 0");
        $stmt->execute([$evaluatorId]);
        $gradedCount = $stmt->fetchColumn();

        // Pending evaluations count (3 stages per group)
        $pendingCount = max(0, ($totalGroups * 3) - $gradedCount);

        // Fetch groups list
        $groups = $db->query("SELECT g.*, p.title as project_title, p.status as project_status, sup.name as supervisor_name
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE p.status = 'Approved'
            ORDER BY g.created_at DESC")->fetchAll();

        $this->render('committee/dashboard', [
            'totalGroups' => $totalGroups,
            'pendingCount' => $pendingCount,
            'gradedCount' => $gradedCount,
            'groups' => $groups
        ]);
    }

    public function evaluations() {
        $evaluatorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch groups along with scheduled and graded evaluation records for this committee member, including abstract
        $groups = $db->query("SELECT g.*, p.title as project_title, sup.name as supervisor_name, prop.abstract as proposal_abstract
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN proposals prop ON g.id = prop.group_id
            WHERE p.status = 'Approved'
            ORDER BY g.created_at DESC")->fetchAll();

        foreach ($groups as &$group) {
            $stmt = $db->prepare("SELECT * FROM evaluations WHERE group_id = ? AND evaluator_id = ?");
            $stmt->execute([$group['id'], $evaluatorId]);
            $evals = $stmt->fetchAll();
            
            $group['proposal_defense'] = null;
            $group['progress_eval'] = null;
            $group['final_presentation'] = null;

            foreach ($evals as $ev) {
                if ($ev['stage'] === 'Proposal Defence Presentation') {
                    $group['proposal_defense'] = $ev;
                } else if ($ev['stage'] === 'FYP Progress Presentation') {
                    $group['progress_eval'] = $ev;
                } else if ($ev['stage'] === 'Final Presentation') {
                    $group['final_presentation'] = $ev;
                }
            }

            // Fetch all comments/remarks from ALL evaluators for Proposal Defence Presentation
            $stmtComments = $db->prepare("SELECT e.remarks, c.name as evaluator_name 
                                          FROM evaluations e
                                          JOIN committees c ON e.evaluator_id = c.user_id
                                          WHERE e.group_id = ? AND e.stage = 'Proposal Defence Presentation' AND e.remarks IS NOT NULL AND e.remarks != ''");
            $stmtComments->execute([$group['id']]);
            $group['proposal_defence_comments'] = $stmtComments->fetchAll();
        }

        $this->render('committee/evaluations', [
            'groups' => $groups
        ]);
    }

    public function gradeEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupId = $_POST['group_id'] ?? null;
            $stage = $_POST['stage'] ?? '';
            $remarks = trim($_POST['remarks'] ?? '');
            $evaluatorId = $_SESSION['user_id'];

            if ($groupId && $stage) {
                $db = \Database::getInstance()->getConnection();
                $totalScore = 0.00;
                $details = [];

                // Inherit evaluator's global visibility status from other evaluations, defaulting to 0
                $stmtVis = $db->prepare("SELECT show_to_student FROM evaluations WHERE evaluator_id = ? ORDER BY id DESC LIMIT 1");
                $stmtVis->execute([$evaluatorId]);
                $lastVis = $stmtVis->fetchColumn();
                $show_to_student = ($lastVis !== false) ? (int)$lastVis : 0;

                if ($stage === 'Proposal Defence Presentation') {
                    $c1 = (float)($_POST['problem_solution'] ?? 0);
                    $c2 = (float)($_POST['literature_feasibility'] ?? 0);
                    $c3 = (float)($_POST['presentation_viva'] ?? 0);

                    $details = [
                        'problem_solution' => $c1,
                        'literature_feasibility' => $c2,
                        'presentation_viva' => $c3
                    ];
                    $totalScore = $c1 + $c2 + $c3; // Out of 30

                } else if ($stage === 'FYP Progress Presentation') {
                    $c1 = (float)($_POST['understanding'] ?? 0);
                    $c2 = (float)($_POST['technical_knowledge'] ?? 0);
                    $c3 = (float)($_POST['implementation_progress'] ?? 0);
                    $c4 = (float)($_POST['presentation_qa'] ?? 0);
                    
                    $details = [
                        'understanding' => $c1,
                        'technical_knowledge' => $c2,
                        'implementation_progress' => $c3,
                        'presentation_qa' => $c4
                    ];
                    $totalScore = $c1 + $c2 + $c3 + $c4; // Out of 40

                } else if ($stage === 'Final Presentation') {
                    $c1 = (float)($_POST['project_demo'] ?? 0);
                    $c2 = (float)($_POST['thesis'] ?? 0);
                    $c3 = (float)($_POST['presentation'] ?? 0);

                    $details = [
                        'project_demo' => $c1,
                        'thesis' => $c2,
                        'presentation' => $c3
                    ];
                    $totalScore = $c1 + $c2 + $c3; // Out of 75
                }

                try {
                    $db->beginTransaction();

                    // Upsert evaluation
                    $stmt = $db->prepare("SELECT id FROM evaluations WHERE group_id = ? AND evaluator_id = ? AND stage = ?");
                    $stmt->execute([$groupId, $evaluatorId, $stage]);
                    $eval = $stmt->fetch();

                    $jsonDetails = json_encode($details);

                    if ($eval) {
                        $stmtUpdate = $db->prepare("UPDATE evaluations SET marks_details = ?, total_marks = ?, remarks = ?, show_to_student = ? WHERE id = ?");
                        $stmtUpdate->execute([$jsonDetails, $totalScore, $remarks, $show_to_student, $eval['id']]);
                    } else {
                        $stmtInsert = $db->prepare("INSERT INTO evaluations (group_id, evaluator_id, stage, marks_details, total_marks, remarks, show_to_student) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmtInsert->execute([$groupId, $evaluatorId, $stage, $jsonDetails, $totalScore, $remarks, $show_to_student]);
                    }

                    // Recalculate average marks for this stage (or keep original behavior for Final Presentation)
                    if ($stage === 'Proposal Defence Presentation' || $stage === 'FYP Progress Presentation') {
                        $stmtAvg = $db->prepare("SELECT AVG(total_marks) FROM evaluations WHERE group_id = ? AND stage = ?");
                        $stmtAvg->execute([$groupId, $stage]);
                        $averageScore = round((float)$stmtAvg->fetchColumn());

                        if ($stage === 'Proposal Defence Presentation') {
                            $stmtGrade = $db->prepare("UPDATE grades SET proposal_defense_marks = ? WHERE group_id = ?");
                            $stmtGrade->execute([$averageScore, $groupId]);
                            
                            // Update group progress stage
                            $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'Proposal Defence Presentation Completed' WHERE id = ?");
                            $stmtStage->execute([$groupId]);
                        } else {
                            $stmtGrade = $db->prepare("UPDATE grades SET progress_presentation_marks = ? WHERE group_id = ?");
                            $stmtGrade->execute([$averageScore, $groupId]);
                            
                            // Update group progress stage
                            $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'FYP Progress Presentation Completed' WHERE id = ?");
                            $stmtStage->execute([$groupId]);
                        }
                    } else if ($stage === 'Final Presentation') {
                        $totalScore = round($totalScore);
                        $stmtGrade = $db->prepare("UPDATE grades SET final_presentation_marks = ? WHERE group_id = ?");
                        $stmtGrade->execute([$totalScore, $groupId]);

                        // Check if supervision marks are assigned (not NULL)
                        $stmtSupervision = $db->prepare("SELECT supervision_marks FROM grades WHERE group_id = ?");
                        $stmtSupervision->execute([$groupId]);
                        $supervisionMarks = $stmtSupervision->fetchColumn();

                        if ($supervisionMarks !== null) {
                            $targetStage = 'Final Grading Completed';
                        } else {
                            $targetStage = 'Final Presentation Completed';
                        }

                        // Update group progress stage
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = ? WHERE id = ?");
                        $stmtStage->execute([$targetStage, $groupId]);
                    }

                    // Recalculate overall grades
                    $stmtGrades = $db->prepare("SELECT * FROM grades WHERE group_id = ?");
                    $stmtGrades->execute([$groupId]);
                    $gData = $stmtGrades->fetch();

                    if ($gData) {
                        $total = round(
                             (float)$gData['proposal_marks'] + 
                             (float)$gData['proposal_defense_marks'] + 
                             (float)$gData['progress_presentation_marks'] + 
                             (float)$gData['final_presentation_marks'] + 
                             (float)$gData['supervision_marks']
                        );
                        
                        $percentage = round(($total / 200.0) * 100.0);
                        
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

                    $db->commit();

                    // Notify student group members
                    $stmtM = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                    $stmtM->execute([$groupId]);
                    $members = $stmtM->fetchAll();

                    foreach ($members as $m) {
                        $this->addNotification($m['student_id'], 'Marks Awarded', "Evaluation marks for $stage have been published.");
                    }

                    $this->flash('success', "Marks and evaluation details for $stage saved successfully.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Error saving evaluation: ' . $e->getMessage());
                }
            }
        }
        redirect('/committee/evaluations');
    }

    public function toggleCommitteeVisibility() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $evaluatorId = $_SESSION['user_id'];
            $groupId = $_POST['group_id'] ?? null;
            $stage = $_POST['stage'] ?? null;
            $show = isset($_POST['show']) ? (int)$_POST['show'] : 0;

            if ($groupId && $stage) {
                $db = \Database::getInstance()->getConnection();
                
                // Update visibility
                $stmt = $db->prepare("UPDATE evaluations SET show_to_student = ? WHERE group_id = ? AND evaluator_id = ? AND stage = ?");
                $stmt->execute([$show, $groupId, $evaluatorId, $stage]);

                $this->flash('success', $show ? "Marks for $stage are now visible to students." : "Marks for $stage are now hidden from students.");
            } else {
                $db = \Database::getInstance()->getConnection();
                
                // Global toggle for all evaluations by this committee member
                $stmt = $db->prepare("UPDATE evaluations SET show_to_student = ? WHERE evaluator_id = ?");
                $stmt->execute([$show, $evaluatorId]);

                $this->flash('success', $show ? "All presentation marks are now visible to students." : "All presentation marks are now hidden from students.");
            }
        }
        redirect('/committee/evaluations');
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch committee details
        $stmt = $db->prepare("SELECT c.name, c.department, u.email, u.cnic FROM committees c JOIN users u ON c.user_id = u.id WHERE c.user_id = ?");
        $stmt->execute([$userId]);
        $committee = $stmt->fetch();
        if (!$committee) {
            die("Committee Member profile not found.");
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
            $hasCnicInDb = !empty($committee['cnic']);
            $cnicToSave = $committee['cnic'];

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

                    // Check if profile exists
                    $stmtCheck = $db->prepare("SELECT user_id FROM profiles WHERE user_id = ?");
                    $stmtCheck->execute([$userId]);
                    $profileExists = $stmtCheck->fetch();

                    if ($profileExists) {
                        // Update profiles table
                        $stmt = $db->prepare("UPDATE profiles SET prefix = ?, mobile_code = ?, mobile_no = ?, home_address = ?, cnic = ?, surname = ? WHERE user_id = ?");
                        $stmt->execute([$prefix, $mobile_code, $mobile_no, $home_address, $cnicToSave, $surnameToSave, $userId]);
                    } else {
                        // Insert profiles table
                        $stmt = $db->prepare("INSERT INTO profiles (user_id, prefix, mobile_code, mobile_no, home_address, cnic, surname, dob, gender) VALUES (?, ?, ?, ?, ?, ?, ?, '1980-01-01', 'Male')");
                        $stmt->execute([$userId, $prefix, $mobile_code, $mobile_no, $home_address, $cnicToSave, $surnameToSave]);
                    }

                    // Update users table cnic if it was updated
                    if (!$hasCnicInDb) {
                        $stmt = $db->prepare("UPDATE users SET cnic = ? WHERE id = ?");
                        $stmt->execute([$cnicToSave, $userId]);
                    }

                    $db->commit();
                    $this->flash('success', 'Profile updated successfully.');
                    redirect('/committee/profile');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Database error: ' . $e->getMessage());
                }
            } else {
                $this->flash('error', implode(" ", $errors));
            }
        }

        $this->render('committee/profile', [
            'committee' => $committee,
            'profile' => $profile
        ]);
    }
}
