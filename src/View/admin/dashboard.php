<!-- Admin Dashboard View -->
<div class="row g-4 mb-4">
    <!-- Stat card 1 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Total Users</h6>
                <h3 class="m-0 fw-bold"><?php echo $stats['total_users']; ?></h3>
            </div>
            <div class="card-icon icon-primary">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    
    <!-- Stat card 2 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Active Projects</h6>
                <h3 class="m-0 fw-bold text-success"><?php echo $stats['active_projects']; ?></h3>
            </div>
            <div class="card-icon icon-secondary">
                <i class="bi bi-folder-check"></i>
            </div>
        </div>
    </div>

    <!-- Stat card 3 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Pending Evals</h6>
                <h3 class="m-0 fw-bold text-warning"><?php echo $stats['pending_evaluations']; ?></h3>
            </div>
            <div class="card-icon icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <!-- Stat card 4 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Avg Grades</h6>
                <h3 class="m-0 fw-bold text-primary"><?php echo $stats['avg_marks']; ?></h3>
            </div>
            <div class="card-icon icon-primary">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Users -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-dark">Recent Signups / Users</h5>
                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users" class="btn btn-outline-primary btn-sm rounded-pill">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentUsers as $ru): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($ru['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($ru['email']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-secondary text-uppercase" style="font-size: 0.65rem;">
                                    <?php echo htmlspecialchars($ru['role']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($ru['status'] === 'approved'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">Approved</span>
                                <?php elseif($ru['status'] === 'pending'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1 small animate-pulse">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 small">Rejected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Groups -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-dark">Recent Project Groups</h5>
                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/groups" class="btn btn-outline-primary btn-sm rounded-pill">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Group Code</th>
                            <th>Project Title</th>
                            <th>Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentGroups as $rg): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($rg['group_code'] ?? 'Pending'); ?></td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($rg['project_title'] ?? 'No Title Yet'); ?>">
                                    <?php echo htmlspecialchars($rg['project_title'] ?? 'No Title Yet'); ?>
                                </div>
                                <small class="text-muted">By: <?php echo htmlspecialchars($rg['creator_name']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 small">
                                    <?php echo htmlspecialchars($rg['progress_stage']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentGroups)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No project groups created yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Supervisor Slots Status -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold text-dark m-0"><i class="bi bi-person-badge-fill text-primary me-2"></i> Supervisor Slots Status</h5>
                <p class="text-muted small m-0">Monitor approved project counts and capacity limits (maximum 8 groups per supervisor)</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Supervisor Name</th>
                            <th>Department</th>
                            <th>Current Slots Assigned</th>
                            <th>Remaining Slots</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($supervisorsList as $sup): ?>
                            <?php 
                            $current = (int)$sup['current_slots'];
                            $remaining = max(0, 8 - $current);
                            ?>
                            <tr>
                                <td><strong class="text-dark"><?php echo htmlspecialchars($sup['name']); ?></strong></td>
                                <td><span class="small text-muted"><?php echo htmlspecialchars($sup['department']); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px; max-width: 150px; background-color: var(--border-color); border-radius: 4px; overflow: hidden;">
                                            <div class="progress-bar <?php echo $current >= 8 ? 'bg-danger' : ($current >= 6 ? 'bg-warning' : 'bg-success'); ?>" role="progressbar" style="width: <?php echo ($current / 8) * 100; ?>%"></div>
                                        </div>
                                        <span class="fw-bold small text-dark"><?php echo $current; ?> / 8</span>
                                    </div>
                                </td>
                                <td><span class="fw-bold <?php echo $remaining === 0 ? 'text-danger' : 'text-success'; ?> small"><?php echo $remaining; ?></span></td>
                                <td>
                                    <?php if($current >= 8): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small">Full</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">Available</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($supervisorsList)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No supervisors registered yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
