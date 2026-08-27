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

/* ─── Hero overrides ─── */
.page-hero-icon i {
    font-size: 2rem;
}
</style>

<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4">
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
        <div class="page-section h-100">
            <div class="page-section-header">
                <div>
                    <h6 class="mb-0 fw-bold">Pending Requests & Scheduled</h6>
                    <small class="text-muted">Manage active meeting requests</small>
                </div>
            </div>
            <div class="page-section-body p-4">
                <?php 
                $activeMeetings = array_filter($meetings, function($m) {
                    return in_array($m['status'], ['Pending', 'Scheduled', 'Rescheduled']);
                });
                
                if (empty($activeMeetings)): ?>
                    <div class="text-center py-5" style="background: var(--form-bg); border-radius: 16px; border: 1px dashed var(--border-color);">
                        <i class="bi bi-calendar-check text-muted" style="font-size: 2.5rem; opacity: 0.5"></i>
                        <p class="text-muted mt-3 mb-0 fw-medium">No pending requests or scheduled meetings.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($activeMeetings as $meeting): ?>
                            <div class="meeting-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1.05rem;"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.7rem; border-radius: 6px; padding: 5px 8px;"><i class="bi bi-folder-fill me-1"></i> <?php echo htmlspecialchars($meeting['project_title']); ?></span>
                                            <span class="badge status-<?php echo $meeting['status']; ?>"><?php echo $meeting['status']; ?></span>
                                        </div>
                                    </div>
                                    <div class="text-end" style="background: var(--form-bg); border: 1px solid var(--border-color); padding: 8px 14px; border-radius: 10px;">
                                        <div class="fw-bold" style="color: var(--text-primary); font-size: 0.95rem">
                                            <?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?>
                                        </div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.75rem">
                                            <?php echo date('h:i A', strtotime($meeting['meeting_date'])); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="small text-muted mb-4" style="line-height: 1.6; background: var(--bg-color); padding: 12px; border-radius: 10px; border: 1px solid var(--border-color);">
                                    <div class="fw-bold mb-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em; color: var(--text-secondary)">Agenda</div>
                                    <?php echo nl2br(htmlspecialchars($meeting['agenda'])); ?>
                                </div>
                                
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3" style="border-top: 1px solid var(--border-color);">
                                    <div class="d-flex align-items-center gap-2 small fw-bold" style="color: var(--text-secondary)">
                                        <i class="bi <?php echo $meeting['type'] === 'Online' ? 'bi-camera-video-fill text-primary' : 'bi-building-fill text-success'; ?>"></i>
                                        <?php echo $meeting['type']; ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <?php if ($meeting['status'] === 'Pending'): ?>
                                            <button class="btn btn-sm btn-success fw-bold px-3 d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#acceptModal<?php echo $meeting['id']; ?>" style="border-radius: 8px;"><i class="bi bi-check-circle"></i> Accept</button>
                                            <button class="btn btn-sm btn-warning fw-bold px-3 text-dark d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#rescheduleModal<?php echo $meeting['id']; ?>" style="border-radius: 8px;"><i class="bi bi-clock-history"></i> Reschedule</button>
                                            <form action="<?php echo $basePath; ?>/supervisor/meetings/update" method="POST" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                                <input type="hidden" name="status" value="Cancelled">
                                                <button class="btn btn-sm btn-danger fw-bold px-3 d-flex align-items-center gap-1 shadow-sm" onclick="return confirm('Are you sure you want to decline this request?')" style="border-radius: 8px;"><i class="bi bi-x-circle"></i> Decline</button>
                                            </form>
                                        <?php elseif ($meeting['status'] === 'Scheduled' || $meeting['status'] === 'Rescheduled'): ?>
                                            <button class="btn btn-sm btn-primary fw-bold px-3 d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#completeModal<?php echo $meeting['id']; ?>" style="border-radius: 8px;"><i class="bi bi-check2-all"></i> Mark Completed</button>
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
                                            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                                                <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Accept Meeting</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="alert mb-4" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981; border-radius: 10px;">
                                                    <i class="bi bi-info-circle-fill me-2"></i> Confirming meeting for <strong><?php echo date('M d, Y h:i A', strtotime($meeting['meeting_date'])); ?></strong> (<?php echo $meeting['type']; ?>).
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-secondary)">Location / Link</label>
                                                    <input type="text" name="location_link" class="form-control form-control-lg" placeholder="<?php echo $meeting['type'] === 'Online' ? 'Zoom/Teams link' : 'Room number'; ?>" required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary); border-radius: 12px; font-size: 0.95rem">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                                                <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px;">Cancel</button>
                                                <button type="submit" class="btn btn-success rounded-3 fw-bold shadow-sm" style="padding: 10px 24px;">Confirm</button>
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
                                            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                                                <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Reschedule Meeting</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-secondary)">Propose New Date & Time</label>
                                                    <input type="datetime-local" name="new_date" class="form-control form-control-lg" required style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary); border-radius: 12px; font-size: 0.95rem">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                                                <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px;">Cancel</button>
                                                <button type="submit" class="btn btn-warning rounded-3 fw-bold text-dark shadow-sm" style="padding: 10px 24px;">Propose Time</button>
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
                                            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                                                <h5 class="modal-title fw-bold" style="color: var(--text-primary)">Complete Meeting</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-secondary)">Meeting Minutes / Feedback Notes</label>
                                                    <textarea name="supervisor_notes" class="form-control form-control-lg" rows="4" placeholder="Log what was discussed or next action items for the students..." style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-primary); border-radius: 12px; font-size: 0.95rem"></textarea>
                                                    <div class="form-text mt-2"><i class="bi bi-info-circle"></i> These notes will be instantly visible to the group in their portal.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                                                <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px;">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-3 fw-bold shadow-sm" style="padding: 10px 24px;">Mark Completed</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- History -->
    <div class="col-lg-4">
        <div class="page-section h-100">
            <div class="page-section-header">
                <div>
                    <h6 class="mb-0 fw-bold">History</h6>
                    <small class="text-muted">Past meetings</small>
                </div>
            </div>
            <div class="page-section-body p-4">
                <?php 
                $pastMeetings = array_filter($meetings, function($m) {
                    return in_array($m['status'], ['Completed', 'Cancelled']);
                });
                
                if (empty($pastMeetings)): ?>
                    <div class="text-center py-4" style="background: var(--form-bg); border-radius: 12px; border: 1px solid var(--border-color);">
                        <p class="text-muted small mb-0 fw-medium">No past meetings.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($pastMeetings as $meeting): ?>
                            <div class="meeting-card" style="opacity: 0.85; padding: 14px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold" style="font-size: 0.8rem; color: var(--text-primary)"><?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?></span>
                                    <span class="badge status-<?php echo $meeting['status']; ?>" style="font-size: 0.65rem; padding: 4px 8px;"><?php echo $meeting['status']; ?></span>
                                </div>
                                <h6 class="mb-2 fw-bold" style="font-size: 0.9rem; color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                <span class="badge mb-2 d-inline-flex align-items-center gap-1" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.65rem; border-radius: 4px; padding: 3px 6px;">
                                    <i class="bi bi-folder-fill"></i> <?php echo htmlspecialchars($meeting['project_title']); ?>
                                </span>
                                
                                <?php if (!empty($meeting['supervisor_notes'])): ?>
                                    <div class="mt-3 p-3 rounded" style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15)">
                                        <div class="small fw-bold mb-1 d-flex align-items-center gap-2" style="color: #10b981; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em">
                                            <i class="bi bi-journal-text"></i> Your Notes
                                        </div>
                                        <p class="small mb-0 mt-2" style="color: var(--text-secondary); line-height: 1.5; font-size: 0.8rem;"><?php echo nl2br(htmlspecialchars($meeting['supervisor_notes'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
