<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveCredit extends Model {
    protected $table = 'leavecredit_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'USER_ID',
        'LEAVECREDIT',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $dateFormat = 'datetime';

    
    public function insertLeaveCredit($data) {
        return $this->insert($data);
    }
    
    public function setLeaveCreditByUserID($ID, $LEAVECREDIT) {
        return $this->where('USER_ID', $ID)->set('LEAVECREDIT', $LEAVECREDIT)->update() && $this->db->affectedRows() > 0;    
    }
    public function getAllLeaveCredit() {
        return $this->asObject()->findAll();
    }
    public function getLeaveCreditByUserID($user_id) {


        $record = $this->select('LEAVECREDIT')
            ->asObject()
            ->where('USER_ID', $user_id)
            ->first();

        if (!$record || $record->LEAVECREDIT === null) {
            return null;
        }

        //return (float)$record->LEAVECREDIT;

        // Round down to nearest 0.5 if that's the business rule
        return floor((float) $record->LEAVECREDIT * 2) / 2;
        
    }
    
    public function isLeaveCreditExistByID($user_id) {
        return $this->where('USER_ID', $user_id)->countAllResults() > 0;
    }
}
