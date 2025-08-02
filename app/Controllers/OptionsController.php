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
            'TYPE' => (int)$this->request->getPost("option_type"),
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
            'option_type' => [
                'rules' => 'required|min_length[1]|max_length[1]|numeric',
                'errors' => [
                    'required' => 'Option Type is required.',
                    'min_length' => 'Option Type 1 digit is required.',
                    'max_length' => 'Option Type only 1 digit is allowed.',
                    'numeric' => 'Option Type is Must Be Numeric Either 0 or 1.',
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
        $value = ($this->request->getPost($name) === 'on') ? 1 : $this->request->getPost($name);
        $Option = new Option();
        $Option->saveOption($name, $value);
        return $this->RedirectWithtoast($name .' Option Saved', 'Success', 'options.list');
        
    }
}
