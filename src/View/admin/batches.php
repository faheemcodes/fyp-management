<?php
$title = "Academic Batches & Cohorts - Admin Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batches = $batches ?? [];
$departments = $departments ?? [];

$totalBatches = count($batches);
$activeBatchesCount = 0;
$regOpenCount = 0;
$totalCohortGroups = 0;

foreach ($batches as $b) {
    if (!empty($b['is_active'])) $activeBatchesCount++;
    if (!empty($b['is_registration_open'])) $regOpenCount++;
    $totalCohortGroups += (int)($b['groups_count'] ?? 0);
}
?>

<style>
.batch-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.06));
    border-radius: 16px;
    box-shadow: var(--card-shadow, 0 4px 15px rgba(0,0,0,0.03));
}
.form-control-custom {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #0f172a);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.9rem;
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
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Academic Batches &amp; Cohorts</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Manage student cohort academic cycles, toggle active batch states, and open or close project registration windows
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <button type="button" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#createBatchModal">
                <i class="bi bi-plus-circle-fill text-primary"></i> <span>Create New Batch</span>
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════ Metric Summary Cards ═══════════════ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                    <i class="bi bi-collection-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Total Batches</div>
                    <h5 class="fw-bold m-0 text-dark"><?php echo (int)$totalBatches; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Active Batches</div>
                    <h5 class="fw-bold m-0 text-success"><?php echo (int)$activeBatchesCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="bi bi-door-open-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Registration Open</div>
                    <h5 class="fw-bold m-0 text-warning"><?php echo (int)$regOpenCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Enrolled Groups</div>
                    <h5 class="fw-bold m-0 text-primary"><?php echo (int)$totalCohortGroups; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Batches Table Card ═══════════════ -->
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-header border-bottom p-3 p-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--form-bg);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-mortarboard text-primary"></i>
            <h6 class="fw-bold m-0 text-dark">University Academic Batches</h6>
            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2.5 py-1" style="font-size: 0.75rem;">
                <?php echo count($batches); ?> batches
            </span>
        </div>
        <div class="position-relative" style="width: 240px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.8rem;"></i>
            <input type="text" id="batchSearch" class="form-control form-control-custom ps-5 py-1" style="font-size: 0.82rem;" placeholder="Search batches...">
        </div>
    </div>

    <div class="card-body p-0">
        <?php if (empty($batches)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary opacity-50"></i>
                <h6 class="fw-bold text-dark">No Academic Batches Configured</h6>
                <p class="small text-secondary mb-0">Create your first academic batch using the button above.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="batchesTable">
                    <thead>
                        <tr style="background: var(--form-bg);">
                            <th class="ps-4" style="font-size: 0.75rem; text-transform: uppercase;">Batch Name</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Department</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Shift</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Groups Enrolled</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Active Cycle</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Registration Window</th>
                            <th class="text-end pe-4" style="font-size: 0.75rem; text-transform: uppercase;">Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($batches as $b): 
                            $isActive = !empty($b['is_active']);
                            $isRegOpen = !empty($b['is_registration_open']);
                        ?>
                            <tr class="batch-row">
                                <td class="ps-4 text-nowrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                            <?php echo htmlspecialchars($b['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <?php if ($isActive): ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success px-2 py-0.5" style="font-size: 0.7rem;">
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2 py-0.5" style="font-size: 0.7rem;">
                                                Archived
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($b['department'] ?? 'All Departments', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?php echo ($b['shift'] === 'Morning') ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info-emphasis'; ?> px-2.5 py-1" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($b['shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-secondary-subtle text-dark px-2.5 py-1 fw-bold" style="font-size: 0.8rem;">
                                        <i class="bi bi-people me-1 text-primary"></i> <?php echo (int)($b['groups_count'] ?? 0); ?> Groups
                                    </span>
                                </td>
                                <td>
                                    <form action="<?php echo $basePath; ?>/admin/batches/toggle" method="POST" class="d-inline-block m-0">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
                                        <input type="hidden" name="field" value="is_active">
                                        <button type="submit" class="btn btn-sm rounded-pill px-3 py-1 fw-bold <?php echo $isActive ? 'btn-success' : 'btn-outline-secondary'; ?>" style="font-size: 0.78rem;" title="Click to toggle active batch status">
                                            <?php echo $isActive ? '<i class="bi bi-toggle2-on me-1"></i> Active' : '<i class="bi bi-toggle2-off me-1"></i> Inactive'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo $basePath; ?>/admin/batches/toggle" method="POST" class="d-inline-block m-0">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
                                        <input type="hidden" name="field" value="is_registration_open">
                                        <button type="submit" class="btn btn-sm rounded-pill px-3 py-1 fw-bold <?php echo $isRegOpen ? 'btn-warning text-dark' : 'btn-outline-secondary'; ?>" style="font-size: 0.78rem;" title="Click to open/close student registration window">
                                            <?php echo $isRegOpen ? '<i class="bi bi-unlock-fill me-1"></i> Open' : '<i class="bi bi-lock-fill me-1"></i> Closed'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="<?php echo $basePath; ?>/admin/groups?batch_id=<?php echo (int)$b['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.78rem;">
                                        <i class="bi bi-eye me-1"></i> View Groups
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════════ Create Batch Modal ═══════════════ -->
<div class="modal fade" id="createBatchModal" tabindex="-1" aria-labelledby="createBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 18px;">
            <form action="<?php echo $basePath; ?>/admin/batches/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="modal-header border-bottom p-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-circle bg-primary-subtle text-primary">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <h6 class="modal-title fw-bold text-dark" id="createBatchModalLabel">Create Academic Batch</h6>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Batch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="e.g. 2023-2027 or 2024" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Official cohort identifier used across portals.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Department <span class="text-danger">*</span></label>
                        <select name="department" class="form-select form-control-custom" required>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Shift <span class="text-danger">*</span></label>
                        <select name="shift" class="form-select form-control-custom" required>
                            <option value="Morning" selected>Morning</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>

                    <div class="card p-3 border rounded-3 mt-3 bg-light">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active_input" name="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold text-dark small" for="is_active_input">
                                Set as Active Academic Batch
                            </label>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_reg_input" name="is_registration_open" value="1" checked>
                            <label class="form-check-label fw-semibold text-dark small" for="is_reg_input">
                                Open Registration Window for Students
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top p-3 px-4">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-check2 me-1"></i> Save Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Client-side quick search
document.getElementById('batchSearch')?.addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#batchesTable tbody tr.batch-row').forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
