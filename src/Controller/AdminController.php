<?php
namespace Controller;

class AdminController extends BaseController {

    public function dashboard() {
        $db = \Database::getInstance()->getConnection();

        // Analytics query
        $stats = [];
        $stats['total_users'] = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['students'] = $db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
        $stats['supervisors'] = $db->query("SELECT COUNT(*) FROM users WHERE role = 'supervisor'")->fetchColumn();
        $stats['committee'] = $db->query("SELECT COUNT(*) FROM users WHERE role = 'committee'")->fetchColumn();
        
        $stats['active_projects'] = $db->query("SELECT COUNT(*) FROM projects WHERE status = 'Approved'")->fetchColumn();
        $stats['completed_projects'] = $db->query("SELECT COUNT(*) FROM groups WHERE progress_stage = 'Final Grading Completed'")->fetchColumn();
        
        // Pending evaluations (evaluations scheduled but not graded yet, or groups ready for evaluation)
        $stats['pending_evaluations'] = $db->query("SELECT COUNT(*) FROM groups WHERE progress_stage IN ('Proposal Approved', 'Proposal Defence Presentation Completed', 'FYP Progress Presentation Completed')")->fetchColumn();

        // Average marks calculation
        $avgMarks = $db->query("SELECT AVG(total_marks) FROM grades WHERE total_marks > 0")->fetchColumn();
        $stats['avg_marks'] = $avgMarks ? round($avgMarks, 2) . '%' : 'N/A';

        // Recent users and groups
        $recentUsers = $db->query("SELECT u.*, 
            COALESCE(s.name, sup.name, c.name, d.name, 'Admin') as name 
            FROM users u
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN supervisors sup ON u.id = sup.user_id
            LEFT JOIN committees c ON u.id = c.user_id
            LEFT JOIN deans d ON u.id = d.user_id
            ORDER BY u.created_at DESC LIMIT 5")->fetchAll();

        $recentGroups = $db->query("SELECT g.*, p.title as project_title, s.name as creator_name 
            FROM groups g
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN students s ON g.created_by = s.user_id
            ORDER BY g.created_at DESC LIMIT 5")->fetchAll();

        // Fetch all supervisors and their approved slots count
        $supervisorsList = $db->query("
            SELECT s.user_id, s.name, s.department, 
            (SELECT COUNT(*) FROM projects p WHERE p.supervisor_id = s.user_id AND p.status = 'Approved') as current_slots
            FROM supervisors s
            ORDER BY s.name ASC
        ")->fetchAll();

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentGroups' => $recentGroups,
            'supervisorsList' => $supervisorsList
        ]);
    }

    public function users() {
        $db = \Database::getInstance()->getConnection();
        
        // Fetch all users with details
        $users = $db->query("SELECT u.*, 
            COALESCE(s.name, sup.name, c.name, d.name, 'Administrator') as name,
            s.student_id,
            s.avatar,
            sup.designation,
            COALESCE(s.department, sup.department, c.department, d.department, 'N/A') as department
            FROM users u
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN supervisors sup ON u.id = sup.user_id
            LEFT JOIN committees c ON u.id = c.user_id
            LEFT JOIN deans d ON u.id = d.user_id
            ORDER BY u.status DESC, u.created_at DESC")->fetchAll();

        $this->render('admin/users', [
            'users' => $users
        ]);
    }

    public function approveUser() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
            $stmt->execute([$id]);
            
            // Get user's details for notification
            $stmtUser = $db->prepare("SELECT email, role FROM users WHERE id = ?");
            $stmtUser->execute([$id]);
            $user = $stmtUser->fetch();
            
            if ($user) {
                $this->addNotification($id, 'Account Approved', 'Your registration has been approved! You can now log in.');
            }
            
            $this->flash('success', 'User account approved successfully.');
        }
        redirect('/admin/users');
    }

