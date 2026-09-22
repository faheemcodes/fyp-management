<?php
$pageTitle = 'Login - FYP Management System';
$headerBtnText = 'Sign Up';
$headerBtnLink = '/register';
include __DIR__ . '/../layout/auth_header.php';

$activeRole = $_GET['role'] ?? $_GET['type'] ?? $_SESSION['login_role_preference'] ?? 'student';
if (!in_array($activeRole, ['student', 'faculty'])) {
    $activeRole = 'student';
}
unset($_SESSION['login_role_preference']);
?>

<!-- ─── Login Area ─── -->
<main class="login-page">
    <div class="login-wrapper">
        
        <div class="login-card">
            <!-- Brand -->
            <div class="login-brand">
                <div class="auth-brand-badge">
                    <img src="<?php echo $basePath; ?>/images/logo.png" alt="University Logo" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                    <span class="auth-fallback-icon" style="display:none;"><i class="bi bi-mortarboard-fill"></i></span>
                </div>
                <h2>FYP Portal</h2>
                <p>Sign in to your university account</p>
            </div>

            <!-- Role Segmented Switch -->
            <div class="auth-role-switch" id="role-switch-bar" role="tablist" aria-label="Select Account Type">
                <button type="button" 
                        class="role-switch-btn <?php echo $activeRole === 'student' ? 'active' : ''; ?>" 
                        id="tab-student" 
                        onclick="switchLoginRole('student')" 
                        role="tab" 
                        aria-selected="<?php echo $activeRole === 'student' ? 'true' : 'false'; ?>">
                    <i class="bi bi-mortarboard"></i>
                    <span>Student</span>
                </button>
                <button type="button" 
                        class="role-switch-btn <?php echo $activeRole === 'faculty' ? 'active' : ''; ?>" 
                        id="tab-faculty" 
                        onclick="switchLoginRole('faculty')" 
                        role="tab" 
                        aria-selected="<?php echo $activeRole === 'faculty' ? 'true' : 'false'; ?>">
                    <i class="bi bi-person-badge"></i>
                    <span>Faculty / Staff</span>
                </button>
            </div>

            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert-login-minimal alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                    <span><?php echo htmlspecialchars($_SESSION['flash']['error'] ?? '', ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash']['error']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="alert-login-minimal alert-success" role="alert">
                    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                    <span><?php echo htmlspecialchars($_SESSION['flash']['success'] ?? '', ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash']['success']); ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <div id="login-form-view">
                <form action="<?php echo $basePath; ?>/login" method="POST" autocomplete="off">
                    <input type="hidden" id="login_role" name="login_role" value="<?php echo htmlspecialchars($activeRole, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="form-group-minimal">
                        <label class="form-label-minimal" for="identifier" id="identifier-label">
                            <?php echo $activeRole === 'student' ? 'Roll No.' : 'CNIC'; ?>
                        </label>
                        <div class="input-wrap-minimal">
                            <input type="text" 
                                   id="identifier" 
                                   name="identifier" 
                                   class="input-minimal"
                                   placeholder="<?php echo $activeRole === 'student' ? 'e.g. 2k23/SWE/048' : 'e.g. 41303-1234567-1'; ?>" 
                                   required 
                                   autofocus 
                                   autocomplete="username">
                        </div>
                    </div>
                    
                    <div class="form-group-minimal">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label-minimal mb-0" for="password">Password</label>
                            <a href="javascript:void(0);" class="forgot-link-minimal" onclick="showForgotPassword(true);">Forgot password?</a>
                        </div>
                        <div class="input-wrap-minimal">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="input-minimal has-trailing-btn" 
                                   placeholder="••••••••" 
                                   required 
                                   autocomplete="current-password">
                            <button type="button" 
                                    class="pw-eye-btn" 
                                    onclick="togglePasswordVisibility()" 
                                    title="Toggle password visibility"
                                    aria-label="Toggle password visibility">
                                <i class="bi bi-eye" id="pw-eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login-minimal" id="btn-submit-login">Log In</button>
                
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </form>
            </div>
            
            <!-- Forgot Password View (Hidden by default) -->
            <div id="forgot-form-view" style="display: none">
                <form action="<?php echo $basePath; ?>/forgot-password" method="POST" autocomplete="off">
                    <p style="color: var(--lp-text-muted); font-size: 0.8rem; margin-bottom: 14px; text-align: left; line-height: 1.45;">Enter your registered email address to receive password reset instructions.</p>
                    
                    <div class="form-group-minimal">
                        <label class="form-label-minimal" for="reset-email">Email Address</label>
                        <div class="input-wrap-minimal">
                            <input type="email" 
                                   id="reset-email" 
                                   name="email" 
                                   class="input-minimal" 
                                   placeholder="name@university.edu.pk" 
                                   required 
                                   autocomplete="email">
                        </div>
                    </div>

                    <button type="submit" class="btn-login-minimal">Send Reset Link</button>

                    <div style="text-align: center; margin-top: 14px;">
                        <a href="javascript:void(0);" class="forgot-link-minimal" onclick="showForgotPassword(false);">
                            <i class="bi bi-arrow-left"></i> Back to Login
                        </a>
                    </div>
                
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </form>
            </div>

            <!-- Integrated Minimal Card Footer -->
            <div class="auth-card-footer-minimal">
                Don't have an account? <a href="<?php echo $basePath; ?>/register">Sign up</a>
            </div>

        </div>

        <!-- Minimal Trust Note -->
        <div class="auth-trust-minimal">
            Faculty of Engineering &amp; Technology &bull; University of Sindh
        </div>
        
    </div>
</main>

<div class="login-footer">
    &copy; 2026 Faculty of Engineering & Technology, University of Sindh. All rights reserved.
</div>

<script>
function switchLoginRole(role) {
    const tabStudent = document.getElementById('tab-student');
    const tabFaculty = document.getElementById('tab-faculty');
    const roleInput = document.getElementById('login_role');
    const idLabel = document.getElementById('identifier-label');
    const idInput = document.getElementById('identifier');

    if (role === 'faculty') {
        tabFaculty.classList.add('active');
        tabFaculty.setAttribute('aria-selected', 'true');
        tabStudent.classList.remove('active');
        tabStudent.setAttribute('aria-selected', 'false');
        if (roleInput) roleInput.value = 'faculty';
        if (idLabel) idLabel.textContent = 'CNIC';
        if (idInput) idInput.setAttribute('placeholder', 'e.g. 41303-1234567-1');
    } else {
        tabStudent.classList.add('active');
        tabStudent.setAttribute('aria-selected', 'true');
        tabFaculty.classList.remove('active');
        tabFaculty.setAttribute('aria-selected', 'false');
        if (roleInput) roleInput.value = 'student';
        if (idLabel) idLabel.textContent = 'Roll No.';
        if (idInput) idInput.setAttribute('placeholder', 'e.g. 2k23/SWE/048');
    }

    if (idInput) {
        idInput.focus();
    }

    // Keep URL in sync smoothly without reload
    try {
        const url = new URL(window.location);
        url.searchParams.set('role', role);
        window.history.replaceState({}, '', url);
    } catch(e) {}
}

function togglePasswordVisibility() {
    const pwInput = document.getElementById('password');
    const icon = document.getElementById('pw-eye-icon');
    if (!pwInput) return;
    
    if (pwInput.type === 'password') {
        pwInput.type = 'text';
        if (icon) icon.className = 'bi bi-eye-slash';
    } else {
        pwInput.type = 'password';
        if (icon) icon.className = 'bi bi-eye';
    }
}

function showForgotPassword(show) {
    const switchBar = document.getElementById('role-switch-bar');
    const loginView = document.getElementById('login-form-view');
    const forgotView = document.getElementById('forgot-form-view');

    if (show) {
        if (loginView) loginView.style.display = 'none';
        if (switchBar) switchBar.style.display = 'none';
        if (forgotView) forgotView.style.display = 'block';
        const resetEmail = document.getElementById('reset-email');
        if (resetEmail) resetEmail.focus();
    } else {
        if (forgotView) forgotView.style.display = 'none';
        if (switchBar) switchBar.style.display = 'flex';
        if (loginView) loginView.style.display = 'block';
        const idInput = document.getElementById('identifier');
        if (idInput) idInput.focus();
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>


