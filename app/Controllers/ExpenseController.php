<?php

namespace App\Controllers;

use App\Models\ExpenseModel;
use App\Models\LedgerModel;

class ExpenseController extends BaseController
{
    /**
     * CREATE: Insert new expense
     * URL: POST /expenses/create
     * Roles: Admin, Accountant
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

        // 3️⃣ Read JSON body ONLY
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Invalid JSON request'
            ]);
        }

        // 4️⃣ Extract values SAFELY
        $vendorId    = $data['vendor_id'] ?? null;
        $amount      = $data['amount'] ?? null;
        $category    = $data['category'] ?? null;
        $expenseDate = $data['expense_date'] ?? null;
        $description = $data['description'] ?? null;

        // 5️⃣ Validation
        if (!$vendorId || !$amount || !$expenseDate) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Invalid data'
            ]);
        }

        // 6️⃣ Insert into expenses table
        $expenseModel = new ExpenseModel();
        $expenseInserted = $expenseModel->insert([
            'vendor_id'    => $vendorId,
            'amount'       => $amount,
            'category'     => $category,
            'expense_date' => $expenseDate,
            'description'  => $description
        ]);

        if (!$expenseInserted) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'errors'  => $expenseModel->errors()
            ]);
        }

        $expenseId = $expenseModel->getInsertID();

        // 7️⃣ Insert ledger entry
        $ledgerModel = new LedgerModel();
        $ledgerInserted = $ledgerModel->insert([
            'vendor_id'      => $vendorId,
            'reference_type' => 'Expense',
            'reference_id'   => $expenseId,
            'debit'          => $amount,
            'credit'         => 0,
            'entry_date'     => $expenseDate
        ]);

        if (!$ledgerInserted) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Ledger insert failed'
            ]);
        }

        // 8️⃣ Success
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Expense recorded and ledger updated'
        ]);
    }
}
