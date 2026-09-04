<style>
/* ── Horizontal Stepper ── */
.h-stepper {
    display: flex;
    align-items: flex-start;
    position: relative;
    padding: 10px 0;
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(16,185,129,0.3) transparent;
}
.h-stepper::-webkit-scrollbar { height: 4px; }
.h-stepper::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.25); border-radius: 4px; }

.h-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    min-width: 90px;
    position: relative;
    text-align: center;
}

/* connector line between steps */
.h-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 15px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: var(--border-color);
    z-index: 0;
    transition: background 0.3s;
}
.h-step.completed:not(:last-child)::after {
    background: linear-gradient(90deg, #059669, #0d9488);
}
.h-step.active:not(:last-child)::after {
    background: linear-gradient(90deg, #3b82f6 0%, var(--border-color) 100%);
}

.h-step-dot {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 1;
    flex-shrink: 0;
    border: 2px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    transition: all 0.3s ease;
}
.h-step.completed .h-step-dot {
    background: #059669;
    border-color: #059669;
    color: #fff;
    box-shadow: 0 0 0 4px rgba(5,150,105,0.12);
}

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

.h-step.active .h-step-dot {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
    box-shadow: 0 0 0 5px rgba(59,130,246,0.18);
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 0 5px rgba(59,130,246,0.18); }
    50%       { box-shadow: 0 0 0 8px rgba(59,130,246,0.08); }
}

.h-step-label {
    margin-top: 8px;
    font-size: 0.68rem;
    font-weight: 500;
    color: var(--text-secondary);
    line-height: 1.3;
    max-width: 80px;
    word-break: break-word;
}
.h-step.completed .h-step-label { color: #059669; font-weight: 600; }
.h-step.active    .h-step-label { color: #3b82f6; font-weight: 700; }









@media (max-width: 768px) {
}
</style>
<!-- Student Dashboard -->
<?php if (!$group): ?>
    <div class="card border-0 text-center">
        <div class="empty-state">
            <div class="empty-icon text-primary"><i class="bi bi-people-fill"></i></div>
            <h5>You're Not in an FYP Group Yet</h5>
            <p>To begin your Final Year Project journey, submit your project proposal — it will automatically create your group.</p>
            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/student/proposal" class="btn btn-primary px-4 py-2 rounded-3">
                <i class="bi bi-file-earmark-plus-fill me-2"></i>Submit Project Proposal
            </a>
        </div>
    </div>
<?php else: ?>

<?php
$stagesList = [
    'Account Created',
    'Group Created',
    'Proposal Submitted',
    'Proposal Approved',
    'Proposal Defence Presentation Completed',
    'FYP Progress Presentation Completed',
    'Final Presentation Completed',
    'Final Grading Completed'
];
$currentIdx = array_search($group['progress_stage'], $stagesList);
if ($currentIdx === false) $currentIdx = 1;

$statusColors = [
    'Active'   => ['rgba(16,185,129,0.1)',  '#059669'],
    'Pending'  => ['rgba(245,158,11,0.1)',   '#d97706'],
    'Approved' => ['rgba(16,185,129,0.1)',   '#059669'],
    'Rejected' => ['rgba(239,68,68,0.1)',    '#dc2626'],
];
$st = $group['project_status'] ?? 'Pending';
$sc = $statusColors[$st] ?? ['rgba(107,114,128,0.1)', '#6b7280'];
?>



<?php if (isset($isBatchActive) && !$isBatchActive): ?>
<div class="alert border-0 rounded-4 shadow-sm mb-4 p-3 d-flex align-items-center gap-3" style="background: rgba(59, 130, 246, 0.08); border-left: 4px solid #3b82f6 !important; color: #1e40af;">
    <i class="bi bi-info-circle-fill fs-3 text-primary"></i>
    <div>
        <strong class="d-block text-dark" style="font-size: 0.95rem;">Session Concluded &bull; View-Only Access (<?php echo htmlspecialchars($group['batch_name'] ?? 'Previous Session'); ?>)</strong>
        <span style="font-size: 0.85rem; color: #1e3a8a;">Your final year project term has ended. You have continuous access to view and download your project proposal, thesis document, and evaluation grades. Active submissions, supervisor chat, and meeting requests are no longer available.</span>
    </div>
</div>
<?php endif; ?>

<!-- ── Top Hero Banner ── -->
<div class="page-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <?php 
            $heroAvatar = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : '';
            $heroBasePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
            $heroAvatarPath = $_SERVER['DOCUMENT_ROOT'] . $heroBasePath . '/uploads/avatars/' . $heroAvatar;
            $heroHasAvatar = !empty($heroAvatar) && file_exists($heroAvatarPath);
            $heroInitial = strtoupper(substr(trim($_SESSION['name'] ?? 'S'), 0, 1));
            ?>
            <div class="page-hero-icon page-hero-avatar" style="padding: 0; overflow: hidden;">
                <?php if($heroHasAvatar): ?>
                    <img src="<?php echo htmlspecialchars($heroBasePath, ENT_QUOTES, 'UTF-8'); ?>/uploads/avatars/<?php echo htmlspecialchars($heroAvatar, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                <?php else: ?>
                    <span style="font-size: 1.6rem; font-weight: 700; font-style: normal;"><?php echo htmlspecialchars($heroInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    Final Year Project Portal
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    <?php echo htmlspecialchars($group['project_title']); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 justify-content-center justify-content-md-start flex-wrap">
                    <span style="font-size: 0.75rem;background: rgba(255,255,255,0.1);color: rgba(255,255,255,0.8);padding: 4px 12px;border-radius: 20px;font-weight: 600">
                        Group Code: <?php echo htmlspecialchars($group['group_code'] ?? 'Pending'); ?>
                    </span>
                    <span style="font-size: 0.75rem;background: <?php echo htmlspecialchars((string)($sc[0]), ENT_QUOTES, 'UTF-8');?>;color: <?php echo htmlspecialchars((string)($sc[1]), ENT_QUOTES, 'UTF-8');?>;padding: 4px 12px;border-radius: 20px;font-weight: 600">
                        <?php echo htmlspecialchars($st); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <a href="#supervisor-section" class="text-decoration-none">
                <div class="page-stat-pill" style="transition: transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-white">
                        <?php if($group['supervisor_name']): ?>
                            <i class="bi bi-person-check-fill" style="font-size: 1.2rem"></i>
                        <?php else: ?>
                            <i class="bi bi-person-x-fill" style="font-size: 1.2rem;color: var(--text-secondary)"></i>
                        <?php endif; ?>
                    </span>
                    <span class="stat-label text-white">Supervisor</span>
                </div>
            </a>
            <a href="#progress-section" class="text-decoration-none">
                <div class="page-stat-pill" style="margin-right: 0;transition: transform 0.2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-white">
                        <?php echo round((($currentIdx) / (count($stagesList) - 1)) * 100); ?>%
                    </span>
                    <span class="stat-label text-white">Progress</span>
                </div>
            </a>
        </div>
    </div>
</div>

<?php $bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>



<!-- ── Main Content Row ── -->
<div class="row g-4 mb-4">
    <!-- Project Abstract -->
    <div class="col-lg-8">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(13,148,136,0.1);color: #0d9488">
                    <i class="bi bi-file-text-fill"></i>
                </div>
                <div>
                    <h6>Project Abstract</h6>
                    <small>Summary of your final year project</small>
                </div>
            </div>
            <?php
            $desc = $group['project_description'] ?? '';
            if ($desc): 
            ?>
                <div id="abstractText" style="font-size: 0.875rem;line-height: 1.75;text-align: justify;color: var(--text-secondary);display: -webkit-box;-webkit-line-clamp: 6;-webkit-box-orient: vertical;overflow: hidden;transition: all 0.3s ease">
                    <?php echo nl2br(htmlspecialchars($desc)); ?>
                </div>
                <button class="btn btn-link p-0 mt-2 text-decoration-none fw-semibold" id="toggleAbstractBtn" style="font-size: 0.8rem;display: none" onclick="toggleAbstract()">
                    Show more <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem"></i>
                </button>
            <?php else: ?>
                <p class="text-muted mb-0" style="font-size: 0.875rem;line-height: 1.75;text-align: justify">
                    <em>No project description has been added yet.</em>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const abstractContainer = document.getElementById('abstractText');
        const toggleBtn = document.getElementById('toggleAbstractBtn');
        
        if (abstractContainer && toggleBtn) {
            // Check if the text actually overflows the 6 lines
            if (abstractContainer.scrollHeight > abstractContainer.clientHeight || abstractContainer.scrollHeight > 150) {
                toggleBtn.style.display = 'inline-block';
            }

            window.toggleAbstract = function() {
                if (abstractContainer.style.webkitLineClamp === '6') {
                    abstractContainer.style.webkitLineClamp = 'unset';
                    toggleBtn.innerHTML = 'Show less <i class="bi bi-chevron-up ms-1" style="font-size: 0.7rem"></i>';
                } else {
                    abstractContainer.style.webkitLineClamp = '6';
                    toggleBtn.innerHTML = 'Show more <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem"></i>';
                }
            };
        }
    });
    </script>

    <!-- Deadlines -->
    <div class="col-lg-4" id="deadlines-section">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(239,68,68,0.1);color: #dc2626">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div>
                    <h6>Upcoming Deadlines</h6>
                    <small>Important dates for your project</small>
                </div>
            </div>
            <?php if (empty($deadlines)): ?>
                <div class="text-center py-3">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 1.8rem;opacity: 0.3"></i>
                    <p class="text-muted mb-0 mt-2" style="font-size: 0.82rem">No deadlines published yet.</p>
                </div>
            <?php else: ?>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach ($deadlines as $i => $dl):
                        $isPast = strtotime($dl['deadline_date']) < time();
                        $isLast = ($i === count($deadlines) - 1);
                    ?>
                        <li class="d-flex align-items-start gap-3 <?php echo !$isLast ? 'pb-3 mb-3 border-bottom' : ''; ?>">
                            <div style="width: 8px;height: 8px;border-radius: 50%;background: <?php echo $isPast ? '#dc2626' : '#059669';?>;margin-top: 5px;flex-shrink: 0"></div>
                            <div style="flex: 1;min-width: 0">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="fw-semibold text-truncate" style="font-size: 0.82rem"><?php echo htmlspecialchars($dl['stage']); ?></span>
                                    <span style="font-size: 0.62rem;padding: 2px 7px;border-radius: 20px;white-space: nowrap;background: <?php echo $isPast ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)';?>;color: <?php echo $isPast ? '#dc2626' : '#059669';?>;font-weight: 600">
                                        <?php echo $isPast ? 'Closed' : 'Open'; ?>
                                    </span>
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.72rem">
                                    <i class="bi bi-clock me-1"></i><?php echo date('M d, Y — h:i A', strtotime($dl['deadline_date'])); ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- ── Notices & Progress Row ── -->
<div class="row g-4 mb-4">
    
    <!-- ── Recent Notices (col-lg-4) ── -->
    <div class="col-lg-4">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(59,130,246,0.1);color: #3b82f6">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <h6>Recent Notices</h6>
                    <small>Latest announcements and updates</small>
                </div>
            </div>

            <div class="notice-list custom-scroll" style="max-height: 220px; overflow-y: auto;">
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
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                    No recent notices found.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── FYP Progress Path (col-lg-8) ── -->
    <div class="col-lg-8">
        <div class="card border-0 p-4 h-100" id="progress-section">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(99,102,241,0.1);color: #6366f1">
                    <i class="bi bi-map-fill"></i>
                </div>
                <div>
                    <h6>Your FYP Progress Path</h6>
                    <small>Track your project milestones</small>
                </div>
            </div>
            <div class="h-stepper pb-2">
                <?php foreach ($stagesList as $index => $stageName):
                    if ($index <= $currentIdx) {
                        $cls = 'completed';
                    } elseif ($index == $currentIdx + 1) {
                        $cls = 'active';
                    } else {
                        $cls = '';
                    }
                    
                    // Remove 'Completed' for upcoming/active stages so it makes sense grammatically
                    $displayName = $stageName;
                    if ($cls !== 'completed') {
                        $displayName = str_replace(' Completed', '', $stageName);
                    }
                ?>
                <div class="h-step <?php echo $cls; ?>">
                    <div class="h-step-dot">
                        <?php if ($cls === 'completed'): ?>
                            <i class="bi bi-check-lg" style="font-size: 0.8rem"></i>
                        <?php elseif ($cls === 'active'): ?>
                            <i class="bi bi-arrow-right" style="font-size: 0.75rem"></i>
                        <?php else: ?>
                            <?php echo $index + 1; ?>
                        <?php endif; ?>
                    </div>
                    <div class="h-step-label"><?php echo htmlspecialchars($displayName); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex align-items-center gap-4 mt-3 pt-3 border-top flex-wrap" style="font-size: 0.75rem">
                <span class="d-flex align-items-center gap-2">
                    <span style="width: 10px;height: 10px;border-radius: 50%;background: #059669;display: inline-block"></span>
                    <span class="text-muted">Completed</span>
                </span>
                <span class="d-flex align-items-center gap-2">
                    <span style="width: 10px;height: 10px;border-radius: 50%;background: #3b82f6;display: inline-block"></span>
                    <span class="text-muted">Current Stage</span>
                </span>
                <span class="d-flex align-items-center gap-2">
                    <span style="width: 10px;height: 10px;border-radius: 50%;background: var(--border-color);display: inline-block"></span>
                    <span class="text-muted">Upcoming</span>
                </span>
                <span class="ms-auto fw-semibold" style="color: #10b981">
                    <?php echo $currentIdx + 1; ?> of <?php echo count($stagesList); ?> stages complete
                </span>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>


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