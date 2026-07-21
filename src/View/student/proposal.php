<!-- Student Proposal Submission View -->
<?php
$titleVal       = $project['title'] ?? '';
$abstractVal    = $proposal['abstract'] ?? $project['description'] ?? '';
$supervisorIdVal = $project['supervisor_id'] ?? '';
$member1Val     = isset($groupMembers[0]) ? $groupMembers[0]['student_id'] : '';
$member2Val     = isset($groupMembers[1]) ? $groupMembers[1]['student_id'] : '';
$basePath       = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);

$statusMap = [
    'Draft'    => ['rgba(107,114,128,0.1)', '#6b7280',  'bi-pencil-fill'],
    'Pending'  => ['rgba(245,158,11,0.1)',  '#d97706',  'bi-hourglass-split'],
    'Approved' => ['rgba(16,185,129,0.1)',  '#059669',  'bi-patch-check-fill'],
    'Rejected' => ['rgba(239,68,68,0.1)',   '#dc2626',  'bi-x-circle-fill'],
];
$st  = $proposal['status'] ?? 'Draft';
$sc  = $statusMap[$st] ?? $statusMap['Draft'];

$supName = 'Unassigned';
foreach (($supervisors ?? []) as $s) {
    if ($s['user_id'] == $supervisorIdVal) { $supName = $s['name']; break; }
}
?>

<style>
/* ─── Proposal Page Scoped Styles ─── */
.prop-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.prop-hero::before {
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
.prop-hero::after {
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
.prop-hero-icon {
    width: 56px;
    height: 56px;
    background: conic-gradient(from 0deg, #60a5fa, #3b82f6, #1d4ed8, #60a5fa);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}
.prop-hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

/* ─── Section Panel ─── */
.prop-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.prop-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
}
.prop-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}
.prop-section-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.prop-section-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
}
.prop-section-header small {
    font-size: 0.72rem;
    color: var(--text-secondary);
    margin: 0;
}
.prop-section-body {
    padding: 24px;
}

/* ─── Step Cards ─── */
.prop-step {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1.5px solid var(--border-color);
    background: var(--form-bg);
    transition: border-color 0.2s ease;
}
.prop-step.done {
    border-color: rgba(5,150,105,0.3);
    background: rgba(5,150,105,0.04);
}
.prop-step.active {
    border-color: rgba(59,130,246,0.3);
    background: rgba(59,130,246,0.04);
}
.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.78rem;
    flex-shrink: 0;
    background: var(--border-color);
    color: var(--text-secondary);
}
.prop-step.done .step-circle {
    background: #059669;
    color: #fff;
}
.prop-step.active .step-circle {
    background: #3b82f6;
    color: #fff;
}

/* ─── Member Input Slots ─── */
.prop-member-input {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 16px;
    transition: border-color 0.2s ease;
}
.prop-member-input:focus-within {
    border-color: var(--primary-color);
}
.prop-member-input label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.prop-member-input label .num {
    width: 18px;
    height: 18px;
    border-radius: 6px;
    background: rgba(139,92,246,0.1);
    color: #8b5cf6;
    font-size: 0.6rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.prop-member-input input {
    border: none;
    background: transparent;
    padding: 0;
    font-size: 0.85rem;
    color: var(--text-primary);
    width: 100%;
    outline: none;
}
.prop-member-input input::placeholder {
    color: #9ca3af;
    font-size: 0.82rem;
}

/* ─── Save Footer ─── */
.prop-save-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border-color);
    background: var(--form-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.prop-save-footer .btn {
    padding: 10px 32px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 10px;
}

/* ─── Feedback Bubble ─── */
.prop-feedback-bubble {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    padding: 16px;
}

/* ─── Form Group ─── */
.prop-field label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    margin-bottom: 6px;
}

