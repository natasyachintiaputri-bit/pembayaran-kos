
<?php
$namaAdmin = $_SESSION['nama'] ?? 'Administrator';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tagihan</title>

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
                <h1>Tambah Tagihan</h1>
                <p>Masukkan informasi tagihan penghuni kos.</p>
            </div>

            <div class="admin-info">
                Admin:
                <strong><?= htmlspecialchars($namaAdmin) ?></strong>
            </div>
        </div>

        <div class="form-box">

            <form action="index.php?page=tagihan&aksi=tambah" method="POST">

                <div class="form-group">
                    <label>Nama Penghuni</label>

                    <select name="penghuni_id" required>
                        <option value="">-- Pilih Penghuni --</option>

                        <?php foreach ($dataPenghuni as $penghuni): ?>
                            <option value="<?= $penghuni['id'] ?>">
                                <?= htmlspecialchars($penghuni['nama']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Tagihan</label>

                    <select name="jenis" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Listrik">Listrik</option>
                        <option value="WiFi">WiFi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Bulan</label>

                    <select name="bulan" required>
                        <option value="">-- Pilih Bulan --</option>
                        <?php
                        $bulan = [
                            1 => 'Januari', 2 => 'Februari',
                            3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober',
                            11 => 'November', 12 => 'Desember'
                        ];

                        foreach ($bulan as $angka => $nama):
                        ?>
                            <option value="<?= $angka ?>">
                                <?= $nama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tahun</label>

                    <select name="tahun" required>
                        <option value="">-- Pilih Tahun --</option>

                        <?php for ($tahun = 2026; $tahun <= 2030; $tahun++): ?>
                            <option value="<?= $tahun ?>">
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
                        placeholder="Contoh: 150000"
                        min="1"
                        step="1"
                        required
                    >
                </div>

                <button type="submit" class="btn-simpan">
                    Simpan Tagihan
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
