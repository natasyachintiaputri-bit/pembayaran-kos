
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
    <title>Edit Tagihan</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 650px;
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn-simpan {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 7px;
            cursor: pointer;
        }

        .btn-kembali {
            display: inline-block;
            color: #475569;
            text-decoration: none;
            margin-left: 12px;
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
        <a href="index.php?page=tagihan" class="active">Tagihan</a>
        <a href="#">Pembayaran</a>
        <a href="#">Laporan</a>
    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Edit Tagihan</h1>
                <p>Ubah informasi tagihan penghuni kos.</p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= htmlspecialchars($namaAdmin) ?></strong>
            </div>
        </div>

        <div class="form-box">

            <form action="index.php?page=tagihan&aksi=edit&id=<?= (int)$tagihan['id'] ?>" method="POST">

                <div class="form-group">
                    <label>Nama Penghuni</label>

                    <select name="penghuni_id" required>
                        <?php foreach ($dataPenghuni as $penghuni): ?>
                            <option
                                value="<?= $penghuni['id'] ?>"
                                <?= $penghuni['id'] == $tagihan['penghuni_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($penghuni['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Tagihan</label>

                    <select name="jenis" required>
                        <option value="Listrik" <?= $tagihan['jenis'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>
                        <option value="WiFi" <?= $tagihan['jenis'] == 'WiFi' ? 'selected' : '' ?>>WiFi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Bulan</label>

                    <select name="bulan" required>
                        <?php foreach ($namaBulan as $angka => $nama): ?>
                            <option
                                value="<?= $angka ?>"
                                <?= (int)$tagihan['bulan'] == $angka ? 'selected' : '' ?>>
                                <?= $nama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tahun</label>

                    <select name="tahun" required>
                        <?php for ($tahun = 2026; $tahun <= 2030; $tahun++): ?>
                            <option
                                value="<?= $tahun ?>"
                                <?= (int)$tagihan['tahun'] == $tahun ? 'selected' : '' ?>>
                                <?= $tahun ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nominal Tagihan (Rp)</label>

                    <input
                        type="number"
                        name="nominal"
                        value="<?= htmlspecialchars($tagihan['nominal']) ?>"
                        min="1"
                        step="1"
                        required>
                </div>

                <button type="submit" class="btn-simpan">
                    Simpan Perubahan
                </button>

                <a href="index.php?page=tagihan" class="btn-kembali">
                    Kembali
                </a>

            </form>

        </div>

    </main>

</div>

</body>
</html>
