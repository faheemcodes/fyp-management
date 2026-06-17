<!-- Student Grade View -->
<?php if (!$group): ?>
    <div class="alert alert-warning rounded-3 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> You must first create or join a group to view results.
    </div>
<?php else: ?>
    
    <div class="row g-4">
        <!-- Grading Weightage Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4">Final Grade Details & Breakdown</h5>
                
                <div class="table-responsive">
                    <table class="table align-middle border-0 m-0" style="box-shadow: none;">
                        <thead>
                            <tr>
                                <th>Milestone Components</th>
                                <th>Max Marks</th>
                                <th class="text-end">Marks Scored</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">1. Proposal Submission</div>
                                    <small class="text-muted">Awarded automatically upon supervisor proposal approval</small>
                                </td>
                                <td>10 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($grade['proposal_marks'] ?? 0.00, 2); ?> / 10.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">2. Proposal Defence Presentation</div>
                                    <small class="text-muted">Awarded manually by committee on presentation defence</small>
                                </td>
                                <td>30 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($grade['proposal_defense_marks'] ?? 0.00, 2); ?> / 30.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">3. FYP Progress Presentation</div>
                                    <small class="text-muted">Awarded manually by committee on mid-term progress presentation</small>
                                </td>
                                <td>40 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($grade['progress_presentation_marks'] ?? 0.00, 2); ?> / 40.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">4. Supervision</div>
                                    <small class="text-muted">Awarded by supervisor for guidance and performance</small>
                                </td>
                                <td>45 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($grade['supervision_marks'] ?? 0.00, 2); ?> / 45.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">5. Final Presentation</div>
                                    <small class="text-muted">Awarded manually by committee on Project Demo (25), Thesis (25), and Presentation (25)</small>
                                </td>
                                <td>75 Marks</td>
                                <td class="text-end font-monospace fw-bold text-dark"><?php echo number_format($grade['final_presentation_marks'] ?? 0.00, 2); ?> / 75.00</td>
                            </tr>
                            <tr class="table-light border-top">
                                <td><strong class="text-dark">Cumulative Total Score</strong></td>
                                <td><strong>200 Marks</strong></td>
                                <td class="text-end font-monospace fw-bold text-primary fs-4"><?php echo number_format($grade['total_marks'] ?? 0.00, 2); ?> / 200.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Grade Card Overview -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white text-center mb-4">
                <h5 class="fw-bold text-dark mb-4">Overall Result</h5>
                
                <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 90px; height: 90px; font-size: 2.5rem; font-weight: 700;">
                    <?php echo htmlspecialchars($grade['grade'] ?? 'F'); ?>
                </div>

                <h3 class="fw-bold text-dark mb-1"><?php echo number_format($grade['percentage'] ?? 0.00, 1); ?>%</h3>
                <p class="text-muted small mb-4">Aggregate Percentage Score</p>

                <div class="divider border-bottom mb-4"></div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block small">Status</small>
                            <?php if(($grade['status'] ?? '') === 'Pass'): ?>
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
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white bg-gradient text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <h6 class="fw-bold mb-2">Grade Scale Criteria</h6>
                <div class="small text-white-50">
                    <div class="d-flex justify-content-between mb-1"><span>A+</span><span>85% – 100% (170 – 200 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>A</span><span>80% – 84.9% (160 – 169.8 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>B+</span><span>75% – 79.9% (150 – 159.8 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>B</span><span>70% – 74.9% (140 – 149.8 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>C+</span><span>65% – 69.9% (130 – 139.8 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>C</span><span>60% – 64.9% (120 – 129.8 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>D+</span><span>55% – 59.9% (110 – 119.8 Marks)</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>D (Min Pass)</span><span>50% – 54.9% (100 – 109.8 Marks)</span></div>
                    <div class="d-flex justify-content-between"><span>F</span><span>Below 50% (Below 100 Marks)</span></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
