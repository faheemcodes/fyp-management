<?php
$title = 'Batch Management';
$bp = '/fyp-management/public';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Academic Batches</h3>
        <p class="text-muted mb-0">Manage FYP sessions and academic years.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createBatchModal">
        <i class="bi bi-plus-lg me-2"></i>Create New Batch
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Batch Name</th>
                        <th class="py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Created On</th>
                        <th class="py-3 text-muted text-center" style="font-weight: 600; font-size: 0.85rem;">Status (Visible to Faculty)</th>
                        <th class="py-3 text-muted text-center" style="font-weight: 600; font-size: 0.85rem;">Registration (New Groups)</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.85rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($batches)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No batches found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($batches as $b): ?>
                        <tr>
                            <td class="px-4 py-3 fw-bold text-dark">
                                <?php echo htmlspecialchars($b['name']); ?>
                            </td>
                            <td class="py-3 text-muted" style="font-size: 0.9rem;">
                                <?php echo date('M d, Y', strtotime($b['created_at'])); ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if($b['is_active']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill"><i class="bi bi-archive me-1"></i>Archived</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if($b['is_registration_open']): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="bi bi-door-open me-1"></i>Open</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill"><i class="bi bi-door-closed me-1"></i>Closed</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li>
                                            <form action="<?php echo $bp; ?>/admin/batches/toggle" method="POST">
                                                <input type="hidden" name="batch_id" value="<?php echo $b['id']; ?>">
                                                <input type="hidden" name="action" value="toggle_active">
                                                <button type="submit" class="dropdown-item py-2">
                                                    <i class="bi <?php echo $b['is_active'] ? 'bi-archive text-warning' : 'bi-arrow-counterclockwise text-success'; ?> me-2"></i>
                                                    <?php echo $b['is_active'] ? 'Archive Batch' : 'Restore Batch'; ?>
                                                </button>
                                            </form>
                                        </li>
                                        <?php if(!$b['is_registration_open']): ?>
                                        <li>
                                            <form action="<?php echo $bp; ?>/admin/batches/toggle" method="POST">
                                                <input type="hidden" name="batch_id" value="<?php echo $b['id']; ?>">
                                                <input type="hidden" name="action" value="set_registration">
                                                <button type="submit" class="dropdown-item py-2">
                                                    <i class="bi bi-door-open text-primary me-2"></i>Set as Registration Batch
                                                </button>
                                            </form>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="alert alert-info border-0 rounded-4 shadow-sm" role="alert">
    <div class="d-flex gap-3">
        <i class="bi bi-info-circle-fill fs-4 mt-1 text-info"></i>
        <div>
            <h6 class="fw-bold mb-1">How Batches Work</h6>
            <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                <li><strong>Registration:</strong> Only ONE batch can be open for registration at a time. All newly formed student groups will automatically fall into this batch.</li>
                <li><strong>Status (Active vs Archived):</strong> You can have multiple batches active simultaneously. Active batches are visible to Supervisors and Committees to evaluate. Archiving a batch hides those projects from faculty dashboards and resets their supervision limits for the new cycle!</li>
            </ul>
        </div>
    </div>
</div>

<!-- Create Batch Modal -->
<div class="modal fade" id="createBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Create New Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $bp; ?>/admin/batches/create" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" style="font-size: 0.85rem;">Batch Name (e.g., Fall 2026)</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter batch name...">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>
