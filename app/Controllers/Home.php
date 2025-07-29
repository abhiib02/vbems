<?php

namespace App\Controllers;

class Home extends BaseController
{
    public $data=[];
    
    public function index()
    {
        if ($this->AuthCheckAdmin()) {
            return redirect()->to('dashboard');
        }
        elseif ($this->AuthCheck()) {
            return redirect()->to('account');
        }
        else{
            return
                view('layout/header', $this->data).
                view('dashboard/login', $this->data).
                view('layout/footer', $this->data);
        }
        
        
    }
}
