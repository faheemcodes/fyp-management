-- Create database
CREATE DATABASE IF NOT EXISTS fyp_management;
USE fyp_management;

-- Drop tables if they exist to start fresh (in dependency order)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS evaluations;
DROP TABLE IF EXISTS documents;
DROP TABLE IF EXISTS proposals;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS group_members;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS profiles;
DROP TABLE IF EXISTS committees;
DROP TABLE IF EXISTS supervisors;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS deans;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    cnic VARCHAR(50) DEFAULT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dean', 'student', 'supervisor', 'committee') NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Deans Table
CREATE TABLE deans (
    user_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Students Table
CREATE TABLE students (
    user_id INT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    department VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    shift ENUM('Morning', 'Evening') NOT NULL DEFAULT 'Morning',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Supervisors Table
CREATE TABLE supervisors (
    user_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    designation VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    research_interest TEXT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Committees Table
CREATE TABLE committees (
    user_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Profiles Table
CREATE TABLE profiles (
    user_id INT PRIMARY KEY,
    prefix VARCHAR(10) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    cnic VARCHAR(20) NOT NULL UNIQUE,
    cnic_expiry DATE DEFAULT NULL,
    father_name VARCHAR(100) DEFAULT NULL,
    dob DATE NOT NULL,
    mobile_code VARCHAR(5) NOT NULL,
    mobile_no VARCHAR(15) NOT NULL,
    place_of_birth VARCHAR(100) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,
    province_state VARCHAR(100) DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    home_address TEXT NOT NULL,
    permanent_address TEXT DEFAULT NULL,
    zip_code VARCHAR(20) DEFAULT NULL,
    blood_group VARCHAR(5) DEFAULT NULL,
    gender VARCHAR(10) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Groups Table
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_code VARCHAR(50) NOT NULL UNIQUE,
    created_by INT NOT NULL,
    progress_stage ENUM(
        'Account Created', 
        'Group Created', 
        'Proposal Submitted', 
        'Proposal Approved', 
        'Proposal Defence Presentation Completed', 
        'FYP Progress Presentation Completed', 
        'Final Presentation Completed', 
        'Final Grading Completed'
    ) DEFAULT 'Group Created',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES students(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Group Members Table
CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    student_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_group_student (group_id, student_id),
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Projects Table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL UNIQUE,
    supervisor_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Revision Requested') DEFAULT 'Draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (supervisor_id) REFERENCES supervisors(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Proposals Table
CREATE TABLE proposals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL UNIQUE,
    abstract TEXT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    status ENUM('Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Revision Requested') DEFAULT 'Draft',
    feedback TEXT DEFAULT NULL,
    submitted_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents Table (Milestone Deliverable Uploads)
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    stage ENUM('Proposal Defence Presentation', 'FYP Progress Presentation', 'Final Presentation') NOT NULL,
    doc_type ENUM(
        'SRS', 
        'Literature Review', 
        'UML Diagrams', 
        'Prototype', 
        'Source Code', 
        'Final Report', 
        'User Manual', 
        'Test Cases', 
        'Deployment Guide', 
        'Presentation Slides', 
        'Demo Video', 
        'Other'
    ) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    feedback TEXT DEFAULT NULL,
    feedback_by INT DEFAULT NULL,
    feedback_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (feedback_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Evaluations Table (Proposal Defence, FYP Progress & Final Presentation)
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    evaluator_id INT NOT NULL,
    stage ENUM('Proposal Defence Presentation', 'FYP Progress Presentation', 'Final Presentation') NOT NULL,
    marks_details JSON NOT NULL, -- JSON structure for component marks
    total_marks DECIMAL(5,2) NOT NULL,
    remarks TEXT DEFAULT NULL,
    scheduled_date DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_group_evaluator_stage (group_id, evaluator_id, stage),
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluator_id) REFERENCES committees(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Grades Table (Overall system results)
CREATE TABLE grades (
    group_id INT PRIMARY KEY,
    proposal_marks DECIMAL(5,2) DEFAULT 0.00,
    proposal_defense_marks DECIMAL(5,2) DEFAULT NULL,
    progress_presentation_marks DECIMAL(5,2) DEFAULT NULL,
    final_presentation_marks DECIMAL(5,2) DEFAULT NULL,
    supervision_marks DECIMAL(5,2) DEFAULT NULL,
    total_marks DECIMAL(5,2) DEFAULT 0.00,
    percentage DECIMAL(5,2) DEFAULT 0.00,
    grade VARCHAR(5) DEFAULT 'F',
    status ENUM('Pass', 'Fail') DEFAULT 'Fail',
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications Table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Deadlines Table (Helper table for Admin/Dean to manage scheduling)
CREATE TABLE deadlines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stage ENUM('Proposal Submission', 'Proposal Defence Presentation', 'FYP Progress Presentation', 'Final Presentation') NOT NULL UNIQUE,
    deadline_date DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Data (Password hash is for 'AdminPass123!')
-- Admin Account
INSERT INTO users (id, email, cnic, password, role, status) VALUES 
(1, 'admin@fyp.com', '1111111111111', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'admin', 'approved');

-- Dean Account
INSERT INTO users (id, email, cnic, password, role, status) VALUES 
(2, 'dean@fyp.com', '2222222222222', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'dean', 'approved');
INSERT INTO deans (user_id, name, department) VALUES 
(2, 'Dr. Dean Ahmed', 'Computer Science & Software Engineering');

-- Supervisor Account
INSERT INTO users (id, email, cnic, password, role, status) VALUES 
(3, 'supervisor@fyp.com', '3333333333333', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'supervisor', 'approved');
INSERT INTO supervisors (user_id, name, designation, department, research_interest) VALUES 
(3, 'Dr. Faheem Associate Professor', 'Associate Professor', 'Computer Science', 'Artificial Intelligence, Machine Learning, Web Engineering');

-- Committee Member Account
INSERT INTO users (id, email, cnic, password, role, status) VALUES 
(4, 'committee@fyp.com', '4444444444444', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'committee', 'approved');
INSERT INTO committees (user_id, name, department) VALUES 
(4, 'Prof. Zahid Ali', 'Software Engineering');

-- Student Accounts
INSERT INTO users (id, email, cnic, password, role, status) VALUES 
(5, 'student1@fyp.com', '5555555555555', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'student', 'approved'),
(6, 'student2@fyp.com', '6666666666666', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'student', 'approved'),
(7, 'student3@fyp.com', '7777777777777', '$2y$12$R6aRPfeHg6u67TJswr.FieCt6g3eVLLPGxRK2WVK6dSHEjS/CyHR6', 'student', 'approved');

INSERT INTO students (user_id, student_id, name, phone, department, shift) VALUES 
(5, '2k23/SWE/001', 'Kamran Khan', '0300-1234567', 'Software Engineering', 'Morning'),
(6, '2k23/SWE/002', 'Saad Siddiqui', '0312-7654321', 'Software Engineering', 'Morning'),
(7, '2k23/SWE/050', 'Amna Bibi', '0333-9876543', 'Software Engineering', 'Morning');

-- Seed Profiles for Deans, Supervisors, Committee Members, and Students
INSERT INTO profiles (user_id, prefix, surname, cnic, dob, mobile_code, mobile_no, home_address, gender) VALUES 
(2, 'Dr.', 'Ahmed', '2222222222222', '1970-01-01', '+92', '0000000', 'Not Provided Yet', 'Male'),
(3, 'Dr.', 'Faheem', '3333333333333', '1980-01-01', '+92', '0000000', 'Not Provided Yet', 'Male'),
(4, 'Prof.', 'Zahid', '4444444444444', '1975-01-01', '+92', '0000000', 'Not Provided Yet', 'Male'),
(5, 'Mr.', 'Khan', '5555555555555', '2000-01-01', '+92', '3001234567', 'Not Provided Yet', 'Male'),
(6, 'Mr.', 'Siddiqui', '6666666666666', '2001-01-01', '+92', '3127654321', 'Not Provided Yet', 'Male'),
(7, 'Miss.', 'Bibi', '7777777777777', '2002-01-01', '+92', '3339876543', 'Not Provided Yet', 'Female');

-- Seed initial deadlines
INSERT INTO deadlines (stage, deadline_date) VALUES
('Proposal Submission', '2026-09-30 23:59:59'),
('Proposal Defence Presentation', '2026-10-30 23:59:59'),
('FYP Progress Presentation', '2026-12-15 23:59:59'),
('Final Presentation', '2027-05-15 23:59:59');
