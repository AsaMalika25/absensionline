<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Data Sekolah</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            margin-top: 100px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            color: #185a82;
            margin-bottom: 20px;
            border-bottom: 2px solid #185a82;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #185a82;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        .radio-group,
        .checkbox-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }

        .radio-group label,
        .checkbox-group label {
            font-weight: normal;
            cursor: pointer;
        }

        button {
            background-color: #185a82;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        button:hover {
            background-color: #185a82;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Class Creation Form</h2>

        <form action="<?= site_url('save-siswaAd') ?>" method="POST">

            <div class="form-group">
                <label for="nama">Full name</label>
                <input type="text" id="nama" name="nama" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Usia</label>

                <select name="id_kelas" required>

                    <?php foreach ($kelas as $k) : ?>

                        <option value="<?= $k['id']; ?>">

                            <?= $k['nama_kelas']; ?>
                            -
                            <?= $k['level_kelas']; ?>

                        </option>

                    <?php endforeach; ?>

                </select>
            </div>


            <div class="form-group">
                <label for="status_kehadiran">Information</label>

                <select id="status_kehadiran" name="status_kehadiran" required>
                    <option value="Attend">Attend</option>
                    <option value="Permission">Permission</option>
                    <option value="Sick">Sick</option>
                </select>
            </div>

            <!-- Hidden Default -->
            <div class="form-group" id="reasonBox" style="display:none;">
                <label for="Keterangan">Reason for leave/sickness</label>

                <textarea
                    id="Keterangan"
                    name="Keterangan"
                    rows="4"></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
                <button type="button"
                    class="btn"
                    onclick="window.location.href='<?= site_url('AdminHome') ?>'">

                    <i class="ti ti-arrow-left"></i>
                    Back

                </button>

            </div>


        </form>
    </div>

</body>
<?php if (!session()->get('logged_in')) : ?>
    <script>
        window.location.href = "<?= base_url('/login') ?>";
    </script>
<?php endif; ?>
<?php if (session()->getFlashdata('class_success')) : ?>

    <script>
        Swal.fire({
            title: 'Class Success',
            text: 'Data berhasil disimpan',
            icon: 'success',
            confirmButtonColor: '#185a82'
        });
    </script>

<?php endif; ?>
<script>
    const statusSelect = document.getElementById('status_kehadiran');
    const reasonBox = document.getElementById('reasonBox');
    const reasonText = document.getElementById('Keterangan');

    statusSelect.addEventListener('change', function() {

        if (this.value === 'Permission' || this.value === 'Sick') {

            reasonBox.style.display = 'block';
            reasonText.setAttribute('required', true);

        } else {

            reasonBox.style.display = 'none';
            reasonText.removeAttribute('required');
            reasonText.value = '';

        }

    });
</script>

</html>