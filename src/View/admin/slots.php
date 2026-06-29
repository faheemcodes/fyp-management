<?php
$title = 'Supervisor Slots & Workload';
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Supervisor Workload</h3>
        <p class="text-muted mb-0">Monitor all assigned capacity limits (max 8 groups/supervisor) for active batches.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Supervisor Name</th>
                        <th class="py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Department</th>
                        <th class="py-3 text-muted text-center" style="font-weight: 600; font-size: 0.85rem;">Slot Allocation (Current/8)</th>
                        <th class="py-3 text-muted text-center" style="font-weight: 600; font-size: 0.85rem;">Remaining Slots</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.85rem;">Status</th>
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
                            $max = 8;
                            $remaining = max(0, $max - $current);
                            $percentage = ($current / $max) * 100;
                            $statusColor = $percentage >= 100 ? 'danger' : ($percentage >= 75 ? 'warning' : 'success');
                            $statusText = $percentage >= 100 ? 'Full' : 'Available';
                        ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-dark">
                                <?php echo htmlspecialchars($sup['name']); ?>
                            </td>
                            <td class="py-3 text-muted" style="font-size: 0.9rem;">
                                <?php echo htmlspecialchars($sup['department'] ?? '-'); ?>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px; max-width: 120px; background: rgba(0,0,0,0.05);">
                                        <div class="progress-bar bg-<?php echo $statusColor; ?>" style="width: <?php echo min(100, $percentage); ?>%"></div>
                                    </div>
                                    <span class="fw-semibold text-dark" style="font-size: 0.85rem; min-width: 20px; text-align: right;"><?php echo $current; ?></span>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="fw-bold text-<?php echo $statusColor; ?> fs-5"><?php echo $remaining; ?></span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> px-3 py-2 rounded-pill" style="letter-spacing: 0.05em;">
                                    <?php echo $statusText; ?>
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
