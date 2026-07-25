<style>
#is_public:checked {
                                background-color: #10b981 !important;
                                border-color: #10b981 !important;
                                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
                            }
                            #is_public {
                                background-color: var(--pf-bg-alt);
                                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
                            }
</style>
<style>
/* ─── Section Panel ─── */








/* ─── Modern Table Styles ─── */






/* ─── Forms & Buttons ─── */
.pf-group .form-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.pf-group .form-control {
    padding: 12px 16px;
    font-size: 0.85rem;
    border-radius: 12px;
    border: 1.5px solid var(--border-color);
    background: var(--form-bg);
    transition: all 0.2s ease;
}
.pf-group .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
}

.audience-chip-checkbox {
    display: none;
}
.audience-chip-label {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1.5px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
    margin-right: 6px;
    margin-bottom: 8px;
    display: inline-block;
}
.audience-chip-label:hover {
    border-color: #93c5fd;
    background: rgba(16,185,129,0.05);
}
.audience-chip-checkbox:checked + .audience-chip-label {
    background: rgba(16,185,129,0.1);
    color: #10b981;
    border-color: #10b981;
}

.action-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    transition: all 0.2s ease;
    text-decoration: none;
}
.action-btn:hover {
    background: rgba(16,185,129,0.1);
    color: #10b981;
    border-color: rgba(16,185,129,0.2);
}
.action-btn.delete:hover {
    background: rgba(239,68,68,0.1);
    color: #ef4444;
    border-color: rgba(239,68,68,0.2);
}

@media (max-width: 768px) {
    
    
    
    
    .modern-table .subject-col { min-width: 200px; }
    .modern-table .date-col { min-width: 130px; }
    .modern-table .target-col { min-width: 180px; }
}
</style>
<!-- Coordinator Notice Generator View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>



<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Icon -->
        <div class="page-hero-icon" style="background: transparent">
                <i class="bi bi-diagram-3-fill"></i>
            </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                Department Notifications
            </p>
            <h4 class="text-white fw-bold" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                Notice Generator
            </h4>
        </div>

        <!-- Stats -->
        <div class="d-none d-lg-flex gap-3">
            <div class="page-stat-pill">
                <span class="stat-num text-info"><?php echo count($notices); ?></span>
                <span class="stat-label">Total Broadcasts</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ═══════════════ Generate Form ═══════════════ -->
    <div class="col-lg-4">
        <div class="page-section h-100 mb-0">
            <div class="page-section-header">
                <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h6>Create Notice</h6>
                    <small>Draft & broadcast official letters</small>
                </div>
            </div>
            <div class="page-section-body">
                <form action="<?php echo $basePath; ?>/coordinator/notice/create" method="POST">
                    
                    <div class="pf-group mb-3">
                        <label for="ref_no" class="form-label">Reference No. (Optional)</label>
                        <input type="text" class="form-control" id="ref_no" name="ref_no" placeholder="e.g. SU/FET/CS/2026/089">
                    </div>

                    <div class="pf-group mb-3">
                        <label for="notice_date" class="form-label">Notice Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="notice_date" name="notice_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="pf-group mb-4">
                        <label class="form-label d-block">Target Audience <span class="text-danger">*</span></label>
                        <div>
                            <input class="audience-chip-checkbox" type="checkbox" name="target_audiences[]" id="audience_students" value="students" checked>
                            <label class="audience-chip-label" for="audience_students"><i class="bi bi-mortarboard-fill me-1"></i> Students</label>
                            
                            <input class="audience-chip-checkbox" type="checkbox" name="target_audiences[]" id="audience_supervisors" value="supervisors" checked>
                            <label class="audience-chip-label" for="audience_supervisors"><i class="bi bi-person-workspace me-1"></i> Supervisors</label>
                            
                            <input class="audience-chip-checkbox" type="checkbox" name="target_audiences[]" id="audience_committee" value="committee" checked>
                            <label class="audience-chip-label" for="audience_committee"><i class="bi bi-people-fill me-1"></i> Committee</label>
                            
                            <input class="audience-chip-checkbox" type="checkbox" name="target_audiences[]" id="audience_hod" value="hod" checked>
                            <label class="audience-chip-label" for="audience_hod"><i class="bi bi-diagram-3-fill me-1"></i> HOD</label>
                        </div>
                    </div>
                    
                    <div class="pf-group mb-4">
                        <label class="form-label d-block">Public Visibility</label>
                        
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_public" name="is_public" value="1" checked style="cursor: pointer;border: 1px solid #ced4da;box-shadow: none">
                            <label class="form-check-label ms-2" for="is_public" style="cursor: pointer">Display on Public Home Page</label>
                        </div>
                    </div>

                    <div class="pf-group mb-3">
                        <label for="subject" class="form-label">Subject / Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g. FYP-I Initial Proposal Submission Extended" required>
                    </div>

                    <div class="pf-group mb-4">
                        <label for="body" class="form-label">Notice Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="body" name="body" rows="6" placeholder="Write notice details, instructions, or rules here..." required style="resize: vertical"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 12px;font-size: 0.9rem">
                        <i class="bi bi-broadcast me-2"></i> Broadcast Notice
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ═══════════════ Notice History ═══════════════ -->
    <div class="col-lg-8">
        <div class="page-section h-100 mb-0">
            <div class="page-section-header">
                <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h6>Broadcast History</h6>
                    <small>Previously sent official notices</small>
                </div>
            </div>
            <div class="table-responsive" style="max-height: calc(100% - 73px);overflow-y: auto">
                <table class="table modern-table m-0">
                    <thead style="position: sticky;top: 0;z-index: 5">
                        <tr>
                            <th class="ps-4">Ref No.</th>
                            <th class="subject-col">Subject</th>
                            <th class="date-col">Date</th>
                            <th class="target-col">Target</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($notices as $n): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="font-monospace fw-bold text-secondary" style="font-size: 0.85rem">
                                    <?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;font-size: 0.9rem" title="<?php echo htmlspecialchars($n['subject']); ?>">
                                    <?php echo htmlspecialchars($n['subject']); ?>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted fw-medium" style="font-size: 0.85rem">
                                    <?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php 
                                    $audiences = explode(',', $n['target_audience']);
                                    foreach ($audiences as $aud): 
                                        $aud = trim($aud);
                                        if (empty($aud)) continue;
                                    ?>
                                        <span style="background: rgba(139,92,246,0.1);color: #8b5cf6;font-size: 0.6rem;font-weight: 700;padding: 4px 8px;border-radius: 6px;text-transform: uppercase">
                                            <?php echo htmlspecialchars($aud); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?php echo $basePath; ?>/notice/view?id=<?php echo $n['id']; ?>" target="_blank" class="action-btn" title="View Letter">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="<?php echo $basePath; ?>/coordinator/notice/delete?id=<?php echo $n['id']; ?>" class="action-btn delete" title="Delete Notice" onclick="return confirm('Are you sure you want to delete this notice? This will also remove the notification for users.')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($notices)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;opacity: 0.3"></i>
                                    No notices broadcasted yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
