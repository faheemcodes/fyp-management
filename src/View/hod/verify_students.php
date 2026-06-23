<!-- HOD Verify Students View -->
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
    background: conic-gradient(from 0deg, #f59e0b, #fbbf24, #d97706, #f59e0b);
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
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #f59e0b, #fbbf24, #d97706, #f59e0b);">
                <i class="bi bi-person-bounding-box"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em;">Verify Student Registrations</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Review and approve self-registered student accounts in your department</p>
            </div>
        </div>
    </div>
</div>

<div class="grp-section">
    <div class="grp-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-6 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search pending students by name, roll no..." data-target="pending-students-table">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="pending-students-table">
            <thead>
                <tr>
                    <th class="ps-4">Student Details</th>
                    <th>Roll Number</th>
                    <th>Shift</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $s): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <?php $avatarFile = !empty($s['avatar']) ? $s['avatar'] : 'default_avatar.svg'; ?>
                            <img src="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-primary border-opacity-25" style="width: 40px; height: 40px; object-fit: cover;" alt="Avatar">
                            <div>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($s['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($s['email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold text-secondary"><?php echo htmlspecialchars($s['student_id']); ?></td>
                    <td><span class="badge bg-light text-dark border px-2.5 py-1"><?php echo htmlspecialchars($s['shift']); ?></span></td>
                    <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small">Pending</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $s['user_id']; ?>">Details</button>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/students/approve?id=<?php echo $s['user_id']; ?>" class="btn btn-sm btn-success text-white rounded-pill px-3 me-1" onclick="return confirm('Are you sure you want to approve this student?')">Approve</a>
                        <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/students/reject?id=<?php echo $s['user_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to reject this student registration? This will delete their record permanently.')">Reject</a>
                    </td>
                </tr>

                <!-- Details Modal -->
                <div class="modal fade" id="detailsModal<?php echo $s['user_id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-3 border-0 shadow">
                            <div class="modal-header bg-dark text-white border-0 py-3">
                                <h5 class="modal-title fw-bold"><i class="bi bi-person-bounding-box me-2"></i>Registration Details - <?php echo htmlspecialchars($s['name']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <div class="row g-4 align-items-center mb-4 border-bottom pb-4">
                                    <div class="col-md-3 text-center">
                                        <img src="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="img-thumbnail rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar">
                                        <div class="badge bg-warning text-dark text-uppercase shadow-xs">Pending Account</div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <small class="text-muted d-block uppercase" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px;">Full Name</small>
                                                <span class="fw-semibold text-dark fs-6"><?php echo htmlspecialchars($s['name']); ?></span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted d-block uppercase" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px;">Roll Number / Student ID</small>
                                                <span class="fw-bold text-primary fs-6"><?php echo htmlspecialchars($s['student_id']); ?></span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted d-block uppercase" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px;">Email Address</small>
                                                <span class="text-dark fs-6"><?php echo htmlspecialchars($s['email']); ?></span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted d-block uppercase" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px;">Contact Phone</small>
                                                <span class="text-dark fs-6"><?php echo htmlspecialchars($s['phone'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block" style="font-size: 0.68rem; font-weight: 700;">Department</small>
                                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($s['department']); ?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block" style="font-size: 0.68rem; font-weight: 700;">Shift Selection</small>
                                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($s['shift']); ?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block" style="font-size: 0.68rem; font-weight: 700;">Avatar Image File</small>
                                        <span class="text-muted small word-break"><?php echo htmlspecialchars($avatarFile); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-3 bg-light rounded-bottom text-end">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No pending student registration requests for your department.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
