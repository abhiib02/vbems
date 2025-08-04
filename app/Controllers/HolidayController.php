<?php

namespace App\Controllers;

use App\Models\Holiday;

use Config\Services;

class HolidayController extends BaseController {
    public $HolidayModel;

    public function __construct() {
        $this->session = Services::session();
        $this->HolidayModel = new Holiday();
    }

    public function addHolidayProcess() {
        $CurrentDate = new \DateTime();

        $HolidayData = [
            "DATE" => $this->request->getPost('date'),
            "HOLIDAY" => $this->request->getPost('holiday'),
        ];
        // check input valid or not
        $rules = [
            'date' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Date is required.',
                ]
            ],
            'holiday' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Holiday Name is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'holidays.list');
        }
        $CurrentYear = $CurrentDate->format("Y");
        $InputDate = new \DateTime($HolidayData["DATE"]);
        $InputYear = $InputDate->format("Y");

        if ($CurrentYear != $InputYear) {
            return $this->RedirectWithtoast("Year Must be $CurrentYear | Only current year holidays are permitted", 'danger', 'holidays.list');
        }
        $isHolidayExist = $this->HolidayModel->isHolidayExist($HolidayData['DATE']);
        if ($isHolidayExist) {
            return $this->RedirectWithtoast('Holiday Already Exist', 'danger', 'holidays.list');
        }
        $this->HolidayModel->insertHoliday($HolidayData);
        return $this->RedirectWithtoast('Holiday Added', 'success', 'holidays.list');
    }

    public function updateHolidayProcess() {

        $id = $this->request->getPost('id');
        if (!($this->HolidayModel->isHolidayExistID($id))) {
            return $this->RedirectWithtoast('Holiday Doesnt Exist', 'danger', 'holidays.list');
        }

        $HolidayData = [
            "DATE" => $this->request->getPost('date'),
            "HOLIDAY" => $this->request->getPost('holiday'),
        ];

        $rules = [
            'date' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Date is required.',
                ]
            ],
            'holiday' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Holiday Name is required.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $response =  [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            $firstError = reset($response['message']);
            return $this->RedirectWithtoast($firstError, 'warning', 'holidays.list');
        }

        $this->HolidayModel->updateHoliday($id, $HolidayData);
        return $this->RedirectWithtoast('Holiday Updated', 'info', 'holidays.list');
    }

    public function deleteHolidayProcess($id) {
        if (!($this->HolidayModel->isHolidayExistID($id))) {
            return $this->RedirectWithtoast('Holiday Doesnt Exist', 'danger', 'holidays.list');
        }
        $this->HolidayModel->deleteHoliday($id);
        return $this->RedirectWithtoast('Holiday Deleted', 'danger', 'holidays.list');
    }
}
