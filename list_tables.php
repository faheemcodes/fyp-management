<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "Filesize: " . filesize('database.sqlite') . "\n";
try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
    $tables = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach($tables as $t) {
        echo "- " . $t['name'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
