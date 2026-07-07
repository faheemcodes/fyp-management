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
    'Approved' => ['rgba(59,130,246,0.1)',   '#2563eb'],
    'Rejected' => ['rgba(239,68,68,0.1)',    '#dc2626'],
];
$st = $group['project_status'] ?? 'Pending';
$sc = $statusColors[$st] ?? ['rgba(107,114,128,0.1)', '#6b7280'];
?>

<style>
/* ── Horizontal Stepper ── */
.h-stepper {
    display: flex;
    align-items: flex-start;
    position: relative;
    padding: 10px 0;
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(59,130,246,0.3) transparent;
}
.h-stepper::-webkit-scrollbar { height: 4px; }
.h-stepper::-webkit-scrollbar-thumb { background: rgba(59,130,246,0.25); border-radius: 4px; }

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

/* ─── Hero Banner Styles ─── */
.group-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.group-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: 10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(16,185,129,0.05) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: 0 4px 15px rgba(59,130,246,0.15);
    flex-shrink: 0;
}
.group-stat-pill {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 12px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 120px;
    backdrop-filter: blur(10px);
    margin-right: 12px;
}
.group-stat-pill .stat-num {
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 4px;
}
.group-stat-pill .stat-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: rgba(255,255,255,0.6);
    font-weight: 600;
}
@media (max-width: 768px) {
    .group-hero { padding: 24px 16px; }
    .group-stat-pill { margin-bottom: 10px; min-width: calc(50% - 12px); }
    .hero-stats-container { flex-wrap: wrap; justify-content: center; margin-top: 20px; }
}
</style>

<!-- ── Top Hero Banner ── -->
<div class="group-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Final Year Project Portal
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                    <?php echo htmlspecialchars($group['project_title']); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 justify-content-center justify-content-md-start flex-wrap">
                    <span style="font-size: 0.75rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                        Group Code: <?php echo htmlspecialchars($group['group_code'] ?? 'Pending'); ?>
                    </span>
                    <span style="font-size: 0.75rem; background: <?php echo $sc[0]; ?>; color: <?php echo $sc[1]; ?>; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                        <?php echo htmlspecialchars($st); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <a href="#supervisor-section" class="text-decoration-none">
                <div class="group-stat-pill" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-info">
                        <?php if($group['supervisor_name']): ?>
                            <i class="bi bi-person-check-fill" style="font-size: 1.2rem;"></i>
                        <?php else: ?>
                            <i class="bi bi-person-x-fill" style="font-size: 1.2rem; color: var(--text-secondary);"></i>
                        <?php endif; ?>
                    </span>
                    <span class="stat-label text-white">Supervisor</span>
                </div>
            </a>
            <a href="#progress-section" class="text-decoration-none">
                <div class="group-stat-pill" style="margin-right: 0; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-primary">
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
            <div class="section-title"><i class="bi bi-file-text-fill"></i> Project Abstract</div>
            <?php
            $desc = $group['project_description'] ?? '';
            if ($desc): 
            ?>
                <div id="abstractText" style="font-size: 0.875rem; line-height: 1.75; text-align: justify; color: var(--text-secondary); display: -webkit-box; -webkit-line-clamp: 6; -webkit-box-orient: vertical; overflow: hidden; transition: all 0.3s ease;">
                    <?php echo nl2br(htmlspecialchars($desc)); ?>
                </div>
                <button class="btn btn-link p-0 mt-2 text-decoration-none fw-semibold" id="toggleAbstractBtn" style="font-size: 0.8rem; display: none;" onclick="toggleAbstract()">
                    Show more <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                </button>
            <?php else: ?>
                <p class="text-muted mb-0" style="font-size: 0.875rem; line-height: 1.75; text-align: justify;">
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
                    toggleBtn.innerHTML = 'Show less <i class="bi bi-chevron-up ms-1" style="font-size: 0.7rem;"></i>';
                } else {
                    abstractContainer.style.webkitLineClamp = '6';
                    toggleBtn.innerHTML = 'Show more <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem;"></i>';
                }
            };
        }
    });
    </script>

    <!-- Deadlines -->
    <div class="col-lg-4" id="deadlines-section">
        <div class="card border-0 p-4 h-100">
            <div class="section-title"><i class="bi bi-calendar-event-fill"></i> Upcoming Deadlines</div>
            <?php if (empty($deadlines)): ?>
                <div class="text-center py-3">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 1.8rem; opacity: 0.3;"></i>
                    <p class="text-muted mb-0 mt-2" style="font-size: 0.82rem;">No deadlines published yet.</p>
                </div>
            <?php else: ?>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach ($deadlines as $i => $dl):
                        $isPast = strtotime($dl['deadline_date']) < time();
                        $isLast = ($i === count($deadlines) - 1);
                    ?>
                        <li class="d-flex align-items-start gap-3 <?php echo !$isLast ? 'pb-3 mb-3 border-bottom' : ''; ?>">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: <?php echo $isPast ? '#dc2626' : '#059669'; ?>; margin-top: 5px; flex-shrink: 0;"></div>
                            <div style="flex: 1; min-width: 0;">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="fw-semibold text-truncate" style="font-size: 0.82rem;"><?php echo htmlspecialchars($dl['stage']); ?></span>
                                    <span style="font-size: 0.62rem; padding: 2px 7px; border-radius: 20px; white-space: nowrap; background: <?php echo $isPast ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)'; ?>; color: <?php echo $isPast ? '#dc2626' : '#059669'; ?>; font-weight: 600;">
                                        <?php echo $isPast ? 'Closed' : 'Open'; ?>
                                    </span>
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.72rem;">
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

<!-- ── Horizontal Progress Stepper (Full Width) ── -->
<div class="card border-0 p-4" id="progress-section">
    <div class="section-title mb-4"><i class="bi bi-map-fill"></i> Your FYP Progress Path</div>
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
                    <i class="bi bi-check-lg" style="font-size: 0.8rem;"></i>
                <?php elseif ($cls === 'active'): ?>
                    <i class="bi bi-arrow-right" style="font-size: 0.75rem;"></i>
                <?php else: ?>
                    <?php echo $index + 1; ?>
                <?php endif; ?>
            </div>
            <div class="h-step-label"><?php echo htmlspecialchars($displayName); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="d-flex align-items-center gap-4 mt-3 pt-3 border-top flex-wrap" style="font-size: 0.75rem;">
        <span class="d-flex align-items-center gap-2">
            <span style="width: 10px; height: 10px; border-radius: 50%; background: #059669; display: inline-block;"></span>
            <span class="text-muted">Completed</span>
        </span>
        <span class="d-flex align-items-center gap-2">
            <span style="width: 10px; height: 10px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
            <span class="text-muted">Current Stage</span>
        </span>
        <span class="d-flex align-items-center gap-2">
            <span style="width: 10px; height: 10px; border-radius: 50%; background: var(--border-color); display: inline-block;"></span>
            <span class="text-muted">Upcoming</span>
        </span>
        <span class="ms-auto fw-semibold" style="color: #3b82f6;">
            <?php echo $currentIdx + 1; ?> of <?php echo count($stagesList); ?> stages complete
        </span>
    </div>
</div>

<?php endif; ?>
