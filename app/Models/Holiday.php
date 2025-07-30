<?php

namespace App\Models;

use CodeIgniter\Model;

class Holiday extends Model {
    protected $table = 'holidays_table';
    protected $allowedFields = [
        'DATE',
        'TITLE',
        'DESCRIPTION',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }
    public function insertHoliday($data) {

        return $this->db->table($this->table)->insert($data);
    }
    public function updateHoliday($id, $data) {

        $builder = $this->db->table($this->table);
        $builder->where('ID', $id);
        $builder->set($data);
        $builder->update();
        return $id;
    }
    public function deleteHoliday($id) {

        $builder = $this->db->table($this->table);
        $builder->where('ID', $id);
        $builder->delete();
        return $id;
    }
    public function isHolidayExist($date) {

        $builder = $this->db->table($this->table);
        $builder->select('DATE');
        $builder->where('DATE', $date);
        $query = $builder->countAllResults();
        return ($query > 0) ? 1 : 0;
    }
    public function isHolidayExistID($id) {

        $builder = $this->db->table($this->table);
        $builder->select('ID');
        $builder->where('ID', $id);
        $query = $builder->countAllResults();

        return ($query > 0) ? 1 : 0;
    }
    public function getAllHolidays() {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllHolidaysofMonthYear($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('MONTH(DATE)', $month);
        $builder->where('YEAR(DATE)', $year);
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllNonSundayHolidaysCountofMonthYear($month, $year) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('MONTH(DATE)', $month);
        $builder->where('YEAR(DATE)', $year);
        $builder->where('DAYOFWEEK(DATE) !=', 1);
        $count = $builder->countAllResults();
        return $count;
    }
    public function getAllHolidaysofYear($year) {
        $sql = "
            SELECT 
                m.MONTH_NUMBER,
                m.MONTH_NAME,
                COUNT(h.DATE) AS HOLIDAY_COUNT
            FROM (
                SELECT 1 AS MONTH_NUMBER, 'January' AS MONTH_NAME UNION
                SELECT 2, 'February' UNION
                SELECT 3, 'March' UNION
                SELECT 4, 'April' UNION
                SELECT 5, 'May' UNION
                SELECT 6, 'June' UNION
                SELECT 7, 'July' UNION
                SELECT 8, 'August' UNION
                SELECT 9, 'September' UNION
                SELECT 10, 'October' UNION
                SELECT 11, 'November' UNION
                SELECT 12, 'December'
            ) AS m
            LEFT JOIN holidays_table h 
                ON MONTH(h.DATE) = m.MONTH_NUMBER AND YEAR(h.DATE) = ?
            GROUP BY m.MONTH_NUMBER, m.MONTH_NAME
            ORDER BY m.MONTH_NUMBER
        ";

        return $this->db->query($sql, [$year])->getResult();
    }
    public function getNextHoliday() {

        $builder = $this->db->table($this->table);
        $builder->where('DATE >', date('Y-m-d')); // today’s date
        $builder->orderBy('DATE', 'ASC');
        $builder->limit(1);
        $query = $builder->get();
        return $query->getRow(); // returns the next holiday object
    }
}
