<!-- Coordinator Cumulative Evaluation Sheet View -->
<?php
$title = "Cumulative Evaluation Sheet - Coordinator Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$selectedBatchId = $selectedBatchId ?? 0;
$selectedShift = $selectedShift ?? 'all';
$selectedBatchName = $selectedBatchName ?? 'All Batches';
$department = $department ?? 'Software Engineering';
$coordinatorShift = $coordinatorShift ?? 'Morning';
?>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative z-1">
        <!-- Left: Icon & Titles -->
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon">
                <i class="bi bi-file-earmark-ruled-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2">
                    Cumulative Evaluation &amp; Grading Sheet
                </h4>
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap justify-content-center justify-content-md-start">
                    <span class="badge rounded-pill px-3 py-1.5 text-nowrap d-inline-flex align-items-center" style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.95); font-size: 0.76rem; font-weight: 600; gap: 7px;">
                        <i class="bi bi-mortarboard-fill" style="font-size: 0.82rem;"></i>
                        <span>Batch: <?php echo htmlspecialchars($selectedBatchName, ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                    <?php if ($allMarksPublished): ?>
                        <span class="badge rounded-pill px-3 py-1.5 text-nowrap d-inline-flex align-items-center" style="background: rgba(16,185,129,0.3); color: #a7f3d0; font-size: 0.76rem; font-weight: 600; border: 1px solid rgba(16,185,129,0.4); gap: 7px;">
                            <i class="bi bi-eye-fill" style="font-size: 0.82rem;"></i>
                            <span>Marks Published to Students</span>
                        </span>
                    <?php else: ?>
                        <span class="badge rounded-pill px-3 py-1.5 text-nowrap d-inline-flex align-items-center" style="background: rgba(245,158,11,0.25); color: #fde68a; font-size: 0.76rem; font-weight: 600; border: 1px solid rgba(245,158,11,0.35); gap: 7px;">
                            <i class="bi bi-eye-slash-fill" style="font-size: 0.82rem;"></i>
                            <span>Marks Draft Mode</span>
                        </span>
                    <?php endif; ?>
                </div>
                <p class="mb-0 mt-2" style="color: rgba(255,255,255,0.78); font-size: 0.84rem">
                    Official <?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift evaluation records across Proposal Defence (40), Progress (40), Supervision (45), and Final Presentation (75) &bull; Total 200 Marks
                </p>
            </div>
        </div>

        <!-- Right: Action Buttons -->
        <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-end">
            <!-- Publish / Hide Marks Button -->
            <button type="button" class="btn btn-sm btn-warning rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#publishMarksModal" style="font-size: 0.85rem;">
                <i class="bi bi-shield-lock-fill"></i> <span>Publish / Hide Marks</span>
            </button>

            <!-- Print Cumulative Sheet (Same tab) -->
            <a href="<?php echo $basePath; ?>/coordinator/cumulative-sheet/print?batch_id=<?php echo (int)$selectedBatchId; ?>" class="btn btn-sm btn-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="color: #0f172a; font-size: 0.85rem;">
                <i class="bi bi-printer-fill text-primary"></i> <span>Print Sheet</span>
            </a>

            <!-- Return to Presentation Sheets -->
            <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets" class="btn btn-sm btn-outline-light rounded-pill px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border: 1.5px solid rgba(255,255,255,0.35); font-size: 0.85rem;">
                <i class="bi bi-collection-fill"></i> <span>Presentation Sheets</span>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════ KPI Summary Cards (Standard Portal Cards) ═══════════════ -->
<div class="row g-3 mb-4">
    <!-- Total Students -->
    <div class="col-sm-6 col-xl-3">
        <div class="card premium-stat-card premium-card-blue h-100">
            <div class="premium-card-accent"></div>
            <div class="d-flex align-items-center gap-3 position-relative z-1">
                <div class="premium-card-icon premium-icon-blue">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="premium-card-count"><?php echo (int)$totalStudents; ?></div>
                    <div class="premium-card-label">Total Students (<?php echo (int)$totalGroups; ?> Groups)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Passed Students -->
    <div class="col-sm-6 col-xl-3">
        <div class="card premium-stat-card premium-card-green h-100">
            <div class="premium-card-accent"></div>
            <div class="d-flex align-items-center gap-3 position-relative z-1">
                <div class="premium-card-icon premium-icon-green">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="premium-card-count text-success"><?php echo (int)$passedCount; ?></div>
                    <div class="premium-card-label">Passed (<?php echo $totalStudents > 0 ? round(($passedCount / $totalStudents) * 100, 1) : 0; ?>% Rate)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Average -->
    <div class="col-sm-6 col-xl-3">
        <div class="card premium-stat-card premium-card-amber h-100">
            <div class="premium-card-accent"></div>
            <div class="d-flex align-items-center gap-3 position-relative z-1">
                <div class="premium-card-icon premium-icon-amber">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="premium-card-count"><?php echo (int)round((float)$avgScore); ?> <small class="fs-6 fw-normal text-muted">/ 200</small></div>
                    <div class="premium-card-label">Batch Average (<?php echo (int)round(((float)$avgScore / 200.0) * 100); ?>%)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Marks Published -->
    <div class="col-sm-6 col-xl-3">
        <div class="card premium-stat-card <?php echo $allMarksPublished ? 'premium-card-green' : 'premium-card-purple'; ?> h-100">
            <div class="premium-card-accent"></div>
            <div class="d-flex align-items-center gap-3 position-relative z-1">
                <div class="premium-card-icon <?php echo $allMarksPublished ? 'premium-icon-green' : 'premium-icon-purple'; ?>">
                    <i class="bi <?php echo $allMarksPublished ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?>"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="premium-card-count <?php echo $allMarksPublished ? 'text-success' : ''; ?>">
                        <?php echo (int)$publishedEvalsCount; ?> <small class="fs-6 fw-normal text-muted">/ <?php echo (int)$totalEvalsOverall; ?></small>
                    </div>
                    <div class="premium-card-label"><?php echo $allMarksPublished ? 'Marks Published' : 'Draft Mode (Hidden)'; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Notice Banner ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: rgba(59, 130, 246, 0.05); border-left: 4px solid #3b82f6 !important;">
    <div class="d-flex gap-3 align-items-center">
        <div class="p-2 rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.15); color: #2563eb; flex-shrink: 0;">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
        <div class="small" style="font-size: 0.84rem; color: var(--text-secondary, #475569); line-height: 1.5;">
            <strong style="color: var(--text-primary, #1e293b);">Coordinator Authority on Marks Visibility:</strong> 
            Only the Coordinator has administrative authority to publish or hide final student marks. Committee member access to publish marks has been locked. 
            <strong>Student Project Comments:</strong> Feedback and remarks written by committee members are directly and automatically visible to students to guide their project revisions.
        </div>
    </div>
</div>

<!-- ═══════════════ Filter and Search Controls ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: var(--card-bg, #ffffff);">
    <form method="GET" action="<?php echo $basePath; ?>/coordinator/cumulative-sheet" id="filterForm">
        <div class="row g-3 align-items-center">
            <!-- Academic Batch Filter -->
            <div class="col-md-3">
                <label class="form-label text-muted small fw-semibold mb-1" style="font-size: 0.76rem;">Academic Batch</label>
                <select name="batch_id" class="form-select form-select-sm rounded-pill" onchange="document.getElementById('filterForm').submit()">
                    <option value="0" <?php echo $selectedBatchId === 0 ? 'selected' : ''; ?>>All Batches</option>
                    <?php foreach ($batches as $b): ?>
                        <option value="<?php echo (int)$b['id']; ?>" <?php echo $selectedBatchId == $b['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?> <?php echo $b['is_active'] ? '(Active)' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Coordinator Shift Indicator -->
            <div class="col-md-2">
                <label class="form-label text-muted small fw-semibold mb-1" style="font-size: 0.76rem;">Coordinator Shift</label>
                <?php if ($coordinatorShift === 'All'): ?>
                    <select name="shift" class="form-select form-select-sm rounded-pill" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?php echo $selectedShift === 'all' ? 'selected' : ''; ?>>All Shifts</option>
                        <option value="Morning" <?php echo $selectedShift === 'Morning' ? 'selected' : ''; ?>>Morning</option>
                        <option value="Evening" <?php echo $selectedShift === 'Evening' ? 'selected' : ''; ?>>Evening</option>
                    </select>
                <?php else: ?>
                    <div class="form-control form-control-sm rounded-pill bg-light border d-flex align-items-center gap-2 fw-bold text-dark" style="font-size: 0.82rem; height: 31px;">
                        <i class="bi <?php echo $coordinatorShift === 'Evening' ? 'bi-moon-stars-fill text-purple' : 'bi-sun-fill text-warning'; ?>"></i>
                        <span><?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift</span>
                    </div>
                    <input type="hidden" name="shift" value="<?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
            </div>

            <!-- Grade Filter (Client-side) -->
            <div class="col-md-2">
                <label class="form-label text-muted small fw-semibold mb-1" style="font-size: 0.76rem;">Filter by Grade</label>
                <select id="gradeFilter" class="form-select form-select-sm rounded-pill">
                    <option value="all">All Grades</option>
                    <option value="A+">A+ (85%+)</option>
                    <option value="A">A (80%-84%)</option>
                    <option value="B+">B+ (75%-79%)</option>
                    <option value="B">B (70%-74%)</option>
                    <option value="C+">C+ (65%-69%)</option>
                    <option value="C">C (60%-64%)</option>
                    <option value="D+">D+ (55%-59%)</option>
                    <option value="D">D (50%-54%)</option>
                    <option value="F">F (&lt; 50%)</option>
                </select>
            </div>

            <!-- Search Filter -->
            <div class="col-md-5">
                <label class="form-label text-muted small fw-semibold mb-1" style="font-size: 0.76rem;">Live Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text rounded-start-pill bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="liveSearchInput" class="form-control rounded-end-pill border-start-0" placeholder="Search Roll No, Student Name, Group, Supervisor...">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ═══════════════ Cumulative Marks Table ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4" style="background: var(--card-bg, #ffffff);">
    <div class="page-section-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="page-section-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                <i class="bi bi-table"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0" style="color: var(--text-primary); font-size: 0.95rem;">Student Performance Records</h6>
                <small class="text-muted" style="font-size: 0.78rem;">
                    Showing <span id="visibleRowCount"><?php echo count($studentsList); ?></span> of <?php echo count($studentsList); ?> students
                </small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="resetFiltersBtn">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 modern-table" id="cumulativeTable" style="font-size: 0.85rem;">
            <thead style="background: var(--form-bg, #f8fafc); border-bottom: 1px solid var(--border-color, #e2e8f0);">
                <tr class="text-uppercase fw-bold" style="font-size: 0.74rem; letter-spacing: 0.04em; color: var(--text-secondary, #64748b);">
                    <th class="ps-4 text-center" style="width: 45px;">#</th>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Group / Project</th>
                    <th>Supervisor</th>
                    <th class="text-center" style="width: 80px;" title="Proposal Defence (Max 40)">Prop.<br><small class="text-muted">(40)</small></th>
                    <th class="text-center" style="width: 80px;" title="FYP Progress Presentation (Max 40)">Prog.<br><small class="text-muted">(40)</small></th>
                    <th class="text-center" style="width: 80px;" title="Supervisor Evaluation (Max 45)">Sup.<br><small class="text-muted">(45)</small></th>
                    <th class="text-center" style="width: 80px;" title="Final Presentation (Max 75)">Final<br><small class="text-muted">(75)</small></th>
                    <th class="text-center fw-bolder text-primary" style="width: 85px;" title="Total Marks (Max 200)">Total<br><small>(200)</small></th>
                    <th class="text-center" style="width: 65px;">%</th>
                    <th class="text-center" style="width: 65px;">Grade</th>
                    <th class="text-center" style="width: 80px;">Status</th>
                    <th class="text-center pe-4" style="width: 120px;">Visibility</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($studentsList)): ?>
                    <tr>
                        <td colspan="14" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            No approved student projects or marks found for this batch/shift.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $serial = 1;
                    foreach ($studentsList as $s): 
                        $prop = $s['proposal_defense_marks'] !== null ? (float)$s['proposal_defense_marks'] : null;
                        $prog = $s['progress_presentation_marks'] !== null ? (float)$s['progress_presentation_marks'] : null;
                        $sup  = $s['supervision_marks'] !== null ? (float)$s['supervision_marks'] : null;
                        $fin  = $s['final_presentation_marks'] !== null ? (float)$s['final_presentation_marks'] : null;

                        $hasAny = ($prop !== null || $prog !== null || $sup !== null || $fin !== null);
                        $tot = $s['total_marks'] !== null ? (int)round((float)$s['total_marks']) : ($hasAny ? (int)round(($prop ?? 0) + ($prog ?? 0) + ($sup ?? 0) + ($fin ?? 0)) : null);
                        $pct = $s['percentage'] !== null ? (int)round((float)$s['percentage']) : ($tot !== null ? (int)round(($tot / 200.0) * 100.0) : null);

                        $grade = $s['grade'] ?? null;
                        if (!$grade && $pct !== null) {
                            if ($pct >= 85) $grade = 'A+';
                            else if ($pct >= 80) $grade = 'A';
                            else if ($pct >= 75) $grade = 'B+';
                            else if ($pct >= 70) $grade = 'B';
                            else if ($pct >= 65) $grade = 'C+';
                            else if ($pct >= 60) $grade = 'C';
                            else if ($pct >= 55) $grade = 'D+';
                            else if ($pct >= 50) $grade = 'D';
                            else $grade = 'F';
                        }
                        if (!$grade) $grade = '-';

                        $passFail = $s['pass_fail_status'] ?? null;
                        if (!$passFail && $pct !== null) {
                            $passFail = ($pct >= 50) ? 'Pass' : 'Fail';
                        }
                        if (!$passFail) $passFail = '-';

                        $gradeBadgeClass = 'bg-secondary-subtle text-secondary';
                        if (in_array($grade, ['A+', 'A'])) $gradeBadgeClass = 'bg-success text-white';
                        else if (in_array($grade, ['B+', 'B'])) $gradeBadgeClass = 'bg-primary text-white';
                        else if (in_array($grade, ['C+', 'C'])) $gradeBadgeClass = 'bg-info text-dark';
                        else if (in_array($grade, ['D+', 'D'])) $gradeBadgeClass = 'bg-warning text-dark';
                        else if ($grade === 'F') $gradeBadgeClass = 'bg-danger text-white';

                        $isPublished = !empty($s['is_published']);
                    ?>
                        <tr class="student-row" 
                            data-roll="<?php echo strtolower(htmlspecialchars($s['roll_no'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-name="<?php echo strtolower(htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-group="<?php echo strtolower(htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-title="<?php echo strtolower(htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-supervisor="<?php echo strtolower(htmlspecialchars($s['supervisor_name'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-grade="<?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?>"
                            data-status="<?php echo htmlspecialchars($passFail, ENT_QUOTES, 'UTF-8'); ?>">
                            
                            <td class="ps-4 text-center text-muted fw-semibold" style="font-size: 0.78rem;"><?php echo $serial++; ?></td>
                            <td class="fw-bold" style="color: var(--text-primary); font-family: monospace; font-size: 0.88rem;">
                                <?php echo htmlspecialchars($s['roll_no'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: var(--text-primary);"><?php echo htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted" style="font-size: 0.74rem;"><?php echo htmlspecialchars($s['shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 mb-1 font-monospace" style="font-size: 0.74rem;">
                                    <?php echo htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <div class="text-truncate text-secondary" style="max-width: 220px; font-size: 0.78rem;" title="<?php echo htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($s['project_title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td>
                                <span class="text-truncate d-block" style="max-width: 140px; font-size: 0.8rem;" title="<?php echo htmlspecialchars($s['supervisor_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($s['supervisor_name'] ?? 'Unassigned', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="text-center font-monospace"><?php echo $prop !== null ? (int)round($prop) : '<span class="text-muted">-</span>'; ?></td>
                            <td class="text-center font-monospace"><?php echo $prog !== null ? (int)round($prog) : '<span class="text-muted">-</span>'; ?></td>
                            <td class="text-center font-monospace"><?php echo $sup !== null ? (int)round($sup) : '<span class="text-muted">-</span>'; ?></td>
                            <td class="text-center font-monospace"><?php echo $fin !== null ? (int)round($fin) : '<span class="text-muted">-</span>'; ?></td>
                            <td class="text-center fw-bold font-monospace" style="color: #10b981; font-size: 0.95rem;">
                                <?php echo $tot !== null ? (int)round($tot) : '<span class="text-muted">-</span>'; ?>
                            </td>
                            <td class="text-center font-monospace fw-semibold"><?php echo $pct !== null ? (int)round($pct) . '%' : '<span class="text-muted">-</span>'; ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill px-2.5 py-1 <?php echo $gradeBadgeClass; ?>" style="font-size: 0.75rem;">
                                    <?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($passFail === 'Pass'): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.74rem;">
                                        Pass
                                    </span>
                                <?php elseif ($passFail === 'Fail'): ?>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size: 0.74rem;">
                                        Fail
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.78rem;">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <?php if ($isPublished): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.72rem;" title="Marks visible to student">
                                        <i class="bi bi-eye-fill me-1"></i> Visible
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 text-dark" style="font-size: 0.72rem;" title="Marks hidden from student">
                                        <i class="bi bi-eye-slash-fill me-1"></i> Hidden
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════ Modal: Marks Visibility Control ═══════════════ -->
<div class="modal fade" id="publishMarksModal" tabindex="-1" aria-labelledby="publishMarksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: var(--card-bg, #ffffff);">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-warning fs-5"></i>
                    <h5 class="modal-title fw-bold m-0" id="publishMarksModalLabel" style="font-size: 1.05rem;">Manage Student Marks Visibility</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?php echo $basePath; ?>/coordinator/cumulative-sheet/toggle-visibility" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="batch_id" value="<?php echo (int)$selectedBatchId; ?>">

                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 mb-3" style="background: rgba(16, 185, 129, 0.08); border-left: 3px solid #10b981;">
                        <div class="small fw-semibold text-dark mb-1">
                            <i class="bi bi-shield-check text-success me-1"></i> Coordinator-Exclusive Control
                        </div>
                        <div class="small text-muted" style="font-size: 0.8rem; line-height: 1.5;">
                            Only coordinators have authorization to publish marks. Committee member access to publish marks has been locked. 
                            <strong>Note:</strong> Committee comments and remarks remain accessible to students continuously to support their academic revisions.
                        </div>
                    </div>

                    <!-- Action Type: Publish or Hide -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.04em;">Select Action</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="action" id="actionPublish" value="publish" checked>
                                <label class="btn btn-outline-success w-100 py-2.5 rounded-3 d-flex flex-column align-items-center gap-1" for="actionPublish">
                                    <i class="bi bi-eye-fill fs-5"></i>
                                    <span class="fw-bold" style="font-size: 0.82rem;">Publish Marks</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Make visible to students</small>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="action" id="actionHide" value="hide">
                                <label class="btn btn-outline-danger w-100 py-2.5 rounded-3 d-flex flex-column align-items-center gap-1" for="actionHide">
                                    <i class="bi bi-eye-slash-fill fs-5"></i>
                                    <span class="fw-bold" style="font-size: 0.82rem;">Hide Marks</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">Keep marks in draft</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Stage Scope -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.04em;">Component / Stage</label>
                        <select name="stage" class="form-select form-select-sm rounded-3">
                            <option value="all" selected>All Components &amp; Supervision (Complete FYP)</option>
                            <option value="Proposal Defence Presentation">Proposal Defence Presentation (40 Marks)</option>
                            <option value="FYP Progress Presentation">FYP Progress Presentation (40 Marks)</option>
                            <option value="Final Presentation">Final Presentation (75 Marks)</option>
                            <option value="supervision">Supervision Marks (45 Marks)</option>
                        </select>
                    </div>

                    <!-- Target Scope Info -->
                    <div class="p-2.5 rounded-3 bg-light border text-muted small" style="font-size: 0.78rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Target: <strong>Batch <?php echo htmlspecialchars($selectedBatchName, ENT_QUOTES, 'UTF-8'); ?></strong> 
                        (<strong><?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift</strong>).
                    </div>
                </div>

                <div class="modal-footer border-top py-3 px-4 d-flex justify-content-end gap-2 bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold">Apply Visibility</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════ Client-Side Filter & Search Script ═══════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const gradeFilter = document.getElementById('gradeFilter');
    const resetBtn = document.getElementById('resetFiltersBtn');
    const rows = document.querySelectorAll('.student-row');
    const rowCountSpan = document.getElementById('visibleRowCount');

    function applyFilters() {
        const query = (searchInput.value || '').trim().toLowerCase();
        const selectedGrade = gradeFilter.value;
        let visibleCount = 0;

        rows.forEach(function(row) {
            const roll = row.dataset.roll || '';
            const name = row.dataset.name || '';
            const grp = row.dataset.group || '';
            const title = row.dataset.title || '';
            const sup = row.dataset.supervisor || '';
            const grade = row.dataset.grade || '';

            const matchesSearch = !query || 
                roll.includes(query) || 
                name.includes(query) || 
                grp.includes(query) || 
                title.includes(query) || 
                sup.includes(query);

            const matchesGrade = (selectedGrade === 'all') || (grade === selectedGrade);

            if (matchesSearch && matchesGrade) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (rowCountSpan) {
            rowCountSpan.textContent = visibleCount;
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    if (gradeFilter) {
        gradeFilter.addEventListener('change', applyFilters);
    }
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (gradeFilter) gradeFilter.value = 'all';
            applyFilters();
        });
    }
});
</script>
