<?php
$users = [
    [
        'id' => 1, 'name' => 'John', 'role' => 'coordinator', 'email' => 'j@j.com', 'cnic' => '123',
        'status' => 'approved', 'created_at' => '2023', 'student_id' => '', 'avatar' => '',
        'shift' => '', 'designation' => 'Lecturer', 'department' => 'CS', 'prefix' => 'Mr.',
        'surname' => 'Doe', 'father_name' => 'F', 'dob' => '2000-01-01', 'mobile_no' => '123',
        'province_state' => 'S', 'district' => 'D', 'home_address' => 'H', 'gender' => 'Male'
    ]
];
$_SESSION = ['csrf_token' => 'abc'];
$basePath = '';
ob_start();
include 'src/View/admin/users.php';
$html = ob_get_clean();
echo "Rendered length: " . strlen($html) . "\n";
