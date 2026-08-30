<?php
$title = 'Department Settings';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* Modern Form Group matches profile */
.pf-group {
    position: relative;
}
.pf-group .form-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.pf-group .form-control {
    padding: 10px 14px;
    font-size: 0.85rem;
    border-radius: 10px;
    border: 1px solid var(--border-color);
}


/* Alert matching profile.php */
.profile-alert {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 1px solid rgba(0,0,0,0.04);
}
.profile-alert-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.profile-alert h6 {
    font-size: 0.82rem;
    font-weight: 700;
    margin: 0 0 3px 0;
}
.profile-alert p {
    font-size: 0.78rem;
    margin: 0;
    line-height: 1.5;
    opacity: 0.85;
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.2); color: white;">
                <i class="bi bi-sliders"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Department Settings</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7);font-size: 0.85rem">Configure supervisor limits and system preferences for your department</p>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="profile-alert" style="background: rgba(59,130,246,0.06);color: #2563eb">
    <div class="profile-alert-icon" style="background: rgba(59,130,246,0.1);color: #3b82f6">
        <i class="bi bi-info-circle-fill"></i>
    </div>
    <div>
        <h6>How slot limits work</h6>
        <p>Slot limits are calculated per-shift. Saving will immediately notify all supervisors and students in your department.</p>
    </div>
</div>

<form action="<?php echo $basePath; ?>/hod/settings/update" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    <div class="row">
        <!-- Supervisor Limits (70% on large screens) -->
        <div class="col-lg-8 mb-4">
            <div class="page-section h-100">
                <div class="page-section-header">
                    <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Supervisor Limits</h6>
                        <small class="text-muted">Configure the maximum number of groups per supervisor for each shift</small>
                    </div>
                </div>
                
                <div class="page-section-body">
                    <div class="row g-4">
                        <div class="col-md-6 pf-group">
                            <label class="form-label">Morning Shift Slots <span class="text-danger">*</span></label>
                            <input type="number" name="max_morning_slots" class="form-control" value="<?php echo htmlspecialchars((string)($settings['max_morning_slots'] ?? 5)); ?>" min="1" max="50" required>
                        </div>
                        <div class="col-md-6 pf-group">
                            <label class="form-label">Evening Shift Slots <span class="text-danger">*</span></label>
                            <input type="number" name="max_evening_slots" class="form-control" value="<?php echo htmlspecialchars((string)($settings['max_evening_slots'] ?? 5)); ?>" min="1" max="50" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Group Limits (30% on large screens) -->
        <div class="col-lg-4 mb-4">
            <div class="page-section h-100">
                <div class="page-section-header">
                    <div class="page-section-icon" style="background: rgba(13,148,136,0.1);color: #0d9488">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Student Limits</h6>
                        <small class="text-muted">Max group members allowed</small>
                    </div>
                </div>
                
                <div class="page-section-body">
                    <div class="pf-group">
                        <label class="form-label">Max Group Members <span class="text-danger">*</span></label>
                        <input type="number" name="max_group_members" class="form-control" value="<?php echo htmlspecialchars((string)($settings['max_group_members'] ?? 3)); ?>" min="1" max="10" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-section">
        <div class="d-flex align-items-center justify-content-end p-3">
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="padding: 10px 32px; font-weight: 600; border-radius: 10px;">
                <i class="bi bi-check-circle-fill"></i> Save Settings
            </button>
        </div>
    </div>
    </div>
</form>