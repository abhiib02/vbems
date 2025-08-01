<?php

namespace App\Models;

use CodeIgniter\Model;

class Leave extends Model {
    protected $table = 'leaves_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'USER_ID',
        'FROM_DATE',
        'TO_DATE',
        'DAYS',
        'TYPE',
        'REASON',
        'STATUS',
        'SUNDAY_COUNT',
        'CREATED_ON',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $db;
    protected $dateFormat = 'datetime';

   
    public function insertLeave($data) {
        return $this->insert($data);
    }
    public function getAllLeaves() {
        return $this->asObject()->findAll();
    }
    public function getAllPendingLeaveRequest() {

        return $this->select("{$this->table}.*, users_table.NAME")
                    ->join("users_table", "users_table.ID = {$this->table}.USER_ID")
                    ->where('STATUS', 'Pending')
                    ->get()
                    ->getResult();
    }
    public function getAllPendingLeaveRequestofMonthYear($month, $year) {


        return $this->select("{$this->table}.*, users_table.NAME")
            ->join("users_table", "users_table.ID = {$this->table}.USER_ID")
            ->where('MONTH(FROM_DATE)', $month, false)
            ->where('YEAR(FROM_DATE)', $year, false)
            ->where('STATUS', 'Pending')
            ->get()
            ->getResult();

    }
    public function getAllApprovedLeaveRequestofMonthYear($month, $year) {


        return $this->select("{$this->table}.*, users_table.NAME")
            ->join("users_table", "users_table.ID = {$this->table}.USER_ID")
            ->where('MONTH(FROM_DATE)', $month, false)
            ->where('YEAR(FROM_DATE)', $year, false)
            ->where('STATUS', 'Approved')
            ->get()
            ->getResult();

    }
    public function getAllRejectedLeaveRequestofMonthYear($month, $year) {

        return $this->select("{$this->table}.*, users_table.NAME")
            ->join("users_table", "users_table.ID = {$this->table}.USER_ID")
            ->where('MONTH(FROM_DATE)', $month, false)
            ->where('YEAR(FROM_DATE)', $year, false)
            ->where('STATUS', 'Rejected')
            ->get()
            ->getResult();

    }
    
    
    public function getAllPendingLeaveRequestCount() {
        return $this->where('STATUS', 'Pending')->countAllResults();
    }
    public function getLeavesByUserID($user_id) {

        return $this->select("{$this->table}.*, users_table.NAME, leavecredit_table.LEAVECREDIT")
                    ->join('users_table', "users_table.ID = {$this->table}.USER_ID", 'left')
                    ->join('leavecredit_table', "leavecredit_table.USER_ID = {$this->table}.USER_ID", 'left')
                    ->where("{$this->table}.USER_ID", $user_id)
                    ->orderBy('ID', 'desc')
                    ->get()
                    ->getResult();
    }
    public function getLeavesAfterTodayByDepartmentID($DEPARTMENT_ID) {
        $TODAY = date('Y-m-d');
        return $this->select("{$this->table}.*, users_table.NAME, leavecredit_table.LEAVECREDIT")
            ->join('users_table', "users_table.ID = {$this->table}.USER_ID", 'left')
            ->join('leavecredit_table', "leavecredit_table.USER_ID = {$this->table}.USER_ID", 'left')
            ->where("{$this->table}.DEPARTMENT_ID", $DEPARTMENT_ID)
            ->where("{$this->table}.TO_DATE >=", $TODAY)
            ->where('STATUS', 'Approved')
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function getLeavesCountAfterTodayByDepartmentID($DEPARTMENT_ID) {
        $today = date('Y-m-d');

        return $this->where('DEPARTMENT_ID', $DEPARTMENT_ID)
            ->where('FROM_DATE >', $today) 
            ->where('STATUS !=', 'Rejected')
            ->countAllResults();
    }
    public function getLatestLeaveByUserID($USER_ID) {

        return $this->asObject()
            ->where('USER_ID', $USER_ID)
            ->orderBy('ID', 'DESC')
            ->first();
    }
    public function getLeaveByID($ID) {

        return $this->asObject()->find($ID);
    }
    public function getuserByrequestID($requestId) {

        $row = $this->select('USER_ID')->asObject()->find($requestId);
        return $row ? (int) $row->USER_ID : null;
    }
    public function getLeaveDayCountofMonthYear($month, $year) {

        $startDate = date('Y-m-d', strtotime("$year-$month-01"));
        $endDate = date('Y-m-t', strtotime($startDate)); // last day of the month

        $result = $this->selectSum('DAYS')
            ->where('FROM_DATE >=', $startDate)
            ->where('FROM_DATE <=', $endDate)
            ->asObject()
            ->first();

        return $result && $result->DAYS !== null ? $result->DAYS : 0;
    }
    public function isLeaveExist($user_id, $from, $to) {

        return $this->where('FROM_DATE', $from)
            ->where('TO_DATE', $to)
            ->where('USER_ID', $user_id)
            ->countAllResults() > 0;
    }
    public function isLeaveExistByRequestID($requestID) {

        return $this->where('ID', $requestID)
            ->countAllResults() > 0;
    }
    public function isDateExistBetweenFromAndTo($department_id, $date) {
        return $this->where("FROM_DATE <=", $date)
            ->where("TO_DATE >=", $date)
            ->where("DEPARTMENT_ID", $department_id)
            ->countAllResults() > 0;
    }
    public function isDepartmentLeaveExistonDate($department_id, $from, $to) {
        return $this->where('FROM_DATE', $from)
            ->where('TO_DATE', $to)
            ->where('DEPARTMENT_ID', $department_id)
            ->countAllResults() > 0;
    }
    public function isSandwichLeave($user_id, $fromdate) {

        return $this->where('FROM_DATE', $fromdate)
            ->where('USER_ID', $user_id)
            ->where('TYPE', 'Sandwich')
            ->countAllResults() > 0;
    }
    public function isOnNotifiedLeaveOnFromDate($user_id, $day, $month, $year) {

        $fromDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

        return $this->where('FROM_DATE', $fromDate)
            ->where('USER_ID', $user_id)
            ->where('STATUS', 'Approved')
            ->countAllResults() > 0;

    }
    public function isOnNotifiedLeaveOnToDate($user_id, $day, $month, $year) {

        $toDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

        return $this->where('TO_DATE', $toDate)
            ->where('USER_ID', $user_id)
            ->where('STATUS', 'Approved')
            ->countAllResults() > 0;
    }
    public function getApprovedLeavesofMonthByID($user_id, $month, $year) {

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate)); // gets the last day of the month

