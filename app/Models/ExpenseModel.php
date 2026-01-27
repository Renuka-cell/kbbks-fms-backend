<<<<<<< HEAD
<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table      = 'expenses';
    protected $primaryKey = 'expense_id';

    protected $allowedFields = [
        'expense_date',
        'vendor_id',
        'amount',
        'category',
        'description',
        'bill_file'
    ];
}
=======
<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table      = 'expenses';
    protected $primaryKey = 'expense_id';

    protected $allowedFields = [
        'expense_date',
        'vendor_id',
        'amount',
        'category',
        'description',
        'bill_file'
    ];
}
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
