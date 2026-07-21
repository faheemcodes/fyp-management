<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>External Assessment Report</title>
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
            /* Ensure table headers repeat on every printed page */
            thead {
                display: table-header-group;
            }
            /* Prevent rows from splitting across pages */
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            /* Prevent breaking inside cells */
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
        <h4>Department of <?php echo htmlspecialchars($department); ?> - Faculty of Engineering and Technology - University of Sindh</h4>
        <p>Final Presentation Sheet (External Assessment) - Shift: <?php echo htmlspecialchars($shift); ?></p>
    </div>

    <div class="evaluator-details">
        <div>Dated: <?php echo date('d-m-Y'); ?></div>
        <div>Evaluator's Name: _______________________</div>
        <div>Evaluator's Signature: _______________________</div>
    </div>

    <table class="eval-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 40px;">Sr. No</th>
                <th rowspan="2" style="width: 80px;">Project ID</th>
                <th rowspan="2">Title of Project</th>
                <th rowspan="2" style="width: 140px;">Primary Supervisor</th>
                <th colspan="2">Group Members</th>
                <?php foreach ($attributes as $attr): ?>
                    <th rowspan="2" style="width: 60px;"><?php echo htmlspecialchars($attr['name']); ?><br><small>(<?php echo htmlspecialchars((string)($attr['marks']), ENT_QUOTES, 'UTF-8'); ?> marks)</small></th>
                <?php endforeach; ?>
                <th rowspan="2" style="width: 60px;">Total<br><small>(50 marks)</small></th>
            </tr>
            <tr>
                <th style="width: 100px;">Roll No</th>
                <th style="width: 160px;">Name</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $srNo = 1;
            foreach ($grouped as $groupId => $members): 
                $numMembers = count($members);
                $firstMember = $members[0];
            ?>
                <!-- First row for the group includes the merged columns -->
                <tr>
                    <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="merged-cell"><?php echo $srNo++; ?></td>
                    <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="merged-cell"><?php echo htmlspecialchars($firstMember['group_code']); ?></td>
                    <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
                    <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
                    
                    <!-- First Member Details -->
                    <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
                    <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
                    
                    <!-- Evaluation Columns -->
                    <?php foreach ($attributes as $attr): ?>
                        <td></td>
                    <?php endforeach; ?>
                    <td></td>
                </tr>

                <!-- Remaining members of the same group -->
                <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                        <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                        <!-- Evaluation Columns -->
                        <?php foreach ($attributes as $attr): ?>
                            <td></td>
                        <?php endforeach; ?>
                        <td></td>
                    </tr>
                <?php endfor; ?>
                
            <?php endforeach; ?>
            
            <?php if (empty($grouped)): ?>
                <tr>
                    <td colspan="<?php echo 6 + count($attributes) + 1; ?>" class="text-center py-4 text-muted">No approved projects found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
