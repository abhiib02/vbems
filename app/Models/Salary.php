<?php

namespace App\Models;

use CodeIgniter\Model;

class Salary extends Model {
    protected $table = 'salary_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['USER_ID', 'BASIC_SALARY', 'CREATED_ON', 'UPDATED_AT'];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    public function insertSalary($data) {
        return $this->insert($data);
    }

    public function isSalaryExistByUserID($user_id) {
        return $this->where('USER_ID', $user_id)
            ->where('BASIC_SALARY IS NOT NULL', null, false)
            ->countAllResults() > 0;
    }

    public function getAllSalaries() {
        return $this->asObject()->findAll();
    }

    public function getSalaryByUserID($user_id) {
        $salary = $this->select('BASIC_SALARY')
            ->asObject()
            ->where('USER_ID', $user_id)
            ->first();

        return $salary->BASIC_SALARY ?? null;
    }

    public function setSalarybyID($user_id, $salary) {
        return $this->where('USER_ID', $user_id)
            ->set('BASIC_SALARY', $salary)
            ->update() && $this->db->affectedRows() > 0;
    }
}
