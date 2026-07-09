<?php
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/') { $basePath = ''; }
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$isSolidHeader = $isSolidHeader ?? false;
$navbarClass = $isSolidHeader ? 'lp-navbar scrolled' : 'lp-navbar';
?>
<nav class="<?php echo $navbarClass; ?>" id="lpNavbar">
    <div class="container">
        <div class="nav-inner">
            <a href="<?php echo $basePath; ?>/" class="brand">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo">
                <div class="brand-text">
                    <h1>Faculty of Engineering & Technology</h1>
                    <p>University of Sindh</p>
                </div>
            </a>
            <div class="nav-actions">
                <?php if (strpos($currentUri, 'register') === false): ?>
                    <a href="<?php echo $basePath; ?>/register" class="btn-nav btn-nav-ghost">Register</a>
                <?php endif; ?>
                
                <?php if (strpos($currentUri, 'login') === false): ?>
                    <a href="<?php echo $basePath; ?>/login" class="btn-nav btn-nav-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
