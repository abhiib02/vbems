<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Salary;

class User extends Model {
    public $table = 'users_table';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['NAME', 'BIOMETRIC_ID', 'EMAIL', 'CONTACT', 'ROLE', 'DESIGNATION', 'DEPARTMENT_ID', 'DEACTIVATE', 'CREATED_ON', 'UPDATED_AT'];
    protected $createdField = 'CREATED_ON';
    protected $updatedField = 'UPDATED_AT';
    protected $salaryModel;

    public function insertUser($data) {
        $this->insert($data);
        return $this->insertID();
    }
    public function updateUser($id, $data){
        return $this->update($id, $data);
    }
    public function getAllUsers() {
        return $this->asObject()->findAll();
    }
    public function getUserID(string $email){
        $user = $this->asObject()->select('ID')->where('EMAIL', $email)->first();
        return $user->ID ?? null;
    }
    public function getUser($email) {
        return $this->asObject()->where('EMAIL', $email)->first();
    }
    public function getUserByID(int $id) {
        return $this->asObject()->find($id);
    }
    public function getUserName($email) {
        $user = $this->findOneByEmail($email);
        return $user->NAME ?? null;
    }
    public function getUserNameByID($ID) {
        return $this->asArray()->find($ID)['NAME'] ?? null;
    }
    public function getUserCreatedDate($ID) {
        $user = $this->select('CREATED_ON')->asArray()->find($ID);
        if (!$user || empty($user['CREATED_ON'])) {
            return null;
        }
        return (new \DateTime($user['CREATED_ON']))->format('Y-m-d');
    }
    public function getUserEmailByID($ID) {
        $user = $this->select('EMAIL')->asArray()->find($ID);
        return $user['EMAIL'] ?? null;
    }
    public function getUserIDByBiometricID(string $biometricID) {
        $user = $this->select('ID')
            ->asArray()
            ->where('BIOMETRIC_ID', $biometricID)
            ->first();
        return $user['ID'] ?? null;
    }
    public function getDepartmentIDByUserID($user_id) {
        $user = $this->select('DEPARTMENT_ID')->asObject()->find($user_id);
        return $user->DEPARTMENT_ID ?? null;
    }
    public function getUserIDByEmail($email) {
        $user = $this->select('ID')->asObject()->where('EMAIL', $email)->first();
        return $user->ID ?? null;
    }
    public function isUserExist($email) {
        return $this->where('EMAIL', $email)->countAllResults() > 0;
    }
    public function isUserDeactivated($email) {
        return $this->where('EMAIL', $email)->where('DEACTIVATE', 1)->countAllResults() > 0;
    }
    public function isUserExistByID($id) {
        return $this->where('ID', $id)->countAllResults() > 0;
    }
    public function isBiometricIDExist($bid) {
        return $this->where('BIOMETRIC_ID', $bid)->countAllResults() > 0;
    }
    public function isDepartmentUsed($department_id) {
        return $this->where('DEPARTMENT_ID', $department_id)->countAllResults() > 0;
    }
    public function delUser($id) {
        $this->delete($id);
        return $this->db->affectedRows() > 0;
    }
    public function isHashExist($hash) {
        return $this->where('PASSWORD', $hash)->first() !== null;
    }
    public function insertPasswordResetTokenToEmail($email, $token) {

        return $this->where('EMAIL', $email)
            ->set('PASSWORD_RESET_TOKEN', $token)
            ->update();
    }
    public function isTokenExist($token) {
        return $this->where('PASSWORD_RESET_TOKEN', $token)->countAllResults() > 0;
    }
    public function isAdmin($email) {
        $user = $this->select('ROLE')->asObject()->where('EMAIL', $email)->first();
        return isset($user->ROLE) && (int)$user->ROLE === 1;
    }
    public function MakeAdmin($id) {
        return $this->update($id, ['ROLE' => 1]);
    }

    public function RemoveAdmin($id) {

        return $this->update($id, ['ROLE' => 0]);
    }
    public function getUserPassHash($email) {

        $user = $this->select('PASSWORD')
            ->asObject()
            ->where('EMAIL', $email)
            ->first();

        return $user->PASSWORD ?? null;
    }
    public function getUserPassResetToken($email) {

        $user = $this->select('PASSWORD_RESET_TOKEN')
            ->asObject()
            ->where('EMAIL', $email)
            ->first();

        return $user->PASSWORD_RESET_TOKEN ?? null;
    }
    public function removePassResetToken($token) {

        return $this->where('PASSWORD_RESET_TOKEN', $token)
            ->set('PASSWORD_RESET_TOKEN', null)
            ->update();
    }
    public function can_login($username, $password) {
        $passHash = $this->getUserPassHash($username);
        return (password_verify($password, $passHash)) ? true : false;
    }
    public function changePasswordByAdmin($id, $password) {

        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        return $this->update($id, ['PASSWORD' => $hashedPass]);
    }
    public function resetpassword($token, $newhash) {

        $updated = $this->where('PASSWORD_RESET_TOKEN', $token)
            ->set('PASSWORD', $newhash)
            ->update();

        if ($updated && $this->db->affectedRows() > 0) {
            $this->removePassResetToken($token);
            return true;
        }
        return false;
    }

    public function isAdminExist() {
        return $this->where('ROLE', 1)->countAllResults() > 0;
    }
    public function getAllEmployees() {

        return $this->asObject()
            ->where('ROLE', 0)
            ->findAll();
    }
    public function getAllEmployeesID() {

        return array_column($this->select('ID')->asArray()->where('ROLE', 0)->findAll(), 'ID');
    }
    public function getAllEmployeesCount() {

        return $this->where('ROLE', 0)->countAllResults();
    }
    public function getAllEmployeesWithSalaryandDepartment() {

        return $this->db->table($this->table)
            ->select("{$this->table}.*, salary_table.BASIC_SALARY, department_table.NAME as DEPARTMENT_NAME")
            ->join('salary_table', "{$this->table}.ID = salary_table.USER_ID", 'left')
            ->join('department_table', "{$this->table}.DEPARTMENT_ID = department_table.ID", 'left')
            ->where("{$this->table}.ROLE", 0)
            ->orderBy("{$this->table}.ID", 'desc')
            ->get()
            ->getResult();
    }
    public function setUserDeactivate($id) {
        $this->update($id, ['DEACTIVATE' => 1]);
        return $this->db->affectedRows() > 0;
    }
    public function unsetUserDeactivate($id) {
        $this->update($id, ['DEACTIVATE' => 0]);
        return $this->db->affectedRows() > 0;
    }
    public function setColumnbyID($ID, $COLUMN, $VALUE) {
        $builder = $this->db->table($this->table);
        $builder->where(["ID" => $ID]);
        $builder->set($COLUMN, $VALUE);
        $builder->update();
        return $ID;
    }
    //------------ Private Functions----------//
    private function findOneByEmail(string $email) {
        return $this->asObject()->where('EMAIL', $email)->first();
    }
}
