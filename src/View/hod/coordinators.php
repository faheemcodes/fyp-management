<!-- HOD Coordinator Management View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
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
.group-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
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

/* ─── Section Panel ─── */
.grp-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 24px;
    transition: box-shadow 0.25s ease;
}
.grp-section-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
    border-top-left-radius: calc(var(--border-radius-lg) - 1px);
    border-top-right-radius: calc(var(--border-radius-lg) - 1px);
}

/* ─── Modern Table Styles ─── */
.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.modern-table thead th {
    background: var(--form-bg);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-secondary);
    font-weight: 700;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}
.modern-table tbody td {
    padding: 16px 24px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    background: var(--card-bg);
    transition: background-color 0.2s ease;
}
.modern-table tbody tr:hover td {
    background: var(--hover-bg);
}
.modern-table tbody tr:last-child td {
    border-bottom: none;
}
</style>

<!-- Top Hero Banner -->
<div class="group-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Department Coordinators Directory</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Create, edit, or remove FYP coordinators for the Department of <strong><?php echo htmlspecialchars($department); ?></strong></p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0" style="background: linear-gradient(135deg, #3b82f6, #2563eb);" data-bs-toggle="modal" data-bs-target="#createCoordinatorModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Coordinator
        </button>
    </div>
</div>

