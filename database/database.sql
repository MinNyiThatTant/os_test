-- Create Database
CREATE DATABASE IF NOT EXISTS cloud_db;
USE cloud_db;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    roll_no VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Questions table with category_id
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Student attempts
CREATE TABLE IF NOT EXISTS student_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    category_id INT,
    score INT DEFAULT 0,
    total_questions INT DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attempt (student_id, category_id)
);

-- Categories
INSERT INTO categories (name, description) VALUES
('Test 1 - Cloud Computing Basics', 'Fundamental cloud computing concepts - 10 questions'),
('Test 2 - Cloud Service Models', 'IaaS, PaaS, SaaS and deployment models - 15 questions'),
('Test 3 - Cloud Security & Management', 'Cloud security, compliance, and management - 12 questions');

-- Sample Questions for Test 1 (category_id = 1)
IINSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(1, 'What is cloud computing?', 'Storing files on local hard drive', 'Delivering computing services over the internet', 'Installing software on a single computer', 'Using a physical server in office', 'B'),
(1, 'Which of the following is NOT a characteristic of cloud computing?', 'On-demand self-service', 'Broad network access', 'Limited scalability', 'Resource pooling', 'C'),
(1, 'What does "pay-as-you-go" mean in cloud computing?', 'Pay monthly fixed fee only', 'Pay only for resources you actually use', 'Free for first year only', 'Pay based on company size', 'B'),
(1, 'Which is a major cloud service provider?', 'Microsoft Office', 'Amazon Web Services (AWS)', 'Google Chrome', 'Mozilla Firefox', 'B'),
(1, 'What is a public cloud?', 'Cloud owned by a single organization', 'Cloud only for government use', 'Cloud open to general public', 'Cloud requiring special permission', 'C'),
(1, 'What is a private cloud?', 'Cloud used only by one organization', 'Cloud free for everyone', 'Cloud with no security', 'Cloud for gaming only', 'A'),
(1, 'What is a hybrid cloud?', 'Combination of public and private cloud', 'Only public cloud', 'Only private cloud', 'No internet connection', 'A'),
(1, 'Which of these is a benefit of cloud computing?', 'Higher hardware costs', 'Limited accessibility', 'Elasticity (scale up/down as needed)', 'Redundant local servers only', 'C'),
(1, 'What is multi-tenancy in cloud computing?', 'Single user per system', 'Multiple customers sharing same infrastructure', 'Ten different servers required', 'No sharing allowed', 'B'),
(1, 'Which company offers "Azure" cloud platform?', 'Amazon', 'Google', 'Microsoft', 'IBM', 'C');