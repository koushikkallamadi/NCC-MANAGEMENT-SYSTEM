<?php
require_once 'db.php';

// 1. Add new fields to users table if they don't exist
$alter_users = "ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS regiment_no VARCHAR(50) UNIQUE AFTER password,
    ADD COLUMN IF NOT EXISTS roll_no VARCHAR(50) AFTER regiment_no,
    ADD COLUMN IF NOT EXISTS branch VARCHAR(100) AFTER roll_no,
    ADD COLUMN IF NOT EXISTS year INT AFTER branch,
    ADD COLUMN IF NOT EXISTS contact VARCHAR(20) AFTER year,
    ADD COLUMN IF NOT EXISTS photo VARCHAR(255) AFTER contact,
    ADD COLUMN IF NOT EXISTS unit_id INT AFTER photo,
    ADD COLUMN IF NOT EXISTS status ENUM('Pending', 'Approved', 'Deactivated') DEFAULT 'Pending' AFTER unit_id";

if ($conn->query($alter_users) === TRUE) {
    echo "Users table updated successfully\n";
} else {
    echo "Error updating users table: " . $conn->error . "\n";
}

// 2. Create units table
$create_units = "CREATE TABLE IF NOT EXISTS units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('Army', 'Navy', 'Air') NOT NULL,
    strength INT DEFAULT 0
)";
$conn->query($create_units);

// 3. Create attendance table
$create_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_attendance);

// 4. Create camps_events table
$create_camps = "CREATE TABLE IF NOT EXISTS camps_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('Camp', 'Event') NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255),
    duration VARCHAR(50),
    max_cadets INT,
    details TEXT,
    status ENUM('Upcoming', 'Closed') DEFAULT 'Upcoming'
)";
$conn->query($create_camps);

// 5. Create enrollments table
$create_enrollments = "CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('Applied', 'Selected', 'Enrolled') DEFAULT 'Applied',
    FOREIGN KEY (item_id) REFERENCES camps_events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_enrollments);

// 6. Create certificates table
$create_certificates = "CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(100) NOT NULL,
    grade VARCHAR(10),
    issue_date DATE NOT NULL,
    issuing_officer_id INT NOT NULL,
    file_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_certificates);

// 7. Create announcements table
$create_announcements = "CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    posted_by INT NOT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($create_announcements);

// 8. Seed Default Admin
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$seed_admin = "INSERT IGNORE INTO users (name, email, password, regiment_no, role, status) 
               VALUES ('System Admin', 'admin@ncc.com', '$admin_pass', 'ADMIN001', 'Admin', 'Approved')";
$conn->query($seed_admin);

// 9. Seed Initial Units
$seed_units = "INSERT IGNORE INTO units (id, name, type, strength) VALUES 
    (1, '1st Bengal Battalion', 'Army', 150),
    (2, '2nd Air Squadron', 'Air', 100),
    (3, '3rd Naval Unit', 'Navy', 120)";
$conn->query($seed_units);

$conn->close();
echo "Database schema updated and seeded successfully!";
?>
