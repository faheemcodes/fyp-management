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
        
        $stmtGroupsCount = $db->prepare("SELECT COUNT(*) FROM groups g JOIN students s ON g.created_by = s.user_id WHERE s.department = ?");
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
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $designation = trim($_POST['designation'] ?? '');
            $department = trim($_POST['department'] ?? '');
            $research_interest = trim($_POST['research_interest'] ?? '');

            if (empty($name) || empty($email) || empty($password) || empty($designation) || empty($department)) {
                $this->flash('error', 'Please fill in all required fields.');
                redirect('/hod/supervisors');
            }

            $db = \Database::getInstance()->getConnection();

            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->flash('error', 'Email is already registered.');
                redirect('/hod/supervisors');
            }

            try {
                $db->beginTransaction();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (email, password, role, status) VALUES (?, ?, 'supervisor', 'approved')");
                $stmt->execute([$email, $hashed]);
                $userId = $db->lastInsertId();

                $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department, research_interest) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $name, $designation, $department, $research_interest]);

                $db->commit();
                $this->flash('success', "Supervisor $name added successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error adding supervisor: ' . $e->getMessage());
            }
        }
        redirect('/hod/supervisors');
    }

    public function editSupervisor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                $this->flash('error', "Failed to delete supervisor: " . $e->getMessage());
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
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $department = trim($_POST['department'] ?? '');

            if (empty($name) || empty($email) || empty($password) || empty($department)) {
                $this->flash('error', 'All fields are required.');
                redirect('/hod/committee');
            }

            $db = \Database::getInstance()->getConnection();

            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->flash('error', 'Email is already registered.');
                redirect('/hod/committee');
            }

            try {
                $db->beginTransaction();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (email, password, role, status) VALUES (?, ?, 'committee', 'approved')");
                $stmt->execute([$email, $hashed]);
                $userId = $db->lastInsertId();

                $stmt = $db->prepare("INSERT INTO committees (user_id, name, department) VALUES (?, ?, ?)");
                $stmt->execute([$userId, $name, $department]);

                $db->commit();
                $this->flash('success', "Committee Member $name added successfully.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error adding committee member: ' . $e->getMessage());
            }
        }
        redirect('/hod/committee');
    }

    public function editCommittee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                $this->flash('error', "Failed to delete committee member: " . $e->getMessage());
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
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cnic = trim($_POST['cnic'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($email) || empty($cnic) || empty($password)) {
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
                
                $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, department) VALUES (?, ?, ?)");
                $stmt->execute([$userId, $name, $dept]);
                
                // Keep profiles table in sync
                $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Mr.', ?, ?, '1985-01-01', '+92', '0000000', 'Not Provided Yet', 'Male')");
                $stmtP->execute([$userId, $name, $cnic]);
                
                $db->commit();
                $this->flash('success', "Coordinator $name created successfully under department $dept.");
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Error creating coordinator: ' . $e->getMessage());
            }
        }
        redirect('/hod/coordinators');
    }

    public function editCoordinator() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                $this->flash('error', 'Error updating coordinator: ' . $e->getMessage());
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
                    $this->flash('error', 'Error deleting coordinator: ' . $e->getMessage());
                }
            } else {
                $this->flash('error', 'Unauthorized: Coordinator is not in your department.');
            }
        }
        redirect('/hod/coordinators');
    }
}

