<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
.group-code-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(16,185,129,0.1);
    color: #10b981;
    font-family: monospace;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 50rem;
    letter-spacing: 0.02em;
    white-space: nowrap;
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
    width: 34px;
    height: 34px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid var(--card-bg);
    margin-left: -10px;
    transition: transform 0.2s ease;
}
.avatar-stack img:first-child {
    margin-left: 0;
}
.avatar-stack img:hover {
    transform: translateY(-2px);
    z-index: 10;
}
.action-btn {
    padding: 6px 14px;
    height: 34px;
    border-radius: 50rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
}
.action-btn:hover {
    background: rgba(59,130,246,0.1);
    color: #3b82f6;
    border-color: rgba(59,130,246,0.25);
}
.action-btn.review-btn {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border-color: rgba(139, 92, 246, 0.25);
}
.action-btn.review-btn:hover {
    background: #8b5cf6;
    color: #ffffff;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Project Proposals Management</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Review, endorse, approve, or request revisions on FYP proposals across all university departments
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$totalProposals = count($proposals ?? []);
$approvedCount = 0;
$underReviewCount = 0;
$revisionCount = 0;
foreach ($proposals ?? [] as $pr) {
    $st = $pr['status'] ?? '';
    if ($st === 'Approved') $approvedCount++;
    elseif ($st === 'Under Review' || $st === 'Submitted') $underReviewCount++;
    elseif ($st === 'Revision Requested') $revisionCount++;
}
?>

<!-- ═══════════════ Metric KPI Cards ═══════════════ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Total Proposals</span>
            </div>
            <h4 class="fw-bold m-0 text-dark"><?php echo $totalProposals; ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Under Review</span>
            </div>
            <h4 class="fw-bold m-0 <?php echo ($underReviewCount > 0) ? 'text-warning' : 'text-dark'; ?>"><?php echo $underReviewCount; ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Approved</span>
            </div>
            <h4 class="fw-bold m-0 text-success"><?php echo $approvedCount; ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Needs Revision</span>
            </div>
            <h4 class="fw-bold m-0 text-danger"><?php echo $revisionCount; ?></h4>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="<?php echo $basePath; ?>/admin/proposals" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">Department</label>
                <select name="department" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <option value="all">All Departments</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($selectedDept === $d) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($d); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Shift</label>
                <select name="shift" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <option value="all" <?php echo ($selectedShift === 'all') ? 'selected' : ''; ?>>All Shifts</option>
                    <option value="Morning" <?php echo ($selectedShift === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                    <option value="Evening" <?php echo ($selectedShift === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Status</label>
                <select name="status" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <option value="all" <?php echo ($selectedStatus === 'all') ? 'selected' : ''; ?>>All Statuses</option>
                    <option value="Submitted" <?php echo ($selectedStatus === 'Submitted') ? 'selected' : ''; ?>>Submitted</option>
                    <option value="Under Review" <?php echo ($selectedStatus === 'Under Review') ? 'selected' : ''; ?>>Under Review</option>
                    <option value="Approved" <?php echo ($selectedStatus === 'Approved') ? 'selected' : ''; ?>>Approved</option>
                    <option value="Revision Requested" <?php echo ($selectedStatus === 'Revision Requested') ? 'selected' : ''; ?>>Revision Requested</option>
                    <option value="Rejected" <?php echo ($selectedStatus === 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <a href="<?php echo $basePath; ?>/admin/proposals" class="btn btn-outline-secondary w-100 rounded-pill fw-semibold" style="font-size: 0.85rem;">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Proposals Table Card -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-header bg-transparent border-0 p-3 p-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h6 class="fw-bold m-0 text-dark" style="font-size: 1rem;">
            <i class="bi bi-list-task me-2 text-primary"></i>Submitted Proposals (<?php echo count($proposals); ?>)
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="border-color: var(--border-color);">
            <thead>
                <tr style="background: var(--form-bg);">
                    <th class="ps-4" style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Group Code</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Project Title</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Department &amp; Shift</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Supervisor</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Team Members</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Status</th>
                    <th class="text-end pe-4" style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proposals)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                            No project proposals found matching the selected filters.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($proposals as $prop): 
                        $statusBadgeClass = 'bg-secondary';
                        if ($prop['status'] === 'Approved') $statusBadgeClass = 'bg-success';
                        elseif ($prop['status'] === 'Submitted') $statusBadgeClass = 'bg-warning text-dark';
                        elseif ($prop['status'] === 'Under Review') $statusBadgeClass = 'bg-info text-dark';
                        elseif ($prop['status'] === 'Revision Requested') $statusBadgeClass = 'bg-danger';
                        elseif ($prop['status'] === 'Rejected') $statusBadgeClass = 'bg-dark';
                    ?>
                        <tr>
                            <td class="ps-4">
                                <span class="group-code-badge"><?php echo htmlspecialchars($prop['group_code'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                            </td>
                            <td>
                                <div class="project-title-cell text-truncate" title="<?php echo htmlspecialchars($prop['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($prop['project_title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <a href="javascript:void(0)" class="small text-primary text-decoration-none view-abstract-btn" 
                                   data-title="<?php echo htmlspecialchars($prop['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   data-abstract="<?php echo htmlspecialchars($prop['abstract'] ?? $prop['project_description'] ?? 'No abstract provided.', ENT_QUOTES, 'UTF-8'); ?>">
                                    View Abstract
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo htmlspecialchars($prop['department'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($prop['shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                    <?php echo htmlspecialchars($prop['supervisor_name'] ?? 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td>
                                <div class="avatar-stack">
                                    <?php foreach (($prop['members'] ?? []) as $mem): 
                                        $avatar = !empty($mem['avatar']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $mem['avatar']) 
                                            ? $basePath . '/uploads/avatars/' . $mem['avatar'] 
                                            : $basePath . '/uploads/avatars/default_avatar.svg';
                                    ?>
                                        <img src="<?php echo $avatar; ?>" alt="<?php echo htmlspecialchars($mem['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($mem['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($mem['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)">
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?php echo $statusBadgeClass; ?> px-2.5 py-1" style="font-size: 0.75rem;">
                                    <?php echo htmlspecialchars($prop['status'] ?? 'Draft', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="action-btn review-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reviewModal<?php echo $prop['id']; ?>">
                                    <i class="bi bi-pencil-square"></i> Review
                                </button>
                            </td>
                        </tr>

                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal<?php echo $prop['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow" style="border-radius: 16px; background: var(--card-bg);">
                                    <form action="<?php echo $basePath; ?>/admin/proposals/review" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                        <input type="hidden" name="proposal_id" value="<?php echo (int)$prop['id']; ?>">
                                        <div class="modal-header border-bottom p-3">
                                            <h6 class="modal-title fw-bold text-dark">
                                                Review Proposal &bull; <?php echo htmlspecialchars($prop['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Project Title</label>
                                                <div class="p-2.5 rounded-3 fw-bold text-dark" style="background: var(--form-bg); font-size: 0.88rem;">
                                                    <?php echo htmlspecialchars($prop['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="actionSelect<?php echo $prop['id']; ?>" class="form-label small fw-bold text-secondary">Administrative Action</label>
                                                <select name="action" id="actionSelect<?php echo $prop['id']; ?>" class="form-select" required>
                                                    <option value="Approved" <?php echo ($prop['status'] === 'Approved') ? 'selected' : ''; ?>>Approve Proposal</option>
                                                    <option value="Supervisor Approved" <?php echo ($prop['status'] === 'Supervisor Approved') ? 'selected' : ''; ?>>Supervisor Endorsed</option>
                                                    <option value="Under Review" <?php echo ($prop['status'] === 'Under Review') ? 'selected' : ''; ?>>Under Review</option>
                                                    <option value="Revision Requested" <?php echo ($prop['status'] === 'Revision Requested') ? 'selected' : ''; ?>>Request Revision</option>
                                                    <option value="Rejected" <?php echo ($prop['status'] === 'Rejected') ? 'selected' : ''; ?>>Reject Proposal</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="feedbackText<?php echo $prop['id']; ?>" class="form-label small fw-bold text-secondary">Feedback &amp; Remarks (Sent to Students)</label>
                                                <textarea name="feedback" id="feedbackText<?php echo $prop['id']; ?>" class="form-control" rows="4" placeholder="Enter comments or revision requests..."><?php echo htmlspecialchars($prop['feedback'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top p-3">
                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Decision</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Abstract Viewer Modal -->
<div class="modal fade" id="abstractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; background: var(--card-bg);">
            <div class="modal-header border-bottom p-3">
                <h6 class="modal-title fw-bold text-dark" id="abstractModalTitle">Project Abstract</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p id="abstractModalBody" class="text-secondary mb-0" style="line-height: 1.6; font-size: 0.92rem; white-space: pre-wrap;"></p>
            </div>
            <div class="modal-footer border-top p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const abstractBtns = document.querySelectorAll('.view-abstract-btn');
    const modalTitle = document.getElementById('abstractModalTitle');
    const modalBody = document.getElementById('abstractModalBody');
    const abstractModal = new bootstrap.Modal(document.getElementById('abstractModal'));

    abstractBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modalTitle.textContent = this.getAttribute('data-title');
            modalBody.textContent = this.getAttribute('data-abstract');
            abstractModal.show();
        });
    });
});
</script>
