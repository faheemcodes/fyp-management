<!-- Coordinator Notice Generator View -->
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-megaphone-fill text-primary me-2"></i>Generate Notice Letter</h5>
            <form action="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/coordinator/notice/create" method="POST">
                
                <div class="mb-3">
                    <label for="ref_no" class="form-label small fw-semibold text-secondary">Reference No. (Optional)</label>
                    <input type="text" class="form-control bg-light" id="ref_no" name="ref_no" placeholder="e.g. SU/FET/CS/2026/089">
                </div>

                <div class="mb-3">
                    <label for="notice_date" class="form-label small fw-semibold text-secondary">Notice Date</label>
                    <input type="date" class="form-control bg-light" id="notice_date" name="notice_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="target_audience" class="form-label small fw-semibold text-secondary">Target Audience</label>
                    <select class="form-select bg-light" id="target_audience" name="target_audience" required>
                        <option value="all">All (Students, HOD & Supervisors)</option>
                        <option value="students">Students Only</option>
                        <option value="supervisors">Supervisors Only</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label small fw-semibold text-secondary">Subject / Title</label>
                    <input type="text" class="form-control bg-light" id="subject" name="subject" placeholder="e.g. FYP-I Initial Proposal Submission Extended" required>
                </div>

                <div class="mb-4">
                    <label for="body" class="form-label small fw-semibold text-secondary">Notice Body Content</label>
                    <textarea class="form-control bg-light" id="body" name="body" rows="6" placeholder="Write notice details, instructions, or rules here..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold"><i class="bi bi-broadcast me-2"></i>Publish & Broadcast Notice</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-4">Broadcasted Notice History</h5>
            
            <div class="table-responsive">
                <table class="table table-hover border-0 align-middle m-0" style="box-shadow: none;">
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
                        <?php foreach($notices as $n): ?>
                        <tr>
                            <td class="font-monospace fw-bold text-secondary"><?php echo htmlspecialchars($n['ref_no'] ?? 'N/A'); ?></td>
                            <td>
                                <div class="fw-semibold text-dark text-wrap mb-1" style="max-width: 220px;">
                                    <?php echo htmlspecialchars($n['subject']); ?>
                                </div>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($n['notice_date'])); ?></td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 text-uppercase" style="font-size: 0.65rem;">
                                    <?php echo htmlspecialchars($n['target_audience']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1.5">
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/notice/view?id=<?php echo $n['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">View Letter</a>
                                    <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); ?>/coordinator/notice/delete?id=<?php echo $n['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle p-1 px-2 shadow-xs" onclick="return confirm('Are you sure you want to delete this notice? This will also remove the notification for users.')">
                                        <i class="bi bi-trash-fill" style="font-size: 0.78rem;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($notices)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No notices broadcasted yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
