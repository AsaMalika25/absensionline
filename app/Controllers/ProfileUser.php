<?php

namespace App\Controllers;

use App\Models\LoginModel;


class DashboardUser extends BaseController
{
    public function ProfileUser()
    {
        if (session()->get('role') !== 'user') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }
        return view('dashboardUser/ProfileUser');
    }
    public function deleteAccount()
    {
        $userModel = new \App\Models\LoginModel();

        $username = session()->get('username');

        $userModel
            ->where('username', $username)
            ->delete();

        session()->destroy();

        return redirect()->to('/auth/login')
            ->with('success', 'Akun berhasil dihapus');
    }
    public function getUser()
    {
        $model = new LoginModel();

        $user = $model
            ->where('username', session()->get('username'))
            ->first();

        return $this->response->setJSON($user);
    }
    public function updateProfile()
    {
        $model = new LoginModel();

        $user = $model
            ->where('username', session()->get('username'))
            ->first();

        $model->update($user['id'], [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email')
        ]);

        session()->set([
            'username' => $this->request->getPost('username')
        ]);

        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }
}
