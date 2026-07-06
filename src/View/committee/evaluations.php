<!-- Student Portal Inspired Evaluations View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
?>

<style>
/* ─── Hero Section ─── */
.eval-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.eval-hero::before {
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
.eval-hero::after {
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
.eval-hero-icon {
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

/* ─── Section Panel ─── */
.eval-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.eval-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
}
.eval-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}
.eval-section-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.eval-section-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}
.eval-section-header small {
    font-size: 0.72rem;
    color: var(--text-secondary);
    margin: 0;
}

/* ─── Table ─── */
.eval-table th {
    background: transparent;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}
.eval-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
}
.eval-table tbody tr:last-child td {
    border-bottom: none;
}
.eval-table tbody tr {
    transition: background-color 0.2s;
}
.eval-table tbody tr:hover {
    background-color: var(--table-hover-bg);
}

/* ─── Badges & Status ─── */
.group-code-badge {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.8rem;
    font-family: monospace;
    display: inline-block;
}
html.dark-theme .group-code-badge { background: rgba(59,130,246,0.15); color: #60a5fa; }

.grade-box {
    background: rgba(16, 185, 129, 0.1);
    border-left: 3px solid #10b981;
    padding: 0.5rem 0.8rem;
    border-radius: 6px;
}
.grade-box-score {
    color: #059669;
    font-weight: 700;
    font-size: 0.85rem;
}
html.dark-theme .grade-box-score { color: #34d399; }
.grade-box-remarks {
    color: #047857;
    font-size: 0.7rem;
    margin-top: 2px;
    max-width: 140px;
}
html.dark-theme .grade-box-remarks { color: #6ee7b7; }

/* Mobile Row */
.eval-mobile-card {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    padding: 1.25rem;
    margin-bottom: 1rem;
}
.eval-mobile-grade {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--card-bg);
    border-radius: 10px;
    margin-bottom: 0.5rem;
    border: 1px solid var(--border-color);
}

/* Modals Override - High z-index */
.modal {
    z-index: 99999 !important;
}
.modal-backdrop {
    z-index: 99998 !important;
}
.eval-modal .modal-content {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.eval-modal .modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
    background: var(--form-bg);
    color: var(--text-primary);
}
.eval-modal .modal-body {
    padding: 1.5rem;
    color: var(--text-primary);
}
.eval-modal .modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1.5rem;
}
.eval-modal .form-control {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    border-radius: 10px;
}
.eval-modal .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* Fix close button visibility in dark theme */
html.dark-theme .modal .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.8;
}
html.dark-theme .modal .btn-close:hover {
    opacity: 1;
}

/* Previous Remarks Alert */
.previous-remarks-alert {
    background: var(--form-bg);
    border-left: 3px solid var(--primary-color);
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
    border-top: 1px solid var(--border-color);
    border-right: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
}
.previous-remarks-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--primary-color);
    text-transform: uppercase;
    margin-bottom: 0.5rem;
    letter-spacing: 0.05em;
}
.previous-remarks-body {
    max-height: 100px;
    overflow-y: auto;
    font-size: 0.8rem;
    color: var(--text-primary);
    line-height: 1.5;
}

