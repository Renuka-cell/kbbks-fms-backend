<?php

namespace App\Controllers;

use App\Models\VendorModel;

class VendorController extends BaseController
{
    /**
     * READ: Fetch all vendors
     * URL: GET /vendors
     * Roles: Admin, Accountant, Viewer
     */
    public function index()
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized access'
            ]);
        }

        if (!$this->checkRole(['Admin', 'Accountant', 'Viewer'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Access denied'
            ]);
        }

        $model = new VendorModel();
        return $this->response->setJSON([
            'status' => true,
            'data'   => $model->findAll()
        ]);
    }

    /**
     * CREATE: Insert new vendor
     * URL: POST /vendors/create
     * Roles: Admin, Accountant
     */
    public function create()
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized access'
            ]);
        }

        if (!$this->checkRole(['Admin', 'Accountant'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Access denied'
            ]);
        }

        $requestData = $this->request->getJSON(true);

        $data = [
            'vendor_name'     => $requestData['vendor_name'] ?? null,
            'contact_person' => $requestData['contact_person'] ?? null,
            'phone'           => $requestData['phone'] ?? null,
            'email'           => $requestData['email'] ?? null
        ];

        // Basic validation
        if (empty($data['vendor_name'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Vendor name is required'
            ]);
        }

        $model = new VendorModel();

        if ($model->insert($data) === false) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'errors'  => $model->errors()
            ]);
        }

        return $this->response->setJSON([
            'status'    => true,
            'message'   => 'Vendor created successfully',
            'vendor_id' => $model->getInsertID()
        ]);
    }

    /**
     * UPDATE: Update vendor
     * URL: POST /vendors/update/{id}
     * Roles: Admin, Accountant
     */
    public function update($id)
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized access'
            ]);
        }

        if (!$this->checkRole(['Admin', 'Accountant'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Access denied'
            ]);
        }

        $model = new VendorModel();

        if ($model->update($id, $this->request->getPost()) === false) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => false,
                'errors' => $model->errors()
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Vendor updated successfully'
        ]);
    }

    /**
     * DELETE: Delete vendor
     * URL: GET /vendors/delete/{id}
     * Roles: Admin
     */
    public function delete($id)
    {
        if (!$this->checkToken()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => false,
                'message' => 'Unauthorized access'
            ]);
        }

        if (!$this->checkRole(['Admin'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => false,
                'message' => 'Access denied'
            ]);
        }

        $model = new VendorModel();
        $model->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Vendor deleted successfully'
        ]);
    }
}
