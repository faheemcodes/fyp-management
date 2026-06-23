<!-- Admin Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<style>
/* ─── Hero Banner Styles ─── */
.group-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.group-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero-icon {
    width: 56px;
    height: 56px;
    background: conic-gradient(from 0deg, #3b82f6, #6366f1, #8b5cf6, #3b82f6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}
.group-stat-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 20px;
    background: rgba(255,255,255,0.06);
    border-radius: 12px;
    min-width: 90px;
    margin-right: 12px;
}
.group-stat-pill .stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    letter-spacing: -0.03em;
}
.group-stat-pill .stat-label {
    font-size: 0.62rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: rgba(255,255,255,0.4);
    margin-top: 4px;
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
    font-size: 1rem;
    flex-shrink: 0;
}
.grp-section-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}
.grp-section-header small {
    font-size: 0.72rem;
    color: var(--text-secondary);
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
    background-color: rgba(59,130,246,0.02);
}
.modern-table tbody tr:last-child td {
    border-bottom: none;
}
.status-pill {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    display: inline-block;
}

@media (max-width: 768px) {
    .group-hero { padding: 24px 16px; }
    .group-stat-pill { margin-bottom: 10px; min-width: calc(50% - 12px); }
    .hero-stats-container { flex-wrap: wrap; justify-content: center; margin-top: 20px; }
    .modern-table { min-width: 600px; }
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="group-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <!-- Icon -->
            <div class="group-hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <!-- Info -->
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    System Administration
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                    Super Admin Portal
                </h4>
            </div>
        </div>

        <!-- Stats -->
        <div class="d-flex flex-wrap hero-stats-container">
            <div class="group-stat-pill">
                <span class="stat-num text-info"><?php echo $stats['total_users']; ?></span>
                <span class="stat-label">Total Users</span>
            </div>
            <div class="group-stat-pill">
                <span class="stat-num text-success"><?php echo $stats['active_projects']; ?></span>
                <span class="stat-label">Active Projects</span>
            </div>
            <div class="group-stat-pill">
                <span class="stat-num text-warning"><?php echo $stats['pending_evaluations']; ?></span>
                <span class="stat-label">Pending Evals</span>
            </div>
            <div class="group-stat-pill" style="margin-right: 0;">
                <span class="stat-num text-primary"><?php echo $stats['avg_marks']; ?></span>
                <span class="stat-label">Avg Grades</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ═══════════════ Recent Users ═══════════════ -->
    <div class="col-xl-6">
        <div class="grp-section h-100 mb-0">
            <div class="grp-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="grp-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <h6>Recent Users</h6>
                        <small>Latest system signups</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/admin/users" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem; padding: 4px 12px;">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table modern-table m-0">
                    <thead>
                        <tr>
                            <th class="ps-4">User Details</th>
                            <th>Role</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentUsers as $ru): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo htmlspecialchars($ru['name']); ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($ru['email']); ?></div>
                            </td>
                            <td>
                                <span class="status-pill bg-light text-secondary border">
                                    <?php echo htmlspecialchars($ru['role']); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <?php if($ru['status'] === 'approved'): ?>
                                    <span class="status-pill" style="background: rgba(16,185,129,0.15); color: #059669;">Approved</span>
                                <?php elseif($ru['status'] === 'pending'): ?>
                                    <span class="status-pill animate-pulse" style="background: rgba(245,158,11,0.15); color: #d97706;">Pending</span>
                                <?php else: ?>
                                    <span class="status-pill" style="background: rgba(239,68,68,0.15); color: #dc2626;">Rejected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════════ Recent Groups ═══════════════ -->
    <div class="col-xl-6">
        <div class="grp-section h-100 mb-0">
            <div class="grp-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="grp-section-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div>
                        <h6>Recent Project Groups</h6>
                        <small>Newly formed teams</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/admin/groups" class="btn btn-outline-success btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem; padding: 4px 12px; color: #10b981; border-color: #10b981;">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table modern-table m-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Group Code</th>
                            <th>Project Title</th>
                            <th class="text-end pe-4">Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentGroups as $rg): ?>
                        <tr>
                            <td class="ps-4 fw-bold" style="color: #10b981; font-size: 0.85rem; font-family: monospace;">
                                <?php echo htmlspecialchars($rg['group_code'] ?? 'Pending'); ?>
                            </td>
                            <td>
                                <div class="text-truncate fw-semibold text-dark" style="max-width: 200px; font-size: 0.85rem;" title="<?php echo htmlspecialchars($rg['project_title'] ?? 'No Title Yet'); ?>">
                                    <?php echo htmlspecialchars($rg['project_title'] ?? 'No Title Yet'); ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">By: <?php echo htmlspecialchars($rg['creator_name']); ?></div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="status-pill" style="background: rgba(16,185,129,0.1); color: #10b981;">
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

<div class="row g-4 mt-0 mb-4">
    <!-- ═══════════════ Supervisor Slots ═══════════════ -->
    <div class="col-12">
        <div class="grp-section mb-0">
            <div class="grp-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="grp-section-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6>Supervisor Slots Status</h6>
                        <small>Monitor assigned capacity limits (max 8 groups/supervisor)</small>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table modern-table m-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Supervisor Name</th>
                            <th>Department</th>
                            <th>Slot Allocation (Current/8)</th>
                            <th class="text-center">Remaining Slots</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($supervisorsList as $sup): ?>
                            <?php 
                            $current = (int)$sup['current_slots'];
                            $remaining = max(0, 8 - $current);
                            ?>
                            <tr>
                                <td class="ps-4 fw-bold text-dark" style="font-size: 0.85rem;"><?php echo htmlspecialchars($sup['name']); ?></td>
                                <td><span class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($sup['department']); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px; max-width: 150px; background-color: var(--border-color); border-radius: 4px; overflow: hidden;">
                                            <div class="progress-bar <?php echo $current >= 8 ? 'bg-danger' : ($current >= 6 ? 'bg-warning' : 'bg-success'); ?>" role="progressbar" style="width: <?php echo ($current / 8) * 100; ?>%"></div>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 0.75rem;"><?php echo $current; ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold <?php echo $remaining === 0 ? 'text-danger' : 'text-success'; ?>" style="font-size: 0.85rem;">
                                        <?php echo $remaining; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($current >= 8): ?>
                                        <span class="status-pill" style="background: rgba(239,68,68,0.15); color: #dc2626;">Full</span>
                                    <?php else: ?>
                                        <span class="status-pill" style="background: rgba(16,185,129,0.15); color: #059669;">Available</span>
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
