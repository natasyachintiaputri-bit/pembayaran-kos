<?php

require_once __DIR__ . '/../../models/tagihanPenghuni.php';

$penghuni_id = (int) ($_SESSION['penghuni_id'] ?? 0);

$daftarTagihan = TagihanPenghuni::semua($penghuni_id);

$totalTagihan = 0;

foreach ($daftarTagihan as $tagihan) {
    if ($tagihan['status'] != 'Lunas') {
        $totalTagihan += (float) $tagihan['nominal'];
    }
}

$namaBulan = [
    1 => 'Januari', 2 => 'Februari',
    3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober',
    11 => 'November', 12 => 'Desember'
];

function rupiah($angka)
{
    return 'Rp' . number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Saya</title>

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

        .content-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .content-box h2 {
            margin-bottom: 20px;
        }

        .tagihan-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tagihan-table th,
        .tagihan-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .tagihan-table th {
            background: #f8fafc;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        .belum {
            background: #fef3c7;
            color: #92400e;
        }

        .menunggu {
            background: #dbeafe;
            color: #1e40af;
        }

        .lunas {
            background: #dcfce7;
            color: #166534;
        }

        .total-box {
            margin-top: 20px;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
        }

        @media(max-width: 700px) {
            .content-box {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Pembayaran Kos</h2>

        <div class="menu-penghuni">

            <a href="index.php?page=penghuni-dashboard">
                Dashboard
            </a>

            <a href="index.php?page=tagihan-saya" class="active">
                Tagihan Saya
            </a>

            <a href="index.php?page=pembayaran-penghuni">
                Pembayaran
            </a>

            <a href="index.php?page=riwayat-pembayaran">
                Riwayat Pembayaran
            </a>

        </div>

    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Tagihan Saya</h1>
                <p>Daftar tagihan listrik dan WiFi bulanan.</p>
            </div>
        </div>

        <div class="content-box">

            <h2>Daftar Tagihan</h2>

            <table class="tagihan-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Tagihan</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($daftarTagihan)): ?>

                        <tr>
                            <td colspan="5" style="text-align:center;">
                                Belum ada tagihan yang diterbitkan.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($daftarTagihan as $index => $tagihan): ?>

                            <?php
                            $status = $tagihan['status'];
                            $kelasStatus = 'belum';

                            if ($status == 'Lunas') {
                                $kelasStatus = 'lunas';
                            } elseif ($status == 'Menunggu Konfirmasi') {
                                $kelasStatus = 'menunggu';
                            }
                            ?>

                            <tr>
                                <td><?= $index + 1 ?></td>

                                <td>
                                    <?= htmlspecialchars($tagihan['jenis']) ?>
                                </td>

                                <td>
                                    <?= $namaBulan[(int)$tagihan['bulan']] ?? '-' ?>
                                    <?= htmlspecialchars($tagihan['tahun']) ?>
                                </td>

                                <td>
                                    <?= rupiah($tagihan['nominal']) ?>
                                </td>

                                <td>
                                    <span class="status <?= $kelasStatus ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

            <div class="total-box">
                Total Tagihan: <?= rupiah($totalTagihan) ?>
            </div>

        </div>

    </main>

</div>

</body>
</html>