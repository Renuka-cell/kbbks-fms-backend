<?php

namespace App\Controllers;

use App\Models\VendorModel;

class VendorController extends BaseController
{
    /**
     * READ: Fetch all vendors
     * URL: GET /vendors
     */
    public function index()
    {
        $model = new VendorModel();
        return $this->response->setJSON($model->findAll());
    }

    /**
     * CREATE: Insert new vendor
     * URL: POST /vendors/create
     */
    public function create()
    {
        $model = new VendorModel();

        $data = [
            'vendor_name'     => $this->request->getPost('vendor_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone'           => $this->request->getPost('phone'),
            'email'           => $this->request->getPost('email')
        ];

        if ($model->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Vendor inserted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Insert failed',
                'errors'  => $model->errors()
            ]);
        }
    }

    /**
     * UPDATE: Update existing vendor
     * URL: POST /vendors/update/{id}
     */
    public function update($id)
    {
        $model = new VendorModel();

        $data = [
            'vendor_name'     => $this->request->getPost('vendor_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone'           => $this->request->getPost('phone'),
            'email'           => $this->request->getPost('email')
        ];

        if ($model->update($id, $data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Vendor updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Update failed',
                'errors'  => $model->errors()
            ]);
        }
    }

    /**
     * DELETE: Delete vendor
     * URL: GET /vendors/delete/{id}
     */
    public function delete($id)
    {
        $model = new VendorModel();

        if ($model->delete($id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Vendor deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Delete failed'
            ]);
        }
    }
}
