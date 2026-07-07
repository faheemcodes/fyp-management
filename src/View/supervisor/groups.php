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
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: 0 4px 15px rgba(59,130,246,0.15);
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
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.1) !important;
    outline: none;
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
        <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
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
                        <td class="text-end pe-4">
                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $g['id']; ?>">
                                    <i class="bi bi-info-circle-fill"></i> <span>Details</span>
                                </button>
                                <button class="action-btn grade" title="Manage Grades" data-bs-toggle="modal" data-bs-target="#gradeGroupModal<?php echo $g['id']; ?>">
                                    <i class="bi bi-pencil-fill"></i> <span>Grade</span>
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
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg);">
            <div class="modal-header border-0 py-3 rounded-top-4" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;">
                <h6 class="modal-title fw-bold">Project Details - <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary);"><?php echo htmlspecialchars($g['project_title']); ?></h5>
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
                                    <div class="fw-semibold" style="font-size: 0.9rem; color: var(--text-primary);">
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
            <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg);">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color);">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- MANUAL GRADING MODAL -->
<div class="modal fade eval-modal" id="gradeGroupModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header eval-modal-header border-0">
                <h5 class="modal-title fw-semibold" style="color: #0d9488; font-size: 1.05rem;"><i class="bi bi-person-check-fill me-2"></i>Supervision Marks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/groups/grade" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                <div class="modal-body p-3 text-start">
                    <p class="mb-3" style="font-size: 0.82rem; line-height: 1.5; color: var(--text-secondary);">Assign individual supervision marks out of 45 for each student in the group. Overall totals and grades will be updated automatically.</p>
                    
                    <div class="eval-table-wrapper">
                        <table class="eval-table">
                            <thead>
                                <tr>
                                    <th class="text-start ps-3">Student</th>
                                    <th class="text-center" style="width: 30%;">Supervision Marks (45)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($g['members'] as $m): ?>
                                <tr>
                                    <td class="text-start ps-3">
                                        <div class="eval-student-info">
                                            <div class="eval-student-avatar"><?php echo strtoupper(substr($m['name'], 0, 1)); ?></div>
                                            <div>
                                                <div class="eval-student-name"><?php echo htmlspecialchars($m['name']); ?></div>
                                                <div class="eval-student-roll"><?php echo htmlspecialchars($m['student_id']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="eval-input" name="marks[<?php echo $m['user_id']; ?>][supervision]" min="0" max="45" step="1" value="<?php echo isset($m['supervision_marks']) ? (int)$m['supervision_marks'] : ''; ?>" required>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 d-flex justify-content-end gap-2" style="background: var(--card-bg);">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color);">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold" style="background: #0d9488; border-color: #0d9488;">Save Marks</button>
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
