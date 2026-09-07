<?php
$title = "Presentation Evaluation Sheets - Admin Portal";
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
.stage-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.06));
    border-radius: 18px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    box-shadow: var(--card-shadow, 0 4px 15px rgba(0,0,0,0.03));
}
.stage-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-printer-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Presentation Evaluation Sheets</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Generate and print official defense evaluation and rubrics sheets for all evaluation committees
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <a href="<?php echo $basePath; ?>/admin/attendance-sheet" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-file-earmark-spreadsheet-fill text-primary"></i> <span>Attendance Sheets</span>
            </a>
            <a href="<?php echo $basePath; ?>/admin/cumulative-sheet" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-file-earmark-ruled-fill text-info"></i> <span>Cumulative Marks</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ Quick Action Cards for All 3 Stages ═══════════════ -->
<div class="row g-4 mb-4">
    <!-- 1. Proposal Defence Presentation -->
    <div class="col-md-4">
        <div class="card stage-card h-100 border-0 p-4 text-center d-flex flex-column justify-content-between" style="border-top: 4px solid #3b82f6 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                    <i class="bi bi-file-earmark-text-fill fs-4"></i>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary); font-size: 1.05rem;">Proposal Defence</h5>
                    <span class="badge rounded-pill bg-primary-subtle text-primary" style="font-size: 0.72rem;">40 Marks</span>
                </div>
                <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.4;">
                    Initial proposal evaluation sheet with Project Details, 40 marks rubric, and Evaluator Remarks.
                </p>
            </div>
            <div>
                <a href="<?php echo $basePath; ?>/admin/presentation-sheets/print?department=<?php echo urlencode($selectedDept); ?>&shift=<?php echo urlencode($selectedShift); ?>&batch_id=<?php echo (int)$selectedBatchId; ?>&committee_number=<?php echo (int)$selectedCommittee; ?>&stage=Proposal+Defence+Presentation" target="_blank" class="btn btn-outline-primary rounded-pill w-100 fw-semibold py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print Proposal Sheet
                </a>
            </div>
        </div>
    </div>

    <!-- 2. FYP Progress Presentation -->
    <div class="col-md-4">
        <div class="card stage-card h-100 border-0 p-4 text-center d-flex flex-column justify-content-between" style="border-top: 4px solid #06b6d4 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(6, 182, 212, 0.12); color: #0891b2;">
                    <i class="bi bi-graph-up-arrow fs-4"></i>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary); font-size: 1.05rem;">Progress Presentation</h5>
                    <span class="badge rounded-pill bg-info-subtle text-info" style="font-size: 0.72rem;">40 Marks</span>
                </div>
                <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.4;">
                    Midway progress evaluation including Previous Comments column, 40 marks rubric, and Remarks.
                </p>
            </div>
            <div>
                <a href="<?php echo $basePath; ?>/admin/presentation-sheets/print?department=<?php echo urlencode($selectedDept); ?>&shift=<?php echo urlencode($selectedShift); ?>&batch_id=<?php echo (int)$selectedBatchId; ?>&committee_number=<?php echo (int)$selectedCommittee; ?>&stage=FYP+Progress+Presentation" target="_blank" class="btn btn-outline-info rounded-pill w-100 fw-semibold py-2 text-dark" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print Progress Sheet
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Final Presentation -->
    <div class="col-md-4">
        <div class="card stage-card h-100 border-0 p-4 text-center d-flex flex-column justify-content-between" style="border-top: 4px solid #10b981 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-trophy-fill fs-4"></i>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary); font-size: 1.05rem;">Final Presentation</h5>
                    <span class="badge rounded-pill bg-success-subtle text-success" style="font-size: 0.72rem;">75 Marks</span>
                </div>
                <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.4;">
                    Final defense evaluation with Presentation (25), Thesis (25), and Project Demo (25).
                </p>
            </div>
            <div>
                <a href="<?php echo $basePath; ?>/admin/presentation-sheets/print?department=<?php echo urlencode($selectedDept); ?>&shift=<?php echo urlencode($selectedShift); ?>&batch_id=<?php echo (int)$selectedBatchId; ?>&committee_number=<?php echo (int)$selectedCommittee; ?>&stage=Final+Presentation" target="_blank" class="btn btn-success rounded-pill w-100 fw-semibold py-2 shadow-sm text-white" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print Final Sheet
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Custom Evaluation Sheet Generator Form ═══════════════ -->
<form action="<?php echo $basePath; ?>/admin/presentation-sheets/print" method="GET" target="_blank">
    <div class="row g-4">
        <!-- Configuration Controls -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: var(--card-bg);">
                <div class="card-header border-bottom p-3 p-md-4" style="background: var(--form-bg);">
                    <h6 class="fw-bold m-0 text-dark">
                        <i class="bi bi-sliders me-2 text-primary"></i>Sheet Parameters
                    </h6>
                </div>
                <div class="card-body p-4">
                    <!-- Department -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Department</label>
                        <select name="department" id="deptSelect" class="form-select form-control-custom" onchange="updateFilters()">
                            <?php foreach ($departments as $d): ?>
                                <option value="<?php echo htmlspecialchars($d, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($selectedDept === $d) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Batch & Shift -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">Academic Batch</label>
                            <select name="batch_id" id="batchSelect" class="form-select form-control-custom" onchange="updateFilters()">
                                <?php foreach ($batches as $b): ?>
                                    <option value="<?php echo (int)$b['id']; ?>" <?php echo ($selectedBatchId == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> <?php echo !empty($b['is_active']) ? '(Active)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">Shift</label>
                            <select name="shift" id="shiftSelect" class="form-select form-control-custom" onchange="updateFilters()">
                                <option value="Morning" <?php echo ($selectedShift === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                                <option value="Evening" <?php echo ($selectedShift === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                            </select>
                        </div>
                    </div>

                    <!-- Committee Number -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Committee Number</label>
                        <select name="committee_number" id="commSelect" class="form-select form-control-custom" onchange="updateFilters()">
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
                            <label class="form-label small fw-bold text-secondary">Venue</label>
                            <input type="text" name="venue" class="form-control form-control-custom" value="FYP Seminar Hall" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-printer-fill"></i> <span>Generate &amp; Print Evaluation Sheet</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Groups Preview -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: var(--card-bg);">
                <div class="card-header border-bottom p-3 p-md-4 d-flex align-items-center justify-content-between" style="background: var(--form-bg);">
                    <h6 class="fw-bold m-0 text-dark">
                        <i class="bi bi-people-fill me-2 text-primary"></i>Assigned Groups in Committee <?php echo (int)$selectedCommittee; ?> (<?php echo count($groups); ?>)
                    </h6>
                    <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold px-2.5 py-1" style="font-size: 0.75rem;">
                        <?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?> &bull; <?php echo htmlspecialchars($selectedShift, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($groups)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                            No groups found for Committee <?php echo (int)$selectedCommittee; ?> in <?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($selectedShift, ENT_QUOTES, 'UTF-8'); ?>).
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr style="background: var(--form-bg);">
                                        <th class="ps-4" style="font-size: 0.75rem; text-transform: uppercase;">Group Code</th>
                                        <th style="font-size: 0.75rem; text-transform: uppercase;">Project Title</th>
                                        <th style="font-size: 0.75rem; text-transform: uppercase;">Supervisor</th>
                                        <th style="font-size: 0.75rem; text-transform: uppercase;">Team Members</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($groups as $grp): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold font-monospace text-primary"><?php echo htmlspecialchars($grp['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="fw-semibold text-dark text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($grp['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="small text-secondary"><?php echo htmlspecialchars($grp['supervisor_name'] ?? 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <div class="small">
                                                    <?php foreach ($grp['members'] as $m): ?>
                                                        <div>&bull; <?php echo htmlspecialchars($m['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($m['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)</div>
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

<script>
function updateFilters() {
    const dept = encodeURIComponent(document.getElementById('deptSelect').value);
    const batchId = encodeURIComponent(document.getElementById('batchSelect').value);
    const shift = encodeURIComponent(document.getElementById('shiftSelect').value);
    const comm = encodeURIComponent(document.getElementById('commSelect').value);
    window.location.href = '<?php echo $basePath; ?>/admin/presentation-sheets?department=' + dept + '&batch_id=' + batchId + '&shift=' + shift + '&committee_number=' + comm;
}
</script>
