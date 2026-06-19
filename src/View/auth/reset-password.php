<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - FYP Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <?php
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath === '\\' || $basePath === '/') {
        $basePath = '';
    }
    ?>
    <link href="<?php echo $basePath; ?>/css/style.css" rel="stylesheet">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-theme');
        }
    </script>
    <style>
        body {
            background: var(--bg-color);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .reset-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .btn-primary {
            background-color: #2E5BFF;
            border-color: #2E5BFF;
            box-shadow: 0 4px 12px rgba(46,91,255,0.25);
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #1A40D4;
            border-color: #1A40D4;
        }
    </style>
</head>
<body>

<div class="reset-card shadow-lg">
    <div class="reset-header">
        <h4 class="fw-bold m-0"><i class="bi bi-shield-lock-fill"></i> New Password</h4>
        <p class="text-muted mb-0 mt-2 text-white-50">Create your new secure password</p>
    </div>
    
    <div class="p-4 p-sm-5">
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/reset-password" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="password" name="password" minlength="8" placeholder="At least 8 characters" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="confirm_password" name="confirm_password" minlength="8" placeholder="Retype new password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill mb-3">Save New Password</button>
            
            <div class="text-center small mt-4">
                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/login" class="text-decoration-none fw-semibold"><i class="bi bi-arrow-left"></i> Back to Login</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
