<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Option;
use App\Models\LeaveCredit;
use Config\Services;

class AdminDashboardController extends BaseController {
    private $data, $UserModel, $LeaveModel, $SalaryModel, $HolidayModel, $DepartmentModel, $OptionModel, $AttendanceModel, $LeaveCreditModel;
    public function __construct() {
        $this->UserModel = new User();
        $this->LeaveModel = new Leave();
        $this->SalaryModel = new Salary();
        $this->HolidayModel = new Holiday();
        $this->AttendanceModel = new Attendance();
        $this->DepartmentModel = new Department();
        $this->OptionModel = new Option();
        $this->LeaveCreditModel = new LeaveCredit();
        $this->session = Services::session();
        $this->data['id'] = $this->session->get('id');
        $this->data['name'] = $this->session->get('name');
        $this->data['email'] = $this->session->get('email');
        $this->data['noindex'] = 1;
        $this->data['leaveRequestsCount'] = $this->LeaveModel->getAllPendingLeaveRequestCount();
        $this->data['ShowPunchOutButton'] = $this->OptionModel->getOptionValue('ShowPunchOutButton');
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
    //------------------ Admin View Functions ---------------------//

    public function AdminDashboard() {

        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();
        $this->data['title'] = 'Admin Dashboard';

        $AllHolidaysCount = $this->HolidayModel->getAllHolidaysofYear($this->data['year']);
        $HolidaysArr = [];
        for ($i = 1; $i <= 12; $i++) {
            $HolidaysCount = $AllHolidaysCount[$i - 1]->HOLIDAY_COUNT;
            $sundaysCount = $this->getTotalSundaysInMonth($i, $this->data['year']);
            array_push($HolidaysArr, ($HolidaysCount + $sundaysCount));
        }
        
        $data =[
            'todayattendance'=> $this->AttendanceModel->getTodayattendance(),
            'TotalEmployees' => $this->UserModel->getAllEmployeesCount(),
            'yearlyAttendance' => json_encode($this->AttendanceModel->getSumofTotalUserCountofmonthyear($this->data['year'])),
            'nextHoliday' => $this->HolidayModel->getNextHoliday(),
            'TotalDepartments' => $this->DepartmentModel->getAllDepartmentsCount(),
            'HolidaysArr'=> json_encode($HolidaysArr)
        ];

        $this->data = array_merge($this->data, $data);
        return $this->renderAdminPage('dashboard/admin/admin-dashboard', $this->data);
    }
    public function employeeList() {

        $this->data['title'] = 'Employees List';

        $data = [
            'employees' => $this->UserModel->getAllEmployeesWithSalaryandDepartment(),
            'departments' => $this->DepartmentModel->getAllDepartments(),
        ];

        $this->data = array_merge($this->data, $data);

        return $this->renderAdminPage('dashboard/admin/lists/admin-employeeList', $this->data);
    }
    public function leaveRequestsList($status = "pending") {
        $status = strtolower($status);
        $validStatuses = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            return $this->RedirectWithtoast('Unknown Status', 'danger', 'leaveRequests.list');
        }

        $this->data['title'] = 'Leave Requests';
        $this->data['status'] = $status;

        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();

        if($status == "pending") {
            $this->data['leaveRequests'] = $this->LeaveModel->getAllPendingLeaveRequest();
        }
        if ($status == "approved") {
            $this->data['leaveRequests'] = $this->LeaveModel->getAllApprovedLeaveRequestofMonthYear($this->data['month'], $this->data['year']);
        }
        if ($status == "rejected") {
            $this->data['leaveRequests'] = $this->LeaveModel->getAllRejectedLeaveRequestofMonthYear($this->data['month'], $this->data['year']);
        }
        return $this->renderAdminPage('dashboard/admin/lists/admin-leaveRequests', $this->data);
    }
    public function attendance() {
        
        [$this->data['month'], $this->data['year']] = $this->getRequestMonthYear();

        $data = [
            'title' => $this->getMonthName($this->data['month']) . ' ' . $this->data['year'] . ' Attendance',
            'all_employees_count' => $this->UserModel->getAllEmployeesCount(),
            'Holidays' =>  $this->HolidayModel->getAllHolidaysofMonthYear($this->data['month'], $this->data['year']),
            'AttendanceStrength' => $this->AttendanceModel->getEachDayattendanceDataofMonth($this->data['month'], $this->data['year']),
            'today_strength' => $this->AttendanceModel->getTotalAttendeesonDate(date('Y-m-d')),
        ];
        
        $this->data = array_merge($this->data, $data);

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
        $this->data = array_merge($this->data, $this->getAllDataforAdmin_EmployeeAttendance($id));
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
    public function optionsList() {
        $this->data['title'] = 'Options List';
        $this->data['options'] = $this->OptionModel->getAllOptions();
        return $this->renderAdminPage('dashboard/admin/lists/admin-optionList', $this->data);
    }
    //---------------- Internal Functions -----------------------//
    protected function getAllDataforAdmin_EmployeeAttendance($id) {

        [$month, $year] = $this->getRequestMonthYear();
        $createdDate = $this->UserModel->getUserCreatedDate($id);
        $employeename = $this->UserModel->getUserNameByID($id);
        // Attendance
        $attendanceData = [
            'half_days'  => $this->AttendanceModel->getAllHalfDayattendanceofUserByMonthYear($id, $month, $year),
            'full_days'  => $this->AttendanceModel->getAllFullDayattendanceofUserByMonthYear($id, $month, $year),
            'attendance' => $this->AttendanceModel->getAllattendanceofUserByMonthYear($id, $month, $year),
        ];

        // Leaves
        $leaveData = [
            'leaves'         => $this->LeaveModel->getLeavesByUserID($id),
            'approvedLeaves' => $this->LeaveModel->getApprovedLeavesofMonthByID($id, $month, $year),
            'sundays_in_leaves' => $this->LeaveModel->getSumofSundayinApprovedLeavesofMonthByID($id, $month, $year),
            'zeroleavecredit' => $this->LeaveModel->getApprovedZeroLeaveCreditDaysCountofMonthByID($id, $month, $year),
            'paid_leaves'    => $this->LeaveModel->getPaidLeaveDayCountofMonthYearByUserID($id, $month, $year),
            'unpaid_leaves' => $this->LeaveModel->getLeaveDayCountofMonthYearByUserID($id, $month, $year),
            'leaveCredit' =>$this->LeaveCreditModel->getLeaveCreditByUserID($id)
        ];

        // Holidays
        $holidayData = [
            'Holidays'         => $this->HolidayModel->getAllHolidaysofMonthYear($month, $year),
            'nonSundayHolidays' => $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($month, $year),
            'sundays'          => $this->getTotalSundaysInMonth($month, $year),
            'sundayBeforeJoining' => $this->countSundaysBeforeDate($createdDate, $month, $year),
        ];

        // Salary
        $salaryData = [
            'salary'       => $this->SalaryModel->getSalaryByUserID($id),
            'monthsalary'  => $this->AttendanceModel->getBaseSalaryofMonthYearbyUserid($id, $month, $year),
        ];
        return [
            'id'          => $id,
            'employeename'=> $employeename,
            'month'     => $month,
            'year'      => $year,            
            ...$attendanceData,
            ...$leaveData,
            ...$holidayData,
            ...$salaryData,
        ];
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
