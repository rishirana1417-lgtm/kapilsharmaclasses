CREATE DATABASE kapil_classes;
USE kapil_classes;

CREATE TABLE demo_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    course VARCHAR(50) NOT NULL,
    batch_preference ENUM('Offline', 'Online') NOT NULL,
    preferred_time VARCHAR(50),
    message TEXT,
    status ENUM('Pending', 'Demo Given', 'Joined Offline', 'Joined Online', 'Not Interested') DEFAULT 'Pending',
    qr_email_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admin_users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: admin123