<?php
$title = "Official Notices & Bulletins - Admin Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$notices = $notices ?? [];
$departments = $departments ?? [];

$totalNotices = count($notices);
$publicNoticesCount = 0;
$deptSpecificCount = 0;
foreach ($notices as $n) {
    if (!empty($n['is_public'])) $publicNoticesCount++;
    if (!empty($n['department']) && $n['department'] !== 'All') $deptSpecificCount++;
}
?>

<style>
.notice-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, rgba(0,0,0,0.06));
    border-radius: 16px;
    box-shadow: var(--card-shadow, 0 4px 15px rgba(0,0,0,0.03));
}
.form-control-custom {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #cbd5e1);
    color: var(--text-primary, #0f172a);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.9rem;
}
.form-control-custom:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    outline: none;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Official Notices &amp; Bulletins</h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Publish university-wide announcements, deadlines, and circulars across all departments and roles
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <a href="<?php echo $basePath; ?>/notice-board" target="_blank" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-box-arrow-up-right text-primary"></i> <span>Public Notice Board</span>
            </a>
            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createNoticeModal">
                <i class="bi bi-plus-lg"></i> <span>Broadcast Notice</span>
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════ KPI Summary Cards ═══════════════ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                    <i class="bi bi-broadcast fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Total Notices</div>
                    <h5 class="fw-bold m-0 text-dark"><?php echo (int)$totalNotices; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="bi bi-globe2 fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Public Notices</div>
                    <h5 class="fw-bold m-0 text-success"><?php echo (int)$publicNoticesCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <i class="bi bi-building fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">Dept. Specific</div>
                    <h5 class="fw-bold m-0 text-primary"><?php echo (int)$deptSpecificCount; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 14px; background: var(--card-bg);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2.5 rounded-3" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium" style="font-size: 0.75rem;">University Target</div>
                    <h5 class="fw-bold m-0 text-warning">All Portals</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Notices Table Card ═══════════════ -->
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-header border-bottom p-3 p-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--form-bg);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-text text-primary"></i>
            <h6 class="fw-bold m-0 text-dark">Published Notices Roster</h6>
        </div>
        <div class="position-relative" style="width: 240px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="font-size: 0.8rem;"></i>
            <input type="text" id="noticeSearch" class="form-control form-control-custom ps-5 py-1" style="font-size: 0.82rem;" placeholder="Search notices...">
        </div>
    </div>

    <div class="card-body p-0">
        <?php if (empty($notices)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-3 text-secondary opacity-50"></i>
                <h6 class="fw-bold text-dark">No Notices Published</h6>
                <p class="small text-secondary mb-0">Click "Broadcast Notice" above to publish an official bulletin.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="noticesTable">
                    <thead>
                        <tr style="background: var(--form-bg);">
                            <th class="ps-4" style="font-size: 0.75rem; text-transform: uppercase;">Date / Ref</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Subject</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Department</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Audience</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Author</th>
                            <th style="font-size: 0.75rem; text-transform: uppercase;">Public Status</th>
                            <th class="text-end pe-4" style="font-size: 0.75rem; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notices as $n): 
                            $nDate = !empty($n['notice_date']) ? date('M d, Y', strtotime($n['notice_date'])) : '—';
                            $isPub = !empty($n['is_public']);
                        ?>
                            <tr class="notice-row">
                                <td class="ps-4 text-nowrap">
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?php echo $nDate; ?></div>
                                    <?php if (!empty($n['ref_no'])): ?>
                                        <div class="small font-monospace text-muted" style="font-size: 0.75rem;">Ref: <?php echo htmlspecialchars($n['ref_no'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 300px;">
                                    <div class="fw-bold text-dark text-truncate" title="<?php echo htmlspecialchars($n['subject'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($n['subject'] ?? 'Untitled Notice', ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="small text-secondary text-truncate" style="font-size: 0.75rem; max-width: 280px;">
                                        <?php echo htmlspecialchars(strip_tags($n['body'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($n['department'] ?? 'All', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-primary-subtle text-primary px-2.5 py-1" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($n['target_audience'] ?? 'All', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark"><?php echo htmlspecialchars($n['sender_name'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></div>
                                </td>
                                <td>
                                    <a href="<?php echo $basePath; ?>/admin/notice/toggle?id=<?php echo (int)$n['id']; ?>" class="badge rounded-pill px-2.5 py-1 text-decoration-none fw-bold <?php echo $isPub ? 'bg-success-subtle text-success border border-success' : 'bg-secondary-subtle text-secondary'; ?>" title="Click to toggle public board visibility">
                                        <?php echo $isPub ? '<i class="bi bi-globe me-1"></i> Public' : '<i class="bi bi-lock me-1"></i> Internal'; ?>
                                    </a>
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 fw-semibold me-1" style="font-size: 0.78rem;" 
                                            data-bs-toggle="modal" data-bs-target="#viewNoticeModal"
                                            data-subject="<?php echo htmlspecialchars($n['subject'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-ref="<?php echo htmlspecialchars($n['ref_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-date="<?php echo $nDate; ?>"
                                            data-dept="<?php echo htmlspecialchars($n['department'] ?? 'All', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-audience="<?php echo htmlspecialchars($n['target_audience'] ?? 'All', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-body="<?php echo htmlspecialchars($n['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <a href="<?php echo $basePath; ?>/admin/notice/delete?id=<?php echo (int)$n['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.78rem;" onclick="return confirm('Are you sure you want to permanently delete this notice?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════════ Create Notice Modal ═══════════════ -->
<div class="modal fade" id="createNoticeModal" tabindex="-1" aria-labelledby="createNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 18px;">
            <form action="<?php echo $basePath; ?>/admin/notice/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="modal-header border-bottom p-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-circle bg-primary-subtle text-primary">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <h6 class="modal-title fw-bold text-dark" id="createNoticeModalLabel">Broadcast Official Notice</h6>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Reference / Dispatch No.</label>
                            <input type="text" name="ref_no" class="form-control form-control-custom" placeholder="e.g. FYP/NOT/2026/01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Notice Date <span class="text-danger">*</span></label>
                            <input type="date" name="notice_date" class="form-control form-control-custom" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Target Department <span class="text-danger">*</span></label>
                            <select name="department" class="form-select form-control-custom" required>
                                <option value="All" selected>All Departments (University-wide)</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($dept, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Target Audience <span class="text-danger">*</span></label>
                            <select name="target_audience" class="form-select form-control-custom" required>
                                <option value="All" selected>All Portals (Students, Supervisors, Committees, HODs)</option>
                                <option value="Students">Students Only</option>
                                <option value="Supervisors">Supervisors Only</option>
                                <option value="Committees">Evaluation Committees Only</option>
                                <option value="Coordinators">Coordinators Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Subject / Title <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control form-control-custom" placeholder="e.g. Submission Deadline for Final Presentation Slides" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Notice Content / Instructions <span class="text-danger">*</span></label>
                        <textarea name="body" class="form-control form-control-custom" rows="6" placeholder="Provide complete guidelines, deadlines, requirements, and evaluation instructions..." required></textarea>
                    </div>

                    <div class="form-check form-switch p-0 ps-5 mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_public" name="is_public" value="1" checked>
                        <label class="form-check-label fw-semibold text-dark small" for="is_public">
                            Publish to Public Notice Board (/notice-board)
                        </label>
                    </div>
                </div>

                <div class="modal-footer border-top p-3 px-4">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-send-fill me-1"></i> Broadcast Notice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════ View Notice Modal ═══════════════ -->
<div class="modal fade" id="viewNoticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 18px;">
            <div class="modal-header border-bottom p-3 px-4">
                <div>
                    <span class="badge rounded-pill bg-primary-subtle text-primary mb-1" id="vAudience"></span>
                    <span class="badge rounded-pill bg-light text-dark border mb-1" id="vDept"></span>
                    <h6 class="modal-title fw-bold text-dark m-0" id="vSubject"></h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 text-secondary small pb-2 border-bottom">
                    <div id="vRef"></div>
                    <div id="vDate"></div>
                </div>
                <div id="vBody" style="line-height: 1.7; white-space: pre-wrap; font-size: 0.92rem; color: #1e293b;"></div>
            </div>
            <div class="modal-footer border-top p-3 px-4">
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side quick search
document.getElementById('noticeSearch')?.addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#noticesTable tbody tr.notice-row').forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
    });
});

// View Notice Modal population
const viewModal = document.getElementById('viewNoticeModal');
if (viewModal) {
    viewModal.addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('vSubject').textContent = btn.getAttribute('data-subject') || '';
        document.getElementById('vRef').textContent = btn.getAttribute('data-ref') ? 'Ref: ' + btn.getAttribute('data-ref') : '';
        document.getElementById('vDate').textContent = btn.getAttribute('data-date') || '';
        document.getElementById('vDept').textContent = btn.getAttribute('data-dept') || '';
        document.getElementById('vAudience').textContent = btn.getAttribute('data-audience') || '';
        document.getElementById('vBody').textContent = btn.getAttribute('data-body') || '';
    });
}
</script>
