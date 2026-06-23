<!-- HOD Supervisor Management View -->
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
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #10b981, #34d399, #059669, #10b981);">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Supervisor Faculty Directory</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Create, edit, or remove FYP supervisors under your faculty</p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-stretch align-self-md-center shadow-sm border-0" style="background: linear-gradient(135deg, #10b981, #059669);" data-bs-toggle="modal" data-bs-target="#createSupervisorModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Supervisor
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
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search supervisors by name, email, department..." data-target="supervisors-table">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="supervisors-table">
            <thead>
                <tr>
                    <th class="ps-4">Supervisor Details</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Research Interest</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($supervisors as $s): ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">
                                <?php echo strtoupper(substr($s['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($s['name']); ?></div>
                                <small class="text-muted" style="font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($s['email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark border px-2.5 py-1.5"><?php echo htmlspecialchars($s['designation']); ?></span></td>
                    <td>
                        <span class="text-secondary small fw-medium"><?php echo htmlspecialchars($s['department']); ?></span>
                    </td>
                    <td>
                        <div class="text-wrap small text-muted" style="max-width: 200px;">
                            <?php echo htmlspecialchars($s['research_interest'] ?? 'Not specified'); ?>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $s['user_id']; ?>"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                        <a href="<?php echo $basePath; ?>/hod/supervisors/delete?id=<?php echo $s['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this supervisor? This will delete their user account permanently.')"><i class="bi bi-trash-fill me-1"></i>Delete</a>
                    </td>
                </tr>
                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $s['user_id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-3 border-0">
                            <div class="modal-header bg-dark text-white border-0 py-3">
                                <h5 class="modal-title fw-bold">Edit Supervisor Profile</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/supervisors/edit" method="POST">
                                <div class="modal-body p-4">
                                    <input type="hidden" name="user_id" value="<?php echo $s['user_id']; ?>">
                                    
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Full Name</label>
                                        <input type="text" class="form-control bg-light" name="name" value="<?php echo htmlspecialchars($s['name']); ?>" required>
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Designation</label>
                                        <select class="form-select bg-light" name="designation" required>
                                            <option value="Lecturer" <?php echo $s['designation'] === 'Lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                                            <option value="Assistant Professor" <?php echo $s['designation'] === 'Assistant Professor' ? 'selected' : ''; ?>>Assistant Professor</option>
                                            <option value="Associate Professor" <?php echo $s['designation'] === 'Associate Professor' ? 'selected' : ''; ?>>Associate Professor</option>
                                            <option value="Professor" <?php echo $s['designation'] === 'Professor' ? 'selected' : ''; ?>>Professor</option>
                                        </select>
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Department</label>
                                        <select class="form-select bg-light" name="department" required>
                                            <option value="Computer Science" <?php echo $s['department'] === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                            <option value="Software Engineering" <?php echo $s['department'] === 'Software Engineering' ? 'selected' : ''; ?>>Software Engineering</option>
                                            <option value="Information Technology" <?php echo $s['department'] === 'Information Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                            <option value="Data Science" <?php echo $s['department'] === 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                        </select>
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-semibold text-secondary">Research Interests</label>
                                        <textarea class="form-control bg-light" name="research_interest" rows="3"><?php echo htmlspecialchars($s['research_interest'] ?? ''); ?></textarea>
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
                <?php if (empty($supervisors)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No supervisors are registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Supervisor Modal -->
<div class="modal fade" id="createSupervisorModal" tabindex="-1" aria-labelledby="createSupervisorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="createSupervisorModalLabel">Add New Faculty Supervisor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/supervisors/create" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="supFirstName" class="form-label small fw-semibold text-secondary">First Name</label>
                            <input type="text" class="form-control bg-light" id="supFirstName" name="first_name" required placeholder="e.g. Faheem">
                        </div>
                        <div class="col-6">
                            <label for="supLastName" class="form-label small fw-semibold text-secondary">Last Name</label>
                            <input type="text" class="form-control bg-light" id="supLastName" name="last_name" required placeholder="e.g. Ahmed">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="supCnic" class="form-label small fw-semibold text-secondary">CNIC (no dashes)</label>
                        <input type="text" class="form-control bg-light" id="supCnic" name="cnic" required placeholder="e.g. 4130312345671" pattern="[0-9]{13}">
                    </div>

                    <div class="mb-3">
                        <label for="supContact" class="form-label small fw-semibold text-secondary">Contact No. (Phone)</label>
                        <input type="text" class="form-control bg-light" id="supContact" name="contact_no" required placeholder="e.g. 03001234567">
                    </div>

                    <div class="mb-3">
                        <label for="supEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="supEmail" name="email" required placeholder="name@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="supPassword" class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-light" id="supPassword" name="password" required placeholder="••••••••">
                            <button class="btn btn-outline-secondary bg-light text-muted border" type="button" onclick="const el = document.getElementById('supPassword'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="supDesignation" class="form-label small fw-semibold text-secondary">Designation</label>
                        <select class="form-select bg-light" id="supDesignation" name="designation" required>
                            <option value="Lecturer">Lecturer</option>
                            <option value="Assistant Professor" selected>Assistant Professor</option>
                            <option value="Associate Professor">Associate Professor</option>
                            <option value="Professor">Professor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="supDept" class="form-label small fw-semibold text-secondary">Department</label>
                        <select class="form-select bg-light" id="supDept" name="department" required>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Software Engineering">Software Engineering</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Data Science">Data Science</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="supInterests" class="form-label small fw-semibold text-secondary">Research Interests</label>
                        <textarea class="form-control bg-light" id="supInterests" name="research_interest" rows="3" placeholder="e.g. IoT, NLP, Machine Learning"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Add Supervisor</button>
                </div>
            </form>
        </div>
    </div>
</div>
