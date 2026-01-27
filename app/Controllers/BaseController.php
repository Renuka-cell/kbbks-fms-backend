<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
<<<<<<< HEAD
=======
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
 */
abstract class BaseController extends Controller
{
    /**
<<<<<<< HEAD
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
=======
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
    }
}
