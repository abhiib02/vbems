<?php

namespace App\Controllers;

use App\Models;
use App\Models\Option;
use Config\Services;

class OptionsController extends BaseController
{


    public function save($name)
    {
        $value = $this->request->getPost($name);
        $Option = new Option();
        $Option->saveOption($name, $value);
        $this->session->setFlashdata('FlashMessage', ['message' => 'Option Saved', 'status' => 'Success']);
        return redirect()->to('/options-list');
    }
}
