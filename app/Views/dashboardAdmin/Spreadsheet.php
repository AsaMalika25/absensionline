<!DOCTYPE html>
<html>

<head>

    <title>Spreadsheet Absensi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between mb-3">

            <h3>Spreadsheet Absensi</h3>

            <div>
                <a href="<?= site_url('DashboardAdmin/ChartAdmin') ?>"
                    class="btn btn-success">

                    Back

                </a>
                <a href="<?= site_url('DashboardAdmin/exportExcel') ?>"
                    class="btn btn-success">

                    Download Excel

                </a>
            </div>


        </div>

        <?php if (empty($absensi)): ?>

            <div class="alert alert-warning">

                Data absensi masih kosong.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped">

                    <thead class="table-primary">

                        <tr>

                            <th>No</th>

                            <th>Nama</th>

                            <th>Gender</th>

                            <th>Kelas</th>

                            <th>Status</th>

                            <th>Keterangan</th>

                            <th>Tanggal</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($absensi as $a): ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td><?= esc($a['nama']) ?></td>

                                <td><?= esc($a['gender']) ?></td>

                                <td><?= esc($a['nama_kelas']) ?></td>

                                <td>

                                    <?php
                                    $status =
                                        strtolower(
                                            $a['status_kehadiran']
                                        );
                                    ?>

                                    <?php if ($status == 'attend'): ?>

                                        <span class="badge bg-success">

                                            Attend

                                        </span>

                                    <?php elseif ($status == 'permission'): ?>

                                        <span class="badge bg-warning text-dark">

                                            Permission

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">

                                            Sick

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= esc($a['keterangan']) ?>
                                </td>

                                <td>

                                    <?= date(
                                        'd-m-Y H:i',
                                        strtotime(
                                            $a['created_at']
                                        )
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>