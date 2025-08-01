<?php

namespace App\Models;

use CodeIgniter\Model;

class Department extends Model {
    protected $table = 'department_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'NAME',
        'LEAVE_PERSON_COUNT',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    
    public function insertDepartment($data) {
        return $this->insert($data);
    }
    public function updateDepartment($id, $data) {
        return $this->update($id, $data);
    }
    public function deleteDepartment($id) {
        return $this->delete($id);
    }
    public function isDepartmentExistID($id) {
        return $this->where('ID', $id)->countAllResults() > 0;
    }
    public function getAllDepartments() {
        return $this->asObject()->findAll();
    }
    public function getAllDepartmentsCount() {
        return $this->db->table($this->table)->countAllResults();
    }
    public function getDepartmentByID($ID) {
        return $this->find($ID);
    }
    public function getDepartmentNameByDepartmentID($ID) {
        $result = $this->asObject()
            ->select('NAME')
            ->where('ID', $ID)
            ->first();

        return $result?->NAME ?? null;
    }
    public function getLeavePersonsCountByDepartmentID($ID) {
        $result = $this->asObject()
            ->select('NAME')
            ->where('ID', $ID)
            ->first();

        return $result?->LEAVE_PERSON_COUNT ?? null;
    }
}
