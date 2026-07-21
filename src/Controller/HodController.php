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
        
        // Fetch recent supervisors and committee members scoped to this department
        $stmtRecentSup = $db->prepare("SELECT s.*, u.email FROM supervisors s JOIN users u ON s.user_id = u.id WHERE s.department = ? ORDER BY u.created_at DESC LIMIT 5");
        $stmtRecentSup->execute([$dept]);
        $recentSupervisors = $stmtRecentSup->fetchAll();
        
        $stmtRecentComm = $db->prepare("SELECT c.*, u.email FROM committees c JOIN users u ON c.user_id = u.id WHERE c.department = ? ORDER BY u.created_at DESC LIMIT 5");
        $stmtRecentComm->execute([$dept]);
        $recentCommittee = $stmtRecentComm->fetchAll();

        $this->render('hod/dashboard', [
            'stats' => $stats,
            'recentSupervisors' => $recentSupervisors,
            'recentCommittee' => $recentCommittee
        ]);
    }

    public function supervisors() {
        $db = \Database::getInstance()->getConnection();
        $supervisors = $db->query("SELECT s.*, u.email FROM supervisors s JOIN users u ON s.user_id = u.id ORDER BY s.name ASC")->fetchAll();
        
        $this->render('hod/supervisors', [
            'supervisors' => $supervisors
        ]);
    }

    public function createSupervisor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            $designation = trim($_POST['designation'] ?? '');
            $department = trim($_POST['department'] ?? '');
            $research_interest = trim($_POST['research_interest'] ?? '');

            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($password) || empty($designation) || empty($department)) {
                $this->flash('error', 'Please fill in all required fields.');
                redirect('/hod/supervisors');
            }

            $cnic = str_replace('-', '', $cnic);
            $db = \Database::getInstance()->getConnection();

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
                $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department, research_interest) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $fullName, $designation, $department, $research_interest]);

                // Sync profiles table
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '1980-01-01', '+92', '03000000000', 'Not Provided Yet', 'Male')");
                $stmtP->execute([$userId, $lastName, $cnic]);

                $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Supervisor');

                $db->commit();
                $this->flash('success', "Supervisor $fullName added successfully and credentials sent.");
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
            $userId = $_POST['user_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $department = trim($_POST['department'] ?? '');
            $research_interest = trim($_POST['research_interest'] ?? '');

            if ($userId && $name && $designation && $department) {
                $db = \Database::getInstance()->getConnection();
                $stmt = $db->prepare("UPDATE supervisors SET name = ?, designation = ?, department = ?, research_interest = ? WHERE user_id = ?");
                $stmt->execute([$name, $designation, $department, $research_interest, $userId]);
                
                $this->flash('success', "Supervisor profile updated.");
            } else {
                $this->flash('error', "Failed to update supervisor profile. Fill all fields.");
            }
        }
        redirect('/hod/supervisors');
    }

    public function deleteSupervisor() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \Database::getInstance()->getConnection();
            try {
                $db->beginTransaction();
                // Deleting from users will cascade delete from supervisors
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $db->commit();
                $this->flash('success', "Supervisor deleted successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Failed to delete supervisor. Please try again.');
            }
        }
        redirect('/hod/supervisors');
    }

    public function committee() {
        $db = \Database::getInstance()->getConnection();
        $committees = $db->query("SELECT c.*, u.email FROM committees c JOIN users u ON c.user_id = u.id ORDER BY c.name ASC")->fetchAll();
        
        $this->render('hod/committee', [
            'committees' => $committees
        ]);
    }

    public function createCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $contactNo = trim($_POST['contact_no'] ?? '');
            $password = $_POST['password'] ?? '';
            $department = trim($_POST['department'] ?? '');

            if (empty($firstName) || empty($lastName) || empty($email) || empty($cnic) || empty($designation) || empty($password) || empty($department)) {
                $this->flash('error', 'All fields are required.');
                redirect('/hod/committee');
            }

            $cnic = str_replace('-', '', $cnic);
            $db = \Database::getInstance()->getConnection();

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
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '1980-01-01', '+92', '03000000000', 'Not Provided Yet', 'Male')");
                $stmtP->execute([$userId, $lastName, $cnic]);

                $this->sendCredentialsMessage($db, $userId, $firstName, $lastName, $email, $cnic, $password, 'Committee Member');

                $db->commit();
                $this->flash('success', "Committee Member $fullName added successfully and credentials sent.");
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
            $userId = $_POST['user_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $department = trim($_POST['department'] ?? '');

            if ($userId && $name && $department) {
                $db = \Database::getInstance()->getConnection();
                $stmt = $db->prepare("UPDATE committees SET name = ?, department = ? WHERE user_id = ?");
                $stmt->execute([$name, $department, $userId]);
                
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
        
        $students = $db->prepare("SELECT s.*, u.email, u.status FROM students s JOIN users u ON s.user_id = u.id WHERE u.status = 'pending' AND s.department = ? ORDER BY u.created_at DESC");
        $students->execute([$dept]);
        $pending = $students->fetchAll();
        
        $this->render('hod/verify_students', [
            'students' => $pending
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
                
                $this->addNotification($id, 'Account Approved', 'Your registration has been approved! You can now log in.');
                $this->flash('success', 'Student account approved successfully.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/hod/students/verify');
    }

    public function rejectStudent() {
        $id = $_GET['id'] ?? null;
        if ($id) {
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
                
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $this->flash('success', 'Student registration rejected and deleted.');
            } else {
                $this->flash('error', 'Unauthorized: Student is not in your department.');
            }
        }
        redirect('/hod/students/verify');
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

        // 2. Dispatch real email via PHP mail()
        $headers = "From: no-reply@fypmanagement.com\r\n"
                 . "Reply-To: no-reply@fypmanagement.com\r\n"
                 . "X-Mailer: PHP/" . phpversion();
        @mail($email, $subject, $messageBody, $headers);

        // 3. Add system notification inside the database for welcome banner
        $welcomeTitle = "Welcome to the $portalType Portal";
        $welcomeMsg = "Welcome! Your account credentials have been generated by HOD $hodName. You can update your editable profile information under the My Profile menu.";
        $stmtN = $db->prepare("INSERT INTO notifications (user_id, title, message, redirect_url) VALUES (?, ?, ?, ?)");
        $stmtN->execute([$userId, $welcomeTitle, $welcomeMsg, '/' . strtolower(str_replace(' Member', '', $portalType)) . '/profile']);
    }
}

