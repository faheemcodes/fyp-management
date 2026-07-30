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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts including modern fallbacks for Chuner/Pierknife -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800;900&family=Bebas+Neue&family=Oswald:wght@400;500;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') { $basePath = ''; }
    ?>
    <link rel="icon" href="<?php echo $basePath; ?>/images/logo.png" type="image/png">
    
    
<style>
        html { scroll-behavior: smooth; }
/* LANDING SPECIFIC VARIABLES */
        :root {
            --lp-mac-body: #e2e8f0;
            --lp-mac-face: #cbd5e1;
            --lp-mac-touchpad: #94a3b8;
        }
        :root[data-theme="dark"] {
            --lp-mac-body: #475569;
            --lp-mac-face: #64748b;
            --lp-mac-touchpad: #334155;
        }
        
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
        .section-heading { 
            font-size: clamp(2.2rem, 4.5vw, 3.2rem); 
            margin-bottom: 16px; 
            color: var(--lp-text); 
            letter-spacing: -1px;
            line-height: 1.1;
        }
        .section-sub { font-size: 1.15rem; line-height: 1.6; color: var(--lp-text-muted); max-width: 600px; margin-bottom: 40px; }

        /* CARDS & BENTO */
        .card-modern {
            background: var(--lp-card);
            border: 1px solid var(--lp-border);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s ease;
        }
        .card-modern:hover { transform: translateY(-5px); border-color: var(--lp-text-muted); }
        
        .card-notice-board {
            background: #1f2937; /* Dark Charcoal */
            border-radius: 20px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            --lp-text-muted: #9ca3af;
            --lp-text: #f3f4f6;
            --lp-border: rgba(255,255,255,0.05);
            --lp-bg-alt: rgba(255,255,255,0.02);
            --lp-card-hover: rgba(255,255,255,0.05);
        }
        .card-notice-board::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 160px;
            height: 160px;
            background: #ffffff; /* Explicitly white so it stays white in dark theme too */
            border-radius: 50%;
            opacity: 1;
            z-index: 0;
            box-shadow: none;
        }
        .card-notice-board > * {
            position: relative;
            z-index: 1;
        }
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
        .bento-grid { display: grid; grid-template-columns: 1fr; gap: 60px 50px; padding-top: 40px; padding-left: 20px; }
        @media (min-width: 768px) { .bento-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 992px) { .bento-grid { grid-template-columns: repeat(3, 1fr); } }
        
        .bento-item { position: relative; border-radius: 28px; padding: 30px 24px; display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); color: #fff; border: none; z-index: 1; height: 100%; }
        .bento-item:hover { transform: translateY(-6px); }
        
        .card-number { position: absolute; top: -20px; left: -25px; width: 105px; height: 105px; background: var(--lp-bg) !important; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3.2rem; font-weight: 800; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 2; }
        
        .card-header { margin-left: 65px; margin-bottom: 15px; text-transform: uppercase; text-align: right; }
        .card-header h4 { color: inherit; font-size: 1.05rem; font-weight: 800; margin: 0; letter-spacing: 1px; }
        .card-header span { font-size: 0.8rem; font-weight: 600; opacity: 0.9; letter-spacing: 0.5px; }
        
        .card-body { flex-grow: 1; margin-top: 5px; padding: 0; display: flex; flex-direction: column; }
        .card-body p { color: #fff; font-size: 0.95rem; line-height: 1.6; margin: 0; text-align: left; opacity: 0.95; }

        /* Themes matching the image with 3D tinted shadows */
        .theme-orange { background: #f05a30; box-shadow: 0 15px 40px rgba(240, 90, 48, 0.35); }
        .theme-orange:hover { box-shadow: 0 25px 50px rgba(240, 90, 48, 0.45); }
        .theme-orange .card-number { color: #f05a30; }
        
        .theme-slate { background: #4a5568; box-shadow: 0 15px 40px rgba(74, 85, 104, 0.35); }
        .theme-slate:hover { box-shadow: 0 25px 50px rgba(74, 85, 104, 0.45); }
        .theme-slate .card-number { color: #4a5568; }
        
        .theme-crimson { background: #e11d48; box-shadow: 0 15px 40px rgba(225, 29, 72, 0.35); }
        .theme-crimson:hover { box-shadow: 0 25px 50px rgba(225, 29, 72, 0.45); }
        .theme-crimson .card-number { color: #e11d48; }
        
        .theme-blue { background: #7dd3fc; box-shadow: 0 15px 40px rgba(125, 211, 252, 0.35); }
        .theme-blue:hover { box-shadow: 0 25px 50px rgba(125, 211, 252, 0.45); }
        .theme-blue .card-number { color: #0284c7; }
        
        .theme-emerald { background: #10b981; box-shadow: 0 15px 40px rgba(16, 185, 129, 0.35); }
        .theme-emerald:hover { box-shadow: 0 25px 50px rgba(16, 185, 129, 0.45); }
        .theme-emerald .card-number { color: #10b981; }

        /* HOW IT WORKS PROCESS FLOW */
        .timeline-grid { display: grid; grid-template-columns: 1fr; gap: 40px; margin-top: 50px; max-width: 900px; margin-left: auto; margin-right: auto; }
        @media (min-width: 768px) {
            .timeline-grid { grid-template-columns: 1fr 1fr; gap: 40px 60px; position: relative; align-items: start; }
            .timeline-card:nth-child(even) { margin-top: 100px; }
            
            /* Connecting Arrow: Odd -> Even (e.g. 1 to 2) */
            .timeline-card:nth-child(odd):not(:last-child)::after {
                content: ''; position: absolute;
                top: 50px; left: 100%;
                width: calc(50% + 60px); height: 30px;
                border-top: 2px dashed var(--lp-text-muted);
                border-right: 2px dashed var(--lp-text-muted);
                border-top-right-radius: 20px;
                opacity: 0.4; z-index: -1;
            }
            .timeline-card:nth-child(odd):not(:last-child)::before {
                content: ''; position: absolute;
                top: 78px; left: calc(150% + 60px - 5px);
                border: 6px solid transparent;
                border-top-color: var(--lp-text-muted);
                opacity: 0.4; z-index: -1;
            }

            /* Connecting Arrow: Even -> Odd (e.g. 2 to 3) */
            .timeline-card:nth-child(even):not(:last-child)::after {
                content: ''; position: absolute;
                top: calc(100% - 40px); right: calc(100% + 30px);
                width: calc(50% + 30px); height: 60px;
                border-top: 2px dashed var(--lp-text-muted);
                border-left: 2px dashed var(--lp-text-muted);
                border-top-left-radius: 20px;
                opacity: 0.4; z-index: -1;
            }
            .timeline-card:nth-child(even):not(:last-child)::before {
                content: ''; position: absolute;
                top: calc(100% + 20px - 2px); right: calc(150% + 60px - 5px);
                border: 6px solid transparent;
                border-top-color: var(--lp-text-muted);
                opacity: 0.4; z-index: -1;
            }
        }

        .timeline-card { position: relative; border-radius: 24px; padding: 30px 30px 30px 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); z-index: 1; border: 1px solid var(--lp-border); }
        
        .card-pill { position: absolute; left: -20px; top: 50%; transform: translateY(-50%) rotate(180deg); padding: 15px 8px; border-radius: 30px; writing-mode: vertical-rl; text-orientation: mixed; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; display: flex; align-items: center; justify-content: center; min-height: 100px; box-shadow: 0 10px 20px rgba(0,0,0,0.15); z-index: 2; white-space: nowrap; }

        .timeline-card .card-header { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .timeline-card .icon-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .timeline-card h4 { font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--lp-text); }
        .timeline-card p { font-size: 0.95rem; line-height: 1.6; margin: 0; color: var(--lp-text-muted); }

        .section-separator {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--lp-border), transparent);
            margin: 0 auto;
            width: 100%;
            max-width: 800px;
            opacity: 0.5;
        }

        /* Styles */
        .tl-style-green { background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2); }
        .tl-style-green .card-pill { background: #064e3b; color: #fff; }
        .tl-style-green .icon-circle { background: rgba(16, 185, 129, 0.15); color: #10b981; }

        .tl-style-grey { background: var(--lp-card); }
        .tl-style-grey .card-pill { background: var(--lp-text); color: var(--lp-bg); }
        .tl-style-grey .icon-circle { background: var(--lp-bg-alt); color: var(--lp-text); border: 1px solid var(--lp-border); }
        
        /* Ensure pill text is readable against the light silver pill background in dark mode */
        :root[data-theme="dark"] .tl-style-grey .card-pill { color: #334155; }

        /* Mobile Responsive Fixes */
        @media (max-width: 767px) {
            /* Notice Board */
            .card-notice-board::before { width: 100px; height: 100px; top: -30px; right: -30px; }

            /* Departments */
            .bento-grid { padding-left: 10px; gap: 55px 25px; }
            .card-number { width: 80px; height: 80px; font-size: 2.3rem; top: -15px; left: -15px; }
            .bento-item { padding: 25px 20px 20px; border-radius: 20px; }
            .bento-item .card-header { margin-left: 55px; margin-bottom: 10px; }

            /* Process */
            .timeline-grid { padding-left: 15px; gap: 30px; }
            .timeline-card { padding: 20px 20px 20px 40px; }
            .card-pill { left: -15px; padding: 10px 5px; font-size: 0.75rem; min-height: 80px; }

            /* Mobile arrows */
            .timeline-card:not(:last-child)::after {
                content: ''; position: absolute;
                bottom: -22px; left: 50%;
                width: 2px; height: 18px;
                border-left: 2px dashed var(--lp-text-muted);
                opacity: 0.4; z-index: -1;
            }
            .timeline-card:not(:last-child)::before {
                content: ''; position: absolute;
                bottom: -28px; left: calc(50% - 5px);
                border-width: 6px 6px 0 6px;
                border-style: solid;
                border-color: var(--lp-text-muted) transparent transparent transparent;
                opacity: 0.4; z-index: -1;
            }
        }




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
<style>
:root[data-theme="dark"] .modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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
                <div class="hero-badge" data-aos="fade-right" data-aos-duration="800">
                    <span class="status-dot"></span> University of Sindh &bull; Official FYP Portal
                </div>
                <h1 class="hero-title heading-main" data-aos="fade-right" data-aos-duration="800" data-aos-delay="100">
                    We Build <br><span class="highlight">Future Engineers</span><br>That Grow Tech
                </h1>
                <p class="hero-desc" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                    We design, develop, and streamline the Final Year Project workflow — from stunning ideas to powerful management strategies that put you ahead of the competition.
                </p>
                <div class="hero-btns" data-aos="fade-right" data-aos-duration="800" data-aos-delay="300">
                    <a href="<?php echo $basePath; ?>/login" class="btn-hero btn-hero-gradient">
                        <i class="bi bi-rocket-fill"></i> Launch Your FYP
                    </a>
                    <a href="<?php echo $basePath; ?>/register" class="btn-hero btn-hero-outline-light">
                        Student Registration <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div class="hero-stats" data-aos="fade-right" data-aos-duration="800" data-aos-delay="400">
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
            
            <div class="hero-right" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
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
                <div class="floating-badge badge-1" data-aos="zoom-in" data-aos-delay="500">
                    <div class="icon-wrap" style="background: rgba(139,92,246,0.2);color: var(--lp-violet)"><i class="bi bi-people-fill"></i></div> Supervisor Allocation
                </div>
                <div class="floating-badge badge-2" data-aos="zoom-in" data-aos-delay="700">
                    <div class="icon-wrap" style="background: rgba(16,185,129,0.2);color: var(--lp-accent)"><i class="bi bi-bar-chart-steps"></i></div> Milestone Tracking
                </div>
                <div class="floating-badge badge-3" data-aos="zoom-in" data-aos-delay="900">
                    <div class="icon-wrap" style="background: rgba(245,158,11,0.2);color: var(--lp-amber)"><i class="bi bi-file-earmark-text-fill"></i></div> Thesis Submissions
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
                                                    <div class="lb-card" style="background:rgba(16,185,129,0.15)"><div class="lb-card-num" style="color:#10b981">24</div><div class="lb-card-label" style="color:#94a3b8">Groups</div></div>
                                                    <div class="lb-card" style="background:rgba(139,92,246,0.15)"><div class="lb-card-num" style="color:#8b5cf6">52</div><div class="lb-card-label" style="color:#94a3b8">Projects</div></div>
                                                    <div class="lb-card" style="background:rgba(245,158,11,0.15)"><div class="lb-card-num" style="color:#f59e0b">18</div><div class="lb-card-label" style="color:#94a3b8">Faculty</div></div>
                                                    <div class="lb-card" style="background:rgba(244,63,94,0.15)"><div class="lb-card-num" style="color:#f43f5e">31</div><div class="lb-card-label" style="color:#94a3b8">Done</div></div>
                                                </div>
                                                <div class="lb-table">
                                                    <div class="lb-row"><div class="lb-bar" style="width:22px;background:#cbd5e1"></div><div class="lb-bar" style="width:12px"></div><div class="lb-bar" style="width:14px"></div><div class="lb-status" style="background:rgba(16,185,129,0.3);color:#10b981">OK</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:18px;background:#cbd5e1"></div><div class="lb-bar" style="width:10px"></div><div class="lb-bar" style="width:16px"></div><div class="lb-status" style="background:rgba(245,158,11,0.3);color:#f59e0b">...</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:25px;background:#cbd5e1"></div><div class="lb-bar" style="width:14px"></div><div class="lb-bar" style="width:12px"></div><div class="lb-status" style="background:rgba(139,92,246,0.3);color:#8b5cf6">Rev</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:20px;background:#cbd5e1"></div><div class="lb-bar" style="width:11px"></div><div class="lb-bar" style="width:15px"></div><div class="lb-status" style="background:rgba(16,185,129,0.3);color:#10b981">OK</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:16px;background:#cbd5e1"></div><div class="lb-bar" style="width:13px"></div><div class="lb-bar" style="width:11px"></div><div class="lb-status" style="background:rgba(244,63,94,0.3);color:#f43f5e">No</div></div>
                                                    <div class="lb-row"><div class="lb-bar" style="width:24px;background:#cbd5e1"></div><div class="lb-bar" style="width:10px"></div><div class="lb-bar" style="width:13px"></div><div class="lb-status" style="background:rgba(16,185,129,0.3);color:#10b981">OK</div></div>
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
            <div class="col-lg-6" data-aos="fade-up">
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
            
            <div class="col-lg-6 mt-5" data-aos="fade-left" data-aos-delay="200">
                <div class="d-flex flex-column h-100">
                    <span class="section-label emerald">Updates</span>
                    <h2 class="section-heading heading-main">Notice Board</h2>
                    <div class="card-notice-board mt-4 flex-grow-1">
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
                                    <p class="text-truncate mb-2" style="max-width: 90%;font-size: 0.8rem;color: var(--lp-text-muted)"><?php echo htmlspecialchars(strip_tags($notice['body'])); ?></p>
                                    <div class="d-flex flex-wrap align-items-center mt-2 gap-3">
                                        <span class="badge" style="background-color: var(--lp-border);color: var(--lp-text);font-weight: 500;font-size: 0.7rem;padding: 4px 8px"><?php echo htmlspecialchars($notice['department'] ?? 'General'); ?></span>
                                        <small style="color: var(--lp-text);opacity: 0.7;font-size: 0.7rem"><i class="bi bi-calendar3 me-1"></i><?php echo date('M d, Y', strtotime($notice['notice_date'])); ?></small>
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
        <div class="text-center mb-5" data-aos="fade-down">
            <span class="section-label violet">Explore</span>
            <h2 class="section-heading heading-main">Departments</h2>
            <p class="section-sub mx-auto">Undergraduate and graduate programs in Engineering & Technology.</p>
        </div>
        
        <div class="bento-grid">
            <div class="bento-item theme-orange" data-aos="fade-up" data-aos-delay="100">
                <div class="card-number"><i class="bi bi-pc-display"></i></div>
                <div class="card-header">
                    <h4>Information Technology</h4>
                    <span>Department</span>
                </div>
                <div class="card-body">
                    <p>Established in 1998, the IT department provides an NCEAC-accredited program emphasizing critical thinking. Students learn to provide practical IT solutions for the nation's administrative challenges using state-of-the-art facilities.</p>
                </div>
            </div>
            <div class="bento-item theme-slate" data-aos="fade-up" data-aos-delay="200">
                <div class="card-number"><i class="bi bi-code-square"></i></div>
                <div class="card-header">
                    <h4>Software Engineering</h4>
                    <span>Department</span>
                </div>
                <div class="card-body">
                    <p>One of Pakistan's first programs of its kind, offering NCEAC-accredited education. The curriculum focuses on engineering complex systems through research, design, and testing to build robust software architectures.</p>
                </div>
            </div>
            <div class="bento-item theme-crimson" data-aos="fade-up" data-aos-delay="300">
                <div class="card-number"><i class="bi bi-broadcast-pin"></i></div>
                <div class="card-header">
                    <h4>Telecommunication</h4>
                    <span>Department</span>
                </div>
                <div class="card-body">
                    <p>Operating under an Outcome-Based Education framework, this PEC-accredited program produces graduates equipped with a vision for modern telecommunications. Students master next-generation networks and technologies.</p>
                </div>
            </div>
            <div class="bento-item theme-blue" data-aos="fade-up" data-aos-delay="400">
                <div class="card-number"><i class="bi bi-cpu"></i></div>
                <div class="card-header">
                    <h4>Electronic Engineering</h4>
                    <span>Department</span>
                </div>
                <div class="card-body">
                    <p>With roots dating back to 1979, this PEC-accredited department bridges theoretical concepts with practical application. Students engage in experimental learning using modern hardware to meet socio-economic needs.</p>
                </div>
            </div>
            <div class="bento-item theme-emerald" data-aos="fade-up" data-aos-delay="500">
                <div class="card-number"><i class="bi bi-bar-chart-line"></i></div>
                <div class="card-header">
                    <h4>Data Science</h4>
                    <span>Department</span>
                </div>
                <div class="card-body">
                    <p>Harnessing the immense power of big data, machine learning, and artificial intelligence. Students learn to extract actionable insights from complex datasets, build predictive models, and implement AI-driven automation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section section-alt">
    <div class="container">
        <div class="text-center" data-aos="fade-down">
            <h2 class="section-heading heading-main">The Process</h2>
            <p class="section-sub mx-auto">Your final year project journey from start to finish.</p>
        </div>
        
        <div class="timeline-grid">
            <div class="timeline-card tl-style-green" data-aos="flip-left" data-aos-delay="100">
                <div class="card-pill">Phase 1</div>
                <div class="card-header">
                    <div class="icon-circle"><i class="bi bi-search"></i></div>
                    <h4>Propose</h4>
                </div>
                <p>Form a group, brainstorm innovative ideas, and submit your initial project proposal for approval.</p>
            </div>
            
            <div class="timeline-card tl-style-grey" data-aos="flip-left" data-aos-delay="200">
                <div class="card-pill">Phase 2</div>
                <div class="card-header">
                    <div class="icon-circle"><i class="bi bi-person-badge"></i></div>
                    <h4>Supervision</h4>
                </div>
                <p>Get assigned to an expert faculty member who will guide and mentor your project development.</p>
            </div>

            <div class="timeline-card tl-style-grey" data-aos="flip-left" data-aos-delay="300">
                <div class="card-pill">Phase 3</div>
                <div class="card-header">
                    <div class="icon-circle"><i class="bi bi-code-slash"></i></div>
                    <h4>Development</h4>
                </div>
                <p>Iterate through bi-weekly assessments, build your project, and refine the core functionality.</p>
            </div>

            <div class="timeline-card tl-style-green" data-aos="flip-left" data-aos-delay="400">
                <div class="card-pill">Phase 4</div>
                <div class="card-header">
                    <div class="icon-circle"><i class="bi bi-box-arrow-up"></i></div>
                    <h4>Defense</h4>
                </div>
                <p>Present and finalize your graduation project in front of the evaluation committee.</p>
            </div>
        </div>
    </div>
</section>

<!-- FACULTY -->
<section class="section">
    <div class="container text-center">
        <div data-aos="fade-down">
            <h2 class="section-heading heading-main">Faculty & Staff</h2>
            <p class="section-sub mx-auto">Guiding FYP research across all departments.</p>
        </div>
        
        <div class="row justify-content-center g-4 mb-5">
            <?php if (!empty($supervisors)): ?>
                <?php foreach (array_slice($supervisors, 0, 4) as $index => $supervisor): ?>
                <div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4 + 1) * 100; ?>">
                    <div class="card-modern text-center h-100 p-4">
                        <div class="avatar mx-auto mb-3" style="width: 80px;height: 80px;background: rgba(16, 185, 129, 0.1);color: var(--lp-accent);border-radius: 50%;display: flex;align-items: center;justify-content: center;font-size: 2.5rem;font-weight: 700">
                            <?php echo strtoupper(substr($supervisor['name'], 0, 1)); ?>
                        </div>
                        <h5 style="font-weight: 700;margin-bottom: 5px"><?php echo htmlspecialchars($supervisor['name']); ?></h5>
                        <p style="color: var(--lp-text-muted);font-size: 0.9rem;margin-bottom: 15px">Faculty Member</p>
                        <div style="font-size: 0.85rem;color: var(--lp-text-muted)">
                            <div class="mb-1"><i class="bi bi-building me-1"></i> <?php echo htmlspecialchars($supervisor['department']); ?></div>
                        </div>
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



<!-- Notice Modal -->

<div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px;border: none;box-shadow: 0 10px 30px rgba(0,0,0,0.1);background: var(--lp-card)">
            <div class="modal-header" style="border-bottom: 1px solid var(--lp-border);padding: 24px">
                <h5 class="modal-title fw-bold" id="noticeModalLabel" style="color: var(--lp-text)">Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 32px 24px">
                <h4 id="modalNoticeSubject" class="fw-bold mb-4" style="color: var(--lp-text)"></h4>
                <div id="modalNoticeBody" class="notice-body-content mb-4" style="color: var(--lp-text);line-height: 1.8;white-space: pre-wrap;font-size: 0.95rem">
                </div>
                <div class="d-flex flex-wrap align-items-center border-top pt-3 mt-2 gap-3" style="border-color: var(--lp-border) !important">
                    <span id="modalNoticeDept" class="badge px-3 py-2 rounded-pill" style="background-color: var(--lp-border);color: var(--lp-text)"></span>
                    <div class="small" style="color: var(--lp-text);opacity: 0.7">
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