<div class="grp-section">
    <div class="grp-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-6 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search coordinators by name, email, cnic..." data-target="coordinators-table">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="coordinators-table">
            <thead>
                <tr>
                    <th class="ps-4">Coordinator Details</th>
                    <th>CNIC / Passport</th>
                    <th>Department</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($coordinators as $c): ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">
                                <?php echo strtoupper(substr($c['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($c['name']); ?></div>
                                <small class="text-muted" style="font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($c['email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-monospace text-secondary small px-2 py-1 bg-light border rounded"><?php echo htmlspecialchars($c['cnic'] ?? 'N/A'); ?></span>
                    </td>
                    <td>
                        <span class="small text-muted fw-medium"><?php echo htmlspecialchars($c['department']); ?></span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(4, 127, 176, 0.1); color: #047fb0; border: none; font-weight: 600;" onmouseover="this.style.background='rgba(4, 127, 176, 0.18)';" onmouseout="this.style.background='rgba(4, 127, 176, 0.1)';" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="bi bi-pencil-fill" style="font-size: 0.85rem;"></i> <span class="d-none d-md-inline ms-2">Edit</span>
                            </button>
                            <a href="<?php echo $basePath; ?>/hod/coordinators/delete?id=<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(168, 10, 52, 0.1); color: #a80a34; border: none; font-weight: 600;" onmouseover="this.style.background='rgba(168, 10, 52, 0.18)';" onmouseout="this.style.background='rgba(168, 10, 52, 0.1)';" onclick="confirmAction(event, 'Are you sure you want to delete this coordinator? This will permanently delete their account.')">
                                <i class="bi bi-trash3-fill" style="font-size: 0.85rem;"></i> <span class="d-none d-md-inline ms-2">Delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0" style="border-radius: 1.5rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2.5rem 2rem 1.5rem; border-bottom: 1px solid var(--border-color) !important;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="rounded-circle p-3 mb-3 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 64px; height: 64px;">
                                    <i class="bi bi-pencil-square text-primary" style="font-size: 1.75rem;"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-primary text-center" style="font-size: 1.35rem; letter-spacing: -0.02em;">Edit Coordinator Details</h5>
                                <div class="badge rounded-pill text-primary mb-2" style="background: rgba(59, 130, 246, 0.1); font-size: 0.85rem; padding: 0.4rem 0.8rem; font-weight: 600;">
                                    <?php echo htmlspecialchars($c['name']); ?>
                                </div>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/coordinators/edit" method="POST">
                                <div class="modal-body p-4">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                    
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Full Name</label>
                                        <input type="text" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" name="name" value="<?php echo htmlspecialchars($c['name']); ?>" required placeholder="e.g. Dr. Hameed">
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Email Address</label>
                                        <input type="email" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" name="email" value="<?php echo htmlspecialchars($c['email']); ?>" required placeholder="email@university.edu">
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">CNIC / Passport (no dashes)</label>
                                        <input type="text" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" name="cnic" value="<?php echo htmlspecialchars($c['cnic']); ?>" required placeholder="4130312345671">
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">New Password (leave blank to keep current)</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="pwdEdit<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>" name="password" placeholder="••••••••" style="padding-right: 56px;">
                                            <button type="button" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 0.8rem; font-weight: 600; color: #6b7280; cursor: pointer; padding: 0; z-index: 5;" onclick="const el = document.getElementById('pwdEdit<?php echo htmlspecialchars((string)($c['user_id']), ENT_QUOTES, 'UTF-8'); ?>'); el.type = el.type === 'password' ? 'text' : 'password'; this.innerText = el.type === 'password' ? 'Show' : 'Hide';">Show</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <div class="d-flex w-100 gap-2">
                                        <button type="button" class="btn flex-grow-1 rounded-pill fw-bold transition-all" style="background: rgba(100, 116, 139, 0.1); color: var(--text-primary); padding: 0.6rem 0;" onmouseover="this.style.background='rgba(100, 116, 139, 0.18)';" onmouseout="this.style.background='rgba(100, 116, 139, 0.1)';" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn flex-grow-1 rounded-pill fw-bold text-white transition-all shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 0.6rem 0;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Save Changes</button>
                                    </div>
                                </div>
                            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($coordinators)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No coordinators are registered for this department.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Coordinator Modal -->
<div class="modal fade" id="createCoordinatorModal" tabindex="-1" aria-labelledby="createCoordinatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius: 1.5rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2.5rem 2rem 1.5rem; border-bottom: 1px solid var(--border-color) !important;">
                <div class="position-absolute top-0 end-0 p-3">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="rounded-circle p-3 mb-3 d-flex align-items-center justify-content-center shadow-sm" style="background: var(--form-bg); border: 1px solid var(--border-color); width: 64px; height: 64px;">
                    <i class="bi bi-person-plus-fill text-primary" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-bold mb-1 text-primary text-center" style="font-size: 1.35rem; letter-spacing: -0.02em;" id="createCoordinatorModalLabel">Add Department Coordinator</h5>
                <p class="text-muted small mb-0">Fill in the details to register a new coordinator.</p>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/coordinators/create" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="coordFirstName" class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">First Name</label>
                            <input type="text" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="coordFirstName" name="first_name" required placeholder="e.g. Faheem">
                        </div>
                        <div class="col-md-6">
                            <label for="coordLastName" class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Last Name</label>
                            <input type="text" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="coordLastName" name="last_name" required placeholder="e.g. Soomro">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="coordEmail" class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Email Address</label>
                            <input type="email" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="coordEmail" name="email" required placeholder="email@university.edu">
                        </div>
                        <div class="col-md-6">
                            <label for="coordCnic" class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">CNIC / Passport (no dashes)</label>
                            <input type="text" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="coordCnic" name="cnic" required placeholder="e.g. 4130312345671" pattern="[0-9]{13}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="coordDesignation" class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Designation</label>
                            <select class="form-select border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="coordDesignation" name="designation" required>
                                <option value="Lecturer">Lecturer</option>
                                <option value="Assistant Professor" selected>Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Department</label>
                            <input type="text" class="form-control text-muted fw-semibold border-0 shadow-sm rounded-3 py-2 px-3" value="<?php echo htmlspecialchars($department); ?>" readonly style="background: var(--bg-color);">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="coordPassword" class="form-label small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control border-0 shadow-sm rounded-3 py-2 px-3 fw-medium" id="coordPassword" name="password" required placeholder="••••••••" style="padding-right: 56px;">
                            <button type="button" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 0.8rem; font-weight: 600; color: #6b7280; cursor: pointer; padding: 0; z-index: 5;" onclick="const el = document.getElementById('coordPassword'); el.type = el.type === 'password' ? 'text' : 'password'; this.innerText = el.type === 'password' ? 'Show' : 'Hide';">Show</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <div class="d-flex w-100 gap-2">
                        <button type="button" class="btn flex-grow-1 rounded-pill fw-bold transition-all" style="background: rgba(100, 116, 139, 0.1); color: var(--text-primary); padding: 0.6rem 0;" onmouseover="this.style.background='rgba(100, 116, 139, 0.18)';" onmouseout="this.style.background='rgba(100, 116, 139, 0.1)';" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn flex-grow-1 rounded-pill fw-bold text-white transition-all shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 0.6rem 0;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">Add Coordinator</button>
                    </div>
                </div>
            
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
        </div>
    </div>
</div>
