<!-- Admin FYP Groups View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Final Year Project Groups</h4>
            <p class="text-muted m-0 small">Monitor project status, edit team details, and manage supervisors</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-start align-self-sm-center" data-bs-toggle="modal" data-bs-target="#createGroupModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Create New Group
        </button>
    </div>

    <!-- Filters and Search Controls -->
    <div class="row g-3 mb-4 align-items-center">
        <!-- Search Input -->
        <div class="col-md-4">
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0 table-search" placeholder="Search groups by code, title, members..." data-target="groups-table">
            </div>
        </div>
        <!-- Supervisor Filter -->
        <div class="col-md-4">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="supervisor" data-target="groups-table">
                <option value="all">All Supervisors</option>
                <option value="unassigned">Unassigned</option>
                <?php foreach($supervisors as $s): ?>
                    <option value="<?php echo htmlspecialchars($s['name']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Progress Stage Filter -->
        <div class="col-md-4">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="stage" data-target="groups-table">
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

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="groups-table">
            <thead>
                <tr>
                    <th>Group Code</th>
                    <th>Project Details</th>
                    <th>Team Members</th>
                    <th>Supervisor</th>
                    <th>Progress Stage</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr data-supervisor="<?php echo htmlspecialchars($g['supervisor_id'] ? $g['supervisor_name'] : 'unassigned'); ?>" data-stage="<?php echo htmlspecialchars($g['progress_stage']); ?>">
                    <td class="fw-bold text-primary" style="font-size: 1.1rem;">
                        <?php echo htmlspecialchars($g['group_code'] ?? 'Group ID Pending'); ?>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark mb-1" style="max-width: 250px; white-space: normal;"><?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?></div>
                        <small class="text-muted d-block" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($g['project_description'] ?? ''); ?>">
                            <?php echo htmlspecialchars($g['project_description'] ?? 'No abstract/description'); ?>
                        </small>
                    </td>
                    <td>
                        <ul class="list-unstyled m-0 p-0 small">
                            <?php foreach($g['members'] as $m): ?>
                                <li>
                                    <i class="bi bi-person me-1 text-secondary"></i>
                                    <strong><?php echo htmlspecialchars($m['name']); ?></strong> 
                                    <span class="text-muted font-monospace">(<?php echo htmlspecialchars($m['student_id']); ?>)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>
                        <?php if($g['supervisor_id']): ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold text-dark"><i class="bi bi-person-badge text-success me-1"></i><?php echo htmlspecialchars($g['supervisor_name']); ?></span>
                                <button class="btn btn-link p-0 text-decoration-none small" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#assignModal<?php echo $g['id']; ?>">Change</button>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#assignModal<?php echo $g['id']; ?>">
                                <i class="bi bi-plus-circle me-1"></i> Assign Supervisor
                            </button>
                        <?php endif; ?>

                        <!-- Assign Modal -->
                        <div class="modal fade" id="assignModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content rounded-3 border-0 shadow">
                                    <div class="modal-header bg-dark text-white border-0 py-2.5">
                                        <h6 class="modal-title fw-bold">Assign Supervisor</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/assign" method="POST">
                                        <div class="modal-body p-3">
                                            <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                                            <div class="mb-3 text-start">
                                                <label class="form-label small text-secondary">Select Faculty Member</label>
                                                <select class="form-select bg-light" name="supervisor_id" required>
                                                    <option value="">-- Choose --</option>
                                                    <?php foreach($supervisors as $s): ?>
                                                        <option value="<?php echo $s['user_id']; ?>" <?php echo $g['supervisor_id'] == $s['user_id'] ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($s['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                            <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1.5 small fw-semibold">
                            <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                        <?php if($g['project_status'] === 'Submitted'): ?>
                            <small class="d-block text-warning mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i>Proposal Pending</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Manage
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                <li>
                                    <button class="dropdown-item btn-edit-group" type="button" data-bs-toggle="modal" data-bs-target="#editGroupModal"
                                        data-id="<?php echo $g['id']; ?>"
                                        data-code="<?php echo htmlspecialchars($g['group_code'] ?? ''); ?>"
                                        data-stage="<?php echo htmlspecialchars($g['progress_stage']); ?>">
                                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Group Details
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item btn-manage-members" type="button" data-bs-toggle="modal" data-bs-target="#manageMembersModal"
                                        data-id="<?php echo $g['id']; ?>"
                                        data-members='<?php echo json_encode(array_column($g['members'], "user_id")); ?>'>
                                        <i class="bi bi-people-fill me-2 text-success"></i>Manage Members
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item btn-edit-project" type="button" data-bs-toggle="modal" data-bs-target="#editProjectModal"
                                        data-id="<?php echo $g['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($g['project_title'] ?? ''); ?>"
                                        data-desc="<?php echo htmlspecialchars($g['project_description'] ?? ''); ?>"
                                        data-status="<?php echo htmlspecialchars($g['project_status'] ?? 'Draft'); ?>"
                                        data-supervisor="<?php echo htmlspecialchars($g['supervisor_id'] ?? ''); ?>">
                                        <i class="bi bi-journal-text me-2 text-info"></i>Edit Project/Proposal
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item btn-edit-grades" type="button" data-bs-toggle="modal" data-bs-target="#editGradesModal"
                                        data-id="<?php echo $g['id']; ?>"
                                        data-prop="<?php echo htmlspecialchars($g['proposal_marks'] ?? '0.00'); ?>"
                                        data-def="<?php echo htmlspecialchars($g['proposal_defense_marks'] ?? ''); ?>"
                                        data-prog="<?php echo htmlspecialchars($g['progress_presentation_marks'] ?? ''); ?>"
                                        data-final="<?php echo htmlspecialchars($g['final_presentation_marks'] ?? ''); ?>"
                                        data-sup="<?php echo htmlspecialchars($g['supervision_marks'] ?? ''); ?>">
                                        <i class="bi bi-patch-check-fill me-2 text-warning"></i>Edit Grades/Marks
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups/delete?id=<?php echo $g['id']; ?>" onclick="return confirm('Are you sure you want to permanently delete this group and all its records? This cannot be undone.');">
                                        <i class="bi bi-trash-fill me-2"></i>Delete Group
                                    </a>
                                </li>
                            </ul>
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
                                <option value="<?php echo $st['user_id']; ?>">
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
                                <option value="<?php echo $st['user_id']; ?>">
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
                                    <option value="<?php echo $s['user_id']; ?>">
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
                        <div class="col-md-6 mb-3">
                            <label for="editGradesProp" class="form-label small fw-semibold text-secondary">Proposal Submission (0-10)</label>
                            <input type="number" class="form-control bg-light" id="editGradesProp" name="proposal_marks" min="0" max="10" step="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editGradesDef" class="form-label small fw-semibold text-secondary">Proposal Defence (0-30)</label>
                            <input type="number" class="form-control bg-light" id="editGradesDef" name="proposal_defense_marks" min="0" max="30" step="1">
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
