<?php

namespace App\Controllers;


use App\Models\User;
use App\Models\Salary;
use App\Models\LeaveCredit;
use Config\Services;

class RegisterController extends BaseController {
    protected $helpers = ['form'];
    public $data;
    public function __construct() {
        $this->session = Services::session();
    }
    protected function render_page($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        return
            view('layout/header', $this->data) .
            view($view, $this->data) .
            view('layout/footer', $this->data);
    }
    public function index() {
        $this->data['noindex'] = 1;
        if ($this->AuthCheckAdmin()) {
            return redirect()->to('dashboard');
        } else {
            $UserModel = new User();
            $isAdminExist = $UserModel->isAdminExist();
            if ($isAdminExist) {
                return redirect()->to('/login');
            } else {
                return $this->render_page('dashboard/admin/admin-signup', $this->data);
            
            }
        }
    }
    
    public function SignupValidation() {

        $UserModel = new User();
        $LeaveCreditModel = new LeaveCredit();
        $queryData = [
            "NAME" => $this->request->getPost('name'),
            "EMAIL" => $this->request->getPost('email'),
            "CONTACT" => $this->request->getPost('contact'),
        ];
        $password = $this->request->getPost('password');
        $cpassword = $this->request->getPost('cpassword');

        // check input valid or not
        $rules = [
            'name' => [
                'rules' => 'required|regex_match[/^[A-Za-z\s]*$/]',
                'errors' => [
                    'required' => 'Name is required.',
                    'regex_match' => 'Name must only be Alpabetic'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Email is not valid.',
                ]
            ],
            'password' => [
                'rules' => 'trim|required|min_length[8]',
                'errors' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be 8 characters long.',
                ]
            ],
            'contact' => [
                'rules' => 'required|min_length[10]|numeric',
                'errors' => [
                    'required' => 'Contact is required.',
                    'min_length' => 'Contact must be atleast 10 characters long.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'auth.signup');
        }

        if ($cpassword != $password) {
            return $this->RedirectWithtoast('Password is not matching', 'danger', 'auth.signup');
        }
        // check user exist
        $userExist = $UserModel->isUserExist($queryData['EMAIL']);
        if ($userExist) {
            return $this->RedirectWithtoast('Employee Already Exist', 'warning', '/login');
        }
        $queryData['PASSWORD'] = password_hash((string) $password, PASSWORD_DEFAULT);
        $UserModel->insertUser($queryData);

        $user_id = $UserModel->getUserID($queryData['EMAIL']);
        $LeaveCreditData = [
            'USER_ID' => $user_id,
        ];
        $LeaveCreditModel->insertLeaveCredit($LeaveCreditData);

        return $this->RedirectWithtoast('Employee Registered', 'success', '/login');
    }
    
    public function SignupValidation_admin() {

        $UserModel = new User();
        $LeaveCreditModel = new LeaveCredit();
        $queryData = [
            "NAME" => $this->request->getPost('name'),
            "EMAIL" => $this->request->getPost('email'),
            "CONTACT" => $this->request->getPost('contact'),
            "ROLE" => $this->request->getPost('role'),
        ];
        $password = $this->request->getPost('password');
        $cpassword = $this->request->getPost('cpassword');

        // check input valid or not
        $rules = [
            'name' => [
                'rules' => 'required|regex_match[/^[A-Za-z\s]*$/]',
                'errors' => [
                    'required' => 'Name is required.',
                    'regex_match' => 'Name must only be Alpabetic'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Email is not valid.',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be 8 characters long.',
                ]
            ],
            'contact' => [
                'rules' => 'required|min_length[10]|numeric',
                'errors' => [
                    'required' => 'Contact is required.',
                    'min_length' => 'Contact must be atleast 10 characters long.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'auth.signup');
        }

        if ($cpassword != $password) {
            return $this->RedirectWithtoast('Password is not matching', 'danger', 'auth.signup');
        }
        // check user exist
        $userExist = $UserModel->isUserExist($queryData['EMAIL']);

        if ($userExist) {
            return $this->RedirectWithtoast('Email Already Exist', 'warning', 'auth.login');
        }
        $queryData['PASSWORD'] = password_hash((string) $password, PASSWORD_DEFAULT);
        $UserModel->insertUser($queryData);

        $user_id = $UserModel->getUserID($queryData['EMAIL']);
        $LeaveCreditData = [
            'USER_ID' => $user_id,
        ];
        $LeaveCreditModel->insertLeaveCredit($LeaveCreditData);

        return $this->RedirectWithtoast('Admin Registered', 'success', 'auth.login');
    }

    public function addUserByAdmin() {

        $UserModel = new User();
        $SalaryModel = new Salary();
        $LeaveCreditModel = new LeaveCredit();
        $queryData = [
            "NAME" => $this->request->getPost('name'),
            "EMAIL" => $this->request->getPost('email'),
            "CONTACT" => $this->request->getPost('contact'),
            "DESIGNATION" => $this->request->getPost('designation'),
            "DEPARTMENT_ID" => $this->request->getPost('department'),
            "BIOMETRIC_ID" => $this->request->getPost('biometric'),

        ];
        // check input valid or not
        $rules = [
            'name' => [
                'rules' => 'required|regex_match[/^[A-Za-z\s]*$/]',
                'errors' => [
                    'required' => 'Name is required.',
                    'regex_match'=> 'Name must only be Alpabetic'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users_table.EMAIL]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Enter a valid email address.',
                    'is_unique' => 'Email already exists.',
                ]
            ],
            'contact' => [
                'rules' => 'required|numeric|min_length[10]|is_unique[users_table.CONTACT]',
                'errors' => [
                    'required' => 'Contact number is required.',
                    'min_length' => 'Contact must be at least 10 digits.',
                    'is_unique' => 'Contact already exists.',
                ]
            ],
            'department' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Department is required.',
                    'numeric' => 'Invalid department ID.',
                ]
            ],
            'designation' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Designation is required.',
                ]
            ],
            'biometric' => [
                'rules' => 'required|alpha_numeric|is_unique[users_table.BIOMETRIC_ID]',
                'errors' => [
                    'required' => 'Biometric ID is required.',
                    'alpha_numeric' => 'Biometric ID must be alphanumeric.',
                    'is_unique' => 'Biometric ID already exists.',
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

        // check user exist

        $userExist = $UserModel->isUserExist($queryData['EMAIL']);

        if ($userExist) {
            return $this->RedirectWithtoast('Employee Already Exist', 'warning', 'employee.list');
        }

        $UserModel->insertUser($queryData);

        $user_id = $UserModel->getUserID($queryData['EMAIL']);

        $LeaveCreditData = [
            'USER_ID' => $user_id,
        ];

        $SalaryData = [
            'USER_ID' => $user_id,
            'BASIC_SALARY' => $this->request->getPost('salary')
        ];

        $LeaveCreditModel->insertLeaveCredit($LeaveCreditData);
        $SalaryModel->insertSalary($SalaryData);

        return $this->RedirectWithtoast('Employee Registered', 'success', 'employee.list');
    }
}
