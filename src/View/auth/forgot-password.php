<?php
$pageTitle = 'Forgot Password - FYP Management System';
$headerBtnText = 'Sign Up';
$headerBtnLink = '/register';
include __DIR__ . '/../layout/auth_header.php';
?>

<!-- ─── Login Area ─── -->
<main class="login-page">
    <div class="login-wrapper">
        
        <div class="login-card">
            <div class="login-brand">
                <h2>Recover Password</h2>
                <p>Enter your email to reset password</p>
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

            <form action="<?php echo $basePath; ?>/forgot-password" method="POST" autocomplete="off">
                <div class="input-wrap">
                    <input type="email" id="email" name="email" placeholder=" " required>
                    <label for="email">Email Address</label>
                </div>

                <button type="submit" class="btn-login" style="margin-bottom: 20px;">Send Reset Instructions</button>
                
                <div style="text-align: center;">
                    <a href="<?php echo $basePath; ?>/login" class="forgot-link">Back to Login</a>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</main>

<div class="login-footer">
    &copy; <?php echo date('Y'); ?> University of Sindh &middot; FYP Management Portal
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>
