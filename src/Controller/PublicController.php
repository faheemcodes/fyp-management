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
                SELECT id, subject, body, target_audience, department, notice_date 
                FROM notices 
                WHERE is_public = 1
                ORDER BY notice_date DESC 
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
    
    public function about() {
        $this->render('about', [
            'pageTitle' => 'About - FYP Management Portal'
        ]);
    }
    
    public function contact() {
        $this->render('contact', [
            'pageTitle' => 'Contact Us - FYP Management Portal'
        ]);
    }
    
    public function faculty() {
        try {
            $supervisors = $this->db->query("
                SELECT s.name, s.department, s.designation, s.research_interest, u.email 
                FROM supervisors s 
                JOIN users u ON s.user_id = u.id 
                WHERE u.status = 'approved' 
                ORDER BY FIELD(s.designation, 'Professor', 'Associate Professor', 'Assistant Professor', 'Lecturer', 'Lab Engineer'), s.name ASC
            ")->fetchAll();
            
            // Fetch HODs
            $hods = $this->db->query("
                SELECT h.name, u.email, h.department
                FROM hods h 
                JOIN users u ON h.user_id = u.id
                WHERE u.status = 'approved'
                ORDER BY h.department ASC
            ")->fetchAll();

            // Fetch Committee members
            $committee = $this->db->query("
                SELECT c.name, c.department, u.email
                FROM committees c
                JOIN users u ON c.user_id = u.id
                WHERE u.status = 'approved'
                ORDER BY c.department ASC, c.name ASC
            ")->fetchAll();

            $this->render('faculty', [
                'pageTitle' => 'Faculty & Staff - FYP Management Portal',
                'supervisors' => $supervisors,
                'hods' => $hods,
                'committee' => $committee
            ]);
        } catch (\PDOException $e) {
            $this->render('faculty', [
                'pageTitle' => 'Faculty & Staff - FYP Management Portal',
                'supervisors' => [],
                'hods' => [],
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
                WHERE n.is_public = 1
                ORDER BY n.notice_date DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();
            $notices = $stmt->fetchAll();

            // Total count for pagination
            $totalNotices = $this->db->query("SELECT COUNT(*) FROM notices WHERE is_public = 1")->fetchColumn();
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
}
