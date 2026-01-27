<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 */
abstract class BaseController extends Controller
{
    /**
     * @return void
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    /**
     * BASIC TOKEN CHECK (Phase-1)
     * Checks Authorization: Bearer <token>
     */
    protected function checkToken()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return false;
        }

        // Expected format: Bearer tokenvalue
        if (strpos($authHeader, 'Bearer ') !== 0) {
            return false;
        }

        $token = substr($authHeader, 7);

        if (empty($token)) {
            return false;
        }

        return true;
    }

    /**
     * ROLE CHECK (Phase-1)
     * Allowed roles passed as array
     */
    protected function checkRole(array $allowedRoles)
    {
        $role = $this->request->getHeaderLine('Role');

        if (!$role) {
            return false;
        }

        return in_array($role, $allowedRoles);
    }
}
