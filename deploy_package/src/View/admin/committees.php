<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
.committee-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0, 0, 0, 0.08));
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    height: 100%;
    transition: all 0.2s ease;
}
.committee-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    border-color: rgba(59, 130, 246, 0.3);
}
.group-code-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(16,185,129,0.1);
    color: #10b981;
    font-family: monospace;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 50rem;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Committee &amp; Group Allocation</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Manage committee rosters, balance group distributions, and reallocate project groups
                </p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#distributeModal">
                <i class="bi bi-shuffle text-primary"></i> <span>Auto-Distribute Groups</span>
            </button>
        </div>
    </div>
</div>

<!-- Department & Shift Filter Bar -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="<?php echo $basePath; ?>/admin/committees" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small fw-bold text-secondary mb-1">Select Department</label>
                <select name="department" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <?php foreach ($departments as $d): ?>
                        <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($selectedDept === $d) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($d); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">Shift</label>
                <select name="shift" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <option value="all" <?php echo ($selectedShift === 'all') ? 'selected' : ''; ?>>All Shifts</option>
                    <option value="Morning" <?php echo ($selectedShift === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                    <option value="Evening" <?php echo ($selectedShift === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <a href="<?php echo $basePath; ?>/admin/committees" class="btn btn-outline-secondary w-100 rounded-pill fw-semibold">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Allocation KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                    <i class="bi bi-folder-check"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Approved Groups</span>
            </div>
            <h4 class="fw-bold m-0 text-dark"><?php echo $totalGroups; ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Configured Committees</span>
            </div>
            <h4 class="fw-bold m-0" style="color: #7c3aed;"><?php echo $numCommittees; ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Allocated Groups</span>
            </div>
            <h4 class="fw-bold m-0 text-success"><?php echo $totalGroups - $unassignedCount; ?></h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2 rounded-3" style="background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <span class="text-muted small fw-medium" style="font-size: 0.75rem;">Unallocated Groups</span>
            </div>
            <h4 class="fw-bold m-0 <?php echo ($unassignedCount > 0) ? 'text-danger' : 'text-dark'; ?>"><?php echo $unassignedCount; ?></h4>
        </div>
    </div>
</div>

<!-- Committee Rosters Grid -->
<div class="row g-4 mb-4">
    <?php for ($c = 1; $c <= $numCommittees; $c++): 
        $members = $committeeMembers[$c] ?? [];
        $assignedGrpCount = $committeeCounts[$c] ?? 0;
    ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="committee-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); font-size: 0.85rem;">
                            C<?php echo $c; ?>
                        </div>
                        <h6 class="fw-bold m-0 text-dark">Committee <?php echo $c; ?></h6>
                    </div>
                    <span class="badge rounded-pill bg-primary px-3 py-1.5" style="font-size: 0.78rem;">
                        <?php echo $assignedGrpCount; ?> Groups
                    </span>
                </div>
                <div class="small fw-bold text-secondary mb-2 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.7rem;">Evaluators</div>
                <?php if (empty($members)): ?>
                    <p class="text-muted small mb-0 fst-italic">No evaluators assigned yet.</p>
                <?php else: ?>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                        <?php foreach ($members as $m): ?>
                            <li class="d-flex align-items-center justify-content-between p-2 rounded-2" style="background: var(--form-bg);">
                                <span class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo htmlspecialchars($m['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                <small class="text-muted"><?php echo htmlspecialchars($m['designation'] ?? 'Evaluator', ENT_QUOTES, 'UTF-8'); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php endfor; ?>
</div>

<!-- Groups Table -->
<div class="card border-0 shadow-sm" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-header bg-transparent border-0 p-3 p-md-4">
        <h6 class="fw-bold m-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i>Approved Project Groups (<?php echo count($groups); ?>)
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="border-color: var(--border-color);">
            <thead>
                <tr style="background: var(--form-bg);">
                    <th class="ps-4" style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Group Code</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Project Title</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Supervisor</th>
                    <th style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Current Committee</th>
                    <th class="text-end pe-4" style="font-size: 0.78rem; text-transform: uppercase; color: var(--text-secondary);">Reallocate</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($groups)): ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">No approved groups in this department &amp; shift.</td></tr>
                <?php else: ?>
                    <?php foreach ($groups as $g): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="group-code-badge"><?php echo htmlspecialchars($g['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 320px;" title="<?php echo htmlspecialchars($g['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($g['project_title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="small text-muted"><?php echo htmlspecialchars($g['student_shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo htmlspecialchars($g['supervisor_name'] ?? 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></div>
                            </td>
                            <td>
                                <?php if (!empty($g['committee_number'])): ?>
                                    <span class="badge rounded-pill bg-info text-dark px-3 py-1 fw-bold">
                                        Committee <?php echo (int)$g['committee_number']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger px-3 py-1">Unallocated</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <form action="<?php echo $basePath; ?>/admin/committees/reassign" method="POST" class="d-inline-flex align-items-center gap-2">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                    <input type="hidden" name="group_id" value="<?php echo (int)$g['id']; ?>">
                                    <input type="hidden" name="department" value="<?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="shift" value="<?php echo htmlspecialchars($selectedShift, ENT_QUOTES, 'UTF-8'); ?>">
                                    <select name="committee_number" class="form-select form-select-sm" style="width: 140px; border-radius: 8px;">
                                        <?php for ($opt = 1; $opt <= $numCommittees; $opt++): ?>
                                            <option value="<?php echo $opt; ?>" <?php echo ((int)$g['committee_number'] === $opt) ? 'selected' : ''; ?>>
                                                Committee <?php echo $opt; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Distribute Modal -->
<div class="modal fade" id="distributeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; background: var(--card-bg);">
            <form action="<?php echo $basePath; ?>/admin/committees/distribute" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="department" value="<?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="shift" value="<?php echo htmlspecialchars($selectedShift, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="modal-header border-bottom p-3">
                    <h6 class="modal-title fw-bold text-dark">Sequential Group Allocation</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Specify target capacity for each committee. Groups will be assigned in sequential order by group code across <strong><?php echo htmlspecialchars($selectedDept); ?></strong> (<?php echo htmlspecialchars($selectedShift); ?>).
                    </p>
                    <?php for ($c = 1; $c <= $numCommittees; $c++): 
                        $defaultCap = ceil($totalGroups / max(1, $numCommittees));
                    ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Committee <?php echo $c; ?> Target Capacity</label>
                            <input type="number" name="capacity[<?php echo $c; ?>]" class="form-control" value="<?php echo $defaultCap; ?>" min="0" required>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Apply Auto-Distribution</button>
                </div>
            </form>
        </div>
    </div>
</div>
