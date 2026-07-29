<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
try {
    $db->exec('ALTER TABLE hods ADD COLUMN designation VARCHAR(100) NULL AFTER name');
    echo "Added designation to hods\n";
} catch(Exception $e) {
    echo $e->getMessage();
}
