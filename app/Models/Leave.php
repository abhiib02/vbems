<?php

namespace App\Models;

use CodeIgniter\Model;

class Leave extends Model {
    protected $table = 'leaves_table';
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

    public function __construct() {
        $this->db = \Config\Database::connect();
    }
    public function insertLeave($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function getAllLeaves() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllPendingLeaveRequest() {

        $builder = $this->db->table($this->table);
        $builder->select('leaves_table.*, users_table.NAME');
        $builder->join('users_table', 'users_table.ID = leaves_table.USER_ID');
        $builder->where('STATUS', 'Pending');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllPendingLeaveRequestofMonthYear($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select('leaves_table.*, users_table.NAME');
        $builder->join('users_table', 'users_table.ID = leaves_table.USER_ID');
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('STATUS', 'Pending');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllApprovedLeaveRequestofMonthYear($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select('leaves_table.*, users_table.NAME');
        $builder->join('users_table', 'users_table.ID = leaves_table.USER_ID');
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('STATUS', 'Approved');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllRejectedLeaveRequestofMonthYear($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select('leaves_table.*, users_table.NAME');
        $builder->join('users_table', 'users_table.ID = leaves_table.USER_ID');
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('STATUS', 'Rejected');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    
    
    public function getAllPendingLeaveRequestCount() {

        $builder = $this->db->table($this->table);
        $builder->where('STATUS', 'Pending');
        $count = $builder->countAllResults();
        return $count;
    }
    public function getLeavesByUserID($USER_ID) {
        $builder = $this->db->table($this->table);
        $builder->select('leaves_table.*, users_table.NAME, leavecredit_table.LEAVECREDIT');
        $builder->join('users_table', 'users_table.ID = leaves_table.USER_ID', 'left');
        $builder->join('leavecredit_table', 'leavecredit_table.USER_ID = leaves_table.USER_ID', 'left');
        $builder->where('leaves_table.USER_ID', $USER_ID);
        $builder->orderBy('ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getLeavesAfterTodayByDepartmentID($DEPARTMENT_ID) {
        $TODAY = date('Y-m-d');
        $builder = $this->db->table($this->table);
        $builder->select('leaves_table.*, users_table.NAME, leavecredit_table.LEAVECREDIT');
        $builder->join('users_table', 'users_table.ID = leaves_table.USER_ID', 'left');
        $builder->join('leavecredit_table', 'leavecredit_table.USER_ID = leaves_table.USER_ID', 'left');
        $builder->where('leaves_table.DEPARTMENT_ID', $DEPARTMENT_ID);
        $builder->where('DATE(leaves_table.TO_DATE) >=', $TODAY);
        $builder->where('STATUS', 'Approved');
        $builder->orderBy('leaves_table.ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getLeavesCountAfterTodayByDepartmentID($DEPARTMENT_ID) {
        $TODAY = date('Y-m-d');
        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('DEPARTMENT_ID', $DEPARTMENT_ID);
        $builder->where('DATE(FROM_DATE) >', $TODAY);
        $builder->where('STATUS !=', 'Rejected');
        $count = $builder->countAllResults();
        return $count;
    }
    public function getLatestLeaveByUserID($USER_ID) {

        $builder = $this->db->table($this->table);
        $builder->where('USER_ID', $USER_ID);
        $builder->orderBy('ID', 'DESC');
        $builder->limit(1);
        $query = $builder->get();
        return $query->getRow();
    }
    public function getLeaveByID($ID) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }
    public function getuserByrequestID($ID) {

        $builder = $this->db->table($this->table);
        $builder->select("USER_ID");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->USER_ID;
    }
    public function getLeaveDayCountofMonthYear($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->selectSum("DAYS");
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->DAYS;
    }
    public function isLeaveExist($user_id, $from, $to) {

        $builder = $this->db->table($this->table);
        $builder->where('FROM_DATE', $from);
        $builder->where('TO_DATE', $to);
        $builder->where('USER_ID', $user_id);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isLeaveExistByRequestID($requestID) {

        $builder = $this->db->table($this->table);
        $builder->where('ID', $requestID);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isDateExistBetweenFromAndTo($department_id, $date) {
        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where("'$date' BETWEEN FROM_DATE AND TO_DATE", null, false);
        $builder->where('DEPARTMENT_ID', $department_id);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isDepartmentLeaveExistonDate($department_id, $from, $to) {
        $builder = $this->db->table($this->table);
        $builder->where('FROM_DATE', $from);
        $builder->where('TO_DATE', $to);
        $builder->where('DEPARTMENT_ID', $department_id);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isSandwichLeave($user_id, $date) {

        $builder = $this->db->table($this->table);
        $builder->where('FROM_DATE', $date);
        $builder->where('USER_ID', $user_id);
        $builder->where('TYPE', 'Sandwich');
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isOnNotifiedLeaveOnFromDate($user_id, $day, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->where('DAY(FROM_DATE)', $day, false);
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('USER_ID', $user_id);
        $builder->where('STATUS', 'Approved');
        $query = $builder->get();

        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isOnNotifiedLeaveOnToDate($user_id, $day, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->where('DAY(TO_DATE)', $day, false);
        $builder->where('MONTH(TO_DATE)', $month, false);
        $builder->where('YEAR(TO_DATE)', $year, false);
        $builder->where('USER_ID', $user_id);
        $builder->where('STATUS', 'Approved');
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function getApprovedLeavesofMonthByID($user_id, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('STATUS', 'Approved');
        $builder->where('USER_ID', $user_id);
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getApprovedZeroLeaveCreditDaysCountofMonthByID($user_id, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select("SUM(DAYS) AS DAYS");
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('STATUS', 'Approved');
        $builder->where('ZEROLEAVECREDIT', 1);
        $builder->where('USER_ID', $user_id);
        $query = $builder->get();
        $result = $query->getRowObject();
        return $result;
    }
    public function getSumofSundayinApprovedLeavesofMonthByID($user_id, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select("SUM(SUNDAY_COUNT) as SUNDAYS");
        $builder->where('MONTH(FROM_DATE)', $month, false);
        $builder->where('YEAR(FROM_DATE)', $year, false);
        $builder->where('USER_ID', $user_id);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->SUNDAYS;
    }
    public function getLeaveDayCountofMonthYearByUserID($user_id, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->selectSum("DAYS");
        $builder->where('MONTH(FROM_DATE)', $month);
        $builder->where('YEAR(FROM_DATE)', $year);
        $builder->where('USER_ID', $user_id);
        $builder->where('TYPE NOT LIKE', '%PL');
        $builder->where('STATUS', 'Approved');
        $query = $builder->get();
        $result = $query->getRow();
        return $result->DAYS;
    }
    public function getPaidLeaveDayCountofMonthYearByUserID($user_id, $month, $year) {

        $builder = $this->db->table($this->table);
        $builder->selectSum("DAYS");
        $builder->where('MONTH(FROM_DATE)', $month);
        $builder->where('YEAR(FROM_DATE)', $year);
        $builder->where('USER_ID', $user_id);
        $builder->where('TYPE LIKE', '%PL');
        $builder->where('STATUS', 'Approved');
        $query = $builder->get();
        $result = $query->getRow();
        return $result->DAYS;
    }
    public function approveLeaveByID($ID) {

        $builder = $this->db->table($this->table);
        $builder->where(["ID" => $ID]);
        $builder->set('STATUS', 'Approved');
        $builder->update();
        $builder->get();
        return $ID;
    }
    public function rejectLeaveByID($ID) {

        $builder = $this->db->table($this->table);
        $builder->where(["ID" => $ID]);
        $builder->set('STATUS', 'Rejected');
        $builder->update();
        return $ID;
    }
}
