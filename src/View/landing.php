<?php include __DIR__ . '/layout/auth_header.php'; ?>

<style>
    /* Premium Landing Page Styles */
    :root {
        --landing-primary: #2563eb;
        --landing-secondary: #0d9488;
        --landing-dark: #0f172a;
        --landing-light: #f8fafc;
    }
    
    body {
        background-color: var(--landing-light);
        font-family: 'Inter', sans-serif;
    }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 58, 138, 0.9) 100%), 
                    url('<?php echo $basePath; ?>/images/hero-bg.jpg') center/cover no-repeat;
        color: white;
        padding: 100px 0 140px;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: var(--landing-light);
        clip-path: polygon(0 100%, 100% 100%, 100% 0);
    }
    
    .hero-logo-wrapper {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 20px;
        padding: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        margin-bottom: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-logo-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 20px;
        background: linear-gradient(to right, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        font-weight: 400;
        color: #cbd5e1;
        max-width: 600px;
        margin-bottom: 40px;
    }
    
    .btn-hero {
        padding: 14px 32px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .btn-hero-primary {
        background: linear-gradient(135deg, #3b82f6, #4f46e5);
        color: white;
        border: none;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    
    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.5);
        color: white;
    }
    
    .btn-hero-outline {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }
    
    .btn-hero-outline:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        transform: translateY(-2px);
    }

    /* Deadlines Bulletin Board - Prioritized */
    .bulletin-board {
        margin-top: -80px; /* Overlaps hero */
        position: relative;
        z-index: 10;
        margin-bottom: 80px;
    }
    
    .bulletin-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .bulletin-header {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        padding: 20px 30px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .bulletin-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }
    
    .bulletin-item {
        padding: 18px 30px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: background 0.2s;
    }
    
    .bulletin-item:hover {
        background: #f8fafc;
    }
    
    .bulletin-item:last-child {
        border-bottom: none;
    }
    
    .bulletin-date {
        background: rgba(245, 158, 11, 0.1);
        color: #ea580c;
        padding: 8px 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        min-width: 90px;
        text-align: center;
    }
    
    /* Process Section */
    .section-title {
        font-weight: 800;
        color: var(--landing-dark);
        margin-bottom: 15px;
        text-align: center;
    }
    
    .process-card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        height: 100%;
        transition: transform 0.3s;
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    .process-card:hover {
        transform: translateY(-5px);
    }
    
    .process-icon {
        width: 70px;
        height: 70px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
    }
    
    /* Supervisor Spotlight */
    .supervisor-card {
        background: white;
        border-radius: 16px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s;
        height: 100%;
    }
    
    .supervisor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    }
    
    .supervisor-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e2e8f0, #f8fafc);
        color: #64748b;
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 3px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    /* Footer */
    .landing-footer {
        background: var(--landing-dark);
        color: #94a3b8;
        padding: 60px 0 30px;
        margin-top: 80px;
    }
    
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-logo-wrapper">
                    <img src="<?php echo $basePath; ?>/images/logo.png" alt="University Logo">
                </div>
                <h1 class="hero-title">Empowering the Next Generation of Engineers.</h1>
                <p class="hero-subtitle">The official Final Year Project (FYP) management portal for the Faculty of Engineering & Technology, University of Sindh.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?php echo $basePath; ?>/login" class="btn btn-hero btn-hero-primary text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i> Login to Portal
                    </a>
                    <a href="<?php echo $basePath; ?>/register" class="btn btn-hero btn-hero-outline text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="bi bi-person-plus-fill"></i> Register as Student
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Prioritized Bulletin Board -->
<div class="container bulletin-board">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="bulletin-card">
                <div class="bulletin-header">
                    <i class="bi bi-megaphone-fill fs-4"></i>
                    <h5 class="m-0 fw-bold">Important Deadlines & Announcements</h5>
                </div>
                <ul class="bulletin-list">
                    <?php if (empty($deadlines)): ?>
                        <li class="bulletin-item justify-content-center text-muted py-4">
                            <i class="bi bi-info-circle me-2"></i> No upcoming deadlines at the moment.
                        </li>
                    <?php else: ?>
                        <?php foreach ($deadlines as $deadline): ?>
                            <li class="bulletin-item">
                                <div class="bulletin-date">
                                    <?php echo date('M d, Y', strtotime($deadline['date'])); ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($deadline['title']); ?></h6>
                                    <p class="mb-0 text-secondary small">
                                        <i class="bi bi-diagram-3-fill text-muted me-1"></i> <?php echo htmlspecialchars($deadline['department'] ?? 'All Departments'); ?> 
                                        &nbsp;&bull;&nbsp; 
                                        <i class="bi bi-clock-history text-muted me-1"></i> Due by <?php echo htmlspecialchars($deadline['time'] ?? '23:59'); ?>
                                    </p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- How it Works -->
