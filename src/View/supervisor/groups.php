<!-- Supervisor Assigned Groups View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<div class="mb-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 border-bottom pb-3">
    <div>
        <h4 class="fw-bold text-dark m-0">Your Supervised FYP Groups</h4>
        <p class="text-muted m-0 small">Monitor project descriptions, team details, and enter supervision marks manually</p>
    </div>
    <?php
    $anySupervisionHidden = false;
    $hasSupervisionGrades = false;
    foreach ($groups as $g) {
        if (isset($g['supervision_marks']) && $g['supervision_marks'] !== null) {
            $hasSupervisionGrades = true;
            if ($g['show_supervision_to_student'] == 0) {
                $anySupervisionHidden = true;
            }
        }
    }
    $globalSupervisionShowAction = ($anySupervisionHidden || !$hasSupervisionGrades) ? 1 : 0;
    ?>
    <form action="<?php echo $basePath; ?>/supervisor/groups/toggle-visibility" method="POST" class="m-0">
        <input type="hidden" name="show" value="<?php echo $globalSupervisionShowAction; ?>">
        <button type="submit" class="btn btn-sm <?php echo $globalSupervisionShowAction ? 'btn-outline-primary' : 'btn-success text-white'; ?> rounded-pill px-4 py-2 fw-semibold shadow-sm">
            <i class="bi <?php echo $globalSupervisionShowAction ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?> me-2"></i>
            <?php echo $globalSupervisionShowAction ? 'Show All Supervision Marks to Students' : 'Hide All Supervision Marks from Students'; ?>
        </button>
    </form>
</div>

<div class="row g-4">
    <?php foreach($groups as $g): ?>
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <span class="badge bg-primary text-uppercase mb-1" style="font-size: 0.65rem;"><?php echo htmlspecialchars($g['group_code'] ?? 'Group ID Pending'); ?></span>
                        <h5 class="fw-bold text-dark m-0 leading-snug"><?php echo htmlspecialchars($g['project_title']); ?></h5>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                        <?php echo htmlspecialchars($g['progress_stage']); ?>
                    </span>
                </div>
                
                <p class="text-muted small mb-3" style="text-align: justify;"><?php echo nl2br(htmlspecialchars($g['project_description'])); ?></p>
                
                <!-- Manual Grading & Progress summary -->
                <div class="bg-light rounded-3 p-3 mb-4 mt-auto">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                        <span class="fw-bold text-secondary small text-uppercase">Evaluation & Marks</span>
                        <button class="btn btn-xs btn-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#gradeGroupModal<?php echo $g['id']; ?>">
                            <i class="bi bi-pencil-square me-1"></i> Edit Marks
                        </button>
                    </div>
                    <div class="row g-2 text-center mb-2">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block" style="font-size: 0.65rem;">Supervision</small>
                            <span class="fw-bold text-dark small"><?php echo number_format($g['supervision_marks'] ?? 0, 0); ?>/45</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size: 0.65rem;">Total base</small>
                            <span class="fw-bold text-primary small"><?php echo number_format($g['total_marks'] ?? 0, 0); ?>/200</span>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <h6 class="fw-bold text-secondary mb-2 small text-uppercase">Team Members</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle m-0">
                            <tbody>
                                <?php foreach($g['members'] as $m): ?>
                                <tr>
                                    <td class="ps-0 border-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                                            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-primary border-opacity-25" style="width: 32px; height: 32px; object-fit: cover;" alt="Avatar">
                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    <?php echo htmlspecialchars($m['name']); ?>
                                                    <?php if($m['user_id'] == $g['created_by']): ?>
                                                        <span class="badge bg-secondary-subtle text-secondary small ms-1" style="font-size: 0.55rem;">Leader</span>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Roll: <?php echo htmlspecialchars($m['student_id']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0"><span class="small text-muted"><?php echo htmlspecialchars($m['email']); ?></span></td>
                                    <td class="pe-0 border-0 text-end"><span class="small font-monospace"><?php echo htmlspecialchars($m['phone'] ?? 'N/A'); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MANUAL GRADING MODAL -->
        <div class="modal fade" id="gradeGroupModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 rounded-3 shadow">
                    <div class="modal-header bg-dark text-white border-0 py-2.5">
                        <h6 class="modal-title fw-bold">Manual Group Grading</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?php echo $basePath; ?>/supervisor/groups/grade" method="POST">
                        <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                        <div class="modal-body p-3 text-start">
                            <p class="text-muted small mb-3">Review work manually and enter marks. Overall totals and grades will be updated automatically.</p>
                            
                            <div class="mb-3">
                                <label for="supervision_marks_<?php echo $g['id']; ?>" class="form-label small text-secondary fw-semibold">Supervision Marks (Max 45)</label>
                                <input type="number" class="form-control bg-light" id="supervision_marks_<?php echo $g['id']; ?>" name="supervision_marks" min="0" max="45" step="1" value="<?php echo htmlspecialchars(number_format($g['supervision_marks'] ?? 0, 0)); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                            <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Save Marks</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if(empty($groups)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 p-5 text-center bg-white">
                <i class="bi bi-people-fill text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-muted m-0">No assigned student groups found.</h5>
            </div>
        </div>
    <?php endif; ?>
</div>
