<?php

namespace App\Controllers;

use App\Controllers\AttendanceController;
use Config\Services;
use App\Models\User;

class LoginController extends BaseController {
    protected $helpers = ['form'];
    public $session;
    public $UserModel;
    public function __construct() {
        $this->session = Services::session();
        $this->UserModel = new User();
    }

    public function index() {
        $data['noindex'] = 1;

        if ($this->AuthCheckAdmin()) {
            return redirect()->to('dashboard');
        } elseif ($this->AuthCheck()) {
            return redirect()->to('account');
        } else {
            return view('layout/header', $data) . view('dashboard/login') . view('layout/footer');
        }
    }

    public function loginValidation() {

        $AttendanceController = new AttendanceController();
        $queryData = [
            "EMAIL" => $this->request->getPost('email'),
            "PASSWORD" => $this->request->getPost('password'),
        ];
        $email = $queryData['EMAIL'];
        $password = $queryData['PASSWORD'];
        $userExist = $this->UserModel->isUserExist($queryData['EMAIL']);
        $isUserDeactivated = $this->UserModel->isUserDeactivated($queryData['EMAIL']);


        // check input valid or not
        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Email is not valid.',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'auth.login');
        }

        // check user exist
        if (!$userExist) {
            return $this->RedirectWithtoast('User Does not Exist', 'danger', 'auth.login');
        }
        if ($isUserDeactivated) {
            return $this->RedirectWithtoast('User Deactived', 'danger', 'auth.login');
        }

        $LoginStatus = $this->UserModel->can_login($email, $password);

        if (!($LoginStatus)) {
            return $this->RedirectWithtoast('Incorrect Email or Password', 'danger', 'auth.login');
        }

        $isAdmin = $this->UserModel->isAdmin($email);

        if (!$isAdmin) {
            $ID = $this->UserModel->getUserIDByEmail($email);
            $AttendanceController->attendanceEntryProcessWhileLogin($ID);
        }
        $isAdmin = $isAdmin ? true : false;
        $LoggedInData = [
            'id' => $this->UserModel->getUserID($email),
            'name' => $this->UserModel->getUserName($queryData['EMAIL']),
            'email' => $queryData['EMAIL'],
            'logged_in' => true,  
            'role' => $isAdmin,
        ];

        $this->session->set($LoggedInData);
        return ($this->AuthCheckAdmin()) ? redirect()->to(route_to('admin.dashboard')) : redirect()->to(route_to('employee.account'));
    }

    public function logout() {

        if ($this->AuthCheck() || $this->AuthCheckAdmin()) {
            $LoggedInData = ['id', 'name', 'email', 'logged_in', 'role'];
            $this->session->remove($LoggedInData);
            return redirect()->to('login');
        } else {
            return redirect()->to('login');
        }
    }
}
