-- Create database
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT 'default-avatar.jpg',
    phone VARCHAR(20),
    address TEXT,
    member_since TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Books table
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    cover VARCHAR(255),
    description TEXT,
    isbn VARCHAR(13) UNIQUE,
    publisher VARCHAR(100),
    pages INT,
    language VARCHAR(50),
    rating DECIMAL(3,1),
    total_copies INT DEFAULT 1,
    available_copies INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Forum Categories table
CREATE TABLE forum_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Discussions table
CREATE TABLE discussions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    tags VARCHAR(255),
    view INT DEFAULT 0,
    likes INT DEFAULT 0,
    is_sticky BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES forum_categories(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Discussion Replies table
CREATE TABLE discussion_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    discussion_id INT,
    user_id INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (discussion_id) REFERENCES discussions(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Borrowing Records table
CREATE TABLE borrowing_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    book_id INT,
    borrow_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date TIMESTAMP NOT NULL,
    return_date TIMESTAMP NULL,
    status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Reservations table
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    book_id INT,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'fulfilled', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- User Reading Interests table
CREATE TABLE user_interests (
    user_id INT,
    interest VARCHAR(50),
    PRIMARY KEY (user_id, interest),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


-- Events table
CREATE TABLE events (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    event_name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    even_date DATE
);

-- Insert default forum categories
INSERT INTO forum_categories (name, description) VALUES
('Book Clubs', 'Join our book club discussions and reading groups'),
('Study Groups', 'Find study partners and academic discussion groups'),
('Local History', 'Discuss and share local historical content'),
('Reading Challenges', 'Participate in reading challenges and track progress'),
('Book Discussions', 'General book discussions and recommendations');