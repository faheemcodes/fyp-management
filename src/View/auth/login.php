<?php
$pageTitle = 'Login - FYP Management System';
$headerBtnText = 'Sign Up';
$headerBtnLink = '/register';
include __DIR__ . '/../layout/auth_header.php';
?>

<!-- ─── Login Area ─── -->
<main class="login-page">
    <div class="login-wrapper">
        
        <div class="login-card">
            <div class="login-brand">
                <h2>FYP Portal</h2>
                <p>Sign in to manage your projects</p>
            </div>

            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert-login alert-danger" role="alert">
                    <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="alert-login alert-success" role="alert">
                    <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
                </div>
            <?php endif; ?>

            <div id="login-form-view">
            <form action="<?php echo $basePath; ?>/login" method="POST" autocomplete="off">
                
                <div class="input-wrap">
                    <input type="text" id="identifier" name="identifier" placeholder=" " required autofocus>
                    <label for="identifier">Roll No. / CNIC</label>
                </div>
                
                <div class="input-wrap">
                    <input type="password" id="password" name="password" placeholder=" " required style="padding-right: 56px;">
                    <label for="password">Password</label>
                    <button class="pw-toggle" type="button" onclick="const el=document.getElementById('password');el.type=el.type==='password'?'text':'password';this.innerText=el.type==='password'?'Show':'Hide';">Show</button>
                </div>

                <button type="submit" class="btn-login">Log In</button>
                
                <div class="divider">
                    <span>or</span>
                </div>
                
                <a href="javascript:void(0);" class="forgot-link" onclick="document.getElementById('login-form-view').style.display='none'; document.getElementById('forgot-form-view').style.display='block';">Forgot password?</a>
            </form>
            </div>
            
            <!-- Forgot Password View (Hidden by default) -->
            <div id="forgot-form-view" style="display: none;">
                <form action="<?php echo $basePath; ?>/forgot-password" method="POST" autocomplete="off">
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px; text-align: center;">Enter your email to receive a password reset link.</p>
                    <div class="input-wrap">
                        <input type="email" id="reset-email" name="email" placeholder=" " required>
                        <label for="reset-email">Email Address</label>
                    </div>
                    <button type="submit" class="btn-login">Send Reset Link</button>
                    <div style="text-align: center; margin-top: 15px;">
                        <a href="javascript:void(0);" class="forgot-link" onclick="document.getElementById('forgot-form-view').style.display='none'; document.getElementById('login-form-view').style.display='block';">Back to Login</a>
                    </div>
                </form>
            </div>

        </div>

        <div class="register-card">
            Don't have an account? <a href="<?php echo $basePath; ?>/register">Sign up</a>
        </div>
        
    </div>
</main>

<div class="login-footer">
    &copy; 2026 Faculty of Engineering & Technology, University of Sindh. All rights reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>
