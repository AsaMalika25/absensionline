<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;

class HistoryAd extends BaseController
{
    public function delete($id)
    {
        $model = new \App\Models\SiswaModel();

        $model->delete($id);
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }

        return redirect()->back()->with('delete_success', true);
    }
    public function edit($id)
    {
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();

        $data['kelas'] = $siswaModel->find($id);

        $data['kelas_list'] = $kelasModel->findAll();

        return view('FormEdit/FormSiswaEditAd', $data);
    }

    public function update($id)
    {
        $model = new SiswaModel();

        $model->update($id, [

            'nama' => $this->request->getPost('nama'),

            'gender' => $this->request->getPost('gender'),

            'id_kelas' => $this->request->getPost('id_kelas'),

            'status_kehadiran' => $this->request->getPost('status_kehadiran'),

            'keterangan' => $this->request->getPost('Keterangan')

        ]);

        return redirect()->to(site_url('HistorysiswaAd'))
            ->with('edit_success', 'Data updated successfully');
    }
}
