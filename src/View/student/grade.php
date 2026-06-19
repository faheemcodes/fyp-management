<!-- Student Grade View -->
<?php if (!$group): ?>
    <div class="alert alert-warning rounded-3 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> You must first create or join a group to view results.
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
    
    // 1. Proposal Marks: Always visible
    $showProposalMarks = (float)($grade['proposal_marks'] ?? 0.00);
    
    // 2. Proposal Defence:
    $visibleDefenseCount = 0;
    $visibleDefenseSum = 0;
    foreach ($proposalDefenseEvals as $e) {
        if ($e['show_to_student'] == 1) {
            $visibleDefenseCount++;
            $visibleDefenseSum += (float)$e['total_marks'];
        } else {
            $hasHiddenMarks = true;
        }
    }
    if (count($proposalDefenseEvals) > 0) {
        $showProposalDefenseMarks = ($visibleDefenseCount > 0) ? ($visibleDefenseSum / count($proposalDefenseEvals)) : 0.00;
    } else {
        $showProposalDefenseMarks = 0.00;
    }
    
    // 3. FYP Progress:
    $visibleProgressCount = 0;
    $visibleProgressSum = 0;
    foreach ($progressEvals as $e) {
        if ($e['show_to_student'] == 1) {
            $visibleProgressCount++;
            $visibleProgressSum += (float)$e['total_marks'];
        } else {
            $hasHiddenMarks = true;
        }
    }
    if (count($progressEvals) > 0) {
        $showProgressPresentationMarks = ($visibleProgressCount > 0) ? ($visibleProgressSum / count($progressEvals)) : 0.00;
    } else {
        $showProgressPresentationMarks = 0.00;
    }
    
    // 4. Supervision Marks:
    if (isset($grade['supervision_marks']) && $grade['supervision_marks'] !== null) {
        if ($grade['show_supervision_to_student'] == 1) {
            $showSupervisionMarks = (float)$grade['supervision_marks'];
        } else {
            $showSupervisionMarks = 0.00;
            $hasHiddenMarks = true;
        }
    } else {
        $showSupervisionMarks = 0.00;
    }
    
    // 5. Final Presentation:
    $visibleFinalCount = 0;
    $visibleFinalSum = 0;
    foreach ($finalEvals as $e) {
        if ($e['show_to_student'] == 1) {
            $visibleFinalCount++;
            $visibleFinalSum += (float)$e['total_marks'];
        } else {
            $hasHiddenMarks = true;
        }
    }
    if (count($finalEvals) > 0) {
        $showFinalPresentationMarks = ($visibleFinalCount > 0) ? ($visibleFinalSum / count($finalEvals)) : 0.00;
    } else {
        $showFinalPresentationMarks = 0.00;
    }
    
    // Cumulative visible calculations
    $showTotalMarks = $showProposalMarks + $showProposalDefenseMarks + $showProgressPresentationMarks + $showSupervisionMarks + $showFinalPresentationMarks;
    $showPercentage = ($showTotalMarks / 200.0) * 100.0;
    
    // Calculate grade and status based on visible marks
    $showGrade = 'F';
    if ($showPercentage >= 85) $showGrade = 'A+';
    else if ($showPercentage >= 80) $showGrade = 'A';
    else if ($showPercentage >= 75) $showGrade = 'B+';
    else if ($showPercentage >= 70) $showGrade = 'B';
    else if ($showPercentage >= 65) $showGrade = 'C+';
    else if ($showPercentage >= 60) $showGrade = 'C';
    else if ($showPercentage >= 55) $showGrade = 'D+';
    else if ($showPercentage >= 50) $showGrade = 'D';
    
    $showStatus = ($showPercentage >= 50) ? 'Pass' : 'Fail';
    ?>
    
    <div class="row g-4">
        <!-- Grading Weightage Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4">Final Grade Details & Breakdown</h5>
                
                <?php if ($hasHiddenMarks): ?>
                    <div class="alert alert-info rounded-3 py-2 px-3 small mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-1"></i> Some component marks are currently hidden by the evaluators/supervisor. Overall results only include published marks.
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table align-middle border-0 m-0" style="box-shadow: none;">
                        <thead>
                            <tr>
                                <th>Evaluation Rubrics</th>
                                <th>Max Marks</th>
                                <th class="text-end">Marks Scored</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">1. Proposal Submission</div>
                                    <small class="text-muted d-none d-md-block">Awarded automatically upon supervisor proposal approval</small>
                                </td>
                                <td>10 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($showProposalMarks, 0); ?> / 10</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">2. Proposal Defence Presentation</div>
                                    <small class="text-muted d-none d-md-block">Awarded manually by committee on presentation defence</small>
                                </td>
                                <td>30 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($showProposalDefenseMarks, 0); ?> / 30</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">3. FYP Progress Presentation</div>
                                    <small class="text-muted d-none d-md-block">Awarded manually by committee on mid-term progress presentation</small>
                                </td>
                                <td>40 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($showProgressPresentationMarks, 0); ?> / 40</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">4. Supervision</div>
                                    <small class="text-muted d-none d-md-block">Awarded by supervisor for guidance and performance</small>
                                </td>
                                <td>45 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($showSupervisionMarks, 0); ?> / 45</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">5. Final Presentation</div>
                                    <small class="text-muted d-none d-md-block">Awarded manually by committee on Project Demo (25), Thesis (25), and Presentation (25)</small>
                                </td>
                                <td>75 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($showFinalPresentationMarks, 0); ?> / 75</td>
                            </tr>
                            <tr class="table-light border-top">
                                <td><strong class="text-dark">Cumulative Total Score</strong></td>
                                <td><strong>200 Marks</strong></td>
                                <td class="text-end font-monospace fw-bold text-primary fs-4"><?php echo number_format($showTotalMarks, 0); ?> / 200</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Committee Evaluations & Comments -->
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mt-4">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-chat-right-text-fill text-primary me-2"></i>Committee Presentation Feedback & Comments</h5>
                
                <?php if (!empty($evaluations)): ?>
                    <?php
                    // Group evaluations by stage
                    $groupedEvals = [];
                    foreach ($evaluations as $eval) {
                        $groupedEvals[$eval['stage']][] = $eval;
                    }
                    ?>
                    
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
                            <div class="accordion-item border-bottom last-no-border">
                                <h2 class="accordion-header" id="heading<?php echo $accordionIndex; ?>">
                                    <button class="accordion-button collapsed px-0 fw-semibold text-dark d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $accordionIndex; ?>" aria-expanded="false" aria-controls="collapse<?php echo $accordionIndex; ?>">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bi bi-calendar2-check-fill text-primary small"></i>
                                            <?php echo htmlspecialchars($stageName); ?>
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $accordionIndex; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $accordionIndex; ?>" data-bs-parent="#evaluationsAccordion">
                                    <div class="accordion-body px-0 pt-3">
                                        <div class="row g-3">
                                            <?php foreach ($stageEvals as $se): ?>
                                                <div class="col-12">
                                                    <div class="p-3 bg-light rounded-3 border-start border-primary border-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div class="fw-bold text-secondary" style="font-size: 0.85rem;"><i class="bi bi-person-fill me-1"></i><?php echo htmlspecialchars($se['evaluator_name']); ?></div>
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                                                                <?php if ($se['show_to_student'] == 1): ?>
                                                                    <?php echo number_format($se['total_marks'], 0); ?> / <?php echo $stageMaxMarks; ?>
                                                                <?php else: ?>
                                                                    0 / <?php echo $stageMaxMarks; ?> (Hidden)
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                        <div class="small text-muted" style="line-height: 1.4;">
                                                            <strong>Evaluator Remarks:</strong>
                                                            <div class="mt-1 bg-white p-2.5 rounded border border-light text-dark text-justify">
                                                                <?php if ($se['show_to_student'] == 1): ?>
                                                                    <?php echo !empty($se['remarks']) ? nl2br(htmlspecialchars($se['remarks'])) : '<em class="text-muted">No specific remarks entered.</em>'; ?>
                                                                <?php else: ?>
                                                                    <em class="text-muted">Marks and remarks are hidden by the evaluator.</em>
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
                    <div class="text-center text-muted py-4 small">
                        <i class="bi bi-chat-left-dots-fill fs-3 mb-2 d-block text-secondary opacity-50"></i>
                        No committee evaluations or remarks have been recorded for your group yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Grade Card Overview -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white text-center mb-4">
                <h5 class="fw-bold text-dark mb-4">Overall Result</h5>
                
                <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 90px; height: 90px; font-size: 2.5rem; font-weight: 700;">
                    <?php echo htmlspecialchars($showGrade); ?>
                </div>

                <h3 class="fw-bold text-dark mb-1"><?php echo number_format($showPercentage, 0); ?>%</h3>
                <p class="text-muted small mb-4">Aggregate Percentage Score</p>

                <div class="divider border-bottom mb-4"></div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block small">Status</small>
                            <?php if($showStatus === 'Pass'): ?>
                                <span class="fw-bold text-success fs-5">PASS</span>
                            <?php else: ?>
                                <span class="fw-bold text-danger fs-5">FAIL</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block small">Scale</small>
                            <span class="fw-bold text-dark fs-5">A+ to F</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade scale criteria info -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold mb-3">Grade Scale Criteria</h6>
                <div class="small text-muted">
                    <div class="d-flex justify-content-between mb-1.5"><span>A+</span><span>85% – 100% (170 – 200 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>A</span><span>80% – 84% (160 – 169 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>B+</span><span>75% – 79% (150 – 159 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>B</span><span>70% – 74% (140 – 149 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>C+</span><span>65% – 69% (130 – 139 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>C</span><span>60% – 64% (120 – 129 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>D+</span><span>55% – 59% (110 – 119 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1.5"><span>D (Min Pass)</span><span>50% – 54% (100 – 109 Marks)</span></div>
                    <div class="d-flex justify-content-between"><span>F</span><span>Below 50% (Below 100 Marks)</span></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
