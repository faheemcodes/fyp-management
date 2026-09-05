<?php
namespace Controller;

class CommitteeController extends BaseController {

    public function dashboard() {
        $evaluatorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch committee details & assigned committee number
        $stmt = $db->prepare("SELECT name, department, committee_number FROM committees WHERE user_id = ?");
        $stmt->execute([$evaluatorId]);
        $committee = $stmt->fetch();
        $department = $committee['department'] ?? $_SESSION['department'] ?? 'Software Engineering';
        $myCommNum = (int)($committee['committee_number'] ?? 1);

        // Count assigned evaluations for this committee & department
        $stmtCount = $db->prepare("
            SELECT COUNT(*) 
            FROM `groups` g 
            JOIN projects p ON g.id = p.group_id 
            JOIN students s ON g.created_by = s.user_id
            JOIN academic_batches b ON g.batch_id = b.id 
            WHERE p.status = 'Approved' AND b.is_active = 1 AND s.department = ? AND (g.committee_number = ? OR g.committee_number IS NULL)
        ");
        $stmtCount->execute([$department, $myCommNum]);
        $totalGroups = $stmtCount->fetchColumn();

        // Graded evaluations count
        $stmtGraded = $db->prepare("SELECT COUNT(*) FROM evaluations e JOIN `groups` g ON e.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE e.evaluator_id = ? AND b.is_active = 1 AND total_marks > 0");
        $stmtGraded->execute([$evaluatorId]);
        $gradedCount = $stmtGraded->fetchColumn();

        // Pending evaluations count (3 stages per group)
        $pendingCount = max(0, ($totalGroups * 3) - $gradedCount);

        // Fetch groups list assigned to this committee
        $stmtGroups = $db->prepare("
            SELECT g.*, p.title as project_title, p.thesis_file, p.status as project_status, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE p.status = 'Approved' AND b.is_active = 1 AND s.department = ? AND (g.committee_number = ? OR g.committee_number IS NULL)
            ORDER BY g.created_at DESC
        ");
        $stmtGroups->execute([$department, $myCommNum]);
        $groups = $stmtGroups->fetchAll();

        // Get system notices
        $stmtNotices = $db->prepare("SELECT * FROM notices WHERE is_hidden = 0 AND (target_audience = 'All' OR FIND_IN_SET('committee', target_audience) > 0) AND (department = ? OR department IS NULL OR department = '') ORDER BY created_at DESC LIMIT 5");
        $stmtNotices->execute([$department]);
        $recentNotices = $stmtNotices->fetchAll();

        $this->render('committee/dashboard', [
            'totalGroups' => $totalGroups,
            'pendingCount' => $pendingCount,
            'gradedCount' => $gradedCount,
            'groups' => $groups,
            'committee' => $committee,
            'recentNotices' => $recentNotices
        ]);
    }

    public function evaluations() {
        $evaluatorId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch committee details & assigned committee number
        $stmt = $db->prepare("SELECT name, department, committee_number FROM committees WHERE user_id = ?");
        $stmt->execute([$evaluatorId]);
        $committee = $stmt->fetch();
        $department = $committee['department'] ?? 'Software Engineering';
        $myCommNum = (int)($committee['committee_number'] ?? 1);

        // Fetch groups along with scheduled and graded evaluation records for this committee member, including abstract
        $stmtGroups = $db->prepare("
            SELECT g.*, p.title as project_title, p.thesis_file, sup.name as supervisor_name, prop.abstract as proposal_abstract
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN proposals prop ON g.id = prop.group_id
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE p.status = 'Approved' AND b.is_active = 1 AND s.department = ? AND (g.committee_number = ? OR g.committee_number IS NULL)
            ORDER BY g.group_code ASC, g.created_at DESC
        ");
        $stmtGroups->execute([$department, $myCommNum]);
        $groups = $stmtGroups->fetchAll();

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
            'groups' => $groups,
            'committee' => $committee
        ]);
    }

    public function printSheet() {
        $evaluatorId = $_SESSION['user_id'];
        $stage = $_GET['stage'] ?? '';
        
        if (!in_array($stage, ['Proposal Defence Presentation', 'FYP Progress Presentation', 'Final Presentation'])) {
            die("Invalid stage.");
        }

        $db = \Database::getInstance()->getConnection();

        // Fetch committee details
        $stmtC = $db->prepare("SELECT c.name, c.department, c.committee_number FROM committees c WHERE c.user_id = ?");
        $stmtC->execute([$evaluatorId]);
        $committee = $stmtC->fetch();
        $department = $committee['department'] ?? 'Software Engineering';
        $myCommNum = (int)($committee['committee_number'] ?? 1);

        // Fetch groups assigned to this committee member
        $stmtGroups = $db->prepare("
            SELECT g.id as group_id, g.group_code, p.title as project_title, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE p.status = 'Approved' AND b.is_active = 1 AND s.department = ? AND (g.committee_number = ? OR g.committee_number IS NULL)
            ORDER BY g.group_code ASC
        ");
        $stmtGroups->execute([$department, $myCommNum]);
        $groups = $stmtGroups->fetchAll();

        $grouped = [];

        foreach ($groups as $group) {
            $groupId = $group['group_id'];
            
            // Fetch group members
            $stmtM = $db->prepare("SELECT s.name as student_name, s.student_id as roll_no FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ? ORDER BY s.student_id ASC");
            $stmtM->execute([$groupId]);
            $members = $stmtM->fetchAll();

            if (empty($members)) continue;

            $groupData = [];
            
            // Fetch previous comments if it's FYP Progress Presentation
            $previousComments = [];
            if ($stage === 'FYP Progress Presentation') {
                $stmtComments = $db->prepare("SELECT e.remarks FROM evaluations e WHERE e.group_id = ? AND e.stage = 'Proposal Defence Presentation' AND e.remarks IS NOT NULL AND e.remarks != ''");
                $stmtComments->execute([$groupId]);
                $comments = $stmtComments->fetchAll();
                foreach ($comments as $c) {
                    $previousComments[] = $c['remarks'];
                }
            }

            foreach ($members as $m) {
                $groupData[] = [
                    'group_id' => $groupId,
                    'group_code' => $group['group_code'],
                    'project_title' => $group['project_title'],
                    'supervisor_name' => $group['supervisor_name'],
                    'roll_no' => $m['roll_no'],
                    'student_name' => $m['student_name'],
                    'previous_comments' => implode(" ", $previousComments)
                ];
            }
            
            $grouped[$groupId] = $groupData;
        }

        $this->render('committee/print_sheet', [
            'grouped' => $grouped,
            'stage' => $stage,
            'committee' => $committee
        ]);
    }

    public function gradingSheet() {
        $evaluatorId = $_SESSION['user_id'];
        $stage = $_GET['stage'] ?? '';
        $view = $_GET['view'] ?? 'detailed';
        
        if (!in_array($stage, ['Proposal Defence Presentation', 'FYP Progress Presentation', 'Final Presentation'])) {
            die("Invalid stage.");
        }

        $db = \Database::getInstance()->getConnection();

        // Fetch committee details
        $stmtC = $db->prepare("SELECT c.name, c.department, c.committee_number FROM committees c WHERE c.user_id = ?");
        $stmtC->execute([$evaluatorId]);
        $committee = $stmtC->fetch();
        $department = $committee['department'] ?? 'Software Engineering';
        $myCommNum = (int)($committee['committee_number'] ?? 1);

        $stmtGroups = $db->prepare("
            SELECT g.id as group_id, g.group_code, p.title as project_title, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE p.status = 'Approved' AND b.is_active = 1 AND s.department = ? AND (g.committee_number = ? OR g.committee_number IS NULL)
            ORDER BY g.group_code ASC
        ");
        $stmtGroups->execute([$department, $myCommNum]);
        $groups = $stmtGroups->fetchAll();

        $grouped = [];

        foreach ($groups as $group) {
            $groupId = $group['group_id'];
            
            $stmtM = $db->prepare("SELECT s.name as student_name, s.student_id as roll_no, s.user_id as student_id FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ? ORDER BY s.student_id ASC");
            $stmtM->execute([$groupId]);
            $members = $stmtM->fetchAll();

            if (empty($members)) continue;

            $stmtEval = $db->prepare("SELECT id, remarks, marks_details FROM evaluations WHERE group_id = ? AND evaluator_id = ? AND stage = ?");
            $stmtEval->execute([$groupId, $evaluatorId, $stage]);
            $eval = $stmtEval->fetch();
            $marksDetails = $eval ? json_decode($eval['marks_details'], true) : [];
            $groupRemarks = $eval ? $eval['remarks'] : '';

            $groupData = [];
            
            $previousComments = [];
            if ($stage === 'FYP Progress Presentation') {
                $stmtComments = $db->prepare("SELECT e.remarks FROM evaluations e WHERE e.group_id = ? AND e.stage = 'Proposal Defence Presentation' AND e.remarks IS NOT NULL AND e.remarks != ''");
                $stmtComments->execute([$groupId]);
                $comments = $stmtComments->fetchAll();
                foreach ($comments as $c) {
                    $previousComments[] = $c['remarks'];
                }
            }

            foreach ($members as $m) {
                $studentMarks = isset($marksDetails[$m['student_id']]) ? $marksDetails[$m['student_id']] : [];
                $groupData[] = [
                    'group_id' => $groupId,
                    'group_code' => $group['group_code'],
                    'project_title' => $group['project_title'],
                    'supervisor_name' => $group['supervisor_name'],
                    'student_id' => $m['student_id'],
                    'roll_no' => $m['roll_no'],
                    'student_name' => $m['student_name'],
                    'previous_comments' => implode(" ", $previousComments),
                    'marks' => $studentMarks,
                    'group_remarks' => $groupRemarks
                ];
            }
            
            $grouped[$groupId] = $groupData;
        }

        $this->render('committee/grading_sheet', [
            'grouped' => $grouped,
            'stage' => $stage,
            'view' => $view
        ]);
    }

    public function bulkGradeEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $stage = $_POST['stage'] ?? '';
            $evaluations = $_POST['evaluations'] ?? [];
            $evaluatorId = $_SESSION['user_id'];

            if ($stage && !empty($evaluations)) {
                $db = \Database::getInstance()->getConnection();
                

                try {
                    $db->beginTransaction();

                    foreach ($evaluations as $groupId => $groupEval) {
                        $marksArr = $groupEval['marks'] ?? [];
                        $remarks = trim($groupEval['remarks'] ?? '');
                        
                        $totalScore = 0.00;
                        $details = [];
                        
                        // We will map the 15 exact fields or 1 field to the existing structure.
                        // The user requested table fields that perfectly match the print sheets.
                        
                        if ($stage === 'Proposal Defence Presentation' || $stage === 'FYP Progress Presentation') {
                            foreach ($marksArr as $studentId => $studentMarks) {
                                $totalStr = isset($studentMarks['total']) && $studentMarks['total'] !== '' ? (float)$studentMarks['total'] : null;

                                if ($stage === 'FYP Progress Presentation') {
                                    // Split the 40 marks into 4 categories equally just to satisfy the old model,
                                    // or just update old logic to allow 'total' alone. Let's provide total and let old model see it if it checks.
                                    // Actually, if we just save 'understanding' = total, it's fine.
                                    // Or we can save exactly what they entered.
                                    // We will just store 'total' and sum it.
                                    $details[$studentId] = [
                                        'total' => $totalStr !== null ? $totalStr : '',
                                        'understanding' => $totalStr !== null ? $totalStr : '' // For backward compatibility if needed, but not strictly required if we sum.
                                    ];
                                } else {
                                    $details[$studentId] = [
                                        'total' => $totalStr !== null ? $totalStr : ''
                                    ];
                                }
                                $totalScore += (float)$totalStr; 
                            }
                            if (count($marksArr) > 0) $totalScore /= count($marksArr);
                        } else if ($stage === 'Final Presentation') {
                            $isMinimized = (isset($_POST['view']) && $_POST['view'] === 'minimized');

                            foreach ($marksArr as $studentId => $sm) {
                                $hasPresMerged = isset($sm['presentation']) && $sm['presentation'] !== '';
                                $hasThesisMerged = isset($sm['thesis']) && $sm['thesis'] !== '';
                                
                                $presSubSum = ((float)($sm['pres_contents']??0) + (float)($sm['pres_time']??0) + (float)($sm['pres_confidence']??0) + (float)($sm['pres_qa']??0) + (float)($sm['pres_language']??0));
                                $thesisSubSum = ((float)($sm['thesis_contents']??0) + (float)($sm['thesis_formatting']??0) + (float)($sm['thesis_referencing']??0) + (float)($sm['thesis_fig']??0) + (float)($sm['thesis_completeness']??0));

                                if ($hasPresMerged && (!$presSubSum || $isMinimized)) {
                                    $pres_total = (float)$sm['presentation'];
                                    $pres_share = round($pres_total / 5.0, 2);
                                    $pres_contents = $pres_time = $pres_confidence = $pres_qa = $pres_language = (string)$pres_share;
                                } else {
                                    $pres_total = $presSubSum;
                                    $pres_contents = $sm['pres_contents'] ?? '';
                                    $pres_time = $sm['pres_time'] ?? '';
                                    $pres_confidence = $sm['pres_confidence'] ?? '';
                                    $pres_qa = $sm['pres_qa'] ?? '';
                                    $pres_language = $sm['pres_language'] ?? '';
                                }

                                if ($hasThesisMerged && (!$thesisSubSum || $isMinimized)) {
                                    $thesis_total = (float)$sm['thesis'];
                                    $thesis_share = round($thesis_total / 5.0, 2);
                                    $thesis_contents = $thesis_formatting = $thesis_referencing = $thesis_fig = $thesis_completeness = (string)$thesis_share;
                                } else {
                                    $thesis_total = $thesisSubSum;
                                    $thesis_contents = $sm['thesis_contents'] ?? '';
                                    $thesis_formatting = $sm['thesis_formatting'] ?? '';
                                    $thesis_referencing = $sm['thesis_referencing'] ?? '';
                                    $thesis_fig = $sm['thesis_fig'] ?? '';
                                    $thesis_completeness = $sm['thesis_completeness'] ?? '';
                                }

                                $demo_val = isset($sm['demo_total']) && $sm['demo_total'] !== '' ? $sm['demo_total'] : ($sm['demo'] ?? '');
                                $demo_total = (float)$demo_val;

                                $details[$studentId] = [
                                    'pres_contents' => $pres_contents,
                                    'pres_time' => $pres_time,
                                    'pres_confidence' => $pres_confidence,
                                    'pres_qa' => $pres_qa,
                                    'pres_language' => $pres_language,
                                    'thesis_contents' => $thesis_contents,
                                    'thesis_formatting' => $thesis_formatting,
                                    'thesis_referencing' => $thesis_referencing,
                                    'thesis_fig' => $thesis_fig,
                                    'thesis_completeness' => $thesis_completeness,
                                    'demo_total' => (string)$demo_val,
                                    
                                    // Merged values for easy access and legacy compatibility
                                    'presentation' => $pres_total,
                                    'thesis' => $thesis_total,
                                    'demo' => $demo_total
                                ];
                                $totalScore += ($pres_total + $thesis_total + $demo_total);
                            }
                            if (count($marksArr) > 0) $totalScore /= count($marksArr);
                        }
                        
                        $stmt = $db->prepare("SELECT id FROM evaluations WHERE group_id = ? AND evaluator_id = ? AND stage = ?");
                        $stmt->execute([$groupId, $evaluatorId, $stage]);
                        $eval = $stmt->fetch();

                        $jsonDetails = json_encode($details);

                        if ($eval) {
                            $stmtUpdate = $db->prepare("UPDATE evaluations SET marks_details = ?, total_marks = ?, remarks = ? WHERE id = ?");
                            $stmtUpdate->execute([$jsonDetails, $totalScore, $remarks, $eval['id']]);
                        } else {
                            $stmtInsert = $db->prepare("INSERT INTO evaluations (group_id, evaluator_id, stage, marks_details, total_marks, remarks, show_to_student) VALUES (?, ?, ?, ?, ?, ?, 0)");
                            $stmtInsert->execute([$groupId, $evaluatorId, $stage, $jsonDetails, $totalScore, $remarks]);
                        }
                        
                        // Recalculate average marks for this stage PER STUDENT
                        $stmtM = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                        $stmtM->execute([$groupId]);
                        $members = $stmtM->fetchAll();

                        foreach ($members as $m) {
                            $sId = $m['student_id'];
                            
                            $stmtEvals = $db->prepare("SELECT marks_details FROM evaluations WHERE group_id = ? AND stage = ?");
                            $stmtEvals->execute([$groupId, $stage]);
                            $allEvals = $stmtEvals->fetchAll();

                            $studentTotal = 0;
                            $countEvals = 0;

                            foreach ($allEvals as $ev) {
                                $mDetails = json_decode($ev['marks_details'], true);
                                if (isset($mDetails[$sId])) {
                                    $countEvals++;
                                    // For new format, we need to specifically sum the correct fields
                                    if ($stage === 'Final Presentation') {
                                        $sm = $mDetails[$sId];
                                        $presSubSum = ((float)($sm['pres_contents']??0) + (float)($sm['pres_time']??0) + (float)($sm['pres_confidence']??0) + (float)($sm['pres_qa']??0) + (float)($sm['pres_language']??0));
                                        $thesisSubSum = ((float)($sm['thesis_contents']??0) + (float)($sm['thesis_formatting']??0) + (float)($sm['thesis_referencing']??0) + (float)($sm['thesis_fig']??0) + (float)($sm['thesis_completeness']??0));
                                        $demoVal = (float)($sm['demo_total'] ?? $sm['demo'] ?? 0);

                                        $presVal = (isset($sm['presentation']) && $sm['presentation'] !== '') ? (float)$sm['presentation'] : $presSubSum;
                                        $thesisVal = (isset($sm['thesis']) && $sm['thesis'] !== '') ? (float)$sm['thesis'] : $thesisSubSum;

                                        $evTotal = $presVal + $thesisVal + $demoVal;
                                        $studentTotal += $evTotal;
                                    } else {
                                        // Proposal or Progress
                                        $sm = $mDetails[$sId];
                                        if (isset($sm['total'])) {
                                            $evTotal = (float)$sm['total'];
                                        } else {
                                            // Legacy fallback
                                            $evTotal = array_sum(array_map('floatval', array_values($sm)));
                                        }
                                        $studentTotal += $evTotal;
                                    }
                                }
                            }

                            $averageScore = $countEvals > 0 ? round($studentTotal / $countEvals) : 0;

                            if ($stage === 'Proposal Defence Presentation') {
                                $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id, proposal_defense_marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE proposal_defense_marks = VALUES(proposal_defense_marks), group_id = VALUES(group_id)");
                                $stmtGrade->execute([$sId, $groupId, $averageScore]);
                            } else if ($stage === 'FYP Progress Presentation') {
                                $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id, progress_presentation_marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE progress_presentation_marks = VALUES(progress_presentation_marks), group_id = VALUES(group_id)");
                                $stmtGrade->execute([$sId, $groupId, $averageScore]);
                            } else if ($stage === 'Final Presentation') {
                                $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id, final_presentation_marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE final_presentation_marks = VALUES(final_presentation_marks), group_id = VALUES(group_id)");
                                $stmtGrade->execute([$sId, $groupId, $averageScore]);
                            }

                            // Recalculate overall grades per student
                            $stmtGrades = $db->prepare("SELECT * FROM grades WHERE student_id = ?");
                            $stmtGrades->execute([$sId]);
                            $gData = $stmtGrades->fetch();

                            if ($gData) {
                                $total = round(
                                     (float)$gData['proposal_defense_marks'] + 
                                     (float)$gData['progress_presentation_marks'] + 
                                     (float)$gData['final_presentation_marks'] + 
                                     (float)$gData['supervision_marks']
                                );
                                
                                $percentage = round(($total / 200.0) * 100.0);
                                
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

                        // Update group progress stage
                        if ($stage === 'Proposal Defence Presentation') {
                            $stmtStage = $db->prepare("UPDATE `groups` SET progress_stage = 'Proposal Defence Presentation Completed' WHERE id = ?");
                            $stmtStage->execute([$groupId]);
                        } else if ($stage === 'FYP Progress Presentation') {
                            $stmtStage = $db->prepare("UPDATE `groups` SET progress_stage = 'FYP Progress Presentation Completed' WHERE id = ?");
                            $stmtStage->execute([$groupId]);
                        } else if ($stage === 'Final Presentation') {
                            $stmtSupervision = $db->prepare("SELECT supervision_marks FROM grades WHERE group_id = ? LIMIT 1");
                            $stmtSupervision->execute([$groupId]);
                            $supervisionMarks = $stmtSupervision->fetchColumn();

                            $targetStage = ($supervisionMarks !== null) ? 'Final Grading Completed' : 'Final Presentation Completed';
                            $stmtStage = $db->prepare("UPDATE `groups` SET progress_stage = ? WHERE id = ?");
                            $stmtStage->execute([$targetStage, $groupId]);
                        }
                    }

                    $db->commit();
                    $this->flash('success', "Marks for all groups saved successfully.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Error saving evaluations. Please try again.');
                }
            }
        }
        $redirectUrl = '/committee/grading-sheet?stage=' . urlencode($_POST['stage'] ?? '');
        if (!empty($_POST['view']) && $_POST['view'] === 'minimized') {
            $redirectUrl .= '&view=minimized';
        }
        redirect($redirectUrl);
    }

    public function gradeEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $groupId = $_POST['group_id'] ?? null;
            $stage = $_POST['stage'] ?? '';
            $remarks = trim($_POST['remarks'] ?? '');
            $evaluatorId = $_SESSION['user_id'];

            if ($groupId && $stage) {
                $db = \Database::getInstance()->getConnection();
                $totalScore = 0.00;
                $details = [];


                if ($stage === 'Proposal Defence Presentation') {
                    $marksArr = $_POST['marks'] ?? [];
                    
                    foreach ($marksArr as $studentId => $studentMarks) {
                        $totalStr = isset($studentMarks['total']) && $studentMarks['total'] !== '' ? (float)$studentMarks['total'] : null;

                        $details[$studentId] = [
                            'total' => $totalStr !== null ? $totalStr : ''
                        ];
                        // Out of 40
                        $totalScore += (float)$totalStr; 
                    }
                    if (count($marksArr) > 0) $totalScore /= count($marksArr); // Store average in total_marks for group overview

                } else if ($stage === 'FYP Progress Presentation') {
                    $marksArr = $_POST['marks'] ?? [];
                    
                    foreach ($marksArr as $studentId => $studentMarks) {
                        $c1 = isset($studentMarks['understanding']) && $studentMarks['understanding'] !== '' ? (float)$studentMarks['understanding'] : null;
                        $c2 = isset($studentMarks['technical_knowledge']) && $studentMarks['technical_knowledge'] !== '' ? (float)$studentMarks['technical_knowledge'] : null;
                        $c3 = isset($studentMarks['implementation_progress']) && $studentMarks['implementation_progress'] !== '' ? (float)$studentMarks['implementation_progress'] : null;
                        $c4 = isset($studentMarks['presentation_qa']) && $studentMarks['presentation_qa'] !== '' ? (float)$studentMarks['presentation_qa'] : null;
                        
                        $details[$studentId] = [
                            'understanding' => $c1 !== null ? $c1 : '',
                            'technical_knowledge' => $c2 !== null ? $c2 : '',
                            'implementation_progress' => $c3 !== null ? $c3 : '',
                            'presentation_qa' => $c4 !== null ? $c4 : ''
                        ];
                        // Out of 40
                        $totalScore += ((float)$c1 + (float)$c2 + (float)$c3 + (float)$c4);
                    }
                    if (count($marksArr) > 0) $totalScore /= count($marksArr);

                } else if ($stage === 'Final Presentation') {
                    $marksArr = $_POST['marks'] ?? [];
                    
                    foreach ($marksArr as $studentId => $studentMarks) {
                        $c1 = isset($studentMarks['thesis']) && $studentMarks['thesis'] !== '' ? (float)$studentMarks['thesis'] : null;
                        $c2 = isset($studentMarks['demo']) && $studentMarks['demo'] !== '' ? (float)$studentMarks['demo'] : null;
                        $c3 = isset($studentMarks['presentation']) && $studentMarks['presentation'] !== '' ? (float)$studentMarks['presentation'] : null;

                        $details[$studentId] = [
                            'thesis' => $c1 !== null ? $c1 : '',
                            'demo' => $c2 !== null ? $c2 : '',
                            'presentation' => $c3 !== null ? $c3 : ''
                        ];
                        // Out of 75
                        $totalScore += ((float)$c1 + (float)$c2 + (float)$c3);
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
                        $stmtUpdate = $db->prepare("UPDATE evaluations SET marks_details = ?, total_marks = ?, remarks = ? WHERE id = ?");
                        $stmtUpdate->execute([$jsonDetails, $totalScore, $remarks, $eval['id']]);
                    } else {
                        $stmtInsert = $db->prepare("INSERT INTO evaluations (group_id, evaluator_id, stage, marks_details, total_marks, remarks, show_to_student) VALUES (?, ?, ?, ?, ?, ?, 0)");
                        $stmtInsert->execute([$groupId, $evaluatorId, $stage, $jsonDetails, $totalScore, $remarks]);
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
                                $evTotal = array_sum(array_map('floatval', array_values($mDetails[$sId])));
                                $studentTotal += $evTotal;
                            }
                        }

                        $averageScore = $countEvals > 0 ? round($studentTotal / $countEvals) : 0;

                        if ($stage === 'Proposal Defence Presentation') {
                            $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id, proposal_defense_marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE proposal_defense_marks = VALUES(proposal_defense_marks), group_id = VALUES(group_id)");
                            $stmtGrade->execute([$sId, $groupId, $averageScore]);
                        } else if ($stage === 'FYP Progress Presentation') {
                            $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id, progress_presentation_marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE progress_presentation_marks = VALUES(progress_presentation_marks), group_id = VALUES(group_id)");
                            $stmtGrade->execute([$sId, $groupId, $averageScore]);
                        } else if ($stage === 'Final Presentation') {
                            $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id, final_presentation_marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE final_presentation_marks = VALUES(final_presentation_marks), group_id = VALUES(group_id)");
                            $stmtGrade->execute([$sId, $groupId, $averageScore]);
                        }

                        // Recalculate overall grades per student
                        $stmtGrades = $db->prepare("SELECT * FROM grades WHERE student_id = ?");
                        $stmtGrades->execute([$sId]);
                        $gData = $stmtGrades->fetch();

                        if ($gData) {
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

                            $stmtUpdateGrade = $db->prepare("UPDATE grades SET total_marks = ?, percentage = ?, grade = ?, status = ? WHERE student_id = ?");
                            $stmtUpdateGrade->execute([$total, $percentage, $grade, $status, $sId]);
                        }
                    }

                    // Update group progress stage (done once per group)
                    if ($stage === 'Proposal Defence Presentation') {
                        $stmtStage = $db->prepare("UPDATE `groups` SET progress_stage = 'Proposal Defence Presentation Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    } else if ($stage === 'FYP Progress Presentation') {
                        $stmtStage = $db->prepare("UPDATE `groups` SET progress_stage = 'FYP Progress Presentation Completed' WHERE id = ?");
                        $stmtStage->execute([$groupId]);
                    } else if ($stage === 'Final Presentation') {
                        // Check if supervision marks are assigned for at least one student in the group
                        $stmtSupervision = $db->prepare("SELECT supervision_marks FROM grades WHERE group_id = ? LIMIT 1");
                        $stmtSupervision->execute([$groupId]);
                        $supervisionMarks = $stmtSupervision->fetchColumn();

                        $targetStage = ($supervisionMarks !== null) ? 'Final Grading Completed' : 'Final Presentation Completed';
                        $stmtStage = $db->prepare("UPDATE `groups` SET progress_stage = ? WHERE id = ?");
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
                    $this->flash('error', 'Error saving evaluation. Please try again.');
                }
            }
        }
        redirect('/committee/evaluations');
    }

    public function toggleCommitteeVisibility() {
        $this->flash('error', 'Only the Department Coordinator has authorization to publish or hide student marks.');
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
            $this->validateCsrf();
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
                    $this->flash('error', 'Database error. Please try again.');
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
