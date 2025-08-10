<?php

namespace App\Controllers;

use App\Controllers\EmailController;
use App\Models\User;
use Config\Services;

class UserController extends BaseController {

    protected $helpers = ['form'];
    public $UserModel;
    protected $EmailController;
    public $data = [];
    public $TOKEN_EXPIRATION_MINUTES = 5;
    public function __construct() {
        $this->session = Services::session();
        $this->UserModel = new User();
        $this->EmailController = new EmailController();
    }
    protected function render_page($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        return
            view('layout/header', $this->data) .
            view($view, $this->data) .
            view('layout/footer', $this->data);
    }
    public function index() {
        return redirect()->to('/login');
    }

    public function deleteEmp() {
        $this->UserModel->deleteUser(3);
        return $this->RedirectWithtoast('deleted Employee', 'danger', 'employee.list');
    }

    public function forgotpass() {
        return $this->render_page('dashboard/forgot-password', $this->data);
    }

    public function generateHashToken() {
        //$hashToken =  hash('sha256', $email . date('his'));
        $hashToken = bin2hex(random_bytes(32));
        return $hashToken;
    }

    public function forgotpassword() {

        $email = $this->request->getPost("email");
        $userExist = $this->UserModel->isUserExist($email);
        if (!$userExist) {
            //return $this->RedirectWithtoast('User Does not Exist', 'danger', 'user.forgot.password.form');
            //This avoids user enumeration attacks.
            return $this->RedirectWithtoast('Reset Password Link Sent (if user exists)', 'info', 'auth.login');
        }
        $generatedToken = $this->generateHashToken();
        $this->UserModel->insertPasswordResetTokenToEmail($email, $generatedToken);
        $this->EmailController->reset_password_mail($email, $generatedToken);
        return $this->RedirectWithtoast('Reset Password Link Sent to Mail  (if user exists)', 'info', 'auth.login');
    }

    public function resetform() {
        $this->data['noindex'] = 1;

        $token = $this->request->getVar('token');
        $tokenExist = $this->UserModel->isTokenExist($token);
        $minutesPassed = $this->tokenExpirationCheck($this->UserModel->getUserUpdatedAtforToken($token));

        if (!$tokenExist) {
            return $this->RedirectWithtoast('Invalid Link', 'danger', 'auth.login');
        }
        if ($minutesPassed > $this->TOKEN_EXPIRATION_MINUTES) {
            return $this->RedirectWithtoast('Token Expired', 'danger', 'user.forgot.password.form');
        }
        $this->data["token"] = $token;
        return $this->render_page('dashboard/reset-password', $this->data);
    }

    public function reset() {

        $password = $this->request->getPost("password");
        $token = $this->request->getPost("token");
        $rules = $this->validate([
            'password' => 'required|min_length[8]',
        ]);
        if (!$rules) {
            return $this->RedirectWithtoast('Password Must be 8 Characters long', 'Danger', '/resetform?token=' . $token);
        }
        $newhash = password_hash((string) $password, PASSWORD_DEFAULT);
        $this->UserModel->resetpasswordByToken($token, $newhash);
        return $this->RedirectWithtoast('Password Reset Successfully', 'success', 'auth.login');
    }

    public function deactivateUserProcess() {
        $id = $this->request->getPost("id");
        $set = $this->request->getPost("set");
        if ($set) {
            $this->UserModel->setUserDeactivate($id);
            return $this->RedirectWithtoast('Employee Deactivated', 'Danger', 'employee.list');
        } else {
            $this->UserModel->unsetUserDeactivate($id);
            return $this->RedirectWithtoast('Employee Activated', 'Success', 'employee.list');
        }
    }

