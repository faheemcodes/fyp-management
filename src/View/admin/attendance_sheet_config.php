<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$selectedDept = $selectedDept ?? 'Software Engineering';
$selectedShift = $selectedShift ?? 'Morning';
$selectedBatchId = $selectedBatchId ?? 0;
$selectedCommittee = $selectedCommittee ?? 1;
$selectedStage = $selectedStage ?? 'Proposal Defence Presentation';
?>

<style>
.form-control-custom {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #0f172a);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}
.form-control-custom:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    outline: none;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Presentation Attendance Sheets</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Generate and print official attendance sheets for student defense and evaluation panels across all departments
                </p>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo $basePath; ?>/admin/attendance-sheet/print" method="GET" target="_blank">
    <div class="row g-4">
        <!-- Configuration Controls -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: var(--card-bg);">
                <div class="card-header border-bottom p-3 p-md-4" style="background: var(--form-bg);">
                    <h6 class="fw-bold m-0 text-dark">
                        <i class="bi bi-sliders me-2 text-primary"></i>Attendance Sheet Parameters
                    </h6>
                </div>
                <div class="card-body p-4">
                    <!-- Department -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Department</label>
                        <select name="department" id="deptSelect" class="form-select form-control-custom" onchange="window.location.href='<?php echo $basePath; ?>/admin/attendance-sheet?department='+encodeURIComponent(this.value)+'&shift='+encodeURIComponent(document.getElementById('shiftSelect').value)">
                            <?php foreach ($departments as $d): ?>
                                <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($selectedDept === $d) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Batch & Shift -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">Academic Batch</label>
                            <select name="batch_id" class="form-select form-control-custom" onchange="window.location.href='<?php echo $basePath; ?>/admin/attendance-sheet?department='+encodeURIComponent(document.getElementById('deptSelect').value)+'&batch_id='+this.value+'&shift='+encodeURIComponent(document.getElementById('shiftSelect').value)">
                                <?php foreach ($batches as $b): ?>
                                    <option value="<?php echo $b['id']; ?>" <?php echo ($selectedBatchId == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['name']); ?> <?php echo !empty($b['is_active']) ? '(Active)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">Shift</label>
                            <select name="shift" id="shiftSelect" class="form-select form-control-custom" onchange="window.location.href='<?php echo $basePath; ?>/admin/attendance-sheet?department='+encodeURIComponent(document.getElementById('deptSelect').value)+'&shift='+encodeURIComponent(this.value)">
                                <option value="Morning" <?php echo ($selectedShift === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                                <option value="Evening" <?php echo ($selectedShift === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                            </select>
                        </div>
                    </div>

                    <!-- Committee Number -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Committee Number</label>
                        <select name="committee_number" class="form-select form-control-custom" onchange="window.location.href='<?php echo $basePath; ?>/admin/attendance-sheet?department='+encodeURIComponent(document.getElementById('deptSelect').value)+'&shift='+encodeURIComponent(document.getElementById('shiftSelect').value)+'&committee_number='+this.value">
                            <?php for ($c = 1; $c <= 8; $c++): ?>
                                <option value="<?php echo $c; ?>" <?php echo ($selectedCommittee == $c) ? 'selected' : ''; ?>>
                                    Committee <?php echo $c; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Presentation Stage -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Presentation Stage</label>
                        <select name="stage" class="form-select form-control-custom">
                            <option value="Proposal Defence Presentation" <?php echo ($selectedStage === 'Proposal Defence Presentation') ? 'selected' : ''; ?>>Proposal Defence Presentation</option>
                            <option value="FYP Progress Presentation" <?php echo ($selectedStage === 'FYP Progress Presentation') ? 'selected' : ''; ?>>FYP Progress Presentation</option>
                            <option value="Final Presentation" <?php echo ($selectedStage === 'Final Presentation') ? 'selected' : ''; ?>>Final Presentation</option>
                        </select>
                    </div>

                    <!-- Date, Time, Venue -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-4">
                            <label class="form-label small fw-bold text-secondary">Date</label>
                            <input type="date" name="date" class="form-control form-control-custom" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label small fw-bold text-secondary">Time</label>
                            <input type="text" name="time" class="form-control form-control-custom" value="09:00 AM" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label small fw-bold text-secondary">Hall / Room</label>
                            <input type="text" name="venue" class="form-control form-control-custom" value="FYP Lab 1" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-printer-fill"></i> <span>Generate &amp; Print Attendance Sheet</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Groups Preview -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: var(--card-bg);">
                <div class="card-header border-bottom p-3 p-md-4 d-flex align-items-center justify-content-between" style="background: var(--form-bg);">
                    <h6 class="fw-bold m-0 text-dark">
                        <i class="bi bi-people-fill me-2 text-primary"></i>Assigned Groups in Committee <?php echo $selectedCommittee; ?> (<?php echo count($groups); ?>)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($groups)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                            No groups found for Committee <?php echo $selectedCommittee; ?> in <?php echo htmlspecialchars($selectedDept); ?> (<?php echo htmlspecialchars($selectedShift); ?>).
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr style="background: var(--form-bg);">
                                        <th class="ps-4" style="font-size: 0.75rem; text-transform: uppercase;">Group Code</th>
                                        <th style="font-size: 0.75rem; text-transform: uppercase;">Project Title</th>
                                        <th style="font-size: 0.75rem; text-transform: uppercase;">Team Members</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($groups as $grp): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold font-monospace text-primary"><?php echo htmlspecialchars($grp['group_code']); ?></td>
                                            <td class="fw-semibold text-dark text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($grp['project_title']); ?></td>
                                            <td>
                                                <div class="small">
                                                    <?php foreach ($grp['members'] as $m): ?>
                                                        <div>&bull; <?php echo htmlspecialchars($m['name']); ?> (<?php echo htmlspecialchars($m['student_id']); ?>)</div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>
