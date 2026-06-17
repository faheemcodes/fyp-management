<?php
$role = $_SESSION['role'] ?? '';
$name = $_SESSION['name'] ?? 'User';
$email = $_SESSION['email'] ?? '';
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = dirname($scriptName);
if ($baseDir !== '/' && $baseDir !== '\\' && strpos($currentUri, $baseDir) === 0) {
    $currentUri = substr($currentUri, strlen($baseDir));
}
$currentUri = '/' . ltrim($currentUri, '/');

// Helper to check active link
function isActive($uri, $currentUri) {
    return $currentUri === $uri ? 'active' : '';
}

// Helper to prefix base path
$urlPrefix = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($urlPrefix === '/') {
    $urlPrefix = '';
}
?>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header text-center">
        <h5 class="m-0 text-white fw-bold"><i class="bi bi-mortarboard-fill text-primary"></i> University of Sindh</h5>
        <small class="text-muted text-uppercase" style="font-size: 0.65rem; display: block; margin-top: 4px;">Faculty of Engineering & Tech</small>
    </div>

    <div class="p-3 text-center border-bottom border-secondary border-opacity-10">
        <?php if ($role === 'student'): ?>
            <?php $avatarFile = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'default_avatar.svg'; ?>
            <img src="<?php echo $urlPrefix; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-2 border-primary mb-2" style="width: 48px; height: 48px; object-fit: cover;" alt="Profile photo">
        <?php else: ?>
            <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.35rem; font-weight: 600;">
                <?php echo strtoupper(substr($name, 0, 1)); ?>
            </div>
        <?php endif; ?>
        <div class="text-white fw-semibold small text-truncate" style="max-width: 220px;"><?php echo htmlspecialchars($name); ?></div>
        <div class="badge bg-secondary text-white text-uppercase" style="font-size: 0.65rem;"><?php echo htmlspecialchars($role); ?></div>
    </div>

    <ul class="list-unstyled nav flex-column mt-3">
        <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/admin/dashboard" class="nav-link <?php echo isActive('/admin/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/admin/users" class="nav-link <?php echo isActive('/admin/users', $currentUri); ?>">
                    <i class="bi bi-people-fill"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/admin/groups" class="nav-link <?php echo isActive('/admin/groups', $currentUri); ?>">
                    <i class="bi bi-folder-fill"></i> FYP Groups
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/admin/deadlines" class="nav-link <?php echo isActive('/admin/deadlines', $currentUri); ?>">
                    <i class="bi bi-calendar2-event-fill"></i> Deadlines
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/admin/reports" class="nav-link <?php echo isActive('/admin/reports', $currentUri); ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i> Analytics & Reports
                </a>
            </li>

        <?php elseif ($role === 'dean'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/dean/dashboard" class="nav-link <?php echo isActive('/dean/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/dean/supervisors" class="nav-link <?php echo isActive('/dean/supervisors', $currentUri); ?>">
                    <i class="bi bi-person-badge-fill"></i> Supervisors
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/dean/committee" class="nav-link <?php echo isActive('/dean/committee', $currentUri); ?>">
                    <i class="bi bi-shield-lock-fill"></i> Committee Members
                </a>
            </li>

        <?php elseif ($role === 'student'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/student/dashboard" class="nav-link <?php echo isActive('/student/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/student/profile" class="nav-link <?php echo isActive('/student/profile', $currentUri); ?>">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/student/group" class="nav-link <?php echo isActive('/student/group', $currentUri); ?>">
                    <i class="bi bi-people-fill"></i> Group & Members
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/student/proposal" class="nav-link <?php echo isActive('/student/proposal', $currentUri); ?>">
                    <i class="bi bi-file-earmark-plus-fill"></i> Project Proposal
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/student/grade" class="nav-link <?php echo isActive('/student/grade', $currentUri); ?>">
                    <i class="bi bi-award-fill"></i> Final Grade
                </a>
            </li>

        <?php elseif ($role === 'supervisor'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/supervisor/dashboard" class="nav-link <?php echo isActive('/supervisor/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/supervisor/profile" class="nav-link <?php echo isActive('/supervisor/profile', $currentUri); ?>">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/supervisor/groups" class="nav-link <?php echo isActive('/supervisor/groups', $currentUri); ?>">
                    <i class="bi bi-people-fill"></i> Assigned Groups
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/supervisor/reviews" class="nav-link <?php echo isActive('/supervisor/reviews', $currentUri); ?>">
                    <i class="bi bi-clipboard-check-fill"></i> Review Documents
                </a>
            </li>

        <?php elseif ($role === 'committee'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/dashboard" class="nav-link <?php echo isActive('/committee/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/evaluations" class="nav-link <?php echo isActive('/committee/evaluations', $currentUri); ?>">
                    <i class="bi bi-calendar-check-fill"></i> FYP Evaluations
                </a>
            </li>
        <?php endif; ?>
        
        <li class="nav-item mt-auto border-top border-secondary border-opacity-10">
            <a href="<?php echo $urlPrefix; ?>/logout" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right"></i> Log Out
            </a>
        </li>
    </ul>
</nav>

<!-- Page Content Wrappers -->
<div id="content" class="bg-light">
    <!-- Navbar / Header Actions -->
    <nav class="navbar navbar-expand-lg top-navbar navbar-light">
        <div class="container-fluid p-0">
            <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary d-lg-none">
                <i class="bi bi-justify"></i>
            </button>
            <h6 class="fw-bold m-0 d-none d-sm-inline-block text-dark"><i class="bi bi-mortarboard-fill text-primary me-2"></i>UNIVERSITY OF SINDH - FACULTY OF ENGINEERING AND TECHNOLOGY</h6>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <!-- Dark Theme Toggle -->
                <button class="btn btn-link text-secondary p-1" id="theme-toggle" type="button" title="Toggle Theme" style="text-decoration: none; border: none; box-shadow: none;">
                    <i class="bi bi-sun-fill fs-5 d-none" id="theme-sun" style="color: #F59E0B;"></i>
                    <i class="bi bi-moon-fill fs-5" id="theme-moon"></i>
                </button>

                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link text-secondary position-relative p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-5"></i>
                        <span class="badge bg-danger notification-badge" id="notification-badge" style="display: none;">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-0" style="width: 320px; max-height: 400px; overflow-y: auto;" id="notification-dropdown">
                        <div class="p-2 border-bottom d-flex align-items-center justify-content-between bg-primary text-white rounded-top">
                            <span class="small fw-semibold">Recent Alerts</span>
                            <a href="#" class="text-white text-decoration-none x-small" id="mark-all-read" style="font-size: 0.75rem;">Mark all read</a>
                        </div>
                        <div id="notification-list" class="py-1">
                            <!-- Populated dynamically -->
                            <li><a class="dropdown-item text-muted text-center py-2" href="#">Loading notifications...</a></li>
                        </div>
                    </ul>
                </div>

                <div class="vr bg-secondary opacity-25" style="height: 20px;"></div>

                <div class="d-flex align-items-center gap-2">
                    <?php if ($role === 'student'): ?>
                        <?php $avatarFile = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'default_avatar.svg'; ?>
                        <img src="<?php echo $urlPrefix; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-1 border-primary" style="width: 32px; height: 32px; object-fit: cover;" alt="Profile photo">
                    <?php endif; ?>
                    <div class="text-end d-none d-md-block">
                        <div class="fw-semibold small text-dark"><?php echo htmlspecialchars($name); ?></div>
                        <div class="x-small text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($email); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4">
        
        <!-- Flash Alert Messages -->
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
