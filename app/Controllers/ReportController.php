<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

class ReportController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * REPORT 1: Monthly Expense Report
     * URL: GET /reports/monthly-expense
     */
    public function monthlyExpense()
    {
        if (!$this->checkToken()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        // Example: current month expenses
        $query = $this->db->query("
            SELECT 
                MONTHNAME(expense_date) AS month,
                YEAR(expense_date) AS year,
                SUM(amount) AS total_expense
            FROM expenses
            GROUP BY YEAR(expense_date), MONTH(expense_date)
            ORDER BY year DESC, MONTH(expense_date) DESC
            LIMIT 1
        ");

        $result = $query->getRow();

        return $this->response->setJSON($result);
    }

    /**
     * REPORT 2: Vendor Outstanding Report
     * URL: GET /reports/vendor-outstanding
     */
    public function vendorOutstanding()
    {
        if (!$this->checkToken()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $query = $this->db->query("
            SELECT 
                v.vendor_name,
                (SUM(b.bill_amount) - IFNULL(SUM(p.amount_paid), 0)) AS outstanding_amount
            FROM vendors v
            LEFT JOIN bills b ON v.vendor_id = b.vendor_id
            LEFT JOIN payments p ON b.bill_id = p.bill_id
            GROUP BY v.vendor_id
            HAVING outstanding_amount > 0
        ");

        return $this->response->setJSON($query->getResult());
    }

    /**
     * REPORT 3: Income vs Expense Summary
     * URL: GET /reports/income-expense
     */
    public function incomeExpense()
    {
        if (!$this->checkToken()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $incomeQuery = $this->db->query("
            SELECT IFNULL(SUM(amount), 0) AS total_income FROM invoices
        ");

        $expenseQuery = $this->db->query("
            SELECT IFNULL(SUM(amount), 0) AS total_expense FROM expenses
        ");

        return $this->response->setJSON([
            'total_income'  => $incomeQuery->getRow()->total_income,
            'total_expense' => $expenseQuery->getRow()->total_expense
        ]);
    }
}
