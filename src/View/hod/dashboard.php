
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

            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block custom-table-scroll" style="max-height: 230px; overflow-y: auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Ref No.</th>
                            <th>Subject</th>
                            <th>Date</th>
                            
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentNotices as $n): ?>
                        <tr>
                            <td>
                                <span class="fw-semibold text-secondary" style="font-family: monospace;font-size: 0.8rem;background: var(--form-bg);padding: 4px 8px;border-radius: 6px;border: 1px solid var(--border-color)">
                                    <?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-wrap" title="<?php echo htmlspecialchars($n['subject']); ?>" style="font-size: 0.85rem;max-width: 400px;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden; color: var(--text-primary)">
                                    <?php echo htmlspecialchars($n['subject']); ?>
                                </div>
                            </td>
                            <td style="white-space: nowrap">
                                <span style="font-size: 0.8rem;color: var(--text-secondary)">
                                    <i class="bi bi-calendar-event me-1"></i><?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                                </span>
                            </td>

                            <td class="text-end">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="btn btn-sm text-primary" style="background: rgba(16,185,129,0.1);border-radius: 8px;font-weight: 600;font-size: 0.75rem;padding: 6px 12px; border: none;"><i class="bi bi-eye-fill me-1"></i>View</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($recentNotices)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                                    No recent notices found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards View -->
            <div class="d-md-none p-3 pb-4" style="max-height: 350px; overflow-y: auto;">
                <?php foreach($recentNotices as $n): ?>
                <div class="mb-3 p-3 shadow-sm" style="background: var(--form-bg);border-radius: 16px;border: 1px solid var(--border-color);transition: transform 0.2s">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold" style="font-family: monospace;font-size: 0.75rem;color: var(--text-secondary);background: rgba(0,0,0,0.05);padding: 4px 8px;border-radius: 6px">
                            <i class="bi bi-hash me-1"></i><?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <h6 class="fw-bold mb-3 lh-base" style="font-size: 0.85rem; color: var(--text-primary)">
                        <?php echo htmlspecialchars($n['subject']); ?>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2" style="border-top: 1px solid var(--border-color)">
                        <span class="fw-semibold" style="font-size: 0.75rem;color: var(--text-secondary)">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                        </span>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="btn btn-sm btn-primary rounded-pill px-4 py-1 fw-bold shadow-sm" style="font-size: 0.75rem; border: none;">View</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($recentNotices)): ?>
                    <div class="text-center text-muted py-4">
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








