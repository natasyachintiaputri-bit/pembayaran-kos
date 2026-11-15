
<?php

function eLaporan($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiahLaporan($angka)
{
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
}

$namaAdmin = $_SESSION['nama'] ?? 'Administrator';

$totalTagihan = (int) ($ringkasan['total_tagihan'] ?? 0);
$tagihanLunas = (int) ($ringkasan['tagihan_lunas'] ?? 0);
$tagihanBelum = (int) ($ringkasan['tagihan_belum'] ?? 0);
$tagihanMenunggu = (int) ($ringkasan['tagihan_menunggu'] ?? 0);
$totalPemasukan = (float) ($totalPembayaran['total_pemasukan'] ?? 0);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran Kos</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
            overflow-x: auto;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-top: 25px;
        }

        .report-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .report-card p {
            color: #718096;
            margin: 0 0 12px;
            font-size: 14px;
        }

        .report-card h2 {
            margin: 0;
            font-size: 25px;
            color: #172b4d;
        }

        .total-card {
            margin-top: 20px;
        }

        .laporan-table {
            width: 100%;
            border-collapse: collapse;
        }

        .laporan-table th,
        .laporan-table td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .laporan-table th {
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        .menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .diterima {
            background: #dcfce7;
            color: #166534;
        }

        .ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .bukti {
            color: #2563eb;
            text-decoration: none;
        }

        .bukti:hover {
            text-decoration: underline;
        }

        .kosong {
            text-align: center;
            padding: 30px;
            color: #718096;
        }

        @media (max-width: 1000px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Pembayaran Kos</h2>
        <p>Sistem Pengelolaan Kos</p>

        <a href="index.php?page=dashboard">
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

        <a href="index.php?page=laporan" class="active">
            Laporan
        </a>

    </aside>

    <main class="main-content">

        <div class="header">

            <div>
                <h1>Laporan Pembayaran Kos</h1>
                <p>
                    Halo, <?= eLaporan($namaAdmin) ?>.
                    Berikut ringkasan data pembayaran kos.
                </p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= eLaporan($namaAdmin) ?></strong>
            </div>

        </div>

        <div class="cards">

            <div class="report-card">
                <p>Total Tagihan</p>
                <h2><?= $totalTagihan ?></h2>
            </div>

            <div class="report-card">
                <p>Tagihan Lunas</p>
                <h2><?= $tagihanLunas ?></h2>
            </div>

            <div class="report-card">
                <p>Belum Bayar</p>
                <h2><?= $tagihanBelum ?></h2>
            </div>

            <div class="report-card">
                <p>Menunggu Konfirmasi</p>
                <h2><?= $tagihanMenunggu ?></h2>
            </div>

        </div>

        <div class="report-card total-card">
            <p>Total Uang Pembayaran Diterima</p>
            <h2><?= rupiahLaporan($totalPemasukan) ?></h2>
        </div>

        <div class="content-box">

            <h3>Riwayat Pembayaran</h3>

            <?php if (!empty($dataPembayaran)): ?>

                <table class="laporan-table">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Penghuni</th>
                            <th>Jenis Tagihan</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Tanggal</th>
                            <th>Bukti Transfer</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($dataPembayaran as $i => $data): ?>

                            <?php
                            $status = $data['status_verifikasi'] ?? 'Menunggu';
                            $classStatus = 'menunggu';

                            if ($status === 'Diterima') {
                                $classStatus = 'diterima';
                            } elseif ($status === 'Ditolak') {
                                $classStatus = 'ditolak';
                            }
                            ?>

                            <tr>

                                <td><?= $i + 1 ?></td>

                                <td>
                                    <?= eLaporan($data['nama_penghuni'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= eLaporan($data['jenis'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= eLaporan($data['bulan'] ?? '-') ?>
                                    <?= eLaporan($data['tahun'] ?? '') ?>
                                </td>

                                <td>
                                    <?= rupiahLaporan($data['nominal'] ?? 0) ?>
                                </td>

                                <td>
                                    <?= eLaporan($data['metode'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= !empty($data['tanggal_bayar'])
                                        ? date('d-m-Y H:i', strtotime($data['tanggal_bayar']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?php if (!empty($data['bukti_transfer'])): ?>

                                        <a
                                            class="bukti"
                                            href="uploads/bukti_pembayaran/<?= rawurlencode(basename($data['bukti_transfer'])) ?>"
                                            target="_blank">
                                            Lihat Bukti
                                        </a>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="status <?= $classStatus ?>">
                                        <?= eLaporan($status) ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="kosong">
                    Belum ada riwayat pembayaran.
                </div>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>
</html>
