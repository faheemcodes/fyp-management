<!-- HOD Coordinator Management View -->
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
.modern-table thead th {
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.04em !important;
    text-transform: uppercase !important;
    color: var(--text-secondary) !important;
}
.modern-table tbody td {
    font-size: 0.88rem !important;
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Coordinators</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75); font-size: 0.85rem">Manage departmental Morning &amp; Evening FYP coordinators</p>
            </div>
        </div>
        <button class="btn rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0 fw-semibold d-inline-flex align-items-center justify-content-center gap-2" style="background: #ffffff; color: #047fb0; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#createCoordinatorModal">
            <i class="bi bi-person-plus-fill"></i> <span>Add Coordinator</span>
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
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search coordinators..." data-target="coordinators-table">
                </div>
            </div>
            <div class="col-md-6 pe-0 text-md-end text-muted small">
                Total: <strong><?php echo count($coordinators); ?></strong> coordinator(s)
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="coordinators-table">
            <thead>
                <tr>
                    <th class="ps-4">Coordinator</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Shift</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($coordinators as $c): ?>
                <?php 
                    $coordShift = $c['shift'] ?? 'Morning'; 
                    $coordPrefix = $c['prefix'] ?? 'Mr.';
                    $coordFirstName = $c['name'] ?? '';
                    $coordSurname = $c['surname'] ?? '';
                    $coordFullName = formatPersonName($coordPrefix, $coordFirstName, $coordSurname);
                ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.95rem">
                                <?php echo getNameInitial($coordFirstName); ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.95rem;"><?php echo htmlspecialchars($coordFullName, ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted d-block" style="font-size: 0.82rem;"><?php echo htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge border rounded-pill px-3 py-1.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important; font-size: 0.84rem; font-weight: 500;"><?php echo htmlspecialchars($c['designation'] ?? 'FYP Coordinator', ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td>
                        <span class="badge border rounded-pill px-3 py-1.5" style="background: rgba(59, 130, 246, 0.1); color: #2563eb; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.84rem; font-weight: 500;"><?php echo htmlspecialchars($c['department'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </td>
                    <td>
                        <?php if ($coordShift === 'Evening'): ?>
                        <span class="badge border rounded-pill px-3 py-1.5" style="background: rgba(245, 158, 11, 0.1); color: #d97706; border-color: rgba(245, 158, 11, 0.25) !important; font-size: 0.84rem; font-weight: 600;">
                            Evening Shift
                        </span>
                        <?php elseif ($coordShift === 'All'): ?>
                        <span class="badge border rounded-pill px-3 py-1.5" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important; font-size: 0.84rem; font-weight: 600;">
                            All Shifts
                        </span>
                        <?php else: ?>
                        <span class="badge border rounded-pill px-3 py-1.5" style="background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.25) !important; font-size: 0.84rem; font-weight: 600;">
                            Morning Shift
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <!-- View Button -->
                            <button type="button" class="action-btn action-btn-view" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <!-- Edit Button -->
                            <button type="button" class="action-btn action-btn-edit" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <!-- Delete Button -->
                            <a href="<?php echo $basePath; ?>/hod/coordinators/delete?id=<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="action-btn action-btn-delete" title="Delete" onclick="confirmAction(event, 'Are you sure you want to delete this coordinator?')">
                                <i class="bi bi-trash3-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.25); width: 60px; height: 60px;">
                                    <i class="bi bi-person-workspace text-primary" style="font-size: 1.6rem"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);"><?php echo htmlspecialchars($coordFullName, ENT_QUOTES, 'UTF-8'); ?></h5>
                                <div class="d-flex align-items-center gap-1.5 justify-content-center">
                                    <span class="badge px-2.5 py-1 rounded-pill" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.78rem;">
                                        <?php echo htmlspecialchars($c['designation'] ?? 'FYP Coordinator', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 0.78rem;">
                                        <?php echo htmlspecialchars($coordShift, ENT_QUOTES, 'UTF-8'); ?> Shift
                                    </span>
                                </div>
                            </div>
                            <div class="modal-body p-4 pt-3">
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <div class="row g-3 small">
                                        <div class="col-12">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Full Name</span>
                                            <strong style="color: var(--text-primary); font-size: 0.95rem;"><?php echo htmlspecialchars($coordFullName, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Department</span>
                                            <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($c['department'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Assigned Shift</span>
                                            <strong class="text-primary"><?php echo htmlspecialchars($coordShift, ENT_QUOTES, 'UTF-8'); ?> Shift</strong>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Email Address</span>
                                            <span style="color: var(--text-primary);"><i class="bi bi-envelope me-1 text-primary"></i><?php echo htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">CNIC</span>
                                            <span class="font-monospace" style="color: var(--text-primary);"><?php echo htmlspecialchars($c['cnic'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Status</span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5">Active</span>
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
                <div class="modal fade" id="editModal<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 56px; height: 56px">
                                    <i class="bi bi-pencil-square text-primary" style="font-size: 1.5rem"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);">Edit Coordinator Role</h5>
                                <div class="badge rounded-pill text-primary mb-1" style="background: rgba(16, 185, 129, 0.1); font-size: 0.85rem; padding: 0.35rem 0.75rem;">
                                    <?php echo htmlspecialchars($coordFullName, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <small class="text-muted"><?php echo htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                            <form action="<?php echo $basePath; ?>/hod/coordinators/edit" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="modal-body p-4 pt-2">
                                    <div class="alert alert-light border small text-muted py-2 px-3 mb-3 rounded-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-info-circle text-primary fs-6"></i>
                                        <span>Personal profile details (CNIC, Email, Phone) are managed in <a href="<?php echo $basePath; ?>/hod/supervisors" class="fw-semibold text-decoration-none">View Faculty</a>.</span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Assigned Shift *</label>
                                        <select class="form-select" name="shift" required>
                                            <option value="Morning" <?php echo ($coordShift === 'Morning') ? 'selected' : ''; ?>>Morning Shift</option>
                                            <option value="Evening" <?php echo ($coordShift === 'Evening') ? 'selected' : ''; ?>>Evening Shift</option>
                                            <option value="All" <?php echo ($coordShift === 'All') ? 'selected' : ''; ?>>All Shifts (Both)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Coordinator Designation / Role *</label>
                                        <input type="text" class="form-control" name="designation" value="<?php echo htmlspecialchars($c['designation'] ?? 'FYP Coordinator', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. FYP Coordinator, Head Coordinator" required>
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
                <?php if (empty($coordinators)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-person-workspace fs-2 d-block mb-2 opacity-50"></i>
                        No coordinators appointed yet.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Coordinator Modal -->
<div class="modal fade" id="createCoordinatorModal" tabindex="-1" aria-labelledby="createCoordinatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                <div class="position-absolute top-0 end-0 p-3">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 56px; height: 56px">
                    <i class="bi bi-person-plus-fill text-primary" style="font-size: 1.5rem"></i>
                </div>
                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);" id="createCoordinatorModalLabel">Add Coordinator</h5>
                <p class="text-muted small mb-0">Department: <strong class="text-primary"><?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?></strong></p>
            </div>
            <form action="<?php echo $basePath; ?>/hod/coordinators/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="supervisor_user_id" id="hiddenSupervisorId" value="0">
                
                <div class="modal-body p-4">
                    <!-- Select Supervisor -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Select Existing Supervisor (Auto-fills Details)</label>
                        <select class="form-select" id="supervisorSelect" onchange="onSupervisorSelected(this)">
                            <option value="">-- Or Create New / Select Existing Supervisor --</option>
                            <?php foreach(($available_supervisors ?? []) as $sup): ?>
                                <?php
                                    $sPrefix = $sup['prefix'] ?? 'Mr.';
                                    $sFirstName = $sup['name'] ?? '';
                                    $sSurname = $sup['surname'] ?? '';
                                    $sFullName = formatPersonName($sPrefix, $sFirstName, $sSurname);
                                ?>
                                <option value="<?php echo (int)$sup['user_id']; ?>"
                                        data-prefix="<?php echo htmlspecialchars($sPrefix, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-firstname="<?php echo htmlspecialchars($sFirstName, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lastname="<?php echo htmlspecialchars($sSurname, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-email="<?php echo htmlspecialchars($sup['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-cnic="<?php echo htmlspecialchars($sup['cnic'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-designation="<?php echo htmlspecialchars($sup['designation'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-mobilecode="<?php echo htmlspecialchars($sup['mobile_code'] ?? '+92', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-contact="<?php echo htmlspecialchars($sup['mobile_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($sFullName, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($sup['email'], ENT_QUOTES, 'UTF-8'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Prefix *</label>
                            <select class="form-select" name="prefix" id="createPrefix" required>
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
                            <input type="text" class="form-control" name="first_name" id="createFirstName" required placeholder="e.g. Asad">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Surname (Last Name) *</label>
                            <input type="text" class="form-control" name="last_name" id="createLastName" required placeholder="e.g. Shaikh">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email Address *</label>
                            <input type="email" class="form-control" name="email" id="createEmail" required placeholder="name@university.edu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CNIC *</label>
                            <input type="text" class="form-control" name="cnic" id="createCnic" required placeholder="e.g. 4130312345671" pattern="[0-9]{13}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Designation *</label>
                            <input type="text" class="form-control" name="designation" id="createDesignation" required value="FYP Coordinator" placeholder="e.g. Assistant Professor & Coordinator">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Assigned Shift *</label>
                            <select class="form-select" name="shift" required>
                                <option value="Morning">Morning Shift</option>
                                <option value="Evening">Evening Shift</option>
                                <option value="All">All Shifts (Both)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Contact Number</label>
                            <div class="input-group">
                                <select class="form-select flex-shrink-0" name="mobile_code" id="createMobileCode" style="max-width: 90px;">
                                    <option value="+92" selected>+92</option>
                                    <option value="+1">+1</option>
                                    <option value="+44">+44</option>
                                    <option value="+971">+971</option>
                                    <option value="+966">+966</option>
                                </select>
                                <input type="tel" class="form-control" name="contact_no" id="createContact" placeholder="3001234567">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold text-muted m-0">Password *</label>
                                <button type="button" class="btn btn-link p-0 text-decoration-none small text-primary fw-semibold" onclick="generateRandomPassword('coordPassword')">
                                    <i class="bi bi-magic me-1"></i>Generate
                                </button>
                            </div>
                            <div class="position-relative">
                                <input type="text" class="form-control font-monospace" id="coordPassword" name="password" required placeholder="Enter or generate password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <div class="d-flex w-100 gap-2">
                        <button type="button" class="btn btn-light flex-grow-1 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-semibold" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">Add Coordinator</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function onSupervisorSelected(sel) {
    const opt = sel.options[sel.selectedIndex];
    const hiddenId = document.getElementById('hiddenSupervisorId');
    const prefixInput = document.getElementById('createPrefix');
    const fNameInput = document.getElementById('createFirstName');
    const lNameInput = document.getElementById('createLastName');
    const emailInput = document.getElementById('createEmail');
    const cnicInput = document.getElementById('createCnic');
    const desigInput = document.getElementById('createDesignation');
    const mobileCodeInput = document.getElementById('createMobileCode');
    const contactInput = document.getElementById('createContact');

    if (opt && opt.value) {
        hiddenId.value = opt.value;
        if (prefixInput) prefixInput.value = opt.getAttribute('data-prefix') || 'Dr.';
        if (fNameInput) fNameInput.value = opt.getAttribute('data-firstname') || '';
        if (lNameInput) lNameInput.value = opt.getAttribute('data-lastname') || '';
        if (emailInput) emailInput.value = opt.getAttribute('data-email') || '';
        if (cnicInput) cnicInput.value = opt.getAttribute('data-cnic') || '';
        if (desigInput) desigInput.value = (opt.getAttribute('data-designation') || 'Assistant Professor') + ' & Coordinator';
        if (mobileCodeInput) mobileCodeInput.value = opt.getAttribute('data-mobilecode') || '+92';
        if (contactInput) contactInput.value = opt.getAttribute('data-contact') || '';
    } else {
        hiddenId.value = '0';
        if (prefixInput) prefixInput.value = 'Dr.';
        if (fNameInput) fNameInput.value = '';
        if (lNameInput) lNameInput.value = '';
        if (emailInput) emailInput.value = '';
        if (cnicInput) cnicInput.value = '';
        if (desigInput) desigInput.value = 'FYP Coordinator';
        if (mobileCodeInput) mobileCodeInput.value = '+92';
        if (contactInput) contactInput.value = '';
    }
}

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