        return $this->asObject()
            ->where('FROM_DATE >=', $startDate)
            ->where('FROM_DATE <=', $endDate)
            ->where('STATUS', 'Approved')
            ->where('USER_ID', $user_id)
            ->findAll();
    }
    public function getApprovedZeroLeaveCreditDaysCountofMonthByID($user_id, $month, $year) {

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $result = $this->selectSum('DAYS')
            ->where('FROM_DATE >=', $startDate)
            ->where('FROM_DATE <=', $endDate)
            ->where('STATUS', 'Approved')
            ->where('ZEROLEAVECREDIT', 1)
            ->where('USER_ID', $user_id)
            ->first();
        return isset($result['DAYS']) ? (int)$result['DAYS'] : 0;
    }
    public function getSumofSundayinApprovedLeavesofMonthByID($user_id, $month, $year) {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $result = $this->selectSum('SUNDAY_COUNT')
            ->where('FROM_DATE >=', $startDate)
            ->where('FROM_DATE <=', $endDate)
            ->where('STATUS', 'Approved')
            ->where('USER_ID', $user_id)
            ->first();

        return isset($result['SUNDAY_COUNT']) ? (int) $result['SUNDAY_COUNT'] : 0;
    }
    public function getLeaveDayCountofMonthYearByUserID($user_id, $month, $year) {

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $result = $this->selectSum('DAYS')
            ->where('FROM_DATE >=', $startDate)
            ->where('FROM_DATE <=', $endDate)
            ->where('USER_ID', $user_id)
            ->where('STATUS', 'Approved')
            ->where('TYPE NOT LIKE', '%PL')
            ->first();

        return isset($result['DAYS']) ? (int)$result['DAYS'] : 0;
    }
    public function getPaidLeaveDayCountofMonthYearByUserID($user_id, $month, $year) {

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $result = $this->selectSum('DAYS')
            ->where('FROM_DATE >=', $startDate)
            ->where('FROM_DATE <=', $endDate)
            ->where('USER_ID', $user_id)
            ->where('TYPE LIKE', '%PL')
            ->where('STATUS', 'Approved')
            ->first();

        return isset($result['DAYS']) ? (int)$result['DAYS'] : 0;
    }
    public function approveLeaveByID($ID) {

        if (!$this->isLeaveExistByRequestID($ID)) {
            return false;
        }
        return $this->db->table($this->table)
            ->where('ID', $ID)
            ->update(['STATUS' => 'Approved']);
    }
    public function rejectLeaveByID($ID) {

        if (!$this->isLeaveExistByRequestID($ID)) {
            return false;
        }
        return $this->db->table($this->table)
            ->where('ID', $ID)
            ->update(['STATUS' => 'Rejected']);
    }
}
