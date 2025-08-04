<?php

namespace App\Controllers;


use App\Models\User;
use App\Models\Leave;
use App\Models\LeaveCredit;
use App\Models\Holiday;
use App\Models\Salary;
use App\Models\Attendance;
use App\Models\Option;
use Config\Services;

class AttendanceController extends BaseController {
    public $UserModel,
        $AttendanceModel,
        $LeaveCreditModel,
        $LeaveModel,
        $HolidayModel,
        $SalaryModel,
        $OptionModel,
        $LeaveService,
        $AttendanceService;

    public $data = [];
    private $flag = [];

    public function __construct() {
        $this->session = Services::session();
        $this->LeaveService = Services::leaveService();
        $this->AttendanceService = Services::attendanceService();
        $this->UserModel = new User();
        $this->AttendanceModel = new Attendance();
        $this->LeaveModel = new Leave();
        $this->HolidayModel = new Holiday();
        $this->LeaveCreditModel = new LeaveCredit();
        $this->SalaryModel = new Salary();
        $this->OptionModel = new Option();
        $this->data['TotalEmployeeOnDate'] = $this->AttendanceModel->getTotalAttendeesonDate(date('Y-m-d'));
        $this->flag['EnableMarkAttendanceOnLogin'] = $this->OptionModel->getOptionValue('EnableMarkAttendanceOnLogin');
    }

    public function AttendanceByDate() {
        $date = $this->request->getGet('date');
        // Basic validation
        if (!$this->AttendanceService->isValidDate($date)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Invalid date format.']);
        }
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

            if ($this->AttendanceService->checkHalfDayOnPunchOut()) {
                $this->AttendanceModel->setAttendanceHalfDayByUserID($user_id, $date);
            }
            $hoursPassedAfterEntry = $this->AttendanceService->hoursPassedAfterEntry($user_id, $date);

            if ($hoursPassedAfterEntry > $this->AttendanceService->PUNCH_OUT_BUFFER_HOURS) {
                $this->AttendanceModel->setAttendancePunchOutByUserID($user_id, $date);
                return $this->RedirectWithtoast('Attendance Punch Out Marked', 'Success', 'auth.login');
            } else {
                return $this->RedirectWithtoast('Punched in recently. Please wait.', 'warning', 'auth.login');
            }
        } elseif (!$isEntryExist) {

            $AttandenceData = $this->AttendanceService->prepareAttendanceData($user_id, $date);

            $this->AttendanceModel->insertEntry($AttandenceData);
            $this->AttendanceService->addDayLeaveCredit($user_id);
            $this->AttendanceService->checkandCreateSandwichLeave($user_id, $date);

            return $this->RedirectWithtoast('Attendance Marked', 'Success', 'auth.login');
        }
        return $this->RedirectWithtoast('Attendance Fully Marked', 'Success', 'auth.login');
    }
    public function AttendanceEntryProcessWhileLogin($USER_ID) {

        if (!$this->flag['EnableMarkAttendanceOnLogin']) {
            return 0;
        }
        $date     = date('Y-m-d');
        $user_id  = $USER_ID;
        $isEntryExist = $this->AttendanceModel->isEntryExist($date, $user_id);

        if (!$isEntryExist) {
            $AttandenceData = $this->AttendanceService->prepareAttendanceData($user_id, $date);

            $this->AttendanceModel->insertEntry($AttandenceData);
            $this->AttendanceService->addDayLeaveCredit($user_id);
            $this->AttendanceService->checkandCreateSandwichLeave($user_id, $date);

            log_message('info', "Attendance marked for USER_ID: {$user_id} on login.");

            return $this->RedirectWithtoast('Attendance Marked', 'Success', 'employee.account');
        }
        return 0;
    }
    public function AttendanceEntryPunchOutProcess() {


        $date     = date('Y-m-d');
        $user_id  = $this->request->getPost('user_id');
        $isEntryExist = $this->AttendanceModel->isEntryExist($date, $user_id);
        $isEntryPunchedOut = $this->AttendanceModel->isEntryPunchedOut($date, $user_id);

        if ($isEntryPunchedOut) {
            return $this->RedirectWithtoast('Attendance Punch Out Already Marked', 'warning', 'auth.login');
        } elseif ($isEntryExist && !($isEntryPunchedOut)) {

            if ($this->AttendanceService->checkHalfDayOnPunchOut()) {
                $this->AttendanceModel->setAttendanceHalfDayByUserID($user_id, $date);
            }

            $hoursPassedAfterEntry = $this->AttendanceService->hoursPassedAfterEntry($user_id, $date);

            if ($hoursPassedAfterEntry > $this->AttendanceService->PUNCH_OUT_BUFFER_HOURS) {
                $this->AttendanceModel->setAttendancePunchOutByUserID($user_id, $date);
                return $this->RedirectWithtoast('Attendance Punch Out Marked', 'Success', 'auth.logout');
            } else {
                return $this->RedirectWithtoast('Punched in recently. Please wait.', 'warning', 'auth.logout');
            }
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
        foreach ($employeesIDArr as $employee) {
            $this->AttendanceService->subtractDayLeaveCredit($employee->ID, $previousDate_Month, $previousDate_Year);
        }
        return $this->RedirectWithtoast('Leave Credit Recalculated', 'Success', '/login');
    }
}
