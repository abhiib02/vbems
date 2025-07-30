<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveCredit;
use Config\Services;

class DashboardController extends BaseController {
    public $data, $UserModel, $LeaveModel, $SalaryModel, $HolidayModel, $DepartmentModel, $AttendanceModel, $LeaveCreditModel;
    public function __construct() {
        $this->UserModel = new User();
        $this->LeaveModel = new Leave();
        $this->SalaryModel = new Salary();
        $this->HolidayModel = new Holiday();
        $this->AttendanceModel = new Attendance();
        $this->DepartmentModel = new Department();
        $this->LeaveCreditModel = new LeaveCredit();
        $this->session = Services::session();
        $this->data['id'] = $this->session->get('id');
        $this->data['name'] = $this->session->get('name');
        $this->data['email'] = $this->session->get('email');
        $this->data['noindex'] = 1;
        $this->data['leaveRequestsCount'] = $this->LeaveModel->getAllPendingLeaveRequestCount();
    }
    //------------------ Render Functions ---------------------//
    protected function renderAdminPage($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        return
            view('layout/header', $this->data) .
            view('dashboard/admin/layout/admin-header', $this->data) .
            view($view, $this->data) .
            view('dashboard/admin/layout/admin-footer', $this->data) .
            view('layout/footer', $this->data);
    }

