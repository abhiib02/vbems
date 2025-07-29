<?php

namespace App\Controllers;


use App\Models\Department;
use Config\Services;

class DepartmentController extends BaseController
{   
    public $DepartmentModel;
    public $data = [];
    public function __construct(){
        $this->session = Services::session();
        $this->DepartmentModel = new Department();

    }
    public function AddDepartmentProcess(){

        $departmentData = [
            'NAME'=>$this->request->getPost("department_name"),
            'LEAVE_PERSON_COUNT'=>$this->request->getPost("leave_person_count"),
        ];

        // check input valid or not
        $rules = [
            'department_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Department Name is required.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'departments.list');
        }
        
        $this->DepartmentModel->insertDepartment($departmentData);
        return $this->RedirectWithtoast('Department Added', 'info', 'departments.list');
        

    }
    public function updateDepartmentProcess(){

        $id = $this->request->getPost("id");
        if (!($this->DepartmentModel->isDepartmentExistID($id))) {
            return $this->RedirectWithtoast('Department Doesnt Exist', 'danger', 'departments.list');
        }

        $departmentData = [
            'NAME'=>$this->request->getPost("department_name"),
            'LEAVE_PERSON_COUNT'=>$this->request->getPost("leave_person_count"),
        ];
        // check input valid or not
        $rules = [
            'department_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Department Name is required.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'departments.list');
            
        }
        
        $this->DepartmentModel->updateDepartment($id,$departmentData);
        return $this->RedirectWithtoast('Department Updated', 'info', 'departments.list');
        

    }
    public function deleteDepartmentProcess($id)
    {   
        if (!($this->DepartmentModel->isDepartmentExistID($id))) {
            return $this->RedirectWithtoast('Department Doesnt Exist', 'danger', 'departments.list');
        }

        $this->DepartmentModel->deleteDepartment($id);
        return $this->RedirectWithtoast('Department Deleted', 'danger', 'departments.list');
    }
}