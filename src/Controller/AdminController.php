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
        $stats['committee'] = $db->query("SELECT COUNT(*) FROM committees")->fetchColumn();
        
        $stats['active_projects'] = $db->query("SELECT COUNT(*) FROM projects WHERE status = 'Approved'")->fetchColumn();
        $stats['completed_projects'] = $db->query("SELECT COUNT(*) FROM `groups` WHERE progress_stage = 'Final Grading Completed'")->fetchColumn();
        
        // Pending evaluations (evaluations scheduled but not graded yet, or pending proposals)
        $pendingProposals = $db->query("SELECT COUNT(*) FROM projects WHERE status = 'Pending'")->fetchColumn();
        $pendingPresentations = $db->query("SELECT COUNT(*) FROM evaluations WHERE total_marks = 0 OR total_marks IS NULL")->fetchColumn();
        $stats['pending_evaluations'] = $pendingProposals + $pendingPresentations;

        // Average percentage calculation
        $avgMarks = $db->query("SELECT AVG(percentage) FROM grades WHERE percentage > 0")->fetchColumn();
        $stats['avg_marks'] = $avgMarks ? round($avgMarks, 2) . '%' : 'N/A';

        // Recent users and groups
        $recentUsers = $db->query("SELECT u.*, 
            COALESCE(s.name, sup.name, c.name, d.name, 'Admin') as name 
            FROM users u
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN supervisors sup ON u.id = sup.user_id
            LEFT JOIN committees c ON u.id = c.user_id
            LEFT JOIN hods d ON u.id = d.user_id
            ORDER BY u.created_at DESC LIMIT 5")->fetchAll();

        $recentGroups = $db->query("SELECT g.*, p.title as project_title, s.name as creator_name 
            FROM `groups` g
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN students s ON g.created_by = s.user_id
            ORDER BY g.created_at DESC LIMIT 5")->fetchAll();

        // Fetch all supervisors and their approved slots count
        $supervisorsList = $db->query("
            SELECT s.user_id, s.name, s.department, 
            (SELECT COUNT(*) FROM projects p JOIN `groups` g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = s.user_id AND p.status = 'Approved' AND b.is_active = 1) as current_slots
            FROM supervisors s
            ORDER BY s.name ASC LIMIT 5
        ")->fetchAll();

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentGroups' => $recentGroups,
            'supervisorsList' => $supervisorsList
        ]);
    }

    
    public function supervisorSlots() {
        $db = \Database::getInstance()->getConnection();
        $supervisorsList = $db->query("
            SELECT s.user_id, s.name, s.department, 
            (SELECT COUNT(*) FROM projects p JOIN `groups` g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = s.user_id AND p.status = 'Approved' AND b.is_active = 1) as current_slots
            FROM supervisors s
            ORDER BY s.name ASC
        ")->fetchAll();

        $this->render('admin/slots', [
            'supervisorsList' => $supervisorsList
        ]);
    }

    public function users() {
        $db = \Database::getInstance()->getConnection();
        
        // Fetch all users with details
        $users = $db->query("SELECT u.*, 
            COALESCE(s.name, sup.name, c.name, d.name, coord.name, 'Administrator') as name,
            s.student_id,
            s.avatar,
            s.shift,
            sup.designation,
            COALESCE(s.department, sup.department, c.department, d.department, coord.department, 'N/A') as department,
            prof.father_name,
            prof.dob,
            prof.mobile_code,
            prof.mobile_no,
            prof.gender,
            prof.province_state,
            prof.district,
            prof.home_address
            FROM users u
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN supervisors sup ON u.id = sup.user_id
            LEFT JOIN committees c ON u.id = c.user_id
            LEFT JOIN hods d ON u.id = d.user_id
            LEFT JOIN coordinators coord ON u.id = coord.user_id
            LEFT JOIN profiles prof ON u.id = prof.user_id
            ORDER BY CASE WHEN u.status = 'pending' THEN 1 ELSE 2 END ASC, u.created_at DESC")->fetchAll();

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
            
            // Get student avatar file to delete from disk if it exists
            $stmtAvatar = $db->prepare("SELECT avatar FROM students WHERE user_id = ?");
            $stmtAvatar->execute([$id]);
            $avatarFile = $stmtAvatar->fetchColumn();
            if ($avatarFile && $avatarFile !== 'default_avatar.svg' && $avatarFile !== 'default_avatar.png') {
                $filePath = __DIR__ . '/../../public/uploads/avatars/' . $avatarFile;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $this->flash('success', 'User registration rejected and record deleted from system.');
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
            
            // Common Profile
            $surname = trim($_POST['surname'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            
            // Student Profile Defaults (Since admin only inputs basic info now)
            $father_name = !empty($_POST['father_name']) ? trim($_POST['father_name']) : '';
            $dob = !empty($_POST['dob']) ? trim($_POST['dob']) : '2000-01-01';
            $gender = !empty($_POST['gender']) ? trim($_POST['gender']) : 'Male';
            $mobile_code = !empty($_POST['mobile_code']) ? trim($_POST['mobile_code']) : '+92';
            $mobile_no = !empty($_POST['mobile_no']) ? trim($_POST['mobile_no']) : '0000000000';
            $country = !empty($_POST['country']) ? trim($_POST['country']) : 'Pakistan';
            $province_state = !empty($_POST['province_state']) ? trim($_POST['province_state']) : '';
            $district = !empty($_POST['district']) ? trim($_POST['district']) : '';
            $home_address = !empty($_POST['home_address']) ? trim($_POST['home_address']) : 'Not Provided Yet';
            $shift = !empty($_POST['shift']) ? trim($_POST['shift']) : 'Morning';
            
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
                $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, ?, 'approved')");
                $stmt->execute([$email, (empty($cnic) ? null : $cnic), $hashed, $role]);
                $userId = $db->lastInsertId();
                
                if ($role === 'student') {
                    $stmt = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, father_name, dob, gender, mobile_code, mobile_no, country, province_state, district, home_address) VALUES (?, 'Mr.', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $surname, $cnic, $father_name, $dob, $gender, $mobile_code, $mobile_no, $country, $province_state, $district, $home_address]);
                    
                    $stmt = $db->prepare("INSERT INTO students (user_id, student_id, name, department, shift) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $student_id, $name, $department, $shift]);
                } else {
                    // For all staff
                    $prefix = ($role === 'supervisor') ? 'Dr.' : 'Mr.';
                    $stmt = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, gender, home_address) VALUES (?, ?, ?, ?, '1980-01-01', 'Male', 'Not Provided Yet')");
                    $stmt->execute([$userId, $prefix, $surname, $cnic]);
                    
                    if ($role === 'supervisor') {
                        $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$userId, $name, $designation, $department]);
                    } else if ($role === 'hod') {
                        $stmt = $db->prepare("INSERT INTO hods (user_id, name, department) VALUES (?, ?, ?)");
                        $stmt->execute([$userId, $name, $department]);
                    } else if ($role === 'coordinator') {
                        $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, department) VALUES (?, ?, ?)");
                        $stmt->execute([$userId, $name, $department]);
                    } else if ($role === 'committee') {
                        $stmt = $db->prepare("INSERT INTO committees (user_id, name, department) VALUES (?, ?, ?)");
                        $stmt->execute([$userId, $name, $department]);
                    }
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
        
        // Fetch all groups with their members, projects, supervisors, and grades
        $groups = $db->query("SELECT g.*, p.title as project_title, p.description as project_description, p.status as project_status,
            sup.name as supervisor_name, sup.user_id as supervisor_id,
            creator.name as creator_name,
            gr.proposal_defense_marks, gr.progress_presentation_marks, gr.final_presentation_marks, gr.supervision_marks, gr.total_marks, gr.percentage, gr.grade as final_grade, gr.status as pass_fail_status
            FROM `groups` g
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN students creator ON g.created_by = creator.user_id
            LEFT JOIN grades gr ON g.id = gr.group_id
            ORDER BY g.created_at DESC")->fetchAll();

        // Get list of group members for each group
        foreach ($groups as &$group) {
            $stmt = $db->prepare("SELECT s.name, s.student_id, s.department, s.user_id FROM group_members gm
                JOIN students s ON gm.student_id = s.user_id
                WHERE gm.group_id = ?");
            $stmt->execute([$group['id']]);
            $group['members'] = $stmt->fetchAll();
        }
        
        // Fetch all supervisors to populate assignment dropdowns
        $supervisors = $db->query("SELECT user_id, name FROM supervisors ORDER BY name ASC")->fetchAll();

        // Fetch all students to populate student assignment dropdowns
        $students = $db->query("SELECT user_id, name, student_id FROM students ORDER BY name ASC")->fetchAll();

        $this->render('admin/groups', [
            'groups' => $groups,
            'supervisors' => $supervisors,
            'students' => $students
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
        
        $department = $_GET['department'] ?? 'Software Engineering';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stage = $_POST['stage'] ?? '';
            $date = $_POST['deadline_date'] ?? '';
            $status = $_POST['status'] ?? 'Inactive';
            $formDepartment = $_POST['department'] ?? 'Software Engineering';
            
            if ($stage && $date) {
                $stmt = $db->prepare("INSERT INTO deadlines (stage, deadline_date, status, department) VALUES (?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE deadline_date = ?, status = ?");
                $stmt->execute([$stage, $date, $status, $formDepartment, $date, $status]);
                
                // Notify students in that department if active
                if ($status === 'Active') {
                    $stmtStudents = $db->prepare("SELECT user_id FROM students WHERE department = ?");
                    $stmtStudents->execute([$formDepartment]);
                    $students = $stmtStudents->fetchAll();
                    foreach ($students as $s) {
                        $this->addNotification($s['user_id'], 'Deadline Updated', "The deadline for $stage has been updated to $date.");
                    }
                }
                
                $this->flash('success', "$stage deadline updated for $formDepartment.");
                redirect('/admin/deadlines?department=' . urlencode($formDepartment));
            }
        }
        
        $stmt = $db->prepare("SELECT * FROM deadlines WHERE department = ? ORDER BY id ASC");
        $stmt->execute([$department]);
        $deadlines = $stmt->fetchAll();
        $this->render('admin/deadlines', [
            'deadlines' => $deadlines,
            'department' => $department
        ]);
    }

    public function reports() {
        $db = \Database::getInstance()->getConnection();
        
        // Fetch stats for reporting
        $progressStages = $db->query("SELECT progress_stage, COUNT(*) as count FROM `groups` GROUP BY progress_stage")->fetchAll();
        
        $studentGrades = $db->query("SELECT g.group_code, p.title as project_title, gr.*, s.name as supervisor_name, st.name as student_name, st.student_id as roll_no
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN grades gr ON g.id = gr.group_id
            JOIN students st ON gr.student_id = st.user_id
            LEFT JOIN supervisors s ON p.supervisor_id = s.user_id
            ORDER BY g.group_code ASC, gr.total_marks DESC")->fetchAll();
            
        $this->render('admin/reports', [
            'progressStages' => $progressStages,
            'studentGrades' => $studentGrades
        ]);
    }

    public function editUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $role = $_POST['role'] ?? '';
            $department = trim($_POST['department'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // role specific
            $student_id = trim($_POST['student_id'] ?? '');
            $shift = $_POST['shift'] ?? 'Morning';
            $designation = $_POST['designation'] ?? '';
            $research_interest = trim($_POST['research_interest'] ?? '');
            
            if (!$id || empty($name) || empty($email) || empty($role)) {
                $this->flash('error', 'Required fields are missing.');
                redirect('/admin/users');
            }

            // Remove dashes from CNIC if present
            $cnic = str_replace('-', '', $cnic);

            $db = \Database::getInstance()->getConnection();
            
            try {
                $db->beginTransaction();
                
                // Update users table
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ?, password = ? WHERE id = ?");
                    $stmt->execute([$email, $cnic, $hashed, $id]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ? WHERE id = ?");
                    $stmt->execute([$email, $cnic, $id]);
                }
                
                // Update role-specific table and profiles
                if ($role === 'student') {
                    $stmt = $db->prepare("INSERT INTO students (user_id, student_id, name, department, shift) VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE student_id = ?, name = ?, department = ?, shift = ?");
                    $stmt->execute([$id, $student_id, $name, $department, $shift, $student_id, $name, $department, $shift]);
                } else if ($role === 'supervisor') {
                    $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department, research_interest) VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE name = ?, designation = ?, department = ?, research_interest = ?");
                    $stmt->execute([$id, $name, $designation, $department, $research_interest, $name, $designation, $department, $research_interest]);
                } else if ($role === 'hod') {
                    $stmt = $db->prepare("INSERT INTO hods (user_id, name, department) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE name = ?, department = ?");
                    $stmt->execute([$id, $name, $department, $name, $department]);
                } else if ($role === 'coordinator') {
                    $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, department) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE name = ?, department = ?");
                    $stmt->execute([$id, $name, $department, $name, $department]);
                }
                
                // Keep profiles table in sync
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '2000-01-01', '+92', '0000000', 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE cnic = ?, surname = ?");
                $stmtP->execute([$id, $name, $cnic, $cnic, $name]);
                
                $db->commit();
                $this->flash('success', "User account updated successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating user: ' . $e->getMessage());
            }
        }
        redirect('/admin/users');
    }

    public function deleteUser() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                
                // Get student avatar file to delete from disk if it exists
                $stmtAvatar = $db->prepare("SELECT avatar FROM students WHERE user_id = ?");
                $stmtAvatar->execute([$id]);
                $avatarFile = $stmtAvatar->fetchColumn();
                if ($avatarFile && $avatarFile !== 'default_avatar.svg' && $avatarFile !== 'default_avatar.png') {
                    $filePath = __DIR__ . '/../../public/uploads/avatars/' . $avatarFile;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                
                $db->commit();
                $this->flash('success', 'User account deleted successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error deleting user: ' . $e->getMessage());
            }
        }
        redirect('/admin/users');
    }

    public function createGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $group_code = trim($_POST['group_code'] ?? '');
            $created_by = $_POST['created_by'] ?? null;
            $progress_stage = $_POST['progress_stage'] ?? 'Group Created';
            
            if (empty($created_by)) {
                $this->flash('error', 'A Group Leader (student) is required.');
                redirect('/admin/groups');
            }
            
            $db = \Database::getInstance()->getConnection();
            
            // Check if leader already belongs to a group
            $stmtC = $db->prepare("SELECT group_id FROM group_members WHERE student_id = ?");
            $stmtC->execute([$created_by]);
            if ($stmtC->fetchColumn()) {
                $this->flash('error', 'The selected leader student is already a member of another group.');
                redirect('/admin/groups');
            }

            try {
                $db->beginTransaction();
                
                if (empty($group_code)) {
                    $stmtLeader = $db->prepare("SELECT student_id, department, shift FROM students WHERE user_id = ?");
                    $stmtLeader->execute([$created_by]);
                    $studentInfo = $stmtLeader->fetch();
                    
                    if (!$studentInfo) {
                        throw new \Exception("Student details not found for the selected leader.");
                    }
                    
                    $rollNo = $studentInfo['student_id'] ?? '';
                    $parts = explode('/', $rollNo);
                    $year = !empty($parts[0]) ? trim($parts[0]) : '2k23';
                    
                    $deptMap = [
                        'Software Engineering' => 'SWE',
                        'Information Technology' => 'IT',
                        'Data Science' => 'DS',
                        'Electronic Engineering' => 'EL',
                        'Telecommunication Engineering' => 'TL'
                    ];
                    $deptCode = $deptMap[$studentInfo['department'] ?? ''] ?? 'GEN';
                    $shiftLetter = (($studentInfo['shift'] ?? '') === 'Evening') ? 'E' : 'M';
                    
                    $prefix = $year . '-' . $deptCode . $shiftLetter . '-';
                    
                    $stmtCount = $db->prepare("SELECT COUNT(*) FROM `groups` WHERE group_code LIKE ?");
                    $stmtCount->execute([$prefix . '%']);
                    $count = (int)$stmtCount->fetchColumn();
                    $nextNumber = $count + 1;
                    $group_code = $prefix . $nextNumber;
                }
                
                // Insert group
                $stmt = $db->prepare("INSERT INTO `groups` (group_code, created_by, progress_stage) VALUES (?, ?, ?)");
                $stmt->execute([$group_code, $created_by, $progress_stage]);
                $groupId = $db->lastInsertId();
                
                // Add leader to group members
                $stmtM = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                $stmtM->execute([$groupId, $created_by]);
                
                // Create grade record
                $stmtG = $db->prepare("INSERT INTO grades (group_id) VALUES (?)");
                $stmtG->execute([$groupId]);
                
                $db->commit();
                $this->flash('success', "Group $group_code created successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error creating group: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function editGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $group_code = trim($_POST['group_code'] ?? '');
            $progress_stage = $_POST['progress_stage'] ?? '';
            
            if (!$id || empty($group_code) || empty($progress_stage)) {
                $this->flash('error', 'Group ID, code and progress stage are required.');
                redirect('/admin/groups');
            }
            
            $db = \Database::getInstance()->getConnection();
            try {
                $stmt = $db->prepare("UPDATE `groups` SET group_code = ?, progress_stage = ? WHERE id = ?");
                $stmt->execute([$group_code, $progress_stage, $id]);
                
                $this->flash('success', "Group details updated successfully.");
            } catch (\Exception $e) {
                $this->flash('error', 'Error updating group: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function deleteGroup() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            try {
                $stmt = $db->prepare("DELETE FROM `groups` WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Group and all associated files/records deleted successfully.');
            } catch (\Exception $e) {
                $this->flash('error', 'Error deleting group: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function updateGroupMembers() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupId = $_POST['group_id'] ?? null;
            $members = $_POST['members'] ?? [];
            
            if (!$groupId) {
                $this->flash('error', 'Group ID is required.');
                redirect('/admin/groups');
            }
            
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                
                $stmtLeader = $db->prepare("SELECT created_by FROM `groups` WHERE id = ?");
                $stmtLeader->execute([$groupId]);
                $leaderId = $stmtLeader->fetchColumn();
                
                if ($leaderId && !in_array($leaderId, $members)) {
                    $members[] = $leaderId;
                }
                
                foreach ($members as $stdId) {
                    if ($stdId == $leaderId) continue;
                    $stmtC = $db->prepare("SELECT group_id FROM group_members WHERE student_id = ? AND group_id != ?");
                    $stmtC->execute([$stdId, $groupId]);
                    $existingGroup = $stmtC->fetchColumn();
                    if ($existingGroup) {
                        throw new \Exception("One or more selected students already belong to another group.");
                    }
                }
                
                $stmtDel = $db->prepare("DELETE FROM group_members WHERE group_id = ?");
                $stmtDel->execute([$groupId]);
                
                $stmtIns = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                foreach ($members as $stdId) {
                    if (!empty($stdId)) {
                        $stmtIns->execute([$groupId, $stdId]);
                    }
                }
                
                $db->commit();
                $this->flash('success', 'Group members updated successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating group members: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function editProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupId = $_POST['group_id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = $_POST['status'] ?? 'Draft';
            $supervisor_id = !empty($_POST['supervisor_id']) ? $_POST['supervisor_id'] : null;
            
            if (!$groupId || empty($title)) {
                $this->flash('error', 'Group ID and Project Title are required.');
                redirect('/admin/groups');
            }
            
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                
                $stmtP = $db->prepare("SELECT id FROM projects WHERE group_id = ?");
                $stmtP->execute([$groupId]);
                $exists = $stmtP->fetchColumn();
                
                if ($exists) {
                    $stmtUpdate = $db->prepare("UPDATE projects SET title = ?, description = ?, status = ?, supervisor_id = ? WHERE group_id = ?");
                    $stmtUpdate->execute([$title, $description, $status, $supervisor_id, $groupId]);
                } else {
                    $stmtInsert = $db->prepare("INSERT INTO projects (group_id, title, description, status, supervisor_id) VALUES (?, ?, ?, ?, ?)");
                    $stmtInsert->execute([$groupId, $title, $description, $status, $supervisor_id]);
                }
                
                $stmtProp = $db->prepare("SELECT id FROM proposals WHERE group_id = ?");
                $stmtProp->execute([$groupId]);
                $propExists = $stmtProp->fetchColumn();
                
                if ($propExists) {
                    $stmtPropUpdate = $db->prepare("UPDATE proposals SET abstract = ?, status = ? WHERE group_id = ?");
                    $stmtPropUpdate->execute([$description, $status, $groupId]);
                } else {
                    $stmtPropInsert = $db->prepare("INSERT INTO proposals (group_id, abstract, file_path, status) VALUES (?, ?, '/uploads/proposals/test.pdf', ?)");
                    $stmtPropInsert->execute([$groupId, $description, $status]);
                }
                
                $db->commit();
                $this->flash('success', 'Project details saved successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error saving project details: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function deleteProject() {
        $groupId = $_GET['group_id'] ?? null;
        if ($groupId) {
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                $db->prepare("DELETE FROM projects WHERE group_id = ?")->execute([$groupId]);
                $db->prepare("DELETE FROM proposals WHERE group_id = ?")->execute([$groupId]);
                $db->commit();
                $this->flash('success', 'Project and proposal records deleted successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error deleting project: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function editGrades() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $groupId = $_POST['group_id'] ?? null;
            
            $proposal_defense_marks = isset($_POST['proposal_defense_marks']) && $_POST['proposal_defense_marks'] !== '' ? (float)$_POST['proposal_defense_marks'] : null;
            $progress_presentation_marks = isset($_POST['progress_presentation_marks']) && $_POST['progress_presentation_marks'] !== '' ? (float)$_POST['progress_presentation_marks'] : null;
            $final_presentation_marks = isset($_POST['final_presentation_marks']) && $_POST['final_presentation_marks'] !== '' ? (float)$_POST['final_presentation_marks'] : null;
            $supervision_marks = isset($_POST['supervision_marks']) && $_POST['supervision_marks'] !== '' ? (float)$_POST['supervision_marks'] : null;
            
            if (!$groupId) {
                $this->flash('error', 'Group ID is required.');
                redirect('/admin/groups');
            }
            
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                
                $stmt = $db->prepare("UPDATE grades SET 
                    proposal_defense_marks = ?, 
                    progress_presentation_marks = ?, 
                    final_presentation_marks = ?, 
                    supervision_marks = ? 
                    WHERE group_id = ?");
                $stmt->execute([
                    $proposal_defense_marks, 
                    $progress_presentation_marks, 
                    $final_presentation_marks, 
                    $supervision_marks, 
                    $groupId
                ]);
                
                $total = round(
                    (float)$proposal_defense_marks + 
                    (float)$progress_presentation_marks + 
                    (float)$final_presentation_marks + 
                    (float)$supervision_marks
                );
                
                $percentage = round(($total / 200.0) * 100.0);
                
                $grade = 'F';
                if ($percentage >= 85) $grade = 'A+';
                else if ($percentage >= 80) $grade = 'A';
                else if ($percentage >= 75) $grade = 'B+';
                else if ($percentage >= 70) $grade = 'B';
                else if ($percentage >= 65) $grade = 'C+';
                else if ($percentage >= 60) $grade = 'C';
                else if ($percentage >= 55) $grade = 'D+';
                else if ($percentage >= 50) $grade = 'D';
                
                $status = ($percentage >= 50) ? 'Pass' : 'Fail';

                $stmtUpdateGrades = $db->prepare("UPDATE grades SET total_marks = ?, percentage = ?, grade = ?, status = ? WHERE group_id = ?");
                $stmtUpdateGrades->execute([$total, $percentage, $grade, $status, $groupId]);
                
                $db->commit();
                $this->flash('success', 'Grades updated and recalculated successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating grades: ' . $e->getMessage());
            }
        }
        redirect('/admin/groups');
    }

    public function deleteDeadline() {
        $stage = $_GET['stage'] ?? '';
        $department = $_GET['department'] ?? 'Software Engineering';
        if ($stage) {
            $db = \Database::getInstance()->getConnection();
            try {
                $stmt = $db->prepare("DELETE FROM deadlines WHERE stage = ? AND department = ?");
                $stmt->execute([$stage, $department]);
                $this->flash('success', "Deadline for $stage deleted successfully.");
            } catch (\Exception $e) {
                $this->flash('error', 'Error deleting deadline: ' . $e->getMessage());
            }
        }
        redirect('/admin/deadlines?department=' . urlencode($department));
    }

    public function batches() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM academic_batches ORDER BY created_at DESC");
        $batches = $stmt->fetchAll();
        $this->render('admin/batches', ['batches' => $batches]);
    }

    public function createBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $this->flash('error', 'Batch name is required.');
                redirect('/admin/batches');
            }
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO academic_batches (name, is_active, is_registration_open) VALUES (?, 1, 0)");
            try {
                $stmt->execute([$name]);
                $this->flash('success', 'Batch created successfully.');
            } catch (\Exception $e) {
                $this->flash('error', 'Failed to create batch. Ensure the name is unique.');
            }
        }
        redirect('/admin/batches');
    }

    public function toggleBatch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['batch_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            $db = \Database::getInstance()->getConnection();

            if ($action === 'set_registration') {
                // Only one can be open for registration
                $db->exec("UPDATE academic_batches SET is_registration_open = 0");
                $stmt = $db->prepare("UPDATE academic_batches SET is_registration_open = 1 WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Registration batch updated.');
            } elseif ($action === 'toggle_active') {
                $stmt = $db->prepare("UPDATE academic_batches SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Batch active status toggled.');
            }
        }
        redirect('/admin/batches');
    }
}
