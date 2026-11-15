
<?php

require_once __DIR__ . '/../models/tagihan.php';
require_once __DIR__ . '/../models/penghuni.php';

class TagihanController
{
    public function index()
    {
        $aksi = $_GET['aksi'] ?? '';

        // Tambah tagihan
        if ($aksi == 'tambah') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $penghuni_id = (int) ($_POST['penghuni_id'] ?? 0);
                $jenis = $_POST['jenis'] ?? '';
                $bulan = (int) ($_POST['bulan'] ?? 0);
                $tahun = (int) ($_POST['tahun'] ?? 0);
                $nominal = (float) ($_POST['nominal'] ?? 0);

                if (
                    $penghuni_id <= 0 ||
                    !in_array($jenis, ['Listrik', 'WiFi']) ||
                    $bulan < 1 || $bulan > 12 ||
                    $tahun < 2020 || $tahun > 2100 ||
                    $nominal <= 0
                ) {
                    die("Data tagihan tidak valid.");
                }

                if (Tagihan::tambah($penghuni_id, $jenis, $bulan, $tahun, $nominal)) {
                    header("Location: index.php?page=tagihan");
                    exit;
                }

                die("Gagal menambahkan tagihan.");

            } else {

                $dataPenghuni = Penghuni::semua();
                require_once __DIR__ . '/../views/tagihan/tambah.php';
                return;
            }
        }

        // Edit tagihan
        if ($aksi == 'edit') {

            $id = (int) ($_GET['id'] ?? 0);
            $tagihan = Tagihan::cari($id);

            if (!$tagihan) {
                die("Data tagihan tidak ditemukan.");
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $penghuni_id = (int) ($_POST['penghuni_id'] ?? 0);
                $jenis = $_POST['jenis'] ?? '';
                $bulan = (int) ($_POST['bulan'] ?? 0);
                $tahun = (int) ($_POST['tahun'] ?? 0);
                $nominal = (float) ($_POST['nominal'] ?? 0);

                if (
                    $penghuni_id <= 0 ||
                    !in_array($jenis, ['Listrik', 'WiFi']) ||
                    $bulan < 1 || $bulan > 12 ||
                    $tahun < 2020 || $tahun > 2100 ||
                    $nominal <= 0
                ) {
                    die("Data tagihan tidak valid.");
                }

                if (Tagihan::ubah($id, $penghuni_id, $jenis, $bulan, $tahun, $nominal)) {
                    header("Location: index.php?page=tagihan");
                    exit;
                }

                die("Gagal mengubah tagihan.");

            } else {

                $dataPenghuni = Penghuni::semua();
                require_once __DIR__ . '/../views/tagihan/edit.php';
                return;
            }
        }

        // Hapus tagihan
        if ($aksi == 'hapus') {

            $id = (int) ($_GET['id'] ?? 0);

            if ($id <= 0) {
                die("ID tagihan tidak valid.");
            }

            if (Tagihan::hapus($id)) {
                header("Location: index.php?page=tagihan");
                exit;
            }

            die("Gagal menghapus tagihan.");
        }

        // Daftar tagihan
        $dataTagihan = Tagihan::semua();
        require_once __DIR__ . '/../views/tagihan/index.php';
    }
}

?>
