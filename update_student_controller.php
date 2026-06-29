<?php
$file = 'src/Controller/StudentController.php';
$content = file_get_contents($file);

// 1. First Group Creation (around line 205)
$search1 = <<<'EOD'
                // Insert into groups
                $stmt = $db->prepare("INSERT INTO groups (group_code, created_by, progress_stage) VALUES (NULL, ?, 'Group Created')");
                $stmt->execute([$userId]);
EOD;

$replace1 = <<<'EOD'
                // Get active registration batch
                $batchStmt = $db->query("SELECT id FROM academic_batches WHERE is_registration_open = 1 LIMIT 1");
                $batch = $batchStmt->fetch();
                if (!$batch) {
                    throw new \Exception("No active registration batch found. Please contact administration.");
                }

                // Insert into groups
                $stmt = $db->prepare("INSERT INTO groups (group_code, created_by, progress_stage, batch_id) VALUES (NULL, ?, 'Group Created', ?)");
                $stmt->execute([$userId, $batch['id']]);
EOD;

$content = str_replace($search1, $replace1, $content);


// 2. Second Group Creation (around line 485)
$search2 = <<<'EOD'
                $groupId = null;
                if (!$group) {
                    $stmt = $db->prepare("INSERT INTO groups (group_code, created_by, progress_stage) VALUES (NULL, ?, 'Proposal Submitted')");
                    $stmt->execute([$userId]);
EOD;

$replace2 = <<<'EOD'
                $groupId = null;
                if (!$group) {
                    // Get active registration batch
                    $batchStmt = $db->query("SELECT id FROM academic_batches WHERE is_registration_open = 1 LIMIT 1");
                    $batch = $batchStmt->fetch();
                    if (!$batch) {
                        throw new \Exception("No active registration batch found. Please contact administration.");
                    }

                    $stmt = $db->prepare("INSERT INTO groups (group_code, created_by, progress_stage, batch_id) VALUES (NULL, ?, 'Proposal Submitted', ?)");
                    $stmt->execute([$userId, $batch['id']]);
EOD;

$content = str_replace($search2, $replace2, $content);

file_put_contents($file, $content);
echo "StudentController updated.";
