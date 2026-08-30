<style>
/* ─── Group Page Scoped Styles ─── */








/* ─── Section Panel ─── */



@media (min-width: 769px) {
    .table-responsive {
        overflow: visible;
    }
}

/* ─── Modern Table Styles ─── */





.group-code-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(16,185,129,0.1);
    color: #10b981;
    font-family: monospace;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
    letter-spacing: 0.02em;
}
.project-title-cell {
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    font-size: 0.9rem;
    max-width: 250px;
}
.progress-stage-chip {
    font-size: 0.7rem;
    font-weight: 600;
    background: rgba(16,185,129,0.15);
    color: #059669;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.avatar-stack img, .avatar-stack .rounded-circle, .member-avatar-click, .eval-student-avatar {
    cursor: pointer !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.avatar-stack img {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid var(--card-bg);
    margin-left: -12px;
}
.avatar-stack img:first-child {
    margin-left: 0;
}
.avatar-stack img:hover, .avatar-stack .rounded-circle:hover, .member-avatar-click:hover, .eval-student-avatar:hover {
    transform: scale(1.18) translateY(-2px);
    z-index: 20;
    box-shadow: 0 4px 12px rgba(0,0,0,0.18) !important;
}

.action-btn {
    padding: 6px 14px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
}
.action-btn:hover {
    background: rgba(16,185,129,0.1);
    color: #10b981;
    border-color: rgba(16,185,129,0.2);
}
.action-btn.grade:hover {
    background: rgba(13,148,136,0.1);
    color: #0d9488;
    border-color: rgba(13,148,136,0.2);
}
.action-btn.review:hover {
    background: rgba(13,148,136,0.1);
    color: #0d9488;
    border-color: rgba(13,148,136,0.2);
}

@media (max-width: 768px) {
    
    
}

/* Minimal Modal & Table Styles */
.eval-modal .modal-content {
    border: none !important;
    border-radius: 12px !important;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.eval-modal-header {
    background: #f8fafc !important;
    border-bottom: 1px solid var(--border-color) !important;
    padding: 16px 20px !important;
}

html.dark-theme .eval-modal-header {
    background: #1e293b !important;
}

.eval-table-wrapper {
    background: var(--card-bg);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.eval-table {
    margin: 0;
    border-collapse: collapse;
    width: 100%;
    min-width: 600px;
}

.eval-table thead th {
    background: var(--form-bg);
    color: var(--text-secondary);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.eval-table tbody tr {
    border-bottom: 1px solid var(--border-color);
}

.eval-table tbody tr:last-child {
    border-bottom: none;
}

.eval-table td {
    padding: 12px 16px;
    vertical-align: middle;
}

.eval-student-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.eval-student-avatar {
    width: 32px;
    height: 32px;
    background: #e2e8f0;
    color: #475569;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

html.dark-theme .eval-student-avatar {
    background: #334155;
    color: #cbd5e1;
}

.eval-student-name {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.85rem;
    line-height: 1.2;
}

.eval-student-roll {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.eval-input {
    background-color: transparent !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding: 6px 8px !important;
    text-align: center;
    font-size: 0.85rem !important;
    color: var(--text-primary) !important;
    width: 100%;
    max-width: 70px;
    margin: 0 auto;
}

html.dark-theme .eval-input {
    border-color: #334155 !important;
}

.eval-input:focus {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 2px rgba(16,185,129,0.1) !important;
    outline: none;
}
</style>
<!-- Supervisor Assigned Groups View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>



<?php
$anySupervisionHidden = false;
$hasSupervisionGrades = false;
foreach ($groups as $g) {
    if (isset($g['supervision_marks']) && $g['supervision_marks'] !== null) {
        $hasSupervisionGrades = true;
        if ($g['show_supervision_to_student'] == 0) {
            $anySupervisionHidden = true;
        }
    }
}
$globalSupervisionShowAction = ($anySupervisionHidden || !$hasSupervisionGrades) ? 1 : 0;
?>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Icon -->
        <div class="page-hero-icon" style="background: transparent">
                <i class="bi bi-person-workspace"></i>
            </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                Supervision Dashboard
            </p>
            <h4 class="text-white fw-bold mb-3" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                FYP Groups
            </h4>
            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                <form action="<?php echo $basePath; ?>/supervisor/groups/toggle-visibility" method="POST" class="m-0">
                    <input type="hidden" name="show" value="<?php echo $globalSupervisionShowAction; ?>">
                    <button type="submit" class="btn btn-sm <?php echo $globalSupervisionShowAction ? 'btn-outline-light' : 'btn-light text-success'; ?> rounded-pill px-4 py-1 fw-semibold" style="font-size: 0.78rem">
                        <i class="bi <?php echo $globalSupervisionShowAction ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?> me-2"></i>
                        <?php echo $globalSupervisionShowAction ? 'Publish Marks to Students' : 'Marks are Visible'; ?>
                    </button>
                
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
            </div>
        </div>

        <!-- Stats -->
        <div class="d-none d-lg-flex gap-3">
            <div class="page-stat-pill">
                <span class="stat-num"><?php echo count($groups); ?></span>
                <span class="stat-label">Total Groups</span>
            </div>
        </div>
    </div>
</div>

<?php if(empty($groups)): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card border-0 text-center p-5 shadow-sm" style="border-radius: var(--border-radius-lg)">
                <div style="width: 72px;height: 72px;background: rgba(16,185,129,0.08);border-radius: 20px;display: flex;align-items: center;justify-content: center;margin: 0 auto 20px;font-size: 1.8rem;color: #10b981">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">No Assigned Groups</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem;max-width: 380px;margin: 0 auto">You currently have no FYP groups assigned to you for supervision.</p>
            </div>
        </div>
    </div>
<?php else: ?>

    <div class="card border-0 p-3 p-md-4 h-100 mb-4" style="border-radius: 16px;background: var(--card-bg);box-shadow: var(--card-shadow)">
        <div class="page-section-header mb-4 d-md-none">
            <div class="page-section-icon" style="background: rgba(99, 102, 241, 0.1);color: #6366f1">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h6>Assigned Groups</h6>
                <small>Manage your supervised teams</small>
            </div>
        </div>
        <div class="d-none d-md-block table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th class="ps-4">Group Code</th>
                        <th>Project Title</th>
                        <th>Progress Stage</th>
                        <th>Team Members</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($groups as $g): 
                        $isAccepted = (($g['project_status'] ?? '') === 'Approved' || ($g['proposal_status'] ?? '') === 'Approved' || (!empty($g['progress_stage']) && $g['progress_stage'] !== 'Proposal Submitted'));
                    ?>
                    <tr>
                        <td class="ps-4">
                            <span class="group-code-badge">
                                <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="project-title-cell text-truncate" title="<?php echo htmlspecialchars($g['project_title']); ?>">
                                <?php echo htmlspecialchars($g['project_title']); ?>
                            </div>
                            <?php if (!empty($g['proposal_file_path'])): ?>
                                <a href="<?php echo $basePath . htmlspecialchars($g['proposal_file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>Proposal PDF
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="progress-stage-chip">
                                <?php echo htmlspecialchars($g['progress_stage']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-stack">
                                    <?php 
                                    $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                    foreach(array_slice($g['members'], 0, 4) as $idx => $m): 
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
                                                 style="width: 36px; height: 36px; margin-left: <?php echo $idx > 0 ? '-12px' : '0'; ?>; font-size: 0.8rem; font-weight: 600; flex-shrink: 0;"
                                                 title="<?php echo htmlspecialchars($m['name']); ?> (Click to view)"
                                                 onclick="showAvatarPopup('', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo $initials; ?>', '<?php echo $colClass; ?>'); event.stopPropagation();">
                                                <?php echo $initials; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php if(count($g['members']) > 4): ?>
                                    <span class="text-muted small fw-semibold">+<?php echo count($g['members']) - 4; ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="bi bi-info-circle-fill"></i> <span>Details</span>
                                </button>
                                <?php if ($isAccepted): ?>
                                    <button class="action-btn grade" title="Manage Grades" data-bs-toggle="modal" data-bs-target="#gradeGroupModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-pencil-fill"></i> <span>Grade</span>
                                    </button>
                                <?php else: ?>
                                    <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#proposalReviewModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-clipboard-check-fill"></i> <span>Review</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="d-block d-md-none mt-3">
            <?php foreach($groups as $g): 
                $isAccepted = (($g['project_status'] ?? '') === 'Approved' || ($g['proposal_status'] ?? '') === 'Approved' || (!empty($g['progress_stage']) && $g['progress_stage'] !== 'Proposal Submitted'));
            ?>
                <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg)">
                    <div class="mb-2">
                        <span class="group-code-badge" style="font-size: 0.75rem;">
                            <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?>
                        </span>
                    </div>
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden; color: var(--text-primary) !important;">
                        <?php echo htmlspecialchars($g['project_title']); ?>
                    </h6>
                    <div class="mb-3">
                        <span class="progress-stage-chip" style="font-size: 0.65rem;">
                            <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="avatar-stack">
                            <?php 
                            $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                            foreach(array_slice($g['members'], 0, 4) as $idx => $m): 
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
                        <?php if(count($g['members']) > 4): ?>
                            <span class="text-muted small fw-semibold" style="font-size: 0.7rem;">+<?php echo count($g['members']) - 4; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-2 pt-3 border-top" style="border-color: var(--border-color) !important; gap: 8px;">
                        <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                            <i class="bi bi-info-circle-fill"></i> Details
                        </button>
                        <?php if ($isAccepted): ?>
                            <button class="action-btn grade" title="Manage Grades" data-bs-toggle="modal" data-bs-target="#gradeGroupModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                <i class="bi bi-pencil-fill"></i> Grade
                            </button>
                        <?php else: ?>
                            <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#proposalReviewModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.75rem; padding: 4px 10px;">
                                <i class="bi bi-clipboard-check-fill"></i> Review
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>

<?php 
foreach($groups as $g): 
    $isAccepted = (($g['project_status'] ?? '') === 'Approved' || ($g['proposal_status'] ?? '') === 'Approved' || (!empty($g['progress_stage']) && $g['progress_stage'] !== 'Proposal Submitted'));
?>

<!-- DETAILS MODAL -->
<div class="modal fade" id="detailsModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header py-3 rounded-top-4" style="background: var(--card-bg); border-bottom: 1px solid var(--border-color);">
                <h6 class="modal-title fw-bold" style="color: var(--text-primary);">Project Details - <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-2" style="color: var(--text-primary)"><?php echo htmlspecialchars($g['project_title']); ?></h5>
                        <span class="badge" style="background: rgba(16,185,129,0.1);color: #10b981;font-weight: 600;padding: 6px 12px;border-radius: 20px">
                            Stage: <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                    </div>
                    <?php if (!empty($g['thesis_file'])): ?>
                        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="viewThesisOffcanvas('<?php echo htmlspecialchars($g['thesis_file']); ?>')">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>View Thesis
                        </button>
                    <?php elseif (!empty($g['proposal_file_path'])): ?>
                        <a href="<?php echo $basePath . htmlspecialchars($g['proposal_file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="btn btn-sm rounded-pill px-3 fw-bold shadow-sm" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>View Proposal PDF
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-2" style="letter-spacing: 0.04em">Project Abstract / Description</label>
                    <div class="p-3 rounded-3 text-muted" style="background: var(--form-bg);border: 1px solid var(--border-color);font-size: 0.85rem;line-height: 1.65;text-align: justify;max-height: 250px;overflow-y: auto">
                        <?php echo nl2br(htmlspecialchars($g['project_description'] ?? $g['proposal_abstract'] ?? '')); ?>
                    </div>
                </div>

                <div>
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-3" style="letter-spacing: 0.04em">Team Members</label>
                    <div class="row g-3">
                        <?php foreach($g['members'] as $m): ?>
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
                                        <?php if($m['user_id'] == $g['created_by']): ?>
                                            <span class="badge ms-1" style="background: rgba(16,185,129,0.15);color: #10b981;font-size: 0.6rem">Leader</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted font-monospace" style="font-size: 0.75rem"><?php echo htmlspecialchars($m['student_id']); ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($m['email']); ?></div>
                                    <?php if(!empty($m['phone'])): ?>
                                        <div class="text-muted" style="font-size: 0.75rem"><i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($m['phone']); ?></div>
                                    <?php endif; ?>
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

<?php if ($isAccepted): ?>
<!-- MANUAL GRADING MODAL -->
<div class="modal fade eval-modal" id="gradeGroupModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header py-3 rounded-top-4" style="background: var(--card-bg); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold" style="color: var(--text-primary); font-size: 1.05rem"><i class="bi bi-person-check-fill me-2 text-primary"></i>Supervision Marks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/groups/grade" method="POST">
                <input type="hidden" name="group_id" value="<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="modal-body p-3 text-start">
                    <p class="mb-3" style="font-size: 0.82rem;line-height: 1.5;color: var(--text-secondary)">Assign individual supervision marks out of 45 for each student in the group. Overall totals and grades will be updated automatically.</p>
                    
                    <div class="eval-table-wrapper">
                        <table class="eval-table">
                            <thead>
                                <tr>
                                    <th class="text-start ps-3">Student</th>
                                    <th class="text-center" style="width: 30%">Supervision Marks (45)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($g['members'] as $m): ?>
                                <tr>
                                    <td class="text-start ps-3">
                                        <?php 
                                        $mAvatarPath = !empty($m['avatar']) ? $basePath . '/uploads/avatars/' . $m['avatar'] : null; 
                                        ?>
                                        <div class="eval-student-info">
                                            <div class="eval-student-avatar member-avatar-click" title="Click to view student" onclick="showAvatarPopup('<?php echo ($mAvatarPath && file_exists($_SERVER['DOCUMENT_ROOT'] . $mAvatarPath)) ? htmlspecialchars($mAvatarPath) : ''; ?>', '<?php echo htmlspecialchars(addslashes($m['name'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($m['student_id'] ?? '')); ?>', '<?php echo strtoupper(substr($m['name'] ?? 'U', 0, 1)); ?>', 'bg-primary');">
                                                <?php if ($mAvatarPath && file_exists($_SERVER['DOCUMENT_ROOT'] . $mAvatarPath)): ?>
                                                    <img src="<?php echo htmlspecialchars($mAvatarPath); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                <?php else: ?>
                                                    <?php echo strtoupper(substr($m['name'], 0, 1)); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="eval-student-name"><?php echo htmlspecialchars($m['name']); ?></div>
                                                <div class="eval-student-roll"><?php echo htmlspecialchars($m['student_id']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="eval-input" name="marks[<?php echo htmlspecialchars((string)($m['user_id']), ENT_QUOTES, 'UTF-8'); ?>][supervision]" min="0" max="45" step="1" value="<?php echo isset($m['supervision_marks']) ? (int)$m['supervision_marks'] : ''; ?>" required>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 d-flex justify-content-end gap-2" style="background: var(--card-bg)">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary);border: 1px solid var(--border-color)">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold" style="background: #0d9488;border-color: #0d9488">Save Marks</button>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            </form>
        </div>
    </div>
</div>
<?php else: ?>
<!-- PROPOSAL REVIEW MODAL -->
<div class="modal fade" id="proposalReviewModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold" style="color: var(--text-primary)"><i class="bi bi-clipboard-check-fill me-2" style="color: #0d9488;"></i>Review Project Proposal</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/proposal/action" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <div class="modal-body p-4 text-start">
                    <input type="hidden" name="proposal_id" value="<?php echo htmlspecialchars((string)($g['proposal_id'] ?? $g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Project Title</label>
                        <div class="fw-semibold" style="font-size: 0.95rem; color: var(--text-primary);"><?php echo htmlspecialchars($g['project_title']); ?></div>
                        <?php if (!empty($g['proposal_file_path'])): ?>
                            <div class="mt-2">
                                <a href="<?php echo $basePath . htmlspecialchars($g['proposal_file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="btn btn-sm px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem; background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);">
                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>View Proposal PDF
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Review Decision</label>
                        <select class="form-select fw-medium" name="status" style="background-color: var(--form-bg); border-color: var(--border-color); color: var(--text-primary);" required>
                            <option value="Approved" <?php echo ($g['proposal_status'] ?? '') === 'Approved' ? 'selected' : ''; ?>>Approve (Accept Project)</option>
                            <option value="Revision Requested" <?php echo ($g['proposal_status'] ?? '') === 'Revision Requested' ? 'selected' : ''; ?>>Request Revision</option>
                            <option value="Rejected" <?php echo ($g['proposal_status'] ?? '') === 'Rejected' ? 'selected' : ''; ?>>Reject</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Feedback Remarks (Optional)</label>
                        <textarea class="form-control" name="feedback" rows="4" placeholder="Enter comments, revision notes, or feedback here..." style="background-color: var(--form-bg); border-color: var(--border-color); color: var(--text-primary);"><?php echo htmlspecialchars($g['proposal_feedback'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg)">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color);">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold" style="background: #0d9488; border-color: #0d9488;">Submit Decision</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endforeach; ?>


<?php include __DIR__ . '/../shared/thesis_offcanvas.php'; ?>

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
        // Move all modals to the body to prevent z-index issues from CSS stacking contexts
        const modals = document.querySelectorAll('.modal, .offcanvas');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });
</script>
