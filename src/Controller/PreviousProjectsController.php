<?php
namespace Controller;

class PreviousProjectsController extends BaseController {
    public function index() {
        // Enforce login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $db = \Database::getInstance()->getConnection();

        // Fetch projects that belong to an inactive batch and are Approved.
        $stmt = $db->query("
            SELECT 
                p.id as project_id, 
                p.title, 
                p.description as abstract, 
                g.group_code, 
                b.name as batch_name,
                sup.name as supervisor_name,
                (
                    SELECT GROUP_CONCAT(st.name SEPARATOR ', ')
                    FROM group_members gm
                    JOIN students st ON gm.student_id = st.user_id
                    WHERE gm.group_id = g.id
                ) as team_members
            FROM projects p
            JOIN `groups` g ON p.group_id = g.id
            JOIN academic_batches b ON g.batch_id = b.id
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id
            WHERE b.is_active = 0 AND p.status = 'Approved'
            ORDER BY b.created_at DESC, p.title ASC
        ");
        $projects = $stmt->fetchAll();

        // Extract unique batches and supervisors for filters
        $batches = [];
        $supervisors = [];
        foreach ($projects as $proj) {
            if ($proj['batch_name'] && !in_array($proj['batch_name'], $batches)) {
                $batches[] = $proj['batch_name'];
            }
            if ($proj['supervisor_name'] && !in_array($proj['supervisor_name'], $supervisors)) {
                $supervisors[] = $proj['supervisor_name'];
            }
        }
        
        sort($batches);
        sort($supervisors);

        // Render view (starting with student specific view as requested)
        $this->render('student/previous_projects', [
            'projects' => $projects,
            'batches' => $batches,
            'supervisors' => $supervisors
        ]);
    }
}
