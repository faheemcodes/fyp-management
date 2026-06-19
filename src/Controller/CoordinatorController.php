<?php
namespace Controller;

class CoordinatorController extends BaseController {

    private function getCoordinatorDept($db, $userId) {
        $stmt = $db->prepare("SELECT department FROM coordinators WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    private function getCoordinatorName($db, $userId) {
        $stmt = $db->prepare("SELECT name FROM coordinators WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    private function getHodNameForDept($db, $dept) {
        // Query the HOD for the department
        $stmt = $db->prepare("SELECT name FROM hods WHERE department LIKE ? OR ? LIKE CONCAT('%', department, '%') LIMIT 1");
        $stmt->execute(['%' . $dept . '%', $dept]);
        return $stmt->fetchColumn() ?: 'HOD';
    }

    public function dashboard() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getCoordinatorDept($db, $userId);
        
        $stats = [];
        // Pending student approvals in department
        $stmt = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ?");
        $stmt->execute([$dept]);
        $stats['pending_approvals'] = $stmt->fetchColumn();
        
        // Active students in department
        $stmt = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'approved' AND s.department = ?");
        $stmt->execute([$dept]);
        $stats['total_students'] = $stmt->fetchColumn();

        // Notices generated
        $stmt = $db->prepare("SELECT COUNT(*) FROM notices WHERE sender_id = ?");
        $stmt->execute([$userId]);
        $stats['total_notices'] = $stmt->fetchColumn();

        // Fetch recent notices
        $stmt = $db->prepare("SELECT * FROM notices WHERE sender_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$userId]);
        $recentNotices = $stmt->fetchAll();

        $this->render('coordinator/dashboard', [
            'stats' => $stats,
            'recentNotices' => $recentNotices,
            'department' => $dept
        ]);
    }

    public function verifyStudents() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

        $stmt = $db->prepare("SELECT s.*, u.email, u.status FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ? ORDER BY u.created_at DESC");
        $stmt->execute([$dept]);
        $students = $stmt->fetchAll();

        $this->render('coordinator/verify_students', [
            'students' => $students
        ]);
    }

    public function approveStudent() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

            // Check student department
            $stmtCheck = $db->prepare("SELECT department FROM students WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            $studentDept = $stmtCheck->fetchColumn();

            if ($studentDept === $dept) {
                $stmt = $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
                $stmt->execute([$id]);

                $this->addNotification($id, 'Account Approved', 'Your registration has been approved! You can now log in.');
                $this->flash('success', 'Student account approved successfully.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/coordinator/users');
    }

    public function rejectStudent() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getCoordinatorDept($db, $_SESSION['user_id'] ?? 0);

            // Check student department
            $stmtCheck = $db->prepare("SELECT department, avatar FROM students WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            $student = $stmtCheck->fetch();

            if ($student && $student['department'] === $dept) {
                $avatarFile = $student['avatar'];
                if ($avatarFile && $avatarFile !== 'default_avatar.svg' && $avatarFile !== 'default_avatar.png') {
                    $filePath = __DIR__ . '/../../public/uploads/avatars/' . $avatarFile;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Student registration rejected and deleted.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/coordinator/users');
    }

    public function notice() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        
        $stmt = $db->prepare("SELECT * FROM notices WHERE sender_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $notices = $stmt->fetchAll();

        $this->render('coordinator/notice', [
            'notices' => $notices
        ]);
    }

    public function createNotice() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $body = trim($_POST['body'] ?? '');
            $notice_date = $_POST['notice_date'] ?? date('Y-m-d');
            $ref_no = trim($_POST['ref_no'] ?? '');
            $target_audience = $_POST['target_audience'] ?? 'all';

            if (empty($subject) || empty($body) || empty($notice_date)) {
                $this->flash('error', 'Subject, Date and Body are required.');
                redirect('/coordinator/notice');
            }

            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            $dept = $this->getCoordinatorDept($db, $userId);

            try {
                $db->beginTransaction();

                $stmt = $db->prepare("INSERT INTO notices (sender_id, subject, body, notice_date, ref_no, target_audience) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $subject, $body, $notice_date, $ref_no ?: null, $target_audience]);
                $noticeId = $db->lastInsertId();

                // Send notifications to target audience in the department
                $recipients = [];
                if ($target_audience === 'students' || $target_audience === 'all') {
                    $stmtStudents = $db->prepare("SELECT user_id FROM students WHERE department = ?");
                    $stmtStudents->execute([$dept]);
                    $recipients = array_merge($recipients, $stmtStudents->fetchAll(\PDO::FETCH_COLUMN));
                }
                if ($target_audience === 'supervisors' || $target_audience === 'all') {
                    $stmtSups = $db->prepare("SELECT user_id FROM supervisors WHERE department = ?");
                    $stmtSups->execute([$dept]);
                    $recipients = array_merge($recipients, $stmtSups->fetchAll(\PDO::FETCH_COLUMN));
                }

                // Add HOD to notifications
                $stmtHod = $db->prepare("SELECT user_id FROM hods WHERE department LIKE ? OR ? LIKE CONCAT('%', department, '%')");
                $stmtHod->execute(['%' . $dept . '%', $dept]);
                $hodIds = $stmtHod->fetchAll(\PDO::FETCH_COLUMN);
                $recipients = array_merge($recipients, $hodIds);

                $recipients = array_unique($recipients);
                
                foreach ($recipients as $recId) {
                    $this->addNotification($recId, 'New Department Notice', "Notice: $subject. Click to view.", '/notice/view?id=' . $noticeId);
                }

                $db->commit();
                $this->flash('success', 'Notice generated and broadcasted successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error generating notice: ' . $e->getMessage());
            }
        }
        redirect('/coordinator/notice');
    }

    public function viewNotice() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("Notice ID is required.");
        }

        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM notices WHERE id = ?");
        $stmt->execute([$id]);
        $notice = $stmt->fetch();

        if (!$notice) {
            die("Notice not found.");
        }

        // Get Coordinator and HOD signatures
        $coordName = $this->getCoordinatorName($db, $notice['sender_id']);
        $coordDept = $this->getCoordinatorDept($db, $notice['sender_id']);
        $hodName = $this->getHodNameForDept($db, $coordDept);

        // Standalone clean view for letters
        $this->render('coordinator/view_notice', [
            'notice' => $notice,
            'coordName' => $coordName,
            'coordDept' => $coordDept,
            'hodName' => $hodName
        ]);
    }

    public function deleteNotice() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'] ?? 0;
            
            $stmt = $db->prepare("DELETE FROM notices WHERE id = ? AND sender_id = ?");
            $stmt->execute([$id, $userId]);
            $this->flash('success', 'Notice deleted successfully.');
        }
        redirect('/coordinator/notice');
    }
}
