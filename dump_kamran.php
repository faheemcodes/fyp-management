<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
print_r($db->query("SELECT u.email, u.cnic as users_cnic, p.cnic as profiles_cnic FROM users u LEFT JOIN profiles p ON u.id = p.user_id WHERE u.email = 'kamii@fyp.com'")->fetchAll(PDO::FETCH_ASSOC));
