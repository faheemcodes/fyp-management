<?php
namespace Controller;

class PublicController extends BaseController {
    
    public function landing() {
        try {
            $cacheFile = __DIR__ . '/../../sessions/landing_cache.json';
            $cacheTtl = 300; // 5 minutes cache
            
            if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
                $cacheData = json_decode(file_get_contents($cacheFile), true);
                if ($cacheData && isset($cacheData['stats'])) {
                    $this->render('landing', [
                        'pageTitle' => 'FYP Portal - Faculty of Engineering & Technology, University of Sindh',
                        'stats' => $cacheData['stats'],
                        'notices' => $cacheData['notices'],
                        'deadlines' => $cacheData['deadlines']
                    ]);
                    return;
                }
            }

            // Live Stats Strip
            $stats = [];
            
            // Departments (The Faculty has exactly 5 fixed departments)
            $stats['departments'] = 5;
            
            // Faculty (approved users with faculty roles)
            $stats['faculty'] = $this->db->query("
                SELECT COUNT(*) FROM users WHERE role IN ('hod', 'supervisor', 'committee') AND status = 'approved'
            ")->fetchColumn();
            
            // Projects (total projects)
            $stats['projects'] = $this->db->query("
                SELECT COUNT(*) FROM projects
            ")->fetchColumn();
            
            // Students (approved student users)
            $stats['students'] = $this->db->query("
                SELECT COUNT(*) FROM users WHERE role = 'student' AND status = 'approved'
            ")->fetchColumn();
            
            // Notice Board Preview
            $notices = $this->db->query("
                SELECT n.id, n.subject, n.body, n.target_audience, n.department, n.notice_date, n.ref_no,
                       c.name AS coord_name, h.name AS hod_name
                FROM notices n
                LEFT JOIN coordinators c ON n.sender_id = c.user_id
                LEFT JOIN hods h ON c.department = h.department
                WHERE n.is_public = 1 AND n.is_hidden = 0
                ORDER BY n.notice_date DESC 
                LIMIT 4
            ")->fetchAll();

            // Upcoming Deadlines
            $deadlines = $this->db->query("
                SELECT stage, deadline_date, status, department 
                FROM deadlines 
                WHERE deadline_date >= CURRENT_DATE AND status = 'Active' 
                ORDER BY deadline_date ASC 
                LIMIT 5
            ")->fetchAll();

            // Save to cache
            file_put_contents($cacheFile, json_encode([
                'stats' => $stats,
                'notices' => $notices,
                'deadlines' => $deadlines
            ]));

            $this->render('landing', [
                'pageTitle' => 'FYP Portal - Faculty of Engineering & Technology, University of Sindh',
                'stats' => $stats,
                'notices' => $notices,
                'deadlines' => $deadlines
            ]);
        } catch (\PDOException $e) {
            // Fallback for live data unavailability
            $this->render('landing', [
                'pageTitle' => 'FYP Portal - Faculty of Engineering & Technology, University of Sindh',
                'stats' => ['departments' => 'N/A', 'faculty' => 'N/A', 'projects' => 'N/A', 'students' => 'N/A'],
                'notices' => [],
                'deadlines' => [],
                'error' => 'Live data temporarily unavailable.'
            ]);
        }
    }
    
    public function contact() {
        try {
            $coordinators = $this->db->query("
                SELECT c.name, c.department, u.email 
                FROM coordinators c
                JOIN users u ON c.user_id = u.id
                WHERE u.status = 'approved'
                ORDER BY c.department ASC
            ")->fetchAll();
        } catch (\Exception $e) {
            $coordinators = [];
        }

        $this->render('contact', [
            'pageTitle' => 'Contact Us - FYP Management Portal',
            'coordinators' => $coordinators
        ]);
    }
    
    public function faculty() {
        try {
            $supervisors = $this->db->query("
                SELECT s.name, s.department, s.designation, u.email, p.prefix, p.surname
                FROM supervisors s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN profiles p ON s.user_id = p.user_id
                WHERE u.status = 'approved' 
                ORDER BY FIELD(s.designation, 'Professor', 'Associate Professor', 'Assistant Professor', 'Lecturer', 'Lab Engineer'), s.name ASC
            ")->fetchAll();
            
            // Fetch HODs
            $hods = $this->db->query("
                SELECT h.name, u.email, h.department, p.prefix, p.surname
                FROM hods h 
                JOIN users u ON h.user_id = u.id
                LEFT JOIN profiles p ON h.user_id = p.user_id
                WHERE u.status = 'approved'
                ORDER BY h.department ASC
            ")->fetchAll();

            // Fetch Coordinators
            $coordinators = $this->db->query("
                SELECT c.name, u.email, c.department, p.prefix, p.surname
                FROM coordinators c 
                JOIN users u ON c.user_id = u.id
                LEFT JOIN profiles p ON c.user_id = p.user_id
                WHERE u.status = 'approved'
                ORDER BY c.department ASC
            ")->fetchAll();

            // Fetch Committee members
            $committee = $this->db->query("
                SELECT c.name, c.department, u.email, p.prefix, p.surname
                FROM committees c
                JOIN users u ON c.user_id = u.id
                LEFT JOIN profiles p ON c.user_id = p.user_id
                WHERE u.status = 'approved'
                ORDER BY c.department ASC, c.name ASC
            ")->fetchAll();

            $this->render('faculty', [
                'pageTitle' => 'Faculty & Staff - FYP Management Portal',
                'supervisors' => $supervisors,
                'hods' => $hods,
                'coordinators' => $coordinators,
                'committee' => $committee
            ]);
        } catch (\PDOException $e) {
            $this->render('faculty', [
                'pageTitle' => 'Faculty & Staff - FYP Management Portal',
                'supervisors' => [],
                'hods' => [],
                'coordinators' => [],
                'committee' => [],
                'error' => 'Faculty data temporarily unavailable.'
            ]);
        }
    }

    public function noticeBoard() {
        try {
            $limit = 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;

            $stmt = $this->db->prepare("
                SELECT n.subject, n.body, n.notice_date, n.ref_no, u.role, n.department
                FROM notices n 
                JOIN users u ON n.sender_id = u.id
                WHERE n.is_public = 1 AND n.is_hidden = 0
                ORDER BY n.notice_date DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();
            $notices = $stmt->fetchAll();

            // Total count for pagination
            $totalNotices = $this->db->query("SELECT COUNT(*) FROM notices WHERE is_public = 1 AND is_hidden = 0")->fetchColumn();
            $totalPages = ceil($totalNotices / $limit);

            $this->render('public/notice-board', [
                'pageTitle' => 'Notice Board - FYP Management Portal',
                'notices' => $notices,
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]);
        } catch (\PDOException $e) {
            $this->render('public/notice-board', [
                'pageTitle' => 'Notice Board - FYP Management Portal',
                'notices' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'error' => 'Notice board is temporarily unavailable.'
            ]);
        }
    }

    public function contactSubmit() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
            return;
        }

        // Validate CSRF
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $query_type = trim($_POST['query_type'] ?? '');
        $messageText = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($department) || empty($query_type) || empty($messageText)) {
            echo json_encode(['success' => false, 'error' => 'All required fields must be filled.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Invalid email address format.']);
            return;
        }

        $mailConfigPath = __DIR__ . '/../../config/mail.php';
        if (!file_exists($mailConfigPath)) {
            echo json_encode(['success' => false, 'error' => 'Mail configuration not found.']);
            return;
        }

        $mailConfig = require $mailConfigPath;

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $mailConfig['smtp_host'];
            $mail->SMTPAuth   = $mailConfig['smtp_auth'];
            $mail->Username   = $mailConfig['smtp_username'];
            $mail->Password   = $mailConfig['smtp_password'];
            $mail->SMTPSecure = $mailConfig['smtp_secure'] === 'tls' ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $mailConfig['smtp_port'];

            // Recipients
            $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
            // The destination for contact form submissions:
            $mail->addAddress('fet.usindh@gmail.com', 'FYP Support Team');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Support Query: {$query_type} - {$name}";
            
            $htmlBody = "
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></p>
                <p><strong>Department:</strong> " . htmlspecialchars($department) . "</p>
                <p><strong>Query Type:</strong> " . htmlspecialchars($query_type) . "</p>
                <hr>
                <h3>Message:</h3>
                <p>" . nl2br(htmlspecialchars($messageText)) . "</p>
            ";
            $mail->Body = $htmlBody;
            $mail->AltBody = strip_tags(str_replace('<br>', "\n", $htmlBody));

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    }
}
