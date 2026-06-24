<!-- User Change Password Form View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* ─── Premium Change Password Styles ─── */
.cp-info-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
    border-radius: 1.5rem;
    box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.4);
    position: relative;
    overflow: hidden;
    height: 100%;
}
.cp-info-card::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -20%;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(59,130,246,0.3) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.cp-info-card::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: -10%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(16,185,129,0.2) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.cp-icon-box {
    width: 64px;
    height: 64px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
.cp-checklist-box {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 1rem;
    padding: 1.25rem;
}
.cp-checklist li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0.5rem;
    color: rgba(255,255,255,0.8);
    font-size: 0.85rem;
}
.cp-checklist li i {
    color: #34d399;
    font-size: 1.1rem;
    filter: drop-shadow(0 0 4px rgba(52,211,153,0.4));
}

/* ─── Form Elements ─── */
.cp-form-card {
    background: var(--card-bg);
    border-radius: 1.5rem;
    box-shadow: var(--card-shadow);
    padding: 2.5rem;
    height: 100%;
}
.password-group {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.password-group:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
    transform: translateY(-1px);
}
.password-group .input-group-text,
.password-group .btn {
    border: none;
    background: transparent;
    color: var(--text-secondary);
}
.password-group .form-control {
    border: none;
    background: transparent;
    font-size: 0.95rem;
    color: var(--text-primary);
}
.password-group .form-control:focus {
    box-shadow: none;
}
.password-group .btn:hover {
    color: #3b82f6;
}
.cp-submit-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border: none;
    border-radius: 99px;
    font-weight: 600;
    color: #fff;
    box-shadow: 0 8px 20px -6px rgba(59,130,246,0.5);
    transition: all 0.3s ease;
}
.cp-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -6px rgba(59,130,246,0.6);
    color: #fff;
}
</style>

<div class="row g-4 justify-content-center">
    <!-- Left: Security Info Column -->
    <div class="col-lg-4 col-md-5">
        <div class="cp-info-card p-4 p-xl-5">
            <div class="cp-icon-box">
                <i class="bi bi-shield-check"></i>
            </div>
            
            <h4 class="fw-bold mb-3 text-white" style="letter-spacing: -0.02em;">Secure Your Account</h4>
            <p style="font-size: 0.9rem; color: rgba(255,255,255,0.75); line-height: 1.6; margin-bottom: 2rem;">
                Regularly updating your password ensures your FYP portal data and personal information remain fully protected against unauthorized access.
            </p>
            
            <div class="cp-checklist-box">
                <h6 class="fw-bold mb-3 text-white" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    Password Requirements
                </h6>
                <ul class="list-unstyled m-0 cp-checklist">
                    <li><i class="bi bi-check-circle-fill"></i> 8 to 50 characters long</li>
                    <li><i class="bi bi-check-circle-fill"></i> 1 Uppercase & 1 Lowercase</li>
                    <li><i class="bi bi-check-circle-fill"></i> 1 Number (0-9)</li>
                    <li><i class="bi bi-check-circle-fill"></i> 1 Special Character (!@#...)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right: Form Column -->
    <div class="col-lg-7 col-md-7">
        <div class="cp-form-card">
            <div class="d-flex align-items-center gap-3 border-bottom pb-4 mb-4">
                <div style="width: 48px; height: 48px; background: rgba(59,130,246,0.1); color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="bi bi-key-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold m-0" style="color: var(--text-primary); letter-spacing: -0.01em;">Change Password</h4>
                    <p class="m-0 mt-1" style="color: var(--text-secondary); font-size: 0.85rem;">Update your credentials to maintain account security</p>
                </div>
            </div>

            <form action="<?php echo $basePath; ?>/change-password" method="POST">
                
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="form-label small fw-bold text-uppercase" style="color: var(--text-secondary); letter-spacing: 0.05em;">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group password-group py-1 px-2">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="Enter current password" autocomplete="off">
                        <button class="btn" type="button" onclick="togglePassword('current_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="new_password" class="form-label small fw-bold text-uppercase" style="color: var(--text-secondary); letter-spacing: 0.05em;">New Password <span class="text-danger">*</span></label>
                    <div class="input-group password-group py-1 px-2">
                        <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                        <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Min 8, Max 50 characters" minlength="8" maxlength="50" autocomplete="new-password">
                        <button class="btn" type="button" onclick="togglePassword('new_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-5">
                    <label for="confirm_password" class="form-label small fw-bold text-uppercase" style="color: var(--text-secondary); letter-spacing: 0.05em;">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group password-group py-1 px-2">
                        <span class="input-group-text"><i class="bi bi-shield-check-fill"></i></span>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Verify new password" minlength="8" maxlength="50" autocomplete="new-password">
                        <button class="btn" type="button" onclick="togglePassword('confirm_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end border-top pt-4">
                    <a href="<?php echo $basePath; ?>/<?php echo htmlspecialchars($_SESSION['role'] ?? 'login'); ?>/dashboard" class="btn btn-light rounded-pill px-4 py-2 fw-bold order-2 order-sm-1" style="color: var(--text-secondary); border: 1px solid var(--border-color);">
                        Cancel
                    </a>
                    <button type="submit" class="btn cp-submit-btn px-4 py-2 order-1 order-sm-2">
                        <i class="bi bi-check2-circle me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
