
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

                if (!in_array($metode, ['Cash', 'Transfer'])) {
                    die("Metode pembayaran tidak valid.");
                }

                $bukti = null;
                $folder = __DIR__ . '/../../uploads/bukti_pembayaran/';

                // Upload bukti jika menggunakan transfer
                if ($metode == 'Transfer') {

                    if (
                        !isset($_FILES['bukti_transfer']) ||
                        $_FILES['bukti_transfer']['error'] != UPLOAD_ERR_OK
                    ) {
                        die("Silakan unggah bukti transfer.");
                    }

                    $file = $_FILES['bukti_transfer'];

                    $ekstensi = strtolower(
                        pathinfo($file['name'], PATHINFO_EXTENSION)
                    );

                    if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
                        die("Bukti pembayaran harus JPG, JPEG, atau PNG.");
                    }

                    if ($file['size'] > 2 * 1024 * 1024) {
                        die("Ukuran bukti maksimal 2 MB.");
                    }

                    if (getimagesize($file['tmp_name']) === false) {
                        die("File yang diunggah bukan gambar yang valid.");
                    }

                    if (!is_dir($folder)) {
                        mkdir($folder, 0775, true);
                    }

                    $bukti = bin2hex(random_bytes(16)) . '.' . $ekstensi;

                    if (!move_uploaded_file(
                        $file['tmp_name'],
                        $folder . $bukti
                    )) {
                        die("Gagal mengunggah bukti pembayaran.");
                    }
                }

                $berhasil = Pembayaran::buatPengajuan(
                    $tagihan_id,
                    $penghuni_id,
                    $metode,
                    $bukti
                );

                if ($berhasil) {
                    header("Location: index.php?page=riwayat-pembayaran&berhasil=1");
                    exit;
                }

                if ($bukti !== null && file_exists($folder . $bukti)) {
                    unlink($folder . $bukti);
                }

                header("Location: index.php?page=pembayaran-penghuni&gagal=1");
                exit;
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

                // Disamakan dengan name="pembayaran_id" pada halaman admin
                $id = (int) ($_POST['pembayaran_id'] ?? 0);

                $status = $_POST['status'] ?? '';
                $admin_id = (int) ($_SESSION['id'] ?? 0);

                if (
                    $id <= 0 ||
                    $admin_id <= 0 ||
                    !in_array($status, ['Diterima', 'Ditolak'])
                ) {
                    die("Data verifikasi tidak valid.");
                }

                if (Pembayaran::verifikasi($id, $status, $admin_id)) {
                    header("Location: index.php?page=pembayaran&berhasil=1");
                    exit;
                }

                header("Location: index.php?page=pembayaran&gagal=1");
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
