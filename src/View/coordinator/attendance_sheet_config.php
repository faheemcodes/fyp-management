<!-- Coordinator Attendance Sheet Configuration View -->
<?php
$title = 'Attendance Sheets';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.15); border-radius: 16px; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-file-earmark-spreadsheet-fill text-white fs-3"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1">Presentation Attendance Sheets</h4>
                <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Generate and print official attendance sheets organized by committees for presentations</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo $basePath; ?>/coordinator/committees" class="btn btn-outline-light rounded-pill px-4 fw-semibold shadow-sm">
                <i class="bi bi-people-fill me-1"></i> Committee Allocation
            </a>
        </div>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <!-- Generator Form Card -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5" style="background: var(--card-bg, #ffffff);">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="p-3 rounded-circle" style="background: rgba(4, 127, 176, 0.1); color: #047fb0;">
                    <i class="bi bi-printer-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--text-primary, #1e293b);">Configure Attendance Sheet</h5>
                    <small class="text-muted">Fill in the presentation parameters to generate printable sheets with per-committee page breaks</small>
                </div>
            </div>

            <form action="<?php echo $basePath; ?>/coordinator/attendance-sheet/print" method="GET" target="_blank">

                <!-- Presentation Title -->
                <div class="mb-4">
                    <label class="form-label fw-bold" style="font-size: 0.92rem; color: var(--text-primary, #1e293b);">
                        Presentation / Event Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="presentation_name_input" name="presentation_name" class="form-control form-control-lg rounded-3 shadow-none" value="Proposal defense" required placeholder="e.g. Proposal defense">
                    
                    <!-- Quick Presets -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                        <span class="text-muted small fw-semibold">Quick Presets:</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 preset-btn" onclick="setPreset('Proposal defense')">Proposal defense</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 preset-btn" onclick="setPreset('FYP Progress Presentation')">FYP Progress</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 preset-btn" onclick="setPreset('Final Defense Presentation')">Final Defense</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 preset-btn" onclick="setPreset('Initial Presentation')">Initial Presentation</button>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Committee Selection -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="font-size: 0.92rem; color: var(--text-primary, #1e293b);">
                            Committee Scope
                        </label>
                        <select name="committee" class="form-select form-select-lg rounded-3 shadow-none">
                            <option value="all" selected>All Committees (Separate Pages)</option>
                            <?php foreach ($committeesGrouped as $cNum => $members): ?>
                                <option value="<?php echo $cNum; ?>">
                                    Committee <?php echo $cNum; ?> (<?php echo count($members); ?> Evaluators)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Printing "All" automatically puts each committee on a fresh page.</small>
                    </div>

                    <!-- Academic Batch -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="font-size: 0.92rem; color: var(--text-primary, #1e293b);">
                            Academic Batch
                        </label>
                        <select name="batch_id" class="form-select form-select-lg rounded-3 shadow-none">
                            <?php foreach ($batches as $b): ?>
                                <option value="<?php echo $b['id']; ?>" <?php echo $b['is_active'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($b['name']); ?> (<?php echo htmlspecialchars($b['shift']); ?>) <?php echo $b['is_active'] ? '— Active' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Defaults to current active batch.</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Session / Year -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="font-size: 0.92rem; color: var(--text-primary, #1e293b);">
                            FYP Session / Year
                        </label>
                        <input type="text" name="session_year" class="form-control form-control-lg rounded-3 shadow-none" value="<?php echo date('Y'); ?>" placeholder="e.g. 2026">
                    </div>

                    <!-- Department and Shift Info -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="font-size: 0.92rem; color: var(--text-primary, #1e293b);">
                            Department &amp; Shift
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-none bg-light" value="<?php echo htmlspecialchars($department . ' (' . $shift . ')'); ?>" readonly>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex align-items-center justify-content-end gap-3 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                        <i class="bi bi-printer-fill me-2"></i> Generate &amp; Print Attendance Sheet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info / Demo Specs Card -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background: var(--card-bg, #ffffff);">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--text-primary, #1e293b);">
                <i class="bi bi-info-circle-fill text-primary"></i> Official Sheet Format
            </h6>
            
            <p class="text-muted small mb-3">
                The generated document follows the official department attendance layout:
            </p>

            <ul class="list-unstyled d-flex flex-column gap-3 small mb-4">
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong>Department Header &amp; Crest:</strong> Includes official faculty heading, session year, batch name, degree, and custom presentation title.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong>Tabular Columns:</strong> Serial Number, Project ID (`g.group_code`), Project Title, Group Members (Roll Number and Name), and Signature space for each student.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong>Per-Committee Isolation:</strong> Each committee's cohort prints on a separate page with its assigned evaluators.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong>Evaluator Sign-off:</strong> Dedicated signature blocks for Committee Evaluators and the FYP Coordinator at the bottom of each sheet.
                    </div>
                </li>
            </ul>

            <div class="p-3 rounded-3" style="background: rgba(59, 130, 246, 0.08); border-left: 3px solid #3b82f6;">
                <span class="d-block fw-bold text-primary small mb-1"><i class="bi bi-lightbulb-fill me-1"></i> Printing Tip</span>
                <span class="text-muted small">In the browser print dialog, select <strong>A4</strong> paper and ensure <strong>"Background Graphics"</strong> is enabled for table shading.</span>
            </div>
        </div>
    </div>
</div>

<script>
function setPreset(name) {
    document.getElementById('presentation_name_input').value = name;
}
</script>
