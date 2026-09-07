<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$stage = $stage ?? 'Proposal Defence Presentation';
$batchName = $batchName ?? '2023';
$shift = $shift ?? 'Morning';
$committeeNumber = $committeeNumber ?? 1;
$date = $date ?? date('Y-m-d');
$time = $time ?? '09:00 AM';
$venue = $venue ?? 'FYP Lab';
$department = $department ?? 'Software Engineering';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($stage, ENT_QUOTES, 'UTF-8'); ?> - Attendance Sheet</title>
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
            max-width: 900px;
            margin: 24px auto;
            background: #ffffff;
            padding: 24px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 6px;
        }
        @media print {
            .no-print-toolbar, .no-print, .d-print-none { display: none !important; }
            body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; }
            .print-canvas {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm 12mm;
            }
        }
        .header-title-main {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
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
            font-size: 9pt;
        }
        .print-sheet-table th, .print-sheet-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .print-sheet-table th {
            background-color: #f0f0f0 !important;
            text-align: center;
            font-weight: bold;
        }
        .sig-block {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-line {
            border-top: 1px solid #000;
            width: 170px;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
            font-size: 8.5pt;
        }
    </style>
</head>
<body>

<!-- Floating Toolbar -->
<div class="no-print-toolbar no-print d-print-none d-flex align-items-center justify-content-between">
    <a href="<?php echo $basePath; ?>/admin/attendance-sheet" class="toolbar-back-btn">
        <i class="bi bi-arrow-left"></i> Back to Config
    </a>
    <div class="fw-bold" style="font-size: 0.95rem;">
        <?php echo htmlspecialchars($stage, ENT_QUOTES, 'UTF-8'); ?> &bull; Attendance Sheet (Committee <?php echo $committeeNumber; ?>)
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" onclick="window.print()">
        <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
    </button>
</div>

<!-- Print Canvas -->
<div class="print-canvas">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
        <div style="width: 65px;">
            <img src="<?php echo $basePath; ?>/images/logo.png" alt="University Logo" style="max-height: 60px; object-fit: contain;">
        </div>
        <div class="text-center flex-grow-1">
            <h1 class="header-title-main">DEPARTMENT OF <?php echo strtoupper(htmlspecialchars($department, ENT_QUOTES, 'UTF-8')); ?></h1>
            <h2 class="header-title-sub"><?php echo strtoupper(htmlspecialchars($stage, ENT_QUOTES, 'UTF-8')); ?> - ATTENDANCE SHEET</h2>
            <div style="font-size: 9pt; margin-top: 2px; font-weight: bold;">
                Batch: <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?> &bull; 
                Shift: <?php echo htmlspecialchars(ucfirst($shift), ENT_QUOTES, 'UTF-8'); ?> &bull; 
                Committee: #<?php echo $committeeNumber; ?>
            </div>
        </div>
        <div style="width: 65px;"></div>
    </div>

    <!-- Info Row -->
    <div class="d-flex justify-content-between mb-3" style="font-size: 9pt; font-weight: bold;">
        <div><strong>Date:</strong> <?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></div>
        <div><strong>Time:</strong> <?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?></div>
        <div><strong>Venue:</strong> <?php echo htmlspecialchars($venue, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <!-- Attendance Table -->
    <table class="print-sheet-table mb-4">
        <thead>
            <tr>
                <th style="width: 35px;">S.#</th>
                <th style="width: 85px;">Group Code</th>
                <th style="width: 95px;">Roll No</th>
                <th>Student Name</th>
                <th>Project Title</th>
                <th style="width: 110px;">Signature</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($groups)): ?>
                <tr>
                    <td colspan="6" class="text-center py-4">No groups allocated to Committee <?php echo $committeeNumber; ?>.</td>
                </tr>
            <?php else: ?>
                <?php 
                $sn = 1;
                foreach ($groups as $grp): 
                    $members = $grp['members'] ?? [];
                    $memCount = count($members);
                    if ($memCount === 0):
                ?>
                    <tr>
                        <td class="text-center"><?php echo $sn++; ?></td>
                        <td style="font-family: monospace; font-weight: bold; text-align: center;"><?php echo htmlspecialchars($grp['group_code']); ?></td>
                        <td colspan="3" class="text-muted">No registered members</td>
                        <td></td>
                    </tr>
                <?php else: 
                    foreach ($members as $mIdx => $m):
                ?>
                    <tr>
                        <td class="text-center"><?php echo $sn++; ?></td>
                        <?php if ($mIdx === 0): ?>
                            <td rowspan="<?php echo $memCount; ?>" style="font-family: monospace; font-weight: bold; text-align: center;">
                                <?php echo htmlspecialchars($grp['group_code']); ?>
                            </td>
                        <?php endif; ?>
                        <td style="font-family: monospace; font-weight: bold;"><?php echo htmlspecialchars($m['student_id']); ?></td>
                        <td style="font-weight: bold;"><?php echo htmlspecialchars($m['name']); ?></td>
                        <?php if ($mIdx === 0): ?>
                            <td rowspan="<?php echo $memCount; ?>" style="font-size: 8.5pt;">
                                <?php echo htmlspecialchars($grp['project_title']); ?>
                            </td>
                        <?php endif; ?>
                        <td style="height: 32px;"></td>
                    </tr>
                <?php 
                    endforeach;
                    endif;
                endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Committee Members & Evaluators -->
    <div class="mb-4">
        <div style="font-size: 9.5pt; font-weight: bold; margin-bottom: 8px;">Evaluation Committee Members:</div>
        <div class="row g-2">
            <?php if (empty($evaluators)): ?>
                <div class="col-12 text-muted small fst-italic">Evaluator names will be filled by committee during session.</div>
            <?php else: ?>
                <?php foreach ($evaluators as $idx => $ev): ?>
                    <div class="col-4">
                        <div class="p-2 border" style="font-size: 8.5pt;">
                            <strong><?php echo $idx + 1; ?>. <?php echo htmlspecialchars($ev['name']); ?></strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars($ev['designation'] ?? 'Evaluator'); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Signatures -->
    <div class="sig-block">
        <div class="sig-line">Committee Member 1</div>
        <div class="sig-line">Committee Member 2</div>
        <div class="sig-line">FYP Coordinator</div>
        <div class="sig-line">Super Administrator</div>
    </div>
</div>

</body>
</html>
