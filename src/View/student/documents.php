<!-- Student Documents Upload View -->
<?php if (!$group): ?>
    <div class="alert alert-warning rounded-3 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> You must first create or join a group to upload files.
    </div>
<?php else: ?>
    
    <div class="row g-4">
        <!-- Upload Form -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4">Upload Project Documents</h5>
                
                <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/student/documents/upload" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="stage" class="form-label small fw-semibold text-secondary">Project Stage</label>
                        <select class="form-select bg-light" id="stage" name="stage" required>
                            <option value="">-- Choose Stage --</option>
                            <option value="Proposal Defence Presentation">Proposal Defence Presentation Documents</option>
                            <option value="FYP Progress Presentation">FYP Progress Presentation Final Outputs</option>
                            <option value="Final Presentation">Final Presentation/Defense</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="doc_type" class="form-label small fw-semibold text-secondary">Document / Deliverable Type</label>
                        <select class="form-select bg-light" id="doc_type" name="doc_type" required>
                            <option value="">-- Choose Type --</option>
                            <!-- Proposal Defence Options -->
                            <optgroup label="Proposal Defence Deliverables">
                                <option value="SRS">Software Requirements Specification (SRS)</option>
                                <option value="Literature Review">Literature Review Document</option>
                                <option value="UML Diagrams">Design / UML Diagrams</option>
                                <option value="Prototype">Initial Prototype Files (ZIP)</option>
                            </optgroup>
                            <!-- FYP Progress Options -->
                            <optgroup label="FYP Progress Deliverables">
                                <option value="Source Code">Final Source Code (ZIP)</option>
                                <option value="Final Report">Final Project Report (PDF)</option>
                                <option value="User Manual">User Manual</option>
                                <option value="Test Cases">Test Cases Document</option>
                                <option value="Deployment Guide">Deployment Guide</option>
                            </optgroup>
                            <!-- Final Presentation Options -->
                            <optgroup label="Final Presentation Deliverables">
                                <option value="Presentation Slides">Presentation Slides (PPT)</option>
                                <option value="Demo Video">Demo Video Link / File</option>
                                <option value="Other">Other Document</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="doc_file" class="form-label small fw-semibold text-secondary">Upload File (PDF, DOCX, PPTX, or ZIP)</label>
                        <input type="file" class="form-control bg-light" id="doc_file" name="doc_file" required>
                        <div class="form-text small text-muted mt-1">For source code and prototypes, upload a consolidated ZIP file.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Upload Document</button>
                </form>
            </div>
        </div>

        <!-- Documents List Grid -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-4">Uploaded File History</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0 m-0" style="box-shadow: none;">
                        <thead>
                            <tr>
                                <th>File Name / Date</th>
                                <th>Milestone Type</th>
                                <th>Feedback Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($documents as $doc): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 180px;" title="<?php echo htmlspecialchars($doc['original_name']); ?>">
                                        <?php echo htmlspecialchars($doc['original_name']); ?>
                                    </div>
                                    <div class="x-small text-muted mb-1"><?php echo date('M d, Y h:i A', strtotime($doc['uploaded_at'])); ?></div>
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?><?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="btn btn-link p-0 text-decoration-none small text-primary fw-semibold" style="font-size: 0.8rem;">
                                        <i class="bi bi-cloud-arrow-down-fill me-1"></i>Download
                                    </a>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark"><?php echo htmlspecialchars($doc['stage']); ?></div>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;"><?php echo htmlspecialchars($doc['doc_type']); ?></span>
                                </td>
                                <td>
                                    <?php if($doc['feedback']): ?>
                                        <div class="small text-success bg-success-subtle p-2.5 rounded-3 border border-success-subtle" style="max-width: 220px;">
                                            <div class="fw-bold mb-0.5"><i class="bi bi-person-badge-fill me-1"></i>Feedback:</div>
                                            <?php echo htmlspecialchars($doc['feedback']); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small animate-pulse">Awaiting Review</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($documents)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No files uploaded yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
