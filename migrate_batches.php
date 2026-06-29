<?php
require 'config/database.php';
$db = \Database::getInstance()->getConnection();

try {
    // 1. Create academic_batches table
    $db->exec("CREATE TABLE IF NOT EXISTS academic_batches (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        is_active TINYINT(1) DEFAULT 1,
        is_registration_open TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 2. Insert default batch
    $stmt = $db->query("SELECT id FROM academic_batches WHERE name = 'Default Batch (2026)'");
    $batch = $stmt->fetch();
    if (!$batch) {
        $db->exec("INSERT INTO academic_batches (name, is_active, is_registration_open) VALUES ('Default Batch (2026)', 1, 1)");
        $batchId = $db->lastInsertId();
    } else {
        $batchId = $batch['id'];
    }

    // 3. Add batch_id to groups table if it doesn't exist
    $stmt = $db->query("SHOW COLUMNS FROM groups LIKE 'batch_id'");
    if (!$stmt->fetch()) {
        $db->exec("ALTER TABLE groups ADD COLUMN batch_id INT NULL");
        
        // Assign existing groups to default batch
        $stmtUpdate = $db->prepare("UPDATE groups SET batch_id = ?");
        $stmtUpdate->execute([$batchId]);

        // Make it NOT NULL and add foreign key
        $db->exec("ALTER TABLE groups MODIFY COLUMN batch_id INT NOT NULL");
        $db->exec("ALTER TABLE groups ADD CONSTRAINT fk_groups_batch FOREIGN KEY (batch_id) REFERENCES academic_batches(id) ON DELETE RESTRICT");
    }

    echo "Database schema updated successfully!\n";

} catch (\Exception $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
