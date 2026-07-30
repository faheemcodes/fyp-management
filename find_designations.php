<?php
require_once 'config/database.php';
$db = Database::getInstance()->getConnection();
$tables = ['supervisors', 'hods', 'committees', 'coordinators', 'profiles'];
$designations = [];
foreach($tables as $t) {
    try {
        $stmt = $db->query("SELECT DISTINCT designation FROM $t WHERE designation IS NOT NULL AND designation != ''");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $designations[] = $row['designation'];
        }
    } catch(Exception $e) {}
}
print_r(array_unique($designations));
?>
