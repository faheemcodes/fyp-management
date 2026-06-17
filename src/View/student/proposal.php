<!-- Student Proposal Submission View -->
<?php
$titleVal = $project['title'] ?? '';
$abstractVal = $proposal['abstract'] ?? $project['description'] ?? '';
$supervisorIdVal = $project['supervisor_id'] ?? '';
$member1Val = isset($groupMembers[0]) ? $groupMembers[0]['student_id'] : '';
$member2Val = isset($groupMembers[1]) ? $groupMembers[1]['student_id'] : '';

$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<div class="row g-4 justify-content-center">
    <?php if ($group && !$isLeader): ?>
        <!-- Group Member View (Read-Only) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Your Group Project</small>
                        <h4 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($project['title'] ?? 'FYP Project'); ?></h4>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-semibold"><?php echo htmlspecialchars($proposal['status'] ?? 'Draft'); ?></span>
                </div>

                <div class="alert alert-info rounded-3 border-0 bg-info bg-opacity-10 text-info mb-4" role="alert">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <span>You are a group member. Only the group leader (creator) has permission to edit the proposal, change the supervisor, or manage team members.</span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-secondary mb-2 small text-uppercase">Project Abstract / Summary</h6>
                    <div class="p-3 bg-light rounded-3 text-muted small leading-relaxed" style="text-align: justify;">
                        <?php echo nl2br(htmlspecialchars($abstractVal)); ?>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary mb-1 small text-uppercase">Supervisor</h6>
                        <div class="fw-semibold text-dark">
                            <i class="bi bi-person-badge text-success me-1"></i>
                            <?php 
                            $supName = 'Unassigned';
                            foreach ($supervisors as $s) {
                                if ($s['user_id'] == $supervisorIdVal) {
                                    $supName = $s['name'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($supName);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary mb-1 small text-uppercase">Group Code</h6>
                        <div class="fw-semibold text-primary font-monospace">
                            <i class="bi bi-folder-fill me-1"></i>
                            <?php echo htmlspecialchars($group['group_code']); ?>
                        </div>
                    </div>
                </div>

                <?php if ($proposal && $proposal['file_path']): ?>
                    <div class="mb-4 border-top pt-3">
                        <h6 class="fw-bold text-secondary mb-2 small text-uppercase">Proposal Document</h6>
                        <a href="<?php echo $basePath; ?><?php echo htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="btn btn-outline-primary rounded-pill btn-sm px-4">
                            <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Download Proposal File
                        </a>
                    </div>
                <?php endif; ?>

                <div class="border-top pt-3">
                    <h6 class="fw-bold text-secondary mb-3 small text-uppercase">Group Members Directory</h6>
                    <div class="table-responsive">
                        <table class="table border-0 align-middle m-0" style="box-shadow: none;">
                            <tbody>
                                <!-- Leader -->
                                <tr>
                                    <td class="ps-0 border-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: bold; font-size: 0.85rem;">L</div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($group['creator_name'] ?? 'Group Leader'); ?> <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 0.6rem;">Leader</span></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0"><span class="small font-monospace text-muted"><?php echo htmlspecialchars($group['creator_student_id'] ?? ''); ?></span></td>
                                </tr>
                                <!-- Other members -->
                                <?php foreach($groupMembers as $m): ?>
                                    <tr>
                                        <td class="ps-0 border-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: bold; font-size: 0.85rem;">M</div>
                                                <div>
                                                    <div class="fw-semibold text-dark"><?php echo htmlspecialchars($m['name']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-0"><span class="small font-monospace text-muted"><?php echo htmlspecialchars($m['student_id']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Leader / Creator Form View -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Milestone 1</small>
                        <h4 class="fw-bold text-dark m-0">Project Proposal Submission</h4>
                    </div>
                    <?php if($proposal): ?>
                        <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-semibold"><?php echo htmlspecialchars($proposal['status']); ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary rounded-pill px-3 py-1.5 fw-semibold">Draft Mode</span>
                    <?php endif; ?>
                </div>

                <?php if ($proposal && $proposal['status'] === 'Approved'): ?>
                    <div class="alert alert-success rounded-3 p-4 border-0 mb-0" style="background-color: #d1e7dd; color: #0f5132;">
                        <h5 class="fw-bold mb-2"><i class="bi bi-patch-check-fill me-1"></i> Proposal Approved!</h5>
                        <p class="small mb-2">Your final year project proposal has been formally approved by your supervisor.</p>
                        <p class="small mb-0"><strong>Your Assigned Supervisor:</strong> 
                            <?php 
                            foreach ($supervisors as $s) {
                                if ($s['user_id'] == $supervisorIdVal) {
                                    echo htmlspecialchars($s['name']);
                                    break;
                                }
                            }
                            ?>
                        </p>
                    </div>
                <?php else: ?>
                    <form action="<?php echo $basePath; ?>/student/proposal/submit" method="POST" enctype="multipart/form-data">
                        
                        <!-- Title & Supervisor Row -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="title" class="form-label small fw-semibold text-secondary">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light" id="title" name="title" value="<?php echo htmlspecialchars($titleVal); ?>" required placeholder="e.g. AI-Powered Smart Grid Analytics">
                            </div>
                            <div class="col-md-5">
                                <label for="supervisor_id" class="form-label small fw-semibold text-secondary">Choose Supervisor <span class="text-danger">*</span></label>
                                <select class="form-select bg-light" id="supervisor_id" name="supervisor_id" required>
                                    <option value="" disabled <?php echo empty($supervisorIdVal) ? 'selected' : ''; ?>>Select Faculty Member</option>
                                    <?php foreach ($supervisors as $s): ?>
                                        <option value="<?php echo $s['user_id']; ?>" <?php echo $s['user_id'] == $supervisorIdVal ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($s['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Abstract -->
                        <div class="mb-3">
                            <label for="abstract" class="form-label small fw-semibold text-secondary">Project Abstract / Summary <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light" id="abstract" name="abstract" rows="6" required placeholder="Write a brief summary of the proposed project scope, goals, and technology stack..."><?php echo htmlspecialchars($abstractVal); ?></textarea>
                        </div>

                        <!-- Members selection -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="member1" class="form-label small fw-semibold text-secondary">Project Member 1 (Optional)</label>
                                <input type="text" class="form-control bg-light" id="member1" name="member1" value="<?php echo htmlspecialchars($member1Val); ?>" placeholder="Enter Email or Roll No">
                                <div class="form-text small text-muted" style="font-size: 0.7rem;">Leave blank if you have no team members yet.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="member2" class="form-label small fw-semibold text-secondary">Project Member 2 (Optional)</label>
                                <input type="text" class="form-control bg-light" id="member2" name="member2" value="<?php echo htmlspecialchars($member2Val); ?>" placeholder="Enter Email or Roll No">
                                <div class="form-text small text-muted" style="font-size: 0.7rem;">Leave blank if your team size is 1 or 2.</div>
                            </div>
                        </div>

                        <!-- Proposal file -->
                        <div class="mb-4">
                            <label for="proposal_file" class="form-label small fw-semibold text-secondary">Upload Proposal File (PDF, DOC, DOCX) <?php echo $proposal ? '' : '<span class="text-danger">*</span>'; ?></label>
                            <input type="file" class="form-control bg-light" id="proposal_file" name="proposal_file" <?php echo $proposal ? '' : 'required'; ?>>
                            <?php if($proposal && $proposal['file_path']): ?>
                                <div class="mt-2 small text-muted">
                                    <i class="bi bi-file-earmark-check text-primary me-1"></i> Currently uploaded: 
                                    <a href="<?php echo $basePath; ?><?php echo htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="fw-semibold text-decoration-none">
                                        Download Current Proposal File
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <?php echo $proposal ? 'Resubmit Proposal' : 'Submit Proposal'; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Supervisor Feedback & Guidelines -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3">Supervisor Feedback</h5>
            <?php if ($proposal && $proposal['feedback']): ?>
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center gap-2 mb-2 text-dark">
                        <i class="bi bi-person-badge-fill text-success"></i>
                        <span class="fw-semibold small">Comments / Requests</span>
                    </div>
                    <p class="small text-muted mb-0 leading-relaxed"><?php echo nl2br(htmlspecialchars($proposal['feedback'])); ?></p>
                    <div class="text-end mt-2">
                        <small class="x-small text-muted" style="font-size: 0.7rem;">Last updated: <?php echo date('M d, Y', strtotime($proposal['updated_at'])); ?></small>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-left-dots text-secondary" style="font-size: 2rem;"></i>
                    <p class="small mt-2 mb-0">No review remarks or revision requests have been registered yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white" style="border-left: 4px solid var(--primary-color) !important;">
            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-info-circle-fill text-primary me-1"></i> Proposal Submission Rules</h6>
            <div class="small text-muted mt-2 leading-relaxed">
                <p class="mb-2">1. Submitting the proposal automatically makes you the **Group Leader**.</p>
                <p class="mb-2">2. You can invite up to **2 other members** to your project group during this step.</p>
                <p class="mb-2">3. **Only the Group Leader** can modify the proposal, update members, or change the selected supervisor.</p>
                <p class="mb-0">4. Group members have read-only view access to check proposal status and members directory.</p>
            </div>
        </div>
    </div>
</div>
