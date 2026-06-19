<!-- Admin Deadlines View -->
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4">Set / Update Stage Deadline</h5>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/deadlines" method="POST">
                <div class="mb-3">
                    <label for="stage" class="form-label small fw-semibold text-secondary">Project Submission Stage</label>
                    <select class="form-select bg-light" id="stage" name="stage" required>
                        <option value="Proposal Submission">Proposal Submission</option>
                        <option value="Proposal Defence Presentation">Proposal Defence Presentation</option>
                        <option value="FYP Progress Presentation">FYP Progress Presentation</option>
                        <option value="Final Presentation">Final Presentation</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="deadline_date" class="form-label small fw-semibold text-secondary">Deadline Date & Time</label>
                    <input type="datetime-local" class="form-control bg-light" id="deadline_date" name="deadline_date" required>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label small fw-semibold text-secondary">Visibility Status</label>
                    <select class="form-select bg-light" id="status" name="status" required>
                        <option value="Active">Active (Publish to Students)</option>
                        <option value="Inactive" selected>Inactive (Hidden/Unpublished)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill">Update Deadline</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-4">Current Timeline Deadlines</h5>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>FYP Project Stage</th>
                            <th>Deadline Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
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
                                <?php if($dl['status'] === 'Active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1.5 small">Active (Visible)</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1.5 small">Inactive (Hidden)</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/deadlines/delete?stage=<?php echo urlencode($dl['stage']); ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1 px-2 shadow-xs" onclick="return confirm('Are you sure you want to delete this deadline?');">
                                    <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($deadlines)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No deadlines have been set yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deadlinesData = <?php echo json_encode($deadlines); ?>;
    const stageSelect = document.getElementById('stage');
    const dateInput = document.getElementById('deadline_date');
    const statusSelect = document.getElementById('status');
    
    stageSelect.addEventListener('change', function() {
        const selectedStage = this.value;
        const dl = deadlinesData.find(d => d.stage === selectedStage);
        if (dl) {
            // Format date for datetime-local input (YYYY-MM-DDTHH:MM)
            const dateObj = new Date(dl.deadline_date);
            const yyyy = dateObj.getFullYear();
            const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
            const dd = String(dateObj.getDate()).padStart(2, '0');
            const hh = String(dateObj.getHours()).padStart(2, '0');
            const min = String(dateObj.getMinutes()).padStart(2, '0');
            dateInput.value = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
            statusSelect.value = dl.status;
        } else {
            dateInput.value = '';
            statusSelect.value = 'Inactive';
        }
    });
    
    // Trigger initial load check
    stageSelect.dispatchEvent(new Event('change'));
});
</script>
