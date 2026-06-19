<!-- Student Group & Members View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$member1Val = isset($groupMembers[0]) ? $groupMembers[0]['student_id'] : '';
$member2Val = isset($groupMembers[1]) ? $groupMembers[1]['student_id'] : '';
?>

<?php if (!$group): ?>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 p-5 text-center bg-white">
                <i class="bi bi-people-fill text-primary mb-3" style="font-size: 3.5rem;"></i>
                <h4 class="fw-bold text-dark mb-2">No Project Group Found</h4>
                <p class="text-muted small mb-4">You are not currently registered in any project group. In our updated FYP workflow, you do not create a group manually. Instead, you directly submit your project proposal, choose your supervisor, and specify your group members there.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php echo $basePath; ?>/student/proposal" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-file-earmark-plus-fill me-1"></i> Go to Project Proposal Page
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    
    <div class="row g-4">
        <!-- Members List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Your Team</small>
                        <h4 class="fw-bold text-dark m-0">Group Members Directory</h4>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-1.5 font-monospace fs-6"><?php echo htmlspecialchars($group['group_code'] ?? 'Group ID Pending'); ?></span>
                </div>

                <div class="table-responsive">
                    <table class="table border-0 align-middle m-0" style="box-shadow: none;">
                        <thead>
                            <tr>
                                <th>Student details</th>
                                <th>Department</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($members as $m): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                        <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-primary border-opacity-25" style="width: 40px; height: 40px; object-fit: cover;" alt="Avatar">
                                        <div>
                                            <div class="fw-semibold text-dark">
                                                <?php echo htmlspecialchars($m['name']); ?>
                                                <?php if($m['user_id'] == $group['created_by']): ?>
                                                    <span class="badge bg-primary-subtle text-primary small ms-1" style="font-size: 0.55rem;">Leader</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted font-monospace"><?php echo htmlspecialchars($m['student_id']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small text-muted"><?php echo htmlspecialchars($m['department']); ?></span></td>
                                <td><span class="small font-monospace"><?php echo htmlspecialchars($m['phone'] ?? 'N/A'); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($group['created_by'] == $_SESSION['user_id']): ?>
                <!-- Edit Group Members Card for Leader -->
                <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                        <h5 class="fw-bold text-dark m-0"><i class="bi bi-people-fill text-primary me-1"></i> Manage Group Members</h5>
                    </div>
                    <p class="text-muted small mb-4">As the Group Leader, you can update your team members here. Enter their Student ID or registered Email Address.</p>
                    
                    <form action="<?php echo $basePath; ?>/student/group/update-members" method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="member1" class="form-label small fw-semibold text-secondary">Project Member 1</label>
                                <input type="text" class="form-control bg-light" id="member1" name="member1" value="<?php echo htmlspecialchars($member1Val); ?>" placeholder="Enter Email or Student ID">
                                <div class="form-text small text-muted" style="font-size: 0.7rem;">Leave blank to remove member.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="member2" class="form-label small fw-semibold text-secondary">Project Member 2</label>
                                <input type="text" class="form-control bg-light" id="member2" name="member2" value="<?php echo htmlspecialchars($member2Val); ?>" placeholder="Enter Email or Student ID">
                                <div class="form-text small text-muted" style="font-size: 0.7rem;">Leave blank to remove member.</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-save-fill me-1"></i> Save Members
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Supervisor & Group Info Panel -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3">Group Information</h5>
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold m-0 text-uppercase" style="font-size: 0.65rem;">Assigned Supervisor</label>
                    <div class="fw-bold text-dark mt-1">
                        <i class="bi bi-person-badge-fill text-success me-1"></i>
                        <?php echo htmlspecialchars($group['supervisor_name'] ?? 'Not Assigned Yet'); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold m-0 text-uppercase" style="font-size: 0.65rem;">Project Title</label>
                    <div class="small text-dark fw-medium mt-1">
                        <?php echo htmlspecialchars($group['project_title'] ?? 'No project title set'); ?>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label small text-secondary fw-semibold m-0 text-uppercase" style="font-size: 0.65rem;">Progress Stage</label>
                    <div class="mt-1">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                            <?php echo htmlspecialchars($group['progress_stage']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white" style="border-left: 4px solid var(--accent-color) !important;">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Leadership Notice</h6>
                <div class="small text-muted mt-2 leading-relaxed">
                    Only the **Group Leader** can modify members or re-assign the supervisor. These details can be updated on the **Project Proposal** form before it is approved.
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
