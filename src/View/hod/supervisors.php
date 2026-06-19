<!-- HOD Supervisor Management View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Supervisor Faculty Directory</h4>
            <p class="text-muted m-0 small">Create, edit, or remove FYP supervisors under your faculty</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-start align-self-sm-center" data-bs-toggle="modal" data-bs-target="#createSupervisorModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Supervisor
        </button>
    </div>

    <!-- Search Input -->
    <div class="mb-3">
        <div class="input-group" style="max-width: 380px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control table-search" placeholder="Search supervisors by name, email, department..." data-target="supervisors-table">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="supervisors-table">
            <thead>
                <tr>
                    <th>Supervisor Details</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Research Interest</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($supervisors as $s): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                <?php echo strtoupper(substr($s['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($s['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($s['email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5"><?php echo htmlspecialchars($s['designation']); ?></span></td>
                    <td><?php echo htmlspecialchars($s['department']); ?></td>
                    <td>
                        <div class="text-wrap small text-muted" style="max-width: 200px;">
                            <?php echo htmlspecialchars($s['research_interest'] ?? 'Not specified'); ?>
                        </div>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $s['user_id']; ?>">Edit</button>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/supervisors/delete?id=<?php echo $s['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this supervisor? This will delete their user account permanently.')">Delete</a>
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
                    <div class="mb-3">
                        <label for="supName" class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control bg-light" id="supName" name="name" required placeholder="e.g. Dr. Faheem">
                    </div>

                    <div class="mb-3">
                        <label for="supEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="supEmail" name="email" required placeholder="name@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="supPassword" class="form-label small fw-semibold text-secondary">Password</label>
                        <input type="password" class="form-control bg-light" id="supPassword" name="password" required placeholder="••••••••">
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
