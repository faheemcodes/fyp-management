<style>
.meeting-card {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    padding: 16px;
    transition: all 0.2s ease;
}
.meeting-card:hover {
    border-color: rgba(59,130,246,0.3);
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
}
.status-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
}
.status-Pending { background: rgba(245,158,11,0.1); color: #f59e0b; }
.status-Scheduled { background: rgba(16,185,129,0.1); color: #10b981; }
.status-Completed { background: rgba(59,130,246,0.1); color: #3b82f6; }
.status-Cancelled { background: rgba(239,68,68,0.1); color: #ef4444; }
.status-Rescheduled { background: rgba(139,92,246,0.1); color: #8b5cf6; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--text-primary); letter-spacing: -0.02em">Meetings Dashboard</h4>
        <p class="text-muted small mb-0">Manage meeting requests from your assigned groups</p>
    </div>
</div>

<div class="row g-4">
    <!-- Active Requests & Scheduled -->
    <div class="col-lg-8">
        <h6 class="fw-bold mb-3" style="color: var(--text-secondary); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em">Pending Requests & Scheduled</h6>
        
        <?php 
        $activeMeetings = array_filter($meetings, function($m) {
            return in_array($m['status'], ['Pending', 'Scheduled', 'Rescheduled']);
        });
        
        if (empty($activeMeetings)): ?>
            <div class="text-center py-5" style="background: var(--card-bg); border-radius: 16px; border: 1px dashed var(--border-color);">
                <i class="bi bi-calendar-check text-muted" style="font-size: 2.5rem; opacity: 0.5"></i>
                <p class="text-muted mt-3 mb-0 fw-medium">No pending requests or scheduled meetings.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($activeMeetings as $meeting): ?>
                    <div class="meeting-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-1" style="color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-secondary rounded-pill" style="font-size:0.7rem"><?php echo htmlspecialchars($meeting['project_title']); ?></span>
                                    <span class="badge status-<?php echo $meeting['status']; ?>"><?php echo $meeting['status']; ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 0.95rem">
                                    <?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?>
                                </div>
                                <div class="text-muted small fw-medium">
                                    <?php echo date('h:i A', strtotime($meeting['meeting_date'])); ?>
                                </div>
                            </div>
                        </div>
                        
                        <p class="small text-muted mb-3" style="line-height: 1.5; background: var(--bg-color); padding: 10px; border-radius: 8px;">
                            <?php echo nl2br(htmlspecialchars($meeting['agenda'])); ?>
                        </p>
                        
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-2" style="border-top: 1px solid var(--border-color);">
                            <div class="d-flex align-items-center gap-2 small fw-medium" style="color: var(--text-secondary)">
                                <i class="bi <?php echo $meeting['type'] === 'Online' ? 'bi-camera-video' : 'bi-building'; ?>"></i>
                                <?php echo $meeting['type']; ?>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <?php if ($meeting['status'] === 'Pending'): ?>
                                    <button class="btn btn-sm btn-success fw-bold px-3" data-bs-toggle="modal" data-bs-target="#acceptModal<?php echo $meeting['id']; ?>">Accept</button>
                                    <button class="btn btn-sm btn-warning fw-bold px-3 text-dark" data-bs-toggle="modal" data-bs-target="#rescheduleModal<?php echo $meeting['id']; ?>">Reschedule</button>
                                    <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button class="btn btn-sm btn-danger fw-bold px-3" onclick="return confirm('Are you sure you want to decline this request?')">Decline</button>
                                    </form>
                                <?php elseif ($meeting['status'] === 'Scheduled' || $meeting['status'] === 'Rescheduled'): ?>
                                    <button class="btn btn-sm btn-primary fw-bold px-3" data-bs-toggle="modal" data-bs-target="#completeModal<?php echo $meeting['id']; ?>">Mark Completed</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Accept Modal -->
                    <div class="modal fade" id="acceptModal<?php echo $meeting['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
                                <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                    <input type="hidden" name="status" value="Scheduled">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Accept Meeting</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small text-muted mb-3">Meeting is requested for <strong><?php echo date('M d, Y h:i A', strtotime($meeting['meeting_date'])); ?></strong> (<?php echo $meeting['type']; ?>).</p>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-muted">Location / Link</label>
                                            <input type="text" name="location_link" class="form-control" placeholder="<?php echo $meeting['type'] === 'Online' ? 'Zoom/Teams link' : 'Room number'; ?>" required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success rounded-3 fw-semibold px-4">Confirm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Reschedule Modal -->
                    <div class="modal fade" id="rescheduleModal<?php echo $meeting['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
                                <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                    <input type="hidden" name="status" value="Rescheduled">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Reschedule Meeting</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-muted">Propose New Date & Time</label>
                                            <input type="datetime-local" name="new_date" class="form-control" required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning rounded-3 fw-semibold px-4">Propose Time</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Complete Modal -->
                    <div class="modal fade" id="completeModal<?php echo $meeting['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
                                <form action="<?php echo $basePath; ?>/supervisor/meetings/complete" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Complete Meeting</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-muted">Meeting Minutes / Feedback Notes</label>
                                            <textarea name="supervisor_notes" class="form-control" rows="4" placeholder="Log what was discussed or next action items for the students..." style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)"></textarea>
                                            <div class="form-text">These notes will be visible to the group in their portal.</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary rounded-3 fw-semibold px-4">Mark Completed</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- History -->
    <div class="col-lg-4">
        <h6 class="fw-bold mb-3" style="color: var(--text-secondary); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em">History</h6>
        
        <?php 
        $pastMeetings = array_filter($meetings, function($m) {
            return in_array($m['status'], ['Completed', 'Cancelled']);
        });
        
        if (empty($pastMeetings)): ?>
            <div class="text-center py-4" style="background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color);">
                <p class="text-muted small mb-0">No past meetings.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($pastMeetings as $meeting): ?>
                    <div class="meeting-card" style="opacity: 0.8">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="font-size: 0.85rem; color: var(--text-primary)"><?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?></span>
                            <span class="badge status-<?php echo $meeting['status']; ?>"><?php echo $meeting['status']; ?></span>
                        </div>
                        <h6 class="mb-1" style="font-size: 0.9rem; color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                        <span class="badge bg-secondary rounded-pill mb-2" style="font-size:0.65rem"><?php echo htmlspecialchars($meeting['project_title']); ?></span>
                        
                        <?php if (!empty($meeting['supervisor_notes'])): ?>
                            <div class="mt-2 p-2 rounded" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.1)">
                                <div class="small fw-bold mb-1" style="color: #10b981; font-size: 0.7rem; text-transform: uppercase;">Your Notes</div>
                                <p class="small mb-0" style="color: var(--text-secondary)"><?php echo nl2br(htmlspecialchars($meeting['supervisor_notes'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
