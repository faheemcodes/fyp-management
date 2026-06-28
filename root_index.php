<?php
// This acts as a front-controller for shared hosting that doesn't allow proper .htaccess rewrites
$_SERVER['SCRIPT_NAME'] = '/public/index.php';
require __DIR__ . '/public/index.php';
