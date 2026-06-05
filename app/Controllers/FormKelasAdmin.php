<?php

namespace App\Controllers;

use App\Models\KelasModel;

class FormKelasAdmin extends BaseController
{
    public function Formkelasadmin()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }
        return view('form/FormKelasAdmin');
    }

    public function saveAdmin()
    {
        $model = new KelasModel();

        date_default_timezone_set('Asia/Jakarta');

        $data = [
            'nama_kelas'  => $this->request->getPost('nama'),
            'level_kelas' => $this->request->getPost('jenjang'),
            'keterangan'  => $this->request->getPost('Keterangan'),
            'created_at'  => date('Y-m-d H:i:s')
        ];

        if ($model->save($data)) {
            // data berhasil masuk database → arahkan ke HomeAdmin
            return redirect()->to('/AdminHome')
                ->with('class_success', 'Class saved successfully');
        }

        // gagal → kembali ke form
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan data, coba lagi.');
    }
    public function delete($id)
    {
        $model = new KelasModel();

        $model->delete($id);

        return redirect()->to('/AdminHome')
            ->with('delete_success', 'Class successfully deleted');
    }
}