/* Minimal Modal & Table Styles */
.eval-modal .modal-content {
    border: none !important;
    border-radius: 12px !important;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.eval-modal-header {
    background: #f8fafc !important;
    border-bottom: 1px solid var(--border-color) !important;
    padding: 16px 20px !important;
}

html.dark-theme .eval-modal-header {
    background: #1e293b !important;
}

.eval-table-wrapper {
    background: var(--card-bg);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.eval-table {
    margin: 0;
    border-collapse: collapse;
    width: 100%;
    min-width: 600px;
}

.eval-table thead th {
    background: var(--form-bg);
    color: var(--text-secondary);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.eval-table tbody tr {
    border-bottom: 1px solid var(--border-color);
}

.eval-table tbody tr:last-child {
    border-bottom: none;
}

.eval-table td {
    padding: 12px 16px;
    vertical-align: middle;
}

.eval-student-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.eval-student-avatar {
    width: 32px;
    height: 32px;
    background: #e2e8f0;
    color: #475569;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

html.dark-theme .eval-student-avatar {
    background: #334155;
    color: #cbd5e1;
}

.eval-student-name {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.85rem;
    line-height: 1.2;
}

.eval-student-roll {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.eval-input {
    background-color: transparent !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding: 6px 8px !important;
    text-align: center;
    font-size: 0.85rem !important;
    color: var(--text-primary) !important;
    width: 100%;
    max-width: 70px;
    margin: 0 auto;
}

html.dark-theme .eval-input {
    border-color: #334155 !important;
}

.eval-input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.1) !important;
    outline: none;
}

.eval-remarks-box {
    margin-top: 16px;
}

.eval-remarks-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.eval-remarks-textarea {
    border-radius: 8px !important;
    border: 1px solid var(--border-color) !important;
    background-color: var(--card-bg) !important;
    padding: 10px 12px !important;
    resize: vertical;
    font-size: 0.85rem;
}
</style>

<!-- Hero Section -->
<div class="eval-hero">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 position-relative" style="z-index: 1;">
        <div class="d-flex align-items-center gap-3">
            <div class="eval-hero-icon shadow-sm">
                <i class="bi bi-clipboard-data"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1" style="font-size: 1.4rem; letter-spacing: -0.02em;">Group Evaluations</h4>
                <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Review project abstracts and submit presentation grades.</p>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            
        </div>
        
        <?php
        $anyHidden = false;
        $hasEvaluations = false;
        foreach ($groups as $g) {
            if ($g['proposal_defense']) {
                $hasEvaluations = true;
                if ($g['proposal_defense']['show_to_student'] == 0) $anyHidden = true;
            }
            if ($g['progress_eval']) {
                $hasEvaluations = true;
                if ($g['progress_eval']['show_to_student'] == 0) $anyHidden = true;
            }
            if ($g['final_presentation']) {
                $hasEvaluations = true;
                if ($g['final_presentation']['show_to_student'] == 0) $anyHidden = true;
            }
        }
        $globalShowAction = ($anyHidden || !$hasEvaluations) ? 1 : 0;
        ?>
        <div class="d-flex gap-2">
            <form action="<?php echo $bp; ?>/committee/evaluations/toggle-visibility" method="POST" class="m-0">
                <input type="hidden" name="show" value="<?php echo $globalShowAction; ?>">
                <button type="submit" class="btn <?php echo $globalShowAction ? 'btn-light text-black' : 'btn-outline-light'; ?> rounded-pill px-4 fw-semibold shadow-sm" style="font-size: 0.85rem;">
                    <i class="bi <?php echo $globalShowAction ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?> me-1"></i>
                    <?php echo $globalShowAction ? 'Publish Marks' : 'Hide Marks'; ?>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Main Table Section -->
<div class="eval-section">
    <div class="eval-section-header flex-column flex-md-row gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="eval-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                <i class="bi bi-card-list"></i>
            </div>
            <div>
                <h6>Evaluation Roster</h6>
                <small>Assigned FYP groups and grading status</small>
            </div>
        </div>
        
        <div class="input-group" style="width: 250px; max-width: 100%;">
            <span class="input-group-text bg-transparent border-end-0 border-light-subtle text-muted" style="border-radius: 50rem 0 0 50rem; padding-left: 1rem;"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control bg-transparent border-start-0 border-light-subtle table-search shadow-none" placeholder="Search groups..." data-target="evals-table" style="border-radius: 0 50rem 50rem 0; font-size: 0.85rem; color: var(--text-primary);">
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="d-none d-md-block table-responsive">
        <table class="table eval-table m-0" id="evals-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Group</th>
                    <th style="width: 25%;">Project Details</th>
                    <th style="width: 20%;">Proposal Defence</th>
                    <th style="width: 20%;">FYP Progress</th>
                    <th style="width: 20%;">Final Presentation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr>
                    <td>
                        <span class="group-code-badge"><?php echo htmlspecialchars($g['group_code']); ?></span>
                    </td>
                    <td>
                        <div class="fw-bold mb-1" style="font-size: 0.9rem; line-height: 1.4; color: var(--text-primary);">
                            <?php echo htmlspecialchars($g['project_title'] ?? 'Untitled Project'); ?>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 8px;">
                            <i class="bi bi-person text-primary me-1"></i>Sup: <?php echo htmlspecialchars($g['supervisor_name'] ?? 'Unassigned'); ?>
                        </div>
                        <button class="btn btn-link text-decoration-none p-0 fw-semibold text-primary" data-bs-toggle="modal" data-bs-target="#abstractModal<?php echo $g['id']; ?>" style="font-size: 0.8rem;">
                            View Abstract
                        </button>
                    </td>
                    
                    <!-- 1. Proposal Defence -->
                    <td>
                        <?php if ($g['proposal_defense'] && $g['proposal_defense']['total_marks'] > 0): ?>
                            <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;"><i class="bi bi-eye me-1"></i>Graded</a>
                        <?php else: ?>
                            <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Evaluate</a>
                        <?php endif; ?>
                    </td>
                    
                    <!-- 2. FYP Progress -->
                    <td>
                        <?php if ($g['progress_eval'] && $g['progress_eval']['total_marks'] > 0): ?>
                            <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;"><i class="bi bi-eye me-1"></i>Graded</a>
                        <?php else: ?>
                            <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Evaluate</a>
                        <?php endif; ?>
                    </td>

                    <!-- 3. Final Presentation -->
                    <td>
                        <?php if ($g['final_presentation'] && $g['final_presentation']['total_marks'] > 0): ?>
                            <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Final Presentation" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;"><i class="bi bi-eye me-1"></i>Graded</a>
                        <?php else: ?>
                            <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Final Presentation" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Evaluate</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i>No project groups available for evaluation yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile View -->
    <div class="d-block d-md-none p-3" id="evals-table-mobile">
        <?php foreach($groups as $g): ?>
            <div class="eval-mobile-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="group-code-badge"><?php echo htmlspecialchars($g['group_code']); ?></span>
                    <button class="btn btn-link p-0 text-primary fw-bold text-decoration-none" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#abstractModal<?php echo $g['id']; ?>">
                        Abstract
                    </button>
                </div>
                <h6 class="fw-bold mb-1" style="font-size: 0.95rem; color: var(--text-primary);"><?php echo htmlspecialchars($g['project_title'] ?? 'Untitled Project'); ?></h6>
                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    <i class="bi bi-person text-primary me-1"></i>Sup: <?php echo htmlspecialchars($g['supervisor_name'] ?? 'Unassigned'); ?>
                </div>

                <div class="eval-mobile-grade">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary);">Proposal (30)</span>
                    <?php if ($g['proposal_defense'] && $g['proposal_defense']['total_marks'] > 0): ?>
                        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Graded</a>
                    <?php else: ?>
                        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Proposal Defence Presentation" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Evaluate</a>
                    <?php endif; ?>
                </div>
                <div class="eval-mobile-grade">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary);">Progress (40)</span>
                    <?php if ($g['progress_eval'] && $g['progress_eval']['total_marks'] > 0): ?>
                        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Graded</a>
                    <?php else: ?>
                        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=FYP Progress Presentation" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Evaluate</a>
                    <?php endif; ?>
                </div>
                <div class="eval-mobile-grade">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary);">Final (25)</span>
                    <?php if ($g['final_presentation'] && $g['final_presentation']['total_marks'] > 0): ?>
                        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Final Presentation" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Graded</a>
                    <?php else: ?>
                        <a href="<?php echo $bp; ?>/committee/grading-sheet?stage=Final Presentation" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">Evaluate</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($groups)): ?>
            <div class="text-center py-4 text-muted" style="font-size: 0.9rem;">No projects assigned.</div>
        <?php endif; ?>
    </div>
