<?php

namespace App\Services;


use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Salary;
use App\Models\LeaveCredit;
use App\Models\Leave;
use App\Models\Option;
use App\Models\User;
use Config\Services;


class AttendanceService {

    protected $LeaveService;
    protected $data, $flag, $AttendanceModel, $HolidayModel, $LeaveCreditModel, $SalaryModel, $LeaveModel, $UserModel, $OptionModel;
    public $LEAVE_CREDIT_PER_ATTENDANCE;
    public $LEAVE_CREDIT_PER_MONTH = 1.5;
    public $HALFDAY_ENTRY_TIME = '11:00:00';
    public $HALFDAY_EXIT_TIME = '17:00:00';
    public $PUNCH_OUT_BUFFER_HOURS = 1;

    public function __construct() {
        $this->HolidayModel = new Holiday();
        $this->LeaveCreditModel = new LeaveCredit();
        $this->AttendanceModel = new Attendance();
        $this->SalaryModel = new Salary();
        $this->LeaveModel = new Leave();
        $this->UserModel = new User();
        $this->LeaveService = Services::leaveService();
        $this->OptionModel = new Option();
        $this->data['TotalEmployeeOnDate'] = $this->AttendanceModel->getTotalAttendeesonDate(date('Y-m-d'));
        $this->flag['EnableMarkAttendanceOnLogin'] = $this->OptionModel->getOptionValue('EnableMarkAttendanceOnLogin');
    }

    public function markEntryOnLogin($USER_ID) {
        if (!$this->flag['EnableMarkAttendanceOnLogin']) {
            return 0;
        }
        $date     = date('Y-m-d');
        $user_id  = $USER_ID;
        $isEntryExist = $this->AttendanceModel->isEntryExist($date, $user_id);

        if (!$isEntryExist) {
            $AttandenceData = $this->prepareAttendanceData($user_id, $date);

            $this->AttendanceModel->insertEntry($AttandenceData);
            $this->addDayLeaveCredit($user_id);
            $this->checkandCreateSandwichLeave($user_id, $date);

            log_message('info', "Attendance marked for USER_ID: {$user_id} on login.");

            return 1;
        }
        return 0;
    }

    public function addDayLeaveCredit($user_id) {
        $month = date('m');
        $year = date('Y');
        $date = new \DateTime("$year-$month-01");

        $totalDays = (int)$date->format('t');
        $totalSundays = $this->getTotalSundaysInMonth($month, $year);
        $totalNonSundayHoliday = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($month, $year);
        $workingDays = $totalDays - ($totalSundays + $totalNonSundayHoliday);

        $this->LEAVE_CREDIT_PER_ATTENDANCE = ($this->LEAVE_CREDIT_PER_MONTH / $workingDays);

        $leaveCredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);
        $leaveCredit = $leaveCredit + $this->LEAVE_CREDIT_PER_ATTENDANCE;
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
        var_dump($ApprovedPrevMondayLeave && $ApprovedPrevSaturdayLeave);
        var_dump(($presentOnPrevMonday === 0) && ($ApprovedPrevSaturdayLeave === 0));
        var_dump(($ApprovedPrevMondayLeave === 1) && ($presentOnPrevSaturday === 0));
        var_dump(($presentOnPrevMonday === 0) && ($presentOnPrevSaturday === 0));
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
        //echo "Hours Passed After Entry: $hoursPassedAfterEntry";
        return $hoursPassedAfterEntry;
    }
    public function getTotalSundaysInMonth($month, $year) {
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
    public function prepareAttendanceData($user_id, $date) {
        $data = [
            'DATE' => $date,
            'HALF_DAY' => $this->checkHalfDayOnPunchIn(),
            'USER_ID' => $user_id,
            'TOTAL_USERCOUNT' => $this->AttendanceModel->getTotalAttendeesonDate($date),
            'BASE_SALARY' => $this->SalaryModel->getSalaryByUserID($user_id),
        ];

        return $data;
    }
    public function checkHalfDayOnPunchIn() {
        // Check For half day on Punch in
        $entryTime = new \DateTime(); // now
        $cutoff = new \DateTime(date('Y-m-d')  . ' ' . $this->HALFDAY_ENTRY_TIME);
        if ($entryTime > $cutoff) {
            return 1;
        }
        return 0;
    }
    public   function checkHalfDayOnPunchOut() {
        // Check For half day on Punch out
        $exitTime = new \DateTime(); // now
        $cutoff = new \DateTime(date('Y-m-d') . ' ' . $this->HALFDAY_EXIT_TIME);

        if ($exitTime < $cutoff) {
            return 1;
        }
        return 0;
    }
    public function subtractDayLeaveCredit($user_id, $month, $year) {

        $date = new \DateTime("$year-$month-01");

        $totalDays = (int)$date->format('t');
        $totalSundays = $this->getTotalSundaysInMonth($month, $year);
        $totalNonSundayHoliday = $this->HolidayModel->getAllNonSundayHolidaysCountofMonthYear($month, $year);
        $workingDays = $totalDays - ($totalSundays + $totalNonSundayHoliday);
        $attendedDays = count($this->AttendanceModel->getAllAttendanceofUserByMonthYear($user_id, $month, $year));

        $totalAbsentDays = $workingDays - $attendedDays;

        $this->LEAVE_CREDIT_PER_ATTENDANCE = ($this->LEAVE_CREDIT_PER_MONTH / $workingDays);

        $totalSubtractedLeaveCredit = $totalAbsentDays * $this->LEAVE_CREDIT_PER_ATTENDANCE;

        $leaveCredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);

        if (($leaveCredit - $totalSubtractedLeaveCredit) >= 0) {
            $leaveCredit = $leaveCredit - $totalSubtractedLeaveCredit;
        } else {
            $leaveCredit = 0;
        }
        $this->LeaveCreditModel->setLeaveCreditByUserID($user_id, $leaveCredit);
    }
    public function isValidDate($date) {
        return \DateTime::createFromFormat('Y-m-d', $date) !== false;
    }
}
