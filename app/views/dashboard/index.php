
<?php

require_once __DIR__ . '/../../../config/database.php';

$namaAdmin = $_SESSION['nama'] ?? 'Administrator';

// Mengambil total penghuni
$queryPenghuni = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM penghuni"
);

$totalPenghuni = $queryPenghuni
    ? mysqli_fetch_assoc($queryPenghuni)['total']
    : 0;

// Mengambil jumlah tagihan bulan ini
$bulanIni = date('n');
$tahunIni = date('Y');

$queryTagihan = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tagihan
     WHERE bulan = ? AND tahun = ?"
);

mysqli_stmt_bind_param($queryTagihan, "ii", $bulanIni, $tahunIni);
mysqli_stmt_execute($queryTagihan);

$dataTagihan = mysqli_fetch_assoc(
    mysqli_stmt_get_result($queryTagihan)
);

$totalTagihan = $dataTagihan['total'] ?? 0;

// Mengambil total pembayaran yang sudah diterima
$queryPembayaran = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(nominal), 0) AS total
     FROM pembayaran
     WHERE status_verifikasi = 'Diterima'"
);

$dataPembayaran = $queryPembayaran
    ? mysqli_fetch_assoc($queryPembayaran)
    : ['total' => 0];

$totalPembayaran = $dataPembayaran['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .logout-link {
            display: block;
            margin-top: 25px;
            padding: 12px 15px;
            background: #dc2626;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
        }

        .logout-link:hover {
            background: #b91c1c;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Pembayaran Kos</h2>
        <p>Sistem Pengelolaan Kos</p>

        <a href="index.php?page=dashboard" class="active">
            Dashboard
        </a>

        <a href="index.php?page=penghuni">
            Data Penghuni
        </a>

        <a href="index.php?page=tagihan">
            Tagihan
        </a>

        <a href="index.php?page=pembayaran">
            Pembayaran
        </a>

        <a href="index.php?page=laporan">
            Laporan
        </a>

        <a href="index.php?logout=1"
           class="logout-link"
           onclick="return confirm('Apakah kamu yakin ingin logout?')">
            Logout
        </a>

    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Dashboard</h1>
                <p>Selamat datang di sistem pembayaran kos.</p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= htmlspecialchars($namaAdmin) ?></strong>
            </div>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Penghuni</h3>
                <h2><?= $totalPenghuni ?></h2>
            </div>

            <div class="card">
                <h3>Tagihan Bulan Ini</h3>
                <h2><?= $totalTagihan ?></h2>
            </div>

            <div class="card">
                <h3>Total Pembayaran Lunas</h3>
                <h2>Rp<?= number_format((float)$totalPembayaran, 0, ',', '.') ?></h2>
            </div>

        </div>

    </main>

</div>

</body>
</html>
