<?php 
$title = "Online Grading Sheet - " . htmlspecialchars($stage);
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);

// Determine hero icon and color scheme based on stage
$heroIcon = 'bi-file-earmark-text';
$heroGradient = 'linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%)';
$heroIconGradient = 'conic-gradient(from 0deg, #60a5fa, #3b82f6, #1d4ed8, #60a5fa)';
$heroSubtitle = 'Assign and manage marks for all assigned groups.';
$stageBadgeColor = '#3b82f6';

if ($stage === 'Proposal Defence Presentation') {
    $heroIcon = 'bi-file-earmark-text';
    $heroGradient = 'linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%)';
    $heroIconGradient = 'conic-gradient(from 0deg, #60a5fa, #3b82f6, #1d4ed8, #60a5fa)';
    $heroSubtitle = 'Grade project proposals and provide initial feedback.';
    $stageBadgeColor = '#3b82f6';
} elseif ($stage === 'FYP Progress Presentation') {
    $heroIcon = 'bi-graph-up-arrow';
    $heroGradient = 'linear-gradient(135deg, #042f2e 0%, #065f46 50%, #042f2e 100%)';
    $heroIconGradient = 'conic-gradient(from 0deg, #6ee7b7, #10b981, #047857, #6ee7b7)';
    $heroSubtitle = 'Evaluate project progress and development milestones.';
    $stageBadgeColor = '#10b981';
} elseif ($stage === 'Final Presentation') {
    $heroIcon = 'bi-trophy';
    $heroGradient = 'linear-gradient(135deg, #1c1917 0%, #78350f 50%, #1c1917 100%)';
    $heroIconGradient = 'conic-gradient(from 0deg, #fbbf24, #f59e0b, #b45309, #fbbf24)';
    $heroSubtitle = 'Complete final evaluation including presentation, thesis, and demo.';
    $stageBadgeColor = '#f59e0b';
}

$groupCount = count($grouped ?? []);
?>

<style>
/* ─── Hero Section ─── */
.gs-hero {
    background: <?php echo $heroGradient; ?>;
    border-radius: var(--border-radius-lg);
    padding: 28px 32px;
    position: sticky;
    top: 75px; /* Offset for top navbar */
    z-index: 100;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.gs-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.gs-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -5%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.gs-hero-icon {
    width: 52px;
    height: 52px;
    background: <?php echo $heroIconGradient; ?>;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #fff;
    flex-shrink: 0;
}
.gs-hero-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 10px 18px;
    backdrop-filter: blur(4px);
}
.gs-hero-stat-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}
.gs-hero-stat-label {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.6);
}

/* ─── Table Section ─── */
.gs-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    margin-bottom: 24px;
}
.gs-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
    gap: 16px;
    flex-wrap: wrap;
}
.gs-section-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.gs-section-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

/* ─── Eval Table ─── */
.eval-table-wrapper {
    overflow-x: auto;
}
.eval-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    min-width: 1200px;
}
.eval-table th, .eval-table td {
    border: 1px solid var(--border-color);
    padding: 8px 12px;
    vertical-align: middle;
}
.eval-table th {
    background: var(--form-bg);
    color: var(--text-secondary);
    font-weight: 600;
    text-align: center;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.eval-table td.merged-cell {
    text-align: center;
    background: var(--card-bg);
}
.eval-table .vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
    height: 120px;
    padding: 10px 5px;
    font-size: 0.75rem;
}
.eval-table tbody tr {
    transition: background-color 0.15s ease;
}
.eval-table tbody tr:hover {
    background-color: rgba(59,130,246,0.03);
}

/* ─── Inputs ─── */
.eval-input {
    width: 35px !important;
    min-width: 35px !important;
    margin: 0 auto;
    text-align: center;
    background-color: var(--form-bg) !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 4px !important;
    padding: 2px !important;
    color: var(--text-primary) !important;
    font-size: 0.9rem;
    font-weight: 600;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.eval-input::-webkit-outer-spin-button,
.eval-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.eval-input[type=number] {
    -moz-appearance: textfield;
}
html.dark-theme .eval-input {
    border-color: #334155 !important;
}
.eval-input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.15) !important;
    outline: none;
}
.eval-remarks-input {
    width: 100%;
    min-width: 150px;
    background-color: var(--form-bg) !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding: 6px 8px !important;
    color: var(--text-primary) !important;
    font-size: 0.8rem;
    resize: vertical;
    transition: border-color 0.2s, box-shadow 0.2s;
}
html.dark-theme .eval-remarks-input {
    border-color: #334155 !important;
}
.eval-remarks-input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.15) !important;
    outline: none;
}

