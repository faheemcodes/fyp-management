<!-- Coordinator Group Allocation View -->
<?php
$title = 'Group Allocation';
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* Portal Unified Curved Theme (Emerald & Forest Palette) */
:root {
    --curve-xl: 26px;
    --curve-lg: 22px;
    --curve-md: 16px;
    --curve-sm: 10px;
    --curve-pill: 999px;
    
    --portal-emerald: #10b981;
    --portal-emerald-dark: #059669;
    --portal-emerald-deep: #064e3b;
    --portal-teal: #14b8a6;
}

/* Hero Banner with Unified Portal Emerald Theme */
.page-hero-curved {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #059669 100%);
    border-radius: var(--curve-xl);
    padding: 2.2rem 2.5rem;
    position: relative;
    overflow: hidden;
    color: #ffffff;
    box-shadow: 0 16px 36px -12px rgba(5, 150, 105, 0.35);
}
.page-hero-curved::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -40px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.page-hero-curved::after {
    content: '';
    position: absolute;
    bottom: -80px;
    left: 20%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.page-hero-icon-curved {
    width: 62px;
    height: 62px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1.5px solid rgba(255, 255, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* Stat Cards with Unified Curves */
.alloc-stat-card-curved {
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.07));
    border-radius: var(--curve-lg);
    padding: 1.4rem 1.5rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    position: relative;
    overflow: hidden;
}
.alloc-stat-card-curved:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px -8px rgba(16, 185, 129, 0.18);
    border-color: rgba(16, 185, 129, 0.35);
}
.stat-icon-bubble {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    transition: transform 0.3s ease;
}
.alloc-stat-card-curved:hover .stat-icon-bubble {
    transform: scale(1.08) rotate(5deg);
}

