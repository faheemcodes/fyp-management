<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
print_r($db->query("DESCRIBE profiles")->fetchAll(PDO::FETCH_ASSOC));
print_r($db->query("DESCRIBE coordinators")->fetchAll(PDO::FETCH_ASSOC));
print_r($db->query("DESCRIBE committees")->fetchAll(PDO::FETCH_ASSOC));
