<style>
/* ─── Section Panel ─── */







/* ─── Modern Table Styles ─── */







@media (max-width: 768px) {
    
    
    
    
}
</style>
<!-- Admin Dashboard View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>


<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero">
    <div class="d-flex flex-column flex-xl-row align-items-center justify-content-between gap-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <!-- Icon -->
            <div class="page-hero-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <!-- Info -->
            <div>
                <p class="mb-1" style="font-size: 0.68rem;font-weight: 600;text-transform: uppercase;letter-spacing: 0.08em;color: rgba(255,255,255,0.35)">
                    System Administration
                </p>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem;letter-spacing: -0.02em;line-height: 1.2">
                    Super Admin Portal
                </h4>
            </div>
        </div>

            </div>
</div>

<!-- -- Premium Stat Cards Row -- -->
<div class="row g-3 mb-4 mt-2">
    <!-- Manage Users Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $basePath; ?>/admin/users" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-blue">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['total_users'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">Manage Users</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- FYP Groups Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $basePath; ?>/admin/groups" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-green">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-green">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="premium-card-count"><?php echo htmlspecialchars((string)($stats['active_projects'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="premium-card-label">FYP Groups</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>



    <!-- Supervisor Slots Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $basePath; ?>/admin/slots" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-amber">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-amber" style="width: 54px; height: 54px; font-size: 1.4rem;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">Supervisor Slots</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">Manage capacity limits</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Deadlines Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $basePath; ?>/admin/deadlines" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-rose">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-rose" style="width: 54px; height: 54px; font-size: 1.4rem;">
                        <i class="bi bi-calendar2-event-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">Deadlines</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">Set project milestones</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Analytics & Reports Card -->
    <div class="col-xl-4 col-sm-6">
        <a href="<?php echo $basePath; ?>/admin/reports" class="text-decoration-none">
            <div class="card premium-stat-card premium-card-indigo">
                <div class="premium-card-accent"></div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="premium-card-icon premium-icon-indigo" style="width: 54px; height: 54px; font-size: 1.4rem;">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-dark fw-bold" style="font-size: 1.1rem; letter-spacing: -0.01em;">Analytics & Reports</div>
                        <div class="text-secondary mt-1" style="font-size: 0.78rem;">View system insights</div>
                    </div>
                    <div class="premium-card-arrow">
                        <i class="bi bi-arrow-right-short"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>



