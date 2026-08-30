<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FYP Portal - University of Sindh</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') {
        $basePath = '';
    }
    ?>
    <link rel="icon" href="<?php echo $basePath; ?>/images/logo.png" type="image/png">
    <link href="<?php echo $basePath; ?>/css/style.css?v=1.2.4" rel="stylesheet">
    <link href="<?php echo $basePath; ?>/css/admin-theme.css?v=1.2.4" rel="stylesheet">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-theme');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
        if (localStorage.getItem('sidebar_collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
</head>
<body data-role="<?php echo htmlspecialchars($_SESSION['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <?php include __DIR__ . '/loader.php'; ?>
<div class="d-flex">
    <!-- Sidebar component is loaded separately right after header -->
