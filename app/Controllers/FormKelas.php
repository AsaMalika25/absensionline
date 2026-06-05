<?php

namespace App\Controllers;

use App\Models\KelasModel;

class FormKelas extends BaseController
{
    public function Formkelas()
    {
        if (session()->get('role') !== 'user') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }

        return view('form/FormKelas');
    }

    public function save()
    {
        $model = new KelasModel();

        date_default_timezone_set('Asia/Jakarta');

        $model->save([
            'nama_kelas'  => $this->request->getPost('nama'),
            'level_kelas' => $this->request->getPost('jenjang'),
            'keterangan'  => $this->request->getPost('Keterangan'),
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/Homeuser')
            ->with('class_success', 'Class Success');
    }
    public function delete($id)
    {
        $model = new KelasModel();

        $model->delete($id);

        return redirect()->to('/Homeuser')
            ->with('delete_success', 'Class successfully deleted');
    }
}
