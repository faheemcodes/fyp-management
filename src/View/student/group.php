<!-- Student Group & Members View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$member1Val = isset($groupMembers[0]) ? $groupMembers[0]['student_id'] : '';
$member2Val = isset($groupMembers[1]) ? $groupMembers[1]['student_id'] : '';
$isLeader = isset($group) && $group && $group['created_by'] == ($_SESSION['user_id'] ?? 0);
?>

<style>
/* ─── Group Page Scoped Styles ─── */
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
    background: conic-gradient(from 0deg, #60a5fa, #3b82f6, #1d4ed8, #60a5fa);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}
.group-hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}
.group-stat-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 20px;
    background: rgba(255,255,255,0.06);
    border-radius: 12px;
    min-width: 80px;
}
.group-stat-pill .stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    letter-spacing: -0.03em;
}
.group-stat-pill .stat-label {
    font-size: 0.62rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: rgba(255,255,255,0.4);
    margin-top: 4px;
}

/* ─── Section Panel (reuse profile pattern) ─── */
.grp-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.grp-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
}
.grp-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}
.grp-section-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.grp-section-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}
.grp-section-header small {
    font-size: 0.72rem;
    color: var(--text-secondary);
    margin: 0;
}
.grp-section-body {
    padding: 24px;
}

/* ─── Member Row Card ─── */
.member-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}
.member-row:hover {
    border-color: rgba(59,130,246,0.25);
    box-shadow: 0 4px 16px rgba(59,130,246,0.07);
    transform: translateY(-1px);
}
.member-row .m-avatar {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    object-fit: cover;
    border: 2px solid rgba(59,130,246,0.15);
    flex-shrink: 0;
}
.member-row .m-name {
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}
.member-row .m-detail {
    font-size: 0.75rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 4px;
}
.member-row .m-detail i {
    font-size: 0.7rem;
    opacity: 0.7;
}
.member-role-badge {
    font-size: 0.6rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    white-space: nowrap;
}

/* ─── Info Item ─── */
.grp-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid var(--border-color);
}
.grp-info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.grp-info-item .info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
    margin-top: 2px;
}
.grp-info-item .info-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    margin-bottom: 2px;
}
.grp-info-item .info-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.45;
}

