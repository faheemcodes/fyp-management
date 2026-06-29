<?php
$file = 'src/View/committee/evaluations.php';
$content = file_get_contents($file);

// Replace "View / Edit" with "View Marks" in Desktop
$content = str_replace(
    '<div class="grade-box">
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 w-100 mb-1" style="font-size: 0.75rem; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g[\'id\']; ?>Proposal"><i class="bi bi-eye me-1"></i>View / Edit</button>
                                <div class="grade-box-remarks text-truncate" title="<?php echo htmlspecialchars($g[\'proposal_defense\'][\'remarks\'] ?? \'\'); ?>">
                                    <?php echo htmlspecialchars($g[\'proposal_defense\'][\'remarks\'] ?? \'No remarks\'); ?>
                                </div>
                            </div>',
    '<button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g[\'id\']; ?>Proposal"><i class="bi bi-eye me-1"></i>View Marks</button>',
    $content
);

$content = str_replace(
    '<div class="grade-box">
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 w-100 mb-1" style="font-size: 0.75rem; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g[\'id\']; ?>Progress"><i class="bi bi-eye me-1"></i>View / Edit</button>
                                <div class="grade-box-remarks text-truncate" title="<?php echo htmlspecialchars($g[\'progress_eval\'][\'remarks\'] ?? \'\'); ?>">
                                    <?php echo htmlspecialchars($g[\'progress_eval\'][\'remarks\'] ?? \'No remarks\'); ?>
                                </div>
                            </div>',
    '<button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g[\'id\']; ?>Progress"><i class="bi bi-eye me-1"></i>View Marks</button>',
    $content
);

$content = str_replace(
    '<div class="grade-box">
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 w-100 mb-1" style="font-size: 0.75rem; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g[\'id\']; ?>Final"><i class="bi bi-eye me-1"></i>View / Edit</button>
                                <div class="grade-box-remarks text-truncate" title="<?php echo htmlspecialchars($g[\'final_presentation\'][\'remarks\'] ?? \'\'); ?>">
                                    <?php echo htmlspecialchars($g[\'final_presentation\'][\'remarks\'] ?? \'No remarks\'); ?>
                                </div>
                            </div>',
    '<button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#gradeModal<?php echo $g[\'id\']; ?>Final"><i class="bi bi-eye me-1"></i>View Marks</button>',
    $content
);

// Replace "View / Edit" with "View Marks" in Mobile
$content = str_replace('View / Edit</button>', 'View Marks</button>', $content);

// For Modals: Add "disabled" logic to inputs based on $g['total_marks'] > 0
// We can just conditionally output 'disabled' if the evaluation exists!
// Let's replace 'required>' with 'required <?php echo ($g["proposal_defense"] && $g["proposal_defense"]["total_marks"] > 0) ? "disabled" : ""; ?>>'

$content = preg_replace(
    '/(name="marks\[<\?php echo \$m\[\'user_id\'\]; \?>\]\[(problem_solution|literature_feasibility|presentation_viva)\]".*?required)>/',
    '$1 <?php echo ($g[\'proposal_defense\'] && $g[\'proposal_defense\'][\'total_marks\'] > 0) ? \'disabled\' : \'\'; ?>>',
    $content
);

$content = preg_replace(
    '/(name="marks\[<\?php echo \$m\[\'user_id\'\]; \?>\]\[(understanding|technical_knowledge|implementation_progress|presentation_qa)\]".*?required)>/',
    '$1 <?php echo ($g[\'progress_eval\'] && $g[\'progress_eval\'][\'total_marks\'] > 0) ? \'disabled\' : \'\'; ?>>',
    $content
);

$content = preg_replace(
    '/(name="marks\[<\?php echo \$m\[\'user_id\'\]; \?>\]\[(presentation)\]".*?required)>/',
    '$1 <?php echo ($g[\'final_presentation\'] && $g[\'final_presentation\'][\'total_marks\'] > 0) ? \'disabled\' : \'\'; ?>>',
    $content
);

// Also disable remarks textarea
$content = preg_replace_callback(
    '/(<\?php \$pR = \$g\[\'(.*?)\'\]\[\'remarks\'\].*?><textarea.*?name="remarks".*?)>/',
    function($m) {
        return $m[1] . ' <?php echo ($g[\'' . $m[2] . '\'] && $g[\'' . $m[2] . '\'][\'total_marks\'] > 0) ? \'disabled\' : \'\'; ?>>';
    },
    $content
);


// And remove the "Update Evaluation" submit button.
// Currently it is:
/*
<?php if($g['proposal_defense'] && $g['proposal_defense']['total_marks'] > 0): ?><button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm">Update Evaluation</button><?php else: ?><button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">Submit Evaluation</button><?php endif; ?>
*/

$content = preg_replace_callback(
    '/<\?php if\(\$g\[\'(.*?)\'\] && \$g\[\'\1\'\]\[\'total_marks\'\] > 0\): \?><button type="submit".*?>Update Evaluation<\/button><\?php else: \?><button type="submit".*?>Submit Evaluation<\/button><\?php endif; \?>/is',
    function($m) {
        return '<?php if(!$g[\'' . $m[1] . '\'] || $g[\'' . $m[1] . '\'][\'total_marks\'] <= 0): ?><button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">Submit Evaluation</button><?php endif; ?>';
    },
    $content
);

file_put_contents($file, $content);
echo "Changes applied!";
