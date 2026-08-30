<?php
namespace Controller;

class HodController extends BaseController {

    public function dashboard() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
        
        $stats = [];
        
        $stmtSupCount = $db->prepare("SELECT COUNT(*) FROM supervisors WHERE department = ?");
        $stmtSupCount->execute([$dept]);
        $stats['supervisors'] = $stmtSupCount->fetchColumn();
        
        $stmtCommCount = $db->prepare("SELECT COUNT(*) FROM committees WHERE department = ?");
        $stmtCommCount->execute([$dept]);
        $stats['committee'] = $stmtCommCount->fetchColumn();
        
        $stmtPending = $db->prepare("SELECT COUNT(*) FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ?");
        $stmtPending->execute([$dept]);
        $stats['pending_approvals'] = $stmtPending->fetchColumn();
        
        $stmtGroupsCount = $db->prepare("SELECT COUNT(*) FROM `groups` g JOIN students s ON g.created_by = s.user_id WHERE s.department = ?");
        $stmtGroupsCount->execute([$dept]);
        $stats['total_groups'] = $stmtGroupsCount->fetchColumn();
        
        $stmtCoordCount = $db->prepare("SELECT COUNT(*) FROM coordinators WHERE department = ?");
        $stmtCoordCount->execute([$dept]);
        $stats['coordinators'] = $stmtCoordCount->fetchColumn();

        // FYP Progress Stages Funnel
        $stages = [
            'Proposal Submitted' => 0,
            'Proposal Approved' => 0,
            'Proposal Defence Presentation Completed' => 0,
            'FYP Progress Presentation Completed' => 0,
            'Final Presentation Completed' => 0,
            'Final Grading Completed' => 0
        ];

        $stmtStages = $db->prepare("SELECT g.progress_stage, COUNT(*) as count 
            FROM `groups` g 
            JOIN students s ON g.created_by = s.user_id 
            WHERE s.department = ? 
            GROUP BY g.progress_stage");
        $stmtStages->execute([$dept]);
        $stageResults = $stmtStages->fetchAll();

        foreach ($stageResults as $r) {
            $stageName = $r['progress_stage'];
            if (isset($stages[$stageName])) {
                $stages[$stageName] = (int)$r['count'];
            } elseif ($stageName === 'Account Created' || $stageName === 'Group Created') {
                // If group is created, count under initial setup/proposal
                $stages['Proposal Submitted'] += (int)$r['count'];
            }
        }

        // Supervisor Workload & Capacity Matrix
        $stmtSettings = $db->prepare("SELECT * FROM department_settings WHERE department = ?");
        $stmtSettings->execute([$dept]);
        $deptSettings = $stmtSettings->fetch();
        $maxMorning = $deptSettings ? (int)($deptSettings['max_morning_slots'] ?? 5) : 5;
        $maxEvening = $deptSettings ? (int)($deptSettings['max_evening_slots'] ?? 5) : 5;

        $stmtWorkload = $db->prepare("
            SELECT s.user_id, s.name, s.designation, u.email,
                COALESCE(SUM(CASE WHEN st.shift = 'Morning' OR st.shift IS NULL THEN 1 ELSE 0 END), 0) as morning_projects,
                COALESCE(SUM(CASE WHEN st.shift = 'Evening' THEN 1 ELSE 0 END), 0) as evening_projects,
                COUNT(p.id) as total_projects
            FROM supervisors s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN projects p ON s.user_id = p.supervisor_id
            LEFT JOIN `groups` g ON p.group_id = g.id
            LEFT JOIN students st ON g.created_by = st.user_id
            WHERE s.department = ?
            GROUP BY s.user_id, s.name, s.designation, u.email
            ORDER BY s.name ASC
        ");
        $stmtWorkload->execute([$dept]);
        $supervisorsWorkload = $stmtWorkload->fetchAll();
        
        // Fetch recent supervisors and committee members scoped to this department
        $stmtRecentSup = $db->prepare("SELECT s.*, u.email FROM supervisors s JOIN users u ON s.user_id = u.id WHERE s.department = ? ORDER BY u.created_at DESC LIMIT 5");
        $stmtRecentSup->execute([$dept]);
        $recentSupervisors = $stmtRecentSup->fetchAll();
        
        $stmtRecentComm = $db->prepare("SELECT c.*, u.email FROM committees c JOIN users u ON c.user_id = u.id WHERE c.department = ? ORDER BY u.created_at DESC LIMIT 5");
        $stmtRecentComm->execute([$dept]);
        $recentCommittee = $stmtRecentComm->fetchAll();

        // Get system notices
        $stmtNotices = $db->prepare("SELECT * FROM notices WHERE is_hidden = 0 AND (target_audience = 'All' OR FIND_IN_SET('hod', target_audience) > 0) AND (department = ? OR department IS NULL OR department = '') ORDER BY created_at DESC LIMIT 5");
        $stmtNotices->execute([$dept]);
        $recentNotices = $stmtNotices->fetchAll();

        $this->render('hod/dashboard', [
            'stats' => $stats,
            'stages' => $stages,
            'supervisorsWorkload' => $supervisorsWorkload,
            'maxMorning' => $maxMorning,
            'maxEvening' => $maxEvening,
            'recentSupervisors' => $recentSupervisors,
            'recentCommittee' => $recentCommittee,
            'recentNotices' => $recentNotices,
            'department' => $dept
        ]);
    }

    public function settings() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
        
        $stmtSettings = $db->prepare("SELECT * FROM department_settings WHERE department = ?");
        $stmtSettings->execute([$dept]);
        $settings = $stmtSettings->fetch();
        if (!$settings) {
            $settings = ['max_supervisor_slots' => 5];
        }

        $this->render('hod/settings', [
            'settings' => $settings,
            'department' => $dept
        ]);
    }

    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
            $maxMorningSlots = (int)($_POST['max_morning_slots'] ?? 5);
            $maxEveningSlots = (int)($_POST['max_evening_slots'] ?? 5);
            $maxGroupMembers = (int)($_POST['max_group_members'] ?? 3);