/* ─── Group Code Badge ─── */
.gs-group-badge {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 0.3rem 0.55rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.78rem;
    font-family: monospace;
    display: inline-block;
}
html.dark-theme .gs-group-badge { background: rgba(59,130,246,0.15); color: #60a5fa; }

/* ─── Search Highlights ─── */
.search-highlight {
    background-color: #fde047;
    color: #000;
    border-radius: 2px;
    padding: 0 2px;
}
.search-highlight.active {
    background-color: #f97316;
    color: #fff;
}

/* ─── Custom Search Bar ─── */
.custom-search-bar {
    display: flex;
    align-items: center;
    background-color: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50rem;
    padding: 0.3rem 0.5rem;
    max-width: 300px;
    width: 100%;
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
}
.custom-search-bar:focus-within {
    background-color: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.3);
    box-shadow: 0 0 0 3px rgba(255,255,255,0.08) !important;
}
.custom-search-bar .search-icon {
    color: rgba(255,255,255,0.5);
    margin-left: 0.4rem;
    margin-right: 0.4rem;
    font-size: 0.9rem;
}
.custom-search-bar .search-input {
    border: none;
    background: transparent;
    outline: none;
    flex-grow: 1;
    color: #fff;
    font-size: 0.85rem;
    min-width: 0;
}
.custom-search-bar .search-input::placeholder {
    color: rgba(255,255,255,0.4);
}
.custom-search-bar .search-count {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.6);
    margin: 0 0.4rem;
    font-variant-numeric: tabular-nums;
    font-weight: 500;
    white-space: nowrap;
}
.custom-search-bar .search-nav {
    display: flex;
    border-left: 1px solid rgba(255,255,255,0.15);
    padding-left: 0.2rem;
}
.custom-search-bar .search-btn {
    background: transparent;
    border: none;
    color: rgba(255,255,255,0.5);
    padding: 0.2rem 0.4rem;
    border-radius: 50rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}
.custom-search-bar .search-btn:hover {
    background-color: rgba(255,255,255,0.1);
    color: #fff;
}

/* ─── Save Button ─── */
.gs-save-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff;
    font-weight: 700;
    padding: 12px 40px;
    border-radius: 50rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59,130,246,0.3);
}
.gs-save-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.4);
    color: #fff;
}

/* ─── Mobile Responsiveness ─── */
@media (max-width: 768px) {
    .gs-hero {
        padding: 12px 16px;
        position: sticky; /* Keep sticky on mobile */
        top: 60px; /* Mobile top navbar is usually shorter */
        margin-bottom: 16px;
    }
    .gs-hero .d-flex.flex-column > div {
        width: 100%;
        justify-content: flex-start;
    }
    .gs-hero-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
    }
    .gs-hero h4 {
        font-size: 1.1rem !important;
    }
    .gs-hero p.mb-0 {
        display: none; /* Hide subtitle to save vertical space on mobile */
    }
    .gs-hero .search-and-print {
        flex-wrap: wrap;
    }
    .custom-search-bar {
        max-width: 100%;
        flex: 1 1 auto;
    }
    .eval-table-wrapper {
        padding-bottom: 10px;
    }
    .gs-save-btn {
        width: 100%;
        padding: 14px;
        text-align: center;
    }
    .gs-section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>

