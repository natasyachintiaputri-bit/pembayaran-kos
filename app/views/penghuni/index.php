
<?php
$namaAdmin = $_SESSION['nama'] ?? 'Administrator';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penghuni</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .btn-tambah {
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
        }

        .btn-edit {
            color: #2563eb;
            text-decoration: none;
        }

        .btn-hapus {
            color: #dc2626;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <h2>Pembayaran Kos</h2>
        <p>Sistem Pengelolaan Kos</p>

        <a href="index.php?page=dashboard">Dashboard</a>

        <a href="index.php?page=penghuni" class="active">
            Data Penghuni
        </a>

        <a href="index.php?page=tagihan">Tagihan</a>

        <a href="index.php?page=pembayaran">Pembayaran</a>

        <a href="index.php?page=laporan">Laporan</a>
    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Data Penghuni</h1>
                <p>Kelola data penghuni kos.</p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= htmlspecialchars($namaAdmin) ?></strong>
            </div>
        </div>

        <div class="content-box">

            <a href="index.php?page=penghuni&aksi=tambah"
               class="btn-tambah">
                + Tambah Penghuni
            </a>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penghuni</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($dataPenghuni)): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($dataPenghuni as $penghuni): ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($penghuni['nama']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($penghuni['created_at']) ?>
                                </td>

                                <td>
                                    <a class="btn-edit"
                                       href="index.php?page=penghuni&aksi=edit&id=<?= (int)$penghuni['id'] ?>">
                                        Edit
                                    </a>

                                    |

                                    <a class="btn-hapus"
                                       href="index.php?page=penghuni&aksi=hapus&id=<?= (int)$penghuni['id'] ?>"
                                       onclick="return confirm('Yakin ingin menghapus penghuni ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Belum ada data penghuni.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>

    </main>

</div>

</body>
</html>
