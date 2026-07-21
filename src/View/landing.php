<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'FYP Management System'; ?></title>
    <meta name="description" content="Official Final Year Project management portal for the Faculty of Engineering & Technology, University of Sindh, Jamshoro.">
    
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

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts including modern fallbacks for Chuner/Pierknife -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;500;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') { $basePath = ''; }
    ?>
    <link rel="icon" href="<?php echo $basePath; ?>/images/logo.png" type="image/png">
    
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
            
            --lp-mac-body: #1e293b; /* Rich Midnight Slate */
            --lp-mac-face: #0f172a; /* Deep Slate */
            --lp-mac-touchpad: #334155; /* Medium Slate */
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

            --lp-mac-body: #f8fafc; /* Premium Ice Silver */
            --lp-mac-face: #e2e8f0; /* Frosted Silver */
            --lp-mac-touchpad: #cbd5e1; /* Deep Silver */
        }

        html, body {
            background-color: var(--lp-bg);
            color: var(--lp-text);
            font-family: var(--font-body);
            transition: background-color 0.3s, color 0.3s;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        /* TYPOGRAPHY */
        .heading-main { font-family: var(--font-heading-main); text-transform: uppercase; letter-spacing: 1px; }
        .heading-alt { font-family: var(--font-heading-alt); text-transform: uppercase; letter-spacing: 0.5px; }

        /* NAVBAR OVERRIDES */
        .lp-navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 20px 0;
            transition: all 0.3s ease;
            background: transparent !important;
        }
        .lp-navbar.scrolled {
            background: var(--lp-nav-bg) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--lp-border);
            padding: 12px 0;
        }
        .lp-navbar .brand-text h1 { font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--lp-text) !important; font-family: var(--font-heading-alt); }
        .lp-navbar .brand-text p { font-size: 0.75rem; margin: 0; color: var(--lp-text-muted) !important; }
        .lp-navbar .brand { text-decoration: none; display: flex; align-items: center; gap: 14px; }
        .lp-navbar .brand img { width: 40px; height: 40px; object-fit: contain; }
        .lp-navbar .nav-inner { display: flex; align-items: center; justify-content: space-between; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        
        .btn-nav { padding: 9px 20px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.25s ease; border: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-nav-ghost { color: var(--lp-text-muted); background: transparent; }
        .btn-nav-ghost:hover { color: var(--lp-text); background: var(--lp-bg-alt); }
        .btn-nav-primary { background: var(--lp-accent); color: white !important; }
        .btn-nav-primary:hover { background: var(--lp-accent-hover); transform: translateY(-1px); }

        /* BUTTONS */
        .btn-hero { padding: 16px 36px; border-radius: 12px; font-size: 1rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-hero-fill { background: var(--lp-text); color: var(--lp-bg) !important; }
        .btn-hero-fill:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-hero-outline { background: rgba(128, 128, 128, 0.15); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: var(--lp-text) !important; border: 2px solid var(--lp-text); }
        .btn-hero-outline:hover { background: var(--lp-text); color: var(--lp-bg) !important; transform: translateY(-3px); }

        /* HERO SECTION */
        .lp-hero {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex; align-items: center; justify-content: center;
            position: relative; padding: 100px 0 40px;
            background: var(--lp-bg);
            overflow: hidden;
        }
        .lp-hero::before, .lp-hero::after {
            content: '';
            position: absolute; inset: 0; z-index: 0;
            background-position: center center;
            pointer-events: none;
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
        .floating-el { position: absolute; opacity: 0.35; z-index: 0; pointer-events: none; }
        .el-1 { top: 10%; left: 10%; color: var(--lp-accent); animation: drift1 25s ease-in-out infinite; font-size: 1.5rem; }
        .el-2 { top: 30%; left: 80%; color: var(--lp-violet); animation: drift2 35s ease-in-out infinite; font-size: 1.2rem; }
        .el-3 { top: 70%; left: 20%; color: var(--lp-amber); animation: drift3 28s ease-in-out infinite; font-size: 1rem; }
        .el-4 { top: 50%; left: 50%; color: var(--lp-accent); animation: drift1 40s ease-in-out infinite reverse; font-size: 2rem; }
        .el-5 { top: 80%; left: 70%; color: var(--lp-violet); animation: drift2 30s ease-in-out infinite reverse; font-size: 0.8rem; }
        .el-6 { top: 20%; left: 40%; color: var(--lp-amber); animation: drift3 32s ease-in-out infinite; font-size: 1.4rem; }
        .el-7 { top: 60%; left: 10%; color: var(--lp-accent); animation: drift2 22s ease-in-out infinite; font-size: 1.7rem; }
        .el-8 { top: 15%; left: 70%; color: var(--lp-violet); animation: drift1 38s ease-in-out infinite reverse; font-size: 1.1rem; }
        .el-9 { top: 85%; left: 40%; color: var(--lp-amber); animation: drift3 26s ease-in-out infinite reverse; font-size: 1.3rem; }
        .el-10 { top: 40%; left: 90%; color: var(--lp-accent); animation: drift1 34s ease-in-out infinite; font-size: 1.6rem; }
        
        @keyframes drift1 {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(15vw, -20vh) rotate(120deg); }
            66% { transform: translate(-10vw, 15vh) rotate(240deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }
        @keyframes drift2 {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            50% { transform: translate(-20vw, 25vh) rotate(-180deg) scale(1.3); }
            100% { transform: translate(0, 0) rotate(-360deg) scale(1); }
        }
        @keyframes drift3 {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-15vw, -15vh) rotate(90deg); }
            66% { transform: translate(20vw, 10vh) rotate(180deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }
        .hero-container { position: relative; z-index: 2; width: 100%; height: 100%; padding: 0 15px; }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 40px;
            width: 100%;
        }
        .hero-left { text-align: left; max-width: 650px; }
        
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--lp-bg-alt);
            border: 1px solid var(--lp-border);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem; font-weight: 600;
            color: var(--lp-text-muted);
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }
        .status-dot {
            width: 8px; height: 8px; background: var(--lp-accent); border-radius: 50%;
            box-shadow: 0 0 10px var(--lp-accent);
        }
        
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
            font-size: 1.1rem; color: var(--lp-text-muted); line-height: 1.6; margin-bottom: 30px; max-width: 90%;
        }

        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 40px; }
        
        .btn-hero-gradient {
            padding: 16px 36px; border-radius: 12px; font-size: 1rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--lp-violet), var(--lp-accent));
            color: #fff !important; border: none; box-shadow: 0 4px 15px rgba(139,92,246,0.3);
        }
        .btn-hero-gradient:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(139,92,246,0.5); }
        
        .btn-hero-outline-light {
            padding: 16px 36px; border-radius: 12px; font-size: 1rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;
            background: transparent; color: var(--lp-text) !important; border: 2px solid var(--lp-text);
        }
        .btn-hero-outline-light:hover { background: var(--lp-text); color: var(--lp-bg) !important; transform: translateY(-3px); }
        
        .hero-stats { display: flex; justify-content: flex-start; gap: 40px; flex-wrap: wrap; padding-top: 20px; border-top: 1px solid var(--lp-border); }
        .hero-stat h3 { font-size: clamp(2rem, 4vw, 2.5rem); margin: 0; color: var(--lp-text); line-height: 1; }
        .hero-stat p { font-size: 0.85rem; color: var(--lp-amber); margin: 8px 0 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

        /* Right Column (Laptop & Badges) */
        .hero-right {
            position: relative; display: flex; justify-content: center; align-items: center; min-height: 300px; z-index: 1;
        }
        
        .hero-bg-circle { position: absolute; border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; pointer-events: none; }
        
        .hero-bg-circle-1 { width: 400px; height: 400px; border: 2px dashed rgba(16,185,129,0.3); animation: spinSlow 30s linear infinite; }
        .hero-bg-circle-2 { width: 550px; height: 550px; border: 1px solid rgba(139,92,246,0.3); animation: spinSlow 40s linear reverse infinite; }

        .orbit-dot { position: absolute; border-radius: 50%; top: 50%; left: 50%; }
        
        /* Dots for Ring 1 (Radius = 200px) */
        .dot-1 { width: 12px; height: 12px; background: #10b981; transform: translate(-50%, -50%) rotate(0deg) translateY(-200px); box-shadow: 0 0 15px #10b981; }
        .dot-2 { width: 8px; height: 8px; background: #10b981; transform: translate(-50%, -50%) rotate(120deg) translateY(-200px); box-shadow: 0 0 10px #10b981; }
        .dot-3 { width: 16px; height: 16px; border: 2px solid #10b981; transform: translate(-50%, -50%) rotate(240deg) translateY(-200px); box-shadow: 0 0 15px #10b981 inset, 0 0 15px #10b981; background: transparent; }

        /* Dots for Ring 2 (Radius = 275px) */
        .dot-4 { width: 14px; height: 14px; background: #8b5cf6; transform: translate(-50%, -50%) rotate(45deg) translateY(-275px); box-shadow: 0 0 15px #8b5cf6; }
        .dot-5 { width: 10px; height: 10px; background: #f59e0b; transform: translate(-50%, -50%) rotate(135deg) translateY(-275px); box-shadow: 0 0 15px #f59e0b; }
        .dot-6 { width: 22px; height: 22px; border: 2px solid #8b5cf6; transform: translate(-50%, -50%) rotate(225deg) translateY(-275px); box-shadow: 0 0 15px #8b5cf6 inset, 0 0 15px #8b5cf6; background: transparent; }
        .dot-7 { width: 6px; height: 6px; background: #8b5cf6; transform: translate(-50%, -50%) rotate(315deg) translateY(-275px); box-shadow: 0 0 10px #8b5cf6; }

        @keyframes spinSlow { 0% { transform: translate(-50%, -50%) rotate(0deg); } 100% { transform: translate(-50%, -50%) rotate(360deg); } }
        
        .floating-badge {
            position: absolute; display: flex; align-items: center; gap: 6px;
            background: var(--lp-card); padding: 5px 12px 5px 5px; border-radius: 50px;
            border: 1px solid var(--lp-border); box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            font-size: 0.7rem; font-weight: 600; color: var(--lp-text); z-index: -1;
            backdrop-filter: blur(10px); animation: float 6s ease-in-out infinite; white-space: nowrap;
        }
        .floating-badge .icon-wrap { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; }
        .badge-1 { top: 5%; left: 5%; animation-delay: 0s; }
        .badge-2 { bottom: 25%; left: 0%; animation-delay: 2s; }
        .badge-3 { top: 40%; right: -5%; animation-delay: 4s; }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* SECTIONS */
        .section { padding: 100px 0; }
        .section-alt { background: var(--lp-bg-alt); }
        .section-label { display: inline-flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 16px; padding: 6px 14px; border-radius: 30px; align-self: flex-start; }
        .section-label.emerald { background: rgba(16,185,129,0.1); color: var(--lp-accent); }
        .section-label.amber { background: rgba(245,158,11,0.1); color: var(--lp-amber); }
        .section-label.violet { background: rgba(139,92,246,0.1); color: var(--lp-violet); }
        .section-heading { font-size: clamp(1.8rem, 4vw, 3rem); margin-bottom: 12px; color: var(--lp-text); }
        .section-sub { font-size: 1.1rem; color: var(--lp-text-muted); max-width: 600px; margin-bottom: 40px; }

        /* CARDS & BENTO */
        .card-modern {
            background: var(--lp-card);
            border: 1px solid var(--lp-border);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s ease;
        }
        .card-modern:hover { transform: translateY(-5px); border-color: var(--lp-text-muted); }

        /* DEADLINES & NOTICES SIDE BY SIDE */
        .list-item { padding: 16px 0; border-bottom: 1px solid var(--lp-border); display: flex; gap: 16px; align-items: center; }
        .list-item:last-child { border-bottom: none; padding-bottom: 0; }
        .date-box { background: var(--lp-bg-alt); border-radius: 12px; padding: 10px; text-align: center; min-width: 70px; border: 1px solid var(--lp-border); }
        .date-box span { display: block; }
        .date-month { font-size: 0.75rem; font-weight: 700; color: var(--lp-text-muted); text-transform: uppercase; }
        .date-day { font-size: 1.2rem; font-weight: 800; color: var(--lp-text); }
        .item-info h6 { font-size: 1rem; font-weight: 600; color: var(--lp-text); margin: 0 0 4px; }
        .item-info p { margin: 0; font-size: 0.85rem; color: var(--lp-text-muted); }

        /* BENTO DEPARTMENTS */
        .bento-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .bento-item { background: var(--lp-card); border: 1px solid var(--lp-border); border-radius: 24px; padding: 32px; display: flex; flex-direction: column; transition: all 0.3s; }
        .bento-item:hover { background: var(--lp-card-hover); transform: scale(1.02); }
        .dept-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
        .dept-icon.emerald { background: rgba(16,185,129,0.1); color: var(--lp-accent); }
        .dept-icon.amber { background: rgba(245,158,11,0.1); color: var(--lp-amber); }
        .dept-icon.violet { background: rgba(139,92,246,0.1); color: var(--lp-violet); }
        .dept-icon.rose { background: rgba(244,63,94,0.1); color: var(--lp-rose); }
        .dept-icon.teal { background: rgba(20,184,166,0.1); color: var(--lp-teal); }
        .bento-item h4 { font-size: 1.25rem; font-weight: 700; color: var(--lp-text); margin-bottom: 10px; }
        .bento-item p { color: var(--lp-text-muted); font-size: 0.9rem; margin-bottom: 0; flex-grow: 1; line-height: 1.6; }

        /* HOW IT WORKS TIMELINE */
        .timeline { display: flex; justify-content: space-between; position: relative; gap: 20px; margin-top: 40px; flex-wrap: wrap; }
        .timeline::before { content: ''; position: absolute; top: 30px; left: 0; right: 0; height: 2px; background: var(--lp-border); z-index: 0; }
        @media (max-width: 768px) { .timeline::before { display: none; } }
        .timeline-step { flex: 1; min-width: 200px; position: relative; z-index: 1; text-align: center; margin-bottom: 20px; }
        .step-number { width: 60px; height: 60px; background: var(--lp-bg); border: 2px solid var(--lp-text); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; color: var(--lp-text); margin: 0 auto 20px; }
        .timeline-step h5 { font-size: 1.1rem; font-weight: 700; color: var(--lp-text); margin-bottom: 10px; }
        .timeline-step p { color: var(--lp-text-muted); font-size: 0.85rem; }

        /* FACULTY PREVIEW */
        .faculty-avatar { width: 60px; height: 60px; border-radius: 50%; background: var(--lp-bg-alt); border: 1px solid var(--lp-border); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--lp-text); margin: 0 auto 16px; font-size: 1.2rem; }

        /* CTA */
        .cta-box { background: var(--lp-text); border-radius: 30px; padding: 80px 40px; text-align: center; color: var(--lp-bg); position: relative; overflow: hidden; }
        .cta-box h2 { color: var(--lp-bg); font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom: 20px; }
        .cta-box p { color: var(--lp-bg); opacity: 0.8; font-size: 1.1rem; margin-bottom: 40px; }
        .btn-cta { background: var(--lp-bg); color: var(--lp-text) !important; padding: 16px 36px; border-radius: 12px; font-size: 1rem; font-weight: 600; text-decoration: none; display: inline-block; transition: transform 0.3s; }
        .btn-cta:hover { transform: translateY(-3px); }

        /* FOOTER */
        .footer { background: var(--lp-bg-alt); padding: 60px 0 30px; border-top: 1px solid var(--lp-border); }
        .footer h6 { font-size: 1rem; font-weight: 700; color: var(--lp-text); margin-bottom: 20px; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: var(--lp-text-muted); text-decoration: none; transition: color 0.2s; font-size: 0.9rem; }
        .footer-links a:hover { color: var(--lp-text); }
        .footer-bottom { border-top: 1px solid var(--lp-border); margin-top: 40px; padding-top: 20px; text-align: center; color: var(--lp-text-muted); font-size: 0.8rem; }

        /* 3D LAPTOP */
        .laptop-scene { position: relative; inset: auto; pointer-events: none; display: flex; justify-content: center; align-items: center; overflow: visible; z-index: 10; }
        .laptop-scene .macbook { width: 150px; height: 96px; position: relative; perspective: 500px; transform: scale(2.2); margin-top: 10px; }
        .laptop-scene .shadow { position: absolute; width: 60px; height: 0px; left: 40px; top: 160px; transform: rotateX(80deg); box-shadow: 0 0 60px 40px rgba(0,0,0,0.3); }
        .laptop-scene .inner { z-index: 20; position: absolute; width: 150px; height: 96px; left: 0; top: 0; transform-style: preserve-3d; transform: rotateX(-20deg) rotateY(0deg) rotateZ(0deg); transition: transform 0.1s ease-out; }
        .laptop-scene .screen { width: 150px; height: 96px; position: absolute; left: 0; bottom: 0; border-radius: 7px; background: var(--lp-mac-body); transform-style: preserve-3d; transform-origin: 50% 93px; background-image: linear-gradient(45deg, rgba(0,0,0,0.34) 0%, rgba(0,0,0,0) 100%); box-shadow: inset 0 3px 7px rgba(255,255,255,0.2); transition: background 0.3s; }
        .laptop-scene .screen::after { content: ''; position: absolute; inset: 0; border-radius: 7px; background: linear-gradient(105deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 30%, rgba(255,255,255,0) 70%, rgba(255,255,255,0.1) 100%); pointer-events: none; }
        .laptop-scene .screen .face-one { width: 150px; height: 96px; position: absolute; left: 0; bottom: 0; border-radius: 7px; background: var(--lp-mac-face); transform: translateZ(2px); background-image: linear-gradient(45deg, rgba(0,0,0,0.24) 0%, rgba(0,0,0,0) 100%); transition: background 0.3s; }
        .laptop-scene .screen .face-one .camera { width: 14px; height: 4px; border-radius: 4px; background: #000; position: absolute; left: 50%; top: 1px; margin-left: -7px; box-shadow: inset 0 -1px 1px rgba(255,255,255,0.2); }
        .laptop-scene .screen .face-one .camera::after { content: ''; position: absolute; width: 1.5px; height: 1.5px; background: #10b981; border-radius: 50%; right: 2px; top: 1.2px; box-shadow: 0 0 2px 0.5px #10b981; }
        .laptop-scene .screen .face-one .display { width: 142px; height: 86px; margin: 5px 4px; background: linear-gradient(180deg, #0f172a, #1e293b); border-radius: 2px; position: relative; box-shadow: inset 0 0 2px rgba(0,0,0,1); overflow: hidden; }
        .laptop-scene .screen .face-one .display .shade { position: absolute; inset: 0; background: linear-gradient(-135deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 47%, rgba(255,255,255,0) 48%); background-size: 300px 200px; z-index: 5; }
        .laptop-scene .screen .face-one span { position: absolute; bottom: 3px; left: 0; width: 100%; text-align: center; font-size: 6px; color: #666; font-weight: bold; }

        /* Dashboard UI on screen */
        .lb-dash { position: absolute; inset: 0; display: flex; font-family: sans-serif; }
        .lb-sidebar { width: 18px; background: #0c1222; display: flex; flex-direction: column; align-items: center; padding-top: 4px; gap: 3px; }
        .lb-sidebar .lb-dot { width: 6px; height: 6px; border-radius: 50%; background: #10b981; opacity: 0.6; }
        .lb-sidebar .lb-dot:first-child { opacity: 1; }
        .lb-main { flex: 1; padding: 3px; }
        .lb-header { height: 8px; background: #1a2744; border-radius: 1px; margin-bottom: 3px; display: flex; align-items: center; padding: 0 3px; }
        .lb-header-text { width: 25px; height: 2px; background: #e2e8f0; border-radius: 1px; }
        .lb-cards { display: flex; gap: 2px; margin-bottom: 3px; }
        .lb-card { flex: 1; height: 14px; border-radius: 2px; padding: 2px; }
        .lb-card-num { font-size: 5px; font-weight: bold; line-height: 1; }
        .lb-card-label { font-size: 2.5px; opacity: 0.7; margin-top: 1px; }
        .lb-table { background: rgba(0,0,0,0.2); border-radius: 1px; }
        .lb-row { height: 5px; display: flex; align-items: center; gap: 3px; padding: 0 3px; border-bottom: 0.3px solid rgba(255,255,255,0.05); }
        .lb-row:nth-child(odd) { background: rgba(26,39,68,0.4); }
        .lb-bar { height: 2px; border-radius: 1px; background: #475569; }
        .lb-status { width: 10px; height: 3px; border-radius: 1px; font-size: 2px; text-align: center; line-height: 3px; font-weight: bold; }

        /* Macbook body */
        .laptop-scene .macbody { width: 150px; height: 96px; position: absolute; left: 0; bottom: 0; border-radius: 7px; background: var(--lp-mac-body); transform-style: preserve-3d; transform-origin: 50% 93px; transform: rotateX(-90deg); background-image: linear-gradient(45deg, rgba(0,0,0,0.24) 0%, rgba(0,0,0,0) 100%); transition: background 0.3s; }
        .laptop-scene .macbody .face-one { width: 150px; height: 96px; position: absolute; left: 0; bottom: 0; border-radius: 7px; transform-style: preserve-3d; background: var(--lp-mac-face); transform: translateZ(-2px); background-image: linear-gradient(30deg, rgba(0,0,0,0.24) 0%, rgba(0,0,0,0) 100%); transition: background 0.3s; }
        .laptop-scene .macbody .face-one::after { content: ''; position: absolute; inset: 0; border-radius: 7px; background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 40%, rgba(255,255,255,0) 60%, rgba(255,255,255,0.15) 100%); pointer-events: none; }
        .laptop-scene .macbody .touchpad { width: 40px; height: 31px; position: absolute; left: 50%; top: 50%; border-radius: 4px; margin: -44px 0 0 -18px; background: var(--lp-mac-touchpad); background-image: linear-gradient(30deg, rgba(0,0,0,0.24) 0%, rgba(0,0,0,0) 100%); box-shadow: inset 0 1px 2px rgba(0,0,0,0.4), 0 0.5px 0 rgba(255,255,255,0.2); transition: background 0.3s; }
        .laptop-scene .macbody .keyboard { width: 130px; height: 45px; position: absolute; left: 7px; top: 41px; border-radius: 4px; transform-style: preserve-3d; background: var(--lp-mac-touchpad); background-image: linear-gradient(30deg, rgba(0,0,0,0.24) 0%, rgba(0,0,0,0) 100%); box-shadow: inset 0 1px 3px rgba(0,0,0,0.6), 0 0.5px 0 rgba(255,255,255,0.2); padding: 2px; display: flex; flex-direction: column-reverse; gap: 1px; justify-content: space-between; transition: background 0.3s; }
        .laptop-scene .k-row { display: flex; gap: 1px; justify-content: space-between; height: 6px; }
        .laptop-scene .k-row:first-child { height: 3.5px; }
        .laptop-scene .key { background: #333; flex-grow: 1; transform: translateZ(-1px); border-radius: 1.5px; box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 -1px 0 rgba(0,0,0,0.8); position: relative; }
        .laptop-scene .key::after { content: attr(data-key); position: absolute; left: 0.5px; bottom: 0.5px; font-size: 8px; transform: scale(0.2); transform-origin: left bottom; color: rgba(255,255,255,0.4); font-family: -apple-system, sans-serif; white-space: nowrap; pointer-events: none; }
        .laptop-scene .key.delete { flex-grow: 1.5; }
        .laptop-scene .key.tab { flex-grow: 1.5; }
        .laptop-scene .key.caps { flex-grow: 1.8; }
        .laptop-scene .key.return { flex-grow: 1.8; }
        .laptop-scene .key.shift, .laptop-scene .key.shift-r { flex-grow: 2.2; }
        .laptop-scene .key.ctrl, .laptop-scene .key.opt, .laptop-scene .key.cmd { flex-grow: 1.2; }
        .laptop-scene .key.space { flex-grow: 6.5; }
        .laptop-scene .arrows { display: flex; flex-direction: column-reverse; gap: 0.5px; width: 17.5px; margin-left: 1px; flex-shrink: 0; }
        .laptop-scene .arrows-bottom { display: flex; gap: 1px; height: 2.5px; }
        .laptop-scene .key.up { height: 2.5px; margin: 0 auto; width: 5.5px; flex-grow: 0; }
        .laptop-scene .key.left, .laptop-scene .key.down, .laptop-scene .key.right { height: 2.5px; width: 5.5px; flex-grow: 1; }
        .laptop-scene .macbody .pad { width: 5px; height: 5px; background: #333; border-radius: 50%; position: absolute; }
        .laptop-scene .pad.one { left: 20px; top: 20px; }
        .laptop-scene .pad.two { right: 20px; top: 20px; }
        .laptop-scene .pad.three { right: 20px; bottom: 20px; }
        .laptop-scene .pad.four { left: 20px; bottom: 20px; }

        @media (max-width: 991px) {
            .hero-grid { grid-template-columns: 1fr; text-align: center; gap: 60px; }
            .hero-left { text-align: center; max-width: 100%; }
            .hero-btns { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-desc { margin: 0 auto 30px; }
            .badge-1 { top: -5%; left: 15%; }
            .badge-2 { bottom: 0%; left: 10%; }
            .badge-3 { top: 30%; right: 5%; }
            .laptop-scene .macbook { transform: scale(1.6); }
        }
        
        @media (max-width: 768px) { 
            .laptop-scene .macbook { transform: scale(1.4); } 
            .badge-1 { top: -5%; left: 10%; transform: scale(0.85); }
            .badge-2 { bottom: 0%; left: 5%; transform: scale(0.85); }
            .badge-3 { top: 30%; right: 0%; transform: scale(0.85); }
            
            /* Force stats onto one line for mobile but keep them legible */
            .hero-stats { gap: 10px; flex-wrap: nowrap; justify-content: space-between; width: 100%; overflow-x: auto; padding-bottom: 5px; }
            .hero-stat h3 { font-size: 1.8rem; }
            .hero-stat p { font-size: 0.75rem; letter-spacing: 0px; margin-top: 4px; }
            
            .hero-desc { font-size: 1rem; }
            .section-sub { font-size: 0.95rem; margin-bottom: 30px; }
            
            /* Vertical Timeline for Mobile */
            .timeline { display: grid; grid-template-columns: 1fr; gap: 30px; position: relative; padding-left: 10px; margin-top: 30px; }
            .timeline::before { display: block !important; left: 34px; top: 10px; bottom: 10px; width: 2px; height: auto; right: auto; background: var(--lp-border); }
            .timeline-step { display: grid; grid-template-columns: 50px 1fr; gap: 20px; text-align: left; align-items: flex-start; margin-bottom: 0; min-width: 0; }
            .step-number { grid-column: 1; grid-row: 1 / 3; margin: 0; width: 50px; height: 50px; font-size: 1.2rem; z-index: 2; position: relative; }
            .timeline-step h5 { grid-column: 2; grid-row: 1; margin-bottom: 4px; margin-top: 2px; }
            .timeline-step p { grid-column: 2; grid-row: 2; margin-bottom: 0; }
        }

        /* Compact layout for shorter screens (like 1366x768 laptops) */
        @media (max-height: 800px) and (min-width: 992px) {
            .lp-hero { padding: 80px 0 20px; }
            .hero-title { font-size: clamp(2rem, 4vw, 3.5rem); margin-bottom: 10px; }
            .hero-desc { margin-bottom: 20px; font-size: 1rem; }
            .hero-btns { margin-bottom: 20px; }
            .hero-badge { margin-bottom: 10px; }
            .laptop-scene .macbook { transform: scale(1.8); }
        }
        @media (max-height: 650px) and (min-width: 992px) {
            .lp-hero { padding: 60px 0 10px; }
            .hero-title { font-size: clamp(1.8rem, 3.5vw, 3rem); }
            .hero-desc { margin-bottom: 15px; font-size: 0.95rem; }
            .hero-btns { margin-bottom: 15px; }
            .hero-stats { padding-top: 10px; gap: 20px; }
            .hero-stat h3 { font-size: clamp(1.5rem, 3vw, 2rem); }
            .laptop-scene .macbook { transform: scale(1.5); }
        }

        .notice-board-item {
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 8px;
            background: var(--lp-bg-alt);
            border: 1px solid var(--lp-border);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .notice-board-item:hover {
            transform: translateY(-3px) translateX(4px);
            background: var(--lp-card-hover);
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border-color: rgba(16, 185, 129, 0.3);
        }
        .notice-board-item .item-info { flex: 1; min-width: 0; }
        .notice-board-item h6 { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; }
        .notice-board-item .chevron-icon {
            font-size: 1.1rem;
            color: var(--lp-accent);
            opacity: 0.3;
            transition: all 0.3s ease;
            transform: translateX(-8px);
        }
        .notice-board-item:hover .chevron-icon {
            opacity: 1;
            transform: translateX(0);
        }

    </style>
</head>
<body>

<?php include __DIR__ . '/layout/lp_navbar.php'; ?>

<!-- HERO -->
<section class="lp-hero">
    <div class="hero-shape"></div>
    <div class="hero-shape-2"></div>
    
    <!-- Floating Elements -->
    <div class="floating-el el-1"><i class="bi bi-star-fill"></i></div>
    <div class="floating-el el-2"><i class="bi bi-triangle"></i></div>
    <div class="floating-el el-3"><i class="bi bi-circle-fill"></i></div>
    <div class="floating-el el-4"><i class="bi bi-square"></i></div>
    <div class="floating-el el-5"><i class="bi bi-plus-lg"></i></div>
    <div class="floating-el el-6"><i class="bi bi-asterisk"></i></div>
    <div class="floating-el el-7"><i class="bi bi-hexagon-fill"></i></div>
    <div class="floating-el el-8"><i class="bi bi-diamond-fill"></i></div>
    <div class="floating-el el-9"><i class="bi bi-star"></i></div>
    <div class="floating-el el-10"><i class="bi bi-circle"></i></div>

    <div class="container hero-container">
        <div class="hero-grid">
            <div class="hero-left">
                <div class="hero-badge">
                    <span class="status-dot"></span> University of Sindh &bull; Official FYP Portal
                </div>
                <h1 class="hero-title heading-main">
                    We Build <br><span class="highlight">Future Engineers</span><br>That Grow Tech
                </h1>
                <p class="hero-desc">
                    We design, develop, and streamline the Final Year Project workflow — from stunning ideas to powerful management strategies that put you ahead of the competition.
                </p>
                <div class="hero-btns">
                    <a href="<?php echo $basePath; ?>/login" class="btn-hero btn-hero-gradient">
                        <i class="bi bi-rocket-fill"></i> Launch Your FYP
                    </a>
                    <a href="<?php echo $basePath; ?>/register" class="btn-hero btn-hero-outline-light">
                        Student Registration <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div class="hero-stats">
                    <div class="hero-stat">
                        <h3 class="heading-main"><?php echo $stats['departments'] ?? 0; ?>+</h3>
                        <p>Departments</p>
                    </div>
                    <div class="hero-stat">
                        <h3 class="heading-main"><?php echo $stats['faculty'] ?? 0; ?>+</h3>
                        <p>Faculty</p>
                    </div>
                    <div class="hero-stat">
                        <h3 class="heading-main"><?php echo $stats['projects'] ?? 0; ?>+</h3>
                        <p>Projects</p>
                    </div>
                    <div class="hero-stat">
                        <h3 class="heading-main"><?php echo $stats['students'] ?? 0; ?>+</h3>
                        <p>Students</p>
                    </div>
                </div>
            </div>
            
            <div class="hero-right">
                <div class="hero-bg-circle hero-bg-circle-1">
                    <div class="orbit-dot dot-1"></div>
                    <div class="orbit-dot dot-2"></div>
                    <div class="orbit-dot dot-3"></div>
                </div>
                <div class="hero-bg-circle hero-bg-circle-2">
                    <div class="orbit-dot dot-4"></div>
                    <div class="orbit-dot dot-5"></div>
                    <div class="orbit-dot dot-6"></div>
                    <div class="orbit-dot dot-7"></div>
                </div>
                <div class="floating-badge badge-1">
                    <div class="icon-wrap" style="background: rgba(139,92,246,0.2); color: var(--lp-violet);"><i class="bi bi-people-fill"></i></div> Supervisor Allocation
                </div>
                <div class="floating-badge badge-2">
                    <div class="icon-wrap" style="background: rgba(16,185,129,0.2); color: var(--lp-accent);"><i class="bi bi-bar-chart-steps"></i></div> Milestone Tracking
                </div>
                <div class="floating-badge badge-3">
                    <div class="icon-wrap" style="background: rgba(245,158,11,0.2); color: var(--lp-amber);"><i class="bi bi-file-earmark-text-fill"></i></div> Thesis Submissions
                </div>

                <!-- 3D Laptop -->
                <div class="laptop-scene" id="laptopScene">
                    <div class="macbook">
                        <div class="inner">
                            <div class="screen">
                                <div class="face-one">
                                    <div class="camera"></div>
                                    <div class="display">
                                        <div class="lb-dash">
                                            <div class="lb-main">
                                                <div class="lb-header"><div class="lb-header-text"></div></div>
                                                <div class="lb-cards">
                                                    <div class="lb-card" style="background:rgba(16,185,129,0.15);"><div class="lb-card-num" style="color:#10b981;">24</div><div class="lb-card-label" style="color:#94a3b8;">Groups</div></div>
                                                    <div class="lb-card" style="background:rgba(139,92,246,0.15);"><div class="lb-card-num" style="color:#8b5cf6;">52</div><div class="lb-card-label" style="color:#94a3b8;">Projects</div></div>
                                                    <div class="lb-card" style="background:rgba(245,158,11,0.15);"><div class="lb-card-num" style="color:#f59e0b;">18</div><div class="lb-card-label" style="color:#94a3b8;">Faculty</div></div>
                                                    <div class="lb-card" style="background:rgba(244,63,94,0.15);"><div class="lb-card-num" style="color:#f43f5e;">31</div><div class="lb-card-label" style="color:#94a3b8;">Done</div></div>
                                                </div>
                                                <div class="lb-table">
                                                    <div class="lb-row"><div class="lb-bar" style="width:22px;background:#cbd5e1;"></div><div class="lb-bar" style="width:12px;"></div><div class="lb-bar" style="width:14px;"></div><div class="lb-status" style="background:rgba(16,185,129,0.3);color:#10b981;">OK</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:18px;background:#cbd5e1;"></div><div class="lb-bar" style="width:10px;"></div><div class="lb-bar" style="width:16px;"></div><div class="lb-status" style="background:rgba(245,158,11,0.3);color:#f59e0b;">...</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:25px;background:#cbd5e1;"></div><div class="lb-bar" style="width:14px;"></div><div class="lb-bar" style="width:12px;"></div><div class="lb-status" style="background:rgba(139,92,246,0.3);color:#8b5cf6;">Rev</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:20px;background:#cbd5e1;"></div><div class="lb-bar" style="width:11px;"></div><div class="lb-bar" style="width:15px;"></div><div class="lb-status" style="background:rgba(16,185,129,0.3);color:#10b981;">OK</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:16px;background:#cbd5e1;"></div><div class="lb-bar" style="width:13px;"></div><div class="lb-bar" style="width:11px;"></div><div class="lb-status" style="background:rgba(244,63,94,0.3);color:#f43f5e;">No</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:24px;background:#cbd5e1;"></div><div class="lb-bar" style="width:10px;"></div><div class="lb-bar" style="width:13px;"></div><div class="lb-status" style="background:rgba(16,185,129,0.3);color:#10b981;">OK</div></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shade"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="macbody">
                                <div class="face-one">
                                    <div class="touchpad"></div>
                                    <div class="keyboard">
                                        <div class="k-row"><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div></div>
                                        <div class="k-row"><div class="key" data-key="~"></div><div class="key" data-key="1"></div><div class="key" data-key="2"></div><div class="key" data-key="3"></div><div class="key" data-key="4"></div><div class="key" data-key="5"></div><div class="key" data-key="6"></div><div class="key" data-key="7"></div><div class="key" data-key="8"></div><div class="key" data-key="9"></div><div class="key" data-key="0"></div><div class="key" data-key="-"></div><div class="key" data-key="="></div><div class="key delete" data-key="delete"></div></div>
                                        <div class="k-row"><div class="key tab" data-key="tab"></div><div class="key" data-key="Q"></div><div class="key" data-key="W"></div><div class="key" data-key="E"></div><div class="key" data-key="R"></div><div class="key" data-key="T"></div><div class="key" data-key="Y"></div><div class="key" data-key="U"></div><div class="key" data-key="I"></div><div class="key" data-key="O"></div><div class="key" data-key="P"></div><div class="key" data-key="["></div><div class="key" data-key="]"></div><div class="key" data-key="\"></div></div>
                                        <div class="k-row"><div class="key caps" data-key="caps lock"></div><div class="key" data-key="A"></div><div class="key" data-key="S"></div><div class="key" data-key="D"></div><div class="key" data-key="F"></div><div class="key" data-key="G"></div><div class="key" data-key="H"></div><div class="key" data-key="J"></div><div class="key" data-key="K"></div><div class="key" data-key="L"></div><div class="key" data-key=";"></div><div class="key return" data-key="return"></div></div>
                                        <div class="k-row"><div class="key shift" data-key="shift"></div><div class="key" data-key="Z"></div><div class="key" data-key="X"></div><div class="key" data-key="C"></div><div class="key" data-key="V"></div><div class="key" data-key="B"></div><div class="key" data-key="N"></div><div class="key" data-key="M"></div><div class="key" data-key=","></div><div class="key" data-key="."></div><div class="key shift-r" data-key="shift"></div></div>
                                        <div class="k-row"><div class="key ctrl" data-key="control"></div><div class="key opt" data-key="option"></div><div class="key cmd" data-key="command"></div><div class="key space"></div><div class="key cmd" data-key="command"></div><div class="key opt" data-key="option"></div><div class="arrows"><div class="key up"></div><div class="arrows-bottom"><div class="key left"></div><div class="key down"></div><div class="key right"></div></div></div></div>
                                    </div>
                                </div>
                                <div class="pad one"></div><div class="pad two"></div><div class="pad three"></div><div class="pad four"></div>
                            </div>
                        </div>
                        <div class="shadow"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DEADLINES & NOTICES -->
<section class="section section-alt">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="d-flex flex-column h-100">
                    <span class="section-label amber">Announcements</span>
                    <h2 class="section-heading heading-main">Deadlines</h2>
                    <div class="card-modern mt-4 flex-grow-1">
                    <?php if (empty($deadlines)): ?>
                        <p class="text-muted m-0">No upcoming deadlines at the moment.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($deadlines, 0, 4) as $deadline): ?>
                            <div class="list-item">
                                <div class="date-box">
                                    <span class="date-month"><?php echo date('M', strtotime($deadline['deadline_date'])); ?></span>
                                    <span class="date-day"><?php echo date('d', strtotime($deadline['deadline_date'])); ?></span>
                                </div>
                                <div class="item-info">
                                    <h6><?php echo htmlspecialchars($deadline['stage']); ?></h6>
                                    <p><?php echo htmlspecialchars($deadline['department'] ?? 'All Departments'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            
            <div class="col-lg-6 mt-5 ">
                <div class="d-flex flex-column h-100">
                    <span class="section-label emerald">Updates</span>
                    <h2 class="section-heading heading-main">Notice Board</h2>
                    <div class="card-modern mt-4 flex-grow-1">
                    <?php if (empty($notices)): ?>
                        <p class="text-muted m-0">No notices have been published yet.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($notices, 0, 4) as $notice): ?>
                            <div class="notice-board-item" data-bs-toggle="modal" data-bs-target="#noticeModal" 
                                 data-subject="<?php echo htmlspecialchars($notice['subject'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 data-audience="<?php echo htmlspecialchars($notice['target_audience'] ?? 'All', ENT_QUOTES, 'UTF-8'); ?>" 
                                 data-body="<?php echo htmlspecialchars($notice['body'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 data-date="<?php echo date('M d, Y', strtotime($notice['notice_date'])); ?>"
                                 data-dept="<?php echo htmlspecialchars($notice['department'] ?? 'General', ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="item-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6><?php echo htmlspecialchars($notice['subject']); ?></h6>
                                    </div>
                                    <p class="text-truncate mb-2" style="max-width: 90%; font-size: 0.8rem; color: var(--lp-text-muted);"><?php echo htmlspecialchars(strip_tags($notice['body'])); ?></p>
                                    <div class="d-flex flex-wrap align-items-center mt-2 gap-3">
                                        <span class="badge" style="background-color: var(--lp-border); color: var(--lp-text); font-weight: 500; font-size: 0.7rem; padding: 4px 8px;"><?php echo htmlspecialchars($notice['department'] ?? 'General'); ?></span>
                                        <small style="color: var(--lp-text); opacity: 0.7; font-size: 0.7rem;"><i class="bi bi-calendar3 me-1"></i><?php echo date('M d, Y', strtotime($notice['notice_date'])); ?></small>
                                    </div>
                                </div>
                                <div class="chevron-icon">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DEPARTMENTS BENTO -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label violet">Explore</span>
            <h2 class="section-heading heading-main">Departments</h2>
            <p class="section-sub mx-auto">Undergraduate and graduate programs in Engineering & Technology.</p>
        </div>
        
        <div class="bento-grid">
            <div class="bento-item">
                <div class="dept-icon emerald"><i class="bi bi-pc-display"></i></div>
                <h4>Information Technology</h4>
                <p>Data management, networking, cybersecurity, and IT infrastructure.</p>
            </div>
            <div class="bento-item">
                <div class="dept-icon amber"><i class="bi bi-code-square"></i></div>
                <h4>Software Engineering</h4>
                <p>Building robust software systems and agile methodologies.</p>
            </div>
            <div class="bento-item">
                <div class="dept-icon teal"><i class="bi bi-broadcast-pin"></i></div>
                <h4>Telecommunication</h4>
                <p>Wireless communication and next-generation networks.</p>
            </div>
            <div class="bento-item">
                <div class="dept-icon rose"><i class="bi bi-cpu"></i></div>
                <h4>Electronic Engineering</h4>
                <p>Integrated circuits, embedded systems, and electronic devices.</p>
            </div>
            <div class="bento-item">
                <div class="dept-icon violet"><i class="bi bi-bar-chart-line"></i></div>
                <h4>Data Science</h4>
                <p>Harnessing big data, machine learning, and AI.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section section-alt">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading heading-main">The Process</h2>
            <p class="section-sub mx-auto">Your final year project journey from start to finish.</p>
        </div>
        
        <div class="timeline">
            <div class="timeline-step">
                <div class="step-number">1</div>
                <h5>Propose</h5>
                <p>Form a group and submit your initial project idea.</p>
            </div>
            <div class="timeline-step">
                <div class="step-number">2</div>
                <h5>Supervision</h5>
                <p>Get assigned to an expert faculty member.</p>
            </div>
            <div class="timeline-step">
                <div class="step-number">3</div>
                <h5>Development</h5>
                <p>Iterate through bi-weekly assessments.</p>
            </div>
            <div class="timeline-step">
                <div class="step-number">4</div>
                <h5>Defense</h5>
                <p>Present and finalize your graduation project.</p>
            </div>
        </div>
    </div>
</section>

<!-- FACULTY -->
<section class="section">
    <div class="container text-center">
        <h2 class="section-heading heading-main">Faculty & Staff</h2>
        <p class="section-sub mx-auto">Guiding FYP research across all departments.</p>
        
        <div class="row justify-content-center g-4 mb-5">
            <?php if (!empty($supervisors)): ?>
                <?php foreach (array_slice($supervisors, 0, 4) as $supervisor): ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card-modern">
                        <div class="faculty-avatar">
                            <?php echo strtoupper(substr($supervisor['name'], 0, 1)); ?>
                        </div>
                        <h6 class="mb-1" style="color: var(--lp-text);"><?php echo htmlspecialchars($supervisor['name']); ?></h6>
                        <small style="color: var(--lp-text-muted);"><?php echo htmlspecialchars($supervisor['department']); ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mt-4">
            <a href="<?php echo $basePath; ?>/faculty" class="btn-hero btn-hero-fill">View Full Directory</a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container">
        <div class="cta-box">
            <h2 class="heading-main">Start Building Today</h2>
            <p>Register your group and take the first step towards your FYP.</p>
            <a href="<?php echo $basePath; ?>/register" class="btn-cta">Get Started</a>
        </div>
    </div>
</section>

<!-- Notice Modal -->
<style>
    :root[data-theme="dark"] .modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>
<div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: var(--lp-card);">
            <div class="modal-header" style="border-bottom: 1px solid var(--lp-border); padding: 24px;">
                <h5 class="modal-title fw-bold" id="noticeModalLabel" style="color: var(--lp-text);">Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 32px 24px;">
                <h4 id="modalNoticeSubject" class="fw-bold mb-4" style="color: var(--lp-text);"></h4>
                <div id="modalNoticeBody" class="notice-body-content mb-4" style="color: var(--lp-text); line-height: 1.8; white-space: pre-wrap; font-size: 0.95rem;">
                </div>
                <div class="d-flex flex-wrap align-items-center border-top pt-3 mt-2 gap-3" style="border-color: var(--lp-border) !important;">
                    <span id="modalNoticeDept" class="badge px-3 py-2 rounded-pill" style="background-color: var(--lp-border); color: var(--lp-text);"></span>
                    <div class="small" style="color: var(--lp-text); opacity: 0.7;">
                        <i class="bi bi-calendar3 me-1"></i> Published on <span id="modalNoticeDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var noticeModal = document.getElementById('noticeModal');
    if (noticeModal) {
        noticeModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var subject = button.getAttribute('data-subject');
            var audience = button.getAttribute('data-audience');
            var body = button.getAttribute('data-body');
            var date = button.getAttribute('data-date');
            var dept = button.getAttribute('data-dept');
            
            noticeModal.querySelector('#modalNoticeSubject').textContent = subject;
            noticeModal.querySelector('#modalNoticeDept').textContent = dept;
            noticeModal.querySelector('#modalNoticeDate').textContent = date;
            noticeModal.querySelector('#modalNoticeBody').textContent = body;
        });
    }
});
</script>

<script>
    // Scroll parallax & Mouse tracking for 3D laptop
    (function() {
        const scene = document.getElementById('laptopScene');
        const macbook = scene ? scene.querySelector('.macbook') : null;
        if (!scene || !macbook) return;
        const hero = scene.closest('.lp-hero');
        const inner = macbook.querySelector('.inner');

        // Scroll logic (scale & vertical translation) based on viewport position
        window.addEventListener('scroll', () => {
            const rect = scene.getBoundingClientRect();
            // When rect.top drops below 200px (scrolling up), we start the fade out
            let progress = 0;
            if (rect.top < 200) {
                progress = Math.min(Math.max((200 - rect.top) / 400, 0), 1);
            }
            
            const ty = progress * -60;
            const baseScale = window.innerWidth <= 768 ? 1.4 : (window.innerWidth <= 991 ? 1.6 : 2.2);
            const scale = baseScale - (progress * baseScale * 0.25);
            
            macbook.style.transform = 'scale(' + scale + ') translateY(' + ty + 'px)';
            macbook.style.opacity = 1 - progress * 0.8;
        });

        // Mouse tracking logic (rotation)
        document.addEventListener('mousemove', (e) => {
            const mouseX = (e.clientX / window.innerWidth - 0.5) * 2; // -1 to 1
            const mouseY = (e.clientY / window.innerHeight - 0.5) * 2; // -1 to 1
            
            // Base rotation is rotateX(-20deg)
            const rotX = -20 + (mouseY * -20); // Mouse up -> laptop tilts up
            const rotY = mouseX * 40; // Mouse right -> laptop turns right
            
            if (inner) {
                inner.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg) rotateZ(0deg)`;
            }
        });
    })();
</script>

<?php include __DIR__ . '/layout/lp_footer.php'; ?>
