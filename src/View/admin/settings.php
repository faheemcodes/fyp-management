<?php
$title = "Department FYP Settings - Admin Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$departments = $departments ?? [];
$selectedDept = $selectedDept ?? 'Software Engineering';
$settings = $settings ?? [
    'department' => $selectedDept,
    'max_morning_slots' => 5,
    'max_evening_slots' => 5,
    'max_group_members' => 3,
    'num_committees' => 2
];
?>

<style>
.dept-nav-pills .nav-link {
    border-radius: 999px;
    padding: 8px 18px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, #e2e8f0);
    background: var(--card-bg, #ffffff);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.dept-nav-pills .nav-link:hover {
    color: #2563eb;
    border-color: #93c5fd;
    background: rgba(59, 130, 246, 0.05);
}
.dept-nav-pills .nav-link.active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.form-control-custom {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #0f172a);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.92rem;
    font-weight: 600;
}
.form-control-custom:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    outline: none;
}
.settings-box {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.06));
    border-radius: 18px;
    box-shadow: var(--card-shadow, 0 4px 15px rgba(0,0,0,0.03));
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-gear-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Department Settings &amp; Governance</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Configure supervisor capacity limits, group member sizes, and evaluation committee structures per department
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <a href="<?php echo $basePath; ?>/admin/committees" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-diagram-3-fill text-primary"></i> <span>Committees Allocation</span>
            </a>
            <a href="<?php echo $basePath; ?>/admin/slots" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-speedometer2 text-info"></i> <span>Supervisor Slots</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ Department Selector Nav ═══════════════ -->
<div class="d-flex align-items-center gap-2 overflow-auto pb-2 mb-4 dept-nav-pills">
    <?php foreach ($departments as $dept): 
        $isActive = ($selectedDept === $dept);
    ?>
        <a href="<?php echo $basePath; ?>/admin/settings?department=<?php echo urlencode($dept); ?>" class="nav-link <?php echo $isActive ? 'active' : ''; ?>">
            <i class="bi bi-building me-1"></i> <?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- ═══════════════ Main Settings Form ═══════════════ -->
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="settings-box p-4 p-md-5 h-100">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="bi bi-sliders fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-dark">Policy Rules: <?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?></h5>
                        <small class="text-muted">Changes take effect immediately across student, supervisor, and committee portals</small>
                    </div>
                </div>
                <span class="badge rounded-pill bg-primary-subtle text-primary fw-bold px-3 py-1.5" style="font-size: 0.78rem;">
                    Active Department
                </span>
            </div>

            <form action="<?php echo $basePath; ?>/admin/settings/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="department" value="<?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?>">

                <!-- 1. Supervisor Slot Capacity Limits -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-person-badge-fill text-primary"></i>
                        <h6 class="fw-bold m-0 text-dark">1. Supervisor Capacity Quotas (Groups per Faculty)</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-secondary">
                                Morning Shift Maximum Slots <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="max_morning_slots" class="form-control form-control-custom" value="<?php echo (int)($settings['max_morning_slots'] ?? 5); ?>" min="1" max="50" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Cap for Morning student groups per supervisor.</small>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-secondary">
                                Evening Shift Maximum Slots <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="max_evening_slots" class="form-control form-control-custom" value="<?php echo (int)($settings['max_evening_slots'] ?? 5); ?>" min="1" max="50" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Cap for Evening student groups per supervisor.</small>
                        </div>
                    </div>
                </div>

                <!-- 2. Student Group Limit -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-people-fill text-info"></i>
                        <h6 class="fw-bold m-0 text-dark">2. FYP Group Formation Policy</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-secondary">
                                Maximum Students per Group <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="max_group_members" class="form-control form-control-custom" value="<?php echo (int)($settings['max_group_members'] ?? 3); ?>" min="1" max="10" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Prevents students from adding more members during registration.</small>
                        </div>
                    </div>
                </div>

                <!-- 3. Committees Structure -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-diagram-3-fill text-success"></i>
                        <h6 class="fw-bold m-0 text-dark">3. Evaluation Committees Architecture</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-secondary">
                                Number of Evaluation Committees <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="num_committees" class="form-control form-control-custom" value="<?php echo (int)($settings['num_committees'] ?? 2); ?>" min="1" max="10" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Determines automatic committee division in this department.</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> <span>Save <?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?> Settings</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side Information & Impact Panel -->
    <div class="col-12 col-lg-4">
        <div class="settings-box p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-info-circle-fill text-primary me-2"></i>Policy Enforcement Scope
            </h6>
            <p class="small text-secondary" style="line-height: 1.6;">
                These parameters are actively queried during runtime across all portals:
            </p>
            <ul class="list-unstyled small text-secondary m-0">
                <li class="d-flex align-items-start gap-2 mb-2.5">
                    <i class="bi bi-check-lg text-success mt-0.5"></i>
                    <span><strong>Student Portal:</strong> Enforces member limits when students create or invite teammates.</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2.5">
                    <i class="bi bi-check-lg text-success mt-0.5"></i>
                    <span><strong>Supervisor Portal:</strong> Rejects proposal supervisor requests once the quota is exhausted.</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2.5">
                    <i class="bi bi-check-lg text-success mt-0.5"></i>
                    <span><strong>Coordinator Portal:</strong> Configures automated round-robin committee assignment logic.</span>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-lg text-success mt-0.5"></i>
                    <span><strong>HOD Portal:</strong> Synchronized directly with departmental oversight metrics.</span>
                </li>
            </ul>
        </div>

        <div class="settings-box p-4" style="background: rgba(59, 130, 246, 0.04); border: 1px solid rgba(59, 130, 246, 0.15);">
            <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-bold">
                <i class="bi bi-shield-lock-fill"></i> Super Admin Authority
            </div>
            <p class="small text-secondary m-0" style="line-height: 1.6;">
                As Super Admin, any changes you make here supersede individual departmental coordinator and HOD configurations.
            </p>
        </div>
    </div>
</div>
