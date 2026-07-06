<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Committee Evaluation Sheet - <?php echo htmlspecialchars($stage); ?></title>
    <!-- Include Bootstrap for base styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8fafc;
            color: #0f172a;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h4 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        .report-header p {
            margin: 0;
            font-size: 0.9rem;
            color: #475569;
        }
        .report-header .title-underline {
            text-decoration: underline;
            font-weight: 700;
            color: #000;
            margin-top: 5px;
        }
        .evaluator-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.95rem;
            font-weight: 600;
        }
        .eval-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            background: #fff;
        }
        .eval-table th, .eval-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .eval-table th {
            background-color: #e2e8f0;
            font-weight: 700;
            text-align: center;
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
            font-size: 0.75rem;
        }
        
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }
        
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
            body {
                background-color: #fff;
                padding: 0;
                margin: 0;
                font-size: 10pt;
            }
            .no-print {
                display: none !important;
            }
            .eval-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            thead {
                display: table-header-group;
            }
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            td, th {
                page-break-inside: avoid;
                break-inside: avoid;
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

    <div class="report-header">
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
                    <th rowspan="2" style="width: 80px;">Project ID</th>
                    <th rowspan="2" style="width: 150px;">Title of Project</th>
                    <th rowspan="2" style="width: 120px;">Primary Supervisor</th>
                    <th colspan="2">Group Members</th>
                    <?php if ($stage === 'FYP Progress Presentation'): ?>
                        <th rowspan="2" style="width: 200px;">Previous comments</th>
                    <?php endif; ?>
                    <th rowspan="2" style="width: 80px;">Marks (Out of 40)</th>
                </tr>
                <tr>
                    <th style="width: 90px;">Roll No</th>
                    <th style="width: 140px;">Full Name</th>
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
                            <td rowspan="<?php echo $numMembers; ?>" style="font-size: 0.75rem; color: #475569;"><?php echo htmlspecialchars($firstMember['previous_comments'] ?: 'None'); ?></td>
                        <?php endif; ?>
                        
                        <td></td>
                    </tr>
                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                            <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                            <td></td>
                        </tr>
                    <?php endfor; ?>
                <?php endforeach; ?>
                <?php if (empty($grouped)): ?>
                    <tr>
                        <td colspan="<?php echo $stage === 'FYP Progress Presentation' ? 8 : 7; ?>" class="text-center py-4 text-muted">No approved projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
        <?php elseif ($stage === 'Final Presentation'): ?>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;">Sr.<br>No</th>
                    <th rowspan="2" style="width: 80px;">Project ID</th>
                    <th rowspan="2" style="width: 150px;">Title of Project</th>
                    <th rowspan="2" style="width: 120px;">Primary Supervisor</th>
                    <th colspan="2">Group Members</th>
                    <th colspan="5">Presentation<br>(25 marks)</th>
                    <th colspan="5">Thesis<br>(25 marks)</th>
                    <th rowspan="2" class="vertical-text">Project<br>Demo<br>(25<br>marks)</th>
                </tr>
                <tr>
                    <th style="width: 90px;">Roll No</th>
                    <th style="width: 140px;">Full Name</th>
                    
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
                        
                        <?php for($k=0; $k<11; $k++): ?><td></td><?php endfor; ?>
                    </tr>
                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                            <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                            <?php for($k=0; $k<11; $k++): ?><td></td><?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                <?php endforeach; ?>
                <?php if (empty($grouped)): ?>
                    <tr>
                        <td colspan="17" class="text-center py-4 text-muted">No approved projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        <?php endif; ?>
    </table>

</body>
</html>
