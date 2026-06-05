<?php

$username = session()->get('username') ?? 'User';

$words = explode(' ', $username);

$initials = '';

foreach ($words as $word) {

    $initials .= strtoupper(substr($word, 0, 1));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f5f5;
            --bg-tertiary: #efefef;
            --text-primary: #1a1a1a;
            --text-secondary: #555;
            --text-tertiary: #999;
            --border: rgba(0, 0, 0, 0.12);
            --border-md: rgba(0, 0, 0, 0.22);
            --accent: #1e6f9f;
            --accent-hover: #185a82;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg-tertiary);
            color: var(--text-primary);
            font-size: 15px;
            padding-top: 56px;
        }

        /* Topbar */
        .topbar {
            background: var(--bg-primary);
            border-bottom: 0.5px solid var(--border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            height: 56px;

            position: fixed;
            top: 0;
            left: 0;
            width: 100%;

            z-index: 9999;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 17px;
            font-weight: 500;
            color: var(--accent);
            text-decoration: none;
        }

        .logo i {
            font-size: 22px;
        }

        .search-bar {
            flex: 1;
            max-width: 400px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            height: 34px;
            border: 0.5px solid var(--border-md);
            border-radius: var(--radius-md);
            padding: 0 12px 0 34px;
            font-size: 14px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            outline: none;
        }

        .search-bar input:focus {
            border-color: var(--accent);
        }

        .search-bar i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: var(--text-secondary);
        }

        .topbar {
            transition: 0.3s;
        }

        .topbar.scrolled {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-left: auto;
        }

        .nav-btn {
            background: none;
            border: none;
            padding: 6px 10px;
            border-radius: var(--radius-md);
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-btn:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .nav-btn i {
            font-size: 16px;
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
        }

        /* Layout */
        .layout {
            display: flex;
            min-height: calc(100vh - 56px);
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: var(--bg-primary);
            border-right: 0.5px solid var(--border);
            padding: 16px 0;
            flex-shrink: 0;

            position: sticky;
            top: 56px;
            /* tepat di bawah topbar */
            height: calc(100vh - 56px);
            overflow-y: auto;
            /* scroll sendiri kalau menu panjang */
            align-self: flex-start;
        }

        .sidebar-section {
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .sidebar-label {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 8px;
            margin-bottom: 2px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 8px;
            border-radius: var(--radius-md);
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
            text-decoration: none;
        }

        .sidebar-item:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .sidebar-item.active {
            background: var(--bg-secondary);
            color: var(--accent);
        }

        .sidebar-item i {
            font-size: 16px;
        }

        .sidebar-divider {
            border: none;
            border-top: 0.5px solid var(--border);
            margin: 8px 12px;
        }

        /* Main */
        .main {
            flex: 1;
            padding: 24px;
            min-width: 0;
        }

        .breadcrumb {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .breadcrumb span {
            color: var(--accent);
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            cursor: pointer;
            border: 0.5px solid var(--border-md);
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .btn:hover {
            background: var(--bg-secondary);
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .btn i {
            font-size: 15px;
        }

        .sort-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .sort-bar select {
            border: 0.5px solid var(--border-md);
            border-radius: var(--radius-md);
            padding: 5px 10px;
            font-size: 13px;
            background: var(--bg-primary);
            color: var(--text-primary);
            cursor: pointer;
            outline: none;
        }

        .section-title {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-title i {
            font-size: 16px;
        }

        /* Books grid */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .book-card {
            background: var(--bg-primary);
            border: 0.5px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .book-card:hover {
            border-color: var(--border-md);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .book-cover {
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .book-cover i {
            font-size: 40px;
            opacity: 0.85;
        }

        .book-body {
            padding: 12px;
        }

        .book-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
            line-height: 1.4;
            color: var(--text-primary);
        }

        .book-desc {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-tertiary);
        }

        .book-meta-item {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .book-meta-item i {
            font-size: 13px;
        }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        .tag-blue {
            min-width: 10px;
            height: 30px;
            border: none;
            border-radius: 8px;
            background: #E6F1FB;
            color: #185FA5;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .tag-teal {
            background: #E1F5EE;
            color: #085041;
        }

        .tag-amber {
            background: #FAEEDA;
            color: #633806;
        }

        .tag-purple {
            background: #EEEDFE;
            color: #3C3489;
        }
    </style>
    <style>
        /* ================= TOGGLER ================= */

        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 8px;
            background: var(--bg-secondary);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 22px;
            color: var(--text-primary);
        }

        /* ================= MOBILE ================= */

        @media (max-width: 991px) {

            .topbar {
                justify-content: space-between;
                flex-wrap: nowrap;
            }

            .menu-toggle {
                display: flex;
            }

            .topbar-nav {
                display: none;
            }

            .layout {
                position: relative;
            }

            .sidebar {
                position: fixed;
                top: 56px;
                left: -260px;
                width: 250px;
                height: calc(100vh - 56px);
                background: #fff;
                z-index: 999;
                transition: 0.3s ease;
                overflow-y: auto;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            }

            .sidebar.active {
                left: 0;
            }

            .main {
                width: 100%;
                padding: 16px;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
        }

        @media (max-width: 576px) {

            .books-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions .btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* ================= PROFILE DROPDOWN ================= */

        .profile-dropdown {
            position: relative;
        }

        .dropdown-menu-profile {
            position: absolute;
            top: 45px;
            right: 0;
            width: 220px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: none;
            z-index: 9999;
        }

        .dropdown-menu-profile.show {
            display: block;
        }

        .dropdown-user {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            font-size: 14px;
            background: #fafafa;
        }

        .dropdown-item-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: 0.2s;
        }

        .dropdown-item-profile:hover {
            background: #f5f5f5;
        }

        .dropdown-item-profile i {
            font-size: 18px;
        }

        .lang-switcher {
            display: flex;
            align-items: center;
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 3px;
            margin-right: 10px;
        }

        .lang-btn {
            border: none;
            background: transparent;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            transition: 0.2s;
        }

        .lang-btn.active {
            background: var(--accent);
            color: white;
        }

        .book-card {
            position: relative;
        }

        .card-menu {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .menu-btn {
            background: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .menu-btn i {
            font-size: 18px;
            color: #555;
        }

        .menu-dropdown {
            position: absolute;
            top: 38px;
            right: 0;
            background: white;
            border-radius: 10px;
            min-width: 140px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: none;
            overflow: hidden;
        }

        .menu-dropdown a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu-dropdown a:hover {
            background: #f5f5f5;
        }

        .absen-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 5px;
            background: #1e6f9f;
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 12px;
            transition: 0.2s;
        }

        .absen-btn:hover {
            background: #185a82;
            color: #fff;
        }

        .book-name {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .card-body canvas {
            max-height: 240px;
        }
    </style>
</head>

<body>

    <!-- Topbar -->
    <header class="topbar d-flex flex-wrap">
        <a href="#" class="logo">
            <i class="ti ti-receipt"></i>
            Absensi Online
        </a>
        <button class="menu-toggle" id="menuToggle">
            <i class="ti ti-menu-2"></i>
        </button>

        <nav class="topbar-nav">
            <a href="<?= site_url('DashboardAdmin/HomeAdmin') ?>" class="sidebar-item">
                <i class="ti ti-home"></i> Home
            </a>
            <a href="<?= site_url('DashboardAdmin/HistorySiswaAd') ?>" class="sidebar-item">
                <i class="ti ti-school"></i> Student
            </a>

            <div class="profile-dropdown">

                <div class="avatar" id="profileToggle">
                    <?= esc($initials) ?>
                </div>


                <div class="dropdown-menu-profile" id="profileMenu">

                    <div class="dropdown-user">
                        <strong><?= esc($username) ?></strong>
                    </div>

                    <a href="<?= site_url('ProfileAd') ?>" class="dropdown-item-profile">
                        <i class="ti ti-user"></i>
                        Profile
                    </a>

                    <a href="<?= site_url('DashboardAdmin/Settings') ?>" class="dropdown-item-profile">
                        <i class="ti ti-settings"></i>
                        Settings
                    </a>

                    <a href="<?= site_url('logout') ?>" class="dropdown-item-profile">
                        <i class="ti ti-logout"></i>
                        Logout
                    </a>

                </div>

            </div>
        </nav>
    </header>

    <div class="layout">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-label">Home Page</div>
                <a href="<?= site_url('DashboardAdmin/HomeAdmin') ?>" class="sidebar-item">
                    <i class="ti ti-home"></i> Home
                </a>
                <a href="<?= site_url('DashboardAdmin/HistorySiswaAd') ?>" class="sidebar-item">
                    <i class="ti ti-school"></i> Student
                </a>
                <a href="#" class="sidebar-item"><i class="ti ti-chart-bar"></i> Chart</a>
            </div>
            <hr class="sidebar-divider">
            <hr class="sidebar-divider">
            <div class="sidebar-section">
                <div class="sidebar-label">Personal</div>
                <a href="<?= site_url('ProfileAd') ?>" class="sidebar-item">
                    <i class="ti ti-user"></i>
                    Profile
                </a>
                <a href="<?= site_url('DashboardAdmin/Settings') ?>" class="sidebar-item">
                    <i class="ti ti-settings"></i>
                    Settings
                </a>
                <a href="<?= site_url('logout') ?>" class="sidebar-item">
                    <i class="ti ti-logout-2"></i>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <div class="page-header">
                <div>
                    <div class="breadcrumb"><span>Home Page</span></div>
                    <h1 class="page-title">Chart</h1>

                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <i class="ti ti-search"></i>
                        <input type="text" id="searchInput" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="section-title d-flex justify-content-between align-items-center">

                <div>
                    <i class="ti ti-layout-2"></i> All Chart
                </div>

                <div class="d-flex gap-2">

                    <!-- Download Excel -->
                    <a href="<?= site_url('DashboardAdmin/exportExcel') ?>"
                        class="btn btn-success btn-sm"
                        title="Download Excel">

                        <i class="ti ti-file-spreadsheet"></i>
                        Excel
                    </a>

                    <!-- Buka Spreadsheet -->
                    <a href="<?= site_url('DashboardAdmin/Spreadsheet') ?>"
                        class="btn btn-primary btn-sm">

                        <i class="ti ti-brand-google-drive"></i>
                        Spreadsheet
                    </a>

                </div>

            </div>


            <?php foreach ($chartData as $c): ?>

                <div class="col-12 mb-4 chart-card"
                    data-search="<?= strtolower($c['nama_kelas']) ?>">
                    <div class="card shadow-sm">
                        <div class="card-body">


                            <!-- Jumlah siswa dalam kelas -->


                            <div class="row align-items-start">

                                <!-- Donut kiri: jumlah siswa per status -->
                                <div class="col-md-5 d-flex justify-content-center">
                                    <div style="max-width:240px; width:100%;">
                                        <canvas id="donut<?= $c['id'] ?>"></canvas>
                                    </div>
                                </div>

                                <!-- Bar kanan: persentase pertemuan per siswa -->
                                <div class="col-md-7">
                                    <canvas id="bar<?= $c['id'] ?>" height="220"></canvas>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </main>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', () => {

        sidebar.classList.toggle('active');

    });

    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');

    profileToggle.addEventListener('click', () => {

        profileMenu.classList.toggle('show');

    });

    // close ketika klik luar
    window.addEventListener('click', function(e) {

        if (!profileToggle.contains(e.target) &&
            !profileMenu.contains(e.target)) {

            profileMenu.classList.remove('show');

        }

    });
</script>
<?php if (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Blank Data',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#1e6f9f'
        });
    </script>
<?php endif; ?>
<script>
    window.addEventListener('scroll', function() {

        const topbar = document.querySelector('.topbar');

        if (window.scrollY > 10) {

            topbar.classList.add('scrolled');

        } else {

            topbar.classList.remove('scrolled');

        }

    });
</script>
<script>
    const searchInput = document.getElementById('searchInput');
    const chartCards = document.querySelectorAll('.chart-card');

    searchInput.addEventListener('keyup', function() {

        const keyword = this.value.toLowerCase().trim();

        chartCards.forEach(card => {

            const kelas = card.dataset.search;

            if (kelas.includes(keyword)) {

                card.style.display = '';

            } else {

                card.style.display = 'none';

            }

        });

    });
</script>
<?php if (!session()->get('logged_in')) : ?>
    <script>
        window.location.href = "<?= base_url('/login') ?>";
    </script>
<?php endif; ?>
<script>
    document.querySelectorAll('.menu-btn').forEach(button => {

        button.addEventListener('click', function(e) {

            e.stopPropagation();

            const dropdown =
                this.nextElementSibling;

            document.querySelectorAll('.menu-dropdown')
                .forEach(menu => {

                    if (menu !== dropdown) {
                        menu.style.display = 'none';
                    }

                });

            dropdown.style.display =
                dropdown.style.display === 'block' ?
                'none' :
                'block';
        });

    });

    window.addEventListener('click', function() {

        document.querySelectorAll('.menu-dropdown')
            .forEach(menu => {

                menu.style.display = 'none';

            });

    });
</script>
<script>
    // simpan referensi bar chart per kelas agar bisa di-update tombol
    const barCharts = {};

    <?php foreach ($chartData as $c): ?>

        // ===== Donut: jumlah siswa per status dalam kelas =====
        // ===== Donut: jumlah siswa per status dalam kelas =====
        new Chart(document.getElementById('donut<?= $c['id'] ?>'), {
            type: 'doughnut',
            data: {
                labels: ['Attend', 'Permission', 'Sick'],
                datasets: [{
                    data: [<?= $c['hadir'] ?>, <?= $c['izin'] ?>, <?= $c['sakit'] ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: '<?= addslashes($c['nama_kelas']) ?>' // judul = nama kelas
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            title: function() {
                                return '';
                            },
                            label: function(ctx) {
                                const data = ctx.dataset.data;
                                const total = data.reduce((a, b) => a + b, 0);
                                const persen = total ? Math.round((ctx.parsed / total) * 100) : 0;

                                return persen + '%';
                            }
                        }
                    }
                }
            }
        });
        // ===== Bar: persentase pertemuan per siswa (mulai dari 0%) =====
        // ===== Bar: persen ditetapkan otomatis per siswa =====
        barCharts[<?= $c['id'] ?>] = new Chart(document.getElementById('bar<?= $c['id'] ?>'), {
            type: 'bar',
            data: {
                labels: [
                    <?php foreach (($siswaPerKelas[$c['id']] ?? []) as $s): ?> '<?= addslashes($s['nama']) ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Kehadiran (%)',
                    data: [
                        <?php foreach (($siswaPerKelas[$c['id']] ?? []) as $s): ?>
                            <?= $s['persen'] ?>,
                        <?php endforeach; ?>
                    ],
                    // warna sesuai status
                    backgroundColor: [
                        <?php foreach (($siswaPerKelas[$c['id']] ?? []) as $s): ?>

                            <?php

                            if (
                                $s['hadir'] >= $s['izin']
                                && $s['hadir'] >= $s['sakit']
                            ) {

                                echo "'#28a745',"; // Hijau

                            } elseif ($s['izin'] >= $s['sakit']) {

                                echo "'#ffc107',"; // Kuning

                            } else {

                                echo "'#dc3545',"; // Merah

                            }

                            ?>

                        <?php endforeach; ?>
                    ],
                    // simpan status & keterangan untuk tooltip
                    keterangan: [
                        <?php foreach (($siswaPerKelas[$c['id']] ?? []) as $s): ?>

                            <?php

                            if (
                                $s['hadir'] >= $s['izin']
                                && $s['hadir'] >= $s['sakit']
                            ) {

                                echo "'#28a745',"; // Hijau

                            } elseif ($s['izin'] >= $s['sakit']) {

                                echo "'#ffc107',"; // Kuning

                            } else {

                                echo "'#dc3545',"; // Merah

                            }

                            ?>

                        <?php endforeach; ?>
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            title: function() {
                                return '';
                            },
                            label: function(ctx) {
                                return ctx.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: v => v + '%'
                        }
                    }
                }
            }
        });

    <?php endforeach; ?>
</script>

</html>