<?php
require 'src/Config/Database.php';
$db = Database::getInstance()->getConnection();
$tables = $db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    echo "TABLE: $table\n";
    print_r($db->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC));
}
