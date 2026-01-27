<<<<<<< HEAD
<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table      = 'invoices';
    protected $primaryKey = 'invoice_id';

    protected $allowedFields = [
        'invoice_number',
        'invoice_date',
        'amount',
        'received_status'
    ];
}
=======
<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table      = 'invoices';
    protected $primaryKey = 'invoice_id';

    protected $allowedFields = [
        'invoice_number',
        'invoice_date',
        'amount',
        'received_status'
    ];
}
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
