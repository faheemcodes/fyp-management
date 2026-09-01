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
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: var(--text-secondary);
    font-size: 0.8rem;
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

.modern-table th {
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    color: var(--text-secondary) !important;
    padding: 10px 14px !important;
}
.modern-table td {
    padding: 10px 14px !important;
    vertical-align: middle;
    font-size: 0.84rem;
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
        <div class="page-hero-icon">
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
                            <th class="ps-3" style="width: 80px;">Ref No.</th>
                            <th>Subject</th>
                            <th class="text-nowrap" style="width: 105px;">Date</th>
                            <th class="text-nowrap" style="width: 130px;">Target</th>
                            <th class="text-end pe-3" style="width: 110px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($notices as $n): 
                            $rawAudiences = array_filter(array_map('trim', explode(',', $n['target_audience'] ?? '')));
                            $isAll = count($rawAudiences) >= 4 || strtolower($n['target_audience'] ?? '') === 'all';
                        ?>
                        <tr>
                            <td class="ps-3">
                                <span class="font-monospace text-muted" style="font-size: 0.78rem;">
                                    <?php echo htmlspecialchars($n['ref_no'] ?? '-'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-truncate" style="max-width: 210px; font-size: 0.86rem; color: var(--text-primary);" title="<?php echo htmlspecialchars($n['subject']); ?>">
                                    <?php echo htmlspecialchars($n['subject']); ?>
                                </div>
                            </td>
                            <td class="text-nowrap text-muted" style="font-size: 0.82rem;">
                                <?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                            </td>
                            <td>
                                <?php if ($isAll): ?>
                                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 0.72rem; font-weight: 700;">
                                        <i class="bi bi-people-fill me-1"></i>All Roles
                                    </span>
                                <?php else: ?>
                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                        <?php foreach ($rawAudiences as $aud): ?>
                                            <span class="badge rounded-pill px-2 py-0.5 text-nowrap" style="background: rgba(139,92,246,0.1); color: #7c3aed; font-size: 0.68rem; font-weight: 700; text-transform: uppercase;">
                                                <?php echo htmlspecialchars($aud); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1.5">
                                    <button type="button" class="action-btn" title="View Notice" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </button>
                                    <a href="<?php echo $basePath; ?>/coordinator/notice/toggle?id=<?php echo $n['id']; ?>" class="action-btn <?php echo !empty($n['is_hidden']) ? 'text-muted' : 'text-primary'; ?>" title="<?php echo !empty($n['is_hidden']) ? 'Hidden from users - Click to Show' : 'Visible to users - Click to Hide'; ?>">
                                        <i class="bi <?php echo !empty($n['is_hidden']) ? 'bi-eye-slash-fill' : 'bi-eye-fill'; ?>"></i>
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

<!-- Notice Modals -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
<style>
@media (max-width: 768px) {
    .notice-modal-dialog { margin: 0.5rem; }
    .letterhead-container { padding: 30px 18px !important; min-height: auto !important; }
    .header-logo-section { gap: 8px !important; margin-bottom: 18px !important; padding-bottom: 10px !important; }
    .header-logo-section img { width: 48px !important; height: 48px !important; }
    .uni-title { font-size: 0.98rem !important; }
    .fac-title { font-size: 0.72rem !important; }
    .dept-title { font-size: 0.68rem !important; }
    .meta-section { font-size: 0.72rem !important; margin-bottom: 18px !important; padding-bottom: 6px !important; }
    .subject-line { font-size: 0.82rem !important; margin-bottom: 15px !important; padding-left: 6px !important; }
    .body-content { font-size: 0.78rem !important; line-height: 1.55 !important; margin-bottom: 30px !important; }
    .watermark { width: 200px !important; height: 200px !important; }
    .signatures-section { flex-direction: row !important; flex-wrap: nowrap !important; justify-content: space-between !important; padding-top: 30px !important; }
    .signature-line { width: 100% !important; max-width: 130px !important; font-size: 0.68rem !important; }
    .signature-line .small { font-size: 0.65rem !important; }
    .signature-line .x-small { font-size: 0.58rem !important; }
    .signature-cursive { font-size: 1.15rem !important; top: -22px !important; left: 5px !important; }
    .sign-title { font-size: 0.58rem !important; }
}
</style>
<?php 
$noticesForModal = isset($recentNotices) ? $recentNotices : (isset($notices) ? $notices : []);
$db = \Database::getInstance()->getConnection();
foreach($noticesForModal as $n): 
    $sender_id = $n['sender_id'];
    $stmtC = $db->prepare("SELECT name, department FROM coordinators WHERE user_id = ?");
    $stmtC->execute([$sender_id]);
    $coordUser = $stmtC->fetch();
    $coordName = $coordUser ? $coordUser['name'] : 'Coordinator';
    $coordDept = $coordUser ? $coordUser['department'] : 'Department';

    $stmtH = $db->prepare("SELECT name FROM hods WHERE department = ?");
    $stmtH->execute([$coordDept]);
    $hodUser = $stmtH->fetch();
    $hodName = $hodUser ? $hodUser['name'] : 'Head of Department';
    
    $basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<div class="modal fade" id="noticeModal<?php echo $n['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl notice-modal-dialog">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-body p-0 d-flex justify-content-center position-relative">
                
                <button type="button" class="btn-close shadow-sm position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10; background-color: rgba(255,255,255,0.9); border-radius: 50%; padding: 0.8rem;"></button>

                <div class="letterhead-container w-100" style="background: #fdfcfb; max-width: 820px; padding: 60px 70px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border-radius: 8px; position: relative; min-height: 1060px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; font-family: 'Lora', Georgia, serif; color: #1e293b; text-align: left;">
                    
                    <!-- Watermark -->
                    <div class="watermark" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); width: 380px; height: 380px; opacity: 0.035; pointer-events: none; z-index: 0;">
                        <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Watermark" style="width: 100%;height: 100%;object-fit: contain;filter: grayscale(100%)">
                    </div>

                    <div class="letterhead-content" style="position: relative; z-index: 1;">
                        <div class="header-logo-section" style="border-bottom: 3px double #1e293b; padding-bottom: 20px; margin-bottom: 35px; display: flex; align-items: center; justify-content: center; gap: 20px;">
                            <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Logo" width="80" height="80" style="object-fit: contain">
                            <div class="header-text" style="text-align: left;">
                                <h3 class="uni-title m-0" style="font-family: 'Cinzel', serif; font-size: 1.6rem; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase; color: #0f172a; line-height: 1.2;">University of Sindh</h3>
                                <h5 class="fac-title m-0" style="font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: #334155; margin-top: 3px;">Faculty of Engineering & Technology</h5>
                                <h6 class="dept-title m-0" style="font-family: 'Lora', Georgia, serif; font-size: 1.05rem; font-weight: 600; color: #475569; margin-top: 3px;">Department of <?php echo htmlspecialchars($coordDept); ?></h6>
                                <small class="text-muted" style="font-size: 0.78rem;display: block;margin-top: 3px;font-family: sans-serif;letter-spacing: 0.3px">Jamshoro, Sindh, Pakistan</small>
                            </div>
                        </div>

                        <div class="meta-section d-flex justify-content-between align-items-center" style="font-size: 0.95rem; margin-bottom: 40px; color: #334155; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px;">
                            <div>
                                <span class="fw-bold">Ref No:</span> <span style="font-family: monospace; font-size: 1.05rem;"><?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?></span>
                            </div>
                            <div>
                                <span class="fw-bold">Date:</span> <?php echo date('F d, Y', strtotime($n['notice_date'])); ?>
                            </div>
                        </div>

                        <div class="subject-line" style="font-size: 1rem; font-weight: bold; margin-bottom: 20px; color: #0f172a; border-left: 3px solid #1e3a8a; padding-left: 12px;">
                            SUBJECT: <?php echo htmlspecialchars($n['subject']); ?>
                        </div>

                        <div class="body-content" style="font-size: 0.95rem; line-height: 1.8; text-align: justify; white-space: pre-wrap; margin-bottom: 60px; color: #1e293b;">
                            <?php echo htmlspecialchars($n['body']); ?>
                        </div>
                    </div>

                    <div class="signatures-section d-flex justify-content-between align-items-end" style="position: relative; z-index: 1; margin-top: auto; padding-top: 50px;">
                        
                        <div class="signature-box" style="position: relative; display: inline-block; text-align: left;">
                            <div class="signature-cursive" style="font-family: 'Great Vibes', cursive; font-size: 2.1rem; color: #047857; position: absolute; top: -38px; left: 20px; transform: rotate(-3deg); opacity: 0.9; pointer-events: none; letter-spacing: 1px; text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);">
                                <?php echo htmlspecialchars($coordName); ?>
                            </div>
                            <div class="signature-line" style="border-top: 1.5px solid #0f172a; width: 230px; padding-top: 8px; font-size: 0.9rem; font-weight: bold; color: #0f172a;">
                                <div class="small mb-1"><?php echo htmlspecialchars($coordName); ?></div>
                                <div class="sign-title" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: #475569;">FYP Coordinator</div>
                                <div class="text-muted x-small" style="font-size: 0.75rem; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                            </div>
                        </div>

                        <div class="signature-box" style="position: relative; display: inline-block; text-align: left;">
                            <div class="signature-cursive" style="font-family: 'Great Vibes', cursive; font-size: 2.1rem; color: #047857; position: absolute; top: -38px; left: 20px; transform: rotate(-3deg); opacity: 0.9; pointer-events: none; letter-spacing: 1px; text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);">
                                <?php echo htmlspecialchars($hodName); ?>
                            </div>
                            <div class="signature-line" style="border-top: 1.5px solid #0f172a; width: 230px; padding-top: 8px; font-size: 0.9rem; font-weight: bold; color: #0f172a;">
                                <div class="small mb-1"><?php echo htmlspecialchars($hodName); ?></div>
                                <div class="sign-title" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: #475569;">Chairperson</div>
                                <div class="text-muted x-small" style="font-size: 0.75rem; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });
</script>

