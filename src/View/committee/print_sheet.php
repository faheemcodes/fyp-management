<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Committee Evaluation Sheet - <?php echo htmlspecialchars($stage); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #fff;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12.5pt; /* Scaled 1.25x */
            padding: 15px;
        }

        /* ─── Header ─── */
        .report-header {
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .report-header .dept { font-size: 14pt; font-weight: bold; } /* Scaled 1.25x */
        .report-header .batch { font-size: 11pt; } /* Scaled 1.25x */
        .report-header .stage-title { font-size: 12.5pt; font-weight: bold; text-decoration: underline; margin-top: 2px; } /* Scaled 1.25x */

        /* ─── Evaluator Info ─── */
        .evaluator-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin: 8px 0 10px 0;
            font-size: 11pt; /* Scaled 1.25x */
        }

        /* ─── Table ─── */
        table.sheet {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt; /* Scaled 1.25x */
            table-layout: auto;
        }
        table.sheet th,
        table.sheet td {
            border: 1.5px solid #000;
            padding: 3px 4px;
            vertical-align: middle;
        }
        table.sheet th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            font-size: 9pt; /* Scaled 1.25x */
        }
        table.sheet td { font-size: 10pt; } /* Scaled 1.25x */
        table.sheet td.center { text-align: center; }

        /* Vertical text for sub-columns */
        table.sheet th.vtext {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            height: 100px; /* Scaled 1.25x */
            padding: 4px 2px;
            font-size: 8pt; /* Scaled 1.25x */
            width: 25px; /* Scaled 1.25x */
        }

        /* Marks empty cells */
        table.sheet td.mark {
            width: 25px; /* Scaled 1.25x */
            text-align: center;
            height: 25px; /* Scaled 1.25x */
        }

        /* Signature */
        .sig-line {
            text-align: right;
            margin-top: 20px;
            font-size: 11pt; /* Scaled 1.25x */
            font-weight: bold;
        }

        /* ─── Print Button ─── */
        .no-print { text-align: center; margin-bottom: 12px; font-family: Arial, sans-serif; }
        .no-print button { padding: 8px 24px; font-size: 10pt; cursor: pointer; border-radius: 20px; border: 1px solid #ccc; margin: 0 5px; }
        .no-print .print-btn { background: #2563eb; color: #fff; border: none; font-weight: bold; }
        .no-print .back-btn { background: #f1f5f9; }

        /* ─── Print ─── */
        @media print {
            @page { size: landscape; margin: 6mm; }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Restore to the compact sizes for physical A4 printing */
            body { padding: 0; font-size: 10pt !important; background: #fff !important; }
            .report-header .dept { font-size: 11pt !important; }
            .report-header .batch { font-size: 9pt !important; }
            .report-header .stage-title { font-size: 10pt !important; }
            .evaluator-row { font-size: 9pt !important; margin: 6px 0 8px 0 !important; }
            
            table.sheet {
                border-collapse: collapse !important;
                font-size: 8pt !important;
                width: 100% !important;
            }
            table.sheet th { font-size: 7pt !important; }
            table.sheet td { font-size: 8pt !important; }
            table.sheet th.vtext { height: 80px !important; width: 20px !important; font-size: 6.5pt !important; padding: 3px 1px !important; }
            table.sheet td.mark { width: 20px !important; height: 20px !important; }
            .sig-line { font-size: 9pt !important; margin-top: 15px !important; }

            .no-print { display: none !important; }

            table.sheet,
            table.sheet thead,
            table.sheet tbody,
            table.sheet tr,
            table.sheet th,
            table.sheet td {
                border: 1pt solid black !important; /* Use 1pt instead of px to prevent vanishing lines */
                background-clip: padding-box !important;
            }

            table.sheet th {
                background-color: #e8e8e8 !important;
            }

            thead { display: table-header-group !important; }

            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            td, th {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="print-btn" onclick="window.print()">🖨️ Print Sheet</button>
    <button class="back-btn" onclick="window.history.back()">← Go Back</button>
</div>

<div class="report-header">
    <div class="dept">Department of <?php echo htmlspecialchars($committee['department'] ?? 'Software Engineering'); ?> - Faculty of Engineering and Technology</div>
    <div class="batch">BS (Software Engineering) - Batch 2k<?php echo date('y') - 4; ?> - Morning</div>
    <div class="stage-title"><?php echo htmlspecialchars($stage); ?></div>
</div>

<div class="evaluator-row">
    <div>Dated: <?php echo date('d-m-Y'); ?></div>
    <div>Evaluators' Name: <span style="text-decoration: underline; padding: 0 50px;"><?php echo htmlspecialchars($committee['name'] ?? ''); ?></span></div>
    <div>Evaluators' Signature: _______________</div>
</div>

<table class="sheet">
<?php if ($stage === 'Proposal Defence Presentation' || $stage === 'FYP Progress Presentation'): ?>
    <thead>
        <tr>
            <th rowspan="2" style="width: 22px;">S.<br>No</th>
            <th rowspan="2" style="width: 58px;">Project ID</th>
            <th rowspan="2">Title of Project</th>
            <th rowspan="2" style="width: 100px;">Primary Supervisor</th>
            <th colspan="2">Group Members</th>
            <?php if ($stage === 'FYP Progress Presentation'): ?>
                <th rowspan="2" style="width: 130px;">Previous comments</th>
            <?php endif; ?>
            <th rowspan="2" style="width: 45px;">Marks<br>(40)</th>
            <th rowspan="2" style="width: 80px;">Remarks</th>
        </tr>
        <tr>
            <th style="width: 80px;">Roll No</th>
            <th style="min-width: 130px; width: auto;">Full Name</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $srNo = 1;
    foreach ($grouped as $groupId => $members): 
        $numMembers = count($members);
        $firstMember = $members[0];
    ?>
        <tr>
            <td rowspan="<?php echo $numMembers; ?>" class="center"><?php echo $srNo++; ?></td>
            <td rowspan="<?php echo $numMembers; ?>" class="center" style="font-size: 7pt;"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
            <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
            <?php if ($stage === 'FYP Progress Presentation'): ?>
                <td rowspan="<?php echo $numMembers; ?>" style="font-size: 6.5pt;"><?php echo htmlspecialchars($firstMember['previous_comments'] ?: ''); ?></td>
            <?php endif; ?>
            <td class="mark"></td>
            <td rowspan="<?php echo $numMembers; ?>"></td>
        </tr>
        <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
        <tr>
            <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($member['student_name']); ?></td>
            <td class="mark"></td>
        </tr>
        <?php endfor; ?>
    <?php endforeach; ?>
    <?php if (empty($grouped)): ?>
        <tr><td colspan="<?php echo $stage === 'FYP Progress Presentation' ? 9 : 8; ?>" class="center" style="padding: 20px; color: #999;">No approved projects found.</td></tr>
    <?php endif; ?>
    </tbody>

<?php elseif ($stage === 'Final Presentation'): ?>
    <thead>
        <tr>
            <th rowspan="2" style="width: 22px;">S.<br>No</th>
            <th rowspan="2" style="width: 55px;">Project ID</th>
            <th rowspan="2">Title of Project</th>
            <th rowspan="2" style="width: 95px;">Primary Supervisor</th>
            <th colspan="2">Group Members</th>
            <th colspan="5">Presentation<br>(25 marks)</th>
            <th colspan="5">Thesis<br>(25 marks)</th>
            <th rowspan="2" class="vtext">Project Demo (25 marks)</th>
        </tr>
        <tr>
            <th style="width: 75px;">Roll No</th>
            <th style="min-width: 130px; width: auto;">Full Name</th>
            <!-- Presentation -->
            <th class="vtext">Contents (5)</th>
            <th class="vtext">Time spent (5)</th>
            <th class="vtext">Confidence (5)</th>
            <th class="vtext">Q & A (5)</th>
            <th class="vtext">Language used (5)</th>
            <!-- Thesis -->
            <th class="vtext">Contents (5)</th>
            <th class="vtext">Formatting (5)</th>
            <th class="vtext">Referencing (5)</th>
            <th class="vtext">Fig. & tables (5)</th>
            <th class="vtext">Completeness (5)</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $srNo = 1;
    foreach ($grouped as $groupId => $members): 
        $numMembers = count($members);
        $firstMember = $members[0];
    ?>
        <tr>
            <td rowspan="<?php echo $numMembers; ?>" class="center"><?php echo $srNo++; ?></td>
            <td rowspan="<?php echo $numMembers; ?>" class="center" style="font-size: 7pt;"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
            <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
            <?php for($k=0; $k<11; $k++): ?><td class="mark"></td><?php endfor; ?>
        </tr>
        <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
        <tr>
            <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($member['student_name']); ?></td>
            <?php for($k=0; $k<11; $k++): ?><td class="mark"></td><?php endfor; ?>
        </tr>
        <?php endfor; ?>
    <?php endforeach; ?>
    <?php if (empty($grouped)): ?>
        <tr><td colspan="17" class="center" style="padding: 20px; color: #999;">No approved projects found.</td></tr>
    <?php endif; ?>
    </tbody>
<?php endif; ?>
</table>

<div class="sig-line">Instructor's Signature: _______________</div>

</body>
</html>
