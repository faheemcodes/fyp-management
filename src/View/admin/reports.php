<style>
/* ─── Section Panel ─── */



/* ─── Modern Table Styles ─── */







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
    background: var(--accent-color, #10b981);
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
<!-- Admin Reports & Analytics View -->


<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="admin-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="admin-hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <h4 class="fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Reports & Analytics</h4>
                <p class="mb-0 mt-1" style="font-size: 0.85rem">View group progress, evaluation criteria, and cumulative final grades</p>
            </div>
        </div>
        <a href="/admin/reports/print" target="_blank" class="btn-hero-glass rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm text-decoration-none d-flex align-items-center justify-content-center">
            <i class="bi bi-printer me-2"></i> Print Report
        </a>
    </div>
</div>



<!-- Detailed Grading Report -->
<div class="d-none d-print-block text-center mb-4 pb-2" style="border-bottom: 2px solid #000;">
    <h2 class="fw-bold mb-1" style="color: #000;">Cumulative Final Grading Report</h2>
    <p class="mb-1" style="color: #333; font-size: 1.1rem;">FYP Management System - University of Sindh</p>
    <p class="mb-0" style="color: #666; font-size: 0.9rem;">Generated on <?php echo date('F j, Y'); ?></p>
</div>
<div class="glass-panel mb-4">
    <div class="border-bottom p-3 bg-light rounded-top d-print-none" style="border-radius: 16px 16px 0 0;">
        <h6 class="fw-bold text-dark m-0">Cumulative Final Grading Report</h6>
    </div>
    
    <!-- Filters and Search Controls -->
    <div class="p-3 border-bottom d-print-none">
        <div class="d-flex flex-column gap-3">
            <!-- Row 1: Search, Department, Shift -->
            <div class="premium-filter-group w-100">
                <!-- Search Input -->
                <div class="flex-grow-1 d-flex align-items-center px-3">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" class="form-control premium-filter-input table-search" placeholder="Search grades by group code, project title..." data-target="grades-table">
                </div>
                
                <!-- Divider -->
                <div class="premium-filter-divider"></div>
                
                <!-- Department Filter -->
                <div class="d-flex align-items-center px-2" style="flex-basis: 25%;">
                    <select class="form-select premium-filter-input table-filter w-100" data-column="department" data-target="grades-table">
                        <option value="all">All Departments</option>
                        <?php 
                        $uniqueDepts = array_unique(array_filter(array_column($studentGrades, 'department')));
                        sort($uniqueDepts);
                        foreach($uniqueDepts as $dept): 
                        ?>
                            <option value="<?php echo htmlspecialchars($dept); ?>"><?php echo htmlspecialchars($dept); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Divider -->
                <div class="premium-filter-divider"></div>
                
                <!-- Shift Filter -->
                <div class="d-flex align-items-center px-2 pe-3" style="flex-basis: 20%;">
                    <select class="form-select premium-filter-input table-filter w-100" data-column="shift" data-target="grades-table">
                        <option value="all">All Shifts</option>
                        <?php 
                        $uniqueShifts = array_unique(array_filter(array_column($studentGrades, 'shift')));
                        sort($uniqueShifts);
                        foreach($uniqueShifts as $shift): 
                        ?>
                            <option value="<?php echo htmlspecialchars($shift); ?>"><?php echo htmlspecialchars($shift); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Row 2: Supervisor, Status, Grade -->
            <div class="premium-filter-group w-100">
                <!-- Supervisor Filter -->
                <div class="d-flex align-items-center px-2 ps-3 flex-grow-1">
                    <select class="form-select premium-filter-input table-filter w-100" data-column="supervisor" data-target="grades-table">
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
                
                <!-- Divider -->
                <div class="premium-filter-divider"></div>
                
                <!-- Status Filter -->
                <div class="d-flex align-items-center px-2 flex-grow-1">
                    <select class="form-select premium-filter-input table-filter w-100" data-column="status" data-target="grades-table">
                        <option value="all">All Statuses</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                    </select>
                </div>
                
                <!-- Divider -->
                <div class="premium-filter-divider"></div>
                
                <!-- Grade Filter -->
                <div class="d-flex align-items-center px-2 pe-3 flex-grow-1">
                    <select class="form-select premium-filter-input table-filter w-100" data-column="grade" data-target="grades-table">
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
    </div>

    <div class="table-responsive p-3">
        <table class="table premium-table m-0" id="grades-table">
            <thead>
                <tr>
                    <th class="ps-4 align-middle">Student Details</th>
                    <th class="text-center align-middle">Prop. Def. (40)</th>
                    <th class="text-center align-middle">Prog. Pres. (40)</th>
                    <th class="text-center align-middle">Supv. (45)</th>
                    <th class="text-center align-middle">Final Pres. (75)</th>
                    <th class="text-center align-middle" style="background: rgba(16,185,129,0.1);color: #1e3a5f">Total (200)</th>
                    <th class="text-center align-middle">Grade</th>
                    <th class="text-end pe-4 align-middle">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($studentGrades as $sg): ?>
                <tr data-supervisor="<?php echo htmlspecialchars($sg['supervisor_name'] ?? 'unassigned'); ?>" data-status="<?php echo htmlspecialchars($sg['status']); ?>" data-grade="<?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?>" data-department="<?php echo htmlspecialchars($sg['department'] ?? ''); ?>" data-shift="<?php echo htmlspecialchars($sg['shift'] ?? ''); ?>">
                    <td class="ps-4">
                        <div class="fw-bold text-primary" style="font-size: 0.95rem"><?php echo htmlspecialchars($sg['student_name'] ?? 'Unknown'); ?> (<?php echo htmlspecialchars($sg['roll_no'] ?? ''); ?>)</div>
                        <div class="small mt-1" style="font-weight: 500; word-break: break-word;" title="<?php echo htmlspecialchars($sg['project_title']); ?>"><?php echo htmlspecialchars($sg['project_title']); ?></div>
                        <div class="text-muted" style="font-size: 0.75rem;margin-top: 2px"><i class="bi bi-people me-1"></i><?php echo htmlspecialchars($sg['group_code'] ?? 'N/A'); ?> &nbsp;|&nbsp; <i class="bi bi-person-badge me-1"></i><?php echo htmlspecialchars($sg['supervisor_name'] ?? 'Unassigned'); ?></div>
                    </td>
                    <td class="text-center align-middle font-monospace opacity-75"><?php echo number_format($sg['proposal_defense_marks'] ?? 0, 0); ?></td>
                    <td class="text-center align-middle font-monospace opacity-75"><?php echo number_format($sg['progress_presentation_marks'] ?? 0, 0); ?></td>
                    <td class="text-center align-middle font-monospace opacity-75"><?php echo number_format($sg['supervision_marks'] ?? 0, 0); ?></td>
                    <td class="text-center align-middle font-monospace opacity-75"><?php echo number_format($sg['final_presentation_marks'] ?? 0, 0); ?></td>
                    <td class="text-center align-middle font-monospace" style="background: rgba(16,185,129,0.02);"><?php echo number_format($sg['total_marks'] ?? 0, 0); ?></td>
                    <td class="text-center align-middle">
                        <span class="fw-semibold" style="font-size: 1.1rem;">
                            <?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?>
                        </span>
                    </td>
                    <td class="text-end pe-4 align-middle">
                        <?php if($sg['status'] === 'Pass'): ?>
                            <span class="premium-badge success">Pass</span>
                        <?php else: ?>
                            <span class="premium-badge danger">Fail</span>
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
