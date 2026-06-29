<?php
$file = 'src/Controller/AdminController.php';
$content = file_get_contents($file);

$content = str_replace(
    "SELECT g.*, s.name as leader_name, s.department as leader_dept, pr.title as project_title, sup.name as supervisor_name",
    "SELECT g.*, s.name as leader_name, s.department as leader_dept, pr.title as project_title, sup.name as supervisor_name, b.name as batch_name, b.is_active as batch_is_active",
    $content
);

$content = str_replace(
    "LEFT JOIN supervisors sup ON pr.supervisor_id = sup.user_id",
    "LEFT JOIN supervisors sup ON pr.supervisor_id = sup.user_id\n            LEFT JOIN academic_batches b ON g.batch_id = b.id",
    $content
);

file_put_contents($file, $content);

$file2 = 'src/View/admin/groups.php';
if (file_exists($file2)) {
    $content2 = file_get_contents($file2);
    
    // Add Batch Column header
    $content2 = str_replace(
        '<th class="py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Project Title</th>',
        '<th class="py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Project Title</th><th class="py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">Batch</th>',
        $content2
    );

    // Add Batch Data cell
    $content2 = preg_replace(
        '/(<td>\s*<div class="fw-bold text-dark text-truncate" style="max-width: 250px;" title="<\?php echo htmlspecialchars\(\$g\[\'project_title\'\] \?\? \'-\'\); \?>">\s*<\?php echo htmlspecialchars\(\$g\[\'project_title\'\] \?\? \'-\'\); \?>\s*<\/div>\s*<\/td>)/',
        '$1<td><?php echo htmlspecialchars($g[\'batch_name\'] ?? \'-\'); ?><?php if(isset($g[\'batch_is_active\']) && !$g[\'batch_is_active\']): ?> <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:0.7rem;">Archived</span><?php endif; ?></td>',
        $content2
    );

    file_put_contents($file2, $content2);
}

echo "Admin groups updated to show batches.\n";
