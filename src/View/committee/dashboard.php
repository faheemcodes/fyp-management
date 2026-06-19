<!-- Committee Dashboard View -->
<div class="row g-4 mb-4">
    <!-- Stat card 1 -->
    <div class="col-md-4">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Assigned Projects</h6>
                <h3 class="m-0 fw-bold"><?php echo $totalGroups; ?></h3>
            </div>
            <div class="card-icon icon-primary">
                <i class="bi bi-folder-fill"></i>
            </div>
        </div>
    </div>
    
    <!-- Stat card 2 -->
    <div class="col-md-4">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Pending Evaluations</h6>
                <h3 class="m-0 fw-bold text-warning animate-pulse"><?php echo $pendingCount; ?></h3>
            </div>
            <div class="card-icon icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <!-- Stat card 3 -->
    <div class="col-md-4">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Graded Evaluations</h6>
                <h3 class="m-0 fw-bold text-success"><?php echo $gradedCount; ?></h3>
            </div>
            <div class="card-icon icon-secondary">
                <i class="bi bi-patch-check-fill"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="mb-4">
        <h5 class="fw-bold text-dark m-0">FYP Student Groups & Stages</h5>
        <p class="text-muted m-0 small">Review the current progress stage of university project teams</p>
    </div>
    
    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none;">
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
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($g['group_code']); ?></td>
                    <td>
                        <div class="fw-semibold text-dark text-wrap" style="max-width: 320px;">
                             <?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>
                        </div>
                    </td>
                    <td>
                        <?php if($g['supervisor_name']): ?>
                            <span class="small fw-semibold text-dark"><i class="bi bi-person-badge text-success me-1"></i><?php echo htmlspecialchars($g['supervisor_name']); ?></span>
                        <?php else: ?>
                            <span class="text-muted small">Unassigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                            <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations" class="btn btn-sm btn-outline-primary rounded-pill px-3">Evaluate</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No project groups registered in the platform yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card List -->
    <div class="d-block d-md-none">
        <?php foreach($groups as $g): ?>
            <div class="card border rounded-3 p-3 mb-3 bg-light-subtle shadow-xs">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-primary"><?php echo htmlspecialchars($g['group_code']); ?></span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small" style="font-size: 0.7rem;">
                        <?php echo htmlspecialchars($g['progress_stage']); ?>
                    </span>
                </div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;"><?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?></h6>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    <small class="text-muted">
                        <i class="bi bi-person-badge text-success me-1"></i>
                        <?php echo $g['supervisor_name'] ? htmlspecialchars($g['supervisor_name']) : 'Unassigned'; ?>
                    </small>
                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations" class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;">Evaluate</a>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($groups)): ?>
            <div class="text-center text-muted py-4 bg-light rounded-3 small">No project groups registered in the platform yet.</div>
        <?php endif; ?>
    </div>
</div>
