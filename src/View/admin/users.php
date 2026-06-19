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
                <option value="hod">HOD</option>
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
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1 btn-view-user shadow-sm" 
                            data-bs-toggle="modal" data-bs-target="#viewUserModal"
                            data-id="<?php echo $u['id']; ?>"
                            data-name="<?php echo htmlspecialchars($u['name'] ?? ''); ?>"
                            data-role="<?php echo htmlspecialchars($u['role'] ?? ''); ?>"
                            data-email="<?php echo htmlspecialchars($u['email'] ?? ''); ?>"
                            data-cnic="<?php echo htmlspecialchars($u['cnic'] ?? 'N/A'); ?>"
                            data-student-id="<?php echo htmlspecialchars($u['student_id'] ?? 'N/A'); ?>"
                            data-dept="<?php echo htmlspecialchars($u['department'] ?? ''); ?>"
                            data-shift="<?php echo htmlspecialchars($u['shift'] ?? 'N/A'); ?>"
                            data-father="<?php echo htmlspecialchars($u['father_name'] ?? 'N/A'); ?>"
                            data-phone="<?php echo htmlspecialchars(($u['mobile_code'] ?? '') . ($u['mobile_no'] ?? 'N/A')); ?>"
                            data-gender="<?php echo htmlspecialchars($u['gender'] ?? 'N/A'); ?>"
                            data-dob="<?php echo htmlspecialchars($u['dob'] ?? 'N/A'); ?>"
                            data-domicile="<?php echo htmlspecialchars(($u['province_state'] ?? '') . ' / ' . ($u['district'] ?? 'N/A')); ?>"
                            data-address="<?php echo htmlspecialchars($u['home_address'] ?? 'Not Provided Yet'); ?>"
                            data-designation="<?php echo htmlspecialchars($u['designation'] ?? 'N/A'); ?>"
                            data-status="<?php echo htmlspecialchars($u['status'] ?? ''); ?>"
                            data-avatar="<?php echo htmlspecialchars($u['role'] === 'student' ? (!empty($u['avatar']) ? $u['avatar'] : 'default_avatar.svg') : ''); ?>">
                            <i class="bi bi-eye-fill me-1"></i> Details
                        </button>
                        <?php if($u['status'] === 'pending'): ?>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/approve?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 me-1 shadow-sm">Approve</a>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/reject?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">Reject</a>
                        <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1 btn-edit-user shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#editUserModal"
                                data-id="<?php echo $u['id']; ?>"
                                data-name="<?php echo htmlspecialchars($u['name'] ?? ''); ?>"
                                data-role="<?php echo htmlspecialchars($u['role'] ?? ''); ?>"
                                data-email="<?php echo htmlspecialchars($u['email'] ?? ''); ?>"
                                data-cnic="<?php echo htmlspecialchars($u['cnic'] ?? ''); ?>"
                                data-student-id="<?php echo htmlspecialchars($u['student_id'] ?? ''); ?>"
                                data-dept="<?php echo htmlspecialchars($u['department'] ?? ''); ?>"
                                data-shift="<?php echo htmlspecialchars($u['shift'] ?? 'Morning'); ?>"
                                data-designation="<?php echo htmlspecialchars($u['designation'] ?? ''); ?>"
                                data-interests="<?php echo htmlspecialchars($u['research_interest'] ?? ''); ?>">
                                <i class="bi bi-pencil-fill me-1"></i> Edit
                            </button>
                            <?php if($u['role'] !== 'admin'): ?>
                                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/delete?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Are you sure you want to permanently delete this user account? This cannot be undone.');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            <?php endif; ?>
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
                            <option value="hod">HOD</option>
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="editUserModalLabel">Edit User Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/admin/users/edit" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" id="editModalId" name="id">
                    <input type="hidden" id="editModalRole" name="role">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Role</label>
                        <input type="text" class="form-control bg-light" id="editModalRoleDisplay" readonly style="font-weight: bold; text-transform: uppercase;">
                    </div>

                    <div class="mb-3">
                        <label for="editModalName" class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control bg-light" id="editModalName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="editModalEmail" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control bg-light" id="editModalEmail" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="editModalCnic" class="form-label small fw-semibold text-secondary">CNIC / Passport (no dashes)</label>
                        <input type="text" class="form-control bg-light" id="editModalCnic" name="cnic" required>
                    </div>

                    <div class="mb-3">
                        <label for="editModalPassword" class="form-label small fw-semibold text-secondary">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control bg-light" id="editModalPassword" name="password" placeholder="••••••••">
                    </div>

                    <div class="mb-3" id="editModalDeptGroup">
                        <label for="editModalDepartment" class="form-label small fw-semibold text-secondary">Department</label>
                        <select class="form-select bg-light" id="editModalDepartment" name="department">
                            <option value="Computer Science">Computer Science</option>
                            <option value="Software Engineering">Software Engineering</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Data Science">Data Science</option>
                            <option value="Electronic Engineering">Electronic Engineering</option>
                            <option value="Telecommunication Engineering">Telecommunication Engineering</option>
                        </select>
                    </div>

                    <!-- Student Specific -->
                    <div id="editModalStudentFields" class="d-none">
                        <div class="mb-3">
                            <label for="editModalStudentId" class="form-label small fw-semibold text-secondary">Student Registration ID</label>
                            <input type="text" class="form-control bg-light" id="editModalStudentId" name="student_id">
                        </div>
                        <div class="mb-3">
                            <label for="editModalShift" class="form-label small fw-semibold text-secondary">Shift</label>
                            <select class="form-select bg-light" id="editModalShift" name="shift">
                                <option value="Morning">Morning</option>
                                <option value="Evening">Evening</option>
                            </select>
                        </div>
                    </div>

                    <!-- Supervisor Specific -->
                    <div id="editModalSupervisorFields" class="d-none">
                        <div class="mb-3">
                            <label for="editModalDesignation" class="form-label small fw-semibold text-secondary">Designation</label>
                            <select class="form-select bg-light" id="editModalDesignation" name="designation">
                                <option value="Lecturer">Lecturer</option>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editModalInterests" class="form-label small fw-semibold text-secondary">Research Interests</label>
                            <input type="text" class="form-control bg-light" id="editModalInterests" name="research_interest">
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

