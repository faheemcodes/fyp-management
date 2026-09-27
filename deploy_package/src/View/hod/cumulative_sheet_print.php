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
    <title>Cumulative FYP Evaluation Sheet - <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?> - HOD View</title>
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
            padding: 0;
            margin: 0;
            height: 100%;
        }
        .toolbar-input-group select option {
            background: #1e293b;
            color: #ffffff;
        }

        .toolbar-print-btn {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            color: #ffffff;
            border-radius: 999px;
            padding: 7px 20px;
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.35);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .toolbar-print-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.5);
            color: #ffffff;
        }

        /* ─── Printable Document Container ─── */
        .page-container {
            width: 100%;
            max-width: 1120px;
            margin: 24px auto;
            background: #fff;
            padding: 24px 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
        }

        /* ─── Official Letterhead ─── */
        .letterhead-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .letterhead-header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .letterhead-header h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }
        .letterhead-header h4 {
            font-size: 11pt;
            font-weight: bold;
            margin: 0 0 4px 0;
            text-decoration: underline;
        }

        /* ─── Meta Table ─── */
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9.5pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 6px;
            vertical-align: middle;
        }

        /* ─── Main Cumulative Table ─── */
        table.cum-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 16px;
        }
        table.cum-table th,
        table.cum-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: middle;
        }
        table.cum-table th {
            background-color: #f2f2f2 !important;
            text-align: center;
            font-weight: bold;
            font-size: 8.5pt;
        }
        table.cum-table td.center {
            text-align: center;
        }
        table.cum-table td.num {
            text-align: center;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 8.5pt;
        }
        table.cum-table td.bold {
            font-weight: bold;
        }

        /* ─── Summary Box ─── */
        .summary-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #000;
            padding: 8px 14px;
            margin-bottom: 30px;
            font-size: 9pt;
            background: #fafafa;
        }

        /* ─── Signatures Row ─── */
        .signatures-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
            padding: 0 20px;
            page-break-inside: avoid;
        }
        .sig-block {
            text-align: center;
            width: 28%;
        }
        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 6px;
        }

        /* ─── Print Media Styles ─── */
        @media print {
            .no-print-toolbar {
                display: none !important;
            }
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .page-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
                max-width: 100%;
            }
            @page {
                size: landscape;
                margin: 10mm 12mm 10mm 12mm;
            }
        }
    </style>
</head>
<body>

