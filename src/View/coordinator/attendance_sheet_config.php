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
/* ─── Attendance Sheet Config Styles ─── */
.att-config-container {
    max-width: 860px;
    margin: 0 auto;
}

.att-hero-card {
    background: linear-gradient(135deg, #047fb0 0%, #0369a1 50%, #0284c7 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(4, 127, 176, 0.2);
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.att-hero-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}

.att-config-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.att-config-card-header {
    background: var(--form-bg, #f8fafc);
    border-bottom: 1px solid var(--border-color, #e2e8f0);
    padding: 20px 28px;
}

.att-config-card-body {
    padding: 32px 28px;
}

.att-form-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text-primary, #0f172a);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.att-input-box {
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, #cbd5e1);
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: var(--text-primary, #0f172a);
    font-weight: 500;
    transition: all 0.2s ease;
}
.att-input-box:focus {
    border-color: #047fb0;
    box-shadow: 0 0 0 3.5px rgba(4, 127, 176, 0.15);
    outline: none;
}

.preset-chip {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 999px;
    background: var(--form-bg, #f1f5f9);
    color: var(--text-secondary, #475569);
    border: 1px solid var(--border-color, #cbd5e1);
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.preset-chip:hover {
    background: rgba(4, 127, 176, 0.1);
    color: #047fb0;
    border-color: rgba(4, 127, 176, 0.4);
    transform: translateY(-1px);
}
.preset-chip.active {
    background: #047fb0;
    color: #ffffff;
    border-color: #047fb0;
    box-shadow: 0 2px 8px rgba(4, 127, 176, 0.25);
}

.att-info-pill {
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    color: #ffffff;
    border-radius: 999px;
    padding: 4px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-generate-print {
    background: linear-gradient(135deg, #047fb0 0%, #0284c7 100%);
    color: #ffffff;
    border: none;
    border-radius: 999px;
    padding: 12px 36px;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    box-shadow: 0 4px 16px rgba(4, 127, 176, 0.35);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-generate-print:hover {
    background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(4, 127, 176, 0.45);
}
</style>

<div class="att-config-container py-3">

    <!-- Hero Banner -->
    <div class="att-hero-card">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative" style="z-index: 1;">
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                    <span class="att-info-pill">
                        <i class="bi bi-mortarboard-fill"></i> <?php echo htmlspecialchars($department ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="att-info-pill">
                        <i class="bi bi-clock-history"></i> <?php echo htmlspecialchars($shift ?? '', ENT_QUOTES, 'UTF-8'); ?> Shift
                    </span>
                    <?php if ($totalCommittees > 0): ?>
                        <span class="att-info-pill">
                            <i class="bi bi-people-fill"></i> <?php echo (int)$totalCommittees; ?> Committees
                        </span>
                    <?php endif; ?>
                </div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Presentation Attendance Sheets</h3>
                <p class="mb-0 text-white-50" style="font-size: 0.92rem;">
                    Configure and print official departmental attendance sheets organized by evaluation committees
                </p>
            </div>
            <div>
                <a href="<?php echo $basePath; ?>/coordinator/committees" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="border: 1.5px solid rgba(255,255,255,0.45); font-size: 0.88rem;">
                    <i class="bi bi-diagram-3-fill"></i> <span>Group Allocation</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Generator Form Card -->
    <div class="att-config-card">
        <div class="att-config-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-circle" style="background: rgba(4, 127, 176, 0.1); color: #047fb0;">
                    <i class="bi bi-sliders fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary, #0f172a); font-size: 1.05rem;">Sheet Configuration</h5>
                    <small class="text-muted">Specify the event name and scope for the physical attendance sheet</small>
                </div>
            </div>
            <span class="badge rounded-pill px-3 py-1.5" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 700; font-size: 0.78rem;">
                <i class="bi bi-check-circle-fill me-1"></i> Ready for Print / PDF
            </span>
        </div>

        <div class="att-config-card-body">
            <form action="<?php echo $basePath; ?>/coordinator/attendance-sheet/print" method="GET" target="_blank">

                <!-- Presentation Title -->
                <div class="mb-4">
                    <label class="att-form-label" for="presentation_name_input">
                        <i class="bi bi-card-heading text-primary"></i> Presentation / Event Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="presentation_name_input" name="presentation_name" class="form-control att-input-box" value="Proposal defense" required placeholder="e.g. Proposal defense">
                    
                    <!-- Quick Presets -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2.5">
                        <span class="text-muted small fw-bold me-1" style="font-size: 0.75rem;">QUICK TITLES:</span>
                        <span class="preset-chip active" onclick="applyPreset('Proposal defense', this)">
                            <i class="bi bi-check-lg"></i> Proposal defense
                        </span>
                        <span class="preset-chip" onclick="applyPreset('FYP Progress Presentation', this)">
                            FYP Progress
                        </span>
                        <span class="preset-chip" onclick="applyPreset('Final Defense Presentation', this)">
                            Final Defense
                        </span>
                        <span class="preset-chip" onclick="applyPreset('Initial Presentation', this)">
                            Initial Presentation
                        </span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Committee Selection -->
                    <div class="col-md-6">
                        <label class="att-form-label" for="committee_select">
                            <i class="bi bi-diagram-2 text-primary"></i> Committee Scope
                        </label>
                        <select id="committee_select" name="committee" class="form-select att-input-box">
                            <option value="all" selected>All Committees (Separate Pages)</option>
                            <?php foreach ($committeesGrouped as $cNum => $members): ?>
                                <option value="<?php echo (int)$cNum; ?>">
                                    Committee <?php echo (int)$cNum; ?> (<?php echo count($members); ?> Evaluators)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1.5 ps-1" style="font-size: 0.76rem;">
                            <i class="bi bi-info-circle me-1"></i>Selecting "All" cleanly splits each committee onto separate A4 sheets.
                        </small>
                    </div>

                    <!-- Academic Batch -->
                    <div class="col-md-6">
                        <label class="att-form-label" for="batch_select">
                            <i class="bi bi-box-seam text-primary"></i> Academic Batch
                        </label>
                        <select id="batch_select" name="batch_id" class="form-select att-input-box">
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
                        <small class="text-muted d-block mt-1.5 ps-1" style="font-size: 0.76rem;">
                            <i class="bi bi-check2-circle me-1"></i>Select specific batch or keep current active cohort.
                        </small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Session / Year -->
                    <div class="col-md-6">
                        <label class="att-form-label" for="session_year_input">
                            <i class="bi bi-calendar-event text-primary"></i> FYP Session / Year
                        </label>
                        <input type="text" id="session_year_input" name="session_year" class="form-control att-input-box" value="<?php echo htmlspecialchars(date('Y'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 2026">
                    </div>

                    <!-- Department & Shift Info -->
                    <div class="col-md-6">
                        <label class="att-form-label">
                            <i class="bi bi-building text-primary"></i> Coordinator Jurisdiction
                        </label>
                        <input type="text" class="form-control att-input-box bg-light" value="<?php echo htmlspecialchars(($department ?? '') . ' (' . ($shift ?? '') . ')', ENT_QUOTES, 'UTF-8'); ?>" readonly style="cursor: not-allowed;">
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top mt-2">
                    <div class="text-muted small d-flex align-items-center gap-2">
                        <i class="bi bi-printer text-primary fs-5"></i>
                        <span>Opens in a print-ready layout with instant browser print trigger.</span>
                    </div>

                    <button type="submit" class="btn btn-generate-print">
                        <i class="bi bi-printer-fill"></i> <span>Generate &amp; Print Attendance Sheet</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function applyPreset(name, chipEl) {
    document.getElementById('presentation_name_input').value = name;
    document.querySelectorAll('.preset-chip').forEach(c => c.classList.remove('active'));
    if (chipEl) {
        chipEl.classList.add('active');
    }
}
</script>
