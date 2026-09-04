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
                <i class="bi bi-calendar-event"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1" style="font-size: 1.25rem; letter-spacing: -0.02em">Meetings</h4>
                <p class="mb-0" style="font-size: 0.85rem; color: rgba(255,255,255,0.7)">Schedule and manage meetings with your supervisor</p>
            </div>
        </div>
        
        <?php if ($supervisor && isset($group['project_status']) && $group['project_status'] === 'Approved'): ?>
            <?php if (!empty($isBatchActive)): ?>
                <button type="button" class="btn text-white fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#requestMeetingModal" style="background: rgba(255,255,255,0.2); border-radius: 12px; padding: 10px 20px; border: 1px solid rgba(255,255,255,0.3)">
                    <i class="bi bi-plus-lg"></i> Request Meeting
                </button>
            <?php else: ?>
                <button type="button" class="btn text-white-50 d-flex align-items-center gap-2" disabled style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 10px 20px; border: 1px solid rgba(255,255,255,0.15)" title="Meeting scheduling is closed as your project term has concluded">
                    <i class="bi bi-lock-fill"></i> Meetings Closed
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php if (!$group): ?>
    <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 14px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> You must create or join a group first.
    </div>
<?php elseif (!$supervisor): ?>
    <div class="alert alert-info border-0 shadow-sm" style="border-radius: 14px;">
        <i class="bi bi-info-circle-fill me-2"></i> You don't have an assigned supervisor yet. Meetings can be requested after your proposal is approved.
    </div>
<?php else: ?>

    <?php if (isset($isBatchActive) && !$isBatchActive): ?>
        <div class="alert border-0 shadow-sm mb-4" style="border-radius: 14px; background: rgba(59, 130, 246, 0.08); border-left: 4px solid #3b82f6 !important; color: #1e40af;">
            <i class="bi bi-info-circle-fill me-2"></i> <strong>Session Concluded:</strong> Meeting scheduling with your supervisor is closed. You can view your previous meeting history below.
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Upcoming & Pending -->
        <div class="col-lg-8">
            <div class="card border-0 p-4 h-100">
                <div class="page-section-header mb-4">
                    <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div>
                        <h6>Active Meetings</h6>
                        <small>Pending and upcoming scheduled meetings</small>
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
                                        <?php
                                            $bg = ''; $color = '';
                                            if ($meeting['status'] === 'Pending') { $bg = 'rgba(245,158,11,0.1)'; $color = '#f59e0b'; }
                                            elseif ($meeting['status'] === 'Scheduled') { $bg = 'rgba(16,185,129,0.1)'; $color = '#10b981'; }
                                            elseif ($meeting['status'] === 'Rescheduled') { $bg = 'rgba(139,92,246,0.1)'; $color = '#8b5cf6'; }
                                        ?>
                                        <span class="badge" style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; font-size: 0.7rem;"><?php echo $meeting['status']; ?></span>
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
                                
                                <div class="d-flex align-items-center gap-4">
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
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Past Meetings -->
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
                                <h6 class="mb-2 fw-bold" style="font-size: 0.85rem; color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                
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

    <!-- Request Modal -->
    <div class="modal fade" id="requestMeetingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="<?php echo $basePath; ?>/student/meetings/request" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold">Request a Meeting</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Chapter 3 Review" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Date & Time</label>
                                <input type="datetime-local" name="meeting_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Format</label>
                                <select name="type" class="form-select">
                                    <option value="In-Person">In-Person</option>
                                    <option value="Online">Online</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-muted">Agenda</label>
                            <textarea name="agenda" class="form-control" rows="3" placeholder="Briefly describe what you want to discuss..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
