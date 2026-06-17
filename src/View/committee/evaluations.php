<!-- Committee Evaluations & Scheduling View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <div class="mb-4">
        <h4 class="fw-bold text-dark m-0">Group Presentations & Scheduling Panel</h4>
        <p class="text-muted m-0 small">Schedule presentation sessions and enter evaluation marks manually</p>
    </div>

    <!-- Search Input -->
    <div class="mb-3">
        <div class="input-group" style="max-width: 380px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control table-search" placeholder="Search presentations by group code, supervisor..." data-target="evals-table">
        </div>
    </div>

    <div class="table-responsive">
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
                    </td>
                    
                    <!-- 1. Proposal Defence Presentation -->
                    <td>
                        <?php if ($g['proposal_defense'] && $g['proposal_defense']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['proposal_defense']['total_marks'], 1); ?>/100</div>
                                <div class="x-small text-muted text-wrap" style="max-width: 150px;">Remarks: <?php echo htmlspecialchars($g['proposal_defense']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-1.5">
                                <?php if($g['proposal_defense'] && $g['proposal_defense']['scheduled_date']): ?>
                                    <div class="x-small text-dark font-monospace mb-1 bg-light p-1 border rounded" style="font-size: 0.7rem;"><i class="bi bi-calendar-event text-warning me-1"></i><?php echo date('M d, Y h:i A', strtotime($g['proposal_defense']['scheduled_date'])); ?></div>
                                <?php endif; ?>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#schedModal<?php echo $g['id']; ?>Proposal">Schedule</button>
                                    <button class="btn btn-xs btn-primary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Proposal">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- 2. FYP Progress Presentation -->
                    <td>
                        <?php if ($g['progress_eval'] && $g['progress_eval']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['progress_eval']['total_marks'], 1); ?>/100</div>
                                <div class="x-small text-muted text-wrap" style="max-width: 150px;">Remarks: <?php echo htmlspecialchars($g['progress_eval']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-1.5">
                                <?php if($g['progress_eval'] && $g['progress_eval']['scheduled_date']): ?>
                                    <div class="x-small text-dark font-monospace mb-1 bg-light p-1 border rounded" style="font-size: 0.7rem;"><i class="bi bi-calendar-event text-warning me-1"></i><?php echo date('M d, Y h:i A', strtotime($g['progress_eval']['scheduled_date'])); ?></div>
                                <?php endif; ?>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#schedModal<?php echo $g['id']; ?>Progress">Schedule</button>
                                    <button class="btn btn-xs btn-primary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Progress">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- 3. Final Presentation -->
                    <td>
                        <?php if ($g['final_presentation'] && $g['final_presentation']['total_marks'] > 0): ?>
                            <div class="p-2 bg-success-subtle text-success border border-success-subtle rounded-3 small">
                                <div class="fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Graded: <?php echo number_format($g['final_presentation']['total_marks'], 1); ?>/100</div>
                                <div class="x-small text-muted text-wrap" style="max-width: 150px;">Remarks: <?php echo htmlspecialchars($g['final_presentation']['remarks'] ?? ''); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-1.5">
                                <?php if($g['final_presentation'] && $g['final_presentation']['scheduled_date']): ?>
                                    <div class="x-small text-dark font-monospace mb-1 bg-light p-1 border rounded" style="font-size: 0.7rem;"><i class="bi bi-calendar-event text-warning me-1"></i><?php echo date('M d, Y h:i A', strtotime($g['final_presentation']['scheduled_date'])); ?></div>
                                <?php endif; ?>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#schedModal<?php echo $g['id']; ?>Final">Schedule</button>
                                    <button class="btn btn-xs btn-primary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g['id']; ?>Final">Grade</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- SCHEDULE MODALS -->
                
                <!-- 1. Proposal Defence -->
                <div class="modal fade" id="schedModal<?php echo $g['id']; ?>Proposal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 rounded-3 shadow">
                            <div class="modal-header bg-dark text-white border-0 py-2.5">
                                <h6 class="modal-title fw-bold">Schedule Proposal Defence</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/schedule" method="POST">
                                <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                                <input type="hidden" name="stage" value="Proposal Defence Presentation">
                                <div class="modal-body p-3 text-start">
                                    <div class="mb-3">
                                        <label class="form-label small text-secondary">Defence Date & Time</label>
                                        <input type="datetime-local" class="form-control bg-light" name="scheduled_date" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                    <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. FYP Progress -->
                <div class="modal fade" id="schedModal<?php echo $g['id']; ?>Progress" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 rounded-3 shadow">
                            <div class="modal-header bg-dark text-white border-0 py-2.5">
                                <h6 class="modal-title fw-bold">Schedule Progress Presentation</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/schedule" method="POST">
                                <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                                <input type="hidden" name="stage" value="FYP Progress Presentation">
                                <div class="modal-body p-3 text-start">
                                    <div class="mb-3">
                                        <label class="form-label small text-secondary">Presentation Date & Time</label>
                                        <input type="datetime-local" class="form-control bg-light" name="scheduled_date" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                    <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 3. Final Presentation -->
                <div class="modal fade" id="schedModal<?php echo $g['id']; ?>Final" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 rounded-3 shadow">
                            <div class="modal-header bg-dark text-white border-0 py-2.5">
                                <h6 class="modal-title fw-bold">Schedule Final Presentation</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/committee/evaluations/schedule" method="POST">
                                <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                                <input type="hidden" name="stage" value="Final Presentation">
                                <div class="modal-body p-3 text-start">
                                    <div class="mb-3">
                                        <label class="form-label small text-secondary">Presentation Date & Time</label>
                                        <input type="datetime-local" class="form-control bg-light" name="scheduled_date" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                    <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- GRADING MODALS -->
                
                <!-- 1. Proposal Defence -->
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
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Problem Identification & Solution (0-10)</label>
                                            <input type="number" class="form-control bg-light" name="problem_solution" min="0" max="10" step="0.5" required placeholder="8">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Literature & Feasibility (0-10)</label>
                                            <input type="number" class="form-control bg-light" name="literature_feasibility" min="0" max="10" step="0.5" required placeholder="7.5">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-secondary">Presentation & Viva (0-10)</label>
                                        <input type="number" class="form-control bg-light" name="presentation_viva" min="0" max="10" step="0.5" required placeholder="8">
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

                <!-- 2. FYP Progress -->
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
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Project Understanding (0-10)</label>
                                            <input type="number" class="form-control bg-light" name="understanding" min="0" max="10" step="0.5" required placeholder="8">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Technical Knowledge (0-10)</label>
                                            <input type="number" class="form-control bg-light" name="technical_knowledge" min="0" max="10" step="0.5" required placeholder="7.5">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Implementation Progress (0-10)</label>
                                            <input type="number" class="form-control bg-light" name="implementation_progress" min="0" max="10" step="0.5" required placeholder="8">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Presentation & Q&A (0-10)</label>
                                            <input type="number" class="form-control bg-light" name="presentation_qa" min="0" max="10" step="0.5" required placeholder="8.5">
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

                <!-- 3. Final Presentation -->
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
                                            <input type="number" class="form-control bg-light" name="project_demo" min="0" max="25" step="0.5" required placeholder="20">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-semibold text-secondary">Thesis (0-25)</label>
                                            <input type="number" class="form-control bg-light" name="thesis" min="0" max="25" step="0.5" required placeholder="19.5">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-secondary">Presentation (0-25)</label>
                                        <input type="number" class="form-control bg-light" name="presentation" min="0" max="25" step="0.5" required placeholder="21">
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
                <?php endforeach; ?>
                <?php if(empty($groups)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No project groups registered in the platform yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
