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
.action-btn.review {
    color: #8b5cf6;
    background: rgba(139, 92, 246, 0.08);
    border-color: rgba(139, 92, 246, 0.2);
}
.action-btn.review:hover {
    background: #8b5cf6;
    color: #fff;
    border-color: #8b5cf6;
}

@media (max-width: 768px) {
    
    
}
</style>
<!-- Coordinator Project Proposals View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>



<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Icon -->
        <div class="page-hero-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                Department View
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
            $approvedCount = 0;
            foreach ($proposals as $p) {
                if ($p['status'] === 'Approved') {
                    $approvedCount++;
                }
            }
            if ($approvedCount > 0):
            ?>
            <div class="page-stat-pill" style="background: rgba(16,185,129,0.15)">
                <span class="stat-num" style="color: #34d399"><?php echo htmlspecialchars((string)($approvedCount), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Approved</span>
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
                <h5 class="fw-bold mb-2">No Proposals Found</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem;max-width: 380px;margin: 0 auto">No project proposals have been submitted by students in your department yet.</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 p-3 p-md-4 h-100 mb-4" style="border-radius: 16px;background: var(--card-bg);box-shadow: var(--card-shadow)">
        <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom d-md-none" style="border-color: var(--border-color) !important">
            <i class="bi bi-file-earmark-text text-primary" style="font-size: 1.2rem;"></i>
            <h6 class="fw-bold m-0" style="color: var(--text-primary);letter-spacing: -0.01em">Project Proposals</h6>
        </div>
        <div class="d-none d-md-block table-responsive">
            <table class="table modern-table">
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
                    <?php foreach($proposals as $pr): ?>
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
                                    <!-- Laptop Offcanvas trigger -->
                                    <span role="button" class="small text-decoration-none mt-1 d-none d-md-inline-block fw-medium" style="font-size: 0.75rem; cursor: pointer; color: #10b981;" data-bs-toggle="offcanvas" data-bs-target="#pdfOffcanvas<?php echo htmlspecialchars((string)($pr['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-layout-sidebar-reverse me-1"></i>View PDF
                                    </span>
                                    <!-- Mobile new tab trigger -->
                                    <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="small text-decoration-none mt-1 d-inline-block d-md-none fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>View PDF
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" class="small text-decoration-none mt-1 d-inline-block fw-medium" style="font-size: 0.75rem; color: #10b981;">
                                        <i class="bi bi-file-earmark-arrow-down-fill me-1"></i>Download Document
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem">
                                    <?php echo htmlspecialchars($pr['supervisor_name'] ?? 'Not Assigned'); ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-stack">
                                    <?php foreach(array_slice($pr['members'], 0, 4) as $m): ?>
                                        <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                        <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" 
                                             title="<?php echo htmlspecialchars($m['student_name']); ?>"
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

        <!-- Mobile Card List -->
        <div class="d-block d-md-none mt-3">
            <?php foreach($proposals as $pr): ?>
                <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg)">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="group-code-badge" style="font-size: 0.75rem;">
                            <?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?>
                        </span>
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
                        <span style="background: <?php echo $bg;?>;color: <?php echo $color;?>;font-weight: 600;font-size: 0.7rem;padding: 3px 8px;border-radius: 20px;">
                            <?php echo htmlspecialchars($st); ?>
                        </span>
                    </div>
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden; color: var(--text-primary) !important;">
                        <?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?>
                    </h6>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="avatar-stack">
                            <?php foreach(array_slice($pr['members'], 0, 4) as $m): ?>
                                <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" 
                                     title="<?php echo htmlspecialchars($m['student_name']); ?>"
                                     alt="Avatar" style="width: 24px; height: 24px;">
                            <?php endforeach; ?>
                        </div>
                        <?php if(count($pr['members']) > 4): ?>
                            <span class="text-muted small fw-semibold" style="font-size: 0.7rem;">+<?php echo count($pr['members']) - 4; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end align-items-center mt-2 pt-3 border-top" style="border-color: var(--border-color) !important; gap: 8px;">
                        <?php if($pr['file_path']): ?>
                            <?php $ext = strtolower(pathinfo($pr['file_path'], PATHINFO_EXTENSION)); ?>
                            <?php if($ext === 'pdf'): ?>
                                <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="action-btn text-decoration-none" style="font-size: 0.75rem; padding: 4px 10px; color: #10b981; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> View PDF
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="action-btn text-decoration-none" style="font-size: 0.75rem; padding: 4px 10px; color: #10b981; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                                    <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Download
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
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
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary)"><?php echo htmlspecialchars($pr['project_title'] ?? 'Untitled'); ?></h5>
                    <div class="text-muted small fw-semibold mb-3">Supervisor: <?php echo htmlspecialchars($pr['supervisor_name'] ?? 'Not Assigned'); ?></div>
                    <?php 
                    $st = $pr['status'];
                    $bg = $statusMap[$st][0] ?? 'rgba(107,114,128,0.1)';
                    $color = $statusMap[$st][1] ?? '#6b7280';
                    ?>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge" style="background: <?php echo $bg;?>;color: <?php echo $color;?>;font-weight: 600;padding: 6px 12px;border-radius: 20px">
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
                    <div class="p-3 rounded-3 text-muted" style="background: var(--form-bg);border: 1px solid var(--border-color);font-size: 0.85rem;line-height: 1.65;text-align: justify;max-height: 250px;overflow-y: auto">
                        <?php echo nl2br(htmlspecialchars($pr['abstract'])); ?>
                    </div>
                </div>

                <div>
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-3" style="letter-spacing: 0.04em">Team Members</label>
                    <div class="row g-3">
                        <?php foreach($pr['members'] as $m): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 h-100" style="border: 1px solid var(--border-color);background: var(--card-bg)">
                                <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle me-3 border border-2 border-white shadow-sm" style="width: 48px;height: 48px;object-fit: cover" alt="Avatar">
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;color: var(--text-primary)">
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
                <button type="button" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary);border: 1px solid var(--border-color)">Close</button>
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
            <div class="text-muted small fw-medium mt-1">Group: <span style="color: #0d9488; font-family: monospace;"><?php echo htmlspecialchars($pr['group_code'] ?? 'Pending'); ?></span></div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo $basePath . htmlspecialchars($pr['file_path']); ?>" target="_blank" onclick="window.open(this.href, '_blank'); return false;" class="btn btn-sm px-3 py-2 fw-semibold rounded-pill d-flex align-items-center gap-2" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); transition: all 0.2s ease;">
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
                            <small class="text-muted">Evaluate, set status, assign group code, or re-assign supervisor</small>
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
                                <option value="Approved" <?php echo $pr['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
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
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Provide notes, suggestions, or conditions for the student group and supervisor..."><?php echo htmlspecialchars($pr['review_notes'] ?? ''); ?></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Move all modals to the body to prevent z-index issues from CSS stacking contexts
        const modals = document.querySelectorAll('.modal, .offcanvas');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });
</script>

<?php include __DIR__ . '/../shared/thesis_offcanvas.php'; ?>
