<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
try {
    $db->beginTransaction();
    $stmtP = $db->prepare("INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender, province_state, district, father_name) VALUES (?, ?, ?, ?, ?, '+92', ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE prefix = ?, surname = ?, cnic = ?, dob = ?, mobile_no = ?, home_address = ?, gender = ?, province_state = ?, district = ?, father_name = ?");
    
    // Simulate updating a coordinator with ID 1 (or any ID that exists)
    $stmt = $db->query("SELECT id FROM users WHERE role = 'coordinator' LIMIT 1");
    $user = $stmt->fetch();
    if (!$user) {
        $stmt = $db->query("SELECT id FROM users LIMIT 1");
        $user = $stmt->fetch();
    }
    if ($user) {
        $id = $user['id'];
        $prefix = 'Dr.';
        $surname = '';
        $profile_cnic = '';
        $dob = '2000-01-01';
        $mobile_no = '';
        $home_address = '';
        $gender = 'Male';
        $province_state = '';
        $district = '';
        $father_name = '';
        
        $stmtP->execute([$id, $prefix, $surname, $profile_cnic, $dob, $mobile_no, $home_address, $gender, $province_state, $district, $father_name, $prefix, $surname, $profile_cnic, $dob, $mobile_no, $home_address, $gender, $province_state, $district, $father_name]);
        echo "Profile updated successfully for ID $id\n";
    }
    $db->rollBack();
} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
