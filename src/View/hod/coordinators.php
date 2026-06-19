<!-- HOD Coordinator Management View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Department Coordinators Directory</h4>
            <p class="text-muted m-0 small">Create, edit, or remove FYP coordinators for the Department of <strong><?php echo htmlspecialchars($department); ?></strong></p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-start align-self-sm-center" data-bs-toggle="modal" data-bs-target="#createCoordinatorModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Coordinator
        </button>
    </div>

    <!-- Search Input -->
    <div class="mb-3">
        <div class="input-group" style="max-width: 380px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control table-search" placeholder="Search coordinators by name, email, cnic..." data-target="coordinators-table">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="coordinators-table">
            <thead>
                <tr>
                    <th>Coordinator Details</th>
                    <th>CNIC / Passport</th>
                    <th>Department</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($coordinators as $c): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                <?php echo strtoupper(substr($c['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($c['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($c['email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-monospace text-secondary small"><?php echo htmlspecialchars($c['cnic'] ?? 'N/A'); ?></span>
                    </td>
                    <td>
                        <span class="small text-muted"><?php echo htmlspecialchars($c['department']); ?></span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $c['user_id']; ?>">Edit</button>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/coordinators/delete?id=<?php echo $c['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this coordinator? This will permanently delete their account.')">Delete</a>
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
                    <div class="mb-3">
                        <label for="coordName" class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control bg-light" id="coordName" name="name" required placeholder="e.g. Dr. Hameed">
                    </div>

                    <div class="mb-3">
                        <label for="coordEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="coordEmail" name="email" required placeholder="email@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="coordCnic" class="form-label small fw-semibold text-secondary">CNIC / Passport (no dashes)</label>
                        <input type="text" class="form-control bg-light" id="coordCnic" name="cnic" required placeholder="4130312345671">
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
