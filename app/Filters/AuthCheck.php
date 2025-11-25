<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('app-login'))
                ->with('error', 'Please log in first.');
        }

        if ($arguments) {
            $requiredRole = $arguments[0];
            $userRole = session()->get('role');

            if ($userRole !== $requiredRole) {
                return redirect()->to(base_url('unauthorized'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $uri = trim($request->getUri()->getPath(), '/');

        // ✅ If already logged in and tries to access login page → redirect to respective dashboard
        if (session()->get('isLoggedIn') && in_array($uri, ['app-login', 'login/authenticate'])) {
            $role = session()->get('role');

            if ($role === 'admin') {
                return redirect()->to(base_url('admin/'));
            } elseif ($role === 'user') {
                return redirect()->to(base_url('user/'));
            }
        }
    }
}
