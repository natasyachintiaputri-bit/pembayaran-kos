<?php

$namaPenghuni = $_SESSION['nama'] ?? 'Penghuni';

function rupiahRiwayat($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function eRiwayat($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #263247;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            background: #172b4d;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 7px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #31558b;
        }

        .main {
            margin-left: 230px;
            padding: 35px;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th, td {
            padding: 13px;
            text-align: left;
            border-bottom: 1px solid #e8edf4;
        }

        th {
            background: #f3f6fa;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            background: #eee;
        }

        .menunggu {
            background: #fff2cc;
            color: #805b00;
        }

        .diterima {
            background: #d9f3df;
            color: #216b32;
        }

        .ditolak {
            background: #ffe0e0;
            color: #a12828;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        .alert {
            background: #e7f5e9;
            padding: 14px;
            border-radius: 7px;
            margin-bottom: 20px;
            color: #216b32;
        }

        @media(max-width: 700px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">

    <h2>Pembayaran Kos</h2>

    <a href="index.php?page=penghuni-dashboard">
        Dashboard
    </a>

    <a href="index.php?page=tagihan-saya">
        Tagihan Saya
    </a>

    <a href="index.php?page=pembayaran-penghuni">
        Pembayaran
    </a>

    <a href="index.php?page=riwayat-pembayaran" class="active">
        Riwayat Pembayaran
    </a>

</div>

<div class="main">

    <h1>Riwayat Pembayaran</h1>

    <p>
        Halo, <?= eRiwayat($namaPenghuni); ?>.
        Berikut daftar pembayaran kamu.
    </p>

    <?php if (isset($_GET['berhasil'])): ?>

        <div class="alert">
            Pengajuan pembayaran berhasil dikirim.
            Silakan tunggu verifikasi admin.
        </div>

    <?php endif; ?>

    <div class="card">

        <?php if (count($dataPembayaran) > 0): ?>

            <table>

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jenis Tagihan</th>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($dataPembayaran as $i => $data): ?>

                        <?php
                        $status = $data['status_verifikasi'] ?? 'Menunggu';

                        $classStatus = 'menunggu';

                        if ($status == 'Diterima') {
                            $classStatus = 'diterima';
                        } elseif ($status == 'Ditolak') {
                            $classStatus = 'ditolak';
                        }
                        ?>

                        <tr>

                            <td><?= $i + 1; ?></td>

                            <td>
                                <?= eRiwayat($data['jenis']); ?>
                            </td>

                            <td>
                                <?= eRiwayat($data['bulan']); ?>
                                <?= eRiwayat($data['tahun']); ?>
                            </td>

                            <td>
                                <?= rupiahRiwayat($data['nominal']); ?>
                            </td>

                            <td>
                                <?= eRiwayat($data['metode']); ?>
                            </td>

                            <td>
                                <?= !empty($data['tanggal_bayar'])
                                    ? date('d-m-Y H:i', strtotime($data['tanggal_bayar']))
                                    : '-'; ?>
                            </td>

                            <td>
                                <span class="status <?= $classStatus; ?>">
                                    <?= eRiwayat($status); ?>
                                </span>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                Belum ada riwayat pembayaran.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>