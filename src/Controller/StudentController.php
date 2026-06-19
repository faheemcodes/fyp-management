<?php
namespace Controller;

class StudentController extends BaseController {

    private function getStudentGroup($userId) {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT g.*, p.title as project_title, p.description as project_description, p.status as project_status, p.supervisor_id, sup.name as supervisor_name,
            creator_std.name as creator_name, creator_std.student_id as creator_student_id
            FROM group_members gm
            JOIN groups g ON gm.group_id = g.id
            LEFT JOIN students creator_std ON g.created_by = creator_std.user_id
            LEFT JOIN projects p ON g.id = p.group_id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE gm.student_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function dashboard() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Get group details
        $group = $this->getStudentGroup($userId);
        
        $members = [];
        $proposal = null;
        $grades = null;
        
        if ($group) {
            // Get group members
            $stmt = $db->prepare("SELECT s.* FROM group_members gm JOIN students s ON gm.student_id = s.user_id WHERE gm.group_id = ?");
            $stmt->execute([$group['id']]);
            $members = $stmt->fetchAll();

            // Get proposal status
            $stmt = $db->prepare("SELECT * FROM proposals WHERE group_id = ?");
            $stmt->execute([$group['id']]);
            $proposal = $stmt->fetch();

            // Get grades
            $stmt = $db->prepare("SELECT * FROM grades WHERE group_id = ?");
            $stmt->execute([$group['id']]);
            $grades = $stmt->fetch();
        }

        // Get system deadlines
        $deadlines = $db->query("SELECT * FROM deadlines ORDER BY id ASC")->fetchAll();

        $this->render('student/dashboard', [
            'group' => $group,
            'members' => $members,
            'proposal' => $proposal,
            'grades' => $grades,
            'deadlines' => $deadlines
        ]);
    }

    public function group() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        $group = $this->getStudentGroup($userId);
        $members = [];
        $groupMembers = [];

        if ($group) {
            $stmt = $db->prepare("SELECT s.*, u.email FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                JOIN users u ON s.user_id = u.id 
                WHERE gm.group_id = ?");
            $stmt->execute([$group['id']]);
            $members = $stmt->fetchAll();

            // Get non-leader members for editing form
            $stmt = $db->prepare("SELECT s.student_id, u.email, s.name FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                JOIN users u ON s.user_id = u.id 
                WHERE gm.group_id = ? AND gm.student_id != ?");
            $stmt->execute([$group['id'], $group['created_by']]);
            $groupMembers = $stmt->fetchAll();
        }

        $this->render('student/group', [
            'group' => $group,
            'members' => $members,
            'groupMembers' => $groupMembers
        ]);
    }

    public function updateMembers() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $db = \Database::getInstance()->getConnection();

            $group = $this->getStudentGroup($userId);
            
            if (!$group) {
                $this->flash('error', 'Group not found.');
                redirect('/student/group');
            }
            
            // Check if current user is the group leader
            if ($group['created_by'] != $userId) {
                $this->flash('error', 'Only the group leader can edit team members.');
                redirect('/student/group');
            }

            $member1_ident = trim($_POST['member1'] ?? '');
            $member2_ident = trim($_POST['member2'] ?? '');

            try {
                $db->beginTransaction();

                $member_ids = [];
                $idents = array_filter([$member1_ident, $member2_ident]);
                
                // Get current student's registration ID / email to check if they tried to add themselves
                $stmt = $db->prepare("SELECT s.student_id, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
                $stmt->execute([$userId]);
                $currentUserInfo = $stmt->fetch();
                $currId = strtolower($currentUserInfo['student_id'] ?? '');
                $currEmail = strtolower($currentUserInfo['email'] ?? '');
                
                foreach ($idents as $ident) {
                    $identLower = strtolower($ident);
                    if ($identLower === $currId || $identLower === $currEmail) {
                        throw new \Exception("You cannot add yourself as a group member.");
                    }
                    
                    $stmt = $db->prepare("SELECT user_id FROM students s JOIN users u ON s.user_id = u.id WHERE LOWER(u.email) = ? OR LOWER(s.student_id) = ?");
                    $stmt->execute([$identLower, $identLower]);
                    $member = $stmt->fetch();
                    if (!$member) {
                        throw new \Exception("Member identifier '$ident' not found as a registered student.");
                    }
                    
                    $mId = $member['user_id'];
                    if (in_array($mId, $member_ids)) {
                        throw new \Exception("Duplicate member identifier '$ident'.");
                    }
                    
                    // Verify member is not in another group
                    $stmt = $db->prepare("SELECT group_id FROM group_members WHERE student_id = ?");
                    $stmt->execute([$mId]);
                    $existingGroup = $stmt->fetch();
                    if ($existingGroup && $existingGroup['group_id'] != $group['id']) {
                        throw new \Exception("Student '$ident' is already in another project group.");
                    }
                    
                    $member_ids[] = $mId;
                }

                $groupId = $group['id'];

                // Reset non-leader group members
                $stmt = $db->prepare("DELETE FROM group_members WHERE group_id = ? AND student_id != ?");
                $stmt->execute([$groupId, $userId]);

                // Add selected group members
                foreach ($member_ids as $mId) {
                    $stmt = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                    $stmt->execute([$groupId, $mId]);
                }

                $db->commit();
                $this->flash('success', 'Group members updated successfully.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', $e->getMessage());
            }
        }
        redirect('/student/group');
    }

