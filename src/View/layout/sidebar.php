
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

// Fetch pending students count for HOD and Coordinator
$pendingStudentsCount = 0;
if ($role === 'hod' || $role === 'coordinator') {
    try {
        $dbSidebar = \Database::getInstance()->getConnection();
        $table = $role === 'hod' ? 'hods' : 'coordinators';
        $stmtDept = $dbSidebar->prepare("SELECT department FROM $table WHERE user_id = ?");
        $stmtDept->execute([$_SESSION['user_id'] ?? 0]);
        $userDept = $stmtDept->fetchColumn();

        if ($userDept) {
            $stmtPending = $dbSidebar->prepare("
                SELECT COUNT(*) FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE u.status = 'pending' AND s.department = ?
            ");
            $stmtPending->execute([$userDept]);
            $pendingStudentsCount = $stmtPending->fetchColumn();
        }
    } catch (Exception $e) {
        // Ignore DB errors in sidebar to prevent breaking layout
    }
}

// Fetch pending proposals count for Supervisor
$pendingProposalsCount = 0;
if ($role === 'supervisor') {
    try {
        $dbSidebar = \Database::getInstance()->getConnection();
        $stmtSup = $dbSidebar->prepare("SELECT id FROM supervisors WHERE user_id = ?");
        $stmtSup->execute([$_SESSION['user_id'] ?? 0]);
        $supId = $stmtSup->fetchColumn();

        if ($supId) {
            $stmtPendingProp = $dbSidebar->prepare("
                SELECT COUNT(*) FROM proposals pr
                JOIN projects p ON pr.group_id = p.group_id
                WHERE p.supervisor_id = ? AND pr.status = 'Submitted'
            ");
            $stmtPendingProp->execute([$supId]);
            $pendingProposalsCount = $stmtPendingProp->fetchColumn();
        }
    } catch (Exception $e) {
        // Ignore DB errors
    }
}

// Fetch pending meetings and status for Student
$pendingStudentMeetings = 0;
$unreadStudentChat = 0;
$hasApprovedStudentProject = false;
if ($role === 'student') {
    try {
        $dbSidebar = \Database::getInstance()->getConnection();
        $stmtG = $dbSidebar->prepare("
            SELECT g.id, p.status AS proj_status 
            FROM `groups` g 
            LEFT JOIN projects p ON p.group_id = g.id
            WHERE g.created_by = ? OR g.id IN (SELECT group_id FROM group_members WHERE student_id = ?) 
            LIMIT 1
        ");
        $userId = $_SESSION['user_id'] ?? 0;
        $stmtG->execute([$userId, $userId]);
        $grp = $stmtG->fetch(PDO::FETCH_ASSOC);
        
        if ($grp) {
            $hasApprovedStudentProject = ($grp['proj_status'] === 'Approved');
            $stmtSM = $dbSidebar->prepare("SELECT COUNT(*) FROM meetings WHERE group_id = ? AND status = 'Scheduled' AND meeting_date >= NOW()");
            $stmtSM->execute([$grp['id']]);
            $pendingStudentMeetings = $stmtSM->fetchColumn();
        }
        
        // Chat count
        $stmtChatCount = $dbSidebar->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0 AND redirect_url LIKE '%/chat%'");
        $stmtChatCount->execute([$userId]);
        $unreadStudentChat = $stmtChatCount->fetchColumn();
    } catch (Exception $e) { }
}

// Fetch pending meetings for Supervisor
$pendingSupMeetings = 0;
$unreadSupChat = 0;
if ($role === 'supervisor') {
    try {
        $dbSidebar = \Database::getInstance()->getConnection();
        $stmtSupM = $dbSidebar->prepare("SELECT COUNT(*) FROM meetings WHERE supervisor_id = ? AND status = 'Pending'");
        $stmtSupM->execute([$_SESSION['user_id'] ?? 0]);
        $pendingSupMeetings = $stmtSupM->fetchColumn();
        
        // Chat count
        $stmtChatCount = $dbSidebar->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0 AND redirect_url LIKE '%/chat%'");
        $stmtChatCount->execute([$_SESSION['user_id'] ?? 0]);
        $unreadSupChat = $stmtChatCount->fetchColumn();
    } catch (Exception $e) { }
}
?>

<!-- Sidebar -->
<nav id="sidebar" class="d-flex flex-column">
    <div class="sidebar-header d-flex align-items-center justify-content-between flex-shrink-0">
        <a href="<?php echo $urlPrefix; ?>/<?php echo htmlspecialchars($role ?: 'login'); ?>/dashboard" class="d-flex align-items-center gap-3 text-decoration-none sidebar-brand">
            <div style="width: 40px;height: 40px;display: flex;align-items: center;justify-content: center;flex-shrink: 0">
                <img src="<?php echo $urlPrefix; ?>/images/logo.png" alt="Logo" style="max-width: 100%;max-height: 100%;object-fit: contain">
            </div>
            <div class="sidebar-brand-text">
                <h6 class="m-0 fw-bold" style="color: var(--text-primary);font-size: 0.88rem;letter-spacing: -0.01em">FYP Portal</h6>
            </div>
        </a>
        <button type="button" id="desktopSidebarCollapse" class="btn btn-link p-0 d-none d-lg-flex align-items-center justify-content-center" style="color: var(--text-primary);width: 36px;height: 36px;opacity: 0.7;transition: all 0.2s" title="Toggle Sidebar">
            <i class="bi bi-layout-sidebar" style="font-size: 1.2rem"></i>
        </button>
    </div>

    <!-- Scrollable Navigation Items -->
    <div class="sidebar-nav-container flex-grow-1 py-2">
        <ul class="list-unstyled nav flex-column flex-nowrap mb-0">
            <?php if ($role === 'admin'): ?>
                <?php
                    $dbSidebar = \Database::getInstance()->getConnection();
                    $stmtSidebar = $dbSidebar->query("SELECT COUNT(*) FROM users WHERE status = 'pending' AND role = 'student'");
                    $pendingStudentsCount = $stmtSidebar->fetchColumn();
                ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/dashboard" class="nav-link <?php echo isActive('/admin/dashboard', $currentUri); ?>">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/users" class="nav-link <?php echo isActive('/admin/users', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-people-fill"></i> <span>Manage Users</span></div>
                        <?php if ($pendingStudentsCount > 0): ?>
                        <span class="badge rounded-pill ms-auto" style="font-size: 0.7rem; font-weight: 700; padding: 0.4em 0.7em; background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);"><?php echo $pendingStudentsCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/groups" class="nav-link <?php echo isActive('/admin/groups', $currentUri); ?>">
                        <i class="bi bi-folder-fill"></i> <span>FYP Groups</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/batches" class="nav-link <?php echo isActive('/admin/batches', $currentUri); ?>">
                        <i class="bi bi-box-seam-fill"></i> <span>Batches</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/slots" class="nav-link <?php echo isActive('/admin/slots', $currentUri); ?>">
                        <i class="bi bi-person-badge-fill"></i> <span>Supervisor Slots</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/deadlines" class="nav-link <?php echo isActive('/admin/deadlines', $currentUri); ?>">
                        <i class="bi bi-calendar2-event-fill"></i> <span>Deadlines</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/admin/reports" class="nav-link <?php echo isActive('/admin/reports', $currentUri); ?>">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i> <span>Analytics & Reports</span>
                    </a>
                </li>

            <?php elseif ($role === 'hod'): ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/dashboard" class="nav-link <?php echo isActive('/hod/dashboard', $currentUri); ?>">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/profile" class="nav-link <?php echo isActive('/hod/profile', $currentUri); ?>">
                        <i class="bi bi-person-circle"></i> <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/supervisors" class="nav-link <?php echo isActive('/hod/supervisors', $currentUri); ?>">
                        <i class="bi bi-person-badge-fill"></i> <span>Supervisors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/committee" class="nav-link <?php echo isActive('/hod/committee', $currentUri); ?>">
                        <i class="bi bi-shield-fill"></i> <span>Committee Members</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/coordinators" class="nav-link <?php echo isActive('/hod/coordinators', $currentUri); ?>">
                        <i class="bi bi-person-workspace"></i> <span>Coordinators</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/students/verify" class="nav-link <?php echo isActive('/hod/students/verify', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-person-check-fill"></i> <span>Verify Students</span></div>
                        <?php if (isset($pendingStudentsCount) && $pendingStudentsCount > 0): ?>
                            <span class="badge rounded-pill ms-auto" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 0.7rem; padding: 0.35em 0.65em; border: 1px solid rgba(239, 68, 68, 0.3);"><?php echo $pendingStudentsCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/hod/settings" class="nav-link <?php echo isActive('/hod/settings', $currentUri); ?>">
                        <i class="bi bi-sliders"></i> <span>Department Settings</span>
                    </a>
                </li>

            <?php elseif ($role === 'student'): ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/dashboard" class="nav-link <?php echo isActive('/student/dashboard', $currentUri); ?>">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/profile" class="nav-link <?php echo isActive('/student/profile', $currentUri); ?>">
                        <i class="bi bi-person-circle"></i> <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/group" class="nav-link <?php echo isActive('/student/group', $currentUri); ?>">
                        <i class="bi bi-people-fill"></i> <span>Group & Members</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/proposal" class="nav-link <?php echo isActive('/student/proposal', $currentUri); ?>">
                        <i class="bi bi-file-earmark-plus-fill"></i> <span>Project Proposal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/previous-projects" class="nav-link <?php echo isActive('/student/previous-projects', $currentUri); ?>">
                        <i class="bi bi-archive-fill"></i> <span>Previous Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/grade" class="nav-link <?php echo isActive('/student/grade', $currentUri); ?>">
                        <i class="bi bi-award-fill"></i> <span>Final Grade</span>
                    </a>
                </li>
                <?php if (!empty($hasApprovedStudentProject)): ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/chat" class="nav-link <?php echo isActive('/student/chat', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-chat-dots-fill"></i> <span>Chat with Supervisor</span></div>
                        <?php if (isset($unreadStudentChat) && $unreadStudentChat > 0): ?>
                            <span class="badge rounded-pill ms-auto" style="background: rgba(16, 185, 129, 0.15); color: #10b981; font-size: 0.7rem; padding: 0.35em 0.65em; border: 1px solid rgba(16, 185, 129, 0.3);"><?php echo $unreadStudentChat; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/student/meetings" class="nav-link <?php echo isActive('/student/meetings', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-calendar-event-fill"></i> <span>Meetings</span></div>
                        <?php if (isset($pendingStudentMeetings) && $pendingStudentMeetings > 0): ?>
                            <span class="badge rounded-pill ms-auto" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; font-size: 0.7rem; padding: 0.35em 0.65em; border: 1px solid rgba(59, 130, 246, 0.3);"><?php echo $pendingStudentMeetings; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

            <?php elseif ($role === 'supervisor'): ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/supervisor/dashboard" class="nav-link <?php echo isActive('/supervisor/dashboard', $currentUri); ?>">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/supervisor/profile" class="nav-link <?php echo isActive('/supervisor/profile', $currentUri); ?>">
                        <i class="bi bi-person-circle"></i> <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/supervisor/groups" class="nav-link <?php echo isActive('/supervisor/groups', $currentUri); ?>">
                        <i class="bi bi-people-fill"></i> <span>FYP Groups</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/supervisor/chat" class="nav-link <?php echo isActive('/supervisor/chat', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-chat-dots-fill"></i> <span>Messages</span></div>
                        <?php if (isset($unreadSupChat) && $unreadSupChat > 0): ?>
                            <span class="badge rounded-pill ms-auto" style="background: rgba(16, 185, 129, 0.15); color: #10b981; font-size: 0.7rem; padding: 0.35em 0.65em; border: 1px solid rgba(16, 185, 129, 0.3);"><?php echo $unreadSupChat; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/supervisor/meetings" class="nav-link <?php echo isActive('/supervisor/meetings', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-calendar-event-fill"></i> <span>Meetings</span></div>
                        <?php if (isset($pendingSupMeetings) && $pendingSupMeetings > 0): ?>
                            <span class="badge rounded-pill ms-auto" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; font-size: 0.7rem; padding: 0.35em 0.65em; border: 1px solid rgba(59, 130, 246, 0.3);"><?php echo $pendingSupMeetings; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/supervisor/previous-projects" class="nav-link <?php echo isActive('/supervisor/previous-projects', $currentUri); ?>">
                        <i class="bi bi-archive-fill"></i> <span>Previous Projects</span>
                    </a>
                </li>

            <?php elseif ($role === 'committee'): ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/committee/dashboard" class="nav-link <?php echo isActive('/committee/dashboard', $currentUri); ?>">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/committee/profile" class="nav-link <?php echo isActive('/committee/profile', $currentUri); ?>">
                        <i class="bi bi-person-circle"></i> <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/committee/evaluations" class="nav-link <?php echo isActive('/committee/evaluations', $currentUri); ?>">
                        <i class="bi bi-calendar-check-fill"></i> <span>Evaluations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="nav-link <?php echo isActive('/committee/grading-sheet', $currentUri) && (isset($_GET['stage']) && $_GET['stage'] === 'Proposal Defence Presentation') ? 'active' : ''; ?>">
                        <i class="bi bi-table"></i> <span>Grade Proposal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="nav-link <?php echo isActive('/committee/grading-sheet', $currentUri) && (isset($_GET['stage']) && $_GET['stage'] === 'FYP Progress Presentation') ? 'active' : ''; ?>">
                        <i class="bi bi-table"></i> <span>Grade Progress</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/committee/grading-sheet?stage=Final Presentation" class="nav-link <?php echo isActive('/committee/grading-sheet', $currentUri) && (isset($_GET['stage']) && $_GET['stage'] === 'Final Presentation') ? 'active' : ''; ?>">
                        <i class="bi bi-table"></i> <span>Grade Final</span>
                    </a>
                </li>

            <?php elseif ($role === 'coordinator'): ?>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/dashboard" class="nav-link <?php echo isActive('/coordinator/dashboard', $currentUri); ?>">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/profile" class="nav-link <?php echo isActive('/coordinator/profile', $currentUri); ?>">
                        <i class="bi bi-person-circle"></i> <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/proposals" class="nav-link <?php echo isActive('/coordinator/proposals', $currentUri); ?>">
                        <i class="bi bi-file-earmark-text-fill"></i> <span>Project Proposals</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/users" class="nav-link <?php echo isActive('/coordinator/users', $currentUri); ?> d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-truncate"><i class="bi bi-person-check-fill"></i> <span>Verify Students</span></div>
                        <?php if (isset($pendingStudentsCount) && $pendingStudentsCount > 0): ?>
                            <span class="badge rounded-pill ms-auto" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 0.7rem; padding: 0.35em 0.65em; border: 1px solid rgba(239, 68, 68, 0.3);"><?php echo $pendingStudentsCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/assessment" class="nav-link <?php echo isActive('/coordinator/assessment', $currentUri); ?>">
                        <i class="bi bi-file-earmark-excel-fill"></i> <span>External Assessment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/notice" class="nav-link <?php echo isActive('/coordinator/notice', $currentUri); ?>">
                        <i class="bi bi-megaphone-fill"></i> <span>Notice Generator</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/meetings" class="nav-link <?php echo isActive('/coordinator/meetings', $currentUri); ?>">
                        <i class="bi bi-calendar2-check-fill"></i> <span>Meetings Audit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $urlPrefix; ?>/coordinator/previous-projects" class="nav-link <?php echo isActive('/coordinator/previous-projects', $currentUri); ?>">
                        <i class="bi bi-archive-fill"></i> <span>Previous Projects</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Pinned Bottom Links Footer -->
    <div class="sidebar-footer flex-shrink-0 pt-2 pb-3">
        <ul class="list-unstyled nav flex-column flex-nowrap mb-0">
            <li class="nav-item mt-1">
                <a href="#" class="nav-link d-flex align-items-center justify-content-between" id="theme-toggle">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-palette-fill"></i>
                        <span>Appearance</span>
                    </div>
                    
                    <!-- Tiny Pill Switch -->
                    <div class="theme-switch" style="width: 44px; height: 24px; border-radius: 24px; background: #cbd5e1; position: relative; transition: all 0.3s ease; flex-shrink: 0;">
                        <div class="theme-switch-knob shadow-sm d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; border-radius: 50%; background: #ffffff; position: absolute; top: 2px; left: 2px; transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);">
                            <i class="bi bi-brightness-high switch-sun" style="font-size: 0.7rem; color: #1e293b; position: absolute; transition: all 0.3s ease;"></i>
                            <i class="bi bi-moon-stars switch-moon" style="font-size: 0.7rem; color: #1e293b; position: absolute; transition: all 0.3s ease; opacity: 0; transform: scale(0.5) rotate(90deg);"></i>
                        </div>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/change-password" class="nav-link <?php echo isActive('/change-password', $currentUri); ?>">
                    <i class="bi bi-shield-lock-fill"></i> <span>Change Password</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $urlPrefix; ?>/logout" class="nav-link <?php echo isActive('/logout', $currentUri); ?>">
                    <i class="bi bi-box-arrow-right"></i> <span>Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Page Content Wrappers -->
<div id="content" class="bg-light">
    <!-- Navbar / Header Actions -->
    <nav class="navbar navbar-expand-lg top-navbar" style="border-bottom: 1px solid var(--border-color);padding-top: 14px;padding-bottom: 14px">
        <div class="container-fluid p-0">
            <!-- Desktop Branding -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--text-primary);font-size: 0.95rem;letter-spacing: -0.01em">Faculty of Engineering &amp; Technology</h6>
                    <small style="color: var(--text-secondary);font-size: 0.72rem;letter-spacing: 0.02em;font-weight: 500">University of Sindh, Jamshoro</small>
                </div>
            </div>
            
            <!-- Mobile Branding -->
            <a href="<?php echo $urlPrefix; ?>/<?php echo htmlspecialchars($role ?? 'login'); ?>/dashboard" class="d-flex align-items-center gap-2 d-sm-none text-decoration-none" style="cursor: pointer">
                <div style="width: 38px;height: 38px;display: flex;align-items: center;justify-content: center;flex-shrink: 0">
                    <img src="<?php echo $urlPrefix; ?>/images/logo.png" alt="Logo" style="max-width: 100%;max-height: 100%;object-fit: contain">
                </div>
                <div style="max-width: 160px;">
                    <h6 class="fw-bold m-0 text-wrap" style="color: var(--text-primary);font-size: 0.8rem;line-height: 1.25;letter-spacing: -0.01em">Faculty of Engineering &amp; Technology</h6>
                </div>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">


                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 34px;height: 34px;border: 1px solid var(--border-color);background: var(--form-bg)">
                        <i class="bi bi-bell-fill" style="font-size: 1rem;color: var(--text-secondary)"></i>
                        <span class="position-absolute bg-danger text-white fw-bold d-none align-items-center justify-content-center" id="notification-badge" style="top: -4px;right: -4px;font-size: 0.65rem;min-width: 18px;height: 18px;padding: 0 4px;border-radius: 10px;border: 2px solid var(--navbar-bg);box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);z-index: 2"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-0" style="width: 320px;max-height: 440px;overflow-y: auto;border-radius: 14px;background: var(--card-bg)" id="notification-dropdown">
                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between rounded-top" style="background: #1e352f;color: #ffffff;border-color: var(--border-color) !important; position: sticky; top: 0; z-index: 100;">
                            <span class="small fw-semibold">Recent Alerts</span>
                            <a href="#" class="text-white text-decoration-none" id="mark-all-read" style="font-size: 0.75rem;opacity: 0.8">Mark all read</a>
                        </div>
                        <div id="notification-list" class="py-1">
                            <li><a class="dropdown-item text-center py-3 small" style="color: var(--text-secondary)" href="#">Loading notifications...</a></li>
                        </div>
                    </ul>
                </div>

                <div class="vr d-none d-md-block mx-1" style="height: 24px;background-color: var(--border-color);opacity: 1"></div>

                <!-- User Profile Pill -->
                <div class="d-flex align-items-center gap-2 pe-md-2 py-1" style="cursor: default">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-bold" style="color: var(--text-primary);font-size: 0.85rem;letter-spacing: -0.01em"><?php echo htmlspecialchars($name); ?></div>
                        <div class="text-uppercase fw-semibold" style="color: var(--text-secondary);font-size: 0.65rem;letter-spacing: 0.05em"><?php echo htmlspecialchars($role); ?></div>
                    </div>
                    <?php if ($role === 'student'): ?>
                        <?php 
                        $avatarFile = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'default_avatar.svg'; 
                        ?>
                        <img src="<?php echo $urlPrefix; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle shadow-sm ms-1 d-none d-sm-inline-block" style="width: 36px;height: 36px;object-fit: cover;border: 2px solid var(--border-color)" alt="Profile photo">
                    <?php endif; ?>
                </div>

                <!-- Three-line hamburger menu button placed on the right on mobile -->
                <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none ms-1 rounded-3 d-flex align-items-center justify-content-center" style="width: 36px;height: 36px;border: 1px solid var(--border-color);background: var(--form-bg);color: var(--text-primary)">
                    <i class="bi bi-list" style="font-size: 1.2rem"></i>
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

