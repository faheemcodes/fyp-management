<!-- Admin Executive Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$totalProjectsInFunnel = array_sum($stages ?? []);
?>

<style>
/* ── Admin Dashboard Custom Section Cards & Components ── */
.admin-section-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    margin-bottom: 1.5rem;
    transition: box-shadow 0.25s ease, border-color 0.25s ease;
}
.admin-section-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: rgba(59, 130, 246, 0.25);
}

.admin-section-header {
    padding: 1.1rem 1.35rem;
    background: var(--form-bg, #f8fafc);
    border-bottom: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
    gap: 10px;
}

.admin-section-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex-grow: 1;
}

.admin-section-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.admin-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    margin: 0;
    line-height: 1.3;
    letter-spacing: -0.01em;
}

.admin-section-subtitle {
    font-size: 0.75rem;
    color: var(--text-secondary, #64748b);
    margin: 2px 0 0 0;
    display: block;
}

.admin-section-body {
    padding: 1.35rem;
}

/* ── Funnel Step Cards & Grid ── */
.funnel-grid {
    --bs-gutter-x: 1.15rem;
    --bs-gutter-y: 1.25rem;
}
@media (min-width: 1200px) {
    .funnel-grid {
        --bs-gutter-x: 1.35rem;
        --bs-gutter-y: 1.35rem;
    }
}

.funnel-step-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    border-radius: 16px;
    padding: 18px 12px;
    text-align: center;
    position: relative;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 115px;
    height: 100%;
    overflow: hidden;
}
.funnel-step-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    border-color: rgba(59, 130, 246, 0.35);
}
.funnel-step-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    font-size: 0.75rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
}
.funnel-step-count {
    font-size: 1.65rem;
    font-weight: 800;
    line-height: 1.1;
    color: var(--text-primary, #1e293b);
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}
.funnel-step-title {
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--text-secondary, #64748b);
    line-height: 1.25;
}

/* ── Modern Table inside Admin Cards ── */
.admin-table th {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 12px 16px;
}
.admin-table td {
    padding: 14px 16px;
    vertical-align: middle;
    font-size: 0.88rem;
    border-bottom: 1px solid var(--border-color);
}
.admin-table tr:last-child td {
    border-bottom: none;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.45rem; letter-spacing: -0.02em">Executive Command Center</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-person-workspace me-1"></i> Super Admin
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(16, 185, 129, 0.25); color: #a7f3d0; border: 1px solid rgba(16, 185, 129, 0.45); font-size: 0.82rem;">
                        <i class="bi bi-box-seam me-1"></i> Batch <?php echo htmlspecialchars($stats['active_batch_name'] ?? '2023', ENT_QUOTES, 'UTF-8'); ?> Active
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Total system oversight, student registrations, faculty allocations, milestone tracking, and cumulative academic records
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center">
            <a href="<?php echo $basePath; ?>/admin/users" class="btn rounded-pill px-3.5 py-2 fw-semibold shadow-sm border-0 d-inline-flex align-items-center gap-2" style="background: #ffffff; color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-person-plus-fill text-primary"></i> <span>Add User</span>
            </a>
            <a href="<?php echo $basePath; ?>/admin/cumulative-sheet" class="btn rounded-pill px-3.5 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-file-earmark-ruled-fill"></i> <span>Cumulative Sheet</span>
            </a>
            <a href="<?php echo $basePath; ?>/admin/meetings" class="btn rounded-pill px-3.5 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-calendar2-check-fill"></i> <span>Meetings Audit</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ 9 Core Action Cards Grid (3 Rows of 3) ═══════════════ -->
<div class="mb-4">
    <!-- Row 1: Users, Groups & Proposals -->
    <div class="row g-3 mb-3">
        <!-- 1. Manage Users Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/users" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-blue h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-blue">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="premium-card-count"><?php echo htmlspecialchars((string)($stats['total_users'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($stats['pending_approvals'])): ?>
                                    <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.7rem;"><?php echo $stats['pending_approvals']; ?> New</span>
                                <?php endif; ?>
                            </div>
                            <div class="premium-card-label">Manage Users</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 2. FYP Groups Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/groups" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-green h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-green">
                            <i class="bi bi-folder-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count">
                                <?php echo htmlspecialchars((string)($stats['active_projects'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>
                                <span style="font-size: 0.95rem; font-weight: 600; opacity: 0.7;">/ <?php echo htmlspecialchars((string)($stats['total_groups'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="premium-card-label">FYP Groups</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 3. Project Proposals Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/proposals" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-purple h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-purple">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="premium-card-count"><?php echo htmlspecialchars((string)($stats['pending_proposals'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($stats['pending_proposals'])): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Review</span>
                                <?php endif; ?>
                            </div>
                            <div class="premium-card-label">Project Proposals</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Row 2: Allocation & Evaluation Sheets -->
    <div class="row g-3 mb-3">
        <!-- 4. Group Allocation Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/committees" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-cyan h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-cyan">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count">
                                <?php echo htmlspecialchars((string)($stats['allocated_groups'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>
                                <span style="font-size: 0.95rem; font-weight: 600; opacity: 0.7;">/ <?php echo htmlspecialchars((string)($stats['total_groups'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="premium-card-label">Group Allocation</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 5. Presentation Sheets Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/presentation-sheets" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-teal h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-teal">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count" style="font-size: 1.25rem;">Generate</div>
                            <div class="premium-card-label">Presentation Sheets</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 6. Attendance Sheets Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/attendance-sheet" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-amber h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-amber">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count" style="font-size: 1.25rem;">Generate</div>
                            <div class="premium-card-label">Attendance Sheets</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Row 3: Cumulative Sheet, Meetings Audit & Supervisor Slots -->
    <div class="row g-3">
        <!-- 7. Cumulative Sheet Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/cumulative-sheet" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-indigo h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-indigo">
                            <i class="bi bi-file-earmark-ruled-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['avg_marks'] ?? 'N/A'), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="premium-card-label">Cumulative Sheet</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 8. Meetings Audit Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/meetings" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-rose h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-rose">
                            <i class="bi bi-calendar2-check-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="premium-card-count"><?php echo htmlspecialchars((string)($stats['pending_meetings'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($stats['pending_meetings'])): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Audit</span>
                                <?php endif; ?>
                            </div>
                            <div class="premium-card-label">Meetings Audit</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 9. Supervisor Slots Card -->
        <div class="col-12 col-md-4">
            <a href="<?php echo $basePath; ?>/admin/slots" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-green h-100">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-green">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['supervisors'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="premium-card-label">Supervisor Slots</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ University-Wide FYP Funnel ═══════════════ -->
<div class="admin-section-card mb-4">
    <div class="admin-section-header">
        <div class="admin-section-title-wrap">
            <div class="admin-section-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-funnel-fill"></i>
            </div>
            <div>
                <h6 class="admin-section-title">University-Wide Project Progress Pipeline</h6>
                <span class="admin-section-subtitle">Real-time status of all active projects across all milestone defense stages</span>
            </div>
        </div>
        <div>
            <span class="badge rounded-pill fw-bold" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.25); padding: 6px 12px;">
                Total Tracked: <?php echo $totalProjectsInFunnel; ?> Groups
            </span>
        </div>
    </div>
    <div class="admin-section-body">
        <div class="row g-3 funnel-grid">
            <?php 
            $funnelMeta = [
                'Proposal Submitted' => ['num' => '1', 'title' => 'Proposal Submitted', 'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,0.1)'],
                'Proposal Approved' => ['num' => '2', 'title' => 'Proposal Approved', 'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.1)'],
                'Proposal Defence Presentation Completed' => ['num' => '3', 'title' => 'Defense Done', 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.1)'],
                'FYP Progress Presentation Completed' => ['num' => '4', 'title' => 'Progress Done', 'color' => '#06b6d4', 'bg' => 'rgba(6,182,212,0.1)'],
                'Final Presentation Completed' => ['num' => '5', 'title' => 'Final Done', 'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.1)'],
                'Final Grading Completed' => ['num' => '6', 'title' => 'Graded & Done', 'color' => '#ec4899', 'bg' => 'rgba(236,72,153,0.1)']
            ];
            foreach ($funnelMeta as $stageKey => $meta): 
                $cnt = $stages[$stageKey] ?? 0;
            ?>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="funnel-step-card">
                        <div class="funnel-step-num" style="background: <?php echo $meta['bg']; ?>; color: <?php echo $meta['color']; ?>;">
                            <?php echo $meta['num']; ?>
                        </div>
                        <div class="funnel-step-count" style="color: <?php echo $meta['color']; ?>;">
                            <?php echo $cnt; ?>
                        </div>
                        <div class="funnel-step-title">
                            <?php echo $meta['title']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ═══════════════ Department Breakdown & Quick Pending Review ═══════════════ -->
<div class="row g-4 mb-4">
    <!-- Department Overview Table -->
    <div class="col-12 col-xl-7">
        <div class="admin-section-card h-100 mb-0">
            <div class="admin-section-header">
                <div class="admin-section-title-wrap">
                    <div class="admin-section-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <div>
                        <h6 class="admin-section-title">Department Cohort Breakdown</h6>
                        <span class="admin-section-subtitle">Student registrations, faculty numbers, and approved project allocations</span>
                    </div>
                </div>
            </div>
            <div class="p-0 table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Department</th>
                            <th class="text-center">Students</th>
                            <th class="text-center">Groups</th>
                            <th class="text-center">Supervisors</th>
                            <th class="text-center">Approved Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($departmentStats)): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No departmental records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($departmentStats as $deptName => $dInfo): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <i class="bi bi-mortarboard text-primary me-2"></i><?php echo htmlspecialchars($deptName, ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td class="text-center fw-semibold text-secondary"><?php echo $dInfo['students']; ?></td>
                                    <td class="text-center fw-semibold text-secondary"><?php echo $dInfo['groups']; ?></td>
                                    <td class="text-center fw-semibold text-secondary"><?php echo $dInfo['supervisors']; ?></td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 0.8rem; font-weight: 700; padding: 4px 10px;">
                                            <?php echo $dInfo['approved_projects']; ?> Approved
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Approvals & Proposals Quick Actions -->
    <div class="col-12 col-xl-5">
        <div class="admin-section-card h-100 mb-0">
            <div class="admin-section-header">
                <div class="admin-section-title-wrap">
                    <div class="admin-section-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h6 class="admin-section-title">Pending Tasks Requiring Action</h6>
                        <span class="admin-section-subtitle">Awaiting administrative approval or review</span>
                    </div>
                </div>
            </div>
            <div class="admin-section-body">
                <!-- Tab Controls -->
                <ul class="nav nav-pills mb-3 gap-2" id="pendingTasksTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.82rem;" id="pending-students-tab" data-bs-toggle="pill" data-bs-target="#pending-students-panel" type="button" role="tab">
                            Students (<?php echo count($pendingStudentsList); ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.82rem;" id="pending-proposals-tab" data-bs-toggle="pill" data-bs-target="#pending-proposals-panel" type="button" role="tab">
                            Proposals (<?php echo count($pendingProposalsList); ?>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pendingTasksContent">
                    <!-- Students Tab -->
                    <div class="tab-pane fade show active" id="pending-students-panel" role="tabpanel">
                        <?php if (empty($pendingStudentsList)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success fs-3 mb-2 d-block"></i>
                                <p class="text-muted m-0" style="font-size: 0.85rem;">All student registrations are verified and up to date.</p>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($pendingStudentsList as $pStd): ?>
                                    <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between gap-2" style="background: var(--form-bg);">
                                        <div class="d-flex align-items-center gap-2.5 min-width-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm flex-shrink-0" style="width: 34px; height: 34px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); font-size: 0.75rem;">
                                                <?php echo strtoupper(substr($pStd['name'] ?? 'S', 0, 1)); ?>
                                            </div>
                                            <div class="text-truncate">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;"><?php echo htmlspecialchars($pStd['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                <div class="text-muted" style="font-size: 0.74rem;">
                                                    <?php echo htmlspecialchars($pStd['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?> &bull; <?php echo htmlspecialchars($pStd['department'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?php echo $basePath; ?>/admin/users/approve?id=<?php echo $pStd['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 0.75rem;">
                                            Approve
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo $basePath; ?>/admin/users" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.8rem;">
                                    View All Pending Students <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Proposals Tab -->
                    <div class="tab-pane fade" id="pending-proposals-panel" role="tabpanel">
                        <?php if (empty($pendingProposalsList)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success fs-3 mb-2 d-block"></i>
                                <p class="text-muted m-0" style="font-size: 0.85rem;">No project proposals awaiting review.</p>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($pendingProposalsList as $pProp): ?>
                                    <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between gap-2" style="background: var(--form-bg);">
                                        <div class="min-width-0">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem; max-width: 250px;">
                                                <?php echo htmlspecialchars($pProp['project_title'] ?? 'Untitled Project', ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.74rem;">
                                                <span class="badge bg-secondary rounded-pill me-1" style="font-size: 0.68rem;"><?php echo htmlspecialchars($pProp['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                                <?php echo htmlspecialchars($pProp['leader_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($pProp['department'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)
                                            </div>
                                        </div>
                                        <a href="<?php echo $basePath; ?>/admin/proposals" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 0.75rem;">
                                            Review
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo $basePath; ?>/admin/proposals" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.8rem;">
                                    View All Proposals <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
