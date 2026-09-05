<!-- HOD Cumulative Evaluation Sheet View -->
<?php
$title = "Cumulative Marks - HOD Portal";
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$selectedBatchId = $selectedBatchId ?? 0;
$selectedShift = $selectedShift ?? 'all';
$selectedBatchName = $selectedBatchName ?? 'All Batches';
$department = $department ?? 'Software Engineering';
?>

<style>
/* ── Cumulative Table Readability, Spacing & S.No Boldness ── */
#cumulativeTable {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
#cumulativeTable th {
    padding: 13px 10px !important;
    vertical-align: middle;
    font-size: 0.73rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.04em;
    color: var(--text-secondary, #64748b) !important;
    background: var(--form-bg, #f8fafc) !important;
    border-bottom: 1.5px solid var(--border-color, #e2e8f0) !important;
    white-space: nowrap;
}
#cumulativeTable td {
    padding: 13px 10px !important;
    vertical-align: middle;
    font-size: 0.86rem !important;
    border-bottom: 1px solid var(--border-color, #f1f5f9) !important;
}
#cumulativeTable tbody tr {
    transition: background-color 0.15s ease;
}
#cumulativeTable tbody tr:hover td {
    background-color: rgba(4, 127, 176, 0.035) !important;
}

/* S.No column: reduced boldness, light and clear */
.col-serial-num {
    width: 48px;
    text-align: center;
    font-weight: 400 !important;
    color: var(--text-secondary, #94a3b8) !important;
    font-size: 0.8rem !important;
}
th.col-serial-num {
    font-weight: 600 !important;
    color: var(--text-secondary, #64748b) !important;
}

/* Student Name column: generous dedicated space */
.col-student-name {
    min-width: 210px;
    width: 230px;
}
.student-name-text {
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--text-primary, #0f172a);
    line-height: 1.35;
}

/* Roll Number */
.col-roll-no {
    min-width: 110px;
    width: 115px;
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    font-size: 0.86rem;
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    white-space: nowrap;
}

/* Group & Project */
.col-group-project {
    min-width: 190px;
    max-width: 240px;
}
.project-title-sub {
    font-size: 0.77rem;
    color: var(--text-secondary, #64748b);
    line-height: 1.35;
    margin-top: 2px;
}

/* Supervisor */
.col-supervisor {
    min-width: 130px;
    max-width: 160px;
    font-size: 0.82rem;
    color: var(--text-secondary, #475569);
}

/* Numerical Marks Columns */
.col-mark {
    width: 72px;
    text-align: center;
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    font-size: 0.88rem;
    color: var(--text-primary, #1e293b);
}
.col-total-mark {
    width: 84px;
    text-align: center;
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    font-size: 0.94rem;
    font-weight: 800;
    color: #047fb0;
    background: rgba(4, 127, 176, 0.04);
}
.col-pct {
    width: 64px;
    text-align: center;
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    font-size: 0.85rem;
    font-weight: 600;
}
.col-grade {
    width: 68px;
    text-align: center;
}
.col-status {
    width: 82px;
    text-align: center;
}
.col-visibility {
    width: 115px;
    text-align: center;
}

/* Notice Card */
.notice-info-box {
    background: linear-gradient(135deg, rgba(4, 127, 176, 0.08) 0%, rgba(14, 165, 233, 0.05) 100%);
    border-left: 4px solid #047fb0;
    border-radius: 12px;
    padding: 14px 18px;
}
</style>

<!-- ═══════════════ Top Hero Banner ═══════════════ -->
<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 position-relative z-1">
        <!-- Left: Icon, Title & Short Description -->
        <div class="d-flex align-items-center gap-3 text-start">
            <div class="page-hero-icon">
                <i class="bi bi-file-earmark-ruled-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold m-0" style="font-size: 1.35rem; letter-spacing: -0.02em; line-height: 1.2">
                    Cumulative Evaluation &amp; Marks Sheet
                </h4>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.82); font-size: 0.85rem">
                    Official departmental evaluation and grading records across all project stages.
                </p>
            </div>
        </div>

        <!-- Right: Only Total Students Count -->
        <div class="d-flex align-items-center justify-content-center justify-content-md-end">
            <div class="d-flex align-items-center gap-3 px-3.5 py-2 rounded-pill" style="background: rgba(255, 255, 255, 0.14); border: 1px solid rgba(255, 255, 255, 0.22);">
                <i class="bi bi-people-fill text-white fs-5"></i>
                <div class="text-start">
                    <div class="text-white fw-bold lh-1" style="font-size: 1.25rem; font-family: 'SFMono-Regular', Consolas, monospace;">
                        <?php echo (int)$totalStudents; ?>
                    </div>
                    <small style="color: rgba(255, 255, 255, 0.78); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">
                        Total Students
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Filters Toolbar ═══════════════ -->
<div class="card border-0 rounded-4 shadow-sm p-3 mb-4" style="background: var(--card-bg, #ffffff);">
    <form method="GET" action="<?php echo $basePath; ?>/hod/cumulative-sheet" id="filterForm">
        <div class="row g-2 align-items-center">
            <!-- Batch Filter -->
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1" for="batchSelect">
                    <i class="bi bi-mortarboard me-1"></i> Academic Batch
                </label>
                <select name="batch_id" id="batchSelect" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                    <option value="all" <?php echo ($selectedBatchId == 0) ? 'selected' : ''; ?>>All Batches</option>
                    <?php foreach ($batches as $b): ?>
                        <option value="<?php echo (int)$b['id']; ?>" <?php echo ($selectedBatchId == $b['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?> <?php echo $b['is_active'] ? '(Active)' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Shift Filter (All Shifts / Morning / Evening) -->
            <div class="col-12 col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1" for="shiftSelect">
                    <i class="bi bi-clock me-1"></i> Department Shift
                </label>
                <select name="shift" id="shiftSelect" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                    <option value="all" <?php echo ($selectedShift === 'all') ? 'selected' : ''; ?>>All Shifts (Morning &amp; Evening)</option>
                    <option value="Morning" <?php echo ($selectedShift === 'Morning') ? 'selected' : ''; ?>>Morning Shift</option>
                    <option value="Evening" <?php echo ($selectedShift === 'Evening') ? 'selected' : ''; ?>>Evening Shift</option>
                </select>
            </div>

            <!-- Grade / Status Filter (Client-side) -->
            <div class="col-12 col-md-2">
                <label class="form-label small fw-bold text-secondary mb-1" for="statusFilter">
                    <i class="bi bi-funnel me-1"></i> Release Status
                </label>
                <select id="statusFilter" class="form-select form-select-sm rounded-pill">
                    <option value="all">All Records</option>
                    <option value="released">Released Only</option>
                    <option value="draft">Draft Only</option>
                    <option value="pass">Passed (Released)</option>
                    <option value="fail">Failed (Released)</option>
                </select>
            </div>

            <!-- Search Filter (Client-side) -->
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1" for="liveSearchInput">
                    <i class="bi bi-search me-1"></i> Quick Search
                </label>
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
    <div class="page-section-header d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="page-section-icon" style="background: rgba(4, 127, 176, 0.12); color: #047fb0; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-table"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0" style="color: var(--text-primary); font-size: 0.95rem;">Student Performance &amp; Evaluation Marks</h6>
                <small class="text-muted" style="font-size: 0.78rem;">
                    Showing <span id="visibleRowCount"><?php echo count($studentsList); ?></span> of <?php echo count($studentsList); ?> students
                </small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo $basePath; ?>/hod/cumulative-sheet/print?batch_id=<?php echo (int)$selectedBatchId; ?>&shift=<?php echo urlencode($selectedShift); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                <i class="bi bi-printer-fill"></i> <span>Print Sheet</span>
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5" id="resetFiltersBtn" style="font-size: 0.8rem;">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 modern-table" id="cumulativeTable" style="font-size: 0.85rem;">
            <thead style="background: var(--form-bg, #f8fafc); border-bottom: 1.5px solid var(--border-color, #e2e8f0);">
                <tr class="text-uppercase" style="font-size: 0.73rem; letter-spacing: 0.04em; color: var(--text-secondary, #64748b);">
                    <th class="ps-3 text-center col-serial-num">#</th>
                    <th class="col-roll-no">Roll No</th>
                    <th class="col-student-name">Student Name</th>
                    <th class="col-group-project">Group / Project</th>
                    <th class="col-supervisor">Supervisor</th>
                    <th class="col-mark" title="Proposal Defence (Max 40)">Prop.<br><small class="text-muted fw-normal">(40)</small></th>
                    <th class="col-mark" title="FYP Progress Presentation (Max 40)">Prog.<br><small class="text-muted fw-normal">(40)</small></th>
                    <th class="col-mark" title="Supervisor Evaluation (Max 45)">Sup.<br><small class="text-muted fw-normal">(45)</small></th>
                    <th class="col-mark" title="Final Presentation (Max 75)">Final<br><small class="text-muted fw-normal">(75)</small></th>
                    <th class="col-total-mark" title="Total Marks (Max 200)">Total<br><small class="fw-normal">(200)</small></th>
                    <th class="col-pct">%</th>
                    <th class="col-grade">Grade</th>
                    <th class="col-status">Status</th>
                    <th class="col-visibility pe-3">Coordinator Release</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($studentsList)): ?>
                    <tr>
                        <td colspan="14" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            No approved student projects or marks found for this batch and shift filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $serial = 1;
                    foreach ($studentsList as $s): 
                        $isFullyReleased = !empty($s['is_fully_released']);
                        $hasAnyDraft = !empty($s['has_any_draft']);

                        // Visible marks (only set if released by coordinator)
                        $visProp = $s['vis_prop'];
                        $visProg = $s['vis_prog'];
                        $visSup  = $s['vis_sup'];
                        $visFin  = $s['vis_fin'];

                        // If fully released, calculate official total, pct, grade, and pass/fail
                        if ($isFullyReleased) {
                            $tot = $s['total_marks'] !== null ? (int)round((float)$s['total_marks']) : (int)round(($visProp ?? 0) + ($visProg ?? 0) + ($visSup ?? 0) + ($visFin ?? 0));
                            $pct = $s['percentage'] !== null ? (int)round((float)$s['percentage']) : (int)round(($tot / 200.0) * 100.0);

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
                        } else {
                            // Partially released or completely draft
                            $releasedSum = ($visProp ?? 0) + ($visProg ?? 0) + ($visSup ?? 0) + ($visFin ?? 0);
                            $tot = ($releasedSum > 0) ? $releasedSum : null;
                            $pct = null;
                            $grade = 'Draft';
                            $passFail = 'Pending';
                        }

                        $gradeBadgeClass = 'bg-secondary-subtle text-secondary';
                        if ($isFullyReleased) {
                            if (in_array($grade, ['A+', 'A'])) $gradeBadgeClass = 'bg-success text-white';
                            else if (in_array($grade, ['B+', 'B'])) $gradeBadgeClass = 'bg-primary text-white';
                            else if (in_array($grade, ['C+', 'C'])) $gradeBadgeClass = 'bg-info text-dark';
                            else if (in_array($grade, ['D+', 'D'])) $gradeBadgeClass = 'bg-warning text-dark';
                            else if ($grade === 'F') $gradeBadgeClass = 'bg-danger text-white';
                        }

                        $rowReleaseCategory = $isFullyReleased ? 'released' : 'draft';
                        if ($isFullyReleased && $passFail === 'Pass') $rowReleaseCategory .= ' pass';
                        if ($isFullyReleased && $passFail === 'Fail') $rowReleaseCategory .= ' fail';
                    ?>
                        <tr class="student-row" 
                            data-roll="<?php echo strtolower(htmlspecialchars($s['roll_no'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-name="<?php echo strtolower(htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-group="<?php echo strtolower(htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-title="<?php echo strtolower(htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-supervisor="<?php echo strtolower(htmlspecialchars($s['supervisor_name'] ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                            data-category="<?php echo htmlspecialchars($rowReleaseCategory, ENT_QUOTES, 'UTF-8'); ?>">
                            
                            <td class="ps-3 col-serial-num"><?php echo $serial++; ?></td>
                            <td class="col-roll-no">
                                <?php echo htmlspecialchars($s['roll_no'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="col-student-name">
                                <div class="student-name-text"><?php echo htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                                <small class="text-muted d-block mt-0.5" style="font-size: 0.74rem;">
                                    <i class="bi bi-clock me-1 text-secondary opacity-75"></i><?php echo htmlspecialchars($s['shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?> Shift
                                </small>
                            </td>
                            <td class="col-group-project">
                                <span class="badge bg-light text-dark border px-2 py-1 mb-1 font-monospace" style="font-size: 0.74rem;">
                                    <?php echo htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <div class="text-truncate project-title-sub" title="<?php echo htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($s['project_title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td class="col-supervisor">
                                <span class="text-truncate d-block" title="<?php echo htmlspecialchars($s['supervisor_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($s['supervisor_name'] ?? 'Unassigned', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>

                            <!-- Proposal Defence -->
                            <td class="col-mark">
                                <?php if ($visProp !== null): ?>
                                    <span class="fw-bold"><?php echo (int)$visProp; ?></span>
                                <?php elseif (!empty($s['prop_draft'])): ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5" style="font-size: 0.7rem;" title="Mark recorded; pending release by coordinator">
                                        <i class="bi bi-eye-slash-fill text-warning me-1"></i>Draft
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- FYP Progress Presentation -->
                            <td class="col-mark">
                                <?php if ($visProg !== null): ?>
                                    <span class="fw-bold"><?php echo (int)$visProg; ?></span>
                                <?php elseif (!empty($s['prog_draft'])): ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5" style="font-size: 0.7rem;" title="Mark recorded; pending release by coordinator">
                                        <i class="bi bi-eye-slash-fill text-warning me-1"></i>Draft
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- Supervision Marks -->
                            <td class="col-mark">
                                <?php if ($visSup !== null): ?>
                                    <span class="fw-bold"><?php echo (int)$visSup; ?></span>
                                <?php elseif (!empty($s['sup_draft'])): ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5" style="font-size: 0.7rem;" title="Mark recorded; pending release by coordinator">
                                        <i class="bi bi-eye-slash-fill text-warning me-1"></i>Draft
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- Final Presentation -->
                            <td class="col-mark">
                                <?php if ($visFin !== null): ?>
                                    <span class="fw-bold"><?php echo (int)$visFin; ?></span>
                                <?php elseif (!empty($s['fin_draft'])): ?>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5" style="font-size: 0.7rem;" title="Mark recorded; pending release by coordinator">
                                        <i class="bi bi-eye-slash-fill text-warning me-1"></i>Draft
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- Total Marks -->
                            <td class="col-total-mark">
                                <?php if ($isFullyReleased && $tot !== null): ?>
                                    <span><?php echo (int)$tot; ?></span>
                                <?php elseif ($tot !== null): ?>
                                    <span class="text-muted" title="Partial total of released components; remaining components are in draft mode">
                                        <?php echo (int)$tot; ?><small class="text-warning fw-bold">*</small>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- Percentage -->
                            <td class="col-pct">
                                <?php if ($isFullyReleased && $pct !== null): ?>
                                    <span><?php echo (int)$pct . '%'; ?></span>
                                <?php else: ?>
                                    <span class="text-muted opacity-50">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <!-- Grade -->
                            <td class="col-grade">
                                <?php if ($isFullyReleased && $grade !== '-'): ?>
                                    <span class="badge rounded-pill px-2.5 py-1 <?php echo $gradeBadgeClass; ?>" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Status -->
                            <td class="col-status">
                                <?php if ($isFullyReleased): ?>
                                    <?php if ($passFail === 'Pass'): ?>
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.74rem;">
                                            Pass
                                        </span>
                                    <?php elseif ($passFail === 'Fail'): ?>
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size: 0.74rem;">
                                            Fail
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.78rem;">-</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Coordinator Release Status -->
                            <td class="col-visibility pe-3">
                                <?php if ($isFullyReleased): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.72rem;" title="All marks officially released to students by coordinator">
                                        <i class="bi bi-eye-fill me-1"></i> Released
                                    </span>
                                <?php elseif ($hasAnyDraft): ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 text-dark" style="font-size: 0.72rem;" title="Marks saved in draft mode; pending release by coordinator">
                                        <i class="bi bi-eye-slash-fill me-1"></i> Draft Mode
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.78rem;">Not Evaluated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════ Client-side Search & Filter Script ═══════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFiltersBtn');
    const rows = document.querySelectorAll('.student-row');
    const visibleCountSpan = document.getElementById('visibleRowCount');

    function filterRows() {
        const query = (searchInput.value || '').trim().toLowerCase();
        const statusVal = statusFilter.value;
        let count = 0;

        rows.forEach(row => {
            const roll = row.getAttribute('data-roll') || '';
            const name = row.getAttribute('data-name') || '';
            const group = row.getAttribute('data-group') || '';
            const title = row.getAttribute('data-title') || '';
            const supervisor = row.getAttribute('data-supervisor') || '';
            const category = row.getAttribute('data-category') || '';

            let matchesSearch = true;
            if (query !== '') {
                matchesSearch = roll.includes(query) ||
                                name.includes(query) ||
                                group.includes(query) ||
                                title.includes(query) ||
                                supervisor.includes(query);
            }

            let matchesStatus = true;
            if (statusVal === 'released') {
                matchesStatus = category.includes('released');
            } else if (statusVal === 'draft') {
                matchesStatus = category.includes('draft');
            } else if (statusVal === 'pass') {
                matchesStatus = category.includes('pass');
            } else if (statusVal === 'fail') {
                matchesStatus = category.includes('fail');
            }

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCountSpan) {
            visibleCountSpan.textContent = count;
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterRows);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterRows);
    }
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = 'all';
            filterRows();
        });
    }
});
</script>
