<?php

namespace App\Controllers;


use App\Models\Salary;
use Config\Services;

class SalaryController extends BaseController {
    public $SalaryModel;
    public $data = [];

    public function __construct() {
        $this->session = Services::session();
        $this->SalaryModel = new Salary();
    }

    public function setSalaryProcess() {

        $id = $this->request->getPost("id");
        $salary = (float)$this->request->getPost("salary");
        $salaryData = [
            'USER_ID' => $id,
            'BASIC_SALARY' => $salary
        ];
        // check input valid or not
        $rules = $this->salaryValidationRules();

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'employee.list');
        }

        $this->SalaryModel->insertSalary($salaryData);
        return $this->RedirectWithtoast('Employee Salary Updated', 'info', 'employee.list');
    }
    
    public function updateSalaryProcess() {

        $id = $this->request->getPost("id");
        $salary = (float)$this->request->getPost("salary");

        if (!($this->SalaryModel->isSalaryExistByUserID($id))) {
            return $this->RedirectWithtoast('Salary Doesnt Exist', 'danger', 'employee.list');
        }
        // check input valid or not
        $rules =  $this->salaryValidationRules();

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'employee.list');
        }
        $this->SalaryModel->updateSalary($id, $salary);
        return $this->RedirectWithtoast('Employee Salary Updated', 'info', 'employee.list');
    }


    private function salaryValidationRules(): array {
        return [
                'id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'ID is required.',
                    ]
                ],
                'salary' => [
                    'rules' => 'required|numeric|greater_than_equal_to[0]',
                    'errors' => [
                        'required' => 'Salary is required.',
                        'numeric' => 'Salary must be a number.',
                        'greater_than_equal_to' => 'Salary must be non-negative.',
                    ]
                ]
            ];
    }
}
