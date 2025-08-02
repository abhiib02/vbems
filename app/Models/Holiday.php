<?php

namespace App\Models;

use CodeIgniter\Model;

class Holiday extends Model {
    protected $table = 'holidays_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'DATE',
        'HOLIDAY',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';

    public function insertHoliday($data) {
        return $this->insert($data);
    }
    public function updateHoliday($id, $data) {

        return $this->db->table($this->table)
            ->where('ID', $id)
            ->update($data);
    }
    public function deleteHoliday($id) {

        return $this->db->table($this->table)
            ->where('ID', $id)
            ->delete();
    }
    public function isHolidayExist($date) {

        return $this->db->table($this->table)
            ->where('DATE', $date)
            ->countAllResults() > 0;
    }
    public function isHolidayExistID($id) {

        return $this->db->table($this->table)
            ->where('ID', $id)
            ->countAllResults() > 0;
    }
    public function getAllHolidays() {
        return $this->asObject()->orderBy('DATE', 'asc')->findAll();
    }
    public function getAllHolidaysofCurrentYear() {
        $date = date('Y');
        return $this->asObject()
            ->where('YEAR(DATE)', $date, false)
            ->orderBy('DATE', 'asc')
            ->findAll();
    }
    public function getAllHolidaysofMonthYear($month, $year) {

        return $this->asObject()
            ->where('MONTH(DATE)', $month, false)
            ->where('YEAR(DATE)', $year, false)
            ->orderBy('DATE', 'asc')
            ->findAll();
    }
    public function getAllNonSundayHolidaysCountofMonthYear($month, $year) {

        return $this->db->table($this->table)
            ->where('MONTH(DATE)', $month, false)
            ->where('YEAR(DATE)', $year, false)
            ->where('DAYOFWEEK(DATE) !=', 1)
            ->countAllResults();
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
        LEFT JOIN {$this->table} h 
            ON MONTH(h.DATE) = m.MONTH_NUMBER AND YEAR(h.DATE) = ?
        GROUP BY m.MONTH_NUMBER, m.MONTH_NAME
        ORDER BY m.MONTH_NUMBER
    ";
        return $this->db->query($sql, [$year])->getResult();
    }
    
    public function getNextHoliday() {

        return $this->asObject()
            ->where('DATE >', date('Y-m-d'))
            ->orderBy('DATE', 'asc')
            ->first(); // cleaner than using builder->limit(1)->get()
    }
}