            $stmtOld = $db->prepare("SELECT * FROM department_settings WHERE department = ?");
            $stmtOld->execute([$dept]);
            $oldSettings = $stmtOld->fetch();
            
            $oldMorning = $oldSettings ? (int)$oldSettings['max_morning_slots'] : 5;
            $oldEvening = $oldSettings ? (int)$oldSettings['max_evening_slots'] : 5;
            $oldGroup = $oldSettings ? (int)$oldSettings['max_group_members'] : 3;

            $stmt = $db->prepare("INSERT INTO department_settings (department, max_morning_slots, max_evening_slots, max_group_members) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE max_morning_slots = ?, max_evening_slots = ?, max_group_members = ?");
            $stmt->execute([$dept, $maxMorningSlots, $maxEveningSlots, $maxGroupMembers, $maxMorningSlots, $maxEveningSlots, $maxGroupMembers]);
            
            $changedMorning = ($oldMorning !== $maxMorningSlots);
            $changedEvening = ($oldEvening !== $maxEveningSlots);
            $changedGroup = ($oldGroup !== $maxGroupMembers);

            // Fetch users once if we need to notify
            if ($changedMorning || $changedEvening || $changedGroup) {
                $stmtSups = $db->prepare("SELECT user_id FROM supervisors WHERE department = ?");
                $stmtSups->execute([$dept]);
                $supervisors = $stmtSups->fetchAll();

                $stmtStudents = $db->prepare("SELECT user_id, shift FROM students WHERE department = ?");
                $stmtStudents->execute([$dept]);
                $students = $stmtStudents->fetchAll();

                if ($changedMorning) {
                    $title = "Morning Slots Updated";
                    $msgSup = "Your Morning Shift supervision capacity has been updated to $maxMorningSlots.";
                    $msgStu = "Supervisor capacities for Morning Shift have been updated to $maxMorningSlots.";
                    foreach ($supervisors as $sup) { $this->addNotification($sup['user_id'], $title, $msgSup); }
                    foreach ($students as $stu) {
                        if (($stu['shift'] ?? 'Morning') === 'Morning') {
                            $this->addNotification($stu['user_id'], $title, $msgStu);
                        }
                    }
                }

                if ($changedEvening) {
                    $title = "Evening Slots Updated";
                    $msgSup = "Your Evening Shift supervision capacity has been updated to $maxEveningSlots.";
                    $msgStu = "Supervisor capacities for Evening Shift have been updated to $maxEveningSlots.";
                    foreach ($supervisors as $sup) { $this->addNotification($sup['user_id'], $title, $msgSup); }
                    foreach ($students as $stu) {
                        if (($stu['shift'] ?? '') === 'Evening') {
                            $this->addNotification($stu['user_id'], $title, $msgStu);
                        }
                    }
                }

                if ($changedGroup) {
                    $title = "Group Member Limit Updated";
                    $msg = "The maximum number of members allowed in a student project group has been updated to $maxGroupMembers.";
                    foreach ($supervisors as $sup) { $this->addNotification($sup['user_id'], $title, $msg); }
                    foreach ($students as $stu) { $this->addNotification($stu['user_id'], $title, $msg); }
                }
            }

