<?php
namespace Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends BaseController {
    
    public function index() {
        redirect('/login');
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginType = $_POST['login_type'] ?? 'student';
            $password = $_POST['password'] ?? '';
            
            if (empty($password)) {
                $this->flash('error', 'Password is required.');
                redirect('/login');
            }
            
            $db = \Database::getInstance()->getConnection();
            $user = null;
            
            if ($loginType === 'student') {
                $studentId = trim($_POST['student_id'] ?? '');
                if (empty($studentId)) {
                    $this->flash('error', 'Roll Number is required.');
                    redirect('/login');
                }
                // Retrieve user matching student registration ID
                $stmt = $db->prepare("SELECT u.* FROM students s JOIN users u ON s.user_id = u.id WHERE s.student_id = ?");
                $stmt->execute([$studentId]);
                $user = $stmt->fetch();
            } else {
                $cnic = trim($_POST['cnic'] ?? '');
                if (empty($cnic)) {
                    $this->flash('error', 'CNIC is required.');
                    redirect('/login');
                }
                // Retrieve user matching CNIC (or fallback to email for backwards compatibility with seeds)
                $stmt = $db->prepare("SELECT * FROM users WHERE cnic = ? OR email = ?");
                $stmt->execute([$cnic, $cnic]);
                $user = $stmt->fetch();
            }
            
            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] === 'pending') {
                    $this->flash('error', 'Your account is pending approval.');
                    redirect('/login');
                } else if ($user['status'] === 'rejected') {
                    $this->flash('error', 'Your account registration has been rejected.');
                    redirect('/login');
                }
                
                // Set session details
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Fetch profile specific name
                if ($user['role'] === 'student') {
                    $sStmt = $db->prepare("SELECT name, student_id, avatar FROM students WHERE user_id = ?");
                    $sStmt->execute([$user['id']]);
                    $student = $sStmt->fetch();
                    $_SESSION['name'] = $student['name'] ?? 'Student';
                    $_SESSION['student_id'] = $student['student_id'] ?? '';
                    $_SESSION['avatar'] = $student['avatar'] ?? '';
                } else if ($user['role'] === 'supervisor') {
                    $sStmt = $db->prepare("SELECT name FROM supervisors WHERE user_id = ?");
                    $sStmt->execute([$user['id']]);
                    $supervisor = $sStmt->fetch();
                    $_SESSION['name'] = $supervisor['name'] ?? 'Supervisor';
                } else if ($user['role'] === 'committee') {
                    $sStmt = $db->prepare("SELECT name FROM committees WHERE user_id = ?");
                    $sStmt->execute([$user['id']]);
                    $committee = $sStmt->fetch();
                    $_SESSION['name'] = $committee['name'] ?? 'Committee Member';
                } else if ($user['role'] === 'dean') {
                    $sStmt = $db->prepare("SELECT name FROM deans WHERE user_id = ?");
                    $sStmt->execute([$user['id']]);
                    $dean = $sStmt->fetch();
                    $_SESSION['name'] = $dean['name'] ?? 'Dean';
                } else {
                    $_SESSION['name'] = 'System Admin';
                }
                
                redirect('/' . $user['role'] . '/dashboard');
            } else {
                $this->flash('error', 'Invalid login credentials or password.');
                redirect('/login');
            }
        }
        
        $this->render('auth/login');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save old input values to preserve form state on validation failure
            $_SESSION['old'] = $_POST;
            $_SESSION['has_old_data'] = true;
            unset($_SESSION['old']['password']);
            unset($_SESSION['old']['confirm_password']);
            unset($_SESSION['old']['staff_password']);
            unset($_SESSION['old']['confirm_staff_password']);

            $role = $_POST['role'] ?? 'student';
            $db = \Database::getInstance()->getConnection();
            
            if ($role === 'student') {
                $student_id = trim($_POST['student_id'] ?? ''); // Roll No
                $cnic = trim($_POST['cnic'] ?? '');
                $confirm_cnic = trim($_POST['confirm_cnic'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $mobile_code = trim($_POST['mobile_code'] ?? '');
                $mobile_no = trim($_POST['mobile_no'] ?? '');
                $name = trim($_POST['name'] ?? '');
                $father_name = trim($_POST['father_name'] ?? '');
                $surname = trim($_POST['surname'] ?? '');
                $gender = trim($_POST['gender'] ?? '');
                $country = trim($_POST['country'] ?? '');
                $province_state = trim($_POST['province_state'] ?? '');
                $district = trim($_POST['district'] ?? '');
                $department = trim($_POST['student_department'] ?? '');
                $shift = trim($_POST['shift'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                
                // Detailed check for missing fields
                $missingFields = [];
                if (empty($student_id)) $missingFields[] = 'Roll No.';
                if (empty($cnic)) $missingFields[] = 'CNIC No.';
                if (empty($confirm_cnic)) $missingFields[] = 'Re-Type CNIC No.';
                if (empty($email)) $missingFields[] = 'Email Address';
                if (empty($mobile_code)) $missingFields[] = 'Mobile Code';
                if (empty($mobile_no)) $missingFields[] = 'Mobile Number';
                if (empty($name)) $missingFields[] = 'Full Name';
                if (empty($father_name)) $missingFields[] = 'Father\'s Name';
                if (empty($gender)) $missingFields[] = 'Gender';
                if (empty($country)) $missingFields[] = 'Country';
                if (empty($province_state)) $missingFields[] = 'Domicile Province';
                if (empty($district)) $missingFields[] = 'Domicile District';
                if (empty($department)) $missingFields[] = 'Department';
                if (empty($shift)) $missingFields[] = 'Shift';
                if (empty($password)) $missingFields[] = 'Password';

                if (!empty($missingFields)) {
                    $this->flash('error', 'The following mandatory fields are missing: ' . implode(', ', $missingFields));
                    redirect('/register');
                }

                $allowedDepts = [
                    'Information Technology',
                    'Software Engineering',
                    'Data Science',
                    'Electronic Engineering',
                    'Telecommunication Engineering'
                ];
                if (!in_array($department, $allowedDepts)) {
                    $this->flash('error', 'Please select a valid department.');
                    redirect('/register');
                }
                
                $allowedShifts = ['Morning', 'Evening'];
                if (!in_array($shift, $allowedShifts)) {
                    $this->flash('error', 'Please select a valid shift.');
                    redirect('/register');
                }
                
                if (strpos($cnic, '-') !== false) {
                    $this->flash('error', 'CNIC must be entered without dashes.');
                    redirect('/register');
                }
                
                if ($cnic !== $confirm_cnic) {
                    $this->flash('error', 'CNIC / B-Form entries do not match.');
                    redirect('/register');
                }
                
                if (strlen($password) < 8) {
                    $this->flash('error', 'Password must be at least 8 characters long.');
                    redirect('/register');
                }
                
                if ($password !== $confirm_password) {
                    $this->flash('error', 'Passwords do not match.');
                    redirect('/register');
                }
                
                // Profile Image / Avatar File Validation
                if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                    $this->flash('error', 'Profile image is required.');
                    redirect('/register');
                }
                
                $file = $_FILES['avatar'];
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/x-png', 'image/pjpeg'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                
                if (!in_array($file['type'], $allowedTypes) && !in_array($extension, $allowedExtensions)) {
                    $this->flash('error', 'Profile image must be in JPG, JPEG, or PNG format.');
                    redirect('/register');
                }
                
                if ($file['size'] > 500 * 1024) {
                    $this->flash('error', 'Profile image size cannot exceed 500KB.');
                    redirect('/register');
                }
                
                // Check uniqueness of email
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $this->flash('error', 'This email address is already registered.');
                    redirect('/register');
                }
                
                // Check uniqueness of CNIC
                $stmt = $db->prepare("SELECT id FROM users WHERE cnic = ?");
                $stmt->execute([$cnic]);
                if ($stmt->fetch()) {
                    $this->flash('error', 'This CNIC / B-Form number is already registered.');
                    redirect('/register');
                }
                
                // Check uniqueness of Student ID (Roll No)
                $stmt = $db->prepare("SELECT user_id FROM students WHERE student_id = ?");
                $stmt->execute([$student_id]);
                if ($stmt->fetch()) {
                    $this->flash('error', 'This Roll Number is already registered.');
                    redirect('/register');
                }
                
                // Upload and save the profile image
                $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                // Use extension extracted and validated above
                $avatarName = 'avatar_' . time() . '_' . uniqid() . '.' . $extension;
                $avatarPath = $uploadDir . $avatarName;
                
                if (!move_uploaded_file($file['tmp_name'], $avatarPath)) {
                    $this->flash('error', 'Failed to save uploaded profile image.');
                    redirect('/register');
                }
                
                try {
                    $db->beginTransaction();
                    
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, 'student', 'pending')");
                    $stmt->execute([$email, $cnic, $hashedPassword]);
                    $userId = $db->lastInsertId();
                    
                    $phoneCombined = $mobile_code . $mobile_no;
                    
                    // Insert into students table
                    $stmt = $db->prepare("INSERT INTO students (user_id, student_id, name, phone, department, shift, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $student_id, $name, $phoneCombined, $department, $shift, $avatarName]);
                    
                    // Seed profiles table
                    $stmt = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, father_name, dob, mobile_code, mobile_no, country, province_state, district, home_address, gender) VALUES (?, 'Mr.', ?, ?, ?, '2000-01-01', ?, ?, ?, ?, ?, 'Not Provided Yet', ?)");
                    $stmt->execute([$userId, $surname, $cnic, $father_name, $mobile_code, $mobile_no, $country, $province_state, $district, $gender]);
                    
                    $db->commit();
                    
                    $this->addNotification(1, 'New Student Registration', "Student {$name} ({$student_id}) registered and is pending approval.");
                    
                    unset($_SESSION['old']); // success, clear old inputs
                    $this->flash('success', 'Registration successful! Your student account is pending review by Admin/Dean.');
                    redirect('/login');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Student registration transaction failed: ' . $e->getMessage());
                    redirect('/register');
                }
            } else {
                // Teacher / HOD / Admin Registration
                $name = trim($_POST['staff_first_name'] ?? '');
                $surname = trim($_POST['staff_last_name'] ?? '');
                $email = trim($_POST['staff_email'] ?? '');
                $cnic = trim($_POST['staff_cnic'] ?? '');
                $department = trim($_POST['staff_department'] ?? '');
                $designation = trim($_POST['designation'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $password = $_POST['staff_password'] ?? '';
                $confirm_password = $_POST['confirm_staff_password'] ?? '';
                
                // Detailed check for missing fields
                $missingFields = [];
                if (empty($name)) $missingFields[] = 'First Name';
                if (empty($surname)) $missingFields[] = 'Last Name';
                if (empty($email)) $missingFields[] = 'Email Address';
                if (empty($cnic)) $missingFields[] = 'CNIC No.';
                if (empty($department)) $missingFields[] = 'Department';
                if (empty($designation)) $missingFields[] = 'Designation';
                if (empty($phone)) $missingFields[] = 'Contact Number';
                if (empty($password)) $missingFields[] = 'Password';

                if (!empty($missingFields)) {
                    $this->flash('error', 'The following mandatory fields are missing: ' . implode(', ', $missingFields));
                    redirect('/register');
                }
                
                if (strlen($password) < 8) {
                    $this->flash('error', 'Password must be at least 8 characters long.');
                    redirect('/register');
                }
                
                if ($password !== $confirm_password) {
                    $this->flash('error', 'Passwords do not match.');
                    redirect('/register');
                }
                
                // Validate CNIC no dashes
                if (strpos($cnic, '-') !== false) {
                    $this->flash('error', 'CNIC must be entered without dashes.');
                    redirect('/register');
                }
                
                // Check uniqueness of email
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $this->flash('error', 'This email address is already registered.');
                    redirect('/register');
                }
                
                // Check uniqueness of CNIC
                $stmt = $db->prepare("SELECT id FROM users WHERE cnic = ?");
                $stmt->execute([$cnic]);
                if ($stmt->fetch()) {
                    $this->flash('error', 'This CNIC is already registered.');
                    redirect('/register');
                }
                
                try {
                    $db->beginTransaction();
                    
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (email, cnic, password, role, status) VALUES (?, ?, ?, ?, 'pending')");
                    $stmt->execute([$email, $cnic, $hashedPassword, $role]);
                    $userId = $db->lastInsertId();
                    
                    $fullName = $name . ' ' . $surname;
                    
                    if ($role === 'supervisor') {
                        $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$userId, $fullName, $designation, $department]);
                    } else if ($role === 'dean') {
                        $stmt = $db->prepare("INSERT INTO deans (user_id, name, department) VALUES (?, ?, ?)");
                        $stmt->execute([$userId, $fullName, $department]);
                    }
                    
                    // Seed profiles table
                    $stmt = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES (?, 'Dr.', ?, ?, '1980-01-01', '+92', ?, 'Not Provided Yet', 'Male')");
                    $stmt->execute([$userId, $surname, $cnic, $phone]);
                    
                    $db->commit();
                    
                    $this->addNotification(1, 'New Staff Registration', "A new staff member ($fullName) registered as $role and is pending approval.");
                    
                    unset($_SESSION['old']); // success, clear old inputs
                    $this->flash('success', 'Registration successful! Your staff account is pending review.');
                    redirect('/login');
                } catch (\Exception $e) {
                    $db->rollBack();
                    $this->flash('error', 'Staff registration transaction failed: ' . $e->getMessage());
                    redirect('/register');
                }
            }
        }
        
        $this->render('auth/register');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if (empty($email)) {
                $this->flash('error', 'Please provide your email.');
                redirect('/forgot-password');
            }
            
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', time() + 3600); // 1 hour validity
                
                $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
                $stmt->execute([$token, $expiry, $user['id']]);
                
                // Construct reset password link
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $scriptName = $_SERVER['SCRIPT_NAME'];
                $baseDir = dirname($scriptName);
                if ($baseDir === '\\' || $baseDir === '/') {
                    $baseDir = '';
                }
                $resetLink = $protocol . '://' . $host . $baseDir . '/reset-password?token=' . $token;
                
                // Log email to file
                $logDir = __DIR__ . '/../../sessions';
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0700, true);
                }
                $logFile = $logDir . '/reset_emails.log';
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Reset request for $email. Link: $resetLink\n";
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                
                $subject = "Password Reset Request - FYP Management Portal";
                $message = "Hello,\n\n" .
                           "We received a request to reset your password for your FYP Management Portal account.\n" .
                           "Please click the link below to set a new password:\n\n" .
                           $resetLink . "\n\n" .
                           "This link is valid for 1 hour. If you did not make this request, you can safely ignore this email.\n\n" .
                           "Regards,\nUniversity of Sindh FYP Portal Support";
                $headers = "From: noreply@usindh.edu.pk\r\n" .
                           "Reply-To: noreply@usindh.edu.pk\r\n" .
                           "X-Mailer: PHP/" . phpversion();

                // Load SMTP mail config
                $mailConfig = require __DIR__ . '/../../config/mail.php';
                $emailSent = false;

                // Try PHPMailer if SMTP credentials are configured (not default placeholders)
                if (isset($mailConfig['smtp_username']) && $mailConfig['smtp_username'] !== 'your_email@gmail.com' && !empty($mailConfig['smtp_password'])) {
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = $mailConfig['smtp_host'];
                        $mail->SMTPAuth   = $mailConfig['smtp_auth'];
                        $mail->Username   = $mailConfig['smtp_username'];
                        $mail->Password   = $mailConfig['smtp_password'];
                        $mail->SMTPSecure = ($mailConfig['smtp_secure'] === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = $mailConfig['smtp_port'];

                        // Recipients
                        $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(false);
                        $mail->Subject = $subject;
                        $mail->Body    = $message;

                        $mail->send();
                        $emailSent = true;
                    } catch (\Exception $e) {
                        // Log failure and let it fall back
                        error_log("PHPMailer failed: " . $mail->ErrorInfo);
                    }
                }

                // Fallback to PHP built-in mail()
                if (!$emailSent) {
                    @mail($email, $subject, $message, $headers);
                }
                
                $this->flash('success', 'Password reset instructions have been sent to your email address.');
            } else {
                $this->flash('error', 'No account found with this email address.');
            }
            redirect('/forgot-password');
        }
        $this->render('auth/forgot-password');
    }
    
    public function resetPassword() {
        $db = \Database::getInstance()->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = trim($_POST['token'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($token)) {
                $this->flash('error', 'Invalid token.');
                redirect('/forgot-password');
            }
            
            if (strlen($password) < 8) {
                $this->flash('error', 'Password must be at least 8 characters long.');
                redirect('/reset-password?token=' . urlencode($token));
            }
            
            if ($password !== $confirmPassword) {
                $this->flash('error', 'Passwords do not match.');
                redirect('/reset-password?token=' . urlencode($token));
            }
            
            // Check token validity (make sure token is valid and not expired)
            $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > ?");
            $stmt->execute([$token, date('Y-m-d H:i:s')]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $this->flash('error', 'The password reset token is invalid or has expired.');
                redirect('/forgot-password');
            }
            
            // Hash and update the new password, and clear the token columns
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            
            $this->flash('success', 'Your password has been reset successfully. You can now log in.');
            redirect('/login');
        } else {
            $token = trim($_GET['token'] ?? '');
            if (empty($token)) {
                $this->flash('error', 'No token provided.');
                redirect('/forgot-password');
            }
            
            $stmt = $db->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > ?");
            $stmt->execute([$token, date('Y-m-d H:i:s')]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $this->flash('error', 'The password reset token is invalid or has expired.');
                redirect('/forgot-password');
            }
            
            $this->render('auth/reset-password', ['token' => $token]);
        }
    }
    
    public function logout() {
        session_destroy();
        redirect('/login');
    }
    
    public function fetchNotifications() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
        }
        
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();
        
        $stmtCount = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmtCount->execute([$userId]);
        $unreadCount = (int)$stmtCount->fetchColumn();
        
        $this->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
    
    public function markNotificationRead() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
        }
        
        $db = \Database::getInstance()->getConnection();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $notifId = $data['id'] ?? null;
            
            if ($notifId) {
                $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
                $stmt->execute([$notifId, $userId]);
            } else {
                // Mark all as read
                $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
                $stmt->execute([$userId]);
            }
            $this->json(['success' => true]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $password = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($password) || empty($confirmPassword)) {
                $this->flash('error', 'All fields are required.');
                redirect('/change-password');
            }
            
            $db = \Database::getInstance()->getConnection();
            $userId = $_SESSION['user_id'];
            
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $this->flash('error', 'Incorrect current password.');
                redirect('/change-password');
            }
            
            if ($currentPassword === $password) {
                $this->flash('error', 'New password cannot be the same as your current password.');
                redirect('/change-password');
            }
            
            $len = strlen($password);
            if ($len < 8 || $len > 50) {
                $this->flash('error', 'New password must be between 8 and 50 characters in length.');
                redirect('/change-password');
            }
            if (!preg_match('/[0-9]/', $password)) {
                $this->flash('error', 'New password must contain at least one digit.');
                redirect('/change-password');
            }
            if (!preg_match('/[a-z]/', $password)) {
                $this->flash('error', 'New password must contain at least one lowercase character.');
                redirect('/change-password');
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $this->flash('error', 'New password must contain at least one uppercase character.');
                redirect('/change-password');
            }
            if (!preg_match('/[!@#\$%\^&\*\(\)\.]/', $password)) {
                $this->flash('error', 'New password must contain at least one special character [!,@,#,$,%,^,&,*,(,),.].');
                redirect('/change-password');
            }
            
            if ($password !== $confirmPassword) {
                $this->flash('error', 'New password and confirm password do not match.');
                redirect('/change-password');
            }
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $userId]);
            
            $this->flash('success', 'Your password has been changed successfully.');
            redirect('/' . $_SESSION['role'] . '/dashboard');
        } else {
            $this->render('auth/change-password');
        }
    }
}
