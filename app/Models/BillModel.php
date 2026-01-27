<<<<<<< HEAD
<?php

namespace App\Models;

use CodeIgniter\Model;

class BillModel extends Model
{
    protected $table      = 'bills';
    protected $primaryKey = 'bill_id';

    protected $allowedFields = [
        'vendor_id',
        'bill_number',
        'bill_date',
        'bill_amount',
        'bill_file',
        'status'
    ];
}
=======
<?php

namespace App\Models;

use CodeIgniter\Model;

class BillModel extends Model
{
    protected $table      = 'bills';
    protected $primaryKey = 'bill_id';

    protected $allowedFields = [
        'vendor_id',
        'bill_number',
        'bill_date',
        'bill_amount',
        'bill_file',
        'status'
    ];
}
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
