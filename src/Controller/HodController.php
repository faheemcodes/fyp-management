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
        $supervisors = $db->query("SELECT s.*, u.email, (CASE WHEN c.user_id IS NOT NULL THEN 1 ELSE 0 END) as is_committee FROM supervisors s JOIN users u ON s.user_id = u.id LEFT JOIN committees c ON s.user_id = c.user_id ORDER BY s.name ASC")->fetchAll();
        
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

    public function toggleCommitteeRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            if ($userId) {
                $db = \Database::getInstance()->getConnection();
                
                // Get supervisor details
                $stmt = $db->prepare("SELECT * FROM supervisors WHERE user_id = ?");
                $stmt->execute([$userId]);
                $supervisor = $stmt->fetch();
                
                if ($supervisor) {
                    // Check if already in committees
                    $stmtCheck = $db->prepare("SELECT user_id FROM committees WHERE user_id = ?");
                    $stmtCheck->execute([$userId]);
                    $isCommittee = $stmtCheck->fetch();
                    
                    if ($isCommittee) {
                        // Toggle OFF: remove from committees
                        try {
                            $db->beginTransaction();
                            $stmtDel = $db->prepare("DELETE FROM committees WHERE user_id = ?");
                            $stmtDel->execute([$userId]);
                            $db->commit();
                            $this->flash('success', "Supervisor {$supervisor['name']} removed from the committee.");
                        } catch (\Exception $e) {
                            $db->rollBack();
                            $this->flash('error', "Failed to remove committee role: " . $e->getMessage());
                        }
                    } else {
                        // Toggle ON: assign to committee. Check limit first!
                        $department = $supervisor['department'];
                        
                        $stmtCount = $db->prepare("SELECT COUNT(*) FROM committees WHERE department = ?");
                        $stmtCount->execute([$department]);
                        $currentCount = (int)$stmtCount->fetchColumn();
                        
                        if ($currentCount >= 5) {
                            $this->flash('error', "Cannot assign. The '{$department}' department already has the maximum limit of 5 committee members.");
                            redirect('/hod/supervisors');
                        }
                        
                        try {
                            $db->beginTransaction();
                            $stmtIns = $db->prepare("INSERT INTO committees (user_id, name, department) VALUES (?, ?, ?)");
                            $stmtIns->execute([$userId, $supervisor['name'], $department]);
                            $db->commit();
                            $this->flash('success', "Supervisor {$supervisor['name']} assigned as a committee member.");
                        } catch (\Exception $e) {
                            $db->rollBack();
                            $this->flash('error', "Failed to assign committee role: " . $e->getMessage());
                        }
                    }
                }
            }
        }
        redirect('/hod/supervisors');
    }
}
