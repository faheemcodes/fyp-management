<?php
$title = 'Academic Batches - Coordinator Portal';
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="coordinator-hero mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 16px; padding: 2rem; color: #fff; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-3 text-center text-md-start">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; border: 1px solid rgba(255, 255, 255, 0.15);">
                <i class="bi bi-box-seam-fill text-primary-light"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.7);">
                    Department Coordinator &bull; <?php echo htmlspecialchars($department ?? 'Software Engineering'); ?>
                </p>
                <h3 class="fw-bold m-0" style="letter-spacing: -0.02em;">
                    Academic Batches (<?php echo htmlspecialchars($shift ?? 'Morning'); ?> Shift)
                </h3>
            </div>
        </div>
        
        <div>
            <button class="btn btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#createBatchModal" style="color: #0f172a;">
                <i class="bi bi-plus-lg me-2 text-primary"></i>Create New Batch
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════ Lifecycle Explanatory Notice ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: rgba(59, 130, 246, 0.05); border-left: 4px solid #3b82f6 !important;">
    <div class="d-flex gap-3 align-items-start">
        <div class="p-2 rounded-circle bg-blue-100 text-primary mt-1" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.15);">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 0.95rem;">Academic Batch Lifecycle & Automatic Shift</h6>
            <div class="row g-2 mt-1" style="font-size: 0.83rem; color: #475569;">
                <div class="col-md-6">
                    <p class="mb-1"><strong>&bull; Incoming Students:</strong> Auto-assigned to the active registration batch of your department &amp; shift.</p>
                    <p class="mb-0"><strong>&bull; Supervisor Dashboards:</strong> Faculty view current active batch projects. Prior batches shift to <em>Previous Projects</em>.</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>&bull; Previous Students:</strong> Retain continuous view and download access to their project details, thesis, and grades. Chat &amp; scheduling are closed.</p>
                    <p class="mb-0"><strong>&bull; Storage Optimization:</strong> Chat attachments &amp; chat records are cleaned up when a batch concludes. Project proposals, abstracts, and theses remain available in the repository.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Batches List Table ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <tr>
                    <th class="ps-4 py-3" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Batch Name</th>
                    <th class="py-3" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Shift</th>
                    <th class="py-3" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Groups / Projects</th>
                    <th class="py-3 text-center" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Batch Status</th>
                    <th class="py-3 text-center" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Registration Status</th>
                    <th class="py-3" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Created Date</th>
                    <th class="pe-4 py-3 text-end" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($batches)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            No academic batches found for your department and shift.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($batches as $b): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle p-2 bg-light text-dark border">
                                        <i class="bi bi-mortarboard-fill text-primary"></i>
                                    </span>
                                    <div>
                                        <span class="fw-bold text-dark d-block"><?php echo htmlspecialchars($b['name'] ?? ''); ?></span>
                                        <small class="text-muted"><?php echo htmlspecialchars($b['department'] ?? ''); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-pill bg-light text-dark border px-3 py-1">
                                    <?php echo htmlspecialchars($b['shift'] ?? 'Morning'); ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="fw-semibold text-dark"><?php echo (int)($b['group_count'] ?? 0); ?></span>
                                <span class="text-muted" style="font-size: 0.8rem;">groups</span>
                                <?php if (!empty($b['approved_projects_count'])): ?>
                                    <span class="badge bg-success-subtle text-success ms-1" style="font-size: 0.7rem;"><?php echo (int)$b['approved_projects_count']; ?> approved</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if ($b['is_active']): ?>
                                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-weight: 600; border: 1px solid rgba(16, 185, 129, 0.25);">
                                        <i class="bi bi-check-circle-fill me-1"></i> Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(100, 116, 139, 0.12); color: #475569; font-weight: 500; border: 1px solid rgba(100, 116, 139, 0.25);">
                                        <i class="bi bi-clock-history me-1"></i> Concluded
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if ($b['is_registration_open']): ?>
                                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(59, 130, 246, 0.12); color: #2563eb; font-weight: 600; border: 1px solid rgba(59, 130, 246, 0.25);">
                                        <i class="bi bi-door-open-fill me-1"></i> Open (New Students)
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill px-3 py-2 text-muted bg-light border" style="font-weight: 500;">
                                        <i class="bi bi-door-closed-fill me-1"></i> Closed
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-muted" style="font-size: 0.85rem;">
                                <?php echo date('M d, Y', strtotime($b['created_at'])); ?>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2 align-items-center">
                                    <!-- Toggle Active / Archive -->
                                    <form action="<?php echo $bp; ?>/coordinator/batches/toggle" method="POST" class="m-0" onsubmit="return confirm('<?php echo $b['is_active'] ? 'Concluding this batch will move its projects to Previous Projects, close chat for its students, and clean up chat storage. Continue?' : 'Activating this batch will make it active for your department & shift and move the prior batch to Previous Projects. Continue?'; ?>');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="batch_id" value="<?php echo htmlspecialchars((string)$b['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <?php if ($b['is_active']): ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1" title="Conclude Batch & Shift to Previous Projects">
                                                <i class="bi bi-check2-circle me-1"></i> Conclude
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" title="Restore & Activate Batch">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Activate
                                            </button>
                                        <?php endif; ?>
                                    </form>

                                    <!-- Set Registration Open -->
                                    <?php if (!$b['is_registration_open']): ?>
                                        <form action="<?php echo $bp; ?>/coordinator/batches/toggle" method="POST" class="m-0">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="batch_id" value="<?php echo htmlspecialchars((string)$b['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="action" value="set_registration">
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" title="Open Registration for New Students">
                                                <i class="bi bi-door-open me-1"></i> Open Reg
                                            </button>
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

<!-- ═══════════════ Create Batch Modal ═══════════════ -->
<div class="modal fade" id="createBatchModal" tabindex="-1" aria-labelledby="createBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom py-3 px-4" style="background: #f8fafc;">
                <h5 class="modal-title fw-bold" id="createBatchModalLabel" style="color: #0f172a;">
                    <i class="bi bi-plus-circle-fill text-primary me-2"></i>Create New Academic Batch
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $bp; ?>/coordinator/batches/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="modal-body p-4">
                    <!-- Batch Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Batch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-3 fs-6" placeholder="e.g., 2025, 2k25-SWE, Fall 2025" required>
                        <div class="form-text">Give your batch a recognizable title (e.g., 2025 or 2k25).</div>
                    </div>

                    <!-- Shift -->
                    <?php if (($shift ?? '') === 'All'): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Shift <span class="text-danger">*</span></label>
                            <select name="shift" class="form-select form-control-lg rounded-3 fs-6" required>
                                <option value="Morning">Morning</option>
                                <option value="Evening">Evening</option>
                                <option value="All">All Shifts</option>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="shift" value="<?php echo htmlspecialchars($shift ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="mb-3 p-3 rounded-3 bg-light border">
                            <small class="text-muted d-block">Department &amp; Shift:</small>
                            <strong class="text-dark"><?php echo htmlspecialchars($department ?? 'Software Engineering'); ?> &bull; <?php echo htmlspecialchars($shift ?? 'Morning'); ?> Shift</strong>
                        </div>
                    <?php endif; ?>

                    <!-- Activation Checkbox -->
                    <div class="form-check form-switch p-3 rounded-3 border mt-3" style="background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2) !important;">
                        <input class="form-check-input ms-0 me-3" type="checkbox" role="switch" id="activateNow" name="activate_now" value="1" checked>
                        <label class="form-check-label text-dark fw-semibold" for="activateNow">
                            Activate immediately &amp; Open Registration
                        </label>
                        <div class="text-muted mt-1" style="font-size: 0.8rem;">
                            Auto-archives current active batch for this shift, moves its supervised projects into Previous Projects, and cleans chat attachment files to free up disk storage.
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top px-4 py-3 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i>Create Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
