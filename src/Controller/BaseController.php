<?php
namespace Controller;

class BaseController {
    // Render view
    protected function render($viewName, $data = []) {
        // Extract data to local variables
        extract($data);
        
        // Setup view file path
        $viewFile = __DIR__ . '/../View/' . $viewName . '.php';
        
        if (file_exists($viewFile)) {
            // Some views (like login/register/forgot-password) don't need header/sidebar/footer.
            $noLayoutViews = ['auth/login', 'auth/register', 'auth/forgot-password'];
            
            if (in_array($viewName, $noLayoutViews)) {
                require $viewFile;
            } else {
                // Get page notifications
                $db = \Database::getInstance()->getConnection();
                $userId = $_SESSION['user_id'] ?? null;
                $notifications = [];
                $unreadCount = 0;
                if ($userId) {
                    $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 6");
                    $stmt->execute([$userId]);
                    $notifications = $stmt->fetchAll();

                    $stmtCount = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
                    $stmtCount->execute([$userId]);
                    $unreadCount = $stmtCount->fetchColumn();
                }
                
                require __DIR__ . '/../View/layout/header.php';
                require __DIR__ . '/../View/layout/sidebar.php';
                require $viewFile;
                require __DIR__ . '/../View/layout/footer.php';
            }
        } else {
            die("View $viewName not found at $viewFile");
        }
    }
    
    // JSON response
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    // Flash message helper
    protected function flash($key, $message = null) {
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
        } else {
            $msg = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
    }

    // Add notification helper
    protected function addNotification($userId, $title, $message) {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $title, $message]);
    }
}
