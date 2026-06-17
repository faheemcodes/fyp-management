<!-- Admin User Management View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">User Account Management</h4>
            <p class="text-muted m-0 small">Approve self-registered accounts or add academic staff directly</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 align-self-start align-self-sm-center" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New User
        </button>
    </div>

    <!-- Filters and Search Controls -->
    <div class="row g-3 mb-4 align-items-center">
        <!-- Search Input -->
        <div class="col-md-4">
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0 table-search" placeholder="Search users by name, email, department..." data-target="users-table">
            </div>
        </div>
        <!-- Role Filter -->
        <div class="col-md-3">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="role" data-target="users-table">
                <option value="all">All Roles</option>
                <option value="student">Student</option>
                <option value="supervisor">Supervisor</option>
                <option value="committee">Committee Member</option>
                <option value="dean">Dean</option>
            </select>
        </div>
        <!-- Department Filter -->
        <div class="col-md-3">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="department" data-target="users-table">
                <option value="all">All Departments</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Software Engineering">Software Engineering</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Data Science">Data Science</option>
                <option value="Electronic Engineering">Electronic Engineering</option>
                <option value="Telecommunication Engineering">Telecommunication Engineering</option>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="col-md-2">
            <select class="form-select table-filter shadow-sm rounded-3 bg-light" data-column="status" data-target="users-table">
                <option value="all">All Statuses</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="users-table">
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr data-role="<?php echo htmlspecialchars($u['role']); ?>" data-department="<?php echo htmlspecialchars($u['department']); ?>" data-status="<?php echo htmlspecialchars($u['status']); ?>">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <?php if ($u['role'] === 'student'): ?>
                                <?php $avatarFile = !empty($u['avatar']) ? $u['avatar'] : 'default_avatar.svg'; ?>
                                <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-primary border-opacity-25" style="width: 40px; height: 40px; object-fit: cover;" alt="Avatar">
                            <?php else: ?>
                                <div class="avatar bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                    <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($u['name']); ?></div>
                                <div class="x-small text-muted"><?php echo htmlspecialchars($u['email']); ?></div>
                                <?php if($u['student_id']): ?>
                                    <div class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">ID: <?php echo htmlspecialchars($u['student_id']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary text-uppercase" style="font-size: 0.65rem;">
                            <?php echo htmlspecialchars($u['role']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="small"><?php echo htmlspecialchars($u['department']); ?></div>
                        <?php if($u['designation']): ?>
                            <small class="text-muted"><?php echo htmlspecialchars($u['designation']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($u['status'] === 'approved'): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">Approved</span>
                        <?php elseif($u['status'] === 'pending'): ?>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small animate-pulse">Pending</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <?php if($u['status'] === 'pending'): ?>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/approve?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 me-1">Approve</a>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/reject?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">Reject</a>
                        <?php else: ?>
                            <span class="text-muted small">No actions</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="createUserModalLabel">Add Academic / Student User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/create" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="modalRole" class="form-label small fw-semibold text-secondary">Account Role</label>
                        <select class="form-select bg-light" id="modalRole" name="role" required>
                            <option value="student">Student</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="committee">Committee Member</option>
                            <option value="dean">Dean</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalName" class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control bg-light" id="modalName" name="name" required placeholder="Dr. Ali Khan">
                    </div>

                    <div class="mb-3">
                        <label for="modalEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="modalEmail" name="email" required placeholder="ali.khan@university.edu">
                    </div>

                    <div class="mb-3">
                        <label for="modalPassword" class="form-label small fw-semibold text-secondary">Password</label>
                        <input type="password" class="form-control bg-light" id="modalPassword" name="password" required placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label for="modalDepartment" class="form-label small fw-semibold text-secondary">Department</label>
                        <select class="form-select bg-light" id="modalDepartment" name="department" required>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Software Engineering">Software Engineering</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Data Science">Data Science</option>
                        </select>
                    </div>

                    <!-- Student Specific -->
                    <div id="modalStudentFields" class="mb-3">
                        <label for="modalStudentId" class="form-label small fw-semibold text-secondary">Student Registration ID</label>
                        <input type="text" class="form-control bg-light" id="modalStudentId" name="student_id" placeholder="2023-CS-100">
                    </div>

                    <!-- Supervisor Specific -->
                    <div id="modalSupervisorFields" class="d-none">
                        <div class="mb-3">
                            <label for="modalDesignation" class="form-label small fw-semibold text-secondary">Designation</label>
                            <select class="form-select bg-light" id="modalDesignation" name="designation">
                                <option value="Lecturer">Lecturer</option>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modalInterests" class="form-label small fw-semibold text-secondary">Research Interests</label>
                            <input type="text" class="form-control bg-light" id="modalInterests" name="research_interest" placeholder="e.g. AI, NLP, Security">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('modalRole');
        const studentFields = document.getElementById('modalStudentFields');
        const supervisorFields = document.getElementById('modalSupervisorFields');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'student') {
                studentFields.classList.remove('d-none');
                supervisorFields.classList.add('d-none');
                document.getElementById('modalStudentId').required = true;
            } else if (this.value === 'supervisor') {
                studentFields.classList.add('d-none');
                supervisorFields.classList.remove('d-none');
                document.getElementById('modalStudentId').required = false;
            } else {
                studentFields.classList.add('d-none');
                supervisorFields.classList.add('d-none');
                document.getElementById('modalStudentId').required = false;
            }
        });
    });
</script>
