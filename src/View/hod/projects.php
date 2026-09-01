<!-- HOD Department FYP Projects Explorer -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
.avatar-group {
    display: inline-flex;
    align-items: center;
}
.avatar-group .avatar-item {
    position: relative;
    margin-left: -10px;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), z-index 0.2s ease;
    border: 2px solid var(--card-bg, #ffffff);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    object-fit: cover;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
}
.avatar-group .avatar-item:first-child {
    margin-left: 0;
}
.avatar-group .avatar-item:hover {
    transform: translateY(-3px) scale(1.15);
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.modern-table thead th {
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.04em !important;
    text-transform: uppercase !important;
    color: var(--text-secondary) !important;
}
.modern-table tbody td {
    font-size: 0.88rem !important;
}
.btn-filter-pill {
    font-size: 0.82rem;
    padding: 5px 14px;
}
.action-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
    transition: all 0.2s ease;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    text-decoration: none;
    cursor: pointer;
    padding: 0;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}
.action-btn-view {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border-color: rgba(59, 130, 246, 0.25);
}
.action-btn-view:hover {
    background: rgba(59, 130, 246, 0.2);
    color: #2563eb;
}
.btn-details {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.25);
    font-size: 0.82rem;
    font-weight: 600;
    padding: 0.35rem 0.95rem;
    border-radius: 50rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
}
.btn-details:hover {
    background: rgba(59, 130, 246, 0.2);
    color: #1d4ed8;
    border-color: rgba(59, 130, 246, 0.4);
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(37, 99, 235, 0.15);
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-kanban-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Department Projects</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75); font-size: 0.85rem">All registered project groups and milestones</p>
            </div>
        </div>
        <div class="text-white text-end d-none d-md-block">
            <div class="fs-4 fw-bold"><?php echo count($projects); ?></div>
            <div class="small opacity-75">Active Groups</div>
        </div>
    </div>
</div>

