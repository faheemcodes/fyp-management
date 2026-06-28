<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    $db->exec("DROP TABLE IF EXISTS grades;");
    
    $query = "
    CREATE TABLE grades (
        student_id INT PRIMARY KEY,
        group_id INT NOT NULL,
        proposal_marks DECIMAL(5,2) DEFAULT 0.00,
        proposal_defense_marks DECIMAL(5,2) DEFAULT NULL,
        progress_presentation_marks DECIMAL(5,2) DEFAULT NULL,
        final_presentation_marks DECIMAL(5,2) DEFAULT NULL,
        supervision_marks DECIMAL(5,2) DEFAULT NULL,
        show_supervision_to_student TINYINT(1) DEFAULT 0,
        total_marks DECIMAL(5,2) DEFAULT 0.00,
        percentage DECIMAL(5,2) DEFAULT 0.00,
        grade VARCHAR(5) DEFAULT 'F',
        status ENUM('Pass', 'Fail') DEFAULT 'Fail',
        calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
        FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $db->exec($query);
    
    // Also, initialize grades for all current students in groups
    $students = $db->query("SELECT student_id, group_id FROM group_members WHERE group_id IS NOT NULL")->fetchAll();
    $stmt = $db->prepare("INSERT IGNORE INTO grades (student_id, group_id) VALUES (?, ?)");
    foreach ($students as $s) {
        $stmt->execute([$s['student_id'], $s['group_id']]);
    }
    
    echo "Grades table recreated and initialized successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