    public function updateProfileProcess() {

        $id = $this->request->getPost("id");
        if (!($this->UserModel->isUserExistByID($id))) {
            return $this->RedirectWithtoast('Employee Doesnt Exist', 'danger', 'departments.list');
        }

        $profileData = [
            'NAME' => $this->request->getPost("name"),
            'EMAIL' => $this->request->getPost("email"),
            'CONTACT' => $this->request->getPost("contact"),
            'BIOMETRIC_ID' => $this->request->getPost("biometric"),
            'DESIGNATION' => $this->request->getPost("designation"),
            'DEPARTMENT_ID' => $this->request->getPost("department"),
        ];

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
                    'valid_email' => 'Enter a valid email address.',
                ]
            ],
            'contact' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Contact number is required.',
                    'min_length' => 'Contact must be at least 10 digits.',
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
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'Biometric ID is required.',
                    'alpha_numeric' => 'Biometric ID must be alphanumeric.',
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

        $this->UserModel->updateUser($id, $profileData);

        return $this->RedirectWithtoast('Profile Updated', 'info', 'employee.list');
    }
    private function tokenExpirationCheck($datetime) {
        $end = new \DateTime($datetime);
        $start   = new \DateTime(date('Y-m-d h:i:s'));
        $interval = $start->diff($end);
        $minutesPassed = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i + ($interval->s / 60);;
        return (int)$minutesPassed;
    }
    //////////////////////////////////
    //////////////////////////////
    /////////////////////////////
    ////////////////////////////
    /// UNUSED FUNCTIONS /////


    /*
    public function makeAdmin() {
        $id = $this->request->getPost('id');
        $this->UserModel->MakeAdmin($id);
        return $this->RedirectWithtoast('UserID : ' . $id . ' Admin Granted', 'info', '/users');
    }

    public function removeAdmin() {
        $id = $this->request->getPost('id');
        if (in_array($id, $this->data['permanentAdmins'])) {
            return $this->RedirectWithtoast('Cant Revoke Admin of this user', 'danger', '/users');
        }
        $this->UserModel->RemoveAdmin($id);
        return $this->RedirectWithtoast('UserID : ' . $id . ' Admin Permission Revoked', 'Warning', '/users');
    }

    public function changePassword() {
        $id = $this->request->getPost('id');
        $password = $this->request->getPost('password');
        $this->UserModel->changePasswordByAdmin($id, $password);
        return $this->RedirectWithtoast('UserID:' . $id . ' Password Changed', 'Success', '/users');
    }
    */

    /*
    public function deleteUser() {
        $id = $this->request->getPost('id');
        if (!($this->UserModel->isUserExistByID($id))) {
            return $this->RedirectWithtoast('User Doesnt Exist', 'danger', 'auth.users');
        }
        if (in_array($id, $this->data['permanentAdmins'])) {
            return $this->RedirectWithtoast('Cannot Delete This User', 'danger', 'auth.users');
        }
        $this->UserModel->delUser($id);
        return $this->RedirectWithtoast('User Deleted', 'danger', '/users');
    }
    */

    ///---------------- this system functions -----------------------//
    /*
    public function updateDesignationProcess() {
        $id = $this->request->getPost("id");
        $designation = $this->request->getPost("designation");
        
        // check input valid or not
        $rules = [
            'id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID is required.',
                ]
            ],
            'designation' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Designation is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'employee.list');
        }

        $this->UserModel->setColumnbyID($id, 'DESIGNATION', $designation);
        return $this->RedirectWithtoast('Employee Designation Updated', 'info', 'employee.list');
    }
    

    public function updateEmployeeDepartmentProcess() {
        $id = $this->request->getPost("id");
        $department = $this->request->getPost("department");

        if (!($this->UserModel->isUserExistByID($id))) {
            return $this->RedirectWithtoast('Employee Doesnt Exist', 'danger', 'departments.list');
        }

        // check input valid or not
        $rules = [
            'id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID is required.',
                ]
            ],
            'department' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Department is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'employee.list');
        }

        $this->UserModel->setColumnbyID($id, 'DEPARTMENT_ID', $department);
        return $this->RedirectWithtoast('Employee Department Updated', 'info', 'employee.list');
    }
        */
    ///---------------- this system functions -----------------------//
}
