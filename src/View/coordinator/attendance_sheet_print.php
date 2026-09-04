<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$batchId = $batchId ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($presentationName ?? 'Presentation', ENT_QUOTES, 'UTF-8'); ?> - Attendance Sheet</title>
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
            color: #0f172a;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
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
            -webkit-backdrop-filter: blur(12px);
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
        .toolbar-input-group input::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }
        .toolbar-input-group select option {
            background: #0f172a;
            color: #f8fafc;
            padding: 6px 10px;
        }

        .btn-toolbar-update {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #f1f5f9;
            border-radius: 999px;
            padding: 7px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            height: 38px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-toolbar-update:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.35);
            color: #ffffff;
        }

        .btn-toolbar-print {
            background: linear-gradient(135deg, #047fb0 0%, #0284c7 100%);
            border: none;
            color: #ffffff;
            border-radius: 999px;
            padding: 7px 20px;
            font-size: 0.82rem;
            font-weight: 700;
            height: 38px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 10px rgba(4, 127, 176, 0.4);
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-toolbar-print:hover {
            box-shadow: 0 4px 16px rgba(4, 127, 176, 0.6);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .paper-container {
            max-width: 960px;
            margin: 24px auto 40px auto;
        }

        /* ─── Individual Committee Sheet ─── */
        .committee-sheet {
            background: #ffffff;
            width: 100%;
            padding: 24px 28px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-radius: 4px;
            position: relative;
        }

        /* ─── Sheet Header (Matching Physical Demo Sheet) ─── */
        .sheet-header {
            text-align: center;
            margin-bottom: 14px;
            padding-bottom: 10px;
        }
        .sheet-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            margin-bottom: 6px;
        }
        .header-dept-line {
            font-size: 11pt;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.35;
            margin-bottom: 2px;
        }
        .header-session-line {
            font-size: 10pt;
            font-weight: 600;
            color: #334155;
            line-height: 1.35;
            margin-bottom: 6px;
        }
        .header-title-badge {
            font-size: 12.5pt;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .header-presentation-sub {
            font-size: 10.5pt;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 8px;
        }
        .header-committee-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px 12px;
            font-size: 9pt;
            margin-top: 6px;
        }
        .header-committee-info strong {
            color: #0f172a;
        }

        /* ─── Attendance Table Layout ─── */
        .att-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.2pt;
            background: #ffffff;
            margin-bottom: 16px;
        }
        .att-table th, 
        .att-table td {
            border: 1px solid #000000;
            padding: 5px 7px;
            color: #000000;
        }
        .att-table th {
            background-color: #e2e8f0 !important;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            font-size: 9.5pt;
            padding: 7px 5px;
        }
        .att-table td.sr-col {
            text-align: center;
            vertical-align: middle;
            font-weight: 600;
            width: 48px;
        }
        .att-table td.project-id-col {
            text-align: center;
            vertical-align: middle;
            font-weight: 700;
            font-family: 'Courier New', Courier, monospace;
            width: 125px;
            font-size: 9.5pt;
        }
        .att-table td.project-title-col {
            vertical-align: middle;
            font-weight: 500;
            line-height: 1.35;
        }
        .att-table td.roll-col {
            width: 110px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            font-weight: 600;
            vertical-align: middle;
            white-space: nowrap;
        }
        .att-table td.name-col {
            width: 165px;
            vertical-align: middle;
            font-weight: 500;
        }
        .att-table td.sig-col {
            width: 125px;
            vertical-align: middle;
            background: #ffffff;
            min-height: 32px;
        }

        /* ─── Signatures Block at Bottom ─── */
        .sheet-footer-signatures {
            margin-top: 26px;
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
        }
        .sig-box {
            text-align: center;
            flex: 1;
            max-width: 250px;
        }
        .sig-line {
            border-bottom: 1.5px solid #000;
            margin-bottom: 6px;
            height: 36px;
        }
        .sig-name {
            font-size: 9pt;
            font-weight: 700;
            color: #000;
        }
        .sig-role {
            font-size: 8pt;
            color: #475569;
        }

        /* ─── Print Styles & Page Breaks ─── */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 10mm 12mm 10mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 9pt !important;
            }
            .no-print-toolbar,
            .no-print {
                display: none !important;
            }
            .paper-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .committee-sheet {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin-bottom: 0 !important;
                page-break-after: always !important;
                break-after: page !important;
            }
            .committee-sheet:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }
            .att-table {
                page-break-inside: auto;
            }
            .att-table tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .att-table th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .header-presentation-sub {
                color: #000000 !important;
            }
            .header-committee-info {
                border-color: #000000 !important;
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .sheet-footer-signatures {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- ═══════════════ Screen Floating Toolbar (Hidden when Printing) ═══════════════ -->
    <div class="no-print-toolbar no-print">
        <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-3 px-2">
            
            <!-- Left: Back to Attendance Sheet & Context Info -->
            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo $basePath; ?>/coordinator/attendance-sheet" class="toolbar-back-btn">
                    <i class="bi bi-arrow-left"></i> <span>Back to Attendance Sheet</span>
                </a>
                <div class="d-none d-lg-flex align-items-center gap-2 ps-2" style="border-left: 1px solid rgba(255, 255, 255, 0.12);">
                    <span class="badge rounded-pill" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-weight: 600; font-size: 0.74rem;">
                        <i class="bi bi-printer me-1"></i> Print Preview
                    </span>
                    <span class="text-white-50 small fw-medium" style="font-size: 0.8rem;">
                        <?php echo htmlspecialchars($department ?? '', ENT_QUOTES, 'UTF-8'); ?> &bull; <?php echo htmlspecialchars($shift ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
            </div>

            <!-- Right: Quick Filters & Actions -->
            <form action="<?php echo $basePath; ?>/coordinator/attendance-sheet/print" method="GET" class="d-flex align-items-center gap-2 m-0 flex-wrap">
                
                <!-- Presentation Title Filter -->
                <div class="toolbar-input-group" style="width: 220px;" title="Presentation Title">
                    <span class="toolbar-icon"><i class="bi bi-card-heading"></i></span>
                    <input type="text" name="presentation_name" value="<?php echo htmlspecialchars($presentationName ?? 'Proposal Defense', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Presentation Title">
                </div>

                <!-- Committee Selector Filter -->
                <div class="toolbar-input-group" style="width: 200px;" title="Filter by Committee">
                    <span class="toolbar-icon"><i class="bi bi-people-fill"></i></span>
                    <select name="committee">
                        <option value="all" <?php echo $selectedCommittee === 'all' ? 'selected' : ''; ?>>All Committees (Separate)</option>
                        <?php foreach ($committeesGrouped as $cNum => $members): ?>
                            <option value="<?php echo (int)$cNum; ?>" <?php echo (string)$selectedCommittee === (string)$cNum ? 'selected' : ''; ?>>
                                Committee <?php echo (int)$cNum; ?> (<?php echo count($members); ?> Evaluators)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if (!empty($batchId)): ?>
                    <input type="hidden" name="batch_id" value="<?php echo (int)$batchId; ?>">
                <?php endif; ?>
                <input type="hidden" name="session_year" value="<?php echo htmlspecialchars($sessionYear ?? date('Y'), ENT_QUOTES, 'UTF-8'); ?>">

                <!-- Update Button -->
                <button type="submit" class="btn-toolbar-update">
                    <i class="bi bi-arrow-repeat"></i> <span>Update</span>
                </button>

                <!-- Print / Save PDF Button -->
                <button type="button" onclick="window.print()" class="btn-toolbar-print">
                    <i class="bi bi-printer-fill"></i> <span>Print / Save PDF</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════════ Printable Paper Container ═══════════════ -->
    <div class="paper-container">

        <?php if (empty($groupsByCommittee)): ?>
            <div class="committee-sheet text-center py-5">
                <i class="bi bi-exclamation-circle text-warning fs-1 d-block mb-3"></i>
                <h5 class="fw-bold">No Approved Projects Found</h5>
                <p class="text-muted">There are no approved projects for this department and shift to generate attendance sheets.</p>
                <a href="<?php echo $basePath; ?>/coordinator/attendance-sheet" class="btn btn-primary rounded-pill px-4">Back to Attendance Sheet</a>
            </div>
        <?php else: ?>

            <?php 
            $committeeIndex = 0;
            $globalSerial = 1;
            foreach ($groupsByCommittee as $cNum => $committeeGroups): 
                $committeeIndex++;
                // Evaluators for this committee
                $evaluators = $committeesGrouped[$cNum] ?? [];
                $evaluatorNames = array_map(fn($e) => $e['name'], $evaluators);
                $evaluator1 = $evaluatorNames[0] ?? 'Committee Evaluator 1';
                $evaluator2 = $evaluatorNames[1] ?? 'Committee Evaluator 2';
                $committeeLabel = $cNum > 0 ? "Committee " . str_pad((string)$cNum, 2, '0', STR_PAD_LEFT) : "Unassigned Committee";
            ?>

            <div class="committee-sheet">

                <!-- Header Block matching physical document -->
                <div class="sheet-header">
                    <div class="header-dept-line">
                        Department of <?php echo htmlspecialchars($department); ?>, Faculty of Engineering &amp; Technology
                    </div>
                    
                    <div class="header-session-line">
                        FYP Session <?php echo htmlspecialchars($sessionYear); ?>, Batch <?php echo htmlspecialchars($batchName); ?>, BS (<?php echo htmlspecialchars($department); ?>) <?php echo htmlspecialchars($shift); ?>
                    </div>
                    
                    <div class="header-title-badge">
                        ATTENDANCE SHEET
                    </div>
                    
                    <div class="header-presentation-sub">
                        <?php echo htmlspecialchars($presentationName); ?>
                    </div>

                    <div class="header-committee-info">
                        <div>
                            <strong><?php echo htmlspecialchars($committeeLabel); ?></strong>
                            <span class="text-muted ms-2">(<?php echo count($committeeGroups); ?> Projects Allocated)</span>
                        </div>
                        <div>
                            <strong>Evaluators:</strong> 
                            <?php if (!empty($evaluatorNames)): ?>
                                <?php echo htmlspecialchars(implode('  •  ', $evaluatorNames), ENT_QUOTES, 'UTF-8'); ?>
                            <?php else: ?>
                                <span class="text-muted">Not Assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Main Attendance Table -->
                <table class="att-table">
                    <thead>
                        <tr>
                            <th style="width: 48px;">Sr.<br>Nr.</th>
                            <th style="width: 125px;">Project ID</th>
                            <th>Title of Project</th>
                            <th colspan="2" style="width: 275px;">Group Members</th>
                            <th style="width: 125px;">Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($committeeGroups)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No approved projects currently allocated to <?php echo htmlspecialchars($committeeLabel); ?>.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $localSr = 1;
                            foreach ($committeeGroups as $grp): 
                                $members = $grp['members'] ?? [];
                                $memberCount = count($members);
                                if ($memberCount < 1) {
                                    $members = [['roll_no' => '—', 'name' => 'Pending Student']];
                                    $memberCount = 1;
                                }
                            ?>
                                <!-- Row for 1st Member (with merged group details) -->
                                <tr>
                                    <td rowspan="<?php echo $memberCount; ?>" class="sr-col">
                                        <?php echo $localSr++; ?>
                                    </td>
                                    <td rowspan="<?php echo $memberCount; ?>" class="project-id-col">
                                        <?php echo htmlspecialchars($grp['group_code']); ?>
                                    </td>
                                    <td rowspan="<?php echo $memberCount; ?>" class="project-title-col">
                                        <?php echo htmlspecialchars($grp['project_title']); ?>
                                    </td>
                                    <td class="roll-col">
                                        <?php echo htmlspecialchars($members[0]['roll_no']); ?>
                                    </td>
                                    <td class="name-col">
                                        <?php echo htmlspecialchars($members[0]['name']); ?>
                                    </td>
                                    <td class="sig-col"></td>
                                </tr>

                                <!-- Additional Members Rows -->
                                <?php for ($m = 1; $m < $memberCount; $m++): ?>
                                    <tr>
                                        <td class="roll-col">
                                            <?php echo htmlspecialchars($members[$m]['roll_no']); ?>
                                        </td>
                                        <td class="name-col">
                                            <?php echo htmlspecialchars($members[$m]['name']); ?>
                                        </td>
                                        <td class="sig-col"></td>
                                    </tr>
                                <?php endfor; ?>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Bottom Evaluator Signatures Block -->
                <div class="sheet-footer-signatures">
                    <div class="sig-box">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo htmlspecialchars($evaluator1); ?></div>
                        <div class="sig-role">Committee Evaluator</div>
                    </div>
                    
                    <div class="sig-box">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo htmlspecialchars($evaluator2); ?></div>
                        <div class="sig-role">Committee Evaluator</div>
                    </div>

                    <div class="sig-box">
                        <div class="sig-line"></div>
                        <div class="sig-name"><?php echo !empty($coordinatorName) ? htmlspecialchars($coordinatorName) : 'Department Coordinator'; ?></div>
                        <div class="sig-role">FYP Coordinator</div>
                    </div>
                </div>

            </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>
</html>
