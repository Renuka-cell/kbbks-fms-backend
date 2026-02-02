<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    /**
     * Vendor Outstanding Report
     */
    public function getVendorOutstanding()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('vendors v');

        $builder->select("
            v.vendor_name,
            IFNULL(SUM(b.bill_amount), 0) AS total_bill,
            IFNULL(SUM(p.amount_paid), 0) AS paid_amount,
            (IFNULL(SUM(b.bill_amount), 0) - IFNULL(SUM(p.amount_paid), 0)) AS outstanding_amount
        ");

        $builder->join('bills b', 'b.vendor_id = v.vendor_id', 'left');
        $builder->join('payments p', 'p.bill_id = b.bill_id', 'left');

        $builder->groupBy('v.vendor_id');

        return $builder->get()->getResultArray();
    }
}
