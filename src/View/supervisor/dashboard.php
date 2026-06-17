<!-- Supervisor Dashboard View -->
<div class="row g-4 mb-4">
    <!-- Stat card 1 -->
    <div class="col-md-4">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Assigned Groups</h6>
                <h3 class="m-0 fw-bold"><?php echo $groupCount; ?></h3>
            </div>
            <div class="card-icon icon-primary">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    
    <!-- Stat card 2 -->
    <div class="col-md-4">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Pending Proposals</h6>
                <h3 class="m-0 fw-bold text-warning animate-pulse"><?php echo $pendingProposals; ?></h3>
            </div>
            <div class="card-icon icon-warning">
                <i class="bi bi-file-earmark-exclamation-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat card 3 -->
    <div class="col-md-4">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Pending File Reviews</h6>
                <h3 class="m-0 fw-bold text-danger"><?php echo $pendingDocs; ?></h3>
            </div>
            <div class="card-icon icon-danger">
                <i class="bi bi-cloud-arrow-down-fill"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-4">Your Assigned FYP Groups</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none;">
            <thead>
                <tr>
                    <th>Group Code</th>
                    <th>Project Title</th>
                    <th>Project Status</th>
                    <th>Milestone Stage</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($g['group_code']); ?></td>
                    <td>
                        <div class="fw-semibold text-dark text-truncate" style="max-width: 350px;" title="<?php echo htmlspecialchars($g['project_title']); ?>">
                            <?php echo htmlspecialchars($g['project_title']); ?>
                        </div>
                    </td>
                    <td>
                        <?php if($g['project_status'] === 'Approved'): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">Approved</span>
                        <?php elseif($g['project_status'] === 'Submitted'): ?>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small">Submitted</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 small"><?php echo htmlspecialchars($g['project_status']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                            <?php echo htmlspecialchars($g['progress_stage']); ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/supervisor/groups" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No student groups assigned to you yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
