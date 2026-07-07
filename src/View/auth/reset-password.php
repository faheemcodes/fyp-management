<?php
$pageTitle = 'Reset Password - FYP Management System';
$headerBtnText = 'Sign Up';
$headerBtnLink = '/register';
include __DIR__ . '/../layout/auth_header.php';
?>

<!-- ─── Login Area ─── -->
<main class="login-page">
    <div class="login-wrapper">
        
        <div class="login-card">
            <div class="login-brand">
                <h2>New Password</h2>
                <p>Create your new secure password</p>
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

            <form action="<?php echo $basePath; ?>/reset-password" method="POST" autocomplete="off">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="input-wrap">
                    <input type="password" id="password" name="password" placeholder=" " minlength="8" required style="padding-right: 56px;">
                    <label for="password">New Password</label>
                    <button class="pw-toggle" type="button" onclick="const el=document.getElementById('password');el.type=el.type==='password'?'text':'password';this.innerText=el.type==='password'?'Show':'Hide';">Show</button>
                </div>
                
                <div class="input-wrap">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder=" " minlength="8" required style="padding-right: 56px;">
                    <label for="confirm_password">Confirm Password</label>
                    <button class="pw-toggle" type="button" onclick="const el=document.getElementById('confirm_password');el.type=el.type==='password'?'text':'password';this.innerText=el.type==='password'?'Show':'Hide';">Show</button>
                </div>

                <button type="submit" class="btn-login" style="margin-bottom: 20px;">Save New Password</button>
                
                <div style="text-align: center;">
                    <a href="<?php echo $basePath; ?>/login" class="forgot-link">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</main>

<div class="login-footer">
    &copy; <?php echo date('Y'); ?> University of Sindh &middot; FYP Management Portal
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>
