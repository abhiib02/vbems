<?php

namespace App\Models;

use CodeIgniter\Model;

class Salary extends Model {
    protected $table = 'salary_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['USER_ID', 'BASIC_SALARY', 'CREATED_ON', 'UPDATED_AT'];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $db;
    public function __construct() {
        $this->db = \Config\Database::connect();
    }
    public function insertSalary($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function isSalaryExistByUserID($user_id) {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('USER_ID', $user_id);
        $builder->where('BASIC_SALARY !=', NULL);
        $query = $builder->countAllResults();

        return ($query == 1) ? 1 : 0;
    }
    public function getAllSalaries() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getSalaryByUserID($USER_ID) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('USER_ID', $USER_ID);
        $query = $builder->get();
        $result = $query->getRow();
        return ($result != null) ? $result->BASIC_SALARY : 0;
    }
    public function setSalarybyID($ID, $SALARY) {

        $builder = $this->db->table($this->table);
        $builder->where('USER_ID', $ID);
        $builder->set('BASIC_SALARY', $SALARY);
        $builder->update();
        return $ID;
    }
}