/* ─── Manage Form ─── */
.manage-member-input {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 16px;
    transition: border-color 0.2s ease;
}
.manage-member-input:focus-within {
    border-color: var(--primary-color);
}
.manage-member-input label {
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
.manage-member-input label .num {
    width: 18px;
    height: 18px;
    border-radius: 6px;
    background: rgba(59,130,246,0.1);
    color: #3b82f6;
    font-size: 0.6rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.manage-member-input input {
    border: none;
    background: transparent;
    padding: 0;
    font-size: 0.85rem;
    color: var(--text-primary);
    width: 100%;
    outline: none;
}
.manage-member-input input::placeholder {
    color: #9ca3af;
    font-size: 0.82rem;
}

/* ─── Notice Card ─── */
.notice-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    padding: 20px;
    position: relative;
    overflow: hidden;
}
.notice-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(180deg, #ef4444, #dc2626);
    border-radius: 0 4px 4px 0;
}

@media (max-width: 768px) {
    .group-hero { padding: 24px 16px; }
    .group-stat-pill { display: none; }
    .grp-section-body { padding: 16px; }
    .member-row { padding: 12px; gap: 12px; }
    .member-row .m-avatar { width: 42px; height: 42px; border-radius: 12px; }
}
</style>

<?php if (!$group): ?>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card border-0 text-center p-5">
                <div style="width: 72px; height: 72px; background: rgba(59,130,246,0.08); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: #3b82f6;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">No Project Group Found</h5>
                <p class="text-muted mb-4" style="font-size: 0.875rem; max-width: 380px; margin: 0 auto 24px;">Submit your project proposal to automatically create a group — you can add team members during that step.</p>
                <a href="<?php echo $basePath; ?>/student/proposal" class="btn btn-primary px-4 rounded-3">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i>Go to Project Proposal
                </a>
            </div>
        </div>
    </div>
<?php else: ?>

    <!-- ═══════════════ Hero Banner ═══════════════ -->
    <div class="group-hero">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <!-- Icon -->
            <div class="group-hero-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <!-- Info -->
            <div class="flex-grow-1 text-center text-md-start">
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Project Team
                </p>
                <h4 class="text-white fw-bold mb-2" style="font-size: 1.25rem; letter-spacing: -0.02em; line-height: 1.35;">
                    <?php echo htmlspecialchars($group['project_title'] ?? 'Your FYP Group'); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <span class="group-hero-chip" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); font-family: monospace;">
                        <?php echo htmlspecialchars($group['group_code'] ?? 'ID PENDING'); ?>
                    </span>
                    <?php if ($isLeader): ?>
                        <span class="group-hero-chip" style="background: rgba(59,130,246,0.2); color: #93c5fd;">
                            <i class="bi bi-shield-fill-check"></i> Group Leader
                        </span>
                    <?php endif; ?>
                    <span class="group-hero-chip" style="background: rgba(16,185,129,0.15); color: #6ee7b7;">
                        <i class="bi bi-activity"></i> <?php echo htmlspecialchars($group['progress_stage']); ?>
                    </span>
                </div>
            </div>

            <!-- Stats Pills -->
            <div class="d-none d-lg-flex gap-3">
                <div class="group-stat-pill">
                    <span class="stat-num"><?php echo count($members); ?></span>
                    <span class="stat-label">Member<?php echo count($members) != 1 ? 's' : ''; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- ═══════════════ COLUMN 1: Members + Manage ═══════════════ -->
        <div class="col-lg-7">

            <!-- Members Directory -->
            <div class="grp-section">
                <div class="grp-section-header">
                    <div class="grp-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h6>Team Members</h6>
                        <small><?php echo count($members); ?> member<?php echo count($members) != 1 ? 's' : ''; ?> in this group</small>
                    </div>
                </div>
                <div class="grp-section-body">
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($members as $idx => $m):
                            $avatarFile = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg';
                            $isThisLeader = $m['user_id'] == $group['created_by'];
                        ?>
                        <div class="member-row">
                            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="m-avatar" alt="Avatar">
                            <div style="flex: 1; min-width: 0;">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="m-name"><?php echo htmlspecialchars($m['name']); ?></span>
                                    <?php if ($isThisLeader): ?>
                                        <span class="member-role-badge" style="background: rgba(59,130,246,0.12); color: #3b82f6;">
                                            <i class="bi bi-star-fill" style="font-size: 0.5rem; margin-right: 2px;"></i> Leader
                                        </span>
                                    <?php else: ?>
                                        <span class="member-role-badge" style="background: rgba(107,114,128,0.08); color: #6b7280;">
                                            Member
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="m-detail">
                                        <i class="bi bi-person-badge"></i>
                                        <span class="font-monospace"><?php echo htmlspecialchars($m['student_id']); ?></span>
                                    </span>
                                    <span class="m-detail">
                                        <i class="bi bi-building"></i>
                                        <?php echo htmlspecialchars($m['department']); ?>
                                    </span>
                                    <?php if (!empty($m['phone'])): ?>
                                        <span class="m-detail">
                                            <i class="bi bi-telephone"></i>
                                            <span class="font-monospace"><?php echo htmlspecialchars($m['phone']); ?></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Manage Members (Leader only) -->
            <?php if ($isLeader): ?>
            <div class="grp-section">
                <div class="grp-section-header">
                    <div class="grp-section-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <h6>Manage Team</h6>
                        <small>Add or remove members by Roll No or email</small>
                    </div>
                </div>
                <div class="grp-section-body">
                    <form action="<?php echo $basePath; ?>/student/group/update-members" method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="manage-member-input">
                                    <label><span class="num">1</span> Member Slot</label>
                                    <input type="text" id="member1" name="member1" value="<?php echo htmlspecialchars($member1Val); ?>" placeholder="Roll No or Email address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="manage-member-input">
                                    <label><span class="num">2</span> Member Slot</label>
                                    <input type="text" id="member2" name="member2" value="<?php echo htmlspecialchars($member2Val); ?>" placeholder="Roll No or Email address">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="font-size: 0.75rem; color: var(--text-secondary);">
                                <i class="bi bi-info-circle me-1"></i>Leave blank to remove a member
                            </span>
                            <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">
                                <i class="bi bi-check2-circle me-2"></i>Save Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <!-- ═══════════════ COLUMN 2: Group Info + Notice ═══════════════ -->
        <div class="col-lg-5">

            <!-- Group Information -->
            <div class="grp-section">
                <div class="grp-section-header">
                    <div class="grp-section-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div>
                        <h6>Group Details</h6>
                        <small>Project and supervision info</small>
                    </div>
                </div>
                <div class="grp-section-body">
                    <div class="grp-info-item">
                        <div class="info-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div>
                            <div class="info-label">Supervisor</div>
                            <div class="info-value"><?php echo htmlspecialchars($group['supervisor_name'] ?? 'Not Assigned'); ?></div>
                        </div>
                    </div>
                    <div class="grp-info-item">
                        <div class="info-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div class="info-label">Project Title</div>
                            <div class="info-value" style="font-weight: 500; word-break: break-word;"><?php echo htmlspecialchars($group['project_title'] ?? '—'); ?></div>
                        </div>
                    </div>
                    <div class="grp-info-item">
                        <div class="info-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                            <i class="bi bi-hash"></i>
                        </div>
                        <div>
                            <div class="info-label">Group Code</div>
                            <div class="info-value font-monospace" style="color: #3b82f6; letter-spacing: 0.02em;">
                                <?php echo htmlspecialchars($group['group_code'] ?? 'Pending'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="grp-info-item">
                        <div class="info-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                            <i class="bi bi-signpost-split"></i>
                        </div>
                        <div>
                            <div class="info-label">Progress Stage</div>
                            <span style="font-size: 0.78rem; background: rgba(59,130,246,0.1); color: #3b82f6; padding: 4px 14px; border-radius: 20px; font-weight: 600; display: inline-block; margin-top: 4px;">
                                <?php echo htmlspecialchars($group['progress_stage']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leadership Notice -->
            <div class="notice-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 34px; height: 34px; background: rgba(239,68,68,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; color: #ef4444; flex-shrink: 0;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <span class="fw-bold text-danger" style="font-size: 0.85rem;">Leadership Notice</span>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.65; padding-left: 46px;">
                    Only the <strong>Group Leader</strong> can modify members or reassign the supervisor. Changes can be made from the <strong>Project Proposal</strong> page before approval.
                </p>
            </div>

        </div>
    </div>

<?php endif; ?>
