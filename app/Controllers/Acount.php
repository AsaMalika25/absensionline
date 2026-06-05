<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Acount extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 🔐 Proteksi admin
    private function checkAdmin()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/auth/login');
        }
    }
    public function index()
    {
        $db = \Config\Database::connect();

        // ambil semua data dari tabel login
        $data['users'] = $db->table('login')->get()->getResult();

        // kirim ke view
        return view('tabel/acount', $data);
    }
    public function users()
    {
        $db = \Config\Database::connect();

        $data['users'] = $db->table('login')->get()->getResult();

        return view('tabel/acount', $data); // bisa satu halaman
    }

    // ➕ Tambah user
    public function addUser()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $this->db->table('login')->insert([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role') ?? 'user'
        ]);

        return redirect()->to('/admin/users')->with('success', 'User added successfully');
    }

    public function updateRole($id)
    {
        $role = $this->request->getPost('role');

        $this->db->table('login')
            ->where('id', $id)
            ->update(['role' => $role]);

        return redirect()->to('/acount');
    }
    // 💾 Update user
    public function updateUser($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role')
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->db->table('login')
            ->where('id', $id)
            ->update($data);

        return redirect()->to('/admin/users')->with('success', 'User updated successfully');
    }

    // ❌ Hapus user
    public function deleteUser($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $this->db->table('login')
            ->where('id', $id)
            ->delete();

        return redirect()->to('/acount')->with('success', 'User deleted successfully');
    }
}
