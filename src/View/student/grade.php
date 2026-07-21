<!-- Student Grade View -->
<?php if (!$group): ?>
    <div class="alert border-0 rounded-3 d-flex align-items-center gap-3" style="background: rgba(245,158,11,0.08); color: #d97706;" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
        <span>You must first create or join a group to view your results.</span>
    </div>
<?php else: ?>

<?php
// Calculate visible marks dynamically
$visibleProposalDefenseMarks = 0.00;
$visibleProgressPresentationMarks = 0.00;
$visibleFinalPresentationMarks = 0.00;
$visibleSupervisionMarks = 0.00;

$hasHiddenMarks = false;

// Group evaluations by stage
$proposalDefenseEvals = [];
$progressEvals = [];
$finalEvals = [];

foreach ($evaluations as $e) {
    if ($e['stage'] === 'Proposal Defence Presentation') {
        $proposalDefenseEvals[] = $e;
    } elseif ($e['stage'] === 'FYP Progress Presentation') {
        $progressEvals[] = $e;
    } elseif ($e['stage'] === 'Final Presentation') {
        $finalEvals[] = $e;
    }
}



// 2. Proposal Defence
$visibleDefenseCount = 0; $visibleDefenseSum = 0;
foreach ($proposalDefenseEvals as $e) {
    if ($e['show_to_student'] == 1) { 
        $visibleDefenseCount++; 
        $details = json_decode($e['marks_details'], true);
        if (isset($details[$_SESSION['user_id']])) {
            $sd = $details[$_SESSION['user_id']];
            if (isset($sd['total'])) {
                $visibleDefenseSum += floatval($sd['total']);
            } else {
                $visibleDefenseSum += array_sum(array_map('floatval', array_values($sd)));
            }
        }
    }
    else $hasHiddenMarks = true;
}
$showProposalDefenseMarks = count($proposalDefenseEvals) > 0 && $visibleDefenseCount > 0 ? round($visibleDefenseSum / count($proposalDefenseEvals)) : 0.00;

// 3. FYP Progress
$visibleProgressCount = 0; $visibleProgressSum = 0;
foreach ($progressEvals as $e) {
    if ($e['show_to_student'] == 1) { 
        $visibleProgressCount++; 
        $details = json_decode($e['marks_details'], true);
        if (isset($details[$_SESSION['user_id']])) {
            $sd = $details[$_SESSION['user_id']];
            if (isset($sd['total'])) {
                $visibleProgressSum += floatval($sd['total']);
            } else {
                $visibleProgressSum += array_sum(array_map('floatval', array_values($sd)));
            }
        }
    }
    else $hasHiddenMarks = true;
}
$showProgressPresentationMarks = count($progressEvals) > 0 && $visibleProgressCount > 0 ? round($visibleProgressSum / count($progressEvals)) : 0.00;

// 4. Supervision Marks
if (isset($grade['supervision_marks']) && $grade['supervision_marks'] !== null) {
    if ($grade['show_supervision_to_student'] == 1) { $showSupervisionMarks = (float)$grade['supervision_marks']; }
    else { $showSupervisionMarks = 0.00; $hasHiddenMarks = true; }
} else { $showSupervisionMarks = 0.00; }

// 5. Final Presentation
$visibleFinalCount = 0; $visibleFinalSum = 0;
foreach ($finalEvals as $e) {
    if ($e['show_to_student'] == 1) { 
        $visibleFinalCount++; 
        $details = json_decode($e['marks_details'], true);
        if (isset($details[$_SESSION['user_id']])) {
            $sd = $details[$_SESSION['user_id']];
            if (isset($sd['presentation']) && isset($sd['thesis']) && isset($sd['demo'])) {
                $visibleFinalSum += floatval($sd['presentation']) + floatval($sd['thesis']) + floatval($sd['demo']);
            } else {
                $visibleFinalSum += array_sum(array_map('floatval', array_values($sd)));
            }
        }
    }
    else $hasHiddenMarks = true;
}
$showFinalPresentationMarks = count($finalEvals) > 0 && $visibleFinalCount > 0 ? round($visibleFinalSum / count($finalEvals)) : 0.00;

// Cumulative
$showTotalMarks = $showProposalDefenseMarks + $showProgressPresentationMarks + $showSupervisionMarks + $showFinalPresentationMarks;
$showPercentage = ($showTotalMarks / 200.0) * 100.0;

// Grade letter
$showGrade = 'F';
if ($showPercentage >= 85) $showGrade = 'A+';
elseif ($showPercentage >= 80) $showGrade = 'A';
elseif ($showPercentage >= 75) $showGrade = 'B+';
elseif ($showPercentage >= 70) $showGrade = 'B';
elseif ($showPercentage >= 65) $showGrade = 'C+';
elseif ($showPercentage >= 60) $showGrade = 'C';
elseif ($showPercentage >= 55) $showGrade = 'D+';
elseif ($showPercentage >= 50) $showGrade = 'D';