    public function rejectUser() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$id]);
            $this->flash('success', 'User account rejected.');
        }
        redirect('/admin/users');
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $department = trim($_POST['department'] ?? '');
            
            // Specifics
            $designation = trim($_POST['designation'] ?? '');
            $student_id = trim($_POST['student_id'] ?? '');
            $research_interest = trim($_POST['research_interest'] ?? '');
            
            if (empty($email) || empty($password) || empty($role) || empty($name)) {
                $this->flash('error', 'All core fields are required.');
                redirect('/admin/users');
            }
            
            $db = \Database::getInstance()->getConnection();
            
            // Check email
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->flash('error', 'Email already registered.');
                redirect('/admin/users');
            }
            
            try {
                $db->beginTransaction();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (email, password, role, status) VALUES (?, ?, ?, 'approved')");
                $stmt->execute([$email, $hashed, $role]);
                $userId = $db->lastInsertId();
                
                if ($role === 'student') {
                    $stmt = $db->prepare("INSERT INTO students (user_id, student_id, name, department) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$userId, $student_id, $name, $department]);
                } else if ($role === 'supervisor') {
                    $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department, research_interest) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $name, $designation, $department, $research_interest]);
                } else if ($role === 'committee') {
                    $stmt = $db->prepare("INSERT INTO committees (user_id, name, department) VALUES (?, ?, ?)");
                    $stmt->execute([$userId, $name, $department]);
                } else if ($role === 'dean') {
                    $stmt = $db->prepare("INSERT INTO deans (user_id, name, department) VALUES (?, ?, ?)");
                    $stmt->execute([$userId, $name, $department]);
                }
                
                $db->commit();
                $this->flash('success', "User $name ($role) created successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error creating user: ' . $e->getMessage());
            }
        }
        redirect('/admin/users');
    }

    public function groups() {
        $db = \Database::getInstance()->getConnection();
        
        // Fetch all groups with their members, projects, and supervisors
        $groups = $db->query("SELECT g.*, p.title as project_title, p.description as project_description, p.status as project_status,
            sup.name as supervisor_name, sup.user_id as supervisor_id,
            creator.name as creator_name
            FROM groups g
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN students creator ON g.created_by = creator.user_id
            ORDER BY g.created_at DESC")->fetchAll();

        // Get list of group members for each group
        foreach ($groups as &$group) {
            $stmt = $db->prepare("SELECT s.name, s.student_id, s.department FROM group_members gm
                JOIN students s ON gm.student_id = s.user_id
                WHERE gm.group_id = ?");
            $stmt->execute([$group['id']]);
            $group['members'] = $stmt->fetchAll();
        }
        
        // Fetch all supervisors to populate assignment dropdowns
        $supervisors = $db->query("SELECT user_id, name FROM supervisors ORDER BY name ASC")->fetchAll();

        $this->render('admin/groups', [
            'groups' => $groups,
            'supervisors' => $supervisors
        ]);
    }

    public function assignSupervisor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupId = $_POST['group_id'] ?? null;
            $supervisorId = $_POST['supervisor_id'] ?? null;

            if ($groupId && $supervisorId) {
                $db = \Database::getInstance()->getConnection();
                
                // Update projects table
                $stmt = $db->prepare("UPDATE projects SET supervisor_id = ? WHERE group_id = ?");
                $stmt->execute([$supervisorId, $groupId]);
                
                // Get supervisor name
                $sStmt = $db->prepare("SELECT name FROM supervisors WHERE user_id = ?");
                $sStmt->execute([$supervisorId]);
                $supervisorName = $sStmt->fetchColumn();

                // Notify group members
                $mStmt = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                $mStmt->execute([$groupId]);
                $members = $mStmt->fetchAll();
                
                foreach ($members as $m) {
                    $this->addNotification($m['student_id'], 'Supervisor Assigned', "Dr. $supervisorName has been assigned as your supervisor.");
                }
                
                // Notify supervisor
                $this->addNotification($supervisorId, 'New Group Assigned', "You have been assigned to supervise Group #$groupId.");

                $this->flash('success', 'Supervisor assigned successfully.');
            }
        }
        redirect('/admin/groups');
    }

    public function deadlines() {
        $db = \Database::getInstance()->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stage = $_POST['stage'] ?? '';
            $date = $_POST['deadline_date'] ?? '';
            
            if ($stage && $date) {
                $stmt = $db->prepare("INSERT INTO deadlines (stage, deadline_date) VALUES (?, ?) 
                    ON DUPLICATE KEY UPDATE deadline_date = ?");
                $stmt->execute([$stage, $date, $date]);
                
                // Notify all students
                $students = $db->query("SELECT user_id FROM students")->fetchAll();
                foreach ($students as $s) {
                    $this->addNotification($s['user_id'], 'Deadline Updated', "The deadline for $stage has been updated to $date.");
                }
                
                $this->flash('success', "$stage deadline updated.");
            }
        }
        
        $deadlines = $db->query("SELECT * FROM deadlines ORDER BY id ASC")->fetchAll();
        $this->render('admin/deadlines', [
            'deadlines' => $deadlines
        ]);
    }

    public function reports() {
        $db = \Database::getInstance()->getConnection();
        
        // Fetch stats for reporting
        $progressStages = $db->query("SELECT progress_stage, COUNT(*) as count FROM groups GROUP BY progress_stage")->fetchAll();
        
        $studentGrades = $db->query("SELECT g.group_code, p.title as project_title, gr.*, s.name as supervisor_name
            FROM groups g
            JOIN projects p ON g.id = p.group_id
            LEFT JOIN grades gr ON g.id = gr.group_id
            LEFT JOIN supervisors s ON p.supervisor_id = s.user_id
            ORDER BY gr.total_marks DESC")->fetchAll();
            
        $this->render('admin/reports', [
            'progressStages' => $progressStages,
            'studentGrades' => $studentGrades
        ]);
    }
}
