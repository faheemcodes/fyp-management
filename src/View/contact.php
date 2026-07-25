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
    <title><?php echo htmlspecialchars($pageTitle ?? 'Contact Us - FYP Portal'); ?></title>
    
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
    <title><?php echo htmlspecialchars($pageTitle ?? 'Contact Us - FYP Portal'); ?></title>
    
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
        
        /* CONTACT CARDS NEW DESIGN */
        .contact-cards-wrapper { display: grid; grid-template-columns: 1fr; gap: 30px; padding-top: 20px; }
        @media (min-width: 992px) { .contact-cards-wrapper { grid-template-columns: repeat(3, 1fr); align-items: stretch; } }

        .contact-card { position: relative; border-radius: 45px; padding: 40px 30px; display: flex; flex-direction: column; color: #fff; transition: transform 0.3s ease; }
        .contact-card:hover { transform: translateY(-5px); }

        .contact-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 30px; }
        .contact-icon-circle { width: 90px; height: 90px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; flex-shrink: 0; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s; }

        .contact-header { text-transform: uppercase; text-align: right; }
        .contact-header h4 { font-size: 1.4rem; font-weight: 800; margin: 0 0 2px 0; color: #fff; letter-spacing: 1px; }
        .contact-header span { font-size: 0.85rem; font-weight: 600; opacity: 0.9; letter-spacing: 0.5px; }

        .contact-body { flex-grow: 1; display: flex; flex-direction: column; }
        .contact-body p { font-size: 0.95rem; line-height: 1.6; margin: 0 0 30px 0; opacity: 0.95; }

        .contact-pill { border-radius: 50px; padding: 14px 20px; margin-top: auto; display: flex; justify-content: center; align-items: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s; }
        .contact-pill a { text-decoration: none !important; font-weight: 800; font-size: 0.95rem; display: flex; align-items: center; letter-spacing: 0.5px; }

        /* Themes - Solid Punchy with Dark Insets */
        .cc-emerald { background: #10b981; box-shadow: 0 15px 40px rgba(16, 185, 129, 0.25); }
        .cc-yellow { background: #facc15; box-shadow: 0 15px 40px rgba(250, 204, 21, 0.25); }
        .cc-rose { background: #f43f5e; box-shadow: 0 15px 40px rgba(244, 63, 94, 0.25); }

        .cc-emerald .contact-icon-circle, .cc-emerald .contact-pill,
        .cc-yellow .contact-icon-circle, .cc-yellow .contact-pill,
        .cc-rose .contact-icon-circle, .cc-rose .contact-pill {
            background: rgba(0, 0, 0, 0.25); 
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
        }

        .cc-emerald .contact-icon-circle, .cc-emerald .contact-pill a,
        .cc-yellow .contact-icon-circle, .cc-yellow .contact-pill a,
        .cc-rose .contact-icon-circle, .cc-rose .contact-pill a {
            color: #ffffff;
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
                Let's <span class="highlight">Connect</span>
            </h1>
            <p class="hero-desc">
                Have questions about the Final Year Project guidelines? Need technical support with the portal? Our team at the Faculty of Engineering & Technology is here to help you navigate your FYP journey.
            </p>
        </div>
    </div>
</section>

<!-- CONTACT PILLARS -->
<section class="section">
    <div class="container">
        <div class="contact-cards-wrapper">
            <div class="contact-card cc-emerald">
                <div class="contact-card-top">
                    <div class="contact-icon-circle"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="contact-header">
                        <h4>VISIT US</h4>
                        <span>Location & Directions</span>
                    </div>
                </div>
                <div class="contact-body">
                    <p>Faculty of Engineering & Technology<br>University of Sindh<br>Jamshoro, 76080, Pakistan</p>
                    <div class="contact-pill">
                        <a href="https://maps.app.goo.gl/2equBvBv4dWupdY6A" target="_blank"><i class="bi bi-geo-alt-fill me-2 fs-5"></i> Get Directions</a>
                    </div>
                </div>
            </div>
            
            <div class="contact-card cc-yellow">
                <div class="contact-card-top">
                    <div class="contact-icon-circle"><i class="bi bi-telephone-fill"></i></div>
                    <div class="contact-header">
                        <h4>CALL US</h4>
                        <span>Portal Support</span>
                    </div>
                </div>
                <div class="contact-body">
                    <p>Reach out to the Dean's Office for administrative inquiries or urgent portal support.</p>
                    <div class="contact-pill">
                        <a href="#" onclick="copyContact(event, '+92 337-8001160', 'phone')"><i class="bi bi-telephone-fill me-2 fs-5"></i> +92 337-8001160</a>
                    </div>
                </div>
            </div>
            
            <div class="contact-card cc-rose">
                <div class="contact-card-top">
                    <div class="contact-icon-circle"><i class="bi bi-envelope-fill"></i></div>
                    <div class="contact-header">
                        <h4>EMAIL US</h4>
                        <span>General Inquiries</span>
                    </div>
                </div>
                <div class="contact-body">
                    <p>Drop us an email for general questions, proposals, or technical issues.</p>
                    <div class="contact-pill">
                        <a href="mailto:fyp.support@usindh.edu.pk" target="_blank" onclick="copyContact(event, 'fyp.support@usindh.edu.pk', 'email')"><i class="bi bi-envelope-fill me-2 fs-5"></i> fyp.support@usindh.edu.pk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/layout/lp_footer.php'; ?>

<script>
function copyContact(e, text, type) {
    if (type === 'phone') {
        e.preventDefault(); // Only prevent default for phone, let email natively trigger mailto:
    }
    navigator.clipboard.writeText(text).then(() => {
        // Create and style the tooltip
        const tooltip = document.createElement('div');
        tooltip.textContent = type === 'email' ? 'Email Copied!' : 'Number Copied!';
        tooltip.style.position = 'fixed';
        tooltip.style.left = (e.clientX + 15) + 'px';
        tooltip.style.top = (e.clientY + 15) + 'px';
        tooltip.style.background = 'var(--lp-text)';
        tooltip.style.color = 'var(--lp-bg)';
        tooltip.style.padding = '6px 12px';
        tooltip.style.borderRadius = '8px';
        tooltip.style.fontSize = '0.85rem';
        tooltip.style.fontWeight = '600';
        tooltip.style.zIndex = '9999';
        tooltip.style.pointerEvents = 'none';
        tooltip.style.opacity = '0';
        tooltip.style.transform = 'translateY(5px)';
        tooltip.style.transition = 'all 0.2s ease-out';
        tooltip.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        
        document.body.appendChild(tooltip);
        
        // Trigger fade in
        requestAnimationFrame(() => {
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateY(0)';
        });
        
        // Fade out and remove
        setTimeout(() => {
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateY(-5px)';
            setTimeout(() => tooltip.remove(), 200);
        }, 1500);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>

</body>
</html>
