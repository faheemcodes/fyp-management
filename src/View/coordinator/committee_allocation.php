<!-- Coordinator Group Allocation View -->
<?php
$title = 'Group Allocation';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* Modern UI Styles for Group Allocation */
.group-code-pill {
    display: inline-flex;
    align-items: center;
    background: rgba(4, 127, 176, 0.1);
    color: #047fb0;
    font-family: monospace;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 8px;
    border: 1px solid rgba(4, 127, 176, 0.2);
}

.alloc-stat-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: 16px;
    padding: 1.25rem 1.4rem;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}
.alloc-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    border-color: rgba(4, 127, 176, 0.3);
}

.capacity-box {
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: 14px;
    padding: 1.25rem;
    transition: all 0.2s ease;
}
.capacity-box:focus-within {
    border-color: #047fb0;
    box-shadow: 0 0 0 3px rgba(4, 127, 176, 0.12);
}

.avatar-stack {
    display: inline-flex;
    align-items: center;
}
.avatar-stack img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--card-bg, #ffffff);
    margin-left: -10px;
    transition: transform 0.2s ease, z-index 0.2s ease;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.avatar-stack img:first-child {
    margin-left: 0;
}
.avatar-stack img:hover {
    transform: translateY(-3px) scale(1.15);
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.btn-filter-pill {
    background: var(--form-bg, #f8fafc);
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    font-size: 0.8rem;
    font-weight: 600;
    padding: 6px 16px;
    border-radius: 999px;
    transition: all 0.2s ease;
}
.btn-filter-pill:hover {
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #1e293b);
}
.btn-filter-pill.active {
    background: #047fb0;
    color: #ffffff;
    border-color: #047fb0;
    box-shadow: 0 3px 10px rgba(4, 127, 176, 0.25);
}

.committee-badge-1 {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.25);
}
.committee-badge-2 {
    background: rgba(2, 132, 199, 0.1);
    color: #0284c7;
    border: 1px solid rgba(2, 132, 199, 0.25);
}
.committee-badge-3 {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.25);
}
.committee-badge-4 {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.25);
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Group Allocation</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(16, 185, 129, 0.3); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-clock-history me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">Distribute project groups sequentially to presentation lab committees</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo $basePath; ?>/coordinator/assessment" class="btn btn-sm btn-outline-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> <span>Evaluation Sheets</span>
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #fee2e2; color: #991b1b;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Summary Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Total Groups -->
    <div class="col-md-3 col-6">
        <div class="alloc-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 0.72rem;">Total Active Groups</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--text-primary);"><?php echo (int)$totalGroups; ?></h3>
                </div>
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="background: rgba(4, 127, 176, 0.1); color: #047fb0; width: 44px; height: 44px;">
                    <i class="bi bi-collection-fill fs-5"></i>
                </div>
            </div>
            <small class="text-muted d-block mt-2" style="font-size: 0.74rem;">
                <i class="bi bi-check-circle-fill text-success me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
            </small>
        </div>
    </div>

    <!-- Committees Dynamic Cards -->
    <?php for($i = 1; $i <= $numCommittees; $i++): ?>
    <?php 
        $badgeClass = "committee-badge-{$i}";
        $countThis = (int)($committeeCounts[$i] ?? 0);
        $evalCount = count($committeeMembers[$i] ?? []);
    ?>
    <div class="col-md-3 col-6">
        <div class="alloc-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 0.72rem;">Committee <?php echo $i; ?></span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--text-primary);"><?php echo $countThis; ?> <span class="fw-normal text-muted" style="font-size: 0.8rem;">groups</span></h3>
                </div>
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center <?php echo $badgeClass; ?>" style="width: 44px; height: 44px;">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
            </div>
            <small class="text-muted d-block mt-2" style="font-size: 0.74rem;">
                <i class="bi bi-people-fill me-1"></i><?php echo $evalCount; ?> Evaluators (HOD Assigned)
            </small>
        </div>
    </div>
    <?php endfor; ?>

    <!-- Unassigned (if any) -->
    <?php if ($unassignedCount > 0): ?>
    <div class="col-md-3 col-6">
        <div class="alloc-stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 0.72rem;">Unassigned</span>
                    <h3 class="fw-bold m-0 mt-1 text-danger"><?php echo (int)$unassignedCount; ?></h3>
                </div>
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 44px; height: 44px;">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                </div>
            </div>
            <small class="text-danger d-block mt-2" style="font-size: 0.74rem;">
                <i class="bi bi-arrow-down-circle me-1"></i>Allocate quotas below
            </small>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Lab Capacity & Sequential Allocation Section -->
