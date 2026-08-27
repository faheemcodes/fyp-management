<?php
$title = 'Supervisor Slots & Workload';
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="admin-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="admin-hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <h4 class="fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Supervisor Workload</h4>
                <p class="mb-0 mt-1" style="font-size: 0.85rem">Monitor all assigned capacity limits (max 8 groups/supervisor) for active batches.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-section">
    <!-- Filters and Search Controls -->
    <div class="page-section-header">
        <div class="premium-filter-group w-100">
            <!-- Search Input -->
            <div class="flex-grow-1 d-flex align-items-center px-3">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" class="form-control premium-filter-input table-search" placeholder="Search supervisors by name, department..." data-target="slots-table">
            </div>
            
            <!-- Divider -->
            <div class="premium-filter-divider"></div>
            
            <!-- Department Filter -->
            <div class="d-flex align-items-center px-2" style="flex-basis: 30%;">
                <select class="form-select premium-filter-input table-filter w-100" data-column="department" data-target="slots-table">
                    <option value="all">All Departments</option>
                    <?php 
                    $departments = array_unique(array_filter(array_column($supervisorsList ?? [], 'department')));
                    sort($departments);
                    foreach($departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept); ?>"><?php echo htmlspecialchars($dept); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Divider -->
            <div class="premium-filter-divider"></div>
            
            <!-- Status Filter -->
            <div class="d-flex align-items-center px-2 pe-3" style="flex-basis: 30%;">
                <select class="form-select premium-filter-input table-filter w-100" data-column="status" data-target="slots-table">
                    <option value="all">All Statuses</option>
                    <option value="Available">Available</option>
                    <option value="Full">Full</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table premium-table mb-0" id="slots-table">
            <thead>
                <tr>
                    <th class="ps-4">Supervisor Name</th>
                    <th>Department</th>
                    <th class="text-center">Slot Allocation (Current / 10)</th>
                    <th class="text-center">Remaining Slots</th>
                    <th class="text-end pe-4">Status</th>
                </tr>
            </thead>
                <tbody>
                    <?php if(empty($supervisorsList)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No supervisors found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($supervisorsList as $sup): 
                            $current = (int)$sup['current_slots'];
                            $max = 10;
                            $remaining = $max - $current;
                            $percentage = ($current / $max) * 100;
                            $statusColor = $percentage >= 100 ? 'danger' : ($percentage >= 75 ? 'warning' : 'success');
                            $statusText = $percentage >= 100 ? 'Full' : 'Available';
                        ?>
                        <tr data-department="<?php echo htmlspecialchars($sup['department'] ?? ''); ?>" data-status="<?php echo htmlspecialchars($statusText); ?>">
                            <td class="ps-4 py-3 fw-medium text-dark">
                                <?php echo htmlspecialchars($sup['name']); ?>
                            </td>
                            <td class="py-3 text-muted" style="font-size: 0.9rem">
                                <?php echo htmlspecialchars($sup['department'] ?? '-'); ?>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;max-width: 120px;background: rgba(0,0,0,0.05)">
                                        <div class="progress-bar bg-<?php echo $statusColor; ?>" style="width: <?php echo min(100, $percentage);?>%"></div>
                                    </div>
                                    <span class="fw-semibold text-dark" style="font-size: 0.85rem;min-width: 20px;text-align: right"><?php echo htmlspecialchars((string)($current), ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> border border-<?php echo $statusColor; ?> border-opacity-25" style="font-family: monospace; font-size: 1.1rem; padding: 6px 12px; font-weight: 700;">
                                    <?php echo htmlspecialchars((string)($remaining), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <span class="premium-badge <?php echo $statusColor; ?>">
                                    <?php echo htmlspecialchars((string)($statusText), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
