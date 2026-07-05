<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../helpers/functions.php';

session_start();

$router = new Router();

// Public routes
$router->get('/',                  'HomeController',         'index');
$router->get('/courses',           'HomeController',         'courses');
$router->get('/courses/detail',    'HomeController',         'courseDetail');

// Auth
$router->get('/login',             'Auth\AuthController',    'loginForm');
$router->post('/login',            'Auth\AuthController',    'login');
$router->get('/register',          'Auth\AuthController',    'registerForm');
$router->post('/register',         'Auth\AuthController',    'register');
$router->get('/logout',            'Auth\AuthController',    'logout');

// Student routes
$router->get('/student/dashboard', 'Student\DashboardController', 'index');
$router->get('/student/courses',   'Student\CourseController',    'index');
$router->get('/student/course/{id}', 'Student\CourseController',  'show');
$router->post('/student/course/{id}/enroll',   'Student\CourseController', 'enroll');
$router->post('/student/course/{id}/unenroll', 'Student\CourseController', 'unenroll');
$router->get('/student/content/{id}/view',     'Student\CourseController', 'viewContent');
$router->get('/student/content/{id}/download', 'Student\CourseController', 'downloadContent');
$router->post('/student/course/{id}/question', 'Student\CourseController', 'askQuestion');
$router->get('/student/profile',   'Student\ProfileController',   'show');
$router->post('/student/profile',  'Student\ProfileController',   'update');

// Doctor routes
$router->get('/doctor/dashboard',  'Doctor\DashboardController',  'index');
$router->get('/doctor/courses',    'Doctor\CourseController',     'index');
$router->get('/doctor/courses/create', 'Doctor\CourseController', 'create');
$router->post('/doctor/courses/store', 'Doctor\CourseController', 'store');
$router->get('/doctor/course/{id}',    'Doctor\CourseController', 'show');
$router->get('/doctor/course/{id}/edit',    'Doctor\CourseController', 'edit');
$router->post('/doctor/course/{id}/update', 'Doctor\CourseController', 'update');
$router->post('/doctor/course/{id}/delete', 'Doctor\CourseController', 'destroy');
$router->get('/doctor/course/{id}/students', 'Doctor\CourseController', 'students');

$router->post('/doctor/course/{id}/content', 'Doctor\ContentController', 'store');
$router->get('/doctor/content/{id}/edit',    'Doctor\ContentController', 'editForm');
$router->post('/doctor/content/{id}/update', 'Doctor\ContentController', 'update');
$router->post('/doctor/content/{id}/delete', 'Doctor\ContentController', 'destroy');

$router->post('/doctor/course/{id}/announcement',        'Doctor\AnnouncementController', 'store');
$router->post('/doctor/announcement/{id}/delete',        'Doctor\AnnouncementController', 'destroy');

$router->get('/doctor/questions',           'Doctor\QuestionController', 'index');
$router->post('/doctor/question/{id}/answer', 'Doctor\QuestionController', 'answer');

$router->get('/doctor/profile',   'Doctor\ProfileController',   'show');
$router->post('/doctor/profile',  'Doctor\ProfileController',   'update');

$uri        = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $httpMethod);



