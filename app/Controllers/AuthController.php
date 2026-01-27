<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // REGISTER NEW USER
    public function register()
    {
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role')
        ];

        if (!$data['name'] || !$data['email'] || !$this->request->getPost('password') || !$data['role']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'All fields are required'
            ]);
        }

        $userModel = new UserModel();

        // Check if email already exists
        if ($userModel->where('email', $data['email'])->first()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email already registered'
            ]);
        }

        $userModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'User registered successfully'
        ]);
    }

    // LOGIN USER
    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email and password are required'
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ]);
        }

        // Simple token for Phase-1
        $token = bin2hex(random_bytes(16));

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token,
            'role' => $user['role']
        ]);
    }
}
