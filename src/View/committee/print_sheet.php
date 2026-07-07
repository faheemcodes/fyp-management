<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Committee Evaluation Sheet - <?php echo htmlspecialchars($stage); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 18px;
        }
        .report-header h4 {
            font-weight: 700;
            margin-bottom: 4px;
            font-size: 1.1rem;
        }
        .report-header p {
            margin: 0;
            font-size: 0.85rem;
            color: #475569;
        }
        .report-header .title-underline {
            text-decoration: underline;
            font-weight: 700;
            color: #000;
            margin-top: 4px;
        }
        .evaluator-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .signature-line {
            text-align: right;
            margin-top: 30px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* ─── Table Styles ─── */
        .eval-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
            background: #fff;
            table-layout: fixed;
        }
        .eval-table th, .eval-table td {
            border: 1.5px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .eval-table th {
            background-color: #e2e8f0;
            font-weight: 700;
            text-align: center;
            font-size: 0.75rem;
        }
        .eval-table td.merged-cell {
            text-align: center;
        }
        .eval-table .vertical-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            height: 100px;
            padding: 5px 2px;
            font-size: 0.7rem;
        }
        .eval-table td.marks-cell {
            min-height: 28px;
            height: 28px;
        }

        /* ─── No Print ─── */
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }

        /* ─── Print Specific Styles ─── */
        @media print {
            @page {
                size: landscape;
                margin: 8mm;
            }
            body {
                background-color: #fff !important;
                padding: 0;
                margin: 0;
                font-size: 9pt;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .eval-table {
                border-collapse: collapse !important;
            }
            .eval-table th, .eval-table td {
                border: 1.5px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .eval-table th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            thead {
                display: table-header-group;
            }
            tbody {
                display: table-row-group;
            }
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .signature-line {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-printer-fill me-2"></i> Print Sheet
        </button>
        <button onclick="window.history.back()" class="btn btn-outline-secondary rounded-pill px-4 fw-bold ms-2">
            Go Back
        </button>
    </div>

    <?php
    // Build header text
    $headerText = 'Committee Evaluation Form - ' . htmlspecialchars($stage);
    ?>

    <div class="report-header">
        <small style="font-size: 0.75rem; color: #64748b;"><?php echo $headerText; ?></small>
        <h4>Department of <?php echo htmlspecialchars($committee['department'] ?? 'Software Engineering'); ?>, Faculty of Engineering & Technology</h4>
        <p>FYP Session <?php echo date('Y') + 1; ?>, Batch 2k<?php echo date('y') - 4; ?>, BS (Software Engineering) Morning</p>
        <div class="title-underline"><?php echo htmlspecialchars($stage); ?></div>
    </div>

    <div class="evaluator-details">
        <div>Dated: <?php echo date('d M Y'); ?></div>
        <div>Instructor's Name: <span style="text-decoration: underline; padding: 0 50px;"><?php echo htmlspecialchars($committee['name'] ?? '_______________________'); ?></span></div>
        <div>Instructor's Signature: _______________________</div>
    </div>

    <table class="eval-table">
        <?php if ($stage === 'FYP Progress Presentation' || $stage === 'Proposal Defence Presentation'): ?>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;">Sr.<br>No</th>
                    <th rowspan="2" style="width: 75px;">Project ID</th>
                    <th rowspan="2" style="width: 140px;">Title of Project</th>
                    <th rowspan="2" style="width: 100px;">Primary Supervisor</th>
                    <th colspan="2">Group Members</th>
                    <?php if ($stage === 'FYP Progress Presentation'): ?>
                        <th rowspan="2" style="width: 180px;">Previous comments</th>
                    <?php endif; ?>
                    <th rowspan="2" style="width: 70px;">Marks<br>(Out of 40)</th>
                    <th rowspan="2" style="width: 120px;">Remarks</th>
                </tr>
                <tr>
                    <th style="width: 85px;">Roll No</th>
                    <th style="width: 120px;">Full Name</th>
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
                        <td rowspan="<?php echo $numMembers; ?>" class="merged-cell"><?php echo $srNo++; ?></td>
                        <td rowspan="<?php echo $numMembers; ?>" class="merged-cell"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
                        <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
                        <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
                        
                        <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
                        <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
                        
                        <?php if ($stage === 'FYP Progress Presentation'): ?>
                            <td rowspan="<?php echo $numMembers; ?>" style="font-size: 0.7rem; color: #475569;"><?php echo htmlspecialchars($firstMember['previous_comments'] ?: ''); ?></td>
                        <?php endif; ?>
                        
                        <td class="marks-cell"></td>
                        <td rowspan="<?php echo $numMembers; ?>"></td>
                    </tr>
                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                            <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                            <td class="marks-cell"></td>
                        </tr>
                    <?php endfor; ?>
                <?php endforeach; ?>
                <?php if (empty($grouped)): ?>
                    <tr>
                        <td colspan="<?php echo $stage === 'FYP Progress Presentation' ? 9 : 8; ?>" class="text-center py-4 text-muted">No approved projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
        <?php elseif ($stage === 'Final Presentation'): ?>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 28px;">Sr.<br>No</th>
                    <th rowspan="2" style="width: 70px;">Project ID</th>
                    <th rowspan="2" style="width: 130px;">Title of Project</th>
                    <th rowspan="2" style="width: 95px;">Primary Supervisor</th>
                    <th colspan="2">Group Members</th>
                    <th colspan="5">Presentation<br>(25 marks)</th>
                    <th colspan="5">Thesis<br>(25 marks)</th>
                    <th rowspan="2" class="vertical-text">Project<br>Demo<br>(25<br>marks)</th>
                    <th rowspan="2" style="width: 80px;">Remarks</th>
                </tr>
                <tr>
                    <th style="width: 80px;">Roll No</th>
                    <th style="width: 110px;">Full Name</th>
                    
                    <!-- Presentation Sub -->
                    <th class="vertical-text">Contents (5)</th>
                    <th class="vertical-text">Time spent (5)</th>
                    <th class="vertical-text">Confidence (5)</th>
                    <th class="vertical-text">Q & A (5)</th>
                    <th class="vertical-text">Language used (5)</th>
                    
                    <!-- Thesis Sub -->
                    <th class="vertical-text">Contents (5)</th>
                    <th class="vertical-text">Formatting (5)</th>
                    <th class="vertical-text">Referencing (5)</th>
                    <th class="vertical-text">Fig. & tables (5)</th>
                    <th class="vertical-text">Completeness (5)</th>
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
                        <td rowspan="<?php echo $numMembers; ?>" class="merged-cell"><?php echo $srNo++; ?></td>
                        <td rowspan="<?php echo $numMembers; ?>" class="merged-cell"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
                        <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
                        <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
                        
                        <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
                        <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
                        
                        <?php for($k=0; $k<11; $k++): ?><td class="marks-cell"></td><?php endfor; ?>
                        <td rowspan="<?php echo $numMembers; ?>"></td>
                    </tr>
                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                            <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                            <?php for($k=0; $k<11; $k++): ?><td class="marks-cell"></td><?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                <?php endforeach; ?>
                <?php if (empty($grouped)): ?>
                    <tr>
                        <td colspan="18" class="text-center py-4 text-muted">No approved projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        <?php endif; ?>
    </table>

    <div class="signature-line">
        Instructor's Signature: _______________________
    </div>

</body>
</html>
