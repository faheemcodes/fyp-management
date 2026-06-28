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
/* ─── Proposal Page Unified Styles ─── */

/* ── Section Cards ── */
.prop-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    margin-bottom: 24px;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.prop-section:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    transform: translateY(-2px);
}
.prop-section-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    background: rgba(var(--bg-color-rgb), 0.3);
}
.prop-section-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.prop-section-header h6 {
    font-size: 1.05rem;
    font-weight: 700;
    margin: 0 0 2px 0;
    color: var(--text-primary);
}
.prop-section-header small {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin: 0;
}
.prop-section-body {
    padding: 24px;
}

/* ── Input Styling ── */
.prop-field {
    margin-bottom: 20px;
}
.prop-field label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
    display: block;
}
.prop-input {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: var(--text-primary);
    width: 100%;
    transition: all 0.2s;
}
.prop-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
    outline: none;
    background: var(--card-bg);
}

/* ── Member Input ── */
.member-slot {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    padding: 10px 16px;
    transition: all 0.2s;
}
.member-slot:focus-within {
    border-color: var(--primary-color);
    background: var(--card-bg);
}
.member-slot .num {
    width: 24px;
    height: 24px;
    border-radius: 8px;
    background: rgba(99,102,241,0.15);
    color: #6366f1;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.member-slot input {
    border: none;
    background: transparent;
    padding: 0;
    width: 100%;
    font-size: 0.95rem;
    color: var(--text-primary);
    outline: none;
}

