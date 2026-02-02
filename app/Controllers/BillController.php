<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\VendorModel;

class BillController extends BaseController
{
    /**
     * CREATE BILL
     * POST /bills/create
     */
    public function create()
    {
        // Auth check
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => false,
                'message' => 'Invalid JSON'
            ]);
        }

        // Required fields
        if (
            empty($data['vendor_id']) ||
            empty($data['bill_number']) ||
            empty($data['bill_date']) ||
            empty($data['bill_amount'])
        ) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => false,
                'message' => 'Missing required fields'
            ]);
        }

        // ✅ Validate vendor existence
        $vendorModel = new VendorModel();
        if (!$vendorModel->find($data['vendor_id'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => false,
                'message' => 'Invalid vendor_id'
            ]);
        }

        $billModel = new BillModel();

        // Insert bill
        if (!$billModel->insert([
            'vendor_id'   => $data['vendor_id'],
            'bill_number' => $data['bill_number'],
            'bill_date'   => $data['bill_date'],
            'bill_amount' => $data['bill_amount'],
            'status'      => 'UNPAID'
        ])) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'errors' => $billModel->errors()
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Bill created successfully',
            'bill_id' => $billModel->getInsertID()
        ]);
    }
}
