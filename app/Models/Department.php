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
    protected $db;
    public function __construct() {
        $this->db = \Config\Database::connect();
    }
    public function insertDepartment($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function updateDepartment($id, $data) {

        $builder = $this->db->table($this->table);
        $builder->where(["ID" => $id]);
        $builder->set($data);
        $builder->update();
        return 1;
    }

    public function deleteDepartment($id) {

        $builder = $this->db->table($this->table);
        $builder->where('ID', $id);
        $builder->delete();
        return $id;
    }
    public function isDepartmentExistID($id) {

        $builder = $this->db->table($this->table);
        $builder->select('ID');
        $builder->where('ID', $id);
        $query = $builder->countAllResults();

        return ($query > 0) ? 1 : 0;
    }
    public function getAllDepartments() {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllDepartmentsCount() {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $count = $builder->countAllResults();
        return $count;
    }
    public function getDepartmentByID($ID) {
        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return ($result != null) ? $result : null;
    }
    public function getDepartmentNameByDepartmentID($ID) {
        $builder = $this->db->table($this->table);
        $builder->select("NAME");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return ($result != null) ? $result->NAME : null;
    }
    
    public function getLeavePersonsCountByDepartmentID($ID) {
        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return ($result != null) ? $result->LEAVE_PERSON_COUNT : null;
    }
}
