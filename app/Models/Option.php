<?php

namespace App\Models;

use CodeIgniter\Model;

class Option extends Model
{


    public function getAllOptions()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('options_table');
        $builder->select("*");
        $query = $builder->get();
        $result = $query->getResultObject();
        return $result;
    }
    public function getOptionValue($name)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('options_table');
        $builder->select("*");
        $builder->where('name', $name);
        $query = $builder->get();
        $result = $query->getRowObject();
        return $result->value;
    }
    public function saveOption($name, $value)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('options_table');
        $builder->set("value", $value);
        $builder->where('name', $name);
        $builder->update();
    }
}
