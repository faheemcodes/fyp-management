<!-- Admin FYP Groups View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <div class="mb-4">
        <h4 class="fw-bold text-dark m-0">Final Year Project Groups</h4>
        <p class="text-muted m-0 small">Monitor project status and assign supervisors to student groups</p>
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
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr data-supervisor="<?php echo htmlspecialchars($g['supervisor_id'] ? $g['supervisor_name'] : 'unassigned'); ?>" data-stage="<?php echo htmlspecialchars($g['progress_stage']); ?>">
                    <td class="fw-bold text-primary" style="font-size: 1.1rem;">
                        <?php echo htmlspecialchars($g['group_code']); ?>
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
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No project groups have been registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
