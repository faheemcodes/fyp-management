<?php
$title = 'Batch Management';
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>



<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="admin-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4 text-center text-md-start">
            <!-- Icon -->
            <div class="admin-hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <!-- Info -->
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.6)">
                    System Administration
                </p>
                <h4 class="fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    Academic Batches
                </h4>
            </div>
        </div>
        
        <!-- Action Button -->
        <div>
            <button class="btn-hero-glass rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createBatchModal">
                <i class="bi bi-plus-lg me-2"></i>Create New Batch
            </button>
        </div>
    </div>
</div>

<div class="border rounded-4 shadow-sm mb-4 p-3" style="background: var(--card-bg); border-color: var(--border-color) !important;">
    <div class="d-flex gap-3 align-items-start">
        <i class="bi bi-info-circle-fill fs-5 mt-1" style="color: #0ea5e9;"></i>
        <div>
            <h6 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 0.9rem;">How Batches Work</h6>
            <ul class="mb-0 ps-3" style="font-size: 0.8rem; color: var(--text-secondary);">
                <li class="mb-1"><strong style="color: var(--text-primary);">Registration:</strong> Only one batch can be open. New groups automatically join it.</li>
                <li><strong style="color: var(--text-primary);">Status:</strong> Active batches are visible to faculty. Archiving a batch hides it from their dashboards and frees up their project slots.</li>
            </ul>
        </div>
    </div>
</div>

<div class="glass-panel p-4 mb-4">
    <div class="table-responsive">
        <table class="table premium-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Batch Name</th>
                    <th>Created On</th>
                    <th class="text-center">Status (Visible to Faculty)</th>
                    <th class="text-center">Registration (New Groups)</th>
                    <th class="text-end pe-4">Actions</th>
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
                            <td class="ps-4 py-3 fw-bold text-dark">
                                <?php echo htmlspecialchars($b['name']); ?>
                            </td>
                            <td class="py-3 text-muted" style="font-size: 0.9rem">
                                <?php echo date('M d, Y', strtotime($b['created_at'])); ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if($b['is_active']): ?>
                                    <span class="premium-badge success"><i class="bi bi-check-circle"></i> Active</span>
                                <?php else: ?>
                                    <span class="premium-badge neutral"><i class="bi bi-archive"></i> Archived</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if($b['is_registration_open']): ?>
                                    <span class="premium-badge primary"><i class="bi bi-door-open"></i> Open</span>
                                <?php else: ?>
                                    <span class="premium-badge neutral"><i class="bi bi-door-closed"></i> Closed</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="<?php echo $bp; ?>/admin/batches/toggle" method="POST" class="m-0">
                                        <input type="hidden" name="batch_id" value="<?php echo htmlspecialchars((string)($b['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <button type="submit" class="table-action-btn <?php echo $b['is_active'] ? 'delete' : 'edit'; ?>" title="<?php echo $b['is_active'] ? 'Archive Batch' : 'Restore Batch'; ?>">
                                            <i class="bi <?php echo $b['is_active'] ? 'bi-archive-fill' : 'bi-arrow-counterclockwise'; ?>"></i>
                                        </button>
                                    
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>

                                    <?php if(!$b['is_registration_open']): ?>
                                    <form action="<?php echo $bp; ?>/admin/batches/toggle" method="POST" class="m-0">
                                        <input type="hidden" name="batch_id" value="<?php echo htmlspecialchars((string)($b['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="set_registration">
                                        <button type="submit" class="table-action-btn edit" title="Set as Registration Batch" style="width: auto; padding: 0 0.5rem; font-size: 0.8rem;">
                                            <i class="bi bi-door-open-fill me-1"></i> Open Reg
                                        </button>
                                    
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
                                    <?php endif; ?>
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

<!-- Create Batch Modal -->
<div class="modal fade premium-modal" id="createBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold">Create New Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $bp; ?>/admin/batches/create" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" style="font-size: 0.85rem">Batch Name (e.g., Fall 2026)</label>
                        <input type="text" class="form-control premium-input" name="name" required placeholder="Enter batch name...">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-premium rounded-pill px-4">Create Batch</button>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>