<!-- Hero Section -->
<div class="gs-hero">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 1;">
        <div class="d-flex align-items-center gap-3">
            <div class="gs-hero-icon shadow-sm">
                <i class="bi <?php echo $heroIcon; ?>"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1" style="font-size: 1.3rem; letter-spacing: -0.02em;"><?php echo htmlspecialchars($stage); ?> Grading</h4>
                <p class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.82rem;"><?php echo $heroSubtitle; ?></p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 search-and-print">
            <!-- Search Bar -->
            <div class="custom-search-bar">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="gradingSearch" class="search-input" placeholder="Find in page...">
                <span id="searchCount" class="search-count" style="display: none;">0/0</span>
                <div class="search-nav">
                    <button type="button" id="searchPrev" class="search-btn"><i class="bi bi-chevron-up"></i></button>
                    <button type="button" id="searchNext" class="search-btn"><i class="bi bi-chevron-down"></i></button>
                </div>
            </div>

            <!-- Print Button -->
            <a href="<?php echo $bp; ?>/committee/evaluations/print?stage=<?php echo urlencode($stage); ?>" class="btn btn-outline-light rounded-pill px-4 fw-semibold shadow-sm" target="_blank" style="font-size: 0.85rem; white-space: nowrap;">
                <i class="bi bi-printer me-1"></i> Print
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: var(--border-radius-lg); border: none;">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: var(--border-radius-lg); border: none;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo $bp; ?>/committee/grading-sheet/save" method="POST">
    <input type="hidden" name="stage" value="<?php echo htmlspecialchars($stage); ?>">
    
    <div class="gs-section">
        <div class="gs-section-header">
            <div class="gs-section-title">
                <div class="gs-section-icon" style="background: rgba(<?php echo $stage === 'FYP Progress Presentation' ? '16,185,129' : ($stage === 'Final Presentation' ? '245,158,11' : '59,130,246'); ?>,0.1); color: <?php echo $stageBadgeColor; ?>;">
                    <i class="bi <?php echo $heroIcon; ?>"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" style="font-size: 0.95rem; color: var(--text-primary);">Grading Sheet</h6>
                    <small class="text-muted" style="font-size: 0.78rem;">Enter marks for each student below</small>
                </div>
            </div>
            <div>
                <a href="<?php echo $bp; ?>/committee/evaluations" class="btn btn-light rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.8rem;">
                    <i class="bi bi-arrow-left me-1"></i> Back to Evaluations
                </a>
            </div>
        </div>

        <div class="eval-table-wrapper">
            <table class="eval-table">
                <?php if ($stage === 'FYP Progress Presentation' || $stage === 'Proposal Defence Presentation'): ?>
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 40px;">Sr.No</th>
                            <th rowspan="2" style="width: 100px;">Project ID</th>
                            <th rowspan="2" style="width: 200px;">Title of Project</th>
                            <th rowspan="2" style="width: 150px;">Primary Supervisor</th>
                            <th colspan="2">Group Members</th>
                            <?php if ($stage === 'FYP Progress Presentation'): ?>
                                <th rowspan="2" style="width: 250px;">Previous comments</th>
                            <?php endif; ?>
                            <th rowspan="2" style="width: 100px;">Marks (Out of 40)</th>
                            <th rowspan="2" style="width: 200px;">Your Group Remarks</th>
                        </tr>
                        <tr>
                            <th style="width: 100px;">Roll No</th>
                            <th style="width: 150px;">Full Name</th>
                        </tr>
                    </thead>
                    <?php 
                    $srNo = 1;
                    foreach ($grouped as $groupId => $members): 
                        $numMembers = count($members);
                        $firstMember = $members[0];
                    ?>
                    <tbody class="eval-group-tbody">
                            <tr>
                                <td rowspan="<?php echo $numMembers; ?>" class="merged-cell fw-bold"><?php echo $srNo++; ?></td>
                                <td rowspan="<?php echo $numMembers; ?>" class="merged-cell">
                                    <span class="gs-group-badge"><?php echo htmlspecialchars($firstMember['group_code']); ?></span>
                                </td>
                                <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
                                <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
                                
                                <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
                                
                                <?php if ($stage === 'FYP Progress Presentation'): ?>
                                    <td rowspan="<?php echo $numMembers; ?>" style="font-size: 0.8rem; color: var(--text-secondary);">
                                        <?php echo htmlspecialchars($firstMember['previous_comments'] ?: 'None'); ?>
                                    </td>
                                <?php endif; ?>
                                
                                <!-- First Member Mark -->
                                <td class="text-center">
                                    <input type="number" step="0.5" max="40" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][total]" value="<?php echo htmlspecialchars($firstMember['marks']['total'] ?? ''); ?>">
                                </td>
                                
                                <!-- Group Remarks -->
                                <td rowspan="<?php echo $numMembers; ?>">
                                    <textarea class="eval-remarks-input" rows="<?php echo $numMembers; ?>" name="evaluations[<?php echo $groupId; ?>][remarks]" placeholder="Enter remarks..."><?php echo htmlspecialchars($firstMember['group_remarks']); ?></textarea>
                                </td>
                            </tr>
                            <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                                    <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                                    <td class="text-center">
                                        <input type="number" step="0.5" max="40" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][total]" value="<?php echo htmlspecialchars($member['marks']['total'] ?? ''); ?>">
                                    </td>
                                </tr>
                            <?php endfor; ?>
                    </tbody>
                    <?php endforeach; ?>
                    <tbody>
                        <?php if (empty($grouped)): ?>
                            <tr>
                                <td colspan="<?php echo $stage === 'FYP Progress Presentation' ? 9 : 8; ?>" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    No approved projects assigned to you for evaluation.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    
                <?php elseif ($stage === 'Final Presentation'): ?>
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 40px;">Sr.No</th>
                            <th rowspan="2" style="width: 90px;">Project ID</th>
                            <th rowspan="2" style="width: 150px;">Title of Project</th>
                            <th rowspan="2" style="width: 120px;">Primary Supervisor</th>
                            <th colspan="2">Group Members</th>
                            <th colspan="5">Presentation<br>(25 marks)</th>
                            <th colspan="5">Thesis<br>(25 marks)</th>
                            <th rowspan="2" class="vertical-text text-center">Project<br>Demo<br>(25 marks)</th>
                            <th rowspan="2" style="width: 150px;">Your Group Remarks</th>
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
                    <?php 
                    $srNo = 1;
                    foreach ($grouped as $groupId => $members): 
                        $numMembers = count($members);
                        $firstMember = $members[0];
                    ?>
                    <tbody class="eval-group-tbody">
                            <tr>
                                <td rowspan="<?php echo $numMembers; ?>" class="merged-cell fw-bold"><?php echo $srNo++; ?></td>
                                <td rowspan="<?php echo $numMembers; ?>" class="merged-cell">
                                    <span class="gs-group-badge"><?php echo htmlspecialchars($firstMember['group_code']); ?></span>
                                </td>
                                <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['project_title'] ?: 'Untitled'); ?></td>
                                <td rowspan="<?php echo $numMembers; ?>"><?php echo htmlspecialchars($firstMember['supervisor_name'] ?: 'Not Assigned'); ?></td>
                                
                                <td><?php echo htmlspecialchars($firstMember['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($firstMember['student_name']); ?></td>
                                
                                <!-- Presentation Fields -->
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][pres_contents]" value="<?php echo htmlspecialchars($firstMember['marks']['pres_contents'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][pres_time]" value="<?php echo htmlspecialchars($firstMember['marks']['pres_time'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][pres_confidence]" value="<?php echo htmlspecialchars($firstMember['marks']['pres_confidence'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][pres_qa]" value="<?php echo htmlspecialchars($firstMember['marks']['pres_qa'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][pres_language]" value="<?php echo htmlspecialchars($firstMember['marks']['pres_language'] ?? ''); ?>"></td>
                                
                                <!-- Thesis Fields -->
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][thesis_contents]" value="<?php echo htmlspecialchars($firstMember['marks']['thesis_contents'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][thesis_formatting]" value="<?php echo htmlspecialchars($firstMember['marks']['thesis_formatting'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][thesis_referencing]" value="<?php echo htmlspecialchars($firstMember['marks']['thesis_referencing'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][thesis_fig]" value="<?php echo htmlspecialchars($firstMember['marks']['thesis_fig'] ?? ''); ?>"></td>
                                <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][thesis_completeness]" value="<?php echo htmlspecialchars($firstMember['marks']['thesis_completeness'] ?? ''); ?>"></td>
                                
                                <!-- Demo Field -->
                                <td><input type="number" step="0.5" max="25" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $firstMember['student_id']; ?>][demo_total]" value="<?php echo htmlspecialchars($firstMember['marks']['demo_total'] ?? ''); ?>"></td>
                                
                                <!-- Group Remarks -->
                                <td rowspan="<?php echo $numMembers; ?>">
                                    <textarea class="eval-remarks-input" rows="<?php echo $numMembers; ?>" name="evaluations[<?php echo $groupId; ?>][remarks]" placeholder="Remarks..."><?php echo htmlspecialchars($firstMember['group_remarks']); ?></textarea>
                                </td>
                            </tr>
                            <?php for ($i = 1; $i < $numMembers; $i++): $member = $members[$i]; ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['roll_no']); ?></td>
                                    <td><?php echo htmlspecialchars($member['student_name']); ?></td>
                                    
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][pres_contents]" value="<?php echo htmlspecialchars($member['marks']['pres_contents'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][pres_time]" value="<?php echo htmlspecialchars($member['marks']['pres_time'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][pres_confidence]" value="<?php echo htmlspecialchars($member['marks']['pres_confidence'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][pres_qa]" value="<?php echo htmlspecialchars($member['marks']['pres_qa'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][pres_language]" value="<?php echo htmlspecialchars($member['marks']['pres_language'] ?? ''); ?>"></td>
                                    
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][thesis_contents]" value="<?php echo htmlspecialchars($member['marks']['thesis_contents'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][thesis_formatting]" value="<?php echo htmlspecialchars($member['marks']['thesis_formatting'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][thesis_referencing]" value="<?php echo htmlspecialchars($member['marks']['thesis_referencing'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][thesis_fig]" value="<?php echo htmlspecialchars($member['marks']['thesis_fig'] ?? ''); ?>"></td>
                                    <td><input type="number" step="0.5" max="5" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][thesis_completeness]" value="<?php echo htmlspecialchars($member['marks']['thesis_completeness'] ?? ''); ?>"></td>
                                    
                                    <td><input type="number" step="0.5" max="25" class="eval-input" name="evaluations[<?php echo $groupId; ?>][marks][<?php echo $member['student_id']; ?>][demo_total]" value="<?php echo htmlspecialchars($member['marks']['demo_total'] ?? ''); ?>"></td>
                                </tr>
                            <?php endfor; ?>
                    </tbody>
                    <?php endforeach; ?>
                    <tbody>
                        <?php if (empty($grouped)): ?>
                            <tr>
                                <td colspan="18" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    No approved projects assigned to you for evaluation.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="gs-save-btn">
            <i class="bi bi-save2-fill me-2"></i> Save All Marks
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('gradingSearch');
    const searchCount = document.getElementById('searchCount');
    const searchNext = document.getElementById('searchNext');
    const searchPrev = document.getElementById('searchPrev');
    const table = document.querySelector('.eval-table');
    
    let matches = [];
    let currentIndex = -1;

    function clearHighlights() {
        const highlights = table.querySelectorAll('.search-highlight');
        highlights.forEach(h => {
            const parent = h.parentNode;
            parent.replaceChild(document.createTextNode(h.textContent), h);
            parent.normalize();
        });
        matches = [];
        currentIndex = -1;
        searchCount.textContent = '0/0';
        searchCount.style.display = 'none';
    }

    function getTextNodes(node) {
        let textNodes = [];
        if (node.nodeType === 3) {
            textNodes.push(node);
        } else if (node.nodeType === 1 && node.nodeName !== 'SCRIPT' && node.nodeName !== 'STYLE' && node.nodeName !== 'INPUT' && node.nodeName !== 'TEXTAREA') {
            for (let child of node.childNodes) {
                textNodes.push(...getTextNodes(child));
            }
        }
        return textNodes;
    }

    function highlightText(term) {
        clearHighlights();
        if (!term) {
            document.querySelectorAll('.eval-group-tbody').forEach(tb => tb.style.display = '');
            return;
        }

        searchCount.style.display = '';
        const textNodes = getTextNodes(table);
        const termLower = term.toLowerCase();

        textNodes.forEach(node => {
            let nodeText = node.nodeValue;
            let nodeTextLower = nodeText.toLowerCase();
            let index;

            while ((index = nodeTextLower.indexOf(termLower)) !== -1) {
                const matchText = nodeText.substr(index, term.length);
                const beforeText = nodeText.substr(0, index);
                const afterText = nodeText.substr(index + term.length);

                const mark = document.createElement('span');
                mark.className = 'search-highlight';
                mark.textContent = matchText;

                const beforeNode = document.createTextNode(beforeText);
                const afterNode = document.createTextNode(afterText);

                const parent = node.parentNode;
                parent.replaceChild(afterNode, node);
                parent.insertBefore(mark, afterNode);
                parent.insertBefore(beforeNode, mark);

                matches.push(mark);

                node = afterNode;
                nodeText = node.nodeValue;
                nodeTextLower = nodeText.toLowerCase();
            }
        });

        if (matches.length > 0) {
            currentIndex = 0;
            updateHighlights();
        } else {
            searchCount.textContent = '0/0';
        }
        
        document.querySelectorAll('.eval-group-tbody').forEach(tb => tb.style.display = '');
    }

    function updateHighlights() {
        if (matches.length === 0) return;
        
        matches.forEach(m => m.classList.remove('active'));
        const activeMatch = matches[currentIndex];
        activeMatch.classList.add('active');
        
        searchCount.textContent = `${currentIndex + 1}/${matches.length}`;
        
        activeMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    if (searchInput && table) {
        searchInput.addEventListener('input', function(e) {
            highlightText(e.target.value.trim());
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (matches.length > 0) {
                    if (e.shiftKey) {
                        currentIndex = (currentIndex - 1 + matches.length) % matches.length;
                    } else {
                        currentIndex = (currentIndex + 1) % matches.length;
                    }
                    updateHighlights();
                }
            }
        });

        searchNext.addEventListener('click', function() {
            if (matches.length > 0) {
                currentIndex = (currentIndex + 1) % matches.length;
                updateHighlights();
            }
        });

        searchPrev.addEventListener('click', function() {
            if (matches.length > 0) {
                currentIndex = (currentIndex - 1 + matches.length) % matches.length;
                updateHighlights();
            }
        });
    }
});
</script>
