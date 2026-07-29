<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();

$id = 4; // Assuming Kamran's ID is 4, wait let me check the DB.
$email = 'kamii@fyp.com';
$cnic = '';
$password = '';
$role = 'supervisor';
$department = 'Information Technology';
$prefix = 'Mr.';
$surname = '';
$student_id = '';
$shift = 'Morning';
$designation = 'Assistant Professor';
$mobile_no = '3001234567';
$gender = 'Male';
$dob = '2000-01-01';
$province_state = '';
$district = '';
$home_address = '';
$father_name = '';

$name = 'Kamran';

// Remove dashes from CNIC if present
$cnic = str_replace('-', '', $cnic);

// For users table, empty string must be null to avoid UNIQUE constraint violations
$user_cnic = ($cnic === '') ? null : $cnic;
// For profiles table, cnic cannot be null
$profile_cnic = ($cnic === '') ? '' : $cnic;

try {
    $db->beginTransaction();
    
    // Update users table
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ?, password = ? WHERE email = ?");
        $stmt->execute([$email, $user_cnic, $hashed, $email]);
    } else {
        $stmt = $db->prepare("UPDATE users SET email = ?, cnic = ? WHERE email = ?");
        $stmt->execute([$email, $user_cnic, $email]);
    }
    
    // Get the user ID
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $id = $stmt->fetchColumn();

    if (!$id) {
        throw new Exception("User not found");
    }

    echo "Updating user ID: $id\n";
    
    // Update role-specific table and profiles
    if ($role === 'student') {
        $stmt = $db->prepare("INSERT INTO students (user_id, student_id, name, department, shift) VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE student_id = ?, name = ?, department = ?, shift = ?");
        $stmt->execute([$id, $student_id, $name, $department, $shift, $student_id, $name, $department, $shift]);
    } else if ($role === 'supervisor') {
        $stmt = $db->prepare("INSERT INTO supervisors (user_id, name, designation, department) VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name = ?, designation = ?, department = ?");
        $stmt->execute([$id, $name, $designation, $department, $name, $designation, $department]);
    } else if ($role === 'hod') {
        $stmt = $db->prepare("INSERT INTO hods (user_id, name, department) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE name = ?, department = ?");
        $stmt->execute([$id, $name, $department, $name, $department]);
    } else if ($role === 'coordinator') {
        $stmt = $db->prepare("INSERT INTO coordinators (user_id, name, department, designation) VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name = ?, department = ?, designation = ?");
        $stmt->execute([$id, $name, $department, $designation, $name, $department, $designation]);
    } else if ($role === 'committee') {
        $stmt = $db->prepare("INSERT INTO committees (user_id, name, department, designation) VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name = ?, department = ?, designation = ?");
        $stmt->execute([$id, $name, $department, $designation, $name, $department, $designation]);
    }
    
    echo "Updating profiles table\n";
    // Keep profiles table in sync
    $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender, province_state, district, father_name) VALUES (?, ?, ?, ?, ?, '+92', ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE prefix = ?, surname = ?, cnic = ?, dob = ?, mobile_no = ?, home_address = ?, gender = ?, province_state = ?, district = ?, father_name = ?");
    $stmtP->execute([$id, $prefix, $surname, $profile_cnic, $dob, $mobile_no, $home_address, $gender, $province_state, $district, $father_name, $prefix, $surname, $profile_cnic, $dob, $mobile_no, $home_address, $gender, $province_state, $district, $father_name]);
    
    $db->commit();
    echo "User account updated successfully.\n";
} catch (\Exception $e) {
    $db->rollBack();
    echo "Error updating user: " . $e->getMessage() . "\n";
}
