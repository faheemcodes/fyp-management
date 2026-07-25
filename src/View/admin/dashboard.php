<style>
/* ─── Section Panel ─── */







/* ─── Modern Table Styles ─── */







@media (max-width: 768px) {
    
    
    
    
}
</style>
<!-- Admin Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>


<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <!-- Icon -->
            <div class="page-hero-icon" style="background: transparent">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <!-- Info -->
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    System Administration
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    Super Admin Portal
                </h4>
            </div>
        </div>

        <!-- Stats -->
        <div class="d-flex flex-wrap hero-stats-container">
            <div class="page-stat-pill">
                <span class="stat-num text-info"><?php echo htmlspecialchars((string)($stats['total_users']), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Total Users</span>
            </div>
            <div class="page-stat-pill">
                <span class="stat-num text-success"><?php echo htmlspecialchars((string)($stats['active_projects']), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Active Projects</span>
            </div>
            <div class="page-stat-pill">
                <span class="stat-num text-warning"><?php echo htmlspecialchars((string)($stats['pending_evaluations']), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Pending Evals</span>
            </div>
            <div class="page-stat-pill" style="margin-right: 0">
                <span class="stat-num text-primary"><?php echo htmlspecialchars((string)($stats['avg_marks']), ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="stat-label">Avg Grades</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ═══════════════ Recent Users ═══════════════ -->
    <div class="col-xl-6">
        <div class="page-section h-100 mb-0">
            <div class="page-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <h6>Recent Users</h6>
                        <small>Latest system signups</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/admin/users" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem;padding: 4px 12px">View All</a>
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
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem"><?php echo htmlspecialchars($ru['name']); ?></div>
                                <div class="text-muted" style="font-size: 0.75rem"><?php echo htmlspecialchars($ru['email']); ?></div>
                            </td>
                            <td>
                                <span class="status-pill bg-light text-secondary border">
                                    <?php echo htmlspecialchars($ru['role']); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <?php if($ru['status'] === 'approved'): ?>
                                    <span class="status-pill" style="background: rgba(16,185,129,0.15);color: #059669">Approved</span>
                                <?php elseif($ru['status'] === 'pending'): ?>
                                    <span class="status-pill animate-pulse" style="background: rgba(245,158,11,0.15);color: #d97706">Pending</span>
                                <?php else: ?>
                                    <span class="status-pill" style="background: rgba(239,68,68,0.15);color: #dc2626">Rejected</span>
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
        <div class="page-section h-100 mb-0">
            <div class="page-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div>
                        <h6>Recent Project Groups</h6>
                        <small>Newly formed teams</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/admin/groups" class="btn btn-outline-success btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem;padding: 4px 12px;color: #10b981;border-color: #10b981">View All</a>
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
                            <td class="ps-4 fw-bold" style="color: #10b981;font-size: 0.85rem;font-family: monospace">
                                <?php echo htmlspecialchars($rg['group_code'] ?? 'Pending'); ?>
                            </td>
                            <td>
                                <div class="text-truncate fw-semibold text-dark" style="max-width: 200px;font-size: 0.85rem" title="<?php echo htmlspecialchars($rg['project_title'] ?? 'No Title Yet'); ?>">
                                    <?php echo htmlspecialchars($rg['project_title'] ?? 'No Title Yet'); ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem">By: <?php echo htmlspecialchars($rg['creator_name']); ?></div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="status-pill" style="background: rgba(16,185,129,0.1);color: #10b981">
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
        <div class="page-section mb-0">
            <div class="page-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-section-icon" style="background: rgba(139,92,246,0.1);color: #8b5cf6">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6>Supervisor Slots Status</h6>
                        <small>Monitor assigned capacity limits (max 8 groups/supervisor)</small>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>/admin/slots" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold" style="font-size: 0.75rem;padding: 4px 12px">View All</a>
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
                                <td class="ps-4 fw-bold text-dark" style="font-size: 0.85rem"><?php echo htmlspecialchars($sup['name']); ?></td>
                                <td><span class="text-muted" style="font-size: 0.8rem"><?php echo htmlspecialchars($sup['department']); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;max-width: 150px;background-color: var(--border-color);border-radius: 4px;overflow: hidden">
                                            <div class="progress-bar <?php echo $current >= 8 ? 'bg-danger' : ($current >= 6 ? 'bg-warning' : 'bg-success'); ?>" role="progressbar" style="width: <?php echo ($current / 8) * 100;?>%"></div>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 0.75rem"><?php echo htmlspecialchars((string)($current), ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold <?php echo $remaining === 0 ? 'text-danger' : 'text-success'; ?>" style="font-size: 0.85rem">
                                        <?php echo htmlspecialchars((string)($remaining), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($current >= 8): ?>
                                        <span class="status-pill" style="background: rgba(239,68,68,0.15);color: #dc2626">Full</span>
                                    <?php else: ?>
                                        <span class="status-pill" style="background: rgba(16,185,129,0.15);color: #059669">Available</span>
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
