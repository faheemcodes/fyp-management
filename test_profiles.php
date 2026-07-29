<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
print_r($db->query('DESCRIBE profiles')->fetchAll(PDO::FETCH_ASSOC));
