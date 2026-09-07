<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batchName = $batchName ?? 'All Batches';
$shift = $shift ?? 'all';
$dated = date('d-m-Y');
$department = $department ?? 'Software Engineering';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cumulative FYP Evaluation Sheet - <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?> - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        * { box-sizing: border-box; }
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
        .no-print-toolbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #0f172a;
            color: #f8fafc;
            padding: 10px 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
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
        }
        .print-canvas {
            width: 297mm;
            min-height: 210mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 14mm 16mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        @media print {
            .no-print-toolbar { display: none !important; }
            body { background: #ffffff !important; }
            .print-canvas {
                width: 100% !important;
                margin: 0 !important;
                padding: 5mm 8mm !important;
                box-shadow: none !important;
            }
            @page {
                size: A4 landscape;
                margin: 6mm 8mm;
            }
        }
        .header-title-main {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 0;
        }
        .header-title-sub {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0 0 0;
        }
        .print-sheet-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .print-sheet-table th, .print-sheet-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }
        .print-sheet-table th {
            background-color: #f0f0f0 !important;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center !important; }
        .sig-block {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
            font-size: 9pt;
        }
    </style>
</head>
<body>

<!-- Floating Toolbar -->
<div class="no-print-toolbar d-flex align-items-center justify-content-between">
    <a href="<?php echo $basePath; ?>/admin/cumulative-sheet" class="toolbar-back-btn">
        <i class="bi bi-arrow-left"></i> Back to Portal
    </a>
    <div class="fw-bold" style="font-size: 0.95rem;">
        Cumulative Evaluation Sheet &bull; <?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?>)
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" onclick="window.print()">
        <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
    </button>
</div>

<!-- Print Canvas -->
<div class="print-canvas">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
        <div style="width: 70px;">
            <img src="<?php echo $basePath; ?>/images/logo.png" alt="University Logo" style="max-height: 65px; object-fit: contain;">
        </div>
        <div class="text-center flex-grow-1">
            <h1 class="header-title-main">DEPARTMENT OF <?php echo strtoupper(htmlspecialchars($department, ENT_QUOTES, 'UTF-8')); ?></h1>
            <h2 class="header-title-sub">FINAL YEAR PROJECT (FYP) CUMULATIVE MARKS SHEET</h2>
            <div style="font-size: 9pt; margin-top: 3px; font-weight: bold;">
                Batch: <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?> &bull; 
                Shift: <?php echo htmlspecialchars(ucfirst($shift), ENT_QUOTES, 'UTF-8'); ?> &bull; 
                Dated: <?php echo htmlspecialchars($dated, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
        <div style="width: 70px; text-align: right; font-size: 8pt; font-weight: bold;">
            CONFIDENTIAL
        </div>
    </div>

    <!-- Table -->
    <table class="print-sheet-table">
        <thead>
            <tr>
                <th style="width: 30px;">S.#</th>
                <th style="width: 80px;">Roll No</th>
                <th>Student Name</th>
                <th>Group Code</th>
                <th>Project Title</th>
                <th>Supervisor</th>
                <th style="width: 55px;">Proposal<br>(30)</th>
                <th style="width: 55px;">Progress<br>(30)</th>
                <th style="width: 55px;">Supervision<br>(50)</th>
                <th style="width: 55px;">Final<br>(90)</th>
                <th style="width: 60px;">Total<br>(200)</th>
                <th style="width: 45px;">%</th>
                <th style="width: 45px;">Grade</th>
                <th style="width: 50px;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="14" class="text-center py-4">No student records found.</td>
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
                        <td class="text-center"><?php echo $sn++; ?></td>
                        <td style="font-family: monospace; font-weight: bold;"><?php echo htmlspecialchars($s['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="font-weight: bold;"><?php echo htmlspecialchars($s['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center" style="font-family: monospace;"><?php echo htmlspecialchars($s['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="font-size: 8pt; max-width: 200px;"><?php echo htmlspecialchars($s['project_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="font-size: 8pt;"><?php echo htmlspecialchars($s['supervisor_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center"><?php echo isset($s['proposal_defense_marks']) ? round($s['proposal_defense_marks']) : '-'; ?></td>
                        <td class="text-center"><?php echo isset($s['progress_presentation_marks']) ? round($s['progress_presentation_marks']) : '-'; ?></td>
                        <td class="text-center"><?php echo isset($s['supervision_marks']) ? round($s['supervision_marks']) : '-'; ?></td>
                        <td class="text-center"><?php echo isset($s['final_presentation_marks']) ? round($s['final_presentation_marks']) : '-'; ?></td>
                        <td class="text-center" style="font-weight: bold;"><?php echo $total > 0 ? round($total) : '-'; ?></td>
                        <td class="text-center"><?php echo $percentage > 0 ? round($percentage, 1) . '%' : '-'; ?></td>
                        <td class="text-center" style="font-weight: bold;"><?php echo htmlspecialchars($grade, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($passStatus, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Signature Block -->
    <div class="sig-block">
        <div class="sig-line">
            FYP Coordinator
        </div>
        <div class="sig-line">
            Head of Department (HOD)
        </div>
        <div class="sig-line">
            Super Administrator
        </div>
    </div>
</div>

</body>
</html>
