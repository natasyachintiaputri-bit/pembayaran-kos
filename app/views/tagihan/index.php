
<?php
$namaAdmin = $_SESSION['nama'] ?? 'Administrator';

$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
    4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tagihan</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
            overflow-x: auto;
        }

        .btn-tambah {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            border-radius: 7px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .tagihan-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tagihan-table th,
        .tagihan-table td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .tagihan-table th {
            background: #f8fafc;
        }

        .btn-edit {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 7px 13px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-hapus {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 7px 13px;
            border-radius: 6px;
            text-decoration: none;
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

        <a href="index.php?page=tagihan" class="active">
            Tagihan
        </a>

        <a href="index.php?page=pembayaran">
            Pembayaran
        </a>

        <a href="index.php?page=laporan">
            Laporan
        </a>
    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Data Tagihan</h1>
                <p>Kelola tagihan listrik dan WiFi penghuni kos.</p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= htmlspecialchars($namaAdmin) ?></strong>
            </div>
        </div>

        <div class="content-box">

            <a href="index.php?page=tagihan&aksi=tambah" class="btn-tambah">
                + Tambah Tagihan
            </a>

            <table class="tagihan-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penghuni</th>
                        <th>Jenis Tagihan</th>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($dataTagihan)): ?>

                        <tr>
                            <td colspan="7" style="text-align:center;">
                                Belum ada data tagihan.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($dataTagihan as $tagihan): ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($tagihan['nama_penghuni']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($tagihan['jenis']) ?>
                                </td>

                                <td>
                                    <?= $namaBulan[(int)$tagihan['bulan']] ?>
                                    <?= htmlspecialchars($tagihan['tahun']) ?>
                                </td>

                                <td>
                                    Rp<?= number_format((float)$tagihan['nominal'], 0, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($tagihan['status']) ?>
                                </td>

                                <td>
                                    <a
                                        href="index.php?page=tagihan&aksi=edit&id=<?= (int)$tagihan['id'] ?>"
                                        class="btn-edit">
                                        Edit
                                    </a>

                                    <a
                                        href="index.php?page=tagihan&aksi=hapus&id=<?= (int)$tagihan['id'] ?>"
                                        class="btn-hapus"
                                        onclick="return confirm('Yakin ingin menghapus tagihan ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>

    </main>

</div>

</body>
</html>
