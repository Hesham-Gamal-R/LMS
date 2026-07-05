CREATE DATABASE IF NOT EXISTS lms_db;
USE lms_db;
 
CREATE TABLE IF NOT EXISTS students (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(150) NOT NULL,
    age           TINYINT UNSIGNED NOT NULL,
    national_id   VARCHAR(20) NOT NULL UNIQUE,
    department    VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS doctors (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(150) NOT NULL,
    age           TINYINT UNSIGNED NOT NULL,
    national_id   VARCHAR(20) NOT NULL UNIQUE,
    department    VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    bio           TEXT DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code         VARCHAR(20) NOT NULL UNIQUE,
    name         VARCHAR(200) NOT NULL,
    description  TEXT DEFAULT NULL,
    doctor_id    INT UNSIGNED NOT NULL,
    credit_hours TINYINT UNSIGNED NOT NULL DEFAULT 3,
    status       ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS enrollments (
    student_id  INT UNSIGNED NOT NULL,
    course_id   INT UNSIGNED NOT NULL,
    enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id)  REFERENCES courses(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contents (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id   INT UNSIGNED NOT NULL,
    title       VARCHAR(200) NOT NULL,
    description TEXT DEFAULT NULL,
    type        ENUM('pdf','video') NOT NULL,
    file_path   VARCHAR(500) NOT NULL,
    order_num   SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS content_views (
    content_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    viewed_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (content_id, student_id),
    FOREIGN KEY (content_id) REFERENCES contents(id)  ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS announcements (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id  INT UNSIGNED NOT NULL,
    doctor_id  INT UNSIGNED NOT NULL,
    title      VARCHAR(200) NOT NULL,
    body       TEXT NOT NULL,
    type       ENUM('general','assignment','exam','other') NOT NULL DEFAULT 'general',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id)  ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS student_questions (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id    INT UNSIGNED NOT NULL,
    student_id   INT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    answer_text   TEXT DEFAULT NULL,
    answered_by   INT UNSIGNED DEFAULT NULL,
    answered_at   DATETIME DEFAULT NULL,
    status        ENUM('pending','answered') NOT NULL DEFAULT 'pending',
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id)   REFERENCES courses(id)  ON DELETE CASCADE,
    FOREIGN KEY (student_id)  REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (answered_by) REFERENCES doctors(id)  ON DELETE SET NULL
);
