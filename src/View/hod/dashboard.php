<!-- HOD Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
.notice-minimal-item {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.notice-minimal-item:hover {
    background: var(--card-bg);
    border-color: rgba(16, 185, 129, 0.3);
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
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.02em;
}
.notice-view-btn {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 5px 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
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
    padding-right: 8px;
    padding-left: 2px;
    padding-top: 2px;
    padding-bottom: 2px;
}
.notice-list::-webkit-scrollbar {
    width: 5px;
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

/* Funnel Step Cards */
.funnel-step-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 16px 14px;
    text-align: center;
    position: relative;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}
.funnel-step-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    border-color: rgba(59, 130, 246, 0.3);
}
.funnel-step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
}
.funnel-step-count {
    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1.1;
    color: var(--text-primary);
    margin-bottom: 4px;
}
.funnel-step-title {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-secondary);
    line-height: 1.2;
}

/* Progress bar inside table */
.workload-progress {
    height: 8px;
    border-radius: 8px;
    background-color: var(--form-bg);
    overflow: hidden;
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.45rem;letter-spacing: -0.02em">HOD Department Command Center</h4>
                    <span class="badge rounded-pill bg-white bg-opacity-20 text-white px-3 py-1 fw-semibold" style="font-size: 0.8rem">
                        <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75);font-size: 0.85rem">Live academic tracking, supervisor workload balancing, and faculty management</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo $basePath; ?>/hod/projects" class="btn btn-light rounded-pill px-3.5 py-2 fw-semibold shadow-sm border-0 d-inline-flex align-items-center gap-2" style="color: #047fb0; font-size: 0.85rem;">
                <i class="bi bi-kanban-fill"></i> <span>View All Projects</span>
            </a>
            <a href="<?php echo $basePath; ?>/hod/supervisors" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="bi bi-person-plus-fill"></i> <span>Manage Faculty</span>
            </a>
        </div>
    </div>
</div>

