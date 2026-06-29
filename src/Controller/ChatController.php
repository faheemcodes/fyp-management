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
}
