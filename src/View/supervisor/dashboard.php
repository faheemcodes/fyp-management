<!-- Supervisor Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$fullName = trim($_SESSION['name'] ?? 'Supervisor');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>

<style>
/* ─── Hero Banner Styles ─── */
.group-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.group-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: 10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(16,185,129,0.05) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.group-hero-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: conic-gradient(from 0deg, #3b82f6, #2563eb, #1d4ed8, #3b82f6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: 0 8px 20px rgba(59,130,246,0.3);
    flex-shrink: 0;
}
.group-stat-pill {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 12px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 120px;
    backdrop-filter: blur(10px);
    margin-right: 12px;
}
.group-stat-pill .stat-num {
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 4px;
}
.group-stat-pill .stat-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: rgba(255,255,255,0.6);
    font-weight: 600;
}
@media (max-width: 768px) {
    .group-hero { padding: 24px 16px; }
    .group-stat-pill { margin-bottom: 10px; min-width: calc(50% - 12px); }
    .hero-stats-container { flex-wrap: wrap; justify-content: center; margin-top: 20px; }
}
</style>

<!-- Top Hero Banner -->
<div class="group-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: conic-gradient(from 0deg, #60a5fa, #3b82f6, #1d4ed8, #60a5fa);">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Welcome Back
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                    <?php echo htmlspecialchars($fullName); ?>
                </h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Manage your assigned groups and track their progress</p>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <a href="<?php echo $basePath; ?>/supervisor/groups" class="text-decoration-none">
                <div class="group-stat-pill" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-primary"><?php echo $groupCount; ?></span>
                    <span class="stat-label text-white">Assigned Groups</span>
                </div>
            </a>
            <a href="<?php echo $basePath; ?>/supervisor/reviews" class="text-decoration-none">
                <div class="group-stat-pill" style="margin-right: 0; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num text-warning"><?php echo $pendingProposals; ?></span>
                    <span class="stat-label text-white">Pending Proposals</span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 p-4 h-100" style="border-radius: 16px; background: var(--card-bg); box-shadow: var(--card-shadow);">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <i class="bi bi-person-video3 text-primary" style="font-size: 1.2rem;"></i>
        <h6 class="fw-bold m-0" style="color: var(--text-primary); letter-spacing: -0.01em;">Your Assigned FYP Groups</h6>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none;">
            <thead style="background: var(--table-header-bg);">
                <tr>
                    <th class="py-3 px-3 border-0 rounded-start text-uppercase" style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.05em;">Group Code</th>
                    <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.05em;">Project Title</th>
                    <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.05em;">Project Status</th>
                    <th class="py-3 px-3 border-0 text-uppercase" style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.05em;">Current FYP Stage</th>
                    <th class="py-3 px-3 border-0 rounded-end text-end text-uppercase" style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr style="transition: background-color 0.2s;">
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important;">
                        <span class="fw-bold" style="color: #3b82f6; font-size: 0.9rem;"><?php echo htmlspecialchars($g['group_code'] ?? 'Pending'); ?></span>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important;">
                        <div class="fw-semibold text-truncate" style="max-width: 350px; color: var(--text-primary); font-size: 0.9rem;" title="<?php echo htmlspecialchars($g['project_title']); ?>">
                            <?php echo htmlspecialchars($g['project_title']); ?>
                        </div>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important;">
                        <?php if($g['project_status'] === 'Approved'): ?>
                            <span style="font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; background: rgba(16,185,129,0.1); color: #059669; font-weight: 600;">Approved</span>
                        <?php elseif($g['project_status'] === 'Submitted'): ?>
                            <span style="font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; background: rgba(245,158,11,0.1); color: #d97706; font-weight: 600;">Submitted</span>
                        <?php else: ?>
                            <span style="font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; background: var(--form-bg); color: var(--text-secondary); font-weight: 600; border: 1px solid var(--border-color);"><?php echo htmlspecialchars($g['project_status']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-3 border-bottom" style="border-color: var(--border-color) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(59,130,246,0.15);"></span>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-primary); line-height: 1.3;">
                                <?php echo htmlspecialchars($g['progress_stage']); ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-3 py-3 border-bottom text-end" style="border-color: var(--border-color) !important;">
                        <a href="<?php echo $basePath; ?>/supervisor/groups" class="btn btn-sm px-3 rounded-pill fw-semibold" style="font-size: 0.75rem; background: var(--form-bg); color: var(--text-primary); border: 1px solid var(--border-color); transition: all 0.2s;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='#fff'; this.style.borderColor='var(--primary-color)';" onmouseout="this.style.background='var(--form-bg)'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)';">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div style="font-size: 2.5rem; color: var(--border-color); margin-bottom: 1rem;"><i class="bi bi-people"></i></div>
                            <h6 class="fw-bold" style="color: var(--text-primary);">No Assigned Groups</h6>
                            <p class="small text-muted mb-0">You have no student groups assigned to you yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
