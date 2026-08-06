<style>
/* FOOTER REDESIGN */
.footer-wrapper {
    filter: drop-shadow(0 -10px 25px rgba(0,0,0,0.08));
}
/* FOOTER REDESIGN */
.footer-wave {
    width: 100%;
    line-height: 0;
    margin-bottom: -1px;
}
.footer-wave svg {
    display: block;
    width: 100%;
    height: 90px;
}
.footer-wave path.wave-fill {
    fill: var(--lp-bg-alt);
}
.footer { 
    background: var(--lp-bg-alt); 
    padding: 40px 0; 
    position: relative;
    overflow: hidden;
    border-top: 1px solid var(--lp-border);
}
.footer h6 { 
    font-size: 1.1rem; 
    font-weight: 800; 
    color: var(--lp-text); 
    margin-bottom: 25px; 
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.footer-brand-title {
    font-family: 'Jost', 'Inter', sans-serif;
    font-size: 1.4rem;
    font-weight: 600;
    letter-spacing: -0.5px;
    color: var(--lp-text);
}
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 15px; display: flex; align-items: center; gap: 12px; color: var(--lp-text-muted); font-size: 0.95rem; }
.footer-links a { 
    color: var(--lp-text-muted); 
    text-decoration: none; 
    transition: all 0.3s ease; 
    display: inline-flex;
    align-items: center;
}
.footer-links a:hover { 
    color: var(--lp-accent); 
    transform: translateX(5px); 
}
.footer-contact-icon {
    color: var(--lp-accent);
    font-size: 1rem;
    background: var(--lp-bg);
    border: 1px solid var(--lp-border);
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    flex-shrink: 0;
}
.footer-brand-desc {
    color: var(--lp-text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    max-width: 400px;
    margin-top: 15px;
}
.footer-bottom { 
    border-top: 1px solid var(--lp-border); 
    margin-top: 60px; 
    padding-top: 30px; 
    display: flex;
    flex-direction: column;
    gap: 20px;
    color: var(--lp-text-muted); 
    font-size: 0.85rem; 
}
@media (min-width: 768px) {
    .footer-bottom { flex-direction: row; justify-content: space-between; align-items: center; }
}
.dev-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(16, 185, 129, 0.08);
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-radius: 50px;
    font-weight: normal;
    color: var(--lp-text);
    text-align: center;
}
:root[data-theme="dark"] .dev-badge {
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}
.dev-link {
    color: var(--lp-accent, #10b981);
    text-decoration: none;
    position: relative;
    font-weight: 600;
    transition: color 0.2s ease, text-decoration 0.2s ease;
}
.dev-link:hover {
    text-decoration: underline;
    text-underline-offset: 3px;
    color: var(--lp-text);
}
</style>

<!-- FOOTER -->
<div class="footer-wrapper">

<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-3">
                    <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" width="40" height="40">
                    <h5 class="m-0 footer-brand-title">FYP Management Portal</h5>
                </div>
                <p class="footer-brand-desc">
                    The official Final Year Project Management Portal for the Faculty of Engineering & Technology. A centralized platform dedicated to streamlining project proposals, faculty collaboration, and grading.
                </p>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <h6>Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="<?php echo $basePath; ?>/faculty">Faculty Directory</a></li>
                    <li><a href="<?php echo $basePath; ?>/contact">Contact Us</a></li>
                    <li><a href="<?php echo $basePath; ?>/login">Student Login</a></li>
                    <li><a href="<?php echo $basePath; ?>/register">Registration</a></li>
                    <li><a href="https://fet.usindh.edu.pk" target="_blank">FET Website</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6>Get in touch</h6>
                <ul class="footer-links">
                    <li>
                        <i class="bi bi-geo-alt footer-contact-icon"></i>
                        <span>Jamshoro, Sindh, Pakistan</span>
                    </li>
                    <li>
                        <i class="bi bi-telephone footer-contact-icon"></i>
                        <span>+92 337-8001160</span>
                    </li>
                    <li>
                        <i class="bi bi-globe footer-contact-icon"></i>
                        <a href="https://fet.usindh.edu.pk" target="_blank">fet.usindh.edu.pk</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div>
                &copy; <?php echo date('Y'); ?> Faculty of Engineering & Technology, University of Sindh. All rights reserved.
            </div>
            <div>
                <div class="dev-badge">
                    Developed by <strong><a href="https://www.linkedin.com/in/mr-faheem/" target="_blank" class="dev-link">Faheem Ahmed</a>, <a href="https://www.linkedin.com/in/akash-kumar-65463a25b/" target="_blank" class="dev-link">Akash Kumar</a> & <a href="https://www.linkedin.com/in/kamran-memon-4b1baa364/" target="_blank" class="dev-link">Kamran</a></strong>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });
    }
</script>
</body>
</html>
