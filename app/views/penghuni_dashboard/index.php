<?php

require_once __DIR__ . '/../../models/pembayaran.php';

$namaPenghuni = $_SESSION['nama'] ?? 'Penghuni';
$penghuniId = (int) ($_SESSION['penghuni_id'] ?? 0);

// Mengambil ringkasan dari database
$ringkasan = Pembayaran::ringkasanPenghuni($penghuniId);

$totalTagihan = $ringkasan['total_tagihan'];
$totalPembayaran = $ringkasan['total_pembayaran'];
$statusPembayaran = $ringkasan['status_pembayaran'];

function rupiahDashboard($angka)
{
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Penghuni</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .menu-penghuni {
            margin-top: 30px;
        }

        .menu-penghuni a {
            display: block;
            padding: 13px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 7px;
            margin-bottom: 8px;
        }

        .menu-penghuni a:hover,
        .menu-penghuni a.active {
            background: #2563eb;
            color: white;
        }

        .menu-penghuni .logout-link {
            margin-top: 20px;
            color: #fca5a5;
        }

        .menu-penghuni .logout-link:hover {
            background: #dc2626;
            color: white;
        }

        .info-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .info-box h3 {
            margin-bottom: 12px;
        }

        .info-box p {
            color: #64748b;
            line-height: 1.7;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Pembayaran Kos</h2>
        <p>Portal Penghuni Kos</p>

        <div class="menu-penghuni">

            <a href="index.php?page=penghuni-dashboard" class="active">
                Dashboard
            </a>

            <a href="index.php?page=tagihan-saya">
                Tagihan Saya
            </a>

            <a href="index.php?page=pembayaran-penghuni">
                Pembayaran
            </a>

            <a href="index.php?page=riwayat-pembayaran">
                Riwayat Pembayaran
            </a>

            <a href="index.php?logout=1"
               class="logout-link"
               onclick="return confirm('Apakah kamu yakin ingin logout?')">
                Logout
            </a>

        </div>

    </aside>

    <main class="main-content">

        <div class="header">

            <div>
                <h1>Dashboard Penghuni</h1>
                <p>Selamat datang di sistem pembayaran kos.</p>
            </div>

            <div class="admin-info">
                Penghuni:
                <strong>
                    <?= htmlspecialchars($namaPenghuni) ?>
                </strong>
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Tagihan</h3>
                <h2>
                    <?= rupiahDashboard($totalTagihan) ?>
                </h2>
            </div>

            <div class="card">
                <h3>Status Pembayaran</h3>
                <h2>
                    <?= htmlspecialchars($statusPembayaran) ?>
                </h2>
            </div>

            <div class="card">
                <h3>Total Pembayaran</h3>
                <h2>
                    <?= rupiahDashboard($totalPembayaran) ?>
                </h2>
            </div>

        </div>

        <div class="info-box">

            <h3>Informasi Pembayaran</h3>

            <p>
                Silakan periksa tagihan listrik dan WiFi setiap bulan.
                Lakukan pembayaran melalui transfer ke rekening pemilik kos
                dan unggah bukti transfer untuk dikonfirmasi oleh admin.
            </p>

        </div>

    </main>

</div>

</body>
</html>