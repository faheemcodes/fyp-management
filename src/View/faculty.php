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

        .faculty-group {
            margin-bottom: 60px;
            padding-bottom: 60px;
            border-bottom: 1px solid var(--lp-border);
        }
        .faculty-group:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .section-title {
            font-family: 'Inter', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0 auto 40px auto;
            color: var(--lp-text);
            display: flex;
            width: fit-content;
            align-items: center;
            gap: 12px;
            padding: 6px 20px 6px 6px;
            background: var(--lp-card);
            border-radius: 50px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.04);
            letter-spacing: -0.2px;
            border: 1px solid var(--lp-border);
        }
        .section-title i {
            color: var(--lp-bg);
            background: var(--lp-text);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        
        /* PAPER LAYERS CARD - THEMED */
        .stage-paper{
            width:100%;
            aspect-ratio:16/11;
            position:relative;
            display:flex;
            align-items:center;
            justify-content:center;
            perspective: 1200px;
            transform-style: preserve-3d;
        }
        .stage-paper:hover {
            z-index: 10;
        }
        .stage-paper:hover .paper-back {
            transform: rotate(-6deg) translateZ(-40px) translateY(10px) translateX(-10px);
            opacity: 0.15;
        }
        .stage-paper:hover .paper-mid {
            transform: rotate(3deg) translateZ(-20px) translateY(5px) translateX(5px);
            opacity: 0.25;
        }
        .stage-paper:hover .card-paper {
            transform: translateZ(40px) rotateX(10deg) rotateY(-10deg);
            box-shadow: -20px 30px 50px rgba(0,0,0,0.15);
            border-color: var(--lp-text-muted);
        }
        .paper-back, .paper-mid, .card-paper {
            transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            backface-visibility: hidden;
            will-change: transform;
        }
        .paper-back{
            position:absolute;
            width:82%;
            height:78%;
            background: var(--lp-accent);
            opacity: 0.35;
            border-radius:20px;
            transform:rotate(-6deg) translateZ(0);
            top:8%;
            z-index: 1;
        }
        .paper-mid{
            position:absolute;
            width:84%;
            height:80%;
            background: var(--lp-amber);
            opacity: 0.35;
            border-radius:20px;
            transform:rotate(3deg) translateZ(0);
            z-index: 2;
        }
        .card-paper{
            position:relative;
            width:86%;
            height:82%;
            background: var(--lp-card);
            border-radius:18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid var(--lp-border);
            padding:22px 24px;
            color: var(--lp-text);
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            text-align:left;
            transform: translateZ(0) rotateX(0) rotateY(0);
            z-index: 3;
        }
        .paper-name{
            font-size:19px;
            font-weight:700;
            color: var(--lp-text);
        }
        .paper-role{
            font-size:12px;
            color: var(--lp-accent);
            font-weight:600;
            margin-top:2px;
        }
        .paper-list{
            margin-top:14px;
            display:flex;
            flex-direction:column;
            gap:7px;
        }
        .paper-item{
            font-size:12px;
            color: var(--lp-text-muted);
            font-weight:600;
        }
        .paper-item span{
            color: var(--lp-accent);
            font-weight:700;
        }
        .paper-item a {
            color: inherit;
            text-decoration: none;
        }
        .paper-item a:hover {
            color: var(--lp-accent);
            text-decoration: underline;
        }
        .role-description {
            text-align: center;
            color: var(--lp-text-muted);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto 50px auto;
            line-height: 1.6;
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
        <div class="faculty-group">
        <h2 class="section-title"><i class="bi bi-bank"></i> Heads of Department</h2>
        <div class="role-description">
            HODs oversee the entire FYP process within their respective departments. They manage faculty allocations, ensure standards are maintained, and approve final project grades.
        </div>
        <div class="row justify-content-center g-4">
            <?php foreach ($hods as $hod): ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="stage-paper">
                        <div class="paper-back"></div>
                        <div class="paper-mid"></div>
                        <div class="card-paper">
                            <div>
                                <div class="paper-name"><?php echo htmlspecialchars(formatPersonName($hod['prefix'] ?? '', $hod['name'], $hod['surname'] ?? '')); ?></div>
                                <div class="paper-role">Head of Department</div>
                            </div>
                            <div class="paper-list">
                                <div class="paper-item"><span>Dept &mdash; </span><?php echo htmlspecialchars(ucfirst($hod['department']) ?? 'Department'); ?></div>
                                <div class="paper-item"><span>Mail &mdash; </span><a href="mailto:<?php echo htmlspecialchars($hod['email']); ?>"><?php echo htmlspecialchars($hod['email']); ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
        <?php endif; ?>

        <!-- Coordinators -->
        <?php if (!empty($coordinators)): ?>
        <div class="faculty-group">
        <h2 class="section-title"><i class="bi bi-diagram-3"></i> FYP Coordinators</h2>
        <div class="role-description">
            Coordinators are responsible for the day-to-day administration of the FYP portal. They verify student accounts, manage deadlines, organize defense schedules, and handle official notices.
        </div>
        <div class="row justify-content-center g-4">
            <?php foreach ($coordinators as $coord): ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="stage-paper">
                        <div class="paper-back"></div>
                        <div class="paper-mid"></div>
                        <div class="card-paper">
                            <div>
                                <div class="paper-name"><?php echo htmlspecialchars(formatPersonName($coord['prefix'] ?? '', $coord['name'], $coord['surname'] ?? '')); ?></div>
                                <div class="paper-role">FYP Coordinator</div>
                            </div>
                            <div class="paper-list">
                                <div class="paper-item"><span>Dept &mdash; </span><?php echo htmlspecialchars(ucfirst($coord['department']) ?? 'Coordinator'); ?></div>
                                <div class="paper-item"><span>Mail &mdash; </span><a href="mailto:<?php echo htmlspecialchars($coord['email']); ?>"><?php echo htmlspecialchars($coord['email']); ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Committee Members -->
<?php if (!empty($committee)): ?>
<section class="section" style="background-color: var(--lp-bg-alt); border-top: 1px solid var(--lp-border); border-bottom: 1px solid var(--lp-border);">
    <div class="container">
        <div class="faculty-group" style="margin-bottom: 0;">
        <h2 class="section-title"><i class="bi bi-clipboard-check"></i> Evaluation Committee</h2>
        <div class="role-description">
            Committee Members form the examination panels. They review project proposals, conduct mid-year and final defenses, and evaluate the overall quality and presentation of the projects.
        </div>
        <div class="row justify-content-center g-4">
            <?php foreach ($committee as $member): ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="stage-paper">
                        <div class="paper-back"></div>
                        <div class="paper-mid"></div>
                        <div class="card-paper">
                            <div>
                                <div class="paper-name"><?php echo htmlspecialchars(formatPersonName($member['prefix'] ?? '', $member['name'], $member['surname'] ?? '')); ?></div>
                                <div class="paper-role">Committee Member</div>
                            </div>
                            <div class="paper-list">
                                <div class="paper-item"><span>Dept &mdash; </span><?php echo htmlspecialchars(ucfirst($member['department']) ?? 'Department'); ?></div>
                                <div class="paper-item"><span>Mail &mdash; </span><a href="mailto:<?php echo htmlspecialchars($member['email']); ?>"><?php echo htmlspecialchars($member['email']); ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Supervisors -->
<?php if (!empty($supervisors)): ?>
<section class="section">
    <div class="container">
        <div class="faculty-group">
        <h2 class="section-title"><i class="bi bi-person-workspace"></i> Project Supervisors</h2>
        <div class="role-description">
            Supervisors directly mentor student groups. They provide technical guidance, track bi-weekly progress, approve documentation, and help students overcome challenges throughout their project journey.
        </div>
        <div class="row justify-content-center g-4">
            <?php foreach ($supervisors as $supervisor): ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="stage-paper">
                        <div class="paper-back"></div>
                        <div class="paper-mid"></div>
                        <div class="card-paper">
                            <div>
                                <div class="paper-name"><?php echo htmlspecialchars(formatPersonName($supervisor['prefix'] ?? '', $supervisor['name'], $supervisor['surname'] ?? '')); ?></div>
                                <div class="paper-role"><?php echo htmlspecialchars($supervisor['designation'] ?? 'Supervisor'); ?></div>
                            </div>
                            <div class="paper-list">
                                <div class="paper-item"><span>Dept &mdash; </span><?php echo htmlspecialchars($supervisor['department']); ?></div>
                                <div class="paper-item"><span>Mail &mdash; </span><a href="mailto:<?php echo htmlspecialchars($supervisor['email']); ?>"><?php echo htmlspecialchars($supervisor['email']); ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/layout/lp_footer.php'; ?>
