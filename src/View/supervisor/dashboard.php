<!-- Supervisor Dashboard View -->
<style>
.project-title-cell {
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    font-size: 0.9rem;
    max-width: 380px;
}

.avatar-stack img, .avatar-stack .rounded-circle, .member-avatar-click {
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
.avatar-stack img:hover, .avatar-stack .rounded-circle:hover, .member-avatar-click:hover {
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
.action-btn.review:hover {
    background: rgba(13,148,136,0.1);
    color: #0d9488;
    border-color: rgba(13,148,136,0.2);
}

.notice-minimal-item {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 10px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: block;
}
.notice-minimal-item:hover {
    background: var(--card-bg);
    border-color: rgba(16, 185, 129, 0.35);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
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
.notice-arrow-icon {
    font-size: 0.72rem;
    color: var(--text-secondary);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.notice-minimal-item:hover .notice-arrow-icon {
    background: #10b981;
    color: #fff;
    border-color: #10b981;
    transform: translate(2px, -2px);
}
.notice-subject-text {
    font-size: 0.8rem;
    font-weight: 400;
    color: var(--text-primary);
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-top: 6px;
}

.notice-list {
    padding-right: 6px;
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
</style>
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$fullName = trim($_SESSION['name'] ?? 'Supervisor');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>



<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
        <div class="page-hero-icon">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div>
            <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                Welcome Back
            </p>
            <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                <?php echo htmlspecialchars($fullName); ?>
            </h4>
            <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7);font-size: 0.85rem">Manage your assigned groups and track their progress</p>
        </div>
    </div>
</div>

<!-- -- Premium Stat Cards Row -- -->
<div class="row g-3 mb-4">
    <!-- Assigned Groups Card -->
    <div class="col-xl-4 col-md-6">
        <a href="<?php echo $basePath; ?>/supervisor/groups" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-amber">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-amber">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($groupCount), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">FYP Groups</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Review Proposals Card -->
    <div class="col-xl-4 col-md-6">
        <a href="#pending-proposals" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-blue">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-blue">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($pendingProposals), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Review Proposals</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Meetings Card -->
    <div class="col-xl-4 col-md-12">
        <a href="<?php echo $basePath; ?>/supervisor/meetings" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-purple">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-purple">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($meetingsCount ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Scheduled Meetings</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- -- Main Content Grid (Pending Proposals & Notices) -- -->
<div class="row g-4 mb-4">
    <!-- -- Recent Notices (25%) -- -->
    <div class="col-lg-3 col-xl-3">
        <div class="card border-0 p-3 p-xl-3 h-100" style="border-radius: 16px; background: var(--card-bg); box-shadow: var(--card-shadow)">
            <div class="page-section-header mb-3 position-relative">
                <div class="d-flex align-items-center gap-2">
                    <div class="page-section-icon" style="background: rgba(59, 130, 246, 0.1);color: #3b82f6; width: 34px; height: 34px; font-size: 0.9rem;">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0" style="font-size: 0.92rem;">Recent Notices</h6>
                        <small style="font-size: 0.72rem;">Latest updates</small>
                    </div>
                </div>
            </div>
            
            <div class="notice-list custom-scroll" style="max-height: 380px; overflow-y: auto;">
                <?php foreach($recentNotices as $n): ?>
                <div class="notice-minimal-item" role="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>">
                    <div class="notice-accent-bar"></div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="notice-date-badge">
                            <i class="bi bi-calendar3" style="font-size: 0.62rem;"></i>
                            <?php echo date('M d', strtotime($n['notice_date'])); ?>
                        </span>
                        <span class="notice-arrow-icon">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>
                    </div>
                    <div class="notice-subject-text" title="<?php echo htmlspecialchars($n['subject']); ?>">
                        <?php echo htmlspecialchars($n['subject']); ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($recentNotices)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                    No recent notices found.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



    <!-- -- Pending Proposals (75%) -- -->
    <div class="col-lg-9 col-xl-9">
        <div class="card border-0 p-3 p-md-4 h-100" id="pending-proposals" style="border-radius: 16px; background: var(--card-bg); box-shadow: var(--card-shadow)">
            <div class="page-section-header mb-4 position-relative d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-section-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Pending Proposals</h6>
                        <small>Evaluate and review student project submissions</small>
                    </div>
                </div>
                <span class="badge rounded-pill fw-bold shadow-sm" style="font-size: 0.78rem; background: rgba(139, 92, 246, 0.15); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.3); padding: 6px 14px;">
                    <?php echo count($proposals); ?> Pending
                </span>
            </div>
            
            <div class="d-none d-md-block table-responsive custom-table-scroll" style="max-height: 380px; overflow-y: auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-3 border-0 text-uppercase rounded-start" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Project Title</th>
                            <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Team Members</th>
                            <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Status</th>
                            <th class="py-3 px-3 border-0 text-uppercase text-end rounded-end" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $statusMap = [
                            'Approved' => ['rgba(5,150,105,0.1)', '#059669'],
                            'Submitted' => ['rgba(245,158,11,0.1)', '#d97706'],
                            'Revision Requested' => ['rgba(139,92,246,0.1)', '#8b5cf6'],
                            'Rejected' => ['rgba(220,38,38,0.1)', '#dc2626']
                        ];
                        foreach($proposals as $pr): 
                            $st = $pr['status'];
                            $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                            $color = $statusMap[$st][1] ?? '#6b7280';
                        ?>
                        <tr style="transition: background-color 0.2s">
                            <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                                <div class="project-title-cell text-truncate" title="<?php echo htmlspecialchars($pr['project_title']); ?>">
                                    <?php echo htmlspecialchars($pr['project_title']); ?>
                                </div>
                                <?php if(!empty($pr['file_path'])): ?>
                                    <?php $ext = strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)); ?>
                                    <?php if($ext === 'pdf'): ?>
                                        <span role="button" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem; cursor: pointer; color: #10b981;" data-bs-toggle="offcanvas" data-bs-target="#pdfOffcanvas<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-layout-sidebar-reverse me-1"></i>View PDF
                                        </span>
                                    <?php else: ?>
                                        <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                            <i class="bi bi-file-earmark-arrow-down-fill me-1"></i>Download Document
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-stack">
                                        <?php 
                                        $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                        foreach(array_slice($pr['members'], 0, 4) as $idx => $m): 
                                            $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg';
                                            $avatarPath = $basePath . '/uploads/avatars/' . $avatarFile;
                                            $initials = strtoupper(substr($m['name'] ?? 'U', 0, 1));
                                            $colClass = $colors[$idx % count($colors)];
                                        ?>
                                            <?php if (!empty($m['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                                <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                                                     title="<?php echo htmlspecialchars($m['name']); ?> (Click to view)"
                                                     alt="Avatar"
                                                     onclick="showAvatarPopup('<?php echo htmlspecialchars($avatarPath); ?>', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                            <?php else: ?>
                                                <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-white <?php echo $colClass; ?>" 
                                                     style="width: 32px; height: 32px; margin-left: <?php echo $idx > 0 ? '-10px' : '0'; ?>; font-size: 0.75rem; font-weight: 600; flex-shrink: 0;"
                                                     title="<?php echo htmlspecialchars($m['name']); ?> (Click to view)"
                                                     onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
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
                            <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                                <span style="background: <?php echo $bg;?>;color: <?php echo $color;?>;font-weight: 600;font-size: 0.7rem;padding: 5px 12px;border-radius: 20px;display: inline-flex;align-items: center">
                                    <?php echo htmlspecialchars($st); ?>
                                </span>
                            </td>
                            <td class="px-3 py-3 border-bottom text-end" style="border-color: var(--border-color) !important">
                                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                    <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#proposalDetailsModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-info-circle-fill"></i> <span>Details</span>
                                    </button>
                                    <?php if($pr['status'] !== 'Approved'): ?>
                                    <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#proposalReviewModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-clipboard-check-fill"></i> <span>Review</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($proposals)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div style="font-size: 2.5rem;color: var(--border-color);margin-bottom: 1rem"><i class="bi bi-file-earmark-text"></i></div>
                                    <h6 class="fw-bold" style="color: var(--text-primary)">No Pending Proposals</h6>
                                    <p class="small text-muted mb-0">No project proposals requiring review at this time.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="d-block d-md-none mt-3">
                <?php foreach($proposals as $pr): 
                    $st = $pr['status'];
                    $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                    $color = $statusMap[$st][1] ?? '#6b7280';
                ?>
                    <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg)">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <span style="background: <?php echo $bg;?>;color: <?php echo $color;?>;font-weight: 600;font-size: 0.7rem;padding: 3px 8px;border-radius: 20px;">
                                <?php echo htmlspecialchars($st); ?>
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden; color: var(--text-primary) !important;">
                            <?php echo htmlspecialchars($pr['project_title']); ?>
                        </h6>
                        <?php if(!empty($pr['file_path'])): ?>
                            <?php $ext = strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)); ?>
                            <div class="mb-2">
                                <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="small text-decoration-none fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                    <i class="bi <?php echo ($ext === 'pdf') ? 'bi-box-arrow-up-right' : 'bi-file-earmark-arrow-down-fill'; ?> me-1"></i> <?php echo ($ext === 'pdf') ? 'View PDF' : 'Download'; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-stack">
                                <?php foreach(array_slice($pr['members'], 0, 4) as $idx => $m): 
                                    $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg';
                                    $avatarPath = $basePath . '/uploads/avatars/' . $avatarFile;
                                    $initials = strtoupper(substr($m['name'] ?? 'U', 0, 1));
                                    $colClass = $colors[$idx % count($colors)];
                                ?>
                                    <?php if (!empty($m['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                        <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                                             title="<?php echo htmlspecialchars($m['name']); ?> (Click to view)"
                                             alt="Avatar" style="width: 24px; height: 24px;"
                                             onclick="showAvatarPopup('<?php echo htmlspecialchars($avatarPath); ?>', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                    <?php else: ?>
                                        <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-white <?php echo $colClass; ?>" 
                                             style="width: 24px; height: 24px; margin-left: <?php echo $idx > 0 ? '-8px' : '0'; ?>; font-size: 0.6rem; font-weight: 600;"
                                             title="<?php echo htmlspecialchars($m['name']); ?> (Click to view)"
                                             onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                            <?php echo $initials; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php if(count($pr['members']) > 4): ?>
                                <span class="text-muted small fw-semibold" style="font-size: 0.7rem;">+<?php echo count($pr['members']) - 4; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end align-items-center mt-2 pt-3 border-top" style="border-color: var(--border-color) !important; gap: 8px;">
                            <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#proposalDetailsModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                <i class="bi bi-info-circle-fill"></i> Details
                            </button>
                            <?php if($pr['status'] !== 'Approved'): ?>
                            <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#proposalReviewModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                <i class="bi bi-clipboard-check-fill"></i> Review
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($proposals)): ?>
                    <div class="text-center text-muted py-4 rounded-3 small" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                        No pending proposals requiring review.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Notice Modals -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
<style>
@media (max-width: 768px) {
    .notice-modal-dialog { margin: 0.5rem; }
    .letterhead-container { padding: 30px 18px !important; min-height: auto !important; }
    .header-logo-section { gap: 8px !important; margin-bottom: 18px !important; padding-bottom: 10px !important; }
    .header-logo-section img { width: 48px !important; height: 48px !important; }
    .uni-title { font-size: 0.98rem !important; }
    .fac-title { font-size: 0.72rem !important; }
    .dept-title { font-size: 0.68rem !important; }
    .meta-section { font-size: 0.72rem !important; margin-bottom: 18px !important; padding-bottom: 6px !important; }
    .subject-line { font-size: 0.82rem !important; margin-bottom: 15px !important; padding-left: 6px !important; }
    .body-content { font-size: 0.78rem !important; line-height: 1.55 !important; margin-bottom: 30px !important; }
    .watermark { width: 200px !important; height: 200px !important; }
    .signatures-section { flex-direction: row !important; flex-wrap: nowrap !important; justify-content: space-between !important; padding-top: 30px !important; }
    .signature-line { width: 100% !important; max-width: 130px !important; font-size: 0.68rem !important; }
    .signature-line .small { font-size: 0.65rem !important; }
    .signature-line .x-small { font-size: 0.58rem !important; }
    .signature-cursive { font-size: 1.15rem !important; top: -22px !important; left: 5px !important; }
    .sign-title { font-size: 0.58rem !important; }
}
</style>
<?php 
$noticesForModal = isset($recentNotices) ? $recentNotices : (isset($notices) ? $notices : []);
$db = \Database::getInstance()->getConnection();
foreach($noticesForModal as $n): 
    $sender_id = $n['sender_id'];
    $stmtC = $db->prepare("SELECT name, department FROM coordinators WHERE user_id = ?");
    $stmtC->execute([$sender_id]);
    $coordUser = $stmtC->fetch();
    $coordName = $coordUser ? $coordUser['name'] : 'Coordinator';
    $coordDept = $coordUser ? $coordUser['department'] : 'Department';

    $stmtH = $db->prepare("SELECT name FROM hods WHERE department = ?");
    $stmtH->execute([$coordDept]);
    $hodUser = $stmtH->fetch();
    $hodName = $hodUser ? $hodUser['name'] : 'Head of Department';
    
    $basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<div class="modal fade" id="noticeModal<?php echo $n['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl notice-modal-dialog">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-body p-0 d-flex justify-content-center position-relative">
                
                <button type="button" class="btn-close shadow-sm position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10; background-color: rgba(255,255,255,0.9); border-radius: 50%; padding: 0.8rem;"></button>

                <div class="letterhead-container w-100" style="background: #fdfcfb; max-width: 820px; padding: 60px 70px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border-radius: 8px; position: relative; min-height: 1060px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; font-family: 'Lora', Georgia, serif; color: #1e293b; text-align: left;">
                    
                    <!-- Watermark -->
                    <div class="watermark" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); width: 380px; height: 380px; opacity: 0.035; pointer-events: none; z-index: 0;">
                        <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Watermark" style="width: 100%;height: 100%;object-fit: contain;filter: grayscale(100%)">
                    </div>

                    <div class="letterhead-content" style="position: relative; z-index: 1;">
                        <div class="header-logo-section" style="border-bottom: 3px double #1e293b; padding-bottom: 20px; margin-bottom: 35px; display: flex; align-items: center; justify-content: center; gap: 20px;">
                            <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Logo" width="80" height="80" style="object-fit: contain">
                            <div class="header-text" style="text-align: left;">
                                <h3 class="uni-title m-0" style="font-family: 'Cinzel', serif; font-size: 1.6rem; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase; color: #0f172a; line-height: 1.2;">University of Sindh</h3>
                                <h5 class="fac-title m-0" style="font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #334155; margin-top: 3px;">Faculty of Engineering & Technology</h5>
                                <h6 class="dept-title m-0" style="font-family: 'Lora', Georgia, serif; font-size: 1.05rem; font-weight: 600; color: #475569; margin-top: 3px;">Department of <?php echo htmlspecialchars($coordDept); ?></h6>
                                <small class="text-muted" style="font-size: 0.78rem;display: block;margin-top: 3px;font-family: sans-serif;letter-spacing: 0.3px">Jamshoro, Sindh, Pakistan</small>
                            </div>
                        </div>

                        <div class="meta-section d-flex justify-content-between align-items-center" style="font-size: 0.95rem; margin-bottom: 40px; color: #334155; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px;">
                            <div>
                                <span class="fw-bold">Ref No:</span> <span style="font-family: monospace; font-size: 1.05rem;"><?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?></span>
                            </div>
                            <div>
                                <span class="fw-bold">Date:</span> <?php echo date('F d, Y', strtotime($n['notice_date'])); ?>
                            </div>
                        </div>

                        <div class="subject-line" style="font-size: 1.15rem; font-weight: bold; margin-bottom: 30px; color: #0f172a; border-left: 3px solid #1e3a8a; padding-left: 12px;">
                            SUBJECT: <?php echo htmlspecialchars($n['subject']); ?>
                        </div>

                        <div class="body-content" style="font-size: 1.05rem; line-height: 1.8; text-align: justify; white-space: pre-wrap; margin-bottom: 60px; color: #1e293b;">
                            <?php echo htmlspecialchars($n['body']); ?>
                        </div>
                    </div>

                    <div class="signatures-section d-flex justify-content-between align-items-end" style="position: relative; z-index: 1; margin-top: auto; padding-top: 50px;">
                        
                        <div class="signature-box" style="position: relative; display: inline-block; text-align: left;">
                            <div class="signature-cursive" style="font-family: 'Great Vibes', cursive; font-size: 2.1rem; color: #047857; position: absolute; top: -38px; left: 20px; transform: rotate(-3deg); opacity: 0.9; pointer-events: none; letter-spacing: 1px; text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);">
                                <?php echo htmlspecialchars($coordName); ?>
                            </div>
                            <div class="signature-line" style="border-top: 1.5px solid #0f172a; width: 230px; padding-top: 8px; font-size: 0.9rem; font-weight: bold; color: #0f172a;">
                                <div class="small mb-1"><?php echo htmlspecialchars($coordName); ?></div>
                                <div class="sign-title" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: #475569;">FYP Coordinator</div>
                                <div class="text-muted x-small" style="font-size: 0.75rem; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                            </div>
                        </div>

                        <div class="signature-box" style="position: relative; display: inline-block; text-align: left;">
                            <div class="signature-cursive" style="font-family: 'Great Vibes', cursive; font-size: 2.1rem; color: #047857; position: absolute; top: -38px; left: 20px; transform: rotate(-3deg); opacity: 0.9; pointer-events: none; letter-spacing: 1px; text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);">
                                <?php echo htmlspecialchars($hodName); ?>
                            </div>
                            <div class="signature-line" style="border-top: 1.5px solid #0f172a; width: 230px; padding-top: 8px; font-size: 0.9rem; font-weight: bold; color: #0f172a;">
                                <div class="small mb-1"><?php echo htmlspecialchars($hodName); ?></div>
                                <div class="sign-title" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: #475569;">Chairperson</div>
                                <div class="text-muted x-small" style="font-size: 0.75rem; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Proposal Modals & Offcanvas rendered for Dashboard -->
<?php foreach($proposals as $pr): 
    $st = $pr['status'];
    $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
    $color = $statusMap[$st][1] ?? '#6b7280';
?>

<!-- DETAILS MODAL -->
<div class="modal fade" id="proposalDetailsModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold">Proposal Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary)"><?php echo htmlspecialchars($pr['project_title']); ?></h5>
                    <span class="badge" style="background: <?php echo $bg;?>;color: <?php echo $color;?>;font-weight: 600;padding: 6px 12px;border-radius: 20px">
                        Status: <?php echo htmlspecialchars($st); ?>
                    </span>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-2" style="letter-spacing: 0.04em">Project Abstract</label>
                    <div class="p-3 rounded-3 text-muted" style="background: var(--form-bg);border: 1px solid var(--border-color);font-size: 0.85rem;line-height: 1.65;text-align: justify;max-height: 250px;overflow-y: auto">
                        <?php echo nl2br(htmlspecialchars($pr['abstract'])); ?>
                    </div>
                </div>

                <div>
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-3" style="letter-spacing: 0.04em">Proposed Team Members</label>
                    <div class="row g-3">
                        <?php foreach($pr['members'] as $m): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="border: 1px solid var(--border-color);background: var(--card-bg)">
                                <?php 
                                $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; 
                                $avatarPath = $basePath . '/uploads/avatars/' . $avatarFile;
                                ?>
                                <?php if (!empty($m['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" class="rounded-circle me-3 border border-2 border-white shadow-sm member-avatar-click" style="width: 48px;height: 48px;object-fit: cover" alt="Avatar" title="Click to view photo" onclick="showAvatarPopup('<?php echo htmlspecialchars($avatarPath); ?>', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo strtoupper(substr($m['name'] ?? 'U', 0, 1)); ?>', 'bg-primary');">
                                <?php else: ?>
                                    <div class="rounded-circle me-3 border border-2 border-white shadow-sm d-flex align-items-center justify-content-center bg-primary text-white fw-bold member-avatar-click" style="width: 48px; height: 48px; font-size: 1.1rem; flex-shrink: 0;" title="Click to view details" onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo strtoupper(substr($m['name'] ?? 'U', 0, 1)); ?>', 'bg-primary');">
                                        <?php echo strtoupper(substr($m['name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;color: var(--text-primary)">
                                        <?php echo htmlspecialchars($m['name']); ?>
                                        <?php if($m['user_id'] == $pr['created_by']): ?>
                                            <span class="badge ms-1" style="background: rgba(16,185,129,0.15);color: #10b981;font-size: 0.6rem">Leader</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted font-monospace" style="font-size: 0.75rem"><?php echo htmlspecialchars($m['student_id']); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg)">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary);border: 1px solid var(--border-color)">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- REVIEW MODAL -->
<div class="modal fade" id="proposalReviewModal<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold">Submit Review</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/proposal/action" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <div class="modal-body p-4 text-start">
                    <input type="hidden" name="proposal_id" value="<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em;color: var(--text-secondary)">Review Decision</label>
                        <select class="form-select fw-medium" name="status" style="background-color: var(--form-bg);border-color: var(--border-color);color: var(--text-primary)" required>
                            <option value="Approved" <?php echo $pr['status'] === 'Approved' ? 'selected' : ''; ?>>Approve</option>
                            <option value="Revision Requested" <?php echo $pr['status'] === 'Revision Requested' ? 'selected' : ''; ?>>Request Revision</option>
                            <option value="Rejected" <?php echo $pr['status'] === 'Rejected' ? 'selected' : ''; ?>>Reject</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em;color: var(--text-secondary)">Feedback Remarks (Optional)</label>
                        <textarea class="form-control" name="feedback" rows="5" placeholder="Enter comments, suggestions, or revision notes here..." style="background-color: var(--form-bg);border-color: var(--border-color);color: var(--text-primary)"><?php echo htmlspecialchars($pr['feedback'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg)">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary);border: 1px solid var(--border-color)">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold" style="background: #0d9488;border-color: #0d9488">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(!empty($pr['file_path']) && strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)) === 'pdf'): ?>
<!-- PDF Offcanvas (Right Side) -->
<div class="offcanvas offcanvas-end shadow-lg border-start-0" tabindex="-1" id="pdfOffcanvas<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>" style="width: 75vw; max-width: 1400px; min-width: 320px; z-index: 1060; background: var(--main-bg);">
  <div class="offcanvas-header px-4 py-3" style="background: var(--card-bg); border-bottom: 1px solid var(--border-color);">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; background: rgba(13,148,136,0.1); color: #0d9488;">
            <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
        </div>
        <div>
            <h6 class="offcanvas-title fw-bold mb-0" style="color: var(--text-primary); font-size: 1.1rem; letter-spacing: -0.01em;">Proposal Document</h6>
            <div class="text-muted small fw-medium mt-1 text-truncate" style="max-width: 400px;"><?php echo htmlspecialchars($pr['project_title']); ?></div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="btn btn-sm px-3 py-2 fw-semibold rounded-pill d-flex align-items-center gap-2" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); transition: all 0.2s ease;">
            <i class="bi bi-box-arrow-up-right"></i> Open New Tab
        </a>
        <button type="button" class="btn-close ms-2" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
  </div>
  <div class="offcanvas-body p-0" style="background: #e5e7eb;">
    <iframe src="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" width="100%" height="100%" style="border: none; width: 100%; height: 100%;"></iframe>
  </div>
</div>
<?php endif; ?>

<?php endforeach; ?>

<!-- AVATAR PREVIEW POPUP MODAL -->
<div class="modal fade" id="avatarPreviewModal" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content border-0 rounded-4 shadow-lg text-center overflow-hidden" style="background: var(--card-bg);">
            <div class="modal-header border-0 pb-0 justify-content-end p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-1">
                <div class="d-flex justify-content-center mb-3">
                    <div class="position-relative">
                        <img id="avatarPreviewImg" src="" alt="Student Avatar" class="rounded-circle shadow-lg border border-4 border-white d-none" style="width: 170px; height: 170px; object-fit: cover;">
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