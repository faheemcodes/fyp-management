<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batchId = $batchId ?? 0;
$view = $view ?? 'minimized';
$dated = $dated ?? date('d-m-Y');
$stage = $stage ?? 'Proposal Defence Presentation';
$selectedCommittee = $selectedCommittee ?? 'all';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($stage, ENT_QUOTES, 'UTF-8'); ?> - Committee Evaluation Sheets</title>
    <!-- Include Bootstrap for toolbar only -->
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
            font-size: 11pt;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ─── Screen Floating Toolbar (Hidden in Print) ─── */
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
            width: 100%;
        }
        .toolbar-input-group select option {
            background: #1e293b;
            color: #f8fafc;
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
            transition: all 0.2s ease;
            white-space: nowrap;
            cursor: pointer;
        }
        .btn-toolbar-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5);
            color: #ffffff;
        }

        /* ─── Paper Layout (Screen Preview) ─── */
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

        /* ─── Header ─── */
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

        /* ─── Evaluator & Committee Row ─── */
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

        /* ─── Table ─── */
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

        /* Vertical text for sub-columns */
        table.sheet th.vtext {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            height: 95px;
            padding: 4px 2px;
            font-size: 8pt;
            width: 25px;
        }

        table.sheet td.mark {
            width: 25px;
            text-align: center;
            height: 24px;
        }

        /* Signature Line */
        .sig-line {
            text-align: right;
            margin-top: 24px;
            font-size: 10.5pt;
            font-weight: bold;
        }

        /* ─── Print Styles & Page Breaks ─── */
        @media print {
            @page {
                size: landscape;
                margin: 8mm;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                font-size: 9pt !important;
            }

            .no-print-toolbar,
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
                page-break-after: always !important;
                break-after: page !important;
            }

            .sheet-page:last-child {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            .report-header .dept { font-size: 11.5pt !important; }
            .report-header .batch { font-size: 9pt !important; }
            .report-header .stage-title { font-size: 10.5pt !important; }
            .evaluator-row { font-size: 8.5pt !important; margin: 6px 0 8px 0 !important; }

            table.sheet {
                border-collapse: collapse !important;
                font-size: 8pt !important;
                width: 100% !important;
            }
            table.sheet th {
                font-size: 7.5pt !important;
                background-color: #e8e8e8 !important;
            }
            table.sheet td {
                font-size: 8pt !important;
            }
            table.sheet th.vtext {
                height: 75px !important;
                width: 20px !important;
                font-size: 6.5pt !important;
                padding: 3px 1px !important;
            }
            table.sheet td.mark {
                width: 20px !important;
                height: 20px !important;
            }
            .sig-line {
                font-size: 9pt !important;
                margin-top: 18px !important;
            }

            table.sheet,
            table.sheet thead,
            table.sheet tbody,
            table.sheet tr,
            table.sheet th,
            table.sheet td {
                border: 1pt solid black !important;
                background-clip: padding-box !important;
            }

            thead {
                display: table-header-group !important;
            }

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

    <!-- ═══════════════ Screen Floating Toolbar ═══════════════ -->
    <div class="no-print-toolbar no-print">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

            <!-- Left: Back Button & Title -->
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets" class="toolbar-back-btn">
                    <i class="bi bi-arrow-left"></i> <span>Back</span>
                </a>
                <div class="d-none d-md-block">
                    <div style="font-weight: 700; font-size: 0.95rem; color: #ffffff; line-height: 1.2;">
                        <i class="bi bi-printer me-1 text-info"></i> Evaluation Sheets
                    </div>
                    <small style="color: #94a3b8; font-size: 0.75rem;">
                        <?php echo htmlspecialchars($stage ?? '', ENT_QUOTES, 'UTF-8'); ?> <?php echo ($selectedCommittee === 'all') ? '(All Committees)' : '(Committee #' . htmlspecialchars((string)$selectedCommittee, ENT_QUOTES, 'UTF-8') . ')'; ?>
                    </small>
                </div>
            </div>

            <!-- Middle: Quick Filters Form -->
            <form action="<?php echo $basePath; ?>/coordinator/presentation-sheets/print" method="GET" class="d-flex align-items-center gap-2 m-0 flex-wrap">

                <!-- Stage Selector -->
                <div class="toolbar-input-group" style="width: 220px;" title="Select Presentation Stage">
                    <span class="toolbar-icon"><i class="bi bi-file-earmark-text"></i></span>
                    <select name="stage" onchange="this.form.submit()">
                        <option value="Proposal Defence Presentation" <?php echo ($stage === 'Proposal Defence Presentation') ? 'selected' : ''; ?>>Proposal Defence</option>
                        <option value="FYP Progress Presentation" <?php echo ($stage === 'FYP Progress Presentation') ? 'selected' : ''; ?>>FYP Progress</option>
                        <option value="Final Presentation" <?php echo ($stage === 'Final Presentation') ? 'selected' : ''; ?>>Final Presentation</option>
                    </select>
                </div>

                <!-- Final Presentation View Mode (Only when stage is Final Presentation) -->
                <?php if ($stage === 'Final Presentation'): ?>
                <div class="toolbar-input-group" style="width: 170px;" title="Layout Format">
                    <span class="toolbar-icon"><i class="bi bi-layout-split"></i></span>
                    <select name="view" onchange="this.form.submit()">
                        <option value="minimized" <?php echo ($view === 'minimized') ? 'selected' : ''; ?>>Minimize Version</option>
                        <option value="detailed" <?php echo ($view === 'detailed') ? 'selected' : ''; ?>>Detailed Version</option>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Committee Selector -->
                <div class="toolbar-input-group" style="width: 170px;" title="Select Committee">
                    <span class="toolbar-icon"><i class="bi bi-people"></i></span>
                    <select name="committee" onchange="this.form.submit()">
                        <option value="all" <?php echo ($selectedCommittee === 'all') ? 'selected' : ''; ?>>All Committees</option>
                        <?php foreach (array_keys($committeesGrouped) as $cNum): ?>
                            <option value="<?php echo (int)$cNum; ?>" <?php echo ($selectedCommittee == $cNum) ? 'selected' : ''; ?>>
                                Committee #<?php echo (int)$cNum; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Batch Selector -->
                <div class="toolbar-input-group" style="width: 150px;" title="Academic Batch">
                    <span class="toolbar-icon"><i class="bi bi-mortarboard"></i></span>
                    <select name="batch_id" onchange="this.form.submit()">
                        <?php foreach ($batches as $b): ?>
                            <option value="<?php echo (int)$b['id']; ?>" <?php echo ($batchId == $b['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="dated" value="<?php echo htmlspecialchars($dated ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </form>

            <!-- Right: Print Button -->
            <div>
                <button type="button" onclick="window.print()" class="btn-toolbar-print">
                    <i class="bi bi-printer-fill"></i> <span>Print / PDF</span>
                </button>
            </div>

        </div>
    </div>

    <!-- ═══════════════ Printable Paper Container ═══════════════ -->
    <div class="sheet-wrapper">

        <?php if (empty($groupsByCommittee)): ?>
            <div class="sheet-page text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-muted">No Approved Groups Found</h5>
                <p class="text-secondary small">No groups match the selected criteria for this department and batch.</p>
                <a href="<?php echo $basePath; ?>/coordinator/presentation-sheets" class="btn btn-primary rounded-pill px-4">Back to Configuration</a>
            </div>
        <?php else: ?>

            <?php foreach ($groupsByCommittee as $cNum => $grouped): 
                $committeeEvaluators = $committeesGrouped[$cNum] ?? [];
                $evalNames = array_map(fn($m) => $m['name'], $committeeEvaluators);
                $evalNamesStr = !empty($evalNames) ? implode(', ', $evalNames) : 'Committee Members';
            ?>

                <div class="sheet-page">

                    <!-- Header -->
                    <div class="report-header">
                        <div class="dept">Department of <?php echo htmlspecialchars($department ?? '', ENT_QUOTES, 'UTF-8'); ?> - Faculty of Engineering and Technology</div>
                        <div class="batch">BS (Software Engineering) - <?php echo htmlspecialchars($batchName ?? '', ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($shift ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="stage-title">
                            <?php echo htmlspecialchars(($stage ?? '') . ($stage === 'Final Presentation' && $view === 'minimized' ? ' (Minimize Version)' : ''), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>

                    <!-- Evaluator & Committee Row -->
                    <div class="evaluator-row">
                        <div class="comm-badge-title">Committee: #<?php echo (int)$cNum; ?></div>
                        <div>Dated: <u><?php echo htmlspecialchars($dated ?? '', ENT_QUOTES, 'UTF-8'); ?></u></div>
                        <div>Evaluators' Name: <span style="text-decoration: underline; padding: 0 20px"><?php echo htmlspecialchars($evalNamesStr ?? '', ENT_QUOTES, 'UTF-8'); ?></span></div>
                        <div>Signature: _______________</div>
                    </div>

                    <!-- Evaluation Table -->
                    <table class="sheet">
                        <?php if ($stage === 'Proposal Defence Presentation' || $stage === 'FYP Progress Presentation'): ?>
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 25px">S.<br>No</th>
                                    <th rowspan="2" style="width: 65px">Project ID</th>
                                    <th rowspan="2">Title of Project</th>
                                    <th rowspan="2" style="width: 120px">Primary Supervisor</th>
                                    <th colspan="2">Group Members</th>
                                    <?php if ($stage === 'FYP Progress Presentation'): ?>
                                        <th rowspan="2" style="width: 140px">Previous comments</th>
                                    <?php endif; ?>
                                    <th rowspan="2" style="width: 50px">Marks<br>(40)</th>
                                    <th rowspan="2" style="width: 150px">Remarks</th>
                                </tr>
                                <tr>
                                    <th style="width: 85px">Roll No</th>
                                    <th style="width: 140px">Full Name</th>
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
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="center"><?php echo $srNo++; ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="center" style="font-size: 7.5pt"><?php echo htmlspecialchars($firstMember['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                        
                                        <td><?php echo htmlspecialchars($firstMember['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($firstMember['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        
                                        <?php if ($stage === 'FYP Progress Presentation'): ?>
                                            <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" style="font-size: 7pt;"><?php echo htmlspecialchars($firstMember['previous_comments'] ?: '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php endif; ?>
                                        
                                        <td class="mark"></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"></td>
                                    </tr>
                                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($member['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($member['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="mark"></td>
                                    </tr>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                                <?php if (empty($grouped)): ?>
                                    <tr><td colspan="<?php echo $stage === 'FYP Progress Presentation' ? 9 : 8; ?>" class="center" style="padding: 20px; color: #999">No approved projects allocated to Committee #<?php echo (int)$cNum; ?>.</td></tr>
                                <?php endif; ?>
                            </tbody>

                        <?php elseif ($stage === 'Final Presentation' && $view === 'minimized'): ?>
                            <!-- Final Presentation: Minimized Version (Merged Columns) -->
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 25px">S.<br>No</th>
                                    <th rowspan="2" style="width: 65px">Project ID</th>
                                    <th rowspan="2">Title of Project</th>
                                    <th rowspan="2" style="width: 120px">Primary Supervisor</th>
                                    <th colspan="2">Group Members</th>
                                    <th rowspan="2" style="width: 75px">Presentation<br>(25 marks)</th>
                                    <th rowspan="2" style="width: 75px">Thesis<br>(25 marks)</th>
                                    <th rowspan="2" style="width: 75px">Project Demo<br>(25 marks)</th>
                                    <th rowspan="2" style="width: 130px">Remarks</th>
                                </tr>
                                <tr>
                                    <th style="width: 85px">Roll No</th>
                                    <th style="width: 140px">Full Name</th>
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
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="center"><?php echo $srNo++; ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="center" style="font-size: 7.5pt"><?php echo htmlspecialchars($firstMember['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                        
                                        <td><?php echo htmlspecialchars($firstMember['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($firstMember['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        
                                        <td class="mark"></td>
                                        <td class="mark"></td>
                                        <td class="mark"></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"></td>
                                    </tr>
                                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($member['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($member['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="mark"></td>
                                        <td class="mark"></td>
                                        <td class="mark"></td>
                                    </tr>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                                <?php if (empty($grouped)): ?>
                                    <tr><td colspan="10" class="center" style="padding: 20px; color: #999">No approved projects allocated to Committee #<?php echo (int)$cNum; ?>.</td></tr>
                                <?php endif; ?>
                            </tbody>

                        <?php elseif ($stage === 'Final Presentation'): ?>
                            <!-- Final Presentation: Detailed Version (5 sub-columns each) -->
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 22px">S.<br>No</th>
                                    <th rowspan="2" style="width: 55px">Project ID</th>
                                    <th rowspan="2">Title of Project</th>
                                    <th rowspan="2" style="width: 95px">Primary Supervisor</th>
                                    <th colspan="2">Group Members</th>
                                    <th colspan="5">Presentation<br>(25 marks)</th>
                                    <th colspan="5">Thesis<br>(25 marks)</th>
                                    <th rowspan="2" class="vtext">Project Demo (25 marks)</th>
                                </tr>
                                <tr>
                                    <th style="width: 75px">Roll No</th>
                                    <th style="min-width: 130px; width: auto">Full Name</th>
                                    <!-- Presentation -->
                                    <th class="vtext">Contents (5)</th>
                                    <th class="vtext">Time spent (5)</th>
                                    <th class="vtext">Confidence (5)</th>
                                    <th class="vtext">Q &amp; A (5)</th>
                                    <th class="vtext">Language used (5)</th>
                                    <!-- Thesis -->
                                    <th class="vtext">Contents (5)</th>
                                    <th class="vtext">Formatting (5)</th>
                                    <th class="vtext">Referencing (5)</th>
                                    <th class="vtext">Fig. &amp; tables (5)</th>
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
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="center"><?php echo $srNo++; ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>" class="center" style="font-size: 7pt"><?php echo htmlspecialchars($firstMember['group_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td rowspan="<?php echo htmlspecialchars((string)($numMembers), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($firstMember['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($firstMember['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php for($k=0; $k<11; $k++): ?><td class="mark"></td><?php endfor; ?>
                                    </tr>
                                    <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($member['roll_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($member['student_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php for($k=0; $k<11; $k++): ?><td class="mark"></td><?php endfor; ?>
                                    </tr>
                                    <?php endfor; ?>
                                <?php endforeach; ?>
                                <?php if (empty($grouped)): ?>
                                    <tr><td colspan="17" class="center" style="padding: 20px; color: #999">No approved projects allocated to Committee #<?php echo (int)$cNum; ?>.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>

                    <!-- Signature Line -->
                    <div class="sig-line">
                        Evaluators' Signature: _______________________________
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>
</html>
