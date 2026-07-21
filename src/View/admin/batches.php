<?php
$title = 'Batch Management';
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
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
.group-hero-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: 0 4px 15px rgba(59,130,246,0.15);
    flex-shrink: 0;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4 text-center text-md-start">
            <!-- Icon -->
            <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <!-- Info -->
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    System Administration
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                    Academic Batches
                </h4>
            </div>
        </div>
        
        <!-- Action Button -->
        <div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" style="font-weight: 600;" data-bs-toggle="modal" data-bs-target="#createBatchModal">
                <i class="bi bi-plus-lg me-2"></i>Create New Batch
            </button>
        </div>
    </div>
</div>

<div class="alert alert-info border-0 rounded-4 shadow-sm mb-4" role="alert">
    <div class="d-flex gap-3">
        <i class="bi bi-info-circle-fill fs-4 mt-1 text-info"></i>
        <div>
            <h6 class="fw-bold mb-1">How Batches Work</h6>
            <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                <li><strong>Registration:</strong> Only one batch can be open. New groups automatically join it.</li>
                <li><strong>Status:</strong> Active batches are visible to faculty. Archiving a batch hides it from their dashboards and frees up their project slots.</li>
            </ul>
        </div>
    </div>
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
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="<?php echo $bp; ?>/admin/batches/toggle" method="POST" class="m-0">
                                        <input type="hidden" name="batch_id" value="<?php echo htmlspecialchars((string)($b['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <button type="submit" class="btn btn-sm <?php echo $b['is_active'] ? 'btn-outline-warning' : 'btn-outline-success'; ?> rounded-pill" title="<?php echo $b['is_active'] ? 'Archive Batch' : 'Restore Batch'; ?>">
                                            <i class="bi <?php echo $b['is_active'] ? 'bi-archive-fill' : 'bi-arrow-counterclockwise'; ?>"></i>
                                            <span class="ms-1 d-none d-md-inline"><?php echo $b['is_active'] ? 'Archive' : 'Restore'; ?></span>
                                        </button>
                                    
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>

                                    <?php if(!$b['is_registration_open']): ?>
                                    <form action="<?php echo $bp; ?>/admin/batches/toggle" method="POST" class="m-0">
                                        <input type="hidden" name="batch_id" value="<?php echo htmlspecialchars((string)($b['id']), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="set_registration">
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill" title="Set as Registration Batch">
                                            <i class="bi bi-door-open-fill"></i>
                                            <span class="ms-1 d-none d-md-inline">Open Registration</span>
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
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>
