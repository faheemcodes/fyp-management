<!-- Admin Reports & Analytics View -->
<style>
/* ─── Hero Banner Styles ─── */
.group-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.group-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero-icon {
    width: 56px;
    height: 56px;
    background: conic-gradient(from 0deg, #3b82f6, #6366f1, #8b5cf6, #3b82f6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}

/* ─── Section Panel ─── */
.grp-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 24px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.grp-section-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}

/* ─── Modern Table Styles ─── */
.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.modern-table thead th {
    background: var(--form-bg);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-secondary);
    font-weight: 700;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}
.modern-table tbody td {
    padding: 16px 24px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    background: var(--card-bg);
    transition: background-color 0.2s ease;
}
.modern-table tbody tr:hover td {
    background-color: rgba(59,130,246,0.02);
}
.modern-table tbody tr:last-child td {
    border-bottom: none;
}
.status-pill {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    display: inline-block;
}

@media (max-width: 768px) {
/* ─── Evaluation Info Cards ─── */
:root {
    --eval-bg: linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%);
    --eval-text: #1e293b;
    --eval-text-muted: rgba(0, 0, 0, 0.55);
    --eval-border: rgba(0,0,0,0.08);
    --eval-card-bg: rgba(255,255,255,0.6);
    --eval-card-border: rgba(0,0,0,0.05);
    --eval-card-hover: #ffffff;
    --eval-card-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
    --eval-icon-bg: rgba(0,0,0,0.03);
}
html.dark-theme, body.dark-theme {
    --eval-bg: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
    --eval-text: #f8fafc;
    --eval-text-muted: rgba(255, 255, 255, 0.55);
    --eval-border: rgba(255,255,255,0.08);
    --eval-card-bg: rgba(255,255,255,0.03);
    --eval-card-border: rgba(255,255,255,0.05);
    --eval-card-hover: rgba(255,255,255,0.06);
    --eval-card-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
    --eval-icon-bg: rgba(255,255,255,0.03);
}

