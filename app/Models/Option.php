<?php

namespace App\Models;

use CodeIgniter\Model;

class Option extends Model {
    protected $table = 'options_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'NAME',
        'VALUE',
        'TYPE',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';

    public function insertOption($data) {
        return $this->insert($data);
    }

    public function getAllOptions() {
        return $this->asObject()->findAll();
    }
    public function getOptionValue($name) {
        $row = $this->asObject()->where('NAME', $name)->first();

        if (!$row) { return null; }

        return is_numeric($row->VALUE) ? (int)$row->VALUE : $row->VALUE;
    }
    public function saveOption($name, $value) {
        $this->where('NAME', $name)->set('VALUE', $value)->update();
    }
}
