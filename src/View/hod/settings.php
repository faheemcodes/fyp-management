<?php
$title = 'Department Settings';
$basePath = dirname($_SERVER['SCRIPT_NAME']) :== '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-dark fw-bold" style="letter-spacing: -0.02em;">Department Settings</h4>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Configure limits and preferences for your department</p>
    </div>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SERVER['flash']['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 p-4 shadow-sm" style="border-radius: 16px;">
    <div class="page-section-header mb-4">
        <div class="page-section-icon" style="background: rgba(139, 92, 246, 0.1);color: #8b5cf6">
            <i class="bi bi-sliders"></i>
        </div>
        <div>
            <h6 class="mb-0 fw-bold">Supervisor Limits</h6>
            <small class="text-muted">Configure how many project groups a supervisor can handle</small>
        </div>
    </div>
    
    <form action="<?php echo $basePath; ?>/hod/settings/update" method="POST">
        <div class="row align-items-end">
            <div class="col-md-5">
                <label class="form-label text-secondary fw5semibold">Max Supervisor Slots (per shift)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-people text-muted"></i></span>
                    <input type="number" name="max_supervisor_slots" class="form-control" value="<?php echo htmlspecialchars((string)($settings['max_supervisor_slots'] ?? 5)); ?>" min="1" max="50" required>
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
                    The limit is calculated <strong>per-shift</strong>. For example, if you set this to 5, a supervisor can be assigned up to 5 Morning shift projects AND 5 Evening shift projects (10 total).<br><br>
                    Saving this setting will immediately notify all supervisors and students in your department.
                </p>
            </div>
        </div>
    </form>
</div>