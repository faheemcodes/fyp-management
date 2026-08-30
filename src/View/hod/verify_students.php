<!-- HOD Verify Students View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$pendingCount = count($students);
?>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Verify Student Registrations</h4>
                    <span class="badge rounded-pill bg-white bg-opacity-20 text-white px-3 py-1 fw-semibold" style="font-size: 0.8rem">
                        <?php echo htmlspecialchars($department ?? 'FET', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75);font-size: 0.85rem">Review and approve self-registered student accounts under your department</p>
            </div>
        </div>

        <?php if ($pendingCount > 0): ?>
        <form action="<?php echo $basePath; ?>/hod/students/approve-all" method="POST" class="m-0 align-self-stretch align-self-md-center" onsubmit="return confirm('Are you sure you want to approve all <?php echo $pendingCount; ?> pending student registrations at once?');">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 w-100 shadow-sm border-0 d-flex align-items-center justify-content-center gap-2 fw-semibold" style="background: linear-gradient(135deg, #10b981, #059669)">
                <i class="bi bi-check-all fs-5"></i>
                <span>Approve All (<?php echo $pendingCount; ?>)</span>
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<div class="page-section">
    <div class="page-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-6 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search pending students by name, roll no, email..." data-target="pending-students-table">
                </div>
            </div>
            <div class="col-md-6 pe-0 text-md-end text-muted small">
                Showing <strong><?php echo $pendingCount; ?></strong> pending student registration(s)
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
                    <th>Registered On</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $s): ?>
                <?php $avatarFile = !empty($s['avatar']) ? $s['avatar'] : 'default_avatar.svg'; ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile, ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="rounded-circle border cursor-pointer avatar-hover-preview" 
                                 style="width: 42px;height: 42px;object-fit: cover; cursor: pointer;" 
                                 alt="Avatar"
                                 onclick="showStudentPhotoModal('<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($s['name']), ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($s['student_id']), ENT_QUOTES, 'UTF-8'); ?>')">
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.92rem;"><?php echo htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted" style="font-size: 0.78rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold font-monospace" style="color: var(--text-secondary);"><?php echo htmlspecialchars($s['student_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><span class="badge border px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;"><?php echo htmlspecialchars($s['shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td class="small text-muted"><?php echo !empty($s['registered_at']) ? date('M d, Y', strtotime($s['registered_at'])) : 'Recent'; ?></td>
                    <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small">Pending Verification</span></td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo $basePath; ?>/hod/students/approve?id=<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(16, 185, 129, 0.12);color: #10b981;border: 1px solid rgba(16, 185, 129, 0.25);font-weight: 600" onmouseover="this.style.background='rgba(16, 185, 129, 0.2)';" onmouseout="this.style.background='rgba(16, 185, 129, 0.12)';" onclick="confirmAction(event, 'Are you sure you want to approve this student?')">
                                <i class="bi bi-check-circle-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-1.5">Approve</span>
                            </a>
                            <button type="button" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(239, 68, 68, 0.12);color: #ef4444;border: 1px solid rgba(239, 68, 68, 0.25);font-weight: 600" onmouseover="this.style.background='rgba(239, 68, 68, 0.2)';" onmouseout="this.style.background='rgba(239, 68, 68, 0.12)';" onclick="openRejectModal('<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($s['name']), ENT_QUOTES, 'UTF-8'); ?>')">
                                <i class="bi bi-x-circle-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-1.5">Reject</span>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                        <strong>All caught up!</strong>
                        <p class="small text-muted mb-0">No pending student registrations waiting for verification.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-0 bg-danger bg-opacity-10 py-3 px-4">
                <div class="d-flex align-items-center gap-2 text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <h5 class="modal-title fw-bold m-0" style="font-size: 1.1rem;">Reject Student Registration</h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo $basePath; ?>/hod/students/reject" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="id" id="rejectStudentId">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Rejecting registration for <strong id="rejectStudentName" style="color: var(--text-primary);"></strong>. Please provide a clear reason that will be emailed to the student.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Reason for Rejection *</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="e.g. Invalid roll number or incorrect department selected..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3" style="background: var(--form-bg); border-top: 1px solid var(--border-color) !important;">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Student Avatar Preview Modal (Zero header border) -->
<div class="modal fade" id="studentPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg text-center" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-body p-4 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-flex flex-column align-items-center mt-2">
                    <img id="modalStudentPhoto" src="" class="rounded-circle shadow mb-3" style="width: 130px; height: 130px; object-fit: cover; border: 4px solid var(--form-bg);" alt="Student Photo">
                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);" id="modalStudentName"></h6>
                    <span class="badge border font-monospace mt-1 px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;" id="modalStudentRoll"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openRejectModal(id, name) {
    document.getElementById('rejectStudentId').value = id;
    document.getElementById('rejectStudentName').innerText = name;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function showStudentPhotoModal(src, name, roll) {
    document.getElementById('modalStudentPhoto').src = src;
    document.getElementById('modalStudentName').innerText = name;
    document.getElementById('modalStudentRoll').innerText = roll;
    new bootstrap.Modal(document.getElementById('studentPhotoModal')).show();
}
</script>
