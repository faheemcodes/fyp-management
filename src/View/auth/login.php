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
            <div class="login-brand">
                <h2>FYP Portal</h2>
                <p>Sign in to manage your projects</p>
            </div>

            <!-- Role Segmented Control -->
            <div class="auth-role-switch" id="role-switch-bar" role="tablist" aria-label="Select Account Type">
                <button type="button" 
                        class="role-switch-btn <?php echo $activeRole === 'student' ? 'active' : ''; ?>" 
                        id="tab-student" 
                        onclick="switchLoginRole('student')" 
                        role="tab" 
                        aria-selected="<?php echo $activeRole === 'student' ? 'true' : 'false'; ?>">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Student</span>
                </button>
                <button type="button" 
                        class="role-switch-btn <?php echo $activeRole === 'faculty' ? 'active' : ''; ?>" 
                        id="tab-faculty" 
                        onclick="switchLoginRole('faculty')" 
                        role="tab" 
                        aria-selected="<?php echo $activeRole === 'faculty' ? 'true' : 'false'; ?>">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Faculty / Staff</span>
                </button>
            </div>

            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert-login alert-danger" role="alert">
                    <?php echo htmlspecialchars($_SESSION['flash']['error'] ?? '', ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash']['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="alert-login alert-success" role="alert">
                    <?php echo htmlspecialchars($_SESSION['flash']['success'] ?? '', ENT_QUOTES, 'UTF-8'); unset($_SESSION['flash']['success']); ?>
                </div>
            <?php endif; ?>

            <div id="login-form-view">
            <form action="<?php echo $basePath; ?>/login" method="POST" autocomplete="off">
                <input type="hidden" id="login_role" name="login_role" value="<?php echo htmlspecialchars($activeRole, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="input-wrap">
                    <input type="text" id="identifier" name="identifier" placeholder=" " required autofocus autocomplete="username">
                    <label for="identifier" id="identifier-label"><?php echo $activeRole === 'student' ? 'Roll No.' : 'CNIC'; ?></label>
                </div>
                
                <div class="input-wrap">
                    <input type="password" id="password" name="password" placeholder=" " required style="padding-right: 56px" autocomplete="current-password">
                    <label for="password">Password</label>
                    <button class="pw-toggle" type="button" onclick="const el=document.getElementById('password');el.type=el.type==='password'?'text':'password';this.innerText=el.type==='password'?'Show':'Hide';">Show</button>
                </div>

                <button type="submit" class="btn-login" id="btn-submit-login">Log In</button>
                
                <div class="divider">
                    <span>or</span>
                </div>
                
                <a href="javascript:void(0);" class="forgot-link" onclick="showForgotPassword(true);">Forgot password?</a>
            
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </form>
            </div>
            
            <!-- Forgot Password View (Hidden by default) -->
            <div id="forgot-form-view" style="display: none">
                <form action="<?php echo $basePath; ?>/forgot-password" method="POST" autocomplete="off">
                    <p style="color: var(--lp-text-muted);font-size: 0.9rem;margin-bottom: 20px;text-align: center">Enter your email to receive a password reset link.</p>
                    <div class="input-wrap">
                        <input type="email" id="reset-email" name="email" placeholder=" " required>
                        <label for="reset-email">Email Address</label>
                    </div>
                    <button type="submit" class="btn-login">Send Reset Link</button>
                    <div style="text-align: center;margin-top: 15px">
                        <a href="javascript:void(0);" class="forgot-link" onclick="showForgotPassword(false);">Back to Login</a>
                    </div>
                
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </form>
            </div>

        </div>

        <div class="login-card-bottom">
            Don't have an account? <a href="<?php echo $basePath; ?>/register">Sign up</a>
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
    } else {
        tabStudent.classList.add('active');
        tabStudent.setAttribute('aria-selected', 'true');
        tabFaculty.classList.remove('active');
        tabFaculty.setAttribute('aria-selected', 'false');
        if (roleInput) roleInput.value = 'student';
        if (idLabel) idLabel.textContent = 'Roll No.';
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

function showForgotPassword(show) {
    const switchBar = document.getElementById('role-switch-bar');
    const loginView = document.getElementById('login-form-view');
    const forgotView = document.getElementById('forgot-form-view');

    if (show) {
        if (loginView) loginView.style.display = 'none';
        if (switchBar) switchBar.style.display = 'none';
        if (forgotView) forgotView.style.display = 'block';
    } else {
        if (forgotView) forgotView.style.display = 'none';
        if (switchBar) switchBar.style.display = 'flex';
        if (loginView) loginView.style.display = 'block';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>

