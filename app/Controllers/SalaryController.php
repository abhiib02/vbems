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
        $salary = $this->request->getPost("salary");
        $salaryData = [
            'USER_ID' => $id,
            'BASIC_SALARY' => $salary
        ];
        // check input valid or not
        $rules = [
            'id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID is required.',
                ]
            ],
            'salary' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Salary is required.',
                ]
            ]
        ];

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
        $salary = $this->request->getPost("salary");

        if (!($this->SalaryModel->isSalaryExistByUserID($id))) {
            return $this->RedirectWithtoast('Salary Doesnt Exist', 'danger', 'employee.list');
        }
        // check input valid or not
        $rules = [
            'id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID is required.',
                ]
            ],
            'salary' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Salary is required.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'employee.list');
        }
        $this->SalaryModel->setSalarybyID($id, $salary);
        return $this->RedirectWithtoast('Employee Salary Updated', 'info', 'employee.list');
    }
}
