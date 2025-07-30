<?php

namespace App\Controllers;


use App\Models\User;
use App\Models\Leave;
use App\Models\LeaveCredit;
use App\Models\Holiday;
use App\Models\Salary;
use App\Models\Attendance;
use Config\Services;

class AttendanceController extends BaseController {
    public $UserModel,
        $AttendanceModel,
        $LeaveCreditModel,
        $LeaveModel,
        $HolidayModel,
        $SalaryModel,
        $LeaveService;
    public $data = [];
    public $LEAVE_CREDIT_PER_ATTENDANCE;

    public function __construct() {
        $this->session = Services::session();
        $this->LeaveService = Services::leaveService();

        $this->UserModel = new User();
        $this->AttendanceModel = new Attendance();
        $this->LeaveModel = new Leave();
        $this->HolidayModel = new Holiday();
        $this->LeaveCreditModel = new LeaveCredit();
        $this->SalaryModel = new Salary();
        
        $this->data['TotalEmployeeOnDate'] = $this->AttendanceModel->getTotalAttendeesonDate(date('Y-m-d'));
    }

    public function AttendanceByDate() {

        $date     = $this->request->getGet('date');
        $dateAttendance = $this->AttendanceModel->getAttendanceByDate($date);
        return $this->response->setJSON($dateAttendance);
    }

    public function AttendanceEntryProcess($USER_BIOMETRIC_ID) {
        
        if (!($this->UserModel->isBiometricIDExist($USER_BIOMETRIC_ID))) {
            return $this->RedirectWithtoast('Biometric ID Doesnt Exist', 'danger', 'auth.login');
        }

        $date     = date('Y-m-d');
        $user_id  = $this->UserModel->getUserIDByBiometricID($USER_BIOMETRIC_ID);
        $isEntryExist = $this->AttendanceModel->isEntryExist($date, $user_id);
        $isEntryPunchedOut = $this->AttendanceModel->isEntryPunchedOut($date, $user_id);

        if ($isEntryExist && !($isEntryPunchedOut)) {

            $hoursTimeforPunchout = 1;
            $hoursPassedAfterEntry = $this->hoursPassedAfterEntry($user_id, $date);

            if ($hoursPassedAfterEntry > $hoursTimeforPunchout) {
                $this->AttendanceModel->setAttendancePunchOutByUserID($user_id, $date);
                return $this->RedirectWithtoast('Attendance Punch Out Marked', 'Success', 'auth.login');
            } else {
                return $this->RedirectWithtoast('Attendance Already Marked', 'warning', 'auth.login');
            }
        }
        if (!$isEntryExist) {

            $AttandenceData = $this->prepareAttendanceData($user_id, $date);

            $this->AttendanceModel->insertEntry($AttandenceData);
            $this->addDayLeaveCredit($user_id);
            $this->checkandCreateSandwichLeave($user_id, $date);

            return $this->RedirectWithtoast('Attendance Marked', 'Success', 'auth.login');
        }
        return $this->RedirectWithtoast('Attendance Fully Marked', 'Success', 'auth.login');
    }

    public function AttendanceEntryProcessWhileLogin($USER_ID) {

        $date     = date('Y-m-d');
        $user_id  = $USER_ID;
        $isEntryExist = $this->AttendanceModel->isEntryExist($date, $user_id);

        if (!$isEntryExist) {
            $AttandenceData = $this->prepareAttendanceData($user_id, $date);

            $this->AttendanceModel->insertEntry($AttandenceData);
            $this->addDayLeaveCredit($user_id);
            $this->checkandCreateSandwichLeave($user_id, $date);

            return $this->RedirectWithtoast('Attendance Marked', 'Success', '');
        }
        return 0;
    }