<div class="page-section mb-4">
    <div class="page-section-header">
        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="page-section-icon" style="background: rgba(4, 127, 176, 0.1); color: #047fb0;">
                    <i class="bi bi-sliders2-vertical"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" style="color: var(--text-primary);">Sequential Lab Allocation Engine</h6>
                    <small class="text-muted">Enter presentation group limits for each committee lab (Total: <strong><?php echo (int)$totalGroups; ?></strong> groups)</small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="balanceEqually()">
                <i class="bi bi-distribute-horizontal"></i> <span>Equal Split</span>
            </button>
        </div>
    </div>

    <div class="page-section-body p-4">
        <form action="<?php echo $basePath; ?>/coordinator/committees/distribute" method="POST" id="distributeForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            
            <div class="row g-3 mb-4">
                <?php 
                    $baseShare = ($totalGroups > 0 && $numCommittees > 0) ? floor($totalGroups / $numCommittees) : 0;
                    $remainder = ($totalGroups > 0 && $numCommittees > 0) ? ($totalGroups % $numCommittees) : 0;
                ?>
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <?php 
                    $suggestedVal = ($committeeCounts[$i] > 0) ? $committeeCounts[$i] : ($baseShare + ($i <= $remainder ? 1 : 0));
                    $memberNames = !empty($committeeMembers[$i]) ? array_map(fn($m) => $m['name'], $committeeMembers[$i]) : [];
                    $badgeClass = "committee-badge-{$i}";
                ?>
                <div class="col-md-<?php echo ($numCommittees <= 2) ? '6' : '4'; ?>">
                    <div class="capacity-box h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge rounded-pill px-2.5 py-1 fw-bold <?php echo $badgeClass; ?>" style="font-size: 0.8rem;">
                                <i class="bi bi-shield-check me-1"></i>Committee <?php echo $i; ?>
                            </span>
                            <span class="text-muted small" style="font-size: 0.74rem;">
                                <?php echo count($memberNames); ?> Evaluator(s)
                            </span>
                        </div>
                        
                        <label class="form-label small fw-bold text-muted mb-1">Presentation Capacity Limit</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-people text-muted"></i></span>
                            <input type="number" 
                                   id="capInput<?php echo $i; ?>" 
                                   name="capacity[<?php echo $i; ?>]" 
                                   class="form-control capacity-input border-start-0 border-end-0 fw-bold shadow-none" 
                                   value="<?php echo $suggestedVal; ?>" 
                                   min="0" 
                                   max="<?php echo $totalGroups; ?>" 
                                   required 
                                   oninput="recalcCapacityTotal()">
                            <span class="input-group-text bg-white border-start-0 text-muted small">groups</span>
                        </div>

                        <div class="mt-2 text-muted small" style="font-size: 0.72rem;">
                            <strong>Evaluators:</strong> 
                            <?php if (!empty($memberNames)): ?>
                                <?php echo htmlspecialchars(implode(', ', $memberNames), ENT_QUOTES, 'UTF-8'); ?>
                            <?php else: ?>
                                <span class="text-warning">No evaluators appointed by HOD yet</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Distribution Info & Submit Bar -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between p-3 rounded-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                    <i class="bi bi-info-circle-fill text-primary"></i>
                    <span class="small" id="capacitySummaryText" style="color: var(--text-primary);">
                        Allocating: <strong><?php echo $totalGroups; ?></strong> of <strong><?php echo $totalGroups; ?></strong> total groups sequentially.
                    </span>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #047fb0, #03658c); border: none;">
                    <i class="bi bi-magic"></i> <span>Distribute Groups Sequentially</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Project Groups Sequence & Reassignment Table -->
