<?php
$dir = new RecursiveDirectoryIterator('src');
$ite = new RecursiveIteratorIterator($dir);
$tables = [];
foreach($ite as $file) {
    if ($file->getExtension() == 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/(?:FROM|JOIN|UPDATE|INTO)\s+([a-zA-Z0-9_]+)/i', $content, $matches);
        foreach($matches[1] as $m) {
            $t = strtolower($m);
            if (!in_array($t, ['users', 'hods', 'students', 'supervisors', 'committees', 'profiles', 'groups', 'group_members', 'projects', 'proposals', 'evaluations', 'grades', 'notifications', 'deadlines', 'where', 'set', 'select'])) {
                $tables[$t] = true;
            }
        }
    }
}
print_r(array_keys($tables));