            $_SESSION['flash']['success'] = "Department settings updated successfully.";
            redirect('/hod/settings');
        }
    }

    public function supervisors() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
        
        $stmt = $db->prepare("SELECT s.*, u.email, u.cnic,
            (SELECT COUNT(*) FROM projects p WHERE p.supervisor_id = s.user_id) as active_projects
            FROM supervisors s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.department = ? 
            ORDER BY s.name ASC");
        $stmt->execute([$dept]);
        $supervisors = $stmt->fetchAll();
        
        $this->render('hod/supervisors', [
            'supervisors' => $supervisors,
            'department' => $dept
        ]);
    }

    public function createSupervisor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            $designation = trim($_POST['designation'] ?? '');
            $department = $dept; // Auto-lock to HOD's department

            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($password) || empty($designation)) {
                $this->flash('error', 'Please fill in all required fields.');
                redirect('/hod/supervisors');
            }

            $cnic = str_replace('-', '', $cnic);

            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->flash('error', 'Email is already registered.');
                redirect('/hod/supervisors');
            }

            // Check if CNIC already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE cnic = ?");
            $stmt->execute([$cnic]);
            if ($stmt->fetch()) {
                $this->flash('error', 'CNIC is already registered.');
                redirect('/hod/supervisors');
            }

            try {
                $db->beginTransaction();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, 'supervisor', 'approved')");
                $stmt->execute([$email, $cnic, $hashed]);
                $userId = $db->lastInsertId();

                $fullName = $firstName . ' ' . $lastName;
                $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                $stmt->execute([$userId, $fullName, $designation, $department]);

                // Sync profiles table
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '1980-01-01', '+92', ?, 'Not Provided Yet', 'Male')");
                $stmtP->execute([$userId, $lastName, $cnic, !empty($contactNo) ? $contactNo : '03000000000']);

                $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Supervisor');

                $db->commit();
                $this->flash('success', "Supervisor $fullName added successfully to Department of $department and credentials sent.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error adding supervisor. Please try again.');
            }
        }
        redirect('/hod/supervisors');
    }

    public function editSupervisor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $userId = $_POST['user_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($userId && $name && $designation) {
                // Verify supervisor is in HOD's department
                $stmtCheck = $db->prepare("SELECT department FROM supervisors WHERE user_id = ?");
                $stmtCheck->execute([$userId]);
                if ($stmtCheck->fetchColumn() !== $dept) {
                    $this->flash('error', 'Unauthorized: Supervisor is not in your department.');
                    redirect('/hod/supervisors');
                }

                $stmt = $db->prepare("UPDATE supervisors SET name = ?, designation = ? WHERE user_id = ?");
                $stmt->execute([$name, $designation, $userId]);

                if (!empty($email)) {
                    $stmtU = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                    $stmtU->execute([$email, $userId]);
                }

                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmtPass = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmtPass->execute([$hashed, $userId]);
                }
                
                $this->flash('success', "Supervisor profile updated.");
            } else {
                $this->flash('error', "Failed to update supervisor profile. Fill all required fields.");
            }
        }
        redirect('/hod/supervisors');
    }

    public function deleteSupervisor() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $stmtCheck = $db->prepare("SELECT department FROM supervisors WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            if ($stmtCheck->fetchColumn() === $dept) {
                try {
                    $db->beginTransaction();
                    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$id]);
                    $db->commit();
                    $this->flash('success', "Supervisor deleted successfully.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Failed to delete supervisor. Please try again.');
                }
            } else {
                $this->flash('error', 'Unauthorized: Supervisor is not in your department.');
            }
        }
        redirect('/hod/supervisors');
    }

    public function committee() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

        $stmt = $db->prepare("SELECT c.*, u.email, u.cnic FROM committees c JOIN users u ON c.user_id = u.id WHERE c.department = ? ORDER BY c.name ASC");
        $stmt->execute([$dept]);
        $committees = $stmt->fetchAll();
        
        $this->render('hod/committee', [
            'committees' => $committees,
            'department' => $dept
        ]);
    }

    public function createCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            $department = $dept; // Auto-lock to HOD department

            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($designation) || empty($password)) {
                $this->flash('error', 'All fields are required.');
                redirect('/hod/committee');
            }

            $cnic = str_replace('-', '', $cnic);

            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->flash('error', 'Email is already registered.');
                redirect('/hod/committee');
            }

            // Check if CNIC already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE cnic = ?");
            $stmt->execute([$cnic]);
            if ($stmt->fetch()) {
                $this->flash('error', 'CNIC is already registered.');
                redirect('/hod/committee');
            }

            try {
                $db->beginTransaction();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, 'committee', 'approved')");
                $stmt->execute([$email, $cnic, $hashed]);
                $userId = $db->lastInsertId();

                $fullName = $firstName . ' ' . $lastName;
                $stmt = $db->prepare("INSERT INTO committees (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                $stmt->execute([$userId, $fullName, $designation, $department]);

                // Sync profiles table
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '1980-01-01', '+92', ?, 'Not Provided Yet', 'Male')");
                $stmtP->execute([$userId, $lastName, $cnic, !empty($contactNo) ? $contactNo : '03000000000']);

                $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Committee Member');

                $db->commit();
                $this->flash('success', "Committee Member $fullName added successfully to Department of $department and credentials sent.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error adding committee member. Please try again.');
            }
        }
        redirect('/hod/committee');
    }

    public function editCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $userId = $_POST['user_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($userId && $name) {
                $stmtCheck = $db->prepare("SELECT department FROM committees WHERE user_id = ?");
                $stmtCheck->execute([$userId]);
                if ($stmtCheck->fetchColumn() !== $dept) {
                    $this->flash('error', 'Unauthorized: Committee member is not in your department.');
                    redirect('/hod/committee');
                }

                $stmt = $db->prepare("UPDATE committees SET name = ?, designation = ? WHERE user_id = ?");
                $stmt->execute([$name, $designation, $userId]);

                if (!empty($email)) {
                    $stmtU = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                    $stmtU->execute([$email, $userId]);
                }

                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmtPass = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmtPass->execute([$hashed, $userId]);
                }
                
                $this->flash('success', "Committee member details updated.");
            } else {
                $this->flash('error', "All fields are required.");
            }
        }
        redirect('/hod/committee');
    }

    public function deleteCommittee() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $stmtCheck = $db->prepare("SELECT department FROM committees WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            if ($stmtCheck->fetchColumn() === $dept) {
                try {
                    $db->beginTransaction();
                    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$id]);
                    $db->commit();
                    $this->flash('success', "Committee member deleted successfully.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Failed to delete committee member. Please try again.');
                }
            } else {
                $this->flash('error', 'Unauthorized: Committee member is not in your department.');
            }
        }
        redirect('/hod/committee');
    }

    private function getHodDepartment($db, $userId) {
        $stmt = $db->prepare("SELECT department FROM hods WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function verifyStudents() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
        
        $students = $db->prepare("SELECT s.*, u.email, u.status, u.created_at as registered_at 
            FROM students s 
            JOIN users u ON s.user_id = u.id 
            WHERE u.status = 'pending' AND s.department = ? 
            ORDER BY u.created_at DESC");
        $students->execute([$dept]);
        $pending = $students->fetchAll();
        
        $this->render('hod/verify_students', [
            'students' => $pending,
            'department' => $dept
        ]);
    }

    public function approveStudent() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
            
            $stmtCheck = $db->prepare("SELECT department FROM students WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            $studentDept = $stmtCheck->fetchColumn();
            
            if ($studentDept === $dept) {
                $stmt = $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
                $stmt->execute([$id]);
                
                // Fetch student details for email
                $stmtUser = $db->prepare("
                    SELECT u.email, s.student_id, s.name 
                    FROM users u 
                    JOIN students s ON u.id = s.user_id 
                    WHERE u.id = ?
                ");
                $stmtUser->execute([$id]);
                $user = $stmtUser->fetch();

                if ($user) {
                    $this->addNotification($id, 'Account Approved', 'Your registration has been approved by your HOD! You can now log in.');
                    
                    $subject = "Your Account has been Approved";
                    $identifierStr = "Roll Number: " . $user['student_id'] . "\nPassword: (The password you chose during registration)";
                    
                    $message = "Hello " . ($user['name'] ?? 'Student') . ",\n\nYour account on the FYP Management Portal has been approved by your Head of Department ($dept).\n\n"
                             . "Your Login Credentials:\n"
                             . $identifierStr . "\n\n"
                             . "You can now log in to the portal.\n\nRegards,\nFYP Management Team";
                             
                    $this->sendEmail($user['email'], $subject, $message);
                }

                $this->flash('success', 'Student account approved successfully.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/hod/students/verify');
    }

    public function approveAllStudents() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $stmtPending = $db->prepare("SELECT s.user_id, s.name, s.student_id, u.email 
                FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE u.status = 'pending' AND s.department = ?");
            $stmtPending->execute([$dept]);
            $pendingStudents = $stmtPending->fetchAll();

            if (empty($pendingStudents)) {
                $this->flash('info', 'No pending student accounts found to approve.');
                redirect('/hod/students/verify');
            }

            try {
                $db->beginTransaction();

                $stmtUpdate = $db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
                foreach ($pendingStudents as $stu) {
                    $stmtUpdate->execute([$stu['user_id']]);
                    $this->addNotification($stu['user_id'], 'Account Approved', 'Your registration has been approved by your HOD! You can now log in.');
                    
                    $subject = "Your Account has been Approved";
                    $identifierStr = "Roll Number: " . $stu['student_id'] . "\nPassword: (The password you chose during registration)";
                    $message = "Hello " . $stu['name'] . ",\n\nYour account on the FYP Management Portal has been approved by your Head of Department ($dept).\n\n"
                             . "Your Login Credentials:\n"
                             . $identifierStr . "\n\n"
                             . "You can now log in to the portal.\n\nRegards,\nFYP Management Team";
                             
                    $this->sendEmail($stu['email'], $subject, $message);
                }

                $db->commit();
                $count = count($pendingStudents);
                $this->flash('success', "Successfully approved all $count pending student registration(s).");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error approving students. Please try again.');
            }
        }
        redirect('/hod/students/verify');
    }

    public function rejectStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $id = $_POST['id'] ?? null;
            $reason = trim($_POST['reason'] ?? '');
            
            if ($id && $reason) {
                $db = \Database::getInstance()->getConnection();
                $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
                
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
                    
                    $stmtUser = $db->prepare("SELECT email, (SELECT name FROM students WHERE user_id = ?) as student_name FROM users WHERE id = ?");
                    $stmtUser->execute([$id, $id]);
                    $userData = $stmtUser->fetch();
                    $userEmail = $userData ? $userData['email'] : null;
                    
                    if ($userEmail) {
                        $subject = "Your Registration has been Rejected";
                        $message = "Hello " . ($userData['student_name'] ?? 'Student') . ",\n\nUnfortunately, your registration for the FYP Management Portal has been rejected by your HOD.\n\n"
                                 . "Reason for rejection:\n$reason\n\n"
                                 . "Please correct the issues mentioned above and create a new account, or contact your department if you believe this was a mistake.\n\n"
                                 . "Regards,\nFYP Management Team";
                        $this->sendEmail($userEmail, $subject, $message);
                    }

                    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$id]);
                    $this->flash('success', 'Student registration rejected and deleted.');
                } else {
                    $this->flash('error', 'Unauthorized: Student is not in your department.');
                }
            } else {
                $this->flash('error', 'Rejection reason is required.');
            }
        }
        redirect('/hod/students/verify');
    }

    public function projects() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

        $stmt = $db->prepare("
            SELECT g.id as group_id, g.group_code, g.progress_stage, g.created_at,
                   p.id as project_id, p.title as project_title, p.description as abstract, p.status as project_status, p.thesis_file,
                   pr.id as proposal_id, pr.file_path as proposal_file, pr.status as proposal_status,
                   sup.name as supervisor_name, sup.designation as supervisor_designation
            FROM `groups` g
            JOIN students s ON g.created_by = s.user_id
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN proposals pr ON g.id = pr.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE s.department = ?
            ORDER BY g.created_at DESC
        ");
        $stmt->execute([$dept]);
        $projects = $stmt->fetchAll();

        // Fetch team members for each group
        foreach ($projects as &$proj) {
            $stmtM = $db->prepare("
                SELECT s.user_id, s.student_id as roll_no, s.name as student_name, s.avatar, s.shift, u.email
                FROM group_members gm
                JOIN students s ON gm.student_id = s.user_id
                JOIN users u ON s.user_id = u.id
                WHERE gm.group_id = ?
            ");
            $stmtM->execute([$proj['group_id']]);
            $proj['members'] = $stmtM->fetchAll();
        }

        $this->render('hod/projects', [
            'projects' => $projects,
            'department' => $dept
        ]);
    }

    public function coordinators() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
        
        $coordinators = $db->prepare("SELECT c.*, u.email, u.cnic FROM coordinators c JOIN users u ON c.user_id = u.id WHERE c.department = ? ORDER BY c.name ASC");
        $coordinators->execute([$dept]);
        $coordinators = $coordinators->fetchAll();
        
        $this->render('hod/coordinators', [
            'coordinators' => $coordinators,
            'department' => $dept
        ]);
    }

    public function createCoordinator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($password) || empty($designation)) {
                $this->flash('error', 'All fields are required.');
                redirect('/hod/coordinators');
            }
            
            $cnic = str_replace('-', '', $cnic);
            
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
            
            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->flash('error', 'Email is already registered.');
                redirect('/hod/coordinators');
            }
            
            // Check if CNIC already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE cnic = ?");
            $stmt->execute([$cnic]);
            if ($stmt->fetch()) {
                $this->flash('error', 'CNIC is already registered.');
                redirect('/hod/coordinators');
            }
            
            try {
                $db->beginTransaction();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, 'coordinator', 'approved')");
                $stmt->execute([$email, $cnic, $hashed]);
                $userId = $db->lastInsertId();
                
                $fullName = $firstName . ' ' . $lastName;
                $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                $stmt->execute([$userId, $fullName, $designation, $dept]);
                
                // Keep profiles table in sync
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '1985-01-01', '+92', '03000000000', 'Not Provided Yet', 'Male')");
                $stmtP->execute([$userId, $lastName, $cnic]);

                $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Coordinator');
                
                $db->commit();
                $this->flash('success', "Coordinator $fullName created successfully under department $dept and credentials sent.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error creating coordinator. Please try again.');
            }
        }
        redirect('/hod/coordinators');
    }

    public function editCoordinator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $userId = $_POST['user_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (!$userId || empty($name) || empty($email) || empty($cnic)) {
                $this->flash('error', 'Required fields are missing.');
                redirect('/hod/coordinators');
            }
            
            $cnic = str_replace('-', '', $cnic);
            
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
            
            // Check that coordinator belongs to HOD's department
            $stmtCheck = $db->prepare("SELECT department FROM coordinators WHERE user_id = ?");
            $stmtCheck->execute([$userId]);
            $coordDept = $stmtCheck->fetchColumn();
            
            if ($coordDept !== $dept) {
                $this->flash('error', 'Unauthorized: Coordinator is not in your department.');
                redirect('/hod/coordinators');
            }
            
            try {
                $db->beginTransaction();
                
                // Update users
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ?, password = ? WHERE id = ?");
                    $stmt->execute([$email, $cnic, $hashed, $userId]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ? WHERE id = ?");
                    $stmt->execute([$email, $cnic, $userId]);
                }
                
                // Update coordinators
                $stmt = $db->prepare("UPDATE coordinators SET name = ? WHERE user_id = ?");
                $stmt->execute([$name, $userId]);
                
                // Update profiles
                $stmtP = $db->prepare("UPDATE profiles SET cnic = ?, surname = ? WHERE user_id = ?");
                $stmtP->execute([$cnic, $name, $userId]);
                
                $db->commit();
                $this->flash('success', "Coordinator details updated successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error updating coordinator. Please try again.');
            }
        }
        redirect('/hod/coordinators');
    }

    public function deleteCoordinator() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
            
            // Check that coordinator belongs to HOD's department
            $stmtCheck = $db->prepare("SELECT department FROM coordinators WHERE user_id = ?");
            $stmtCheck->execute([$id]);
            $coordDept = $stmtCheck->fetchColumn();
            
            if ($coordDept === $dept) {
                try {
                    $db->beginTransaction();
                    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$id]);
                    $db->commit();
                    $this->flash('success', "Coordinator deleted successfully.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Error deleting coordinator. Please try again.');
                }
            } else {
                $this->flash('error', 'Unauthorized: Coordinator is not in your department.');
            }
        }
        redirect('/hod/coordinators');
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch HOD details
        $stmt = $db->prepare("SELECT h.name, h.department, u.email, u.cnic FROM hods h JOIN users u ON h.user_id = u.id WHERE h.user_id = ?");
        $stmt->execute([$userId]);
        $hod = $stmt->fetch();
        if (!$hod) {
            die("HOD profile not found.");
        }

        // Get existing profile info
        $stmt = $db->prepare("SELECT * FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $errors = [];
            $prefix = trim($_POST['prefix'] ?? '');
            $mobile_code = trim($_POST['mobile_code'] ?? '');
            $mobile_no = trim($_POST['mobile_no'] ?? '');
            $home_address = trim($_POST['home_address'] ?? '');
            
            // Check if CNIC was missing and is now submitted
            $cnic = trim($_POST['cnic'] ?? '');
            $hasCnicInDb = !empty($hod['cnic']);
            $cnicToSave = $hod['cnic'];

            if (empty($prefix)) $errors[] = "Prefix is required.";
            if (empty($mobile_code)) $errors[] = "Mobile Code is required.";
            if (empty($mobile_no)) $errors[] = "Mobile Number is required.";
            if (empty($home_address) || $home_address === 'Not Provided Yet') $errors[] = "Home/Office Address is required.";

            if (!$hasCnicInDb) {
                if (empty($cnic)) {
                    $errors[] = "CNIC is required.";
                } else {
                    $cnic = str_replace('-', '', $cnic);
                    if (!preg_match('/^[0-9]+$/', $cnic)) {
                        $errors[] = "CNIC must contain numbers only.";
                    } else {
                        // Check uniqueness
                        $stmtCheck = $db->prepare("SELECT id FROM users WHERE cnic = ? AND id != ?");
                        $stmtCheck->execute([$cnic, $userId]);
                        if ($stmtCheck->fetch()) {
                            $errors[] = "This CNIC is already registered.";
                        } else {
                            $cnicToSave = $cnic;
                        }
                    }
                }
            }

            // Check if Surname was missing and is now submitted
            $surname = trim($_POST['surname'] ?? '');
            $hasSurnameInDb = !empty($profile['surname']);
            $surnameToSave = $profile['surname'] ?? '';
            if (!$hasSurnameInDb) {
                if (empty($surname)) {
                    $errors[] = "Surname is required.";
                } else {
                    $surnameToSave = $surname;
                }
            }

            if (empty($errors)) {
                try {
                    $db->beginTransaction();

                    // Update profiles table
                    $stmt = $db->prepare("UPDATE profiles SET prefix = ?, mobile_code = ?, mobile_no = ?, home_address = ?, cnic = ?, surname = ? WHERE user_id = ?");
                    $stmt->execute([$prefix, $mobile_code, $mobile_no, $home_address, $cnicToSave, $surnameToSave, $userId]);

                    // Update users table cnic if it was updated
                    if (!$hasCnicInDb) {
                        $stmt = $db->prepare("UPDATE users SET cnic = ? WHERE id = ?");
                        $stmt->execute([$cnicToSave, $userId]);
                    }

                    $db->commit();
                    $this->flash('success', 'Profile updated successfully.');
                    redirect('/hod/profile');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Database error. Please try again.');
                }
            } else {
                $this->flash('error', implode(" ", $errors));
            }
        }

        $this->render('hod/profile', [
            'hod' => $hod,
            'profile' => $profile
        ]);
    }

    private function sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, $portalType) {
        $hodUserId = $_SESSION['user_id'] ?? 0;
        $stmtH = $db->prepare("SELECT name, department FROM hods WHERE user_id = ?");
        $stmtH->execute([$hodUserId]);
        $hodData = $stmtH->fetch();
        $hodName = $hodData['name'] ?? 'HOD';
        $deptName = $hodData['department'] ?? 'FET';

        $fullName = $firstName . ' ' . $lastName;
        
        $subject = "Your Portal Credentials - Faculty of Engineering & Technology";
        $messageBody = "Dear $firstName $lastName,\n\n"
                     . "Your account has been created by HOD $hodName for the $portalType Portal (Department of $deptName).\n\n"
                     . "Here are your login credentials:\n"
                     . "Portal Type: $portalType Portal\n"
                     . "CNIC (User ID): $cnic\n"
                     . "Email Address: $email\n"
                     . "Password: $password\n\n"
                     . "Please use your CNIC and the password above to log in to the portal.\n\n"
                     . "Sent by HOD $hodName\n"
                     . "Faculty of Engineering & Technology\n"
                     . "University of Sindh";

        // 1. Log credentials locally to a simulated email inbox log
        $logDir = __DIR__ . '/../../sessions';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0700, true);
        }
        $logFile = $logDir . '/credentials_emails.log';
        $logContent = "==================================================\n"
                    . "Date: " . date('Y-m-d H:i:s') . "\n"
                    . "To: $email ($fullName)\n"
                    . "Subject: $subject\n"
                    . "--------------------------------------------------\n"
                    . $messageBody . "\n"
                    . "==================================================\n\n";
        file_put_contents($logFile, $logContent, FILE_APPEND);

        // 2. Dispatch real email via PHPMailer
        $this->sendEmail($email, $subject, $messageBody);

        // 3. Add system notification inside the database for welcome banner
        $welcomeTitle = "Welcome to the $portalType Portal";
        $welcomeMsg = "Welcome! Your account credentials have been generated by HOD $hodName. You can update your editable profile information under the My Profile menu.";
        $stmtN = $db->prepare("INSERT INTO notifications (user_id, title, message, redirect_url) VALUES (?, ?, ?, ?)");
        $stmtN->execute([$userId, $welcomeTitle, $welcomeMsg, '/' . strtolower(str_replace(' Member', '', $portalType)) . '/profile']);
    }

    private function sendEmail($toEmail, $subject, $message) {
        $mailConfig = require __DIR__ . '/../../config/mail.php';

        if (isset($mailConfig['smtp_username']) && $mailConfig['smtp_username'] !== 'your_email@gmail.com' && !empty($mailConfig['smtp_password'])) {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $mailConfig['smtp_host'];
                $mail->SMTPAuth   = $mailConfig['smtp_auth'];
                $mail->Username   = $mailConfig['smtp_username'];
                $mail->Password   = $mailConfig['smtp_password'];
                $mail->SMTPSecure = ($mailConfig['smtp_secure'] === 'ssl') ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $mailConfig['smtp_port'];

                $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
                $mail->addAddress($toEmail);

                $mail->isHTML(false);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
            } catch (\Exception $e) {
                error_log("PHPMailer failed in HodController: " . $mail->ErrorInfo);
            }
        }
    }
}

