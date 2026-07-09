<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'FYP Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') {
        $basePath = '';
    }
    ?>
    <link rel="icon" href="<?php echo $basePath; ?>/images/logo.png" type="image/png">
    <link href="<?php echo $basePath; ?>/css/style.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/css/auth.css" rel="stylesheet">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-theme');
        }
    </script>
</head>
<body>
    <?php include __DIR__ . '/loader.php'; ?>

<?php 
$isSolidHeader = true; 
include __DIR__ . '/lp_navbar.php'; 
?>
