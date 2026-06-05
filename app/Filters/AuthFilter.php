<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Jika belum login
        if (!$session->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Please log in first');
        }

        // Cek role jika ada argument
        if ($arguments) {
            $role = $session->get('role');

            if (!in_array($role, $arguments)) {
                return redirect()->to('/login')
                    ->with('error', 'You do not have access to this page');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak digunakan
    }
}
