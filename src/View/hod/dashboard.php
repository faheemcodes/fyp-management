<!-- HOD Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$totalProjectsInFunnel = array_sum($stages ?? []);
?>

<style>
/* ── HOD Dashboard Custom Section Cards & Headers ── */
.hod-section-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    margin-bottom: 1.5rem;
    transition: box-shadow 0.25s ease, border-color 0.25s ease;
}
.hod-section-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    border-color: rgba(16, 185, 129, 0.2);
}

.hod-section-header {
    padding: 1.1rem 1.35rem;
    background: var(--form-bg, #f8fafc);
    border-bottom: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.hod-section-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.hod-section-icon {
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

.hod-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    margin: 0;
    line-height: 1.3;
    letter-spacing: -0.01em;
}

.hod-section-subtitle {
    font-size: 0.75rem;
    color: var(--text-secondary, #64748b);
    margin: 2px 0 0 0;
    display: block;
}

.hod-section-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.hod-section-body {
    padding: 1.35rem;
}

/* ── Funnel Step Cards ── */
.funnel-step-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    border-radius: 14px;
    padding: 14px 10px;
    text-align: center;
    position: relative;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    overflow: hidden;
}
.funnel-step-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    border-color: rgba(59, 130, 246, 0.35);
}
.funnel-step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    font-size: 0.72rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
}
.funnel-step-count {
    font-size: 1.55rem;
    font-weight: 800;
    line-height: 1.1;
    color: var(--text-primary, #1e293b);
    margin-bottom: 4px;
    letter-spacing: -0.02em;
}
.funnel-step-title {
    font-size: 0.74rem;
    font-weight: 600;
    color: var(--text-secondary, #64748b);
    line-height: 1.25;
}

/* ── Workload Table Custom Styling ── */
.workload-progress {
    height: 7px;
    border-radius: 10px;
    background-color: var(--form-bg, #f1f5f9);
    overflow: hidden;
}
.workload-user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
    flex-shrink: 0;
}

/* ── Notice Minimal Item Styling ── */
.notice-minimal-item {
    background: var(--form-bg, #f8fafc);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.07));
    border-radius: 12px;
    padding: 11px 13px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.notice-minimal-item:hover {
    background: var(--card-bg, #ffffff);
    border-color: rgba(16, 185, 129, 0.35);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}
.notice-minimal-item .notice-accent-bar {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3.5px;
    background: #10b981;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.notice-minimal-item:hover .notice-accent-bar {
    opacity: 1;
}
.notice-date-badge {
    font-size: 0.68rem;
    font-weight: 600;
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
    padding: 2px 7px;
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    letter-spacing: 0.02em;
}
.notice-view-btn {
    font-size: 0.74rem;
    font-weight: 600;
    color: var(--text-secondary, #64748b);
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.1));
    border-radius: 20px;
    padding: 4px 10px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
    line-height: 1;
}
.notice-minimal-item:hover .notice-view-btn {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.3);
}

.notice-list {
    padding-right: 4px;
    padding-left: 2px;
    padding-top: 2px;
    padding-bottom: 2px;
}
.notice-list::-webkit-scrollbar {
    width: 4px;
}
.notice-list::-webkit-scrollbar-track {
    background: transparent;
}
.notice-list::-webkit-scrollbar-thumb {
    background: rgba(150, 150, 150, 0.25);
    border-radius: 10px;
}
.notice-list::-webkit-scrollbar-thumb:hover {
    background: rgba(150, 150, 150, 0.45);
}

@media (max-width: 767.98px) {
    .hod-section-header {
        padding: 0.95rem 1.15rem;
    }
    .hod-section-body {
        padding: 1.15rem;
    }
    .hod-section-title-wrap {
        width: 100%;
    }
    .hod-section-actions {
        width: 100%;
        justify-content: flex-start;
        margin-top: 2px;
    }
}
</style>

<!-- ── Top Hero Banner ── -->
<div class="page-hero">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.45rem; letter-spacing: -0.02em">Department Overview</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.78); font-size: 0.85rem">Project milestones, supervisor workload, and faculty directory</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo $basePath; ?>/hod/projects" class="btn rounded-pill px-3.5 py-2 fw-semibold shadow-sm border-0 d-inline-flex align-items-center gap-2" style="background: #ffffff; color: #047fb0; font-size: 0.85rem;">
                <i class="bi bi-kanban-fill"></i> <span>Projects</span>
            </a>
            <a href="<?php echo $basePath; ?>/hod/supervisors" class="btn rounded-pill px-3.5 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-person-plus-fill"></i> <span>Faculty</span>
            </a>
        </div>
    </div>
</div>

<!-- ── Stat Cards Grid (Row 1: 3 Cards, Row 2: 2 Cards) ── -->
<div class="mb-4">
    <!-- Row 1: 3 Stat Cards -->
    <div class="row g-3 mb-3">
        <!-- 1. FYP Groups Card -->
        <div class="col-12 col-md-6 col-lg-4">
            <a href="<?php echo $basePath; ?>/hod/projects" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-purple">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-purple">
                            <i class="bi bi-kanban-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['total_groups'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="premium-card-label">FYP Groups</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 2. Supervisors Card -->
        <div class="col-12 col-md-6 col-lg-4">
            <a href="<?php echo $basePath; ?>/hod/supervisors" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-green">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-green">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['supervisors'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="premium-card-label">Supervisors</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 3. Committee Card -->
        <div class="col-12 col-md-12 col-lg-4">
            <a href="<?php echo $basePath; ?>/hod/committee" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-blue">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-blue">
                            <i class="bi bi-shield-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['committee'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="premium-card-label">Committee</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Row 2: 2 Stat Cards -->
    <div class="row g-3">
        <!-- 4. Coordinators Card -->
        <div class="col-12 col-md-6">
            <a href="<?php echo $basePath; ?>/hod/coordinators" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-rose">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-rose">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['coordinators'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="premium-card-label">Coordinators</div>
                        </div>
                        <div class="premium-card-arrow">
                            <i class="bi bi-arrow-right-short"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 5. Verify Students Card -->
        <div class="col-12 col-md-6">
            <a href="<?php echo $basePath; ?>/hod/students/verify" class="text-decoration-none">
                <div class="card premium-stat-card premium-card-amber">
                    <div class="premium-card-accent"></div>
                    <div class="d-flex align-items-center gap-3 position-relative z-1">
                        <div class="premium-card-icon premium-icon-amber">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="premium-card-count"><?php echo htmlspecialchars((string)($stats['pending_approvals'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($stats['pending_approvals'])): ?>
                                    <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Pending</span>
                                <?php endif; ?>
                            </div>
                            <div class="premium-card-label">Verify Students</div>
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

<!-- ── Combined Row: FYP Stage Progress (70%) + Department Notices (30%) ── -->
<div class="row g-3 mb-4 align-items-stretch">
    <!-- Left: FYP Stage Progress (70% on large screens) -->
    <div class="col-12 col-lg-8 d-flex">
        <div class="hod-section-card w-100 d-flex flex-column mb-0">
            <div class="hod-section-header">
                <div class="hod-section-title-wrap">
                    <div class="hod-section-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                        <i class="bi bi-filter-circle-fill"></i>
                    </div>
                    <div>
                        <h6 class="hod-section-title">FYP Stage Progress</h6>
                        <small class="hod-section-subtitle">Milestone breakdown across projects</small>
                    </div>
                </div>
                <div class="hod-section-actions">
                    <span class="badge rounded-pill fw-bold" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.25); padding: 5px 10px; font-size: 0.75rem;">
                        <span class="fw-bolder"><?php echo $totalProjectsInFunnel; ?></span> Projects
                    </span>
                    <a href="<?php echo $basePath; ?>/hod/projects" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                        <span>All Projects</span>
                        <i class="bi bi-arrow-right-short" style="font-size: 1.1rem; line-height: 1;"></i>
                    </a>
                </div>
            </div>

            <div class="hod-section-body flex-grow-1 d-flex flex-column justify-content-center">
                <div class="row g-2.5">
                    <!-- Stage 1 -->
                    <div class="col-6 col-sm-4">
                        <div class="funnel-step-card">
                            <div class="funnel-step-num" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">1</div>
                            <div class="funnel-step-count text-primary"><?php echo (int)($stages['Proposal Submitted'] ?? 0); ?></div>
                            <div class="funnel-step-title">Proposal Submitted</div>
                        </div>
                    </div>
                    <!-- Stage 2 -->
                    <div class="col-6 col-sm-4">
                        <div class="funnel-step-card">
                            <div class="funnel-step-num" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">2</div>
                            <div class="funnel-step-count text-success"><?php echo (int)($stages['Proposal Approved'] ?? 0); ?></div>
                            <div class="funnel-step-title">Proposal Approved</div>
                        </div>
                    </div>
                    <!-- Stage 3 -->
                    <div class="col-6 col-sm-4">
                        <div class="funnel-step-card">
                            <div class="funnel-step-num" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">3</div>
                            <div class="funnel-step-count" style="color: #8b5cf6;"><?php echo (int)($stages['Proposal Defence Presentation Completed'] ?? 0); ?></div>
                            <div class="funnel-step-title">Defense Cleared</div>
                        </div>
                    </div>
                    <!-- Stage 4 -->
                    <div class="col-6 col-sm-4">
                        <div class="funnel-step-card">
                            <div class="funnel-step-num" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">4</div>
                            <div class="funnel-step-count text-warning"><?php echo (int)($stages['FYP Progress Presentation Completed'] ?? 0); ?></div>
                            <div class="funnel-step-title">Progress Cleared</div>
                        </div>
                    </div>
                    <!-- Stage 5 -->
                    <div class="col-6 col-sm-4">
                        <div class="funnel-step-card">
                            <div class="funnel-step-num" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">5</div>
                            <div class="funnel-step-count" style="color: #ec4899;"><?php echo (int)($stages['Final Presentation Completed'] ?? 0); ?></div>
                            <div class="funnel-step-title">Final Presented</div>
                        </div>
                    </div>
                    <!-- Stage 6 -->
                    <div class="col-6 col-sm-4">
                        <div class="funnel-step-card">
                            <div class="funnel-step-num" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">6</div>
                            <div class="funnel-step-count text-info"><?php echo (int)($stages['Final Grading Completed'] ?? 0); ?></div>
                            <div class="funnel-step-title">Grading Complete</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Department Notices (30% on large screens) -->
    <div class="col-12 col-lg-4 d-flex">
        <div class="hod-section-card w-100 d-flex flex-column mb-0">
            <div class="hod-section-header">
                <div class="hod-section-title-wrap">
                    <div class="hod-section-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div>
                        <h6 class="hod-section-title">Department Notices</h6>
                        <small class="hod-section-subtitle">Recent circulars and alerts</small>
                    </div>
                </div>
                <div class="hod-section-actions">
                    <span class="badge rounded-pill fw-bold" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25); padding: 4px 9px; font-size: 0.72rem;">
                        <?php echo count($recentNotices); ?>
                    </span>
                </div>
            </div>

            <div class="hod-section-body flex-grow-1 p-3">
                <div class="notice-list custom-scroll" style="max-height: 280px; overflow-y: auto;">
                    <?php foreach($recentNotices as $n): ?>
                    <div class="notice-minimal-item" role="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>">
                        <div class="notice-accent-bar"></div>
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="notice-date-badge">
                                    <i class="bi bi-calendar3" style="font-size: 0.6rem;"></i>
                                    <?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                                </span>
                            </div>
                            <div class="text-truncate" style="font-size: 0.82rem; font-weight: 500; color: var(--text-primary);" title="<?php echo htmlspecialchars($n['subject'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($n['subject'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="notice-view-btn flex-shrink-0" onclick="event.stopPropagation();">
                            <span>View</span>
                            <i class="bi bi-arrow-up-right" style="font-size: 0.65rem;"></i>
                        </button>
                    </div>
                    <?php endforeach; ?>
                    <?php if(empty($recentNotices)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                        No notices found.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Supervisor Workload Matrix (Full Width) ── -->
<div class="hod-section-card mb-4">
    <div class="hod-section-header">
        <div class="hod-section-title-wrap">
            <div class="hod-section-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                <i class="bi bi-bar-chart-steps"></i>
            </div>
            <div>
                <h6 class="hod-section-title">Supervisor Workload</h6>
                <small class="hod-section-subtitle">Slot allocation &amp; capacity limits</small>
            </div>
        </div>
        <div class="hod-section-actions">
            <a href="<?php echo $basePath; ?>/hod/settings" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                <i class="bi bi-sliders"></i> <span>Capacity Settings</span>
            </a>
            <a href="<?php echo $basePath; ?>/hod/supervisors" class="btn btn-sm btn-primary rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #10b981, #059669); border: none; font-size: 0.8rem;">
                <i class="bi bi-person-badge-fill"></i> <span>Faculty Directory</span>
            </a>
        </div>
    </div>

    <div class="hod-section-body p-0">
        <div class="table-responsive">
            <table class="table modern-table m-0">
                <thead>
                    <tr>
                        <th class="ps-4">Supervisor</th>
                        <th>Designation</th>
                        <th>Morning</th>
                        <th>Evening</th>
                        <th>Total</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($supervisorsWorkload as $sup): ?>
                    <?php 
                        $mCount = (int)$sup['morning_projects'];
                        $eCount = (int)$sup['evening_projects'];
                        $tot = (int)$sup['total_projects'];
                        $maxTotal = $maxMorning + $maxEvening;
                        $percent = $maxTotal > 0 ? min(100, round(($tot / $maxTotal) * 100)) : 0;
                        
                        $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                        $badgeText = 'Available';
                        if ($mCount >= $maxMorning && $eCount >= $maxEvening) {
                            $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                            $badgeText = 'Full';
                        } elseif ($mCount >= $maxMorning || $eCount >= $maxEvening) {
                            $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                            $badgeText = 'Partial';
                        }
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="workload-user-avatar">
                                    <?php echo strtoupper(substr($sup['name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem"><?php echo htmlspecialchars($sup['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <small class="text-muted" style="font-size: 0.75rem"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($sup['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 0.75rem"><?php echo htmlspecialchars($sup['designation'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width: 140px;">
                                <span class="fw-bold text-dark" style="font-size: 0.82rem; width: 38px;"><?php echo $mCount; ?> / <?php echo (int)$maxMorning; ?></span>
                                <div class="progress workload-progress flex-grow-1">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $maxMorning > 0 ? min(100, round(($mCount / $maxMorning) * 100)) : 0; ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width: 140px;">
                                <span class="fw-bold text-dark" style="font-size: 0.82rem; width: 38px;"><?php echo $eCount; ?> / <?php echo (int)$maxEvening; ?></span>
                                <div class="progress workload-progress flex-grow-1">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $maxEvening > 0 ? min(100, round(($eCount / $maxEvening) * 100)) : 0; ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-primary" style="font-size: 0.95rem;"><?php echo $tot; ?></span> <small class="text-muted">Groups</small>
                        </td>
                        <td class="text-end pe-4">
                            <span class="badge <?php echo $badgeClass; ?> border rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                <?php echo $badgeText; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($supervisorsWorkload)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-people fs-4 d-block mb-1 opacity-50"></i>
                            No supervisors registered under this department yet.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Notice Modals -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
<?php foreach($recentNotices as $n): ?>
<div class="modal fade" id="noticeModal<?php echo $n['id']; ?>" tabindex="-1" aria-labelledby="noticeModalLabel<?php echo $n['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable notice-modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: var(--card-bg); color: var(--text-primary);">
            
            <div class="modal-header border-0 py-2.5 px-3.5 d-flex justify-content-between align-items-center" style="background: var(--form-bg); border-bottom: 1px solid var(--border-color) !important;">
                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1" style="font-size: 0.75rem;">Official Notice</span>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 p-md-5">
                <div class="mb-4 text-center">
                    <h5 class="fw-bold mb-1" style="color: var(--text-primary);"><?php echo htmlspecialchars($n['subject'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    <small class="text-muted">Issued on <?php echo date('F d, Y', strtotime($n['notice_date'])); ?></small>
                </div>
                <div class="p-3.5 rounded-3" style="background: var(--form-bg); border: 1px solid var(--border-color); color: var(--text-primary); white-space: pre-line; line-height: 1.6; font-size: 0.92rem;">
                    <?php echo htmlspecialchars($n['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
            <div class="modal-footer border-0 py-2.5 px-3.5" style="background: var(--form-bg); border-top: 1px solid var(--border-color) !important;">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3.5" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
