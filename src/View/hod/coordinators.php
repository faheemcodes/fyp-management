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
    width: 56px;
    height: 56px;
    background: conic-gradient(from 0deg, #3b82f6, #6366f1, #8b5cf6, #3b82f6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
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
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #ec4899, #f43f5e, #e11d48, #ec4899);">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Department Coordinators Directory</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Create, edit, or remove FYP coordinators for the Department of <strong><?php echo htmlspecialchars($department); ?></strong></p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0" style="background: linear-gradient(135deg, #ec4899, #e11d48);" data-bs-toggle="modal" data-bs-target="#createCoordinatorModal">
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
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $c['user_id']; ?>"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                        <a href="<?php echo $basePath; ?>/hod/coordinators/delete?id=<?php echo $c['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this coordinator? This will permanently delete their account.')"><i class="bi bi-trash-fill me-1"></i>Delete</a>
                    </td>
                </tr>
                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $c['user_id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-3 border-0">
                            <div class="modal-header bg-dark text-white border-0 py-3">
                                <h5 class="modal-title fw-bold">Edit Coordinator Details</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/coordinators/edit" method="POST">
                                <div class="modal-body p-4">
                                    <input type="hidden" name="user_id" value="<?php echo $c['user_id']; ?>">
                                    
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Full Name</label>
                                        <input type="text" class="form-control bg-light" name="name" value="<?php echo htmlspecialchars($c['name']); ?>" required placeholder="e.g. Dr. Hameed">
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Email Address</label>
                                        <input type="email" class="form-control bg-light" name="email" value="<?php echo htmlspecialchars($c['email']); ?>" required placeholder="email@university.edu">
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">CNIC / Passport (no dashes)</label>
                                        <input type="text" class="form-control bg-light" name="cnic" value="<?php echo htmlspecialchars($c['cnic']); ?>" required placeholder="4130312345671">
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">New Password (leave blank to keep current)</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control bg-light" id="pwdEdit<?php echo $c['user_id']; ?>" name="password" placeholder="••••••••">
                                            <button class="btn btn-outline-secondary bg-light text-muted border" type="button" onclick="const el = document.getElementById('pwdEdit<?php echo $c['user_id']; ?>'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Save Changes</button>
                                </div>
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
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="createCoordinatorModalLabel">Add Department Coordinator</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/coordinators/create" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="coordFirstName" class="form-label small fw-semibold text-secondary">First Name</label>
                            <input type="text" class="form-control bg-light" id="coordFirstName" name="first_name" required placeholder="e.g. Hameed">
                        </div>
                        <div class="col-6">
                            <label for="coordLastName" class="form-label small fw-semibold text-secondary">Last Name</label>
                            <input type="text" class="form-control bg-light" id="coordLastName" name="last_name" required placeholder="e.g. Memon">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="coordEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="coordEmail" name="email" required placeholder="email@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="coordCnic" class="form-label small fw-semibold text-secondary">CNIC / Passport (no dashes)</label>
                        <input type="text" class="form-control bg-light" id="coordCnic" name="cnic" required placeholder="e.g. 4130312345671" pattern="[0-9]{13}">
                    </div>

                    <div class="mb-3">
                        <label for="coordDesignation" class="form-label small fw-semibold text-secondary">Designation</label>
                        <select class="form-select bg-light" id="coordDesignation" name="designation" required>
                            <option value="Lecturer">Lecturer</option>
                            <option value="Assistant Professor" selected>Assistant Professor</option>
                            <option value="Associate Professor">Associate Professor</option>
                            <option value="Professor">Professor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="coordContact" class="form-label small fw-semibold text-secondary">Contact No. (Phone)</label>
                        <input type="text" class="form-control bg-light" id="coordContact" name="contact_no" required placeholder="e.g. 03001234567">
                    </div>

                    <div class="mb-3">
                        <label for="coordPassword" class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-light" id="coordPassword" name="password" required placeholder="••••••••">
                            <button class="btn btn-outline-secondary bg-light text-muted border" type="button" onclick="const el = document.getElementById('coordPassword'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Department</label>
                        <input type="text" class="form-control bg-light text-muted fw-semibold" value="<?php echo htmlspecialchars($department); ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Add Coordinator</button>
                </div>
            </form>
        </div>
    </div>
</div>
