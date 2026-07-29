<?php
require_once __DIR__ . '/src/Database.php';
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT u.email, prof.* FROM users u LEFT JOIN profiles prof ON u.id = prof.user_id WHERE u.email = 'teststudent@fyp.com'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
$stmt2 = $db->query("SELECT * FROM students WHERE user_id = (SELECT id FROM users WHERE email='teststudent@fyp.com')");
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
