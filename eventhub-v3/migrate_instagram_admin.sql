-- Run this once against your existing `eventhub` database.
-- Safe to run even if you already ran schema.sql before — it won't drop your data.

ALTER TABLE users
  ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS instagram_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_url VARCHAR(500) NOT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    added_by INT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Make yourself an admin (replace with your real account email):
-- UPDATE users SET is_admin = 1 WHERE email = 'you@example.com';
