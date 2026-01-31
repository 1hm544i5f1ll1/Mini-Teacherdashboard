<?php
/** @var App\Core\Router $router */

use App\Modules\Auth\AuthController;
use App\Middleware\RequireLogin;
use App\Core\Auth;
use App\Core\View;

// Auth Routes
$router->get('/', function() {
    View::redirect('/auth/login');
});
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// Manager Routes
$router->get('/manager', function() {
    View::render('manager/index');
}, [RequireLogin::class]);

$router->get('/manager/admissions', function() {
    View::render('manager/admissions');
}, [RequireLogin::class]);

$router->get('/manager/teachers', function() {
    View::render('manager/hr', ['view' => 'staff']);
}, [RequireLogin::class]);

$router->get('/manager/hr', function() {
    View::render('manager/hr', ['view' => 'hr']);
}, [RequireLogin::class]);

$router->get('/manager/finance', function() {
    View::render('manager/finance');
}, [RequireLogin::class]);

// Teacher Dashboard
$router->get('/teacher', function() {
    View::render('teacher/index');
}, [RequireLogin::class]);

$router->get('/teacher/classes', function() {
    View::render('teacher/classes');
}, [RequireLogin::class]);

$router->get('/teacher/hr', function() {
    View::render('teacher/requests');
}, [RequireLogin::class]);

$router->get('/teacher/requests', function() {
    View::render('teacher/requests');
}, [RequireLogin::class]);

// Students
$router->get('/teacher/students', [\App\Modules\Students\StudentsController::class, 'index'], [RequireLogin::class]);
$router->post('/teacher/students', [\App\Modules\Students\StudentsController::class, 'store'], [RequireLogin::class]);

// Admissions
$router->get('/teacher/admissions', [\App\Modules\Admissions\AdmissionsController::class, 'index'], [RequireLogin::class]);
$router->post('/teacher/admissions/result', [\App\Modules\Admissions\AdmissionsController::class, 'submitResult'], [RequireLogin::class]);
$router->post('/manager/admissions/decision', [\App\Modules\Admissions\AdmissionsController::class, 'finalDecision'], [RequireLogin::class]);

// Attendance
$router->get('/teacher/attendance', [\App\Modules\Attendance\AttendanceController::class, 'showTake'], [RequireLogin::class]);
$router->get('/api/attendance/students', [\App\Modules\Attendance\AttendanceController::class, 'loadStudents'], [RequireLogin::class]);
$router->post('/teacher/attendance', [\App\Modules\Attendance\AttendanceController::class, 'store'], [RequireLogin::class]);

// Exams
$router->get('/teacher/exams', [\App\Modules\Exams\ExamsController::class, 'index'], [RequireLogin::class]);
$router->post('/teacher/exams/marks', [\App\Modules\Exams\ExamsController::class, 'storeResults'], [RequireLogin::class]);

// Registration (MVP) — Teacher + Manager
$router->get('/registration', [\App\Modules\Registration\RegistrationController::class, 'index'], [RequireLogin::class]);
$router->get('/registration/create', [\App\Modules\Registration\RegistrationController::class, 'createForm'], [RequireLogin::class]);
$router->post('/registration/store', [\App\Modules\Registration\RegistrationController::class, 'store'], [RequireLogin::class]);
$router->get('/registration/edit', [\App\Modules\Registration\RegistrationController::class, 'editForm'], [RequireLogin::class]);
$router->post('/registration/update', [\App\Modules\Registration\RegistrationController::class, 'update'], [RequireLogin::class]);
$router->post('/registration/submit', [\App\Modules\Registration\RegistrationController::class, 'submit'], [RequireLogin::class]);
$router->post('/registration/approve', [\App\Modules\Registration\RegistrationController::class, 'approve'], [RequireLogin::class]);
$router->post('/registration/reject', [\App\Modules\Registration\RegistrationController::class, 'reject'], [RequireLogin::class]);
$router->post('/registration/lock', [\App\Modules\Registration\RegistrationController::class, 'lock'], [RequireLogin::class]);
$router->get('/registration/summary', [\App\Modules\Registration\RegistrationController::class, 'summary'], [RequireLogin::class]);

// Documents (upload / download for registration)
$router->post('/documents/upload', [\App\Modules\Documents\DocumentsController::class, 'upload'], [RequireLogin::class]);
$router->get('/documents/download', [\App\Modules\Documents\DocumentsController::class, 'download'], [RequireLogin::class]);
