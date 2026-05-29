DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    avatar_color VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    icon VARCHAR(10) NOT NULL,
    color VARCHAR(20) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    priority ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
    deadline DATE NOT NULL,
    progress INT NOT NULL DEFAULT 0,
    status ENUM('Not Started', 'In Progress', 'Completed', 'Overdue') NOT NULL DEFAULT 'Not Started',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, avatar_color) VALUES
('Nadine', 'nadine@studysync.local', 'nadine123', '#5B6CFF'),
('Audrey', 'audrey@studysync.local', 'audrey123', '#7C4DFF'),
('Richard', 'richard@studysync.local', 'richard123', '#22D3EE');

INSERT INTO categories (name, icon, color) VALUES
('Database', 'DB', '#5B6CFF'),
('Statistics', 'ST', '#FBBF24'),
('Operating Systems', 'OS', '#22D3EE'),
('Data Science', 'DS', '#7C4DFF'),
('Programming', 'PR', '#4CAF50'),
('Mathematics', 'MT', '#FF6B6B');

INSERT INTO tasks (user_id, category_id, title, description, priority, deadline, progress, status) VALUES
(1, 1, 'ERD group report', 'Finalize entity relationship diagram and write normalization notes.', 'High', '2026-06-02', 70, 'In Progress'),
(1, 5, 'JavaScript mini project', 'Build task filtering feature for web programming practice.', 'Medium', '2026-06-06', 45, 'In Progress'),
(1, 2, 'Statistics quiz review', 'Review probability distribution examples before quiz.', 'High', '2026-05-30', 20, 'Not Started'),
(1, 6, 'Linear algebra exercises', 'Complete matrix transformation worksheet.', 'Low', '2026-06-12', 100, 'Completed'),
(2, 4, 'Data visualization notebook', 'Clean dataset and prepare charts for presentation.', 'High', '2026-06-03', 55, 'In Progress'),
(2, 3, 'Operating Systems quiz', 'Review key concepts for the upcoming quiz.', 'Medium', '2026-06-08', 10, 'Not Started'),
(2, 1, 'SQL join practice', 'Practice inner join, left join, and grouping queries.', 'Low', '2026-06-11', 100, 'Completed'),
(2, 5, 'PHP session demo', 'Prepare login session explanation for class demo.', 'High', '2026-05-31', 35, 'In Progress'),
(3, 5, 'Algorithm assignment', 'Solve sorting and searching exercises.', 'High', '2026-06-01', 80, 'In Progress'),
(3, 6, 'Calculus worksheet', 'Finish derivative applications worksheet.', 'Medium', '2026-06-05', 25, 'Not Started'),
(3, 2, 'Survey analysis', 'Summarize survey responses and create basic tables.', 'Medium', '2026-06-09', 60, 'In Progress'),
(3, 4, 'Model evaluation notes', 'Compare accuracy, precision, recall, and F1 score.', 'Low', '2026-06-14', 100, 'Completed');
