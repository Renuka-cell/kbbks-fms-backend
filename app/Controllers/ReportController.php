<?php

namespace App\Controllers;

use App\Models\ReportModel;

class ReportController extends BaseController
{
    /**
     * GET /reports/vendor-outstanding
     * Roles: Admin, Accountant, Viewer
     */
    public function vendorOutstanding()
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized'
            ]);
        }

        $reportModel = new ReportModel();
        $data = $reportModel->getVendorOutstanding();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * GET /reports/monthly-expense
     * Roles: Admin, Accountant, Viewer
     */
    public function monthlyExpense()
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized'
            ]);
        }

        $reportModel = new ReportModel();
        $data = $reportModel->getMonthlyExpense();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * GET /reports/income-expense
     * Roles: Admin, Accountant, Viewer
     */
    public function incomeExpense()
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized'
            ]);
        }

        $reportModel = new ReportModel();
        $data = $reportModel->getIncomeExpense();

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * GET /reports/vendor-summary/{vendor_id}
     * Roles: Admin, Accountant ONLY
     */
    public function vendorSummary($vendor_id)
    {
        // 🔐 Token check
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized'
            ]);
        }

        // 🔐 Role check
        if (!$this->checkRole(['Admin', 'Accountant'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Only Admin and Accountant can access vendor summary'
            ]);
        }

        // ✅ Get optional filters safely
        $year  = $this->request->getGet('year') ?? null;
        $month = $this->request->getGet('month') ?? null;

        $reportModel = new ReportModel();

        // ✅ Backward compatible call
        $data = $reportModel->getVendorSummary($vendor_id, $year, $month);

        if ($data === null) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Vendor not found'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }
}
