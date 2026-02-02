<?php

namespace App\Controllers;

use App\Models\ReportModel;

class ReportController extends BaseController
{
    /**
     * GET /reports/vendor-outstanding
     */
    public function vendorOutstanding()
    {
        // Token check
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $reportModel = new ReportModel();
        $data = $reportModel->getVendorOutstanding();

        return $this->response->setJSON($data);
    }
}