/* ── Step Tracker ── */
.prop-step {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border-radius: 14px;
    border: 1.5px solid var(--border-color);
    background: var(--form-bg);
    transition: all 0.2s ease;
    margin-bottom: 12px;
}
.prop-step:last-child { margin-bottom: 0; }
.prop-step.done {
    border-color: rgba(5,150,105,0.4);
    background: rgba(5,150,105,0.05);
}
.prop-step.active {
    border-color: rgba(59,130,246,0.4);
    background: rgba(59,130,246,0.05);
}
.step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
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
    box-shadow: 0 0 0 4px rgba(59,130,246,0.2);
}
.step-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-primary);
}
.prop-step.done .step-text { font-weight: 600; color: #059669; }

/* ── Feedback Bubble ── */
.feedback-bubble {
    background: rgba(245,158,11,0.05);
    border: 1px solid rgba(245,158,11,0.2);
    border-radius: 16px;
    padding: 20px;
    position: relative;
}
.feedback-bubble::before {
    content: '';
    position: absolute;
    top: 20px;
    left: -8px;
    width: 16px;
    height: 16px;
    background: var(--card-bg);
    border-left: 1px solid rgba(245,158,11,0.2);
    border-bottom: 1px solid rgba(245,158,11,0.2);
    transform: rotate(45deg);
}

.file-upload-wrapper {
    position: relative;
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 32px 20px;
    text-align: center;
    background: var(--form-bg);
    transition: all 0.2s;
    cursor: pointer;
}
.file-upload-wrapper:hover {
    border-color: var(--primary-color);
    background: rgba(59,130,246,0.03);
}
.file-upload-wrapper input[type="file"] {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    opacity: 0;
    cursor: pointer;
}
</style>

<?php if ($group && !$isLeader): ?>
<!-- ─── GROUP MEMBER READ-ONLY VIEW ─── -->

    <!-- Standard Hero Banner -->
    <div class="group-hero mb-4">
        <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
            <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
                <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #60a5fa, #3b82f6, #1d4ed8, #60a5fa);">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1 text-white"><?php echo htmlspecialchars($project['title'] ?? 'FYP Project'); ?></h3>
                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                        <span class="badge" style="background: <?php echo $sc[0]; ?>; color: <?php echo $sc[1]; ?>; font-size: 0.85rem; padding: 6px 12px;">
                            <i class="bi <?php echo $sc[2]; ?> me-1"></i> <?php echo $st; ?>
                        </span>
                        <span class="badge" style="background: rgba(255,255,255,0.15); color: #fff; font-size: 0.85rem; padding: 6px 12px;">
                            Group: <?php echo htmlspecialchars($group['group_code'] ?? 'Pending'); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-3 p-3 text-white">
                <i class="bi bi-person-badge-fill fs-3 text-success"></i>
                <div>
                    <div class="opacity-75" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Supervisor</div>
                    <div class="fw-bold fs-6"><?php echo htmlspecialchars($supName); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-body-text"></i>
                    </div>
                    <div>
                        <h6>Project Abstract</h6>
                        <small>Submitted description</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <p style="font-size: 0.95rem; line-height: 1.7; color: var(--text-primary); white-space: pre-wrap;"><?php echo htmlspecialchars($abstractVal); ?></p>
                    
                    <?php if (!empty($proposal['file_path'])): ?>
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-bold mb-3">Attached Document</h6>
                        <a href="<?php echo $basePath . htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill fs-5"></i> View Proposal File
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <div>
                        <h6>Supervisor Feedback</h6>
                        <small>Remarks on this proposal</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <?php if ($proposal && $proposal['feedback']): ?>
                        <div class="feedback-bubble">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-circle fs-5" style="color: #f59e0b;"></i>
                                <span class="fw-bold" style="font-size: 0.9rem;">Review Comments</span>
                            </div>
                            <p class="mb-2" style="font-size: 0.9rem; line-height: 1.6; color: var(--text-primary);"><?php echo nl2br(htmlspecialchars($proposal['feedback'])); ?></p>
                            <div class="text-end text-muted" style="font-size: 0.75rem;">Updated: <?php echo date('M d, Y', strtotime($proposal['updated_at'])); ?></div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0" style="font-size: 0.9rem;">No feedback provided yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
<!-- ─── GROUP LEADER / SUBMISSION VIEW ─── -->

    <!-- Status Alerts -->
    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash']['success'])): ?>
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-check-circle-fill fs-4"></i>
            <div><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Main Form Column -->
        <div class="col-lg-7">

            <?php if ($st === 'Approved'): ?>
                <div class="prop-section text-center p-5">
                    <div style="width: 80px; height: 80px; background: rgba(5,150,105,0.1); color: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-2">Proposal Approved!</h3>
                    <p class="text-muted mb-4" style="font-size: 0.95rem; max-width: 400px; margin: 0 auto;">Your project has been approved by your supervisor. You can now proceed to the next stage of your Final Year Project.</p>
                    <a href="<?php echo $basePath; ?>/student/dashboard" class="btn btn-success px-4 py-2 rounded-pill fw-semibold">
                        Go to Dashboard
                    </a>
                </div>
            <?php else: ?>
                <form action="<?php echo $basePath; ?>/student/proposal/submit" method="POST" enctype="multipart/form-data">
                    
                    <!-- Basic Information -->
                    <div class="prop-section">
                        <div class="prop-section-header">
                            <div class="prop-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div>
                                <h6>Project Details</h6>
                                <small>Core information about your project</small>
                            </div>
                        </div>
                        <div class="prop-section-body">
                            <div class="prop-field">
                                <label for="title">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="prop-input" id="title" name="title" required value="<?php echo htmlspecialchars($titleVal); ?>" placeholder="e.g. AI-Based Healthcare System">
                            </div>
                            
                            <div class="prop-field">
                                <label for="supervisor_id">Choose Supervisor <span class="text-danger">*</span></label>
                                <select class="prop-input form-select" id="supervisor_id" name="supervisor_id" required>
                                    <option value="" disabled <?php echo !$supervisorIdVal ? 'selected' : ''; ?>>Select a supervisor</option>
                                    <?php foreach ($supervisors as $sup): ?>
                                        <option value="<?php echo $sup['user_id']; ?>" <?php echo ($sup['user_id'] == $supervisorIdVal) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sup['name']) . " (" . htmlspecialchars($sup['department']) . ")"; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="prop-field">
                                <label for="document">Proposal Document (PDF/DOCX) <?php echo !$proposal ? '<span class="text-danger">*</span>' : ''; ?></label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="document" name="document" accept=".pdf,.doc,.docx" <?php echo !$proposal ? 'required' : ''; ?>>
                                    <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 2rem;"></i>
                                    <h6 class="mt-3 fw-bold">Click or drag file to upload</h6>
                                    <p class="text-muted small mb-0">PDF, DOC, or DOCX (Max 10MB)</p>
                                </div>
                                <?php if ($proposal && $proposal['file_path']): ?>
                                    <div class="mt-3 d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                                        <i class="bi bi-file-earmark-check text-success fs-5"></i>
                                        <span>Current file: </span>
                                        <a href="<?php echo $basePath . htmlspecialchars($proposal['file_path']); ?>" target="_blank" class="fw-bold text-decoration-none">View Document</a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="prop-field mb-0">
                                <label for="abstract">Project Abstract / Summary <span class="text-danger">*</span></label>
                                <textarea class="prop-input" id="abstract" name="abstract" rows="6" required placeholder="Describe your project scope, objectives, methodology, and expected outcomes..."><?php echo htmlspecialchars($abstractVal); ?></textarea>
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
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="member-slot">
                                        <span class="num">1</span>
                                        <input type="text" id="member1" name="member1" value="<?php echo htmlspecialchars($member1Val); ?>" placeholder="Roll No or Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="member-slot">
                                        <span class="num">2</span>
                                        <input type="text" id="member2" name="member2" value="<?php echo htmlspecialchars($member2Val); ?>" placeholder="Roll No or Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="prop-section-body border-top" style="background: var(--form-bg); padding: 20px 24px; display: flex; align-items: center; justify-content: space-between;">
                            <span class="text-muted" style="font-size: 0.8rem;">
                                <i class="bi bi-info-circle me-1"></i>Members get view access automatically.
                            </span>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                                <i class="bi bi-send-fill"></i> <?php echo $proposal ? 'Resubmit Proposal' : 'Submit Proposal'; ?>
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Right sidebar -->
        <div class="col-lg-5">

            <!-- Submission Process Tracker -->
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div>
                        <h6>Submission Process</h6>
                        <small>Track your progress</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <div class="d-flex flex-column">
                        <?php
                        $steps = [
                            ['Fill in project details', !empty($titleVal)],
                            ['Choose a supervisor', !empty($supervisorIdVal)],
                            ['Upload proposal document', !empty($proposal['file_path'] ?? '')],
                            ['Supervisor review', ($proposal['status'] ?? '') === 'Approved'],
                        ];
                        foreach ($steps as $i => [$label, $done]):
                            $cls = $done ? 'done' : (($proposal || $i === 0) ? 'active' : '');
                        ?>
                        <div class="prop-step <?php echo $cls; ?>">
                            <div class="step-circle">
                                <?php echo $done ? '<i class="bi bi-check-lg"></i>' : ($i + 1); ?>
                            </div>
                            <span class="step-text"><?php echo $label; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 p-3 rounded-3" style="background: rgba(59,130,246,0.05); border: 1px solid rgba(59,130,246,0.1);">
                        <p class="text-muted mb-0" style="font-size: 0.82rem; line-height: 1.6;">
                            <i class="bi bi-lightbulb text-primary me-1"></i>
                            Submitting this form makes you the <strong>Group Leader</strong>. You can change team members until the proposal is approved.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Feedback Bubble -->
            <div class="prop-section">
                <div class="prop-section-header">
                    <div class="prop-section-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <div>
                        <h6>Supervisor Feedback</h6>
                        <small>Remarks on your proposal</small>
                    </div>
                </div>
                <div class="prop-section-body">
                    <?php if ($proposal && $proposal['feedback']): ?>
                        <div class="feedback-bubble">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-circle fs-5 text-warning"></i>
                                <span class="fw-bold" style="font-size: 0.9rem; color: var(--text-primary);">Review Comments</span>
                            </div>
                            <p class="mb-2" style="font-size: 0.95rem; line-height: 1.6; color: var(--text-primary);"><?php echo nl2br(htmlspecialchars($proposal['feedback'])); ?></p>
                            <div class="text-end mt-3"><small class="text-muted fw-semibold">Updated: <?php echo date('M d, Y', strtotime($proposal['updated_at'])); ?></small></div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0" style="font-size: 0.95rem;">No feedback provided yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

<?php endif; ?>
