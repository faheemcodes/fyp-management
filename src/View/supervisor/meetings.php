<style>
/* ─── Hero overrides ─── */
.page-hero-icon i {
    font-size: 2rem;
}
</style>

<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.15); color: #fff;">
                <i class="bi bi-calendar2-check"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1" style="font-size: 1.25rem; letter-spacing: -0.02em">Meetings Dashboard</h4>
                <p class="mb-0" style="font-size: 0.85rem; color: rgba(255,255,255,0.7)">Manage meeting requests from your assigned groups</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Active Requests & Scheduled -->
    <div class="col-lg-8">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(59,130,246,0.1);color: #3b82f6">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
                <div>
                    <h6>Pending & Scheduled</h6>
                    <small>Active meeting requests</small>
                </div>
            </div>
            
            <?php 
            $activeMeetings = array_filter($meetings, function($m) {
                return in_array($m['status'], ['Pending', 'Scheduled', 'Rescheduled']);
            });
            
            if (empty($activeMeetings)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-3 d-block mb-2 text-opacity-50 text-muted"></i>
                    <p class="text-muted mb-0 mt-2" style="font-size: 0.85rem">No active meetings.</p>
                </div>
            <?php else: ?>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach ($activeMeetings as $i => $meeting):
                        $isLast = ($i === array_key_last($activeMeetings));
                    ?>
                        <li class="<?php echo !$isLast ? 'pb-4 mb-4 border-bottom' : ''; ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1rem;"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                        <span class="badge" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.7rem;"><i class="bi bi-folder-fill me-1"></i> <?php echo htmlspecialchars($meeting['project_title']); ?></span>
                                        <span class="badge" style="background: rgba(16,185,129,0.1); color: #10b981; font-size: 0.7rem;"><i class="bi bi-people-fill me-1"></i> <?php echo htmlspecialchars($meeting['group_code'] ?? 'ID PENDING'); ?> (<?php echo htmlspecialchars(explode(' ', trim($meeting['group_leader_name'] ?? 'Group'))[0]); ?>)</span>
                                        <?php
                                            $bg = ''; $color = '';
                                            if ($meeting['status'] === 'Pending') { $bg = 'rgba(245,158,11,0.1)'; $color = '#f59e0b'; }
                                            elseif ($meeting['status'] === 'Scheduled') { $bg = 'rgba(16,185,129,0.1)'; $color = '#10b981'; }
                                            elseif ($meeting['status'] === 'Rescheduled') { $bg = 'rgba(139,92,246,0.1)'; $color = '#8b5cf6'; }
                                        ?>
                                        <span class="badge" style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; font-size: 0.7rem;"><?php echo $meeting['status']; ?></span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="color: var(--text-primary); font-size: 0.85rem">
                                        <?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?>
                                    </div>
                                    <div class="text-muted fw-medium" style="font-size: 0.75rem">
                                        <?php echo date('h:i A', strtotime($meeting['meeting_date'])); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.6"><?php echo nl2br(htmlspecialchars($meeting['agenda'])); ?></p>
                            
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-1 fw-semibold" style="color: var(--text-secondary); font-size: 0.8rem;">
                                        <i class="bi <?php echo $meeting['type'] === 'Online' ? 'bi-camera-video-fill text-primary' : 'bi-building-fill text-success'; ?>"></i>
                                        <?php echo $meeting['type']; ?>
                                    </div>
                                    <?php if (!empty($meeting['location_link'])): ?>
                                        <div class="d-flex align-items-center gap-1 fw-semibold text-primary" style="font-size: 0.8rem;">
                                            <i class="bi <?php echo $meeting['type'] === 'Online' ? 'bi-link-45deg fs-5' : 'bi-geo-alt-fill'; ?>"></i>
                                            <?php if (filter_var($meeting['location_link'], FILTER_VALIDATE_URL)): ?>
                                                <a href="<?php echo htmlspecialchars($meeting['location_link']); ?>" target="_blank" class="text-decoration-none">Join Link</a>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($meeting['location_link']); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <?php if ($meeting['status'] === 'Pending'): ?>
                                        <button class="btn btn-sm btn-light text-success fw-bold px-3 border" data-bs-toggle="modal" data-bs-target="#acceptModal<?php echo $meeting['id']; ?>" style="font-size: 0.75rem;">Accept</button>
                                        <button class="btn btn-sm btn-light text-warning fw-bold px-3 border" data-bs-toggle="modal" data-bs-target="#rescheduleModal<?php echo $meeting['id']; ?>" style="font-size: 0.75rem;">Reschedule</button>
                                        <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                            <input type="hidden" name="status" value="Cancelled">
                                            <button class="btn btn-sm btn-light text-danger fw-bold px-3 border" onclick="return confirm('Are you sure you want to decline this request?')" style="font-size: 0.75rem;">Decline</button>
                                        </form>
                                    <?php elseif ($meeting['status'] === 'Scheduled' || $meeting['status'] === 'Rescheduled'): ?>
                                        <button class="btn btn-sm btn-primary fw-bold px-3" data-bs-toggle="modal" data-bs-target="#completeModal<?php echo $meeting['id']; ?>" style="font-size: 0.75rem;">Complete</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>

                        <!-- Modals omitted for brevity, but retaining full code below -->
                        <!-- Accept Modal -->
                        <div class="modal fade" id="acceptModal<?php echo $meeting['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                        <input type="hidden" name="status" value="Scheduled">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title fw-bold">Accept Meeting</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="small text-muted mb-3">Confirming meeting for <strong><?php echo date('M d, Y h:i A', strtotime($meeting['meeting_date'])); ?></strong>.</p>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Location / Link</label>
                                                <input type="text" name="location_link" class="form-control" placeholder="<?php echo $meeting['type'] === 'Online' ? 'Zoom/Teams link' : 'Room number'; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success fw-bold px-4">Confirm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reschedule Modal -->
                        <div class="modal fade" id="rescheduleModal<?php echo $meeting['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                        <input type="hidden" name="status" value="Rescheduled">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title fw-bold">Reschedule Meeting</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">New Date & Time</label>
                                                <input type="datetime-local" name="new_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning fw-bold px-4">Propose Time</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Complete Modal -->
                        <div class="modal fade" id="completeModal<?php echo $meeting['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="<?php echo $basePath; ?>/supervisor/meetings/complete" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title fw-bold">Complete Meeting</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Meeting Minutes / Feedback Notes</label>
                                                <textarea name="supervisor_notes" class="form-control" rows="4" placeholder="Log what was discussed..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary fw-bold px-4">Complete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- History -->
    <div class="col-lg-4">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(107,114,128,0.1);color: #6b7280">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h6>History</h6>
                    <small>Past meetings</small>
                </div>
            </div>
            
            <?php 
            $pastMeetings = array_filter($meetings, function($m) {
                return in_array($m['status'], ['Completed', 'Cancelled', 'Verified']);
            });
            
            if (empty($pastMeetings)): ?>
                <div class="text-center py-4">
                    <p class="text-muted small mb-0 fw-medium">No past meetings.</p>
                </div>
            <?php else: ?>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach ($pastMeetings as $i => $meeting):
                        $isLast = ($i === array_key_last($pastMeetings));
                    ?>
                        <li class="<?php echo !$isLast ? 'pb-3 mb-3 border-bottom' : ''; ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold" style="font-size: 0.8rem; color: var(--text-primary)"><?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?></span>
                                <?php
                                    $bg = ''; $color = '';
                                    if ($meeting['status'] === 'Completed') { $bg = 'rgba(59,130,246,0.1)'; $color = '#3b82f6'; }
                                    elseif ($meeting['status'] === 'Verified') { $bg = 'rgba(16,185,129,0.1)'; $color = '#10b981'; }
                                    elseif ($meeting['status'] === 'Cancelled') { $bg = 'rgba(239,68,68,0.1)'; $color = '#ef4444'; }
                                ?>
                                <span class="badge" style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; font-size: 0.65rem;">
                                    <?php if ($meeting['status'] === 'Verified'): ?>
                                        <i class="bi bi-shield-check me-1"></i>
                                    <?php endif; ?>
                                    <?php echo $meeting['status']; ?>
                                </span>
                            </div>
                            <h6 class="mb-1 fw-bold" style="font-size: 0.85rem; color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                            <span class="text-muted d-block mb-2" style="font-size: 0.75rem;">
                                <?php echo htmlspecialchars($meeting['project_title']); ?> • <span class="fw-semibold text-success"><?php echo htmlspecialchars($meeting['group_code'] ?? 'ID PENDING'); ?> (<?php echo htmlspecialchars(explode(' ', trim($meeting['group_leader_name'] ?? 'Group'))[0]); ?>)</span>
                            </span>
                            
                            <?php if (!empty($meeting['supervisor_notes'])): ?>
                                <div class="mt-2 p-2 rounded" style="background: var(--form-bg); border-left: 3px solid #10b981;">
                                    <p class="small mb-0" style="color: var(--text-secondary); font-size: 0.75rem;"><?php echo nl2br(htmlspecialchars($meeting['supervisor_notes'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
