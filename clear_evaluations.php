<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Truncate the evaluations table (removes all committee evaluations)
    $db->exec("TRUNCATE TABLE evaluations");
    
    // Reset committee-specific marks in grades table
    $db->exec("UPDATE grades SET proposal_defense_marks = NULL, progress_presentation_marks = NULL, final_presentation_marks = NULL");
    
    // Update total marks to only include supervision_marks for now
    $db->exec("UPDATE grades SET total_marks = IFNULL(supervision_marks, 0) + IFNULL(proposal_marks, 0)");
    
    echo "Successfully removed all committee evaluations data.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
