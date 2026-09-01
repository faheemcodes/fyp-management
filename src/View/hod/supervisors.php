<!-- HOD Supervisor Management View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
/* Minimal Action Buttons */
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px !important;
    font-size: 0.82rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
    text-decoration: none;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}
.action-btn-view {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border-color: rgba(59, 130, 246, 0.25);
}
.action-btn-view:hover {
    background: rgba(59, 130, 246, 0.2);
    color: #2563eb;
}
.action-btn-edit {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.25);
}
.action-btn-edit:hover {
    background: rgba(16, 185, 129, 0.2);
    color: #059669;
}
.action-btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.25);
}
.action-btn-delete:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #dc2626;
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Supervisors</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75); font-size: 0.85rem">Manage project supervisors and slot allocations</p>
            </div>
        </div>
        <button class="btn rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0 fw-semibold d-inline-flex align-items-center justify-content-center gap-2" style="background: #ffffff; color: #047fb0; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#createSupervisorModal">
            <i class="bi bi-person-plus-fill"></i> <span>Add Supervisor</span>
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
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search supervisors..." data-target="supervisors-table">
                </div>
            </div>
            <div class="col-md-6 pe-0 text-md-end text-muted small">
                Total: <strong><?php echo count($supervisors); ?></strong> supervisor(s)
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="supervisors-table">
            <thead>
                <tr>
                    <th class="ps-4">Supervisor</th>
                    <th>Designation</th>
                    <th>Morning Projects</th>
                    <th>Evening Projects</th>
                    <th>Total Projects</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($supervisors as $s): ?>
                <?php 
                    $supPrefix = $s['prefix'] ?? 'Mr.';
                    $supFirstName = $s['name'] ?? '';
                    $supSurname = $s['surname'] ?? '';
                    $supFullName = formatPersonName($supPrefix, $supFirstName, $supSurname);
                ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem">
                                <?php echo getNameInitial($supFirstName); ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.95rem;"><?php echo htmlspecialchars($supFullName, ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;"><?php echo htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge border px-2.5 py-1.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;"><?php echo htmlspecialchars($s['designation'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td>
                        <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.25) !important; font-size: 0.78rem;">
                            <?php echo (int)($s['morning_projects'] ?? 0); ?> / <?php echo (int)($maxMorning ?? 5); ?> Groups
                        </span>
                    </td>
                    <td>
                        <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important; font-size: 0.78rem;">
                            <?php echo (int)($s['evening_projects'] ?? 0); ?> / <?php echo (int)($maxEvening ?? 5); ?> Groups
                        </span>
                    </td>
                    <td>
                        <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.78rem;">
                            <?php echo (int)($s['active_projects'] ?? 0); ?> Groups
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <!-- View Button -->
                            <button type="button" class="action-btn action-btn-view" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <!-- Edit Button -->
                            <button type="button" class="action-btn action-btn-edit" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <!-- Delete Button -->
                            <a href="<?php echo $basePath; ?>/hod/supervisors/delete?id=<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="action-btn action-btn-delete" title="Delete" onclick="confirmAction(event, 'Are you sure you want to delete this supervisor?')">
                                <i class="bi bi-trash3-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.25); width: 60px; height: 60px;">
                                    <i class="bi bi-person-badge-fill text-primary" style="font-size: 1.6rem"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);"><?php echo htmlspecialchars($supFullName, ENT_QUOTES, 'UTF-8'); ?></h5>
                                <span class="badge px-3 py-1 rounded-pill" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.78rem;">
                                    <?php echo htmlspecialchars($s['designation'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <div class="modal-body p-4 pt-3">
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <div class="row g-3 small">
                                        <div class="col-4">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Prefix</span>
                                            <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($supPrefix, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-4">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">First Name</span>
                                            <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($supFirstName, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-4">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Surname</span>
                                            <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($supSurname ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Department</span>
                                            <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($department ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">CNIC</span>
                                            <span class="font-monospace" style="color: var(--text-primary);"><?php echo htmlspecialchars($s['cnic'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Email Address</span>
                                            <span style="color: var(--text-primary);"><?php echo htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Contact Number</span>
                                            <span style="color: var(--text-primary);"><?php echo htmlspecialchars(($s['mobile_code'] ?? '+92') . ' ' . ($s['mobile_no'] ?? 'N/A'), ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Morning Projects</span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5"><?php echo (int)($s['morning_projects'] ?? 0); ?> / <?php echo (int)($maxMorning ?? 5); ?> Groups</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Evening Projects</span>
                                            <span class="badge border rounded-pill px-2.5 py-0.5" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important;"><?php echo (int)($s['evening_projects'] ?? 0); ?> / <?php echo (int)($maxEvening ?? 5); ?> Groups</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Total Projects</span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-0.5"><?php echo (int)($s['active_projects'] ?? 0); ?> Groups</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Role</span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5">Supervisor</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="button" class="btn btn-light w-100 rounded-pill fw-semibold" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 56px; height: 56px">
                                    <i class="bi bi-pencil-square text-primary" style="font-size: 1.5rem"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);">Edit Supervisor</h5>
                                <div class="badge rounded-pill text-primary mb-2" style="background: rgba(16, 185, 129, 0.1); font-size: 0.85rem; padding: 0.35rem 0.75rem;">
                                    <?php echo htmlspecialchars($supFullName, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                            <form action="<?php echo $basePath; ?>/hod/supervisors/edit" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="modal-body p-4 pt-2">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-muted">Prefix *</label>
                                            <select class="form-select" name="prefix" required>
                                                <option value="Mr." <?php echo $supPrefix === 'Mr.' ? 'selected' : ''; ?>>Mr.</option>
                                                <option value="Ms." <?php echo $supPrefix === 'Ms.' ? 'selected' : ''; ?>>Ms.</option>
                                                <option value="Mrs." <?php echo $supPrefix === 'Mrs.' ? 'selected' : ''; ?>>Mrs.</option>
                                                <option value="Dr." <?php echo $supPrefix === 'Dr.' ? 'selected' : ''; ?>>Dr.</option>
                                                <option value="Prof." <?php echo $supPrefix === 'Prof.' ? 'selected' : ''; ?>>Prof.</option>
                                                <option value="Engr." <?php echo $supPrefix === 'Engr.' ? 'selected' : ''; ?>>Engr.</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small fw-bold text-muted">First Name *</label>
                                            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($supFirstName, ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-muted">Surname (Last Name) *</label>
                                            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($supSurname, ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Designation *</label>
                                            <select class="form-select" name="designation" required>
                                                <option value="Lecturer" <?php echo $s['designation'] === 'Lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                                                <option value="Assistant Professor" <?php echo $s['designation'] === 'Assistant Professor' ? 'selected' : ''; ?>>Assistant Professor</option>
                                                <option value="Associate Professor" <?php echo $s['designation'] === 'Associate Professor' ? 'selected' : ''; ?>>Associate Professor</option>
                                                <option value="Professor" <?php echo $s['designation'] === 'Professor' ? 'selected' : ''; ?>>Professor</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Email Address *</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">CNIC (no dashes) *</label>
                                            <input type="text" class="form-control" name="cnic" value="<?php echo htmlspecialchars($s['cnic'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required pattern="[0-9]{13}" placeholder="4130312345671">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Contact Number</label>
                                            <div class="input-group">
                                                <select class="form-select flex-shrink-0" name="mobile_code" style="max-width: 90px;">
                                                    <?php $mCode = $s['mobile_code'] ?? '+92'; ?>
                                                    <option value="+92" <?php echo ($mCode === '+92') ? 'selected' : ''; ?>>+92</option>
                                                    <option value="+1" <?php echo ($mCode === '+1') ? 'selected' : ''; ?>>+1</option>
                                                    <option value="+44" <?php echo ($mCode === '+44') ? 'selected' : ''; ?>>+44</option>
                                                    <option value="+971" <?php echo ($mCode === '+971') ? 'selected' : ''; ?>>+971</option>
                                                    <option value="+966" <?php echo ($mCode === '+966') ? 'selected' : ''; ?>>+966</option>
                                                </select>
                                                <input type="tel" class="form-control" name="contact_no" value="<?php echo htmlspecialchars($s['mobile_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="3001234567">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2 text-start">
                                        <label class="form-label small fw-bold text-muted">Reset Password (leave blank to keep)</label>
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
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2 opacity-50"></i>
                        No supervisors registered yet.
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
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                <div class="position-absolute top-0 end-0 p-3">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 56px; height: 56px">
                    <i class="bi bi-person-plus-fill text-primary" style="font-size: 1.5rem"></i>
                </div>
                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);" id="createSupervisorModalLabel">Add Supervisor</h5>
                <p class="text-muted small mb-0">Department: <strong class="text-primary"><?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?></strong></p>
            </div>
            <form action="<?php echo $basePath; ?>/hod/supervisors/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Prefix *</label>
                            <select class="form-select" name="prefix" required>
                                <option value="Dr." selected>Dr.</option>
                                <option value="Prof.">Prof.</option>
                                <option value="Engr.">Engr.</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Mrs.">Mrs.</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">First Name *</label>
                            <input type="text" class="form-control" name="first_name" required placeholder="e.g. Faheem">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Surname (Last Name) *</label>
                            <input type="text" class="form-control" name="last_name" required placeholder="e.g. Soomro">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email Address *</label>
                            <input type="email" class="form-control" name="email" required placeholder="name@university.edu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CNIC *</label>
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
                            <div class="input-group">
                                <select class="form-select flex-shrink-0" name="mobile_code" style="max-width: 90px;">
                                    <option value="+92" selected>+92</option>
                                    <option value="+1">+1</option>
                                    <option value="+44">+44</option>
                                    <option value="+971">+971</option>
                                    <option value="+966">+966</option>
                                </select>
                                <input type="tel" class="form-control" name="contact_no" placeholder="3001234567">
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold text-muted m-0">Password *</label>
                            <button type="button" class="btn btn-link p-0 text-decoration-none small text-primary fw-semibold" onclick="generateRandomPassword('supPassword')">
                                <i class="bi bi-magic me-1"></i>Generate
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
