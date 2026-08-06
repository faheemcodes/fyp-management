<div align="center">
  <img src="https://raw.githubusercontent.com/faheemcodes/fyp-management/main/public/images/logo.png" alt="FYP Management Logo" width="120" />
  <h1>🎓 FYP Management Portal</h1>
  <p><strong>A Modern, Unified Ecosystem for Academic Project Management</strong></p>
  <p>
    <img src="https://img.shields.io/badge/Status-Active-success.svg?style=for-the-badge" alt="Status" />
    <img src="https://img.shields.io/badge/UI-Glassmorphism-blueviolet.svg?style=for-the-badge" alt="UI Style" />
    <img src="https://img.shields.io/badge/Features-AI%20Chatbot-00C7B7.svg?style=for-the-badge" alt="AI Features" />
    <img src="https://img.shields.io/badge/PHP-8.1+-777BB4.svg?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version" />
  </p>
</div>

<br />

Welcome to the **FYP (Final Year Project) Management Portal**! This is a state-of-the-art, beautifully designed management ecosystem built specifically to streamline the workflow between students, supervisors, coordinators, and Heads of Departments (HOD) in universities. 

No more scattered emails, lost files, or messy spreadsheets. This platform handles project proposals, evaluations, deadlines, chat functionality, and AI-assisted interactions—all in one sleek, modern interface.

---

## ✨ Core Philosophy & UI

The portal is designed with a **Premium, Modern UI/UX** aesthetic. It relies on minimalist hybrid layouts, sharp outer edges, rounded inner elements, frosted-glass effects (glassmorphism), deep vibrant colors, and smooth micro-animations. It automatically supports both **Light** and **Dark Mode**, ensuring optimal readability in any environment.

---

## 💻 Technology Stack

* **Backend Environment:** PHP 8.x (Custom MVC Routing Architecture)
* **Database Engine:** MySQL / MariaDB (PDO Prepared Statements)
* **Frontend Structure:** HTML5 & Vanilla JavaScript
* **Styling Framework:** Vanilla CSS (CSS Variables for Theming) + Bootstrap 5 Grid System
* **Third-Party Integrations:** PHPMailer (SMTP), AOS (Animate on Scroll)

---

## 👥 Role-Based Capabilities

The ecosystem is built around 5 distinct roles, each receiving a fully customized dashboard tailored to their specific needs.

### 🧑‍🎓 1. Students
* **Project Proposals:** Form groups, submit detailed project proposals, and track their real-time approval status (Pending ➔ Supervisor Review ➔ Committee Review ➔ Accepted).
* **AI Assistance:** Chat with an integrated, context-aware AI Chatbot right from the dashboard to get instant help regarding project guidelines, deadlines, and formatting.
* **Direct Communication:** Securely message their assigned Supervisor through a built-in real-time chat interface.
* **Document Management:** Upload project reports, presentations, and code repositories directly to their portal.

### 👨‍🏫 2. Supervisors
* **Proposal Review:** Accept or reject student project proposals with detailed feedback and revision requests.
* **Mentorship Chat:** Communicate directly with all their assigned project groups in dedicated chat rooms.
* **Progress Tracking:** Monitor the weekly and monthly progress of supervised groups.
* **Grading Access:** Submit mid-term and final-term grades for their specific supervisees.

### 📋 3. Department Committee
* **Evaluation Management:** View all approved projects and conduct structured evaluations using predefined rubrics.
* **Digital Grading Sheets:** Input marks digitally during defense presentations, automatically generating comprehensive evaluation reports.
* **Bulk Processing:** Download/export printable grading sheets for physical record keeping.
* **Final Approvals:** Provide the second layer of proposal vetting after supervisor acceptance.

### ⚙️ 4. FYP Coordinators (Admin)
* **User Management:** Approve, verify, or reject new student registrations to ensure only authorized university students access the portal. Automated email notifications are sent upon approval/rejection.
* **Global Announcements:** Post critical deadlines, guidelines, and updates to the central Notice Board, visible to all users.
* **External Assessment:** Generate and assign external assessment links for guest examiners.
* **System Oversight:** Maintain a bird's-eye view of all projects, resolving deadlocks and managing departmental timelines.

### 👑 5. Head of Department (HOD)
* **Analytics Dashboard:** View statistical breakdowns of project domains, pass/fail rates, and supervisor loads.
* **Executive Approval:** Final sign-off on contentious projects or grade disputes.

---

## 🛠️ Advanced Functional Features

| Feature | Description |
| :--- | :--- |
| **Real-Time Notification System** | Instant alerts for proposal updates, new chat messages, and impending deadlines. |
| **Integrated SMTP Mailer** | Automated email dispatch for account verification, password resets, and Contact Us queries. |
| **AI Chatbot Engine** | A floating AI assistant that can answer FAQs, summarize rules, and guide students, reducing the manual support load on coordinators. |
| **Responsive Mobile Layouts** | Flawless rendering on mobile devices, ensuring students and faculty can manage projects on the go. |
| **Dynamic Notice Board** | A beautiful masonry-grid notice board for broadcasting departmental alerts with attached files and links. |

---

## 🔒 Security Standards

We take security seriously in academic software. The platform implements the following protocols:
* **Anti-CSRF Tokens:** All state-changing forms and AJAX requests are validated against unique session CSRF tokens.
* **XSS Prevention:** Strict HTML sanitization on all user-submitted text and outputs.
* **SQL Injection Immunity:** 100% reliance on PDO Prepared Statements for database interactions.
* **Secure Sessions:** Regenerated session IDs upon login to prevent session fixation attacks.

---

<div align="center">
  <i>Designed and engineered with ❤️ for academia. Elevating the standard of university project management.</i>
</div>
