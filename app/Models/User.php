<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Salary;

class User extends Model {
    protected $table = 'users_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['NAME', 'EMAIL', 'PASSWORD', 'ROLE', 'DESIGNATION', 'DEACTIVATE', 'CREATED_ON', 'UPDATED_AT'];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $db;
    protected $salaryModel;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function insertUser($data) {
        return $this->db->table($this->table)->insert($data);
    }
    public function updateUser($id, $data) {

        $builder = $this->db->table($this->table);
        $builder->where(["ID" => $id]);
        $builder->set($data);
        $builder->update();
        return 1;
    }
    public function getAllUsers() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getUserID($email) {

        $builder = $this->db->table($this->table);
        $builder->select("ID");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->ID;
    }
    public function getUser($email) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }
    public function getUserByID($id) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('ID', $id);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }
    public function getUserName($email) {

        $builder = $this->db->table($this->table);
        $builder->select("NAME");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->NAME;
    }
    public function getUserNameByID($ID) {

        $builder = $this->db->table($this->table);
        $builder->select("NAME");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->NAME;
    }
    public function getUserCreatedDate($ID) {

        $builder = $this->db->table($this->table);
        $builder->select("CREATED_ON");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        $CREATED_DATE = explode(' ', $result->CREATED_ON);
        return $CREATED_DATE[0];
    }
    public function getUserEmailByID($ID) {

        $builder = $this->db->table($this->table);
        $builder->select("EMAIL");
        $builder->where('ID', $ID);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->EMAIL;
    }
    public function getUserIDByBiometricID($biometricID) {

        $builder = $this->db->table($this->table);
        $builder->select("ID");
        $builder->where('BIOMETRIC_ID', $biometricID);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->ID;
    }
    public function getDepartmentIDByUserID($user_id) {

        $builder = $this->db->table($this->table);
        $builder->select("DEPARTMENT_ID");
        $builder->where('ID', $user_id);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->DEPARTMENT_ID;
    }
    public function getUserIDByEmail($email) {

        $builder = $this->db->table($this->table);
        $builder->select("ID");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->ID;
    }
    public function isUserExist($email) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isUserDeactivated($email) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('EMAIL', $email);
        $builder->where('DEACTIVATE', 1);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isUserExistByID($id) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('ID', $id);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isBiometricIDExist($bid) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('BIOMETRIC_ID', $bid);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function delUser($id) {

        if (!($this->isUserExistByID($id))) {
            return 0;
        }

        $builder = $this->db->table($this->table);
        $builder->where('ID', $id);
        $builder->delete();
        return 1;
    }
    public function isHashExist($hash) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('PASSWORD', $hash);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function insertPasswordResetTokenToEmail($email, $token) {

        $builder = $this->db->table($this->table);
        $builder->set("PASSWORD_RESET_TOKEN", $token);
        $builder->where('EMAIL', $email);
        $builder->update();
    }
    public function isTokenExist($token) {

        $builder = $this->db->table($this->table);
        $builder->select("*");
        $builder->where('PASSWORD_RESET_TOKEN', $token);
        $query = $builder->get();
        return ($query->getNumRows() > 0) ? 1 : 0;
    }
    public function isAdmin($email) {

        $builder = $this->db->table($this->table);
        $builder->select("ROLE");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->ROLE;
    }
    public function MakeAdmin($id) {

        $builder = $this->db->table($this->table);
        $builder->set("ROLE", 1);
        $builder->where('ID', $id);
        $builder->update();
    }

    public function RemoveAdmin($id) {

        $builder = $this->db->table($this->table);
        $builder->set("ROLE", 0);
        $builder->where('ID', $id);
        $builder->update();
        return 'Admin Privileges Revoked';
    }
    public function getUserPassHash($email) {

        $builder = $this->db->table($this->table);
        $builder->select("PASSWORD");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->PASSWORD;
    }
    public function getUserPassResetToken($email) {

        $builder = $this->db->table($this->table);
        $builder->select("PASSWORD_RESET_TOKEN");
        $builder->where('EMAIL', $email);
        $query = $builder->get();
        $result = $query->getRow();
        return $result->PASSWORD_RESET_TOKEN;
    }
    public function removePassResetToken($token) {

        $builder = $this->db->table($this->table);
        $builder->set("PASSWORD_RESET_TOKEN", null);
        $builder->where("PASSWORD_RESET_TOKEN", $token);
        $builder->update();
    }
    public function can_login($username, $password) {
        $passHash = $this->getUserPassHash($username);
        return (password_verify($password, $passHash)) ? true : false;
    }
    public function changePasswordByAdmin($id, $password) {

        $hashedpass = password_hash((string) $password, PASSWORD_DEFAULT);
        $builder = $this->db->table($this->table);
        $builder->set("PASSWORD", $hashedpass);
        $builder->where("ID", $id);
        $builder->update();
        return true;
    }
    public function resetpassword($token, $newhash) {

        $builder = $this->db->table($this->table);
        $builder->set("PASSWORD", $newhash);
        $builder->where("PASSWORD_RESET_TOKEN", $token);
        $builder->update();
        $this->removePassResetToken($token);
        return true;
    }

    public function isAdminExist() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('ROLE', 1);
        $query = $builder->countAllResults();
        return ($query > 0) ? 1 : 0;
    }
    public function getAllEmployees() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('ROLE', 0);
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function getAllEmployeesCount() {

        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('ROLE', 0);
        $count = $builder->countAllResults();
        return $count;
    }
    public function getAllEmployeesWithSalaryandDepartment() {

        $builder = $this->db->table($this->table);
        $builder->select('users_table.*, salary_table.BASIC_SALARY, department_table.NAME as DEPARTMENT_NAME');
        $builder->join('salary_table', 'users_table.ID = salary_table.USER_ID', 'left');
        $builder->join('department_table', 'users_table.DEPARTMENT_ID = department_table.ID', 'left');
        $builder->where('users_table.ROLE', '0');
        $builder->orderBy('users_table.ID', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }
    public function setUserDeactivate($id) {

        if (!($this->isUserExistByID($id))) {
            return 0;
        }

        $builder = $this->db->table($this->table);
        $builder->where('ID', $id);
        $builder->set('DEACTIVATE', 1);
        $builder->update();
        return 1;
    }
    public function unsetUserDeactivate($id) {

        if (!($this->isUserExistByID($id))) {
            return 0;
        }

        $builder = $this->db->table($this->table);
        $builder->where('ID', $id);
        $builder->set('DEACTIVATE', 0);
        $builder->update();
        return 1;
    }
    public function setColumnbyID($ID, $COLUMN, $VALUE) {
        $builder = $this->db->table($this->table);
        $builder->where(["ID" => $ID]);
        $builder->set($COLUMN, $VALUE);
        $builder->update();
        return $ID;
    }
}
