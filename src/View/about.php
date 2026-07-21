<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath === '\\' || $basePath === '/') {
    $basePath = '';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'About - FYP Portal'); ?></title>
    
    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    
    <!-- Preconnections for Performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* BASE THEME & VARIABLES */
        :root {
            --font-heading-main: 'Chuner', 'Bebas Neue', 'Impact', sans-serif;
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
            
            --lp-accent: #10b981; /* Emerald */
            --lp-accent-hover: #059669;
            --lp-violet: #8b5cf6;
            --lp-rose: #f43f5e;
            --lp-amber: #f59e0b;
            --lp-teal: #14b8a6;
            
            --lp-nav-bg: rgba(255,255,255,0.9);
        }

        :root[data-theme="dark"] {
            --lp-bg: #121212;
            --lp-bg-alt: #18181b;
            --lp-card: #27272a;
            --lp-card-hover: #3f3f46;
            --lp-border: rgba(255,255,255,0.08);
            --lp-text: #f8fafc;
            --lp-text-muted: #a1a1aa;
            
            --lp-accent: #10b981; /* Emerald */
            --lp-accent-hover: #34d399;
            --lp-violet: #a78bfa;
            --lp-rose: #fb7185;
            --lp-amber: #fbbf24;
            --lp-teal: #2dd4bf;
            
            --lp-nav-bg: rgba(18,18,18,0.9);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--lp-bg);
            color: var(--lp-text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .heading-main { font-weight: 900; letter-spacing: -0.03em; }
        .heading-alt { font-weight: 700; letter-spacing: -0.01em; }

        /* NAVBAR */
        .lp-navbar {
            padding: 20px 0;
            position: fixed;
            top: 0; width: 100%;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--lp-border);
            transition: all 0.3s ease;
        }
        .navbar-brand { font-size: 1.2rem; font-weight: 800; color: var(--lp-text) !important; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { width: 32px; height: 32px; }
        .nav-link { color: var(--lp-text-muted) !important; font-weight: 500; font-size: 0.95rem; margin: 0 10px; transition: color 0.2s; }
        .nav-link:hover, .nav-link.active { color: var(--lp-text) !important; }

        /* HERO SECTION */
        .lp-hero {
            position: relative;
            background: var(--lp-bg);
            padding: 160px 0 80px;
            overflow: hidden;
            min-height: 50vh;
            display: flex;
            align-items: center;
        }

        /* Distributed Line Grid Patches */
        .lp-hero::before {
            content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 0;
            background-image: 
                linear-gradient(to right, var(--lp-border) 1px, transparent 1px),
                linear-gradient(to bottom, var(--lp-border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.7;
            -webkit-mask-image: 
                radial-gradient(circle at 12% 25%, black 2%, transparent 25%),
                radial-gradient(circle at 88% 75%, black 5%, transparent 30%),
                radial-gradient(circle at 45% 90%, black 1%, transparent 20%);
            mask-image: 
                radial-gradient(circle at 12% 25%, black 2%, transparent 25%),
                radial-gradient(circle at 88% 75%, black 5%, transparent 30%),
                radial-gradient(circle at 45% 90%, black 1%, transparent 20%);
        }
        /* Distributed Dotted Grid Patches */
        .lp-hero::after {
            content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 0;
            background-image: radial-gradient(var(--lp-text-muted) 1.5px, transparent 1.5px);
            background-size: 20px 20px;
            opacity: 0.45;
            -webkit-mask-image: 
                radial-gradient(circle at 82% 18%, black 4%, transparent 28%),
                radial-gradient(circle at 18% 80%, black 2%, transparent 25%),
                radial-gradient(circle at 65% 35%, black 1%, transparent 18%);
            mask-image: 
                radial-gradient(circle at 82% 18%, black 4%, transparent 28%),
                radial-gradient(circle at 18% 80%, black 2%, transparent 25%),
                radial-gradient(circle at 65% 35%, black 1%, transparent 18%);
        }

        .hero-shape {
            position: absolute; width: 60vw; height: 60vw; border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, rgba(15,23,42,0) 70%);
            top: -20vw; right: -20vw; pointer-events: none; z-index: 0;
        }
        .hero-shape-2 {
            position: absolute; width: 40vw; height: 40vw; border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.06) 0%, rgba(15,23,42,0) 70%);
            bottom: -10vw; left: -10vw; pointer-events: none; z-index: 0;
        }

        .about-content { position: relative; z-index: 5; max-width: 800px; margin: 0 auto; text-align: center; }
        
        .hero-title { font-size: clamp(2.5rem, 6vw, 4rem); line-height: 1.1; margin-bottom: 24px; color: var(--lp-text); }
        .highlight {
            background: linear-gradient(135deg, var(--lp-accent), var(--lp-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc { font-size: 1.15rem; color: var(--lp-text-muted); margin: 0 auto 40px; line-height: 1.6; }

        /* SECTIONS */
        .section { padding: 100px 0; position: relative; z-index: 5; }
        .section-alt { background: var(--lp-bg-alt); }
        
        .card-modern {
            background: var(--lp-card);
            border: 1px solid var(--lp-border);
            border-radius: 20px;
            padding: 40px;
            height: 100%;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .card-modern:hover { transform: translateY(-5px); border-color: var(--lp-text-muted); }
        
        .icon-box {
            width: 60px; height: 60px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin-bottom: 24px;
        }
        .icon-box.emerald { background: rgba(16,185,129,0.1); color: var(--lp-accent); }
        .icon-box.violet { background: rgba(139,92,246,0.1); color: var(--lp-violet); }
        .icon-box.amber { background: rgba(245,158,11,0.1); color: var(--lp-amber); }

        .card-modern h3 { font-size: 1.5rem; font-weight: 800; color: var(--lp-text); margin-bottom: 15px; }
        .card-modern p { color: var(--lp-text-muted); line-height: 1.7; margin-bottom: 0; }

        /* FOOTER */
        .footer { background: var(--lp-bg-alt); padding: 60px 0 30px; border-top: 1px solid var(--lp-border); position: relative; z-index: 5; }
        .footer h6 { font-size: 1rem; font-weight: 700; color: var(--lp-text); margin-bottom: 20px; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: var(--lp-text-muted); text-decoration: none; transition: color 0.2s; font-size: 0.9rem; }
        .footer-links a:hover { color: var(--lp-text); }
        .footer-bottom { border-top: 1px solid var(--lp-border); margin-top: 40px; padding-top: 20px; text-align: center; color: var(--lp-text-muted); font-size: 0.8rem; }

        @media (max-width: 768px) {
            .hero-desc { font-size: 1rem; }
            .section { padding: 60px 0; }
        }
    </style>
</head>
<body>

<!-- NAVBAR & THEME BUTTON -->
<?php include __DIR__ . '/layout/lp_navbar.php'; ?>

<!-- HERO -->
<section class="lp-hero">
    <div class="hero-shape"></div>
    <div class="hero-shape-2"></div>
    <div class="container">
        <div class="about-content">
            <h1 class="hero-title heading-main">
                Empowering <span class="highlight">Innovation</span> <br>Through Technology
            </h1>
            <p class="hero-desc">
                The Final Year Project Management Portal is the official digital infrastructure for the Faculty of Engineering & Technology, designed to streamline collaboration, track academic milestones, and foster groundbreaking research.
            </p>
        </div>
    </div>
</section>

<!-- CORE PILLARS -->
<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-modern">
                    <div class="icon-box emerald"><i class="bi bi-bullseye"></i></div>
                    <h3>Our Goal</h3>
                    <p>To provide a centralized, transparent, and highly efficient platform that eliminates administrative bottlenecks, allowing students and faculty to focus entirely on research and development.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern">
                    <div class="icon-box violet"><i class="bi bi-people-fill"></i></div>
                    <h3>Collaboration</h3>
                    <p>Bridging the gap between students, supervisors, and evaluation committees. We ensure seamless communication, immediate feedback loops, and transparent grading across all departments.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-modern">
                    <div class="icon-box amber"><i class="bi bi-rocket-takeoff-fill"></i></div>
                    <h3>Excellence</h3>
                    <p>By digitizing the entire FYP lifecycle—from initial proposal submission to final defense tracking—we uphold the highest academic standards and prepare students for the modern tech industry.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/layout/lp_footer.php'; ?>