.eval-container {
    background: var(--eval-bg) !important;
    color: var(--eval-text) !important;
    border: 1px solid var(--eval-border) !important;
}
.eval-card {
    background: var(--eval-card-bg);
    border: 1px solid var(--eval-card-border);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.eval-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 4px; height: 100%;
    background: var(--accent-color, #3b82f6);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.eval-card:hover {
    background: var(--eval-card-hover);
    transform: translateX(6px);
    border-color: var(--eval-border);
    box-shadow: var(--eval-card-shadow);
}
.eval-card:hover::before {
    opacity: 1;
}
.eval-card h6 {
    color: var(--eval-text) !important;
}
.eval-bg-icon {
    color: var(--eval-text) !important;
    opacity: 0.04 !important;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #8b5cf6, #a855f7, #6366f1, #8b5cf6);">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Reports & Analytics</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">View group progress, evaluation criteria, and cumulative final grades</p>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-4 align-self-stretch align-self-md-center border-2" style="background: rgba(255,255,255,0.1);">
            <i class="bi bi-printer me-2"></i> Print Report
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Progress Stage Distribution -->
    <div class="col-md-5">
        <div class="grp-section h-100 mb-0 d-flex flex-column">
            <div class="grp-section-header">
                <h6 class="fw-bold m-0" style="color: var(--text-primary);"><i class="bi bi-bar-chart-steps me-2 text-primary"></i>Group Progress Distribution</h6>
            </div>
            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-start">
                <?php if(empty($progressStages)): ?>
                    <div class="text-center text-muted py-4">No group progress records available.</div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach($progressStages as $ps): ?>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4" style="background: var(--form-bg); border: 1px solid var(--border-color); transition: all 0.2s ease;">
                            <span class="fw-semibold" style="font-size: 0.9rem; color: var(--text-primary);">
                                <?php echo htmlspecialchars($ps['progress_stage']); ?>
                            </span>
                            <span class="badge rounded-pill shadow-sm px-3 py-2" style="background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); font-size: 0.85rem;">
                                <?php echo $ps['count']; ?> Groups
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Overview details -->
    <div class="col-md-7">
        <div class="grp-section h-100 mb-0 position-relative eval-container">
            <div class="p-4 position-relative z-1">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4 gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="letter-spacing: -0.02em;">
                            <i class="bi bi-shield-check" style="color: #3b82f6; font-size: 1.3rem;"></i> 
                            Evaluation Criteria
                        </h5>
                        <p class="m-0" style="opacity: 0.6;">Systematic breakdown of the final grading components.</p>
                    </div>
                    <div class="text-sm-end">
                        <span class="badge rounded-pill fw-bold px-3 py-2 shadow-sm" style="background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3); font-size: 0.85rem; letter-spacing: 0.03em;">200 TOTAL MARKS</span>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Stage 1 -->
                    <div class="col-12">
                        <div class="eval-card p-3 rounded-4" style="--accent-color: #f59e0b;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(245,158,11,0.15); color: #f59e0b;">
                                    <i class="bi bi-file-earmark-text-fill fs-5"></i>
                                </div>
                                <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                                    <div>
                                        <h6 class="fw-bold m-0" style="font-size: 0.95rem;">1. Proposal Submission</h6>
                                        <p class="m-0 mt-1" style="font-size: 0.8rem; opacity: 0.6;">Awarded automatically upon supervisor proposal approval.</p>
                                    </div>
                                    <div class="text-md-end flex-shrink-0">
                                        <span class="fw-bold font-monospace badge px-3 py-2" style="background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 0.85rem;">10 PTS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 2 -->
                    <div class="col-12">
                        <div class="eval-card p-3 rounded-4" style="--accent-color: #3b82f6;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(59,130,246,0.15); color: #3b82f6;">
                                    <i class="bi bi-easel-fill fs-5"></i>
                                </div>
                                <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                                    <div>
                                        <h6 class="fw-bold m-0" style="font-size: 0.95rem;">2. Proposal Defence</h6>
                                        <p class="m-0 mt-1" style="font-size: 0.8rem; opacity: 0.6;">Awarded manually by committee on presentation defence.</p>
                                    </div>
                                    <div class="text-md-end flex-shrink-0">
                                        <span class="fw-bold font-monospace badge px-3 py-2" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.85rem;">30 PTS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 3 -->
                    <div class="col-12">
                        <div class="eval-card p-3 rounded-4" style="--accent-color: #8b5cf6;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(139,92,246,0.15); color: #8b5cf6;">
                                    <i class="bi bi-graph-up-arrow fs-5"></i>
                                </div>
                                <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                                    <div>
                                        <h6 class="fw-bold m-0" style="font-size: 0.95rem;">3. FYP Progress</h6>
                                        <p class="m-0 mt-1" style="font-size: 0.8rem; opacity: 0.6;">Awarded manually by committee based on progress presentation score.</p>
                                    </div>
                                    <div class="text-md-end flex-shrink-0">
                                        <span class="fw-bold font-monospace badge px-3 py-2" style="background: rgba(139,92,246,0.1); color: #8b5cf6; font-size: 0.85rem;">40 PTS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 4 -->
                    <div class="col-12">
                        <div class="eval-card p-3 rounded-4" style="--accent-color: #10b981;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(16,185,129,0.15); color: #10b981;">
                                    <i class="bi bi-person-workspace fs-5"></i>
                                </div>
                                <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                                    <div>
                                        <h6 class="fw-bold m-0" style="font-size: 0.95rem;">4. Supervision</h6>
                                        <p class="m-0 mt-1" style="font-size: 0.8rem; opacity: 0.6;">Awarded by supervisor for student guidance and overall performance.</p>
                                    </div>
                                    <div class="text-md-end flex-shrink-0">
                                        <span class="fw-bold font-monospace badge px-3 py-2" style="background: rgba(16,185,129,0.1); color: #10b981; font-size: 0.85rem;">45 PTS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 5 -->
                    <div class="col-12">
                        <div class="eval-card p-3 rounded-4" style="--accent-color: #ec4899;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(236,72,153,0.15); color: #ec4899;">
                                    <i class="bi bi-stars fs-5"></i>
                                </div>
                                <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                                    <div>
                                        <h6 class="fw-bold m-0" style="font-size: 0.95rem;">5. Final Presentation</h6>
                                        <p class="m-0 mt-1" style="font-size: 0.8rem; opacity: 0.6;">Awarded manually by committee: Project Demo (25) + Thesis (25) + Presentation (25).</p>
                                    </div>
                                    <div class="text-md-end flex-shrink-0">
                                        <span class="fw-bold font-monospace badge px-3 py-2" style="background: rgba(236,72,153,0.1); color: #ec4899; font-size: 0.85rem;">75 PTS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Grading Report -->
<div class="grp-section">
    <div class="grp-section-header">
        <h6 class="fw-bold text-dark m-0">Cumulative Final Grading Report</h6>
    </div>
    
    <!-- Filters and Search Controls -->
    <div class="p-3 border-bottom" style="background: var(--form-bg);">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-4 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search grades by group code, supervisor..." data-target="grades-table">
                </div>
            </div>
            <!-- Supervisor Filter -->
            <div class="col-md-3">
                <select class="form-select table-filter shadow-sm rounded-pill border border-light-subtle" data-column="supervisor" data-target="grades-table">
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
                <select class="form-select table-filter shadow-sm rounded-pill border border-light-subtle" data-column="status" data-target="grades-table">
                    <option value="all">All Statuses</option>
                    <option value="Pass">Pass</option>
                    <option value="Fail">Fail</option>
                </select>
            </div>
            <!-- Grade Filter -->
            <div class="col-md-3 pe-0">
                <select class="form-select table-filter shadow-sm rounded-pill border border-light-subtle" data-column="grade" data-target="grades-table">
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
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="grades-table">
            <thead>
                <tr>
                    <th class="ps-4">Student Details</th>
                    <th class="text-center">Prop. Sub. (10)</th>
                    <th class="text-center">Prop. Def. (30)</th>
                    <th class="text-center">Prog. Pres. (40)</th>
                    <th class="text-center">Supv. (45)</th>
                    <th class="text-center">Final Pres. (75)</th>
                    <th class="text-center" style="background: rgba(59,130,246,0.1); color: #1e3a5f;">Total (200)</th>
                    <th class="text-center">Grade</th>
                    <th class="text-end pe-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($studentGrades as $sg): ?>
                <tr data-supervisor="<?php echo htmlspecialchars($sg['supervisor_name'] ?? 'unassigned'); ?>" data-status="<?php echo htmlspecialchars($sg['status']); ?>" data-grade="<?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?>">
                    <td class="ps-4">
                        <div class="fw-bold text-primary" style="font-size: 0.95rem;"><?php echo htmlspecialchars($sg['student_name'] ?? 'Unknown'); ?> (<?php echo htmlspecialchars($sg['roll_no'] ?? ''); ?>)</div>
                        <div class="small text-truncate text-dark mt-1" style="max-width: 180px; font-weight: 500;" title="<?php echo htmlspecialchars($sg['project_title']); ?>"><?php echo htmlspecialchars($sg['project_title']); ?></div>
                        <div class="text-muted" style="font-size: 0.75rem; margin-top: 2px;"><i class="bi bi-people me-1"></i><?php echo htmlspecialchars($sg['group_code'] ?? 'N/A'); ?> &nbsp;|&nbsp; <i class="bi bi-person-badge me-1"></i><?php echo htmlspecialchars($sg['supervisor_name'] ?? 'Unassigned'); ?></div>
                    </td>
                    <td class="text-center font-monospace fw-semibold" style="color: #475569;"><?php echo number_format($sg['proposal_marks'] ?? 0, 0); ?></td>
                    <td class="text-center font-monospace fw-semibold" style="color: #475569;"><?php echo number_format($sg['proposal_defense_marks'] ?? 0, 0); ?></td>
                    <td class="text-center font-monospace fw-semibold" style="color: #475569;"><?php echo number_format($sg['progress_presentation_marks'] ?? 0, 0); ?></td>
                    <td class="text-center font-monospace fw-semibold" style="color: #475569;"><?php echo number_format($sg['supervision_marks'] ?? 0, 0); ?></td>
                    <td class="text-center font-monospace fw-semibold" style="color: #475569;"><?php echo number_format($sg['final_presentation_marks'] ?? 0, 0); ?></td>
                    <td class="text-center font-monospace fw-bold fs-5" style="background: rgba(59,130,246,0.02); color: #1e3a5f;"><?php echo number_format($sg['total_marks'] ?? 0, 0); ?></td>
                    <td class="text-center">
                        <span class="badge font-monospace rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; font-size: 0.95rem; background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff;">
                            <?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <?php if($sg['status'] === 'Pass'): ?>
                            <span class="status-pill" style="background: rgba(16,185,129,0.15); color: #059669;">Pass</span>
                        <?php else: ?>
                            <span class="status-pill" style="background: rgba(239,68,68,0.15); color: #dc2626;">Fail</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($studentGrades)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">No grading records available yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
