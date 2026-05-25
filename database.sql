-- E-MODULE LMS Database Schema
-- Created for Learning Management System

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
    progress INT DEFAULT 1,
    plain_password TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS teachers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    department VARCHAR(100),
    subject VARCHAR(100),
    qualification VARCHAR(255),
    experience_years INT DEFAULT 0,
    phone VARCHAR(15),
    bio TEXT,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    permission_level ENUM(
        'superadmin',
        'moderator',
        'operator'
    ) DEFAULT 'operator',
    department VARCHAR(100),
    phone VARCHAR(15),
    access_modules BOOLEAN DEFAULT 1,
    access_users BOOLEAN DEFAULT 1,
    access_reports BOOLEAN DEFAULT 1,
    access_settings BOOLEAN DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS modules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    outcome TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    quiz TEXT NOT NULL,
    answer VARCHAR(255) NOT NULL,
    unit_number INT NOT NULL,
    file_path VARCHAR(500) DEFAULT NULL,
    youtube_url VARCHAR(500) DEFAULT NULL,
    teacher_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    status ENUM(
        'locked',
        'available',
        'completed'
    ) DEFAULT 'locked',
    quiz_score INT DEFAULT 0,
    quiz_attempts INT DEFAULT 0,
    completed_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_module (user_id, module_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Insert sample teacher user (password: Teacher@123 - hashed with bcrypt)
INSERT INTO
    users (
        name,
        email,
        password,
        role,
        progress
    )
VALUES (
        'Teacher Account',
        'teacher@emodule.com',
        '$2y$10$YQ97c0q3tWNL6Y2pHfP0D.4qGkuwNzUnOm7LGozqV8sV9.LRyOqIy',
        'teacher',
        45
    );

-- Insert teacher details
INSERT INTO
    teachers (
        user_id,
        department,
        subject,
        qualification,
        experience_years,
        phone,
        bio
    )
VALUES (
        1,
        'Computer Science',
        'Advanced Programming',
        'M.Tech',
        5,
        '9876543210',
        'Experienced teacher with 5 years of expertise'
    );

-- Insert sample student user (password: Student@123 - hashed with bcrypt)
INSERT INTO
    users (
        name,
        email,
        password,
        role,
        progress
    )
VALUES (
        'Test Student',
        'student@emodule.com',
        '$2y$10$RTLBOKDHEe3eUZo3Z8TFyeGj6YqHvzJPFlQqc5fKLqxSxp2iR2VyK',
        'student',
        1
    );

-- Insert sample admin user (password: Admin@123 - hashed with bcrypt)
INSERT INTO
    users (
        name,
        email,
        password,
        role,
        progress
    )
VALUES (
        'Admin Account',
        'admin@emodule.com',
        '$2y$10$C2Hlm.ao3/2MP9vP95qdLO.YzIg2j8r/PHHwaRbjnwdQcNDLY1Tv6',
        'admin',
        45
    );

-- Insert admin details
INSERT INTO
    admins (
        user_id,
        permission_level,
        department,
        phone,
        access_modules,
        access_users,
        access_reports,
        access_settings
    )
VALUES (
        3,
        'superadmin',
        'Administration',
        '9876543211',
        1,
        1,
        1,
        1
    );

-- Insert 45 Modules
INSERT INTO
    modules (
        title,
        outcome,
        content,
        quiz,
        answer,
        unit_number
    )
VALUES (
        'Basic Competencies',
        'Upon completion, you will master skills for Unit 1.',
        'This is the lesson content for module 1. Follow instructions carefully.',
        'Question: What unit is this?',
        '1',
        1
    );

INSERT INTO
    modules (
        title,
        outcome,
        content,
        quiz,
        answer,
        unit_number
    )
VALUES (
        'Module 2: Advanced Unit',
        'Upon completion, you will master skills for Unit 2.',
        'This is the lesson content for module 2. Follow instructions carefully.',
        'Question: What unit is this?',
        '2',
        2
    ),
    (
        'Module 3: Advanced Unit',
        'Upon completion, you will master skills for Unit 3.',
        'This is the lesson content for module 3. Follow instructions carefully.',
        'Question: What unit is this?',
        '3',
        3
    ),
    (
        'Module 4: Advanced Unit',
        'Upon completion, you will master skills for Unit 4.',
        'This is the lesson content for module 4. Follow instructions carefully.',
        'Question: What unit is this?',
        '4',
        4
    ),
    (
        'Module 5: Advanced Unit',
        'Upon completion, you will master skills for Unit 5.',
        'This is the lesson content for module 5. Follow instructions carefully.',
        'Question: What unit is this?',
        '5',
        5
    ),
    (
        'Module 6: Advanced Unit',
        'Upon completion, you will master skills for Unit 6.',
        'This is the lesson content for module 6. Follow instructions carefully.',
        'Question: What unit is this?',
        '6',
        6
    ),
    (
        'Module 7: Advanced Unit',
        'Upon completion, you will master skills for Unit 7.',
        'This is the lesson content for module 7. Follow instructions carefully.',
        'Question: What unit is this?',
        '7',
        7
    ),
    (
        'Module 8: Advanced Unit',
        'Upon completion, you will master skills for Unit 8.',
        'This is the lesson content for module 8. Follow instructions carefully.',
        'Question: What unit is this?',
        '8',
        8
    ),
    (
        'Module 9: Advanced Unit',
        'Upon completion, you will master skills for Unit 9.',
        'This is the lesson content for module 9. Follow instructions carefully.',
        'Question: What unit is this?',
        '9',
        9
    ),
    (
        'Module 10: Advanced Unit',
        'Upon completion, you will master skills for Unit 10.',
        'This is the lesson content for module 10. Follow instructions carefully.',
        'Question: What unit is this?',
        '10',
        10
    ),
    (
        'Module 11: Advanced Unit',
        'Upon completion, you will master skills for Unit 11.',
        'This is the lesson content for module 11. Follow instructions carefully.',
        'Question: What unit is this?',
        '11',
        11
    ),
    (
        'Module 12: Advanced Unit',
        'Upon completion, you will master skills for Unit 12.',
        'This is the lesson content for module 12. Follow instructions carefully.',
        'Question: What unit is this?',
        '12',
        12
    ),
    (
        'Module 13: Advanced Unit',
        'Upon completion, you will master skills for Unit 13.',
        'This is the lesson content for module 13. Follow instructions carefully.',
        'Question: What unit is this?',
        '13',
        13
    ),
    (
        'Module 14: Advanced Unit',
        'Upon completion, you will master skills for Unit 14.',
        'This is the lesson content for module 14. Follow instructions carefully.',
        'Question: What unit is this?',
        '14',
        14
    ),
    (
        'Module 15: Advanced Unit',
        'Upon completion, you will master skills for Unit 15.',
        'This is the lesson content for module 15. Follow instructions carefully.',
        'Question: What unit is this?',
        '15',
        15
    ),
    (
        'Module 16: Advanced Unit',
        'Upon completion, you will master skills for Unit 16.',
        'This is the lesson content for module 16. Follow instructions carefully.',
        'Question: What unit is this?',
        '16',
        16
    ),
    (
        'Module 17: Advanced Unit',
        'Upon completion, you will master skills for Unit 17.',
        'This is the lesson content for module 17. Follow instructions carefully.',
        'Question: What unit is this?',
        '17',
        17
    ),
    (
        'Module 18: Advanced Unit',
        'Upon completion, you will master skills for Unit 18.',
        'This is the lesson content for module 18. Follow instructions carefully.',
        'Question: What unit is this?',
        '18',
        18
    ),
    (
        'Module 19: Advanced Unit',
        'Upon completion, you will master skills for Unit 19.',
        'This is the lesson content for module 19. Follow instructions carefully.',
        'Question: What unit is this?',
        '19',
        19
    ),
    (
        'Module 20: Advanced Unit',
        'Upon completion, you will master skills for Unit 20.',
        'This is the lesson content for module 20. Follow instructions carefully.',
        'Question: What unit is this?',
        '20',
        20
    ),
    (
        'Module 21: Advanced Unit',
        'Upon completion, you will master skills for Unit 21.',
        'This is the lesson content for module 21. Follow instructions carefully.',
        'Question: What unit is this?',
        '21',
        21
    ),
    (
        'Module 22: Advanced Unit',
        'Upon completion, you will master skills for Unit 22.',
        'This is the lesson content for module 22. Follow instructions carefully.',
        'Question: What unit is this?',
        '22',
        22
    ),
    (
        'Module 23: Advanced Unit',
        'Upon completion, you will master skills for Unit 23.',
        'This is the lesson content for module 23. Follow instructions carefully.',
        'Question: What unit is this?',
        '23',
        23
    ),
    (
        'Module 24: Advanced Unit',
        'Upon completion, you will master skills for Unit 24.',
        'This is the lesson content for module 24. Follow instructions carefully.',
        'Question: What unit is this?',
        '24',
        24
    ),
    (
        'Module 25: Advanced Unit',
        'Upon completion, you will master skills for Unit 25.',
        'This is the lesson content for module 25. Follow instructions carefully.',
        'Question: What unit is this?',
        '25',
        25
    ),
    (
        'Module 26: Advanced Unit',
        'Upon completion, you will master skills for Unit 26.',
        'This is the lesson content for module 26. Follow instructions carefully.',
        'Question: What unit is this?',
        '26',
        26
    ),
    (
        'Module 27: Advanced Unit',
        'Upon completion, you will master skills for Unit 27.',
        'This is the lesson content for module 27. Follow instructions carefully.',
        'Question: What unit is this?',
        '27',
        27
    ),
    (
        'Module 28: Advanced Unit',
        'Upon completion, you will master skills for Unit 28.',
        'This is the lesson content for module 28. Follow instructions carefully.',
        'Question: What unit is this?',
        '28',
        28
    ),
    (
        'Module 29: Advanced Unit',
        'Upon completion, you will master skills for Unit 29.',
        'This is the lesson content for module 29. Follow instructions carefully.',
        'Question: What unit is this?',
        '29',
        29
    ),
    (
        'Module 30: Advanced Unit',
        'Upon completion, you will master skills for Unit 30.',
        'This is the lesson content for module 30. Follow instructions carefully.',
        'Question: What unit is this?',
        '30',
        30
    ),
    (
        'Module 31: Advanced Unit',
        'Upon completion, you will master skills for Unit 31.',
        'This is the lesson content for module 31. Follow instructions carefully.',
        'Question: What unit is this?',
        '31',
        31
    ),
    (
        'Module 32: Advanced Unit',
        'Upon completion, you will master skills for Unit 32.',
        'This is the lesson content for module 32. Follow instructions carefully.',
        'Question: What unit is this?',
        '32',
        32
    ),
    (
        'Module 33: Advanced Unit',
        'Upon completion, you will master skills for Unit 33.',
        'This is the lesson content for module 33. Follow instructions carefully.',
        'Question: What unit is this?',
        '33',
        33
    ),
    (
        'Module 34: Advanced Unit',
        'Upon completion, you will master skills for Unit 34.',
        'This is the lesson content for module 34. Follow instructions carefully.',
        'Question: What unit is this?',
        '34',
        34
    ),
    (
        'Module 35: Advanced Unit',
        'Upon completion, you will master skills for Unit 35.',
        'This is the lesson content for module 35. Follow instructions carefully.',
        'Question: What unit is this?',
        '35',
        35
    ),
    (
        'Module 36: Advanced Unit',
        'Upon completion, you will master skills for Unit 36.',
        'This is the lesson content for module 36. Follow instructions carefully.',
        'Question: What unit is this?',
        '36',
        36
    ),
    (
        'Module 37: Advanced Unit',
        'Upon completion, you will master skills for Unit 37.',
        'This is the lesson content for module 37. Follow instructions carefully.',
        'Question: What unit is this?',
        '37',
        37
    ),
    (
        'Module 38: Advanced Unit',
        'Upon completion, you will master skills for Unit 38.',
        'This is the lesson content for module 38. Follow instructions carefully.',
        'Question: What unit is this?',
        '38',
        38
    ),
    (
        'Module 39: Advanced Unit',
        'Upon completion, you will master skills for Unit 39.',
        'This is the lesson content for module 39. Follow instructions carefully.',
        'Question: What unit is this?',
        '39',
        39
    ),
    (
        'Module 40: Advanced Unit',
        'Upon completion, you will master skills for Unit 40.',
        'This is the lesson content for module 40. Follow instructions carefully.',
        'Question: What unit is this?',
        '40',
        40
    ),
    (
        'Module 41: Advanced Unit',
        'Upon completion, you will master skills for Unit 41.',
        'This is the lesson content for module 41. Follow instructions carefully.',
        'Question: What unit is this?',
        '41',
        41
    ),
    (
        'Module 42: Advanced Unit',
        'Upon completion, you will master skills for Unit 42.',
        'This is the lesson content for module 42. Follow instructions carefully.',
        'Question: What unit is this?',
        '42',
        42
    ),
    (
        'Module 43: Advanced Unit',
        'Upon completion, you will master skills for Unit 43.',
        'This is the lesson content for module 43. Follow instructions carefully.',
        'Question: What unit is this?',
        '43',
        43
    ),
    (
        'Module 44: Advanced Unit',
        'Upon completion, you will master skills for Unit 44.',
        'This is the lesson content for module 44. Follow instructions carefully.',
        'Question: What unit is this?',
        '44',
        44
    ),
    (
        'Module 45: Advanced Unit',
        'Upon completion, you will master skills for Unit 45.',
        'This is the lesson content for module 45. Follow instructions carefully.',
        'Question: What unit is this?',
        '45',
        45
    );

-- Create indexes for better query performance
CREATE INDEX idx_user_role ON users (role);

CREATE INDEX idx_user_progress ON users (progress);

CREATE INDEX idx_module_unit ON modules (unit_number);

CREATE INDEX idx_user_progress_user_id ON user_progress (user_id);

CREATE INDEX idx_user_progress_module_id ON user_progress (module_id);

CREATE INDEX idx_teachers_user_id ON teachers (user_id);

CREATE INDEX idx_teachers_department ON teachers (department);

CREATE INDEX idx_admins_user_id ON admins (user_id);

CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    module_id INT NOT NULL,
    test_type ENUM('pre', 'post') NOT NULL DEFAULT 'pre',
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer ENUM('A', 'B', 'C', 'D') NOT NULL,
    time_limit_minutes INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Run this if the table already exists:
-- ALTER TABLE quiz_questions ADD COLUMN IF NOT EXISTS test_type ENUM('pre','post') NOT NULL DEFAULT 'pre' AFTER module_id;
-- ALTER TABLE quiz_questions ADD COLUMN IF NOT EXISTS time_limit_minutes INT DEFAULT NULL;

CREATE TABLE IF NOT EXISTS grades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    grade_id INT DEFAULT NULL,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_section_name (name),
    FOREIGN KEY (grade_id) REFERENCES grades (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS student_grade_section (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL UNIQUE,
    grade_id INT NOT NULL,
    section_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    FOREIGN KEY (grade_id) REFERENCES grades (id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS student_teacher (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    teacher_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_student (student_id),
    FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE INDEX idx_quiz_module ON quiz_questions (module_id);
CREATE INDEX idx_module_teacher ON modules (teacher_id);
CREATE INDEX idx_students_user_id ON students (user_id);
CREATE INDEX idx_student_grade_section_student ON student_grade_section (student_id);
CREATE INDEX idx_student_grade_section_grade ON student_grade_section (grade_id);
CREATE INDEX idx_student_grade_section_section ON student_grade_section (section_id);
CREATE INDEX idx_sections_grade_id ON sections (grade_id);
CREATE INDEX idx_student_teacher_student ON student_teacher (student_id);
CREATE INDEX idx_student_teacher_teacher ON student_teacher (teacher_id);

CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    module VARCHAR(50) DEFAULT NULL,
    reference_id INT DEFAULT NULL,
    metadata JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE INDEX idx_activity_logs_user_id ON activity_logs (user_id);
CREATE INDEX idx_activity_logs_action ON activity_logs (action);
CREATE INDEX idx_activity_logs_created_at ON activity_logs (created_at);