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
