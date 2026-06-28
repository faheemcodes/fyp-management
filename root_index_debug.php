<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
echo "Debugging Root...<br>";

$_SERVER['SCRIPT_NAME'] = '/public/index.php';

try {
    require __DIR__ . '/public/index.php';
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
