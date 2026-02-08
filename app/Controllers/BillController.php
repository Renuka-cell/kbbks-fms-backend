<?php

namespace App\Controllers;

use App\Models\BillModel;
use App\Models\VendorModel;

class BillController extends BaseController
{
    /**
     * CREATE BILL
     * POST /bills/create
     * Access: Admin, Accountant
     */
    public function create()
    {
        // 1️⃣ Auth check
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized'
            ]);
        }

        // 2️⃣ Role check (IMPORTANT FIX)
        if (!$this->checkRole(['Admin', 'Accountant'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Only Admin and Accountant can create bills'
            ]);
        }

        // 3️⃣ Read JSON payload
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Invalid JSON'
            ]);
        }

        // 4️⃣ Required fields validation
        if (
            empty($data['vendor_id']) ||
            empty($data['bill_number']) ||
            empty($data['bill_date']) ||
            empty($data['bill_amount'])
        ) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing required fields'
            ]);
        }

        // 5️⃣ Validate vendor existence
        $vendorModel = new VendorModel();
        if (!$vendorModel->find($data['vendor_id'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Invalid vendor_id'
            ]);
        }

        // 6️⃣ Insert bill
        $billModel = new BillModel();

        if (!$billModel->insert([
            'vendor_id'   => $data['vendor_id'],
            'bill_number' => $data['bill_number'],
            'bill_date'   => $data['bill_date'],
            'bill_amount' => $data['bill_amount'],
            'status'      => 'UNPAID'
        ])) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'errors'  => $billModel->errors()
            ]);
        }

        // 7️⃣ Success response (UNCHANGED)
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Bill created successfully',
            'bill_id' => $billModel->getInsertID()
        ]);
    }
}
