-- ==========================================
-- Smart Career & Placement Management System
-- Database
-- ==========================================

CREATE DATABASE IF NOT EXISTS career_placement;
USE career_placement;

-- ==========================================
-- Admin Table
-- ==========================================

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- Student Table
-- ==========================================

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,

    enrollment_no VARCHAR(30) UNIQUE NOT NULL,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) UNIQUE NOT NULL,

    phone VARCHAR(10) NOT NULL,

    password VARCHAR(255) NOT NULL,

    gender ENUM('Male','Female','Other') NOT NULL,

    dob DATE,

    profile_photo VARCHAR(255) DEFAULT 'default.png',

    address TEXT,

    city VARCHAR(100),

    state VARCHAR(100),

    pincode VARCHAR(10),

    course VARCHAR(100),

    semester VARCHAR(20),

    college_name VARCHAR(150),

    cgpa DECIMAL(3,2),

    resume VARCHAR(255),

    account_status ENUM('Active','Inactive')
    DEFAULT 'Active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- Company Table
-- ==========================================

CREATE TABLE companies (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_name VARCHAR(150) NOT NULL,

    email VARCHAR(100) UNIQUE NOT NULL,

    phone VARCHAR(10) NOT NULL,

    password VARCHAR(255) NOT NULL,

    company_logo VARCHAR(255)
    DEFAULT 'default_company.png',

    website VARCHAR(150),

    industry VARCHAR(100),

    address TEXT,

    city VARCHAR(100),

    state VARCHAR(100),

    pincode VARCHAR(10),

    about_company TEXT,

    account_status ENUM('Active','Inactive')
    DEFAULT 'Active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- Jobs Table
-- ==========================================

CREATE TABLE jobs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    company_id INT NOT NULL,

    job_title VARCHAR(150) NOT NULL,

    job_type ENUM(
        'Full Time',
        'Part Time',
        'Internship',
        'Remote'
    ) NOT NULL,

    vacancies INT NOT NULL,

    salary VARCHAR(50),

    location VARCHAR(100),

    required_skills TEXT,

    eligibility VARCHAR(150),

    job_description TEXT,

    last_date DATE NOT NULL,

    status ENUM('Open','Closed')
    DEFAULT 'Open',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_job_company
        FOREIGN KEY (company_id)
        REFERENCES companies(id)
        ON DELETE CASCADE
);

-- ==========================================
-- Applications Table
-- ==========================================

CREATE TABLE applications (

    id INT AUTO_INCREMENT PRIMARY KEY,

    job_id INT NOT NULL,

    student_id INT NOT NULL,

    application_date TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    status ENUM(
        'Pending',
        'Accepted',
        'Rejected'
    ) DEFAULT 'Pending',

    company_remark TEXT,

    CONSTRAINT fk_application_job
        FOREIGN KEY (job_id)
        REFERENCES jobs(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_application_student
        FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    UNIQUE(job_id, student_id)

);

-- ==========================================
-- Student Skills Table
-- ==========================================

CREATE TABLE student_skills (

    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    skill_name VARCHAR(100) NOT NULL,

    skill_level ENUM(
        'Beginner',
        'Intermediate',
        'Advanced'
    ) DEFAULT 'Beginner',

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_skill_student
        FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE

);

-- ==========================================
-- INDEXES
-- ==========================================

CREATE INDEX idx_student_email
ON students(email);

CREATE INDEX idx_company_email
ON companies(email);

CREATE INDEX idx_job_company
ON jobs(company_id);

CREATE INDEX idx_application_student
ON applications(student_id);

CREATE INDEX idx_application_job
ON applications(job_id);

CREATE INDEX idx_skill_student
ON student_skills(student_id);

-- ==========================================
-- DEFAULT ADMIN
-- Email : admin@gmail.com
-- Password : admin123
-- ==========================================

INSERT INTO admins (
    full_name,
    email,
    password
)
VALUES
(
    'Administrator',
    'admin@gmail.com',
    'admin123'
);

-- ==========================================
-- END OF DATABASE
-- ==========================================

ALTER TABLE students
ADD country VARCHAR(100) NULL AFTER state,
ADD university VARCHAR(150) NULL AFTER college_name,
ADD passing_year YEAR NULL AFTER university,
ADD skills TEXT NULL AFTER cgpa,
ADD linkedin VARCHAR(255) NULL AFTER resume,
ADD github VARCHAR(255) NULL AFTER linkedin,
ADD portfolio VARCHAR(255) NULL AFTER github,
ADD preferred_role VARCHAR(100) NULL AFTER portfolio,
ADD preferred_location VARCHAR(100) NULL AFTER preferred_role,
ADD employment_type VARCHAR(50) NULL AFTER preferred_location,
ADD expected_salary VARCHAR(100) NULL AFTER employment_type;

ALTER TABLE student_skills
MODIFY skill_level ENUM(
'Beginner',
'Intermediate',
'Advanced',
'Expert'
) NOT NULL;

ALTER TABLE jobs
ADD category VARCHAR(100) NOT NULL AFTER job_title,
ADD experience VARCHAR(100) NOT NULL AFTER vacancies;

ALTER TABLE jobs
MODIFY status ENUM('Active','Closed')
DEFAULT 'Active';

UPDATE jobs
SET status='Active'
WHERE status='Open' OR status IS NULL;

UPDATE jobs
SET status = 'Active'
WHERE id = 3;

SELECT id, job_title, status
FROM jobs
WHERE id = 3;

ALTER TABLE jobs
ADD vacancy INT NOT NULL AFTER job_type;

ALTER TABLE jobs
ADD qualification VARCHAR(100) NOT NULL AFTER experience;

ALTER TABLE jobs
ADD skills TEXT AFTER qualification,
ADD state VARCHAR(100) AFTER salary,
ADD city VARCHAR(100) AFTER state,
ADD responsibilities TEXT AFTER job_description,
ADD benefits TEXT AFTER responsibilities;

ALTER TABLE admins
ADD profile_photo VARCHAR(255) NOT NULL DEFAULT 'default-admin.png';