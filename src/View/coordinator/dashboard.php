<!-- Coordinator Dashboard View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
$fullName = trim($_SESSION['name'] ?? 'Coordinator');
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
    border-radius: 50%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: 0 4px 15px rgba(59,130,246,0.15);
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

<!-- ── Top Hero Banner ── -->
<div class="group-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="group-hero-icon" style="background: transparent;">
                <img src="<?php echo $basePath; ?>/images/logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <p class="mb-1" style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35);">
                    Department Coordinator
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2;">
                    Welcome back, <?php echo htmlspecialchars($firstName); ?>
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 justify-content-center justify-content-md-start flex-wrap">
                    <span style="font-size: 0.75rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                        <?php echo htmlspecialchars($department); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap hero-stats-container">
            <a href="<?php echo $bp; ?>/coordinator/users" class="text-decoration-none">
                <div class="group-stat-pill" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span class="stat-num" style="color: <?php echo $stats['pending_approvals'] > 0 ? '#f59e0b' : 'var(--text-secondary)'; ?>;"><?php echo $stats['pending_approvals']; ?></span>
                    <span class="stat-label text-white">Pending Approvals</span>
                </div>
            </a>
            <div class="group-stat-pill">
                <span class="stat-num text-success"><?php echo $stats['total_students']; ?></span>
                <span class="stat-label">Active Students</span>
            </div>
            <div class="group-stat-pill" style="margin-right: 0;">
                <span class="stat-num text-info"><?php echo $stats['total_notices']; ?></span>
                <span class="stat-label">Notices Generated</span>
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
