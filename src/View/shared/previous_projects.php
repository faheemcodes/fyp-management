<!-- Student Previous Projects View -->
<style>
/* ─── Hero overrides ─── */
.page-hero-icon i {
    font-size: 2rem;
}

/* ─── Filters & Search ─── */
.filter-bar {
    background: var(--card-bg);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 1rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.project-card {
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}
.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.project-abstract-snippet {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;  
    overflow: hidden;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* ─── Modern Switch ─── */
.modern-switch .form-check-input {
    width: 2.6em;
    height: 1.3em;
    margin-top: 0.15em;
    cursor: pointer;
    background-color: var(--border-color);
    border: none;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    transition: background-position 0.3s cubic-bezier(0.4, 0.0, 0.2, 1), background-color 0.3s ease, border-color 0.3s ease !important;
}
.modern-switch .form-check-input:checked {
    background-color: #475569; /* Sleek Slate Gray */
    border-color: #475569;
}
.modern-switch .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(71, 85, 105, 0.25);
}
.modern-switch .form-check-label {
    padding-left: 0.4rem;
    padding-top: 0.1rem;
    cursor: pointer;
    font-size: 0.9rem;
}
</style>

<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.15); color: #fff;">
                <i class="bi bi-archive"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1" style="font-size: 1.25rem; letter-spacing: -0.02em">Previous Projects</h4>
                <p class="mb-0" style="font-size: 0.85rem; color: rgba(255,255,255,0.7)">Explore successful projects completed by past batches</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="filter-bar">
    <div class="row g-3 align-items-center">
        <div class="col-md-4">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--text-muted);"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by title or keyword..." style="background: var(--form-bg); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 8px; padding-left: 2.5rem; box-shadow: none;">
            </div>
        </div>
        <div class="col-md-3">
            <select id="batchFilter" class="form-select" style="background: var(--form-bg); border: 1px solid var(--border-color); color: var(--text-primary);">
                <option value="">All Batches</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?php echo htmlspecialchars($batch); ?>"><?php echo htmlspecialchars($batch); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select id="supervisorFilter" class="form-select" style="background: var(--form-bg); border: 1px solid var(--border-color); color: var(--text-primary);">
                <option value="">All Supervisors</option>
                <?php foreach ($supervisors as $supervisor): ?>
                    <option value="<?php echo htmlspecialchars($supervisor); ?>"><?php echo htmlspecialchars($supervisor); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 text-end">
            <span id="resultCount" class="text-muted small fw-bold"><?php echo count($projects); ?> Projects</span>
        </div>
        <?php if ($role === 'supervisor'): ?>
        <div class="col-12 mt-3 pt-3 border-top">
            <div class="form-check form-switch modern-switch d-flex align-items-center">
                <input class="form-check-input" type="checkbox" id="myProjectsToggle" checked>
                <label class="form-check-label text-muted fw-medium" for="myProjectsToggle">View only my supervised projects</label>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Projects Grid -->
<div class="row g-4" id="projectsGrid">
    <?php if (empty($projects)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-folder-x fs-1 text-muted text-opacity-50 d-block mb-3"></i>
            <h5 class="text-muted">No Previous Projects Found</h5>
            <p class="text-muted small">There are currently no completed projects in the archive.</p>
        </div>
    <?php else: ?>
        <?php foreach ($projects as $proj): ?>
            <div class="col-md-6 col-lg-4 project-item" data-batch="<?php echo htmlspecialchars($proj['batch_name']); ?>" data-supervisor="<?php echo htmlspecialchars($proj['supervisor_name']); ?>" data-title="<?php echo htmlspecialchars(strtolower($proj['title'])); ?>">
                <div class="card project-card border-0 p-4">
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <span class="badge" style="background: rgba(59,130,246,0.1); color: #3b82f6;"><i class="bi bi-clock-history me-1"></i> <?php echo htmlspecialchars($proj['batch_name']); ?></span>
                        <span class="badge" style="background: rgba(139,92,246,0.1); color: #8b5cf6;"><i class="bi bi-person-badge-fill me-1"></i> <?php echo htmlspecialchars($proj['supervisor_name'] ?? 'N/A'); ?></span>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-primary); font-size: 1.1rem; line-height: 1.4;">
                        <?php echo htmlspecialchars($proj['title']); ?>
                    </h5>
                    <p class="project-abstract-snippet mb-4">
                        <?php echo htmlspecialchars($proj['abstract']); ?>
                    </p>
                    
                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <div class="text-muted small text-truncate" style="max-width: 65%;" title="<?php echo htmlspecialchars($proj['team_members'] ?? 'N/A'); ?>">
                            <i class="bi bi-people-fill me-1"></i> <?php echo htmlspecialchars($proj['team_members'] ?? 'N/A'); ?>
                        </div>
                        <button class="btn btn-sm btn-light border fw-bold text-primary" onclick="viewProjectDetails(<?php echo htmlspecialchars(json_encode([
                            'title' => $proj['title'],
                            'abstract' => nl2br(htmlspecialchars($proj['abstract'])),
                            'batch' => $proj['batch_name'],
                            'supervisor' => $proj['supervisor_name'],
                            'team' => $proj['team_members'],
                            'thesis' => $proj['thesis_file'] ?? null
                        ])); ?>)">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Project Details Modal -->
