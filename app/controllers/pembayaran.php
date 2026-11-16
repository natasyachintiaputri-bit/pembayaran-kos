
<?php

require_once __DIR__ . '/../models/pembayaran.php';

class PembayaranController
{
    // Halaman pembayaran penghuni dan pembayaran admin
    public function index()
    {
        $role = $_SESSION['role'] ?? '';

        // PROSES PEMBAYARAN PENGHUNI
        if ($role == 'penghuni') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $tagihan_id = (int) ($_POST['tagihan_id'] ?? 0);
                $penghuni_id = (int) ($_SESSION['penghuni_id'] ?? 0);
                $metode = $_POST['metode'] ?? '';

                if ($tagihan_id <= 0 || $penghuni_id <= 0) {
                    die("Data pembayaran tidak valid.");
                }

                if (!in_array($metode, ['Cash', 'Transfer'], true)) {
                    die("Metode pembayaran tidak valid.");
                }

                // PEMBAYARAN TRANSFER MELALUI MIDTRANS
                if ($metode == 'Transfer') {

                    $hasil = Pembayaran::buatTransaksiMidtrans(
                        $tagihan_id,
                        $penghuni_id
                    );

                    if (
                        !empty($hasil['success']) &&
                        !empty($hasil['redirect_url'])
                    ) {
                        header(
                            "Location: " . $hasil['redirect_url']
                        );
                        exit;
                    }

                    error_log(
                        'Gagal membuat transaksi Midtrans: ' .
                        ($hasil['message'] ?? 'Kesalahan tidak diketahui')
                    );

                    header(
                        "Location: index.php?page=pembayaran-penghuni&gagal=1"
                    );
                    exit;
                }

                // PEMBAYARAN CASH TETAP MENGGUNAKAN PROSES LAMA
                if ($metode == 'Cash') {

                    $berhasil = Pembayaran::buatPengajuan(
                        $tagihan_id,
                        $penghuni_id,
                        'Cash',
                        null
                    );

                    if ($berhasil) {
                        header(
                            "Location: index.php?page=riwayat-pembayaran&berhasil=1"
                        );
                        exit;
                    }

                    header(
                        "Location: index.php?page=pembayaran-penghuni&gagal=1"
                    );
                    exit;
                }
            }

            $penghuni_id = (int) ($_SESSION['penghuni_id'] ?? 0);

            require_once __DIR__ . '/../views/penghuni_dashboard/pembayaran.php';
            return;
        }

        // PROSES VERIFIKASI ADMIN
        if ($role == 'admin') {

            if (($_GET['aksi'] ?? '') == 'verifikasi') {

                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    header("Location: index.php?page=pembayaran");
                    exit;
                }

                $id = (int) ($_POST['pembayaran_id'] ?? 0);
                $status = $_POST['status'] ?? '';
                $admin_id = (int) ($_SESSION['id'] ?? 0);

                if (
                    $id <= 0 ||
                    $admin_id <= 0 ||
                    !in_array($status, ['Diterima', 'Ditolak'], true)
                ) {
                    die("Data verifikasi tidak valid.");
                }

                if (Pembayaran::verifikasi($id, $status, $admin_id)) {
                    header(
                        "Location: index.php?page=pembayaran&berhasil=1"
                    );
                    exit;
                }

                header(
                    "Location: index.php?page=pembayaran&gagal=1"
                );
                exit;
            }

            $dataPembayaran = Pembayaran::semua();

            require_once __DIR__ . '/../views/pembayaran/index.php';
            return;
        }

        header("Location: index.php?page=login");
        exit;
    }

    // Halaman riwayat pembayaran penghuni
    public function riwayat()
    {
        if (($_SESSION['role'] ?? '') != 'penghuni') {
            header("Location: index.php?page=login");
            exit;
        }

        $penghuni_id = (int) ($_SESSION['penghuni_id'] ?? 0);

        $dataPembayaran = Pembayaran::riwayat($penghuni_id);

        require_once __DIR__ . '/../views/penghuni_dashboard/riwayat.php';
    }
}

?>
