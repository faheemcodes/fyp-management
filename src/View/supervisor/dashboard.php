<!-- Supervisor Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$fullName = trim($_SESSION['name'] ?? 'Supervisor');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>

<!-- Welcome Banner -->
<div class="dash-banner mb-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 1.5rem 2.5rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
    <div style="position: absolute; right: -5%; top: -20%; opacity: 0.1;">
        <i class="bi bi-mortarboard-fill" style="font-size: 10rem;"></i>
    </div>
    <div style="z-index: 1;">
        <h4 class="fw-bold mb-1" style="letter-spacing: -0.01em;">Welcome back, <?php echo htmlspecialchars($firstName); ?></h4>
        <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Manage your assigned groups and track their progress</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Assigned Groups -->
    <div class="col-6 col-lg-3">
        <a href="<?php echo $basePath; ?>/supervisor/groups" class="text-decoration-none d-block stat-card-link" style="color: inherit;">
            <div class="stat-mini d-flex align-items-center gap-3" style="transition: transform 0.2s; background: var(--card-bg); border-radius: 16px; padding: 1.25rem; box-shadow: var(--card-shadow);" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                <div class="stat-mini-icon" style="width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; background: rgba(59,130,246,0.1); color: #3b82f6;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-mini-label" style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 2px;">Assigned Groups</div>
                    <div class="stat-mini-value" style="font-size: 1.3rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em;">
                        <?php echo $groupCount; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Pending Proposals -->
    <div class="col-6 col-lg-3">
        <a href="<?php echo $basePath; ?>/supervisor/reviews" class="text-decoration-none d-block stat-card-link" style="color: inherit;">
            <div class="stat-mini d-flex align-items-center gap-3" style="transition: transform 0.2s; background: var(--card-bg); border-radius: 16px; padding: 1.25rem; box-shadow: var(--card-shadow);" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                <div class="stat-mini-icon" style="width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; background: rgba(245,158,11,0.1); color: #f59e0b;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-mini-label" style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 2px;">Pending Proposals</div>
                    <div class="stat-mini-value" style="font-size: 1.3rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em;">
                        <?php echo $pendingProposals; ?>
                    </div>
                </div>
            </div>
        </a>
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
