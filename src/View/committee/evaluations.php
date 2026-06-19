<!-- Committee Evaluations View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <div class="mb-4">
        <h4 class="fw-bold text-dark m-0">Group Presentations & Evaluations Panel</h4>
        <p class="text-muted m-0 small">Enter presentation evaluation marks and manage student visibility</p>
    </div>

    <!-- Search & Global Toggle -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 border-bottom pb-3">
        <div class="input-group" style="max-width: 380px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control table-search" placeholder="Search presentations by group code, supervisor..." data-target="evals-table">
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
        <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/toggle-visibility" method="POST" class="m-0">
            <input type="hidden" name="show" value="<?php echo $globalShowAction; ?>">
            <button type="submit" class="btn btn-sm <?php echo $globalShowAction ? 'btn-outline-primary' : 'btn-success text-white'; ?> rounded-pill px-4 py-2 fw-semibold shadow-sm">
                <i class="bi <?php echo $globalShowAction ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?> me-2"></i>
                <?php echo $globalShowAction ? 'Show All Marks to Students' : 'Hide All Marks from Students'; ?>
            </button>
        </form>
    </div>

    <!-- Desktop Table -->
    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle border-0 m-0" id="evals-table">
            <thead>
                <tr>
                    <th>Group Code</th>
                    <th>Project Details</th>
                    <th>Proposal Defence Presentation</th>
                    <th>FYP Progress Presentation</th>
                    <th>Final Presentation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $g): ?>
                <tr>
                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($g['group_code']); ?></td>
                    <td>
                        <div class="fw-semibold text-dark text-wrap mb-1" style="max-width: 200px;"><?php echo htmlspecialchars($g['project_title'] ?? 'No Project Title'); ?></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Supervisor: <?php echo htmlspecialchars($g['supervisor_name'] ?? 'Unassigned'); ?></small>
                        <button class="btn btn-link btn-sm p-0 text-decoration-none mt-1 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#abstractModal<?php echo $g['id']; ?>" style="font-size: 0.72rem; font-weight: 600; color: #2E5BFF;">
                            <i class="bi bi-file-text-fill"></i> View Abstract
                        </button>
                    </td>
                    
                    <!-- 1. Proposal Defence Presentation -->
                    <td>
                        <?php if ($g['proposal_defense'] && $g['proposal_defense']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['proposal_defense']['total_marks'], 0); ?>/30</div>
                                <div class="x-small text-muted text-wrap" style="max-width: 150px;">Remarks: <?php echo htmlspecialchars($g['proposal_defense']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-1.5">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-primary rounded-pill px-3 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Proposal">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                    
                    <!-- 2. FYP Progress Presentation -->
                    <td>
                        <?php if ($g['progress_eval'] && $g['progress_eval']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['progress_eval']['total_marks'], 0); ?>/40</div>
                                <div class="x-small text-muted text-wrap" style="max-width: 150px;">Remarks: <?php echo htmlspecialchars($g['progress_eval']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-1.5">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-primary rounded-pill px-3 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Progress">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- 3. Final Presentation -->
                    <td>
                        <?php if ($g['final_presentation'] && $g['final_presentation']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['final_presentation']['total_marks'], 0); ?>/75</div>
                                <div class="x-small text-muted text-wrap" style="max-width: 150px;">Remarks: <?php echo htmlspecialchars($g['final_presentation']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-1.5">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-primary rounded-pill px-3 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Final">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No project groups registered in the platform yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card List -->
    <div class="d-block d-md-none" id="evals-table-mobile">
        <?php foreach($groups as $g): ?>
            <div class="card border rounded-3 p-3 mb-3 bg-light-subtle shadow-xs border">
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                    <span class="fw-bold text-primary"><?php echo htmlspecialchars($g['group_code']); ?></span>
                    <button class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#abstractModal<?php echo $g['id']; ?>" style="font-size: 0.72rem; font-weight: 600; color: #2E5BFF;">
                        <i class="bi bi-file-text-fill"></i> View Abstract
                    </button>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;"><?php echo htmlspecialchars($g['project_title'] ?? 'No Project Title'); ?></h6>
                    <small class="text-muted"><i class="bi bi-person-badge me-1"></i>Supervisor: <?php echo htmlspecialchars($g['supervisor_name'] ?? 'Unassigned'); ?></small>
                </div>
                
                <div class="row g-2 pt-2 border-top">
                    <!-- Proposal Defence -->
                    <div class="col-12 mb-2">
                        <span class="text-secondary fw-semibold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase;">Proposal Defence (30)</span>
                        <?php if ($g['proposal_defense'] && $g['proposal_defense']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['proposal_defense']['total_marks'], 0); ?>/30</div>
                                <div class="x-small text-muted text-wrap">Remarks: <?php echo htmlspecialchars($g['proposal_defense']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded-3 border">
                                <span class="x-small text-muted">Not graded</span>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-primary rounded-pill px-3 py-1" style="font-size: 0.65rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Proposal">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- FYP Progress -->
                    <div class="col-12 mb-2">
                        <span class="text-secondary fw-semibold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase;">FYP Progress (40)</span>
                        <?php if ($g['progress_eval'] && $g['progress_eval']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['progress_eval']['total_marks'], 0); ?>/40</div>
                                <div class="x-small text-muted text-wrap">Remarks: <?php echo htmlspecialchars($g['progress_eval']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded-3 border">
                                <span class="x-small text-muted">Not graded</span>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-primary rounded-pill px-3 py-1" style="font-size: 0.65rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Progress">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Final Presentation -->
                    <div class="col-12 mb-1">
                        <span class="text-secondary fw-semibold d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase;">Final Presentation (75)</span>
                        <?php if ($g['final_presentation'] && $g['final_presentation']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['final_presentation']['total_marks'], 0); ?>/75</div>
                                <div class="x-small text-muted text-wrap">Remarks: <?php echo htmlspecialchars($g['final_presentation']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded-3 border">
                                <span class="x-small text-muted">Not graded</span>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-primary rounded-pill px-3 py-1" style="font-size: 0.65rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Final">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($groups)): ?>
            <div class="text-center text-muted py-4 bg-light rounded-3 small">No project groups registered in the platform yet.</div>
        <?php endif; ?>
    </div>
</div>

<!-- GRADING & ABSTRACT MODALS FOR ALL GROUPS -->
<?php foreach($groups as $g): ?>
    
    <!-- 1. Proposal Defence Modal -->
    <div class="modal fade" id="gradeModal<?php echo $g['id']; ?>Proposal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-3 shadow">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold">Proposal Defence Marks - <?php echo htmlspecialchars($g['group_code']); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/grade" method="POST">
                    <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                    <input type="hidden" name="stage" value="Proposal Defence Presentation">
                    <div class="modal-body p-4 text-start">
                        <!-- Collapsible Project Abstract -->
                        <div class="mb-3">
                            <button class="btn btn-sm btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center rounded-3 px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#abstractCollapse<?php echo $g['id']; ?>Proposal" aria-expanded="false" style="font-size: 0.8rem; font-weight: 600;">
                                <span><i class="bi bi-file-text-fill me-1"></i> View Project Abstract</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <div class="collapse mt-2" id="abstractCollapse<?php echo $g['id']; ?>Proposal">
                                <div class="p-3 bg-light rounded border-start border-primary border-3 small text-justify text-muted" style="max-height: 180px; overflow-y: auto; font-size: 0.78rem; line-height: 1.45;">
                                    <?php echo nl2br(htmlspecialchars($g['proposal_abstract'] ?? 'No abstract/summary submitted yet.')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Problem Identification & Solution (0-10)</label>
                                <input type="number" class="form-control bg-light" name="problem_solution" min="0" max="10" step="1" required placeholder="8">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Literature & Feasibility (0-10)</label>
                                <input type="number" class="form-control bg-light" name="literature_feasibility" min="0" max="10" step="1" required placeholder="7.5">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Presentation & Viva (0-10)</label>
                            <input type="number" class="form-control bg-light" name="presentation_viva" min="0" max="10" step="1" required placeholder="8">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Remarks / Evaluator Notes</label>
                            <textarea class="form-control bg-light" name="remarks" rows="3" placeholder="Enter session notes or specific suggestions..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light rounded-bottom text-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Submit Marks</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. FYP Progress Modal -->
    <div class="modal fade" id="gradeModal<?php echo $g['id']; ?>Progress" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-3 shadow">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold">FYP Progress Marks - <?php echo htmlspecialchars($g['group_code']); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/grade" method="POST">
                    <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                    <input type="hidden" name="stage" value="FYP Progress Presentation">
                    <div class="modal-body p-4 text-start">
                        <!-- Collapsible Project Abstract -->
                        <div class="mb-3">
                            <button class="btn btn-sm btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center rounded-3 px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#abstractCollapse<?php echo $g['id']; ?>Progress" aria-expanded="false" style="font-size: 0.8rem; font-weight: 600;">
                                <span><i class="bi bi-file-text-fill me-1"></i> View Project Abstract</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <div class="collapse mt-2" id="abstractCollapse<?php echo $g['id']; ?>Progress">
                                <div class="p-3 bg-light rounded border-start border-primary border-3 small text-justify text-muted" style="max-height: 180px; overflow-y: auto; font-size: 0.78rem; line-height: 1.45;">
                                    <?php echo nl2br(htmlspecialchars($g['proposal_abstract'] ?? 'No abstract/summary submitted yet.')); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Previous Proposal Defence Comments Card -->
                        <div class="p-3 mb-3 bg-warning-subtle text-warning-emphasis rounded border-start border-warning border-3">
                            <span class="fw-bold small text-warning-emphasis d-block mb-1"><i class="bi bi-chat-left-text-fill me-1"></i>Proposal Defence Comments</span>
                            <?php if (!empty($g['proposal_defence_comments'])): ?>
                                <div class="small" style="max-height: 150px; overflow-y: auto; font-size: 0.75rem;">
                                    <?php foreach($g['proposal_defence_comments'] as $comment): ?>
                                        <div class="mb-2 pb-2 border-bottom border-warning-subtle last-no-border" style="line-height: 1.3;">
                                            <strong><?php echo htmlspecialchars($comment['evaluator_name']); ?>:</strong>
                                            <span class="text-muted"><?php echo htmlspecialchars($comment['remarks']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="small text-muted">No comments/remarks from Proposal Defence.</span>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Project Understanding (0-10)</label>
                                <input type="number" class="form-control bg-light" name="understanding" min="0" max="10" step="1" required placeholder="8">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Technical Knowledge (0-10)</label>
                                <input type="number" class="form-control bg-light" name="technical_knowledge" min="0" max="10" step="1" required placeholder="7.5">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Implementation Progress (0-10)</label>
                                <input type="number" class="form-control bg-light" name="implementation_progress" min="0" max="10" step="1" required placeholder="8">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Presentation & Q&A (0-10)</label>
                                <input type="number" class="form-control bg-light" name="presentation_qa" min="0" max="10" step="1" required placeholder="8.5">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Remarks / Evaluator Notes</label>
                            <textarea class="form-control bg-light" name="remarks" rows="3" placeholder="Enter session notes or specific suggestions..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light rounded-bottom text-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Submit Marks</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Final Presentation Modal -->
    <div class="modal fade" id="gradeModal<?php echo $g['id']; ?>Final" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-3 shadow">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold">Final Presentation Marks - <?php echo htmlspecialchars($g['group_code']); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/grade" method="POST">
                    <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                    <input type="hidden" name="stage" value="Final Presentation">
                    <div class="modal-body p-4 text-start">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Project Demo (0-25)</label>
                                <input type="number" class="form-control bg-light" name="project_demo" min="0" max="25" step="1" required placeholder="20">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold text-secondary">Thesis (0-25)</label>
                                <input type="number" class="form-control bg-light" name="thesis" min="0" max="25" step="1" required placeholder="19.5">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Presentation (0-25)</label>
                            <input type="number" class="form-control bg-light" name="presentation" min="0" max="25" step="1" required placeholder="21">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Remarks / Evaluator Notes</label>
                            <textarea class="form-control bg-light" name="remarks" rows="3" placeholder="Enter session notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light rounded-bottom text-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Submit Marks</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. View Abstract Modal -->
    <div class="modal fade" id="abstractModal<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-3 shadow">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold">Project Abstract - <?php echo htmlspecialchars($g['project_title'] ?? 'No Project Title'); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <h6 class="fw-bold text-secondary mb-2 small text-uppercase" style="letter-spacing: 0.5px;">Project Description Summary</h6>
                    <div class="bg-light p-3 rounded text-justify text-muted" style="font-size: 0.85rem; line-height: 1.6; max-height: 400px; overflow-y: auto;">
                        <?php echo nl2br(htmlspecialchars($g['proposal_abstract'] ?? 'No abstract/summary submitted yet.')); ?>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-2 rounded-bottom text-end">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
