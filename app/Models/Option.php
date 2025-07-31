<?php

namespace App\Models;

use CodeIgniter\Model;

class Option extends Model
{
    protected $table = 'options_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = [
        'NAME',
        'VALUE',
        'CREATED_ON',
        'UPDATED_AT'
    ];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $db;
    public function insertOption($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function getAllOptions()
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select("*");
        $query = $builder->get();
        $result = $query->getResultObject();
        return $result;
    }
    public function getOptionValue($name)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select("*");
        $builder->where('name', $name);
        $query = $builder->get();
        $result = $query->getRowObject();
        return (is_numeric($result->VALUE)) ? (int)$result->VALUE : $result->VALUE;
    }
    public function saveOption($name, $value)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->set("value", $value);
        $builder->where('name', $name);
        $builder->update();
    }
}
