<?php

namespace App\Services;

use App\Models\Leave;

class LeaveService {
    protected $leaveModel;

    public function __construct() {
        $this->leaveModel = new Leave();
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

        return $this->leaveModel->insertLeave($LeaveRequestData);
    }

    protected function daysCountBetweenDates($start_date, $end_date) {
        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        return $start->diff($end)->days + 1;
    }

    protected function countSundays($start_date, $end_date) {
        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $sundays = 0;

        while ($start <= $end) {
            if ($start->format('w') == 0) {
                $sundays++;
            }
            $start->modify('+1 day');
        }

        return $sundays;
    }
}
