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
        return $this->db->query("
            SELECT 
                v.vendor_name,
                IFNULL(SUM(b.bill_amount), 0) AS total_bill,
                IFNULL(SUM(p.amount_paid), 0) AS paid_amount,
                (IFNULL(SUM(b.bill_amount), 0) - IFNULL(SUM(p.amount_paid), 0)) AS outstanding_amount
            FROM vendors v
            LEFT JOIN bills b ON b.vendor_id = v.vendor_id
            LEFT JOIN payments p ON p.bill_id = b.bill_id
            GROUP BY v.vendor_id
        ")->getResultArray();
    }

    /**
     * Monthly Expense Report
     */
    public function getMonthlyExpense()
    {
        return $this->db->table('expenses')
            ->select("DATE_FORMAT(expense_date, '%Y-%m') AS month, SUM(amount) AS total_expense")
            ->groupBy("DATE_FORMAT(expense_date, '%Y-%m')")
            ->orderBy("month", "DESC")
            ->get()
            ->getResultArray();
    }

    /**
     * Income vs Expense Report (optional)
     */
    public function getIncomeExpense()
    {
        return $this->db->query("
            SELECT 'Income' AS type, SUM(amount) AS total FROM invoices
            UNION ALL
            SELECT 'Expense' AS type, SUM(amount) AS total FROM expenses
        ")->getResultArray();
    }
}
