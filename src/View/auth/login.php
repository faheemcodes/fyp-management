<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FYP Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') {
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
            font-family: 'Outfit', sans-serif;
            color: var(--text-primary);
        }
        .login-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
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
        .nav-pills .nav-link {
            color: var(--text-secondary);
        }
        .nav-pills .nav-link.active {
            background-color: #2E5BFF;
            color: #fff;
        }
    </style>
</head>
<body>
 
<div class="login-card shadow-lg">
    <div class="login-header">
        <h3 class="fw-bold m-0"><i class="bi bi-mortarboard-fill text-primary"></i> University of Sindh</h3>
        <small class="text-uppercase text-white-50 d-block mt-1 fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Faculty of Engineering and Technology</small>
        <p class="text-muted mb-0 mt-3 text-white-50 small">Final Year Project Portal Login</p>
    </div>
    
    <div class="p-4 p-sm-5">
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show small rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show small rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Role Login Selection Tabs -->
        <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-3" id="loginTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-2 small fw-semibold py-2" id="student-tab" data-bs-toggle="pill" type="button" role="tab" onclick="switchLoginType('student')">
                    <i class="bi bi-person-fill me-1"></i> Student
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-2 small fw-semibold py-2" id="staff-tab" data-bs-toggle="pill" type="button" role="tab" onclick="switchLoginType('staff')">
                    <i class="bi bi-person-badge-fill me-1"></i> Staff / Faculty
                </button>
            </li>
        </ul>

        <form action="<?php echo $basePath; ?>/login" method="POST">
            <!-- Hidden Login Type indicator -->
            <input type="hidden" name="login_type" id="login_type" value="student">

            <!-- Dynamic Input Container -->
            <div class="mb-3">
                <label for="identifier-input" class="form-label small fw-semibold text-secondary" id="identifier-label">Roll Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i id="identifier-icon" class="bi bi-card-text text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" id="identifier-input" name="student_id" placeholder="e.g. 2k23/SWE/001" required>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <a href="<?php echo $basePath; ?>/forgot-password" class="small text-decoration-none">Forgot?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill mb-3">Sign In</button>
            
            <div class="text-center small mt-4">
                <span class="text-muted">Don't have an account?</span> 
                <a href="<?php echo $basePath; ?>/register" class="text-decoration-none fw-semibold">Register Now</a>
            </div>
        </form>
    </div>
</div>

<script>
function switchLoginType(type) {
    document.getElementById('login_type').value = type;
    const identifierLabel = document.getElementById('identifier-label');
    const identifierInput = document.getElementById('identifier-input');
    const identifierIcon = document.getElementById('identifier-icon');
    
    if (type === 'student') {
        identifierLabel.innerText = 'Roll Number';
        identifierInput.placeholder = 'e.g. 2k23/SWE/001';
        identifierInput.name = 'student_id';
        identifierIcon.className = 'bi bi-card-text text-muted';
    } else {
        identifierLabel.innerText = 'CNIC No. (without dashes)';
        identifierInput.placeholder = 'e.g. 3520112345671';
        identifierInput.name = 'cnic';
        identifierIcon.className = 'bi bi-credit-card-2-front text-muted';
    }
    identifierInput.value = '';
    identifierInput.focus();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