<div class="page-section">
    <div class="page-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-5 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle bg-white">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-1 table-search shadow-none" placeholder="Search by group code, project title, supervisor..." data-target="group-alloc-table">
                </div>
            </div>
            <!-- Filter Pills -->
            <div class="col-md-7 pe-0 d-flex justify-content-md-end gap-2 flex-wrap align-items-center">
                <button class="btn btn-sm btn-filter-pill active" onclick="filterGroupTable('all', this)">
                    All (<?php echo count($groups); ?>)
                </button>
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <button class="btn btn-sm btn-filter-pill" onclick="filterGroupTable('<?php echo $i; ?>', this)">
                    Committee <?php echo $i; ?> (<?php echo (int)($committeeCounts[$i] ?? 0); ?>)
                </button>
                <?php endfor; ?>
                <?php if ($unassignedCount > 0): ?>
                <button class="btn btn-sm btn-filter-pill text-danger" onclick="filterGroupTable('unassigned', this)">
                    Unassigned (<?php echo $unassignedCount; ?>)
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0 align-middle" id="group-alloc-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 60px;">Seq</th>
                    <th>Group &amp; Project Title</th>
                    <th>Supervisor</th>
                    <th>Team Members</th>
                    <th>Assigned Committee</th>
                    <th class="text-end pe-4" style="width: 170px;">Reassign</th>
                </tr>
            </thead>
            <tbody>
                <?php $seqIdx = 1; ?>
                <?php foreach($groups as $g): ?>
                <?php 
                    $cNum = (int)($g['committee_number'] ?? 0); 
                    $badgeClass = ($cNum > 0) ? "committee-badge-{$cNum}" : "";
                ?>
                <tr data-comm-num="<?php echo $cNum > 0 ? $cNum : 'unassigned'; ?>">
                    <td class="ps-4">
                        <span class="badge rounded-circle fw-bold d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.75rem;">
                            <?php echo $seqIdx++; ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-column" style="max-width: 320px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="group-code-pill">
                                    <?php echo htmlspecialchars($g['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 0.7rem;">
                                    <?php echo htmlspecialchars($g['student_shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <div class="fw-semibold text-truncate" title="<?php echo htmlspecialchars($g['project_title'] ?? 'Title Pending', ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--text-primary); font-size: 0.88rem;">
                                <?php echo htmlspecialchars($g['project_title'] ?? 'Project Title Pending', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($g['supervisor_name'])): ?>
                        <div>
                            <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;"><?php echo htmlspecialchars($g['supervisor_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <span class="badge border px-2 py-0.5 rounded-pill" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.7rem;">
                                <?php echo htmlspecialchars($g['supervisor_designation'] ?? 'Faculty', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <span class="text-muted small">Not Assigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="avatar-stack">
                            <?php foreach(($g['members'] ?? []) as $m): ?>
                            <?php $mAvatar = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($mAvatar, ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?php echo htmlspecialchars($m['student_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                 title="<?php echo htmlspecialchars($m['student_name'] . ' (' . $m['roll_no'] . ')', ENT_QUOTES, 'UTF-8'); ?>">
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($cNum > 0): ?>
                        <span class="badge rounded-pill px-3 py-1.5 font-monospace fw-bold <?php echo $badgeClass; ?>" style="font-size: 0.8rem;">
                            <i class="bi bi-shield-check me-1"></i>Committee <?php echo $cNum; ?>
                        </span>
                        <?php else: ?>
                        <span class="badge rounded-pill px-2.5 py-1 text-danger border border-danger-subtle bg-danger-subtle" style="font-size: 0.76rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>Unassigned
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <form action="<?php echo $basePath; ?>/coordinator/committees/reassign" method="POST" class="d-inline-flex align-items-center justify-content-end gap-1.5 m-0">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="group_id" value="<?php echo (int)$g['id']; ?>">
                            <select name="committee_number" class="form-select form-select-sm rounded-pill shadow-none fw-semibold" style="font-size: 0.78rem; width: 140px; border-color: var(--border-color);" onchange="this.form.submit()">
                                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($cNum === $i) ? 'selected' : ''; ?>>
                                    Committee <?php echo $i; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($groups)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 fw-semibold">No approved project groups found for <?php echo htmlspecialchars($shift, ENT_QUOTES, 'UTF-8'); ?> shift.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function balanceEqually() {
    const totalGroups = <?php echo (int)$totalGroups; ?>;
    const numCommittees = <?php echo (int)$numCommittees; ?>;
    if (numCommittees <= 0) return;

    const base = Math.floor(totalGroups / numCommittees);
    const rem = totalGroups % numCommittees;

    for (let i = 1; i <= numCommittees; i++) {
        const inp = document.getElementById('capInput' + i);
        if (inp) {
            inp.value = base + (i <= rem ? 1 : 0);
        }
    }
    recalcCapacityTotal();
}

function recalcCapacityTotal() {
    const numCommittees = <?php echo (int)$numCommittees; ?>;
    const totalGroups = <?php echo (int)$totalGroups; ?>;
    let total = 0;

    for (let i = 1; i <= numCommittees; i++) {
        const inp = document.getElementById('capInput' + i);
        total += inp ? (parseInt(inp.value, 10) || 0) : 0;
    }

    const summary = document.getElementById('capacitySummaryText');
    if (summary) {
        if (total === totalGroups) {
            summary.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Exact Match: All ${total} of ${totalGroups} groups will be allocated sequentially.</span>`;
        } else if (total < totalGroups) {
            summary.innerHTML = `<span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Allocating ${total} of ${totalGroups} groups (${totalGroups - total} overflow groups will go to the last committee).</span>`;
        } else {
            summary.innerHTML = `<span class="text-info fw-bold"><i class="bi bi-info-circle-fill me-1"></i>Total capacity limit (${total}) covers all ${totalGroups} groups.</span>`;
        }
    }
}

function filterGroupTable(commNum, btn) {
    document.querySelectorAll('.btn-filter-pill').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const rows = document.querySelectorAll('#group-alloc-table tbody tr[data-comm-num]');
    rows.forEach(r => {
        const rowComm = r.getAttribute('data-comm-num');
        if (commNum === 'all' || rowComm === String(commNum)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}
</script>
