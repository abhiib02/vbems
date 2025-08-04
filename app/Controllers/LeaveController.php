<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Leave;
use App\Models\LeaveCredit;
use App\Models\Department;
use Config\Services;
use App\Controllers\EmailController;

class LeaveController extends BaseController {
    public $data = [], $userModel, $leaveModel, $LeaveCreditModel, $DepartmentModel, $EmailController, $session;

    public function __construct() {
        $this->session = Services::session();

        $this->userModel = new User();
        $this->leaveModel = new Leave();
        $this->EmailController = new EmailController();
        $this->LeaveCreditModel = new LeaveCredit();
        $this->DepartmentModel = new Department();
        $this->data['id'] = $this->session->get('id');
        $this->data['name'] = $this->session->get('name');
        $this->data['email'] = $this->session->get('email');
    }

    public function requestLeaveProcess() {

        $user_id = $this->request->getPost('user_id');
        $Leavetype = $this->request->getPost('type');
        $LeaveReason = $this->request->getPost('reason');
        $from_to_date_arr = explode('/', $this->request->getPost('from_to_date'));

        $rules = [
            'user_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'User ID is required.',
                ]
            ],
            'type' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Leave Type is required.',
                ]
            ],
            'from_to_date' => [
                'rules' => 'required|regex_match[/\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}/]',
                'errors' => [
                    'required' => 'Date is required.',
                    'regex_match' => 'Date range format is invalid.'
                ]
            ],
            'reason' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Reason is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', '/my-leaves');
        }

        $days = $this->daysCountBetweenDates($from_to_date_arr[0], $from_to_date_arr[1]);
        $Department_id = $this->userModel->getDepartmentIDByUserID($user_id);
        $deptLeaveCountPerson = $this->DepartmentModel->getLeavePersonsCountByDepartmentID($Department_id);


        $from = new \DateTime($from_to_date_arr[0]);
        $to = new \DateTime($from_to_date_arr[1]);

        $FROM_DATE = $from->format('Y-m-d');
        $TO_DATE = $to->format('Y-m-d');

        $isLeaveExist = $this->leaveModel->isLeaveExist($user_id, $FROM_DATE, $TO_DATE);

        $isDepartmentLeaveExistonDate = $this->leaveModel->isDepartmentLeaveExistonDate($Department_id, $FROM_DATE, $TO_DATE);
        $isFromDateBetweenExistingLeave = $this->leaveModel->isDateExistBetweenFromAndTo($Department_id, $FROM_DATE);
        $isToDateBetweenExistingLeave = $this->leaveModel->isDateExistBetweenFromAndTo($Department_id, $TO_DATE);
        $deptLeaveCount = $this->leaveModel->getLeavesCountAfterTodayByDepartmentID($Department_id);

        if ($FROM_DATE < date('Y-m-d') || $TO_DATE < date('Y-m-d')) {
            return $this->RedirectWithtoast('Leave Request Cannot be in Past', 'danger', '/my-leaves');
        }
        if ($isLeaveExist) {
            return $this->RedirectWithtoast('Leave Request Already Exist', 'danger', '/my-leaves');
        }
        if ($isDepartmentLeaveExistonDate) {
            return $this->RedirectWithtoast('Leave Request Cannot be Processed, Date Already Exist in Department Leave', 'danger', '/my-leaves');
        }
        if ($isFromDateBetweenExistingLeave || $isToDateBetweenExistingLeave) {
            return $this->RedirectWithtoast('Leave Request Cannot be Processed, Date Overlapping in Department Leave', 'danger', '/my-leaves');
        }
        if ($deptLeaveCountPerson <= $deptLeaveCount) {
            return $this->RedirectWithtoast("Leave Request Cannot be Processed, Department Leave Limit Reached", 'danger', '/my-leaves');
        }

        $leavecredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);

        $zeroleavecredit = ($days < $leavecredit) ? 0 : 1;

        $LeaveRequestData = [
            "USER_ID" => $user_id,
            "DEPARTMENT_ID" => $Department_id,
            "FROM_DATE" => $from->format('Y-m-d'),
            "TO_DATE" => $to->format('Y-m-d'),
            "TYPE" => $Leavetype,
            "ZEROLEAVECREDIT" => $zeroleavecredit,
            "DAYS" => $days,
            "REASON" => $LeaveReason,
            "SUNDAY_COUNT" => $this->countSundays($from_to_date_arr[0], $from_to_date_arr[1]),
            "STATUS" => 'Pending',
        ];

        $LeaveRequestDataEmail = [
            "NAME" => $this->data['name'],
            "EMAIL" => $this->data['email'],
            "USER_ID" => $user_id,
            "DEPARTMENT_ID" => $Department_id,
            "FROM_DATE" => $from->format('Y-m-d'),
            "TO_DATE" => $to->format('Y-m-d'),
            "TYPE" => $Leavetype,
            "ZEROLEAVECREDIT" => $zeroleavecredit,
            "DAYS" => $days,
            "REASON" => $LeaveReason,
            "STATUS" => 'Pending',
        ];



        $this->leaveModel->insertLeave($LeaveRequestData);
        $this->EmailController->employee_leave_mail($LeaveRequestDataEmail);
        return $this->RedirectWithtoast('Leave Request Submitted', 'info', '/my-leaves');
    }

    public function createPaidLeaveProcess() {
        $User_id = $this->request->getPost('id');
        $from_to_date = $this->request->getPost('from_to_date');
        $from_to_date_arr = explode('/', $from_to_date);
        $from = $from_to_date_arr[0];
        $to = $from_to_date_arr[1];
        $reason = $this->request->getPost('reason');
        $dept_id = $this->request->getPost('dept_id');

        $type = "Paid Leave|PL";

        $rules = [
            'id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'User ID is required.',
                ]
            ],
            'from_to_date' => [
                'rules' => 'required|regex_match[/\d{4}-\d{2}-\d{2}\/\d{4}-\d{2}-\d{2}/]',
                'errors' => [
                    'required' => 'Date is required.',
                    'regex_match' => 'Date range format is invalid.'
                ]
            ],
            'reason' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Reason is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'employee.list');
        }

        $this->CreateLeave($User_id, $dept_id, $from, $to, $reason, $type);
        return $this->RedirectWithtoast('Paid Leave Created', 'success', 'employee.list');
    }

    public function CreateLeave($user_id, $dept_id, $from, $to, $reason, $type = "Sandwich") {

        $days = $this->daysCountBetweenDates($from, $to);

        $LeaveRequestData = [

            "USER_ID" => $user_id,
            "DEPARTMENT_ID" => $dept_id,
            "FROM_DATE" => $from,
            "TO_DATE" => $to,
            "TYPE" => $type,
            "DAYS" => $days,
            "REASON" => $reason,
            "SUNDAY_COUNT" => $this->countSundays($from, $to),
            "STATUS" => 'Approved',
        ];

        $this->leaveModel->insertLeave($LeaveRequestData);
    }

    public function ApproveLeaveProcess($REQUEST_ID) {

        $isLeaveExist = $this->leaveModel->isLeaveExistByRequestID($REQUEST_ID);
        if (!$isLeaveExist) {
            return $this->RedirectWithtoast('Leave Request Not Found', 'danger', 'leaveRequests.list');
        }

        $this->leaveModel->approveLeaveByID($REQUEST_ID);
        $LeaveRequestData = $this->leaveModel->getLeaveByID($REQUEST_ID);
        $LeaveType = explode('|', $LeaveRequestData->TYPE)[1];
        $user_id = $this->leaveModel->getuserByrequestID($REQUEST_ID);
        $leavecredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);

        if ($LeaveType != 'PL') {
            $leavecredit = (($leavecredit - $LeaveRequestData->DAYS) < 0) ? 0 : ($leavecredit - $LeaveRequestData->DAYS);
        }

        $this->LeaveCreditModel->setLeaveCreditByUserID($user_id, $leavecredit);
        $this->EmailController->leaveRequestProcessed_mail($LeaveRequestData, 1);
        return $this->RedirectWithtoast('Leave Request Approved', 'Success', 'leaveRequests.list');
    }

    public function RejectLeaveProcess($REQUEST_ID) {

        $isLeaveExist = $this->leaveModel->isLeaveExistByRequestID($REQUEST_ID);
        if (!$isLeaveExist) {
            return $this->RedirectWithtoast('Leave Request Not Found', 'danger', 'leaveRequests.list');
        }
        $this->leaveModel->rejectLeaveByID($REQUEST_ID);
        $LeaveRequestData = $this->leaveModel->getLeaveByID($REQUEST_ID);
        // $leavecredit = $this->LeaveCreditModel->getLeaveCreditByUserID($user_id);
        // $this->LeaveCreditModel->setLeaveCreditByUserID($user_id, $leavecredit);
        $this->EmailController->leaveRequestProcessed_mail($LeaveRequestData, 0);
        return $this->RedirectWithtoast('Leave Request Rejected', 'Danger', 'leaveRequests.list');
    }

    //---------------- protected function ----------------//
    protected function daysCountBetweenDates($from, $to) {

        $from = new \DateTime($from);
        $to = new \DateTime($to);
        $interval = $from->diff($to);
        $days = $interval->days + 1;
        return $days;
    }

    protected function countSundays($from, $to) {
        $start = new \DateTime($from);
        $end = new \DateTime($to);
        $count = 0;
        while ($start <= $end) {
            if ($start->format('N') == 7) { // 7 = Sunday
                $count++;
            }
            $start->modify('+1 day');
        }
        return $count;
    }
}