<!-- ═══════════════ Floating Interactive Toolbar (Screen Only) ═══════════════ -->
<div class="no-print-toolbar">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <!-- Left: Back Button & Context Title -->
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo $basePath; ?>/hod/cumulative-sheet" class="toolbar-back-btn">
                <i class="bi bi-arrow-left"></i> Back to Portal
            </a>
            <div class="d-none d-md-block">
                <div class="fw-bold" style="font-size: 0.95rem; color: #ffffff;">
                    FYP Cumulative Marks Sheet (Official HOD Copy)
                </div>
                <div style="font-size: 0.76rem; color: #94a3b8;">
                    Department of <?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        </div>

        <!-- Center: Batch, Shift & Date Filters -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Batch -->
            <div class="toolbar-input-group">
                <span class="toolbar-icon"><i class="bi bi-mortarboard-fill"></i></span>
                <select id="toolbarBatchSelect" onchange="updatePrintFilters()">
                    <option value="all" <?php echo ($batchId == 0) ? 'selected' : ''; ?>>All Batches</option>
                    <?php foreach ($batches as $b): ?>
                        <option value="<?php echo (int)$b['id']; ?>" <?php echo ($batchId == $b['id']) ? 'selected' : ''; ?>>
                            Batch: <?php echo htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Shift -->
            <div class="toolbar-input-group">
                <span class="toolbar-icon"><i class="bi bi-clock-fill"></i></span>
                <select id="toolbarShiftSelect" onchange="updatePrintFilters()">
                    <option value="all" <?php echo ($shift === 'all') ? 'selected' : ''; ?>>All Shifts</option>
                    <option value="Morning" <?php echo ($shift === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                    <option value="Evening" <?php echo ($shift === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                </select>
            </div>

            <!-- Date -->
            <div class="toolbar-input-group">
                <span class="toolbar-icon"><i class="bi bi-calendar3"></i></span>
                <input type="text" id="toolbarDateInput" value="<?php echo htmlspecialchars($dated, ENT_QUOTES, 'UTF-8'); ?>" placeholder="DD-MM-YYYY" style="width: 100px;" onchange="updatePrintFilters()">
            </div>
        </div>

        <!-- Right: Print Action Button -->
        <div>
            <button type="button" onclick="window.print()" class="toolbar-print-btn">
                <i class="bi bi-printer-fill"></i> Print Official Sheet
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════ Printable Landscape Document ═══════════════ -->
<div class="page-container">
    <!-- University Letterhead Header -->
    <div class="letterhead-header">
        <h2>FACULTY OF ENGINEERING &amp; TECHNOLOGY</h2>
        <h3>UNIVERSITY OF SINDH, JAMSHORO</h3>
        <h4>FINAL YEAR PROJECT (FYP) CUMULATIVE EVALUATION &amp; MARKS SHEET</h4>
        <div style="font-size: 8.5pt; color: #444; font-style: italic; margin-top: 3px;">
            Official Departmental Performance Record (Marks Released by Department Coordinator to Students)
        </div>
    </div>

    <!-- Metadata Table -->
    <table class="meta-table">
        <tr>
            <td style="width: 38%;"><strong>Department:</strong> <?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="width: 25%;"><strong>Shift:</strong> <?php echo htmlspecialchars($shift === 'all' ? 'All Shifts (Morning & Evening)' : $shift, ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="width: 20%;"><strong>Batch:</strong> <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="width: 17%; text-align: right;"><strong>Dated:</strong> <?php echo htmlspecialchars($dated, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    </table>

    <!-- Main Cumulative Table -->
    <table class="cum-table">
        <thead>
            <tr>
                <th style="width: 32px;">S#</th>
                <th style="width: 85px;">Roll No</th>
                <th style="width: 155px;">Candidate Name</th>
                <th style="width: 50px;">Shift</th>
                <th style="width: 75px;">Group</th>
                <th>Project Title &amp; Supervisor</th>
                <th style="width: 48px;">Prop.<br>(40)</th>
                <th style="width: 48px;">Prog.<br>(40)</th>
                <th style="width: 48px;">Sup.<br>(45)</th>
                <th style="width: 48px;">Final<br>(75)</th>
                <th style="width: 55px;">Total<br>(200)</th>
                <th style="width: 45px;">%</th>
                <th style="width: 48px;">Grade</th>
                <th style="width: 55px;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($studentsList)): ?>
                <tr>
                    <td colspan="14" class="center" style="padding: 30px; font-style: italic;">
                        No approved student records or evaluation marks found for the selected criteria.
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                $sn = 1;
                $passedCount = 0;
                $releasedTotalScoreSum = 0;
                $releasedCount = 0;
                $draftCount = 0;

                foreach ($studentsList as $s):
                    $isFullyReleased = !empty($s['is_fully_released']);
                    $hasAnyDraft = !empty($s['has_any_draft']);

                    $visProp = $s['vis_prop'];
                    $visProg = $s['vis_prog'];
                    $visSup  = $s['vis_sup'];
                    $visFin  = $s['vis_fin'];

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

                        $releasedCount++;
                        $releasedTotalScoreSum += $tot;
                        if ($passFail === 'Pass' || ($pct !== null && $pct >= 50)) {
                            $passedCount++;
                        }
                    } else {
                        $draftCount++;
                        $releasedSum = ($visProp ?? 0) + ($visProg ?? 0) + ($visSup ?? 0) + ($visFin ?? 0);
                        $tot = ($releasedSum > 0) ? $releasedSum : null;
                        $pct = null;
                        $grade = 'Draft';
                        $passFail = 'Draft';
                    }
                ?>
                    <tr>
                        <td class="center"><?php echo $sn++; ?></td>
                        <td class="num"><?php echo htmlspecialchars($s['roll_no'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="bold"><?php echo htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="center" style="font-size: 8pt;"><?php echo htmlspecialchars($s['shift'] ?? 'Morning', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="center num"><?php echo htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <div><?php echo htmlspecialchars($s['project_title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8'); ?></div>
                            <div style="font-size: 7.5pt; color: #333; font-style: italic;">
                                Sup: <?php echo htmlspecialchars($s['supervisor_name'] ?? 'Unassigned', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </td>

                        <!-- Proposal -->
                        <td class="num">
                            <?php 
                            if ($visProp !== null) echo (int)$visProp;
                            elseif (!empty($s['prop_draft'])) echo '<span style="color: #b45309; font-size: 7pt; font-style: italic;">[Draft]</span>';
                            else echo '-';
                            ?>
                        </td>

                        <!-- Progress -->
                        <td class="num">
                            <?php 
                            if ($visProg !== null) echo (int)$visProg;
                            elseif (!empty($s['prog_draft'])) echo '<span style="color: #b45309; font-size: 7pt; font-style: italic;">[Draft]</span>';
                            else echo '-';
                            ?>
                        </td>

                        <!-- Supervision -->
                        <td class="num">
                            <?php 
                            if ($visSup !== null) echo (int)$visSup;
                            elseif (!empty($s['sup_draft'])) echo '<span style="color: #b45309; font-size: 7pt; font-style: italic;">[Draft]</span>';
                            else echo '-';
                            ?>
                        </td>

                        <!-- Final -->
                        <td class="num">
                            <?php 
                            if ($visFin !== null) echo (int)$visFin;
                            elseif (!empty($s['fin_draft'])) echo '<span style="color: #b45309; font-size: 7pt; font-style: italic;">[Draft]</span>';
                            else echo '-';
                            ?>
                        </td>

                        <!-- Total -->
                        <td class="num bold" style="background: #f4f4f4;">
                            <?php 
                            if ($isFullyReleased && $tot !== null) echo (int)$tot;
                            elseif ($tot !== null) echo (int)$tot . '<span style="font-size: 7pt; color: #b45309;">*</span>';
                            else echo '-';
                            ?>
                        </td>

                        <!-- Percentage -->
                        <td class="num"><?php echo ($isFullyReleased && $pct !== null) ? (int)$pct : '-'; ?></td>

                        <!-- Grade -->
                        <td class="center bold"><?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?></td>

                        <!-- Status -->
                        <td class="center bold"><?php echo htmlspecialchars($passFail, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Summary Statistics -->
    <?php
    $totalCandidates = count($studentsList);
    $failedCount = $releasedCount > 0 ? ($releasedCount - $passedCount) : 0;
    $passRate = $releasedCount > 0 ? round(($passedCount / $releasedCount) * 100, 1) : 0;
    $batchAvg = $releasedCount > 0 ? round($releasedTotalScoreSum / $releasedCount, 1) : 0;
    ?>
    <div class="summary-box">
        <div><strong>Total Enrolled:</strong> <?php echo (int)$totalCandidates; ?> Candidates</div>
        <div><strong>Released by Coordinator:</strong> <?php echo (int)$releasedCount; ?></div>
        <div><strong>Draft / Pending:</strong> <?php echo (int)$draftCount; ?></div>
        <div><strong>Passed (Released):</strong> <?php echo (int)$passedCount; ?> (<?php echo $passRate; ?>%)</div>
        <div><strong>Released Average Score:</strong> <?php echo (int)round($batchAvg); ?> / 200 (<?php echo (int)round(($batchAvg / 200.0) * 100); ?>%)</div>
    </div>

    <!-- Official Signatures Row -->
    <div class="signatures-row">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="fw-bold"><?php echo htmlspecialchars($coordinatorName, ENT_QUOTES, 'UTF-8'); ?></div>
            <div style="font-size: 8.5pt;">Department Coordinator</div>
        </div>

        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="fw-bold"><?php echo htmlspecialchars($hodName, ENT_QUOTES, 'UTF-8'); ?></div>
            <div style="font-size: 8.5pt;">Head of Department (HOD)</div>
        </div>

        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="fw-bold">Dean / External Examiner</div>
            <div style="font-size: 8.5pt;">Faculty of Engineering &amp; Technology</div>
        </div>
    </div>
</div>

<script>
function updatePrintFilters() {
    const batchId = document.getElementById('toolbarBatchSelect').value;
    const shift = encodeURIComponent(document.getElementById('toolbarShiftSelect').value);
    const dated = encodeURIComponent(document.getElementById('toolbarDateInput').value);
    const url = '<?php echo $basePath; ?>/hod/cumulative-sheet/print?batch_id=' + batchId + '&shift=' + shift + '&dated=' + dated;
    window.location.href = url;
}
</script>

</body>
</html>
