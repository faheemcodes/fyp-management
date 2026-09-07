<?php
namespace Controller;

use PHPMailer\PHPMailer\PHPMailer;

class AdminController extends BaseController {

    private function getAllDepartments() {
        return [
            'Software Engineering',
            'Computer Science',
            'Information Technology',
            'Data Science',
            'Electronic Engineering',
            'Telecommunication Engineering'
        ];
    }

    public function dashboard() {
        $db = \Database::getInstance()->getConnection();

        $stats = [];
        // Core metrics
        $stats['total_users'] = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['supervisors'] = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'supervisor'")->fetchColumn();
        $stats['active_projects'] = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'Approved'")->fetchColumn();
        
        $stmtTotGrp = $db->query("SELECT COUNT(*) FROM `groups` g LEFT JOIN academic_batches b ON g.batch_id = b.id WHERE b.is_active = 1 OR b.id IS NULL");
        $stats['total_groups'] = (int)($stmtTotGrp ? $stmtTotGrp->fetchColumn() : 0);
        if ($stats['total_groups'] === 0) {
            $stats['total_groups'] = (int)$db->query("SELECT COUNT(*) FROM `groups`")->fetchColumn();
        }

        $stmtAlloc = $db->query("SELECT COUNT(*) FROM `groups` WHERE committee_number IS NOT NULL AND committee_number > 0");
        $stats['allocated_groups'] = (int)($stmtAlloc ? $stmtAlloc->fetchColumn() : 0);

        // Pending action indicators
        $stmtPendingStud = $db->query("SELECT COUNT(*) FROM users WHERE status = 'pending' AND role = 'student'");
        $stats['pending_approvals'] = (int)($stmtPendingStud ? $stmtPendingStud->fetchColumn() : 0);

        $stmtPendingProp = $db->query("SELECT COUNT(*) FROM proposals WHERE status IN ('Submitted', 'Under Review')");
        $stats['pending_proposals'] = (int)($stmtPendingProp ? $stmtPendingProp->fetchColumn() : 0);

        $stmtPendingMeet = $db->query("SELECT COUNT(*) FROM meetings WHERE status = 'Completed'");
        $stats['pending_meetings'] = (int)($stmtPendingMeet ? $stmtPendingMeet->fetchColumn() : 0);

        // Grade metrics
        $avgMarks = $db->query("SELECT AVG(percentage) FROM grades WHERE percentage > 0")->fetchColumn();
        $stats['avg_marks'] = $avgMarks ? round($avgMarks, 1) . '%' : 'N/A';

        // Active batch
        $stats['active_batch_name'] = $db->query("SELECT name FROM academic_batches WHERE is_active = 1 LIMIT 1")->fetchColumn() ?: '2023';

        // FYP Progress Stages Funnel (University-wide)
        $stages = [
            'Proposal Submitted' => 0,
            'Proposal Approved' => 0,
            'Proposal Defence Presentation Completed' => 0,
            'FYP Progress Presentation Completed' => 0,
            'Final Presentation Completed' => 0,
            'Final Grading Completed' => 0
        ];
        $stmtStages = $db->query("SELECT progress_stage, COUNT(*) as count FROM `groups` GROUP BY progress_stage");
        if ($stmtStages) {
            while ($row = $stmtStages->fetch()) {
                if (array_key_exists($row['progress_stage'], $stages)) {
                    $stages[$row['progress_stage']] = (int)$row['count'];
                }
            }
        }

        // Department Breakdown Summary
        $departments = $this->getAllDepartments();
        $departmentStats = [];
        foreach ($departments as $dept) {
            $stmtSt = $db->prepare("SELECT COUNT(*) FROM students WHERE department = ?");
            $stmtSt->execute([$dept]);
            $studCount = (int)$stmtSt->fetchColumn();

            $stmtGr = $db->prepare("SELECT COUNT(*) FROM `groups` g JOIN students s ON g.created_by = s.user_id WHERE s.department = ?");
            $stmtGr->execute([$dept]);
            $grpCount = (int)$stmtGr->fetchColumn();

            $stmtSp = $db->prepare("SELECT COUNT(*) FROM supervisors WHERE department = ?");
            $stmtSp->execute([$dept]);
            $supCount = (int)$stmtSp->fetchColumn();

            $stmtAp = $db->prepare("SELECT COUNT(*) FROM projects p JOIN `groups` g ON p.group_id = g.id JOIN students s ON g.created_by = s.user_id WHERE s.department = ? AND p.status = 'Approved'");
            $stmtAp->execute([$dept]);
            $projCount = (int)$stmtAp->fetchColumn();

            if ($studCount > 0 || $grpCount > 0 || $supCount > 0) {
                $departmentStats[$dept] = [
                    'students' => $studCount,
                    'groups' => $grpCount,
                    'supervisors' => $supCount,
                    'approved_projects' => $projCount
                ];
            }
        }

        // Pending students awaiting approval list (Top 5)
        $pendingStudentsList = $db->query("
            SELECT u.id, u.email, u.created_at, s.name, s.student_id, s.department, s.shift, s.avatar
            FROM users u
            JOIN students s ON u.id = s.user_id
            WHERE u.status = 'pending' AND u.role = 'student'
            ORDER BY u.created_at ASC LIMIT 5
        ")->fetchAll();

        // Pending proposals awaiting review list (Top 5)
        $pendingProposalsList = $db->query("
            SELECT pr.id, pr.status, pr.submitted_at, p.title as project_title, g.group_code, s.name as leader_name, s.department, sup.name as supervisor_name
            FROM proposals pr
            JOIN projects p ON pr.group_id = p.group_id
            JOIN `groups` g ON p.group_id = g.id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE pr.status IN ('Submitted', 'Under Review')
            ORDER BY pr.submitted_at ASC LIMIT 5
        ")->fetchAll();

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'stages' => $stages,
            'departmentStats' => $departmentStats,
            'pendingStudentsList' => $pendingStudentsList,
            'pendingProposalsList' => $pendingProposalsList
        ]);
    }

    
    public function supervisorSlots() {
        $db = \Database::getInstance()->getConnection();
        $supervisorsList = $db->query("
            SELECT s.user_id, s.name, s.department, 
            (SELECT COUNT(*) FROM projects p JOIN `groups` g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = s.user_id AND p.status = 'Approved' AND b.is_active = 1) as current_slots,
            (COALESCE(ds.max_morning_slots, 5) + COALESCE(ds.max_evening_slots, 5)) as total_max_slots
            FROM supervisors s
            LEFT JOIN department_settings ds ON s.department COLLATE utf8mb4_unicode_ci = ds.department COLLATE utf8mb4_unicode_ci
            ORDER BY s.name ASC
        ")->fetchAll();

        $this->render('admin/slots', [
            'supervisorsList' => $supervisorsList
        ]);
    }

    public function users() {
        $db = \Database::getInstance()->getConnection();
        
        // Fetch KPI stats for users management
        $stats = [
            'total_users' => (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_students' => (int)$db->query("SELECT COUNT(*) FROM students")->fetchColumn(),
            'total_supervisors' => (int)$db->query("SELECT COUNT(*) FROM supervisors")->fetchColumn(),
            'total_coordinators' => (int)$db->query("SELECT COUNT(*) FROM coordinators")->fetchColumn(),
            'total_committees' => (int)$db->query("SELECT COUNT(*) FROM committees")->fetchColumn(),
            'pending_users' => (int)$db->query("SELECT COUNT(*) FROM users WHERE status = 'pending'")->fetchColumn(),
        ];

        // Fetch all users with full multi-role assignment details
        $users = $db->query("
            SELECT u.*, 
            COALESCE(s.name, sup.name, c.name, d.name, coord.name, 'Administrator') as name,
            COALESCE(s.student_id, '') as student_id,
            COALESCE(s.department, sup.department, c.department, d.department, coord.department, 'N/A') as department,
            s.shift,
            s.avatar,
            COALESCE(sup.designation, c.designation, coord.designation, d.designation) as designation,
            prof.cnic,
            prof.prefix,
            prof.surname,
            prof.father_name,
            prof.dob,
            prof.mobile_code,
            prof.mobile_no,
            prof.gender,
            prof.province_state,
            prof.district,
            prof.home_address,
            (SELECT COUNT(*) FROM supervisors WHERE user_id = u.id) as is_supervisor,
            (SELECT COUNT(*) FROM coordinators WHERE user_id = u.id) as is_coordinator,
            (SELECT shift FROM coordinators WHERE user_id = u.id LIMIT 1) as coord_shift,
            (SELECT designation FROM coordinators WHERE user_id = u.id LIMIT 1) as coord_designation,
            (SELECT COUNT(*) FROM committees WHERE user_id = u.id) as is_committee,
            (SELECT committee_number FROM committees WHERE user_id = u.id LIMIT 1) as committee_number,
            (SELECT COUNT(*) FROM hods WHERE user_id = u.id) as is_hod,
            (SELECT COUNT(*) FROM students WHERE user_id = u.id) as is_student
            FROM users u
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN supervisors sup ON u.id = sup.user_id
            LEFT JOIN committees c ON u.id = c.user_id
            LEFT JOIN hods d ON u.id = d.user_id
            LEFT JOIN coordinators coord ON u.id = coord.user_id
            LEFT JOIN profiles prof ON u.id = prof.user_id
            ORDER BY CASE WHEN u.status = 'pending' THEN 1 ELSE 2 END ASC, u.created_at DESC")->fetchAll();

        $this->render('admin/users', [
            'users' => $users,
            'stats' => $stats
        ]);
    }

    public function approveUser() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
            $stmt->execute([$id]);
            
            // Get user's details for notification
            $stmtUser = $db->prepare("
                SELECT u.email, u.role, u.cnic, s.student_id 
                FROM users u 
                LEFT JOIN students s ON u.id = s.user_id 
                WHERE u.id = ?
            ");
            $stmtUser->execute([$id]);
            $user = $stmtUser->fetch();
            
            if ($user) {
                $this->addNotification($id, 'Account Approved', 'Your registration has been approved! You can now log in.');
                $subject = "Your Account has been Approved";
                
                $identifierStr = "";
                if ($user['role'] === 'student') {
                    $identifierStr = "Roll Number: " . $user['student_id'] . "\nPassword: (The password you chose during registration)";
                } else {
                    $identifierStr = "CNIC: " . $user['cnic'] . "\nPassword: (The password you chose during registration)";
                }

                $message = "Hello,\n\nYour account on the FYP Management Portal has been approved by an administrator.\n\n"
                         . "Your Login Credentials:\n"
                         . $identifierStr . "\n\n"
                         . "You can now log in to the portal.\n\nRegards,\nFYP Management Team";
                $this->sendEmail($user['email'], $subject, $message);
            }
            
            $this->flash('success', 'User account approved successfully.');
        }
        redirect('/admin/users');
    }

    public function rejectUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $id = $_POST['id'] ?? null;
            $reason = trim($_POST['reason'] ?? '');
            
            if ($id && !empty($reason)) {
                $db = \Database::getInstance()->getConnection();
                
                // Get user email before deletion
                $stmtUser = $db->prepare("SELECT email FROM users WHERE id = ?");
                $stmtUser->execute([$id]);
                $user = $stmtUser->fetch();
                
                if ($user) {
                    $subject = "Your Account Registration was Rejected";
                    $message = "Hello,\n\nUnfortunately, your account registration on the FYP Management Portal was rejected by an administrator.\n\n"
                             . "Reason for rejection:\n$reason\n\n"
                             . "If you believe this was a mistake, please contact administration.\n\nRegards,\nFYP Management Team";
                    $this->sendEmail($user['email'], $subject, $message);
                }
                
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
            } else {
                $this->flash('error', 'Rejection reason is required.');
            }
        }
        redirect('/admin/users');
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $role = $_POST['role'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $department = trim($_POST['department'] ?? '');
            
            // Specifics
            $designation = trim($_POST['designation'] ?? '');
            $student_id = trim($_POST['student_id'] ?? '');
            
            // Common Profile
            $surname = trim($_POST['surname'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            
            // Student Profile Defaults (Since admin only inputs basic info now)
            $father_name = !empty($_POST['father_name']) ? trim($_POST['father_name']) : '';
            $dob = !empty($_POST['dob']) ? trim($_POST['dob']) : '2000-01-01';
            $gender = !empty($_POST['gender']) ? trim($_POST['gender']) : 'Male';
            $mobile_code = !empty($_POST['mobile_code']) ? trim($_POST['mobile_code']) : '';
            $mobile_no = !empty($_POST['mobile_no']) ? trim($_POST['mobile_no']) : '';
            $country = !empty($_POST['country']) ? trim($_POST['country']) : '';
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
                    $prefix = !empty($_POST['prefix']) ? trim($_POST['prefix']) : (($role === 'supervisor') ? 'Dr.' : 'Mr.');
                    $stmt = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, gender, home_address) VALUES (?, ?, ?, ?, '1980-01-01', 'Male', 'Not Provided Yet')");
                    $stmt->execute([$userId, $prefix, $surname, $cnic]);
                    
                    $is_supervisor = isset($_POST['is_supervisor']) && ($_POST['is_supervisor'] === '1' || $_POST['is_supervisor'] === 'on');
                    $is_coordinator = isset($_POST['is_coordinator']) && ($_POST['is_coordinator'] === '1' || $_POST['is_coordinator'] === 'on');
                    $coord_shift = !empty($_POST['coord_shift']) ? $_POST['coord_shift'] : 'Morning';
                    $is_committee = isset($_POST['is_committee']) && ($_POST['is_committee'] === '1' || $_POST['is_committee'] === 'on');
                    $committee_number = max(1, min(8, (int)($_POST['committee_number'] ?? 1)));

                    if ($role === 'supervisor') $is_supervisor = true;
                    if ($role === 'coordinator') $is_coordinator = true;
                    if ($role === 'committee') $is_committee = true;

                    if ($is_supervisor) {
                        $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$userId, $name, $designation, $department]);
                    }
                    if ($is_coordinator) {
                        $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, designation, department, shift) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$userId, $name, $designation, $department, $coord_shift]);
                    }
                    if ($is_committee) {
                        $stmt = $db->prepare("INSERT INTO committees (user_id, name, designation, department, committee_number) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$userId, $name, $designation, $department, $committee_number]);
                    }
                    if ($role === 'hod') {
                        $stmt = $db->prepare("INSERT INTO hods (user_id, name, department, designation) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$userId, $name, $department, $designation]);
                    }
                }
                
                $db->commit();
                
                $subject = "Welcome to FYP Management Portal";
                
                $identifierStr = "";
                if ($role === 'student') {
                    $identifierStr = "Roll Number: $student_id\nPassword: $password";
                } else {
                    $identifierStr = "CNIC: $cnic\nPassword: $password";
                }

                $message = "Hello $name,\n\nAn administrator has created an account for you on the FYP Management Portal as a " . ucfirst($role) . ".\n\n"
                         . "Your Login Credentials:\n"
                         . $identifierStr . "\n\n"
                         . "Please log in and change your password as soon as possible.\n\nRegards,\nFYP Management Team";
                $this->sendEmail($email, $subject, $message);

                $this->flash('success', "User $name ($role) created successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error creating user. Please try again.');
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
            LEFT JOIN grades gr ON (g.id = gr.group_id AND g.created_by = gr.student_id)
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
            $this->validateCsrf();
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

    public function editUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $role = $_POST['role'] ?? '';
            $department = trim($_POST['department'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // role specific
            $prefix = $_POST['prefix'] ?? 'Mr.';
            $surname = trim($_POST['surname'] ?? '');
            $student_id = trim($_POST['student_id'] ?? '');
            $shift = $_POST['shift'] ?? 'Morning';
            $designation = trim($_POST['designation'] ?? '');
            
            // Profile specific
            $mobile_no = trim($_POST['mobile_no'] ?? '');
            $gender = $_POST['gender'] ?? 'Male';
            $dob = $_POST['dob'] ?? '2000-01-01';
            if(empty($dob)) $dob = '2000-01-01';
            $province_state = trim($_POST['province_state'] ?? '');
            $district = trim($_POST['district'] ?? '');
            $home_address = trim($_POST['home_address'] ?? '');
            $father_name = trim($_POST['father_name'] ?? '');
            
            if (!$id || empty($name) || empty($email) || empty($role)) {
                $this->flash('error', 'Required fields are missing.');
                redirect('/admin/users');
            }

            // Remove dashes from CNIC if present
            $cnic = str_replace('-', '', $cnic);
            
            // For users table, empty string must be null to avoid UNIQUE constraint violations
            $user_cnic = ($cnic === '') ? null : $cnic;
            // For profiles table, cnic cannot be null
            $profile_cnic = ($cnic === '') ? '' : $cnic;

            $db = \Database::getInstance()->getConnection();
            
            try {
                $db->beginTransaction();
                
                // Update users table
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ?, password = ? WHERE id = ?");
                    $stmt->execute([$email, $user_cnic, $hashed, $id]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ? WHERE id = ?");
                    $stmt->execute([$email, $user_cnic, $id]);
                }
                
                // Update role-specific table and profiles
                if ($role === 'student') {
                    $stmt = $db->prepare("INSERT INTO students (user_id, student_id, name, department, shift) VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE student_id = ?, name = ?, department = ?, shift = ?");
                    $stmt->execute([$id, $student_id, $name, $department, $shift, $student_id, $name, $department, $shift]);
                } else {
                    $is_supervisor = isset($_POST['is_supervisor']) && ($_POST['is_supervisor'] === '1' || $_POST['is_supervisor'] === 'on');
                    $is_coordinator = isset($_POST['is_coordinator']) && ($_POST['is_coordinator'] === '1' || $_POST['is_coordinator'] === 'on');
                    $coord_shift = !empty($_POST['coord_shift']) ? $_POST['coord_shift'] : 'Morning';
                    $is_committee = isset($_POST['is_committee']) && ($_POST['is_committee'] === '1' || $_POST['is_committee'] === 'on');
                    $committee_number = max(1, min(8, (int)($_POST['committee_number'] ?? 1)));

                    // If role was explicitly submitted as one of the faculty roles
                    if ($role === 'supervisor') {
                        $is_supervisor = true;
                    } elseif ($role === 'coordinator') {
                        $is_coordinator = true;
                    } elseif ($role === 'committee') {
                        $is_committee = true;
                    }

                    // Supervisors sync
                    if ($is_supervisor) {
                        $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE name = ?, designation = ?, department = ?");
                        $stmt->execute([$id, $name, $designation, $department, $name, $designation, $department]);
                    } else {
                        $stmt = $db->prepare("DELETE FROM supervisors WHERE user_id = ?");
                        $stmt->execute([$id]);
                    }

                    // Coordinators sync
                    if ($is_coordinator) {
                        $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, designation, department, shift) VALUES (?, ?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE name = ?, designation = ?, department = ?, shift = ?");
                        $stmt->execute([$id, $name, $designation, $department, $coord_shift, $name, $designation, $department, $coord_shift]);
                    } else {
                        $stmt = $db->prepare("DELETE FROM coordinators WHERE user_id = ?");
                        $stmt->execute([$id]);
                    }

                    // Committees sync
                    if ($is_committee) {
                        $stmt = $db->prepare("INSERT INTO committees (user_id, name, designation, department, committee_number) VALUES (?, ?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE name = ?, designation = ?, department = ?, committee_number = ?");
                        $stmt->execute([$id, $name, $designation, $department, $committee_number, $name, $designation, $department, $committee_number]);
                    } else {
                        $stmt = $db->prepare("DELETE FROM committees WHERE user_id = ?");
                        $stmt->execute([$id]);
                    }

                    // HOD sync
                    if ($role === 'hod') {
                        $stmt = $db->prepare("INSERT INTO hods (user_id, name, department, designation) VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE name = ?, department = ?, designation = ?");
                        $stmt->execute([$id, $name, $department, $designation, $name, $department, $designation]);
                    }

                    // Ensure primary role in users table remains consistent
                    $primaryRole = $role;
                    if ($role === 'hod') {
                        $primaryRole = 'hod';
                    } elseif ($is_supervisor || $role === 'supervisor') {
                        $primaryRole = 'supervisor';
                    } elseif ($is_coordinator || $role === 'coordinator') {
                        $primaryRole = 'coordinator';
                    } elseif ($is_committee || $role === 'committee') {
                        $primaryRole = 'committee';
                    }
                    $stmtRole = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
                    $stmtRole->execute([$primaryRole, $id]);
                }
                
                // Keep profiles table in sync
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender, province_state, district, father_name) VALUES (?, ?, ?, ?, ?, '+92', ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE prefix = ?, surname = ?, cnic = ?, dob = ?, mobile_no = ?, home_address = ?, gender = ?, province_state = ?, district = ?, father_name = ?");
                $stmtP->execute([$id, $prefix, $surname, $profile_cnic, $dob, $mobile_no, $home_address, $gender, $province_state, $district, $father_name, $prefix, $surname, $profile_cnic, $dob, $mobile_no, $home_address, $gender, $province_state, $district, $father_name]);
                
                $db->commit();
                $this->flash('success', "User account updated successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating user. Please try again.');
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
                $this->flash('error', 'Error deleting user. Please try again.');
            }
        }
        redirect('/admin/users');
    }

    public function createGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
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
                $stmtG = $db->prepare("INSERT INTO grades (student_id, group_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE group_id = VALUES(group_id)");
                $stmtG->execute([$created_by, $groupId]);
                
                $db->commit();
                $this->flash('success', "Group $group_code created successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error creating group. Please try again.');
            }
        }
        redirect('/admin/groups');
    }

    public function editGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
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
                $this->flash('error', 'Error updating group. Please try again.');
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
                $this->flash('error', 'Error deleting group. Please try again.');
            }
        }
        redirect('/admin/groups');
    }

    public function updateGroupMembers() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $groupId = $_POST['group_id'] ?? null;
            $members = $_POST['members'] ?? [];
            
            if (!$groupId) {
                $this->flash('error', 'Group ID is required.');
                redirect('/admin/groups');
            }
            
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                
                $stmtLeader = $db->prepare("SELECT created_by, department FROM `groups` g JOIN students s ON g.created_by = s.user_id WHERE g.id = ?");
                $stmtLeader->execute([$groupId]);
                $groupData = $stmtLeader->fetch();
                
                $leaderId = $groupData ? $groupData['created_by'] : null;
                $dept = $groupData ? $groupData['department'] : null;

                $maxGroupMembers = 3;
                if ($dept) {
                    $stmtDept = $db->prepare("SELECT max_group_members FROM department_settings WHERE department = ?");
                    $stmtDept->execute([$dept]);
                    if ($val = $stmtDept->fetchColumn()) {
                        $maxGroupMembers = $val;
                    }
                }
                
                if ($leaderId && !in_array($leaderId, $members)) {
                    $members[] = $leaderId;
                }

                if (count($members) > $maxGroupMembers) {
                    throw new \Exception("Cannot exceed maximum group limit of $maxGroupMembers members for this department.");
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
                $stmtGrade = $db->prepare("INSERT INTO grades (student_id, group_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE group_id = VALUES(group_id)");
                foreach ($members as $stdId) {
                    if (!empty($stdId)) {
                        $stmtIns->execute([$groupId, $stdId]);
                        $stmtGrade->execute([$stdId, $groupId]);
                    }
                }
                
                $db->commit();
                $this->flash('success', 'Group members updated successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating group members. Please try again.');
            }
        }
        redirect('/admin/groups');
    }

    public function editProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
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
                $this->flash('error', 'Error saving project details. Please try again.');
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
                $this->flash('error', 'Error deleting project. Please try again.');
            }
        }
        redirect('/admin/groups');
    }

    public function editGrades() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
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

                // Update/insert grades per group member
                $stmtMembers = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                $stmtMembers->execute([$groupId]);
                $groupMembers = $stmtMembers->fetchAll();
                
                if (!empty($groupMembers)) {
                    $stmtUpsert = $db->prepare("INSERT INTO grades (student_id, group_id, proposal_defense_marks, progress_presentation_marks, final_presentation_marks, supervision_marks, total_marks, percentage, grade, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE 
                            group_id = VALUES(group_id),
                            proposal_defense_marks = VALUES(proposal_defense_marks),
                            progress_presentation_marks = VALUES(progress_presentation_marks),
                            final_presentation_marks = VALUES(final_presentation_marks),
                            supervision_marks = VALUES(supervision_marks),
                            total_marks = VALUES(total_marks),
                            percentage = VALUES(percentage),
                            grade = VALUES(grade),
                            status = VALUES(status)");
                    foreach ($groupMembers as $gm) {
                        $stmtUpsert->execute([
                            $gm['student_id'],
                            $groupId,
                            $proposal_defense_marks,
                            $progress_presentation_marks,
                            $final_presentation_marks,
                            $supervision_marks,
                            $total,
                            $percentage,
                            $grade,
                            $status
                        ]);
                    }
                } else {
                    $stmtUpdate = $db->prepare("UPDATE grades SET 
                        proposal_defense_marks = ?, 
                        progress_presentation_marks = ?, 
                        final_presentation_marks = ?, 
                        supervision_marks = ?,
                        total_marks = ?,
                        percentage = ?,
                        grade = ?,
                        status = ?
                        WHERE group_id = ?");
                    $stmtUpdate->execute([
                        $proposal_defense_marks, 
                        $progress_presentation_marks, 
                        $final_presentation_marks, 
                        $supervision_marks,
                        $total,
                        $percentage,
                        $grade,
                        $status,
                        $groupId
                    ]);
                }
                
                $db->commit();
                $this->flash('success', 'Grades updated and recalculated successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating grades. Please try again.');
            }
        }
        redirect('/admin/groups');
    }

    public function proposals() {
        $db = \Database::getInstance()->getConnection();
        
        $selectedDept = $_GET['department'] ?? 'all';
        $selectedShift = $_GET['shift'] ?? 'all';
        $selectedStatus = $_GET['status'] ?? 'all';

        $where = [];
        $params = [];

        if ($selectedDept !== 'all') {
            $where[] = "s.department = ?";
            $params[] = $selectedDept;
        }
        if ($selectedShift !== 'all') {
            $where[] = "s.shift = ?";
            $params[] = $selectedShift;
        }
        if ($selectedStatus !== 'all') {
            $where[] = "pr.status = ?";
            $params[] = $selectedStatus;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        $query = "
            SELECT pr.*, p.title as project_title, p.description as project_description,
                   g.group_code, g.id as group_id,
                   s.name as leader_name, s.student_id as leader_roll_no, s.department, s.shift, s.avatar as leader_avatar,
                   sup.name as supervisor_name
            FROM proposals pr
            JOIN projects p ON pr.group_id = p.group_id
            JOIN `groups` g ON p.group_id = g.id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            $whereClause
            ORDER BY pr.submitted_at DESC, pr.id DESC
        ";
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $proposals = $stmt->fetchAll();

        foreach ($proposals as &$prop) {
            $stmtM = $db->prepare("SELECT s.name, s.student_id, s.avatar FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ?");
            $stmtM->execute([$prop['group_id']]);
            $prop['members'] = $stmtM->fetchAll();
        }

        $departments = $this->getAllDepartments();

        $this->render('admin/proposals', [
            'proposals' => $proposals,
            'departments' => $departments,
            'selectedDept' => $selectedDept,
            'selectedShift' => $selectedShift,
            'selectedStatus' => $selectedStatus
        ]);
    }

    public function reviewProposal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $proposalId = (int)($_POST['proposal_id'] ?? 0);
            $action = trim($_POST['action'] ?? '');
            $feedback = trim($_POST['feedback'] ?? '');

            $validActions = ['Approved', 'Supervisor Approved', 'Under Review', 'Revision Requested', 'Rejected'];
            if (!$proposalId || !in_array($action, $validActions)) {
                $this->flash('error', 'Invalid proposal review action.');
                redirect('/admin/proposals');
            }

            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();

                $stmt = $db->prepare("SELECT group_id FROM proposals WHERE id = ?");
                $stmt->execute([$proposalId]);
                $groupId = $stmt->fetchColumn();

                if (!$groupId) {
                    throw new \Exception("Proposal not found.");
                }

                $stmtUpdate = $db->prepare("UPDATE proposals SET status = ?, feedback = ?, updated_at = NOW() WHERE id = ?");
                $stmtUpdate->execute([$action, $feedback, $proposalId]);

                $stmtProj = $db->prepare("UPDATE projects SET status = ? WHERE group_id = ?");
                $stmtProj->execute([$action, $groupId]);

                if ($action === 'Approved') {
                    $stmtGrp = $db->prepare("UPDATE `groups` SET progress_stage = 'Proposal Approved' WHERE id = ?");
                    $stmtGrp->execute([$groupId]);
                }

                $stmtMembers = $db->prepare("SELECT student_id FROM group_members WHERE group_id = ?");
                $stmtMembers->execute([$groupId]);
                $members = $stmtMembers->fetchAll();
                foreach ($members as $m) {
                    $this->addNotification(
                        $m['student_id'],
                        "Proposal Status: $action",
                        "Your FYP project proposal has been reviewed by Administrator: $action." . ($feedback ? " Feedback: $feedback" : "")
                    );
                }

                $db->commit();
                $this->flash('success', "Proposal status updated to '$action'.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating proposal status.');
            }
        }
        redirect('/admin/proposals');
    }

    public function committees() {
        $db = \Database::getInstance()->getConnection();
        
        $departments = $this->getAllDepartments();
        $selectedDept = $_GET['department'] ?? 'Software Engineering';
        if (!in_array($selectedDept, $departments)) {
            $selectedDept = $departments[0];
        }

        $selectedShift = $_GET['shift'] ?? 'all';
        if (!in_array($selectedShift, ['all', 'Morning', 'Evening'])) {
            $selectedShift = 'all';
        }

        $stmtDept = $db->prepare("SELECT num_committees FROM department_settings WHERE department = ?");
        $stmtDept->execute([$selectedDept]);
        $numCommittees = (int)($stmtDept->fetchColumn() ?: 2);

        $stmtComm = $db->prepare("
            SELECT c.*, u.email 
            FROM committees c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.department = ? 
            ORDER BY c.committee_number ASC, c.name ASC
        ");
        $stmtComm->execute([$selectedDept]);
        $allCommitteeMembers = $stmtComm->fetchAll();

        $committeeMembers = [];
        for ($i = 1; $i <= $numCommittees; $i++) {
            $committeeMembers[$i] = array_values(array_filter($allCommitteeMembers, fn($m) => (int)($m['committee_number'] ?? 1) === $i));
        }

        $shiftSql = ($selectedShift !== 'all') ? " AND s.shift = ?" : "";
        $params = [$selectedDept];
        if ($selectedShift !== 'all') {
            $params[] = $selectedShift;
        }

        $stmtGroups = $db->prepare("
            SELECT g.id, g.group_code, g.committee_number, g.progress_stage, g.created_at,
                   p.id as project_id, p.title as project_title, p.status as project_status,
                   sup.name as supervisor_name,
                   s.shift as student_shift
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN academic_batches b ON g.batch_id = b.id
            WHERE s.department = ? AND p.status = 'Approved' AND (b.is_active = 1 OR b.id IS NULL) $shiftSql
            ORDER BY g.group_code ASC, g.id ASC
        ");
        $stmtGroups->execute($params);
        $groups = $stmtGroups->fetchAll();

        foreach ($groups as &$grp) {
            $stmtM = $db->prepare("
                SELECT s.student_id as roll_no, s.name as student_name, s.avatar 
                FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                WHERE gm.group_id = ?
            ");
            $stmtM->execute([$grp['id']]);
            $grp['members'] = $stmtM->fetchAll();
        }

        $committeeCounts = [];
        for ($i = 1; $i <= $numCommittees; $i++) {
            $committeeCounts[$i] = 0;
        }
        $unassignedCount = 0;

        foreach ($groups as $g) {
            $cNum = $g['committee_number'];
            if ($cNum && isset($committeeCounts[(int)$cNum])) {
                $committeeCounts[(int)$cNum]++;
            } else {
                $unassignedCount++;
            }
        }

        $this->render('admin/committees', [
            'departments' => $departments,
            'selectedDept' => $selectedDept,
            'selectedShift' => $selectedShift,
            'numCommittees' => $numCommittees,
            'committeeMembers' => $committeeMembers,
            'groups' => $groups,
            'committeeCounts' => $committeeCounts,
            'unassignedCount' => $unassignedCount,
            'totalGroups' => count($groups)
        ]);
    }

    public function distributeCommittees() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $_POST['department'] ?? 'Software Engineering';
            $shift = $_POST['shift'] ?? 'all';
            $capacities = $_POST['capacity'] ?? [];

            $shiftSql = ($shift !== 'all') ? " AND s.shift = ?" : "";
            $params = [$dept];
            if ($shift !== 'all') {
                $params[] = $shift;
            }

            $stmtGroups = $db->prepare("
                SELECT g.id, g.group_code
                FROM `groups` g
                JOIN projects p ON g.id = p.group_id
                JOIN students s ON g.created_by = s.user_id
                LEFT JOIN academic_batches b ON g.batch_id = b.id
                WHERE s.department = ? AND p.status = 'Approved' AND (b.is_active = 1 OR b.id IS NULL) $shiftSql
                ORDER BY g.group_code ASC, g.id ASC
            ");
            $stmtGroups->execute($params);
            $groups = $stmtGroups->fetchAll();

            $totalGroups = count($groups);
            if ($totalGroups === 0) {
                $this->flash('error', 'No approved project groups found to allocate.');
                redirect('/admin/committees?department=' . urlencode($dept) . '&shift=' . urlencode($shift));
            }

            try {
                $db->beginTransaction();
                $assignedIndex = 0;
                $summaryParts = [];

                foreach ($capacities as $commNum => $cap) {
                    $commNum = (int)$commNum;
                    $cap = max(0, (int)$cap);
                    $assignedToThis = 0;

                    for ($i = 0; $i < $cap && $assignedIndex < $totalGroups; $i++) {
                        $grpId = $groups[$assignedIndex]['id'];
                        $stmtUp = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                        $stmtUp->execute([$commNum, $grpId]);
                        $assignedIndex++;
                        $assignedToThis++;
                    }

                    if ($assignedToThis > 0) {
                        $summaryParts[] = "$assignedToThis groups to Committee $commNum";
                    }
                }

                if ($assignedIndex < $totalGroups) {
                    $lastComm = count($capacities) > 0 ? max(array_keys($capacities)) : 1;
                    $overflowCount = 0;
                    while ($assignedIndex < $totalGroups) {
                        $grpId = $groups[$assignedIndex]['id'];
                        $stmtUp = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                        $stmtUp->execute([$lastComm, $grpId]);
                        $assignedIndex++;
                        $overflowCount++;
                    }
                    if ($overflowCount > 0) {
                        $summaryParts[] = "$overflowCount extra groups to Committee $lastComm";
                    }
                }

                $db->commit();
                $this->flash('success', "Sequential distribution complete: " . implode(', ', $summaryParts) . " ($totalGroups total groups).");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error applying committee distribution.');
            }
            redirect('/admin/committees?department=' . urlencode($dept) . '&shift=' . urlencode($shift));
        }
        redirect('/admin/committees');
    }

    public function reassignGroupCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $groupId = (int)($_POST['group_id'] ?? 0);
            $committeeNumber = max(1, (int)($_POST['committee_number'] ?? 1));
            $dept = $_POST['department'] ?? 'Software Engineering';
            $shift = $_POST['shift'] ?? 'all';

            if ($groupId > 0) {
                $stmt = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                $stmt->execute([$committeeNumber, $groupId]);
                $this->flash('success', "Group successfully allocated to Committee $committeeNumber.");
            } else {
                $this->flash('error', 'Invalid group selection.');
            }
            redirect('/admin/committees?department=' . urlencode($dept) . '&shift=' . urlencode($shift));
        }
        redirect('/admin/committees');
    }

    public function cumulativeSheet() {
        $db = \Database::getInstance()->getConnection();
        $departments = $this->getAllDepartments();
        $selectedDept = $_GET['department'] ?? 'Software Engineering';
        if (!in_array($selectedDept, $departments)) {
            $selectedDept = $departments[0];
        }

        $stmtBatches = $db->prepare("SELECT * FROM academic_batches WHERE department = ? OR department IS NULL ORDER BY is_active DESC, id DESC");
        $stmtBatches->execute([$selectedDept]);
        $batches = $stmtBatches->fetchAll();

        $activeBatch = null;
        foreach ($batches as $b) {
            if (!empty($b['is_active'])) {
                $activeBatch = $b;
                break;
            }
        }
        if (!$activeBatch && !empty($batches)) {
            $activeBatch = $batches[0];
        }

        $batchIdParam = $_GET['batch_id'] ?? null;
        if ($batchIdParam === 'all') {
            $batchId = 0;
        } elseif ($batchIdParam !== null && is_numeric($batchIdParam)) {
            $batchId = (int)$batchIdParam;
        } else {
            $batchId = $activeBatch['id'] ?? 0;
        }

        $selectedShift = trim($_GET['shift'] ?? 'all');
        if (!in_array($selectedShift, ['all', 'Morning', 'Evening'])) {
            $selectedShift = 'all';
        }

        $batchSql = "";
        $params = [$selectedDept];
        if ($batchId > 0) {
            $batchSql = " AND g.batch_id = ?";
            $params[] = $batchId;
        }

        $shiftSql = "";
        if ($selectedShift !== 'all') {
            $shiftSql = " AND st.shift = ?";
            $params[] = $selectedShift;
        }

        $query = "
            SELECT g.id as group_id, g.group_code, g.batch_id, g.committee_number,
                   p.title as project_title, p.status as project_status,
                   sup.name as supervisor_name,
                   st.user_id as student_id, st.name as student_name, st.student_id as roll_no,
                   st.department, st.shift,
                   gr.proposal_defense_marks, gr.progress_presentation_marks,
                   gr.supervision_marks, gr.final_presentation_marks,
                   gr.total_marks, gr.percentage, gr.grade, gr.status as pass_fail_status,
                   gr.show_supervision_to_student
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN group_members gm ON g.id = gm.group_id
            JOIN students st ON gm.student_id = st.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN grades gr ON st.user_id = gr.student_id
            WHERE st.department = ? AND p.status = 'Approved' $batchSql $shiftSql
            ORDER BY g.group_code ASC, st.student_id ASC
        ";
        $stmtStudents = $db->prepare($query);
        $stmtStudents->execute($params);
        $students = $stmtStudents->fetchAll();

        $this->render('admin/cumulative_sheet', [
            'departments' => $departments,
            'selectedDept' => $selectedDept,
            'batches' => $batches,
            'batchId' => $batchId,
            'selectedShift' => $selectedShift,
            'students' => $students
        ]);
    }

    public function printCumulativeSheet() {
        $db = \Database::getInstance()->getConnection();
        $selectedDept = $_GET['department'] ?? 'Software Engineering';
        $selectedShift = $_GET['shift'] ?? 'all';
        $batchId = (int)($_GET['batch_id'] ?? 0);

        $batchSql = "";
        $params = [$selectedDept];
        if ($batchId > 0) {
            $batchSql = " AND g.batch_id = ?";
            $params[] = $batchId;
        }

        $shiftSql = "";
        if ($selectedShift !== 'all') {
            $shiftSql = " AND st.shift = ?";
            $params[] = $selectedShift;
        }

        $query = "
            SELECT g.id as group_id, g.group_code, g.batch_id, g.committee_number,
                   p.title as project_title, p.status as project_status,
                   sup.name as supervisor_name,
                   st.user_id as student_id, st.name as student_name, st.student_id as roll_no,
                   st.department, st.shift,
                   gr.proposal_defense_marks, gr.progress_presentation_marks,
                   gr.supervision_marks, gr.final_presentation_marks,
                   gr.total_marks, gr.percentage, gr.grade, gr.status as pass_fail_status
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN group_members gm ON g.id = gm.group_id
            JOIN students st ON gm.student_id = st.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN grades gr ON st.user_id = gr.student_id
            WHERE st.department = ? AND p.status = 'Approved' $batchSql $shiftSql
            ORDER BY g.group_code ASC, st.student_id ASC
        ";
        $stmtStudents = $db->prepare($query);
        $stmtStudents->execute($params);
        $students = $stmtStudents->fetchAll();

        $batchName = 'All Batches';
        if ($batchId > 0) {
            $stmtB = $db->prepare("SELECT name FROM academic_batches WHERE id = ?");
            $stmtB->execute([$batchId]);
            $batchName = $stmtB->fetchColumn() ?: 'Batch';
        }

        $this->render('admin/cumulative_sheet_print', [
            'department' => $selectedDept,
            'shift' => $selectedShift,
            'batchName' => $batchName,
            'students' => $students
        ]);
    }

    public function toggleMarksVisibility() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $department = $_POST['department'] ?? 'Software Engineering';
            $shift = $_POST['shift'] ?? 'all';
            $batchId = (int)($_POST['batch_id'] ?? 0);
            $action = $_POST['action'] ?? 'publish';
            $newVal = ($action === 'publish') ? 1 : 0;

            try {
                $batchSql = "";
                $params = [$newVal, $department];
                if ($batchId > 0) {
                    $batchSql = " AND g.batch_id = ?";
                    $params[] = $batchId;
                }
                $shiftSql = "";
                if ($shift !== 'all') {
                    $shiftSql = " AND st.shift = ?";
                    $params[] = $shift;
                }

                $query = "
                    UPDATE grades gr
                    JOIN students st ON gr.student_id = st.user_id
                    JOIN `groups` g ON gr.group_id = g.id
                    SET gr.show_supervision_to_student = ?
                    WHERE st.department = ? $batchSql $shiftSql
                ";
                $stmt = $db->prepare($query);
                $stmt->execute($params);
                $affected = $stmt->rowCount();

                $msg = ($newVal === 1) ? "Marks successfully published to students ($affected records updated)." : "Marks successfully hidden from students ($affected records updated).";
                $this->flash('success', $msg);
            } catch (\Exception $e) {
                $this->flash('error', 'Error toggling marks visibility.');
            }
            redirect('/admin/cumulative-sheet?department=' . urlencode($department) . '&shift=' . urlencode($shift) . '&batch_id=' . $batchId);
        }
        redirect('/admin/cumulative-sheet');
    }

    public function attendanceSheet() {
        $db = \Database::getInstance()->getConnection();
        $departments = $this->getAllDepartments();
        $selectedDept = $_GET['department'] ?? 'Software Engineering';
        if (!in_array($selectedDept, $departments)) {
            $selectedDept = $departments[0];
        }

        $stmtBatches = $db->prepare("SELECT * FROM academic_batches WHERE department = ? OR department IS NULL ORDER BY is_active DESC, id DESC");
        $stmtBatches->execute([$selectedDept]);
        $batches = $stmtBatches->fetchAll();

        $selectedBatchId = isset($_GET['batch_id']) ? (int)$_GET['batch_id'] : ($batches[0]['id'] ?? 0);
        $selectedShift = $_GET['shift'] ?? 'Morning';
        $selectedCommittee = isset($_GET['committee_number']) ? (int)$_GET['committee_number'] : 1;
        $selectedStage = $_GET['stage'] ?? 'Proposal Defence Presentation';

        $stmtGroups = $db->prepare("
            SELECT g.id, g.group_code, g.committee_number, p.title as project_title, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? AND s.shift = ? AND g.batch_id = ? AND g.committee_number = ? AND p.status = 'Approved'
            ORDER BY g.group_code ASC
        ");
        $stmtGroups->execute([$selectedDept, $selectedShift, $selectedBatchId, $selectedCommittee]);
        $groups = $stmtGroups->fetchAll();

        foreach ($groups as &$grp) {
            $stmtM = $db->prepare("SELECT s.name, s.student_id FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ?");
            $stmtM->execute([$grp['id']]);
            $grp['members'] = $stmtM->fetchAll();
        }

        $this->render('admin/attendance_sheet_config', [
            'departments' => $departments,
            'selectedDept' => $selectedDept,
            'batches' => $batches,
            'selectedBatchId' => $selectedBatchId,
            'selectedShift' => $selectedShift,
            'selectedCommittee' => $selectedCommittee,
            'selectedStage' => $selectedStage,
            'groups' => $groups
        ]);
    }

    public function printAttendanceSheet() {
        $db = \Database::getInstance()->getConnection();
        $dept = $_GET['department'] ?? 'Software Engineering';
        $shift = $_GET['shift'] ?? 'Morning';
        $batchId = (int)($_GET['batch_id'] ?? 0);
        $committeeNumber = (int)($_GET['committee_number'] ?? 1);
        $stage = $_GET['stage'] ?? 'Proposal Defence Presentation';
        $date = $_GET['date'] ?? date('Y-m-d');
        $time = $_GET['time'] ?? '09:00 AM';
        $venue = $_GET['venue'] ?? 'FYP Lab';

        $stmtB = $db->prepare("SELECT name FROM academic_batches WHERE id = ?");
        $stmtB->execute([$batchId]);
        $batchName = $stmtB->fetchColumn() ?: '2023';

        $stmtC = $db->prepare("SELECT name, designation FROM committees WHERE department = ? AND committee_number = ? ORDER BY name ASC");
        $stmtC->execute([$dept, $committeeNumber]);
        $evaluators = $stmtC->fetchAll();

        $stmtGroups = $db->prepare("
            SELECT g.id, g.group_code, p.title as project_title, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? AND s.shift = ? AND g.batch_id = ? AND g.committee_number = ? AND p.status = 'Approved'
            ORDER BY g.group_code ASC
        ");
        $stmtGroups->execute([$dept, $shift, $batchId, $committeeNumber]);
        $groups = $stmtGroups->fetchAll();

        foreach ($groups as &$grp) {
            $stmtM = $db->prepare("SELECT s.name, s.student_id FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ? ORDER BY s.student_id ASC");
            $stmtM->execute([$grp['id']]);
            $grp['members'] = $stmtM->fetchAll();
        }

        $this->render('admin/attendance_sheet_print', [
            'department' => $dept,
            'shift' => $shift,
            'batchName' => $batchName,
            'committeeNumber' => $committeeNumber,
            'stage' => $stage,
            'date' => $date,
            'time' => $time,
            'venue' => $venue,
            'evaluators' => $evaluators,
            'groups' => $groups
        ]);
    }

    public function presentationSheets() {
        $db = \Database::getInstance()->getConnection();
        $departments = $this->getAllDepartments();
        $selectedDept = $_GET['department'] ?? 'Software Engineering';
        if (!in_array($selectedDept, $departments)) {
            $selectedDept = $departments[0];
        }

        $stmtBatches = $db->prepare("SELECT * FROM academic_batches WHERE department = ? OR department IS NULL ORDER BY is_active DESC, id DESC");
        $stmtBatches->execute([$selectedDept]);
        $batches = $stmtBatches->fetchAll();

        $selectedBatchId = isset($_GET['batch_id']) ? (int)$_GET['batch_id'] : ($batches[0]['id'] ?? 0);
        $selectedShift = $_GET['shift'] ?? 'Morning';
        $selectedCommittee = isset($_GET['committee_number']) ? (int)$_GET['committee_number'] : 1;
        $selectedStage = $_GET['stage'] ?? 'Proposal Defence Presentation';

        $stmtGroups = $db->prepare("
            SELECT g.id, g.group_code, g.committee_number, p.title as project_title, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? AND s.shift = ? AND g.batch_id = ? AND g.committee_number = ? AND p.status = 'Approved'
            ORDER BY g.group_code ASC
        ");
        $stmtGroups->execute([$selectedDept, $selectedShift, $selectedBatchId, $selectedCommittee]);
        $groups = $stmtGroups->fetchAll();

        foreach ($groups as &$grp) {
            $stmtM = $db->prepare("SELECT s.name, s.student_id FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ?");
            $stmtM->execute([$grp['id']]);
            $grp['members'] = $stmtM->fetchAll();
        }

        $this->render('admin/presentation_sheet_config', [
            'departments' => $departments,
            'selectedDept' => $selectedDept,
            'batches' => $batches,
            'selectedBatchId' => $selectedBatchId,
            'selectedShift' => $selectedShift,
            'selectedCommittee' => $selectedCommittee,
            'selectedStage' => $selectedStage,
            'groups' => $groups
        ]);
    }

    public function printPresentationSheets() {
        $db = \Database::getInstance()->getConnection();
        $dept = $_GET['department'] ?? 'Software Engineering';
        $shift = $_GET['shift'] ?? 'Morning';
        $batchId = (int)($_GET['batch_id'] ?? 0);
        $committeeNumber = (int)($_GET['committee_number'] ?? 1);
        $stage = $_GET['stage'] ?? 'Proposal Defence Presentation';
        $date = $_GET['date'] ?? date('Y-m-d');
        $time = $_GET['time'] ?? '09:00 AM';
        $venue = $_GET['venue'] ?? 'FYP Lab';

        $stmtB = $db->prepare("SELECT name FROM academic_batches WHERE id = ?");
        $stmtB->execute([$batchId]);
        $batchName = $stmtB->fetchColumn() ?: '2023';

        $stmtC = $db->prepare("SELECT name, designation FROM committees WHERE department = ? AND committee_number = ? ORDER BY name ASC");
        $stmtC->execute([$dept, $committeeNumber]);
        $evaluators = $stmtC->fetchAll();

        $stmtGroups = $db->prepare("
            SELECT g.id, g.group_code, p.title as project_title, sup.name as supervisor_name
            FROM `groups` g
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ? AND s.shift = ? AND g.batch_id = ? AND g.committee_number = ? AND p.status = 'Approved'
            ORDER BY g.group_code ASC
        ");
        $stmtGroups->execute([$dept, $shift, $batchId, $committeeNumber]);
        $groups = $stmtGroups->fetchAll();

        foreach ($groups as &$grp) {
            $stmtM = $db->prepare("SELECT s.name, s.student_id FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ? ORDER BY s.student_id ASC");
            $stmtM->execute([$grp['id']]);
            $grp['members'] = $stmtM->fetchAll();
        }

        $this->render('admin/presentation_sheet_print', [
            'department' => $dept,
            'shift' => $shift,
            'batchName' => $batchName,
            'committeeNumber' => $committeeNumber,
            'stage' => $stage,
            'date' => $date,
            'time' => $time,
            'venue' => $venue,
            'evaluators' => $evaluators,
            'groups' => $groups
        ]);
    }

    public function meetings() {
        $db = \Database::getInstance()->getConnection();
        $departments = $this->getAllDepartments();
        $selectedDept = $_GET['department'] ?? 'all';
        $selectedStatus = $_GET['status'] ?? 'all';
        $selectedSupervisor = (int)($_GET['supervisor_id'] ?? 0);

        $where = [];
        $params = [];

        if ($selectedDept !== 'all') {
            $where[] = "s.department = ?";
            $params[] = $selectedDept;
        }
        if ($selectedStatus !== 'all') {
            $where[] = "m.status = ?";
            $params[] = $selectedStatus;
        }
        if ($selectedSupervisor > 0) {
            $where[] = "m.supervisor_id = ?";
            $params[] = $selectedSupervisor;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        $stmt = $db->prepare("
            SELECT m.*, p.title as project_title, g.group_code, s.name as group_leader_name, s.department, sup.name as supervisor_name
            FROM meetings m
            JOIN `groups` g ON m.group_id = g.id
            JOIN projects p ON g.id = p.group_id
            JOIN students s ON g.created_by = s.user_id
            JOIN supervisors sup ON m.supervisor_id = sup.user_id
            $whereClause
            ORDER BY m.meeting_date DESC, m.id DESC
        ");
        $stmt->execute($params);
        $meetings = $stmt->fetchAll();

        $supervisors = $db->query("SELECT user_id, name, department FROM supervisors ORDER BY name ASC")->fetchAll();
        $pendingAuditCount = (int)$db->query("SELECT COUNT(*) FROM meetings WHERE status = 'Completed'")->fetchColumn();

        $this->render('admin/meetings', [
            'meetings' => $meetings,
            'departments' => $departments,
            'supervisors' => $supervisors,
            'selectedDept' => $selectedDept,
            'selectedStatus' => $selectedStatus,
            'selectedSupervisor' => $selectedSupervisor,
            'pendingAuditCount' => $pendingAuditCount
        ]);
    }

    public function verifyMeeting() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $meetingId = (int)($_POST['meeting_id'] ?? 0);
            $status = $_POST['status'] ?? 'Verified';

            if ($meetingId > 0 && in_array($status, ['Verified', 'Completed', 'Cancelled'])) {
                $db = \Database::getInstance()->getConnection();
                $stmt = $db->prepare("UPDATE meetings SET status = ? WHERE id = ?");
                $stmt->execute([$status, $meetingId]);
                $this->flash('success', "Meeting status updated to $status.");
            } else {
                $this->flash('error', 'Invalid meeting verification request.');
            }
        }
        redirect('/admin/meetings');
    }

    private function sendEmail($toEmail, $subject, $message) {
        $mailConfig = require __DIR__ . '/../../config/mail.php';

        if (isset($mailConfig['smtp_username']) && $mailConfig['smtp_username'] !== 'your_email@gmail.com' && !empty($mailConfig['smtp_password'])) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $mailConfig['smtp_host'];
                $mail->SMTPAuth   = $mailConfig['smtp_auth'];
                $mail->Username   = $mailConfig['smtp_username'];
                $mail->Password   = $mailConfig['smtp_password'];
                $mail->SMTPSecure = ($mailConfig['smtp_secure'] === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $mailConfig['smtp_port'];

                $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
                $mail->addAddress($toEmail);

                $mail->isHTML(false);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
            } catch (\Exception $e) {
                error_log("PHPMailer failed in AdminController: " . $mail->ErrorInfo);
            }
        }
    }
}
