<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$isCoordinatorMultiShift = ($coordinatorShift === 'All');
?>
<style>
.deadline-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 1.25rem;
    box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.deadline-table th {
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    color: var(--text-secondary) !important;
    padding: 12px 14px !important;
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.deadline-table td {
    padding: 12px 14px !important;
    vertical-align: middle;
    font-size: 0.88rem;
    border-bottom: 1px solid var(--border-color);
}

.deadline-form-control {
    background: var(--card-bg) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 12px !important;
    padding: 10px 14px !important;
    font-size: 0.88rem !important;
    color: var(--text-primary) !important;
    transition: all 0.2s ease;
}
.deadline-form-control:focus {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    font-size: 0.82rem;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}
.action-btn:hover {
    background: rgba(16,185,129,0.1);
    color: #10b981;
    border-color: rgba(16,185,129,0.25);
    transform: translateY(-1px);
}
.action-btn.delete:hover {
    background: rgba(239,68,68,0.1);
    color: #ef4444;
    border-color: rgba(239,68,68,0.25);
}

.shift-tab-pill {
    padding: 6px 16px;
    border-radius: 50rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
.shift-tab-pill.active {
    background: #10b981;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
}
.shift-tab-pill:not(.active) {
    background: var(--card-bg);
    color: var(--text-secondary);
    border-color: var(--border-color);
}
.shift-tab-pill:not(.active):hover {
    background: rgba(16, 185, 129, 0.08);
    color: #10b981;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <!-- Icon & Titles -->
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-calendar2-week-fill"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    Timeline Management &amp; Milestones
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    Submission Deadlines
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap justify-content-center justify-content-md-start">
                    <span class="badge rounded-pill px-3 py-1.5 text-nowrap" style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.95); font-size: 0.76rem; font-weight: 600;">
                        <i class="bi bi-building me-1.5"></i><?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 text-nowrap" style="background: rgba(16,185,129,0.25); color: #6ee7b7; font-size: 0.76rem; font-weight: 600; border: 1px solid rgba(16,185,129,0.3);">
                        <i class="bi bi-clock-history me-1.5"></i>Shift: <?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Stat Pill -->
        <div class="d-none d-lg-flex gap-3">
            <div class="page-stat-pill">
                <span class="stat-num text-info"><?php echo (int)($upcomingCount ?? 0); ?></span>
                <span class="stat-label">Active Deadlines</span>
            </div>
            <div class="page-stat-pill">
                <span class="stat-num text-success"><?php echo count($deadlines); ?></span>
                <span class="stat-label">Total Stages Configured</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Filter Tabs (Only shown if coordinator is for All Shifts) ═══════════════ -->
<?php if ($isCoordinatorMultiShift): ?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="text-secondary fw-semibold small me-1">Filter by Audience Shift:</span>
        <a href="<?php echo $basePath; ?>/coordinator/deadlines?shift=All" class="shift-tab-pill <?php echo $selectedShift === 'All' ? 'active' : ''; ?>">
            <i class="bi bi-layers-fill me-1"></i> All Shifts
        </a>
        <a href="<?php echo $basePath; ?>/coordinator/deadlines?shift=Morning" class="shift-tab-pill <?php echo $selectedShift === 'Morning' ? 'active' : ''; ?>">
            <i class="bi bi-sun-fill me-1"></i> Morning
        </a>
        <a href="<?php echo $basePath; ?>/coordinator/deadlines?shift=Evening" class="shift-tab-pill <?php echo $selectedShift === 'Evening' ? 'active' : ''; ?>">
            <i class="bi bi-moon-stars-fill me-1"></i> Evening
        </a>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- ═══════════════ Set / Update Deadline Form (col-lg-4) ═══════════════ -->
    <div class="col-lg-4">
        <div class="deadline-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom" style="border-color: var(--border-color) !important;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="bi bi-calendar-plus-fill fs-6"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold m-0" id="formHeaderTitle" style="color: var(--text-primary); font-size: 0.95rem;">Set Stage Deadline</h6>
                        <small class="text-muted" style="font-size: 0.74rem;">
                            <?php if ($isCoordinatorMultiShift): ?>
                                Define deadlines &amp; choose shift
                            <?php else: ?>
                                Audience: <?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
                <button type="button" id="cancelEditBtn" class="btn btn-sm btn-light rounded-pill px-3 py-1 fw-semibold text-secondary d-none" onclick="resetDeadlineForm()" style="font-size: 0.75rem;">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
            </div>

            <form action="<?php echo $basePath; ?>/coordinator/deadlines/save" method="POST" id="deadlineForm">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="id" id="deadline_id" value="0">

                <!-- Target Department (Read-only locked badge) -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary mb-1">Target Department</label>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: var(--form-bg); border: 1px solid var(--border-color); font-size: 0.85rem; color: var(--text-primary); font-weight: 600;">
                        <i class="bi bi-shield-lock-fill text-muted"></i>
                        <span><?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="badge rounded-pill ms-auto px-2.5 py-1" style="background: rgba(59,130,246,0.1); color: #2563eb; font-size: 0.7rem;">Locked</span>
                    </div>
                </div>

                <!-- Target Shift Audience -->
                <?php if ($isCoordinatorMultiShift): ?>
                    <!-- Dropdown for Multi-shift Coordinator -->
                    <div class="mb-3">
                        <label for="shiftSelect" class="form-label small fw-bold text-secondary mb-1">Target Shift Audience <span class="text-danger">*</span></label>
                        <select class="form-select deadline-form-control fw-semibold" id="shiftSelect" name="shift" required>
                            <option value="All">All Shifts (Morning &amp; Evening)</option>
                            <option value="Morning">Morning Shift Only</option>
                            <option value="Evening">Evening Shift Only</option>
                        </select>
                    </div>
                <?php else: ?>
                    <!-- Hidden & Locked Display for Single-shift Coordinator -->
                    <input type="hidden" name="shift" id="shiftSelect" value="<?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-1">Target Shift Audience</label>
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: var(--form-bg); border: 1px solid var(--border-color); font-size: 0.85rem; color: var(--text-primary); font-weight: 600;">
                            <i class="bi bi-check2-circle text-success"></i>
                            <span><?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift Only</span>
                            <span class="badge rounded-pill ms-auto px-2.5 py-1" style="background: rgba(16,185,129,0.12); color: #059669; font-size: 0.7rem;">Assigned</span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Project Stage -->
                <div class="mb-3">
                    <label for="stageSelect" class="form-label small fw-bold text-secondary mb-1">Project Stage <span class="text-danger">*</span></label>
                    <select class="form-select deadline-form-control fw-semibold" id="stageSelect" name="stage" required>
                        <option value="Proposal Submission">Proposal Submission</option>
                        <option value="Proposal Defence Presentation">Proposal Defence Presentation</option>
                        <option value="FYP Progress Presentation">FYP Progress Presentation</option>
                        <option value="Final Presentation">Final Presentation</option>
                    </select>
                </div>

                <!-- Deadline Date & Time -->
                <div class="mb-3">
                    <label for="deadlineDateInput" class="form-label small fw-bold text-secondary mb-1">Deadline Date &amp; Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control deadline-form-control fw-semibold" id="deadlineDateInput" name="deadline_date" required>
                </div>

                <!-- Status Selection -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary mb-1.5 d-block">Milestone Status</label>
                    <div class="d-flex align-items-center gap-3">
                        <label class="d-flex align-items-center gap-2 m-0" style="cursor: pointer;">
                            <input type="radio" name="status" id="statusActive" value="Active" checked class="form-check-input mt-0">
                            <span class="badge rounded-pill px-3 py-1" style="background: rgba(16,185,129,0.12); color: #059669; font-size: 0.74rem; font-weight: 700;">Active</span>
                        </label>
                        <label class="d-flex align-items-center gap-2 m-0" style="cursor: pointer;">
                            <input type="radio" name="status" id="statusInactive" value="Inactive" class="form-check-input mt-0">
                            <span class="badge rounded-pill px-3 py-1" style="background: rgba(107,114,128,0.12); color: #6b7280; font-size: 0.74rem; font-weight: 600;">Inactive</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="btn rounded-pill fw-bold w-100 py-2.5 shadow-sm text-white transition-all d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #10b981, #059669); font-size: 0.9rem;">
                    <i class="bi bi-check2-circle me-2 fs-6"></i>
                    <span id="submitBtnText">Publish Deadline</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════════ Deadlines Table (col-lg-8) ═══════════════ -->
    <div class="col-lg-8">
        <div class="deadline-card h-100 d-flex flex-column">
            <div class="p-3.5 px-4 d-flex align-items-center justify-content-between border-bottom" style="border-color: var(--border-color) !important;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(59,130,246,0.1); color: #2563eb;">
                        <i class="bi bi-clock-history fs-6"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold m-0" style="color: var(--text-primary); font-size: 0.95rem;">Scheduled Timeline</h6>
                        <small class="text-muted" style="font-size: 0.74rem;">Active milestones and submission deadlines</small>
                    </div>
                </div>
                <span class="badge rounded-pill px-3 py-1 font-monospace" style="background: rgba(16,185,129,0.1); color: #059669; font-size: 0.76rem; font-weight: 700;">
                    <?php echo count($deadlines); ?> Stages
                </span>
            </div>

            <div class="table-responsive flex-grow-1">
                <table class="table deadline-table m-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 30%;">Stage</th>
                            <th style="width: 16%;">Audience</th>
                            <th style="width: 28%;">Deadline</th>
                            <th style="width: 13%;">Status</th>
                            <th class="text-end pe-4" style="width: 13%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($deadlines as $dl): 
                            $dlShift = $dl['shift'] ?? 'All';
                            $dlTime = strtotime($dl['deadline_date']);
                            $isPassed = $dlTime < time();
                            $daysLeft = ceil(($dlTime - time()) / 86400);

                            $shiftBadgeBg = 'rgba(139,92,246,0.1)';
                            $shiftBadgeColor = '#7c3aed';
                            if ($dlShift === 'Morning') {
                                $shiftBadgeBg = 'rgba(16,185,129,0.12)';
                                $shiftBadgeColor = '#059669';
                            } else if ($dlShift === 'Evening') {
                                $shiftBadgeBg = 'rgba(59,130,246,0.12)';
                                $shiftBadgeColor = '#2563eb';
                            }
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 0.9rem; line-height: 1.35;">
                                    <?php echo htmlspecialchars($dl['stage'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <span class="text-muted d-block mt-0.5" style="font-size: 0.74rem;">
                                    Updated: <?php echo date('M d, Y', strtotime($dl['updated_at'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill text-nowrap px-2.5 py-1" style="background: <?php echo $shiftBadgeBg; ?>; color: <?php echo $shiftBadgeColor; ?>; font-size: 0.74rem; font-weight: 700;">
                                    <i class="bi bi-people-fill me-1"></i><?php echo htmlspecialchars($dlShift, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-calendar3 text-primary" style="font-size: 0.85rem;"></i>
                                    <span class="fw-bold" style="color: var(--text-primary); font-size: 0.88rem;">
                                        <?php echo date('M d, Y', $dlTime); ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-nowrap">
                                    <span class="text-muted fw-medium" style="font-size: 0.76rem;">
                                        <i class="bi bi-clock me-1 text-muted"></i><?php echo date('h:i A', $dlTime); ?>
                                    </span>
                                    <?php if ($dl['status'] === 'Active'): ?>
                                        <?php if ($isPassed): ?>
                                            <span class="badge rounded-pill px-2 py-0.5" style="background: rgba(239,68,68,0.1); color: #dc2626; font-size: 0.68rem; font-weight: 700;">Passed</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill px-2.5 py-0.5" style="background: rgba(16,185,129,0.12); color: #059669; font-size: 0.68rem; font-weight: 700;">
                                                <?php echo $daysLeft == 0 ? 'Today' : ($daysLeft == 1 ? 'Tomorrow' : "$daysLeft days left"); ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($dl['status'] === 'Active'): ?>
                                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap" style="background: rgba(16,185,129,0.12); color: #059669; font-size: 0.74rem; font-weight: 700;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap" style="background: rgba(107,114,128,0.12); color: #6b7280; font-size: 0.74rem; font-weight: 600;">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center" style="gap: 6px;">
                                    <!-- Edit Button -->
                                    <button type="button" class="action-btn" title="Edit Deadline" onclick='editDeadline(<?php echo json_encode($dl, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="<?php echo $basePath; ?>/coordinator/deadlines/delete" method="POST" class="m-0 d-inline-block" onsubmit="return confirm('Are you sure you want to remove the deadline for &quot;<?php echo htmlspecialchars($dl['stage'], ENT_QUOTES, 'UTF-8'); ?>&quot;?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id" value="<?php echo (int)$dl['id']; ?>">
                                        <button type="submit" class="action-btn delete" title="Delete Deadline">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($deadlines)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="mb-2" style="font-size: 2.2rem; opacity: 0.35;"><i class="bi bi-calendar-x"></i></div>
                                    <h6 class="fw-bold" style="color: var(--text-primary); font-size: 0.95rem;">No Deadlines Configured</h6>
                                    <p class="small text-muted mb-0">Use the form on the left to set submission dates for your department and shift.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function editDeadline(dl) {
    document.getElementById('deadline_id').value = dl.id || 0;
    document.getElementById('stageSelect').value = dl.stage || 'Proposal Submission';
    
    const shiftEl = document.getElementById('shiftSelect');
    if (shiftEl && shiftEl.tagName === 'SELECT') {
        shiftEl.value = dl.shift || 'All';
    }
    
    // Format date for datetime-local input
    if (dl.deadline_date) {
        const d = new Date(dl.deadline_date);
        const pad = (n) => String(n).padStart(2, '0');
        const formatted = d.getFullYear() + '-' + 
            pad(d.getMonth() + 1) + '-' + 
            pad(d.getDate()) + 'T' + 
            pad(d.getHours()) + ':' + 
            pad(d.getMinutes());
        document.getElementById('deadlineDateInput').value = formatted;
    }

    if (dl.status === 'Inactive') {
        document.getElementById('statusInactive').checked = true;
    } else {
        document.getElementById('statusActive').checked = true;
    }

    document.getElementById('formHeaderTitle').textContent = 'Edit Stage Deadline';
    document.getElementById('submitBtnText').textContent = 'Update Deadline';
    document.getElementById('cancelEditBtn').classList.remove('d-none');
    
    // Smooth scroll to form on mobile
    document.getElementById('deadlineForm').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function resetDeadlineForm() {
    document.getElementById('deadline_id').value = 0;
    document.getElementById('formHeaderTitle').textContent = 'Set Stage Deadline';
    document.getElementById('submitBtnText').textContent = 'Publish Deadline';
    document.getElementById('cancelEditBtn').classList.add('d-none');
    document.getElementById('deadlineDateInput').value = '';
    document.getElementById('statusActive').checked = true;
}
</script>
