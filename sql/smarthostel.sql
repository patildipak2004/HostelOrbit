

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student', 'staff') NOT NULL,
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admission_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    last_passout VARCHAR(20) NOT NULL,
    last_passout_percentage DECIMAL(5,2) NOT NULL,
    college_id INT NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    percentage_12th DECIMAL(5,2) NOT NULL,
    percentage_10th DECIMAL(5,2) NOT NULL,
    parent_name VARCHAR(100) NOT NULL,
    parent_mobile VARCHAR(15) NOT NULL,
    parent_occupation VARCHAR(100),
    parent_address TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES admission_applications(id) ON DELETE CASCADE
);

CREATE TABLE student_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    application_id INT,
    room_number VARCHAR(20),
    admission_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES admission_applications(id) ON DELETE SET NULL
);

CREATE TABLE staff_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    check_in_time TIME,
    ip_address VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (user_id, date)
);

CREATE TABLE cleaning_checklist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    date DATE NOT NULL,
    bathroom_clean BOOLEAN DEFAULT FALSE,
    toilet_clean BOOLEAN DEFAULT FALSE,
    washbasin_clean BOOLEAN DEFAULT FALSE,
    porch_clean BOOLEAN DEFAULT FALSE,
    office_clean BOOLEAN DEFAULT FALSE,
    garbage_clean BOOLEAN DEFAULT FALSE,
    corridor_staircase_clean BOOLEAN DEFAULT FALSE,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cleaning (staff_id, date)
);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    complaint_type VARCHAR(50) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    leave_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Notices and Maintenance Reports tables removed as requested

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES 
('hostel_ip', '10.198.135.236'),
('hostel_name', 'Smart Hostel'),
('checkin_radius', '500');

INSERT INTO users (username, email, password, role, status) VALUES 
('admin', 'admin@smarthostel.com', 'password', 'admin', 'active');
