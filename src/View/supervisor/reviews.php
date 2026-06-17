<!-- Supervisor Review Documents & Proposals View -->
<div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
    <div class="mb-4">
        <h4 class="fw-bold text-dark m-0">Project Reviews & Feedback Hub</h4>
        <p class="text-muted m-0 small">Review group proposals and provide feedback on milestone documents</p>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs border-bottom mb-4" id="reviewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="proposals-tab" data-bs-toggle="tab" data-bs-target="#proposals-pane" type="button" role="tab" aria-controls="proposals-pane" aria-selected="true">
                <i class="bi bi-file-earmark-plus me-1"></i> Project Proposals (<?php echo count(array_filter($proposals, function($p) { return $p['status'] === 'Submitted'; })); ?> Pending)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-pane" type="button" role="tab" aria-controls="documents-pane" aria-selected="false">
                <i class="bi bi-folder-check me-1"></i> Milestone Documents (<?php echo count(array_filter($documents, function($d) { return $d['feedback'] === null; })); ?> Pending)
            </button>
        </li>
    </ul>

    <!-- Tab Panes -->
    <div class="tab-content" id="reviewTabsContent">
        <!-- Proposals Tab Pane -->
        <div class="tab-pane fade show active" id="proposals-pane" role="tabpanel" aria-labelledby="proposals-tab" tabindex="0">
            <div class="table-responsive">
                <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Group Code</th>
                            <th>Project Title</th>
                            <th>Team Members</th>
                            <th>Abstract</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($proposals as $pr): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($pr['group_code']); ?></td>
                            <td class="fw-semibold text-dark"><?php echo htmlspecialchars($pr['project_title']); ?></td>
                            <td>
                                <ul class="list-unstyled m-0 small">
                                    <?php foreach($pr['members'] as $m): ?>
                                        <li class="text-nowrap mb-1">
                                            <i class="bi bi-person-fill text-muted me-1"></i>
                                            <strong><?php echo htmlspecialchars($m['name']); ?></strong>
                                            <span class="text-muted font-monospace" style="font-size: 0.75rem;">(<?php echo htmlspecialchars($m['student_id']); ?>)</span>
                                            <?php if($m['user_id'] == $pr['created_by']): ?>
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.55rem;">Leader</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <div class="text-wrap small text-muted" style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?php echo htmlspecialchars($pr['abstract']); ?>
                                </div>
                                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?><?php echo htmlspecialchars($pr['file_path']); ?>" target="_blank" class="small text-decoration-none d-inline-block mt-1"><i class="bi bi-file-earmark-arrow-down-fill me-1"></i>Download File</a>
                            </td>
                            <td>
                                <?php if($pr['status'] === 'Approved'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">Approved</span>
                                <?php elseif($pr['status'] === 'Submitted'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small animate-pulse">Submitted</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 small"><?php echo htmlspecialchars($pr['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#proposalModal<?php echo $pr['id']; ?>">
                                    Review
                                </button>
                            </td>
                        </tr>

                        <!-- Proposal Review Modal -->
                        <div class="modal fade" id="proposalModal<?php echo $pr['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 rounded-3">
                                    <div class="modal-header bg-dark text-white border-0 py-2.5">
                                        <h6 class="modal-title fw-bold">Review Proposal - <?php echo htmlspecialchars($pr['group_code']); ?></h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/supervisor/proposal/action" method="POST">
                                        <div class="modal-body p-3 text-start">
                                            <input type="hidden" name="proposal_id" value="<?php echo $pr['id']; ?>">
                                            
                                            <!-- Team Members Display inside Modal -->
                                            <div class="mb-3 bg-light p-3 rounded-3 border">
                                                <h6 class="fw-bold small text-secondary text-uppercase mb-2"><i class="bi bi-people-fill text-primary me-1"></i> Proposed Team Members</h6>
                                                <ul class="list-unstyled m-0 small">
                                                    <?php foreach($pr['members'] as $m): ?>
                                                        <li class="mb-1">
                                                            <i class="bi bi-person-fill text-muted me-1"></i>
                                                            <strong><?php echo htmlspecialchars($m['name']); ?></strong> 
                                                            <span class="text-muted font-monospace">(<?php echo htmlspecialchars($m['student_id']); ?>)</span>
                                                            <?php if($m['user_id'] == $pr['created_by']): ?>
                                                                <span class="badge bg-primary-subtle text-primary ms-1" style="font-size: 0.55rem;">Leader</span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">Review Decision</label>
                                                <select class="form-select bg-light" name="status" required>
                                                    <option value="Approved" <?php echo $pr['status'] === 'Approved' ? 'selected' : ''; ?>>Approve Proposal</option>
                                                    <option value="Revision Requested" <?php echo $pr['status'] === 'Revision Requested' ? 'selected' : ''; ?>>Request Revision</option>
                                                    <option value="Rejected" <?php echo $pr['status'] === 'Rejected' ? 'selected' : ''; ?>>Reject Proposal</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">Feedback Remarks</label>
                                                <textarea class="form-control bg-light" name="feedback" rows="4" placeholder="Enter comments or revision notes..." required><?php echo htmlspecialchars($pr['feedback'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                            <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($proposals)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No project proposals have been uploaded by your groups yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documents Tab Pane -->
        <div class="tab-pane fade" id="documents-pane" role="tabpanel" aria-labelledby="documents-tab" tabindex="0">
            <div class="table-responsive">
                <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none;">
                    <thead>
                        <tr>
                            <th>Group Code</th>
                            <th>Uploader Details</th>
                            <th>Milestone / Type</th>
                            <th>Document File</th>
                            <th>Feedback Remarks</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($documents as $doc): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($doc['group_code']); ?></td>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($doc['student_name']); ?></div>
                                <small class="text-muted"><?php echo date('M d, Y', strtotime($doc['uploaded_at'])); ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small"><?php echo htmlspecialchars($doc['stage']); ?></div>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;"><?php echo htmlspecialchars($doc['doc_type']); ?></span>
                            </td>
                            <td>
                                <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?><?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="btn btn-link p-0 text-decoration-none small fw-semibold">
                                    <i class="bi bi-cloud-arrow-down-fill me-1"></i>Download
                                </a>
                            </td>
                            <td>
                                <?php if($doc['feedback']): ?>
                                    <div class="small text-success bg-success-subtle p-2 rounded-2 border border-success-subtle text-wrap" style="max-width: 250px;">
                                        <strong>Comment:</strong> <?php echo htmlspecialchars($doc['feedback']); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small animate-pulse">Awaiting Review</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#docModal<?php echo $doc['id']; ?>">
                                    Feedback
                                </button>
                            </td>
                        </tr>

                        <!-- Document Feedback Modal -->
                        <div class="modal fade" id="docModal<?php echo $doc['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-3 shadow">
                                    <div class="modal-header bg-dark text-white border-0 py-2.5">
                                        <h6 class="modal-title fw-bold">Feedback Comments - <?php echo htmlspecialchars($doc['doc_type']); ?></h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/supervisor/document/feedback" method="POST">
                                        <div class="modal-body p-3 text-start">
                                            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">Comments or Feedback Notes</label>
                                                <textarea class="form-control bg-light" name="feedback" rows="4" placeholder="Enter critique, remarks, or updates required..." required><?php echo htmlspecialchars($doc['feedback'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-2 bg-light rounded-bottom text-end">
                                            <button type="button" class="btn btn-secondary rounded-pill btn-xs px-3" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill btn-xs px-3">Save Feedback</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($documents)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No milestone documents have been uploaded by your groups yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
