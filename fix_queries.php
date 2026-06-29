<?php
// Fix CommitteeController
$file = 'src/Controller/CommitteeController.php';
$content = file_get_contents($file);

$content = str_replace(
    "SELECT COUNT(*) FROM groups g JOIN projects p ON g.id = p.group_id WHERE p.status = 'Approved'",
    "SELECT COUNT(*) FROM groups g JOIN projects p ON g.id = p.group_id JOIN academic_batches b ON g.batch_id = b.id WHERE p.status = 'Approved' AND b.is_active = 1",
    $content
);

$content = str_replace(
    "            WHERE p.status = 'Approved'\n            ORDER BY g.created_at DESC",
    "            JOIN academic_batches b ON g.batch_id = b.id\n            WHERE p.status = 'Approved' AND b.is_active = 1\n            ORDER BY g.created_at DESC",
    $content
);

file_put_contents($file, $content);
echo "CommitteeController fixed.\n";

// Fix CoordinatorController
$file2 = 'src/Controller/CoordinatorController.php';
$content2 = file_get_contents($file2);

$content2 = str_replace(
    "        \$stmt = \$db->query(\"SELECT COUNT(*) FROM groups\");",
    "        \$stmt = \$db->query(\"SELECT COUNT(*) FROM groups g JOIN academic_batches b ON g.batch_id = b.id WHERE b.is_active = 1\");",
    $content2
);

$content2 = str_replace(
    "SELECT COUNT(*) FROM projects WHERE status = 'Approved'",
    "SELECT COUNT(*) FROM projects p JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.status = 'Approved' AND b.is_active = 1",
    $content2
);

$content2 = str_replace(
    "SELECT COUNT(*) FROM proposals pr JOIN projects p ON pr.group_id = p.group_id WHERE pr.status = 'Pending'",
    "SELECT COUNT(*) FROM proposals pr JOIN projects p ON pr.group_id = p.group_id JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE pr.status = 'Pending' AND b.is_active = 1",
    $content2
);

$content2 = str_replace(
    "            ORDER BY p.created_at DESC",
    "            JOIN academic_batches b ON g.batch_id = b.id WHERE b.is_active = 1\n            ORDER BY p.created_at DESC",
    $content2
);

file_put_contents($file2, $content2);
echo "CoordinatorController fixed.\n";