<div class="page-section">
    <div class="page-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-5 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search projects, supervisors, groups..." data-target="department-projects-table">
                </div>
            </div>
            <!-- Stage Filter Pills -->
            <div class="col-md-7 pe-0 d-flex justify-content-md-end gap-2 flex-wrap">
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold active" onclick="filterProjects('all', this)">All</button>
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold" onclick="filterProjects('proposal', this)">Proposal</button>
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold" onclick="filterProjects('defense', this)">Defense</button>
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold" onclick="filterProjects('final', this)">Final</button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="department-projects-table">
            <thead>
                <tr>
                    <th class="ps-4">Group &amp; Title</th>
                    <th>Supervisor</th>
                    <th>Milestone</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($projects as $p): ?>
                <?php 
                    $stage = $p['progress_stage'] ?? 'Group Created';
                    $stageCategory = 'proposal';
                    if (str_contains($stage, 'Defence') || str_contains($stage, 'Defense')) {
                        $stageCategory = 'defense';
                    } elseif (str_contains($stage, 'Final') || str_contains($stage, 'Grading')) {
                        $stageCategory = 'final';
                    }
                    $cNum = (int)($p['committee_number'] ?? 0);
                    
                    $propUrl = !empty($p['proposal_file']) ? trim($p['proposal_file']) : '';
                    if ($propUrl) {
                        if (!str_contains($propUrl, 'uploads/')) {
                            $propUrl = '/uploads/proposals/' . ltrim($propUrl, '/');
                        }
                        if (!str_starts_with($propUrl, '/')) {
                            $propUrl = '/' . $propUrl;
                        }
                    }
                    $finalPropUrl = $propUrl ? (($basePath ? rtrim($basePath, '/') : '') . $propUrl) : '';

                    $thUrl = !empty($p['thesis_file']) ? trim($p['thesis_file']) : '';
                    if ($thUrl) {
                        if (!str_contains($thUrl, 'uploads/')) {
                            $thUrl = '/uploads/thesis/' . ltrim($thUrl, '/');
                        }
                        if (!str_starts_with($thUrl, '/')) {
                            $thUrl = '/' . $thUrl;
                        }
                    }
                    $finalThUrl = $thUrl ? (($basePath ? rtrim($basePath, '/') : '') . $thUrl) : '';

                    $supFullName = !empty($p['supervisor_name']) ? formatPersonName($p['supervisor_prefix'] ?? 'Mr.', $p['supervisor_name'], $p['supervisor_surname'] ?? '') : null;
                ?>
                <tr data-stage-cat="<?php echo $stageCategory; ?>">
                    <td class="ps-4">
                        <div class="d-flex flex-column" style="max-width: 380px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge border rounded-pill px-2.5 py-1 font-monospace" style="background: rgba(59, 130, 246, 0.1); color: #2563eb; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.78rem; font-weight: 600;">
                                    <?php echo htmlspecialchars($p['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <small class="text-muted" style="font-size: 0.78rem;"><?php echo date('M Y', strtotime($p['created_at'])); ?></small>
                            </div>
                            <div class="fw-bold text-truncate" title="<?php echo htmlspecialchars($p['project_title'] ?? 'Title pending', ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--text-primary); font-size: 0.95rem;">
                                <?php echo htmlspecialchars($p['project_title'] ?? 'Project Title Pending Submission', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php if (!empty($p['abstract'])): ?>
                            <small class="text-muted text-truncate d-block" style="font-size: 0.82rem;" title="<?php echo htmlspecialchars($p['abstract'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($p['abstract'], ENT_QUOTES, 'UTF-8'); ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($p['supervisor_name'])): ?>
                        <div>
                            <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.92rem;"><?php echo htmlspecialchars($supFullName ?: $p['supervisor_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <small class="text-muted d-block" style="font-size: 0.82rem;"><?php echo htmlspecialchars($p['supervisor_designation'] ?? 'Faculty', ENT_QUOTES, 'UTF-8'); ?></small>
                        </div>
                        <?php else: ?>
                        <span class="badge border rounded-pill px-3 py-1.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.84rem; font-weight: 500;">Not Assigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge border rounded-pill px-3 py-1.5" style="background: rgba(59, 130, 246, 0.1); color: #2563eb; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.84rem; font-weight: 600;">
                            <?php echo htmlspecialchars($p['progress_stage'] ?? 'Proposal Stage', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end align-items-center">
                            <!-- View Complete Details Button -->
                            <button type="button" class="btn-details" data-bs-toggle="modal" data-bs-target="#viewProjectModal<?php echo (int)$p['group_id']; ?>" title="View Complete Project Details">
                                <i class="bi bi-eye-fill"></i> <span>Details</span>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- View Complete Project Details Modal -->
                <div class="modal fade" id="viewProjectModal<?php echo (int)$p['group_id']; ?>" tabindex="-1" aria-labelledby="viewProjectModalLabel<?php echo (int)$p['group_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.25); width: 60px; height: 60px;">
                                    <i class="bi bi-kanban-fill text-primary" style="font-size: 1.6rem"></i>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap justify-content-center">
                                    <span class="badge border rounded-pill px-3 py-1 font-monospace" style="background: rgba(59, 130, 246, 0.1); color: #2563eb; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.82rem; font-weight: 700;">
                                        <?php echo htmlspecialchars($p['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <span class="badge border rounded-pill px-3 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.25) !important; font-size: 0.82rem; font-weight: 600;">
                                        <?php echo htmlspecialchars($p['progress_stage'] ?? 'Proposal Stage', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php if (!empty($p['batch_name'])): ?>
                                    <span class="badge border rounded-pill px-3 py-1" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.82rem; font-weight: 500;">
                                        Batch: <?php echo htmlspecialchars($p['batch_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <h5 class="fw-bold mb-1 text-center px-3" style="color: var(--text-primary); font-size: 1.15rem; line-height: 1.4;">
                                    <?php echo htmlspecialchars($p['project_title'] ?? 'Project Title Pending Submission', ENT_QUOTES, 'UTF-8'); ?>
                                </h5>
                            </div>
                            
                            <div class="modal-body p-4 pt-3">
                                <!-- Overview Stats Grid -->
                                <div class="row g-2 mb-3">
                                    <div class="col-sm-4">
                                        <div class="p-3 rounded-3 text-center h-100" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                            <span class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.72rem;">Department</span>
                                            <strong style="color: var(--text-primary); font-size: 0.88rem;"><?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="p-3 rounded-3 text-center h-100" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                            <span class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.72rem;">Assigned Committee</span>
                                            <?php if ($cNum > 0): ?>
                                            <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important; font-size: 0.82rem; font-weight: 600;">
                                                Committee <?php echo $cNum; ?>
                                            </span>
                                            <?php else: ?>
                                            <span class="badge border rounded-pill px-2.5 py-1 text-muted border-light-subtle bg-light" style="font-size: 0.82rem;">Unassigned</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="p-3 rounded-3 text-center h-100" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                            <span class="text-muted d-block small fw-bold text-uppercase" style="font-size: 0.72rem;">Registration Date</span>
                                            <strong style="color: var(--text-primary); font-size: 0.88rem;"><?php echo date('M d, Y', strtotime($p['created_at'])); ?></strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Abstract / Description -->
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: var(--text-primary); font-size: 0.9rem;">
                                        <i class="bi bi-card-text text-primary"></i> Abstract &amp; Scope
                                    </h6>
                                    <p class="mb-0 text-secondary small" style="line-height: 1.6; white-space: pre-line; font-size: 0.85rem;">
                                        <?php echo !empty($p['abstract']) ? htmlspecialchars($p['abstract'], ENT_QUOTES, 'UTF-8') : '<em class="text-muted">No abstract or project description submitted yet.</em>'; ?>
                                    </p>
                                </div>

                                <!-- Supervisor Information (Specific Data Only) -->
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: var(--text-primary); font-size: 0.9rem;">
                                        <i class="bi bi-person-workspace text-primary"></i> Project Supervisor
                                    </h6>
                                    <?php if ($supFullName): ?>
                                    <div class="row g-2 small align-items-center">
                                        <div class="col-md-5">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Name</span>
                                            <strong style="color: var(--text-primary); font-size: 0.9rem;"><?php echo htmlspecialchars($supFullName, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Designation</span>
                                            <span class="badge border rounded-pill px-2.5 py-1" style="background: var(--card-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.82rem; font-weight: 500;">
                                                <?php echo htmlspecialchars($p['supervisor_designation'] ?? 'Faculty Member', ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Email</span>
                                            <span style="color: var(--text-primary); font-size: 0.84rem;"><?php echo htmlspecialchars($p['supervisor_email'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-muted mb-0 small fst-italic">No supervisor assigned to this project yet.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Group Members Section (Specific Data Only) -->
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: var(--text-primary); font-size: 0.9rem;">
                                        <i class="bi bi-people-fill text-primary"></i> Group Members (<?php echo count($p['members'] ?? []); ?>)
                                    </h6>
                                    <?php if (!empty($p['members'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm m-0 align-middle" style="font-size: 0.84rem;">
                                            <thead>
                                                <tr class="text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em; border-bottom: 1px solid var(--border-color);">
                                                    <th class="ps-2 pb-1">Student Name</th>
                                                    <th class="pb-1">Roll No</th>
                                                    <th class="pb-1">Email</th>
                                                    <th class="text-end pe-2 pb-1">Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($p['members'] as $m): ?>
                                                <?php 
                                                    $mFullName = formatPersonName($m['prefix'] ?? '', $m['student_name'] ?? '', $m['surname'] ?? '');
                                                ?>
                                                <tr style="border-bottom: 1px solid var(--border-color);">
                                                    <td class="ps-2 py-2 fw-semibold" style="color: var(--text-primary);">
                                                        <?php echo htmlspecialchars($mFullName, ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <td class="py-2 font-monospace text-muted">
                                                        <?php echo htmlspecialchars($m['roll_no'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <td class="py-2 text-muted">
                                                        <?php echo htmlspecialchars($m['email'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <td class="text-end pe-2 py-2">
                                                        <?php if (!empty($m['is_leader'])): ?>
                                                        <span class="badge border rounded-pill px-2.5 py-0.5" style="background: rgba(245, 158, 11, 0.1); color: #d97706; border-color: rgba(245, 158, 11, 0.25) !important; font-size: 0.72rem; font-weight: 700;">Leader</span>
                                                        <?php else: ?>
                                                        <span class="badge border rounded-pill px-2.5 py-0.5 text-muted" style="background: var(--card-bg); border-color: var(--border-color) !important; font-size: 0.72rem;">Member</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-muted mb-0 small fst-italic">No students registered in this group yet.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Committee (Specific Data Only) -->
                                <?php if ($cNum > 0): ?>
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: var(--text-primary); font-size: 0.9rem;">
                                        <i class="bi bi-shield-check text-primary"></i> Assigned Committee
                                    </h6>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <span class="badge rounded-pill px-3 py-1.5" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.25); font-size: 0.85rem; font-weight: 600;">
                                                Committee <?php echo $cNum; ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($p['committee_evaluators'])): ?>
                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            <span class="text-muted small fw-bold text-uppercase me-1" style="font-size: 0.7rem;">Evaluators:</span>
                                            <?php foreach($p['committee_evaluators'] as $eval): ?>
                                            <span class="badge rounded-pill px-2.5 py-1" style="background: var(--card-bg); color: var(--text-primary); border: 1px solid var(--border-color); font-size: 0.78rem;">
                                                <?php echo htmlspecialchars($eval['name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Submitted Documents -->
                                <div class="p-3 rounded-3 mb-1" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: var(--text-primary); font-size: 0.9rem;">
                                        <i class="bi bi-file-earmark-check-fill text-primary"></i> Project Submissions &amp; Documents
                                    </h6>
                                    <div class="row g-2">
                                        <!-- Proposal File -->
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                                                <div>
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <strong style="color: var(--text-primary); font-size: 0.88rem;">Project Proposal</strong>
                                                        <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(59, 130, 246, 0.1); color: #2563eb; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.75rem; font-weight: 600;">
                                                            <?php echo htmlspecialchars($p['proposal_status'] ?? 'Draft', ENT_QUOTES, 'UTF-8'); ?>
                                                        </span>
                                                    </div>
                                                    <small class="text-muted d-block mb-2" style="font-size: 0.78rem;">
                                                        <?php echo !empty($p['proposal_submitted_at']) ? 'Submitted on ' . date('M d, Y', strtotime($p['proposal_submitted_at'])) : 'No submission date'; ?>
                                                    </small>
                                                </div>
                                                <?php if (!empty($finalPropUrl)): ?>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold flex-grow-1" style="font-size: 0.78rem;" onclick="previewDocument('<?php echo htmlspecialchars($finalPropUrl, ENT_QUOTES, 'UTF-8'); ?>', 'Proposal', '<?php echo htmlspecialchars(addslashes($p['project_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($p['group_code'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>')">
                                                        <i class="bi bi-eye me-1"></i> Preview
                                                    </button>
                                                    <a href="<?php echo htmlspecialchars($finalPropUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" download class="btn btn-sm btn-outline-secondary rounded-pill px-2.5" style="font-size: 0.78rem;" title="Download Proposal">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                                <?php else: ?>
                                                <span class="text-muted small fst-italic">No proposal uploaded</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Thesis File -->
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                                                <div>
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <strong style="color: var(--text-primary); font-size: 0.88rem;">Final Thesis / Report</strong>
                                                        <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.25) !important; font-size: 0.75rem; font-weight: 600;">
                                                            <?php echo !empty($p['thesis_file']) ? 'Available' : 'Pending'; ?>
                                                        </span>
                                                    </div>
                                                    <small class="text-muted d-block mb-2" style="font-size: 0.78rem;">Final documentation file</small>
                                                </div>
                                                <?php if (!empty($finalThUrl)): ?>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold flex-grow-1" style="font-size: 0.78rem;" onclick="previewDocument('<?php echo htmlspecialchars($finalThUrl, ENT_QUOTES, 'UTF-8'); ?>', 'Thesis', '<?php echo htmlspecialchars(addslashes($p['project_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($p['group_code'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>')">
                                                        <i class="bi bi-eye me-1"></i> Preview
                                                    </button>
                                                    <a href="<?php echo htmlspecialchars($finalThUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" download class="btn btn-sm btn-outline-secondary rounded-pill px-2.5" style="font-size: 0.78rem;" title="Download Thesis">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                                <?php else: ?>
                                                <span class="text-muted small fst-italic">No thesis uploaded yet</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer border-0 p-3 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold ms-auto" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-folder2-open fs-2 d-block mb-2 opacity-50"></i>
                        No FYP projects registered yet.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- In-Page Document Viewer Modal -->
<div class="modal fade" id="documentViewerModal" tabindex="-1" aria-labelledby="documentViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 92vw;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-bottom px-4 py-3" style="border-color: var(--border-color) !important;">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" id="docTypeIconBox" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; width: 38px; height: 38px;">
                        <i class="bi bi-file-earmark-pdf-fill fs-5" id="docTypeIcon"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="modal-title fw-bold m-0" id="docModalTitle" style="color: var(--text-primary); font-size: 1rem;">Document Preview</h6>
                            <span class="badge rounded-pill font-monospace" id="docModalGroup" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.72rem;"></span>
                            <span class="badge rounded-pill" id="docModalBadge" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; font-size: 0.72rem;"></span>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">Viewing in-page document viewer</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="docDownloadBtn" href="#" target="_blank" download class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                        <i class="bi bi-box-arrow-up-right"></i> <span>Open Full / Download</span>
                    </a>
                    <button type="button" class="btn-close shadow-none ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0" style="background: #525659; min-height: 78vh; display: flex; align-items: stretch;">
                <iframe id="docViewerIframe" src="about:blank" style="width: 100%; height: 78vh; border: none; display: block;" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Student Avatar Preview Modal -->
<div class="modal fade" id="studentPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg text-center" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-body p-4 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-flex flex-column align-items-center mt-2">
                    <img id="modalStudentPhoto" src="" class="rounded-circle shadow mb-3" style="width: 130px; height: 130px; object-fit: cover; border: 4px solid var(--form-bg);" alt="Student Photo">
                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);" id="modalStudentName"></h6>
                    <span class="badge border font-monospace mt-1 px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary);" id="modalStudentRoll"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewDocument(fileUrl, docType, title, groupCode) {
    document.getElementById('docModalTitle').innerText = (docType === 'Proposal' ? 'Proposal: ' : 'Thesis: ') + (title || 'Project Document');
    document.getElementById('docModalBadge').innerText = docType;
    document.getElementById('docModalGroup').innerText = groupCode || '';
    document.getElementById('docDownloadBtn').href = fileUrl;

    const iconBox = document.getElementById('docTypeIconBox');
    const icon = document.getElementById('docTypeIcon');
    const badge = document.getElementById('docModalBadge');

    if (docType === 'Thesis') {
        iconBox.style.background = 'rgba(16, 185, 129, 0.12)';
        iconBox.style.color = '#10b981';
        badge.style.background = 'rgba(16, 185, 129, 0.15)';
        badge.style.color = '#10b981';
        icon.className = 'bi bi-file-earmark-arrow-down-fill fs-5';
    } else {
        iconBox.style.background = 'rgba(59, 130, 246, 0.12)';
        iconBox.style.color = '#3b82f6';
        badge.style.background = 'rgba(59, 130, 246, 0.15)';
        badge.style.color = '#3b82f6';
        icon.className = 'bi bi-file-earmark-pdf-fill fs-5';
    }

    const iframe = document.getElementById('docViewerIframe');
    iframe.src = fileUrl;

    new bootstrap.Modal(document.getElementById('documentViewerModal')).show();
}

// Reset iframe when modal closes to prevent memory leaks and background audio/rendering
document.getElementById('documentViewerModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('docViewerIframe').src = 'about:blank';
});

function showStudentPhotoModal(src, name, roll) {
    document.getElementById('modalStudentPhoto').src = src;
    document.getElementById('modalStudentName').innerText = name;
    document.getElementById('modalStudentRoll').innerText = roll;
    new bootstrap.Modal(document.getElementById('studentPhotoModal')).show();
}

function filterProjects(category, btn) {
    if (btn) {
        document.querySelectorAll('.btn-filter-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    const rows = document.querySelectorAll('#department-projects-table tbody tr');
    rows.forEach(row => {
        const cat = row.getAttribute('data-stage-cat');
        if (category === 'all' || cat === category) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
