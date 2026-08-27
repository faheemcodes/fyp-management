<?php
$title = 'Department Settings';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

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

<div class="page-section p-4 border-0">
    <div class="page-section-header mb-4 border-bottom pb-3">
        <div class="page-section-icon" style="background: rgba(139, 92, 246, 0.1);color: #8b5cf6">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div>
            <h6 class="mb-0 fw-bold">Supervisor Limits</h6>
            <small class="text-muted">Configure how many project groups a supervisor can handle</small>
        </div>
    </div>
    
    <form action="<?php echo $basePath; ?>/hod/settings/update" method="POST">
        <div class="row align-items-end mb-3">
            <div class="col-md-4">
                <label class="form-label text-secondary fw-semibold">Morning Shift Slots</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-brightness-high text-warning"></i></span>
                    <input type="number" name="max_morning_slots" class="form-control" value="<?php echo htmlspecialchars((string)($settings['max_morning_slots'] ?? 5)); ?>" min="1" max="50" required>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <label class="form-label text-secondary fw-semibold">Evening Shift Slots</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-moon-stars text-primary"></i></span>
                    <input type="number" name="max_evening_slots" class="form-control" value="<?php echo htmlspecialchars((string)($settings['max_evening_slots'] ?? 5)); ?>" min="1" max="50" required>
                </div>
            </div>
            <div class="col-md-3 mt-4 mt-md-0">
                <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; padding: 10px 20px;"><i class="bi bi-save me-2"></i>Save Settings</button>
            </div>
        </div>
        
        <div class="mt-3 p-3 rounded" style="background-color: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.1);">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                <p class="mb-0 text-muted" style="font-size: 0.85rem; line-height: 1.5;">
                    <strong>Note:</strong> This limit applies to all supervisors in your department. 
                    The limits are calculated independently per-shift. For example, if you set Morning to 5 and Evening to 3, a supervisor can handle up to 5 Morning shift projects AND 3 Evening shift projects (8 total).<br><br>
                    Saving this setting will immediately notify all supervisors and students in your department.
                </p>
            </div>
        </div>
    </form>
</div>