/* ─── Approved Banner ─── */
.prop-approved-card {
    text-align: center;
    padding: 40px 24px;
}
.prop-approved-icon {
    width: 72px;
    height: 72px;
    background: rgba(5,150,105,0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 1.8rem;
    color: #059669;
}

@media (max-width: 768px) {
    .prop-hero { padding: 24px 16px; }
    .prop-section-body { padding: 16px; }
}
</style>

<?php if ($group && !$isLeader): ?>
<!-- ─── GROUP MEMBER READ-ONLY VIEW ─── -->

    <!-- Hero Banner -->
    <div class="prop-hero">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="prop-hero-icon">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div class="flex-grow-1 text-center text-md-start">
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Your Group Project
                </p>
                <h4 class="text-white fw-bold mb-2" style="font-size: 1.25rem; letter-spacing: -0.02em; line-height: 1.35;">
                    <?php echo htmlspecialchars($project['title'] ?? 'FYP Project'); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <span class="prop-hero-chip" style="background: <?php echo htmlspecialchars((string)($sc[0]), ENT_QUOTES, 'UTF-8'); ?>; color: <?php echo htmlspecialchars((string)($sc[1]), ENT_QUOTES, 'UTF-8'); ?>;">
                        <i class="bi <?php echo htmlspecialchars((string)($sc[2]), ENT_QUOTES, 'UTF-8'); ?>"></i> <?php echo htmlspecialchars((string)($st), ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="prop-hero-chip" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); font-family: monospace;">
                        <?php echo htmlspecialchars($group['group_code'] ?? 'Pending'); ?>
                    </span>
                </div>
            </div>
            <div class="d-none d-md-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(255,255,255,0.06);">
                <i class="bi bi-person-badge-fill" style="font-size: 1.4rem; color: #34d399;"></i>
                <div>
                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.04em;">Supervisor</div>
                    <div class="text-white fw-semibold" style="font-size: 0.88rem;"><?php echo htmlspecialchars($supName); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-4" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
        <div style="width: 32px; height: 32px; background: rgba(59,130,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #3b82f6; font-size: 0.9rem;">
            <i class="bi bi-info-circle-fill"></i>
        </div>
        <span style="font-size: 0.85rem; color: #2563eb; line-height: 1.5;">You are a group member. Only the <strong>group leader</strong> can edit the proposal, change the supervisor, or update team members.</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <!-- Abstract -->
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-file-text-fill"></i>
                    </div>
                    <div>
                        <h6>Project Abstract</h6>
                        <small>Research summary and objectives</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <p class="text-muted mb-0" style="font-size: 0.875rem; line-height: 1.8; text-align: justify;">
                        <?php echo $abstractVal ? nl2br(htmlspecialchars($abstractVal)) : '<em>No abstract added yet.</em>'; ?>
                    </p>
                </div>
            </div>

            <!-- Members + Details -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="prop-section">
                        <div class="prop-section-header">
                            <div class="prop-section-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div><h6>Group Members</h6></div>
                        </div>
                        <div class="prop-section-body">
                            <div class="d-flex flex-column gap-3">
                                <!-- Leader -->
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 38px; height: 38px; background: linear-gradient(135deg, #60a5fa, #3b82f6); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.85rem; flex-shrink: 0;">L</div>
                                    <div>
                                        <div class="fw-semibold" style="font-size: 0.875rem;"><?php echo htmlspecialchars($group['creator_name'] ?? 'Group Leader'); ?> <span style="font-size: 0.6rem; background: rgba(59,130,246,0.12); color: #3b82f6; padding: 2px 7px; border-radius: 8px; font-weight: 700;">Leader</span></div>
                                        <div class="font-monospace text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($group['creator_student_id'] ?? '—'); ?></div>
                                    </div>
                                </div>
                                <?php foreach ($groupMembers as $m): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 38px; height: 38px; background: var(--table-header-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--text-secondary); font-size: 0.85rem; flex-shrink: 0; border: 1.5px solid var(--border-color);">M</div>
                                    <div>
                                        <div class="fw-semibold" style="font-size: 0.875rem;"><?php echo htmlspecialchars($m['name']); ?></div>
                                        <div class="font-monospace text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($m['student_id']); ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="prop-section">
                        <div class="prop-section-header">
                            <div class="prop-section-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <div><h6>Details</h6></div>
                        </div>
                        <div class="prop-section-body">
                            <div class="mb-3">
                                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 4px;">Supervisor</div>
                                <div class="fw-semibold" style="font-size: 0.875rem;"><?php echo htmlspecialchars($supName); ?></div>
                            </div>
                            <div class="mb-3">
                                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 4px;">Group Code</div>
                                <div class="fw-bold font-monospace" style="color: #3b82f6;"><?php echo htmlspecialchars($group['group_code'] ?? 'Pending'); ?></div>
                            </div>
                            <?php if ($proposal && $proposal['file_path']): ?>
                            <div>
                                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 6px;">Proposal File</div>
                                <a href="<?php echo $basePath . htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <!-- Feedback -->
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <div>
                        <h6>Supervisor Feedback</h6>
                        <small>Review comments from your supervisor</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <?php if ($proposal && $proposal['feedback']): ?>
                        <div class="prop-feedback-bubble">
                            <p class="mb-2" style="font-size: 0.82rem; line-height: 1.65; color: var(--text-primary);"><?php echo nl2br(htmlspecialchars($proposal['feedback'])); ?></p>
                            <div class="text-end"><small class="text-muted" style="font-size: 0.7rem;">Updated: <?php echo date('M d, Y', strtotime($proposal['updated_at'])); ?></small></div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-left-dots text-muted" style="font-size: 2.2rem; opacity: 0.2;"></i>
                            <p class="text-muted mt-3 mb-0" style="font-size: 0.82rem;">No feedback from supervisor yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
<!-- ─── LEADER SUBMISSION FORM VIEW ─── -->

    <!-- Hero Banner -->
    <div class="prop-hero">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="prop-hero-icon">
                <i class="bi bi-file-earmark-plus-fill"></i>
            </div>
            <div class="flex-grow-1 text-center text-md-start">
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Phase 1 — Proposal Submission
                </p>
                <h4 class="text-white fw-bold mb-2" style="font-size: 1.25rem; letter-spacing: -0.02em;">
                    <?php echo $proposal ? htmlspecialchars($project['title'] ?? 'Project Proposal') : 'New Project Proposal'; ?>
                </h4>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <span class="prop-hero-chip" style="background: <?php echo htmlspecialchars((string)($sc[0]), ENT_QUOTES, 'UTF-8'); ?>; color: <?php echo htmlspecialchars((string)($sc[1]), ENT_QUOTES, 'UTF-8'); ?>;">
                        <i class="bi <?php echo htmlspecialchars((string)($sc[2]), ENT_QUOTES, 'UTF-8'); ?>"></i> <?php echo htmlspecialchars((string)($st), ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
            </div>
            <?php if ($proposal): ?>
            <div class="d-none d-md-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(255,255,255,0.06);">
                <i class="bi bi-person-badge-fill" style="font-size: 1.4rem; color: #34d399;"></i>
                <div>
                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.04em;">Supervisor</div>
                    <div class="text-white fw-semibold" style="font-size: 0.88rem;"><?php echo htmlspecialchars($supName); ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <?php if ($proposal && $proposal['status'] === 'Approved'): ?>
                <!-- Approved State -->
                <div class="prop-section">
                    <div class="prop-section-body prop-approved-card">
                        <div class="prop-approved-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #059669;">Proposal Approved!</h5>
                        <p class="text-muted mb-3" style="font-size: 0.875rem; max-width: 380px; margin: 0 auto 16px;">Your FYP proposal has been formally approved by your supervisor. You may now proceed to the next stage.</p>
                        <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-3" style="background: rgba(5,150,105,0.08); border: 1px solid rgba(5,150,105,0.2);">
                            <i class="bi bi-person-badge-fill" style="color: #059669;"></i>
                            <span class="fw-semibold" style="font-size: 0.875rem; color: #059669;"><?php echo htmlspecialchars($supName); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Abstract (read-only) -->
                <div class="prop-section">
                    <div class="prop-section-header">
                        <div class="prop-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                            <i class="bi bi-file-text-fill"></i>
                        </div>
                        <div>
                            <h6>Approved Abstract</h6>
                            <small>Final approved project summary</small>
                        </div>
                    </div>
                    <div class="prop-section-body">
                        <p class="text-muted mb-0" style="font-size: 0.875rem; line-height: 1.8; text-align: justify;">
                            <?php echo nl2br(htmlspecialchars($abstractVal)); ?>
                        </p>
                        <?php if ($proposal['file_path']): ?>
                            <div class="mt-4 pt-3 border-top">
                                <a href="<?php echo $basePath . htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-3 px-4">
                                    <i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Download Proposal Document
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <!-- Submission Form -->
                <form action="<?php echo $basePath; ?>/student/proposal/submit" method="POST" enctype="multipart/form-data">

                    <!-- Project Details -->
                    <div class="prop-section">
                        <div class="prop-section-header">
                            <div class="prop-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div>
                                <h6>Project Details</h6>
                                <small>Title, supervisor, and abstract</small>
                            </div>
                        </div>
                        <div class="prop-section-body">
                            <div class="row g-3">
                                <div class="col-12 prop-field">
                                    <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($titleVal); ?>" required placeholder="e.g. AI-Powered Smart Grid Analytics">
                                </div>
                                <div class="col-md-6 prop-field">
                                    <label class="form-label">Choose Supervisor <span class="text-danger">*</span></label>
                                    <select class="form-select" id="supervisor_id" name="supervisor_id" required>
                                        <option value="" disabled <?php echo empty($supervisorIdVal) ? 'selected' : ''; ?>>Select Faculty Member</option>
                                        <?php foreach ($supervisors as $s): ?>
                                            <option value="<?php echo htmlspecialchars((string)($s['user_id']), ENT_QUOTES, 'UTF-8'); ?>" <?php echo $s['user_id'] == $supervisorIdVal ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 prop-field">
                                    <label class="form-label">Upload Proposal (PDF/DOC) <?php echo $proposal ? '' : '<span class="text-danger">*</span>'; ?></label>
                                    <input type="file" class="form-control" id="proposal_file" name="proposal_file" <?php echo $proposal ? '' : 'required'; ?>>
                                    <?php if ($proposal && $proposal['file_path']): ?>
                                        <div class="mt-1" style="font-size: 0.75rem; color: var(--text-secondary);">
                                            <i class="bi bi-file-earmark-check text-primary me-1"></i>
                                            <a href="<?php echo $basePath . htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="text-decoration-none fw-semibold" style="color: #3b82f6;">Current file uploaded</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 prop-field">
                                    <label class="form-label">Project Abstract / Summary <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="abstract" name="abstract" rows="7" required placeholder="Describe your project scope, objectives, methodology, and expected outcomes..."><?php echo htmlspecialchars($abstractVal); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Members -->
                    <div class="prop-section">
                        <div class="prop-section-header">
                            <div class="prop-section-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h6>Team Members</h6>
                                <small>Optional — add up to 2 members</small>
                            </div>
                        </div>
                        <div class="prop-section-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="prop-member-input">
                                        <label><span class="num">1</span> Member Slot</label>
                                        <input type="text" id="member1" name="member1" value="<?php echo htmlspecialchars($member1Val); ?>" placeholder="Roll No or Email address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="prop-member-input">
                                        <label><span class="num">2</span> Member Slot</label>
                                        <input type="text" id="member2" name="member2" value="<?php echo htmlspecialchars($member2Val); ?>" placeholder="Roll No or Email address">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="prop-save-footer">
                            <span style="font-size: 0.75rem; color: var(--text-secondary);">
                                <i class="bi bi-info-circle me-1"></i>Members receive view access to the proposal
                            </span>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill me-2"></i><?php echo $proposal ? 'Resubmit Proposal' : 'Submit Proposal'; ?>
                            </button>
                        </div>
                    </div>
                
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
            <?php endif; ?>
        </div>

        <!-- Right sidebar -->
        <div class="col-lg-5">

            <!-- Feedback -->
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <div>
                        <h6>Supervisor Feedback</h6>
                        <small>Review comments and remarks</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <?php if ($proposal && $proposal['feedback']): ?>
                        <div class="prop-feedback-bubble">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-badge-fill" style="color: #0d9488;"></i>
                                <span class="fw-semibold" style="font-size: 0.82rem;">Review Comments</span>
                            </div>
                            <p class="mb-1" style="font-size: 0.82rem; line-height: 1.65; color: var(--text-primary);"><?php echo nl2br(htmlspecialchars($proposal['feedback'])); ?></p>
                            <div class="text-end mt-2"><small class="text-muted" style="font-size: 0.7rem;">Updated: <?php echo date('M d, Y', strtotime($proposal['updated_at'])); ?></small></div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-chat-left-dots text-muted" style="font-size: 2rem; opacity: 0.2;"></i>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.82rem;">No feedback yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submission Steps -->
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div>
                        <h6>Submission Process</h6>
                        <small>Track your proposal progress</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <div class="d-flex flex-column gap-2">
                        <?php
                        $steps = [
                            ['Fill in project title & abstract', !empty($titleVal)],
                            ['Choose a supervisor', !empty($supervisorIdVal)],
                            ['Upload proposal document', !empty($proposal['file_path'] ?? '')],
                            ['Supervisor reviews & approves', ($proposal['status'] ?? '') === 'Approved'],
                        ];
                        foreach ($steps as $i => [$label, $done]):
                            $cls = $done ? 'done' : (($proposal || $i === 0) ? 'active' : '');
                        ?>
                        <div class="prop-step <?php echo $cls; ?>">
                            <div class="step-circle">
                                <?php echo $done ? '<i class="bi bi-check-lg" style="font-size:0.75rem;"></i>' : ($i + 1); ?>
                            </div>
                            <span style="font-size: 0.82rem; font-weight: <?php echo $done ? '600' : '500'; ?>; color: <?php echo $done ? '#059669' : 'var(--text-primary)'; ?>;">
                                <?php echo htmlspecialchars((string)($label), ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <p class="text-muted mb-0" style="font-size: 0.76rem; line-height: 1.6;">
                            Submitting this form automatically creates your group and makes you the <strong>Group Leader</strong>. Members can be added or changed until the proposal is approved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
