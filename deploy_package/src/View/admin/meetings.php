<?php
$title = "Meetings Audit & Supervision - Admin Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$selectedDept = $selectedDept ?? 'all';
$selectedStatus = $selectedStatus ?? 'all';
$selectedSupervisor = $selectedSupervisor ?? 0;
$pendingAuditCount = $pendingAuditCount ?? 0;
$meetings = $meetings ?? [];
$departments = $departments ?? [];
$supervisors = $supervisors ?? [];

// Calculate summary stats
$totalMeetings = count($meetings);
$completedCount = 0;
$verifiedCount = 0;
$scheduledCount = 0;
foreach ($meetings as $m) {
    if ($m['status'] === 'Completed') $completedCount++;
    elseif ($m['status'] === 'Verified') $verifiedCount++;
    elseif ($m['status'] === 'Scheduled') $scheduledCount++;
}
?>

<style>
.filter-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: 16px;
    box-shadow: var(--card-shadow, 0 4px 15px rgba(0,0,0,0.03));
}
.form-control-custom {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #0f172a);
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 0.85rem;
}
.form-control-custom:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    outline: none;
}
.meeting-row {
    transition: background 0.15s ease;
}
.meeting-row:hover {
    background: rgba(59, 130, 246, 0.03);
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Meetings Audit &amp; Supervision</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Audit, monitor, and verify supervisor-student consultation meetings across all university departments
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <?php if ($pendingAuditCount > 0): ?>
                <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill"></i> <?php echo (int)$pendingAuditCount; ?> Awaiting Verification
                </span>
            <?php else: ?>
                <span class="badge rounded-pill bg-success text-white px-3 py-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-check-circle-fill"></i> All Meetings Audited
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ═══════════════ Metric KPI Cards ═══════════════ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                    <i class="bi bi-calendar-event-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Total Meetings</div>
                    <h5 class="fw-bold m-0 text-dark"><?php echo (int)$totalMeetings; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="bi bi-hourglass-split fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Awaiting Verification</div>
                    <h5 class="fw-bold m-0 text-warning"><?php echo (int)$completedCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-patch-check-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Verified Meetings</div>
                    <h5 class="fw-bold m-0 text-success"><?php echo (int)$verifiedCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Scheduled / Active</div>
                    <h5 class="fw-bold m-0 text-primary"><?php echo (int)$scheduledCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Filter Toolbar ═══════════════ -->
<div class="filter-card p-3 mb-4">
    <form action="<?php echo $basePath; ?>/admin/meetings" method="GET" class="row g-2 align-items-center">
        <!-- Department Filter -->
        <div class="col-12 col-md-3">
            <label class="form-label small fw-bold text-secondary mb-1">Department</label>
            <select name="department" class="form-select form-control-custom" onchange="this.form.submit()">
                <option value="all" <?php echo ($selectedDept === 'all') ? 'selected' : ''; ?>>All Departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($selectedDept === $dept) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Status Filter -->
        <div class="col-12 col-md-3">
            <label class="form-label small fw-bold text-secondary mb-1">Status</label>
            <select name="status" class="form-select form-control-custom" onchange="this.form.submit()">
                <option value="all" <?php echo ($selectedStatus === 'all') ? 'selected' : ''; ?>>All Statuses</option>
                <option value="Completed" <?php echo ($selectedStatus === 'Completed') ? 'selected' : ''; ?>>Completed (Awaiting Audit)</option>
                <option value="Verified" <?php echo ($selectedStatus === 'Verified') ? 'selected' : ''; ?>>Verified</option>
                <option value="Scheduled" <?php echo ($selectedStatus === 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                <option value="Pending" <?php echo ($selectedStatus === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Cancelled" <?php echo ($selectedStatus === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>

        <!-- Supervisor Filter -->
        <div class="col-12 col-md-3">
            <label class="form-label small fw-bold text-secondary mb-1">Supervisor</label>
            <select name="supervisor_id" class="form-select form-control-custom" onchange="this.form.submit()">
                <option value="0" <?php echo ($selectedSupervisor === 0) ? 'selected' : ''; ?>>All Supervisors</option>
                <?php foreach ($supervisors as $sup): ?>
                    <option value="<?php echo (int)$sup['user_id']; ?>" <?php echo ($selectedSupervisor == $sup['user_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($sup['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($sup['department'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Client Search Filter -->
        <div class="col-12 col-md-3">
            <label class="form-label small fw-bold text-secondary mb-1">Quick Search</label>
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.8rem;"></i>
                <input type="text" id="meetingLiveSearch" class="form-control form-control-custom ps-5" placeholder="Search group, title, supervisor...">
            </div>
        </div>
    </form>
</div>

<!-- ═══════════════ Meetings Table Card ═══════════════ -->
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-header border-bottom p-3 p-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--form-bg);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <h6 class="fw-bold m-0 text-dark">Consultation Meetings Roster</h6>
            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2.5 py-1" style="font-size: 0.75rem;">
                <?php echo count($meetings); ?> records
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <?php if (empty($meetings)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                <h6 class="fw-bold text-dark">No Meetings Found</h6>
                <p class="small text-secondary mb-0">No consultation meetings match your selected filters.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="meetingsTable">
                    <thead>
                        <tr style="background: var(--form-bg);">
                            <th class="ps-4" style="font-size: 0.75rem; text-transform: uppercase;">Date &amp; Time</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Group &amp; Project</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Department</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Supervisor</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Status</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Notes</th>
                            <th class="text-end pe-4" style="font-size: 0.75rem; text-transform: uppercase;">Audit Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($meetings as $m): 
                            $status = $m['status'] ?? 'Pending';
                            $statusBadgeClass = match($status) {
                                'Completed' => 'bg-warning-subtle text-warning-emphasis border border-warning',
                                'Verified' => 'bg-success-subtle text-success border border-success',
                                'Scheduled' => 'bg-info-subtle text-info-emphasis border border-info',
                                'Cancelled' => 'bg-danger-subtle text-danger border border-danger',
                                default => 'bg-secondary-subtle text-secondary'
                            };
                            $mDate = !empty($m['meeting_date']) ? date('M d, Y', strtotime($m['meeting_date'])) : '—';
                            $mTime = !empty($m['meeting_date']) ? date('h:i A', strtotime($m['meeting_date'])) : '';
                        ?>
                            <tr class="meeting-row search-item">
                                <td class="ps-4 text-nowrap">
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?php echo $mDate; ?></div>
                                    <div class="small text-muted" style="font-size: 0.75rem;"><?php echo $mTime; ?></div>
                                </td>
                                <td style="max-width: 250px;">
                                    <span class="badge font-monospace bg-primary-subtle text-primary px-2 py-0.5 rounded-pill mb-1" style="font-size: 0.72rem;">
                                        <?php echo htmlspecialchars($m['group_code'] ?? 'ID PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <div class="fw-semibold text-dark text-truncate" title="<?php echo htmlspecialchars($m['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($m['project_title'] ?: 'Untitled Project', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="small text-secondary" style="font-size: 0.75rem;">
                                        Leader: <?php echo htmlspecialchars($m['group_leader_name'] ?? '—', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($m['department'] ?? '—', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($m['supervisor_name'] ?? '—', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="small text-secondary" style="font-size: 0.75rem;">
                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($m['location_link'] ?? 'In-person', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-2.5 py-1 fw-bold <?php echo $statusBadgeClass; ?>" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($m['supervisor_notes']) || !empty($m['discussion_summary']) || !empty($m['tasks_assigned'])): 
                                        $allNotes = trim(($m['supervisor_notes'] ?? '') . "\n" . ($m['discussion_summary'] ?? '') . "\n" . ($m['tasks_assigned'] ?? ''));
                                    ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.75rem;" 
                                                data-bs-toggle="modal" data-bs-target="#notesModal" 
                                                data-notes="<?php echo htmlspecialchars($allNotes, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-title="<?php echo htmlspecialchars(($m['group_code'] ?? '') . ' - ' . ($m['project_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-card-text me-1"></i> View Notes
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <?php if ($status === 'Completed'): ?>
                                        <form action="<?php echo $basePath; ?>/admin/meetings/verify" method="POST" class="d-inline-block m-0">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="meeting_id" value="<?php echo (int)$m['id']; ?>">
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-sm d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                                <i class="bi bi-patch-check-fill"></i> Verify
                                            </button>
                                        </form>
                                    <?php elseif ($status === 'Verified'): ?>
                                        <span class="badge rounded-pill bg-primary-subtle text-primary px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">
                                            <i class="bi bi-check-all me-1"></i> Verified
                                        </span>
                                    <?php else: ?>
                                        <form action="<?php echo $basePath; ?>/admin/meetings/verify" method="POST" class="d-inline-block m-0">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="meeting_id" value="<?php echo (int)$m['id']; ?>">
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.75rem;" title="Force Verify Meeting">
                                                <i class="bi bi-check-lg"></i> Mark Verified
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════════ Notes Modal ═══════════════ -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-bottom p-3 px-4">
                <h6 class="modal-title fw-bold text-dark" id="notesModalLabel">
                    <i class="bi bi-journal-text me-2 text-primary"></i>Meeting Notes
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-2">
                    <span class="small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Project / Group</span>
                    <h6 class="fw-bold text-dark mb-3" id="modalProjectTitle"></h6>
                </div>
                <div class="p-3 rounded-3" style="background: var(--form-bg); border-left: 4px solid #3b82f6;">
                    <span class="small fw-bold text-secondary text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Supervisor &amp; Discussion Notes</span>
                    <p class="small mb-0 text-dark" id="modalNotesBody" style="line-height: 1.6; white-space: pre-wrap; font-size: 0.85rem;"></p>
                </div>
            </div>
            <div class="modal-footer border-top p-3 px-4">
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side quick search
document.getElementById('meetingLiveSearch')?.addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#meetingsTable tbody tr.search-item').forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
    });
});

// Populate Notes Modal
const notesModal = document.getElementById('notesModal');
if (notesModal) {
    notesModal.addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        const notes = btn.getAttribute('data-notes') || 'No additional notes provided.';
        const title = btn.getAttribute('data-title') || '';
        document.getElementById('modalProjectTitle').textContent = title;
        document.getElementById('modalNotesBody').textContent = notes;
    });
}
</script>
