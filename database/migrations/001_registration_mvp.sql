-- Registration MVP: draft → submitted → approved/rejected → locked
-- Assumes database sohag_kg_system already exists (does not create or drop DB).
-- Run ONCE on existing DB to add new columns. Safe if you already ran updated schema.sql.

USE sohag_kg_system;

-- Guardians: optional phone_2, address; make national_id and relationship nullable
ALTER TABLE guardians ADD COLUMN phone_2 VARCHAR(20) NULL AFTER phone;
ALTER TABLE guardians ADD COLUMN address TEXT NULL AFTER relationship;
ALTER TABLE guardians MODIFY national_id VARCHAR(20) NULL UNIQUE;
ALTER TABLE guardians MODIFY relationship VARCHAR(50) NULL DEFAULT NULL;

-- Admissions: add registration/audit columns
ALTER TABLE admissions ADD COLUMN academic_year VARCHAR(20) NOT NULL DEFAULT '2025-2026' AFTER applied_grade;
ALTER TABLE admissions ADD COLUMN created_by INT NULL AFTER created_at;
ALTER TABLE admissions ADD COLUMN updated_by INT NULL AFTER created_by;
ALTER TABLE admissions ADD COLUMN submitted_at DATETIME NULL AFTER updated_by;
ALTER TABLE admissions ADD COLUMN approved_by INT NULL AFTER decision_at;
ALTER TABLE admissions ADD COLUMN locked_by INT NULL AFTER approved_by;
ALTER TABLE admissions ADD COLUMN locked_at DATETIME NULL AFTER locked_by;

-- Map old statuses to new before changing enum
UPDATE admissions SET status = 'submitted' WHERE status IN ('pending', 'testing');
UPDATE admissions SET status = 'approved' WHERE status = 'accepted';
ALTER TABLE admissions MODIFY status ENUM('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft';

-- Foreign keys (run after columns exist)
ALTER TABLE admissions ADD CONSTRAINT fk_adm_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL;
ALTER TABLE admissions ADD CONSTRAINT fk_adm_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL;
ALTER TABLE admissions ADD CONSTRAINT fk_adm_approved_by FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL;
ALTER TABLE admissions ADD CONSTRAINT fk_adm_locked_by FOREIGN KEY (locked_by) REFERENCES users(id) ON DELETE SET NULL;

-- Student documents: type for registration
ALTER TABLE student_documents ADD COLUMN document_type VARCHAR(50) NOT NULL DEFAULT 'other' AFTER file_type;
