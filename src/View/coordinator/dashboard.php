<style>
/* Add hover effect for horizontal action cards */
.modern-table-card[onclick]:hover {
    border-color: #10b981 !important;
    box-shadow: 0 8px 24px rgba(16,185,129,0.15) !important;
}
html.dark-theme .text-dark {
    color: #f8fafc !important;
}

#avatarPreviewModal .modal-header {
    border: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

.project-title-cell {
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    font-size: 0.9rem;
}

.group-code-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(16,185,129,0.1);
    color: #10b981;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 8px;
    letter-spacing: 0.02em;
}

.avatar-stack img, .avatar-stack .rounded-circle {
    cursor: pointer !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.avatar-stack img {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid var(--card-bg);
    margin-left: -10px;
}
.avatar-stack img:first-child {
    margin-left: 0;
}
.avatar-stack img:hover, .avatar-stack .rounded-circle:hover {
    transform: scale(1.18) translateY(-2px);
    z-index: 20;
    box-shadow: 0 4px 12px rgba(0,0,0,0.18) !important;
}

.action-btn {
    padding: 6px 14px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
}
.action-btn:hover {
    background: rgba(16,185,129,0.1);
    color: #10b981;
    border-color: rgba(16,185,129,0.2);
}
.action-btn.review {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border-color: rgba(139, 92, 246, 0.2);
}
.action-btn.review:hover {
    background: #8b5cf6;
    color: #fff;
    border-color: #8b5cf6;
}

/* ── Modern Table overrides ── */
.modern-table-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}
.modern-table-card th {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 14px 20px;
}
.modern-table-card td {
    padding: 16px 20px;
    vertical-align: middle;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--border-color);
}
.modern-table-card tr:last-child td {
    border-bottom: none;
}

/* ── Mobile Specific Overrides ── */
@media (max-width: 767.98px) {
    .dash-banner {
        padding: 24px 20px;
        border-radius: 20px;
    }
    .page-section-header {
        flex-wrap: wrap !important;
        gap: 12px !important;
    }
    .page-section-header .section-header-left {
        min-width: 0;
        flex: 1 1 100% !important;
    }
    .page-section-header .section-header-actions {
        flex: 1 1 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 8px !important;
        margin-top: 4px;
    }
    .view-all-btn {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        padding: 5px 12px !important;
        font-size: 0.75rem !important;
    }
    .awaiting-badge {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        padding: 5px 10px !important;
        font-size: 0.72rem !important;
    }
}
@media (min-width: 768px) {
    .page-section-header .section-header-left {
        flex: 1 1 auto;
    }
    .page-section-header .section-header-actions {
        flex: 0 0 auto;
    }
}
</style>
<!-- Coordinator Dashboard View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
$urlPrefix = $bp;
$basePath = $bp;
$fullName = trim($_SESSION['name'] ?? 'Coordinator');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];

$pendingProposals = $pendingProposals ?? [];
$supervisors = $supervisors ?? [];
?>

<!-- ── Top Hero Banner ── -->
<div class="page-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    Department Coordinator
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    Welcome back, <?php echo htmlspecialchars($firstName); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 justify-content-center justify-content-md-start flex-wrap">
                    <span style="font-size: 0.75rem;background: rgba(255,255,255,0.1);color: rgba(255,255,255,0.8);padding: 4px 12px;border-radius: 20px;font-weight: 600">
                        <?php echo htmlspecialchars($department); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Premium Stat Cards Row ── -->
