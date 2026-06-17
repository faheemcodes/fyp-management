<!-- Admin Deadlines View -->
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4">Set / Update Stage Deadline</h5>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/deadlines" method="POST">
                <div class="mb-3">
                    <label for="stage" class="form-label small fw-semibold text-secondary">Project Milestone Stage</label>
                    <select class="form-select bg-light" id="stage" name="stage" required>
                        <option value="Proposal Submission">Proposal Submission</option>
                        <option value="Proposal Defence Presentation">Proposal Defence Presentation</option>
                        <option value="FYP Progress Presentation">FYP Progress Presentation</option>
                        <option value="Final Presentation">Final Presentation</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="deadline_date" class="form-label small fw-semibold text-secondary">Deadline Date & Time</label>
                    <input type="datetime-local" class="form-control bg-light" id="deadline_date" name="deadline_date" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill">Update Deadline</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-4">Current Milestone Timelines</h5>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Milestone</th>
                            <th>Deadline Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($deadlines as $dl): ?>
                        <tr>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($dl['stage']); ?></td>
                            <td>
                                <div class="fw-semibold"><i class="bi bi-clock me-1 text-primary"></i><?php echo date('M d, Y h:i A', strtotime($dl['deadline_date'])); ?></div>
                                <small class="text-muted">Last modified: <?php echo date('m/d/y', strtotime($dl['updated_at'])); ?></small>
                            </td>
                            <td>
                                <?php if(strtotime($dl['deadline_date']) < time()): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 small">Closed</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">Active</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($deadlines)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No deadlines have been set yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
