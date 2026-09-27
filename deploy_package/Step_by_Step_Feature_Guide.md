# FYP Management System - Feature & Action Guide

This guide provides a step-by-step walk-through of the features implemented in the system and the exact actions users take to utilize them.

## 1. Authentication & Security
* **Register:** Users navigate to the Registration page, fill in their details (Role, Name, Email, Password, Batch, etc.), and submit to create an account.
* **Login:** Users enter their credentials and are automatically routed to their specific role-based dashboard (Student, Supervisor, HOD, etc.).
* **Session Security:** The system features a 15-minute inactivity timeout. If a user is inactive, they are automatically logged out to protect their session.

## 2. Student Portal
* **Form a Group:** Navigate to the **Group** tab. The student can click "Create Group", then use "Add Member" to search and add peers to their FYP group.
* **Submit Proposal:** Once a group is formed, go to the **Proposal** section. The group leader fills out the project details, uploads necessary files, and clicks "Submit".
* **Real-time Chat:** Navigate to the **Chat** tab to instantly message the assigned project supervisor. Students can type messages or drag-and-drop documents and images directly into the chat.
* **AI Chatbot Assistance:** Use the integrated AI Chatbot to ask questions regarding department rules, project deadlines, or technical issues anytime.
* **View Grades:** Navigate to the **Grade** section to monitor evaluations and scores given by the supervisor and committee members.

## 3. Supervisor Portal
* **Review Proposals:** On the Dashboard, supervisors can view pending project proposals from student groups. They can preview documents natively (like PDFs) and choose to **Accept** or **Reject** the proposal.
* **Grade Assigned Groups:** Navigate to the **Groups** tab, select an assigned group, and click "Grade". Supervisors can assign specific "Supervision Marks" to individual students in the group based on their ongoing progress.
* **Real-Time Communication:** Go to the **Chat** tab to answer student queries, review shared documents, and provide instant, seamless feedback.
* **Grade Visibility:** Supervisors can toggle the visibility of their grades so students only see the marks when the supervisor chooses to publish them.

## 4. Committee Member Portal
* **Evaluate Presentations:** Navigate to **Evaluations** to view scheduled project presentations (Proposal Defense, Progress Presentation, Final Defense).
* **Individual Grading:** Click "Grade" on a specific group to enter detailed, granular scores for each individual student within that group. The system automatically calculates and validates the scores.
* **Finalize Grades:** Committee members can use the visibility toggle to publish finalized presentation scores to the students.

## 5. Department Coordinator Portal
* **Student Verification:** Go to **Users** or **Verify Students**. Coordinators review newly registered students and can **Approve** or **Reject** them, ensuring only valid students access the system.
* **Broadcast Notices:** Navigate to **Notice**, click "Create Notice", enter the subject and details, and broadcast important announcements system-wide.
* **Generate External Assessment:** Go to **Assessment** and click "Generate". The system will dynamically generate and download an External Assessment Sheet (CSV format) that can be filtered by specific attributes and shifts.

## 6. HOD (Head of Department) Portal
* **Manage Staff Accounts:** Navigate to **Supervisors**, **Committee**, or **Coordinators** tabs. The HOD has the authority to **Create, Edit, or Delete** these faculty and administrative accounts.
* **Final Student Approval:** Navigate to **Students -> Verify** to provide the ultimate administrative approval or rejection for student registrations.

## 7. System Admin Portal
* **Manage System Deadlines:** Navigate to **Deadlines** to set and enforce strict cutoff dates for system activities (like group formation or proposal submission).
* **Manage Batches:** Go to **Batches** to **Create** new academic batches and toggle which batch is currently active in the system.
* **Assign Supervisors:** Navigate to **Assign Supervisor** to manually allocate specific supervisors to approved project groups.
* **Total Oversight:** Admins have full capabilities to **Edit or Delete** Users, Groups, Projects, and Grades if manual intervention is required.