/* Curved Section Container */
.curved-section {
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: var(--curve-xl);
    overflow: hidden;
    box-shadow: 0 8px 30px -6px rgba(0,0,0,0.04);
}
.curved-section-header {
    padding: 1.4rem 1.8rem;
    background: linear-gradient(180deg, var(--card-bg, #ffffff) 0%, var(--form-bg, #f8fafc) 100%);
    border-bottom: 1px solid var(--border-color, rgba(0,0,0,0.06));
}

/* Curved Capacity Box */
.capacity-card-curved {
    background: linear-gradient(180deg, var(--card-bg, #ffffff) 0%, var(--form-bg, #f8fafc) 100%);
    border: 1.5px solid var(--border-color, rgba(0,0,0,0.08));
    border-radius: var(--curve-lg);
    padding: 1.5rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    box-shadow: 0 4px 16px rgba(0,0,0,0.02);
}
.capacity-card-curved:hover {
    transform: translateY(-4px);
    border-color: var(--portal-emerald);
    box-shadow: 0 14px 32px -8px rgba(16, 185, 129, 0.16);
}
.capacity-card-curved:focus-within {
    border-color: var(--portal-emerald);
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
}

/* Stepper Capsule Control */
.stepper-capsule {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    background: var(--card-bg, #ffffff);
    border: 1.5px solid var(--border-color, #cbd5e1);
    border-radius: var(--curve-pill);
    padding: 4px 6px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03);
    width: 100%;
    transition: border-color 0.2s ease;
}
.stepper-capsule:focus-within {
    border-color: var(--portal-emerald);
}
.stepper-round-btn {
    width: 38px;
    height: 38px;
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
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    flex-shrink: 0;
}
.stepper-round-btn:hover {
    background: var(--portal-emerald);
    color: #ffffff;
    transform: scale(1.1);
}
.stepper-round-btn:active {
    transform: scale(0.92);
}
.stepper-num-input {
    border: none;
    background: transparent;
    text-align: center;
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--text-primary, #1e293b);
    width: 70px;
    outline: none;
}

/* Bottom Action Capsule */
.action-capsule-bar {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%);
    border: 1.5px dashed rgba(16, 185, 129, 0.35);
    border-radius: var(--curve-lg);
    padding: 1.1rem 1.5rem;
}

/* Committee Theme Badges */
.comm-badge-1 {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.25);
}
.comm-badge-2 {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.25);
}
.comm-badge-3 {
    background: rgba(20, 184, 166, 0.1);
    color: #0d9488;
    border: 1px solid rgba(20, 184, 166, 0.25);
}
.comm-badge-4 {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.25);
}

/* Evaluator Pill Chips */
.evaluator-chip {
    border-radius: var(--curve-pill);
    background: var(--card-bg, #ffffff);
    color: var(--text-secondary, #475569);
    border: 1px solid var(--border-color, #e2e8f0);
    padding: 4px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

/* Filter Pills */
.btn-filter-pill-curved {
    background: var(--form-bg, #f8fafc);
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    font-size: 0.8rem;
    font-weight: 600;
    padding: 7px 18px;
    border-radius: var(--curve-pill);
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.btn-filter-pill-curved:hover {
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #1e293b);
    transform: translateY(-1px);
}
.btn-filter-pill-curved.active {
    background: var(--portal-emerald-dark);
    color: #ffffff;
    border-color: var(--portal-emerald-dark);
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
    transform: translateY(-1px);
}

/* Avatar Stack with Circular Rings */
.avatar-stack-curved {
    display: inline-flex;
    align-items: center;
}
.avatar-stack-curved img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--card-bg, #ffffff);
    margin-left: -10px;
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), z-index 0.2s ease;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
}
.avatar-stack-curved img:first-child {
    margin-left: 0;
}
.avatar-stack-curved img:hover {
    transform: translateY(-4px) scale(1.2);
    z-index: 10;
    box-shadow: 0 6px 14px rgba(0,0,0,0.22);
}

.group-code-pill-curved {
    display: inline-flex;
    align-items: center;
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    font-family: monospace;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: var(--curve-pill);
    border: 1px solid rgba(16, 185, 129, 0.22);
}
</style>

<!-- Top Hero Banner with Portal Emerald Gradient -->
<div class="page-hero-curved mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon-curved">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mb-1">
                    <h3 class="text-white fw-bold m-0" style="letter-spacing: -0.02em">Group Allocation</h3>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <i class="bi bi-clock-history me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
                    </span>
                </div>
                <p class="mb-0" style="color: rgba(255,255,255,0.88); font-size: 0.9rem">Distribute project groups sequentially to presentation lab committees</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo $basePath; ?>/coordinator/assessment" class="btn btn-light rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #059669; font-size: 0.88rem;">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> <span>Evaluation Sheets</span>
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 18px; background-color: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 18px; background-color: #fee2e2; color: #991b1b;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Summary Stat Cards with Unified Curves -->
<div class="row g-3 mb-4">
    <!-- Total Active Groups -->
    <div class="col-lg-3 col-sm-6">
        <div class="alloc-stat-card-curved h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.03em;">Total Active Groups</span>
                    <h2 class="fw-bold m-0 mt-1" style="color: var(--text-primary); font-size: 1.9rem;"><?php echo (int)$totalGroups; ?></h2>
                </div>
                <div class="stat-icon-bubble" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-collection-fill"></i>
                </div>
            </div>
            <div class="mt-2.5 text-muted small" style="font-size: 0.76rem;">
                <i class="bi bi-check-circle-fill text-success me-1"></i><?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift Batches
            </div>
        </div>
    </div>

    <!-- Committees Dynamic Loop -->
    <?php for($i = 1; $i <= $numCommittees; $i++): ?>
    <?php 
        $badgeClass = "comm-badge-{$i}";
        $countThis = (int)($committeeCounts[$i] ?? 0);
        $evalCount = count($committeeMembers[$i] ?? []);
        $pct = $totalGroups > 0 ? round(($countThis / $totalGroups) * 100) : 0;
    ?>
    <div class="col-lg-3 col-sm-6">
        <div class="alloc-stat-card-curved h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.03em;">Committee <?php echo $i; ?></span>
                    <h2 class="fw-bold m-0 mt-1" style="color: var(--text-primary); font-size: 1.9rem;">
                        <?php echo $countThis; ?> <span class="fw-normal text-muted" style="font-size: 0.85rem;">groups</span>
                    </h2>
                </div>
                <div class="stat-icon-bubble <?php echo $badgeClass; ?>">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2.5 pt-2 border-top" style="border-color: var(--border-color) !important; font-size: 0.75rem;">
                <span class="text-muted">
                    <i class="bi bi-people-fill me-1"></i><?php echo $evalCount; ?> Evaluators (HOD)
                </span>
                <span class="badge rounded-pill fw-bold <?php echo $badgeClass; ?>" style="font-size: 0.72rem;">
                    <?php echo $pct; ?>%
                </span>
            </div>
        </div>
    </div>
    <?php endfor; ?>

    <!-- Unassigned Card if any -->
    <?php if ($unassignedCount > 0): ?>
    <div class="col-lg-3 col-sm-6">
        <div class="alloc-stat-card-curved h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block" style="font-size: 0.72rem; letter-spacing: 0.03em;">Unassigned</span>
                    <h2 class="fw-bold m-0 mt-1 text-danger" style="font-size: 1.9rem;"><?php echo (int)$unassignedCount; ?></h2>
                </div>
                <div class="stat-icon-bubble" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
            </div>
            <div class="mt-2.5 text-danger small" style="font-size: 0.76rem;">
                <i class="bi bi-arrow-down-circle me-1"></i>Allocate quotas below
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Sequential Lab Allocation Engine (Curved Card) -->
<div class="curved-section mb-4">
    <div class="curved-section-header">
        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.12); color: #059669; border: 1.5px solid rgba(16, 185, 129, 0.25);">
                    <i class="bi bi-sliders2-vertical fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--text-primary); font-size: 1.08rem;">Sequential Lab Allocation Engine</h5>
                    <p class="text-muted small mb-0 mt-0.5">Enter presentation group limits for each committee lab (Total: <strong><?php echo (int)$totalGroups; ?></strong> groups)</p>
                </div>
            </div>
            <button type="button" class="btn rounded-pill px-3.5 py-1.5 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: rgba(16, 185, 129, 0.08); color: #059669; border: 1.5px solid rgba(16, 185, 129, 0.25); font-size: 0.82rem;" onclick="balanceEqually()" title="Split total groups equally among committees">
                <i class="bi bi-distribute-horizontal"></i> <span>Equal Split</span>
            </button>
        </div>
    </div>

    <div class="p-4">
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
                    $badgeClass = "comm-badge-{$i}";
                ?>
                <div class="col-md-<?php echo ($numCommittees <= 2) ? '6' : '4'; ?>">
                    <div class="capacity-card-curved h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge rounded-pill px-3 py-1.5 fw-bold <?php echo $badgeClass; ?>" style="font-size: 0.82rem;">
                                <i class="bi bi-shield-check me-1"></i>Committee <?php echo $i; ?>
                            </span>
                            <span class="badge rounded-pill px-2.5 py-1" style="background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.74rem;">
                                <?php echo count($memberNames); ?> Evaluator(s)
                            </span>
                        </div>
                        
                        <label class="form-label small fw-bold text-muted mb-2 d-block">Presentation Capacity Limit</label>
                        
                        <!-- Curved Stepper Capsule -->
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

                        <!-- Evaluator Chips Cloud -->
                        <div class="mt-2 pt-2 border-top" style="border-color: var(--border-color) !important;">
                            <span class="text-muted d-block mb-1.5" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">Evaluators (HOD Assigned):</span>
                            <?php if (!empty($memberNames)): ?>
                                <div class="d-flex flex-wrap gap-1.5">
                                    <?php foreach($memberNames as $mName): ?>
                                    <span class="evaluator-chip">
                                        <i class="bi bi-person-check-fill" style="color: var(--portal-emerald); font-size: 0.72rem;"></i>
                                        <?php echo htmlspecialchars($mName, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-warning small d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;">
                                    <i class="bi bi-exclamation-triangle"></i> No evaluators appointed by HOD yet
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Curved Action & Live Info Capsule -->
            <div class="action-capsule-bar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 40px; height: 40px; color: var(--portal-emerald-dark);">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                    </div>
                    <div id="capacitySummaryText" style="color: var(--text-primary); font-size: 0.9rem;">
                        Allocating: <strong><?php echo $totalGroups; ?></strong> of <strong><?php echo $totalGroups; ?></strong> total groups sequentially.
                    </div>
                </div>
                <button type="submit" class="btn rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2 shadow" style="background: linear-gradient(135deg, #10b981, #059669); border: none; color: #ffffff; font-size: 0.92rem;">
                    <i class="bi bi-magic"></i> <span>Distribute Groups Sequentially</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Project Groups Sequence & Reassignment Table -->
<div class="curved-section">
    <div class="curved-section-header">
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
                <button class="btn btn-sm btn-filter-pill-curved active" onclick="filterGroupTable('all', this)">
                    All (<?php echo count($groups); ?>)
                </button>
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <button class="btn btn-sm btn-filter-pill-curved" onclick="filterGroupTable('<?php echo $i; ?>', this)">
                    Committee <?php echo $i; ?> (<?php echo (int)($committeeCounts[$i] ?? 0); ?>)
                </button>
                <?php endfor; ?>
                <?php if ($unassignedCount > 0): ?>
                <button class="btn btn-sm btn-filter-pill-curved text-danger" onclick="filterGroupTable('unassigned', this)">
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
                    <th class="ps-4" style="width: 65px;">Seq</th>
                    <th>Group &amp; Project Title</th>
                    <th>Supervisor</th>
                    <th>Team Members</th>
                    <th>Assigned Committee</th>
                    <th class="text-end pe-4" style="width: 175px;">Reassign</th>
                </tr>
            </thead>
            <tbody>
                <?php $seqIdx = 1; ?>
                <?php foreach($groups as $g): ?>
                <?php 
                    $cNum = (int)($g['committee_number'] ?? 0); 
                    $badgeClass = ($cNum > 0) ? "comm-badge-{$cNum}" : "";
                ?>
                <tr data-comm-num="<?php echo $cNum > 0 ? $cNum : 'unassigned'; ?>">
                    <td class="ps-4">
                        <span class="badge rounded-circle fw-bold d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: var(--form-bg); color: var(--text-secondary); border: 1.5px solid var(--border-color); font-size: 0.78rem;">
                            <?php echo $seqIdx++; ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-column" style="max-width: 330px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="group-code-pill-curved">
                                    <?php echo htmlspecialchars($g['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 0.7rem; font-weight: 600;">
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
                            <span class="badge border px-2.5 py-0.5 rounded-pill mt-0.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.7rem;">
                                <?php echo htmlspecialchars($g['supervisor_designation'] ?? 'Faculty', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <span class="text-muted small">Not Assigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="avatar-stack-curved">
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
                        <span class="badge rounded-pill px-2.5 py-1 text-danger border border-danger-subtle bg-danger-subtle fw-semibold" style="font-size: 0.76rem;">
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
    document.querySelectorAll('.btn-filter-pill-curved').forEach(b => b.classList.remove('active'));
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
