<!-- Coordinator Presentation Sheet Configuration View -->
<?php
$title = "Presentation Evaluation Sheets - Coordinator Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$totalCommittees = $totalCommittees ?? count($committeesGrouped ?? []);
$department = $department ?? 'Software Engineering';
$shift = $shift ?? 'Morning';
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
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #0f172a);
    outline: none;
}
.stage-action-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.06));
    border-radius: 18px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    box-shadow: var(--card-shadow, 0 4px 15px rgba(0,0,0,0.03));
}
.stage-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <!-- Left: Icon & Titles -->
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-printer-fill"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.45);">
                    Department Coordinator
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2">
                    Presentation Evaluation Sheets
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap justify-content-center justify-content-md-start">
                    <?php if ($totalCommittees > 0): ?>
                        <span class="badge rounded-pill px-3 py-1.5 text-nowrap" style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.95); font-size: 0.76rem; font-weight: 600;">
                            <i class="bi bi-people-fill me-1.5"></i><?php echo (int)$totalCommittees; ?> Committees
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($activeBatch)): ?>
                        <span class="badge rounded-pill px-3 py-1.5 text-nowrap" style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.95); font-size: 0.76rem; font-weight: 600;">
                            <i class="bi bi-mortarboard-fill me-1.5"></i>Batch: <?php echo htmlspecialchars($activeBatch['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="mb-0 mt-2" style="color: rgba(255,255,255,0.78); font-size: 0.84rem">
                    Generate and print official evaluation sheets for all committees matching the committee portal format
                </p>
            </div>
        </div>

        <!-- Right: Action Buttons -->
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <a href="<?php echo $basePath; ?>/coordinator/cumulative-sheet" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-file-earmark-ruled-fill text-primary"></i> <span>Cumulative Sheet</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ Info Banner ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: rgba(59, 130, 246, 0.05); border-left: 4px solid #3b82f6 !important;">
    <div class="d-flex gap-3 align-items-center">
        <div class="p-2 rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.15); color: #2563eb; flex-shrink: 0;">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
        <div class="small" style="font-size: 0.84rem; color: var(--text-secondary, #475569); line-height: 1.5;">
            <strong style="color: var(--text-primary, #1e293b);">Committee-Ready Evaluation Sheets:</strong> These sheets fetch the exact evaluation rubric and table layout used in the Committee portal. When you choose <strong>All Committees</strong>, each committee cleanly prints on its own page with committee header, evaluator blanks, and assigned student projects.
        </div>
    </div>
</div>

<!-- ═══════════════ Quick Action Cards for All 3 Presentations ═══════════════ -->
<div class="row g-4 mb-4">
    <!-- 1. Proposal Defence Presentation -->
    <div class="col-md-4">
        <div class="card stage-action-card h-100 border-0 p-4 text-center d-flex flex-column justify-content-between" style="border-top: 4px solid #3b82f6 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
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
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=Proposal+Defence+Presentation&committee=all" class="btn btn-outline-primary rounded-pill w-100 fw-semibold py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print All Committees
                </a>
            </div>
        </div>
    </div>

    <!-- 2. FYP Progress Presentation -->
    <div class="col-md-4">
        <div class="card stage-action-card h-100 border-0 p-4 text-center d-flex flex-column justify-content-between" style="border-top: 4px solid #06b6d4 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(6, 182, 212, 0.12); color: #0891b2;">
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
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=FYP+Progress+Presentation&committee=all" class="btn btn-outline-info rounded-pill w-100 fw-semibold py-2 text-dark" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print All Committees
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Final Presentation -->
    <div class="col-md-4">
        <div class="card stage-action-card h-100 border-0 p-4 text-center d-flex flex-column justify-content-between" style="border-top: 4px solid #10b981 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(16, 185, 129, 0.12); color: #059669;">
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
            <div class="d-flex flex-column gap-2">
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=Final+Presentation&view=minimized&committee=all" class="btn btn-success rounded-pill w-100 fw-semibold py-2 shadow-sm text-white" style="font-size: 0.85rem;">
                    <i class="bi bi-arrows-angle-contract me-1"></i> Print Minimized Version (All)
                </a>
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=Final+Presentation&view=detailed&committee=all" class="btn btn-outline-secondary rounded-pill w-100 fw-semibold py-1.5" style="font-size: 0.78rem;">
                    <i class="bi bi-arrows-angle-expand me-1"></i> Print Detailed Version (All)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Custom Print Configuration Form ═══════════════ -->
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="background: var(--card-bg, #ffffff);">
            <div class="page-section-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-section-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color: var(--text-primary);">Custom Presentation Sheet Generator</h6>
                        <small class="text-muted">Configure stage, committee filter, academic batch, and sheet date</small>
                    </div>
                </div>
                <span class="badge rounded-pill px-3 py-1.5" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 0.76rem;">
                    <i class="bi bi-printer me-1"></i> Print / PDF Ready
                </span>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="<?php echo $basePath; ?>/coordinator/presentation-sheets/print" method="GET">

                    <!-- Presentation Stage -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary mb-1" for="stage_select">
                            Presentation Stage <span class="text-danger">*</span>
                        </label>
                        <select id="stage_select" name="stage" class="form-select form-control-custom" required onchange="toggleFinalOptions(this.value)">
                            <option value="Proposal Defence Presentation">Proposal Defence Presentation</option>
                            <option value="FYP Progress Presentation">FYP Progress Presentation</option>
                            <option value="Final Presentation" selected>Final Presentation</option>
                        </select>
                    </div>

                    <!-- Final Presentation Version (Only shown for Final Presentation) -->
                    <div class="mb-4" id="final_view_container">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            Final Presentation Layout Format
                        </label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="view" id="view_minimized" value="minimized" checked>
                                <label class="form-check-label fw-semibold" for="view_minimized" style="font-size: 0.9rem;">
                                    <strong>Minimized Version</strong> <span class="text-muted small">(Merged Presentation 25 &amp; Thesis 25)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="view" id="view_detailed" value="detailed">
                                <label class="form-check-label fw-semibold" for="view_detailed" style="font-size: 0.9rem;">
                                    <strong>Detailed Version</strong> <span class="text-muted small">(5 sub-columns each)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Committee Selection -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary mb-1" for="committee_select">
                            Committee Filter <span class="text-danger">*</span>
                        </label>
                        <select id="committee_select" name="committee" class="form-select form-control-custom">
                            <option value="all" selected>All Committees (Separate page per committee)</option>
                            <?php foreach ($committeesGrouped as $cNum => $membersList): ?>
                                <?php 
                                $names = array_map(fn($m) => $m['name'], $membersList);
                                $namesStr = !empty($names) ? ' - ' . implode(', ', $names) : '';
                                ?>
                                <option value="<?php echo (int)$cNum; ?>">
                                    Committee #<?php echo (int)$cNum; ?><?php echo htmlspecialchars($namesStr); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                            "All Committees" automatically generates a clean page break for each committee when printing.
                        </small>
                    </div>

                    <!-- Academic Batch & Date Row -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1" for="batch_id_select">
                                Academic Batch <span class="text-danger">*</span>
                            </label>
                            <select id="batch_id_select" name="batch_id" class="form-select form-control-custom">
                                <?php foreach ($batches as $b): ?>
                                    <option value="<?php echo (int)$b['id']; ?>" <?php echo (!empty($activeBatch) && $activeBatch['id'] == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['name']); ?> (<?php echo htmlspecialchars($b['shift']); ?>) <?php echo $b['is_active'] ? '— Active' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1" for="dated_input">
                                Sheet Date
                            </label>
                            <input type="text" id="dated_input" name="dated" class="form-control form-control-custom" value="<?php echo date('d-m-Y'); ?>" placeholder="DD-MM-YYYY">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end pt-3 border-top" style="border-color: var(--border-color, #e2e8f0) !important;">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-printer-fill"></i> <span>Generate &amp; Print Evaluation Sheets</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFinalOptions(stage) {
    const container = document.getElementById('final_view_container');
    if (container) {
        container.style.display = (stage === 'Final Presentation') ? 'block' : 'none';
    }
}
</script>
