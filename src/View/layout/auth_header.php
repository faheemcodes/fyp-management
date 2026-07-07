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

<!-- ─── Top Header ─── -->
<header class="header-top">
    <div class="container header-inner d-flex justify-content-between align-items-center">
        <a class="header-brand" href="<?php echo $basePath; ?>/login">
            <div class="header-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="header-brand-text">
                <h1>University of Sindh</h1>
                <p>Faculty of Engineering & Technology</p>
            </div>
        </a>
        <?php if(isset($headerBtnLink) && isset($headerBtnText)): ?>
        <a href="<?php echo $basePath . $headerBtnLink; ?>" class="header-signup-btn"><?php echo $headerBtnText; ?></a>
        <?php endif; ?>
    </div>
</header>
