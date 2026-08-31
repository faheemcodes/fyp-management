<!-- HOD Department FYP Projects Explorer -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<!-- Top Hero Banner -->
<div class="page-hero">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-kanban-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em">Department Projects</h4>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.82rem; letter-spacing: 0.02em;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($department ?? 'Software Engineering', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.75); font-size: 0.85rem">All registered project groups and milestones</p>
            </div>
        </div>
        <div class="text-white text-end d-none d-md-block">
            <div class="fs-4 fw-bold"><?php echo count($projects); ?></div>
            <div class="small opacity-75">Active Groups</div>
        </div>
    </div>
</div>

<div class="page-section">
    <div class="page-section-header">
        <div class="row g-3 align-items-center w-100 m-0">
            <!-- Search Input -->
            <div class="col-md-5 ps-0">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-0 table-search shadow-none" placeholder="Search projects, supervisors, groups..." data-target="department-projects-table">
                </div>
            </div>
            <!-- Stage Filter Pills -->
            <div class="col-md-7 pe-0 d-flex justify-content-md-end gap-1.5 flex-wrap">
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold active" onclick="filterProjects('all', this)">All</button>
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold" onclick="filterProjects('proposal', this)">Proposal</button>
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold" onclick="filterProjects('defense', this)">Defense</button>
                <button class="btn btn-sm btn-filter-pill rounded-pill px-3 fw-semibold" onclick="filterProjects('final', this)">Final</button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table modern-table m-0" id="department-projects-table">
            <thead>
                <tr>
                    <th class="ps-4">Group &amp; Title</th>
                    <th>Supervisor</th>
                    <th>Team Members</th>
                    <th>Milestone</th>
                    <th class="text-end pe-4">Documents</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($projects as $p): ?>
                <?php 
                    $stage = $p['progress_stage'] ?? 'Group Created';
                    $stageCategory = 'proposal';
                    if (str_contains($stage, 'Defence') || str_contains($stage, 'Defense')) {
                        $stageCategory = 'defense';
                    } elseif (str_contains($stage, 'Final') || str_contains($stage, 'Grading')) {
                        $stageCategory = 'final';
                    }
                ?>
                <tr data-stage-cat="<?php echo $stageCategory; ?>">
                    <td class="ps-4">
                        <div class="d-flex flex-column" style="max-width: 320px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge font-monospace px-2 py-0.5" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.25); font-size: 0.72rem;">
                                    <?php echo htmlspecialchars($p['group_code'] ?? 'PENDING', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <small class="text-muted" style="font-size: 0.7rem;"><?php echo date('M Y', strtotime($p['created_at'])); ?></small>
                            </div>
                            <div class="fw-bold text-truncate" title="<?php echo htmlspecialchars($p['project_title'] ?? 'Title pending', ENT_QUOTES, 'UTF-8'); ?>" style="color: var(--text-primary); font-size: 0.92rem;">
                                <?php echo htmlspecialchars($p['project_title'] ?? 'Project Title Pending Submission', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php if (!empty($p['abstract'])): ?>
                            <small class="text-muted text-truncate" style="font-size: 0.78rem;" title="<?php echo htmlspecialchars($p['abstract'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($p['abstract'], ENT_QUOTES, 'UTF-8'); ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($p['supervisor_name'])): ?>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem">
                                <?php echo strtoupper(substr($p['supervisor_name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;"><?php echo htmlspecialchars($p['supervisor_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted" style="font-size: 0.72rem;"><?php echo htmlspecialchars($p['supervisor_designation'] ?? 'Faculty', ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </div>
                        <?php else: ?>
                        <span class="badge border px-2 py-1 small" style="background: var(--form-bg); color: var(--text-secondary); border-color: var(--border-color) !important;">Not Assigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <?php foreach(($p['members'] ?? []) as $m): ?>
                            <?php $mAvatar = !empty($m['avatar']) ? $m['avatar'] : 'default_avatar.svg'; ?>
                            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($mAvatar, ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="rounded-circle border shadow-2xs" 
                                 style="width: 32px; height: 32px; object-fit: cover; cursor: pointer;" 
                                 alt="<?php echo htmlspecialchars($m['student_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                 title="<?php echo htmlspecialchars($m['student_name'] . ' (' . $m['roll_no'] . ')', ENT_QUOTES, 'UTF-8'); ?>"
                                 onclick="showStudentPhotoModal('<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($mAvatar, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($m['student_name']), ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars(addslashes($m['roll_no']), ENT_QUOTES, 'UTF-8'); ?>')">
                            <?php endforeach; ?>
                            <?php if (empty($p['members'])): ?>
                            <span class="text-muted small">No members</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge border rounded-pill px-2.5 py-1" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; border-color: rgba(59, 130, 246, 0.25) !important; font-size: 0.75rem;">
                            <?php echo htmlspecialchars($p['progress_stage'] ?? 'Proposal Stage', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1.5">
                            <?php if (!empty($p['proposal_file'])): ?>
                            <?php 
                                $propUrl = trim($p['proposal_file']);
                                if (!str_contains($propUrl, 'uploads/')) {
                                    $propUrl = '/uploads/proposals/' . ltrim($propUrl, '/');
                                }
                                if (!str_starts_with($propUrl, '/')) {
                                    $propUrl = '/' . $propUrl;
                                }
                                $finalPropUrl = ($basePath ? rtrim($basePath, '/') : '') . $propUrl;
                            ?>
                            <a href="<?php echo htmlspecialchars($finalPropUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-sm rounded-pill px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.25); font-size: 0.75rem;" title="View Proposal Document">
                                <i class="bi bi-file-earmark-pdf-fill"></i> <span>Proposal</span>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($p['thesis_file'])): ?>
                            <?php 
                                $thUrl = trim($p['thesis_file']);
                                if (!str_contains($thUrl, 'uploads/')) {
                                    $thUrl = '/uploads/thesis/' . ltrim($thUrl, '/');
                                }
                                if (!str_starts_with($thUrl, '/')) {
                                    $thUrl = '/' . $thUrl;
                                }
                                $finalThUrl = ($basePath ? rtrim($basePath, '/') : '') . $thUrl;
                            ?>
                            <a href="<?php echo htmlspecialchars($finalThUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-sm rounded-pill px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 0.75rem;" title="Download Thesis Document">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i> <span>Thesis</span>
                            </a>
                            <?php endif; ?>
                            <?php if (empty($p['proposal_file']) && empty($p['thesis_file'])): ?>
                            <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-folder2-open fs-2 d-block mb-2 opacity-50"></i>
                        No FYP projects registered yet.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Student Avatar Preview Modal -->
<div class="modal fade" id="studentPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg text-center" style="border-radius: 20px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-body p-4 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-flex flex-column align-items-center mt-2">
                    <img id="modalStudentPhoto" src="" class="rounded-circle shadow mb-3" style="width: 130px; height: 130px; object-fit: cover; border: 4px solid var(--form-bg);" alt="Student Photo">
                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);" id="modalStudentName"></h6>
                    <span class="badge border font-monospace mt-1 px-2.5 py-1" style="background: var(--form-bg); color: var(--text-secondary);" id="modalStudentRoll"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showStudentPhotoModal(src, name, roll) {
    document.getElementById('modalStudentPhoto').src = src;
    document.getElementById('modalStudentName').innerText = name;
    document.getElementById('modalStudentRoll').innerText = roll;
    new bootstrap.Modal(document.getElementById('studentPhotoModal')).show();
}

function filterProjects(category, btn) {
    if (btn) {
        document.querySelectorAll('.btn-filter-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    const rows = document.querySelectorAll('#department-projects-table tbody tr');
    rows.forEach(row => {
        const cat = row.getAttribute('data-stage-cat');
        if (category === 'all' || cat === category) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
