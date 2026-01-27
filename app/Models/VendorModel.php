<<<<<<< HEAD
<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table      = 'vendors';
    protected $primaryKey = 'vendor_id';

    protected $allowedFields = [
        'vendor_name',
        'contact_person',
        'phone',
        'email'
    ];
}
=======
<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table      = 'vendors';
    protected $primaryKey = 'vendor_id';

    protected $allowedFields = [
        'vendor_name',
        'contact_person',
        'phone',
        'email'
    ];
}
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
