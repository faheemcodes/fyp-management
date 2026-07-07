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
            font-size: 11pt;
            padding: 15px;
        }

        /* ─── Header ─── */
        .report-header {
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .report-header .dept {
            font-size: 12pt;
            font-weight: bold;
        }
        .report-header .batch {
            font-size: 10pt;
        }
        .report-header .stage-title {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 2px;
        }

        /* ─── Evaluator Info ─── */
        .evaluator-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin: 8px 0 10px 0;
            font-size: 10pt;
        }
        .evaluator-row span {
            font-weight: bold;
        }

        /* ─── Table ─── */
        table.sheet {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        table.sheet th,
        table.sheet td {
            border: 1px solid #000;
            padding: 3px 4px;
            vertical-align: middle;
        }
        table.sheet th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            font-size: 8pt;
        }
        table.sheet td {
            font-size: 9pt;
        }
        table.sheet td.center {
            text-align: center;
        }

        /* Vertical text for sub-columns */
        table.sheet th.vtext {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            height: 90px;
            padding: 4px 2px;
            font-size: 7.5pt;
            width: 22px;
            min-width: 22px;
            max-width: 22px;
        }

        /* Marks empty cells - fixed small width */
        table.sheet td.mark {
            width: 22px;
            min-width: 22px;
            max-width: 22px;
            text-align: center;
            height: 22px;
        }

        /* Signature */
        .sig-line {
            text-align: right;
            margin-top: 20px;
            font-size: 10pt;
            font-weight: bold;
        }

        /* ─── Print Button ─── */
        .no-print {
            text-align: center;
            margin-bottom: 15px;
            font-family: Arial, sans-serif;
        }
        .no-print button {
            padding: 8px 24px;
            font-size: 10pt;
            cursor: pointer;
            border-radius: 20px;
            border: 1px solid #ccc;
            margin: 0 5px;
        }
        .no-print .print-btn { background: #2563eb; color: #fff; border: none; font-weight: bold; }
        .no-print .back-btn { background: #f1f5f9; }

        /* ─── Print ─── */
        @media print {
            @page {
                size: landscape;
                margin: 8mm;
            }
            body { padding: 0; }
            .no-print { display: none !important; }
            table.sheet th,
            table.sheet td {
                border: 1px solid #000 !important;
            }
            table.sheet th {
                background: #e8e8e8 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; break-inside: avoid; }
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
    <div>Evaluators' Name: <span style="text-decoration: underline; padding: 0 60px;"><?php echo htmlspecialchars($committee['name'] ?? ''); ?></span></div>
    <div>Evaluators' Signature: __________________</div>
</div>

<table class="sheet">
<?php if ($stage === 'Proposal Defence Presentation' || $stage === 'FYP Progress Presentation'): ?>
    <thead>
        <tr>
            <th rowspan="2" style="width: 28px;">S.<br>No</th>
            <th rowspan="2" style="width: 70px;">Project ID</th>
            <th rowspan="2">Title of Project</th>
            <th rowspan="2" style="width: 120px;">Primary Supervisor</th>
            <th colspan="2">Group Members</th>
            <?php if ($stage === 'FYP Progress Presentation'): ?>
                <th rowspan="2" style="width: 160px;">Previous comments</th>
            <?php endif; ?>
            <th rowspan="2" style="width: 55px;">Marks<br>(Out of 40)</th>
            <th rowspan="2" style="width: 100px;">Remarks</th>
        </tr>
        <tr>
            <th style="width: 90px;">Roll No</th>
            <th style="width: 130px;">Full Name</th>
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
            <td rowspan="<?php echo $numMembers; ?>" class="center" style="font-size: 8pt;"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
            <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
            <?php if ($stage === 'FYP Progress Presentation'): ?>
                <td rowspan="<?php echo $numMembers; ?>" style="font-size: 7.5pt;"><?php echo htmlspecialchars($firstMember['previous_comments'] ?: ''); ?></td>
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
            <th rowspan="2" style="width: 28px;">S.<br>No</th>
            <th rowspan="2" style="width: 70px;">Project ID</th>
            <th rowspan="2">Title of Project</th>
            <th rowspan="2" style="width: 120px;">Primary Supervisor</th>
            <th colspan="2">Group Members</th>
            <th colspan="5">Presentation<br>(25 marks)</th>
            <th colspan="5">Thesis<br>(25 marks)</th>
            <th rowspan="2" class="vtext">Project Demo (25 marks)</th>
            <th rowspan="2" style="width: 70px;">Remarks</th>
        </tr>
        <tr>
            <th style="width: 90px;">Roll No</th>
            <th style="width: 130px;">Full Name</th>
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
            <td rowspan="<?php echo $numMembers; ?>" class="center" style="font-size: 8pt;"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
            <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
            <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
            <?php for($k=0; $k<11; $k++): ?><td class="mark"></td><?php endfor; ?>
            <td rowspan="<?php echo $numMembers; ?>"></td>
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
        <tr><td colspan="18" class="center" style="padding: 20px; color: #999;">No approved projects found.</td></tr>
    <?php endif; ?>
    </tbody>
<?php endif; ?>
</table>

<div class="sig-line">Instructor's Signature: __________________</div>

</body>
</html>
