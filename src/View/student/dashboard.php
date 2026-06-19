<!-- Student Dashboard View -->
<?php if (!$group): ?>
    <div class="card border-0 shadow-sm rounded-3 p-5 text-center bg-white">
        <div class="mb-4">
            <i class="bi bi-people-fill text-primary" style="font-size: 4rem;"></i>
        </div>
        <h3 class="fw-bold text-dark">You are not in an FYP Group</h3>
        <p class="text-muted mx-auto mb-4" style="max-width: 500px;">To begin your Final Year Project journey, you need to form a team. You can create a new project group or request an existing group to add you.</p>
        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/student/group" class="btn btn-primary rounded-pill px-4 py-2">Create or Join a Group</a>
    </div>
<?php else: ?>
    
    <div class="row g-4">
        <!-- Project & Supervisor Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <span class="badge bg-primary text-uppercase mb-2" style="font-size: 0.7rem;"><?php echo htmlspecialchars($group['group_code'] ?? 'Group ID Pending'); ?></span>
                        <h4 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($group['project_title']); ?></h4>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 small fw-semibold">
                        <?php echo htmlspecialchars($group['project_status']); ?>
                    </span>
                </div>
                
                <h6 class="fw-bold text-secondary mb-2">Project Abstract/Description</h6>
                <p class="text-muted small leading-relaxed"><?php echo nl2br(htmlspecialchars($group['project_description'])); ?></p>
                
                <div class="row pt-3 border-top mt-3">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <h6 class="fw-bold text-secondary mb-1">Supervisor</h6>
                        <?php if($group['supervisor_name']): ?>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge text-success fs-5"></i>
                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($group['supervisor_name']); ?></span>
                            </div>
                        <?php else: ?>
                            <span class="text-muted small"><i class="bi bi-exclamation-circle text-warning me-1"></i>Awaiting Assignment by Admin</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-secondary mb-1">Current Project Stage</h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold small">
                            <?php echo htmlspecialchars($group['progress_stage']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Timeline & Deadlines Sidebar -->
        <div class="col-lg-4">
            <!-- Deadlines Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3">Upcoming Deadlines</h5>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach($deadlines as $dl): ?>
                        <li class="pb-2.5 mb-2.5 border-bottom last-border-0">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-semibold text-dark"><?php echo htmlspecialchars($dl['stage']); ?></span>
                                <?php if(strtotime($dl['deadline_date']) < time()): ?>
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5" style="font-size: 0.6rem;">Closed</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-0.5" style="font-size: 0.6rem;">Active</span>
                                <?php endif; ?>
                            </div>
                            <div class="x-small text-muted font-monospace" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar-event me-1"></i><?php echo date('F d, Y h:i A', strtotime($dl['deadline_date'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Vertical progress stages -->
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3">Your Progress Path</h5>
                
                <?php
                $stagesList = [
                    'Account Created',
                    'Group Created',
                    'Proposal Submitted',
                    'Proposal Approved',
                    'Proposal Defence Presentation Completed',
                    'FYP Progress Presentation Completed',
                    'Final Presentation Completed',
                    'Final Grading Completed'
                ];
                
                $currentIdx = array_search($group['progress_stage'], $stagesList);
                if ($currentIdx === false) $currentIdx = 1; // Default
                ?>

                <ul class="progress-timeline m-0 p-0">
                    <?php foreach($stagesList as $index => $stageName): ?>
                        <?php 
                        $statusClass = '';
                        if ($index < $currentIdx) {
                            $statusClass = 'completed';
                        } else if ($index == $currentIdx) {
                            $statusClass = 'active';
                        }
                        ?>
                        <li class="timeline-item <?php echo $statusClass; ?>">
                            <div class="small fw-semibold text-dark mb-0.5"><?php echo $stageName; ?></div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                <?php if($statusClass === 'completed'): ?>
                                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Completed</span>
                                <?php elseif($statusClass === 'active'): ?>
                                    <span class="text-primary fw-medium"><i class="bi bi-arrow-right-circle-fill me-1"></i>Current Stage</span>
                                <?php else: ?>
                                    <span class="text-muted">Pending Stage</span>
                                <?php endif; ?>
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
