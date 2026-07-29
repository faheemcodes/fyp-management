<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
try {
    $db->exec("ALTER TABLE supervisors DROP COLUMN research_interest");
    echo "Column research_interest removed successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