<div class="container mb-5 pb-4">
    <h2 class="section-title">How the FYP Process Works</h2>
    <p class="text-center text-secondary mb-5">A streamlined journey from idea to final defense.</p>
    
    <div class="row g-4">
        <div class="col-md-3">
            <div class="process-card">
                <div class="process-icon"><i class="bi bi-people-fill"></i></div>
                <h5 class="fw-bold mb-2">1. Form a Group</h5>
                <p class="text-secondary small mb-0">Create your group and submit a solid project proposal to your department.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="process-card">
                <div class="process-icon"><i class="bi bi-person-badge-fill"></i></div>
                <h5 class="fw-bold mb-2">2. Assignment</h5>
                <p class="text-secondary small mb-0">Get assigned a dedicated faculty supervisor who matches your project domain.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="process-card">
                <div class="process-icon"><i class="bi bi-code-square"></i></div>
                <h5 class="fw-bold mb-2">3. Development</h5>
                <p class="text-secondary small mb-0">Build your project with regular bi-weekly assessments and feedback.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="process-card">
                <div class="process-icon"><i class="bi bi-award-fill"></i></div>
                <h5 class="fw-bold mb-2">4. Final Defense</h5>
                <p class="text-secondary small mb-0">Present your final work to the evaluation committee for final grading.</p>
            </div>
        </div>
    </div>
</div>

<!-- Meet the Supervisors -->
<div class="container mb-5 pb-5">
    <h2 class="section-title">Faculty Spotlight</h2>
    <p class="text-center text-secondary mb-5">Meet some of our esteemed supervisors guiding our engineering students.</p>
    
    <div class="row g-4 justify-content-center">
        <?php if (empty($supervisors)): ?>
            <div class="col-12 text-center text-muted">
                <p>Supervisor profiles will be updated shortly.</p>
            </div>
        <?php else: ?>
            <?php foreach ($supervisors as $supervisor): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="supervisor-card">
                        <div class="supervisor-avatar">
                            <?php 
                            $initials = '';
                            $nameParts = explode(' ', $supervisor['name']);
                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            echo htmlspecialchars($initials);
                            ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($supervisor['name']); ?></h5>
                        <p class="text-primary fw-semibold small mb-3"><?php echo htmlspecialchars($supervisor['department']); ?> Department</p>
                        
                        <a href="mailto:<?php echo htmlspecialchars($supervisor['email']); ?>" class="btn btn-sm btn-light w-100 rounded-pill border">
                            <i class="bi bi-envelope-fill text-muted me-2"></i> Contact via Email
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="landing-footer">
    <div class="container">
        <div class="row g-4 border-bottom border-secondary pb-4 mb-4">
            <div class="col-lg-5">
                <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" width="30" height="30" style="object-fit: contain;">
                    University of Sindh
                </h5>
                <p class="small pe-lg-4">The Faculty of Engineering & Technology aims to produce top-tier engineers equipped with practical skills and innovative mindsets through our rigorous Final Year Project curriculum.</p>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-white">University Main Site</a></li>
                    <li class="mb-2"><a href="<?php echo $basePath; ?>/login" class="text-decoration-none text-secondary hover-white">Student Login</a></li>
                    <li class="mb-2"><a href="<?php echo $basePath; ?>/register" class="text-decoration-none text-secondary hover-white">New Registration</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-white">Library Resources</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white fw-bold mb-3">Contact Us</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-geo-alt-fill text-primary"></i> Allama I.I. Kazi Campus, Jamshoro</li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-envelope-fill text-primary"></i> fyp.support@usindh.edu.pk</li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-telephone-fill text-primary"></i> +92 (22) 9213181-90</li>
                </ul>
            </div>
        </div>
        <div class="text-center small">
            &copy; <?php echo date('Y'); ?> Faculty of Engineering & Technology, University of Sindh. All rights reserved.
        </div>
    </div>
</footer>

<?php include __DIR__ . '/layout/footer.php'; ?>
