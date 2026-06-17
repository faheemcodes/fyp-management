<?php
session_start();

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

    // Dean routes
    '/dean/dashboard' => ['Controller\DeanController', 'dashboard'],
    '/dean/supervisors' => ['Controller\DeanController', 'supervisors'],
    '/dean/supervisors/create' => ['Controller\DeanController', 'createSupervisor'],
    '/dean/supervisors/edit' => ['Controller\DeanController', 'editSupervisor'],
    '/dean/supervisors/delete' => ['Controller\DeanController', 'deleteSupervisor'],
    '/dean/committee' => ['Controller\DeanController', 'committee'],
    '/dean/committee/create' => ['Controller\DeanController', 'createCommittee'],
    '/dean/committee/edit' => ['Controller\DeanController', 'editCommittee'],
    '/dean/committee/delete' => ['Controller\DeanController', 'deleteCommittee'],
    
    // Student routes
    '/student/dashboard' => ['Controller\StudentController', 'dashboard'],
    '/student/profile' => ['Controller\StudentController', 'profile'],
    '/student/group' => ['Controller\StudentController', 'group'],
    '/student/group/create' => ['Controller\StudentController', 'createGroup'],
    '/student/group/add-member' => ['Controller\StudentController', 'addMember'],
    '/student/group/update-members' => ['Controller\StudentController', 'updateMembers'],
    '/student/proposal' => ['Controller\StudentController', 'proposal'],
    '/student/proposal/submit' => ['Controller\StudentController', 'submitProposal'],
    '/student/documents' => ['Controller\StudentController', 'documents'],
    '/student/documents/upload' => ['Controller\StudentController', 'uploadDocument'],
    '/student/grade' => ['Controller\StudentController', 'grade'],
    
    // Supervisor routes
    '/supervisor/dashboard' => ['Controller\SupervisorController', 'dashboard'],
    '/supervisor/profile' => ['Controller\SupervisorController', 'profile'],
    '/supervisor/groups' => ['Controller\SupervisorController', 'groups'],
    '/supervisor/groups/grade' => ['Controller\SupervisorController', 'gradeGroup'],
    '/supervisor/reviews' => ['Controller\SupervisorController', 'reviews'],
    '/supervisor/proposal/action' => ['Controller\SupervisorController', 'proposalAction'],
    '/supervisor/document/feedback' => ['Controller\SupervisorController', 'documentFeedback'],

    // Committee routes
    '/committee/dashboard' => ['Controller\CommitteeController', 'dashboard'],
    '/committee/evaluations' => ['Controller\CommitteeController', 'evaluations'],
    '/committee/evaluations/schedule' => ['Controller\CommitteeController', 'scheduleEvaluation'],
    '/committee/evaluations/grade' => ['Controller\CommitteeController', 'gradeEvaluation'],
    
    // Notifications API
    '/api/notifications' => ['Controller\AuthController', 'fetchNotifications'],
    '/api/notifications/read' => ['Controller\AuthController', 'markNotificationRead']
];

// Dispatch route
if (array_key_exists($uri, $routes)) {
    list($controllerClass, $method) = $routes[$uri];
    
    // Check login requirements (simple session validation)
    $authRoutes = ['/', '/login', '/register', '/forgot-password'];
    if (!in_array($uri, $authRoutes)) {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        
        // Role based access check
        $role = $_SESSION['role'];
        if (strpos($uri, '/admin') === 0 && $role !== 'admin') {
            die("Unauthorized access: Admin only.");
        }
        if (strpos($uri, '/dean') === 0 && $role !== 'dean') {
            die("Unauthorized access: Dean only.");
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
