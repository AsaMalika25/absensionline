<?php

namespace App\Controllers;

use App\Models\LoginModel;

class DashboardUser extends BaseController
{
    public function HomeUser()
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT kelas.*, COUNT(siswa.id) as total_siswa
            FROM kelas
            LEFT JOIN siswa ON siswa.id_kelas = kelas.id
            GROUP BY kelas.id
        ");

        $data['kelas'] = $query->getResultArray();

        if (session()->get('role') !== 'user') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }

        return view('dashboardUser/HomeUser', $data);
    }
    public function ProfileUser()
    {
        return view('dashboardUser/ProfileUser');
    }

    public function FormKelas()
    {
        return view('form/FormKelas');
    }
    public function HistorySiswa()
    {
        $model = new \App\Models\SiswaModel();

        $userId = session()->get('user_id');

        $data = [
            'siswa' => $model
                ->select('siswa.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id = siswa.id_kelas')
                ->paginate(10),

            'pager' => $model->pager
        ];

        return view('DashboardUser/HistorySiswa', $data);
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

    public function updatePermission()
    {
        $id     = $this->request->getPost('id');
        $field  = $this->request->getPost('field');   // can_create | can_update | can_delete
        $value  = (int) $this->request->getPost('value'); // 1 atau 0

        // hanya izinkan field yang valid (keamanan)
        $allowed = ['can_create', 'can_update', 'can_delete'];
        if (!in_array($field, $allowed)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'invalid field']);
        }

        $model  = new \App\Models\LoginModel();
        $update = $model->update($id, [$field => $value]);

        if ($update) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error']);
    }
}
