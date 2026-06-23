<!-- Committee Dashboard View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
$fullName = trim($_SESSION['name'] ?? 'Committee Member');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>

<style>
/* ── Welcome banner gradient ── */
.dash-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f2a4a 100%);
    border-radius: var(--border-radius-lg);
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
    border-radius: var(--border-radius-md);
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
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.stat-mini-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    margin-bottom: 3px;
}
.stat-mini-value {
    font-size: 1.15rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--text-primary);
}

/* ── Modern Table overrides ── */
.modern-table-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}
.modern-table-card .card-header {
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 20px 24px;
}
.modern-table-card .card-header h5 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
}
.modern-table-card .card-header p {
    font-size: 0.78rem;
    color: var(--text-secondary);
    margin: 4px 0 0 0;
}
.modern-table-card table {
    margin: 0;
}
.modern-table-card th {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 14px 24px;
}
.modern-table-card td {
    padding: 16px 24px;
    vertical-align: middle;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--border-color);
}
.modern-table-card tr:last-child td {
    border-bottom: none;
}
</style>

<!-- ── Welcome Banner ── -->
<div class="dash-banner">
    <div class="position-relative" style="z-index: 1;">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <p class="mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.45);">
                    <i class="bi bi-person-badge me-1"></i>Evaluation Committee
                </p>
                <h4 class="fw-bold text-white mb-1" style="font-size: 1.35rem; letter-spacing: -0.02em; max-width: 500px;">
                    Welcome back, <?php echo htmlspecialchars($firstName); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                    <span style="font-size: 0.75rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                        <?php echo htmlspecialchars($committee['department'] ?? 'Department'); ?>
                    </span>
                </div>
            </div>
            <div class="text-end d-none d-md-block">
                <a href="<?php echo $bp; ?>/committee/evaluations" class="btn text-white mt-2" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px; transition: all 0.2s;">
                    <i class="bi bi-pencil-square me-2"></i>Go to Evaluations
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ── Stat Mini Cards Row ── -->
<div class="row g-3 mb-4">
    <!-- Assigned Projects -->
    <div class="col-12 col-md-4">
        <div class="stat-mini d-flex align-items-center gap-3">
            <div class="stat-mini-icon" style="background: rgba(59,130,246,0.1);">
                <i class="bi bi-folder-fill" style="color: #3b82f6;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Assigned Projects</div>
                <div class="stat-mini-value text-primary">
                    <?php echo $totalGroups; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Evaluations -->
    <div class="col-12 col-md-4">
        <a href="<?php echo $bp; ?>/committee/evaluations" class="text-decoration-none d-block" style="color: inherit;">
            <div class="stat-mini d-flex align-items-center gap-3">
                <div class="stat-mini-icon" style="background: rgba(245,158,11,0.1);">
                    <i class="bi bi-hourglass-split" style="color: #f59e0b;"></i>
                </div>
                <div>
                    <div class="stat-mini-label">Pending Evaluations</div>
                    <div class="stat-mini-value" style="color: <?php echo $pendingCount > 0 ? '#f59e0b' : 'var(--text-primary)'; ?>;">
                        <?php echo $pendingCount; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Graded Evaluations -->
    <div class="col-12 col-md-4">
        <div class="stat-mini d-flex align-items-center gap-3">
            <div class="stat-mini-icon" style="background: rgba(16,185,129,0.1);">
                <i class="bi bi-patch-check-fill" style="color: #059669;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Graded Evaluations</div>
                <div class="stat-mini-value" style="color: #059669;">
                    <?php echo $gradedCount; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="modern-table-card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5>FYP Student Groups & Stages</h5>
            <p>Review the current progress stage of your assigned project teams</p>
        </div>
        <a href="<?php echo $bp; ?>/committee/evaluations" class="btn btn-sm btn-primary d-md-none rounded-pill px-3">
            <i class="bi bi-pencil-square"></i>
        </a>
    </div>

    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead>
                <tr>
                    <th>Group Code</th>
                    <th>Project Title</th>
                    <th>Supervisor</th>
                    <th>Progress Stage</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr>
                    <td>
                        <span class="fw-semibold text-secondary" style="font-family: monospace; font-size: 0.8rem; background: var(--form-bg); padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border-color);">
                            <?php echo htmlspecialchars($g['group_code']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark text-wrap" style="max-width: 320px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>">
                             <?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>
                        </div>
                    </td>
                    <td>
                        <?php if($g['supervisor_name']): ?>
                            <span class="small fw-semibold" style="color: var(--text-primary);"><i class="bi bi-person-badge text-success me-1"></i><?php echo htmlspecialchars($g['supervisor_name']); ?></span>
                        <?php else: ?>
                            <span class="text-muted small" style="font-style: italic;">Unassigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="font-size: 0.65rem; background: rgba(59,130,246,0.1); color: #2563eb; padding: 4px 10px; border-radius: 20px; font-weight: 700; text-transform: uppercase;">
                            <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo $bp; ?>/committee/evaluations" class="btn btn-sm text-primary" style="background: rgba(59,130,246,0.1); border-radius: 8px; font-weight: 600; font-size: 0.75rem; padding: 6px 12px; white-space: nowrap;">
                            <i class="bi bi-pencil-square me-1"></i>Evaluate
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-folder-x fs-3 d-block mb-2 text-opacity-50"></i>
                            No project groups registered in the platform yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card List -->
    <div class="d-block d-md-none p-3">
        <?php foreach($groups as $g): ?>
            <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg);">
                <div class="mb-2">
                    <span class="fw-semibold text-secondary" style="font-family: monospace; font-size: 0.75rem; background: var(--form-bg); padding: 3px 6px; border-radius: 4px; border: 1px solid var(--border-color);">
                        <?php echo htmlspecialchars($g['group_code']); ?>
                    </span>
                </div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    <?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>
                </h6>
                <div class="mb-3">
                    <span style="font-size: 0.65rem; background: rgba(59,130,246,0.1); color: #2563eb; padding: 4px 10px; border-radius: 20px; font-weight: 700; text-transform: uppercase; display: inline-block;">
                        <?php echo htmlspecialchars($g['progress_stage']); ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-3 border-top">
                    <div style="color: var(--text-secondary); font-size: 0.75rem; line-height: 1.2; max-width: 60%;">
                        <i class="bi bi-person-badge text-success me-1"></i>
                        <?php echo $g['supervisor_name'] ? htmlspecialchars($g['supervisor_name']) : 'Unassigned'; ?>
                    </div>
                    <a href="<?php echo $bp; ?>/committee/evaluations" class="btn btn-sm text-primary" style="background: rgba(59,130,246,0.1); border-radius: 8px; font-weight: 600; font-size: 0.75rem; padding: 6px 14px;">
                        Evaluate
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($groups)): ?>
            <div class="text-center text-muted py-4 bg-light rounded-3 small">
                No project groups registered in the platform yet.
            </div>
        <?php endif; ?>
    </div>
</div>
