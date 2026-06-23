<!-- HOD Committee Management View -->
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
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #8b5cf6, #6366f1, #3b82f6, #8b5cf6);">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">FYP Committee Directory</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Manage evaluation committee members who grade student presentations and project phases</p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0" style="background: linear-gradient(135deg, #3b82f6, #2563eb);" data-bs-toggle="modal" data-bs-target="#createCommitteeModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add Member
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
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search committee by name, email, department..." data-target="committees-table">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="committees-table">
            <thead>
                <tr>
                    <th class="ps-4">Committee Member Details</th>
                    <th>Department</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($committees as $c): ?>
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
                        <span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($c['department']); ?></span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $c['user_id']; ?>"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                        <a href="<?php echo $basePath; ?>/hod/committee/delete?id=<?php echo $c['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this committee member? This will delete their user account permanently.')"><i class="bi bi-trash-fill me-1"></i>Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $c['user_id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content rounded-3 border-0">
                            <div class="modal-header bg-dark text-white border-0 py-2.5">
                                <h6 class="modal-title fw-bold">Edit Committee Member</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/committee/edit" method="POST">
                                <div class="modal-body p-3">
                                    <input type="hidden" name="user_id" value="<?php echo $c['user_id']; ?>">
                                    
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Full Name</label>
                                        <input type="text" class="form-control bg-light" name="name" value="<?php echo htmlspecialchars($c['name']); ?>" required>
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Department</label>
                                        <select class="form-select bg-light" name="department" required>
                                            <option value="Computer Science" <?php echo $c['department'] === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                            <option value="Software Engineering" <?php echo $c['department'] === 'Software Engineering' ? 'selected' : ''; ?>>Software Engineering</option>
                                            <option value="Information Technology" <?php echo $c['department'] === 'Information Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                            <option value="Data Science" <?php echo $c['department'] === 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                    <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($committees)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No committee members have been registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Committee Modal -->
<div class="modal fade" id="createCommitteeModal" tabindex="-1" aria-labelledby="createCommitteeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="createCommitteeModalLabel">Add New Committee Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/committee/create" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="comFirstName" class="form-label small fw-semibold text-secondary">First Name</label>
                            <input type="text" class="form-control bg-light" id="comFirstName" name="first_name" required placeholder="e.g. Zahid">
                        </div>
                        <div class="col-6">
                            <label for="comLastName" class="form-label small fw-semibold text-secondary">Last Name</label>
                            <input type="text" class="form-control bg-light" id="comLastName" name="last_name" required placeholder="e.g. Hussain">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comCnic" class="form-label small fw-semibold text-secondary">CNIC (no dashes)</label>
                        <input type="text" class="form-control bg-light" id="comCnic" name="cnic" required placeholder="e.g. 4130312345671" pattern="[0-9]{13}">
                    </div>

                    <div class="mb-3">
                        <label for="comDesignation" class="form-label small fw-semibold text-secondary">Designation</label>
                        <select class="form-select bg-light" id="comDesignation" name="designation" required>
                            <option value="Lecturer">Lecturer</option>
                            <option value="Assistant Professor" selected>Assistant Professor</option>
                            <option value="Associate Professor">Associate Professor</option>
                            <option value="Professor">Professor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comContact" class="form-label small fw-semibold text-secondary">Contact No. (Phone)</label>
                        <input type="text" class="form-control bg-light" id="comContact" name="contact_no" required placeholder="e.g. 03001234567">
                    </div>

                    <div class="mb-3">
                        <label for="comEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="comEmail" name="email" required placeholder="name@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="comPassword" class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-light" id="comPassword" name="password" required placeholder="••••••••">
                            <button class="btn btn-outline-secondary bg-light text-muted border" type="button" onclick="const el = document.getElementById('comPassword'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comDept" class="form-label small fw-semibold text-secondary">Department</label>
                        <select class="form-select bg-light" id="comDept" name="department" required>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Software Engineering" selected>Software Engineering</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Data Science">Data Science</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
