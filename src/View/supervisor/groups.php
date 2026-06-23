<!-- Supervisor Assigned Groups View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* ─── Group Page Scoped Styles ─── */
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
.group-stat-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 20px;
    background: rgba(255,255,255,0.06);
    border-radius: 12px;
    min-width: 80px;
}
.group-stat-pill .stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    letter-spacing: -0.03em;
}
.group-stat-pill .stat-label {
    font-size: 0.62rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: rgba(255,255,255,0.4);
    margin-top: 4px;
}

/* ─── Section Panel ─── */
.grp-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 24px;
    transition: box-shadow 0.25s ease;
}
.grp-section-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
    border-top-left-radius: calc(var(--border-radius-lg) - 1px);
    border-top-right-radius: calc(var(--border-radius-lg) - 1px);
}

@media (min-width: 769px) {
    .table-responsive {
        overflow: visible;
    }
}

/* ─── Modern Table Styles ─── */
.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 950px;
}
.modern-table thead th {
    background: var(--form-bg);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-secondary);
    font-weight: 700;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
}
.modern-table tbody td {
    padding: 20px;
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
.group-code-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(59,130,246,0.1);
    color: #3b82f6;
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

.avatar-stack {
    display: flex;
    align-items: center;
}
.avatar-stack img {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid var(--card-bg);
    margin-left: -12px;
    transition: transform 0.2s ease;
}
.avatar-stack img:first-child {
    margin-left: 0;
}
.avatar-stack img:hover {
    transform: translateY(-3px);
    z-index: 10;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    transition: all 0.2s ease;
}
.action-btn:hover {
    background: rgba(59,130,246,0.1);
    color: #3b82f6;
    border-color: rgba(59,130,246,0.2);
}
.action-btn.grade:hover {
    background: rgba(13,148,136,0.1);
    color: #0d9488;
    border-color: rgba(13,148,136,0.2);
}

@media (max-width: 768px) {
    .group-hero { padding: 24px 16px; }
    .group-stat-pill { display: none; }
}
</style>

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
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Icon -->
        <div class="group-hero-icon">
            <i class="bi bi-diagram-3-fill"></i>
        </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                Supervision Dashboard
            </p>
            <h4 class="text-white fw-bold mb-3" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                Assigned FYP Groups
            </h4>
            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                <form action="<?php echo $basePath; ?>/supervisor/groups/toggle-visibility" method="POST" class="m-0">
                    <input type="hidden" name="show" value="<?php echo $globalSupervisionShowAction; ?>">
                    <button type="submit" class="btn btn-sm <?php echo $globalSupervisionShowAction ? 'btn-outline-light' : 'btn-light text-success'; ?> rounded-pill px-4 py-1 fw-semibold" style="font-size: 0.78rem;">
                        <i class="bi <?php echo $globalSupervisionShowAction ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?> me-2"></i>
                        <?php echo $globalSupervisionShowAction ? 'Publish Marks to Students' : 'Marks are Visible'; ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats -->
        <div class="d-none d-lg-flex gap-3">
            <div class="group-stat-pill">
                <span class="stat-num"><?php echo count($groups); ?></span>
                <span class="stat-label">Total Groups</span>
            </div>
        </div>
    </div>
</div>

<?php if(empty($groups)): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card border-0 text-center p-5 shadow-sm" style="border-radius: var(--border-radius-lg);">
                <div style="width: 72px; height: 72px; background: rgba(59,130,246,0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: #3b82f6;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">No Assigned Groups</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem; max-width: 380px; margin: 0 auto;">You currently have no FYP groups assigned to you for supervision.</p>
            </div>
        </div>
    </div>
<?php else: ?>

    <div class="grp-section">
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th class="ps-4">Group Code</th>
                        <th>Project Title</th>
                        <th>Progress Stage</th>
                        <th>Team Members</th>
                        <th style="min-width: 120px;">Marks</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($groups as $g): ?>
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
                        </td>
                        <td>
                            <span class="progress-stage-chip">
                                <?php echo htmlspecialchars($g['progress_stage']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-stack">
                                    <?php foreach(array_slice($g['members'], 0, 4) as $m): ?>
                                        <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                        <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" 
                                             title="<?php echo htmlspecialchars($m['name']); ?>"
                                             alt="Avatar">
                                    <?php endforeach; ?>
                                </div>
                                <?php if(count($g['members']) > 4): ?>
                                    <span class="text-muted small fw-semibold">+<?php echo count($g['members']) - 4; ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold" style="font-size: 1rem; color: #0d9488;">
                                    <?php echo number_format($g['supervision_marks'] ?? 0, 0); ?>
                                </span>
                                <span class="text-muted fw-semibold ms-1" style="font-size: 0.75rem;">/ 45</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $g['id']; ?>">
                                    <i class="bi bi-info-circle-fill"></i>
                                </button>
                                <button class="action-btn grade" title="Manage Grades" data-bs-toggle="modal" data-bs-target="#gradeGroupModal<?php echo $g['id']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<!-- Modals outside of loop to avoid z-index issues -->
<?php foreach($groups as $g): ?>

<!-- DETAILS MODAL -->
<div class="modal fade" id="detailsModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 py-3 rounded-top-4" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;">
                <h6 class="modal-title fw-bold">Project Details - <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($g['project_title']); ?></h5>
                    <span class="badge" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-weight: 600; padding: 6px 12px; border-radius: 20px;">
                        Stage: <?php echo htmlspecialchars($g['progress_stage']); ?>
                    </span>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-2" style="letter-spacing: 0.04em;">Project Abstract / Description</label>
                    <div class="p-3 rounded-3 text-muted" style="background: var(--form-bg); border: 1px solid var(--border-color); font-size: 0.85rem; line-height: 1.65; text-align: justify; max-height: 250px; overflow-y: auto;">
                        <?php echo nl2br(htmlspecialchars($g['project_description'])); ?>
                    </div>
                </div>

                <div>
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-3" style="letter-spacing: 0.04em;">Team Members</label>
                    <div class="row g-3">
                        <?php foreach($g['members'] as $m): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="border: 1px solid var(--border-color); background: var(--card-bg);">
                                <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle me-3 border border-2 border-white shadow-sm" style="width: 48px; height: 48px; object-fit: cover;" alt="Avatar">
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($m['name']); ?>
                                        <?php if($m['user_id'] == $g['created_by']): ?>
                                            <span class="badge ms-1" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-size: 0.6rem;">Leader</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted font-monospace" style="font-size: 0.75rem;"><?php echo htmlspecialchars($m['student_id']); ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($m['email']); ?></div>
                                    <?php if(!empty($m['phone'])): ?>
                                        <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($m['phone']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- MANUAL GRADING MODAL -->
<div class="modal fade" id="gradeGroupModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 py-3 rounded-top-4" style="background: linear-gradient(135deg, #0d9488, #0f766e); color: #fff;">
                <h6 class="modal-title fw-bold">Manual Group Grading</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/groups/grade" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                <div class="modal-body p-4 text-start">
                    <p class="text-muted mb-4" style="font-size: 0.82rem; line-height: 1.5;">Review work manually and enter marks. Overall totals and grades will be updated automatically.</p>
                    
                    <div class="mb-2">
                        <label for="supervision_marks_<?php echo $g['id']; ?>" class="form-label small fw-semibold text-secondary text-uppercase" style="letter-spacing: 0.04em;">Supervision Marks</label>
                        <div class="input-group">
                            <input type="number" class="form-control fw-bold text-dark" id="supervision_marks_<?php echo $g['id']; ?>" name="supervision_marks" min="0" max="45" step="1" value="<?php echo htmlspecialchars(number_format($g['supervision_marks'] ?? 0, 0)); ?>" required>
                            <span class="input-group-text bg-light text-muted fw-semibold">/ 45</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4 text-end">
                    <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" style="background: #0d9488; border-color: #0d9488;">Save Marks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move all modals to the body to prevent z-index issues from CSS stacking contexts
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });
</script>
