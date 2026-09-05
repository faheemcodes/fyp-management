<?php
$title = "Presentation Evaluation Sheets - Coordinator Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$totalCommittees = $totalCommittees ?? count($committeesGrouped ?? []);
?>

<!-- ═══════════════ Hero Header ═══════════════ -->
<div class="page-hero mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f766e 100%);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="page-hero-icon" style="background: rgba(255, 255, 255, 0.12); color: #2dd4bf; width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-printer-fill fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Presentation Evaluation Sheets</h4>
                    <?php if ($totalCommittees > 0): ?>
                        <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                            <i class="bi bi-people-fill me-1"></i><?php echo (int)$totalCommittees; ?> Committees
                        </span>
                    <?php endif; ?>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.78); font-size: 0.85rem">
                    Generate and print official evaluation sheets for all committees matching the committee portal format
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo $basePath; ?>/coordinator/attendance-sheet" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> <span>Attendance Sheets</span>
            </a>
            <a href="<?php echo $basePath; ?>/coordinator/committees" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-diagram-3-fill"></i> <span>Group Allocation</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ Info Banner ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: rgba(16, 185, 129, 0.05); border-left: 4px solid #10b981 !important;">
    <div class="d-flex gap-3 align-items-center">
        <div class="p-2 rounded-circle text-success" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: rgba(16, 185, 129, 0.15); flex-shrink: 0;">
            <i class="bi bi-check-circle-fill fs-5"></i>
        </div>
        <div class="small" style="font-size: 0.84rem; color: var(--text-secondary, #475569);">
            <strong style="color: var(--text-primary, #1e293b);">Committee-Ready Evaluation Sheets:</strong> These sheets fetch the exact table layout used in the Committee portal. When you choose <strong>All Committees</strong>, each committee cleanly prints on its own page with its specific evaluators' names, signature line, and assigned student groups.
        </div>
    </div>
</div>

<!-- ═══════════════ Quick Action Cards for All 3 Presentations ═══════════════ -->
<div class="row g-4 mb-4">
    <!-- 1. Proposal Defence Presentation -->
    <div class="col-md-4">
        <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center d-flex flex-column justify-content-between" style="background: var(--card-bg, #ffffff); border-top: 4px solid #3b82f6 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                    <i class="bi bi-file-earmark-text-fill fs-4"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1.05rem;">Proposal Defence</h5>
                <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                    Initial proposal evaluation sheet with Project Details, 40 marks column, and Evaluator Remarks.
                </p>
            </div>
            <div>
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=Proposal+Defence+Presentation&committee=all" target="_blank" class="btn btn-outline-primary rounded-pill w-100 fw-semibold py-2" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print All Committees
                </a>
            </div>
        </div>
    </div>

    <!-- 2. FYP Progress Presentation -->
    <div class="col-md-4">
        <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center d-flex flex-column justify-content-between" style="background: var(--card-bg, #ffffff); border-top: 4px solid #06b6d4 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(6, 182, 212, 0.12); color: #0891b2;">
                    <i class="bi bi-graph-up-arrow fs-4"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1.05rem;">Progress Presentation</h5>
                <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                    Midway progress evaluation including Previous Comments column, 40 marks, and Remarks.
                </p>
            </div>
            <div>
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=FYP+Progress+Presentation&committee=all" target="_blank" class="btn btn-outline-info rounded-pill w-100 fw-semibold py-2 text-dark" style="font-size: 0.85rem;">
                    <i class="bi bi-printer-fill me-1"></i> Print All Committees
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Final Presentation -->
    <div class="col-md-4">
        <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center d-flex flex-column justify-content-between" style="background: var(--card-bg, #ffffff); border-top: 4px solid #10b981 !important;">
            <div>
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-trophy-fill fs-4"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1.05rem;">Final Presentation</h5>
                <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                    Final defense evaluation with Presentation (25), Thesis (25), and Project Demo (25).
                </p>
            </div>
            <div class="d-flex flex-column gap-2">
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=Final+Presentation&view=minimized&committee=all" target="_blank" class="btn btn-success rounded-pill w-100 fw-semibold py-2 shadow-sm text-white" style="font-size: 0.85rem;">
                    <i class="bi bi-arrows-angle-contract me-1"></i> Print Minimize Version (All)
                </a>
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets/print?stage=Final+Presentation&view=detailed&committee=all" target="_blank" class="btn btn-outline-secondary rounded-pill w-100 fw-semibold py-1.5" style="font-size: 0.78rem;">
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
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--form-bg, #f8fafc); border-color: var(--border-color, #e2e8f0) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-circle" style="background: rgba(16, 185, 129, 0.1); color: #059669; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-sliders fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color: var(--text-primary, #0f172a); font-size: 1rem;">Custom Presentation Sheet Generator</h6>
                        <small class="text-muted">Filter by committee, batch, or presentation stage</small>
                    </div>
                </div>
                <span class="badge rounded-pill px-3 py-1.5" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 0.78rem;">
                    <i class="bi bi-printer me-1"></i> Print / PDF Ready
                </span>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="<?php echo $basePath; ?>/coordinator/presentation-sheets/print" method="GET" target="_blank">

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
                                    <strong>Minimize Version</strong> <span class="text-muted small">(Merged Presentation 25 &amp; Thesis 25)</span>
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
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border: none;">
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
