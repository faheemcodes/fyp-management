<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batchId = $batchId ?? 0;
$batchName = $batchName ?? 'All Batches';
$shift = $shift ?? 'all';
$dated = $dated ?? date('d-m-Y');
$department = $department ?? 'Software Engineering';
$coordinatorName = $coordinatorName ?? 'Department Coordinator';
$hodName = $hodName ?? 'Head of Department';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cumulative FYP Evaluation Sheet - <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?></title>
    <!-- Bootstrap & Icons for Toolbar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* ─── Screen & Print Reset ─── */
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #f1f5f9;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ─── Floating Top Toolbar (Hidden in Print) ─── */
        .no-print-toolbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #0f172a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: #f8fafc;
            padding: 10px 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .toolbar-back-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #f1f5f9;
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .toolbar-back-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            transform: translateX(-2px);
        }

        .toolbar-input-group {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            padding: 0 14px;
            height: 38px;
            transition: all 0.2s ease;
        }
        .toolbar-input-group:focus-within {
            border-color: #38bdf8;
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.25);
            background: rgba(255, 255, 255, 0.12);
        }
        .toolbar-input-group .toolbar-icon {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-right: 8px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
        }
        .toolbar-input-group input,
        .toolbar-input-group select {
            background: transparent;
            border: none;
            color: #f8fafc;
            font-size: 0.82rem;
            font-weight: 500;
            outline: none;
            width: 100%;
        }
        .toolbar-input-group select option {
            background: #1e293b;
            color: #f8fafc;
        }

        .btn-toolbar-print {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: #ffffff;
            border-radius: 999px;
            padding: 7px 22px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
            white-space: nowrap;
            cursor: pointer;
        }
        .btn-toolbar-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5);
            color: #ffffff;
        }

        /* ─── Paper Layout (Screen Preview) ─── */
        .sheet-wrapper {
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 12px;
        }

        .sheet-page {
            background: #ffffff;
            padding: 24px 30px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
        }

        /* ─── Header ─── */
        .report-header {
            text-align: center;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .report-header .uni {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.03em;
        }
        .report-header .dept {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .report-header .title-badge {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 4px;
            display: inline-block;
        }
        .report-header .meta-row {
            font-size: 9.5pt;
            margin-top: 5px;
            font-weight: 500;
        }
        .report-header .scheme-info {
            font-size: 8.5pt;
            font-style: italic;
            margin-top: 3px;
            color: #222;
        }

        /* ─── Table ─── */
        table.sheet {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            table-layout: auto;
        }
        table.sheet th,
        table.sheet td {
            border: 1.5px solid #000;
            padding: 4px 5px;
            vertical-align: middle;
        }
        table.sheet th {
            background: #eaeaea;
            font-weight: bold;
            text-align: center;
            font-size: 8.5pt;
        }
        table.sheet td.center {
            text-align: center;
        }
        table.sheet td.num {
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        table.sheet td.bold {
            font-weight: bold;
        }

        /* ─── Stats & Signatures ─── */
        .summary-box {
            margin-top: 14px;
            border: 1px solid #000;
            padding: 6px 12px;
            font-size: 8.5pt;
            background: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .signatures-row {
            display: flex;
            justify-content: space-between;
            margin-top: 45px;
            padding: 0 20px;
            font-size: 9.5pt;
        }
        .sig-block {
            text-align: center;
            min-width: 180px;
        }
        .sig-line {
            border-top: 1.5px solid #000;
            margin-bottom: 6px;
        }

        /* ─── Print Styles ─── */
        @media print {
            @page {
                size: landscape;
                margin: 8mm;
            }
            body {
                background: #ffffff !important;
                font-size: 9pt;
            }
            .no-print-toolbar {
                display: none !important;
            }
            .sheet-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .sheet-page {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            table.sheet {
                page-break-inside: auto;
            }
            table.sheet tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            table.sheet th {
                background: #e0e0e0 !important;
            }
            .signatures-row {
                page-break-inside: avoid;
                margin-top: 40px;
            }
        }
    </style>
</head>
<body>

<!-- ═══════════════ Floating Print Toolbar ═══════════════ -->
<div class="no-print-toolbar">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <!-- Left: Back Button & Title -->
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo $basePath; ?>/coordinator/cumulative-sheet" class="toolbar-back-btn">
                <i class="bi bi-arrow-left"></i> Back to Cumulative Sheet
            </a>
            <div class="d-none d-md-block">
                <span class="fw-bold text-white" style="font-size: 0.95rem;">Cumulative FYP Evaluation Sheet</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">Print Preview</span>
            </div>
        </div>

        <!-- Center: Controls (Batch, Shift, Date) -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Batch -->
            <div class="toolbar-input-group">
                <i class="bi bi-mortarboard-fill toolbar-icon"></i>
                <select id="toolbarBatchSelect" onchange="updatePrintFilters()">
                    <option value="0" <?php echo $batchId === 0 ? 'selected' : ''; ?>>All Batches</option>
                    <?php foreach ($batches as $b): ?>
                        <option value="<?php echo (int)$b['id']; ?>" <?php echo $batchId == $b['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Coordinator Shift Badge -->
            <div class="toolbar-input-group">
                <i class="bi <?php echo $coordinatorShift === 'Evening' ? 'bi-moon-stars-fill' : 'bi-sun-fill'; ?> toolbar-icon"></i>
                <span style="color: #f8fafc; font-size: 0.82rem; font-weight: 600;"><?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift</span>
            </div>

            <!-- Date -->
            <div class="toolbar-input-group" style="width: 140px;">
                <i class="bi bi-calendar3 toolbar-icon"></i>
                <input type="text" id="toolbarDateInput" value="<?php echo htmlspecialchars($dated, ENT_QUOTES, 'UTF-8'); ?>" onchange="updatePrintFilters()" placeholder="DD-MM-YYYY">
            </div>
        </div>

        <!-- Right: Print Button -->
        <div>
            <button type="button" onclick="window.print()" class="btn-toolbar-print">
                <i class="bi bi-printer-fill"></i> Print Sheet
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════ Printable Sheet Page ═══════════════ -->
<div class="sheet-wrapper">
    <div class="sheet-page">
        <!-- University & Department Header -->
        <div class="report-header">
            <div class="uni">UNIVERSITY OF SINDH, JAMSHORO</div>
            <div class="dept">DEPARTMENT OF <?php echo strtoupper(htmlspecialchars($department, ENT_QUOTES, 'UTF-8')); ?></div>
            <div class="title-badge">FINAL YEAR PROJECT (FYP) - CUMULATIVE EVALUATION &amp; GRADING SHEET (<?php echo strtoupper(htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8')); ?> SHIFT)</div>
            <div class="meta-row">
                <strong>Batch:</strong> <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?> &nbsp;|&nbsp;
                <strong>Shift:</strong> <?php echo htmlspecialchars($coordinatorShift, ENT_QUOTES, 'UTF-8'); ?> Shift &nbsp;|&nbsp;
                <strong>Dated:</strong> <?php echo htmlspecialchars($dated, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="scheme-info">
                Grading Scheme: Proposal Defence (Max 40) + FYP Progress Presentation (Max 40) + Supervision (Max 45) + Final Presentation (Max 75) = <strong>Total 200 Marks</strong> (Passing: 50% / 100 Marks)
            </div>
        </div>

        <!-- Sheet Table -->
        <table class="sheet">
            <thead>
                <tr>
                    <th style="width: 30px;">S#</th>
                    <th style="width: 90px;">Roll No</th>
                    <th style="width: 140px;">Student Name</th>
                    <th style="width: 70px;">Group</th>
                    <th>Project Title &amp; Supervisor</th>
                    <th style="width: 50px;">Prop.<br>(40)</th>
                    <th style="width: 50px;">Prog.<br>(40)</th>
                    <th style="width: 50px;">Sup.<br>(45)</th>
                    <th style="width: 50px;">Final<br>(75)</th>
                    <th style="width: 55px; background: #dedede;">Total<br>(200)</th>
                    <th style="width: 45px;">%</th>
                    <th style="width: 45px;">Grade</th>
                    <th style="width: 55px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($studentsList)): ?>
                    <tr>
                        <td colspan="13" class="center" style="padding: 24px;">No student records found for the selected batch/shift.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $sn = 1;
                    $passedCount = 0;
                    $totalScoreSum = 0;

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

                        if ($tot !== null) {
                            $totalScoreSum += $tot;
                        }
                        if ($passFail === 'Pass' || ($pct !== null && $pct >= 50)) {
                            $passedCount++;
                        }
                    ?>
                        <tr>
                            <td class="center"><?php echo $sn++; ?></td>
                            <td class="num"><?php echo htmlspecialchars($s['roll_no'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="bold"><?php echo htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="center num"><?php echo htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                                <div style="font-size: 7.5pt; color: #333; font-style: italic;">
                                    Sup: <?php echo htmlspecialchars($s['supervisor_name'] ?? 'Unassigned', ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td class="num"><?php echo $prop !== null ? (int)round($prop) : '-'; ?></td>
                            <td class="num"><?php echo $prog !== null ? (int)round($prog) : '-'; ?></td>
                            <td class="num"><?php echo $sup !== null ? (int)round($sup) : '-'; ?></td>
                            <td class="num"><?php echo $fin !== null ? (int)round($fin) : '-'; ?></td>
                            <td class="num bold" style="background: #f4f4f4;"><?php echo $tot !== null ? (int)round($tot) : '-'; ?></td>
                            <td class="num"><?php echo $pct !== null ? (int)round($pct) : '-'; ?></td>
                            <td class="center bold"><?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="center bold"><?php echo htmlspecialchars($passFail, ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Summary Statistics -->
        <?php
        $totalCandidates = count($studentsList);
        $failedCount = $totalCandidates - $passedCount;
        $passRate = $totalCandidates > 0 ? round(($passedCount / $totalCandidates) * 100, 1) : 0;
        $batchAvg = $totalCandidates > 0 ? round($totalScoreSum / $totalCandidates, 1) : 0;
        ?>
        <div class="summary-box">
            <div><strong>Total Enrolled Students:</strong> <?php echo (int)$totalCandidates; ?></div>
            <div><strong>Passed:</strong> <?php echo (int)$passedCount; ?></div>
            <div><strong>Failed:</strong> <?php echo (int)$failedCount; ?></div>
            <div><strong>Pass Percentage:</strong> <?php echo $passRate; ?>%</div>
            <div><strong>Batch Average Score:</strong> <?php echo (int)round($batchAvg); ?> / 200 (<?php echo (int)round(($batchAvg / 200.0) * 100); ?>%)</div>
        </div>

        <!-- Official Signatures Row -->
        <div class="signatures-row">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="fw-bold"><?php echo htmlspecialchars($coordinatorName, ENT_QUOTES, 'UTF-8'); ?></div>
                <div style="font-size: 8.5pt;">FYP Coordinator</div>
            </div>

            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="fw-bold"><?php echo htmlspecialchars($hodName, ENT_QUOTES, 'UTF-8'); ?></div>
                <div style="font-size: 8.5pt;">Chairman / HOD</div>
            </div>

            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="fw-bold">Dean / External Examiner</div>
                <div style="font-size: 8.5pt;">Faculty of Engineering &amp; Technology</div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePrintFilters() {
    const batchId = document.getElementById('toolbarBatchSelect').value;
    const dated = encodeURIComponent(document.getElementById('toolbarDateInput').value);
    const url = '<?php echo $basePath; ?>/coordinator/cumulative-sheet/print?batch_id=' + batchId + '&dated=' + dated;
    window.location.href = url;
}
</script>

</body>
</html>
