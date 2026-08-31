<!-- Coordinator Group Allocation View -->
<?php
$title = 'Group Allocation';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* Modern Glassmorphism & Card Styles */
.alloc-hero {
    background: linear-gradient(135deg, #063d59 0%, #047fb0 50%, #0284c7 100%);
    border-radius: 20px;
    padding: 2.2rem 2.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px -10px rgba(4, 127, 176, 0.4);
    color: #ffffff;
}
.alloc-hero::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.alloc-hero-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.85rem;
    color: #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Stat Cards */
.kpi-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.07));
    border-radius: 18px;
    padding: 1.35rem 1.4rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.08);
    border-color: rgba(4, 127, 176, 0.25);
}
.kpi-card-top-bar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}
.kpi-icon-circle {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

/* Capacity Input Cards */
.capacity-card {
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: 18px;
    padding: 1.4rem;
    transition: all 0.25s ease;
    position: relative;
}
.capacity-card:hover {
    border-color: #047fb0;
    box-shadow: 0 8px 24px rgba(4, 127, 176, 0.08);
}
.capacity-card:focus-within {
    border-color: #047fb0;
    box-shadow: 0 0 0 3px rgba(4, 127, 176, 0.15);
}
.stepper-btn {
    width: 36px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--form-bg, #f1f5f9);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #1e293b);
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}
.stepper-btn:hover {
    background: #047fb0;
    color: #ffffff;
    border-color: #047fb0;
}
.stepper-btn:active {
    transform: scale(0.94);
}

/* Live Distribution Progress Bar */
.dist-progress-wrap {
    height: 12px;
    border-radius: 10px;
    background: var(--form-bg, #e2e8f0);
    overflow: hidden;
    display: flex;
}
.dist-progress-seg {
    height: 100%;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Filter Pills */
.btn-filter-pill {
    background: var(--form-bg, #f8fafc);
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    font-size: 0.8rem;
    font-weight: 500;
    padding: 6px 16px;
    border-radius: 999px;
    transition: all 0.2s ease;
}
.btn-filter-pill:hover {
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #1e293b);
    border-color: var(--border-color);
}
.btn-filter-pill.active {
    background: #047fb0;
    color: #ffffff;
    border-color: #047fb0;
    box-shadow: 0 4px 12px rgba(4, 127, 176, 0.25);
    font-weight: 600;
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
    transform: translateY(-3px) scale(1.18);
    z-index: 10;
    box-shadow: 0 6px 14px rgba(0,0,0,0.22);
}

/* Color palettes per committee */
.comm-theme-1 { --comm-color: #8b5cf6; --comm-bg: rgba(139, 92, 246, 0.1); --comm-border: rgba(139, 92, 246, 0.25); }
.comm-theme-2 { --comm-color: #0284c7; --comm-bg: rgba(2, 132, 199, 0.1); --comm-border: rgba(2, 132, 199, 0.25); }
.comm-theme-3 { --comm-color: #10b981; --comm-bg: rgba(16, 185, 129, 0.1); --comm-border: rgba(16, 185, 129, 0.25); }
.comm-theme-4 { --comm-color: #f59e0b; --comm-bg: rgba(245, 158, 11, 0.1); --comm-border: rgba(245, 158, 11, 0.25); }
.comm-theme-5 { --comm-color: #ec4899; --comm-bg: rgba(236, 72, 153, 0.1); --comm-border: rgba(236, 72, 153, 0.25); }
</style>

<!-- Top Hero Banner -->
<div class="alloc-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="alloc-hero-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mb-1">
                    <h3 class="text-white fw-bold m-0" style="letter-spacing: -0.02em">Group Allocation</h3>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(16, 185, 129, 0.28); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-clock-history me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
                    </span>
                </div>
                <p class="mb-0" style="color: rgba(255,255,255,0.85); font-size: 0.9rem">Assign project groups to presentation committees based on lab capacities</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo $basePath; ?>/coordinator/assessment" class="btn btn-light rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #047fb0; font-size: 0.88rem;">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> <span>Evaluation Sheets</span>
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px; background-color: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px; background-color: #fee2e2; color: #991b1b;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Summary KPI Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Total Active Groups -->
    <div class="col-lg-3 col-sm-6">
        <div class="kpi-card h-100">
            <div class="kpi-card-top-bar" style="background: linear-gradient(90deg, #047fb0, #0284c7);"></div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.04em;">Total Active Groups</span>
                    <h2 class="fw-bold m-0 mt-1" style="color: var(--text-primary); font-size: 1.85rem;"><?php echo (int)$totalGroups; ?></h2>
                </div>
                <div class="kpi-icon-circle" style="background: rgba(4, 127, 176, 0.1); color: #047fb0;">
                    <i class="bi bi-collection-fill"></i>
                </div>
            </div>
            <div class="mt-2 text-muted small" style="font-size: 0.76rem;">
                <i class="bi bi-check2-all text-success me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift Batches
            </div>
        </div>
    </div>

    <!-- Committee Cards Dynamic Loop -->
    <?php 
    $commColors = [
        1 => ['hex' => '#8b5cf6', 'grad' => 'linear-gradient(90deg, #8b5cf6, #a78bfa)', 'icon' => 'bi-shield-check', 'bg' => 'rgba(139, 92, 246, 0.1)'],
        2 => ['hex' => '#0284c7', 'grad' => 'linear-gradient(90deg, #0284c7, #38bdf8)', 'icon' => 'bi-shield-shaded', 'bg' => 'rgba(2, 132, 199, 0.1)'],
        3 => ['hex' => '#10b981', 'grad' => 'linear-gradient(90deg, #10b981, #34d399)', 'icon' => 'bi-shield-fill-check', 'bg' => 'rgba(16, 185, 129, 0.1)'],
        4 => ['hex' => '#f59e0b', 'grad' => 'linear-gradient(90deg, #f59e0b, #fbbf24)', 'icon' => 'bi-shield-lock', 'bg' => 'rgba(245, 158, 11, 0.1)'],
    ];
    ?>
    <?php for($i = 1; $i <= $numCommittees; $i++): ?>
    <?php 
        $colConfig = $commColors[$i] ?? $commColors[1];
        $countThis = (int)($committeeCounts[$i] ?? 0);
        $percentThis = $totalGroups > 0 ? round(($countThis / $totalGroups) * 100) : 0;
        $evaluatorCount = count($committeeMembers[$i] ?? []);
    ?>
    <div class="col-lg-3 col-sm-6">
        <div class="kpi-card h-100">
            <div class="kpi-card-top-bar" style="background: <?php echo $colConfig['grad']; ?>;"></div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.04em;">Committee <?php echo $i; ?></span>
                    <h2 class="fw-bold m-0 mt-1" style="color: <?php echo $colConfig['hex']; ?>; font-size: 1.85rem;">
                        <?php echo $countThis; ?> <span class="fw-normal text-muted" style="font-size: 0.85rem;">groups</span>
                    </h2>
                </div>
                <div class="kpi-icon-circle" style="background: <?php echo $colConfig['bg']; ?>; color: <?php echo $colConfig['hex']; ?>;">
                    <i class="bi <?php echo $colConfig['icon']; ?>"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2 pt-1 border-top" style="border-color: var(--border-color) !important; font-size: 0.76rem;">
                <span class="text-muted">
                    <i class="bi bi-people-fill me-1" style="color: <?php echo $colConfig['hex']; ?>;"></i><?php echo $evaluatorCount; ?> Evaluators (HOD)
                </span>
                <span class="badge rounded-pill fw-bold" style="background: <?php echo $colConfig['bg']; ?>; color: <?php echo $colConfig['hex']; ?>;">
                    <?php echo $percentThis; ?>%
                </span>
            </div>
        </div>
    </div>
    <?php endfor; ?>

    <!-- Unassigned Card if any -->
    <?php if ($unassignedCount > 0): ?>
    <div class="col-lg-3 col-sm-6">
        <div class="kpi-card h-100">
            <div class="kpi-card-top-bar" style="background: linear-gradient(90deg, #ef4444, #f87171);"></div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.04em;">Unassigned Groups</span>
                    <h2 class="fw-bold m-0 mt-1 text-danger" style="font-size: 1.85rem;"><?php echo (int)$unassignedCount; ?></h2>
                </div>
                <div class="kpi-icon-circle" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
            </div>
            <div class="mt-2 text-danger small" style="font-size: 0.76rem;">
                <i class="bi bi-arrow-down-circle me-1"></i>Distribute below to allocate
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Lab Capacity & Sequential Allocation Section -->
<div class="page-section mb-4">
    <div class="page-section-header">
        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="page-section-icon" style="background: rgba(4, 127, 176, 0.1); color: #047fb0;">
                    <i class="bi bi-sliders2-vertical"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--text-primary); font-size: 1.05rem;">Lab Group Quotas &amp; Sequential Auto-Distribution</h5>
                    <p class="text-muted small mb-0 mt-0.5">Define student group limits per committee based on presentation lab seating capacity (Total: <strong><?php echo (int)$totalGroups; ?></strong> active groups)</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="balanceEqually()" title="Split total groups equally among committees">
                    <i class="bi bi-distribute-horizontal"></i> <span>Equal Split</span>
                </button>
            </div>
        </div>
    </div>

    <div class="page-section-body p-4">
        <!-- Live Distribution Visual Progress Bar -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1.5 small">
                <span class="fw-bold text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em;">Live Capacity Distribution Bar</span>
                <span class="small fw-semibold" id="distBarPercentText" style="color: var(--text-primary);">Total: 0 / <?php echo (int)$totalGroups; ?> Groups</span>
            </div>
            <div class="dist-progress-wrap" id="distProgressBar">
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <?php $colConfig = $commColors[$i] ?? $commColors[1]; ?>
                <div class="dist-progress-seg" id="progressSeg<?php echo $i; ?>" style="background: <?php echo $colConfig['hex']; ?>; width: 0%;" title="Committee <?php echo $i; ?>"></div>
                <?php endfor; ?>
            </div>
        </div>

        <form action="<?php echo $basePath; ?>/coordinator/committees/distribute" method="POST" id="distributeForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            
            <div class="row g-3 mb-4">
                <?php 
                    $baseShare = ($totalGroups > 0 && $numCommittees > 0) ? floor($totalGroups / $numCommittees) : 0;
                    $remainder = ($totalGroups > 0 && $numCommittees > 0) ? ($totalGroups % $numCommittees) : 0;
                ?>
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <?php 
                    $colConfig = $commColors[$i] ?? $commColors[1];
                    $suggestedVal = ($committeeCounts[$i] > 0) ? $committeeCounts[$i] : ($baseShare + ($i <= $remainder ? 1 : 0));
                    $memberNames = !empty($committeeMembers[$i]) ? array_map(fn($m) => $m['name'], $committeeMembers[$i]) : [];
                ?>
                <div class="col-md-<?php echo ($numCommittees <= 2) ? '6' : '4'; ?>">
                    <div class="capacity-card h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="background: <?php echo $colConfig['bg']; ?>; color: <?php echo $colConfig['hex']; ?>; width: 26px; height: 26px; font-size: 0.8rem;">
                                    <?php echo $i; ?>
                                </span>
                                <label class="form-label fw-bold mb-0" style="font-size: 0.92rem; color: var(--text-primary);">
                                    Committee <?php echo $i; ?>
                                </label>
                            </div>
                            <span class="badge rounded-pill px-2.5 py-1" style="background: <?php echo $colConfig['bg']; ?>; color: <?php echo $colConfig['hex']; ?>; font-size: 0.72rem;">
                                <?php echo count($memberNames); ?> Evaluator(s)
                            </span>
                        </div>
                        <p class="text-muted small mb-2.5" style="font-size: 0.76rem;">Lab capacity for presentation</p>
                        
                        <!-- Interactive Stepper Input -->
                        <div class="d-flex align-items-stretch overflow-hidden rounded-3 border" style="border-color: var(--border-color) !important;">
                            <button type="button" class="stepper-btn border-0 border-end" onclick="stepCapacity(<?php echo $i; ?>, -1)">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                            <input type="number" 
                                   id="capInput<?php echo $i; ?>" 
                                   name="capacity[<?php echo $i; ?>]" 
                                   class="form-control capacity-input border-0 text-center fw-bold shadow-none" 
                                   style="font-size: 1.1rem; color: var(--text-primary); background: transparent;" 
                                   value="<?php echo $suggestedVal; ?>" 
                                   min="0" 
                                   max="<?php echo $totalGroups; ?>" 
                                   required 
                                   oninput="recalcCapacityTotal()">
                            <button type="button" class="stepper-btn border-0 border-start" onclick="stepCapacity(<?php echo $i; ?>, 1)">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                        <!-- Committee Evaluator Badge Tags (Set by HOD) -->
                        <div class="mt-3 pt-2 border-top" style="border-color: var(--border-color) !important;">
                            <span class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">Evaluators (HOD Assigned):</span>
                            <?php if (!empty($memberNames)): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach($memberNames as $mName): ?>
                                    <span class="badge rounded-pill px-2 py-0.5" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.72rem;">
                                        <?php echo htmlspecialchars($mName, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-warning small" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle me-1"></i>No evaluators appointed by HOD yet</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Action Bar -->
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between p-3.5 rounded-4" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center gap-2.5 mb-3 mb-md-0">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 36px; height: 36px;">
                        <i class="bi bi-info-circle-fill text-primary"></i>
                    </div>
                    <div id="capacitySummaryText" style="color: var(--text-primary); font-size: 0.88rem;">
                        Allocating: <strong><?php echo $totalGroups; ?></strong> of <strong><?php echo $totalGroups; ?></strong> total groups.
                    </div>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-2 shadow" style="background: linear-gradient(135deg, #047fb0, #03658c); border: none; font-size: 0.9rem;">
                    <i class="bi bi-magic"></i> <span>Apply Sequential Distribution</span>
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
            <div class="col-lg-5 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle bg-white">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-1 table-search shadow-none" placeholder="Search by group code, project title, supervisor..." data-target="group-alloc-table">
                </div>
            </div>
            <!-- Filter Pills -->
            <div class="col-lg-7 pe-0 d-flex justify-content-lg-end gap-2 flex-wrap align-items-center">
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
                    <th class="ps-4" style="width: 70px;">Seq #</th>
                    <th>Group Code &amp; Project</th>
                    <th>Supervisor</th>
                    <th>Team Members</th>
                    <th>Assigned Committee</th>
                    <th class="text-end pe-4" style="min-width: 170px;">Reassign Committee</th>
                </tr>
            </thead>
            <tbody>
                <?php $seqIdx = 1; ?>
                <?php foreach($groups as $g): ?>
                <?php 
                    $cNum = (int)($g['committee_number'] ?? 0); 
                    $cCol = $commColors[$cNum] ?? null;
                ?>
                <tr data-comm-num="<?php echo $cNum > 0 ? $cNum : 'unassigned'; ?>">
                    <td class="ps-4">
                        <span class="badge rounded-pill fw-bold px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.78rem;">
                            #<?php echo $seqIdx++; ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-column" style="max-width: 340px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge font-monospace px-2.5 py-0.5 fw-bold" style="background: rgba(4, 127, 176, 0.1); color: #047fb0; border: 1px solid rgba(4, 127, 176, 0.25); font-size: 0.74rem;">
                                    <?php echo htmlspecialchars($g['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="badge px-2 py-0.5 rounded-pill" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 0.7rem; font-weight: 600;">
                                    <?php echo htmlspecialchars($g['student_shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <div class="fw-bold text-truncate" title="<?php echo htmlspecialchars($g['project_title'] ?? 'Title Pending', ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--text-primary); font-size: 0.9rem;">
                                <?php echo htmlspecialchars($g['project_title'] ?? 'Project Title Pending', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($g['supervisor_name'])): ?>
                        <div>
                            <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.88rem;"><?php echo htmlspecialchars($g['supervisor_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <span class="badge border px-2 py-0.5 rounded-pill mt-0.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.7rem;">
                                <?php echo htmlspecialchars($g['supervisor_designation'] ?? 'Faculty', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
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
                                 style="width: 30px; height: 30px; border-width: 2px;"
                                 alt="<?php echo htmlspecialchars($m['student_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                 title="<?php echo htmlspecialchars($m['student_name'] . ' (' . $m['roll_no'] . ')', ENT_QUOTES, 'UTF-8'); ?>">
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($cNum > 0 && $cCol): ?>
                        <span class="badge rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: <?php echo $cCol['bg']; ?>; color: <?php echo $cCol['hex']; ?>; border: 1px solid <?php echo $cCol['hex']; ?>40; font-size: 0.8rem;">
                            <i class="bi <?php echo $cCol['icon']; ?> me-1"></i>Committee <?php echo $cNum; ?>
                        </span>
                        <?php else: ?>
                        <span class="badge rounded-pill px-3 py-1.5 text-danger border border-danger-subtle bg-danger-subtle fw-semibold" style="font-size: 0.78rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>Unassigned
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <form action="<?php echo $basePath; ?>/coordinator/committees/reassign" method="POST" class="d-inline-flex align-items-center justify-content-end gap-1.5 m-0">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="group_id" value="<?php echo (int)$g['id']; ?>">
                            <select name="committee_number" class="form-select form-select-sm rounded-pill shadow-none fw-semibold" style="font-size: 0.8rem; width: 145px; border-color: var(--border-color);" onchange="this.form.submit()">
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
    const vals = [];

    for (let i = 1; i <= numCommittees; i++) {
        const inp = document.getElementById('capInput' + i);
        const val = inp ? (parseInt(inp.value, 10) || 0) : 0;
        vals.push(val);
        total += val;
    }

    // Update progress bar
    const barText = document.getElementById('distBarPercentText');
    if (barText) {
        barText.textContent = `Total: ${total} / ${totalGroups} Groups`;
    }

    for (let i = 1; i <= numCommittees; i++) {
        const seg = document.getElementById('progressSeg' + i);
        if (seg) {
            const pct = totalGroups > 0 ? ((vals[i - 1] / totalGroups) * 100) : 0;
            seg.style.width = pct + '%';
        }
    }

    // Update summary text
    const summary = document.getElementById('capacitySummaryText');
    if (summary) {
        if (total === totalGroups) {
            summary.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Perfect distribution: Exactly ${total} of ${totalGroups} groups allocated sequentially.</span>`;
        } else if (total < totalGroups) {
            summary.innerHTML = `<span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Allocating ${total} of ${totalGroups} groups (${totalGroups - total} overflow groups will go to the last committee).</span>`;
        } else {
            summary.innerHTML = `<span class="text-info fw-bold"><i class="bi bi-info-circle-fill me-1"></i>Total quota capacity (${total}) exceeds groups (${totalGroups}). Groups #1 to #${totalGroups} will be allocated sequentially.</span>`;
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

// Initial calculation on load
document.addEventListener('DOMContentLoaded', () => {
    recalcCapacityTotal();
});
</script>
