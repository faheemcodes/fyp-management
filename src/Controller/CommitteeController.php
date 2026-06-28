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
            
            // Fetch group members
            $stmtM = $db->prepare("SELECT s.name, s.student_id, u.id as user_id FROM group_members gm JOIN students s ON gm.student_id = s.user_id JOIN users u ON s.user_id = u.id WHERE gm.group_id = ?");
            $stmtM->execute([$group['id']]);
            $group['members'] = $stmtM->fetchAll();

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
                    $marksArr = $_POST['marks'] ?? [];
                    
                    foreach ($marksArr as $studentId => $studentMarks) {
                        $c1 = (float)($studentMarks['problem_solution'] ?? 0);
                        $c2 = (float)($studentMarks['literature_feasibility'] ?? 0);
                        $c3 = (float)($studentMarks['presentation_viva'] ?? 0);

                        $details[$studentId] = [
                            'problem_solution' => $c1,
                            'literature_feasibility' => $c2,
                            'presentation_viva' => $c3
                        ];
                        // Out of 30
                        $totalScore += ($c1 + $c2 + $c3); 
                    }
                    if (count($marksArr) > 0) $totalScore /= count($marksArr); // Store average in total_marks for group overview

                } else if ($stage === 'FYP Progress Presentation') {
                    $marksArr = $_POST['marks'] ?? [];
                    
                    foreach ($marksArr as $studentId => $studentMarks) {
                        $c1 = (float)($studentMarks['understanding'] ?? 0);
                        $c2 = (float)($studentMarks['technical_knowledge'] ?? 0);
                        $c3 = (float)($studentMarks['implementation_progress'] ?? 0);
                        $c4 = (float)($studentMarks['presentation_qa'] ?? 0);
                        
                        $details[$studentId] = [
                            'understanding' => $c1,
                            'technical_knowledge' => $c2,
                            'implementation_progress' => $c3,
                            'presentation_qa' => $c4
                        ];
                        // Out of 40
                        $totalScore += ($c1 + $c2 + $c3 + $c4);
                    }
                    if (count($marksArr) > 0) $totalScore /= count($marksArr);

                } else if ($stage === 'Final Presentation') {
                    $marksArr = $_POST['marks'] ?? [];
                    
                    foreach ($marksArr as $studentId => $studentMarks) {
                        $c3 = (float)($studentMarks['presentation'] ?? 0);

                        $details[$studentId] = [
                            'presentation' => $c3
                        ];
                        // Out of 25
                        $totalScore += $c3;
                    }
                    if (count($marksArr) > 0) $totalScore /= count($marksArr);
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

                    // Recalculate average marks for this stage PER STUDENT
                    $stmtM = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                    $stmtM->execute([$groupId]);
                    $members = $stmtM->fetchAll();

                    foreach ($members as $m) {
                        $sId = $m['student_id'];
                        
                        // Extract specific student marks from all evaluators for this stage
                        // MySQL JSON_EXTRACT to get the student's marks_details, then sum/avg them.
                        // Or we can fetch all evaluations and do it in PHP to be safe across databases.
                        $stmtEvals = $db->prepare("SELECT marks_details FROM evaluations WHERE group_id = ? AND stage = ?");
                        $stmtEvals->execute([$groupId, $stage]);
                        $allEvals = $stmtEvals->fetchAll();

                        $studentTotal = 0;
                        $countEvals = 0;

                        foreach ($allEvals as $ev) {
                            $mDetails = json_decode($ev['marks_details'], true);
                            if (isset($mDetails[$sId])) {
                                $countEvals++;
                                $evTotal = array_sum(array_values($mDetails[$sId]));
                                $studentTotal += $evTotal;
                            }
                        }

                        $averageScore = $countEvals > 0 ? round($studentTotal / $countEvals) : 0;

                        if ($stage === 'Proposal Defence Presentation') {
                            $stmtGrade = $db->prepare("UPDATE grades SET proposal_defense_marks = ? WHERE student_id = ?");
                            $stmtGrade->execute([$averageScore, $sId]);
                        } else if ($stage === 'FYP Progress Presentation') {
                            $stmtGrade = $db->prepare("UPDATE grades SET progress_presentation_marks = ? WHERE student_id = ?");
                            $stmtGrade->execute([$averageScore, $sId]);
                        } else if ($stage === 'Final Presentation') {
                            $stmtGrade = $db->prepare("UPDATE grades SET final_presentation_marks = ? WHERE student_id = ?");
                            $stmtGrade->execute([$averageScore, $sId]);
                        }

                        // Recalculate overall grades per student
                        $stmtGrades = $db->prepare("SELECT * FROM grades WHERE student_id = ?");
                        $stmtGrades->execute([$sId]);
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

                            $stmtUpdateGrade = $db->prepare("UPDATE grades SET total_marks = ?, percentage = ?, grade = ?, status = ? WHERE student_id = ?");
                            $stmtUpdateGrade->execute([$total, $percentage, $grade, $status, $sId]);
                        }
                    }

                    // Update group progress stage (done once per group)
                    if ($stage === 'Proposal Defence Presentation') {
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'Proposal Defence Presentation Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    } else if ($stage === 'FYP Progress Presentation') {
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'FYP Progress Presentation Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    } else if ($stage === 'Final Presentation') {
                        // Check if supervision marks are assigned for at least one student in the group
                        $stmtSupervision = $db->prepare("SELECT supervision_marks FROM grades WHERE group_id = ? LIMIT 1");
                        $stmtSupervision->execute([$groupId]);
                        $supervisionMarks = $stmtSupervision->fetchColumn();

                        $targetStage = ($supervisionMarks !== null) ? 'Final Grading Completed' : 'Final Presentation Completed';
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = ? WHERE id = ?");
                        $stmtStage->execute([$targetStage, $groupId]);
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
