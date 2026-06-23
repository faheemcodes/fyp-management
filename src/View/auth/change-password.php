<!-- User Change Password Form View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<div class="row g-4 justify-content-center">
    <!-- Left: Security Info Column -->
    <div class="col-lg-4 col-md-5">
        <div class="card border-0 h-100 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <!-- Decorative background elements -->
            <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(59,130,246,0.2) 0%, transparent 70%); z-index: 0;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%); z-index: 0;"></div>
            
            <div class="card-body p-4 p-xl-5 text-white position-relative" style="z-index: 1;">
                <div class="mb-4" style="width: 56px; height: 56px; background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #60a5fa;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                
                <h4 class="fw-bold mb-3" style="letter-spacing: -0.02em;">Secure Your Account</h4>
                <p style="font-size: 0.88rem; color: rgba(255,255,255,0.7); line-height: 1.6; text-align: justify; margin-bottom: 2rem;">
                    Regularly updating your password ensures your FYP portal data and personal information remain fully protected against unauthorized access.
                </p>
                
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                    <h6 class="fw-semibold mb-3" style="color: #f8fafc; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <i class="bi bi-check2-circle text-success me-1"></i> Password Checklist
                    </h6>
                    <ul class="list-unstyled m-0" style="font-size: 0.78rem; color: rgba(255,255,255,0.65); line-height: 1.8;">
                        <li><i class="bi bi-dot" style="color: #3b82f6;"></i> 8 to 50 characters length</li>
                        <li><i class="bi bi-dot" style="color: #3b82f6;"></i> 1 Uppercase & 1 Lowercase</li>
                        <li><i class="bi bi-dot" style="color: #3b82f6;"></i> 1 Number (0-9)</li>
                        <li><i class="bi bi-dot" style="color: #3b82f6;"></i> 1 Special Char (!@#...)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Form Column -->
    <div class="col-lg-7 col-md-7">
        <div class="card border-0 p-4 p-xl-5 h-100" style="border-radius: 16px; background: var(--card-bg); box-shadow: var(--card-shadow);">
            <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-4">
                <div style="width: 42px; height: 42px; background: rgba(59,130,246,0.1); color: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="bi bi-key-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0" style="color: var(--text-primary); letter-spacing: -0.01em;">Change Password</h5>
                    <small style="color: var(--text-secondary); font-size: 0.8rem;">Update your current credentials</small>
                </div>
            </div>

            <form action="<?php echo $basePath; ?>/change-password" method="POST">
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="form-label small fw-semibold" style="color: var(--text-secondary);">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-secondary); border-right: none;"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" style="background: var(--form-bg); border-color: var(--border-color); border-left: none; border-right: none;" id="current_password" name="current_password" required placeholder="Enter current password">
                        <button class="btn btn-outline-secondary" type="button" onclick="const el = document.getElementById('current_password'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye text-primary' : 'bi bi-eye-slash';" style="border-color: var(--border-color); background: var(--form-bg); border-left: none;">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="new_password" class="form-label small fw-semibold" style="color: var(--text-secondary);">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-secondary); border-right: none;"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" class="form-control" style="background: var(--form-bg); border-color: var(--border-color); border-left: none; border-right: none;" id="new_password" name="new_password" required placeholder="Min 8, Max 50 characters" minlength="8" maxlength="50">
                        <button class="btn btn-outline-secondary" type="button" onclick="const el = document.getElementById('new_password'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye text-primary' : 'bi bi-eye-slash';" style="border-color: var(--border-color); background: var(--form-bg); border-left: none;">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-5">
                    <label for="confirm_password" class="form-label small fw-semibold" style="color: var(--text-secondary);">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--form-bg); border-color: var(--border-color); color: var(--text-secondary); border-right: none;"><i class="bi bi-shield-check"></i></span>
                        <input type="password" class="form-control" style="background: var(--form-bg); border-color: var(--border-color); border-left: none; border-right: none;" id="confirm_password" name="confirm_password" required placeholder="Verify new password" minlength="8" maxlength="50">
                        <button class="btn btn-outline-secondary" type="button" onclick="const el = document.getElementById('confirm_password'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye text-primary' : 'bi bi-eye-slash';" style="border-color: var(--border-color); background: var(--form-bg); border-left: none;">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end border-top pt-4" style="border-color: var(--border-color) !important;">
                    <a href="<?php echo $basePath; ?>/<?php echo htmlspecialchars($_SESSION['role'] ?? 'login'); ?>/dashboard" class="btn btn-light rounded-pill px-4 py-2 fw-semibold order-2 order-sm-1" style="font-size: 0.85rem; color: var(--text-secondary); border: 1px solid var(--border-color);">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold order-1 order-sm-2" style="font-size: 0.85rem; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none; box-shadow: 0 4px 12px rgba(59,130,246,0.25);">
                        <i class="bi bi-check2-circle me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
