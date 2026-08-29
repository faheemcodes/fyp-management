<!-- Supervisor Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$fullName = trim($_SESSION['name'] ?? 'Supervisor');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>



<!-- Top Hero Banner -->
<div class="page-hero">
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
</div>

<!-- -- Premium Stat Cards Row -- -->
<div class="row g-3 mb-4">
    <!-- Assigned Groups Card -->
    <div class="col-xl-4 col-md-6">
        <a href="<?php echo $basePath; ?>/supervisor/groups" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-amber">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-amber">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($groupCount), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Assigned Groups</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Review Proposals Card -->
    <div class="col-xl-4 col-md-6">
        <a href="<?php echo $basePath; ?>/supervisor/reviews" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-blue">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-blue">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($pendingProposals), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Review Proposals</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Meetings Card -->
    <div class="col-xl-4 col-md-12">
        <a href="<?php echo $basePath; ?>/supervisor/meetings" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-purple">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-purple">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($meetingsCount ?? 0), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Scheduled Meetings</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- -- Recent Notices -- -->
    <div class="col-xl-4">
        <div class="card border-0 p-3 p-md-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(59, 130, 246, 0.1);color: #3b82f6">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <h6>Recent Notices</h6>
                    <small>View latest announcements and updates</small>
                </div>
            </div>
            <div class="custom-table-scroll" style="max-height: 320px; overflow-y: auto; padding-right: 8px;">
                <?php foreach($recentNotices as $n): ?>
                <div class="d-flex align-items-start py-3" style="border-bottom: 1px solid var(--border-color);">
                    <div class="flex-grow-1 min-w-0" style="min-width: 0;">
                        <div class="fw-semibold text-truncate pe-2" title="<?php echo htmlspecialchars($n['subject']); ?>" style="font-size: 0.9rem; color: var(--text-primary);">
                            <?php echo htmlspecialchars($n['subject']); ?>
                        </div>
                        <div class="mt-1" style="font-size: 0.8rem; color: var(--text-secondary);">
                            <i class="bi bi-calendar3 me-1 opacity-75"></i> <?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                        </div>
                    </div>
                    <div class="ms-2 align-self-center">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="btn btn-link text-decoration-none fw-bold p-0" style="color: #10b981; font-size: 0.85rem;">View</button>
                    </div>
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
    </div>



    <!-- -- Your Assigned FYP Groups -- -->
    <div class="col-xl-8">
        <div class="card border-0 p-3 p-md-4 h-100">
            <div class="page-section-header mb-4 position-relative d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-section-icon" style="background: rgba(16, 185, 129, 0.1);color: #10b981">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Your Assigned FYP Groups</h6>
                        <small>Track your students' progress</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/supervisor/groups" class="btn btn-sm rounded-pill px-4 fw-bold shadow-sm" style="font-size: 0.8rem; background: #10b981; color: #fff; border: none;">
                    View Details
                </a>
            </div>
            
            <div class="d-none d-md-block table-responsive custom-table-scroll" style="max-height: 320px; overflow-y: auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-3 border-0 text-uppercase rounded-start" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Group Code</th>
                            <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Project Title</th>
                            <th class="py-3 px-3 border-0 text-uppercase rounded-end" style="font-size: 0.75rem;font-weight: 600;color: var(--text-secondary);letter-spacing: 0.05em">Team Members</th>
                        </tr>
                    </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr style="transition: background-color 0.2s">
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <span style="color: #10b981; font-size: 0.75rem; font-weight: 500; background: rgba(16, 185, 129, 0.1); padding: 3px 10px; border-radius: 8px; letter-spacing: 0.5px;"><?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?></span>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <div class="fw-semibold text-truncate" style="max-width: 350px;color: var(--text-primary);font-size: 0.9rem" title="<?php echo htmlspecialchars($g['project_title']); ?>">
                            <?php echo htmlspecialchars($g['project_title']); ?>
                        </div>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important">
                        <div class="d-flex align-items-center">
                            <?php if (!empty($g['members'])): ?>
                                <?php $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger']; ?>
                                <?php foreach (array_slice($g['members'], 0, 3) as $index => $m): 
                                    $initials = strtoupper(substr($m['name'] ?? 'U', 0, 1));
                                    $colorClass = $colors[$index % count($colors)];
                                    $avatarPath = !empty($m['avatar']) ? $basePath . '/uploads/avatars/' . $m['avatar'] : null;
                                ?>
                                    <?php if ($avatarPath && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                        <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; margin-left: <?php echo $index > 0 ? '-10px' : '0'; ?>; overflow: hidden;"
                                             title="<?php echo htmlspecialchars($m['name']); ?>">
                                            <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="<?php echo htmlspecialchars($m['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    <?php else: ?>
                                        <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-white <?php echo $colorClass; ?>" 
                                             style="width: 32px; height: 32px; margin-left: <?php echo $index > 0 ? '-10px' : '0'; ?>; font-size: 0.75rem; font-weight: 600;"
                                             title="<?php echo htmlspecialchars($m['name']); ?>">
                                            <?php echo $initials; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (count($g['members']) > 3): ?>
                                    <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center bg-secondary text-white" 
                                         style="width: 32px; height: 32px; margin-left: -10px; font-size: 0.7rem; font-weight: 600;"
                                         title="<?php echo (count($g['members']) - 3); ?> more">
                                        +<?php echo (count($g['members']) - 3); ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">No members</span>
                            <?php endif; ?>
                        </div>
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
                        <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                            <span style="color: #10b981; font-size: 0.75rem; font-weight: 500; background: rgba(16, 185, 129, 0.1); padding: 3px 10px; border-radius: 8px; letter-spacing: 0.5px;">
                                <?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?>
                            </span>
                            
                            <div class="d-flex align-items-center">
                                <?php if (!empty($g['members'])): ?>
                                    <?php $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger']; ?>
                                    <?php foreach (array_slice($g['members'], 0, 3) as $index => $m): 
                                        $initials = strtoupper(substr($m['name'] ?? 'U', 0, 1));
                                        $colorClass = $colors[$index % count($colors)];
                                        $avatarPath = !empty($m['avatar']) ? $basePath . '/uploads/avatars/' . $m['avatar'] : null;
                                    ?>
                                        <?php if ($avatarPath && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
                                            <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center" 
                                                 style="width: 24px; height: 24px; margin-left: <?php echo $index > 0 ? '-8px' : '0'; ?>; overflow: hidden;"
                                                 title="<?php echo htmlspecialchars($m['name']); ?>">
                                                <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="<?php echo htmlspecialchars($m['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        <?php else: ?>
                                            <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center text-white <?php echo $colorClass; ?>" 
                                                 style="width: 24px; height: 24px; margin-left: <?php echo $index > 0 ? '-8px' : '0'; ?>; font-size: 0.6rem; font-weight: 600;"
                                                 title="<?php echo htmlspecialchars($m['name']); ?>">
                                                <?php echo $initials; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (count($g['members']) > 3): ?>
                                        <div class="rounded-circle shadow-sm border border-2 border-white d-flex align-items-center justify-content-center bg-secondary text-white" 
                                             style="width: 24px; height: 24px; margin-left: -8px; font-size: 0.55rem; font-weight: 600;"
                                             title="<?php echo (count($g['members']) - 3); ?> more">
                                            +<?php echo (count($g['members']) - 3); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-2 mt-2" style="font-size: 0.95rem;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden; color: var(--text-primary) !important;">
                            <?php echo htmlspecialchars($g['project_title']); ?>
                        </h6>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <div class="text-center text-muted py-4 rounded-3 small" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                        No project groups assigned to you yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
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