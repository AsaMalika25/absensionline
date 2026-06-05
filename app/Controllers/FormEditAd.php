<?php

namespace App\Controllers;

use App\Models\KelasModel;

class FormEditAd extends BaseController
{
    public function FormKelasEditAd($id)
    {
        $model = new KelasModel();

        $kelas = $model->find($id);

        if (!$kelas) {
            return redirect()->to('/AdminHome');
        }

        $data['kelas'] = $kelas;
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }

        return view('formedit/FormKelasEditAd', $data);
    }

    public function update($id)
    {
        $model = new KelasModel();

        date_default_timezone_set('Asia/Jakarta');

        $model->update($id, [
            'nama_kelas'  => $this->request->getPost('nama'),
            'level_kelas' => $this->request->getPost('jenjang'),
            'keterangan'  => $this->request->getPost('Keterangan'),
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('edit_success', 'Class updated successfully');

        return redirect()->to('/AdminHome');
    }
}
