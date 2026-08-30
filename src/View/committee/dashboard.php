<style>
.notice-minimal-item {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.notice-minimal-item:hover {
    background: var(--card-bg);
    border-color: rgba(16, 185, 129, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}
.notice-minimal-item .notice-accent-bar {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3.5px;
    background: #10b981;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.notice-minimal-item:hover .notice-accent-bar {
    opacity: 1;
}
.notice-date-badge {
    font-size: 0.68rem;
    font-weight: 600;
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.02em;
}
.notice-view-btn {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 5px 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
    line-height: 1;
}
.notice-minimal-item:hover .notice-view-btn {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.3);
}

.notice-list {
    padding-right: 8px;
    padding-left: 2px;
    padding-top: 2px;
    padding-bottom: 2px;
}
.notice-list::-webkit-scrollbar {
    width: 5px;
}
.notice-list::-webkit-scrollbar-track {
    background: transparent;
}
.notice-list::-webkit-scrollbar-thumb {
    background: rgba(150, 150, 150, 0.25);
    border-radius: 10px;
}
.notice-list::-webkit-scrollbar-thumb:hover {
    background: rgba(150, 150, 150, 0.45);
}
</style>
<!-- Committee Dashboard View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
$fullName = trim($_SESSION['name'] ?? 'Committee Member');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>

<!-- -- Top Hero Banner -- -->
<div class="page-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    Welcome back
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    <?php echo htmlspecialchars($fullName); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 justify-content-center justify-content-md-start flex-wrap">
                    <span style="font-size: 0.75rem;background: rgba(255,255,255,0.1);color: rgba(255,255,255,0.8);padding: 4px 12px;border-radius: 20px;font-weight: 600">
                        <?php echo htmlspecialchars($committee['department'] ?? 'Department'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <div class="page-stat-pill d-none d-md-flex">
                <div class="stat-num"><?php echo count($groups); ?></div>
                <div class="stat-label">Total Groups</div>
            </div>
        </div>
    </div>
</div>

<!-- -- Premium Stat Cards Row -- -->
<div class="row g-3 mb-4 mt-2">
    <!-- Grade Proposal Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-purple">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-purple" style="width: 54px; height: 54px; font-size: 1.4rem;">
                        <i class="bi bi-table"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">Grade Proposal</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">Proposal Defence Assessment</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Grade Progress Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-amber">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-amber" style="width: 54px; height: 54px; font-size: 1.4rem;">
                        <i class="bi bi-table"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">Grade Progress</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">FYP Progress Assessment</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Grade Final Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Final Presentation" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-rose">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-rose" style="width: 54px; height: 54px; font-size: 1.4rem;">
                        <i class="bi bi-table"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">Grade Final</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">Final Presentation Assessment</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- -- Recent Notices -- -->
    <div class="col-xl-4">
        <div class="card border-0 p-3 p-md-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(59, 130, 246, 0.1);color: #3b82f6">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <h6>Recent Notices</h6>
                    <small>View latest announcements and updates</small>
                </div>
            </div>
            <div class="notice-list custom-scroll" style="max-height: 320px; overflow-y: auto;">
                <?php foreach($recentNotices as $n): ?>
                <div class="notice-minimal-item" role="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>">
                    <div class="notice-accent-bar"></div>
                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="notice-date-badge">
                                <i class="bi bi-calendar3" style="font-size: 0.62rem;"></i>
                                <?php echo date('M d', strtotime($n['notice_date'])); ?>
                            </span>
                        </div>
                        <div class="text-truncate" style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);" title="<?php echo htmlspecialchars($n['subject']); ?>">
                            <?php echo htmlspecialchars($n['subject']); ?>
                        </div>
                    </div>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo $n['id']; ?>" class="notice-view-btn flex-shrink-0" onclick="event.stopPropagation();">
                        <span>View</span>
                        <i class="bi bi-arrow-up-right" style="font-size: 0.7rem;"></i>
                    </button>
                </div>
                <?php endforeach; ?>
                <?php if(empty($recentNotices)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                    No recent notices found.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="col-xl-8">
        <div class="card border-0 p-3 p-md-4 h-100">
            <div class="page-section-header mb-4 position-relative">
                <div class="page-section-icon" style="background: rgba(16, 185, 129, 0.1);color: #10b981">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h6>FYP Student Groups & Stages</h6>
                    <small>View assigned groups and track progress</small>
                </div>
                <a href="<?php echo $bp; ?>/committee/evaluations" class="btn btn-sm rounded-pill px-4 fw-bold shadow-sm" style="font-size: 0.8rem; background: #10b981; color: #fff; border: none;">
                    Evaluate
                </a>
            </div>

            <div class="d-none d-md-block table-responsive custom-table-scroll" style="max-height: 320px; overflow-y: auto;">
                <table class="table table-hover align-middle m-0">
                    <thead>
                        <tr>
                            <th>Group Code</th>
                            <th>Project Title</th>
                            <th>Progress Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($groups as $g): ?>
                        <tr>
                            <td>
                                <span class="fw-semibold text-secondary" style="font-family: monospace;font-size: 0.8rem;background: var(--form-bg);padding: 4px 8px;border-radius: 6px;border: 1px solid var(--border-color)">
                                    <?php echo htmlspecialchars($g['group_code']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark text-wrap" style="font-size: 0.85rem; max-width: 320px;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden" title="<?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>">
                                     <?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.65rem;background: rgba(16,185,129,0.1);color: #059669;padding: 4px 10px;border-radius: 20px;font-weight: 700;text-transform: uppercase">
                                    <?php echo htmlspecialchars($g['progress_stage']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($groups)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-folder-x fs-3 d-block mb-2 text-opacity-50"></i>
                                    No project groups registered in the platform yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="d-block d-md-none p-3">
                <?php foreach($groups as $g): ?>
                    <div class="card border rounded-3 p-3 mb-3 shadow-sm" style="background: var(--card-bg)">
                        <div class="mb-2">
                            <span class="fw-semibold text-secondary" style="font-family: monospace;font-size: 0.75rem;background: var(--form-bg);padding: 3px 6px;border-radius: 4px;border: 1px solid var(--border-color)">
                                <?php echo htmlspecialchars($g['group_code']); ?>
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;line-height: 1.4;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden">
                            <?php echo htmlspecialchars($g['project_title'] ?? 'No project title set'); ?>
                        </h6>
                        <div class="mb-3">
                            <span style="font-size: 0.65rem;background: rgba(16,185,129,0.1);color: #059669;padding: 4px 10px;border-radius: 20px;font-weight: 700;text-transform: uppercase;display: inline-block">
                                <?php echo htmlspecialchars($g['progress_stage']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <div class="text-center text-muted py-4 bg-light rounded-3 small">
                        No project groups registered in the platform yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Notice Modals -->
<?php foreach($recentNotices as $n): ?>
<div class="modal fade" id="noticeModal<?php echo $n['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-info-circle-fill text-primary me-2"></i> Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="mb-3 pb-3 border-bottom">
                    <h6 class="fw-bold mb-2" style="color: var(--text-primary); font-size: 1.1rem; line-height: 1.4;"><?php echo htmlspecialchars($n['subject']); ?></h6>
                    <div class="d-flex align-items-center gap-3 text-secondary" style="font-size: 0.85rem;">
                        <span><i class="bi bi-calendar3 me-1"></i> <?php echo date('F d, Y', strtotime($n['notice_date'])); ?></span>
                        <span><i class="bi bi-person me-1"></i> <?php echo htmlspecialchars($n['posted_by_name']); ?></span>
                    </div>
                </div>
                <div class="notice-content" style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6; white-space: pre-wrap;"><?php echo htmlspecialchars($n['content']); ?></div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
