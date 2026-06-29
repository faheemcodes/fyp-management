<?php
$files = [
    'src/Controller/CommitteeController.php',
    'src/Controller/CoordinatorController.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // CommitteeController: dashboard, evaluations
    if (strpos($file, 'CommitteeController') !== false) {
        $content = str_replace(
            "SELECT COUNT(*) FROM evaluations WHERE evaluator_id = ?",
            "SELECT COUNT(*) FROM evaluations e JOIN groups g ON e.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE e.evaluator_id = ? AND b.is_active = 1",
            $content
        );

        $content = str_replace(
            "SELECT COUNT(*) FROM evaluations WHERE evaluator_id = ? AND (total_marks = 0 OR total_marks IS NULL)",
            "SELECT COUNT(*) FROM evaluations e JOIN groups g ON e.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE e.evaluator_id = ? AND (e.total_marks = 0 OR e.total_marks IS NULL) AND b.is_active = 1",
            $content
        );

        $content = preg_replace(
            '/SELECT g\.\*, p\.title as project_title, p\.supervisor_id\s*FROM groups g\s*JOIN projects p ON g\.id = p\.group_id\s*WHERE g\.id IN \(\s*SELECT group_id FROM evaluations WHERE evaluator_id = \?\s*\)/',
            "SELECT g.*, p.title as project_title, p.supervisor_id FROM groups g JOIN projects p ON g.id = p.group_id JOIN academic_batches b ON g.batch_id = b.id WHERE b.is_active = 1 AND g.id IN (SELECT group_id FROM evaluations WHERE evaluator_id = ?)",
            $content
        );
    }

    // CoordinatorController: dashboard, projects
    if (strpos($file, 'CoordinatorController') !== false) {
        $content = str_replace(
            "SELECT COUNT(*) FROM groups",
            "SELECT COUNT(*) FROM groups g JOIN academic_batches b ON g.batch_id = b.id WHERE b.is_active = 1",
            $content
        );

        $content = str_replace(
            "SELECT COUNT(*) FROM projects WHERE status = 'Approved'",
            "SELECT COUNT(*) FROM projects p JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE p.status = 'Approved' AND b.is_active = 1",
            $content
        );

        $content = str_replace(
            "SELECT COUNT(*) FROM proposals pr JOIN projects p ON pr.group_id = p.group_id WHERE pr.status = 'Pending'",
            "SELECT COUNT(*) FROM proposals pr JOIN projects p ON pr.group_id = p.group_id JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE pr.status = 'Pending' AND b.is_active = 1",
            $content
        );

        $content = preg_replace(
            '/SELECT p\.\*, g\.group_code, g\.progress_stage\s*FROM projects p\s*JOIN groups g ON p\.group_id = g\.id\s*ORDER BY p\.created_at DESC/',
            "SELECT p.*, g.group_code, g.progress_stage FROM projects p JOIN groups g ON p.group_id = g.id JOIN academic_batches b ON g.batch_id = b.id WHERE b.is_active = 1 ORDER BY p.created_at DESC",
            $content
        );
    }

    file_put_contents($file, $content);
}
echo "Committee & Coordinator updated.";
