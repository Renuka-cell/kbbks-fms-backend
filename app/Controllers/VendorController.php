<?php

namespace App\Controllers;

use App\Models\VendorModel;

class VendorController extends BaseController
{
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
                'message' => 'Insufficient role permission'
            ]);
        }

        $model = new VendorModel();
        $vendors = $model->findAll();

        // ✅ FULL URL + DEFAULT LOGO
        foreach ($vendors as &$vendor) {

            if (!empty($vendor['logo'])) {
                $vendor['logo'] = base_url($vendor['logo']);
            } else {
                $vendor['logo'] = base_url('uploads/vendor_logos/default.png');
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $vendors
        ]);
    }

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
                'message' => 'Only Admin or Accountant can create vendors'
            ]);
        }

        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $data = $this->request->getJSON(true);
        } else {
            $data = $this->request->getPost();
        }

        if (empty($data['vendor_name'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Vendor name is required'
            ]);
        }

        $file = $this->request->getFile('logo');
        $logoPath = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // ✅ FILE SIZE LIMIT (2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'File size must be less than 2MB'
                ]);
            }

            // ✅ ALLOWED EXTENSIONS
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $extension = strtolower($file->getExtension());

            if (!in_array($extension, $allowedExtensions)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'Only PNG, JPG, JPEG files allowed'
                ]);
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/vendor_logos/', $newName);

            $logoPath = 'uploads/vendor_logos/' . $newName;
        }

        $data['logo'] = $logoPath;

        $model = new VendorModel();

        if (!$model->insert($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => false,
                'errors' => $model->errors()
            ]);
        }

        return $this->response->setJSON([
            'status'    => true,
            'message'   => 'Vendor created successfully',
            'vendor_id' => $model->getInsertID()
        ]);
    }

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
                'message' => 'Only Admin or Accountant can update vendors'
            ]);
        }

        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $data = $this->request->getJSON(true);
        } else {
            $data = $this->request->getPost();
        }

        if (empty($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'No data provided to update'
            ]);
        }

        $model = new VendorModel();
        $existingVendor = $model->find($id);

        if (!$existingVendor) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Vendor not found'
            ]);
        }

        $file = $this->request->getFile('logo');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            if ($file->getSize() > 2 * 1024 * 1024) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'File size must be less than 2MB'
                ]);
            }

            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $extension = strtolower($file->getExtension());

            if (!in_array($extension, $allowedExtensions)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'Only PNG, JPG, JPEG files allowed'
                ]);
            }

            // ✅ DELETE OLD LOGO
            if (!empty($existingVendor['logo']) && file_exists(FCPATH . $existingVendor['logo'])) {
                unlink(FCPATH . $existingVendor['logo']);
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/vendor_logos/', $newName);

            $data['logo'] = 'uploads/vendor_logos/' . $newName;
        }

        if (!$model->update($id, $data)) {
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
                'message' => 'Only Admin can delete vendors'
            ]);
        }

        $model = new VendorModel();
        $vendor = $model->find($id);

        // ✅ DELETE LOGO WHEN VENDOR DELETED
        if (!empty($vendor['logo']) && file_exists(FCPATH . $vendor['logo'])) {
            unlink(FCPATH . $vendor['logo']);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Vendor deleted successfully'
        ]);
    }
}