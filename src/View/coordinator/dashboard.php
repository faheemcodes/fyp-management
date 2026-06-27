<!-- Coordinator Dashboard View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
$fullName = trim($_SESSION['name'] ?? 'Coordinator');
$fullName = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.|Prof\.|Engr\.|Dr|Mr|Ms|Mrs|Prof|Engr)\s+/i', '', $fullName);
$firstName = explode(' ', $fullName)[0];
?>

<style>
/* ── Welcome banner gradient ── */
.dash-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f2a4a 100%);
    border-radius: var(--border-radius-lg);
    padding: 28px 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.dash-banner::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: radial-gradient(circle, rgba(59,130,246,0.18) 0%, transparent 70%);
}
.dash-banner::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 20%;
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
}

/* ── Stat mini cards ── */
.stat-mini {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    padding: 16px 18px;
    box-shadow: var(--card-shadow);
    height: 100%;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(59,130,246,0.1);
}
.stat-mini-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.stat-mini-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    margin-bottom: 3px;
}
.stat-mini-value {
    font-size: 1.15rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--text-primary);
}

/* ── Modern Table overrides ── */
.modern-table-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}
.modern-table-card .card-header {
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 20px 24px;
}
.modern-table-card .card-header h5 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
}
.modern-table-card .card-header p {
    font-size: 0.78rem;
    color: var(--text-secondary);
    margin: 4px 0 0 0;
}
.modern-table-card table {
    margin: 0;
}
.modern-table-card th {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    background: var(--form-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 14px 24px;
}
.modern-table-card td {
    padding: 16px 24px;
    vertical-align: middle;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--border-color);
}
.modern-table-card tr:last-child td {
    border-bottom: none;
}

/* ── Mobile Specific Overrides ── */
@media (max-width: 767.98px) {
    .dash-banner {
        padding: 24px 20px;
        border-radius: 20px;
    }
    .mobile-scroll-row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 16px;
        padding-bottom: 12px;
        margin-left: -12px;
        margin-right: -12px;
        padding-left: 12px;
        padding-right: 12px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .mobile-scroll-row::-webkit-scrollbar {
        display: none;
    }
    .mobile-scroll-item {
        flex: 0 0 80%;
    }
    .stat-mini {
        padding: 20px;
        border-radius: 16px;
    }
    .modern-table-card {
        border-radius: 20px;
    }
}
</style>

