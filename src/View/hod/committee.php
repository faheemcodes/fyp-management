<!-- HOD Committee Management View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">FYP Committee Directory</h4>
            <p class="text-muted m-0 small">Manage evaluation committee members who grade student presentations and project phases</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-start align-self-sm-center" data-bs-toggle="modal" data-bs-target="#createCommitteeModal">
            <i class="bi bi-shield-plus me-2"></i> Add Committee Member
        </button>
    </div>

    <!-- Search Input -->
    <div class="mb-3">
        <div class="input-group" style="max-width: 380px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control table-search" placeholder="Search committee by name, email, department..." data-target="committees-table">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="committees-table">
            <thead>
                <tr>
                    <th>Committee Member Details</th>
                    <th>Department</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($committees as $c): ?>
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
                    <td><?php echo htmlspecialchars($c['department']); ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $c['user_id']; ?>">Edit</button>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/committee/delete?id=<?php echo $c['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this committee member? This will delete their user account permanently.')">Delete</a>
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
                    <div class="mb-3">
                        <label for="comName" class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control bg-light" id="comName" name="name" required placeholder="e.g. Prof. Zahid">
                    </div>

                    <div class="mb-3">
                        <label for="comEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="comEmail" name="email" required placeholder="name@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="comPassword" class="form-label small fw-semibold text-secondary">Password</label>
                        <input type="password" class="form-control bg-light" id="comPassword" name="password" required placeholder="••••••••">
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
