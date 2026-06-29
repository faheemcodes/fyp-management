<?php
$file = 'src/Controller/SupervisorController.php';
$content = file_get_contents($file);

// 1. Dashboard: Active projects count
$content = preg_replace(
    '/SELECT COUNT\(\*\) FROM projects WHERE supervisor_id = \? AND status = \'Approved\'/',
    "SELECT COUNT(*) FROM projects p JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND p.status = 'Approved' AND b.is_active = 1",
    $content
);

// Dashboard fallback (if status wasn't there)
$content = str_replace(
    'SELECT COUNT(*) FROM projects WHERE supervisor_id = ?',
    "SELECT COUNT(*) FROM projects p JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND b.is_active = 1",
    $content
);

// 2. Dashboard: Pending proposals count
$content = preg_replace(
    '/SELECT COUNT\(\*\) FROM proposals pr\s*JOIN projects p ON pr.group_id = p.group_id\s*WHERE pr.status = \'Pending\' AND p.supervisor_id = \?/',
    "SELECT COUNT(*) FROM proposals pr JOIN projects p ON pr.group_id = p.group_id JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE pr.status = 'Pending' AND p.supervisor_id = ? AND b.is_active = 1",
    $content
);

// 3. groups() method
$content = preg_replace(
    '/SELECT g\.\*, p\.title as project_title, p\.status as project_status\s*FROM groups g\s*JOIN projects p ON g\.id = p\.group_id\s*WHERE p\.supervisor_id = \?\s*ORDER BY g\.created_at DESC/',
    "SELECT g.*, p.title as project_title, p.status as project_status FROM groups g JOIN projects p ON g.id = p.group_id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND b.is_active = 1 ORDER BY g.created_at DESC",
    $content
);

// 4. groupDetails() method
$content = preg_replace(
    '/SELECT g\.\*, p\.title as project_title, p\.description as project_description, p\.status as project_status\s*FROM groups g\s*JOIN projects p ON g\.id = p\.group_id\s*WHERE g\.id = \? AND p\.supervisor_id = \?/',
    "SELECT g.*, p.title as project_title, p.description as project_description, p.status as project_status FROM groups g JOIN projects p ON g.id = p.group_id JOIN academic_batches b ON g.batch_id = b.id WHERE g.id = ? AND p.supervisor_id = ? AND b.is_active = 1",
    $content
);

// 5. reviews() method
$content = preg_replace(
    '/SELECT pr\.\*, g\.group_code, g\.created_by, p\.title as project_title\s*FROM proposals pr\s*JOIN groups g ON pr\.group_id = g\.id\s*JOIN projects p ON g\.id = p\.group_id\s*WHERE p\.supervisor_id = \?\s*ORDER BY pr\.created_at DESC/',
    "SELECT pr.*, g.group_code, g.created_by, p.title as project_title FROM proposals pr JOIN groups g ON pr.group_id = g.id JOIN projects p ON g.id = p.group_id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND b.is_active = 1 ORDER BY pr.created_at DESC",
    $content
);

// 6. Limits check in reviewProposal()
$content = str_replace(
    "SELECT COUNT(*) FROM projects WHERE supervisor_id = ? AND status = 'Approved'",
    "SELECT COUNT(*) FROM projects p JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.supervisor_id = ? AND p.status = 'Approved' AND b.is_active = 1",
    $content
);

file_put_contents($file, $content);
echo "SupervisorController updated.";
