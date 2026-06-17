<!-- Admin Reports & Analytics View -->
<div class="row g-4 mb-4">
    <!-- Progress Stage Distribution -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-4">Group Progress Distribution</h5>
            <div class="table-responsive">
                <table class="table border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Progress Stage</th>
                            <th class="text-end">Groups Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($progressStages as $ps): ?>
                        <tr>
                            <td class="small fw-semibold"><?php echo htmlspecialchars($ps['progress_stage']); ?></td>
                            <td class="text-end fw-bold text-primary"><?php echo $ps['count']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($progressStages)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No group progress records available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Overview details -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100 bg-gradient text-white" style="background: linear-gradient(135deg, #2E5BFF 0%, #1e293b 100%);">
            <h5 class="fw-bold mb-4"><i class="bi bi-info-circle me-1"></i> FYP Evaluation System Info</h5>
            <p>The system calculates final marks and grading statistics automatically based on manual grading entries across various milestones:</p>
            <div class="table-responsive">
                <table class="table table-dark table-borderless bg-transparent m-0 small" style="box-shadow: none;">
                    <tbody>
                        <tr>
                            <td><strong class="text-warning">1. Proposal Submission</strong></td>
                            <td>10 Marks</td>
                            <td>Awarded automatically upon supervisor proposal approval</td>
                        </tr>
                        <tr>
                            <td><strong class="text-warning">2. Proposal Defence</strong></td>
                            <td>30 Marks</td>
                            <td>Awarded manually by committee on presentation defence</td>
                        </tr>
                        <tr>
                            <td><strong class="text-warning">3. FYP Progress</strong></td>
                            <td>40 Marks</td>
                            <td>Awarded manually by committee on progress presentation score</td>
                        </tr>
                        <tr>
                            <td><strong class="text-warning">4. Supervision</strong></td>
                            <td>45 Marks</td>
                            <td>Awarded by supervisor for student guidance and performance</td>
                        </tr>
                        <tr>
                            <td><strong class="text-warning">5. Final Presentation</strong></td>
                            <td>75 Marks</td>
                            <td>Awarded manually by committee on Project Demo (25) + Thesis (25) + Presentation (25)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Grading Report -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold m-0 text-dark">Cumulative Final Grading Report</h5>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-printer me-1"></i> Print Report</button>
    </div>
    
    <!-- Filters and Search Controls -->
    <div class="row g-3 mb-4 align-items-center">
        <!-- Search Input -->
        <div class="col-md-4">
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0 table-search" placeholder="Search grades by group code, supervisor..." data-target="grades-table">
            </div>
        </div>
        <!-- Supervisor Filter -->
        <div class="col-md-3">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="supervisor" data-target="grades-table">
                <option value="all">All Supervisors</option>
                <option value="unassigned">Unassigned</option>
                <?php 
                $uniqueSups = array_unique(array_filter(array_column($studentGrades, 'supervisor_name')));
                sort($uniqueSups);
                foreach($uniqueSups as $supName): 
                ?>
                    <option value="<?php echo htmlspecialchars($supName); ?>"><?php echo htmlspecialchars($supName); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="col-md-2">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="status" data-target="grades-table">
                <option value="all">All Statuses</option>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
            </select>
        </div>
        <!-- Grade Filter -->
        <div class="col-md-3">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="grade" data-target="grades-table">
                <option value="all">All Grades</option>
                <option value="A+">A+</option>
                <option value="A">A</option>
                <option value="B+">B+</option>
                <option value="B">B</option>
                <option value="C+">C+</option>
                <option value="C">C</option>
                <option value="D+">D+</option>
                <option value="D">D</option>
                <option value="F">F</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="grades-table">
            <thead>
                <tr>
                    <th>Group Details</th>
                    <th>Proposal Submission (10)</th>
                    <th>Proposal Defence (30)</th>
                    <th>Progress Presentation (40)</th>
                    <th>Supervision (45)</th>
                    <th>Final Presentation (75)</th>
                    <th>Total (200)</th>
                    <th>Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($studentGrades as $sg): ?>
                <tr data-supervisor="<?php echo htmlspecialchars($sg['supervisor_name'] ?? 'unassigned'); ?>" data-status="<?php echo htmlspecialchars($sg['status']); ?>" data-grade="<?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?>">
                    <td>
                        <div class="fw-bold text-primary"><?php echo htmlspecialchars($sg['group_code']); ?></div>
                        <div class="small text-truncate text-dark" style="max-width: 180px;" title="<?php echo htmlspecialchars($sg['project_title']); ?>"><?php echo htmlspecialchars($sg['project_title']); ?></div>
                        <small class="text-muted">Supervisor: <?php echo htmlspecialchars($sg['supervisor_name'] ?? 'Unassigned'); ?></small>
                    </td>
                    <td class="font-monospace fw-semibold"><?php echo number_format($sg['proposal_marks'] ?? 0.0, 1); ?></td>
                    <td class="font-monospace fw-semibold"><?php echo number_format($sg['proposal_defense_marks'] ?? 0.0, 1); ?></td>
                    <td class="font-monospace fw-semibold"><?php echo number_format($sg['progress_presentation_marks'] ?? 0.0, 1); ?></td>
                    <td class="font-monospace fw-semibold"><?php echo number_format($sg['supervision_marks'] ?? 0.0, 1); ?></td>
                    <td class="font-monospace fw-semibold"><?php echo number_format($sg['final_presentation_marks'] ?? 0.0, 1); ?></td>
                    <td class="font-monospace fw-bold text-dark fs-5"><?php echo number_format($sg['total_marks'] ?? 0.0, 1); ?></td>
                    <td>
                        <span class="badge bg-dark text-white font-monospace rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?>
                        </span>
                    </td>
                    <td>
                        <?php if($sg['status'] === 'Pass'): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">Pass</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small">Fail</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($studentGrades)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No grading records available yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
