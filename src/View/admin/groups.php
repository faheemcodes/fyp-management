<!-- Admin FYP Groups View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<style>
/* ─── Hero Banner Styles ─── */
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
        overflow: visible !important;
    }
}
@media (max-width: 768px) {
    .table-responsive {
        padding-bottom: 120px; /* Space for dropdowns on mobile */
    }
}

/* ─── Modern Table Styles ─── */
.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.modern-table thead th {
    background: var(--form-bg);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-secondary);
    font-weight: 700;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}
.modern-table tbody td {
    padding: 16px 24px;
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
.status-pill {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    display: inline-block;
}

@media (max-width: 768px) {
    .modern-table { min-width: 900px; }
}

.avatar-stack {
    display: flex;
    align-items: center;
}
.avatar-stack .avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid var(--card-bg);
    margin-left: -10px;
    background: var(--form-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    position: relative;
    transition: transform 0.2s;
}
.avatar-stack .avatar-circle:first-child {
    margin-left: 0;
}
.avatar-stack .avatar-circle:hover {
    transform: translateY(-2px);
    z-index: 10;
}
.action-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
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
.action-btn.btn-edit-group:hover { background: rgba(59,130,246,0.1); color: #3b82f6; border-color: rgba(59,130,246,0.2); }
.action-btn.btn-edit-project:hover { background: rgba(139,92,246,0.1); color: #8b5cf6; border-color: rgba(139,92,246,0.2); }
.action-btn.btn-edit-grades:hover { background: rgba(245,158,11,0.1); color: #f59e0b; border-color: rgba(245,158,11,0.2); }
.action-btn.btn-delete-group:hover { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.2); }
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Project Groups Overview</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Monitor project status, edit team details, and manage supervisors</p>
            </div>
        </div>
    </div>
</div>

<div class="grp-section" style="overflow: visible !important;">
    <!-- Filters and Search Controls -->
    <div class="grp-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-4 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search groups by code, title..." data-target="groups-table">
                </div>
            </div>
            <!-- Supervisor Filter -->
            <div class="col-md-4">
                <select class="form-select table-filter shadow-sm rounded-pill border border-light-subtle" data-column="supervisor" data-target="groups-table">
                    <option value="all">All Supervisors</option>
                    <option value="unassigned">Unassigned</option>
                    <?php foreach($supervisors as $s): ?>
                        <option value="<?php echo htmlspecialchars($s['name']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Progress Stage Filter -->
            <div class="col-md-4 pe-0">
                <select class="form-select table-filter shadow-sm rounded-pill border border-light-subtle" data-column="stage" data-target="groups-table">
                    <option value="all">All Progress Stages</option>
                    <option value="Group Created">Group Created</option>
                    <option value="Proposal Submitted">Proposal Submitted</option>
                    <option value="Proposal Approved">Proposal Approved</option>
                    <option value="Proposal Defence Presentation Completed">Proposal Defence Presentation Completed</option>
                    <option value="FYP Progress Presentation Completed">FYP Progress Presentation Completed</option>
                    <option value="Final Presentation Completed">Final Presentation Completed</option>
                    <option value="Final Grading Completed">Final Grading Completed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive-md">
        <table class="table modern-table m-0" id="groups-table">
            <thead>
                <tr>
                    <th class="ps-4">Group Code</th>
                    <th>Project Details</th>
                    <th>Team Members</th>
                    <th>Supervisor</th>
                    <th>Progress Stage</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr data-supervisor="<?php echo htmlspecialchars($g['supervisor_id'] ? $g['supervisor_name'] : 'unassigned'); ?>" data-stage="<?php echo htmlspecialchars($g['progress_stage']); ?>">
                    <td class="ps-4 fw-bold" style="color: #10b981; font-size: 0.95rem; font-family: monospace;">
                        <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark mb-1" style="max-width: 250px; font-size: 0.9rem;"><?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?></div>
                        <small class="text-muted d-block" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.75rem;" title="<?php echo htmlspecialchars($g['project_description'] ?? ''); ?>">
                            <?php echo htmlspecialchars($g['project_description'] ?? 'No abstract/description'); ?>
                        </small>
                    </td>
                    <td>
                        <div class="avatar-stack">
                            <?php foreach($g['members'] as $idx => $m): ?>
                                <?php if($idx < 3): ?>
                                    <div class="avatar-circle" title="<?php echo htmlspecialchars($m['name']); ?> (<?php echo htmlspecialchars($m['student_id']); ?>)">
                                        <?php echo strtoupper(substr($m['name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if(count($g['members']) > 3): ?>
                                <div class="avatar-circle" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                    +<?php echo count($g['members']) - 3; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if($g['supervisor_id']): ?>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><i class="bi bi-person-badge text-success me-1"></i><?php echo htmlspecialchars($g['supervisor_name']); ?></span>
                                <button class="btn btn-link p-0 text-decoration-none text-start text-primary" style="font-size: 0.7rem; margin-top: 2px;" data-bs-toggle="modal" data-bs-target="#assignModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">Change Supervisor</button>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3 shadow-sm" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#assignModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                Assign Supervisor
                            </button>
                        <?php endif; ?>

                        <!-- Assign Modal -->
                        <div class="modal fade admin-modal" id="assignModal<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header py-3">
                                        <h6 class="modal-title fw-bold m-0">Assign Supervisor</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/assign" method="POST">
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="group_id" value="<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                            <div class="mb-3 text-start">
                                                <label class="form-label small fw-semibold text-secondary">Select Faculty Member</label>
                                                <select class="form-select bg-light" name="supervisor_id" required>
                                                    <option value="">-- Choose --</option>
                                                    <?php foreach($supervisors as $s): ?>
                                                        <option value="<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" <?php echo $g['supervisor_id'] == $s['user_id'] ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($s['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-3 bg-light rounded-bottom text-end border-0">
                                            <button type="button" class="btn btn-secondary rounded-pill btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4">Assign</button>
                                        </div>
                                    
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
                                </div>
                            </div>
                        </div>

                    </td>
                    <td>
                        <span class="status-pill" style="background: rgba(59,130,246,0.1); color: #2563eb;">
                            <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                        <?php if($g['project_status'] === 'Submitted'): ?>
                            <small class="d-block text-warning mt-1 fw-bold" style="font-size: 0.65rem;"><i class="bi bi-exclamation-circle-fill me-1"></i>Proposal Pending</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-1 justify-content-end">
                            <button class="action-btn btn-edit-group" title="Edit Group Details" data-bs-toggle="modal" data-bs-target="#editGroupModal"
                                data-id="<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>"
                                data-code="<?php echo htmlspecialchars($g['group_code'] ?? ''); ?>"
                                data-stage="<?php echo htmlspecialchars($g['progress_stage']); ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="action-btn btn-edit-project" title="Edit Project/Proposal" data-bs-toggle="modal" data-bs-target="#editProjectModal"
                                data-id="<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>"
                                data-title="<?php echo htmlspecialchars($g['project_title'] ?? ''); ?>"
                                data-desc="<?php echo htmlspecialchars($g['project_description'] ?? ''); ?>"
                                data-status="<?php echo htmlspecialchars($g['project_status'] ?? 'Draft'); ?>"
                                data-supervisor="<?php echo htmlspecialchars($g['supervisor_id'] ?? ''); ?>">
                                <i class="bi bi-file-text-fill"></i>
                            </button>
                            <button class="action-btn btn-edit-grades" title="Edit Grades/Marks" data-bs-toggle="modal" data-bs-target="#editGradesModal"
                                data-id="<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>"
                                data-def="<?php echo htmlspecialchars($g['proposal_defense_marks'] ?? ''); ?>"
                                data-prog="<?php echo htmlspecialchars($g['progress_presentation_marks'] ?? ''); ?>"
                                data-final="<?php echo htmlspecialchars($g['final_presentation_marks'] ?? ''); ?>"
                                data-sup="<?php echo htmlspecialchars($g['supervision_marks'] ?? ''); ?>">
                                <i class="bi bi-award-fill"></i>
                            </button>
                            <a class="action-btn btn-delete-group text-danger text-decoration-none" title="Delete Group" href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/delete?id=<?php echo htmlspecialchars((string)($g['id']), ENT_QUOTES, 'UTF-8'); ?>" onclick="return confirm('Are you sure you want to permanently delete this group and all its records? This cannot be undone.');">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No project groups have been registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Group Modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Create New Student Group</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/create" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="createModalCode" class="form-label small fw-semibold text-secondary">Group Code (leave blank to auto-generate)</label>
                        <input type="text" class="form-control bg-light" id="createModalCode" name="group_code" placeholder="e.g. 2k23-SWEM-1">
                    </div>
                    
                    <div class="mb-3">
                        <label for="createModalLeader" class="form-label small fw-semibold text-secondary">Group Leader (Student)*</label>
                        <select class="form-select bg-light" id="createModalLeader" name="created_by" required>
                            <option value="">-- Choose Student --</option>
                            <?php foreach($students as $st): ?>
                                <option value="<?php echo htmlspecialchars((string)($st['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($st['name']); ?> (<?php echo htmlspecialchars($st['student_id']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="createModalStage" class="form-label small fw-semibold text-secondary">Initial Progress Stage</label>
                        <select class="form-select bg-light" id="createModalStage" name="progress_stage" required>
                            <option value="Group Created">Group Created</option>
                            <option value="Proposal Submitted">Proposal Submitted</option>
                            <option value="Proposal Approved">Proposal Approved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Create Group</button>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>

<!-- Edit Group Modal -->
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Group Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/edit" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" id="editGroupModalId" name="id">
                    
                    <div class="mb-3">
                        <label for="editGroupModalCode" class="form-label small fw-semibold text-secondary">Group Code</label>
                        <input type="text" class="form-control bg-light" id="editGroupModalCode" name="group_code" required>
                    </div>

                    <div class="mb-3">
                        <label for="editGroupModalStage" class="form-label small fw-semibold text-secondary">Progress Stage</label>
                        <select class="form-select bg-light" id="editGroupModalStage" name="progress_stage" required>
                            <option value="Group Created">Group Created</option>
                            <option value="Proposal Submitted">Proposal Submitted</option>
                            <option value="Proposal Approved">Proposal Approved</option>
                            <option value="Proposal Defence Presentation Completed">Proposal Defence Presentation Completed</option>
                            <option value="FYP Progress Presentation Completed">FYP Progress Presentation Completed</option>
                            <option value="Final Presentation Completed">Final Presentation Completed</option>
                            <option value="Final Grading Completed">Final Grading Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Save Changes</button>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>

<!-- Manage Members Modal -->
<div class="modal fade" id="manageMembersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Manage Group Members</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/members/update" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" id="manageMembersGroupId" name="group_id">
                    <p class="text-muted small mb-3">Select students to include in this group. Note: The group creator/leader is automatically included and cannot be removed.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Group Members</label>
                        <select class="form-select bg-light" id="manageMembersSelect" name="members[]" multiple size="8" style="height: auto;">
                            <?php foreach($students as $st): ?>
                                <option value="<?php echo htmlspecialchars((string)($st['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($st['name']); ?> (<?php echo htmlspecialchars($st['student_id']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple members.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Update Members</button>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Project / Proposal details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/projects/edit" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" id="editProjectGroupId" name="group_id">
                    
                    <div class="mb-3">
                        <label for="editProjectTitle" class="form-label small fw-semibold text-secondary">Project Title</label>
                        <input type="text" class="form-control bg-light" id="editProjectTitle" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="editProjectDesc" class="form-label small fw-semibold text-secondary">Description / Abstract</label>
                        <textarea class="form-control bg-light" id="editProjectDesc" name="description" rows="5" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editProjectStatus" class="form-label small fw-semibold text-secondary">Project Status</label>
                            <select class="form-select bg-light" id="editProjectStatus" name="status" required>
                                <option value="Draft">Draft</option>
                                <option value="Submitted">Submitted</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Revision Requested">Revision Requested</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editProjectSupervisor" class="form-label small fw-semibold text-secondary">Supervisor</label>
                            <select class="form-select bg-light" id="editProjectSupervisor" name="supervisor_id">
                                <option value="">-- Unassigned --</option>
                                <?php foreach($supervisors as $s): ?>
                                    <option value="<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($s['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Save Project</button>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>

<!-- Edit Grades Modal -->
<div class="modal fade" id="editGradesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Group Grades & Marks</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/grades/edit" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" id="editGradesGroupId" name="group_id">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-semibold text-secondary">Proposal Defense (Max 40)</label>
                            <input type="number" class="form-control bg-light" id="editGradesDef" name="proposal_defense_marks" min="0" max="40" step="1">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editGradesProg" class="form-label small fw-semibold text-secondary">Progress Presentation (0-40)</label>
                            <input type="number" class="form-control bg-light" id="editGradesProg" name="progress_presentation_marks" min="0" max="40" step="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editGradesSup" class="form-label small fw-semibold text-secondary">Supervision Marks (0-45)</label>
                            <input type="number" class="form-control bg-light" id="editGradesSup" name="supervision_marks" min="0" max="45" step="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editGradesFinal" class="form-label small fw-semibold text-secondary">Final Presentation (0-75)</label>
                        <input type="number" class="form-control bg-light" id="editGradesFinal" name="final_presentation_marks" min="0" max="75" step="1">
                    </div>
                    
                    <p class="text-muted small">Note: Total score (out of 200), percentage, grade scale, and pass/fail status will be automatically recalculated upon submission.</p>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Save Marks</button>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Group Details population
        const editGroupBtns = document.querySelectorAll('.btn-edit-group');
        editGroupBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editGroupModalId').value = this.getAttribute('data-id');
                document.getElementById('editGroupModalCode').value = this.getAttribute('data-code');
                document.getElementById('editGroupModalStage').value = this.getAttribute('data-stage');
            });
        });

        // Manage Members population
        const manageMembersBtns = document.querySelectorAll('.btn-manage-members');
        manageMembersBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('manageMembersGroupId').value = this.getAttribute('data-id');
                const members = JSON.parse(this.getAttribute('data-members') || '[]');
                
                const selectEl = document.getElementById('manageMembersSelect');
                for (let i = 0; i < selectEl.options.length; i++) {
                    const opt = selectEl.options[i];
                    opt.selected = members.includes(parseInt(opt.value));
                }
            });
        });

        // Edit Project population
        const editProjectBtns = document.querySelectorAll('.btn-edit-project');
        editProjectBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editProjectGroupId').value = this.getAttribute('data-id');
                document.getElementById('editProjectTitle').value = this.getAttribute('data-title');
                document.getElementById('editProjectDesc').value = this.getAttribute('data-desc');
                document.getElementById('editProjectStatus').value = this.getAttribute('data-status');
                document.getElementById('editProjectSupervisor').value = this.getAttribute('data-supervisor');
            });
        });

        // Edit Grades population
        const editGradesBtns = document.querySelectorAll('.btn-edit-grades');
        editGradesBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editGradesGroupId').value = this.getAttribute('data-id');
                document.getElementById('editGradesProp').value = Math.round(parseFloat(this.getAttribute('data-prop') || 0));
                
                const defVal = this.getAttribute('data-def');
                document.getElementById('editGradesDef').value = defVal !== '' ? Math.round(parseFloat(defVal)) : '';
                
                const progVal = this.getAttribute('data-prog');
                document.getElementById('editGradesProg').value = progVal !== '' ? Math.round(parseFloat(progVal)) : '';
                
                const finalVal = this.getAttribute('data-final');
                document.getElementById('editGradesFinal').value = finalVal !== '' ? Math.round(parseFloat(finalVal)) : '';
                
                const supVal = this.getAttribute('data-sup');
                document.getElementById('editGradesSup').value = supVal !== '' ? Math.round(parseFloat(supVal)) : '';
            });
        });
    });
</script>