<div class="row g-3 mb-4">

    <!-- Pending Proposals Card -->
    <div class="col-xl-4 col-md-6">
        <a href="<?php echo $bp; ?>/coordinator/proposals" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-purple">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-purple">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['pending_proposals'] ?? count($pendingProposals)), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Pending Proposals</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Verify Students Card -->
    <div class="col-xl-4 col-md-6">
        <a href="<?php echo $bp; ?>/coordinator/users" class="text-decoration-none">
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

    <!-- Verify Meetings Card -->
    <div class="col-xl-4 col-md-6">
        <a href="<?php echo $bp; ?>/coordinator/meetings" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-green">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-green">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['pending_meetings'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Verify Meetings</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Notices Generated Card -->
    <div class="col-xl-6 col-md-6">
        <a href="<?php echo $bp; ?>/coordinator/notice" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-rose">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-rose">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['total_notices'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Notices Generated</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- External Assessment Card -->
    <div class="col-xl-6 col-md-12">
        <a href="<?php echo $bp; ?>/coordinator/assessment" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-blue">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-blue" style="width: 50px; height: 50px; font-size: 1.3rem;">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">External Assessment</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">Generate dynamic grading sheets</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ── Main Content: Pending Proposals Table ── -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 p-3 p-md-4 mb-4" style="border-radius: 16px; background: var(--card-bg); box-shadow: var(--card-shadow)">
            <div class="page-section-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div class="d-flex align-items-center gap-3 section-header-left">
                    <div class="page-section-icon flex-shrink-0" style="background: rgba(13, 148, 136, 0.1); color: #0d9488;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div style="min-width: 0;">
                        <h6 class="fw-bold m-0" style="color: var(--text-primary); line-height: 1.3;">Pending &amp; Unverified Proposals</h6>
                        <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">Review, endorse, and finalize departmental submissions</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 section-header-actions ms-auto ms-sm-0 flex-shrink-0">
                    <span class="badge rounded-pill fw-bold shadow-sm awaiting-badge" style="font-size: 0.78rem; background: rgba(13, 148, 136, 0.12); color: #0d9488; border: 1px solid rgba(13, 148, 136, 0.25); padding: 6px 12px; white-space: nowrap;">
                        <span class="fw-bolder"><?php echo count($pendingProposals); ?></span> Awaiting Review
                    </span>
                    <a href="<?php echo $bp; ?>/coordinator/proposals" class="btn btn-outline-primary btn-sm rounded-pill view-all-btn text-nowrap flex-shrink-0" style="font-size: 0.75rem; font-weight: 600; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;">
                        <span>View All</span> <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <?php if(empty($pendingProposals)): ?>
                <div class="text-center py-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-check2-circle fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: var(--text-primary);">All Caught Up!</h5>
                    <p class="text-muted mb-0" style="font-size: 0.875rem; max-width: 400px; margin: 0 auto;">There are no unverified proposals pending review in your department.</p>
                </div>
            <?php else: ?>
                <!-- Desktop Table View -->
                <div class="d-none d-md-block table-responsive custom-table-scroll" style="max-height: 480px; overflow-y: auto;">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Group Code</th>
                                <th>Project Title</th>
                                <th>Supervisor</th>
                                <th>Team Members</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pendingProposals as $pr): 
                                $statusMap = [
                                    'Approved' => ['rgba(5,150,105,0.1)', '#059669'],
                                    'Supervisor Approved' => ['rgba(13,148,136,0.12)', '#0d9488'],
                                    'Submitted' => ['rgba(245,158,11,0.1)', '#d97706'],
                                    'Under Review' => ['rgba(59,130,246,0.1)', '#2563eb'],
                                    'Revision Requested' => ['rgba(139,92,246,0.1)', '#8b5cf6'],
                                    'Rejected' => ['rgba(220,38,38,0.1)', '#dc2626']
                                ];
                                $st = $pr['status'];
                                $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                                $color = $statusMap[$st][1] ?? '#6b7280';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="group-code-badge">
                                        <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="project-title-cell text-truncate" title="<?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?>">
                                        <?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?>
                                    </div>
                                    <?php if($pr['file_path']): ?>
                                        <?php $ext = strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)); ?>
                                        <?php if($ext === 'pdf'): ?>
                                            <span role="button" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem; cursor: pointer; color: #10b981;" data-bs-toggle="offcanvas" data-bs-target="#pdfOffcanvas<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="bi bi-layout-sidebar-reverse me-1"></i>View PDF
                                            </span>
                                        <?php else: ?>
                                            <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                                <i class="bi bi-file-earmark-arrow-down-fill me-1"></i>Download Document
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem">
                                        <?php echo htmlspecialchars($pr['supervisor_name'] ?? 'Not Assigned'); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-stack">
                                            <?php 
                                            $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                            foreach(array_slice($pr['members'], 0, 4) as $idx => $m): 
                                                $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg';
                                                $avatarPath = $basePath . '/uploads/avatars/' . $avatarFile;
                                                $initials = strtoupper(substr($m['student_name'] ?? 'U', 0, 1));
                                                $colClass = $colors[$idx % count($colors)];
                                            ?>
                                                <?php if (!empty($m['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                                                         title="<?php echo htmlspecialchars($m['student_name']); ?> (Click to view)"
                                                         alt="Avatar"
                                                         onclick="showAvatarPopup('<?php echo htmlspecialchars($avatarPath); ?>', '<?php echo htmlspecialchars(addslashes($m['student_name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                                <?php else: ?>
                                                    <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-white <?php echo $colClass; ?>" 
                                                         style="width: 32px; height: 32px; margin-left: <?php echo $idx > 0 ? '-10px' : '0'; ?>; font-size: 0.75rem; font-weight: 600; flex-shrink: 0;"
                                                         title="<?php echo htmlspecialchars($m['student_name']); ?> (Click to view)"
                                                         onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['student_name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                                        <?php echo $initials; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if(count($pr['members']) > 4): ?>
                                            <span class="text-muted small fw-semibold">+<?php echo count($pr['members']) - 4; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span style="background: <?php echo $bg;?>; color: <?php echo $color;?>; font-weight: 600; font-size: 0.72rem; padding: 5px 12px; border-radius: 20px; display: inline-flex; align-items: center; white-space: nowrap;">
                                        <?php echo htmlspecialchars($st); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#proposalDetailsModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-info-circle-fill"></i> <span>Details</span>
                                        </button>
                                        <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#reviewProposalModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-clipboard-check-fill"></i> <span>Review</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List View -->
                <div class="d-block d-md-none mt-3">
                    <?php foreach($pendingProposals as $pr): 
                        $statusMap = [
                            'Approved' => ['rgba(5,150,105,0.1)', '#059669'],
                            'Supervisor Approved' => ['rgba(13,148,136,0.12)', '#0d9488'],
                            'Submitted' => ['rgba(245,158,11,0.1)', '#d97706'],
                            'Under Review' => ['rgba(59,130,246,0.1)', '#2563eb'],
                            'Revision Requested' => ['rgba(139,92,246,0.1)', '#8b5cf6'],
                            'Rejected' => ['rgba(220,38,38,0.1)', '#dc2626']
                        ];
                        $st = $pr['status'];
                        $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                        $color = $statusMap[$st][1] ?? '#6b7280';
                    ?>
                        <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg)">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span class="group-code-badge" style="font-size: 0.75rem;">
                                    <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?>
                                </span>
                                <span style="background: <?php echo $bg;?>; color: <?php echo $color;?>; font-weight: 600; font-size: 0.7rem; padding: 3px 8px; border-radius: 20px;">
                                    <?php echo htmlspecialchars($st); ?>
                                </span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: var(--text-primary) !important;">
                                <?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?>
                            </h6>
                            <div class="text-muted small mb-2">
                                Supervisor: <strong><?php echo htmlspecialchars($pr['supervisor_name'] ?? 'Not Assigned'); ?></strong>
                            </div>
                            <?php if($pr['file_path']): ?>
                                <?php $ext = strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)); ?>
                                <div class="mb-2">
                                    <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="small text-decoration-none fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                        <i class="bi <?php echo ($ext === 'pdf') ? 'bi-box-arrow-up-right' : 'bi-file-earmark-arrow-down-fill'; ?> me-1"></i> <?php echo ($ext === 'pdf') ? 'View PDF' : 'Download'; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="avatar-stack">
                                    <?php 
                                    $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                    foreach(array_slice($pr['members'], 0, 4) as $idx => $m): 
                                        $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg';
                                        $avatarPath = $basePath . '/uploads/avatars/' . $avatarFile;
                                        $initials = strtoupper(substr($m['student_name'] ?? 'U', 0, 1));
                                        $colClass = $colors[$idx % count($colors)];
                                    ?>
                                        <?php if (!empty($m['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                            <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                                                 title="<?php echo htmlspecialchars($m['student_name']); ?> (Click to view)"
                                                 alt="Avatar" style="width: 24px; height: 24px;"
                                                 onclick="showAvatarPopup('<?php echo htmlspecialchars($avatarPath); ?>', '<?php echo htmlspecialchars(addslashes($m['student_name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                        <?php else: ?>
                                            <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-white <?php echo $colClass; ?>" 
                                                 style="width: 24px; height: 24px; margin-left: <?php echo $idx > 0 ? '-8px' : '0'; ?>; font-size: 0.65rem; font-weight: 600;"
                                                 title="<?php echo htmlspecialchars($m['student_name']); ?> (Click to view)"
                                                 onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['student_name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                                <?php echo $initials; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php if(count($pr['members']) > 4): ?>
                                    <span class="text-muted small fw-semibold" style="font-size: 0.7rem;">+<?php echo count($pr['members']) - 4; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-2 pt-3 border-top" style="border-color: var(--border-color) !important; gap: 8px;">
                                <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#proposalDetailsModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                    <i class="bi bi-info-circle-fill"></i> Details
                                </button>
                                <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#reviewProposalModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                    <i class="bi bi-clipboard-check-fill"></i> Review
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals rendered outside the table to prevent z-index/backdrop issues -->
<?php foreach($pendingProposals as $pr): 
    $statusMap = [
        'Approved' => ['rgba(5,150,105,0.1)', '#059669'],
        'Supervisor Approved' => ['rgba(13,148,136,0.12)', '#0d9488'],
        'Submitted' => ['rgba(245,158,11,0.1)', '#d97706'],
        'Under Review' => ['rgba(59,130,246,0.1)', '#2563eb'],
        'Revision Requested' => ['rgba(139,92,246,0.1)', '#8b5cf6'],
        'Rejected' => ['rgba(220,38,38,0.1)', '#dc2626']
    ];
    $st = $pr['status'];
    $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
    $color = $statusMap[$st][1] ?? '#6b7280';
?>
<!-- DETAILS MODAL -->
<div class="modal fade" id="proposalDetailsModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold">Proposal Details - <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary)"><?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?></h5>
                    <div class="text-muted small fw-semibold mb-3">Supervisor: <?php echo htmlspecialchars($pr['supervisor_name'] ?? 'Not Assigned'); ?></div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge" style="background: <?php echo $bg;?>; color: <?php echo $color;?>; font-weight: 600; padding: 6px 12px; border-radius: 20px">
                            Status: <?php echo htmlspecialchars($st); ?>
                        </span>
                        <?php if (!empty($pr['thesis_file'])): ?>
                            <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="viewThesisOffcanvas('<?php echo htmlspecialchars($pr['thesis_file']); ?>')">
                                <i class="bi bi-file-earmark-pdf-fill me-2"></i>View Thesis
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-2" style="letter-spacing: 0.04em">Project Abstract</label>
                    <div class="p-3 rounded-3 text-muted" style="background: var(--form-bg); border: 1px solid var(--border-color); font-size: 0.85rem; line-height: 1.65; text-align: justify; max-height: 250px; overflow-y: auto">
                        <?php echo nl2br(htmlspecialchars($pr['abstract'])); ?>
                    </div>
                </div>

                <div>
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-3" style="letter-spacing: 0.04em">Team Members</label>
                    <div class="row g-3">
                        <?php foreach($pr['members'] as $m): 
                            $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; 
                            $avatarPath = $basePath . '/uploads/avatars/' . $avatarFile;
                            $initials = strtoupper(substr($m['student_name'] ?? 'U', 0, 1));
                        ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="border: 1px solid var(--border-color); background: var(--card-bg)">
                                <?php if (!empty($m['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                                         class="rounded-circle me-3 border border-2 border-white shadow-sm" 
                                         style="width: 48px; height: 48px; object-fit: cover; cursor: pointer;" 
                                         alt="Avatar"
                                         title="Click to view photo"
                                         onclick="showAvatarPopup('<?php echo htmlspecialchars($avatarPath); ?>', '<?php echo htmlspecialchars(addslashes($m['student_name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no'] ?? '')); ?>', '<?php echo $initials; ?>', 'bg-primary');">
                                <?php else: ?>
                                    <div class="rounded-circle me-3 border border-2 border-white shadow-sm d-flex align-items-center justify-content-center bg-primary text-white fw-bold" 
                                         style="width: 48px; height: 48px; font-size: 1.1rem; flex-shrink: 0; cursor: pointer;" 
                                         title="Click to view details" 
                                         onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['student_name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no'] ?? '')); ?>', '<?php echo $initials; ?>', 'bg-primary');">
                                        <?php echo $initials; ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem; color: var(--text-primary)">
                                        <?php echo htmlspecialchars($m['student_name']); ?>
                                    </div>
                                    <div class="text-muted font-monospace" style="font-size: 0.75rem"><?php echo htmlspecialchars($m['roll_no']); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg)">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color)">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if($pr['file_path'] && strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)) === 'pdf'): ?>
<!-- PDF Offcanvas (Right Side) -->
<div class="offcanvas offcanvas-end shadow-lg border-start-0" tabindex="-1" id="pdfOffcanvas<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" style="width: 75vw; max-width: 1400px; min-width: 320px; z-index: 1060; background: var(--main-bg);">
  <div class="offcanvas-header px-4 py-3" style="background: var(--card-bg); border-bottom: 1px solid var(--border-color);">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; background: rgba(13,148,136,0.1); color: #0d9488;">
            <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
        </div>
        <div>
            <h6 class="offcanvas-title fw-bold mb-0" style="color: var(--text-primary); font-size: 1.1rem; letter-spacing: -0.01em;">Proposal Document</h6>
            <small class="text-muted"><?php echo htmlspecialchars($pr['project_title'] ?? 'Document Preview'); ?></small>
        </div>
    </div>
    <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0" style="background: #e5e7eb;">
    <iframe src="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" width="100%" height="100%" style="border: none; width: 100%; height: 100%;"></iframe>
  </div>
</div>
<?php endif; ?>

<!-- REVIEW PROPOSAL MODAL (COORDINATOR) -->
<div class="modal fade" id="reviewProposalModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <form action="<?php echo $urlPrefix; ?>/coordinator/proposals/review" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="proposal_id" value="<?php echo htmlspecialchars((string)$pr['id']); ?>">
                
                <div class="modal-header border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                            <i class="bi bi-clipboard-check-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold m-0" style="color: var(--text-primary);">Coordinator Proposal Review</h6>
                            <small class="text-muted">Evaluate, set status, auto-assign group code, or re-assign supervisor</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <!-- Project Title & Group Code Header -->
                    <div class="p-3 rounded-3 mb-4" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                            <span class="group-code-badge">
                                <?php echo htmlspecialchars($pr['group_code'] ?? 'Group Code Pending'); ?>
                            </span>
                            <span class="badge" style="background: <?php echo $bg;?>; color: <?php echo $color;?>; font-weight: 600; font-size: 0.72rem; padding: 5px 12px; border-radius: 20px;">
                                Current Status: <?php echo htmlspecialchars($st); ?>
                            </span>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--text-primary);"><?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?></h6>
                    </div>

                    <div class="row g-3">
                        <!-- Decision Status -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Proposal Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Approved" <?php echo $pr['status'] === 'Approved' ? 'selected' : ''; ?>>Approved (Final Approval &amp; Group Code)</option>
                                <option value="Supervisor Approved" <?php echo $pr['status'] === 'Supervisor Approved' ? 'selected' : ''; ?>>Supervisor Approved (Pending Coordinator)</option>
                                <option value="Submitted" <?php echo $pr['status'] === 'Submitted' ? 'selected' : ''; ?>>Submitted (Under Review)</option>
                                <option value="Revision Requested" <?php echo $pr['status'] === 'Revision Requested' ? 'selected' : ''; ?>>Revision Requested</option>
                                <option value="Rejected" <?php echo $pr['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>

                        <!-- Supervisor Assignment -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Assigned Supervisor</label>
                            <select name="supervisor_id" class="form-select">
                                <option value="">-- Keep Current / No Change --</option>
                                <?php if(!empty($supervisors)): ?>
                                    <?php foreach($supervisors as $sup): ?>
                                        <option value="<?php echo $sup['user_id']; ?>" <?php echo ($pr['supervisor_id'] == $sup['user_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sup['name'] . (!empty($sup['designation']) ? ' (' . $sup['designation'] . ')' : '')); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Coordinator Feedback / Remarks -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Coordinator Remarks &amp; Feedback</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Provide notes, suggestions, or conditions for the student group and supervisor..."><?php echo htmlspecialchars($pr['feedback'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-3 px-4 rounded-bottom-4 d-flex justify-content-between align-items-center" style="background: var(--card-bg)">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color)">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Save Decision
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- AVATAR ENLARGED POPUP MODAL -->
<div class="modal fade" id="avatarPreviewModal" tabindex="-1" aria-hidden="true" style="z-index: 1070">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: var(--card-bg);">
            <div class="modal-header border-0 border-bottom-0 p-3 pb-0 d-flex justify-content-end" style="border: none !important; border-bottom: none !important;">
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4 pt-1">
                <div class="position-relative d-inline-block mb-3">
                    <div class="rounded-circle p-1" style="background: linear-gradient(135deg, #10b981, #0d9488);">
                        <img id="avatarPreviewImg" src="" alt="Student Photo" class="rounded-circle shadow-lg border border-4 border-white d-none" style="width: 170px; height: 170px; object-fit: cover;">
                        <div id="avatarPreviewInitials" class="rounded-circle shadow-lg border border-4 border-white d-none align-items-center justify-content-center text-white fw-bold" style="width: 170px; height: 170px; font-size: 4.5rem;">
                            U
                        </div>
                    </div>
                </div>
                <h5 id="avatarPreviewName" class="fw-bold mb-1" style="color: var(--text-primary);">Student Name</h5>
                <div id="avatarPreviewRoll" class="badge bg-light text-dark font-monospace mb-1" style="font-size: 0.85rem; border: 1px solid var(--border-color);">Roll No</div>
            </div>
        </div>
    </div>
</div>

<script>
    function showAvatarPopup(imgSrc, name, rollNo, initials, colorClass) {
        const imgEl = document.getElementById('avatarPreviewImg');
        const initEl = document.getElementById('avatarPreviewInitials');
        const nameEl = document.getElementById('avatarPreviewName');
        const rollEl = document.getElementById('avatarPreviewRoll');
        
        if (nameEl) nameEl.textContent = name || 'Student';
        if (rollEl) {
            rollEl.textContent = rollNo || '';
            rollEl.style.display = rollNo ? 'inline-block' : 'none';
        }
        
        if (imgSrc && imgSrc.trim() !== '') {
            imgEl.src = imgSrc;
            imgEl.classList.remove('d-none');
            initEl.classList.add('d-none');
            initEl.classList.remove('d-flex');
        } else {
            imgEl.classList.add('d-none');
            initEl.classList.remove('d-none');
            initEl.classList.add('d-flex');
            initEl.textContent = initials || (name ? name.charAt(0).toUpperCase() : 'U');
            initEl.className = 'rounded-circle shadow-lg border border-4 border-white d-flex align-items-center justify-content-center text-white fw-bold ' + (colorClass || 'bg-primary');
        }
        
        const modalEl = document.getElementById('avatarPreviewModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) {
            modal = new bootstrap.Modal(modalEl);
        }
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Move all modals and offcanvas elements to the body to prevent z-index/stacking context issues
        const overlays = document.querySelectorAll('.modal, .offcanvas');
        overlays.forEach(el => {
            document.body.appendChild(el);
        });
    });
</script>

<?php include __DIR__ . '/../shared/thesis_offcanvas.php'; ?>






