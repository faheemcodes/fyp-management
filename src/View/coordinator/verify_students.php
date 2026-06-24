<!-- Coordinator Verify Students View (Redesigned) -->
<style>
/* ─── Hero Section ─── */
.eval-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.eval-hero::before {
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
.eval-hero::after {
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
.eval-hero-icon {
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
.eval-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.eval-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
}
.eval-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}
.eval-section-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.eval-section-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}
.eval-section-header small {
    font-size: 0.72rem;
    color: var(--text-secondary);
    margin: 0;
}

/* ─── Table ─── */
.eval-table th {
    background: transparent;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}
.eval-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
}
.eval-table tbody tr:last-child td {
    border-bottom: none;
}
.eval-table tbody tr {
    transition: background-color 0.2s;
}
.eval-table tbody tr:hover {
    background-color: var(--table-hover-bg);
}

/* ─── Badges & Status ─── */
.status-badge-pending {
    background: rgba(245, 158, 11, 0.15); 
    color: #d97706; 
    border-radius: 50rem; 
    padding: 6px 12px; 
    font-weight: 600; 
    font-size: 0.75rem;
}
html.dark-theme .status-badge-pending {
    background: rgba(245, 158, 11, 0.2);
    color: #fcd34d;
}

/* Modals Override - High z-index */
.modal {
    z-index: 99999 !important;
}
.modal-backdrop {
    z-index: 99998 !important;
}
.eval-modal .modal-content {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.eval-modal .modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
    background: var(--form-bg);
    color: var(--text-primary);
}
.eval-modal .modal-body {
    padding: 1.5rem;
    color: var(--text-primary);
}
.eval-modal .modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1.5rem;
}

/* Fix close button visibility in dark theme */
html.dark-theme .modal .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.8;
}
html.dark-theme .modal .btn-close:hover {
    opacity: 1;
}

/* Mobile Responsiveness */
@media (max-width: 576px) {
    .eval-section-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 15px;
    }
    .eval-section-header .input-group {
        max-width: 100% !important;
    }
}
</style>


<!-- Hero Section -->
<div class="eval-hero">
    <div class="d-flex justify-content-between align-items-center position-relative z-1">
        <div>
            <h4 class="fw-bold mb-1 text-white">Verify Students</h4>
            <p class="mb-0 text-white-50" style="font-size: 0.9rem;">Review and approve self-registered student accounts securely.</p>
        </div>
        <div class="eval-hero-icon">
            <i class="bi bi-person-check-fill"></i>
        </div>
    </div>
</div>

