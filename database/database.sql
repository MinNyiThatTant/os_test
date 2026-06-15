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
('Test 1 - Basic OS', 'Fundamental OS concepts - 10 questions'),
('Test 2 - Process Management', 'Process scheduling, threads - 15 questions'),
('Test 3 - Memory Management', 'Paging, segmentation, virtual memory - 12 questions');

-- Sample Questions for Test 1 (category_id = 1)
INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(1, 'What is the main function of an Operating System?', 'Manage hardware resources', 'Browse the internet', 'Edit documents', 'Play games', 'A'),
(1, 'Which of the following is NOT an OS?', 'Windows', 'Linux', 'Chrome', 'macOS', 'C'),
(1, 'What does CPU stand for?', 'Computer Processing Unit', 'Central Processing Unit', 'Core Program Unit', 'Control Process Unit', 'B'),
(1, 'Which scheduling algorithm is preemptive?', 'FCFS', 'SJF (non-preemptive)', 'Round Robin', 'LIFO', 'C'),
(1, 'What is a process?', 'Program in execution', 'File on disk', 'Memory partition', 'User command', 'A'),
(1, 'What is RAM?', 'Readily Available Memory', 'Random Access Memory', 'Rapid Access Module', 'Read Access Memory', 'B'),
(1, 'Which is a example of OS?', 'Linux', 'Python', 'Chrome', 'MS Word', 'A'),
(1, 'What is the kernel?', 'Core of OS', 'User interface', 'File system', 'Security module', 'A'),
(1, 'What is multitasking?', 'Running multiple programs at once', 'Multiple CPUs', 'Multiple users', 'Multiple files', 'A'),
(1, 'Which is a CLI?', 'Command Prompt', 'Windows Explorer', 'Desktop', 'Taskbar', 'A');
