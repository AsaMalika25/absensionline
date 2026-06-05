<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    // ================= LOGIN PROCESS =================
    public function process()
    {
        $model = new LoginModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $user = $db->query("SELECT * FROM login WHERE BINARY username = ?", [$username])->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Account not registered yet');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Incorrect password');
        }

        session()->set([
            'id'         => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'logged_in'  => true,
            'can_create' => $user['can_create'] ?? 0,
            'can_update' => $user['can_update'] ?? 0,
            'can_delete' => $user['can_delete'] ?? 0
        ]);

        if ($user['role'] == 'admin') {
            session()->setFlashdata('login_success', true);
            return redirect()->to(base_url('AdminHome'))
                ->with('success', 'Welcome, ' . $user['username']);
        } else {
            session()->setFlashdata('login_success', true);
            return redirect()->to(base_url('Homeuser'))
                ->with('success', 'Welcome, ' . $user['username']);
        }
    }

    // ================= REGISTER PAGE =================
    public function register()
    {
        return view('auth/register');
    }

    // ================= REGISTER PROCESS =================
    public function registerProcess()
    {
        $model = new LoginModel();

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        if ($password != $confirm) {
            return redirect()->back()->with('error', 'Password does not match');
        }

        $checkUser = $model->where('username', $username)->first();
        if ($checkUser) {
            return redirect()->back()->with('error', 'Username already exists');
        }

        $model->insert([
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'user'
        ]);

        return redirect()->to(base_url('auth/login'))
            ->with('success', 'Register success, please login!');
    }

    // ================= FORGOT PAGE =================
    public function forgot()
    {
        return view('auth/forgot');
    }

    // ================= RESET PASSWORD =================
    public function resetPassword()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        $db      = \Config\Database::connect();
        $builder = $db->table('login');

        $user = $builder->where('email', $email)->get()->getRow();

        if (!$user) {
            session()->setFlashdata('error', 'Email not found!');
            return redirect()->to(base_url('auth/forgot'));
        }

        if ($password != $confirm) {
            session()->setFlashdata('error', 'Confirm password does not match!');
            return redirect()->to(base_url('auth/forgot'));
        }

        if (password_verify($password, $user->password)) {
            session()->setFlashdata('error', 'New password cannot be the same as old password!');
            return redirect()->to(base_url('auth/forgot'));
        }

        $builder->where('email', $email)->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        session()->setFlashdata('success', 'New password created successfully, please login!');
        return redirect()->to(base_url('auth/login'));
    }

    // ================= LOGOUT =================
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }

    // ================= DELETE SESSION =================
    public function delete()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'));
    }
}