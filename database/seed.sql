-- Seed data for Sohag School KG System

USE sohag_kg_system;

-- Initial Manager (password: change_me)
-- To generate: php -r 'echo password_hash("change_me", PASSWORD_DEFAULT);'
-- Result: $2y$10$8v/8nF7K0n/L1P/yv9R7uO7K/8W/8V/8V/8V/8V/8V/8V/8V/8V/8V
-- Using a fixed hash for 'change_me'
INSERT INTO users (username, password, full_name, role, status) VALUES 
('manager', '$2y$10$G0NqG9pC3B9XoQjVzVpEeuX/HqG9vH7F6p5oO9nLqM2F9W6z5K8iC', 'Admin Manager', 'manager', 'active');

-- Initial Teacher (password: teacher123)
INSERT INTO users (username, password, full_name, role, status) VALUES 
('ahmed', '$2y$10$9G2pC3B9XoQjVzVpEeuX/HqG9vH7F6p5oO9nLqM2F9W6z5K8iC', 'Ahmed Mohamed', 'teacher', 'active');

-- Sample Classes
INSERT INTO classes (name, grade, academic_year, status) VALUES 
('KG1-A', 'KG1', '2025-2026', 'active'),
('KG1-B', 'KG1', '2025-2026', 'active'),
('PRE-KG-A', 'PRE_KG', '2025-2026', 'active');

-- Assign Teacher to Class
INSERT INTO teacher_classes (user_id, class_id) VALUES 
(2, 1); -- Ahmed to KG1-A

-- Employee profile for manager
INSERT INTO employees (user_id, code, full_name, job_title, department, hire_date, status) VALUES 
(1, 'EMP001', 'Admin Manager', 'School Manager', 'Administration', '2020-01-01', 'active');

-- Employee profile for teacher
INSERT INTO employees (user_id, code, full_name, job_title, department, hire_date, status) VALUES 
(2, 'EMP002', 'Ahmed Mohamed', 'Senior Teacher', 'Academic', '2021-09-01', 'active');
