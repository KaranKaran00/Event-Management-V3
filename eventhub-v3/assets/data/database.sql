-- Run this in phpMyAdmin or MySQL CLI
CREATE DATABASE IF NOT EXISTS eventhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventhub;

-- Users table (for login/signup)
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,        -- stores hashed password
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    slug  VARCHAR(50) NOT NULL UNIQUE,
    name  VARCHAR(100) NOT NULL,
    icon  VARCHAR(10),
    color VARCHAR(10)
);

-- Events table
CREATE TABLE events (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,                  -- organizer (foreign key)
    category_id INT NOT NULL,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    date        DATE NOT NULL,
    time        TIME NOT NULL,
    venue       VARCHAR(200),
    city        VARCHAR(100),
    price       DECIMAL(10,2) DEFAULT 0.00,
    status      ENUM('draft','live','cancelled') DEFAULT 'live',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Ticket bookings table
CREATE TABLE bookings (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    event_id   INT NOT NULL,
    user_id    INT NOT NULL,
    quantity   INT NOT NULL DEFAULT 1,
    total_paid DECIMAL(10,2) NOT NULL,
    booked_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE
);