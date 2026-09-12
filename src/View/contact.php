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
    
    <!-- Preconnections -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://unpkg.com">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet" />

    <style>
        /* HERO SECTION */
        .lp-hero {
            position: relative;
            background: var(--lp-bg);
            padding: 180px 0 100px;
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
            background-size: 40px 40px; opacity: 0.7;
            -webkit-mask-image: radial-gradient(circle at 12% 25%, black 2%, transparent 25%), radial-gradient(circle at 88% 75%, black 5%, transparent 30%), radial-gradient(circle at 45% 90%, black 1%, transparent 20%);
        }
        
        .hero-shape { position: absolute; width: 60vw; height: 60vw; border-radius: 50%; background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, rgba(15,23,42,0) 70%); top: -20vw; right: -20vw; pointer-events: none; z-index: 0; }
        .hero-shape-2 { position: absolute; width: 40vw; height: 40vw; border-radius: 50%; background: radial-gradient(circle, rgba(139,92,246,0.06) 0%, rgba(15,23,42,0) 70%); bottom: -10vw; left: -10vw; pointer-events: none; z-index: 0; }
        
        .about-content { position: relative; z-index: 5; max-width: 800px; margin: 0 auto; text-align: center; }
        
        .hero-title { font-size: clamp(2.5rem, 6vw, 4.5rem); line-height: 1.1; margin-bottom: 24px; color: var(--lp-text); letter-spacing: -1px; }
        .highlight { background: linear-gradient(135deg, #a78bfa, var(--lp-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .hero-desc { font-size: 1.15rem; color: var(--lp-text-muted); margin: 0 auto 40px; line-height: 1.7; max-width: 650px; }

        /* SECTIONS */
        .section { padding: 100px 0; position: relative; z-index: 5; }
        .section-alt { background: var(--lp-bg-alt); }
        
        .section-label { display: inline-flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 16px; padding: 6px 14px; border-radius: 30px; }
        .section-label.emerald { background: rgba(16,185,129,0.1); color: var(--lp-accent); }
        .section-label.violet { background: rgba(139,92,246,0.1); color: var(--lp-violet); }
        
        .section-title { font-size: 2.8rem; font-weight: 800; color: var(--lp-text); margin-bottom: 1rem; text-align: center; letter-spacing: -0.5px; }
        .section-subtitle { font-size: 1.1rem; color: var(--lp-text-muted); text-align: center; margin-bottom: 4rem; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6; }

                /* MINIMAL CONTACT SECTION */
        .minimal-info-header { font-size: 1.8rem; font-weight: 500; color: var(--lp-text); line-height: 1.2; margin-bottom: 15px; letter-spacing: -0.5px; }
        .minimal-info-desc { font-size: 1rem; color: var(--lp-text-muted); margin-bottom: 40px; }
        
        .minimal-info-item { display: flex; align-items: center; gap: 20px; margin-bottom: 30px; }
        .minimal-info-icon { 
            width: 55px; height: 55px; border-radius: 50%; 
            background: rgba(var(--lp-text-rgb, 100, 116, 139), 0.1); 
            color: var(--lp-text); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;
        }
        .minimal-info-text h4 { font-size: 1.05rem; font-weight: 700; color: var(--lp-text); margin-bottom: 2px; }
        .minimal-info-text p { color: var(--lp-text-muted); margin: 0; font-size: 0.95rem; }

        .minimal-form-header { font-size: 1.8rem; font-weight: 500; color: var(--lp-text); margin-bottom: 10px; }
        .minimal-form-desc { font-size: 0.95rem; color: var(--lp-text-muted); margin-bottom: 30px; line-height: 1.5; }
        
        .form-control-minimal {
            background: transparent;
            border: 1px solid var(--lp-text-muted);
            color: var(--lp-text);
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        select.form-control-minimal {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.25rem;
        }
        .form-control-minimal::placeholder { color: var(--lp-text-muted); opacity: 0.7; }
        select.form-control-minimal:invalid { color: var(--lp-text-muted); opacity: 0.7; }
        select.form-control-minimal option { color: var(--lp-text); background: var(--lp-bg); opacity: 1; }
        .form-control-minimal:focus {
            border-color: var(--lp-text);
            box-shadow: 0 0 0 1px var(--lp-text);
            outline: none;
        }
        
        .btn-minimal {
            background: var(--lp-text);
            color: var(--lp-bg) !important;
            border: none; padding: 14px 30px; border-radius: 8px; font-weight: 600;
            transition: opacity 0.3s ease; width: 100%;
        }
        .btn-minimal:hover { opacity: 0.9; }

        /* DIRECTORY GRID */
        .coord-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; }
        .coord-card {
            background: rgba(var(--lp-card-rgb, 255, 255, 255), 0.5);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--lp-border);
            border-radius: 20px; padding: 30px 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column; align-items: center; text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        :root[data-theme="dark"] .coord-card {
            background: rgba(30, 41, 59, 0.5);
        }
        .coord-card:hover { transform: translateY(-8px); border-color: rgba(16,185,129,0.4); box-shadow: 0 20px 40px rgba(16,185,129,0.1); }
        
        .coord-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, var(--lp-accent), var(--lp-teal));
            color: white; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 700; margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(16,185,129,0.3);
        }
        .coord-name { font-size: 1.25rem; font-weight: 800; color: var(--lp-text); margin-bottom: 6px; letter-spacing: -0.3px; }
        .coord-dept { font-size: 0.95rem; color: var(--lp-violet); font-weight: 600; margin-bottom: 20px; }
        .coord-email {
            font-size: 0.85rem; color: var(--lp-text); background: var(--lp-bg);
            padding: 8px 16px; border-radius: 50px; border: 1px solid var(--lp-border);
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;
            font-weight: 500;
        }
        .coord-email:hover { background: var(--lp-accent); color: white; border-color: var(--lp-accent); }

                /* FAQs */
        .accordion-custom { display: flex; flex-direction: column; gap: 15px; }
        .accordion-custom .accordion-item {
            background: rgba(var(--lp-text-rgb, 100, 116, 139), 0.05);
            border: 2px solid transparent !important;
            border-radius: 50px !important;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .accordion-custom .accordion-item:has(.accordion-button:not(.collapsed)) {
            background: var(--lp-bg);
            border-color: rgba(124, 58, 237, 0.4) !important;
            box-shadow: 0 10px 40px rgba(124, 58, 237, 0.1);
            border-radius: 30px !important;
        }

        .accordion-custom .accordion-button {
            font-weight: 500; font-size: 1.05rem; padding: 20px 30px; box-shadow: none !important;
            background: transparent !important; color: var(--lp-text) !important; display: flex; align-items: center; gap: 20px;
        }
        .accordion-custom .faq-num {
            width: 32px; height: 32px; border-radius: 50%; background: var(--lp-bg); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; color: var(--lp-text); flex-shrink: 0; border: 1px solid var(--lp-border);
        }
        .accordion-custom .accordion-item:has(.accordion-button:not(.collapsed)) .faq-num {
            background: rgba(var(--lp-text-rgb, 100, 116, 139), 0.05);
        }

        .accordion-custom .accordion-button::after { display: none; }

        .accordion-custom .faq-icon {
            width: 38px; height: 38px; border-radius: 50%; background: var(--lp-text); color: var(--lp-bg); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; margin-left: auto; transition: all 0.3s ease;
            pointer-events: none;
        }
        .accordion-custom .faq-icon .icon-open { display: none; }
        
        .accordion-custom .accordion-button:not(.collapsed) .faq-icon {
            background: transparent; color: var(--lp-text); border: 1px solid var(--lp-border);
            transform: rotate(180deg);
        }
        .accordion-custom .accordion-button:not(.collapsed) .faq-icon .icon-closed { display: none; }
        .accordion-custom .accordion-button:not(.collapsed) .faq-icon .icon-open { display: block; }

        .accordion-custom .accordion-body { padding: 0 80px 30px 80px; color: var(--lp-text-muted); line-height: 1.7; font-size: 0.95rem; background: transparent; }
        


                /* MAP */
        .map-wrapper { position: relative; max-width: 1100px; margin: 0 auto; z-index: 1; }
        .map-wrapper::before {
            content: ''; position: absolute; inset: -15px;
            background: linear-gradient(135deg, var(--lp-accent), var(--lp-violet), var(--lp-teal));
            filter: blur(30px); opacity: 0.15; border-radius: 40px; z-index: -1;
        }
        .map-container {
            border-radius: 30px; overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid var(--lp-border);
            height: 450px;
            position: relative;
            background: var(--lp-card);
        }
        
        .map-floating-card {
            position: absolute; bottom: 30px; left: 30px;
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--lp-border); border-radius: 24px;
            padding: 24px; width: 320px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            z-index: 10;
        }
        :root[data-theme="dark"] .map-floating-card {
            background: linear-gradient(135deg, rgba(40, 40, 40, 0.8), rgba(20, 20, 20, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .map-floating-icon {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg, var(--lp-accent), var(--lp-violet));
            color: white; display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-right: 15px;
            box-shadow: 0 8px 16px rgba(124, 58, 237, 0.3);
        }
        



        /* MOBILE OPTIMIZATIONS */
        @media (max-width: 768px) {
            /* Layout & Spacing */
            .lp-hero { padding: 120px 0 60px; min-height: auto; }
            .hero-title { font-size: 2.2rem; margin-bottom: 15px; }
            .hero-desc { font-size: 1rem; margin-bottom: 30px; }
            .section { padding: 60px 0; }
            .section-title { font-size: 2rem; margin-bottom: 0.5rem; }
            .section-subtitle { font-size: 0.95rem; margin-bottom: 2.5rem; }
            
            /* Contact Form & Info */
            .minimal-info-header, .minimal-form-header { font-size: 1.5rem; }
            .minimal-info-desc { margin-bottom: 25px; font-size: 0.95rem; }
            .minimal-info-icon { width: 45px; height: 45px; font-size: 1.2rem; }
            .minimal-info-item { gap: 15px; margin-bottom: 20px; }
            .form-control-minimal { padding: 12px 16px; font-size: 0.9rem; }
            
            /* FAQs */
            .accordion-custom .accordion-item { border-radius: 24px !important; }
            .accordion-custom .accordion-item:has(.accordion-button:not(.collapsed)) { border-radius: 20px !important; }
            .accordion-custom .accordion-body { padding: 0 20px 20px 20px; font-size: 0.9rem; }
            .accordion-custom .accordion-button { padding: 15px 20px; gap: 12px; font-size: 0.95rem; }
            .accordion-custom .faq-num { width: 26px; height: 26px; font-size: 0.75rem; }
            .accordion-custom .faq-icon { width: 30px; height: 30px; font-size: 1.2rem; }
            
            /* Map */
            .map-floating-card { position: relative; bottom: 0; left: 0; width: 100%; margin-top: 20px; border-radius: 20px; padding: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
            .map-container { height: 300px; border-radius: 20px; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/layout/lp_navbar.php'; ?>

<!-- HERO -->
<section class="lp-hero">
    <div class="hero-shape"></div>
    <div class="hero-shape-2"></div>
    <div class="container">
        <div class="about-content" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="hero-title heading-main" style="font-weight: 500;">Let's <span class="highlight">Connect</span></h1>
            <p class="hero-desc">Have questions about the Final Year Project guidelines? Need technical support with the portal? Our team at the Faculty of Engineering & Technology is here to help you navigate your FYP journey.</p>
        </div>
    </div>
</section>

<!-- CONTACT FORM & INFO -->
<section class="section">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-5">
            
            <!-- Direct Info (Left) -->
            <div class="col-lg-5 order-lg-1" data-aos="fade-right" data-aos-duration="1000">
                <h2 class="minimal-info-header heading-main">Need more information?<br>Get in touch with us</h2>
                <p class="minimal-info-desc">Our support staff is here to help you with portal issues or general inquiries.</p>
                
                <div class="minimal-info-item">
                    <div class="minimal-info-icon"><i class="bi bi-clock"></i></div>
                    <div class="minimal-info-text">
                        <h4>Office Hours</h4>
                        <p>Monday - Friday<br>9:00 AM - 3:00 PM</p>
                    </div>
                </div>

                <div class="minimal-info-item">
                    <div class="minimal-info-icon"><i class="bi bi-telephone"></i></div>
                    <div class="minimal-info-text">
                        <h4>Direct Call</h4>
                        <p>Dean's Office / Support<br>+92 337-8001160</p>
                    </div>
                </div>

                <div class="minimal-info-item">
                    <div class="minimal-info-icon"><i class="bi bi-envelope"></i></div>
                    <div class="minimal-info-text">
                        <h4>Email Support</h4>
                        <p>General portal inquiries<br>fyp.support@usindh.edu.pk</p>
                    </div>
                </div>
            </div>

            <!-- Form (Right) -->
            <div class="col-lg-7 order-lg-2" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <h3 class="minimal-form-header heading-main">Send Message</h3>
                <p class="minimal-form-desc">Fill out the form below. Our support team will review your query and reply promptly.</p>
                
                                <form id="contactForm" onsubmit="handleContactSubmit(event)">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control-minimal w-100" placeholder="Full Name (e.g. John Doe)" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control-minimal w-100" placeholder="Email Address" required>
                        </div>
                        <div class="col-md-6">
                            <select name="department" class="form-control-minimal w-100" required>
                                <option value="" disabled selected>Department</option>
                                <option>Software Engineering</option>
                                <option>Information Technology</option>
                                <option>Electronics Engineering</option>
                                <option>Telecommunication Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="query_type" class="form-control-minimal w-100" required>
                                <option value="" disabled selected>Query Type</option>
                                <option>Proposal Submission</option>
                                <option>Technical Issue</option>
                                <option>General Inquiry</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <textarea name="message" class="form-control-minimal w-100" rows="5" placeholder="Write Message Here..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn-minimal">Send Message</button>
                        </div>
                        <div class="col-12 mt-2" id="contactFormAlert" style="display: none;"></div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</section>



<!-- FAQs -->
<section class="section section-alt">
    <div class="container" style="max-width: 900px;">
        <div class="text-center" data-aos="fade-up">
            <div class="section-label violet"><i class="bi bi-question-circle-fill me-1"></i> Support</div>
            <h2 class="section-title heading-main" style="font-weight: 500;">Frequently Asked Questions</h2>
            <p class="section-subtitle">Find quick answers about the FYP portal and submission process.</p>
        </div>

                <div class="accordion accordion-custom" id="faqAccordion" data-aos="fade-up" data-aos-delay="200">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        <span class="faq-num">1</span>
                        How do I register my project group?
                        <span class="faq-icon">
                            <i class="bi bi-plus icon-closed"></i>
                            <i class="bi bi-dash icon-open"></i>
                        </span>
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Only the designated group leader needs to submit the initial proposal. Navigate to your dashboard, click "Submit Proposal", and you will be able to add your group members' details during the submission process.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        <span class="faq-num">2</span>
                        What happens after I submit a project proposal?
                        <span class="faq-icon">
                            <i class="bi bi-plus icon-closed"></i>
                            <i class="bi bi-dash icon-open"></i>
                        </span>
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Your proposal is first sent to your selected supervisor for review. If they accept it, the proposal is automatically forwarded to the Department Committee and HOD for final approval. You can track the real-time status directly on your dashboard.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        <span class="faq-num">3</span>
                        How can I keep track of upcoming project deadlines?
                        <span class="faq-icon">
                            <i class="bi bi-plus icon-closed"></i>
                            <i class="bi bi-dash icon-open"></i>
                        </span>
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        All official deadlines (such as Proposal Submission, Mid-term Evaluation, and Final Defense) are managed by the HOD. These are displayed prominently on the landing page and within your student dashboard.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        <span class="faq-num">4</span>
                        Can I change my group members after submitting the proposal?
                        <span class="faq-icon">
                            <i class="bi bi-plus icon-closed"></i>
                            <i class="bi bi-dash icon-open"></i>
                        </span>
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Once a proposal has been submitted, students cannot manually alter the group composition. If a change is absolutely necessary, you must formally contact your Department FYP Coordinator to request an administrative update.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAP -->
<section class="section" style="padding-bottom: 80px;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-label violet"><i class="bi bi-geo-alt-fill me-1"></i> Location</div>
            <h2 class="section-title heading-main" style="font-weight: 500;">Find Us on Campus</h2>
        </div>
        
        <div class="map-wrapper" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="100">
            <div class="map-container">
                <iframe src="https://maps.google.com/maps?q=Faculty%20of%20Engineering%20and%20Technology%2C%20University%20of%20Sindh%2C%20Jamshoro&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            
            <div class="map-floating-card" data-aos="fade-right" data-aos-delay="500">
                <div class="d-flex align-items-center mb-3">
                    <div class="map-floating-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <h4 class="mb-0" style="color: var(--lp-text); font-weight: 700; font-size: 1.1rem;">Main Campus</h4>
                        <p class="mb-0" style="color: var(--lp-text-muted); font-size: 0.9rem;">Jamshoro, Sindh</p>
                    </div>
                </div>
                <p style="color: var(--lp-text-muted); font-size: 0.95rem; margin-bottom: 20px;">
                    Faculty of Engineering & Technology, University of Sindh.
                </p>
                <a href="https://maps.google.com/maps?q=Faculty+of+Engineering+and+Technology,+University+of+Sindh,+Jamshoro" target="_blank" class="btn-minimal py-2 w-100 text-center" style="font-size: 0.95rem; display: block; text-decoration: none; border-radius: 50px;">
                    Get Directions <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/layout/lp_footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    // Initialize AOS Animations
    AOS.init({
        once: true,
        offset: 50,
        duration: 800,
        easing: 'ease-out-cubic'
    });

    // Handle Form Submission Simulation
    async function handleContactSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const alertDiv = document.getElementById('contactFormAlert');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
        btn.disabled = true;
        alertDiv.style.display = 'none';
        
        try {
            const formData = new FormData(form);
            const response = await fetch('<?php echo dirname($_SERVER["SCRIPT_NAME"]) === "/" || dirname($_SERVER["SCRIPT_NAME"]) === "\\\\" ? "" : dirname($_SERVER["SCRIPT_NAME"]); ?>/contact-submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                btn.innerHTML = '<i class="bi bi-check2-circle me-2"></i> Message Sent Successfully!';
                btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                form.reset();
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 4000);
            } else {
                throw new Error(data.error || 'Failed to send message.');
            }
        } catch (error) {
            alertDiv.className = 'alert alert-danger mt-3';
            alertDiv.innerHTML = error.message;
            alertDiv.style.display = 'block';
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>

</body>
</html>