</div>

<!-- GRADING MODALS -->
<?php foreach($groups as $g): ?>
    
    <!-- Grading modals removed. Links point to online sheets instead -->

    <!-- 4. View Abstract -->
    <div class="modal fade eval-modal" id="abstractModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" style="font-size: 1.1rem;"><i class="bi bi-file-earmark-text text-primary me-2"></i>Project Abstract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="font-size: 0.95rem; line-height: 1.7; max-height: 60vh; overflow-y: auto; background: var(--form-bg); padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <?php echo nl2br(htmlspecialchars($g['proposal_abstract'] ?? 'No abstract/summary submitted yet.')); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill w-100 fw-semibold" data-bs-dismiss="modal">Close Window</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Fix for Bootstrap Modal Stacking Context Issue -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Move all modals to the body so they aren't trapped by #content's stacking context
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        document.body.appendChild(modal);
    });

    // Search functionality
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            
            // Filter desktop table
            const trs = document.querySelectorAll('#evals-table tbody tr');
            trs.forEach(function(tr) {
                // skip the empty state row
                if(tr.querySelector('td[colspan]')) return;
                
                if (tr.innerText.toLowerCase().includes(term)) {
                    tr.style.display = '';
                } else {
                    tr.style.display = 'none';
                }
            });

            // Filter mobile cards
            const cards = document.querySelectorAll('.eval-mobile-card');
            cards.forEach(function(card) {
                if (card.innerText.toLowerCase().includes(term)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

});
</script>
