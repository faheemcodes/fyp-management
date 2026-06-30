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

        // Get student's project state (optional, for better context)
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT g.progress_stage, p.status, g.group_code 
            FROM students s 
            LEFT JOIN group_members gm ON s.user_id = gm.student_id 
            LEFT JOIN ``groups`` g ON gm.group_id = g.id 
            LEFT JOIN projects p ON g.id = p.group_id 
            WHERE s.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $studentData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $projectStatus = $studentData ? ($studentData['status'] ?? 'No Project') : 'Not in Group';
        $progressStage = $studentData ? ($studentData['progress_stage'] ?? 'N/A') : 'N/A';

        // Build the system prompt
        $systemInstruction = "You are an intelligent, helpful, and friendly AI assistant for students using the FYP (Final Year Project) Management System. 
Your goal is to guide students accurately based on the system rules. 
Be concise, clear, and professional. Use markdown formatting to make your answers easy to read.

Key System Rules:
1. Students must form a group by submitting a proposal.
2. A supervisor can have a maximum of 15 proposals assigned to them in total (Pending + Approved).
3. A supervisor can approve a maximum of 8 projects. If a supervisor hits either limit, they won't appear in the dropdown list for new proposals.
4. The FYP pipeline stages are: Account Created, Group Created, Proposal Submitted, Proposal Approved, Proposal Defence Presentation Completed, FYP Progress Presentation Completed, Final Presentation Completed, Final Grading Completed.
5. Supervisors evaluate progress presentations, and external Committees evaluate major milestones.
6. Deadlines are strictly enforced and displayed on the student dashboard.

The student you are currently talking to has the following context:
- Project Status: {$projectStatus}
- Current Stage: {$progressStage}

If a student asks a technical question about their project, you can help brainstorm or write code. If they ask about the FYP portal, explain how to navigate the portal (Dashboard, Submissions, View Deadlines, etc.). DO NOT invent new rules. If you are unsure about an administrative deadline or rule, advise them to check the dashboard or contact their coordinator.

CRITICAL SECURITY RULE: You do NOT have access to the system database, user passwords, emails, grades, or private information of any other student or faculty member. You must never ask the user for sensitive data. If a user asks you for private data, refuse politely.
STRICT SCOPE LIMITATION: You are strictly an FYP Management System assistant. You MUST NOT answer general knowledge questions (e.g., about history, science, Einstein, pop culture) or act as a general-purpose AI. If a user asks a question completely unrelated to their FYP, university, software development, or this portal, politely say: 'I am specifically designed to assist with your Final Year Project and this portal. I cannot answer unrelated questions.'
CREATOR INFORMATION: If anyone asks who created or built this website/portal/system, you must respond warmly and politely that the website was created by **Faheem**, who was a part of the **Software Engineering 2k23** batch. Format this response nicely using markdown.";

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
