<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Users</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    th,
    td {
        white-space: nowrap;
    }
</style>

<body class="bg-light">

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2>Data Users</h2>
            <button class="btn btn-primary" onclick="window.location.href='<?= base_url('/client') ?>'">
                <i class="bi bi-plus-lg"></i>
                Form for clients
            </button>
        </div>
        <div class="card shadow">
            <div class="card-body">

                <!-- WRAPPER RESPONSIVE -->
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover text-center" style="overflow-x:auto;">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($user->username) ?></td>
                                        <td><?= esc($user->email) ?></td>
                                        <td>
                                            <form action="/update-role/<?= $user->id ?>" method="post">
                                                <select name="role" class="form-select form-select-sm"
                                                    onchange="this.form.submit()">

                                                    <option value="user" <?= $user->role == 'user' ? 'selected' : '' ?>>
                                                        User
                                                    </option>

                                                    <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>
                                                        Admin
                                                    </option>

                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="/delete-user/<?= $user->id ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin hapus data ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>

                            <?php endif; ?>
                        </tbody>
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                    </table>

                </div>

            </div>
        </div>

    </div>

</body>

</html>