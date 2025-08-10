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
    $routes->post('signupvalidation-admin', 'RegisterController::SignupValidation_admin');

    $routes->get('login', 'LoginController::index', ['as' => 'auth.login']);
    $routes->post('login-validation', 'LoginController::loginValidation');
    $routes->get('logout', 'LoginController::logout', ['as' => 'auth.logout']);

    $routes->get('forgot_password_form', 'UserController::forgotpass', ['as' => 'user.forgot.password.form']);
    $routes->post('forgotpassword', 'UserController::forgotpassword', ['as' => 'user.forgot.password']);

    $routes->get('resetform', 'UserController::resetform');
    $routes->post('reset', 'UserController::reset');
    
});

/*------------------- Employee Protected Routes ------------------*/
$routes->group('', ['filter' => 'AuthGuard'], function ($routes) {
    $routes->get('account', 'EmployeeDashboardController::EmployeeDashboard', ['as' => 'employee.account']);
    $routes->get('profile', 'EmployeeDashboardController::EmployeeProfile');
    $routes->get('my-leaves', 'EmployeeDashboardController::EmployeeLeaves');
    $routes->get('leaveRequestForm', 'EmployeeDashboardController::EmployeeLeaveRequestForm');
    $routes->get('attendanceInfo', 'EmployeeDashboardController::EmployeeAttedance');
    $routes->post('requestleaveprocess', 'LeaveController::requestleaveprocess');
    $routes->post('punch-out', 'AttendanceController::AttendanceEntryPunchOutProcess');
});

/*------------------- Admin Protected Routes ------------------*/
$routes->group('', ['filter' => 'AdminAuthGuard'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'AdminDashboardController::AdminDashboard', ['as' => 'admin.dashboard']);
    
    // Employee
    $routes->get('employee-list', 'AdminDashboardController::employeeList', ['as' => 'employee.list']);
    $routes->get('employee-attendance/(:num)', 'AdminDashboardController::employeeAttendance/$1');
    $routes->post('add-employee', 'RegisterController::addUserByAdmin');
    $routes->post('deactivate-user', 'UserController::deactivateUserProcess');
    $routes->post('update-profile', 'UserController::updateProfileProcess');

    // Salary
    $routes->post('set-salary', 'SalaryController::setSalaryProcess');
    $routes->post('update-salary', 'SalaryController::updateSalaryProcess');

    // Leave Management
    $routes->get('leaveRequests', 'AdminDashboardController::leaveRequestsList', ['as' => 'leaveRequests.list']);
    $routes->get('leaveRequests/(:any)', 'AdminDashboardController::leaveRequestsList/$1');
    $routes->get('lr-approve/(:num)', 'LeaveController::ApproveLeaveProcess/$1');
    $routes->get('lr-reject/(:num)', 'LeaveController::RejectLeaveProcess/$1');
    $routes->post('create-paid-leave', 'LeaveController::createPaidLeaveProcess');

    // Attendance
    $routes->get('attendance', 'AdminDashboardController::attendance', ['as' => 'attendance.admin']);
    $routes->get('attendancebydate', 'AttendanceController::attendanceByDate');

    // Holidays
    $routes->get('holidays-list', 'AdminDashboardController::holidaysList', ['as' => 'holidays.list']);
    $routes->post('add-holiday', 'HolidayController::addHolidayProcess');
    $routes->post('update-holiday', 'HolidayController::updateHolidayProcess');
    $routes->get('delete-holiday/(:num)', 'HolidayController::deleteHolidayProcess/$1');

    // Departments
    $routes->get('departments-list', 'AdminDashboardController::departmentsList', ['as' => 'departments.list']);
    $routes->post('add-department', 'DepartmentController::AddDepartmentProcess');
    $routes->post('update-department', 'DepartmentController::updateDepartmentProcess');
    $routes->get('delete-department/(:num)', 'DepartmentController::deleteDepartmentProcess/$1');

    // Options
    $routes->get('options-list', 'AdminDashboardController::optionsList', ['as' => 'options.list']);
    $routes->post('add-option', 'OptionsController::addOptionProcess');
    $routes->post('option/(:alpha)', 'OptionsController::save/$1');
    
});

/*------------------- Public Attendance ------------------*/
$routes->get('mark-attendance/(:any)', 'AttendanceController::AttendanceEntryProcess/$1');

$routes->get('m-lc-calc', 'AttendanceController::monthlyLeaveCreditCalcforEachEmployee');

$routes->get('deleteEmp', 'UserController::deleteEmp');

