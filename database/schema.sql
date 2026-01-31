-- Sohag School KG System Schema

CREATE DATABASE IF NOT EXISTS sohag_kg_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sohag_kg_system;

-- Users and Roles
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('manager', 'teacher') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Classes
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL, -- e.g., 'KG1-A'
    grade ENUM('PRE_KG', 'KG1') NOT NULL,
    academic_year VARCHAR(20) NOT NULL, -- e.g., '2025-2026'
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Teacher assignments
CREATE TABLE teacher_classes (
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    PRIMARY KEY (user_id, class_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Guardians
CREATE TABLE guardians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    phone_2 VARCHAR(20) NULL,
    national_id VARCHAR(20) NULL UNIQUE,
    relationship VARCHAR(50) NULL,
    address TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_guardian_nid (national_id)
);

-- Students
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    dob DATE NOT NULL,
    birth_place VARCHAR(100),
    religion VARCHAR(50),
    language VARCHAR(50),
    second_language VARCHAR(50),
    grade ENUM('PRE_KG', 'KG1') NOT NULL,
    class_id INT,
    academic_year VARCHAR(20) NOT NULL,
    address TEXT,
    guardian_id INT NOT NULL,
    status ENUM('active', 'inactive', 'graduated', 'withdrawn') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
    FOREIGN KEY (guardian_id) REFERENCES guardians(id),
    INDEX idx_student_name (full_name),
    INDEX idx_student_class (class_id)
);

-- Student Documents
CREATE TABLE student_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    document_type VARCHAR(50) NOT NULL DEFAULT 'other',
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Admissions (Registration MVP: draft → submitted → approved/rejected → locked)
CREATE TABLE admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    applied_grade ENUM('PRE_KG', 'KG1') NOT NULL,
    academic_year VARCHAR(20) NOT NULL DEFAULT '2025-2026',
    test_datetime_from DATETIME,
    test_datetime_to DATETIME,
    notes TEXT,
    status ENUM('draft', 'submitted', 'approved', 'rejected') NOT NULL DEFAULT 'draft',
    decision_note TEXT,
    decision_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL,
    submitted_at DATETIME NULL,
    approved_by INT NULL,
    locked_by INT NULL,
    locked_at DATETIME NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (locked_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_admission_status (status)
);

-- Committee Results (legacy)
CREATE TABLE committee_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admission_id INT NOT NULL,
    committee_name ENUM('Medical', 'Educational', 'Behavioral', 'SocialWorker', 'SpeechTherapist', 'Parent', 'IT', 'Activities', 'PE', 'Music') NOT NULL,
    score INT DEFAULT 0,
    result ENUM('accepted', 'rejected') NOT NULL,
    examiner VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE
);

-- Eleven registration committees: one row per committee per admission
CREATE TABLE registration_committee_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admission_id INT NOT NULL,
    committee_type VARCHAR(50) NOT NULL,
    result VARCHAR(20) NOT NULL DEFAULT 'pending',
    examiner VARCHAR(150) NULL,
    deputy_opinion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_admission_committee (admission_id, committee_type),
    FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
    INDEX idx_admission (admission_id)
);

-- One row per question/answer per committee result
CREATE TABLE registration_committee_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    committee_result_id INT NOT NULL,
    item_index INT NOT NULL,
    answer_text VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_result_item (committee_result_id, item_index),
    FOREIGN KEY (committee_result_id) REFERENCES registration_committee_results(id) ON DELETE CASCADE
);

-- Attendance
CREATE TABLE attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    date DATE NOT NULL,
    taken_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (taken_by) REFERENCES users(id),
    INDEX idx_attendance_session (class_id, date)
);

CREATE TABLE attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('present', 'absent', 'late') NOT NULL,
    reason TEXT,
    FOREIGN KEY (session_id) REFERENCES attendance_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Discipline Logs
CREATE TABLE discipline_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    type VARCHAR(50),
    severity ENUM('low', 'medium', 'high') NOT NULL,
    description TEXT,
    action_taken TEXT,
    created_by INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Exit Logs
CREATE TABLE exit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    picked_up_by VARCHAR(100) NOT NULL,
    picker_phone_or_id VARCHAR(50),
    reason TEXT,
    created_by INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Exams
CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year VARCHAR(20) NOT NULL,
    class_id INT NOT NULL,
    name VARCHAR(100) NOT NULL, -- e.g. 'First Term'
    subject_or_skill VARCHAR(100) NOT NULL,
    max_score INT NOT NULL,
    date DATE NOT NULL,
    is_published BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

CREATE TABLE exam_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    student_id INT NOT NULL,
    score DECIMAL(5,2),
    comment TEXT,
    entered_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (entered_by) REFERENCES users(id)
);

-- Fees
CREATE TABLE fee_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grade ENUM('PRE_KG', 'KG1') NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE fee_plan_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fee_plan_id INT NOT NULL,
    name VARCHAR(100) NOT NULL, -- e.g. 'Tuition', 'Books'
    amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (fee_plan_id) REFERENCES fee_plans(id) ON DELETE CASCADE
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    due_date DATE,
    status ENUM('unpaid', 'partially_paid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    INDEX idx_invoice_student (student_id)
);

CREATE TABLE invoice_lines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method ENUM('cash', 'card', 'bank_transfer') NOT NULL,
    date DATE NOT NULL,
    receipt_ref VARCHAR(50) UNIQUE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    INDEX idx_payment_date (date)
);

-- HR
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    code VARCHAR(20) UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    job_title VARCHAR(100),
    department VARCHAR(50),
    hire_date DATE,
    status ENUM('active', 'on_leave', 'terminated') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE employee_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME,
    check_out TIME,
    status ENUM('present', 'absent', 'late', 'excused') DEFAULT 'present',
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    type VARCHAR(50),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Audit and Security
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50),
    target_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempt_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_successful BOOLEAN DEFAULT FALSE,
    INDEX idx_login_attempts (username, ip_address, attempt_at)
);
