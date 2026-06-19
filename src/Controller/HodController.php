<?php
namespace Controller;

class HodController extends BaseController {

    public function dashboard() {
        $db = \Database::getInstance()->getConnection();
        
        $stats = [];
        $stats['supervisors'] = $db->query("SELECT COUNT(*) FROM supervisors")->fetchColumn();
        $stats['committee'] = $db->query("SELECT COUNT(*) FROM committees")->fetchColumn();
        $stats['pending_approvals'] = $db->query("SELECT COUNT(*) FROM users WHERE status = 'pending'")->fetchColumn();
        $stats['total_groups'] = $db->query("SELECT COUNT(*) FROM groups")->fetchColumn();
        
        // Fetch recent supervisors and committee members
        $recentSupervisors = $db->query("SELECT s.*, u.email FROM supervisors s JOIN users u ON s.user_id = u.id ORDER BY u.created_at DESC LIMIT 5")->fetchAll();
        $recentCommittee = $db->query("SELECT c.*, u.email FROM committees c JOIN users u ON c.user_id = u.id ORDER BY u.created_at DESC LIMIT 5")->fetchAll();

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
}
