<!-- HOD Committee Management View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$numCommittees = $num_committees ?? 2;
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

/* Filter Pills */
.btn-filter-pill {
    background: var(--form-bg, #f8fafc);
    color: var(--text-secondary, #64748b);
    border: 1px solid var(--border-color, rgba(0,0,0,0.08));
    font-size: 0.78rem;
    padding: 5px 14px;
    transition: all 0.2s ease;
}
.btn-filter-pill:hover {
    background: var(--card-bg, #ffffff);
    color: var(--text-primary, #1e293b);
}
.btn-filter-pill.active {
    background: #047fb0;
    color: #ffffff;
    border-color: #047fb0;
    box-shadow: 0 2px 8px rgba(4, 127, 176, 0.25);
}
</style>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-shield-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Committee Members</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(139, 92, 246, 0.25); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem;">
                        <?php echo $numCommittees; ?> Committees Active
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75); font-size: 0.85rem">Manage evaluation committee members across <?php echo $numCommittees; ?> committees</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo $basePath; ?>/hod/settings" class="btn btn-sm btn-outline-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border: 1.5px solid rgba(255,255,255,0.4); font-size: 0.85rem;">
                <i class="bi bi-sliders"></i> <span>Manage Limits</span>
            </a>
            <button class="btn rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0 fw-semibold d-inline-flex align-items-center justify-content-center gap-2" style="background: #ffffff; color: #047fb0; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#createCommitteeModal">
                <i class="bi bi-person-plus-fill"></i> <span>Add Member</span>
            </button>
        </div>
    </div>
</div>

<div class="page-section">
    <div class="page-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-5 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search members by name, email..." data-target="committees-table">
                </div>
            </div>
            <!-- Filter Pills by Committee -->
            <div class="col-md-7 pe-0 d-flex justify-content-md-end gap-2 flex-wrap align-items-center">
                <button class="btn btn-sm btn-filter-pill rounded-pill active" onclick="filterCommittee('all', this)">All (<?php echo count($committees); ?>)</button>
                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                <?php 
                    $countForThis = count(array_filter($committees, fn($c) => (int)($c['committee_number'] ?? 1) === $i));
                ?>
                <button class="btn btn-sm btn-filter-pill rounded-pill" onclick="filterCommittee('<?php echo $i; ?>', this)">
                    Committee <?php echo $i; ?> <span class="opacity-75 ms-1">(<?php echo $countForThis; ?>)</span>
                </button>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="committees-table">
            <thead>
                <tr>
                    <th class="ps-4">Member</th>
                    <th>Committee</th>
                    <th>Designation</th>
                    <th>CNIC</th>
                    <th>Department</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($committees as $c): ?>
                <?php $commNum = (int)($c['committee_number'] ?? 1); ?>
                <tr data-committee="<?php echo $commNum; ?>">
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem">
                                <?php echo getNameInitial($c['name']); ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.95rem;"><?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;"><?php echo htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge border rounded-pill px-2.5 py-1 font-monospace" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25) !important; font-size: 0.78rem;">
                            <i class="bi bi-shield-check me-1"></i>Committee <?php echo $commNum; ?>
                        </span>
                    </td>
                    <td><span class="badge border px-2.5 py-1.5" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;"><?php echo htmlspecialchars($c['designation'] ?? 'Faculty Member', ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td>
                        <span class="font-monospace small px-2 py-1 border rounded" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;"><?php echo htmlspecialchars($c['cnic'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                    </td>
                    <td>
                        <span class="badge border px-2.5 py-1" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; border-color: rgba(59, 130, 246, 0.25) !important;"><?php echo htmlspecialchars($c['department'], ENT_QUOTES, 'UTF-8'); ?></span>
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
                            <a href="<?php echo $basePath; ?>/hod/committee/delete?id=<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="action-btn action-btn-delete" title="Delete" onclick="confirmAction(event, 'Are you sure you want to delete this committee member?')">
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
                                    <i class="bi bi-shield-fill text-primary" style="font-size: 1.6rem"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);"><?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <div class="d-flex align-items-center gap-2 justify-content-center">
                                    <span class="badge px-2.5 py-1 rounded-pill" style="background: var(--form-bg); color: var(--text-secondary); border: 1px solid var(--border-color); font-size: 0.78rem;">
                                        <?php echo htmlspecialchars($c['designation'] ?? 'Evaluator', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.25); font-size: 0.78rem;">
                                        Committee <?php echo $commNum; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-body p-4 pt-3">
                                <div class="p-3 rounded-3 mb-3" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                    <div class="row g-3 small">
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Department</span>
                                            <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($c['department'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Assigned Committee</span>
                                            <strong class="text-purple" style="color: #8b5cf6;">Committee <?php echo $commNum; ?></strong>
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
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5">Active Member</span>
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
                                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);">Edit Committee Member</h5>
                                <div class="badge rounded-pill text-primary mb-2" style="background: rgba(16, 185, 129, 0.1); font-size: 0.85rem; padding: 0.35rem 0.75rem;">
                                    <?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                            <form action="<?php echo $basePath; ?>/hod/committee/edit" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="modal-body p-4 pt-2">
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Full Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Assigned Committee</label>
                                        <select class="form-select" name="committee_number" required>
                                            <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($commNum === $i) ? 'selected' : ''; ?>>Committee <?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Designation</label>
                                        <input type="text" class="form-control" name="designation" value="<?php echo htmlspecialchars($c['designation'] ?? 'Evaluator', ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-muted">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
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
                <?php if (empty($committees)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-shield-check fs-2 d-block mb-2 opacity-50"></i>
                        No committee members registered yet.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Committee Modal -->
<div class="modal fade" id="createCommitteeModal" tabindex="-1" aria-labelledby="createCommitteeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2rem 1.5rem 1rem;">
                <div class="position-absolute top-0 end-0 p-3">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="rounded-circle p-3 mb-2 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 56px; height: 56px">
                    <i class="bi bi-person-plus-fill text-primary" style="font-size: 1.5rem"></i>
                </div>
                <h5 class="fw-bold mb-1 text-center" style="color: var(--text-primary);" id="createCommitteeModalLabel">Add Committee Member</h5>
                <p class="text-muted small mb-0">Department: <strong class="text-primary"><?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?></strong></p>
            </div>
            <form action="<?php echo $basePath; ?>/hod/committee/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="supervisor_user_id" id="hiddenSupervisorId" value="0">
                
                <div class="modal-body p-4">
                    <!-- Select Supervisor -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Select Supervisor (Optional)</label>
                        <select class="form-select" id="supervisorSelect" onchange="onSupervisorSelected(this)">
                            <option value="">-- Select Supervisor --</option>
                            <?php foreach(($available_supervisors ?? []) as $sup): ?>
                                <?php
                                    $cleanName = preg_replace('/^(Dr\.|Prof\.|Engr\.|Mr\.|Mrs\.|Ms\.)\s+/i', '', trim($sup['name']));
                                    $parts = explode(' ', $cleanName, 2);
                                    $fName = $parts[0] ?? '';
                                    $lName = $parts[1] ?? '';
                                ?>
                                <option value="<?php echo (int)$sup['user_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($sup['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-firstname="<?php echo htmlspecialchars($fName, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lastname="<?php echo htmlspecialchars($lName, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-email="<?php echo htmlspecialchars($sup['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-cnic="<?php echo htmlspecialchars($sup['cnic'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-designation="<?php echo htmlspecialchars($sup['designation'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-contact="<?php echo htmlspecialchars($sup['mobile_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($sup['name'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($sup['email'], ENT_QUOTES, 'UTF-8'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">First Name *</label>
                            <input type="text" class="form-control" name="first_name" id="createFirstName" required placeholder="e.g. Ali">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" id="createLastName" required placeholder="e.g. Khan">
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
                            <input type="text" class="form-control" name="designation" id="createDesignation" required placeholder="e.g. Assistant Professor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Assigned Committee *</label>
                            <select class="form-select" name="committee_number" required>
                                <?php for($i = 1; $i <= $numCommittees; $i++): ?>
                                <option value="<?php echo $i; ?>">Committee <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Contact Number</label>
                            <input type="text" class="form-control" name="contact_no" id="createContact" placeholder="03001234567">
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold text-muted m-0">Password *</label>
                                <button type="button" class="btn btn-link p-0 text-decoration-none small text-primary fw-semibold" onclick="generateRandomPassword('commPassword')">
                                    <i class="bi bi-magic me-1"></i>Generate
                                </button>
                            </div>
                            <div class="position-relative">
                                <input type="text" class="form-control font-monospace" id="commPassword" name="password" required placeholder="Enter or generate password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <div class="d-flex w-100 gap-2">
                        <button type="button" class="btn btn-light flex-grow-1 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-semibold" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">Add Member</button>
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
    const fNameInput = document.getElementById('createFirstName');
    const lNameInput = document.getElementById('createLastName');
    const emailInput = document.getElementById('createEmail');
    const cnicInput = document.getElementById('createCnic');
    const desigInput = document.getElementById('createDesignation');
    const contactInput = document.getElementById('createContact');

    if (opt && opt.value) {
        hiddenId.value = opt.value;
        if (fNameInput) fNameInput.value = opt.getAttribute('data-firstname') || '';
        if (lNameInput) lNameInput.value = opt.getAttribute('data-lastname') || '';
        if (emailInput) emailInput.value = opt.getAttribute('data-email') || '';
        if (cnicInput) cnicInput.value = opt.getAttribute('data-cnic') || '';
        if (desigInput) desigInput.value = opt.getAttribute('data-designation') || '';
        if (contactInput) contactInput.value = opt.getAttribute('data-contact') || '';
    } else {
        hiddenId.value = '0';
        if (fNameInput) fNameInput.value = '';
        if (lNameInput) lNameInput.value = '';
        if (emailInput) emailInput.value = '';
        if (cnicInput) cnicInput.value = '';
        if (desigInput) desigInput.value = '';
        if (contactInput) contactInput.value = '';
    }
}

function filterCommittee(commNum, btn) {
    document.querySelectorAll('.btn-filter-pill').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    
    const rows = document.querySelectorAll('#committees-table tbody tr[data-committee]');
    rows.forEach(r => {
        if (commNum === 'all' || r.getAttribute('data-committee') === String(commNum)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
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
