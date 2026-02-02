<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\LedgerModel;
use CodeIgniter\Database\Config;

class PaymentController extends BaseController
{
    /**
     * CREATE: Add new payment
     * URL: POST /payments/create
     */
    public function create()
    {
        // 1️⃣ Token check
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized access'
            ]);
        }

        // 2️⃣ Role check
        if (!$this->checkRole(['Admin', 'Accountant'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Access denied'
            ]);
        }

        // 3️⃣ Read JSON body
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Invalid JSON request'
            ]);
        }

        $billId       = $data['bill_id'] ?? null;
        $amountPaid   = $data['amount_paid'] ?? null;
        $paymentDate  = $data['payment_date'] ?? null;
        $paymentMode  = $data['payment_mode'] ?? null;

        if (!$billId || !$amountPaid || !$paymentDate) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Missing required fields'
            ]);
        }

        $db = Config::connect();
        $db->transStart();

        // 4️⃣ Insert payment
        $paymentModel = new PaymentModel();
        $paymentModel->insert([
            'bill_id'       => $billId,
            'amount_paid'   => $amountPaid,
            'payment_date'  => $paymentDate,
            'payment_mode'  => $paymentMode
        ]);

        $paymentId = $paymentModel->getInsertID();

        // 5️⃣ Ledger entry
        $ledgerModel = new LedgerModel();
        $ledgerModel->insert([
            'reference_type' => 'Payment',
            'reference_id'   => $paymentId,
            'credit'         => $amountPaid,
            'debit'          => 0,
            'entry_date'     => $paymentDate
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Payment transaction failed'
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Payment recorded successfully'
        ]);
    }
}
