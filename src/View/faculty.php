<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath === '\\' || $basePath === '/') {
    $basePath = '';
}
$pageTitle = 'Faculty & Staff - FYP Management Portal';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Faculty & Staff - FYP Portal'); ?></title>
    
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
            background: radial-gradient(circle, var(--lp-accent) 0%, transparent 60%);
            opacity: 0.05; top: -20vw; right: -20vw; z-index: 0;
        }
        .hero-shape-2 {
            position: absolute; width: 40vw; height: 40vw; border-radius: 50%;
            background: radial-gradient(circle, var(--lp-violet) 0%, transparent 60%);
            opacity: 0.05; bottom: -10vw; left: -10vw; z-index: 0;
        }

        .about-content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; text-align: center; }
        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            margin-bottom: 20px;
            color: var(--lp-text);
            line-height: 1.1;
        }
        .hero-title .highlight { 
            background: linear-gradient(135deg, #a78bfa, var(--lp-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 0;
            text-shadow: none;
        }
        .hero-desc {
            font-size: 1.15rem; color: var(--lp-text-muted); line-height: 1.6; max-width: 700px; margin: 0 auto;
        }

        .section { padding: 100px 0; position: relative; z-index: 2; }
        
        .section-title {
            font-family: 'Inter', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 40px;
            color: var(--lp-text);
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--lp-border);
            letter-spacing: -0.5px;
        }
        .section-title i {
            color: var(--lp-accent);
        }
        
        .faculty-card {
            background: var(--lp-card);
            border: 1px solid var(--lp-border);
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }
        .faculty-card:hover {
            transform: translateY(-8px);
            border-color: var(--lp-text-muted);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .faculty-avatar {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: var(--lp-bg-alt);
            color: var(--lp-text);
            font-size: 1.2rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            border: 1px solid var(--lp-border);
        }
        .faculty-card h5 { font-weight: 700; margin: 0 0 6px; font-size: 1.1rem; color: var(--lp-text); }
        .faculty-designation {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
            background: var(--lp-bg-alt);
            color: var(--lp-text-muted);
            border: 1px solid var(--lp-border);
        }
        .faculty-dept { color: var(--lp-text-muted); font-size: 0.85rem; margin: 0; }
        .faculty-email {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--lp-text-muted); font-size: 0.8rem; font-weight: 500;
            text-decoration: none; transition: all 0.2s;
            margin-top: 15px;
        }
        .faculty-email:hover { color: var(--lp-text); }
        .role-description {
            background: var(--lp-card);
            border: 1px solid var(--lp-border);
            border-left: 4px solid var(--lp-accent);
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 40px;
            font-size: 0.95rem;
            color: var(--lp-text-muted);
            line-height: 1.6;
        }

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
                Faculty & <span class="highlight">Staff</span>
            </h1>
            <p class="hero-desc">
                Meet the dedicated team responsible for guiding, managing, and evaluating Final Year Projects across the Faculty of Engineering & Technology.
            </p>
        </div>
    </div>
</section>

<!-- FACULTY ROSTER -->
<section class="section">
    <div class="container">
        
        <!-- Heads of Department -->
        <?php if (!empty($hods)): ?>
        <h2 class="section-title"><i class="bi bi-bank"></i> Heads of Department</h2>
        <div class="role-description">
            HODs oversee the entire FYP process within their respective departments. They manage faculty allocations, ensure standards are maintained, and approve final project grades.
        </div>
        <div class="row justify-content-center g-4 mb-5">
            <?php foreach ($hods as $hod): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="faculty-card">
                        <div class="faculty-avatar">
                            <?php echo strtoupper(substr($hod['name'], 0, 1)); ?>
                        </div>
                        <h5><?php echo htmlspecialchars($hod['name']); ?></h5>
                        <span class="faculty-designation">Head of Department</span>
                        <p class="faculty-dept"><?php echo htmlspecialchars(ucfirst($hod['department']) ?? 'Department'); ?></p>
                        <a href="mailto:<?php echo htmlspecialchars($hod['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($hod['email']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Coordinators -->
        <?php if (!empty($coordinators)): ?>
        <h2 class="section-title"><i class="bi bi-diagram-3"></i> FYP Coordinators</h2>
        <div class="role-description">
            Coordinators are responsible for the day-to-day administration of the FYP portal. They verify student accounts, manage deadlines, organize defense schedules, and handle official notices.
        </div>
        <div class="row justify-content-center g-4 mb-5">
            <?php foreach ($coordinators as $coord): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="faculty-card">
                        <div class="faculty-avatar">
                            <?php echo strtoupper(substr($coord['name'], 0, 1)); ?>
                        </div>
                        <h5><?php echo htmlspecialchars($coord['name']); ?></h5>
                        <span class="faculty-designation">FYP Coordinator</span>
                        <a href="mailto:<?php echo htmlspecialchars($coord['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($coord['email']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Committee Members -->
        <?php if (!empty($committee)): ?>
        <h2 class="section-title"><i class="bi bi-clipboard-check"></i> Evaluation Committee</h2>
        <div class="role-description">
            Committee Members form the examination panels. They review project proposals, conduct mid-year and final defenses, and evaluate the overall quality and presentation of the projects.
        </div>
        <div class="row justify-content-center g-4 mb-5">
            <?php foreach ($committee as $member): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="faculty-card">
                        <div class="faculty-avatar">
                            <?php echo strtoupper(substr($member['name'], 0, 1)); ?>
                        </div>
                        <h5><?php echo htmlspecialchars($member['name']); ?></h5>
                        <span class="faculty-designation">Committee Member</span>
                        <p class="faculty-dept"><?php echo htmlspecialchars(ucfirst($member['department']) ?? 'Department'); ?></p>
                        <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($member['email']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Supervisors -->
        <?php if (!empty($supervisors)): ?>
        <h2 class="section-title"><i class="bi bi-person-workspace"></i> Project Supervisors</h2>
        <div class="role-description">
            Supervisors directly mentor student groups. They provide technical guidance, track bi-weekly progress, approve documentation, and help students overcome challenges throughout their project journey.
        </div>
        <div class="row justify-content-center g-4 mb-5">
            <?php foreach ($supervisors as $supervisor): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="faculty-card">
                        <div class="faculty-avatar">
                            <?php
                            $initials = '';
                            $nameParts = explode(' ', $supervisor['name']);
                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            echo htmlspecialchars($initials);
                            ?>
                        </div>
                        <h5><?php echo htmlspecialchars($supervisor['name']); ?></h5>
                        <?php
                        $desig = $supervisor['designation'] ?? 'Supervisor';
                        ?>
                        <span class="faculty-designation"><?php echo htmlspecialchars($desig); ?></span>
                        <p class="faculty-dept"><?php echo htmlspecialchars($supervisor['department']); ?></p>
                        <a href="mailto:<?php echo htmlspecialchars($supervisor['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($supervisor['email']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/layout/lp_footer.php'; ?>
