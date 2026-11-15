
<?php
$namaAdmin = $_SESSION['nama'] ?? 'Administrator';

function rupiahPembayaran($angka)
{
    return 'Rp' . number_format((float)$angka, 0, ',', '.');
}

function tanggalPembayaran($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    return date('d-m-Y', strtotime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
            overflow-x: auto;
        }

        .pembayaran-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pembayaran-table th,
        .pembayaran-table td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .pembayaran-table th {
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        .diterima {
            background: #dcfce7;
            color: #166534;
        }

        .ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-terima {
            display: inline-block;
            background: #16a34a;
            color: white;
            padding: 7px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-bottom: 5px;
        }

        .btn-tolak {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 7px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .aksi-form {
            display: inline-block;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <h2>Pembayaran Kos</h2>
        <p>Sistem Pengelolaan Kos</p>

        <a href="index.php?page=dashboard">Dashboard</a>
        <a href="index.php?page=penghuni">Data Penghuni</a>
        <a href="index.php?page=tagihan">Tagihan</a>
        <a href="index.php?page=pembayaran" class="active">Pembayaran</a>
        <a href="index.php?page=laporan">Laporan</a>
    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Data Pembayaran</h1>
                <p>Periksa dan verifikasi pembayaran yang diajukan penghuni kos.</p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= htmlspecialchars($namaAdmin) ?></strong>
            </div>
        </div>

        <div class="content-box">

            <table class="pembayaran-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penghuni</th>
                        <th>Tagihan</th>
                        <th>Tanggal Bayar</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($dataPembayaran)): ?>

                        <tr>
                            <td colspan="9" style="text-align:center;">
                                Belum ada data pembayaran.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($dataPembayaran as $p): ?>

                            <?php
                            $status = $p['status_verifikasi'] ?? 'Menunggu';
                            $classStatus = 'menunggu';

                            if ($status === 'Diterima') {
                                $classStatus = 'diterima';
                            } elseif ($status === 'Ditolak') {
                                $classStatus = 'ditolak';
                            }
                            ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($p['nama_penghuni'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($p['jenis'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= tanggalPembayaran($p['tanggal_bayar'] ?? null) ?>
                                </td>

                                <td>
                                    <?= rupiahPembayaran($p['nominal'] ?? 0) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($p['metode'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if (!empty($p['bukti_transfer'])): ?>

                                        <a
                                            href="uploads/bukti_pembayaran/<?= rawurlencode(basename($p['bukti_transfer'])) ?>"
                                            target="_blank">
                                            Lihat Bukti
                                        </a>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="status <?= $classStatus ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($status === 'Menunggu'): ?>

                                        <form
                                            class="aksi-form"
                                            action="index.php?page=pembayaran&aksi=verifikasi"
                                            method="POST">

                                            <input
                                                type="hidden"
                                                name="pembayaran_id"
                                                value="<?= (int)$p['id'] ?>">

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="Diterima">

                                            <button type="submit" class="btn-terima">
                                                Terima
                                            </button>
                                        </form>

                                        <form
                                            class="aksi-form"
                                            action="index.php?page=pembayaran&aksi=verifikasi"
                                            method="POST">

                                            <input
                                                type="hidden"
                                                name="pembayaran_id"
                                                value="<?= (int)$p['id'] ?>">

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="Ditolak">

                                            <button type="submit" class="btn-tolak">
                                                Tolak
                                            </button>
                                        </form>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
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
