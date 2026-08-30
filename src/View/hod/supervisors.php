<!-- HOD Supervisor Management View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Supervisor Faculty Directory</h4>
                    <span class="badge rounded-pill bg-white bg-opacity-20 text-white px-3 py-1 fw-semibold" style="font-size: 0.8rem">
                        <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75);font-size: 0.85rem">Register and manage project supervisors under your department</p>
            </div>
        </div>
        <button class="btn rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0 fw-semibold d-inline-flex align-items-center justify-content-center gap-2" style="background: #ffffff; color: #047fb0; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#createSupervisorModal">
            <i class="bi bi-person-plus-fill"></i> <span>Add New Supervisor</span>
        </button>
    </div>
</div>

<div class="page-section">
    <div class="page-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-6 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search supervisors by name, email, designation..." data-target="supervisors-table">
                </div>
            </div>
            <div class="col-md-6 pe-0 text-md-end text-muted small">
                Showing <strong><?php echo count($supervisors); ?></strong> supervisor(s) in <strong><?php echo htmlspecialchars($department ?? 'FET'); ?></strong>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="supervisors-table">
            <thead>
                <tr>
                    <th class="ps-4">Supervisor Name &amp; Email</th>
                    <th>Designation</th>
                    <th>CNIC</th>
                    <th>Active FYP Projects</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($supervisors as $s): ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 40px;height: 40px;font-size: 1rem">
                                <?php echo strtoupper(substr($s['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.95rem;"><?php echo htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted" style="font-size: 0.8rem"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge border px-2.5 py-1.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;"><?php echo htmlspecialchars($s['designation'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td>
                        <span class="font-monospace small px-2 py-1 border rounded" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;"><?php echo htmlspecialchars($s['cnic'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                    </td>
                    <td>
                        <span class="badge border rounded-pill px-3 py-1 font-monospace" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; border-color: rgba(59, 130, 246, 0.25) !important;">
                            <?php echo (int)($s['active_projects'] ?? 0); ?> Projects
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(4, 127, 176, 0.12);color: #047fb0;border: 1px solid rgba(4, 127, 176, 0.25);font-weight: 600" onmouseover="this.style.background='rgba(4, 127, 176, 0.2)';" onmouseout="this.style.background='rgba(4, 127, 176, 0.12)';" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="bi bi-pencil-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-1.5">Edit</span>
                            </button>
                            <a href="<?php echo $basePath; ?>/hod/supervisors/delete?id=<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(168, 10, 52, 0.12);color: #a80a34;border: 1px solid rgba(168, 10, 52, 0.25);font-weight: 600" onmouseover="this.style.background='rgba(168, 10, 52, 0.2)';" onmouseout="this.style.background='rgba(168, 10, 52, 0.12)';" onclick="confirmAction(event, 'Are you sure you want to delete this supervisor? This will permanently delete their user account.')">
                                <i class="bi bi-trash3-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-1.5">Delete</span>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;overflow: hidden; background: var(--card-bg);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg);border: 1px solid var(--border-color);width: 56px;height: 56px">
                                    <i class="bi bi-pencil-square text-primary" style="font-size: 1.5rem"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);">Edit Supervisor Details</h5>
                                <div class="badge rounded-pill text-primary mb-2" style="background: rgba(16, 185, 129, 0.1);font-size: 0.85rem;padding: 0.35rem 0.75rem;">
                                    <?php echo htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                            <form action="<?php echo $basePath; ?>/hod/supervisors/edit" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="modal-body p-4 pt-2">
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Full Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Designation</label>
                                        <select class="form-select" name="designation" required>
                                            <option value="Lecturer" <?php echo $s['designation'] === 'Lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                                            <option value="Assistant Professor" <?php echo $s['designation'] === 'Assistant Professor' ? 'selected' : ''; ?>>Assistant Professor</option>
                                            <option value="Associate Professor" <?php echo $s['designation'] === 'Associate Professor' ? 'selected' : ''; ?>>Associate Professor</option>
                                            <option value="Professor" <?php echo $s['designation'] === 'Professor' ? 'selected' : ''; ?>>Professor</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="mb-2 text-start">
                                        <label class="form-label small fw-bold text-muted">Reset Password (leave empty to keep current)</label>
                                        <input type="password" class="form-control" name="password" placeholder="••••••••">
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <div class="d-flex w-100 gap-2">
                                        <button type="button" class="btn btn-light flex-grow-1 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn flex-grow-1 rounded-pill fw-semibold" style="background: linear-gradient(135deg, #10b981, #059669); border: none; color: #ffffff;">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($supervisors)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2 opacity-50"></i>
                        No supervisors registered in this department yet.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Supervisor Modal -->
<div class="modal fade" id="createSupervisorModal" tabindex="-1" aria-labelledby="createSupervisorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                <div class="position-absolute top-0 end-0 p-3">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg);border: 1px solid var(--border-color);width: 56px;height: 56px">
                    <i class="bi bi-person-plus-fill text-primary" style="font-size: 1.5rem"></i>
                </div>
                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);" id="createSupervisorModalLabel">Add New Faculty Supervisor</h5>
                <p class="text-muted small mb-0">Department: <strong class="text-primary"><?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?></strong></p>
            </div>
            <form action="<?php echo $basePath; ?>/hod/supervisors/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">First Name *</label>
                            <input type="text" class="form-control" name="first_name" required placeholder="e.g. Faheem">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" required placeholder="e.g. Soomro">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email Address *</label>
                            <input type="email" class="form-control" name="email" required placeholder="name@university.edu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CNIC (no dashes) *</label>
                            <input type="text" class="form-control" name="cnic" required placeholder="e.g. 4130312345671" pattern="[0-9]{13}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Designation *</label>
                            <select class="form-select" name="designation" required>
                                <option value="Lecturer">Lecturer</option>
                                <option value="Assistant Professor" selected>Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Contact Number</label>
                            <input type="text" class="form-control" name="contact_no" placeholder="03001234567">
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold text-muted m-0">Password *</label>
                            <button type="button" class="btn btn-link p-0 text-decoration-none small text-primary fw-semibold" onclick="generateRandomPassword('supPassword')">
                                <i class="bi bi-magic me-1"></i>Generate Password
                            </button>
                        </div>
                        <div class="position-relative">
                            <input type="text" class="form-control font-monospace" id="supPassword" name="password" required placeholder="Enter or generate password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <div class="d-flex w-100 gap-2">
                        <button type="button" class="btn btn-light flex-grow-1 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-semibold" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">Add Supervisor</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateRandomPassword(elementId) {
    const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%';
    let pass = '';
    for (let i = 0; i < 10; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    const input = document.getElementById(elementId);
    if (input) {
        input.value = pass;
    }
}
</script>
