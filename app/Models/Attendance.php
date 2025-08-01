<?php

namespace App\Models;

use CodeIgniter\Model;

class Attendance extends Model {
    protected $table = 'attendance_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'DATE',
        'PUNCH_OUT',
        'HALF_DAY',
        'USER_ID',
        'TOTAL_USERCOUNT',
        'BASE_SALARY',
        'CREATED_ON',
        'UPDATED_AT',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';

    public function insertEntry($data) {
        return $this->insert($data);
    }
    public function setAttendancePunchOutByUserID($user_id, $date) {
        return $this->where('DATE', $date)->where('USER_ID', $user_id)->set('PUNCH_OUT', 1)->update();
    }
    public function setAttendanceHalfDayByUserID($user_id, $date) {
        return $this->where('DATE', $date)->where('USER_ID', $user_id)->set('HALF_DAY', 1)->update();
    }
    public function isEntryExist($date, $user_id) {
        return $this->where('DATE', $date)->where('USER_ID', $user_id)->countAllResults() > 0;
    }
    public function isEntryPunchedOut($date, $user_id) {
        return $this->where('DATE', $date)->where('USER_ID', $user_id)->where('PUNCH_OUT', 1)->countAllResults() > 0;
    }
    public function getEntryCreated($user_id, $date) {

        $result = $this->asObject()
            ->select('CREATED_ON')
            ->where('DATE', $date)
            ->where('USER_ID', $user_id)
            ->first();
        return $result?->CREATED_ON ?? null;
    }
    public function getTodayAttendance() {

        $today = (new \DateTime('now'))->format('Y-m-d'); 
        return $this->db->table($this->table)
            ->select("{$this->table}.*, users_table.NAME, users_table.DESIGNATION")
            ->join('users_table', "users_table.ID = {$this->table}.USER_ID")
            ->where("{$this->table}.DATE", $today)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function getAttendanceByDate($date) {

        return $this->db->table($this->table)
            ->select("{$this->table}.DATE, {$this->table}.CREATED_ON, {$this->table}.UPDATED_AT, users_table.NAME, users_table.ID, users_table.DESIGNATION")
            ->join('users_table', "users_table.ID = {$this->table}.USER_ID")
            ->where("{$this->table}.DATE", $date)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function getAllAttendanceofUser($USER_ID) {

        return $this->db->table($this->table)
            ->where('USER_ID', $USER_ID)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function isUserPresentonDate($USER_ID, $date) {

        return $this->db->table($this->table)
            ->where('DATE', $date)
            ->where('USER_ID', $USER_ID)
            ->countAllResults() > 0;
    }
    public function getAllAttendanceofUserByMonthYear($user_id, $month, $year) {

        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start)); // end of the month

        return $this->db->table($this->table)
            ->where('USER_ID', $user_id)
            ->where("DATE >=", $start)
            ->where("DATE <=", $end)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function getAllHalfDayAttendanceofUserByMonthYear($user_id, $month, $year) {

        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start)); // gets last day of the month

        return $this->db->table($this->table)
            ->where('USER_ID', $user_id)
            ->where('HALF_DAY', 1)
            ->where('DATE >=', $start)
            ->where('DATE <=', $end)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function getAllFullDayAttendanceofUserByMonthYear($user_id, $month, $year) {

        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start));

        return $this->db->table($this->table)
            ->where('USER_ID', $user_id)
            ->where('HALF_DAY', 0)
            ->where('DATE >=', $start)
            ->where('DATE <=', $end)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function getBaseSalaryofMonthYearbyUserid($user_id, $month, $year) {

        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-t', strtotime($start));

        $result = $this->db->table($this->table)
            ->select('MIN(BASE_SALARY) AS BASE_SALARY')
            ->where('USER_ID', $user_id)
            ->where('DATE >=', $start)
            ->where('DATE <=', $end)
            ->get()
            ->getRow();

        return $result ? (float) $result->BASE_SALARY : null;
    }
    public function getTotalAttendeesonDate($date) {

        $count = $this->db->table($this->table)
            ->where('DATE', $date)
            ->countAllResults();

        // Added logic to increment TOTAL_USERCOUNT from 0 to 1 upon the first attendance entry.
        // This ensures the user who performs the initial punch-in is included in the count.

        return $count + 1; 
    }
    public function getEachDayAttendanceDataofMonth($month, $year) {

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->db->table($this->table)
            ->select('DATE, MAX(TOTAL_USERCOUNT) AS TOTAL_USERCOUNT')
            ->where('DATE >=', $startDate)
            ->where('DATE <=', $endDate)
            ->groupBy('DATE')
            ->orderBy('DATE', 'ASC')
            ->get()
            ->getResult();
    }
    public function getSumofTotalUserCountofmonthyear($year) {

        // Subquery: max count per day
        $subQuery = $this->db->table($this->table)
            ->select('DATE, MONTH(DATE) as MONTH, MAX(TOTAL_USERCOUNT) as MAX_USER')
            ->where('YEAR(DATE)', $year)
            ->groupBy('DATE')
            ->getCompiledSelect();

        // Outer query: sum daily max by month
        $query = $this->db->query("
            SELECT MONTH, SUM(MAX_USER) as SUM_OF_USERCOUNT
            FROM ($subQuery) as daily_max
            GROUP BY MONTH
            ORDER BY MONTH
        ");

        $result = $query->getResult();
        return $result;
    }
    public function getAnnualAttendedDayCountByMonthWise($user_id,$year) {

        // Subquery: max count per day
        $subQuery = $this->db->table($this->table)
            ->select('MONTH(DATE) as month, COUNT(*) as attended_days')
            ->where('YEAR(DATE)', $year)
            ->where('USER_ID',$user_id)
            ->groupBy('DATE')
            ->getCompiledSelect();

        // Outer query: sum daily max by month
        $query = $this->db->query("
            SELECT MONTH, SUM(attended_days) as ATTENDED_DAYS
            FROM ($subQuery) as daily_max
            GROUP BY MONTH
            ORDER BY MONTH
        ");

        $result = $query->getResult();
        return $result;
    }
}
