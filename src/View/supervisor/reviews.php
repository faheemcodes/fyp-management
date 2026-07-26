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
    background: rgba(16,185,129,0.1);
    color: #10b981;
    border-color: rgba(16,185,129,0.2);
}
.action-btn.review:hover {
    background: rgba(13,148,136,0.1);
    color: #0d9488;
    border-color: rgba(13,148,136,0.2);
}

@media (max-width: 768px) {
    
    
}

/* ========================================= */
</style>
<!-- Supervisor Review Documents & Proposals View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>



<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Icon -->
        <div class="page-hero-icon">
                <i class="bi bi-person-workspace"></i>
            </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                Review Workflow
            </p>
            <h4 class="text-white fw-bold" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                Project Proposals
            </h4>
        </div>

        <!-- Stats -->
        <div class="d-none d-lg-flex gap-3">
            <div class="page-stat-pill">
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
            <div class="page-stat-pill" style="background: rgba(245,158,11,0.15)">
                <span class="stat-num" style="color: #fcd34d"><?php echo htmlspecialchars((string)($pendingCount), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Action Required</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (empty($proposals)): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card border-0 text-center p-5 shadow-sm" style="border-radius: var(--border-radius-lg)">
                <div style="width: 72px;height: 72px;background: rgba(16,185,129,0.08);border-radius: 20px;display: flex;align-items: center;justify-content: center;margin: 0 auto 20px;font-size: 1.8rem;color: #10b981">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h5 class="fw-bold mb-2">No Proposals</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem;max-width: 380px;margin: 0 auto">No project proposals have been submitted by your assigned groups yet.</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="page-section">
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
                                <?php $ext = strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)); ?>
                                <?php if($ext === 'pdf'): ?>
                                    <!-- Laptop Offcanvas trigger -->
                                    <span role="button" class="small text-primary text-decoration-none mt-1 d-none d-md-inline-block fw-medium" style="font-size: 0.75rem;cursor: pointer" data-bs-toggle="offcanvas" data-bs-target="#pdfOffcanvas<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-layout-sidebar-reverse me-1"></i>View PDF
                                    </span>
                                    <!-- Mobile new tab trigger -->
                                    <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="small text-decoration-none mt-1 d-inline-block d-md-none fw-medium" style="font-size: 0.75rem">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>View PDF
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem">
                                        <i class="bi bi-file-earmark-arrow-down-fill me-1"></i>Download Document
                                    </a>
                                <?php endif; ?>
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
                            <span style="background: <?php echo $bg;?>;color: <?php echo $color;?>;font-weight: 600;font-size: 0.7rem;padding: 5px 12px;border-radius: 20px;display: inline-flex;align-items: center">
                                <?php echo htmlspecialchars($st); ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
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
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Modals rendered outside the table to prevent z-index/backdrop issues -->
<?php foreach($proposals as $pr): ?>

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
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary)"><?php echo htmlspecialchars($pr['project_title']); ?></h5>
                    <?php 
                    $st = $pr['status'];
                    $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                    $color = $statusMap[$st][1] ?? '#6b7280';
                    ?>
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
                                <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle me-3 border border-2 border-white shadow-sm" style="width: 48px;height: 48px;object-fit: cover" alt="Avatar">
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
                <h6 class="modal-title fw-bold">Submit Review - <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/supervisor/proposal/action" method="POST">
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
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
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
            <div class="text-muted small fw-medium mt-1">Group: <span style="color: #0d9488; font-family: monospace;"><?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?></span></div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="btn btn-sm px-3 py-2 fw-semibold rounded-pill d-none d-sm-flex align-items-center gap-2" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); transition: all 0.2s ease;">
            <i class="bi bi-box-arrow-up-right"></i> Open New Tab
        </a>
        <button type="button" class="btn-close ms-2" data-bs-dismiss="offcanvas" aria-label="Close" ></button>
    </div>
  </div>
  <div class="offcanvas-body p-0" style="background: #e5e7eb;">
    <iframe src="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" width="100%" height="100%" style="border: none; width: 100%; height: 100%;"></iframe>
  </div>
</div>
<?php endif; ?>

<?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move all modals to the body to prevent z-index issues from CSS stacking contexts
        const modals = document.querySelectorAll('.modal, .offcanvas');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });
</script>
