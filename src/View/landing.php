<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'FYP Management System'; ?></title>
    <meta name="description" content="Official Final Year Project management portal for the Faculty of Engineering & Technology, University of Sindh, Jamshoro.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') { $basePath = ''; }
    ?>
    <link rel="icon" href="<?php echo $basePath; ?>/images/logo.png" type="image/png">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        
        :root {
            --lp-dark: #0f172a;
            --lp-darker: #020617;
            --lp-card: rgba(255,255,255,0.03);
            --lp-card-solid: #1e293b;
            --lp-border: rgba(255,255,255,0.06);
            --lp-accent: #10b981;
            --lp-blue: #3b82f6;
            --lp-purple: #8b5cf6;
            --lp-cyan: #06b6d4;
            --lp-amber: #f59e0b;
            --lp-rose: #f43f5e;
            --lp-text: #e2e8f0;
            --lp-muted: #94a3b8;
            --lp-white-section: #f8fafc;
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--lp-text);
            overflow-x: hidden;
        }

        /* ─── Sticky Navbar ─── */
        .lp-navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 24px 0;
            transition: all 0.3s ease;
            background: transparent;
        }
        .lp-navbar.scrolled {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--lp-border);
            padding: 16px 0;
        }
        .lp-navbar .nav-inner { display: flex; align-items: center; justify-content: space-between; }
        .lp-navbar .brand { display: flex; align-items: center; gap: 14px; text-decoration: none; color: white; }
        .lp-navbar .brand img { width: 50px; height: 50px; object-fit: contain; }
        .lp-navbar .brand-text h1 { font-size: 1.1rem; font-weight: 700; margin: 0; color: white; }
        .lp-navbar .brand-text p { font-size: 0.75rem; margin: 0; color: var(--lp-muted); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn-nav { padding: 9px 20px; border-radius: 10px; font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.25s ease; display: inline-flex; align-items: center; gap: 6px; }
        .btn-nav-ghost { color: var(--lp-muted); background: transparent; border: none; }
        .btn-nav-ghost:hover { color: white; }
        .btn-nav-primary { background: var(--lp-accent); color: white; border: none; }
        .btn-nav-primary:hover { background: #059669; transform: translateY(-1px); color: white; }

        /* ─── Hero ─── */
        .lp-hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            position: relative; padding: 140px 0 80px;
            background: url('https://fet.usindh.edu.pk/assets/images/slides/1608745872_813.jpg') center/cover no-repeat;
            overflow: hidden;
        }
        .lp-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(2, 6, 23, 0.6) 0%, rgba(15, 23, 42, 0.95) 100%);
        }
        .hero-content { position: relative; z-index: 2; text-align: center; }
        .hero-title { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 900; line-height: 1.15; letter-spacing: -0.02em; margin-bottom: 16px; color: white; text-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .hero-desc { font-size: 1.3rem; color: #e2e8f0; max-width: 700px; margin: 0 auto 40px; text-shadow: 0 2px 10px rgba(0,0,0,0.5); font-weight: 500; }
        .hero-desc span { display: block; margin-top: 12px; font-size: 1rem; color: var(--lp-accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; justify-content: center; }
        .btn-hero { padding: 14px 32px; border-radius: 12px; font-size: 1rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-hero-fill { background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; box-shadow: 0 8px 25px rgba(16,185,129,0.3); }
        .btn-hero-fill:hover { transform: translateY(-3px); box-shadow: 0 14px 35px rgba(16,185,129,0.4); color: white; }
        .btn-hero-outline { background: rgba(255,255,255,0.08); color: white; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.15); color: white; transform: translateY(-3px); }
        .hero-stats { display: flex; justify-content: center; gap: 40px; margin-top: 60px; padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); flex-wrap: wrap; }
        .hero-stat h3 { font-size: 2.2rem; font-weight: 800; margin: 0; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        .hero-stat p { font-size: 0.85rem; color: #cbd5e1; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }

        /* ─── Light Sections ─── */
        .section-light { background: var(--lp-white-section); color: #0f172a; padding: 80px 0; }
        .section-dark { background: var(--lp-dark); padding: 80px 0; }
        .section-darker { background: var(--lp-darker); padding: 80px 0; }
        .section-label { display: inline-flex; align-items: center; gap: 8px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 12px; }
        .section-label.green { color: var(--lp-accent); }
        .section-label.amber { color: var(--lp-amber); }
        .section-label.blue { color: var(--lp-blue); }
        .section-label.purple { color: var(--lp-purple); }
        .section-label.cyan { color: var(--lp-cyan); }
        .section-label.rose { color: var(--lp-rose); }
        .section-heading { font-size: 2rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 10px; }
        .section-sub { font-size: 1rem; max-width: 520px; margin-bottom: 40px; }
        .section-light .section-heading { color: #0f172a; }
        .section-light .section-sub { color: #64748b; }
        .section-dark .section-heading, .section-darker .section-heading { color: white; }
        .section-dark .section-sub, .section-darker .section-sub { color: var(--lp-muted); }

        /* ─── Deadlines ─── */
        .deadline-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
        .deadline-item { padding: 18px 24px; display: flex; align-items: center; gap: 16px; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
        .deadline-item:last-child { border-bottom: none; }
        .deadline-item:hover { background: #f8fafc; }
        .deadline-date-badge { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15); color: #d97706; padding: 8px 14px; border-radius: 10px; font-weight: 700; font-size: 0.82rem; min-width: 90px; text-align: center; white-space: nowrap; }
        .deadline-info h6 { font-weight: 700; margin: 0 0 3px; color: #0f172a; font-size: 0.95rem; }
        .deadline-info p { margin: 0; font-size: 0.8rem; color: #64748b; }
        .deadline-empty { padding: 40px; text-align: center; color: #94a3b8; }

        /* ─── Notice Board ─── */
        .notice-card { background: var(--lp-card-solid); border: 1px solid var(--lp-border); border-radius: 16px; padding: 24px; transition: all 0.3s; height: 100%; }
        .notice-card:hover { transform: translateY(-4px); border-color: rgba(59,130,246,0.2); box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
        .notice-badge { display: inline-block; padding: 3px 10px; border-radius: 6px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .notice-badge.students { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .notice-badge.supervisors { background: rgba(139,92,246,0.15); color: #a78bfa; }
        .notice-badge.all { background: rgba(16,185,129,0.15); color: #34d399; }
        .notice-card h6 { color: white; font-weight: 700; font-size: 0.95rem; margin: 12px 0 8px; line-height: 1.4; }
        .notice-card .notice-meta { color: var(--lp-muted); font-size: 0.78rem; }
        .notice-card .notice-body { color: #cbd5e1; font-size: 0.85rem; line-height: 1.6; margin-top: 10px;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        /* ─── About FET ─── */
        .about-text { color: #334155; font-size: 0.95rem; line-height: 1.8; }
        .about-text p { margin-bottom: 16px; }
        .about-img-wrapper { position: relative; }
        .about-img-wrapper img { width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

        /* ─── Departments ─── */
        .dept-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: center;
        }
        .dept-grid .dept-card-wrap {
            flex: 0 0 calc(33.333% - 16px);
            max-width: calc(33.333% - 16px);
        }
        @media (max-width: 991.98px) { .dept-grid .dept-card-wrap { flex: 0 0 calc(50% - 12px); max-width: calc(50% - 12px); } }
        @media (max-width: 575.98px) { .dept-grid .dept-card-wrap { flex: 0 0 100%; max-width: 100%; } }

        .dept-card {
            background: var(--lp-card-solid);
            border: 1px solid var(--lp-border);
            border-radius: 20px;
            padding: 32px 28px;
            height: 100%;
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }
        .dept-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--lp-accent), var(--lp-cyan));
            opacity: 0;
            transition: opacity 0.3s;
        }
        .dept-card:hover {
            transform: translateY(-8px);
            border-color: rgba(16,185,129,0.25);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .dept-card:hover::before { opacity: 1; }
        .dept-card .dept-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .dept-card .dept-icon.green { background: rgba(16,185,129,0.12); color: #34d399; }
        .dept-card .dept-icon.blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
        .dept-card .dept-icon.cyan { background: rgba(6,182,212,0.12); color: #22d3ee; }
        .dept-card .dept-icon.purple { background: rgba(139,92,246,0.12); color: #a78bfa; }
        .dept-card .dept-icon.amber { background: rgba(245,158,11,0.12); color: #fbbf24; }
        .dept-card .dept-name { color: white; font-weight: 700; font-size: 1.05rem; margin-bottom: 10px; line-height: 1.3; }
        .dept-card .dept-desc { color: var(--lp-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 18px; }
        .dept-card .dept-link { display: inline-flex; align-items: center; gap: 6px; color: var(--lp-accent); font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .dept-card .dept-link:hover { gap: 10px; color: #34d399; }

        /* ─── Process ─── */
        .process-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 18px;
            padding: 32px 24px; text-align: center; height: 100%;
            transition: all 0.35s ease; position: relative; overflow: hidden;
        }
        .process-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; opacity: 0; transition: opacity 0.3s; }
        .process-card:hover { transform: translateY(-6px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
        .process-card:hover::before { opacity: 1; }
        .process-card.c1::before { background: linear-gradient(90deg, var(--lp-accent), var(--lp-cyan)); }
        .process-card.c2::before { background: linear-gradient(90deg, var(--lp-blue), var(--lp-purple)); }
        .process-card.c3::before { background: linear-gradient(90deg, var(--lp-cyan), var(--lp-blue)); }
        .process-card.c4::before { background: linear-gradient(90deg, var(--lp-amber), var(--lp-rose)); }
        .process-step { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 14px; }
        .process-icon-wrap { width: 60px; height: 60px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 18px; }
        .process-icon-wrap.green { background: rgba(16,185,129,0.1); color: #10b981; }
        .process-icon-wrap.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .process-icon-wrap.cyan { background: rgba(6,182,212,0.1); color: #06b6d4; }
        .process-icon-wrap.amber { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .process-card h5 { font-weight: 700; margin-bottom: 8px; font-size: 1rem; color: #0f172a; }
        .process-card p { color: #64748b; font-size: 0.85rem; margin: 0; line-height: 1.6; }

        /* ─── Faculty / Supervisors ─── */
        .faculty-card {
            background: var(--lp-card-solid);
            border: 1px solid var(--lp-border);
            border-radius: 16px;
            padding: 24px 18px 20px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }
        .faculty-card:hover {
            transform: translateY(-5px);
            border-color: rgba(139,92,246,0.2);
            box-shadow: 0 12px 28px rgba(0,0,0,0.25);
        }
        .faculty-avatar {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(59,130,246,0.15));
            color: #a78bfa;
            font-size: 1rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            border: 2px solid var(--lp-border);
        }
        .faculty-card h5 { font-weight: 700; margin: 0 0 6px; font-size: 0.88rem; color: white; }
        .faculty-designation {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }
        .faculty-designation.professor { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .faculty-designation.associate { background: rgba(139,92,246,0.15); color: #a78bfa; }
        .faculty-designation.assistant { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .faculty-designation.lecturer { background: rgba(16,185,129,0.15); color: #34d399; }
        .faculty-designation.other { background: rgba(6,182,212,0.15); color: #22d3ee; }
        .faculty-dept { color: var(--lp-muted); font-size: 0.75rem; margin: 0; }
        .btn-view-all { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; text-decoration: none; background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2); color: #a78bfa; transition: all 0.3s; }
        .btn-view-all:hover { background: rgba(139,92,246,0.2); color: #a78bfa; transform: translateY(-2px); }

        /* ─── CTA ─── */
        .cta-inner {
            background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(59,130,246,0.06));
            border: 1px solid rgba(16,185,129,0.12); border-radius: 24px;
            padding: 60px; text-align: center; position: relative; overflow: hidden;
        }
        .cta-inner h2 { font-size: 2rem; font-weight: 800; margin-bottom: 12px; color: white; position: relative; }
        .cta-inner p { color: var(--lp-muted); max-width: 480px; margin: 0 auto 28px; font-size: 1rem; position: relative; }

        /* ─── Footer ─── */
        .lp-footer { background: var(--lp-darker); border-top: 1px solid var(--lp-border); padding: 50px 0 24px; }
        .lp-footer h6 { font-weight: 700; color: white; margin-bottom: 14px; font-size: 0.88rem; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 8px; color: var(--lp-muted); font-size: 0.84rem; }
        .footer-links a { color: var(--lp-muted); text-decoration: none; font-size: 0.84rem; transition: color 0.2s; }
        .footer-links a:hover { color: white; }
        .footer-bottom { border-top: 1px solid var(--lp-border); padding-top: 20px; margin-top: 30px; text-align: center; font-size: 0.76rem; color: #475569; }

        /* ─── Responsive ─── */
        @media (max-width: 991.98px) { .hero-visual { display: none; } }
        @media (max-width: 575.98px) {
            .lp-hero { padding: 100px 0 50px; min-height: auto; }
            .hero-title { font-size: 1.8rem; }
            .hero-desc { font-size: 1rem; margin-bottom: 30px; }
            .hero-btns { flex-direction: column; }
            .btn-hero { justify-content: center; width: 100%; }
            .hero-stats { gap: 20px; flex-direction: column; padding-top: 30px; margin-top: 40px; }
            .hero-stat h3 { font-size: 1.8rem; }
            .section-heading { font-size: 1.5rem; }
            .cta-inner { padding: 36px 20px; }
            .nav-actions .btn-nav-ghost { display: none; }
        }
    </style>
</head>
<body>

<!-- ─── Navbar ─── -->
<nav class="lp-navbar" id="lpNavbar">
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
                <a href="<?php echo $basePath; ?>/register" class="btn-nav btn-nav-ghost">Register</a>
                <a href="<?php echo $basePath; ?>/login" class="btn-nav btn-nav-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ─── Hero ─── -->
<section class="lp-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                Faculty of Engineering and Technology
            </h1>
            <p class="hero-desc">
                University of Sindh, Jamshoro
                <span>FYP Management Portal - Batch <?php echo date('Y'); ?></span>
            </p>
            <div class="hero-btns">
                <a href="<?php echo $basePath; ?>/login" class="btn-hero btn-hero-fill">
                    <i class="bi bi-box-arrow-in-right"></i> Login to Portal
                </a>
                <a href="<?php echo $basePath; ?>/register" class="btn-hero btn-hero-outline">
                    <i class="bi bi-person-plus-fill"></i> Student Registration
                </a>
            </div>
            
            <div class="hero-stats">
                <div class="hero-stat">
                    <h3><?php echo $stats['departments'] ?? 5; ?></h3>
                    <p>Departments</p>
                </div>
                <div class="hero-stat">
                    <h3><?php echo $stats['supervisors'] ?? '10'; ?>+</h3>
                    <p>Faculty Members</p>
                </div>
                <div class="hero-stat">
                    <h3><?php echo $stats['projects'] ?? '50'; ?>+</h3>
                    <p>Active Projects</p>
                </div>
                <div class="hero-stat">
                    <h3><?php echo $stats['students'] ?? '200'; ?>+</h3>
                    <p>Registered Students</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── Deadlines (Light Section — Prioritized) ─── -->
<section class="section-light">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-5">
                <div class="section-label amber"><i class="bi bi-megaphone-fill"></i> ANNOUNCEMENTS</div>
                <h2 class="section-heading">Important Deadlines</h2>
                <p class="section-sub">Stay updated with all upcoming submission dates, evaluations, and important notices for your batch.</p>
            </div>
            <div class="col-lg-7">
                <div class="deadline-card">
                    <?php if (empty($deadlines)): ?>
                        <div class="deadline-empty">
                            <i class="bi bi-calendar-check fs-2 d-block mb-3" style="opacity:0.3"></i>
                            No upcoming deadlines at the moment. Check back later!
                        </div>
                    <?php else: ?>
                        <?php foreach ($deadlines as $deadline): ?>
                            <div class="deadline-item">
                                <div class="deadline-date-badge">
                                    <?php echo date('M d', strtotime($deadline['deadline_date'])); ?>
                                </div>
                                <div class="deadline-info">
                                    <h6><?php echo htmlspecialchars($deadline['stage']); ?></h6>
                                    <p><i class="bi bi-diagram-3-fill me-1"></i> <?php echo htmlspecialchars($deadline['department'] ?? 'All Departments'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── Public Notice Board (Dark Section) ─── -->
<section class="section-dark">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label blue"><i class="bi bi-clipboard2-pulse-fill"></i> NOTICE BOARD</div>
            <h2 class="section-heading">Latest Notices</h2>
            <p class="section-sub mx-auto">Official announcements and circulars from the FYP coordination office.</p>
        </div>
        <?php if (empty($notices)): ?>
            <div class="text-center py-4" style="color: var(--lp-muted);">
                <i class="bi bi-inbox fs-1 d-block mb-3" style="opacity:0.3"></i>
                <p>No notices have been published yet.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($notices as $notice): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="notice-card">
                            <?php
                            $audience = strtolower($notice['target_audience'] ?? 'all');
                            $badgeClass = 'all';
                            if (strpos($audience, 'student') !== false) $badgeClass = 'students';
                            elseif (strpos($audience, 'supervisor') !== false) $badgeClass = 'supervisors';
                            ?>
                            <span class="notice-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($notice['target_audience'] ?? 'All'); ?></span>
                            <h6><?php echo htmlspecialchars($notice['subject']); ?></h6>
                            <div class="notice-meta">
                                <i class="bi bi-calendar3 me-1"></i> <?php echo date('M d, Y', strtotime($notice['notice_date'])); ?>
                                <?php if (!empty($notice['ref_no'])): ?>
                                    &nbsp;&bull;&nbsp; Ref: <?php echo htmlspecialchars($notice['ref_no']); ?>
                                <?php endif; ?>
                            </div>
                            <div class="notice-body"><?php echo htmlspecialchars(strip_tags($notice['body'])); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ─── About FET (Light Section) ─── -->
<section class="section-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="section-label green"><i class="bi bi-building"></i> ABOUT THE FACULTY</div>
                <h2 class="section-heading">Faculty of Engineering & Technology</h2>
                <div class="about-text">
                    <p>Faculty of Engineering and Technology is composed of an Institute and five departments: Department of Information Technology, Department of Software Engineering, Department of Telecommunication Engineering, Department of Electronic Engineering, and Department of Data Science.</p>
                    <p>FET has an excellent infrastructure with highly qualified faculty members (around 20 PhD faculty members), well-equipped state-of-the-art laboratories, spacious classrooms, and access to digital libraries including IEEE, ACM, Elsevier, and Springer Link.</p>
                    <p>The mission of FET is to provide dynamic learning with quality education through rigorous teaching and research methodologies, ensuring a place of pride in the world of learning.</p>
                </div>
                <a href="https://fet.usindh.edu.pk/Home/about" target="_blank" class="btn-hero btn-hero-fill mt-2" style="padding: 10px 22px; font-size: 0.85rem;">
                    <i class="bi bi-arrow-up-right"></i> Visit FET Website
                </a>
            </div>
            <div class="col-lg-5 about-img-wrapper">
                <img src="https://fet.usindh.edu.pk/assets/images/slides/1608745872_813.jpg" alt="FET Building" style="border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            </div>
        </div>
    </div>
</section>

<!-- ─── Departments (Dark Section) ─── -->
<section class="section-darker">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label cyan"><i class="bi bi-layers-fill"></i> OUR DEPARTMENTS</div>
            <h2 class="section-heading">Institutes & Departments</h2>
            <p class="section-sub mx-auto">Offering accredited undergraduate and graduate programs in Engineering & Technology.</p>
        </div>
        <div class="dept-grid">
            <!-- Row 1: 3 cards -->
            <div class="dept-card-wrap">
                <a href="https://fet.usindh.edu.pk/Home/department/Mw%3D%3D" target="_blank" class="text-decoration-none">
                    <div class="dept-card">
                        <div class="dept-icon green"><i class="bi bi-pc-display-horizontal"></i></div>
                        <div class="dept-name">Department of Information Technology</div>
                        <p class="dept-desc">Focused on data management, networking, cybersecurity, and IT infrastructure for modern enterprises.</p>
                        <span class="dept-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="dept-card-wrap">
                <a href="https://fet.usindh.edu.pk/Home/department/NA%3D%3D" target="_blank" class="text-decoration-none">
                    <div class="dept-card">
                        <div class="dept-icon blue"><i class="bi bi-code-slash"></i></div>
                        <div class="dept-name">Department of Software Engineering</div>
                        <p class="dept-desc">Building robust software systems through design patterns, agile methodologies, and quality assurance practices.</p>
                        <span class="dept-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="dept-card-wrap">
                <a href="https://fet.usindh.edu.pk/Home/department/MQ%3D%3D" target="_blank" class="text-decoration-none">
                    <div class="dept-card">
                        <div class="dept-icon cyan"><i class="bi bi-broadcast-pin"></i></div>
                        <div class="dept-name">Department of Telecommunication Engineering</div>
                        <p class="dept-desc">Exploring wireless communication, signal processing, and next-generation network technologies.</p>
                        <span class="dept-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <!-- Row 2: 2 centered cards -->
            <div class="dept-card-wrap">
                <a href="https://fet.usindh.edu.pk/Home/department/Mg%3D%3D" target="_blank" class="text-decoration-none">
                    <div class="dept-card">
                        <div class="dept-icon purple"><i class="bi bi-cpu"></i></div>
                        <div class="dept-name">Department of Electronic Engineering</div>
                        <p class="dept-desc">Designing integrated circuits, embedded systems, and electronic devices powering tomorrow's innovations.</p>
                        <span class="dept-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="dept-card-wrap">
                <a href="https://fet.usindh.edu.pk" target="_blank" class="text-decoration-none">
                    <div class="dept-card">
                        <div class="dept-icon amber"><i class="bi bi-bar-chart-line-fill"></i></div>
                        <div class="dept-name">Department of Data Science</div>
                        <p class="dept-desc">Harnessing big data, machine learning, and AI to extract insights and drive data-driven decision making.</p>
                        <span class="dept-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ─── How it Works (Light Section) ─── -->
<section class="section-light">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label blue" style="color: #3b82f6;"><i class="bi bi-lightning-charge-fill"></i> HOW IT WORKS</div>
            <h2 class="section-heading">Your FYP Journey</h2>
            <p class="section-sub mx-auto" style="color: #64748b;">A streamlined, transparent process from initial proposal to final defense and grading.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="process-card c1">
                    <div class="process-step" style="color: #10b981;">Step 01</div>
                    <div class="process-icon-wrap green"><i class="bi bi-people-fill"></i></div>
                    <h5>Form Your Group</h5>
                    <p>Create your team and submit a project proposal through the portal.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-card c2">
                    <div class="process-step" style="color: #3b82f6;">Step 02</div>
                    <div class="process-icon-wrap blue"><i class="bi bi-person-badge-fill"></i></div>
                    <h5>Supervisor Assignment</h5>
                    <p>Get matched with a faculty supervisor whose expertise aligns with your domain.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-card c3">
                    <div class="process-step" style="color: #06b6d4;">Step 03</div>
                    <div class="process-icon-wrap cyan"><i class="bi bi-code-square"></i></div>
                    <h5>Build & Iterate</h5>
                    <p>Develop your project with regular bi-weekly assessments and feedback loops.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-card c4">
                    <div class="process-step" style="color: #f59e0b;">Step 04</div>
                    <div class="process-icon-wrap amber"><i class="bi bi-award-fill"></i></div>
                    <h5>Final Defense</h5>
                    <p>Present your work to the evaluation committee for final grading.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── Faculty Spotlight (Dark Section) ─── -->
<section class="section-dark">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label purple"><i class="bi bi-mortarboard-fill"></i> FACULTY SPOTLIGHT</div>
            <h2 class="section-heading">Our Faculty Members</h2>
            <p class="section-sub mx-auto">Meet the professors, coordinators, and supervisors guiding FYP research across all departments.</p>
        </div>
        <div class="row g-3 justify-content-center">
            <?php if (empty($supervisors)): ?>
                <div class="col-12 text-center" style="color: var(--lp-muted);">
                    <i class="bi bi-people fs-1 d-block mb-3" style="opacity:0.3"></i>
                    <p>Faculty profiles will be updated shortly.</p>
                </div>
            <?php else: ?>
                <?php foreach ($supervisors as $supervisor): ?>
                    <div class="col-lg-3 col-md-4 col-6">
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
                            $desig = $supervisor['designation'] ?? '';
                            $desigClass = 'other';
                            if (stripos($desig, 'Associate') !== false) $desigClass = 'associate';
                            elseif (stripos($desig, 'Assistant') !== false) $desigClass = 'assistant';
                            elseif (stripos($desig, 'Professor') !== false) $desigClass = 'professor';
                            elseif (stripos($desig, 'Lecturer') !== false) $desigClass = 'lecturer';
                            ?>
                            <span class="faculty-designation <?php echo $desigClass; ?>"><?php echo htmlspecialchars($desig); ?></span>
                            <p class="faculty-dept"><?php echo htmlspecialchars($supervisor['department']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?php echo $basePath; ?>/faculty" class="btn-view-all">
                <i class="bi bi-people-fill"></i> See All Faculty & Staff <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ─── CTA ─── -->
<section class="section-darker" style="padding: 60px 0;">
    <div class="container">
        <div class="cta-inner">
            <h2>Ready to Start Your FYP Journey?</h2>
            <p>Register today and take the first step toward building something extraordinary.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap position-relative">
                <a href="<?php echo $basePath; ?>/register" class="btn-hero btn-hero-fill">
                    <i class="bi bi-rocket-takeoff-fill"></i> Get Started
                </a>
                <a href="<?php echo $basePath; ?>/login" class="btn-hero btn-hero-outline">
                    <i class="bi bi-box-arrow-in-right"></i> Already Registered? Login
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ─── Footer ─── -->
<footer class="lp-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" width="30" height="30" style="object-fit: contain;">
                    <h6 class="m-0">University of Sindh</h6>
                </div>
                <p style="color: var(--lp-muted); font-size: 0.85rem; line-height: 1.7; max-width: 380px;">
                    Faculty of Engineering & Technology — Striving to provide high quality education to empower people with skills and knowledge in Engineering and Technology disciplines.
                </p>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <h6>Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="<?php echo $basePath; ?>/login">Student Login</a></li>
                    <li><a href="<?php echo $basePath; ?>/register">New Registration</a></li>
                    <li><a href="https://fet.usindh.edu.pk" target="_blank">FET Main Website</a></li>
                    <li><a href="https://fet.usindh.edu.pk/Home/about" target="_blank">About FET</a></li>
                    <li><a href="https://fet.usindh.edu.pk/Home/contact" target="_blank">Contact FET</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6>Contact</h6>
                <ul class="footer-links">
                    <li><i class="bi bi-geo-alt-fill me-2" style="color: var(--lp-accent);"></i> FET, University of Sindh, Jamshoro</li>
                    <li><i class="bi bi-telephone-fill me-2" style="color: var(--lp-accent);"></i> +92-(0)22-9213181-90</li>
                    <li><i class="bi bi-globe2 me-2" style="color: var(--lp-accent);"></i> <a href="https://fet.usindh.edu.pk" target="_blank">fet.usindh.edu.pk</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> Faculty of Engineering & Technology, University of Sindh. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const navbar = document.getElementById('lpNavbar');
    window.addEventListener('scroll', () => { navbar.classList.toggle('scrolled', window.scrollY > 40); });
</script>
</body>
</html>
