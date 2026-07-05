<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';

try {
    $db = Database::getInstance();

    // Clear tables in order
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    foreach (['student_questions','content_views','announcements','contents','enrollments','courses','students','doctors'] as $t) {
        $db->exec("TRUNCATE TABLE $t");
    }
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Doctors
    $doctors = [
        ['Dr. Ahmed Hassan',     35, '10001', 'Computer Science', 'ahmed@uni.edu',   'Expert in algorithms and data structures with 10+ years of teaching.'],
        ['Dr. Sara El-Sayed',    40, '10002', 'Mathematics',      'sara@uni.edu',    'Specializes in calculus and linear algebra for engineering students.'],
        ['Dr. Mohamed Khalil',   38, '10003', 'Computer Science', 'mohamed@uni.edu', 'Database systems and software engineering researcher.'],
        ['Dr. Layla Mostafa',    42, '10004', 'Physics',          'layla@uni.edu',   'Quantum mechanics and modern physics instructor.'],
    ];
    foreach ($doctors as $d) {
        $db->prepare("INSERT INTO doctors (name,age,national_id,department,email,password,bio) VALUES (?,?,?,?,?,?,?)")
           ->execute([$d[0],$d[1],$d[2],$d[3],$d[4],password_hash('password123', PASSWORD_DEFAULT),$d[5]]);
    }

    // Students
    $students = [
        ['Alice Johnson',  20, '20001', 'Computer Science', 'alice@uni.edu'],
        ['Bob Smith',      21, '20002', 'Mathematics',      'bob@uni.edu'],
        ['Carol Davis',    19, '20003', 'Computer Science', 'carol@uni.edu'],
        ['David Brown',    22, '20004', 'Physics',          'david@uni.edu'],
        ['Emma Wilson',    20, '20005', 'Computer Science', 'emma@uni.edu'],
        ['Frank Miller',   23, '20006', 'Mathematics',      'frank@uni.edu'],
    ];
    foreach ($students as $s) {
        $db->prepare("INSERT INTO students (name,age,national_id,department,email,password) VALUES (?,?,?,?,?,?)")
           ->execute([$s[0],$s[1],$s[2],$s[3],$s[4],password_hash('password123', PASSWORD_DEFAULT)]);
    }

    // Courses
    $courses = [
        ['CS101', 'Introduction to Programming',    'Learn the fundamentals of programming using Python and problem-solving techniques.', 1, 3, 'active'],
        ['CS201', 'Data Structures & Algorithms',   'Comprehensive study of data structures, sorting, searching, and algorithm analysis.', 1, 3, 'active'],
        ['MATH101','Calculus I',                    'Limits, derivatives, and integrals with engineering applications.',                  2, 4, 'active'],
        ['CS301', 'Database Systems',               'Relational databases, SQL, normalization, and transaction management.',              3, 3, 'active'],
        ['PHYS101','Classical Mechanics',           'Newton laws, energy, momentum, and rotational motion.',                             4, 3, 'active'],
        ['MATH201','Linear Algebra',                'Vectors, matrices, linear transformations and eigenvalues.',                         2, 3, 'active'],
    ];
    foreach ($courses as $c) {
        $db->prepare("INSERT INTO courses (code,name,description,doctor_id,credit_hours,status) VALUES (?,?,?,?,?,?)")
           ->execute($c);
    }

    // Enrollments
    $enrollments = [[1,1],[1,2],[1,4],[2,3],[2,6],[3,1],[3,4],[4,5],[5,1],[5,2],[6,3]];
    foreach ($enrollments as $e) {
        $db->prepare("INSERT IGNORE INTO enrollments (student_id,course_id) VALUES (?,?)")->execute($e);
    }

    // Contents for course 1
    $db->prepare("INSERT INTO contents (course_id,title,description,type,file_path,order_num) VALUES (?,?,?,?,?,?)")
       ->execute([1,'Lecture 1 - Introduction to Python','Getting started with Python syntax and basics','pdf','pdfs/sample_lecture1.pdf',1]);
    $db->prepare("INSERT INTO contents (course_id,title,description,type,file_path,order_num) VALUES (?,?,?,?,?,?)")
       ->execute([1,'Lecture 2 - Variables and Data Types','Understanding Python data types','pdf','pdfs/sample_lecture2.pdf',2]);

    // Contents for course 2
    $db->prepare("INSERT INTO contents (course_id,title,description,type,file_path,order_num) VALUES (?,?,?,?,?,?)")
       ->execute([2,'Arrays and Linked Lists','Introduction to linear data structures','pdf','pdfs/sample_ds.pdf',1]);

    // Announcements
    $db->prepare("INSERT INTO announcements (course_id,doctor_id,title,body,type) VALUES (?,?,?,?,?)")
       ->execute([1,1,'Welcome to CS101','Welcome everyone to Introduction to Programming. Make sure to check the course materials.','general']);
    $db->prepare("INSERT INTO announcements (course_id,doctor_id,title,body,type) VALUES (?,?,?,?,?)")
       ->execute([1,1,'Assignment 1 Due','Assignment 1 on Python basics is due next Friday. Submit via the course portal.','assignment']);
    $db->prepare("INSERT INTO announcements (course_id,doctor_id,title,body,type) VALUES (?,?,?,?,?)")
       ->execute([4,3,'Midterm Exam','Midterm exam will cover chapters 1-4. Date: Next Monday at 10:00 AM.','exam']);

    // Questions
    $db->prepare("INSERT INTO student_questions (course_id,student_id,question_text,status) VALUES (?,?,?,?)")
       ->execute([1,1,'Can we use Python 3.10 features in the assignments?','pending']);
    $db->prepare("INSERT INTO student_questions (course_id,student_id,question_text,answer_text,answered_by,answered_at,status) VALUES (?,?,?,?,?,NOW(),?)")
       ->execute([1,3,'When is the first lab session?','The first lab session is on Wednesday at 2 PM in Lab 201.',1,'answered']);

    echo "✅ Database seeded successfully.\n";
    echo "   Doctors:  4  (email: ahmed@uni.edu / sara@uni.edu / ... | password: password123)\n";
    echo "   Students: 6  (email: alice@uni.edu / bob@uni.edu / ... | password: password123)\n";
    echo "   Courses:  6\n";
} catch (PDOException $e) {
    echo "❌ Seed error: " . $e->getMessage() . "\n";
    exit(1);
}