<!-- ── Stat Cards Row ── -->
<div class="row g-3 mb-4">
    <!-- Groups/Projects Card -->
    <div class="col-xl col-md-4 col-sm-6">
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

    <!-- Supervisors Card -->
    <div class="col-xl col-md-4 col-sm-6">
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

    <!-- Committee Card -->
    <div class="col-xl col-md-4 col-sm-6">
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

    <!-- Coordinator Card -->
    <div class="col-xl col-md-6 col-sm-6">
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

    <!-- Verify Students Card -->
    <div class="col-xl col-md-6 col-sm-12">
        <a href="<?php echo $basePath; ?>/hod/students/verify" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-amber">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-amber">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['pending_approvals'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
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

<!-- ── Department FYP Progress Funnel ── -->
<div class="card border-0 p-3 p-md-4 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div class="page-section-header m-0 p-0 border-0">
            <div class="page-section-icon" style="background: rgba(59, 130, 246, 0.1);color: #3b82f6">
                <i class="bi bi-filter-circle-fill"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Live FYP Stage Progress Funnel</h6>
                <small class="text-muted">Real-time breakdown of all departmental project batches</small>
            </div>
        </div>
        <a href="<?php echo $basePath; ?>/hod/projects" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5">
            <span>Explore All Projects</span>
            <i class="bi bi-arrow-right-short" style="font-size: 1.1rem;"></i>
        </a>
    </div>

    <div class="row g-2 g-md-3">
        <!-- Stage 1 -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="funnel-step-card">
                <div class="funnel-step-num">1</div>
                <div class="funnel-step-count text-primary"><?php echo $stages['Proposal Submitted'] ?? 0; ?></div>
                <div class="funnel-step-title">Proposal Submitted</div>
            </div>
        </div>
        <!-- Stage 2 -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="funnel-step-card">
                <div class="funnel-step-num" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">2</div>
                <div class="funnel-step-count text-success"><?php echo $stages['Proposal Approved'] ?? 0; ?></div>
                <div class="funnel-step-title">Proposal Approved</div>
            </div>
        </div>
        <!-- Stage 3 -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="funnel-step-card">
                <div class="funnel-step-num" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">3</div>
                <div class="funnel-step-count" style="color: #8b5cf6;"><?php echo $stages['Proposal Defence Presentation Completed'] ?? 0; ?></div>
                <div class="funnel-step-title">Defense Cleared</div>
            </div>
        </div>
        <!-- Stage 4 -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="funnel-step-card">
                <div class="funnel-step-num" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">4</div>
                <div class="funnel-step-count text-warning"><?php echo $stages['FYP Progress Presentation Completed'] ?? 0; ?></div>
                <div class="funnel-step-title">Progress Cleared</div>
            </div>
        </div>
        <!-- Stage 5 -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="funnel-step-card">
                <div class="funnel-step-num" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">5</div>
                <div class="funnel-step-count" style="color: #ec4899;"><?php echo $stages['Final Presentation Completed'] ?? 0; ?></div>
                <div class="funnel-step-title">Final Presented</div>
            </div>
        </div>
        <!-- Stage 6 -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="funnel-step-card">
                <div class="funnel-step-num" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">6</div>
                <div class="funnel-step-count text-info"><?php echo $stages['Final Grading Completed'] ?? 0; ?></div>
                <div class="funnel-step-title">Grading Complete</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Supervisor Workload & Capacity Matrix ── -->
<div class="card border-0 p-3 p-md-4 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div class="page-section-header m-0 p-0 border-0">
            <div class="page-section-icon" style="background: rgba(16, 185, 129, 0.1);color: #10b981">
                <i class="bi bi-bar-chart-steps"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Supervisor Workload & Capacity Balancing</h6>
                <small class="text-muted">Monitor allocated project slots (Max: <?php echo $maxMorning; ?> Morning / <?php echo $maxEvening; ?> Evening)</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo $basePath; ?>/hod/settings" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-sliders"></i> <span>Capacity Settings</span>
            </a>
            <a href="<?php echo $basePath; ?>/hod/supervisors" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                <i class="bi bi-person-badge-fill"></i> <span>Faculty Directory</span>
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0">
            <thead>
                <tr>
                    <th class="ps-3">Supervisor Name</th>
                    <th>Designation</th>
                    <th>Morning Shift Load</th>
                    <th>Evening Shift Load</th>
                    <th>Total Projects</th>
                    <th class="text-end pe-3">Capacity Status</th>
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
                        $badgeText = 'Fully Booked';
                    } elseif ($mCount >= $maxMorning || $eCount >= $maxEvening) {
                        $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                        $badgeText = 'Partially Full';
                    }
                ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 36px;height: 36px;font-size: 0.9rem">
                                <?php echo strtoupper(substr($sup['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.9rem"><?php echo htmlspecialchars($sup['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted" style="font-size: 0.75rem"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($sup['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem"><?php echo htmlspecialchars($sup['designation'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2" style="min-width: 130px;">
                            <span class="fw-bold text-dark" style="font-size: 0.85rem; width: 35px;"><?php echo $mCount; ?> / <?php echo $maxMorning; ?></span>
                            <div class="progress workload-progress flex-grow-1">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $maxMorning > 0 ? min(100, round(($mCount / $maxMorning) * 100)) : 0; ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2" style="min-width: 130px;">
                            <span class="fw-bold text-dark" style="font-size: 0.85rem; width: 35px;"><?php echo $eCount; ?> / <?php echo $maxEvening; ?></span>
                            <div class="progress workload-progress flex-grow-1">
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $maxEvening > 0 ? min(100, round(($eCount / $maxEvening) * 100)) : 0; ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-bold text-primary" style="font-size: 0.95rem;"><?php echo $tot; ?></span> <small class="text-muted">Groups</small>
                    </td>
                    <td class="text-end pe-3">
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

<!-- ── Recent Notices ── -->
<div class="card border-0 p-3 p-md-4 mb-4">
    <div class="page-section-header mb-4">
        <div class="page-section-icon" style="background: rgba(59, 130, 246, 0.1);color: #3b82f6">
            <i class="bi bi-megaphone-fill"></i>
        </div>
        <div>
            <h6 class="mb-0 fw-bold">Department Notices & Announcements</h6>
            <small class="text-muted">Latest circulars and university updates</small>
        </div>
    </div>

    <div class="notice-list custom-scroll" style="max-height: 280px; overflow-y: auto;">
        <?php foreach($recentNotices as $n): ?>
        <div class="notice-minimal-item" role="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>">
            <div class="notice-accent-bar"></div>
            <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="notice-date-badge">
                        <i class="bi bi-calendar3" style="font-size: 0.62rem;"></i>
                        <?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                    </span>
                </div>
                <div class="text-truncate" style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);" title="<?php echo htmlspecialchars($n['subject'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($n['subject'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="notice-view-btn flex-shrink-0" onclick="event.stopPropagation();">
                <span>View</span>
                <i class="bi bi-arrow-up-right" style="font-size: 0.7rem;"></i>
            </button>
        </div>
        <?php endforeach; ?>
        <?php if(empty($recentNotices)): ?>
        <div class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
            No recent notices found.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Notice Modals -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
<?php foreach($recentNotices as $n): ?>
<div class="modal fade" id="noticeModal<?php echo $n['id']; ?>" tabindex="-1" aria-labelledby="noticeModalLabel<?php echo $n['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable notice-modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
            
            <div class="modal-header border-0 bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 0.75rem;">Official Notice</span>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 p-md-5">
                <div class="mb-4 text-center">
                    <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($n['subject'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    <small class="text-muted">Issued on <?php echo date('F d, Y', strtotime($n['notice_date'])); ?></small>
                </div>
                <div class="p-3 bg-light rounded-3 text-dark" style="white-space: pre-line; line-height: 1.6; font-size: 0.92rem;">
                    <?php echo htmlspecialchars($n['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
