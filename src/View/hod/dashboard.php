
<style>
.notice-minimal-item {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.notice-minimal-item:hover {
    background: var(--card-bg);
    border-color: rgba(16, 185, 129, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}
.notice-minimal-item .notice-accent-bar {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3.5px;
    background: #10b981;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.notice-minimal-item:hover .notice-accent-bar {
    opacity: 1;
}
.notice-date-badge {
    font-size: 0.68rem;
    font-weight: 600;
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.02em;
}
.notice-view-btn {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 5px 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
    line-height: 1;
}
.notice-minimal-item:hover .notice-view-btn {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.3);
}

.notice-list {
    padding-right: 8px;
    padding-left: 2px;
    padding-top: 2px;
    padding-bottom: 2px;
}
.notice-list::-webkit-scrollbar {
    width: 5px;
}
.notice-list::-webkit-scrollbar-track {
    background: transparent;
}
.notice-list::-webkit-scrollbar-thumb {
    background: rgba(150, 150, 150, 0.25);
    border-radius: 10px;
}
.notice-list::-webkit-scrollbar-thumb:hover {
    background: rgba(150, 150, 150, 0.45);
}
</style>
<!-- HOD Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.45rem;letter-spacing: -0.02em">Department Overview</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7);font-size: 0.85rem">Manage faculty, coordinate groups, and monitor academic progress</p>
            </div>
        </div>
    </div>
</div>

<!-- ── Stat Cards Row ── -->
<div class="row g-3 mb-4">
    <!-- Supervisors Card -->
    <div class="col-xl-3 col-sm-6">
        <a href="<?php echo $basePath; ?>/hod/supervisors" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-green">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-green">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars($stats['supervisors'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Supervisors</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Committee Card -->
    <div class="col-xl-3 col-sm-6">
        <a href="<?php echo $basePath; ?>/hod/committee" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-blue">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-blue">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars($stats['committee'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Committee</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Coordinator Card -->
    <div class="col-xl-3 col-sm-6">
        <a href="<?php echo $basePath; ?>/hod/coordinators" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-purple">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-purple">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars($stats['coordinators'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Coordinators</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Verify Students Card -->
    <div class="col-xl-3 col-sm-6">
        <a href="<?php echo $basePath; ?>/hod/students/verify" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-amber">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-amber">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars($stats['pending_approvals'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Verify Students</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


<!-- ── Recent Notices ── -->
<div class="card border-0 p-3 p-md-4 mb-4">
    <div class="page-section-header mb-4">
        <div class="page-section-icon" style="background: rgba(59, 130, 246, 0.1);color: #3b82f6">
                    <i class="bi bi-megaphone-fill"></i>
        </div>
        <div>
            <h6>Recent Notices</h6>
            <small>View latest announcements and updates</small>
        </div>
    </div>

            <div class="notice-list custom-scroll" style="max-height: 280px; overflow-y: auto;">
                <?php foreach($recentNotices as $n): ?>
                <div class="notice-minimal-item" role="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>">
                    <div class="notice-accent-bar"></div>
                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="notice-date-badge">
                                <i class="bi bi-calendar3" style="font-size: 0.62rem;"></i>
                                <?php echo date('M d', strtotime($n['notice_date'])); ?>
                            </span>
                        </div>
                        <div class="text-truncate" style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);" title="<?php echo htmlspecialchars($n['subject']); ?>">
                            <?php echo htmlspecialchars($n['subject']); ?>
                        </div>
                    </div>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="notice-view-btn flex-shrink-0" onclick="event.stopPropagation();">
                        <span>View</span>
                        <i class="bi bi-arrow-up-right" style="font-size: 0.7rem;"></i>
                    </button>
                </div>
                <?php endforeach; ?>
                <?php if(empty($recentNotices)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                    No recent notices found.
                </div>
                <?php endif; ?>
            </div>
</div>

<!-- Notice Modals -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
<style>
@media (max-width: 768px) {
    .notice-modal-dialog { margin: 0.5rem; }
    .letterhead-container { padding: 30px 18px !important; min-height: auto !important; }
    .header-logo-section { gap: 8px !important; margin-bottom: 18px !important; padding-bottom: 10px !important; }
    .header-logo-section img { width: 48px !important; height: 48px !important; }
    .uni-title { font-size: 0.98rem !important; }
    .fac-title { font-size: 0.72rem !important; }
    .dept-title { font-size: 0.68rem !important; }
    .meta-section { font-size: 0.72rem !important; margin-bottom: 18px !important; padding-bottom: 6px !important; }
    .subject-line { font-size: 0.82rem !important; margin-bottom: 15px !important; padding-left: 6px !important; }
    .body-content { font-size: 0.78rem !important; line-height: 1.55 !important; margin-bottom: 30px !important; }
    .watermark { width: 200px !important; height: 200px !important; }
    .signatures-section { flex-direction: row !important; flex-wrap: nowrap !important; justify-content: space-between !important; padding-top: 30px !important; }
    .signature-line { width: 100% !important; max-width: 130px !important; font-size: 0.68rem !important; }
    .signature-line .small { font-size: 0.65rem !important; }
    .signature-line .x-small { font-size: 0.58rem !important; }
    .signature-cursive { font-size: 1.15rem !important; top: -22px !important; left: 5px !important; }
    .sign-title { font-size: 0.58rem !important; }
}
</style>
<?php 
$noticesForModal = isset($recentNotices) ? $recentNotices : (isset($notices) ? $notices : []);
$db = \Database::getInstance()->getConnection();
foreach($noticesForModal as $n): 
    $sender_id = $n['sender_id'];
    $stmtC = $db->prepare("SELECT name, department FROM coordinators WHERE user_id = ?");
    $stmtC->execute([$sender_id]);
    $coordUser = $stmtC->fetch();
    $coordName = $coordUser ? $coordUser['name'] : 'Coordinator';
    $coordDept = $coordUser ? $coordUser['department'] : 'Department';

    $stmtH = $db->prepare("SELECT name FROM hods WHERE department = ?");
    $stmtH->execute([$coordDept]);
    $hodUser = $stmtH->fetch();
    $hodName = $hodUser ? $hodUser['name'] : 'Head of Department';
    
    $basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<div class="modal fade" id="noticeModal<?php echo $n['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl notice-modal-dialog">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-body p-0 d-flex justify-content-center position-relative">
                
                <button type="button" class="btn-close shadow-sm position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10; background-color: rgba(255,255,255,0.9); border-radius: 50%; padding: 0.8rem;"></button>

                <div class="letterhead-container w-100" style="background: #fdfcfb; max-width: 820px; padding: 60px 70px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border-radius: 8px; position: relative; min-height: 1060px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; font-family: 'Lora', Georgia, serif; color: #1e293b; text-align: left;">
                    
                    <!-- Watermark -->
                    <div class="watermark" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); width: 380px; height: 380px; opacity: 0.035; pointer-events: none; z-index: 0;">
                        <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Watermark" style="width: 100%;height: 100%;object-fit: contain;filter: grayscale(100%)">
                    </div>

                    <div class="letterhead-content" style="position: relative; z-index: 1;">
                        <div class="header-logo-section" style="border-bottom: 3px double #1e293b; padding-bottom: 20px; margin-bottom: 35px; display: flex; align-items: center; justify-content: center; gap: 20px;">
                            <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Logo" width="80" height="80" style="object-fit: contain">
                            <div class="header-text" style="text-align: left;">
                                <h3 class="uni-title m-0" style="font-family: 'Cinzel', serif; font-size: 1.6rem; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase; color: #0f172a; line-height: 1.2;">University of Sindh</h3>
                                <h5 class="fac-title m-0" style="font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #334155; margin-top: 3px;">Faculty of Engineering & Technology</h5>
                                <h6 class="dept-title m-0" style="font-family: 'Lora', Georgia, serif; font-size: 1.05rem; font-weight: 600; color: #475569; margin-top: 3px;">Department of <?php echo htmlspecialchars($coordDept); ?></h6>
                                <small class="text-muted" style="font-size: 0.78rem;display: block;margin-top: 3px;font-family: sans-serif;letter-spacing: 0.3px">Jamshoro, Sindh, Pakistan</small>
                            </div>
                        </div>

                        <div class="meta-section d-flex justify-content-between align-items-center" style="font-size: 0.95rem; margin-bottom: 40px; color: #334155; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px;">
                            <div>
                                <span class="fw-bold">Ref No:</span> <span style="font-family: monospace; font-size: 1.05rem;"><?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?></span>
                            </div>
                            <div>
                                <span class="fw-bold">Date:</span> <?php echo date('F d, Y', strtotime($n['notice_date'])); ?>
                            </div>
                        </div>

                        <div class="subject-line" style="font-size: 1rem; font-weight: bold; margin-bottom: 20px; color: #0f172a; border-left: 3px solid #1e3a8a; padding-left: 12px;">
                            SUBJECT: <?php echo htmlspecialchars($n['subject']); ?>
                        </div>

                        <div class="body-content" style="font-size: 0.95rem; line-height: 1.8; text-align: justify; white-space: pre-wrap; margin-bottom: 60px; color: #1e293b;">
                            <?php echo htmlspecialchars($n['body']); ?>
                        </div>
                    </div>

                    <div class="signatures-section d-flex justify-content-between align-items-end" style="position: relative; z-index: 1; margin-top: auto; padding-top: 50px;">
                        
                        <div class="signature-box" style="position: relative; display: inline-block; text-align: left;">
                            <div class="signature-cursive" style="font-family: 'Great Vibes', cursive; font-size: 2.1rem; color: #047857; position: absolute; top: -38px; left: 20px; transform: rotate(-3deg); opacity: 0.9; pointer-events: none; letter-spacing: 1px; text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);">
                                <?php echo htmlspecialchars($coordName); ?>
                            </div>
                            <div class="signature-line" style="border-top: 1.5px solid #0f172a; width: 230px; padding-top: 8px; font-size: 0.9rem; font-weight: bold; color: #0f172a;">
                                <div class="small mb-1"><?php echo htmlspecialchars($coordName); ?></div>
                                <div class="sign-title" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: #475569;">FYP Coordinator</div>
                                <div class="text-muted x-small" style="font-size: 0.75rem; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                            </div>
                        </div>

                        <div class="signature-box" style="position: relative; display: inline-block; text-align: left;">
                            <div class="signature-cursive" style="font-family: 'Great Vibes', cursive; font-size: 2.1rem; color: #047857; position: absolute; top: -38px; left: 20px; transform: rotate(-3deg); opacity: 0.9; pointer-events: none; letter-spacing: 1px; text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);">
                                <?php echo htmlspecialchars($hodName); ?>
                            </div>
                            <div class="signature-line" style="border-top: 1.5px solid #0f172a; width: 230px; padding-top: 8px; font-size: 0.9rem; font-weight: bold; color: #0f172a;">
                                <div class="small mb-1"><?php echo htmlspecialchars($hodName); ?></div>
                                <div class="sign-title" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: #475569;">Chairperson</div>
                                <div class="text-muted x-small" style="font-size: 0.75rem; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>








