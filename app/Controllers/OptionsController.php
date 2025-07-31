<?php

namespace App\Controllers;


use App\Models\Option;
use Config\Services;

class OptionsController extends BaseController
{
    public $data, $OptionModel;
    public function __construct() {
        $this->OptionModel = new Option();
    }
        
    public function addOptionProcess(){
        $optionData = [
            'NAME' => $this->request->getPost("option_name"),
            'VALUE' => $this->request->getPost("option_value"),
        ];

        // check input valid or not
        $rules = [
            'option_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Option Name is required.',
                ]
                ],
            'option_value' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Option Value is required.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'options.list');
        }

        $this->OptionModel->insertOption($optionData);
        return $this->RedirectWithtoast('Option Added', 'info', 'options.list');
    }

    public function save($name)
    {
        $value = $this->request->getPost($name);
        $Option = new Option();
        $Option->saveOption($name, $value);
        return $this->RedirectWithtoast('Option Saved', 'Success', 'options.list');
        
    }
}
