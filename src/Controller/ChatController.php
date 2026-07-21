<?php
namespace Controller;

class ChatController extends BaseController {
    
    public function uploadFile() {
        header('Content-Type: application/json');
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error occurred.']);
            exit;
        }

        $file = $_FILES['file'];
        
        // Define allowed mime types
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' // pptx
        ];

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid file type. Allowed types: Images, PDF, Word, Excel, PowerPoint.']);
            exit;
        }

        // Validate file size (e.g., max 10MB)
        $maxSize = 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'File size exceeds the 10MB limit.']);
            exit;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../public/uploads/chat_files/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate a safe unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        // Fallback extension if pathinfo fails
        if (empty($extension)) {
            $mimeMap = [
                'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp',
                'application/pdf' => 'pdf', 'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'application/vnd.ms-excel' => 'xls', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                'application/vnd.ms-powerpoint' => 'ppt', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx'
            ];
            $extension = $mimeMap[$mimeType] ?? 'bin';
        }

        $filename = uniqid('chat_') . '_' . time() . '.' . strtolower($extension);
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Get base URL for returning the correct path
            $scriptName = $_SERVER['SCRIPT_NAME'];
            $baseDir = dirname($scriptName);
            if ($baseDir === '\\' || $baseDir === '/') {
                $baseDir = '';
            }
            
            $fileUrl = $baseDir . '/uploads/chat_files/' . $filename;
            
            echo json_encode([
                'success' => true,
                'fileUrl' => $fileUrl,
                'fileName' => $file['name'],
                'fileType' => $mimeType
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to save uploaded file.']);
        }
        exit;
    }

    public function notifyRecipient() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $recipientId = $input['recipient_id'] ?? null;
        $chatId = $input['chat_id'] ?? null;
        $messagePreview = $input['message_preview'] ?? 'New message received.';
        $senderId = $_SESSION['user_id'];

        if (!$recipientId || !$chatId) {
            echo json_encode(['success' => false, 'error' => 'Missing recipient or chat info']);
            exit;
        }

        $db = \Database::getInstance()->getConnection();
        
        // Check if an email was sent for this chat in the last 15 minutes to this recipient
        $stmt = $db->prepare("SELECT created_at FROM notifications WHERE user_id = ? AND redirect_url LIKE ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$recipientId, "%chat%"]);
        $lastNotification = $stmt->fetchColumn();

        $shouldSendEmail = true;
        if ($lastNotification) {
            $lastTime = strtotime($lastNotification);
            if (time() - $lastTime < 900) { // 15 minutes throttle
                $shouldSendEmail = false;
            }
        }

        // Insert web notification
        $senderName = $_SESSION['user_name'] ?? 'Someone'; 
        
        // Attempt to fetch sender name if not in session
        if (empty($_SESSION['user_name'])) {
            $s = $db->prepare("SELECT COALESCE(students.name, supervisors.name, users.email) as name FROM users LEFT JOIN students ON students.user_id = users.id LEFT JOIN supervisors ON supervisors.user_id = users.id WHERE users.id = ?");
            $s->execute([$senderId]);
            $res = $s->fetch(\PDO::FETCH_ASSOC);
            if ($res && !empty($res['name'])) $senderName = $res['name'];
        }

        $title = "New Message from " . $senderName;
        $redirectUrl = ($_SESSION['role'] === 'student') ? '/supervisor/chat' : '/student/chat'; // recipient's route

        $stmt = $db->prepare("INSERT INTO notifications (user_id, title, message, redirect_url, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$recipientId, $title, substr($messagePreview, 0, 100), $redirectUrl, date('Y-m-d H:i:s')]);

        if ($shouldSendEmail) {
            // Fetch recipient email
            $stmt = $db->prepare("SELECT users.email, COALESCE(students.name, supervisors.name, users.email) as name FROM users LEFT JOIN students ON students.user_id = users.id LEFT JOIN supervisors ON supervisors.user_id = users.id WHERE users.id = ?");
            $stmt->execute([$recipientId]);
            $recipientUser = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($recipientUser && $recipientUser['email']) {
                $recipientName = $recipientUser['name'] ?? 'User';
                
                try {
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.mailtrap.io';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = '215a77f0a6cdcb';
                    $mail->Password   = '16db043e0e7a2b';
                    $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 2525;
                    $mail->setFrom('noreply@fypmanagement.com', 'FYP Management System');
                    $mail->addAddress($recipientUser['email'], $recipientName);
                    $mail->isHTML(true);
                    $mail->Subject = 'New Chat Message Received';
                    
                    $appUrl = 'http://' . $_SERVER['HTTP_HOST'];
                    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
                    if ($basePath === '/') $basePath = '';
                    $fullUrl = $appUrl . $basePath . $redirectUrl;

                    $mail->Body = "
                        <h3>Hello $recipientName,</h3>
                        <p>You have received a new message from <strong>$senderName</strong>.</p>
                        <p><em>\"" . htmlspecialchars(substr($messagePreview, 0, 100)) . "...\"</em></p>
                        <br>
                        <a href='$fullUrl' style='display:inline-block;padding:10px 20px;background:#4f7cf7;color:#fff;text-decoration:none;border-radius:5px;'>View Message</a>
                        <br><br>
                        <small>You won't receive another email for this chat thread for 15 minutes to prevent spam.</small>
                    ";
                    
                    $mail->send();
                } catch (\Exception $e) {
                    // Fail silently for email to not break chat API
                }
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }
}
