<?php 
$title = "Online Grading Sheet - " . htmlspecialchars($stage);
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>

<style>
.eval-table-wrapper {
    background: var(--card-bg);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
    box-shadow: var(--card-shadow);
    overflow-x: auto;
    margin-bottom: 24px;
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
}
/* Hide the up/down arrows on number inputs to save space */
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
    box-shadow: 0 0 0 2px rgba(59,130,246,0.1) !important;
    outline: none;
}
.eval-remarks-input {
    width: 100%;
    min-width: 150px;
    background-color: var(--form-bg) !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 4px !important;
    padding: 6px !important;
    color: var(--text-primary) !important;
    font-size: 0.8rem;
}
html.dark-theme .eval-remarks-input {
    border-color: #334155 !important;
}

.search-highlight {
    background-color: #fde047; /* Yellow */
    color: #000;
    border-radius: 2px;
    padding: 0 2px;
}
.search-highlight.active {
    background-color: #f97316; /* Orange */
    color: #fff;
}

/* Custom Search Bar UI */
.custom-search-bar {
    display: flex;
    align-items: center;
    background-color: var(--form-bg);
    border: 1px solid #cbd5e1;
    border-radius: 50rem;
    padding: 0.35rem 0.5rem;
    max-width: 320px;
    width: 100%;
    transition: all 0.2s ease;
}
html.dark-theme .custom-search-bar {
    border-color: #334155;
}
.custom-search-bar:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
}
.custom-search-bar .search-icon {
    color: #94a3b8;
    margin-left: 0.5rem;
    margin-right: 0.5rem;
    font-size: 0.95rem;
}
.custom-search-bar .search-input {
    border: none;
    background: transparent;
    outline: none;
    flex-grow: 1;
    color: var(--text-primary);
    font-size: 0.9rem;
    min-width: 0;
}
.custom-search-bar .search-input::placeholder {
    color: #94a3b8;
}
.custom-search-bar .search-count {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0 0.5rem;
    font-variant-numeric: tabular-nums;
    font-weight: 500;
}
.custom-search-bar .search-nav {
    display: flex;
    border-left: 1px solid #e2e8f0;
    padding-left: 0.25rem;
}
html.dark-theme .custom-search-bar .search-nav {
    border-left-color: #334155;
}
.custom-search-bar .search-btn {
    background: transparent;
    border: none;
    color: #64748b;
    padding: 0.25rem 0.5rem;
    border-radius: 50rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.custom-search-bar .search-btn:hover {
    background-color: rgba(0,0,0,0.06);
    color: var(--text-primary);
}
html.dark-theme .custom-search-bar .search-btn:hover {
    background-color: rgba(255,255,255,0.1);
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold"><?php echo htmlspecialchars($stage); ?> Grading</h1>
    <div class="d-flex gap-3 align-items-center">
        <div class="custom-search-bar shadow-sm">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="gradingSearch" class="search-input" placeholder="Find in page...">
            <span id="searchCount" class="search-count" style="display: none;">0/0</span>
            <div class="search-nav">
                <button type="button" id="searchPrev" class="search-btn"><i class="bi bi-chevron-up"></i></button>
                <button type="button" id="searchNext" class="search-btn"><i class="bi bi-chevron-down"></i></button>
            </div>
        </div>
        <a href="<?php echo $bp; ?>/committee/evaluations/print?stage=<?php echo urlencode($stage); ?>" class="btn btn-outline-primary shadow-sm rounded-pill px-4" target="_blank" style="white-space: nowrap; font-weight: 600;">
            <i class="bi bi-printer me-1"></i> Print
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo $bp; ?>/committee/grading-sheet/save" method="POST">
    <input type="hidden" name="stage" value="<?php echo htmlspecialchars($stage); ?>">
    
    <div class="eval-table-wrapper shadow-sm">
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
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($firstMember['group_code']); ?></span>
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
                            <td colspan="<?php echo $stage === 'FYP Progress Presentation' ? 9 : 8; ?>" class="text-center py-5 text-muted">No approved projects assigned to you for evaluation.</td>
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
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($firstMember['group_code']); ?></span>
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
                            <td colspan="18" class="text-center py-5 text-muted">No approved projects assigned to you for evaluation.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            <?php endif; ?>
        </table>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill" style="font-size: 1.1rem;">
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
            // Restore visibility of all tbodies if we were previously filtering
            document.querySelectorAll('.eval-group-tbody').forEach(tb => tb.style.display = '');
            return;
        }

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

                // Update node and its text for the next iteration of the while loop
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
        
        // Ensure all rows are visible since we are no longer filtering out rows, but highlighting!
        document.querySelectorAll('.eval-group-tbody').forEach(tb => tb.style.display = '');
    }

    function updateHighlights() {
        if (matches.length === 0) return;
        
        matches.forEach(m => m.classList.remove('active'));
        const activeMatch = matches[currentIndex];
        activeMatch.classList.add('active');
        
        searchCount.textContent = `${currentIndex + 1}/${matches.length}`;
        
        // Scroll into view smoothly
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
