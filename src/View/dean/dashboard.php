<!-- Dean Dashboard View -->
<div class="row g-4 mb-4">
    <!-- Stat card 1 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Supervisors</h6>
                <h3 class="m-0 fw-bold"><?php echo $stats['supervisors']; ?></h3>
            </div>
            <div class="card-icon icon-primary">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        </div>
    </div>
    
    <!-- Stat card 2 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Committee Members</h6>
                <h3 class="m-0 fw-bold"><?php echo $stats['committee']; ?></h3>
            </div>
            <div class="card-icon icon-secondary">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat card 3 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Total FYP Groups</h6>
                <h3 class="m-0 fw-bold text-success"><?php echo $stats['total_groups']; ?></h3>
            </div>
            <div class="card-icon icon-warning">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat card 4 -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="text-muted small text-uppercase mb-2 font-weight-bold">Pending Approvals</h6>
                <h3 class="m-0 fw-bold text-warning"><?php echo $stats['pending_approvals']; ?></h3>
            </div>
            <div class="card-icon icon-danger">
                <i class="bi bi-person-exclamation"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Supervisors -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-dark">Recently Added Supervisors</h5>
                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/dean/supervisors" class="btn btn-outline-primary btn-sm rounded-pill">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Name / Email</th>
                            <th>Designation</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentSupervisors as $rs): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($rs['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($rs['email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($rs['designation']); ?></td>
                            <td><span class="small text-muted"><?php echo htmlspecialchars($rs['department']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentSupervisors)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No supervisors registered yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Committee Members -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-dark">Recently Added Committee Members</h5>
                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/dean/committee" class="btn btn-outline-primary btn-sm rounded-pill">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Name / Email</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentCommittee as $rc): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($rc['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($rc['email']); ?></small>
                            </td>
                            <td><span class="small text-muted"><?php echo htmlspecialchars($rc['department']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentCommittee)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No committee members added yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
