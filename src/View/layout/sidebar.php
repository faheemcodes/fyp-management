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
<nav id="sidebar" class="d-flex flex-column">
    <a href="<?php echo $urlPrefix; ?>/<?php echo htmlspecialchars($role ?: 'login'); ?>/dashboard" class="sidebar-header d-flex align-items-center gap-3 text-decoration-none">
        <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <img src="<?php echo $urlPrefix; ?>/images/logo.png" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <div>
            <h6 class="m-0 text-white fw-bold" style="font-size: 0.88rem; letter-spacing: -0.01em;">University of Sindh</h6>
            <small style="font-size: 0.65rem; color: rgba(255,255,255,0.42);">FYP Portal</small>
        </div>
    </a>

    <ul class="list-unstyled nav flex-column mt-3 flex-grow-1 pb-3" style="overflow-y: auto;">
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
                <a href="<?php echo $urlPrefix; ?>/admin/batches" class="nav-link <?php echo isActive('/admin/batches', $currentUri); ?>">
                    <i class="bi bi-box-seam-fill"></i> Batches
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/admin/slots" class="nav-link <?php echo isActive('/admin/slots', $currentUri); ?>">
                    <i class="bi bi-person-badge-fill"></i> Supervisor Slots
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

        <?php elseif ($role === 'hod'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/hod/dashboard" class="nav-link <?php echo isActive('/hod/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/hod/profile" class="nav-link <?php echo isActive('/hod/profile', $currentUri); ?>">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/hod/supervisors" class="nav-link <?php echo isActive('/hod/supervisors', $currentUri); ?>">
                    <i class="bi bi-person-badge-fill"></i> Supervisors
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/hod/committee" class="nav-link <?php echo isActive('/hod/committee', $currentUri); ?>">
                    <i class="bi bi-shield-fill"></i> Committee Members
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/hod/coordinators" class="nav-link <?php echo isActive('/hod/coordinators', $currentUri); ?>">
                    <i class="bi bi-person-workspace"></i> Coordinators
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/hod/students/verify" class="nav-link <?php echo isActive('/hod/students/verify', $currentUri); ?>">
                    <i class="bi bi-person-check-fill"></i> Verify Students
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
            <?php 
                $dbSidebar = \Database::getInstance()->getConnection();
                $stmtChat = $dbSidebar->prepare("
                    SELECT 1 FROM projects p 
                    JOIN `groups` g ON p.group_id = g.id 
                    WHERE g.created_by = ? AND p.status = 'Approved'
                ");
                $stmtChat->execute([$_SESSION['user_id'] ?? 0]);
                if ($stmtChat->fetchColumn()):
            ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/student/chat" class="nav-link <?php echo isActive('/student/chat', $currentUri); ?>">
                    <i class="bi bi-chat-dots-fill"></i> Chat with Supervisor
                </a>
            </li>
            <?php endif; ?>

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
                    <i class="bi bi-clipboard-check-fill"></i> Review Proposals
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/supervisor/chat" class="nav-link <?php echo isActive('/supervisor/chat', $currentUri); ?>">
                    <i class="bi bi-chat-dots-fill"></i> Messages
                </a>
            </li>

        <?php elseif ($role === 'committee'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/dashboard" class="nav-link <?php echo isActive('/committee/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/profile" class="nav-link <?php echo isActive('/committee/profile', $currentUri); ?>">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/evaluations" class="nav-link <?php echo isActive('/committee/evaluations', $currentUri); ?>">
                    <i class="bi bi-calendar-check-fill"></i> Evaluations
                </a>
            </li>
            
            <li class="nav-item mt-3 mb-1 px-3">
                <span class="text-xs fw-bold text-uppercase text-secondary" style="font-size: 0.7rem; letter-spacing: 0.05em;">Online Grading</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="nav-link <?php echo isActive('/committee/grading-sheet', $currentUri) && (isset($_GET['stage']) && $_GET['stage'] === 'Proposal Defence Presentation') ? 'active' : ''; ?>">
                    <i class="bi bi-table"></i> Grade Proposal
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="nav-link <?php echo isActive('/committee/grading-sheet', $currentUri) && (isset($_GET['stage']) && $_GET['stage'] === 'FYP Progress Presentation') ? 'active' : ''; ?>">
                    <i class="bi bi-table"></i> Grade Progress
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/committee/grading-sheet?stage=Final Presentation" class="nav-link <?php echo isActive('/committee/grading-sheet', $currentUri) && (isset($_GET['stage']) && $_GET['stage'] === 'Final Presentation') ? 'active' : ''; ?>">
                    <i class="bi bi-table"></i> Grade Final
                </a>
            </li>


        <?php elseif ($role === 'coordinator'): ?>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/coordinator/dashboard" class="nav-link <?php echo isActive('/coordinator/dashboard', $currentUri); ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/coordinator/profile" class="nav-link <?php echo isActive('/coordinator/profile', $currentUri); ?>">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/coordinator/proposals" class="nav-link <?php echo isActive('/coordinator/proposals', $currentUri); ?>">
                    <i class="bi bi-file-earmark-text-fill"></i> Project Proposals
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/coordinator/users" class="nav-link <?php echo isActive('/coordinator/users', $currentUri); ?>">
                    <i class="bi bi-person-check-fill"></i> Verify Students
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/coordinator/assessment" class="nav-link <?php echo isActive('/coordinator/assessment', $currentUri); ?>">
                    <i class="bi bi-file-earmark-excel-fill"></i> External Assessment
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/coordinator/notice" class="nav-link <?php echo isActive('/coordinator/notice', $currentUri); ?>">
                    <i class="bi bi-megaphone-fill"></i> Notice Generator
                </a>
            </li>
        <?php endif; ?>
        
        <style>
            .action-icon-pw {
                background: rgba(245, 158, 11, 0.15) !important;
                color: #fcd34d !important;
                width: 26px; height: 26px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;
                transition: all 0.2s ease;
            }
            .action-icon-logout {
                background: rgba(239, 68, 68, 0.2) !important;
                color: #f87171 !important;
                width: 26px; height: 26px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;
                transition: all 0.2s ease;
            }
            /* Protect custom icon colors from the global .active override */
            #sidebar .nav-link.active .action-icon-pw i {
                color: #fcd34d !important;
            }
            #sidebar .nav-link.active .action-icon-logout i {
                color: #f87171 !important;
            }
        </style>
        <div class="mt-auto">
            <ul class="list-unstyled nav flex-column mb-0">
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/change-password" class="nav-link <?php echo isActive('/change-password', $currentUri); ?> d-flex align-items-center gap-2">
                        <div class="action-icon-pw">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        Change Password
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/logout" class="nav-link <?php echo isActive('/logout', $currentUri); ?> d-flex align-items-center gap-2">
                        <div class="action-icon-logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        Log Out
                    </a>
                </li>
            </ul>
        </div>
    </ul>
</nav>

<!-- Page Content Wrappers -->
<div id="content" class="bg-light">
    <!-- Navbar / Header Actions -->
    <nav class="navbar navbar-expand-lg top-navbar" style="border-bottom: 1px solid var(--border-color); padding-top: 14px; padding-bottom: 14px;">
        <div class="container-fluid p-0">
            <!-- Desktop Branding -->
            <a href="<?php echo $urlPrefix; ?>/<?php echo htmlspecialchars($role ?? 'login'); ?>/dashboard" class="d-none d-sm-flex align-items-center gap-3 text-decoration-none" style="cursor: pointer;">
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--text-primary); font-size: 0.95rem; letter-spacing: -0.01em;">Faculty of Engineering &amp; Technology</h6>
                    <small style="color: var(--text-secondary); font-size: 0.72rem; letter-spacing: 0.02em; font-weight: 500;">University of Sindh, Jamshoro</small>
                </div>
            </a>
            
            <!-- Mobile Branding -->
            <a href="<?php echo $urlPrefix; ?>/<?php echo htmlspecialchars($role ?? 'login'); ?>/dashboard" class="d-flex align-items-center gap-2 d-sm-none text-decoration-none" style="cursor: pointer;">
                <div style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <img src="<?php echo $urlPrefix; ?>/images/logo.png" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--text-primary); font-size: 0.85rem; letter-spacing: -0.01em;">University of Sindh</h6>
                    <small style="color: var(--text-secondary); font-size: 0.65rem; letter-spacing: 0.02em;">FYP Portal</small>
                </div>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                <!-- Dark Theme Toggle -->
                <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" id="theme-toggle" type="button" title="Toggle Theme" style="width: 34px; height: 34px; border: 1px solid var(--border-color); background: var(--form-bg);">
                    <i class="bi bi-sun-fill d-none" id="theme-sun" style="font-size: 1rem; color: #F59E0B;"></i>
                    <i class="bi bi-moon-fill" id="theme-moon" style="font-size: 1rem; color: var(--text-secondary);"></i>
                </button>

                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 34px; height: 34px; border: 1px solid var(--border-color); background: var(--form-bg);">
                        <i class="bi bi-bell-fill" style="font-size: 1rem; color: var(--text-secondary);"></i>
                        <span class="position-absolute bg-danger text-white fw-bold d-none align-items-center justify-content-center" id="notification-badge" style="top: -4px; right: -4px; font-size: 0.65rem; min-width: 18px; height: 18px; padding: 0 4px; border-radius: 10px; border: 2px solid var(--navbar-bg); box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3); z-index: 2;"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-0" style="width: 320px; max-height: 440px; overflow-y: auto; border-radius: 14px; background: var(--card-bg);" id="notification-dropdown">
                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between rounded-top" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #ffffff; border-color: var(--border-color) !important;">
                            <span class="small fw-semibold">Recent Alerts</span>
                            <a href="#" class="text-white text-decoration-none" id="mark-all-read" style="font-size: 0.75rem; opacity: 0.8;">Mark all read</a>
                        </div>
                        <div id="notification-list" class="py-1">
                            <li><a class="dropdown-item text-center py-3 small" style="color: var(--text-secondary);" href="#">Loading notifications...</a></li>
                        </div>
                    </ul>
                </div>

                <div class="vr d-none d-md-block mx-1" style="height: 24px; background-color: var(--border-color); opacity: 1;"></div>

                <!-- User Profile Pill -->
                <div class="d-flex align-items-center gap-2 pe-md-2 py-1" style="cursor: default;">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-bold" style="color: var(--text-primary); font-size: 0.85rem; letter-spacing: -0.01em;"><?php echo htmlspecialchars($name); ?></div>
                        <div class="text-uppercase fw-semibold" style="color: var(--text-secondary); font-size: 0.65rem; letter-spacing: 0.05em;"><?php echo htmlspecialchars($role); ?></div>
                    </div>
                    <?php if ($role === 'student'): ?>
                        <?php 
                        $avatarFile = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'default_avatar.svg'; 
                        ?>
                        <img src="<?php echo $urlPrefix; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle shadow-sm ms-1 d-none d-sm-inline-block" style="width: 36px; height: 36px; object-fit: cover; border: 2px solid var(--border-color);" alt="Profile photo">
                    <?php endif; ?>
                </div>

                <!-- Three-line hamburger menu button placed on the right on mobile -->
                <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none ms-1 rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border: 1px solid var(--border-color); background: var(--form-bg); color: var(--text-primary);">
                    <i class="bi bi-list" style="font-size: 1.2rem;"></i>
                </button>
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
