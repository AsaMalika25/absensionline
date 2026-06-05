<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\SiswaModel;

class FormAbsensiAd extends BaseController
{
    public function FormSiswaAd()
    {
        $kelasModel = new KelasModel();

        $data['kelas'] = $kelasModel->findAll();
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }

        return view('form/FormSiswaAd', $data);
    }

    public function saveAd()
    {
        $model = new SiswaModel();

        date_default_timezone_set('Asia/Jakarta');

        $model->save([

            'nama' => $this->request->getPost('nama'),

            'gender' => $this->request->getPost('gender'),

            'id_kelas' => $this->request->getPost('id_kelas'),

            'status_kehadiran' => $this->request->getPost('status_kehadiran'),

            'keterangan' => $this->request->getPost('Keterangan'),

            'created_at' => date('Y-m-d H:i:s')

        ]);

        session()->setFlashdata(
            'siswa_success',
            'Attendance saved successfully'
        );

        return redirect()->to('/HistorySiswaAd');
    }

    public function HistorySiswaAd()
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


        return view('DashboardAdmin/HistorySiswaAd', $data);
    }
    public function delete($id)
    {
        $model = new \App\Models\SiswaModel();

        $model->delete($id);

        return redirect()->back()->with('delete_success', true);
    }
}
