<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->set404Override(function () {
    $myfile = fopen("404logs.txt", "a") or die("Unable to open file!");
    $txt = date("Y-m-d H:i:s") . " - 404 Error: " . current_url() . " - User ID: " . session()->get('id') . ' ' . session()->get('name') . "\n";
    fwrite($myfile, $txt);
    fclose($myfile);
    return view('layout/header') . view('errors/error404');
});

// Default route
$routes->get('/', 'LoginController::index');

/*------------------- Auth Routes ------------------*/
$routes->group('', function ($routes) {
    $routes->get('signup', 'RegisterController::index', ['as' => 'auth.signup']);
    $routes->post('signupvalidation', 'RegisterController::SignupValidation');
    $routes->post('signupvalidation-admin', 'RegisterController::SignupValidation_admin');

    $routes->get('login', 'LoginController::index', ['as' => 'auth.login']);
    $routes->post('login-validation', 'LoginController::loginValidation');
    $routes->get('logout', 'LoginController::logout');

    $routes->get('forgot_password_form', 'UserController::forgotpass', ['as' => 'user.forgot.password.form']);
    $routes->post('forgotpassword', 'UserController::forgotpassword', ['as' => 'user.forgot.password']);

    $routes->get('resetform', 'UserController::resetform');
    $routes->post('reset', 'UserController::reset');
});

/*------------------- Employee Protected Routes ------------------*/
$routes->group('', ['filter' => 'AuthGuard'], function ($routes) {
    $routes->get('account', 'DashboardController::EmployeeDashboard', ['as' => 'employee.account']);
    $routes->get('profile', 'DashboardController::EmployeeProfile');
    $routes->get('my-leaves', 'DashboardController::EmployeeLeaves');
    $routes->get('leaveRequestForm', 'DashboardController::EmployeeLeaveRequestForm');
    $routes->get('attendanceInfo', 'DashboardController::EmployeeAttedance');
    $routes->post('requestleaveprocess', 'LeaveController::requestleaveprocess');
});

/*------------------- Admin Protected Routes ------------------*/
$routes->group('', ['filter' => 'AdminAuthGuard'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'DashboardController::AdminDashboard', ['as' => 'admin.dashboard']);

    // Employee
    $routes->get('employee-list', 'DashboardController::employeeList', ['as' => 'employee.list']);
    $routes->get('employee-attendance/(:num)', 'DashboardController::employeeAttendance/$1');
    $routes->post('add-employee', 'RegisterController::addUserByAdmin');
    $routes->post('deactivate-user', 'UserController::deactivateUserProcess');
    $routes->post('update-profile', 'UserController::updateProfileProcess');

    // Salary
    $routes->post('set-salary', 'SalaryController::setSalaryProcess');
    $routes->post('update-salary', 'SalaryController::updateSalaryProcess');

    // Leave Management
    $routes->get('leaveRequests', 'DashboardController::leaveRequestsList', ['as' => 'leaveRequests.list']);
    $routes->get('leaveRequests/(:any)', 'DashboardController::leaveRequestsList/$1');
    $routes->get('lr-approve/(:num)', 'LeaveController::ApproveLeaveProcess/$1');
    $routes->get('lr-reject/(:num)', 'LeaveController::RejectLeaveProcess/$1');
    $routes->post('create-paid-leave', 'LeaveController::createPaidLeaveProcess');

    // Attendance
    $routes->get('attendance', 'DashboardController::attendance', ['as' => 'attendance.admin']);
    $routes->get('attendancebydate', 'AttendanceController::attendanceByDate');

    // Holidays
    $routes->get('holidays-list', 'DashboardController::holidaysList', ['as' => 'holidays.list']);
    $routes->post('add-holiday', 'HolidayController::addHolidayProcess');
    $routes->post('update-holiday', 'HolidayController::updateHolidayProcess');
    $routes->get('delete-holiday/(:num)', 'HolidayController::deleteHolidayProcess/$1');

    // Departments
    $routes->get('departments-list', 'DashboardController::departmentsList', ['as' => 'departments.list']);
    $routes->post('add-department', 'DepartmentController::AddDepartmentProcess');
    $routes->post('update-department', 'DepartmentController::updateDepartmentProcess');
    $routes->get('delete-department/(:num)', 'DepartmentController::deleteDepartmentProcess/$1');
});

/*------------------- Public Attendance ------------------*/
$routes->get('mark-attendance/(:any)', 'AttendanceController::AttendanceEntryProcess/$1');
