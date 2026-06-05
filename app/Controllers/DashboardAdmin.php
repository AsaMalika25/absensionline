<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


use App\Models\LoginModel;

class DashboardAdmin extends BaseController
{
    public function HomeAdmin()
    {

        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT kelas.*, COUNT(siswa.id) as total_siswa
            FROM kelas
            LEFT JOIN siswa ON siswa.id_kelas = kelas.id
            GROUP BY kelas.id
        ");

        $data['kelas'] = $query->getResultArray();

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }

        return view('dashboardAdmin/HomeAdmin', $data);
    }

    public function ProfileAd()
    {
        return view('dashboardAdmin/ProfileAd');
    }

    public function Formkelasadmin()
    {
        return view('form/FormKelasAdmin');
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

    public function ChartAdmin()
    {
        $db = \Config\Database::connect();

        // Donut Chart per kelas
        $data['chartData'] = $db->query("
        SELECT
            k.id,
            k.nama_kelas,

            COUNT(s.id) AS total_siswa,

            SUM(
                CASE
                    WHEN LOWER(TRIM(s.status_kehadiran))='attend'
                    THEN 1 ELSE 0
                END
            ) AS hadir,

            SUM(
                CASE
                    WHEN LOWER(TRIM(s.status_kehadiran))='permission'
                    THEN 1 ELSE 0
                END
            ) AS izin,

            SUM(
                CASE
                    WHEN LOWER(TRIM(s.status_kehadiran))='sick'
                    THEN 1 ELSE 0
                END
            ) AS sakit

        FROM kelas k
        LEFT JOIN siswa s
            ON s.id_kelas = k.id

        GROUP BY k.id
    ")->getResultArray();


        // ==========================
        // BAR CHART PER SISWA
        // ==========================
        $query = $db->query("
        SELECT
            nama,
            id_kelas,

            SUM(
                CASE
                    WHEN LOWER(TRIM(status_kehadiran))='attend'
                    THEN 1 ELSE 0
                END
            ) AS hadir,

            SUM(
                CASE
                    WHEN LOWER(TRIM(status_kehadiran))='permission'
                    THEN 1 ELSE 0
                END
            ) AS izin,

            SUM(
                CASE
                    WHEN LOWER(TRIM(status_kehadiran))='sick'
                    THEN 1 ELSE 0
                END
            ) AS sakit

        FROM siswa

        WHERE MONTH(created_at)=MONTH(CURDATE())
        AND YEAR(created_at)=YEAR(CURDATE())

        GROUP BY nama,id_kelas
    ");

        $siswaPerKelas = [];

        foreach ($query->getResultArray() as $row) {

            $persenHadir = $row['hadir'] * 10;
            $persenIzin  = $row['izin'] * 4;
            $persenSakit = $row['sakit'] * 5;

            $persenTotal =
                $persenHadir +
                $persenIzin +
                $persenSakit;

            if ($persenTotal > 100) {
                $persenTotal = 100;
            }

            $row['persen'] = $persenTotal;

            $siswaPerKelas[$row['id_kelas']][] = $row;
        }

        $data['siswaPerKelas'] = $siswaPerKelas;

        return view(
            'dashboardAdmin/ChartAdmin',
            $data
        );
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
            ->with('success', 'Account successfully deleted');
    }
    public function getUserAd()
    {
        $model = new LoginModel();

        $user = $model
            ->where('username', session()->get('username'))
            ->first();

        return $this->response->setJSON($user);
    }
    public function updateProfileAd()
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
    public function exportExcel()
    {
        $db = \Config\Database::connect();

        $data = $db->query("
        SELECT
            s.nama,
            s.gender,
            s.status_kehadiran,
            s.keterangan,
            s.created_at,
            k.nama_kelas
        FROM siswa s
        LEFT JOIN kelas k
            ON k.id = s.id_kelas
        ORDER BY k.nama_kelas ASC, s.nama ASC
    ")->getResultArray();

        // Jika tidak ada data
        if (empty($data)) {

            return redirect()->back()->with(
                'error',
                'Attendance data is still empty.'
            );
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI SISWA');

        // Header
        $sheet->setCellValue('A3', 'Nama');
        $sheet->setCellValue('B3', 'Gender');
        $sheet->setCellValue('C3', 'Status Kehadiran');
        $sheet->setCellValue('D3', 'Kelas');
        $sheet->setCellValue('E3', 'Keterangan');
        $sheet->setCellValue('F3', 'Tanggal');

        // Style Header
        $sheet->getStyle('A3:F3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E6F9F']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);

        $row = 4;



        foreach ($data as $d) {

            $sheet->setCellValue('A' . $row, $d['nama']);
            $sheet->setCellValue('B' . $row, $d['gender']);
            $sheet->setCellValue('C' . $row, $d['status_kehadiran']);
            $sheet->setCellValue('D' . $row, $d['nama_kelas']);
            $sheet->setCellValue('E' . $row, $d['keterangan']);
            $sheet->setCellValue(
                'F' . $row,
                date('d-m-Y H:i', strtotime($d['created_at']))
            );

            $row++;
        }

        // Border tabel
        $lastRow = $row - 1;

        $sheet->getStyle("A3:F{$lastRow}")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ]);

        // Auto width
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)
                ->setAutoSize(true);
        }

        // Judul rata tengah
        $sheet->getStyle('A1')
            ->getFont()
            ->setBold(true)
            ->setSize(16);

        $writer = new Xlsx($spreadsheet);

        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment; filename="Laporan_Absensi.xlsx"'
        );

        if ($d['status_kehadiran'] == 'attend') {
            $sheet->getStyle('C' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('28A745');
        }

        if ($d['status_kehadiran'] == 'permission') {
            $sheet->getStyle('C' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFC107');
        }

        if ($d['status_kehadiran'] == 'sick') {
            $sheet->getStyle('C' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('DC3545');
        }

        $writer->save('php://output');
        exit;
    }
    public function Spreadsheet()
    {
        $db = \Config\Database::connect();

        $data['absensi'] = $db->query("
        SELECT
            s.nama,
            s.gender,
            s.status_kehadiran,
            s.keterangan,
            s.created_at,
            k.nama_kelas
        FROM siswa s
        LEFT JOIN kelas k
            ON k.id = s.id_kelas
        ORDER BY
            k.nama_kelas ASC,
            s.created_at DESC
    ")->getResultArray();

        return view(
            'dashboardAdmin/Spreadsheet',
            $data
        );
    }
    public function NoteAd()
    {
        return view('dashboardAdmin/NoteAd');
    }

    public function Settings()
    {
        $model = new \App\Models\LoginModel();

        $data['login'] = $model
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $data['pager'] = $model->pager;

        return view('dashboardAdmin/Settings', $data);
    }
    public function updateRole()
    {
        $id   = $this->request->getPost('id');
        $role = $this->request->getPost('role');

        $model = new \App\Models\LoginModel();

        $update = $model->update($id, [
            'role' => $role
        ]);

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error'
        ]);
    }

    public function deleteAccountS($id = null)
    {
        $session = session();

        // ID user yang sedang login
        $loginId = $session->get('id');

        $db = \Config\Database::connect();

        // Hapus akun
        $db->table('login')
            ->where('id', $id)
            ->delete();

        // Jika yang dihapus akun sendiri
        if ($loginId == $id) {

            $session->destroy();

            return redirect()
                ->to('/login')
                ->with('delete_success', 'Account deleted successfully');
        }

        // Jika yang dihapus akun orang lain
        return redirect()
            ->to('DashboardAdmin/Settings')
            ->with('delete_success', 'Account deleted successfully');
    }
    public function getUserAdS()
    {
        $model = new LoginModel();

        $user = $model
            ->where('username', session()->get('username'))
            ->first();

        return $this->response->setJSON($user);
    }
    public function updateProfileAdS()
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