    protected function renderEmployeePage($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        return
            view('layout/header', $this->data) .
            view('dashboard/employee/layout/employee-header', $this->data) .
            view($view, $this->data) .
            view('dashboard/employee/layout/employee-footer', $this->data) .
            view('layout/footer', $this->data);
    }
    //------------------ Admin View Functions ---------------------//
    public function AdminDashboard() {

        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();
        $this->data['title'] = 'Admin Dashboard';
        
        $this->data['todayattendance'] = $this->AttendanceModel->getTodayattendance();
        $this->data['TotalEmployees'] = $this->UserModel->getAllEmployeesCount();
        $this->data['yearlyAttendance'] = json_encode($this->AttendanceModel->getSumofTotalUserCountofmonthyear($this->data['year']));
        $this->data['nextHoliday'] = $this->HolidayModel->getNextHoliday();
        $this->data['TotalDepartments'] = $this->DepartmentModel->getAllDepartmentsCount();


        $this->data['HolidaysArr'] = [];
        $AllHolidaysCount = $this->HolidayModel->getAllHolidaysofYear($this->data['year']);

        for ($i = 1; $i <= 12; $i++) {
            $HolidaysCount = $AllHolidaysCount[$i - 1]->HOLIDAY_COUNT;
            $sundaysCount = $this->getTotalSundaysInMonth($i, $this->data['year']);
            array_push($this->data['HolidaysArr'], ($HolidaysCount + $sundaysCount));
        }

        $this->data['HolidaysArr'] = json_encode($this->data['HolidaysArr']);

        return $this->renderAdminPage('dashboard/admin/admin-dashboard', $this->data);
    }
    public function employeeList() {

        $this->data['title'] = 'Employees List';
        $this->data['employees'] = $this->UserModel->getAllEmployeesWithSalaryandDepartment();
        $this->data['departments'] = $this->DepartmentModel->getAllDepartments();

        return $this->renderAdminPage('dashboard/admin/lists/admin-employeeList', $this->data);
    }
    public function leaveRequestsList($status = "Pending") {
        
        $GuardClauseCondition = ($status == 'pending' || $status == 'Pending' || $status == "Approved" || $status == "approved" || $status == "Rejected" || $status == "rejected");
        if(!$GuardClauseCondition){
            return $this->RedirectWithtoast('Unknown Status', 'danger', 'leaveRequests.list');
        }

        $this->data['title'] = 'Leave Requests';
        $this->data['status'] = $status;

        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();

        if($status == "Pending" || $status == "pending") {
            $this->data['leaveRequests'] = $this->LeaveModel->getAllPendingLeaveRequest();
        }
        if ($status == "Approved" || $status == "approved") {
            $this->data['leaveRequests'] = $this->LeaveModel->getAllApprovedLeaveRequestofMonthYear($this->data['month'], $this->data['year']);
        }
        if ($status == "Rejected" || $status == "rejected") {
            $this->data['leaveRequests'] = $this->LeaveModel->getAllRejectedLeaveRequestofMonthYear($this->data['month'], $this->data['year']);
        }
        return $this->renderAdminPage('dashboard/admin/lists/admin-leaveRequests', $this->data);
    }
    public function attendance() {
        
        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();
    
        $this->data['title'] = $this->getMonthName($this->data['month']).' '.$this->data['year'].' Attendance';
        $this->data['all_employees_count'] = $this->UserModel->getAllEmployeesCount();
        $this->data['Holidays'] = $this->HolidayModel->getAllHolidaysofMonthYear($this->data['month'], $this->data['year']);
        $this->data['AttendanceStrength'] = $this->AttendanceModel->getEachDayattendanceDataofMonth($this->data['month'], $this->data['year']);
        $this->data['today_strength'] = $this->AttendanceModel->getTotalAttendeesonDate(date('Y-m-d'));

        return $this->renderAdminPage('dashboard/admin/attendance/admin-attendance', $this->data);
    }
    public function employeeattendance($id) {

        if(!($this->UserModel->isUserExistByID($id))){
            return $this->RedirectWithtoast('Employee ID Doesnt Exist', 'danger', 'employee.list');
        }
        
        $userData = $this->UserModel->getUserByID($id);
        $this->data['title'] = $userData->NAME . ' Attendance';
        $this->data['employeename'] = $userData->NAME;
        $this->data['joiningDate'] = explode(' ', $userData->CREATED_ON)[0];
        $this->getAllDataforAdmin_EmployeeAttendance($id);
        return $this->renderAdminPage('dashboard/admin/attendance/admin-employeeattendance', $this->data);
    }
    public function holidaysList() {
        $this->data['title'] = 'Holidays List';
        $this->data['holidays'] = $this->HolidayModel->getAllHolidays();
        return $this->renderAdminPage('dashboard/admin/lists/admin-holidaysList', $this->data);
    }
    public function departmentsList() {
        $this->data['title'] = 'Departments List';
        $this->data['departments'] = $this->DepartmentModel->getAllDepartments();
        return $this->renderAdminPage('dashboard/admin/lists/admin-departmentList', $this->data);
    }
    //------------------ Employee View Functions ---------------------//
    public function EmployeeDashboard() {
        $this->data = $this->getAllDataforEmployeeDashboard();
        $this->data['title'] = $this->data['name'] . ' Dashboard';
        return $this->renderEmployeePage('dashboard/employee/employee-dashboard', $this->data);
    }
    public function EmployeeProfile() {

        $this->data['employee'] = $this->UserModel->getUser($this->data['email']);
        $this->data['salary'] = $this->SalaryModel->getSalaryByUserID($this->data['id']);
        $this->data['DepartmentName'] = $this->DepartmentModel->getDepartmentNameByDepartmentID($this->data['employee']->DEPARTMENT_ID);
        $this->data['title'] = 'My Profile';
        return $this->renderEmployeePage('dashboard/employee/employee-profile', $this->data);
    }
    public function EmployeeLeaves() {
        $this->data['leaves'] = $this->LeaveModel->getLeavesByUserID($this->data['id']);
        $this->data['leavecredit'] = $this->LeaveCreditModel->getLeaveCreditByUserID($this->data['id']);
        $this->data['title'] = 'My Leaves';
        return $this->renderEmployeePage('dashboard/employee/lists/employee-leaves', $this->data);
    }
    public function EmployeeLeaveRequestForm() {
        $this->data['title'] = 'Leave Request Form';
        $dept_id = $this->UserModel->getDepartmentIDByUserID($this->data['id']);
        $this->data['department_leaves'] = $this->LeaveModel->getLeavesAfterTodayByDepartmentID($dept_id);
        $this->data['department_name'] = $this->DepartmentModel->getDepartmentNameByDepartmentID($dept_id);
        $this->data['department_leave_person_count'] = $this->DepartmentModel->getLeavePersonsCountByDepartmentID($dept_id);
        $this->data['department_leaves_count'] = $this->LeaveModel->getLeavesCountAfterTodayByDepartmentID($dept_id);
        return $this->renderEmployeePage('dashboard/employee/form/employee-leave-request-form', $this->data);
    }
    public function EmployeeAttedance() {
        $this->data['title'] = 'Attendance Information';
        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();
        /*------------------- Data Queries---------------------------*/
        $userData = $this->UserModel->getUser($this->data['email']);
        $this->data['employeename'] = $userData->NAME;
        $this->data['joiningDate'] = explode(' ', $userData->CREATED_ON)[0];

        $this->data['salary'] = $this->SalaryModel->getSalaryByUserID($this->data['id']);
        $this->data['monthsalary'] = $this->AttendanceModel->getBaseSalaryofMonthYearbyUserid($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['half_days'] = $this->AttendanceModel->getAllHalfDayattendanceofUserByMonthYear($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['full_days'] = $this->AttendanceModel->getAllFullDayattendanceofUserByMonthYear($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['attendance'] = $this->AttendanceModel->getAllattendanceofUserByMonthYear($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['Holidays'] = $this->HolidayModel->getAllHolidaysofMonthYear($this->data['month'], $this->data['year']);
        $this->data['nonSundayHolidays'] = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($this->data['month'], $this->data['year']);
        $this->data['approvedLeaves'] = $this->LeaveModel->getApprovedLeavesofMonthByID($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['sundays_in_leaves'] = $this->LeaveModel->getSumofSundayinApprovedLeavesofMonthByID($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['sundayBeforeJoining'] = $this->countSundaysBeforeDate($this->UserModel->getUserCreatedDate($this->data['id']), $this->data['month'], $this->data['year']);
        $this->data['leaves'] = $this->LeaveModel->getLeavesByUserID($this->data['id']);
        $this->data['leaveCredit'] = $this->LeaveCreditModel->getLeaveCreditByUserID($this->data['id']);
        $this->data['paid_leaves'] = $this->LeaveModel->getPaidLeaveDayCountofMonthYearByUserID($this->data['id'], $this->data['month'], $this->data['year']);
        $this->data['sundays'] = $this->getTotalSundaysInMonth($this->data['month'], $this->data['year']);
        /*------------------- Data Queries END---------------------------*/

        return $this->renderEmployeePage('dashboard/employee/attendance/employee-attendance', $this->data);
    }
    //---------------- Internal Functions -----------------------//
    protected function getAllDataforEmployeeDashboard() {
        
        $data['id'] = $this->session->get('id');
        $data['name'] = $this->session->get('name');
        $data['email'] = $this->session->get('email');
        $data['employee'] = $this->UserModel->getUser($data['email']);
        $data['leaves'] = $this->LeaveModel->getLeavesByUserID($data['id']);

        [$data['month'], $data['year']] = $this->getRequestMonthYear();
        $data['employeeYearlyAttendance'] = json_encode($this->AttendanceModel->getAnnualAttendedDayCountByMonthWise($data['id'],$data['year']));
        $data['half_days'] = $this->AttendanceModel->getAllHalfDayattendanceofUserByMonthYear($data['id'], $data['month'], $data['year']);
        $data['full_days'] = $this->AttendanceModel->getAllFullDayattendanceofUserByMonthYear($data['id'], $data['month'], $data['year']);
        $data['attendance'] = $this->AttendanceModel->getAllattendanceofUserByMonthYear($data['id'], $data['month'], $data['year']);
        $data['Holidays'] = $this->HolidayModel->getAllHolidaysofMonthYear($data['month'], $data['year']);
        $data['nonSundayHolidays'] = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($data['month'], $data['year']);
        $data['approvedLeaves'] = $this->LeaveModel->getApprovedLeavesofMonthByID($data['id'], $data['month'], $data['year']);
        $data['employeename'] = $this->UserModel->getUserName($data['email']);
        $data['sundayBeforeJoining'] = $this->countSundaysBeforeDate($this->UserModel->getUserCreatedDate($data['id']), $data['month'], $data['year']);
        $data['sundays_in_leaves'] = $this->LeaveModel->getSumofSundayinApprovedLeavesofMonthByID($data['id'], $data['month'], $data['year']);
        $data['sundays'] = $this->getTotalSundaysInMonth($data['month'], $data['year']);
        $data['leaveCredit'] = $this->LeaveCreditModel->getLeaveCreditByUserID($data['id']);
        $data['salary'] = $this->SalaryModel->getSalaryByUserID($data['id']);
        $data['monthsalary'] = $this->AttendanceModel->getBaseSalaryofMonthYearbyUserid($data['id'], $data['month'], $data['year']);
        $data['paid_leaves'] = $this->LeaveModel->getPaidLeaveDayCountofMonthYearByUserID($data['id'], $data['month'], $data['year']);
        $userData = $this->UserModel->getUserByID($data['id']);
        $data['joiningDate'] = explode(' ', $userData->CREATED_ON)[0];
        return $data;
    }
    protected function getAllDataforAdmin_EmployeeAttendance($id) {

        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();

        $this->data['attendance'] = $this->AttendanceModel->getAllattendanceofUserByMonthYear($id, $this->data['month'], $this->data['year']);
        $this->data['half_days'] = $this->AttendanceModel->getAllHalfDayattendanceofUserByMonthYear($id, $this->data['month'], $this->data['year']);
        $this->data['full_days'] = $this->AttendanceModel->getAllFullDayattendanceofUserByMonthYear($id, $this->data['month'], $this->data['year']);
        $this->data['Holidays'] = $this->HolidayModel->getAllHolidaysofMonthYear($this->data['month'], $this->data['year']);
        $this->data['nonSundayHolidays'] = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($this->data['month'], $this->data['year']);
        $this->data['approvedLeaves'] = $this->LeaveModel->getApprovedLeavesofMonthByID($id, $this->data['month'], $this->data['year']);
        $this->data['employeename'] = $this->UserModel->getUserNameByID($id);
        $this->data['sundayBeforeJoining'] = $this->countSundaysBeforeDate($this->UserModel->getUserCreatedDate($id), $this->data['month'], $this->data['year']);
        $this->data['sundays_in_leaves'] = $this->LeaveModel->getSumofSundayinApprovedLeavesofMonthByID($id, $this->data['month'], $this->data['year']);
        $this->data['sundays'] = $this->getTotalSundaysInMonth($this->data['month'], $this->data['year']);
        $this->data['salary'] = $this->SalaryModel->getSalaryByUserID($id);
        $this->data['monthsalary'] = $this->AttendanceModel->getBaseSalaryofMonthYearbyUserid($id, $this->data['month'], $this->data['year']);
        $this->data['paid_leaves'] = $this->LeaveModel->getPaidLeaveDayCountofMonthYearByUserID($id, $this->data['month'], $this->data['year']);
        $this->data['unpaid_leaves'] = $this->LeaveModel->getLeaveDayCountofMonthYearByUserID($id, $this->data['month'], $this->data['year']);
        $this->data['leaveCredit'] = $this->LeaveCreditModel->getLeaveCreditByUserID($id);
    }
    protected function countSundaysBeforeDate($dateString, $currentMonth, $currentYear) {

        $targetDate = new \DateTime($dateString);
        $datemonth = $targetDate->format('m');
        $dateyear = $targetDate->format('Y');
        if (!(($currentMonth == $datemonth) && ($currentYear == $dateyear))) {
            return 0;
        }
        // Start of that month
        $startOfMonth = new \DateTime($targetDate->format('Y-m-01'));

        $count = 0;
        while ($startOfMonth < $targetDate) {
            if ($startOfMonth->format('N') == 7) { // 7 = Sunday
                $count++;
            }
            $startOfMonth->modify('+1 day');
        }

        return $count;
    }
    protected function getTotalSundaysInMonth($month, $year) {
        $start = new \DateTime("$year-$month-01");
        $end = clone $start;
        $end->modify('last day of this month');

        $count = 0;
        while ($start <= $end) {
            if ($start->format('N') == 7) { // Sunday = 7
                $count++;
            }
            $start->modify('+1 day');
        }

        return $count;
    }
    protected function getRequestMonthYear() {
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');
        
        // Validate month (1-12)
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $month = date('m');
        }
        // Validate year (reasonable range)
        if (!is_numeric($year) || $year < 1900 || $year > 2100) {
            $year = date('Y');
        }
        return [$month, $year];
    }
    protected function getMonthName($monthNumber) {
        if (!is_numeric($monthNumber) || $monthNumber < 1 || $monthNumber > 12) {
            return null; // Invalid month number
        }
        $monthArr = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return $monthArr[$monthNumber - 1] ?? null;
    }
}
