<!-- Committee Dashboard View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
$fullName = trim($_SESSION['name'] ?? 'Committee Member');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>

<style>
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

<!-- ── Top Hero Banner ── -->
<div class="group-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Evaluation Committee
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                    Welcome back, <?php echo htmlspecialchars($firstName); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 justify-content-center justify-content-md-start flex-wrap">
                    <span style="font-size: 0.75rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                        <?php echo htmlspecialchars($committee['department'] ?? 'Department'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <div class="group-stat-pill">
                <span class="stat-num text-info"><?php echo htmlspecialchars((string)($totalGroups), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Assigned Projects</span>
            </div>
            <a href="<?php echo $bp; ?>/committee/evaluations" class="text-decoration-none">
                <div class="group-stat-pill" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num" style="color: <?php echo $pendingCount > 0 ? '#f59e0b' : 'var(--text-secondary)'; ?>;"><?php echo htmlspecialchars((string)($pendingCount), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="stat-label text-white">Pending Evals</span>
                </div>
            </a>
            <div class="group-stat-pill" style="margin-right: 0;">
                <span class="stat-num text-success"><?php echo htmlspecialchars((string)($gradedCount), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Graded Evals</span>
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
