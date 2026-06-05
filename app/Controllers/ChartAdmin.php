<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DashboardAdmin extends BaseController
{
    public function ChartAdmin()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Access denied');
        }
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
                'Attendance data is still empty..'
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
}