    public function monthlyLeaveCreditCalcforEachEmployee() {
        $employeesIDArr = $this->UserModel->getAllEmployeesID();
        $today = date('Y-m-d');
        $date = new \DateTime($today);
        $today_date = (int)$date->format('d');
        $previousDate = $date->modify('-1 day');
        $previousDate_Year = $previousDate->format('Y');
        $previousDate_Month = $previousDate->format('m');
        $previousDate = $previousDate->format('Y-m-d');
        
        if ($today_date != 1) {
            return $this->RedirectWithtoast('Today is First Day of month', 'Success', '/login');
        }
        foreach($employeesIDArr as $employee){
            $this->subtractDayLeaveCredit($employee->ID, $previousDate_Month, $previousDate_Year);
        }
        return $this->RedirectWithtoast('Leave Credit Recalculated', 'Success', '/login');
    }
    //----------------- Protected Class Function -----------------------//

    protected function addDayLeaveCredit($user_id) {
        $month = date('m');
        $year = date('Y');
        $date = new \DateTime("$year-$month-01");

        $totalDays = (int)$date->format('t');
        $totalSundays = $this->getTotalSundaysInMonth($month, $year);
        $totalNonSundayHoliday = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($month, $year);
        $workingDays = $totalDays - ($totalSundays + $totalNonSundayHoliday);

        $this->LEAVE_CREDIT_PER_ATTENDANCE = (1.5 / $workingDays);

        $leaveCredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);
        $leaveCredit = $leaveCredit + $this->LEAVE_CREDIT_PER_ATTENDANCE;
        $this->LeaveCreditModel->setLeaveCreditByUserID($user_id, $leaveCredit);
    }

    protected function subtractDayLeaveCredit($user_id,$month,$year) {

        $date = new \DateTime("$year-$month-01");
        
        $totalDays = (int)$date->format('t');
        $totalSundays = $this->getTotalSundaysInMonth($month, $year);
        $totalNonSundayHoliday = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($month, $year);
        $workingDays = $totalDays - ($totalSundays + $totalNonSundayHoliday);

        $attendedDays = count($this->AttendanceModel->getAllAttendanceofUserByMonthYear($user_id, $month, $year));

        $totalAbsentDays = $workingDays - $attendedDays;

        $this->LEAVE_CREDIT_PER_ATTENDANCE = (1.5 / $workingDays);

        $totalSubtractedLeaveCredit = $totalAbsentDays * $this->LEAVE_CREDIT_PER_ATTENDANCE;

        $leaveCredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);

        if(($leaveCredit - $totalSubtractedLeaveCredit) >= 0){
            $leaveCredit = $leaveCredit - $totalSubtractedLeaveCredit;
        }else{
            $leaveCredit = 0;
        }
        $this->LeaveCreditModel->setLeaveCreditByUserID($user_id, $leaveCredit);
        
    }

    public function checkandCreateSandwichLeave($user_id, $date) {

        $datetemp1 = new \DateTime($date);
        $lastMonday          = (clone $datetemp1)->modify('last monday');
        $lastSaturday        = (clone $datetemp1)->modify('last saturday');

        $prevMonday = $lastMonday->format('d');
        $prevSaturday = $lastSaturday->format('d');
        $prevMondayDate = $lastMonday->format('Y-m-d');
        $prevSaturdayDate = $lastSaturday->format('Y-m-d');
        $month = $lastSaturday->format('m');
        $year = $lastSaturday->format('Y');
        $dept_id = $this->UserModel->getDepartmentIDByUserID($user_id);
        $isHolidayExistOnPrevMonday = $this->HolidayModel->isHolidayExist($prevMondayDate);
        $isHolidayExistOnPrevSaturday = $this->HolidayModel->isHolidayExist($prevSaturdayDate);
        $ApprovedPrevMondayLeave = $this->LeaveModel->isOnNotifiedLeaveOnToDate($user_id, $prevMonday, $month, $year);
        $ApprovedPrevSaturdayLeave = $this->LeaveModel->isOnNotifiedLeaveOnFromDate($user_id, $prevSaturday, $month, $year);
        $presentOnPrevMonday = $this->AttendanceModel->isUserPresentonDate($user_id, $prevMondayDate);
        $presentOnPrevSaturday = $this->AttendanceModel->isUserPresentonDate($user_id, $prevSaturdayDate);

        $checkAfterDate = new \DateTime($this->UserModel->getUserCreatedDate($user_id));
        $diff = $checkAfterDate->diff($lastSaturday);
        $PrevSaturdaybeforecreatedOn = $diff->invert;

        /*
        var_dump($PrevSaturdaybeforecreatedOn);
        var_dump($this->LeaveModel->isSandwichLeave($user_id, $prevSaturdayDate));
        var_dump($isHolidayExistOnPrevMonday);
        var_dump($isHolidayExistOnPrevSaturday);
        var_dump($checkNotifiedSaturdayLeave && $checkNotifiedMondayLeave);
        var_dump(($checkNotifiedSaturdayLeave === 0) && ($checkUnAttendedMondayLeave === 1));
        var_dump(($checkUnAttendedSaturdayLeave === 0) && ($checkNotifiedMondayLeave === 1));
        var_dump(($checkUnAttendedSaturdayLeave === 0) && ($checkUnAttendedMondayLeave === 0));
        */

        if ($PrevSaturdaybeforecreatedOn) {
            //log_message('info', 'User Created After Saturday');            
            return 0;
        }
        if ($this->LeaveModel->isSandwichLeave($user_id, $prevSaturdayDate)) {
            //log_message('info', 'Already Sandwich Leave Exists');
            return 0;
        }
        if ($isHolidayExistOnPrevMonday) {
            //log_message('info', 'Holiday on Previous Monday');
            return 0;
        }
        if ($isHolidayExistOnPrevSaturday) {
            //log_message('info', 'Holiday on Previous Saturday');
            return 0;
        }
        if ($ApprovedPrevMondayLeave && $ApprovedPrevSaturdayLeave) {
            //log_message('info', 'Already Notified Leave on Saturday and Monday');
            return 0;
        }
        if (($presentOnPrevMonday === 0) && ($ApprovedPrevSaturdayLeave === 0)) {            
            $reason = 'Saturday Notified & Monday Unattended';
            //log_message('info', 'Saturday Notified & Monday Unattended');
            $this->LeaveService->CreateLeave($user_id, $dept_id, $prevSaturdayDate, $prevMondayDate, $reason);
            return 0;
        }
        if (($ApprovedPrevMondayLeave === 1) && ($presentOnPrevSaturday === 0)) {
            $reason = 'Saturday Unattended & Monday Notified';
            //log_message('info', 'Saturday Unattended & Monday Notified');
            $this->LeaveService->CreateLeave($user_id, $dept_id, $prevSaturdayDate, $prevMondayDate, $reason);
            return 0;
        }
        if (($presentOnPrevMonday === 0) && ($presentOnPrevSaturday === 0)) {
            $reason = 'Saturday Unattended & Monday Unattended';
            //log_message('info', 'Saturday Unattended & Monday Unattended');
            $this->LeaveService->CreateLeave($user_id, $dept_id, $prevSaturdayDate, $prevMondayDate, $reason);
            return 0;
        }
    }

    public function hoursPassedAfterEntry($user_id, $date) {

        $entry = $this->AttendanceModel->getEntryCreated($user_id, $date);
        $start = new \DateTime($entry);
        $end   = new \DateTime(date('Y-m-d G:i:s'));
        $interval = $start->diff($end);
        $hoursPassedAfterEntry = ($interval->days * 24) + $interval->h + ($interval->i / 60);
        echo "Hours Passed After Entry: $hoursPassedAfterEntry";
        return $hoursPassedAfterEntry;
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
    protected function prepareAttendanceData($user_id, $date) {
        return [
            'DATE' => $date,
            'USER_ID' => $user_id,
            'TOTAL_USERCOUNT' => $this->AttendanceModel->getTotalAttendeesonDate($date),
            'BASE_SALARY' => $this->SalaryModel->getSalaryByUserID($user_id),
        ];
    }
    //---------------- Protected Class Function End-----------------------//

}

