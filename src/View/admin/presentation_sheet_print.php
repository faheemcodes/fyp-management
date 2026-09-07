<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$department = $department ?? 'Software Engineering';
$shift = $shift ?? 'Morning';
$batchName = $batchName ?? '2023';
$committeeNumber = $committeeNumber ?? 1;
$stage = $stage ?? 'Proposal Defence Presentation';
$date = $date ?? date('Y-m-d');
$time = $time ?? '09:00 AM';
$venue = $venue ?? 'FYP Lab';
$evaluators = $evaluators ?? [];
$groups = $groups ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($stage, ENT_QUOTES, 'UTF-8'); ?> - Committee #<?php echo (int)$committeeNumber; ?> Evaluation Sheet</title>
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
            font-size: 11pt;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print-toolbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #0f172a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
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
            transition: all 0.2s ease;
        }
        .toolbar-back-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
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
            cursor: pointer;
        }
        .btn-toolbar-print:hover {
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5);
            color: #ffffff;
        }

        .sheet-wrapper {
            max-width: 1100px;
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

        .report-header {
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .report-header .dept {
            font-size: 13pt;
            font-weight: bold;
        }
        .report-header .batch {
            font-size: 10.5pt;
        }
        .report-header .stage-title {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 3px;
        }

        .evaluator-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin: 8px 0 12px 0;
            font-size: 10pt;
            flex-wrap: wrap;
            gap: 6px;
        }

        .comm-badge-title {
            font-weight: bold;
            font-size: 10.5pt;
            color: #000;
        }

        table.sheet {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            table-layout: auto;
        }
        table.sheet th,
        table.sheet td {
            border: 1.5px solid #000;
            padding: 4px 5px;
            vertical-align: middle;
        }
        table.sheet th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
        }
        table.sheet td.center {
            text-align: center;
        }
        table.sheet td.mark {
            width: 50px;
            text-align: center;
            height: 26px;
        }

        .sig-block {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-item {
            text-align: center;
            min-width: 180px;
            font-size: 9.5pt;
        }
        .sig-line {
            border-top: 1.5px solid #000;
            margin-bottom: 5px;
            width: 100%;
        }

        @media print {
            @page {
                size: landscape;
                margin: 8mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                font-size: 9pt !important;
            }
            .no-print {
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
                margin-bottom: 0 !important;
            }
            table.sheet th, table.sheet td {
                border: 1pt solid black !important;
            }
            tr {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    <!-- ═══════════════ Screen Floating Toolbar ═══════════════ -->
    <div class="no-print-toolbar no-print">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo $basePath; ?>/admin/presentation-sheets?department=<?php echo urlencode($department); ?>&shift=<?php echo urlencode($shift); ?>&committee_number=<?php echo (int)$committeeNumber; ?>" class="toolbar-back-btn">
                    <i class="bi bi-arrow-left"></i> <span>Back to Config</span>
                </a>
                <div>
                    <div style="font-weight: 700; font-size: 0.95rem; color: #ffffff; line-height: 1.2;">
                        <i class="bi bi-printer me-1 text-info"></i> Evaluation Sheet: <?php echo htmlspecialchars($stage, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <small style="color: #94a3b8; font-size: 0.75rem;">
                        <?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?> &bull; Committee #<?php echo (int)$committeeNumber; ?> &bull; <?php echo htmlspecialchars($shift, ENT_QUOTES, 'UTF-8'); ?>
                    </small>
                </div>
            </div>

            <div>
                <button type="button" onclick="window.print()" class="btn-toolbar-print">
                    <i class="bi bi-printer-fill"></i> <span>Print / Save PDF</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════ Printable Paper Container ═══════════════ -->
    <div class="sheet-wrapper">
        <div class="sheet-page">

            <!-- Official Report Header -->
            <div class="report-header">
                <div class="dept">Department of <?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?> - Faculty of Engineering and Technology</div>
                <div class="batch">BS (<?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?>) - Batch <?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($shift, ENT_QUOTES, 'UTF-8'); ?>)</div>
                <div class="stage-title">
                    <?php echo htmlspecialchars($stage, ENT_QUOTES, 'UTF-8'); ?> — Official Evaluation Sheet
                </div>
            </div>

            <!-- Committee & Session Metadata -->
            <div class="evaluator-row">
                <div class="comm-badge-title">Committee: #<?php echo (int)$committeeNumber; ?></div>
                <div>Date: <u><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></u> (<?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?>)</div>
                <div>Venue: <u><?php echo htmlspecialchars($venue, ENT_QUOTES, 'UTF-8'); ?></u></div>
                <div>Evaluators: 
                    <?php 
                    if (!empty($evaluators)) {
                        $eNames = array_map(fn($ev) => htmlspecialchars($ev['name'] ?? '', ENT_QUOTES, 'UTF-8'), $evaluators);
                        echo implode(', ', $eNames);
                    } else {
                        echo "_________________________________";
                    }
                    ?>
                </div>
            </div>

            <!-- Evaluation Table -->
            <table class="sheet">
                <?php if ($stage === 'Proposal Defence Presentation' || $stage === 'FYP Progress Presentation'): ?>
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 30px">S.<br>No</th>
                            <th rowspan="2" style="width: 75px">Group ID</th>
                            <th rowspan="2">Title of Project</th>
                            <th rowspan="2" style="width: 130px">Primary Supervisor</th>
                            <th colspan="2">Group Members</th>
                            <?php if ($stage === 'FYP Progress Presentation'): ?>
                                <th rowspan="2" style="width: 140px">Previous Comments</th>
                            <?php endif; ?>
                            <th rowspan="2" style="width: 60px">Marks<br>(40)</th>
                            <th rowspan="2" style="width: 150px">Remarks / Status</th>
                        </tr>
                        <tr>
                            <th style="width: 90px">Roll No</th>
                            <th style="width: 140px">Full Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $srNo = 1;
                        if (!empty($groups)):
                            foreach ($groups as $grp):
                                $members = !empty($grp['members']) ? $grp['members'] : [['name' => '-', 'student_id' => '-']];
                                $numMembers = count($members);
                                $firstMember = $members[0];
                        ?>
                            <tr>
                                <td rowspan="<?php echo (int)$numMembers; ?>" class="center fw-bold"><?php echo $srNo++; ?></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>" class="center font-monospace" style="font-size: 8.5pt"><?php echo htmlspecialchars($grp['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>" style="font-weight: 500;"><?php echo htmlspecialchars($grp['project_title'] ?: 'Untitled Project', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>"><?php echo htmlspecialchars($grp['supervisor_name'] ?: 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                
                                <td class="center font-monospace" style="font-size: 8.5pt;"><?php echo htmlspecialchars($firstMember['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($firstMember['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                
                                <?php if ($stage === 'FYP Progress Presentation'): ?>
                                    <td rowspan="<?php echo (int)$numMembers; ?>" style="font-size: 8pt;"></td>
                                <?php endif; ?>
                                
                                <td class="mark"></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>"></td>
                            </tr>
                            <?php for ($i = 1; $i < $numMembers; $i++): $m = $members[$i]; ?>
                            <tr>
                                <td class="center font-monospace" style="font-size: 8.5pt;"><?php echo htmlspecialchars($m['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($m['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="mark"></td>
                            </tr>
                            <?php endfor; ?>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="<?php echo ($stage === 'FYP Progress Presentation') ? 9 : 8; ?>" class="center py-4 text-muted">
                                    No approved groups allocated to Committee #<?php echo (int)$committeeNumber; ?> for this department and batch.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                <?php else: /* Final Presentation */ ?>
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 30px">S.<br>No</th>
                            <th rowspan="2" style="width: 75px">Group ID</th>
                            <th rowspan="2">Title of Project</th>
                            <th rowspan="2" style="width: 130px">Primary Supervisor</th>
                            <th colspan="2">Group Members</th>
                            <th rowspan="2" style="width: 65px">Pres.<br>(25)</th>
                            <th rowspan="2" style="width: 65px">Thesis<br>(25)</th>
                            <th rowspan="2" style="width: 65px">Demo<br>(25)</th>
                            <th rowspan="2" style="width: 60px">Total<br>(75)</th>
                            <th rowspan="2" style="width: 130px">Remarks</th>
                        </tr>
                        <tr>
                            <th style="width: 90px">Roll No</th>
                            <th style="width: 140px">Full Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $srNo = 1;
                        if (!empty($groups)):
                            foreach ($groups as $grp):
                                $members = !empty($grp['members']) ? $grp['members'] : [['name' => '-', 'student_id' => '-']];
                                $numMembers = count($members);
                                $firstMember = $members[0];
                        ?>
                            <tr>
                                <td rowspan="<?php echo (int)$numMembers; ?>" class="center fw-bold"><?php echo $srNo++; ?></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>" class="center font-monospace" style="font-size: 8.5pt"><?php echo htmlspecialchars($grp['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>" style="font-weight: 500;"><?php echo htmlspecialchars($grp['project_title'] ?: 'Untitled Project', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>"><?php echo htmlspecialchars($grp['supervisor_name'] ?: 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                
                                <td class="center font-monospace" style="font-size: 8.5pt;"><?php echo htmlspecialchars($firstMember['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($firstMember['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                
                                <td class="mark"></td>
                                <td class="mark"></td>
                                <td class="mark"></td>
                                <td class="mark fw-bold"></td>
                                <td rowspan="<?php echo (int)$numMembers; ?>"></td>
                            </tr>
                            <?php for ($i = 1; $i < $numMembers; $i++): $m = $members[$i]; ?>
                            <tr>
                                <td class="center font-monospace" style="font-size: 8.5pt;"><?php echo htmlspecialchars($m['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($m['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="mark"></td>
                                <td class="mark"></td>
                                <td class="mark"></td>
                                <td class="mark fw-bold"></td>
                            </tr>
                            <?php endfor; ?>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="11" class="center py-4 text-muted">
                                    No approved groups allocated to Committee #<?php echo (int)$committeeNumber; ?> for this department and batch.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                <?php endif; ?>
            </table>

            <!-- Signatures Section -->
            <div class="sig-block">
                <div class="sig-item">
                    <div class="sig-line"></div>
                    <div>Evaluator 1 (Signature &amp; Date)</div>
                </div>
                <div class="sig-item">
                    <div class="sig-line"></div>
                    <div>Evaluator 2 (Signature &amp; Date)</div>
                </div>
                <div class="sig-item">
                    <div class="sig-line"></div>
                    <div>FYP Coordinator / Admin</div>
                </div>
                <div class="sig-item">
                    <div class="sig-line"></div>
                    <div>Head of Department</div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
