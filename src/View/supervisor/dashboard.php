<!-- Supervisor Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$fullName = trim($_SESSION['name'] ?? 'Supervisor');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>



<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    Welcome Back
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    <?php echo htmlspecialchars($fullName); ?>
                </h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7);font-size: 0.85rem">Manage your assigned groups and track their progress</p>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <a href="<?php echo $basePath; ?>/supervisor/groups" class="text-decoration-none">
                <div class="page-stat-pill" style="transition: transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-white"><?php echo htmlspecialchars((string)($groupCount), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="stat-label text-white">Assigned Groups</span>
                </div>
            </a>
            <a href="<?php echo $basePath; ?>/supervisor/reviews" class="text-decoration-none">
                <div class="page-stat-pill" style="margin-right: 0;transition: transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-warning"><?php echo htmlspecialchars((string)($pendingProposals), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="stat-label text-white">Pending Proposals</span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- ── Recent Notices ── -->
<div class="card border-0 p-3 p-md-4 mb-4">
    <div class="section-title mb-4">
        <i class="bi bi-megaphone-fill text-primary"></i> Recent Notices
    </div>

            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
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
            <div class="d-md-none p-3 pb-4">
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



<!-- Table Card -->
<div class="card border-0 p-3 p-md-4 h-100 mb-4">
    <div class="section-title mb-4">
        <i class="bi bi-person-video3 text-primary me-2"></i> Your Assigned FYP Groups
    </div>
    
    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none">
            <thead style="background: var(--table-header-bg)">
                <tr>
                    <th class="py-3 px-3 border-0 rounded-start text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Group Code</th>
                    <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Project Title</th>
                    <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Project Status</th>
                    <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Current FYP Stage</th>
                    <th class="py-3 px-3 border-0 rounded-end text-end text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr style="transition: background-color 0.2s">
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <span class="fw-bold" style="color: #10b981;font-size: 0.9rem"><?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?></span>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <div class="fw-semibold text-truncate" style="max-width: 350px;color: var(--text-primary);font-size: 0.9rem" title="<?php echo htmlspecialchars($g['project_title']); ?>">
                            <?php echo htmlspecialchars($g['project_title']); ?>
                        </div>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <?php if($g['project_status'] === 'Approved'): ?>
                            <span style="font-size: 0.7rem;padding: 4px 10px;border-radius: 20px;background: rgba(16,185,129,0.1);color: #059669;font-weight: 600">Approved</span>
                        <?php elseif($g['project_status'] === 'Submitted'): ?>
                            <span style="font-size: 0.7rem;padding: 4px 10px;border-radius: 20px;background: rgba(245,158,11,0.1);color: #d97706;font-weight: 600">Submitted</span>
                        <?php else: ?>
                            <span style="font-size: 0.7rem;padding: 4px 10px;border-radius: 20px;background: var(--form-bg);color: var(--text-secondary);font-weight: 600;border: 1px solid var(--border-color)"><?php echo htmlspecialchars($g['project_status']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px;height: 8px;border-radius: 50%;background: #10b981;flex-shrink: 0;box-shadow: 0 0 0 3px rgba(16,185,129,0.15)"></span>
                            <span style="font-size: 0.8rem;font-weight: 600;color: var(--text-primary);line-height: 1.3">
                                <?php echo htmlspecialchars($g['progress_stage']); ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-3 py-3 border-bottom text-end" style="border-color: var(--border-color) !important">
                        <a href="<?php echo $basePath; ?>/supervisor/groups" class="btn btn-sm px-3 rounded-pill fw-semibold" style="font-size: 0.75rem;background: var(--form-bg);color: var(--text-primary);border: 1px solid var(--border-color);transition: all 0.2s" onmouseover="this.style.background='var(--primary-color)'; this.style.color='#fff'; this.style.borderColor='var(--primary-color)';" onmouseout="this.style.background='var(--form-bg)'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)';">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div style="font-size: 2.5rem;color: var(--border-color);margin-bottom: 1rem"><i class="bi bi-people"></i></div>
                            <h6 class="fw-bold" style="color: var(--text-primary)">No Assigned Groups</h6>
                            <p class="small text-muted mb-0">You have no student groups assigned to you yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card List -->
    <div class="d-block d-md-none mt-3">
        <?php foreach($groups as $g): ?>
            <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg)">
                <div class="mb-2 d-flex align-items-center gap-2">
                    <span class="fw-bold" style="color: #10b981;font-size: 0.75rem;background: rgba(16,185,129,0.1);padding: 3px 6px;border-radius: 4px; border: 1px solid rgba(16,185,129,0.2);">
                        <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?>
                    </span>
                    <?php if($g['project_status'] === 'Approved'): ?>
                        <span style="font-size: 0.65rem;padding: 3px 8px;border-radius: 20px;background: rgba(16,185,129,0.1);color: #059669;font-weight: 600">Approved</span>
                    <?php elseif($g['project_status'] === 'Submitted'): ?>
                        <span style="font-size: 0.65rem;padding: 3px 8px;border-radius: 20px;background: rgba(245,158,11,0.1);color: #d97706;font-weight: 600">Submitted</span>
                    <?php else: ?>
                        <span style="font-size: 0.65rem;padding: 3px 8px;border-radius: 20px;background: var(--form-bg);color: var(--text-secondary);font-weight: 600;border: 1px solid var(--border-color)"><?php echo htmlspecialchars($g['project_status']); ?></span>
                    <?php endif; ?>
                </div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden; color: var(--text-primary) !important;">
                    <?php echo htmlspecialchars($g['project_title']); ?>
                </h6>
                <div class="mb-3">
                    <span style="font-size: 0.65rem;background: rgba(16,185,129,0.1);color: #059669;padding: 4px 10px;border-radius: 20px;font-weight: 700;text-transform: uppercase;display: inline-block">
                        <?php echo htmlspecialchars($g['progress_stage']); ?>
                    </span>
                </div>
                <div class="d-flex justify-content-end align-items-center mt-2 pt-3 border-top" style="border-color: var(--border-color) !important">
                    <a href="<?php echo $basePath; ?>/supervisor/groups" class="btn btn-sm px-3 rounded-pill fw-semibold" style="font-size: 0.75rem;background: rgba(16,185,129,0.1);color: #10b981;border: none;transition: all 0.2s">
                        <i class="bi bi-arrow-right-circle me-1"></i>View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($groups)): ?>
            <div class="text-center text-muted py-4 rounded-3 small" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                No project groups assigned to you yet.
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

                        <div class="subject-line" style="font-size: 1.15rem; font-weight: bold; margin-bottom: 30px; color: #0f172a; border-left: 3px solid #1e3a8a; padding-left: 12px;">
                            SUBJECT: <?php echo htmlspecialchars($n['subject']); ?>
                        </div>

                        <div class="body-content" style="font-size: 1.05rem; line-height: 1.8; text-align: justify; white-space: pre-wrap; margin-bottom: 60px; color: #1e293b;">
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