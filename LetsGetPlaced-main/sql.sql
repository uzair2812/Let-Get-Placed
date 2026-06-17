CREATE DATABASE lets_get_placed_db;
USE lets_get_placed_db;

-- =========================
-- ADMIN TABLE
-- =========================

CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- STUDENTS TABLE
-- =========================

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    usn VARCHAR(30) UNIQUE NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    gender VARCHAR(20),
    dob DATE,
    address TEXT,
    branch VARCHAR(100),
    semester INT,
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- ACADEMIC DETAILS
-- =========================

CREATE TABLE student_academic_details (
    academic_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,

    sslc_school VARCHAR(200),
    sslc_board VARCHAR(100),
    sslc_percentage DECIMAL(5,2),
    sslc_year YEAR,

    pu_college VARCHAR(200),
    pu_board VARCHAR(100),
    pu_percentage DECIMAL(5,2),
    pu_year YEAR,

    degree_college VARCHAR(200),
    degree_branch VARCHAR(100),
    cgpa DECIMAL(4,2),
    backlogs INT DEFAULT 0,
    degree_year YEAR,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE
);

-- =========================
-- STUDENT SKILLS
-- =========================

CREATE TABLE student_skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    skill_name VARCHAR(150) NOT NULL,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE
);

-- =========================
-- CERTIFICATIONS
-- =========================

CREATE TABLE certifications (
    certification_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    certification_name VARCHAR(255),
    issued_by VARCHAR(255),
    completion_date DATE,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE
);

-- =========================
-- PROJECTS
-- =========================

CREATE TABLE student_projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    project_title VARCHAR(255),
    project_description TEXT,
    technologies_used VARCHAR(255),

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE
);

-- =========================
-- INTERNSHIPS
-- =========================

CREATE TABLE internships (
    internship_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    company_name VARCHAR(255),
    role VARCHAR(255),
    duration VARCHAR(100),
    description TEXT,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE
);

-- =========================
-- RESUMES
-- =========================

CREATE TABLE resumes (
    resume_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    resume_file VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE
);

-- =========================
-- COMPANIES
-- =========================

CREATE TABLE companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    company_logo VARCHAR(255),

    industry_type VARCHAR(100),
    website VARCHAR(255),

    role_offered VARCHAR(255),
    job_location VARCHAR(255),

    ctc DECIMAL(10,2),

    internship_details TEXT,
    bond_details TEXT,

    eligibility_cgpa DECIMAL(4,2),
    eligible_branches TEXT,

    max_backlogs INT DEFAULT 0,

    registration_start DATE,
    registration_end DATE,

    drive_date DATE,

    selection_process TEXT,

    company_description TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- JOB DESCRIPTIONS
-- =========================

CREATE TABLE company_documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,

    document_title VARCHAR(255),
    document_file VARCHAR(255),

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (company_id)
    REFERENCES companies(company_id)
    ON DELETE CASCADE
);

-- =========================
-- REGISTRATIONS
-- =========================

CREATE TABLE registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,
    company_id INT NOT NULL,

    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    status ENUM(
        'Registered',
        'Shortlisted',
        'Aptitude Cleared',
        'Technical Cleared',
        'HR Cleared',
        'Selected',
        'Rejected'
    ) DEFAULT 'Registered',

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE,

    FOREIGN KEY (company_id)
    REFERENCES companies(company_id)
    ON DELETE CASCADE
);

-- =========================
-- NOTIFICATIONS
-- =========================

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,

    notification_type ENUM(
        'General',
        'Company',
        'Placement',
        'Urgent'
    ) DEFAULT 'General',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- PLACEMENT MATERIALS
-- =========================

CREATE TABLE placement_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,

    category ENUM(
        'Aptitude',
        'Technical',
        'Interview'
    ),

    title VARCHAR(255),
    material_file VARCHAR(255),

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- STUDENT NOTIFICATION READ STATUS
-- =========================

CREATE TABLE notification_reads (
    read_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,
    notification_id INT NOT NULL,

    read_status BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE,

    FOREIGN KEY (notification_id)
    REFERENCES notifications(notification_id)
    ON DELETE CASCADE
);

-- =========================
-- PLACEMENT STATUS
-- =========================

CREATE TABLE placement_status (
    placement_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    company_id INT NOT NULL,

    package DECIMAL(10,2),

    status ENUM(
        'Placed',
        'Not Placed'
    ) DEFAULT 'Not Placed',

    placement_date DATE,

    FOREIGN KEY (student_id)
    REFERENCES students(student_id)
    ON DELETE CASCADE,

    FOREIGN KEY (company_id)
    REFERENCES companies(company_id)
    ON DELETE CASCADE
);

-- =========================
-- ACTIVITY LOGS
-- =========================

CREATE TABLE activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,

    user_type ENUM('Admin','Student'),

    user_id INT,

    activity TEXT,

    activity_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- DEFAULT ADMIN
-- Password: admin123
-- Replace with hashed password later
-- =========================

INSERT INTO admins (
    full_name,
    username,
    password,
    email
)
VALUES (
    'Placement Officer',
    'admin',
    'admin123',
    'admin@letsgetplaced.com'
);