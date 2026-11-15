
<?php

require_once __DIR__ . '/../../models/tagihanPenghuni.php';
require_once __DIR__ . '/../../models/pembayaran.php';

$namaPenghuni = $_SESSION['nama'] ?? 'Penghuni';
$penghuni_id = (int) ($_SESSION['penghuni_id'] ?? 0);

$daftarTagihan = TagihanPenghuni::semua($penghuni_id);
$daftarRekening = Pembayaran::rekening();

function rupiahPembayaran($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function ePembayaran($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function namaBulanPembayaran($bulan)
{
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $angkaBulan = (int) $bulan;

    return $namaBulan[$angkaBulan] ?? '-';
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>

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
            margin: 0 0 30px;
        }

        .sidebar a {
            display: block;
            padding: 13px 15px;
            margin-bottom: 8px;
            color: white;
            text-decoration: none;
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

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .card {
            background: white;
            padding: 22px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .rekening {
            background: #edf4ff;
            border-left: 4px solid #315fa5;
            padding: 15px;
            margin: 15px 0;
            border-radius: 6px;
        }

        label {
            display: block;
            margin: 12px 0 7px;
            font-weight: bold;
        }

        select,
        input[type=file] {
            width: 100%;
            padding: 11px;
            border: 1px solid #d5dce8;
            border-radius: 7px;
        }

        button {
            background: #2457a6;
            color: white;
            border: 0;
            padding: 12px 18px;
            border-radius: 7px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #183f7d;
        }

        .nominal {
            font-size: 22px;
            font-weight: bold;
            color: #2457a6;
        }

        .alert {
            padding: 14px;
            background: #e7f5e9;
            color: #216b32;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .alert.error {
            background: #ffe9e9;
            color: #a12828;
        }

        .empty {
            padding: 25px;
            text-align: center;
            color: #777;
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

    <a href="index.php?page=pembayaran-penghuni" class="active">
        Pembayaran
    </a>

    <a href="index.php?page=riwayat-pembayaran">
        Riwayat Pembayaran
    </a>

</div>

<div class="main">

    <div class="header">
        <h1>Pembayaran Tagihan</h1>
        <p>
            Halo, <?= ePembayaran($namaPenghuni); ?>.
            Silakan lakukan pembayaran tagihan kos.
        </p>
    </div>

    <?php if (isset($_GET['berhasil'])): ?>
        <div class="alert">
            Pengajuan pembayaran berhasil dikirim.
            Silakan tunggu verifikasi admin.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['gagal'])): ?>
        <div class="alert error">
            Pengajuan pembayaran gagal.
            Periksa kembali tagihan dan coba lagi.
        </div>
    <?php endif; ?>

    <?php
    $adaTagihan = false;

    foreach ($daftarTagihan as $tagihan):

        if ($tagihan['status'] != 'Belum Bayar') {
            continue;
        }

        $adaTagihan = true;
    ?>

        <div class="card">

            <h3>
                <?= ePembayaran($tagihan['jenis']); ?>
            </h3>

            <p>
                Bulan:
                <?= ePembayaran(namaBulanPembayaran($tagihan['bulan'])); ?>
                <?= ePembayaran($tagihan['tahun']); ?>
            </p>

            <p class="nominal">
                <?= rupiahPembayaran($tagihan['nominal']); ?>
            </p>

            <form
                action="index.php?page=pembayaran-penghuni"
                method="POST"
                enctype="multipart/form-data"
            >

                <input
                    type="hidden"
                    name="tagihan_id"
                    value="<?= (int) $tagihan['id']; ?>"
                >

                <label>Metode Pembayaran</label>

                <select name="metode" class="metode" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Cash">Cash</option>
                    <option value="Transfer">Transfer</option>
                </select>

                <div class="informasi-transfer" style="display:none;">

                    <div class="rekening">

                        <strong>Rekening Pembayaran</strong>

                        <?php if (count($daftarRekening) > 0): ?>

                            <?php foreach ($daftarRekening as $rekening): ?>

                                <hr>

                                <p>
                                    Bank:
                                    <?= ePembayaran($rekening['nama_bank']); ?>
                                </p>

                                <p>
                                    Nomor Rekening:
                                    <strong>
                                        <?= ePembayaran($rekening['nomor_rekening']); ?>
                                    </strong>
                                </p>

                                <p>
                                    Atas Nama:
                                    <?= ePembayaran($rekening['atas_nama']); ?>
                                </p>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <p>
                                Informasi rekening belum tersedia.
                                Silakan hubungi admin.
                            </p>

                        <?php endif; ?>

                    </div>

                    <label>Upload Bukti Transfer</label>

                    <input
                        type="file"
                        name="bukti_transfer"
                        accept=".jpg,.jpeg,.png"
                        class="bukti"
                    >

                    <small>
                        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                    </small>

                </div>

                <button type="submit">
                    Ajukan Pembayaran
                </button>

            </form>

        </div>

    <?php endforeach; ?>

    <?php if (!$adaTagihan): ?>

        <div class="card empty">
            Tidak ada tagihan yang bisa dibayar saat ini.
            <br><br>
            Silakan periksa status tagihan atau riwayat pembayaran.
        </div>

    <?php endif; ?>

</div>

<script>
document.querySelectorAll('form').forEach(function(form) {

    const metode = form.querySelector('.metode');
    const informasi = form.querySelector('.informasi-transfer');
    const bukti = form.querySelector('.bukti');

    metode.addEventListener('change', function() {

        if (this.value === 'Transfer') {
            informasi.style.display = 'block';
            bukti.required = true;
        } else {
            informasi.style.display = 'none';
            bukti.required = false;
        }

    });

});
</script>

</body>
</html>
