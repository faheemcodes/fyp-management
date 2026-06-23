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

/* ── Welcome banner gradient ── */
.dash-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f2a4a 100%);
    border-radius: 16px;
    padding: 28px 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.dash-banner::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: radial-gradient(circle, rgba(59,130,246,0.18) 0%, transparent 70%);
}
.dash-banner::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 20%;
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
}

/* ── Stat mini cards ── */
.stat-mini {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 16px 18px;
    box-shadow: var(--card-shadow);
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(59,130,246,0.1);
}
.stat-mini-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.stat-mini-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    margin-bottom: 3px;
}
.stat-mini-value {
    font-size: 0.88rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--text-primary);
}
</style>

<!-- ── Welcome Banner ── -->
<div class="dash-banner">
    <div class="position-relative" style="z-index: 1;">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <p class="mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.45);">
                    <i class="bi bi-mortarboard me-1"></i>Final Year Project Portal
                </p>
                <h4 class="fw-bold text-white mb-1" style="font-size: 1.25rem; letter-spacing: -0.02em; max-width: 500px;">
                    <?php echo htmlspecialchars($group['project_title']); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                    <span style="font-size: 0.75rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); padding: 3px 10px; border-radius: 20px; font-weight: 600; font-family: monospace;">
                        <?php echo htmlspecialchars($group['group_code'] ?? 'ID PENDING'); ?>
                    </span>
                    <span style="font-size: 0.75rem; background: <?php echo $sc[0]; ?>; color: <?php echo $sc[1]; ?>; padding: 3px 10px; border-radius: 20px; font-weight: 600;">
                        <?php echo htmlspecialchars($st); ?>
                    </span>
                </div>
            </div>
            <div class="text-end d-none d-md-block">
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.05em;">Progress</div>
                <div class="fw-bold text-white" style="font-size: 1.6rem; letter-spacing: -0.03em;"><?php echo $currentIdx + 1; ?> / <?php echo count($stagesList); ?></div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4);">stages complete</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Stat Mini Cards Row ── -->
<?php $bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>
<div class="row g-3 mb-4">
    <!-- Supervisor -->
    <div class="col-6 col-lg-3">
        <div class="stat-mini d-flex align-items-center gap-3" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
            <div class="stat-mini-icon" style="background: rgba(13,148,136,0.1);">
                <i class="bi bi-person-badge-fill" style="color: #0d9488;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Supervisor</div>
                <div class="stat-mini-value" style="font-size: 0.82rem;">
                    <?php echo htmlspecialchars($group['supervisor_name'] ?? 'Not Assigned'); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Current Stage -->
    <div class="col-6 col-lg-3">
        <div class="stat-mini d-flex align-items-center gap-3" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
            <div class="stat-mini-icon" style="background: rgba(59,130,246,0.1);">
                <i class="bi bi-flag-fill" style="color: #3b82f6;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Current Stage</div>
                <div class="stat-mini-value" style="font-size: 0.78rem;"><?php echo htmlspecialchars($group['progress_stage']); ?></div>
            </div>
        </div>
    </div>
    <!-- Deadlines count -->
    <div class="col-6 col-lg-3">
        <div class="stat-mini d-flex align-items-center gap-3" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
            <div class="stat-mini-icon" style="background: rgba(245,158,11,0.1);">
                <i class="bi bi-calendar-event-fill" style="color: #f59e0b;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Active Deadlines</div>
                <div class="stat-mini-value" style="font-size: 1.1rem;">
                    <?php echo count(array_filter($deadlines, fn($d) => strtotime($d['deadline_date']) >= time())); ?>
                    <span style="font-size: 0.7rem; color: var(--text-secondary); font-weight: 500;"> remaining</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Status -->
    <div class="col-6 col-lg-3">
        <div class="stat-mini d-flex align-items-center gap-3" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
            <div class="stat-mini-icon" style="background: rgba(99,102,241,0.1);">
                <i class="bi bi-shield-fill-check" style="color: #6366f1;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Project Status</div>
                <div class="stat-mini-value" style="color: <?php echo $sc[1]; ?>;"><?php echo htmlspecialchars($st); ?></div>
            </div>
        </div>
    </div>
</div>

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
