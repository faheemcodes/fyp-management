<?php
// Define custom session save path for portability and isolation across setups
$sessionPath = __DIR__ . '/../sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0700, true);
}
session_save_path($sessionPath);

// Set session cookie security parameters
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0, // Until browser is closed
    'path' => $cookieParams['path'] ?? '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Load Composer Autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Autoload config and src/ files
spl_autoload_register(function ($class) {
    // Replace namespaces with directory separator
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    
    // Check in src folder
    $file = __DIR__ . '/../src/' . $classPath . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Check in config folder
    $fileConfig = __DIR__ . '/../config/' . $classPath . '.php';
    if (file_exists($fileConfig)) {
        require_once $fileConfig;
        return;
    }
});

// Helper function to redirect
function redirect($path) {
    // Normalize URL
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseDir = dirname($scriptName);
    if ($baseDir === '\\' || $baseDir === '/') {
        $baseDir = '';
    }
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
    header('Location: ' . $baseDir . $path);
    exit;
}

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = dirname($scriptName);
if ($baseDir !== '/' && $baseDir !== '\\' && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}
$uri = '/' . ltrim($uri, '/');

// Define routes
$routes = [
    '/' => ['Controller\AuthController', 'index'],
    '/login' => ['Controller\AuthController', 'login'],
    '/logout' => ['Controller\AuthController', 'logout'],
    '/register' => ['Controller\AuthController', 'register'],
    '/forgot-password' => ['Controller\AuthController', 'forgotPassword'],
    '/reset-password' => ['Controller\AuthController', 'resetPassword'],
    '/change-password' => ['Controller\AuthController', 'changePassword'],
    
    // Admin routes
    '/admin/dashboard' => ['Controller\AdminController', 'dashboard'],
    '/admin/users' => ['Controller\AdminController', 'users'],
    '/admin/users/approve' => ['Controller\AdminController', 'approveUser'],
    '/admin/users/reject' => ['Controller\AdminController', 'rejectUser'],
    '/admin/users/create' => ['Controller\AdminController', 'createUser'],
    '/admin/groups' => ['Controller\AdminController', 'groups'],
    '/admin/groups/assign' => ['Controller\AdminController', 'assignSupervisor'],
    '/admin/deadlines' => ['Controller\AdminController', 'deadlines'],
    '/admin/reports' => ['Controller\AdminController', 'reports'],
    '/admin/users/edit' => ['Controller\AdminController', 'editUser'],
    '/admin/users/delete' => ['Controller\AdminController', 'deleteUser'],
    '/admin/groups/create' => ['Controller\AdminController', 'createGroup'],
    '/admin/groups/edit' => ['Controller\AdminController', 'editGroup'],
    '/admin/groups/delete' => ['Controller\AdminController', 'deleteGroup'],
    '/admin/groups/members/update' => ['Controller\AdminController', 'updateGroupMembers'],
    '/admin/projects/edit' => ['Controller\AdminController', 'editProject'],
    '/admin/projects/delete' => ['Controller\AdminController', 'deleteProject'],
    '/admin/grades/edit' => ['Controller\AdminController', 'editGrades'],
    '/admin/deadlines/delete' => ['Controller\AdminController', 'deleteDeadline'],

    // HOD routes
    '/hod/dashboard' => ['Controller\HodController', 'dashboard'],
    '/hod/supervisors' => ['Controller\HodController', 'supervisors'],
    '/hod/supervisors/create' => ['Controller\HodController', 'createSupervisor'],
    '/hod/supervisors/edit' => ['Controller\HodController', 'editSupervisor'],
    '/hod/supervisors/delete' => ['Controller\HodController', 'deleteSupervisor'],
    '/hod/committee' => ['Controller\HodController', 'committee'],
    '/hod/committee/create' => ['Controller\HodController', 'createCommittee'],
    '/hod/committee/edit' => ['Controller\HodController', 'editCommittee'],
    '/hod/committee/delete' => ['Controller\HodController', 'deleteCommittee'],
    '/hod/coordinators' => ['Controller\HodController', 'coordinators'],
    '/hod/coordinators/create' => ['Controller\HodController', 'createCoordinator'],
    '/hod/coordinators/edit' => ['Controller\HodController', 'editCoordinator'],
    '/hod/coordinators/delete' => ['Controller\HodController', 'deleteCoordinator'],
    '/hod/students/verify' => ['Controller\HodController', 'verifyStudents'],
    '/hod/students/approve' => ['Controller\HodController', 'approveStudent'],
    '/hod/students/reject' => ['Controller\HodController', 'rejectStudent'],
    '/hod/profile' => ['Controller\HodController', 'profile'],
    
    // Student routes
    '/student/dashboard' => ['Controller\StudentController', 'dashboard'],
    '/student/profile' => ['Controller\StudentController', 'profile'],
    '/student/group' => ['Controller\StudentController', 'group'],
    '/student/group/create' => ['Controller\StudentController', 'createGroup'],
    '/student/group/add-member' => ['Controller\StudentController', 'addMember'],
    '/student/group/update-members' => ['Controller\StudentController', 'updateMembers'],
    '/student/proposal' => ['Controller\StudentController', 'proposal'],
    '/student/proposal/submit' => ['Controller\StudentController', 'submitProposal'],
    '/student/grade' => ['Controller\StudentController', 'grade'],
    
    // Supervisor routes
    '/supervisor/dashboard' => ['Controller\SupervisorController', 'dashboard'],
    '/supervisor/profile' => ['Controller\SupervisorController', 'profile'],
    '/supervisor/groups' => ['Controller\SupervisorController', 'groups'],
    '/supervisor/groups/grade' => ['Controller\SupervisorController', 'gradeGroup'],
    '/supervisor/groups/toggle-visibility' => ['Controller\SupervisorController', 'toggleVisibility'],
    '/supervisor/reviews' => ['Controller\SupervisorController', 'reviews'],
    '/supervisor/proposal/action' => ['Controller\SupervisorController', 'proposalAction'],
    // Committee routes
    '/committee/dashboard' => ['Controller\CommitteeController', 'dashboard'],
    '/committee/evaluations' => ['Controller\CommitteeController', 'evaluations'],
    '/committee/evaluations/grade' => ['Controller\CommitteeController', 'gradeEvaluation'],
    '/committee/evaluations/toggle-visibility' => ['Controller\CommitteeController', 'toggleCommitteeVisibility'],
    '/committee/profile' => ['Controller\CommitteeController', 'profile'],
    
    // Coordinator routes
    '/coordinator/dashboard' => ['Controller\CoordinatorController', 'dashboard'],
    '/coordinator/profile' => ['Controller\CoordinatorController', 'profile'],
    '/coordinator/users' => ['Controller\CoordinatorController', 'verifyStudents'],
    '/coordinator/users/approve' => ['Controller\CoordinatorController', 'approveStudent'],
    '/coordinator/users/reject' => ['Controller\CoordinatorController', 'rejectStudent'],
    '/coordinator/notice' => ['Controller\CoordinatorController', 'notice'],
    '/coordinator/notice/create' => ['Controller\CoordinatorController', 'createNotice'],
    '/coordinator/notice/view' => ['Controller\CoordinatorController', 'viewNotice'],
    '/coordinator/notice/delete' => ['Controller\CoordinatorController', 'deleteNotice'],
    '/notice/view' => ['Controller\CoordinatorController', 'viewNotice'],
    
    // Notifications API
    '/api/notifications' => ['Controller\AuthController', 'fetchNotifications'],
    '/api/notifications/read' => ['Controller\AuthController', 'markNotificationRead'],
    '/api/notifications/delete' => ['Controller\AuthController', 'deleteNotification']
];

