<!-- HOD Verify Students View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>


<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em">Verify Student Registrations</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7);font-size: 0.85rem">Review and approve self-registered student accounts in your department</p>
            </div>
        </div>
    </div>
</div>

<div class="page-section">
    <div class="page-section-header">
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
                            <img src="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-primary border-opacity-25" style="width: 40px;height: 40px;object-fit: cover" alt="Avatar">
                            <div>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($s['name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($s['email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold text-secondary"><?php echo htmlspecialchars($s['student_id']); ?></td>
                    <td><span class="badge bg-light text-dark border px-2.5 py-1"><?php echo htmlspecialchars($s['shift']); ?></span></td>
                    <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small">Pending</span></td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(4, 127, 176, 0.1);color: #047fb0;border: none;font-weight: 600" onmouseover="this.style.background='rgba(4, 127, 176, 0.18)';" onmouseout="this.style.background='rgba(4, 127, 176, 0.1)';" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="bi bi-info-circle-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-2">Details</span>
                            </button>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/students/approve?id=<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(16, 185, 129, 0.1);color: #10b981;border: none;font-weight: 600" onmouseover="this.style.background='rgba(16, 185, 129, 0.18)';" onmouseout="this.style.background='rgba(16, 185, 129, 0.1)';" onclick="confirmAction(event, 'Are you sure you want to approve this student?')">
                                <i class="bi bi-check-circle-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-2">Approve</span>
                            </a>
                            <button type="button" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(168, 10, 52, 0.1);color: #a80a34;border: none;font-weight: 600" onmouseover="this.style.background='rgba(168, 10, 52, 0.18)';" onmouseout="this.style.background='rgba(168, 10, 52, 0.1)';" onclick="openRejectModal('<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>')">
                                <i class="bi bi-x-circle-fill" style="font-size: 0.85rem"></i> <span class="d-none d-md-inline ms-2">Reject</span>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Details Modal -->
                <div class="modal fade" id="detailsModal<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0" style="border-radius: 1.5rem;overflow: hidden;box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25)">
                            <!-- Header with Gradient & Avatar -->
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2.5rem 2rem 1.5rem;border-bottom: 1px solid var(--border-color) !important">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                
                                <div class="position-relative d-inline-block mb-3">
                                    <div class="rounded-circle p-1" style="background: var(--card-bg);box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1)">
                                        <img src="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle" style="width: 100px;height: 100px;object-fit: cover" alt="Avatar">
                                    </div>
                                    <span class="position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 28px;height: 28px;background: #f59e0b;border: 3px solid var(--card-bg);transform: translate(-10%, -10%)" title="Pending Approval">
                                        <i class="bi bi-hourglass-split text-white" style="font-size: 0.75rem"></i>
                                    </span>
                                </div>
                                
                                <h5 class="fw-bold mb-1 text-primary text-center" style="font-size: 1.35rem;letter-spacing: -0.02em"><?php echo htmlspecialchars($s['name']); ?></h5>
                                <div class="badge rounded-pill text-primary mb-2" style="background: rgba(16, 185, 129, 0.1);font-size: 0.85rem;padding: 0.4rem 0.8rem;font-weight: 600">
                                    <?php echo htmlspecialchars($s['student_id']); ?>
                                </div>
                            </div>

                            <!-- Body with Info Cards -->
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <!-- Contact Info Card -->
                                    <div class="col-12">
                                        <div class="p-3 rounded-4" style="background: var(--form-bg);border: 1px solid var(--border-color)">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="text-primary shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px;height: 36px;background: var(--card-bg)">
                                                    <i class="bi bi-envelope-fill" style="font-size: 1rem"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;letter-spacing: 0.5px">Email Address</div>
                                                    <div class="fw-semibold" style="font-size: 0.95rem;color: var(--text-primary)"><?php echo htmlspecialchars($s['email']); ?></div>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center">
                                                <div class="text-primary shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px;height: 36px;background: var(--card-bg)">
                                                    <i class="bi bi-telephone-fill" style="font-size: 1rem"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;letter-spacing: 0.5px">Contact Phone</div>
                                                    <div class="fw-semibold" style="font-size: 0.95rem;color: var(--text-primary)"><?php echo htmlspecialchars($s['phone'] ?? 'N/A'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Department Card -->
                                    <div class="col-6">
                                        <div class="p-3 rounded-4 h-100 text-center" style="background: rgba(16, 185, 129, 0.05);border: 1px solid rgba(16, 185, 129, 0.1)">
                                            <i class="bi bi-building text-primary mb-2 d-block" style="font-size: 1.25rem"></i>
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem;letter-spacing: 0.5px">Department</div>
                                            <div class="fw-bold" style="font-size: 0.95rem;color: var(--text-primary)"><?php echo htmlspecialchars($s['department']); ?></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Shift Card -->
                                    <div class="col-6">
                                        <div class="p-3 rounded-4 h-100 text-center" style="background: rgba(245, 158, 11, 0.05);border: 1px solid rgba(245, 158, 11, 0.1)">
                                            <i class="bi bi-brightness-high-fill text-warning mb-2 d-block" style="font-size: 1.25rem"></i>
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem;letter-spacing: 0.5px">Shift</div>
                                            <div class="fw-bold" style="font-size: 0.95rem;color: var(--text-primary)"><?php echo htmlspecialchars($s['shift']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="modal-footer border-0 p-4 pt-0">
                                <div class="d-flex w-100 gap-2">
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/students/approve?id=<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn flex-grow-1 rounded-pill fw-bold text-white transition-all shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #10b981, #059669);font-size: 0.9rem;padding: 0.5rem 0" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';" onclick="confirmAction(event, 'Are you sure you want to approve this student?')">
                                        <i class="bi bi-check-circle-fill me-2 fs-6"></i> Approve
                                    </a>
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/students/reject?id=<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn flex-grow-1 rounded-pill fw-bold transition-all d-flex align-items-center justify-content-center" style="background: rgba(168, 10, 52, 0.1);color: #a80a34;font-size: 0.9rem;padding: 0.5rem 0" onmouseover="this.style.background='rgba(168, 10, 52, 0.18)';" onmouseout="this.style.background='rgba(168, 10, 52, 0.1)';" onclick="confirmAction(event, 'Are you sure you want to reject this student registration? This will delete their record permanently.')">
                                        <i class="bi bi-x-circle-fill me-2 fs-6"></i> Reject
                                    </a>
                                </div>
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

<!-- Reject User Modal -->
<div class="modal fade" id="rejectUserModal" tabindex="-1" aria-labelledby="rejectUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background: var(--card-bg)">
            <div class="modal-header border-bottom py-3 rounded-top-4" style="border-color: var(--border-color) !important">
                <h6 class="modal-title fw-semibold text-danger" id="rejectUserModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Reject Student Registration</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/hod/students/reject" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" id="rejectModalId" name="id">
                    <p class="text-secondary mb-3" style="font-size: 0.9rem;">Please provide a reason for rejecting this student. They will receive an email notification detailing the reason.</p>
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label fw-medium text-secondary" style="font-size: 0.85rem">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectReason" name="reason" rows="4" required placeholder="e.g. Invalid document uploaded, wrong department specified..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 rounded-bottom-4 d-flex justify-content-end gap-2" style="background: var(--card-bg)">
                    <button type="button" class="btn btn-light rounded-pill px-4 btn-sm fw-bold" data-bs-dismiss="modal" style="color: var(--text-secondary);border: 1px solid var(--border-color)">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 btn-sm fw-bold">Reject & Notify Student</button>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal(userId) {
        document.getElementById('rejectModalId').value = userId;
        document.getElementById('rejectReason').value = '';
        const modal = new bootstrap.Modal(document.getElementById('rejectUserModal'));
        modal.show();
    }
</script>