<!-- ── Welcome Banner ── -->
<div class="dash-banner">
    <div class="position-relative" style="z-index: 1;">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <p class="mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.45);">
                    <i class="bi bi-person-badge me-1"></i>Department Coordinator
                </p>
                <h4 class="fw-bold text-white mb-1" style="font-size: 1.35rem; letter-spacing: -0.02em; max-width: 500px;">
                    Welcome back, <?php echo htmlspecialchars($firstName); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                    <span style="font-size: 0.75rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                        <?php echo htmlspecialchars($department); ?>
                    </span>
                </div>
            </div>
            <div class="text-end d-none d-md-block">
                <a href="<?php echo $bp; ?>/coordinator/notice" class="btn text-white mt-2" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 10px 20px; transition: all 0.2s;">
                    <i class="bi bi-megaphone-fill me-2"></i>Create Notice
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ── Stat Mini Cards Row ── -->
<div class="row g-3 mb-4 mobile-scroll-row">
    <!-- Pending Approvals -->
    <div class="col-12 col-md-4 mobile-scroll-item">
        <a href="<?php echo $bp; ?>/coordinator/users" class="text-decoration-none d-block" style="color: inherit;">
            <div class="stat-mini d-flex align-items-center gap-3">
                <div class="stat-mini-icon" style="background: rgba(245,158,11,0.1);">
                    <i class="bi bi-person-fill-exclamation" style="color: #f59e0b;"></i>
                </div>
                <div>
                    <div class="stat-mini-label">Pending Approvals</div>
                    <div class="stat-mini-value" style="color: <?php echo $stats['pending_approvals'] > 0 ? '#f59e0b' : 'var(--text-primary)'; ?>;">
                        <?php echo $stats['pending_approvals']; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Active Students -->
    <div class="col-12 col-md-4 mobile-scroll-item">
        <div class="stat-mini d-flex align-items-center gap-3">
            <div class="stat-mini-icon" style="background: rgba(16,185,129,0.1);">
                <i class="bi bi-people-fill" style="color: #059669;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Active Students in Dept</div>
                <div class="stat-mini-value">
                    <?php echo $stats['total_students']; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices Generated -->
    <div class="col-12 col-md-4 mobile-scroll-item">
        <div class="stat-mini d-flex align-items-center gap-3">
            <div class="stat-mini-icon" style="background: rgba(59,130,246,0.1);">
                <i class="bi bi-megaphone-fill" style="color: #3b82f6;"></i>
            </div>
            <div>
                <div class="stat-mini-label">Notices Generated</div>
                <div class="stat-mini-value text-primary">
                    <?php echo $stats['total_notices']; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Quick Actions Row ── -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="modern-table-card p-3 d-flex align-items-center justify-content-between h-100" style="transition: transform 0.2s; cursor: pointer;" onclick="window.location.href='<?php echo $bp; ?>/coordinator/users'">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 1.4rem; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-primary);">Verify Students</h6>
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">
                        <strong style="color: #f59e0b;"><?php echo $stats['pending_approvals']; ?></strong> pending registrations
                    </p>
                </div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </div>
    </div>

    <div class="col-md-6">
        <div class="modern-table-card p-3 d-flex align-items-center justify-content-between h-100" style="transition: transform 0.2s; cursor: pointer;" onclick="window.location.href='<?php echo $bp; ?>/coordinator/assessment'">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 1.4rem; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color: var(--text-primary);">External Assessment</h6>
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Generate dynamic grading sheets</p>
                </div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Table (Full Width) -->
    <div class="col-12">
        <div class="modern-table-card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5>Recent Notices Generated</h5>
                    <p>Broadcasting timeline changes and instructions for <?php echo htmlspecialchars($department); ?></p>
                </div>
                <a href="<?php echo $bp; ?>/coordinator/notice" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> New Notice
                </a>
            </div>

            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Ref No.</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Target</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentNotices as $n): ?>
                        <tr>
                            <td>
                                <span class="fw-semibold text-secondary" style="font-family: monospace; font-size: 0.8rem; background: var(--form-bg); padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border-color);">
                                    <?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark text-wrap" title="<?php echo htmlspecialchars($n['subject']); ?>" style="max-width: 400px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo htmlspecialchars($n['subject']); ?>
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">
                                    <i class="bi bi-calendar-event me-1"></i><?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.65rem; background: rgba(59,130,246,0.1); color: #2563eb; padding: 4px 10px; border-radius: 20px; font-weight: 700; text-transform: uppercase;">
                                    <?php echo htmlspecialchars($n['target_audience']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?php echo $bp; ?>/notice/view?id=<?php echo $n['id']; ?>" target="_blank" class="btn btn-sm text-primary" style="background: rgba(59,130,246,0.1); border-radius: 8px; font-weight: 600; font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-eye-fill me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($recentNotices)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                                    No notices generated by you yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards View -->
            <div class="d-md-none p-3 pb-4">
                <?php foreach($recentNotices as $n): ?>
                <div class="mb-3 p-3 shadow-sm" style="background: var(--form-bg); border-radius: 16px; border: 1px solid var(--border-color); transition: transform 0.2s;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold" style="font-family: monospace; font-size: 0.75rem; color: var(--text-secondary); background: rgba(0,0,0,0.05); padding: 4px 8px; border-radius: 6px;">
                            <i class="bi bi-hash me-1"></i><?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?>
                        </span>
                        <span style="font-size: 0.65rem; background: rgba(59,130,246,0.1); color: #2563eb; padding: 4px 10px; border-radius: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">
                            <?php echo htmlspecialchars($n['target_audience']); ?>
                        </span>
                    </div>
                    <h6 class="fw-bold mb-3 text-dark lh-base" style="font-size: 0.95rem;">
                        <?php echo htmlspecialchars($n['subject']); ?>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2" style="border-top: 1px solid var(--border-color);">
                        <span class="fw-semibold" style="font-size: 0.75rem; color: var(--text-secondary);">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('M d, Y', strtotime($n['notice_date'])); ?>
                        </span>
                        <a href="<?php echo $bp; ?>/notice/view?id=<?php echo $n['id']; ?>" class="btn btn-sm btn-primary rounded-pill px-4 py-1 fw-bold shadow-sm" style="font-size: 0.75rem;">
                            View
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($recentNotices)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-opacity-25"></i>
                        No notices generated yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Add hover effect for horizontal action cards */
.modern-table-card[onclick]:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 8px 24px rgba(59,130,246,0.15) !important;
}
html.dark-theme .text-dark {
    color: #f8fafc !important;
}
</style>
