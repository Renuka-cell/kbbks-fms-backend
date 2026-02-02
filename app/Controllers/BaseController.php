<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\UserModel;

/**
 * BaseController
 * Common authentication & role logic
 */
abstract class BaseController extends Controller
{
    protected $currentUser = null;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    /**
     * Check token and load user
     */
    protected function checkToken(): bool
    {
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
            return false;
        }

        $token = substr($authHeader, 7);

        if (!$token) {
            return false;
        }

        $userModel = new UserModel();
        $user = $userModel->where('token', $token)->first();

        if (!$user) {
            return false;
        }

        // Save logged-in user for later use
        $this->currentUser = $user;

        return true;
    }

    /**
     * Role check using logged-in user
     */
    protected function checkRole(array $allowedRoles): bool
    {
        $role = $this->request->getHeaderLine('Role');
        return in_array($role, $allowedRoles);
    }
}
