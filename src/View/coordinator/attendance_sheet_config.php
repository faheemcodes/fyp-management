<!-- Coordinator Attendance Sheet Configuration View -->
<?php
$title = 'Attendance Sheets';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batches = $batches ?? [];
$activeBatch = $activeBatch ?? null;
$committeesGrouped = $committeesGrouped ?? [];
$department = $department ?? 'Software Engineering';
$shift = $shift ?? 'Morning';
$totalCommittees = count($committeesGrouped);
?>

<style>
/* Filter / Preset Pills */
.btn-filter-pill {
    background: var(--form-bg, #f8fafc);
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    font-size: 0.8rem;
    padding: 6px 16px;
    border-radius: 999px;
    transition: all 0.2s ease;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    user-select: none;
}
.btn-filter-pill:hover {
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #1e293b);
    border-color: #047fb0;
    transform: translateY(-1px);
}
.btn-filter-pill.active {
    background: #047fb0;
    color: #ffffff;
    border-color: #047fb0;
    box-shadow: 0 2px 8px rgba(4, 127, 176, 0.25);
}

.form-control-custom {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #0f172a);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.92rem;
    transition: all 0.2s ease;
}
.form-control-custom:focus {
    border-color: #047fb0;
    box-shadow: 0 0 0 3px rgba(4, 127, 176, 0.15);
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #0f172a);
    outline: none;
}
</style>

<!-- ═══════════════ Top Hero Banner (Standard Portal Design) ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.45)">
                    Presentation Evaluation &amp; Attendance
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Presentation Attendance Sheets</h4>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mt-2">
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(16, 185, 129, 0.25); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-clock-history me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
                    </span>
                    <?php if ($totalCommittees > 0): ?>
                        <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(4, 127, 176, 0.25); color: #ffffff; border: 1px solid rgba(4, 127, 176, 0.4); font-size: 0.82rem;">
                            <i class="bi bi-people-fill me-1"></i><?php echo (int)$totalCommittees; ?> Committees
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo $basePath; ?>/coordinator/committees" class="btn btn-sm btn-outline-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-diagram-3-fill"></i> <span>Group Allocation</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ Guidance Notice ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: rgba(59, 130, 246, 0.05); border-left: 4px solid #3b82f6 !important;">
    <div class="d-flex gap-3 align-items-start">
        <div class="p-2 rounded-circle text-primary mt-1" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.15);">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1" style="color: var(--text-primary, #1e293b); font-size: 0.95rem;">Attendance Sheet Generation</h6>
            <p class="mb-0 text-muted small">
                Attendance sheets automatically group students by their assigned committee. When choosing <strong>All Committees</strong>, each committee prints on its own separate page with evaluator signature blocks at the bottom.
            </p>
        </div>
    </div>
</div>

<!-- ═══════════════ Sheet Configuration Card ═══════════════ -->
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="background: var(--card-bg, #ffffff);">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--form-bg, #f8fafc); border-color: var(--border-color, #e2e8f0) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-circle" style="background: rgba(4, 127, 176, 0.1); color: #047fb0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-sliders fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color: var(--text-primary, #0f172a); font-size: 1rem;">Configure Presentation Parameters</h6>
                        <small class="text-muted">Set the presentation title and cohort parameters before printing</small>
                    </div>
                </div>
                <span class="badge rounded-pill px-3 py-1.5" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 0.78rem;">
                    <i class="bi bi-printer me-1"></i> Print / PDF Ready
                </span>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="<?php echo $basePath; ?>/coordinator/attendance-sheet/print" method="GET" target="_blank">

                    <!-- Presentation Title -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary mb-1" for="presentation_name_input">
                            Presentation / Defense Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="presentation_name_input" name="presentation_name" class="form-control form-control-custom" value="Proposal Defense" required placeholder="e.g. Proposal Defense">
                        
                        <!-- Quick Preset Titles -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-1">
                            <span class="text-muted small fw-bold me-1" style="font-size: 0.75rem;">QUICK TITLES:</span>
                            <button type="button" class="btn-filter-pill active" onclick="applyPreset('Proposal Defense', this)">
                                <i class="bi bi-check-lg"></i> Proposal Defense
                            </button>
                            <button type="button" class="btn-filter-pill" onclick="applyPreset('FYP Progress Presentation', this)">
                                FYP Progress Presentation
                            </button>
                            <button type="button" class="btn-filter-pill" onclick="applyPreset('Final Defense Presentation', this)">
                                Final Defense Presentation
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Committee Selection -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1" for="committee_select">
                                Committee Scope
                            </label>
                            <select id="committee_select" name="committee" class="form-select form-control-custom">
                                <option value="all" selected>All Committees (Separate Pages)</option>
                                <?php foreach ($committeesGrouped as $cNum => $members): ?>
                                    <option value="<?php echo (int)$cNum; ?>">
                                        Committee <?php echo (int)$cNum; ?> (<?php echo count($members); ?> Evaluators)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted d-block mt-1 ps-1" style="font-size: 0.76rem;">
                                <i class="bi bi-info-circle me-1"></i>"All" splits each committee onto a separate A4 page.
                            </small>
                        </div>

                        <!-- Academic Batch -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1" for="batch_select">
                                Academic Batch
                            </label>
                            <select id="batch_select" name="batch_id" class="form-select form-control-custom">
                                <?php if (empty($batches)): ?>
                                    <option value="0">Default Batch (All Groups)</option>
                                <?php else: ?>
                                    <?php foreach ($batches as $b): ?>
                                        <option value="<?php echo (int)$b['id']; ?>" <?php echo !empty($b['is_active']) ? 'selected' : ''; ?>>
                                            Batch <?php echo htmlspecialchars($b['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($b['shift'] ?? '', ENT_QUOTES, 'UTF-8'); ?>) <?php echo !empty($b['is_active']) ? '— Active' : ''; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted d-block mt-1 ps-1" style="font-size: 0.76rem;">
                                <i class="bi bi-box-seam me-1"></i>Filter by batch or use current active cohort.
                            </small>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Session / Year -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1" for="session_year_input">
                                FYP Session / Year
                            </label>
                            <input type="text" id="session_year_input" name="session_year" class="form-control form-control-custom" value="<?php echo htmlspecialchars(date('Y'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 2026">
                        </div>

                        <!-- Department & Shift Info -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1">
                                Coordinator Jurisdiction
                            </label>
                            <input type="text" class="form-control form-control-custom bg-light" value="<?php echo htmlspecialchars(($department ?? '') . ' (' . ($shift ?? '') . ' Shift)', ENT_QUOTES, 'UTF-8'); ?>" readonly style="cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top mt-3" style="border-color: var(--border-color, #e2e8f0) !important;">
                        <div class="text-muted small d-flex align-items-center gap-2">
                            <i class="bi bi-printer text-primary fs-5"></i>
                            <span>Opens in a print-ready preview with browser print trigger.</span>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-printer-fill"></i> <span>Generate &amp; Print Attendance Sheet</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function applyPreset(name, chipEl) {
    document.getElementById('presentation_name_input').value = name;
    document.querySelectorAll('.btn-filter-pill').forEach(c => {
        c.classList.remove('active');
        const icon = c.querySelector('.bi-check-lg');
        if (icon) icon.remove();
    });
    if (chipEl) {
        chipEl.classList.add('active');
        if (!chipEl.querySelector('.bi-check-lg')) {
            const check = document.createElement('i');
            check.className = 'bi bi-check-lg me-1';
            chipEl.prepend(check);
        }
    }
}
</script>
