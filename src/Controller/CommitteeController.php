<?php
namespace Controller;

class CommitteeController extends BaseController {

    public function dashboard() {
        $evaluatorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Count assigned evaluations (groups who have projects)
        $stmt = $db->query("SELECT COUNT(*) FROM groups");
        $totalGroups = $stmt->fetchColumn();

        // Scheduled presentation sessions count
        $stmt = $db->prepare("SELECT COUNT(*) FROM evaluations WHERE evaluator_id = ? AND scheduled_date IS NOT NULL AND total_marks = 0");
        $stmt->execute([$evaluatorId]);
        $scheduledCount = $stmt->fetchColumn();

        // Graded evaluations count
        $stmt = $db->prepare("SELECT COUNT(*) FROM evaluations WHERE evaluator_id = ? AND total_marks > 0");
        $stmt->execute([$evaluatorId]);
        $gradedCount = $stmt->fetchColumn();

        // Fetch groups list
        $groups = $db->query("SELECT g.*, p.title as project_title, p.status as project_status, sup.name as supervisor_name
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            ORDER BY g.created_at DESC")->fetchAll();

        $this->render('committee/dashboard', [
            'totalGroups' => $totalGroups,
            'scheduledCount' => $scheduledCount,
            'gradedCount' => $gradedCount,
            'groups' => $groups
        ]);
    }

    public function evaluations() {
        $evaluatorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch groups along with scheduled and graded evaluation records for this committee member
        $groups = $db->query("SELECT g.*, p.title as project_title, sup.name as supervisor_name
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
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
        }

        $this->render('committee/evaluations', [
            'groups' => $groups
        ]);
    }

    public function scheduleEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupId = $_POST['group_id'] ?? null;
            $stage = $_POST['stage'] ?? ''; // Proposal Defence Presentation, FYP Progress Presentation, Final Presentation
            $date = $_POST['scheduled_date'] ?? '';
            $evaluatorId = $_SESSION['user_id'];

            if ($groupId && $stage && $date) {
                $db = \Database::getInstance()->getConnection();
                
                // Check if evaluation already exists
                $stmt = $db->prepare("SELECT id FROM evaluations WHERE group_id = ? AND evaluator_id = ? AND stage = ?");
                $stmt->execute([$groupId, $evaluatorId, $stage]);
                $eval = $stmt->fetch();

                if ($eval) {
                    $stmtUpdate = $db->prepare("UPDATE evaluations SET scheduled_date = ? WHERE id = ?");
                    $stmtUpdate->execute([$date, $eval['id']]);
                } else {
                    $stmtInsert = $db->prepare("INSERT INTO evaluations (group_id, evaluator_id, stage, scheduled_date, marks_details, total_marks) VALUES (?, ?, ?, ?, '{}', 0.00)");
                    $stmtInsert->execute([$groupId, $evaluatorId, $stage, $date]);
                }

                // Notify student group members
                $stmtM = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                $stmtM->execute([$groupId]);
                $members = $stmtM->fetchAll();

                foreach ($members as $m) {
                    $this->addNotification($m['student_id'], 'Evaluation Scheduled', "Your group's $stage has been scheduled for $date.");
                }

                $this->flash('success', "Evaluation for $stage scheduled successfully.");
            }
        }
        redirect('/committee/evaluations');
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
                        $stmtUpdate = $db->prepare("UPDATE evaluations SET marks_details = ?, total_marks = ?, remarks = ? WHERE id = ?");
                        $stmtUpdate->execute([$jsonDetails, $totalScore, $remarks, $eval['id']]);
                    } else {
                        $stmtInsert = $db->prepare("INSERT INTO evaluations (group_id, evaluator_id, stage, marks_details, total_marks, remarks) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmtInsert->execute([$groupId, $evaluatorId, $stage, $jsonDetails, $totalScore, $remarks]);
                    }

                    // Update grades table marks
                    if ($stage === 'Proposal Defence Presentation') {
                        $stmtGrade = $db->prepare("UPDATE grades SET proposal_defense_marks = ? WHERE group_id = ?");
                        $stmtGrade->execute([$totalScore, $groupId]);
                        
                        // Update group progress stage
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'Proposal Defence Presentation Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    } else if ($stage === 'FYP Progress Presentation') {
                        $stmtGrade = $db->prepare("UPDATE grades SET progress_presentation_marks = ? WHERE group_id = ?");
                        $stmtGrade->execute([$totalScore, $groupId]);
                        
                        // Update group progress stage
                        $stmtStage = $db->prepare("UPDATE groups SET progress_stage = 'FYP Progress Presentation Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    } else if ($stage === 'Final Presentation') {
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
}
