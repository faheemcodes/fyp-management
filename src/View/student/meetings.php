<style>
.meeting-card {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    padding: 16px;
    transition: all 0.2s ease;
}
.meeting-card:hover {
    border-color: rgba(16,185,129,0.3);
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
        <h4 class="fw-bold mb-1" style="color: var(--text-primary); letter-spacing: -0.02em">Meetings</h4>
        <p class="text-muted small mb-0">Schedule and manage meetings with your supervisor</p>
    </div>
    <?php if ($supervisor && isset($group['project_status']) && $group['project_status'] === 'Approved'): ?>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#requestMeetingModal" style="border-radius: 10px; font-weight: 600; padding: 10px 20px;">
            <i class="bi bi-calendar-plus me-2"></i> Request Meeting
        </button>
    <?php endif; ?>
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

    <div class="row g-4">
        <!-- Upcoming & Pending -->
        <div class="col-lg-8">
            <h6 class="fw-bold mb-3" style="color: var(--text-secondary); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em">Active Meetings</h6>
            
            <?php 
            $activeMeetings = array_filter($meetings, function($m) {
                return in_array($m['status'], ['Pending', 'Scheduled', 'Rescheduled']);
            });
            
            if (empty($activeMeetings)): ?>
                <div class="text-center py-5" style="background: var(--card-bg); border-radius: 16px; border: 1px dashed var(--border-color);">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 2.5rem; opacity: 0.5"></i>
                    <p class="text-muted mt-3 mb-0 fw-medium">No active meetings.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($activeMeetings as $meeting): ?>
                        <div class="meeting-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                    <span class="badge status-<?php echo $meeting['status']; ?>"><?php echo $meeting['status']; ?></span>
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
                            
                            <p class="small text-muted mb-3" style="line-height: 1.5"><?php echo nl2br(htmlspecialchars($meeting['agenda'])); ?></p>
                            
                            <div class="d-flex align-items-center gap-3 pt-3" style="border-top: 1px solid var(--border-color);">
                                <div class="d-flex align-items-center gap-2 small fw-medium" style="color: var(--text-secondary)">
                                    <i class="bi <?php echo $meeting['type'] === 'Online' ? 'bi-camera-video' : 'bi-building'; ?>"></i>
                                    <?php echo $meeting['type']; ?>
                                </div>
                                <?php if (!empty($meeting['location_link'])): ?>
                                    <div class="d-flex align-items-center gap-2 small fw-medium text-primary">
                                        <i class="bi bi-geo-alt"></i>
                                        <?php if (filter_var($meeting['location_link'], FILTER_VALIDATE_URL)): ?>
                                            <a href="<?php echo htmlspecialchars($meeting['location_link']); ?>" target="_blank" class="text-decoration-none">Join Link</a>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($meeting['location_link']); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Past Meetings -->
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
                            <h6 class="mb-2" style="font-size: 0.9rem; color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                            
                            <?php if (!empty($meeting['supervisor_notes'])): ?>
                                <div class="mt-2 p-2 rounded" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.1)">
                                    <div class="small fw-bold mb-1" style="color: #10b981; font-size: 0.7rem; text-transform: uppercase;">Supervisor Notes</div>
                                    <p class="small mb-0" style="color: var(--text-secondary)"><?php echo nl2br(htmlspecialchars($meeting['supervisor_notes'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Request Modal -->
    <div class="modal fade" id="requestMeetingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
                <form action="<?php echo $basePath; ?>/student/meetings/request" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Request Meeting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Chapter 3 Review" required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Date & Time</label>
                                <input type="datetime-local" name="meeting_date" class="form-control" required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Format</label>
                                <select name="type" class="form-select" style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)">
                                    <option value="In-Person">In-Person</option>
                                    <option value="Online">Online</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Agenda</label>
                            <textarea name="agenda" class="form-control" rows="3" placeholder="Briefly describe what you want to discuss..." required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-3 fw-semibold px-4">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
