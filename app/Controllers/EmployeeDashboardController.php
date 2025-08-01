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

class EmployeeDashboardController extends BaseController {
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
    protected function renderEmployeePage($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        return
            view('layout/header', $this->data) .
            view('dashboard/employee/layout/employee-header', $this->data) .
            view($view, $this->data) .
            view('dashboard/employee/layout/employee-footer', $this->data) .
            view('layout/footer', $this->data);
    }

    //------------------ Employee View Functions ---------------------//
    public function EmployeeDashboard() {
        $this->data = $this->getAllDataforEmployeeDashboard();
        $this->data['ShowPunchOutButton'] = $this->OptionModel->getOptionValue('ShowPunchOutButton');
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
        $this->data['leavecredit'] = $this->LeaveCreditModel->getLeaveCreditByUserID($this->data['id']);
        $this->data['department_leaves'] = $this->LeaveModel->getLeavesAfterTodayByDepartmentID($dept_id);
        $this->data['department_name'] = $this->DepartmentModel->getDepartmentNameByDepartmentID($dept_id);
        $this->data['department_leave_person_count'] = $this->DepartmentModel->getLeavePersonsCountByDepartmentID($dept_id);
        $this->data['department_leaves_count'] = $this->LeaveModel->getLeavesCountAfterTodayByDepartmentID($dept_id);
        return $this->renderEmployeePage('dashboard/employee/form/employee-leave-request-form', $this->data);
    }
    public function EmployeeAttedance() {
        $this->data['title'] = 'Attendance Information';
        $this->data = array_merge($this->data, $this->getAllDataforEmployeeDashboard());
        return $this->renderEmployeePage('dashboard/employee/attendance/employee-attendance', $this->data);
    }
    //---------------- Internal Functions -----------------------//
    protected function getAllDataforEmployeeDashboard() {

        $id = $this->session->get('id');
        $email = $this->session->get('email');
        $name = $this->session->get('name');

        [$month, $year] = $this->getRequestMonthYear();
        $createdDate = $this->UserModel->getUserCreatedDate($id);

        // Basic user data
        $user = $this->UserModel->getUser($email);
        $userName = $this->UserModel->getUserName($email);
        $userData = $this->UserModel->getUserByID($id);

        // Attendance
        $attendanceData = [
            'employeeYearlyAttendance' => json_encode(
                $this->AttendanceModel->getAnnualAttendedDayCountByMonthWise($id, $year)
            ),
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
            'id'        => $id,
            'name'      => $name,
            'email'     => $email,
            'month'     => $month,
            'year'      => $year,
            'employee'  => $user,
            'employeename' => $userName,
            'joiningDate' => explode(' ', $userData->CREATED_ON)[0],
            'leaveCredit' => $this->LeaveCreditModel->getLeaveCreditByUserID($id),
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