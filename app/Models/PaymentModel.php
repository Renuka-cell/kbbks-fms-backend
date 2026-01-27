<<<<<<< HEAD
<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table      = 'payments';
    protected $primaryKey = 'payment_id';

    protected $allowedFields = [
        'bill_id',
        'payment_date',
        'amount_paid',
        'payment_mode',
        'instrument_file'
    ];
}
=======
<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table      = 'payments';
    protected $primaryKey = 'payment_id';

    protected $allowedFields = [
        'bill_id',
        'payment_date',
        'amount_paid',
        'payment_mode',
        'instrument_file'
    ];
}
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
