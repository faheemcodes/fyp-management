<!-- HOD Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<style>






@media (min-width: 576px) {
    
}



/* ─── Section Panel ─── */
.grp-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 24px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.grp-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
}
.grp-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}
.grp-section-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #fff;
    flex-shrink: 0;
}
.grp-section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-color);
    margin: 0;
}

/* ─── Modern Table Styles ─── */
.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.modern-table thead th {
    background: var(--form-bg);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-secondary);
    font-weight: 700;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}
.modern-table tbody td {
    padding: 16px 24px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    background: var(--card-bg);
    transition: background-color 0.2s ease;
}
.modern-table tbody tr:hover td {
    background: var(--hover-bg);
}
.modern-table tbody tr:last-child td {
    border-bottom: none;
}
</style>

<!-- Top Hero Banner -->
<div class="group-hero">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: transparent;">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.45rem; letter-spacing: -0.02em;">Department Overview</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Manage faculty, coordinate groups, and monitor academic progress</p>
            </div>
        </div>
        
        <!-- Stats Row inside Hero -->
        <div class="d-flex flex-wrap justify-content-center gap-2 w-100 mt-3 mt-lg-0" style="max-width: 480px;">
            <div class="group-stat-pill">
                <span class="stat-num"><?php echo htmlspecialchars($stats['supervisors'] ?? '0'); ?></span>
                <span class="stat-label">Supervisors</span>
            </div>
            <div class="group-stat-pill">
                <span class="stat-num"><?php echo htmlspecialchars($stats['committee'] ?? '0'); ?></span>
                <span class="stat-label">Committee</span>
            </div>
            <div class="group-stat-pill">
                <span class="stat-num text-info"><?php echo htmlspecialchars($stats['total_groups'] ?? '0'); ?></span>
                <span class="stat-label">FYP Groups</span>
            </div>
            <div class="group-stat-pill">
                <span class="stat-num text-warning"><?php echo htmlspecialchars($stats['pending_approvals'] ?? '0'); ?></span>
                <span class="stat-label">Pending</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Supervisors -->
    <div class="col-xl-6">
        <div class="grp-section h-100 mb-0">
            <div class="grp-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="grp-section-icon shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <h5 class="grp-section-title">Recently Added Supervisors</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Latest faculty members</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/hod/supervisors" class="btn btn-outline-primary btn-sm rounded-pill px-3" style="font-size: 0.75rem; font-weight: 600;">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>Faculty Name</th>
                            <th>Designation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentSupervisors as $rs): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                        <?php echo strtoupper(substr($rs['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 0.9rem;"><?php echo htmlspecialchars($rs['name']); ?></div>
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($rs['email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($rs['designation']); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentSupervisors)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No supervisors registered yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Active Committee Members -->
    <div class="col-xl-6">
        <div class="grp-section h-100 mb-0">
            <div class="grp-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="grp-section-icon shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h5 class="grp-section-title">Active Committee Members</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Evaluation panel</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/hod/committee" class="btn btn-outline-primary btn-sm rounded-pill px-3" style="font-size: 0.75rem; font-weight: 600;">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentCommittee as $rc): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                        <?php echo strtoupper(substr($rc['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 0.9rem;"><?php echo htmlspecialchars($rc['name']); ?></div>
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($rc['email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary small fw-medium"><?php echo htmlspecialchars($rc['department']); ?></span>
                            </td>
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
