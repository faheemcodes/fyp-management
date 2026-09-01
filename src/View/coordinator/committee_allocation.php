<!-- Coordinator Group Allocation View -->
<?php
$title = 'Group Allocation';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* Filter Pills */
.btn-filter-pill {
    background: var(--form-bg, #f8fafc);
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    font-size: 0.78rem;
    padding: 5px 14px;
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
    box-shadow: 0 2px 8px rgba(4, 127, 176, 0.25);
}

/* Capacity Input Box */
.capacity-input-card {
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: 18px;
    padding: 1.35rem;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}
.capacity-input-card:hover {
    border-color: #047fb0;
    box-shadow: 0 6px 20px rgba(4, 127, 176, 0.1);
    transform: translateY(-2px);
}
.capacity-input-card:focus-within {
    border-color: #047fb0;
    box-shadow: 0 0 0 3px rgba(4, 127, 176, 0.15);
}

/* Stepper Capsule Control */
.stepper-capsule {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    border-radius: 999px;
    padding: 3px 6px;
    width: 100%;
    transition: border-color 0.2s ease;
}
.stepper-capsule:focus-within {
    border-color: #047fb0;
}
.stepper-round-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: none;
    background: var(--form-bg, #f1f5f9);
    color: var(--text-primary, #1e293b);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.stepper-round-btn:hover {
    background: #047fb0;
    color: #ffffff;
}
.stepper-round-btn:active {
    transform: scale(0.92);
}
.stepper-num-input {
    border: none;
    background: transparent;
    text-align: center;
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--text-primary, #1e293b);
    width: 60px;
    outline: none;
}

/* Avatar Group */
.avatar-group {
    display: inline-flex;
    align-items: center;
}
.avatar-group .avatar-item {
    position: relative;
    margin-left: -8px;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), z-index 0.2s ease;
    border: 2px solid var(--card-bg, #ffffff);
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
}
.avatar-group .avatar-item:first-child {
    margin-left: 0;
}
.avatar-group .avatar-item:hover {
    transform: translateY(-2px) scale(1.15);
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.modern-table th {
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    color: var(--text-secondary) !important;
    padding: 14px 20px;
}
.modern-table td {
    padding: 16px 20px;
    vertical-align: middle;
    font-size: 0.88rem;
}
</style>

<!-- Top Hero Banner (Standard Portal Design) -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Group Allocation</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(16, 185, 129, 0.25); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-clock-history me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75); font-size: 0.85rem">Distribute project groups sequentially across presentation lab committees</p>
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
    <div class="col-md-3 col-6">
        <div class="stat-card p-3 rounded-4 shadow-sm border" style="background: var(--card-bg); border-color: var(--border-color) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 0.72rem;">Total Active Groups</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--text-primary);"><?php echo (int)$totalGroups; ?></h3>
                </div>
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 44px; height: 44px;">
                    <i class="bi bi-collection-fill fs-5"></i>
                </div>
            </div>
            <small class="text-muted d-block mt-2" style="font-size: 0.74rem;">
                <i class="bi bi-check-circle-fill text-success me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
            </small>
        </div>
    </div>
    <?php for($i = 1; $i <= $numCommittees; $i++): ?>
    <div class="col-md-3 col-6">
        <div class="stat-card p-3 rounded-4 shadow-sm border" style="background: var(--card-bg); border-color: var(--border-color) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block" style="font-size: 0.72rem;">Committee <?php echo $i; ?></span>
                    <h3 class="fw-bold m-0 mt-1" style="color: #8b5cf6;"><?php echo (int)($committeeCounts[$i] ?? 0); ?> <span class="fw-normal text-muted" style="font-size: 0.8rem;">groups</span></h3>
                </div>
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; width: 44px; height: 44px;">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
            </div>
            <small class="text-muted d-block mt-2" style="font-size: 0.74rem;">
                <i class="bi bi-people-fill me-1"></i><?php echo count($committeeMembers[$i] ?? []); ?> Evaluators (HOD Assigned)
            </small>
        </div>
    </div>
    <?php endfor; ?>
    <?php if ($unassignedCount > 0): ?>
    <div class="col-md-3 col-6">
        <div class="stat-card p-3 rounded-4 shadow-sm border" style="background: var(--card-bg); border-color: var(--border-color) !important;">
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

<!-- Sequential Lab Allocation Section (Standard Portal Header & Section) -->
<div class="page-section mb-4">
    <div class="page-section-header">
        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="page-section-icon" style="background: rgba(4, 127, 176, 0.1); color: #047fb0;">
                    <i class="bi bi-sliders2-vertical"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Sequential Lab Allocation Engine</h6>
                    <small class="text-muted">Enter presentation group limits for each committee lab (Total: <?php echo (int)$totalGroups; ?> active groups)</small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="balanceEqually()" title="Split total groups equally among committees">
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
                ?>
                <div class="col-md-<?php echo ($numCommittees <= 2) ? '6' : '4'; ?>">
                    <div class="capacity-input-card h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge border rounded-pill px-2.5 py-1 font-monospace" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important; font-size: 0.8rem;">
                                <i class="bi bi-shield-check me-1"></i>Committee <?php echo $i; ?>
                            </span>
                            <span class="badge rounded-pill px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.74rem;">
                                <?php echo count($memberNames); ?> Evaluator(s)
                            </span>
                        </div>
                        
                        <label class="form-label small fw-bold text-muted mb-2 d-block">Presentation Capacity Limit</label>
                        
                        <!-- Stepper Capsule -->
                        <div class="stepper-capsule mb-3">
                            <button type="button" class="stepper-round-btn" onclick="stepCapacity(<?php echo $i; ?>, -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <div class="d-flex align-items-center gap-1">
                                <input type="number" 
                                       id="capInput<?php echo $i; ?>" 
                                       name="capacity[<?php echo $i; ?>]" 
                                       class="stepper-num-input" 
                                       value="<?php echo $suggestedVal; ?>" 
                                       min="0" 
                                       max="<?php echo $totalGroups; ?>" 
                                       required 
                                       oninput="recalcCapacityTotal()">
                                <span class="text-muted small fw-semibold">groups</span>
                            </div>
                            <button type="button" class="stepper-round-btn" onclick="stepCapacity(<?php echo $i; ?>, 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>

                        <!-- Evaluators List -->
                        <div class="mt-2 pt-2 border-top" style="border-color: var(--border-color) !important;">
                            <span class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">Evaluators (HOD Assigned):</span>
                            <?php if (!empty($memberNames)): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach($memberNames as $mName): ?>
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.72rem;">
                                        <i class="bi bi-person-check me-1 text-primary"></i><?php echo htmlspecialchars($mName, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-warning small" style="font-size: 0.74rem;">No evaluators appointed by HOD yet</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Action Bar -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between p-3 rounded-4" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center gap-2.5 mb-3 mb-md-0">
                    <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                    <div id="capacitySummaryText" style="color: var(--text-primary); font-size: 0.88rem;">
                        Allocating: <strong><?php echo $totalGroups; ?></strong> of <strong><?php echo $totalGroups; ?></strong> total groups sequentially.
                    </div>
                </div>
                <button type="submit" class="btn rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #047fb0, #03658c); border: none; color: #ffffff; font-weight: 700;">
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
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search by group code, project title, supervisor..." data-target="group-alloc-table">
                </div>
            </div>
            <!-- Filter Pills -->
            <div class="col-md-7 pe-0 d-flex justify-content-md-end gap-2 flex-wrap align-items-center">
                <button class="btn btn-sm btn-filter-pill rounded-pill active" onclick="filterGroupTable('all', this)">All (<?php echo count($groups); ?>)</button>
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <button class="btn btn-sm btn-filter-pill rounded-pill" onclick="filterGroupTable('<?php echo $i; ?>', this)">
                    Committee <?php echo $i; ?> (<?php echo (int)($committeeCounts[$i] ?? 0); ?>)
                </button>
                <?php endfor; ?>
                <?php if ($unassignedCount > 0): ?>
                <button class="btn btn-sm btn-filter-pill rounded-pill text-danger" onclick="filterGroupTable('unassigned', this)">
                    Unassigned (<?php echo $unassignedCount; ?>)
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="group-alloc-table">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 70px;">Seq #</th>
                    <th>Group Code &amp; Project</th>
                    <th>Supervisor</th>
                    <th>Team Members</th>
                    <th>Assigned Committee</th>
                    <th class="text-end pe-4">Manual Reassign</th>
                </tr>
            </thead>
            <tbody>
                <?php $seqIdx = 1; ?>
                <?php foreach($groups as $g): ?>
                <?php $cNum = (int)($g['committee_number'] ?? 0); ?>
                <tr data-comm-num="<?php echo $cNum > 0 ? $cNum : 'unassigned'; ?>">
                    <td class="ps-4">
                        <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.75rem;">
                            <?php echo $seqIdx++; ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-column" style="max-width: 320px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge font-monospace rounded-pill px-2.5 py-1" style="background: rgba(59, 130, 246, 0.12); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.25); font-size: 0.78rem; font-weight: 700;">
                                    <?php echo htmlspecialchars($g['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 0.75rem; font-weight: 600;">
                                    <?php echo htmlspecialchars($g['student_shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <div class="fw-bold text-truncate" title="<?php echo htmlspecialchars($g['project_title'] ?? 'Title Pending', ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--text-primary); font-size: 0.88rem;">
                                <?php echo htmlspecialchars($g['project_title'] ?? 'Project Title Pending', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($g['supervisor_name'])): ?>
                        <div>
                            <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;"><?php echo htmlspecialchars($g['supervisor_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <small class="text-muted d-block" style="font-size: 0.72rem;"><?php echo htmlspecialchars($g['supervisor_designation'] ?? 'Faculty', ENT_QUOTES, 'UTF-8'); ?></small>
                        </div>
                        <?php else: ?>
                        <span class="text-muted small">Not Assigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="avatar-group">
                            <?php foreach(($g['members'] ?? []) as $m): ?>
                            <?php $mAvatar = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($mAvatar, ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="avatar-item" 
                                 style="width: 28px; height: 28px; border-width: 1.5px;"
                                 alt="<?php echo htmlspecialchars($m['student_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                 title="<?php echo htmlspecialchars($m['student_name'] . ' (' . $m['roll_no'] . ')', ENT_QUOTES, 'UTF-8'); ?>">
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($cNum > 0): ?>
                        <span class="badge border rounded-pill px-3 py-1 font-monospace" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important; font-size: 0.78rem;">
                            <i class="bi bi-shield-check me-1"></i>Committee <?php echo $cNum; ?>
                        </span>
                        <?php else: ?>
                        <span class="badge border rounded-pill px-2.5 py-1 text-danger border-danger-subtle bg-danger-subtle" style="font-size: 0.75rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>Unassigned
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <form action="<?php echo $basePath; ?>/coordinator/committees/reassign" method="POST" class="d-inline-flex align-items-center gap-1.5 m-0">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="group_id" value="<?php echo (int)$g['id']; ?>">
                            <select name="committee_number" class="form-select form-select-sm rounded-pill shadow-none" style="font-size: 0.78rem; width: 135px;" onchange="this.form.submit()">
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
                        <i class="bi bi-folder-x fs-2 d-block mb-2 opacity-50"></i>
                        No approved project groups found for <?php echo htmlspecialchars($shift, ENT_QUOTES, 'UTF-8'); ?> shift.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function stepCapacity(commNum, delta) {
    const inp = document.getElementById('capInput' + commNum);
    if (!inp) return;
    let val = parseInt(inp.value, 10) || 0;
    val = Math.max(0, val + delta);
    inp.value = val;
    recalcCapacityTotal();
}

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