$showStatus = ($showPercentage >= 50) ? 'Pass' : 'Fail';

// Grade color
$gradeColor = '#3b82f6';
if (in_array($showGrade, ['A+','A'])) $gradeColor = '#059669';
elseif (in_array($showGrade, ['B+','B'])) $gradeColor = '#0891b2';
elseif (in_array($showGrade, ['C+','C'])) $gradeColor = '#d97706';
elseif ($showGrade === 'F') $gradeColor = '#dc2626';
?>

<!-- Page Header -->
<div class="page-header mb-4">
    <h4><i class="bi bi-award-fill text-primary me-2"></i>Final Grade</h4>
    <p class="mb-0">Your FYP evaluation breakdown and overall result.</p>
</div>

<div class="row g-4">
    <!-- Left: Grade Breakdown -->
    <div class="col-lg-8">
        <div class="card border-0 p-4 mb-4">
            <div class="section-title"><i class="bi bi-table"></i> Grade Breakdown</div>

            <?php if ($hasHiddenMarks): ?>
                <div class="alert border-0 rounded-3 d-flex align-items-center gap-2 mb-4 py-2 px-3" style="background: rgba(59,130,246,0.07); color: #2563eb; font-size: 0.82rem;" role="alert">
                    <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                    Some component marks are currently hidden by evaluators. Results only include published marks.
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table align-middle border-0 m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Evaluation Component</th>
                            <th>Max</th>
                            <th class="text-end">Scored</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rubrics = [
                            ['label' => 'Proposal Defence Presentation', 'desc' => 'Awarded by committee at defence', 'max' => 40, 'val' => $showProposalDefenseMarks],
                            ['label' => 'FYP Progress Presentation', 'desc' => 'Awarded by committee at mid-term', 'max' => 40, 'val' => $showProgressPresentationMarks],
                            ['label' => 'Supervision', 'desc' => 'Awarded by supervisor for guidance', 'max' => 45, 'val' => $showSupervisionMarks],
                            ['label' => 'Final Presentation', 'desc' => 'Demo (25) + Thesis (25) + Presentation (25)', 'max' => 75, 'val' => $showFinalPresentationMarks],
                        ];
                        foreach ($rubrics as $i => $r):
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size: 0.875rem;"><?php echo ($i+1) . '. ' . $r['label']; ?></div>
                                <small class="text-muted d-none d-md-block" style="font-size: 0.75rem;"><?php echo htmlspecialchars((string)($r['desc']), ENT_QUOTES, 'UTF-8'); ?></small>
                            </td>
                            <td style="font-size: 0.875rem; color: var(--text-secondary);"><?php echo htmlspecialchars((string)($r['max']), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-end">
                                <span class="font-monospace fw-bold" style="font-size: 0.9rem;"><?php echo number_format($r['val'], 0); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr style="background: var(--table-header-bg); border-top: 2px solid var(--border-color) !important;">
                            <td><strong style="font-size: 0.9rem;">Cumulative Total</strong></td>
                            <td><strong style="font-size: 0.9rem;">200</strong></td>
                            <td class="text-end">
                                <span class="font-monospace fw-bold" style="font-size: 1.15rem; color: <?php echo $gradeColor; ?>;"><?php echo number_format($showTotalMarks, 0); ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Committee Feedback -->
        <div class="card border-0 p-4">
            <div class="section-title"><i class="bi bi-chat-right-text-fill"></i> Committee Feedback &amp; Remarks</div>

            <?php
            $groupedEvals = [];
            if (!empty($evaluations)) {
                foreach ($evaluations as $eval) { 
                    // Only include evaluations that have actual remarks
                    if (!empty(trim($eval['remarks']))) {
                        $groupedEvals[$eval['stage']][] = $eval; 
                    }
                }
            }
            ?>
            <?php if (!empty($groupedEvals)): ?>
                <div class="accordion accordion-flush" id="evaluationsAccordion">
                    <?php
                    $accordionIndex = 0;
                    foreach ($groupedEvals as $stageName => $stageEvals):
                        $accordionIndex++;
                        $stageMaxMarks = 100;
                        if ($stageName === 'Proposal Defence Presentation') $stageMaxMarks = 30;
                        elseif ($stageName === 'FYP Progress Presentation') $stageMaxMarks = 40;
                        elseif ($stageName === 'Final Presentation') $stageMaxMarks = 75;
                    ?>
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="heading<?php echo $accordionIndex; ?>">
                                <button class="accordion-button collapsed px-0 fw-semibold d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $accordionIndex; ?>" aria-expanded="false" aria-controls="collapse<?php echo $accordionIndex; ?>">
                                    <i class="bi bi-calendar2-check-fill text-primary" style="font-size: 0.9rem;"></i>
                                    <?php echo htmlspecialchars($stageName); ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $accordionIndex; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $accordionIndex; ?>" data-bs-parent="#evaluationsAccordion">
                                <div class="accordion-body px-0 pt-3 pb-0">
                                    <div class="row g-3">
                                        <?php foreach ($stageEvals as $se): ?>
                                            <div class="col-12">
                                                <div class="p-3 rounded-3" style="background: var(--form-bg); border-left: 3px solid #3b82f6;">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div class="fw-semibold d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                                                            <i class="bi bi-person-fill text-muted"></i>
                                                            <?php echo htmlspecialchars($se['evaluator_name']); ?>
                                                        </div>
                                                    </div>
                                                    <div style="font-size: 0.82rem; color: var(--text-secondary);">
                                                        <strong style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em;">Remarks:</strong>
                                                        <div class="mt-1 p-2 rounded-2" style="background: var(--card-bg); border: 1px solid var(--border-color); line-height: 1.6;">
                                                            <?php if ($se['show_to_student'] == 1): ?>
                                                                <?php echo nl2br(htmlspecialchars(trim($se['remarks']))); ?>
                                                            <?php else: ?>
                                                                <em style="color: var(--text-secondary);">Feedback is hidden by the evaluator.</em>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-chat-left-dots-fill text-muted" style="font-size: 2.5rem; opacity: 0.25;"></i>
                    <p class="text-muted mt-3 mb-0" style="font-size: 0.875rem;">No committee evaluations or remarks recorded yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Result Card -->
    <div class="col-lg-4">
        <div class="card border-0 text-center p-4 mb-4 overflow-hidden" style="position: relative;">
            <div style="position: absolute; top: -40px; right: -40px; width: 160px; height: 160px; background: <?php echo $gradeColor; ?>; opacity: 0.05; border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: <?php echo $gradeColor; ?>; opacity: 0.05; border-radius: 50%;"></div>

            <div class="section-title justify-content-center" style="border: none; padding: 0; margin-bottom: 20px;"><i class="bi bi-trophy-fill"></i> Overall Result</div>

            <!-- Grade Circle -->
            <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle" style="width: 90px; height: 90px; background: <?php echo $gradeColor; ?>; font-size: 2.2rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; box-shadow: 0 8px 20px <?php echo $gradeColor; ?>55;">
                <?php echo htmlspecialchars($showGrade); ?>
            </div>

            <h2 class="fw-bold mb-1" style="font-size: 2rem; letter-spacing: -0.03em;"><?php echo number_format($showPercentage, 1); ?>%</h2>
            <p class="text-muted mb-4" style="font-size: 0.82rem;">Aggregate Percentage Score</p>

            <div class="border-top pt-3 row g-2">
                <div class="col-6">
                    <div class="p-3 rounded-3" style="background: var(--table-header-bg);">
                        <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">Status</small>
                        <span class="fw-bold" style="font-size: 1.1rem; color: <?php echo $showStatus === 'Pass' ? '#059669' : '#dc2626'; ?>;"><?php echo $showStatus; ?></span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded-3" style="background: var(--table-header-bg);">
                        <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">Scale</small>
                        <span class="fw-bold" style="font-size: 1.1rem;">A+ to F</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Scale -->
        <div class="card border-0 p-4">
            <div class="section-title"><i class="bi bi-bar-chart-fill"></i> Grade Scale</div>
            <?php
            $scale = [
                ['A+', '#059669', '85 – 100%', '170 – 200'],
                ['A',  '#0891b2', '80 – 84%',  '160 – 169'],
                ['B+', '#0891b2', '75 – 79%',  '150 – 159'],
                ['B',  '#3b82f6', '70 – 74%',  '140 – 149'],
                ['C+', '#6366f1', '65 – 69%',  '130 – 139'],
                ['C',  '#8b5cf6', '60 – 64%',  '120 – 129'],
                ['D+', '#d97706', '55 – 59%',  '110 – 119'],
                ['D',  '#f59e0b', '50 – 54%',  '100 – 109'],
                ['F',  '#dc2626', 'Below 50%', 'Below 100'],
            ];
            foreach ($scale as $row):
                $isCurrentGrade = $row[0] === $showGrade;
            ?>
            <div class="d-flex align-items-center justify-content-between py-1" style="font-size: 0.78rem; <?php echo $isCurrentGrade ? 'background: ' . $row[1] . '15; border-radius: 6px; padding: 6px 8px;' : ''; ?>">
                <span class="fw-bold" style="color: <?php echo htmlspecialchars((string)($row[1]), ENT_QUOTES, 'UTF-8'); ?>; min-width: 28px;"><?php echo htmlspecialchars((string)($row[0]), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="text-muted"><?php echo htmlspecialchars((string)($row[2]), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="font-monospace text-muted" style="font-size: 0.72rem;"><?php echo htmlspecialchars((string)($row[3]), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($isCurrentGrade): ?><i class="bi bi-arrow-left text-muted" style="font-size: 0.7rem;"></i><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
