<?php
$title = "Cumulative Evaluation Sheet - Super Admin";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batchId = $batchId ?? 0;
$selectedShift = $selectedShift ?? 'all';
$selectedDept = $selectedDept ?? 'Software Engineering';

$totalStudents = count($students ?? []);
$passedCount = 0;
$failedCount = 0;
foreach (($students ?? []) as $st) {
    if (($st['pass_fail_status'] ?? '') === 'Pass' || ($st['grade'] ?? '') !== 'F' && !empty($st['total_marks'])) {
        $passedCount++;
    } else {
        $failedCount++;
    }
}
?>

<style>
#cumulativeTable th {
    padding: 12px 10px !important;
    font-size: 0.74rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.04em;
    color: var(--text-secondary, #64748b) !important;
    background: var(--form-bg, #f8fafc) !important;
    border-bottom: 1.5px solid var(--border-color, #e2e8f0) !important;
    white-space: nowrap;
}
#cumulativeTable td {
    padding: 12px 10px !important;
    vertical-align: middle;
    font-size: 0.86rem !important;
    border-bottom: 1px solid var(--border-color, #f1f5f9) !important;
}
#cumulativeTable tbody tr:hover td {
    background-color: rgba(59, 130, 246, 0.03) !important;
}
.col-serial-num {
    width: 48px;
    text-align: center;
    color: var(--text-secondary, #94a3b8) !important;
    font-size: 0.8rem !important;
}
.col-roll-no {
    font-family: monospace;
    font-size: 0.86rem;
    font-weight: 600;
    white-space: nowrap;
}
.marks-cell {
    text-align: center;
    font-weight: 600;
    font-size: 0.88rem;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.18); color: #ffffff;">
                <i class="bi bi-file-earmark-ruled-fill"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <h4 class="text-white fw-bold m-0" style="font-size: 1.4rem; letter-spacing: -0.02em">Cumulative Marks Sheet</h4>
                    <span class="badge rounded-pill px-3 py-1 fw-bold" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); font-size: 0.8rem;">
                        <?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.8); font-size: 0.85rem">
                    Consolidated grades across Proposal Defence, Progress Presentation, Final Defense, and Supervision
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-center">
            <a href="<?php echo $basePath; ?>/admin/cumulative-sheet/print?department=<?php echo urlencode($selectedDept); ?>&shift=<?php echo urlencode($selectedShift); ?>&batch_id=<?php echo $batchId; ?>" 
               target="_blank" 
               class="btn btn-light rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-printer-fill text-primary"></i> <span>Print Official Sheet</span>
            </a>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: var(--card-bg);">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="<?php echo $basePath; ?>/admin/cumulative-sheet" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">Department</label>
                <select name="department" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <?php foreach ($departments as $d): ?>
                        <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($selectedDept === $d) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($d); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Academic Batch</label>
                <select name="batch_id" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <option value="all" <?php echo ($batchId === 0) ? 'selected' : ''; ?>>All Batches</option>
                    <?php foreach ($batches as $b): ?>
                        <option value="<?php echo $b['id']; ?>" <?php echo ($batchId == $b['id']) ? 'selected' : ''; ?>>
                            Batch <?php echo htmlspecialchars($b['name']); ?> <?php echo !empty($b['is_active']) ? '(Active)' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Shift</label>
                <select name="shift" class="form-select border-0 shadow-sm" style="background: var(--form-bg); border-radius: 10px;" onchange="this.form.submit()">
                    <option value="all" <?php echo ($selectedShift === 'all') ? 'selected' : ''; ?>>All Shifts</option>
                    <option value="Morning" <?php echo ($selectedShift === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                    <option value="Evening" <?php echo ($selectedShift === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <a href="<?php echo $basePath; ?>/admin/cumulative-sheet" class="btn btn-outline-secondary w-100 rounded-pill fw-semibold">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- KPI Stats & Visibility Toggle Row -->
<div class="row g-3 mb-4 align-items-center">
    <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 border text-center shadow-sm" style="background: var(--card-bg);">
            <div class="text-secondary small fw-semibold">Total Students</div>
            <div class="fw-bold fs-4 text-dark"><?php echo $totalStudents; ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 border text-center shadow-sm" style="background: var(--card-bg);">
            <div class="text-secondary small fw-semibold">Passing Candidates</div>
            <div class="fw-bold fs-4 text-success"><?php echo $passedCount; ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 border text-center shadow-sm" style="background: var(--card-bg);">
            <div class="text-secondary small fw-semibold">Needs Improvement / F</div>
            <div class="fw-bold fs-4 text-danger"><?php echo $failedCount; ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 rounded-3 border text-center shadow-sm" style="background: var(--card-bg);">
            <form action="<?php echo $basePath; ?>/admin/cumulative-sheet/toggle-visibility" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                <input type="hidden" name="department" value="<?php echo htmlspecialchars($selectedDept, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="shift" value="<?php echo htmlspecialchars($selectedShift, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="batch_id" value="<?php echo $batchId; ?>">
                <input type="hidden" name="action" value="publish">
                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill w-100 py-2 fw-semibold">
                    <i class="bi bi-eye me-1"></i> Publish Marks to Students
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Cumulative Table Card -->
<div class="card border-0 shadow-sm" style="border-radius: 16px; background: var(--card-bg);">
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="cumulativeTable">
            <thead>
                <tr>
                    <th class="col-serial-num">S.#</th>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Group / Project</th>
                    <th>Supervisor</th>
                    <th class="text-center">Proposal<br><span style="font-size:0.68rem;opacity:0.75">(40)</span></th>
                    <th class="text-center">Progress<br><span style="font-size:0.68rem;opacity:0.75">(40)</span></th>
                    <th class="text-center">Supervision<br><span style="font-size:0.68rem;opacity:0.75">(45)</span></th>
                    <th class="text-center">Final<br><span style="font-size:0.68rem;opacity:0.75">(75)</span></th>
                    <th class="text-center">Total<br><span style="font-size:0.68rem;opacity:0.75">(200)</span></th>
                    <th class="text-center">%</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="13" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x fs-2 d-block mb-2 text-secondary opacity-50"></i>
                            No evaluated students found for the selected department and batch.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $sn = 1;
                    foreach ($students as $s): 
                        $total = !empty($s['total_marks']) ? (float)$s['total_marks'] : 0;
                        $percentage = !empty($s['percentage']) ? (float)$s['percentage'] : 0;
                        $grade = $s['grade'] ?? 'F';
                        $passStatus = $s['pass_fail_status'] ?? 'Fail';
                    ?>
                        <tr>
                            <td class="col-serial-num"><?php echo $sn++; ?></td>
                            <td class="col-roll-no"><?php echo htmlspecialchars($s['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border me-1"><?php echo htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                <div class="text-muted text-truncate" style="font-size: 0.76rem; max-width: 220px;" title="<?php echo htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($s['project_title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td class="small text-secondary"><?php echo htmlspecialchars($s['supervisor_name'] ?? 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="marks-cell"><?php echo isset($s['proposal_defense_marks']) ? round($s['proposal_defense_marks']) : '-'; ?></td>
                            <td class="marks-cell"><?php echo isset($s['progress_presentation_marks']) ? round($s['progress_presentation_marks']) : '-'; ?></td>
                            <td class="marks-cell"><?php echo isset($s['supervision_marks']) ? round($s['supervision_marks']) : '-'; ?></td>
                            <td class="marks-cell"><?php echo isset($s['final_presentation_marks']) ? round($s['final_presentation_marks']) : '-'; ?></td>
                            <td class="marks-cell fw-bold text-primary"><?php echo $total > 0 ? round($total) : '-'; ?></td>
                            <td class="marks-cell"><?php echo $percentage > 0 ? round($percentage, 1) . '%' : '-'; ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?php echo ($grade === 'F' ? 'bg-danger' : 'bg-success'); ?> px-2 py-1" style="font-size: 0.75rem;">
                                    <?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?php echo ($passStatus === 'Pass' ? 'bg-success' : 'bg-secondary'); ?> px-2 py-1" style="font-size: 0.72rem;">
                                    <?php echo htmlspecialchars($passStatus, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
