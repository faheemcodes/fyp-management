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
        
        $stmtGroupsCount = $db->prepare("SELECT COUNT(*) FROM `groups` g JOIN students s ON g.created_by = s.user_id JOIN academic_batches b ON g.batch_id = b.id WHERE s.department = ? AND b.is_active = 1");
        $stmtGroupsCount->execute([$dept]);
        $stats['total_groups'] = $stmtGroupsCount->fetchColumn();
        
        $stmtCoordCount = $db->prepare("SELECT COUNT(*) FROM coordinators WHERE department = ?");
        $stmtCoordCount->execute([$dept]);
        $stats['coordinators'] = $stmtCoordCount->fetchColumn();

        // FYP Progress Stages Funnel (Active batches only)
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
            JOIN academic_batches b ON g.batch_id = b.id
            WHERE s.department = ? AND b.is_active = 1
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

        // Supervisor Workload & Capacity Matrix (Active batches only)
        $stmtSettings = $db->prepare("SELECT * FROM department_settings WHERE department = ?");
        $stmtSettings->execute([$dept]);
        $deptSettings = $stmtSettings->fetch();
        $maxMorning = $deptSettings ? (int)($deptSettings['max_morning_slots'] ?? 5) : 5;
        $maxEvening = $deptSettings ? (int)($deptSettings['max_evening_slots'] ?? 5) : 5;

        $stmtWorkload = $db->prepare("
            SELECT s.user_id, s.name, s.designation, u.email,
                COALESCE(SUM(CASE WHEN (st.shift = 'Morning' OR st.shift IS NULL) AND b.is_active = 1 THEN 1 ELSE 0 END), 0) as morning_projects,
                COALESCE(SUM(CASE WHEN st.shift = 'Evening' AND b.is_active = 1 THEN 1 ELSE 0 END), 0) as evening_projects,
                COALESCE(SUM(CASE WHEN b.is_active = 1 THEN 1 ELSE 0 END), 0) as total_projects
            FROM supervisors s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN projects p ON s.user_id = p.supervisor_id
            LEFT JOIN `groups` g ON p.group_id = g.id
            LEFT JOIN academic_batches b ON g.batch_id = b.id
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
            $numCommittees = max(1, min(10, (int)($_POST['num_committees'] ?? 2)));

            $stmtOld = $db->prepare("SELECT * FROM department_settings WHERE department = ?");
            $stmtOld->execute([$dept]);
            $oldSettings = $stmtOld->fetch();
            
            $oldMorning = $oldSettings ? (int)$oldSettings['max_morning_slots'] : 5;
            $oldEvening = $oldSettings ? (int)$oldSettings['max_evening_slots'] : 5;
            $oldGroup = $oldSettings ? (int)$oldSettings['max_group_members'] : 3;
            $oldNumCommittees = $oldSettings ? (int)($oldSettings['num_committees'] ?? 2) : 2;

            $stmt = $db->prepare("INSERT INTO department_settings (department, max_morning_slots, max_evening_slots, max_group_members, num_committees) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE max_morning_slots = ?, max_evening_slots = ?, max_group_members = ?, num_committees = ?");
            $stmt->execute([$dept, $maxMorningSlots, $maxEveningSlots, $maxGroupMembers, $numCommittees, $maxMorningSlots, $maxEveningSlots, $maxGroupMembers, $numCommittees]);
            
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

        // Fetch department capacity settings
        $stmtSettings = $db->prepare("SELECT * FROM department_settings WHERE department = ?");
        $stmtSettings->execute([$dept]);
        $deptSettings = $stmtSettings->fetch();
        $maxMorning = $deptSettings ? (int)($deptSettings['max_morning_slots'] ?? 5) : 5;
        $maxEvening = $deptSettings ? (int)($deptSettings['max_evening_slots'] ?? 5) : 5;
        
        $stmt = $db->prepare("
            SELECT s.*, u.email, u.cnic,
                p.prefix, p.surname, p.mobile_code, p.mobile_no,
                COALESCE(SUM(CASE WHEN p_proj.id IS NOT NULL AND (st.shift = 'Morning' OR st.shift IS NULL) THEN 1 ELSE 0 END), 0) as morning_projects,
                COALESCE(SUM(CASE WHEN p_proj.id IS NOT NULL AND st.shift = 'Evening' THEN 1 ELSE 0 END), 0) as evening_projects,
                COUNT(p_proj.id) as active_projects
            FROM supervisors s 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN profiles p ON s.user_id = p.user_id
            LEFT JOIN projects p_proj ON s.user_id = p_proj.supervisor_id
            LEFT JOIN `groups` g ON p_proj.group_id = g.id
            LEFT JOIN students st ON g.created_by = st.user_id
            WHERE s.department = ? 
            GROUP BY s.user_id, s.name, s.designation, s.department, u.email, u.cnic, p.prefix, p.surname, p.mobile_code, p.mobile_no
            ORDER BY s.name ASC
        ");
        $stmt->execute([$dept]);
        $supervisors = $stmt->fetchAll();
        
        $this->render('hod/supervisors', [
            'supervisors' => $supervisors,
            'department' => $dept,
            'maxMorning' => $maxMorning,
            'maxEvening' => $maxEvening
        ]);
    }

    public function createSupervisor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $prefix = trim($_POST['prefix'] ?? 'Mr.');
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $mobileCode = trim($_POST['mobile_code'] ?? '+92');
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

                // Store First Name in supervisors.name
                $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                $stmt->execute([$userId, $firstName, $designation, $department]);

                // Sync profiles table with prefix and surname
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1980-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                $stmtP->execute([$userId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Supervisor');

                $db->commit();
                $display = formatPersonName($prefix, $firstName, $lastName);
                $this->flash('success', "Supervisor $display added successfully to Department of $department and credentials sent.");
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

            $userId = (int)($_POST['user_id'] ?? 0);
            $prefix = trim($_POST['prefix'] ?? 'Mr.');
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $mobileCode = trim($_POST['mobile_code'] ?? '+92');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($userId && $firstName && $lastName && $designation && $email) {
                // Verify supervisor is in HOD's department
                $stmtCheck = $db->prepare("SELECT department FROM supervisors WHERE user_id = ?");
                $stmtCheck->execute([$userId]);
                if ($stmtCheck->fetchColumn() !== $dept) {
                    $this->flash('error', 'Unauthorized: Supervisor is not in your department.');
                    redirect('/hod/supervisors');
                }

                $cnic = str_replace('-', '', $cnic);

                // Check email uniqueness
                $stmtEmail = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmtEmail->execute([$email, $userId]);
                if ($stmtEmail->fetch()) {
                    $this->flash('error', 'The email is already registered to another user.');
                    redirect('/hod/supervisors');
                }

                // Check CNIC uniqueness
                if (!empty($cnic)) {
                    $stmtCnic = $db->prepare("SELECT id FROM users WHERE cnic = ? AND id != ?");
                    $stmtCnic->execute([$cnic, $userId]);
                    if ($stmtCnic->fetch()) {
                        $this->flash('error', 'The CNIC is already registered to another user.');
                        redirect('/hod/supervisors');
                    }
                }

                try {
                    $db->beginTransaction();

                    $stmt = $db->prepare("UPDATE supervisors SET name = ?, designation = ? WHERE user_id = ?");
                    $stmt->execute([$firstName, $designation, $userId]);

                    $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) 
                        VALUES (?, ?, ?, ?, '1980-01-01', ?, ?, 'Not Provided Yet', 'Male') 
                        ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), cnic = VALUES(cnic), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                    $stmtP->execute([$userId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                    if (!empty($cnic)) {
                        $stmtU = $db->prepare("UPDATE users SET email = ?, cnic = ? WHERE id = ?");
                        $stmtU->execute([$email, $cnic, $userId]);
                    } else {
                        $stmtU = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                        $stmtU->execute([$email, $userId]);
                    }

                    if (!empty($password)) {
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmtPass = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmtPass->execute([$hashed, $userId]);
                    }

                    // Sync name across committee and coordinator roles if assigned
                    $stmtSyncComm = $db->prepare("UPDATE committees SET name = ? WHERE user_id = ?");
                    $stmtSyncComm->execute([$firstName, $userId]);

                    $stmtSyncCoord = $db->prepare("UPDATE coordinators SET name = ? WHERE user_id = ?");
                    $stmtSyncCoord->execute([$firstName, $userId]);

                    $db->commit();
                    $this->flash('success', "Supervisor profile updated successfully.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Failed to update supervisor profile. Please try again.');
                }
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

        $stmt = $db->prepare("
            SELECT c.*, u.email, u.cnic,
                   p.prefix, p.surname, p.mobile_code, p.mobile_no
            FROM committees c 
            JOIN users u ON c.user_id = u.id 
            LEFT JOIN profiles p ON c.user_id = p.user_id
            WHERE c.department = ? 
            ORDER BY c.committee_number ASC, c.name ASC
        ");
        $stmt->execute([$dept]);
        $committees = $stmt->fetchAll();

        $stmtDept = $db->prepare("SELECT num_committees FROM department_settings WHERE department = ?");
        $stmtDept->execute([$dept]);
        $numCommittees = (int)($stmtDept->fetchColumn() ?: 2);

        // Fetch registered supervisors in this department not enrolled in committees
        $stmtAvailableSups = $db->prepare("
            SELECT s.user_id, s.name, s.designation, s.department, u.email, u.cnic,
                   p.prefix, p.surname, p.mobile_code, p.mobile_no
            FROM supervisors s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN profiles p ON s.user_id = p.user_id
            WHERE s.department = ?
              AND s.user_id NOT IN (SELECT user_id FROM committees WHERE department = ?)
            ORDER BY s.name ASC
        ");
        $stmtAvailableSups->execute([$dept, $dept]);
        $availableSupervisors = $stmtAvailableSups->fetchAll();
        
        $this->render('hod/committee', [
            'committees' => $committees,
            'num_committees' => $numCommittees,
            'available_supervisors' => $availableSupervisors,
            'department' => $dept
        ]);
    }

    public function createCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $supervisorUserId = (int)($_POST['supervisor_user_id'] ?? 0);
            $prefix = trim($_POST['prefix'] ?? 'Mr.');
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $mobileCode = trim($_POST['mobile_code'] ?? '+92');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            $committeeNumber = max(1, (int)($_POST['committee_number'] ?? 1));
            $department = $dept; // Auto-lock to HOD department

            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($designation) || empty($password)) {
                $this->flash('error', 'All fields are required.');
                redirect('/hod/committee');
            }

            $cnic = str_replace('-', '', $cnic);

            // If an existing supervisor was selected
            if ($supervisorUserId > 0) {
                $stmtCheck = $db->prepare("SELECT s.*, u.email, u.cnic FROM supervisors s JOIN users u ON s.user_id = u.id WHERE s.user_id = ? AND s.department = ?");
                $stmtCheck->execute([$supervisorUserId, $dept]);
                $sup = $stmtCheck->fetch();
                if (!$sup) {
                    $this->flash('error', 'Selected supervisor not found in your department.');
                    redirect('/hod/committee');
                }

                // Check if already in committees
                $stmtCommCheck = $db->prepare("SELECT user_id FROM committees WHERE user_id = ?");
                $stmtCommCheck->execute([$supervisorUserId]);
                if ($stmtCommCheck->fetch()) {
                    $this->flash('error', 'This supervisor is already enrolled as a committee member.');
                    redirect('/hod/committee');
                }

                try {
                    $db->beginTransaction();
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUp = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmtUp->execute([$hashed, $supervisorUserId]);

                    $stmt = $db->prepare("INSERT INTO committees (user_id, name, designation, department, committee_number) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$supervisorUserId, $firstName, $designation ?: $sup['designation'], $department, $committeeNumber]);

                    $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1980-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                    $stmtP->execute([$supervisorUserId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                    $this->sendCredentialsMessage($db, $supervisorUserId, $firstName, $lastName, $sup['email'], $sup['cnic'], $password, 'Committee Member');

                    $db->commit();
                    $display = formatPersonName($prefix, $firstName, $lastName);
                    $this->flash('success', "Supervisor {$display} has been successfully appointed to Committee {$committeeNumber}.");
                    redirect('/hod/committee');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Error adding committee member. Please try again.');
                    redirect('/hod/committee');
                }
            } else {
                // Manual creation: Check if email already belongs to a supervisor in this department
                $stmtSupEmail = $db->prepare("SELECT s.user_id, s.name, s.department FROM supervisors s JOIN users u ON s.user_id = u.id WHERE u.email = ?");
                $stmtSupEmail->execute([$email]);
                $matchedSup = $stmtSupEmail->fetch();

                if ($matchedSup) {
                    if ($matchedSup['department'] !== $dept) {
                        $this->flash('error', 'Email belongs to a supervisor in another department.');
                        redirect('/hod/committee');
                    }
                    $stmtCommCheck = $db->prepare("SELECT user_id FROM committees WHERE user_id = ?");
                    $stmtCommCheck->execute([$matchedSup['user_id']]);
                    if ($stmtCommCheck->fetch()) {
                        $this->flash('error', 'This supervisor is already enrolled as a committee member.');
                        redirect('/hod/committee');
                    }

                    try {
                        $db->beginTransaction();
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmtUp = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmtUp->execute([$hashed, $matchedSup['user_id']]);

                        $stmt = $db->prepare("INSERT INTO committees (user_id, name, designation, department, committee_number) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$matchedSup['user_id'], $firstName, $designation, $department, $committeeNumber]);

                        $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1980-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                        $stmtP->execute([$matchedSup['user_id'], $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                        $this->sendCredentialsMessage($db, $matchedSup['user_id'], $firstName, $lastName, $email, $cnic, $password, 'Committee Member');
                        $db->commit();
                        $display = formatPersonName($prefix, $firstName, $lastName);
                        $this->flash('success', "Supervisor {$display} appointed to Committee {$committeeNumber}.");
                        redirect('/hod/committee');
                    } catch (\Exception $e) {
                        $db->rollBack();
                        $this->flash('error', 'Error adding committee member. Please try again.');
                        redirect('/hod/committee');
                    }
                } else {
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

                        $stmt = $db->prepare("INSERT INTO committees (user_id, name, designation, department, committee_number) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$userId, $firstName, $designation, $department, $committeeNumber]);

                        // Ensure supervisor entry exists so faculty profile can be managed in View Faculty
                        $stmtSup = $db->prepare("INSERT IGNORE INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                        $stmtSup->execute([$userId, $firstName, $designation ?: 'Evaluator', $department]);

                        // Sync profiles table
                        $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1980-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                        $stmtP->execute([$userId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                        $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Committee Member');

                        $db->commit();
                        $display = formatPersonName($prefix, $firstName, $lastName);
                        $this->flash('success', "Committee Member $display added successfully to Committee $committeeNumber and credentials sent.");
                    } catch (\Exception $e) {
                        $db->rollBack();
                        $this->flash('error', 'Error adding committee member. Please try again.');
                    }
                }
            }
        }
        redirect('/hod/committee');
    }

    public function editCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $userId = (int)($_POST['user_id'] ?? 0);
            $designation = trim($_POST['designation'] ?? 'Evaluator');
            $committeeNumber = max(1, (int)($_POST['committee_number'] ?? 1));

            if ($userId && $designation) {
                $stmtCheck = $db->prepare("SELECT department FROM committees WHERE user_id = ?");
                $stmtCheck->execute([$userId]);
                if ($stmtCheck->fetchColumn() !== $dept) {
                    $this->flash('error', 'Unauthorized: Committee member is not in your department.');
                    redirect('/hod/committee');
                }

                $stmt = $db->prepare("UPDATE committees SET designation = ?, committee_number = ? WHERE user_id = ?");
                $stmt->execute([$designation, $committeeNumber, $userId]);

                $this->flash('success', "Committee role assignment updated successfully.");
            } else {
                $this->flash('error', "All required role fields must be filled.");
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
                    // If user is also a supervisor, only remove committee record
                    $stmtSup = $db->prepare("SELECT user_id FROM supervisors WHERE user_id = ?");
                    $stmtSup->execute([$id]);
                    if ($stmtSup->fetch()) {
                        $stmt = $db->prepare("DELETE FROM committees WHERE user_id = ?");
                        $stmt->execute([$id]);
                    } else {
                        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$id]);
                    }
                    $db->commit();
                    $this->flash('success', "Committee member removed successfully.");
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
            SELECT g.id as group_id, g.group_code, g.created_by, g.committee_number, g.progress_stage, g.created_at, g.batch_id,
                   b.name as batch_name,
                   p.id as project_id, p.title as project_title, p.description as abstract, p.status as project_status, p.thesis_file,
                   pr.id as proposal_id, pr.file_path as proposal_file, pr.status as proposal_status, pr.feedback as proposal_feedback, pr.submitted_at as proposal_submitted_at,
                   sup.user_id as supervisor_user_id, sup.name as supervisor_name, sup.designation as supervisor_designation, sup.department as supervisor_department,
                   u_sup.email as supervisor_email,
                   p_sup.prefix as supervisor_prefix, p_sup.surname as supervisor_surname, p_sup.mobile_code as supervisor_mobile_code, p_sup.mobile_no as supervisor_mobile_no
            FROM `groups` g
            JOIN students s ON g.created_by = s.user_id
            JOIN academic_batches b ON g.batch_id = b.id
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN proposals pr ON g.id = pr.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            LEFT JOIN users u_sup ON sup.user_id = u_sup.id
            LEFT JOIN profiles p_sup ON sup.user_id = p_sup.user_id
            WHERE s.department = ? AND b.is_active = 1
            ORDER BY g.created_at DESC
        ");
        $stmt->execute([$dept]);
        $projects = $stmt->fetchAll();

        // Fetch team members and evaluators for each group
        foreach ($projects as &$proj) {
            $stmtM = $db->prepare("
                SELECT s.user_id, s.student_id as roll_no, s.name as student_name, s.avatar, s.shift, s.department,
                       u.email, u.cnic,
                       prof.prefix, prof.surname, prof.mobile_code, prof.mobile_no,
                       (CASE WHEN s.user_id = ? THEN 1 ELSE 0 END) as is_leader
                FROM group_members gm
                JOIN students s ON gm.student_id = s.user_id
                JOIN users u ON s.user_id = u.id
                LEFT JOIN profiles prof ON s.user_id = prof.user_id
                WHERE gm.group_id = ?
                ORDER BY is_leader DESC, s.name ASC
            ");
            $stmtM->execute([$proj['created_by'], $proj['group_id']]);
            $proj['members'] = $stmtM->fetchAll();

            // Fetch evaluators for this committee if assigned
            $cNum = (int)($proj['committee_number'] ?? 0);
            if ($cNum > 0) {
                $stmtC = $db->prepare("
                    SELECT c.name, c.designation, u.email, prof.prefix, prof.surname
                    FROM committees c
                    JOIN users u ON c.user_id = u.id
                    LEFT JOIN profiles prof ON c.user_id = prof.user_id
                    WHERE c.department = ? AND c.committee_number = ?
                    ORDER BY c.designation ASC
                ");
                $stmtC->execute([$dept, $cNum]);
                $proj['committee_evaluators'] = $stmtC->fetchAll();
            } else {
                $proj['committee_evaluators'] = [];
            }

            // Fetch evaluations if any
            $stmtEval = $db->prepare("
                SELECT e.stage, e.total_marks, e.remarks, e.scheduled_date, c.name as evaluator_name, c.designation as evaluator_designation
                FROM evaluations e
                JOIN committees c ON e.evaluator_id = c.user_id
                WHERE e.group_id = ?
                ORDER BY e.created_at ASC
            ");
            $stmtEval->execute([$proj['group_id']]);
            $proj['evaluations'] = $stmtEval->fetchAll();
        }

        $stmtNumComm = $db->prepare("SELECT num_committees FROM department_settings WHERE department = ?");
        $stmtNumComm->execute([$dept]);
        $numCommittees = (int)($stmtNumComm->fetchColumn() ?: 2);

        $this->render('hod/projects', [
            'projects' => $projects,
            'num_committees' => $numCommittees,
            'department' => $dept
        ]);
    }

    public function reassignGroupCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $groupId = (int)($_POST['group_id'] ?? 0);
            $committeeNumber = max(1, (int)($_POST['committee_number'] ?? 1));

            if ($groupId > 0) {
                $stmt = $db->prepare("UPDATE `groups` SET committee_number = ? WHERE id = ?");
                $stmt->execute([$committeeNumber, $groupId]);
                $this->flash('success', "Group successfully reallocated to Committee $committeeNumber.");
            } else {
                $this->flash('error', 'Invalid group selection.');
            }
        }
        redirect('/hod/projects');
    }

    public function coordinators() {
        $db = \Database::getInstance()->getConnection();
        $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);
        
        $stmt = $db->prepare("
            SELECT co.*, u.email, u.cnic,
                   p.prefix, p.surname, p.mobile_code, p.mobile_no
            FROM coordinators co 
            JOIN users u ON co.user_id = u.id 
            LEFT JOIN profiles p ON co.user_id = p.user_id
            WHERE co.department = ? 
            ORDER BY co.name ASC
        ");
        $stmt->execute([$dept]);
        $coordinators = $stmt->fetchAll();
        
        // Fetch registered supervisors in this department not enrolled in coordinators
        $stmtAvailableSups = $db->prepare("
            SELECT s.user_id, s.name, s.designation, s.department, u.email, u.cnic,
                   p.prefix, p.surname, p.mobile_code, p.mobile_no
            FROM supervisors s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN profiles p ON s.user_id = p.user_id
            WHERE s.department = ?
              AND s.user_id NOT IN (SELECT user_id FROM coordinators WHERE department = ?)
            ORDER BY s.name ASC
        ");
        $stmtAvailableSups->execute([$dept, $dept]);
        $availableSupervisors = $stmtAvailableSups->fetchAll();

        $this->render('hod/coordinators', [
            'coordinators' => $coordinators,
            'available_supervisors' => $availableSupervisors,
            'department' => $dept
        ]);
    }

    public function createCoordinator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = \Database::getInstance()->getConnection();
            $dept = $this->getHodDepartment($db, $_SESSION['user_id'] ?? 0);

            $supervisorUserId = (int)($_POST['supervisor_user_id'] ?? 0);
            $prefix = trim($_POST['prefix'] ?? 'Mr.');
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $mobileCode = trim($_POST['mobile_code'] ?? '+92');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            $shift = in_array($_POST['shift'] ?? '', ['Morning', 'Evening', 'All']) ? $_POST['shift'] : 'Morning';
            
            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($password) || empty($designation)) {
                $this->flash('error', 'All fields are required.');
                redirect('/hod/coordinators');
            }
            
            $cnic = str_replace('-', '', $cnic);
            
            // If an existing supervisor was selected
            if ($supervisorUserId > 0) {
                $stmtCheck = $db->prepare("SELECT s.*, u.email, u.cnic FROM supervisors s JOIN users u ON s.user_id = u.id WHERE s.user_id = ? AND s.department = ?");
                $stmtCheck->execute([$supervisorUserId, $dept]);
                $sup = $stmtCheck->fetch();
                if (!$sup) {
                    $this->flash('error', 'Selected supervisor not found in your department.');
                    redirect('/hod/coordinators');
                }

                // Check if already in coordinators
                $stmtCoordCheck = $db->prepare("SELECT user_id FROM coordinators WHERE user_id = ?");
                $stmtCoordCheck->execute([$supervisorUserId]);
                if ($stmtCoordCheck->fetch()) {
                    $this->flash('error', 'This supervisor is already appointed as a coordinator.');
                    redirect('/hod/coordinators');
                }

                try {
                    $db->beginTransaction();
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUp = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmtUp->execute([$hashed, $supervisorUserId]);

                    $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, designation, department, shift) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$supervisorUserId, $firstName, $designation, $dept, $shift]);

                    // Sync profiles table
                    $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1985-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                    $stmtP->execute([$supervisorUserId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                    $this->sendCredentialsMessage($db, $supervisorUserId, $firstName, $lastName, $sup['email'], $sup['cnic'], $password, "Coordinator ($shift Shift)");

                    $db->commit();
                    $display = formatPersonName($prefix, $firstName, $lastName);
                    $this->flash('success', "Supervisor $display appointed as Coordinator ($shift Shift) and credentials sent.");
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Error appointing coordinator. Please try again.');
                }
            } else {
                // Check if user already exists as supervisor or other role
                $stmtExisting = $db->prepare("SELECT id FROM users WHERE email = ? OR cnic = ?");
                $stmtExisting->execute([$email, $cnic]);
                $existingUser = $stmtExisting->fetch();

                if ($existingUser) {
                    $userId = $existingUser['id'];
                    $stmtCoordCheck = $db->prepare("SELECT user_id FROM coordinators WHERE user_id = ?");
                    $stmtCoordCheck->execute([$userId]);
                    if ($stmtCoordCheck->fetch()) {
                        $this->flash('error', 'A coordinator with this email or CNIC already exists.');
                        redirect('/hod/coordinators');
                    }

                    try {
                        $db->beginTransaction();
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmtUp = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmtUp->execute([$hashed, $userId]);

                        $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, designation, department, shift) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$userId, $firstName, $designation, $dept, $shift]);

                        $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1985-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                        $stmtP->execute([$userId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                        $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, "Coordinator ($shift Shift)");

                        $db->commit();
                        $display = formatPersonName($prefix, $firstName, $lastName);
                        $this->flash('success', "Coordinator $display ($shift Shift) added successfully and credentials sent.");
                    } catch (\Exception $e) {
                        $db->rollBack();
                        $this->flash('error', 'Error adding coordinator. Please try again.');
                    }
                } else {
                    try {
                        $db->beginTransaction();
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        
                        $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, 'coordinator', 'approved')");
                        $stmt->execute([$email, $cnic, $hashed]);
                        $userId = $db->lastInsertId();
                        
                        $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, designation, department, shift) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$userId, $firstName, $designation, $dept, $shift]);
                        
                        // Ensure supervisor entry exists so faculty profile can be managed in View Faculty
                        $stmtSup = $db->prepare("INSERT IGNORE INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                        $stmtSup->execute([$userId, $firstName, 'Assistant Professor', $dept]);

                        $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, ?, ?, ?, '1985-01-01', ?, ?, 'Not Provided Yet', 'Male') ON DUPLICATE KEY UPDATE prefix = VALUES(prefix), surname = VALUES(surname), mobile_code = VALUES(mobile_code), mobile_no = VALUES(mobile_no)");
                        $stmtP->execute([$userId, $prefix, $lastName, $cnic, $mobileCode, !empty($contactNo) ? $contactNo : '3000000000']);

                        $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, "Coordinator ($shift Shift)");
                        
                        $db->commit();
                        $display = formatPersonName($prefix, $firstName, $lastName);
                        $this->flash('success', "Coordinator $display ($shift Shift) created successfully under department $dept and credentials sent.");
                    } catch (\Exception $e) {
                        $db->rollBack();
                        $this->flash('error', 'Error creating coordinator. Please try again.');
                    }
                }
            }
        }
        redirect('/hod/coordinators');
    }

    public function editCoordinator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $userId = (int)($_POST['user_id'] ?? 0);
            $designation = trim($_POST['designation'] ?? 'FYP Coordinator');
            $shift = in_array($_POST['shift'] ?? '', ['Morning', 'Evening', 'All']) ? $_POST['shift'] : 'Morning';
            
            if (!$userId || empty($designation)) {
                $this->flash('error', 'Required role fields are missing.');
                redirect('/hod/coordinators');
            }
            
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
                // Update coordinator-specific role fields
                $stmt = $db->prepare("UPDATE coordinators SET designation = ?, shift = ? WHERE user_id = ?");
                $stmt->execute([$designation, $shift, $userId]);
                
                $this->flash('success', "Coordinator role assignment updated successfully.");
            } catch (\Exception $e) {
                $this->flash('error', 'Error updating coordinator role.');
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
                    // Check if this user is also a supervisor or committee member
                    $stmtSup = $db->prepare("SELECT user_id FROM supervisors WHERE user_id = ?");
                    $stmtSup->execute([$id]);
                    $isSupervisor = (bool)$stmtSup->fetch();

                    $stmtComm = $db->prepare("SELECT user_id FROM committees WHERE user_id = ?");
                    $stmtComm->execute([$id]);
                    $isCommittee = (bool)$stmtComm->fetch();

                    $stmtDelCoord = $db->prepare("DELETE FROM coordinators WHERE user_id = ?");
                    $stmtDelCoord->execute([$id]);

                    if (!$isSupervisor && !$isCommittee) {
                        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$id]);
                    } else {
                        // Revert primary user role to supervisor or committee if it was coordinator
                        $newRole = $isSupervisor ? 'supervisor' : 'committee';
                        $stmtUpdateRole = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
                        $stmtUpdateRole->execute([$newRole, $id]);
                    }

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

    public function cumulativeSheet() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getHodDepartment($db, $userId);

        if (!$dept) {
            $this->flash('error', 'Unauthorized access or department not found.');
            redirect('/login');
        }

        // Fetch batches for this department
        $stmtBatches = $db->prepare("
            SELECT * FROM academic_batches 
            WHERE department = ? 
            ORDER BY is_active DESC, id DESC
        ");
        $stmtBatches->execute([$dept]);
        $batches = $stmtBatches->fetchAll();

        // Active batch default
        $activeBatch = null;
        foreach ($batches as $b) {
            if ($b['is_active']) {
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
        $params = [$dept];
        if ($batchId > 0) {
            $batchSql = " AND g.batch_id = ?";
            $params[] = $batchId;
        }

        $shiftSql = "";
        if ($selectedShift !== 'all') {
            $shiftSql = " AND st.shift = ?";
            $params[] = $selectedShift;
        }

        // Query students, groups, projects, supervisors, and grades
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
        $studentsList = $stmtStudents->fetchAll();

        // Fetch evaluations visibility per group & stage
        $groupIds = array_unique(array_filter(array_column($studentsList, 'group_id')));
        $stageVis = [];
        if (!empty($groupIds)) {
            $inIds = implode(',', array_map('intval', $groupIds));
            $stmtVis = $db->query("
                SELECT group_id, stage, 
                       MAX(show_to_student) as is_published,
                       COUNT(*) as eval_count
                FROM evaluations 
                WHERE group_id IN ($inIds)
                GROUP BY group_id, stage
            ");
            while ($vr = $stmtVis->fetch(\PDO::FETCH_ASSOC)) {
                $gid = (int)$vr['group_id'];
                $stageVis[$gid][$vr['stage']] = [
                    'is_published' => (int)$vr['is_published'],
                    'eval_count' => (int)$vr['eval_count']
                ];
            }
        }

        // Process student visibility and calculate stats
        $totalStudents = count($studentsList);
        $totalGroups = count($groupIds);
        $fullyReleasedCount = 0;
        $draftCount = 0;
        $passedCount = 0;
        $publishedSum = 0;
        $publishedStudentsCount = 0;

        foreach ($studentsList as &$s) {
            $gid = (int)$s['group_id'];
            $gStages = $stageVis[$gid] ?? [];

            $propPub = !empty($gStages['Proposal Defence Presentation']['is_published']);
            $progPub = !empty($gStages['FYP Progress Presentation']['is_published']);
            $finPub  = !empty($gStages['Final Presentation']['is_published']);
            $supPub  = !empty($s['show_supervision_to_student']);

            $hasPropMark = ($s['proposal_defense_marks'] !== null);
            $hasProgMark = ($s['progress_presentation_marks'] !== null);
            $hasSupMark  = ($s['supervision_marks'] !== null);
            $hasFinMark  = ($s['final_presentation_marks'] !== null);

            // Stage-level visible marks for HOD (only if published by coordinator to students)
            $s['vis_prop'] = ($hasPropMark && $propPub) ? (int)round((float)$s['proposal_defense_marks']) : null;
            $s['vis_prog'] = ($hasProgMark && $progPub) ? (int)round((float)$s['progress_presentation_marks']) : null;
            $s['vis_sup']  = ($hasSupMark && $supPub)   ? (int)round((float)$s['supervision_marks']) : null;
            $s['vis_fin']  = ($hasFinMark && $finPub)   ? (int)round((float)$s['final_presentation_marks']) : null;

            // Draft indicators
            $s['prop_draft'] = ($hasPropMark && !$propPub);
            $s['prog_draft'] = ($hasProgMark && !$progPub);
            $s['sup_draft']  = ($hasSupMark && !$supPub);
            $s['fin_draft']  = ($hasFinMark && !$finPub);

            $s['has_any_eval'] = ($hasPropMark || $hasProgMark || $hasSupMark || $hasFinMark);
            $s['has_any_draft'] = ($s['prop_draft'] || $s['prog_draft'] || $s['sup_draft'] || $s['fin_draft']);

            // Are all evaluated components released?
            $isFullyReleased = false;
            if ($s['has_any_eval'] && !$s['has_any_draft']) {
                $isFullyReleased = true;
            }
            $s['is_fully_released'] = $isFullyReleased;

            if ($isFullyReleased) {
                $fullyReleasedCount++;
                $tot = (int)round((float)($s['total_marks'] ?? 0));
                $publishedSum += $tot;
                $publishedStudentsCount++;

                if (($s['pass_fail_status'] ?? '') === 'Pass' || ($s['percentage'] ?? 0) >= 50) {
                    $passedCount++;
                }
            } else {
                if ($s['has_any_draft']) {
                    $draftCount++;
                }
            }
        }
        unset($s);

        $avgScore = $publishedStudentsCount > 0 ? (int)round($publishedSum / $publishedStudentsCount) : 0;

        $batchName = 'All Batches';
        if ($batchId > 0) {
            foreach ($batches as $b) {
                if ($b['id'] == $batchId) {
                    $batchName = $b['name'];
                    break;
                }
            }
        }

        $this->render('hod/cumulative_sheet', [
            'department' => $dept,
            'batches' => $batches,
            'activeBatch' => $activeBatch,
            'selectedBatchId' => $batchId,
            'selectedBatchName' => $batchName,
            'selectedShift' => $selectedShift,
            'studentsList' => $studentsList,
            'totalStudents' => $totalStudents,
            'totalGroups' => $totalGroups,
            'fullyReleasedCount' => $fullyReleasedCount,
            'draftCount' => $draftCount,
            'passedCount' => $passedCount,
            'avgScore' => $avgScore
        ]);
    }

    public function printCumulativeSheet() {
        $db = \Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'] ?? 0;
        $dept = $this->getHodDepartment($db, $userId);

        if (!$dept) {
            $this->flash('error', 'Unauthorized access or department not found.');
            redirect('/login');
        }

        // Get HOD Name
        $stmtH = $db->prepare("SELECT name FROM hods WHERE user_id = ?");
        $stmtH->execute([$userId]);
        $hodName = $stmtH->fetchColumn() ?: 'Head of Department';

        // Batches
        $stmtBatches = $db->prepare("
            SELECT * FROM academic_batches 
            WHERE department = ? 
            ORDER BY is_active DESC, id DESC
        ");
        $stmtBatches->execute([$dept]);
        $batches = $stmtBatches->fetchAll();

        $activeBatch = null;
        foreach ($batches as $b) {
            if ($b['is_active']) {
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

        $dated = !empty($_GET['dated']) ? $_GET['dated'] : date('d-m-Y');

        // Fetch Coordinator Name(s) for this department
        $stmtCoord = $db->prepare("SELECT name, shift FROM coordinators WHERE department = ? ORDER BY shift ASC");
        $stmtCoord->execute([$dept]);
        $coordRows = $stmtCoord->fetchAll();
        $coordNames = [];
        foreach ($coordRows as $cr) {
            $coordNames[] = $cr['name'] . ($cr['shift'] !== 'All' ? " ({$cr['shift']})" : "");
        }
        $coordinatorName = !empty($coordNames) ? implode(' / ', $coordNames) : 'Department Coordinator';

        $batchSql = "";
        $params = [$dept];
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
        $studentsList = $stmtStudents->fetchAll();

        // Stage visibility
        $groupIds = array_unique(array_filter(array_column($studentsList, 'group_id')));
        $stageVis = [];
        if (!empty($groupIds)) {
            $inIds = implode(',', array_map('intval', $groupIds));
            $stmtVis = $db->query("
                SELECT group_id, stage, 
                       MAX(show_to_student) as is_published,
                       COUNT(*) as eval_count
                FROM evaluations 
                WHERE group_id IN ($inIds)
                GROUP BY group_id, stage
            ");
            while ($vr = $stmtVis->fetch(\PDO::FETCH_ASSOC)) {
                $gid = (int)$vr['group_id'];
                $stageVis[$gid][$vr['stage']] = [
                    'is_published' => (int)$vr['is_published'],
                    'eval_count' => (int)$vr['eval_count']
                ];
            }
        }

        foreach ($studentsList as &$s) {
            $gid = (int)$s['group_id'];
            $gStages = $stageVis[$gid] ?? [];

            $propPub = !empty($gStages['Proposal Defence Presentation']['is_published']);
            $progPub = !empty($gStages['FYP Progress Presentation']['is_published']);
            $finPub  = !empty($gStages['Final Presentation']['is_published']);
            $supPub  = !empty($s['show_supervision_to_student']);

            $hasPropMark = ($s['proposal_defense_marks'] !== null);
            $hasProgMark = ($s['progress_presentation_marks'] !== null);
            $hasSupMark  = ($s['supervision_marks'] !== null);
            $hasFinMark  = ($s['final_presentation_marks'] !== null);

            $s['vis_prop'] = ($hasPropMark && $propPub) ? (int)round((float)$s['proposal_defense_marks']) : null;
            $s['vis_prog'] = ($hasProgMark && $progPub) ? (int)round((float)$s['progress_presentation_marks']) : null;
            $s['vis_sup']  = ($hasSupMark && $supPub)   ? (int)round((float)$s['supervision_marks']) : null;
            $s['vis_fin']  = ($hasFinMark && $finPub)   ? (int)round((float)$s['final_presentation_marks']) : null;

            $s['prop_draft'] = ($hasPropMark && !$propPub);
            $s['prog_draft'] = ($hasProgMark && !$progPub);
            $s['sup_draft']  = ($hasSupMark && !$supPub);
            $s['fin_draft']  = ($hasFinMark && !$finPub);

            $s['has_any_eval'] = ($hasPropMark || $hasProgMark || $hasSupMark || $hasFinMark);
            $s['has_any_draft'] = ($s['prop_draft'] || $s['prog_draft'] || $s['sup_draft'] || $s['fin_draft']);
            $s['is_fully_released'] = ($s['has_any_eval'] && !$s['has_any_draft']);
        }
        unset($s);

        $batchName = 'All Batches';
        if ($batchId > 0) {
            foreach ($batches as $b) {
                if ($b['id'] == $batchId) {
                    $batchName = $b['name'];
                    break;
                }
            }
        }

        $this->render('hod/cumulative_sheet_print', [
            'department' => $dept,
            'hodName' => $hodName,
            'coordinatorName' => $coordinatorName,
            'batches' => $batches,
            'batchId' => $batchId,
            'batchName' => $batchName,
            'shift' => $selectedShift,
            'dated' => $dated,
            'studentsList' => $studentsList
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

