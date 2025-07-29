<?php

namespace App\Models;

use CodeIgniter\Model;

class Attendance extends Model {
    protected $table = 'attendance_table';
    public $db;
    protected $allowedFields = [
        'USER_ID',
        'DATE',
        'PUNCH_IN',
        'PUNCH_OUT',
        'CREATED_ON',
        'UPDATED_AT',
        'TOTAL_USERCOUNT',
        'BASE_SALARY'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';


    public function __construct() {
        $this->db = \Config\Database::connect();
    }
    public function insertEntry($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function setAttendancePunchOutByUserID($user_id, $date) {

        $builder = $this->db->table($this->table);
        $builder->where('DATE', $date);
        $builder->where('USER_ID', $user_id);
        $builder->set('PUNCH_OUT', 1);
        $builder->update();
        return $user_id;
    }
    public function isEntryExist($date, $user_id) {

        $builder = $this->db->table($this->table);
        $builder->where('DATE', $date);
        $builder->where('USER_ID', $user_id);
        $query = $builder->countAllResults();
        if ($query > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function isEntryPunchedOut($date, $user_id) {

        $builder = $this->db->table($this->table);
        $builder->where('DATE', $date);
        $builder->where('USER_ID', $user_id);
        $builder->where('PUNCH_OUT', 1);
        $query = $builder->countAllResults();
        if ($query > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function getEntryCreated($user_id, $date) {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('DATE', $date);
        $builder->where('USER_ID', $user_id);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->CREATED_ON;
    }
    public function getTodayAttendance() {

        $builder = $this->db->table($this->table);
        $builder->select('attendance_table.*, users_table.NAME, users_table.DESIGNATION');
        $builder->join('users_table', 'users_table.ID = attendance_table.USER_ID');
        $builder->where('DATE', date('Y-m-d'));
        $builder->orderBy('ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAttendanceByDate($date) {

        $builder = $this->db->table($this->table);
        $builder->select('attendance_table.DATE,attendance_table.CREATED_ON , attendance_table.UPDATED_AT, users_table.NAME, users_table.ID, users_table.DESIGNATION');
        $builder->join('users_table', 'users_table.ID = attendance_table.USER_ID');
        $builder->where('DATE', $date);
        $builder->orderBy('attendance_table.ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllAttendanceofUser($USER_ID) {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('USER_ID', $USER_ID);
        $builder->orderBy('ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function isUserPresentonDate($USER_ID, $date) {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('DATE', $date);
        $builder->where('USER_ID', $USER_ID);
        $query = $builder->countAllResults();
        if ($query > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function getAllAttendanceofUserByMonthYear($USER_ID, $MONTH, $YEAR) {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('USER_ID', $USER_ID);
        $builder->where('YEAR(DATE) =', $YEAR, false);
        $builder->where('MONTH(DATE) =', $MONTH, false);
        $builder->orderBy('ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getBaseSalaryofMonthYearbyUserid($USER_ID, $MONTH, $YEAR) {

        $builder = $this->db->table($this->table);
        $builder->select('MIN(BASE_SALARY) AS BASE_SALARY');
        $builder->where('USER_ID', $USER_ID);
        $builder->where('YEAR(DATE) =', $YEAR, false);
        $builder->where('MONTH(DATE) =', $MONTH, false);
        $builder->orderBy('ID', 'desc');
        $query = $builder->get();
        $result = $query->getRow();
        return $result->BASE_SALARY;
    }
    public function getTotalAttendeesonDate($date) {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('DATE', $date);
        $count = $builder->countAllResults();
        return $count + 1;
    }
    public function getEachDayAttendanceDataofMonth($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select('DATE, MAX(TOTAL_USERCOUNT) as TOTAL_USERCOUNT');
        $builder->where('MONTH(DATE) =', $month, false);
        $builder->where('YEAR(DATE) =', $year, false);
        $builder->groupBy('DATE');
        $builder->orderBy('DATE', 'ASC');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
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
