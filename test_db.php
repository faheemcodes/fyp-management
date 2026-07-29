<?php
require_once __DIR__ . '/src/Database.php';
$db = Database::getInstance()->getConnection();
foreach (['hods', 'committees', 'coordinators', 'supervisors'] as $table) {
    echo "TABLE $table:\n";
    $stmt = $db->query("DESCRIBE $table");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
