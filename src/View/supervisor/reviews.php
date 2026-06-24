<!-- Supervisor Review Documents & Proposals View -->
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

@media (min-width: 769px) {
    .table-responsive {
        overflow: visible;
    }
}
.grp-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
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
.action-btn.review:hover {
    background: rgba(13,148,136,0.1);
    color: #0d9488;
    border-color: rgba(13,148,136,0.2);
}

@media (max-width: 768px) {
    .group-hero { padding: 24px 16px; }
    .group-stat-pill { display: none; }
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Icon -->
        <div class="group-hero-icon">
            <i class="bi bi-file-earmark-check-fill"></i>
        </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                Review Workflow
            </p>
            <h4 class="text-white fw-bold" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                Project Proposals
            </h4>
        </div>

        <!-- Stats -->
        <div class="d-none d-lg-flex gap-3">
            <div class="group-stat-pill">
                <span class="stat-num"><?php echo count($proposals); ?></span>
                <span class="stat-label">Total Proposals</span>
            </div>
            <?php 
            $pendingCount = 0;
            foreach ($proposals as $p) {
                if ($p['status'] === 'Submitted' || $p['status'] === 'Revision Requested') {
                    $pendingCount++;
                }
            }
            if ($pendingCount > 0):
            ?>
            <div class="group-stat-pill" style="background: rgba(245,158,11,0.15);">
                <span class="stat-num" style="color: #fcd34d;"><?php echo $pendingCount; ?></span>
                <span class="stat-label">Action Required</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (empty($proposals)): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card border-0 text-center p-5 shadow-sm" style="border-radius: var(--border-radius-lg);">
                <div style="width: 72px; height: 72px; background: rgba(59,130,246,0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: #3b82f6;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h5 class="fw-bold mb-2">No Proposals</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem; max-width: 380px; margin: 0 auto;">No project proposals have been submitted by your assigned groups yet.</p>
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
                        <th>Team Members</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($proposals as $pr): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="group-code-badge">
                                <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="project-title-cell text-truncate" title="<?php echo htmlspecialchars($pr['project_title']); ?>">
                                <?php echo htmlspecialchars($pr['project_title']); ?>
                            </div>
                            <?php if($pr['file_path']): ?>
                                <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem;">
                                    <i class="bi bi-file-earmark-arrow-down-fill me-1"></i>Download PDF
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-stack">
                                    <?php foreach(array_slice($pr['members'], 0, 4) as $m): ?>
                                        <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                        <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" 
                                             title="<?php echo htmlspecialchars($m['name']); ?>"
                                             alt="Avatar">
                                    <?php endforeach; ?>
                                </div>
                                <?php if(count($pr['members']) > 4): ?>
                                    <span class="text-muted small fw-semibold">+<?php echo count($pr['members']) - 4; ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php 
                            $statusMap = [
                                'Approved' => ['rgba(5,150,105,0.1)', '#059669'],
                                'Submitted' => ['rgba(245,158,11,0.1)', '#d97706'],
                                'Revision Requested' => ['rgba(139,92,246,0.1)', '#8b5cf6'],
                                'Rejected' => ['rgba(220,38,38,0.1)', '#dc2626']
                            ];
                            $st = $pr['status'];
                            $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                            $color = $statusMap[$st][1] ?? '#6b7280';
                            ?>
                            <span style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; font-weight: 600; font-size: 0.7rem; padding: 5px 12px; border-radius: 20px; display: inline-flex; align-items: center;">
                                <?php echo htmlspecialchars($st); ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="action-btn" title="View Details" data-bs-toggle="modal" data-bs-target="#proposalDetailsModal<?php echo $pr['id']; ?>">
                                    <i class="bi bi-info-circle-fill"></i>
                                </button>
                                <button class="action-btn review" title="Review Proposal" data-bs-toggle="modal" data-bs-target="#proposalReviewModal<?php echo $pr['id']; ?>">
                                    <i class="bi bi-clipboard-check-fill"></i>
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

<!-- Modals rendered outside the table to prevent z-index/backdrop issues -->
<?php foreach($proposals as $pr): ?>

<!-- DETAILS MODAL -->
<div class="modal fade" id="proposalDetailsModal<?php echo $pr['id']; ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg);">
            <div class="modal-header border-0 py-3 rounded-top-4" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;">
                <h6 class="modal-title fw-bold">Proposal Details - <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary);"><?php echo htmlspecialchars($pr['project_title']); ?></h5>
                    <?php 
                    $st = $pr['status'];
                    $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                    $color = $statusMap[$st][1] ?? '#6b7280';
                    ?>
                    <span class="badge" style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; font-weight: 600; padding: 6px 12px; border-radius: 20px;">
                        Status: <?php echo htmlspecialchars($st); ?>
                    </span>
                    <?php if($pr['file_path']): ?>
                        <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 ms-2 fw-medium">
                            <i class="bi bi-download me-1"></i> Download Proposal
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-2" style="letter-spacing: 0.04em;">Project Abstract</label>
                    <div class="p-3 rounded-3 text-muted" style="background: var(--form-bg); border: 1px solid var(--border-color); font-size: 0.85rem; line-height: 1.65; text-align: justify; max-height: 250px; overflow-y: auto;">
                        <?php echo nl2br(htmlspecialchars($pr['abstract'])); ?>
                    </div>
                </div>

                <div>
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-3" style="letter-spacing: 0.04em;">Proposed Team Members</label>
                    <div class="row g-3">
                        <?php foreach($pr['members'] as $m): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="border: 1px solid var(--border-color); background: var(--card-bg);">
                                <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle me-3 border border-2 border-white shadow-sm" style="width: 48px; height: 48px; object-fit: cover;" alt="Avatar">
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem; color: var(--text-primary);">
                                        <?php echo htmlspecialchars($m['name']); ?>
                                        <?php if($m['user_id'] == $pr['created_by']): ?>
                                            <span class="badge ms-1" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-size: 0.6rem;">Leader</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted font-monospace" style="font-size: 0.75rem;"><?php echo htmlspecialchars($m['student_id']); ?></div>
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

<!-- REVIEW MODAL -->
<div class="modal fade" id="proposalReviewModal<?php echo $pr['id']; ?>" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg);">
            <div class="modal-header border-0 py-3 rounded-top-4" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;">
                <h6 class="modal-title fw-bold">Submit Review - <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/proposal/action" method="POST">
                <div class="modal-body p-4 text-start">
                    <input type="hidden" name="proposal_id" value="<?php echo $pr['id']; ?>">
                    
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Review Decision</label>
                        <select class="form-select fw-medium" name="status" style="background-color: var(--form-bg); border-color: var(--border-color); color: var(--text-primary);" required>
                            <option value="Approved" <?php echo $pr['status'] === 'Approved' ? 'selected' : ''; ?>>Approve</option>
                            <option value="Revision Requested" <?php echo $pr['status'] === 'Revision Requested' ? 'selected' : ''; ?>>Request Revision</option>
                            <option value="Rejected" <?php echo $pr['status'] === 'Rejected' ? 'selected' : ''; ?>>Reject</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-uppercase" style="letter-spacing: 0.04em; color: var(--text-secondary);">Feedback Remarks</label>
                        <textarea class="form-control" name="feedback" rows="5" placeholder="Enter comments, suggestions, or revision notes here..." style="background-color: var(--form-bg); border-color: var(--border-color); color: var(--text-primary);" required><?php echo htmlspecialchars($pr['feedback'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg);">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color);">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold" style="background: #0d9488; border-color: #0d9488;">Submit Review</button>
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