    public function createGroup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $db = \Database::getInstance()->getConnection();

            // Verify if student is already in a group
            $stmt = $db->prepare("SELECT id FROM group_members WHERE student_id = ?");
            $stmt->execute([$userId]);
            if ($stmt->fetch()) {
                $this->flash('error', 'You are already a member of a group.');
                redirect('/student/group');
            }

            $projectTitle = trim($_POST['title'] ?? '');
            $projectDesc = trim($_POST['description'] ?? '');

            if (empty($projectTitle) || empty($projectDesc)) {
                $this->flash('error', 'Project title and description are required.');
                redirect('/student/group');
            }

            try {
                $db->beginTransaction();

                // Insert into groups
                $stmt = $db->prepare("INSERT INTO groups (group_code, created_by, progress_stage) VALUES (NULL, ?, 'Group Created')");
                $stmt->execute([$userId]);
                $groupId = $db->lastInsertId();

                // Insert member
                $stmt = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                $stmt->execute([$groupId, $userId]);

                // Insert project
                $stmt = $db->prepare("INSERT INTO projects (group_id, title, description, status) VALUES (?, ?, ?, 'Draft')");
                $stmt->execute([$groupId, $projectTitle, $projectDesc]);

                // Insert grades placeholder
                $stmt = $db->prepare("INSERT INTO grades (group_id) VALUES (?)");
                $stmt->execute([$groupId]);

                $db->commit();
                $this->flash('success', 'Group created successfully! You can now invite team members.');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', 'Failed to create group: ' . $e->getMessage());
            }
        }
        redirect('/student/group');
    }

    public function addMember() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $db = \Database::getInstance()->getConnection();

            $group = $this->getStudentGroup($userId);
            if (!$group) {
                $this->flash('error', 'You must create a group first.');
                redirect('/student/group');
            }

            $searchVal = trim($_POST['member_identity'] ?? ''); // email or student ID
            if (empty($searchVal)) {
                $this->flash('error', 'Please enter student Email or Student ID.');
                redirect('/student/group');
            }

            // Find the student
            $stmt = $db->prepare("SELECT s.user_id, s.name FROM students s 
                JOIN users u ON s.user_id = u.id 
                WHERE u.email = ? OR s.student_id = ?");
            $stmt->execute([$searchVal, $searchVal]);
            $targetStudent = $stmt->fetch();

            if (!$targetStudent) {
                $this->flash('error', 'Student not found in the platform.');
                redirect('/student/group');
            }

            $targetId = $targetStudent['user_id'];

            // Check if student is already in any group
            $stmt = $db->prepare("SELECT id FROM group_members WHERE student_id = ?");
            $stmt->execute([$targetId]);
            if ($stmt->fetch()) {
                $this->flash('error', 'This student is already a member of a group.');
                redirect('/student/group');
            }

            // Add to group
            try {
                $stmt = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                $stmt->execute([$group['id'], $targetId]);

                $this->addNotification($targetId, 'Added to Group', "You have been added to the project group by {$_SESSION['name']}.");
                $this->flash('success', "Student {$targetStudent['name']} added to group successfully.");
            } catch (\Exception $e) {
                $this->flash('error', 'Failed to add member: ' . $e->getMessage());
            }
        }
        redirect('/student/group');
    }

    public function proposal() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        $group = $this->getStudentGroup($userId);
        $proposal = null;
        $project = null;
        $groupMembers = [];
        $isLeader = false;

        if ($group) {
            $isLeader = ($group['created_by'] == $userId);
            
            // Get proposal
            $stmt = $db->prepare("SELECT * FROM proposals WHERE group_id = ?");
            $stmt->execute([$group['id']]);
            $proposal = $stmt->fetch();

            // Get project (for title, description, supervisor_id)
            $stmt = $db->prepare("SELECT * FROM projects WHERE group_id = ?");
            $stmt->execute([$group['id']]);
            $project = $stmt->fetch();

            // Get other members
            $stmt = $db->prepare("SELECT s.student_id, u.email, s.name FROM group_members gm JOIN students s ON gm.student_id = s.user_id JOIN users u ON s.user_id = u.id WHERE gm.group_id = ? AND gm.student_id != ?");
            $stmt->execute([$group['id'], $group['created_by']]);
            $groupMembers = $stmt->fetchAll();
        }

        // Fetch all supervisors for dropdown who have less than 8 approved slots, or who are currently selected by this project
        $currentSupervisorId = $project['supervisor_id'] ?? 0;
        $stmt = $db->prepare("
            SELECT s.user_id, s.name 
            FROM supervisors s
            WHERE (
                SELECT COUNT(*) 
                FROM projects p 
                WHERE p.supervisor_id = s.user_id AND p.status = 'Approved'
            ) < 8 OR s.user_id = ?
            ORDER BY s.name ASC
        ");
        $stmt->execute([$currentSupervisorId]);
        $supervisors = $stmt->fetchAll();

        $this->render('student/proposal', [
            'group' => $group,
            'proposal' => $proposal,
            'project' => $project,
            'groupMembers' => $groupMembers,
            'isLeader' => $isLeader,
            'supervisors' => $supervisors
        ]);
    }

    public function submitProposal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $db = \Database::getInstance()->getConnection();

            $group = $this->getStudentGroup($userId);
            
            // If they already have a group, make sure they are the leader!
            if ($group && $group['created_by'] != $userId) {
                $this->flash('error', 'Only the group leader (creator) can submit or edit the proposal.');
                redirect('/student/proposal');
            }

            $title = trim($_POST['title'] ?? '');
            $abstract = trim($_POST['abstract'] ?? '');
            $supervisor_id = $_POST['supervisor_id'] ?? null;
            $member1_ident = trim($_POST['member1'] ?? '');
            $member2_ident = trim($_POST['member2'] ?? '');

            if (empty($title) || empty($abstract) || empty($supervisor_id)) {
                $this->flash('error', 'Project Title, Abstract, and Supervisor are required fields.');
                redirect('/student/proposal');
            }

            // Check if supervisor exists
            $stmt = $db->prepare("SELECT name FROM supervisors WHERE user_id = ?");
            $stmt->execute([$supervisor_id]);
            $supervisorName = $stmt->fetchColumn();
            if (!$supervisorName) {
                $this->flash('error', 'Please select a valid supervisor.');
                redirect('/student/proposal');
            }

            // Check if supervisor has reached slot limit (unless already assigned to this group)
            $existingSupervisorId = null;
            if ($group) {
                $stmt = $db->prepare("SELECT supervisor_id FROM projects WHERE group_id = ?");
                $stmt->execute([$group['id']]);
                $existingSupervisorId = $stmt->fetchColumn();
            }

            if ($supervisor_id != $existingSupervisorId) {
                $stmt = $db->prepare("SELECT COUNT(*) FROM projects WHERE supervisor_id = ? AND status = 'Approved'");
                $stmt->execute([$supervisor_id]);
                $approvedCount = (int)$stmt->fetchColumn();
                if ($approvedCount >= 8) {
                    $this->flash('error', "Supervisor {$supervisorName} has reached their maximum capacity limit of 8 approved projects. Please select another supervisor.");
                    redirect('/student/proposal');
                }
            }

            // Handle file upload
            $fileUploaded = isset($_FILES['proposal_file']) && $_FILES['proposal_file']['error'] === UPLOAD_ERR_OK;
            
            // Get existing proposal to check if we can reuse the old file
            $existingProposal = null;
            if ($group) {
                $stmt = $db->prepare("SELECT * FROM proposals WHERE group_id = ?");
                $stmt->execute([$group['id']]);
                $existingProposal = $stmt->fetch();
            }

            if (!$fileUploaded && !$existingProposal) {
                $this->flash('error', 'Proposal file is required for new submissions.');
                redirect('/student/proposal');
            }

            $dbPath = $existingProposal['file_path'] ?? '';

            if ($fileUploaded) {
                $file = $_FILES['proposal_file'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['pdf', 'doc', 'docx'];

                if (!in_array($ext, $allowed)) {
                    $this->flash('error', 'Only PDF, DOC, and DOCX files are allowed.');
                    redirect('/student/proposal');
                }

                $uploadDir = __DIR__ . '/../../public/uploads/proposals/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file['name']);
                $destPath = $uploadDir . $fileName;

                if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                    $this->flash('error', 'Failed to save uploaded file.');
                    redirect('/student/proposal');
                }
                $dbPath = '/uploads/proposals/' . $fileName;
            }

            try {
                $db->beginTransaction();

                // Validate members
                $member_ids = [];
                $idents = array_filter([$member1_ident, $member2_ident]);
                
                // Get current student's registration ID / email to check if they tried to add themselves
                $stmt = $db->prepare("SELECT s.student_id, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
                $stmt->execute([$userId]);
                $currentUserInfo = $stmt->fetch();
                $currId = strtolower($currentUserInfo['student_id'] ?? '');
                $currEmail = strtolower($currentUserInfo['email'] ?? '');
                
                foreach ($idents as $ident) {
                    $identLower = strtolower($ident);
                    if ($identLower === $currId || $identLower === $currEmail) {
                        throw new \Exception("You cannot add yourself as a group member.");
                    }
                    
                    $stmt = $db->prepare("SELECT user_id FROM students s JOIN users u ON s.user_id = u.id WHERE LOWER(u.email) = ? OR LOWER(s.student_id) = ?");
                    $stmt->execute([$identLower, $identLower]);
                    $member = $stmt->fetch();
                    if (!$member) {
                        throw new \Exception("Member identifier '$ident' not found as a registered student.");
                    }
                    
                    $mId = $member['user_id'];
                    if (in_array($mId, $member_ids)) {
                        throw new \Exception("Duplicate member identifier '$ident'.");
                    }
                    
                    // Verify member is not in another group
                    $stmt = $db->prepare("SELECT group_id FROM group_members WHERE student_id = ?");
                    $stmt->execute([$mId]);
                    $existingGroup = $stmt->fetch();
                    if ($existingGroup && (!$group || $existingGroup['group_id'] != $group['id'])) {
                        throw new \Exception("Student '$ident' is already in another project group.");
                    }
                    
                    $member_ids[] = $mId;
                }

                $groupId = null;
                if (!$group) {
                    $stmt = $db->prepare("INSERT INTO groups (group_code, created_by, progress_stage) VALUES (NULL, ?, 'Proposal Submitted')");
                    $stmt->execute([$userId]);
                    $groupId = $db->lastInsertId();

                    // Insert leader into group members
                    $stmt = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                    $stmt->execute([$groupId, $userId]);

                    // Insert project
                    $stmt = $db->prepare("INSERT INTO projects (group_id, title, description, supervisor_id, status) VALUES (?, ?, ?, ?, 'Submitted')");
                    $stmt->execute([$groupId, $title, $abstract, $supervisor_id]);

                    // Insert grades placeholder
                    $stmt = $db->prepare("INSERT INTO grades (group_id) VALUES (?)");
                    $stmt->execute([$groupId]);
                } else {
                    $groupId = $group['id'];
                    
                    // Update projects
                    $stmt = $db->prepare("UPDATE projects SET title = ?, description = ?, supervisor_id = ?, status = 'Submitted' WHERE group_id = ?");
                    $stmt->execute([$title, $abstract, $supervisor_id, $groupId]);

                    // Update group progress stage
                    $stmt = $db->prepare("UPDATE groups SET progress_stage = 'Proposal Submitted' WHERE id = ?");
                    $stmt->execute([$groupId]);

                    // Reset non-leader group members
                    $stmt = $db->prepare("DELETE FROM group_members WHERE group_id = ? AND student_id != ?");
                    $stmt->execute([$groupId, $userId]);
                }

                // Add selected group members
                foreach ($member_ids as $mId) {
                    $stmt = $db->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
                    $stmt->execute([$groupId, $mId]);
                }

                // Insert/Update proposals table
                $stmt = $db->prepare("INSERT INTO proposals (group_id, abstract, file_path, status, submitted_at) 
                    VALUES (?, ?, ?, 'Submitted', CURRENT_TIMESTAMP) 
                    ON DUPLICATE KEY UPDATE abstract = ?, file_path = ?, status = 'Submitted', submitted_at = CURRENT_TIMESTAMP");
                $stmt->execute([$groupId, $abstract, $dbPath, $abstract, $dbPath]);

                $db->commit();

                // Get group code
                $groupLabel = !empty($group['group_code']) ? "Group {$group['group_code']}" : "A new project group";

                // Notify assigned supervisor
                $this->addNotification($supervisor_id, 'Proposal Submitted', "$groupLabel has submitted a project proposal selecting you as supervisor.");
                // Notify system admin and dean
                $this->addNotification(1, 'Proposal Submitted', "$groupLabel has submitted a project proposal.");
                $this->addNotification(2, 'Proposal Submitted', "$groupLabel has submitted a project proposal.");

                $this->flash('success', 'Project proposal submitted successfully!');
            } catch (\Exception $e) {
                $db->rollBack();
                $this->flash('error', $e->getMessage());
            }
        }
        redirect('/student/proposal');
    }


    public function grade() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        $group = $this->getStudentGroup($userId);
        $grade = null;
        $evaluations = [];

        if ($group) {
            $stmt = $db->prepare("SELECT * FROM grades WHERE group_id = ?");
            $stmt->execute([$group['id']]);
            $grade = $stmt->fetch();

            // Fetch all evaluations with evaluator names
            $stmtEvals = $db->prepare("SELECT e.*, c.name as evaluator_name 
                                       FROM evaluations e
                                       JOIN committees c ON e.evaluator_id = c.user_id
                                       WHERE e.group_id = ? AND e.total_marks > 0
                                       ORDER BY e.stage ASC, c.name ASC");
            $stmtEvals->execute([$group['id']]);
            $evaluations = $stmtEvals->fetchAll();
        }

        $this->render('student/grade', [
            'group' => $group,
            'grade' => $grade,
            'evaluations' => $evaluations
        ]);
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // Fetch student details
        $stmt = $db->prepare("SELECT s.name, u.email, s.avatar, s.student_id, s.shift FROM students s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
        $stmt->execute([$userId]);
        $student = $stmt->fetch();
        if (!$student) {
            die("Student profile not found.");
        }

        // Get existing profile info
        $stmt = $db->prepare("SELECT * FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        $isLocked = !empty($profile) && !empty($profile['home_address']) && $profile['home_address'] !== 'Not Provided Yet';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($isLocked) {
                $this->flash('error', 'Your profile is locked and cannot be edited after submission.');
                redirect('/student/profile');
            }
            $errors = [];
            $prefix = trim($_POST['prefix'] ?? '');
            $dob = trim($_POST['dob'] ?? '');
            $cnic_expiry = !empty($_POST['cnic_expiry']) ? $_POST['cnic_expiry'] : null;
            $place_of_birth = !empty($_POST['place_of_birth']) ? trim($_POST['place_of_birth']) : null;
            $city = !empty($_POST['city']) ? trim($_POST['city']) : null;
            $home_address = trim($_POST['home_address'] ?? '');
            $permanent_address = !empty($_POST['permanent_address']) ? trim($_POST['permanent_address']) : null;
            $zip_code = !empty($_POST['zip_code']) ? trim($_POST['zip_code']) : null;
            $blood_group = !empty($_POST['blood_group']) ? trim($_POST['blood_group']) : null;

            if (empty($prefix)) $errors[] = "Prefix is required.";
            if (empty($dob)) $errors[] = "Date Of Birth is required.";
            if (empty($home_address) || $home_address === 'Not Provided Yet') $errors[] = "Home Address / Postal Address is required.";

            if (empty($errors)) {
                try {
                    $stmt = $db->prepare("UPDATE profiles SET prefix = ?, dob = ?, cnic_expiry = ?, place_of_birth = ?, city = ?, home_address = ?, permanent_address = ?, zip_code = ?, blood_group = ? WHERE user_id = ?");
                    $stmt->execute([$prefix, $dob, $cnic_expiry, $place_of_birth, $city, $home_address, $permanent_address, $zip_code, $blood_group, $userId]);
                    
                    $this->flash('success', 'Profile updated successfully.');
                    redirect('/student/profile');
                } catch (\Exception $e) {
                    $this->flash('error', 'Database error: ' . $e->getMessage());
                }
            } else {
                $this->flash('error', implode(" ", $errors));
            }
        }

        $this->render('student/profile', [
            'student' => $student,
            'profile' => $profile
        ]);
    }
}
