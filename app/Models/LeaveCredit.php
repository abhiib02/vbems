<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveCredit extends Model {
    protected $table = 'leavecredit_table';
    protected $allowedFields = [
        'USER_ID',
        'LEAVECREDIT',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $db;
    protected $dateFormat = 'datetime';

    public function __construct() {
        $this->db = \Config\Database::connect();
    }
    public function insertLeaveCredit($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function setLeaveCreditByUserID($ID, $LEAVECREDIT) {

        $builder = $this->db->table($this->table);
        $builder->where('USER_ID', $ID);
        $builder->set('LEAVECREDIT', $LEAVECREDIT);
        $builder->update();
        return $ID;
    }
    public function getAllLeaveCredit() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getLeaveCreditByUserID($USER_ID) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('USER_ID', $USER_ID);
        $query = $builder->get();
        $result = $query->getRow();
        return floor((float)$result->LEAVECREDIT * 2) / 2;
        //return (float)$result->LEAVECREDIT;
    }
    public function isLeaveCreditExistByID($id) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('USER_ID', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