// Dispatch route
if (array_key_exists($uri, $routes)) {
    list($controllerClass, $method) = $routes[$uri];
    
    // Inactivity timeout: 15 minutes (900 seconds)
    $timeout = 900;
    $isApiRoute = (strpos($uri, '/api/') === 0);
    
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            // Clear and destroy session
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            
            if ($isApiRoute) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['error' => 'Session expired due to inactivity']);
                exit;
            } else {
                session_start();
                $_SESSION['flash']['error'] = 'Your session has expired due to 15 minutes of inactivity. Please log in again.';
                redirect('/login');
            }
        }
        
        // Refresh activity time only for non-API requests (so background polling doesn't keep it alive)
        if (!$isApiRoute) {
            $_SESSION['last_activity'] = time();
        }
    }

    // Check login requirements (simple session validation)
    $authRoutes = ['/', '/login', '/register', '/forgot-password', '/reset-password'];
    if (!in_array($uri, $authRoutes)) {
        if (!isset($_SESSION['user_id'])) {
            if ($isApiRoute) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            } else {
                redirect('/login');
            }
        }
        
        // Role based access check
        $role = $_SESSION['role'] ?? '';
        if (strpos($uri, '/admin') === 0 && $role !== 'admin') {
            die("Unauthorized access: Admin only.");
        }
        if (strpos($uri, '/hod') === 0 && $role !== 'hod') {
            die("Unauthorized access: HOD only.");
        }
        if (strpos($uri, '/student') === 0 && $role !== 'student') {
            die("Unauthorized access: Student only.");
        }
        if (strpos($uri, '/supervisor') === 0 && $role !== 'supervisor') {
            die("Unauthorized access: Supervisor only.");
        }
        if (strpos($uri, '/committee') === 0 && $role !== 'committee') {
            die("Unauthorized access: Committee only.");
        }
        if (strpos($uri, '/coordinator') === 0 && $role !== 'coordinator') {
            die("Unauthorized access: Coordinator only.");
        }
    } else {
        // Only redirect logged in users to their dashboard if they visit the root '/' URL.
        // This allows them to access /login and /register directly to switch accounts.
        if ($uri === '/' && isset($_SESSION['user_id'])) {
            $dashboardPath = '/' . $_SESSION['role'] . '/dashboard';
            redirect($dashboardPath);
        }
    }

    // Instantiate and execute
    $controller = new $controllerClass();
    $controller->$method();
} else {
    // 404 page
    http_response_code(404);
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>404 Not Found - FYP Management System</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <style>
            body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Segoe UI', sans-serif; }
            .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
        </style>
    </head>
    <body>
        <div class='card p-5 text-center' style='max-width: 480px;'>
            <h1 class='display-1 text-primary fw-bold'>404</h1>
            <h4 class='text-dark mb-3'>Page Not Found</h4>
            <p class='text-muted mb-4'>The requested page could not be found on the server. Please verify the URL or return to the dashboard.</p>
            <a href='" . (isset($_SESSION['role']) ? '/'.$_SESSION['role'].'/dashboard' : '/login') . "' class='btn btn-primary rounded-pill px-4'>Back to Home</a>
        </div>
    </body>
    </html>";
}
