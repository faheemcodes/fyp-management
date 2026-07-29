<style>
/* ─── Section Panel ─── */



/* ─── Modern Table Styles ─── */







@media (max-width: 768px) {
    
}
</style>
<!-- Admin Deadlines View -->


<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="admin-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="admin-hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <h4 class="fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Timeline & Deadlines</h4>
                <p class="mb-0 mt-1" style="font-size: 0.85rem">Set and manage submission deadlines for various FYP stages</p>
            </div>
        </div>
    </div>
</div>

<!-- Department Selector -->
<div class="d-flex justify-content-end mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 gap-md-3 w-100 justify-content-md-end">
        <label for="departmentFilter" class="fw-bold text-secondary m-0 text-nowrap" style="font-size: 0.9rem">Select Department:</label>
        <select class="form-select border-0 shadow-sm fw-semibold text-primary w-100 w-md-auto" style="background: var(--card-bg);max-width: 350px;border-radius: 12px;cursor: pointer" id="departmentFilter" onchange="window.location.href='?department='+encodeURIComponent(this.value)">
            <?php
            $depts = ['Information Technology', 'Software Engineering', 'Data Science', 'Electronic Engineering', 'Telecommunication Engineering'];
            foreach($depts as $d) {
                $sel = (isset($department) && $department === $d) ? 'selected' : '';
                echo "<option value=\"$d\" $sel>$d</option>";
            }
            ?>
        </select>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="glass-panel h-100">
            <div class="border-bottom p-3 bg-light rounded-top" style="border-radius: 16px 16px 0 0;">
                <h6 class="fw-bold text-dark m-0">Set / Update Stage Deadline</h6>
            </div>
            <div class="p-4">
                <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/deadlines" method="POST">
                    <input type="hidden" name="department" value="<?php echo htmlspecialchars($department ?? 'Software Engineering'); ?>">
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
                        <input type="datetime-local" class="form-control premium-input bg-light" id="deadline_date" name="deadline_date" required>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label small fw-semibold text-secondary">Visibility Status</label>
                        <select class="form-select bg-light" id="status" name="status" required>
                            <option value="Active">Active (Publish to Students)</option>
                            <option value="Inactive" selected>Inactive (Hidden/Unpublished)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-premium w-100 rounded-pill mt-2">Update Deadline</button>
                
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="glass-panel h-100">
            <div class="border-bottom p-3 bg-light rounded-top" style="border-radius: 16px 16px 0 0;">
                <h6 class="fw-bold text-dark m-0">Current Timeline Deadlines</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table premium-table m-0">
                    <thead>
                        <tr>
                            <th class="ps-4">FYP Project Stage</th>
                            <th>Deadline Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($deadlines as $dl): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-dark" style="font-size: 0.9rem"><?php echo htmlspecialchars($dl['stage']); ?></td>
                            <td>
                                <div class="fw-semibold text-primary" style="font-size: 0.85rem"><i class="bi bi-clock me-1"></i><?php echo date('M d, Y h:i A', strtotime($dl['deadline_date'])); ?></div>
                                <small class="text-muted" style="font-size: 0.75rem">Last modified: <?php echo date('m/d/y', strtotime($dl['updated_at'])); ?></small>
                            </td>
                            <td>
                                <?php if($dl['status'] === 'Active'): ?>
                                    <span class="premium-badge success">Active</span>
                                <?php else: ?>
                                    <span class="premium-badge neutral">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/deadlines/delete?stage=<?php echo urlencode($dl['stage']); ?>&department=<?php echo urlencode($dl['department'] ?? ($department ?? 'Software Engineering')); ?>" class="table-action-btn delete" onclick="return confirm('Are you sure you want to delete this deadline?');">
                                    <i class="bi bi-trash-fill"></i>
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