<!-- View User Details Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="viewUserModalLabel"><i class="bi bi-person-lines-fill me-2"></i>User Account Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Left column: Avatar and status -->
                    <div class="col-md-4 text-center border-end">
                        <div class="mb-3 position-relative d-inline-block">
                            <img id="detailAvatar" src="#" class="rounded-circle border border-primary border-opacity-25 shadow" style="width: 140px; height: 140px; object-fit: cover;" alt="Profile Picture">
                            <div id="detailInitials" class="rounded-circle bg-light text-primary d-none align-items-center justify-content-center shadow mx-auto" style="width: 140px; height: 140px; font-size: 4rem; font-weight: bold;">
                                X
                            </div>
                        </div>
                        <h5 id="detailName" class="fw-bold text-dark mb-1">Full Name</h5>
                        <p id="detailRoleBadge" class="mb-2"><span class="badge bg-secondary text-uppercase">Role</span></p>
                        <span id="detailStatusBadge" class="badge rounded-pill px-3 py-1.5 small mb-3">Status</span>
                        
                        <div id="modalActionButtons" class="mt-4 pt-3 border-top d-none">
                            <h6 class="text-muted small fw-bold mb-3">Pending Registration Action</h6>
                            <a id="modalApproveBtn" href="#" class="btn btn-success w-100 rounded-pill mb-2 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i>Approve Account</a>
                            <a id="modalRejectBtn" href="#" class="btn btn-danger w-100 rounded-pill shadow-sm"><i class="bi bi-trash-fill me-2"></i>Reject & Delete</a>
                        </div>
                    </div>
                    
                    <!-- Right column: Detailed fields -->
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle m-0">
                                <tbody>
                                    <tr class="border-bottom"><td class="text-muted py-2" style="width: 35%; font-size: 0.8rem; font-weight: 600;">Email Address:</td><td id="detailEmail" class="fw-semibold text-dark py-2">email@domain.com</td></tr>
                                    <tr class="border-bottom"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">CNIC / B-Form No:</td><td id="detailCnic" class="fw-semibold text-dark py-2">3520112345671</td></tr>
                                    <tr class="border-bottom"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Department:</td><td id="detailDept" class="fw-semibold text-dark py-2">Software Engineering</td></tr>
                                    
                                    <!-- Student details -->
                                    <tr class="border-bottom student-detail-row"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Roll Number:</td><td id="detailStudentId" class="fw-semibold text-dark py-2">2k23/SWE/001</td></tr>
                                    <tr class="border-bottom student-detail-row"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Shift:</td><td id="detailShift" class="fw-semibold text-dark py-2">Morning</td></tr>
                                    <tr class="border-bottom student-detail-row"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Father's Name:</td><td id="detailFather" class="fw-semibold text-dark py-2">Father Name</td></tr>
                                    
                                    <!-- Staff details -->
                                    <tr class="border-bottom staff-detail-row"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Designation:</td><td id="detailDesignation" class="fw-semibold text-dark py-2">Assistant Professor</td></tr>
                                    
                                    <!-- Common details -->
                                    <tr class="border-bottom"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Contact Number:</td><td id="detailPhone" class="fw-semibold text-dark py-2">+923001234567</td></tr>
                                    <tr class="border-bottom"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Gender:</td><td id="detailGender" class="fw-semibold text-dark py-2">Male</td></tr>
                                    <tr class="border-bottom"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Date of Birth:</td><td id="detailDob" class="fw-semibold text-dark py-2">2000-01-01</td></tr>
                                    <tr class="border-bottom"><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Domicile Location:</td><td id="detailDomicile" class="fw-semibold text-dark py-2">Sindh / Jamshoro</td></tr>
                                    <tr><td class="text-muted py-2" style="font-size: 0.8rem; font-weight: 600;">Home Address:</td><td id="detailAddress" class="text-wrap text-dark py-2">Not Provided Yet</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
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

        // View User Details logic
        const viewButtons = document.querySelectorAll('.btn-view-user');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const email = this.getAttribute('data-email');
                const cnic = this.getAttribute('data-cnic');
                const studentId = this.getAttribute('data-student-id');
                const dept = this.getAttribute('data-dept');
                const shift = this.getAttribute('data-shift');
                const father = this.getAttribute('data-father');
                const phone = this.getAttribute('data-phone');
                const gender = this.getAttribute('data-gender');
                const dob = this.getAttribute('data-dob');
                const domicile = this.getAttribute('data-domicile');
                const address = this.getAttribute('data-address');
                const designation = this.getAttribute('data-designation');
                const status = this.getAttribute('data-status');
                const avatar = this.getAttribute('data-avatar');
                
                // Populate text fields
                document.getElementById('detailName').textContent = name;
                document.getElementById('detailEmail').textContent = email;
                document.getElementById('detailCnic').textContent = cnic;
                document.getElementById('detailDept').textContent = dept;
                document.getElementById('detailPhone').textContent = phone;
                document.getElementById('detailGender').textContent = gender;
                document.getElementById('detailDob').textContent = dob;
                document.getElementById('detailDomicile').textContent = domicile;
                document.getElementById('detailAddress').textContent = address;
                
                // Role Badge
                const roleBadge = document.querySelector('#detailRoleBadge span');
                roleBadge.textContent = role;
                
                // Status Badge
                const statusBadge = document.getElementById('detailStatusBadge');
                statusBadge.className = 'badge rounded-pill px-3 py-1.5 small mb-3';
                if (status === 'approved') {
                    statusBadge.textContent = 'Approved';
                    statusBadge.classList.add('bg-success-subtle', 'text-success', 'border', 'border-success-subtle');
                } else if (status === 'pending') {
                    statusBadge.textContent = 'Pending';
                    statusBadge.classList.add('bg-warning-subtle', 'text-warning', 'border', 'border-warning-subtle');
                } else {
                    statusBadge.textContent = 'Rejected';
                    statusBadge.classList.add('bg-danger-subtle', 'text-danger', 'border', 'border-danger-subtle');
                }
                
                // Avatar handling
                const imgEl = document.getElementById('detailAvatar');
                const initialsEl = document.getElementById('detailInitials');
                
                if (role === 'student' && avatar && avatar !== 'default_avatar.svg') {
                    imgEl.src = `<?php echo $basePath; ?>/uploads/avatars/${avatar}`;
                    imgEl.classList.remove('d-none');
                    initialsEl.classList.add('d-none');
                    initialsEl.style.display = 'none';
                } else {
                    imgEl.classList.add('d-none');
                    imgEl.src = '#';
                    initialsEl.textContent = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    initialsEl.classList.remove('d-none');
                    initialsEl.style.display = 'flex';
                }
                
                // Conditional Student/Staff rows display
                const studentRows = document.querySelectorAll('.student-detail-row');
                const staffRows = document.querySelectorAll('.staff-detail-row');
                
                if (role === 'student') {
                    studentRows.forEach(row => row.classList.remove('d-none'));
                    staffRows.forEach(row => row.classList.add('d-none'));
                    document.getElementById('detailStudentId').textContent = studentId;
                    document.getElementById('detailShift').textContent = shift;
                    document.getElementById('detailFather').textContent = father;
                } else {
                    studentRows.forEach(row => row.classList.add('d-none'));
                    staffRows.forEach(row => row.classList.remove('d-none'));
                    document.getElementById('detailDesignation').textContent = designation;
                }
                
                // Pending modal action buttons
                const actionContainer = document.getElementById('modalActionButtons');
                if (status === 'pending') {
                    actionContainer.classList.remove('d-none');
                    
                    const basePathClean = "<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>";
                    document.getElementById('modalApproveBtn').href = `${basePathClean}/admin/users/approve?id=${userId}`;
                    document.getElementById('modalRejectBtn').href = `${basePathClean}/admin/users/reject?id=${userId}`;
                } else {
                    actionContainer.classList.add('d-none');
                }
            });
        });

        // Edit User Details logic
        const editButtons = document.querySelectorAll('.btn-edit-user');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const email = this.getAttribute('data-email');
                const cnic = this.getAttribute('data-cnic');
                const studentId = this.getAttribute('data-student-id');
                const dept = this.getAttribute('data-dept');
                const shift = this.getAttribute('data-shift');
                const designation = this.getAttribute('data-designation');
                const interests = this.getAttribute('data-interests');
                
                document.getElementById('editModalId').value = userId;
                document.getElementById('editModalRole').value = role;
                document.getElementById('editModalRoleDisplay').value = role;
                document.getElementById('editModalName').value = name;
                document.getElementById('editModalEmail').value = email;
                document.getElementById('editModalCnic').value = cnic;
                document.getElementById('editModalPassword').value = '';
                
                const deptSelect = document.getElementById('editModalDepartment');
                if (dept) {
                    deptSelect.value = dept;
                }
                
                const stdFields = document.getElementById('editModalStudentFields');
                const supFields = document.getElementById('editModalSupervisorFields');
                const deptGroup = document.getElementById('editModalDeptGroup');
                
                stdFields.classList.add('d-none');
                supFields.classList.add('d-none');
                deptGroup.classList.remove('d-none');
                
                if (role === 'student') {
                    stdFields.classList.remove('d-none');
                    document.getElementById('editModalStudentId').value = studentId;
                    document.getElementById('editModalShift').value = shift;
                } else if (role === 'supervisor') {
                    supFields.classList.remove('d-none');
                    document.getElementById('editModalDesignation').value = designation;
                    document.getElementById('editModalInterests').value = interests;
                } else if (role === 'admin') {
                    deptGroup.classList.add('d-none');
                }
            });
        });
    });
</script>
