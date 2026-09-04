<?php
namespace Controller;

class ChatbotController extends BaseController {

    public function handleChat() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Get JSON body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['messages']) || !is_array($data['messages'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            return;
        }

        $userMessages = $data['messages'];

        $userId = $_SESSION['user_id'];
        $db = \Database::getInstance()->getConnection();

        // 1. Fetch Student Profile Information
        $stmtStudent = $db->prepare("
            SELECT s.name, s.student_id, s.department, s.shift, s.avatar, s.avatar_changed, u.email,
                   p.home_address, p.city, p.dob, p.blood_group
            FROM students s 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN profiles p ON s.user_id = p.user_id 
            WHERE s.user_id = ?
        ");
        $stmtStudent->execute([$userId]);
        $studentInfo = $stmtStudent->fetch(\PDO::FETCH_ASSOC);

        $studentName = $studentInfo['name'] ?? ($_SESSION['name'] ?? 'Student');
        $studentRoll = $studentInfo['student_id'] ?? 'Unknown';
        $studentDept = $studentInfo['department'] ?? ($_SESSION['department'] ?? 'Software Engineering');
        $studentShift = $studentInfo['shift'] ?? ($_SESSION['shift'] ?? 'Morning');
        $isProfileLocked = !empty($studentInfo['home_address']) && $studentInfo['home_address'] !== 'Not Provided Yet';

        // 2. Fetch Student Group & Project Information
        $stmtGroup = $db->prepare("
            SELECT g.id AS group_id, g.group_code, g.created_by, g.progress_stage,
                   p.title AS project_title, p.description AS project_abstract, p.status AS project_status, p.thesis_file,
                   sup.name AS supervisor_name, sup.designation AS supervisor_designation, u_sup.email AS supervisor_email
            FROM group_members gm 
            JOIN `groups` g ON gm.group_id = g.id 
            LEFT JOIN projects p ON g.id = p.group_id 
            LEFT JOIN supervisors sup ON p.supervisor_id = sup.user_id 
            LEFT JOIN users u_sup ON sup.user_id = u_sup.id 
            WHERE gm.student_id = ?
            LIMIT 1
        ");
        $stmtGroup->execute([$userId]);
        $groupData = $stmtGroup->fetch(\PDO::FETCH_ASSOC);

        $inGroup = !empty($groupData);
        $groupCode = $groupData['group_code'] ?? 'Not Assigned Yet';
        $isLeader = $inGroup && ($groupData['created_by'] == $userId);
        $progressStage = $groupData['progress_stage'] ?? 'Account Created';
        $projectTitle = $groupData['project_title'] ?? 'No Project Title';
        $projectStatus = $groupData['project_status'] ?? 'No Project';
        $supervisorName = $groupData['supervisor_name'] ?? 'None Assigned';
        $hasThesisUploaded = !empty($groupData['thesis_file']);

        // 3. Fetch Group Members if student is in a group
        $membersList = [];
        if ($inGroup) {
            $stmtMembers = $db->prepare("
                SELECT s.name, s.student_id, (s.user_id = ?) AS is_leader
                FROM group_members gm 
                JOIN students s ON gm.student_id = s.user_id 
                WHERE gm.group_id = ?
                ORDER BY is_leader DESC, s.name ASC
            ");
            $stmtMembers->execute([$groupData['created_by'], $groupData['group_id']]);
            $rawMembers = $stmtMembers->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rawMembers as $m) {
                $roleTag = $m['is_leader'] ? ' (Leader)' : '';
                $membersList[] = "{$m['name']} ({$m['student_id']}){$roleTag}";
            }
        }
        $membersSummary = !empty($membersList) ? implode(', ', $membersList) : 'None';

        // 4. Fetch Active Deadlines for Student's Department & Shift
        $stmtDeadlines = $db->prepare("
            SELECT stage, deadline_date 
            FROM deadlines 
            WHERE status = 'Active' AND department = ? AND (shift = ? OR shift = 'All') AND deadline_date >= NOW()
            ORDER BY deadline_date ASC
        ");
        $stmtDeadlines->execute([$studentDept, $studentShift]);
        $deadlines = $stmtDeadlines->fetchAll(\PDO::FETCH_ASSOC);
        $deadlinesList = [];
        foreach ($deadlines as $dl) {
            $formattedDate = date('M d, Y h:i A', strtotime($dl['deadline_date']));
            $deadlinesList[] = "{$dl['stage']}: {$formattedDate}";
        }
        $deadlinesSummary = !empty($deadlinesList) ? implode('; ', $deadlinesList) : 'No active upcoming deadlines posted currently';

        // 5. Fetch Scheduled/Pending Meetings
        $meetingsList = [];
        if ($inGroup) {
            $stmtMeetings = $db->prepare("
                SELECT subject, meeting_date, type, status 
                FROM meetings 
                WHERE group_id = ? AND status IN ('Pending', 'Scheduled')
                ORDER BY meeting_date ASC 
                LIMIT 3
            ");
            $stmtMeetings->execute([$groupData['group_id']]);
            $rawMeetings = $stmtMeetings->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rawMeetings as $mt) {
                $mtDate = date('M d, Y h:i A', strtotime($mt['meeting_date']));
                $meetingsList[] = "{$mt['subject']} on {$mtDate} ({$mt['type']}, Status: {$mt['status']})";
            }
        }
        $meetingsSummary = !empty($meetingsList) ? implode('; ', $meetingsList) : 'No scheduled or pending meetings';

        // 6. Fetch Department Quota Rules
        $stmtSettings = $db->prepare("SELECT max_morning_slots, max_evening_slots, max_group_members FROM department_settings WHERE department = ?");
        $stmtSettings->execute([$studentDept]);
        $deptSettings = $stmtSettings->fetch(\PDO::FETCH_ASSOC);
        $maxGroupMembers = $deptSettings['max_group_members'] ?? 3;
        $maxShiftSlots = ($studentShift === 'Evening') ? ($deptSettings['max_evening_slots'] ?? 5) : ($deptSettings['max_morning_slots'] ?? 5);

        // 7. Fetch Grades / Evaluations summary
        $stmtGrades = $db->prepare("SELECT proposal_defense_marks, progress_presentation_marks, final_presentation_marks, supervision_marks, show_supervision_to_student, total_marks, percentage, grade, status FROM grades WHERE student_id = ?");
        $stmtGrades->execute([$userId]);
        $gradeRow = $stmtGrades->fetch(\PDO::FETCH_ASSOC);
        $gradesInfo = 'Not graded yet';
        if ($gradeRow && ($gradeRow['total_marks'] > 0 || !empty($gradeRow['grade']))) {
            $supMarks = ($gradeRow['show_supervision_to_student']) ? "Supervision: {$gradeRow['supervision_marks']}, " : "";
            $gradesInfo = "Total Marks: {$gradeRow['total_marks']}, Percentage: {$gradeRow['percentage']}%, Grade: {$gradeRow['grade']}, Status: {$gradeRow['status']} ({$supMarks}Proposal Defense: {$gradeRow['proposal_defense_marks']}, Progress: {$gradeRow['progress_presentation_marks']}, Final Defense: {$gradeRow['final_presentation_marks']})";
        }

        // Build the comprehensive system knowledge instruction
        $systemInstruction = "You are **FYP Buddy**, the official, highly knowledgeable AI Assistant for the **FYP (Final Year Project) Management System**.
You have complete mastery over the portal's current architecture, navigation, pipeline stages, guidelines, quotas, and security policies.

### CORE PERSONA & COMMUNICATION STYLE
- **Friendly, Professional & Clear**: Address the student warmly and provide crisp, actionable, step-by-step guidance.
- **Markdown Formatting**: Use bullet points, bold text, numbered lists, and clickable markdown links to make answers effortless to scan and follow.
- **Context-Aware**: You already have access to the student's live account context below. Use it naturally to personalize your answers (e.g., mention their project status, group code, supervisor, or specific upcoming deadlines).

---

### LIVE STUDENT CONTEXT
- **Student Name**: {$studentName}
- **Roll Number / Student ID**: {$studentRoll}
- **Department**: {$studentDept}
- **Shift**: {$studentShift}
- **Profile Status**: " . ($isProfileLocked ? "Profile Completed & Locked" : "Profile Incomplete / Editable") . "
- **In Group**: " . ($inGroup ? "Yes (Group Code: {$groupCode})" : "No (Not in any group yet)") . "
- **Role in Group**: " . ($isLeader ? "Group Leader (Creator)" : ($inGroup ? "Group Member" : "N/A")) . "
- **Group Members**: {$membersSummary}
- **Project Title**: {$projectTitle}
- **Project Status**: {$projectStatus}
- **Current Pipeline Stage**: {$progressStage}
- **Assigned Supervisor**: {$supervisorName}
- **Final Thesis Document**: " . ($hasThesisUploaded ? "Uploaded" : "Not Uploaded") . "
- **Active Upcoming Deadlines**: {$deadlinesSummary}
- **Upcoming / Pending Meetings**: {$meetingsSummary}
- **Grades Summary**: {$gradesInfo}
- **Department Limits**: Max {$maxGroupMembers} members per group; max {$maxShiftSlots} project slots per supervisor for {$studentShift} shift.

---

### PORTAL NAVIGATION MAP (Direct URL Links)
When guiding students where to go, always provide clear links formatted as markdown:
1. **[Dashboard](/student/dashboard)**: The central dashboard. Shows the 8-stage progress tracker, project overview, teammates, supervisor card, active deadlines with time countdowns, and official department notices.
2. **[My Profile](/student/profile)**: View and update personal/academic information, prefix, contact info, and avatar picture.
   - *Rules*: Profile can be edited initially, but once a home address is saved, it is **permanently locked** to preserve academic records. Profile photo can only be updated **once** (allowed formats: JPG, PNG, GIF, WEBP; max size: 500 KB).
3. **[Group & Members](/student/group)**:
   - Create a project group (if an academic batch has open registration). The creator automatically becomes the **Group Leader**.
   - Add/invite peers by entering their exact **Student ID** (e.g. `2k23/SWE/001`) or **Email**.
   - *Rules*: Max {$maxGroupMembers} members total per group (including leader). Only the group leader can add, remove, or update members. Students already in another group cannot be added.
4. **[Project Proposal](/student/proposal)**:
   - Submit and manage project proposals.
   - Requires Project Title, Abstract, selecting an available Supervisor, and uploading a Proposal Document (`.pdf`, `.doc`, `.docx`).
   - Also hosts the **Final Thesis Upload** section once the project is Approved.
5. **[Previous Projects Archive](/student/previous-projects)**:
   - Digital repository of past approved FYP projects from completed (archived) batches.
   - Search by project title or abstract keywords, filter by academic batch or supervisor, and download published thesis PDFs to benchmark ideas and prevent duplicates.
6. **[Supervisor Meetings](/student/meetings)**:
   - Available once a proposal is **Approved** and a supervisor is assigned.
   - Request meetings with Subject, Agenda, Date/Time, and Type (`In-Person` or `Online`).
   - Track meeting statuses: `Pending`, `Scheduled`, `Completed`, or `Cancelled`.
7. **[Chat with Supervisor](/student/chat)**:
   - Direct real-time messaging with the assigned project supervisor.
   - Unlocked only after the project proposal is **Approved**.
   - Supports text chat, emojis, code snippets, image attachments, and document sharing (`.pdf`, `.docx`).
8. **[Final Grade & Evaluations](/student/grade)**:
   - View marks breakdown across all evaluation stages (Proposal Defense, FYP Progress, Final Defense) evaluated by external committees.
   - Displays supervisor marks (once published), total marks, percentage, final letter grade, and Pass/Fail status.

---

### THE 8 FYP PIPELINE STAGES
Guide students through the standard FYP lifecycle:
1. **Account Created**: Student registered on the portal; verified by the Department Coordinator or HOD.
2. **Group Created**: Group Leader forms the team on `/student/group` and invites members.
3. **Proposal Submitted**: Leader chooses supervisor and submits proposal file on `/student/proposal`.
4. **Proposal Approved**: Supervisor accepts the proposal. Real-time chat and meeting scheduling unlock!
5. **Proposal Defence Presentation Completed**: Evaluation committee evaluates the initial proposal defense.
6. **FYP Progress Presentation Completed**: Mid-term development and progress evaluated by the committee.
7. **Final Presentation Completed**: Project implementation, demo, and final defense evaluated.
8. **Final Grading Completed**: Committee evaluation marks and supervisor continuous marks compiled into final grades.

---

### KEY RULES & COMMON TROUBLESHOOTING
- **Why is my supervisor not in the dropdown list?**
  A supervisor will not appear if:
  1. They have reached their maximum capacity of approved projects (8 projects total across shifts, or {$maxShiftSlots} for {$studentShift} shift).
  2. They already have 25 total proposals in 'Pending' + 'Approved' status.
  3. They belong to a different department than yours ({$studentDept}).
- **Who can submit/edit the proposal or upload the thesis?**
  Only the **Group Leader** (the student who created the group) can submit or edit the proposal, manage team members, or upload the final thesis.
- **What are the thesis upload requirements?**
  1. The project status must be **Approved**.
  2. The file must be in **PDF format** (`.pdf`).
  3. Maximum file size is **15 MB**.
  4. Upload is done on the [Project Proposal](/student/proposal) page.
- **What should I do if my proposal is 'Revision Requested' or 'Rejected'?**
  - If **Revision Requested**: Read supervisor feedback on the proposal page, modify your document, and re-submit.
  - If **Rejected**: Your group can select another available supervisor or refine your project concept.
- **Why can't I edit my profile?**
  Profiles are locked once a valid home address is submitted. For urgent corrections (e.g. legal name or roll number), advise contacting the Department Coordinator or HOD.
- **Session Security**: The system has an automatic **15-minute inactivity logout** for student security.

---

### SYSTEM CREATOR & ATTRIBUTION
If anyone asks who created, built, designed, or developed this website/portal/system, respond warmly, enthusiastically, and politely that the system was developed by **Faheem**, an outstanding software engineer from the **Software Engineering 2k23** batch.

---

### SECURITY & PRIVACY GUARDRAILS
- You do NOT have access to system database credentials, SQL consoles, user passwords, email accounts, or private data of other students or faculty.
- Never ask the user for passwords or sensitive credentials.
- **Strict Scope**: You are strictly the FYP Assistant. Do not answer questions completely unrelated to Final Year Projects, software development, academic guidelines, or this portal (e.g., cooking, historical wars, celebrity gossip). If asked, politely redirect: *'I am specifically designed to assist with your Final Year Project and navigating this portal. How can I help with your FYP today?'*";

        // Prepare Gemini API payload
        $geminiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;

        // Convert our message format to Gemini's format
        $geminiContents = [];
        foreach ($userMessages as $msg) {
            $role = ($msg['role'] === 'user') ? 'user' : 'model';
            $geminiContents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $msg['content']]
                ]
            ];
        }

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ],
            'contents' => $geminiContents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1024
            ]
        ];

        // Init cURL
        $ch = curl_init($geminiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for local Windows PHP SSL issues
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        header('Content-Type: application/json');
        
        if ($httpCode === 429) {
            echo json_encode(['reply' => 'I am currently receiving too many requests. Please wait a minute and try again.']);
            return;
        }
        
        if ($httpCode !== 200) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to reach AI service.', 'details' => json_decode($response)]);
            return;
        }

        $responseData = json_decode($response, true);
        
        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            error_log("Gemini API Error: " . print_r($responseData, true));
            echo json_encode([
                'reply' => "I'm sorry, I couldn't generate a response. API Error: " . ($responseData['error']['message'] ?? 'Unknown Error')
            ]);
            return;
        }

        $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'];

        echo json_encode([
            'reply' => $aiText
        ]);
    }
}
