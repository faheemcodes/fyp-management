# 🎓 FYP Management Portal

![FYP Management Banner](public/images/bg-dark.jpg)

Welcome to the **FYP (Final Year Project) Management Portal**! This is a state-of-the-art, beautifully designed management ecosystem built specifically to streamline the workflow between students, supervisors, coordinators, and Heads of Departments (HOD) in universities. 

No more scattered emails, lost files, or messy spreadsheets. This platform handles project proposals, evaluations, deadlines, chat functionality, and AI-assisted chatbot interactions—all in one sleek, modern interface.

---

## ✨ Key Features

- **Multi-Role Dashboards:** Unique, tailored experiences for Students, Supervisors, Coordinators, Committees, and HODs.
- **Project Lifecycle Management:** Complete tracking from group formation and project proposal submissions to final grading and evaluations.
- **Smart Chat System:** Real-time messaging between students and their supervisors.
- **AI Chatbot Assistant:** Integrated chatbot widget for students to get instant answers to their FYP-related queries.
- **Automated Evaluations:** Streamlined grading sheets and rubrics for committees and external evaluators.
- **Modern UI/UX:** A stunning, responsive design with glassmorphism effects, dynamic modals, and smooth animations.

---

## 🚀 Quick Setup & Installation

Getting the portal up and running is incredibly easy. 

### Prerequisites
Before you start, make sure you have the following installed on your machine:
- **PHP** (v8.1 or higher)
- **Composer** (PHP dependency manager)
- **MySQL / MariaDB** (via XAMPP, WAMP, or standalone)
- **Git** (for version control)

### Step-by-Step Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/faheemcodes/fyp-management.git
   cd fyp-management
   ```

2. **Automated Setup (Windows):**
   Simply double-click the `setup.bat` file in the main folder!
   *This will automatically check for Composer and install all the necessary PHP dependencies required by the project.*

3. **Database Configuration:**
   - Open XAMPP/WAMP and start **Apache** and **MySQL**.
   - Import the `schema.sql` file into your MySQL database using phpMyAdmin (create a database named `fyp_management`).
   - Copy `config/database.example.php` to `config/database.php` and update it with your actual MySQL credentials if they are different from the defaults.

4. **Email Configuration (Optional):**
   - Copy `config/mail.example.php` to `config/mail.php`.
   - Add your SMTP credentials (like Gmail App Passwords) if you want the system to send automated emails (e.g., password resets).

5. **Run the Live Server:**
   You can serve the application instantly using PHP's built-in server. Open your command prompt and run:
   ```bash
   php -S 0.0.0.0:8000 -t public
   ```
   *Your portal is now live at **http://localhost:8000**! Because we used `0.0.0.0`, you can even access it on your mobile phone by typing your computer's local IP address (e.g., `http://192.168.x.x:8000`).*

---

## 🔒 Security First

We take security seriously. All sensitive configuration files (like your database credentials and SMTP passwords) are protected and ignored by Git. We provide `.example.php` files so the project structure remains intact without compromising your private credentials on GitHub. 

---

## 🤝 Contributing

This project is built to serve institutes worldwide. Whether you want to fix a bug, add a feature, or improve the UI—your contributions are highly welcome! 

1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

---

*Designed and engineered with ❤️ for academia.*
