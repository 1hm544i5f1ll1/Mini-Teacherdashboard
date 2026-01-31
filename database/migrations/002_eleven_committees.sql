-- Eleven registration committees: results + item answers per committee
-- Assumes database sohag_kg_system exists. Run once.

USE sohag_kg_system;

-- One row per committee per admission (11 committees per registration)
CREATE TABLE IF NOT EXISTS registration_committee_results (
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
CREATE TABLE IF NOT EXISTS registration_committee_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    committee_result_id INT NOT NULL,
    item_index INT NOT NULL,
    answer_text VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_result_item (committee_result_id, item_index),
    FOREIGN KEY (committee_result_id) REFERENCES registration_committee_results(id) ON DELETE CASCADE
);
