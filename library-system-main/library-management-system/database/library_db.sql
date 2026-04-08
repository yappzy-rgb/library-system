CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Students Table
CREATE TABLE students (
    student_number VARCHAR(10) PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    course VARCHAR(50) NOT NULL,
    year_level VARCHAR(10) NOT NULL
);

-- Books Table
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    year_published YEAR,
    total_copies INT NOT NULL,
    available_copies INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Borrow Records Table
CREATE TABLE borrow_records (
    borrow_id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(10) NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('Borrowed', 'Returned') DEFAULT 'Borrowed',
    FOREIGN KEY (student_number) REFERENCES students(student_number),
    FOREIGN KEY (book_id) REFERENCES books(book_id)
);

-- Sample Data
INSERT INTO students (student_number, full_name, course, year_level) VALUES
('0326-0001', 'Zeus Macatlang', 'BS Computer Science', '3rd Year'),
('0326-0002', 'JM Coronado', 'BS Information Technology', '2nd Year'),
('0326-0003', 'Lujelle Violante', 'BS Accountancy', '4th Year');

INSERT INTO books (title, author, category, year_published, total_copies, available_copies) VALUES
('Introduction to Programming', 'Sir Dorado', 'Computer Science', 2020, 5, 3),
('Database Systems', 'Sir Lara', 'Database', 2019, 4, 2),
('Web Development', 'Sir Albes', 'Web Development', 2018, 6, 4),
('Data Structures', 'Sir Amora', 'Computer Science', 2021, 3, 1);