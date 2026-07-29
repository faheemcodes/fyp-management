<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Grading Report - Print</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #333;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 6px;
        }
        th {
            background-color: #e2e8f0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        
        .student-name { font-weight: bold; font-size: 13px; margin-bottom: 2px; }
        .project-title { color: #222; font-style: italic; margin-bottom: 3px; }
        .meta-info { font-size: 11px; color: #444; }
        
        .total-col { background-color: #f1f5f9 !important; font-weight: bold; }
        
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            th, .total-col {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Print Action Banner */
        .print-actions {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .print-actions button {
            padding: 10px 24px;
            font-size: 16px;
            cursor: pointer;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .print-actions button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button onclick="window.print()">Print Document</button>
        <p style="margin-top: 10px; font-size: 13px; color: #64748b; margin-bottom: 0;">Please set paper orientation to <strong>Landscape</strong> and enable <strong>Background Graphics</strong> in your printer settings.</p>
    </div>

    <div class="header">
        <h1>Final Grading Report</h1>
        <p>Overall Progress and Evaluations</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%">S.No</th>
                <th class="text-left" style="width: 40%">Student Details</th>
                <th style="width: 8%">Prop. Def.<br>(40)</th>
                <th style="width: 8%">Prog. Pres.<br>(40)</th>
                <th style="width: 8%">Supv.<br>(45)</th>
                <th style="width: 8%">Final Pres.<br>(75)</th>
                <th style="width: 8%" class="total-col">Total<br>(200)</th>
                <th style="width: 8%">Grade</th>
                <th style="width: 8%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sno = 1;
            if (!empty($studentGrades)):
                foreach ($studentGrades as $sg): 
            ?>
            <tr>
                <td class="text-center"><?php echo $sno++; ?></td>
                <td>
                    <div class="student-name">
                        <?php echo htmlspecialchars($sg['student_name']); ?> 
                        (<?php echo htmlspecialchars($sg['roll_no']); ?>)
                    </div>
                    <div class="project-title"><?php echo htmlspecialchars($sg['project_title']); ?></div>
                    <div class="meta-info">
                        <strong>Grp:</strong> <?php echo htmlspecialchars($sg['group_code'] ?? 'N/A'); ?> | 
                        <strong>Supv:</strong> <?php echo htmlspecialchars($sg['supervisor_name'] ?? 'Unassigned'); ?> |
                        <?php echo htmlspecialchars($sg['department'] ?? 'N/A'); ?> (<?php echo htmlspecialchars($sg['shift'] ?? 'N/A'); ?>)
                    </div>
                </td>
                <td class="text-center"><?php echo number_format($sg['proposal_defense_marks'] ?? 0, 0); ?></td>
                <td class="text-center"><?php echo number_format($sg['progress_presentation_marks'] ?? 0, 0); ?></td>
                <td class="text-center"><?php echo number_format($sg['supervision_marks'] ?? 0, 0); ?></td>
                <td class="text-center"><?php echo number_format($sg['final_presentation_marks'] ?? 0, 0); ?></td>
                <td class="text-center total-col"><?php echo number_format($sg['total_marks'] ?? 0, 0); ?></td>
                <td class="text-center" style="font-weight: bold; font-size: 14px;"><?php echo htmlspecialchars($sg['grade'] ?? 'F'); ?></td>
                <td class="text-center" style="font-weight: bold; font-size: 13px; color: <?php echo $sg['status'] === 'Pass' ? '#166534' : '#991b1b'; ?>;">
                    <?php echo htmlspecialchars($sg['status']); ?>
                </td>
            </tr>
            <?php 
                endforeach;
            else: 
            ?>
            <tr>
                <td colspan="9" class="text-center" style="padding: 30px;">No grading records available.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 800);
        };
    </script>
</body>
</html>
