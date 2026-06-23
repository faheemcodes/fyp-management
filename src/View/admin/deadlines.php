<!-- Admin Deadlines View -->
<style>
/* ─── Hero Banner Styles ─── */
.group-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.group-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero-icon {
    width: 56px;
    height: 56px;
    background: conic-gradient(from 0deg, #3b82f6, #6366f1, #8b5cf6, #3b82f6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}

/* ─── Section Panel ─── */
.grp-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 24px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.grp-section-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}

/* ─── Modern Table Styles ─── */
.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.modern-table thead th {
    background: var(--form-bg);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-secondary);
    font-weight: 700;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}
.modern-table tbody td {
    padding: 16px 24px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    background: var(--card-bg);
    transition: background-color 0.2s ease;
}
.modern-table tbody tr:hover td {
    background-color: rgba(59,130,246,0.02);
}
.modern-table tbody tr:last-child td {
    border-bottom: none;
}
.status-pill {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    display: inline-block;
}

@media (max-width: 768px) {
    .modern-table { min-width: 700px; }
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #f59e0b, #fbbf24, #d97706, #f59e0b);">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Timeline & Deadlines</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Set and manage submission deadlines for various FYP stages</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="grp-section h-100">
            <div class="grp-section-header">
                <h6 class="fw-bold text-dark m-0">Set / Update Stage Deadline</h6>
            </div>
            <div class="p-4">
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

                    <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;">Update Deadline</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="grp-section h-100">
            <div class="grp-section-header">
                <h6 class="fw-bold text-dark m-0">Current Timeline Deadlines</h6>
            </div>
            <div class="table-responsive">
                <table class="table modern-table m-0">
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
                            <td class="ps-4 fw-bold text-dark" style="font-size: 0.9rem;"><?php echo htmlspecialchars($dl['stage']); ?></td>
                            <td>
                                <div class="fw-semibold text-primary" style="font-size: 0.85rem;"><i class="bi bi-clock me-1"></i><?php echo date('M d, Y h:i A', strtotime($dl['deadline_date'])); ?></div>
                                <small class="text-muted" style="font-size: 0.75rem;">Last modified: <?php echo date('m/d/y', strtotime($dl['updated_at'])); ?></small>
                            </td>
                            <td>
                                <?php if($dl['status'] === 'Active'): ?>
                                    <span class="status-pill" style="background: rgba(16,185,129,0.15); color: #059669;">Active</span>
                                <?php else: ?>
                                    <span class="status-pill" style="background: rgba(107,114,128,0.15); color: #4b5563;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/deadlines/delete?stage=<?php echo urlencode($dl['stage']); ?>" class="btn btn-sm d-inline-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; width: 32px; height: 32px;" onclick="return confirm('Are you sure you want to delete this deadline?');">
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
