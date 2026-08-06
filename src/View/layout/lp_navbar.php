<?php
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/') { $basePath = ''; }
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$isSolidHeader = $isSolidHeader ?? false;
$navbarClass = $isSolidHeader ? 'lp-navbar scrolled' : 'lp-navbar';
$alwaysSolidAttr = $isSolidHeader ? 'data-always-solid="true"' : '';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;500;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
        :root {
            --font-heading-main: 'Jost', 'Chuner', 'Bebas Neue', 'Impact', sans-serif;
            --font-heading-alt: 'Pierknife', 'Oswald', 'Arial Narrow', sans-serif;
            --font-body: 'Inter', -apple-system, sans-serif;
        }
        :root[data-theme="light"] {
            --lp-bg: #ffffff;
            --lp-bg-alt: #f8fafc;
            --lp-card: #ffffff;
            --lp-card-hover: #f1f5f9;
            --lp-border: rgba(0,0,0,0.06);
            --lp-text: #111827;
            --lp-text-muted: #64748b;
            --lp-accent: #10b981;
            --lp-nav-bg: rgba(255,255,255,0.9);
            --lp-violet: #8b5cf6;
            --lp-rose: #f43f5e;
            --lp-amber: #f59e0b;
            --lp-teal: #14b8a6;
        }
        :root[data-theme="dark"] {
            --lp-bg: #121212;
            --lp-bg-alt: #18181b;
            --lp-card: #27272a;
            --lp-card-hover: #3f3f46;
            --lp-border: rgba(255,255,255,0.08);
            --lp-text: #f8fafc;
            --lp-text-muted: #a1a1aa;
            --lp-accent: #10b981;
            --lp-nav-bg: rgba(18,18,18,0.9);
            --lp-violet: #a78bfa;
            --lp-rose: #fb7185;
            --lp-amber: #fbbf24;
            --lp-teal: #2dd4bf;
        }

        html, body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: var(--lp-bg);
            color: var(--lp-text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            transition: background-color 0.3s, color 0.3s;
            -webkit-font-smoothing: antialiased;
        }

        /* Light theme texture background */
        :root[data-theme="light"] body {
            background-image: url('<?php echo $basePath ?? ""; ?>/images/bg-light.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* Dark theme texture background */
        :root[data-theme="dark"] body {
            background-image: url('<?php echo $basePath ?? ""; ?>/images/bg-dark.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .heading-main { font-family: var(--font-heading-main); text-transform: uppercase; letter-spacing: 1px; }
        .heading-alt { font-family: var(--font-heading-alt); text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* BUTTONS */
        .btn-hero { padding: 16px 36px; border-radius: 12px; font-size: 1rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-hero-fill { background: var(--lp-text); color: var(--lp-bg) !important; }
        .btn-hero-fill:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-hero-outline { background: rgba(128, 128, 128, 0.15); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: var(--lp-text) !important; border: 2px solid var(--lp-text); }
        .btn-hero-outline:hover { background: var(--lp-text); color: var(--lp-bg) !important; transform: translateY(-3px); }
        
        .lp-navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 20px 0;
            transition: all 0.3s ease;
            background: transparent !important;
            font-family: var(--font-body);
        }
        .lp-navbar.scrolled {
            background: var(--lp-nav-bg) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--lp-border);
            padding: 12px 0;
        }
        .lp-navbar.nav-hidden {
            transform: translateY(-100%);
        }
        .lp-navbar .brand-text h1 { font-family: var(--font-body); font-size: 0.95rem; font-weight: 700; margin: 0; color: var(--lp-text) !important; letter-spacing: -0.01em; line-height: 1.1; }
        .lp-navbar .brand-text p { font-size: 0.72rem; margin: 0; color: var(--lp-text-muted) !important; font-family: var(--font-body); letter-spacing: 0.02em; font-weight: 500; }
        .lp-navbar .brand { text-decoration: none; display: flex; align-items: center; gap: 14px; }
        .lp-navbar .brand img { width: 40px; height: 40px; object-fit: contain; }
        .lp-navbar .nav-inner { display: flex; align-items: center; justify-content: space-between; }
        .lp-navbar .nav-actions { display: flex; align-items: center; gap: 10px; }
        
        .lp-navbar .btn-nav { padding: 9px 24px; border-radius: 50px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: all 0.25s ease; border: none; display: inline-flex; align-items: center; gap: 6px; font-family: var(--font-body); white-space: nowrap; }
        .lp-navbar .btn-nav-ghost { color: var(--lp-text-muted); background: transparent; }
        .lp-navbar .btn-nav-ghost:hover { color: var(--lp-text); background: var(--lp-bg-alt); }
        .lp-navbar .btn-nav-primary { 
            background: transparent; 
            color: var(--lp-text) !important; 
            border: 2px solid var(--lp-text);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .lp-navbar .btn-nav-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: var(--lp-text);
            transition: width 0.3s ease;
            z-index: -1;
        }
        .lp-navbar .btn-nav-primary:hover::before {
            width: 100%;
        }
        .lp-navbar .btn-nav-primary:hover { 
            color: var(--lp-bg) !important; 
        }

    /* Mobile Responsiveness for Navbar */
    @media (max-width: 576px) {
        .lp-navbar .brand-text h1 {
            font-size: 0.95rem;
        }
        .lp-navbar .brand-text p {
            font-size: 0.65rem !important;
            display: none; /* Hide subtitle on mobile for cleaner look */
        }
        .lp-navbar .brand {
            gap: 8px !important;
        }
        .lp-navbar .brand img {
            width: 32px !important;
            height: 32px !important;
        }
        .lp-navbar .nav-actions {
            gap: 5px !important;
        }
        .lp-navbar .btn-nav {
            padding: 7px 14px !important;
            font-size: 0.8rem !important;
        }
    }

    /* Floating Theme Button */
    .floating-theme-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--lp-card, #ffffff);
        color: var(--lp-text, #000000);
        border: 1px solid var(--lp-border, rgba(0,0,0,0.1));
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    :root[data-theme="dark"] .floating-theme-btn {
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    .floating-theme-btn:hover {
        transform: translateY(-5px) scale(1.05);
        color: var(--lp-accent, #10b981);
        border-color: var(--lp-text-muted, #ccc);
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800;900&family=Bebas+Neue&family=Oswald:wght@400;500;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<nav class="<?php echo htmlspecialchars((string)($navbarClass), ENT_QUOTES, 'UTF-8'); ?>" id="lpNavbar" <?php echo $alwaysSolidAttr; ?>>
    <div class="container">
        <div class="nav-inner">
            <a href="<?php echo $basePath; ?>/" class="brand">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo">
                <div class="brand-text">
                    <h1>Faculty of Engineering & Technology</h1>
                    <p>University of Sindh, Jamshoro</p>
                </div>
            </a>
            <div class="nav-actions">
                <?php if (strpos($currentUri, 'register') === false): ?>
                    <a href="<?php echo $basePath; ?>/register" class="btn-nav btn-nav-ghost d-none d-sm-inline-flex">Sign Up</a>
                <?php endif; ?>
                
                <?php if (strpos($currentUri, 'login') === false): ?>
                    <a href="<?php echo $basePath; ?>/login" class="btn-nav btn-nav-primary">
                        Sign In &rarr;
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Floating Theme Button -->
<?php if (!isset($hideThemeButton) || !$hideThemeButton): ?>
<button id="themeToggleBtn" class="floating-theme-btn" title="Toggle Light/Dark Theme">
    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
</button>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeIcon = document.getElementById('themeIcon');
    const htmlEl = document.documentElement;

    function updateIcon() {
        if (htmlEl.getAttribute('data-theme') === 'dark') {
            themeIcon.classList.remove('bi-moon-stars-fill');
            themeIcon.classList.add('bi-sun-fill');
        } else {
            themeIcon.classList.remove('bi-sun-fill');
            themeIcon.classList.add('bi-moon-stars-fill');
        }
    }

    if(themeBtn) {
        updateIcon();
        themeBtn.addEventListener('click', () => {
            let currentTheme = htmlEl.getAttribute('data-theme');
            let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlEl.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon();
        });
    }

    const navbar = document.getElementById('lpNavbar');
    let lastScrollY = window.scrollY;

    if (navbar) {
        const handleScroll = () => {
            const currentScrollY = window.scrollY;
            
            if (!navbar.hasAttribute('data-always-solid')) {
                navbar.classList.toggle('scrolled', currentScrollY > 40);
            }
            
            if (currentScrollY > 150 && currentScrollY > lastScrollY) {
                navbar.classList.add('nav-hidden');
            } else {
                navbar.classList.remove('nav-hidden');
            }
            lastScrollY = currentScrollY;
        };
        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Trigger on initial load
    }
});
</script>