<div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3" style="background: var(--card-bg);">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: var(--text-primary);">Project Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-4" style="background: var(--bg-color);">
                
                <div class="d-flex flex-wrap gap-2 mb-4 justify-content-between align-items-center">
                    <div>
                        <span class="badge" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.8rem; padding: 0.5rem 0.75rem;"><i class="bi bi-clock-history me-1"></i> <span id="modalBatch">Batch</span></span>
                        <span class="badge" style="background: rgba(139,92,246,0.1); color: #8b5cf6; font-size: 0.8rem; padding: 0.5rem 0.75rem;"><i class="bi bi-person-badge-fill me-1"></i> Sup. <span id="modalSupervisor">Supervisor</span></span>
                    </div>
                    <div id="modalThesisBtnContainer"></div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Abstract</h6>
                    <div class="p-4 rounded border" style="background: var(--card-bg); color: var(--text-primary); line-height: 1.6;" id="modalAbstract">
                        Full abstract goes here.
                    </div>
                </div>

                <div>
                    <h6 class="fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Team Members</h6>
                    <div class="p-3 rounded border d-flex align-items-center" style="background: var(--card-bg); color: var(--text-primary);">
                        <i class="bi bi-people-fill text-muted fs-4 me-3"></i>
                        <span id="modalTeam" class="fw-medium">Team members list</span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
// Filter Logic
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const batchFilter = document.getElementById('batchFilter');
    const supervisorFilter = document.getElementById('supervisorFilter');
    const resultCount = document.getElementById('resultCount');
    const projectItems = document.querySelectorAll('.project-item');

    const myProjectsToggle = document.getElementById('myProjectsToggle');
    const currentUserName = <?php echo json_encode($currentUserName ?? ''); ?>;

    function filterProjects() {
        const query = searchInput.value.toLowerCase();
        const batch = batchFilter.value;
        const supervisor = supervisorFilter.value;
        const myProjectsOnly = myProjectsToggle ? myProjectsToggle.checked : false;
        let visibleCount = 0;

        projectItems.forEach(item => {
            const itemBatch = item.getAttribute('data-batch');
            const itemSupervisor = item.getAttribute('data-supervisor');
            const itemTitle = item.getAttribute('data-title');
            
            // Abstract is also searchable
            const itemAbstract = item.querySelector('.project-abstract-snippet').textContent.toLowerCase();
            const searchableText = itemTitle + " " + itemAbstract;

            const matchesSearch = searchableText.includes(query);
            const matchesBatch = batch === "" || itemBatch === batch;
            const matchesSupervisor = supervisor === "" || itemSupervisor === supervisor;
            
            let matchesToggle = true;
            if (myProjectsOnly && currentUserName) {
                matchesToggle = (itemSupervisor === currentUserName);
            }

            if (matchesSearch && matchesBatch && matchesSupervisor && matchesToggle) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        resultCount.textContent = visibleCount + (visibleCount === 1 ? ' Project' : ' Projects');
    }

    if (searchInput) searchInput.addEventListener('keyup', filterProjects);
    if (batchFilter) batchFilter.addEventListener('change', filterProjects);
    if (supervisorFilter) supervisorFilter.addEventListener('change', filterProjects);
    if (myProjectsToggle) {
        myProjectsToggle.addEventListener('change', function() {
            // If toggle is ON, optionally disable the supervisor dropdown, or just let them work together
            if (this.checked) {
                supervisorFilter.value = "";
                supervisorFilter.disabled = true;
            } else {
                supervisorFilter.disabled = false;
            }
            filterProjects();
        });
        
        // Init state
        if (myProjectsToggle.checked) {
            supervisorFilter.disabled = true;
        }
    }
    
    // Initial run
    filterProjects();
});

// Modal Logic
function viewProjectDetails(data) {
    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalBatch').textContent = data.batch || 'Unknown Batch';
    document.getElementById('modalSupervisor').textContent = data.supervisor || 'Unknown Supervisor';
    document.getElementById('modalAbstract').innerHTML = data.abstract || 'No abstract provided.';
    document.getElementById('modalTeam').textContent = data.team || 'No team members listed.';
    
    var thesisBtnContainer = document.getElementById('modalThesisBtnContainer');
    if (data.thesis) {
        thesisBtnContainer.innerHTML = `<button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="viewThesisOffcanvas('${data.thesis}')"><i class="bi bi-file-earmark-pdf-fill me-2"></i>View Thesis Document</button>`;
    } else {
        thesisBtnContainer.innerHTML = '';
    }
    
    var modal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
    modal.show();
}

</script>

<?php include __DIR__ . '/thesis_offcanvas.php'; ?>