<div class="eval-section">
    <!-- Header -->
    <div class="eval-section-header">
        <div class="d-flex align-items-center gap-3">
            <div class="eval-section-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-inbox-fill"></i>
            </div>
            <div>
                <h6>Pending Registrations</h6>
                <small>Review new signups</small>
            </div>
        </div>
        <!-- Search Box -->
        <div class="input-group" style="max-width: 250px;">
            <span class="input-group-text bg-transparent border-end-0 border-light-subtle" style="border-radius: 50rem 0 0 50rem;"><i class="bi bi-search text-muted" style="font-size: 0.8rem;"></i></span>
            <input type="text" class="form-control bg-transparent border-start-0 border-light-subtle table-search shadow-none" placeholder="Search pending..." data-target="pending-table" style="border-radius: 0 50rem 50rem 0; font-size: 0.85rem; color: var(--text-primary);">
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table eval-table m-0" id="pending-table">
            <thead>
                <tr>
                    <th>STUDENT INFO</th>
                    <th>ROLL NO</th>
                    <th>SHIFT</th>
                    <th>STATUS</th>
                    <th class="text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $s): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <?php $avatarFile = !empty($s['avatar']) ? $s['avatar'] : 'default_avatar.svg'; ?>
                            <img src="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid var(--card-bg);" alt="Avatar">
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 0.95rem;"><?php echo htmlspecialchars($s['name']); ?></div>
                                <div style="color: var(--text-secondary); font-size: 0.8rem;"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($s['email']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold" style="color: var(--primary-color); font-size: 0.9rem;"><?php echo htmlspecialchars($s['student_id']); ?></td>
                    <td>
                        <span class="group-code-badge"><i class="bi bi-clock-history me-1"></i><?php echo htmlspecialchars($s['shift']); ?></span>
                    </td>
                    <td>
                        <span class="badge status-badge-pending"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(4, 127, 176, 0.1); color: #047fb0; border: none; font-weight: 600;" onmouseover="this.style.background='rgba(4, 127, 176, 0.18)';" onmouseout="this.style.background='rgba(4, 127, 176, 0.1)';" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $s['user_id']; ?>">
                                <i class="bi bi-info-circle-fill" style="font-size: 0.85rem;"></i> <span class="d-none d-md-inline ms-2">Details</span>
                            </button>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/coordinator/users/approve?id=<?php echo $s['user_id']; ?>" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: none; font-weight: 600;" onmouseover="this.style.background='rgba(16, 185, 129, 0.18)';" onmouseout="this.style.background='rgba(16, 185, 129, 0.1)';" onclick="return confirm('Approve this student?')">
                                <i class="bi bi-check-circle-fill" style="font-size: 0.85rem;"></i> <span class="d-none d-md-inline ms-2">Approve</span>
                            </a>
                            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/coordinator/users/reject?id=<?php echo $s['user_id']; ?>" class="btn btn-sm rounded-pill d-flex align-items-center justify-content-center px-3 transition-all" style="background: rgba(168, 10, 52, 0.1); color: #a80a34; border: none; font-weight: 600;" onmouseover="this.style.background='rgba(168, 10, 52, 0.18)';" onmouseout="this.style.background='rgba(168, 10, 52, 0.1)';" onclick="return confirm('Reject and delete this registration?')">
                                <i class="bi bi-x-circle-fill" style="font-size: 0.85rem;"></i> <span class="d-none d-md-inline ms-2">Reject</span>
                            </a>
                        </div>
                    </td>
                </tr>

                 <!-- Details Modal -->
                <div class="modal fade" id="detailsModal<?php echo $s['user_id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0" style="border-radius: 1.5rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                            <!-- Header with Gradient & Avatar -->
                            <div class="modal-header border-0 pb-0 position-relative d-flex flex-column align-items-center" style="padding: 2.5rem 2rem 1.5rem; border-bottom: 1px solid var(--border-color) !important;">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                
                                <div class="position-relative d-inline-block mb-3">
                                    <div class="rounded-circle p-1" style="background: var(--card-bg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                        <img src="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" alt="Avatar">
                                    </div>
                                    <span class="position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background: #f59e0b; border: 3px solid var(--card-bg); transform: translate(-10%, -10%);" title="Pending Approval">
                                        <i class="bi bi-hourglass-split text-white" style="font-size: 0.75rem;"></i>
                                    </span>
                                </div>
                                
                                <h5 class="fw-bold mb-1 text-primary text-center" style="font-size: 1.35rem; letter-spacing: -0.02em;"><?php echo htmlspecialchars($s['name']); ?></h5>
                                <div class="badge rounded-pill text-primary mb-2" style="background: rgba(59, 130, 246, 0.1); font-size: 0.85rem; padding: 0.4rem 0.8rem; font-weight: 600;">
                                    <?php echo htmlspecialchars($s['student_id']); ?>
                                </div>
                            </div>

                            <!-- Body with Info Cards -->
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <!-- Contact Info Card -->
                                    <div class="col-12">
                                        <div class="p-3 rounded-4" style="background: var(--form-bg); border: 1px solid var(--border-color);">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="text-primary shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; background: var(--card-bg);">
                                                    <i class="bi bi-envelope-fill" style="font-size: 1rem;"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Email Address</div>
                                                    <div class="fw-semibold" style="font-size: 0.95rem; color: var(--text-primary);"><?php echo htmlspecialchars($s['email']); ?></div>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center">
                                                <div class="text-primary shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; background: var(--card-bg);">
                                                    <i class="bi bi-telephone-fill" style="font-size: 1rem;"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Contact Phone</div>
                                                    <div class="fw-semibold" style="font-size: 0.95rem; color: var(--text-primary);"><?php echo htmlspecialchars($s['phone'] ?? 'N/A'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Department Card -->
                                    <div class="col-6">
                                        <div class="p-3 rounded-4 h-100 text-center" style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.1);">
                                            <i class="bi bi-building text-primary mb-2 d-block" style="font-size: 1.25rem;"></i>
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Department</div>
                                            <div class="fw-bold" style="font-size: 0.95rem; color: var(--text-primary);"><?php echo htmlspecialchars($s['department']); ?></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Shift Card -->
                                    <div class="col-6">
                                        <div class="p-3 rounded-4 h-100 text-center" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.1);">
                                            <i class="bi bi-brightness-high-fill text-warning mb-2 d-block" style="font-size: 1.25rem;"></i>
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Shift</div>
                                            <div class="fw-bold" style="font-size: 0.95rem; color: var(--text-primary);"><?php echo htmlspecialchars($s['shift']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="modal-footer border-0 p-4 pt-0">
                                <div class="d-flex w-100 gap-2">
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/coordinator/users/approve?id=<?php echo $s['user_id']; ?>" class="btn flex-grow-1 rounded-pill fw-bold text-white transition-all shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #10b981, #059669); font-size: 0.9rem; padding: 0.5rem 0;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';" onclick="return confirm('Approve this student?')">
                                        <i class="bi bi-check-circle-fill me-2 fs-6"></i> Approve
                                    </a>
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/coordinator/users/reject?id=<?php echo $s['user_id']; ?>" class="btn flex-grow-1 rounded-pill fw-bold transition-all d-flex align-items-center justify-content-center" style="background: rgba(168, 10, 52, 0.1); color: #a80a34; font-size: 0.9rem; padding: 0.5rem 0;" onmouseover="this.style.background='rgba(168, 10, 52, 0.18)';" onmouseout="this.style.background='rgba(168, 10, 52, 0.1)';" onclick="return confirm('Reject and delete this registration?')">
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
                        <td colspan="5" class="text-center py-5">
                            <div style="color: var(--text-secondary); font-size: 3rem; opacity: 0.3; margin-bottom: 15px;"><i class="bi bi-inbox"></i></div>
                            <h5 class="fw-bold" style="color: var(--text-primary);">All Caught Up!</h5>
                            <p class="mb-0" style="color: var(--text-secondary);">There are no pending student registrations to review.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
