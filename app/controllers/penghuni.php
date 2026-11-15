
<?php

require_once __DIR__ . '/../models/penghuni.php';

class PenghuniController
{
    public function index()
    {
        $aksi = $_GET['aksi'] ?? '';

        // PROSES TAMBAH
        if ($aksi == 'tambah') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $nama = trim($_POST['nama'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';

                if ($nama == '' || $username == '' || $password == '') {
                    die("Semua data wajib diisi.");
                }

                if (Penghuni::tambah($nama, $username, $password)) {
                    header("Location: index.php?page=penghuni");
                    exit;
                }

                die("Gagal menambahkan penghuni. Username mungkin sudah digunakan.");
            }

            require_once __DIR__ . '/../views/penghuni/tambah.php';
            return;
        }

        // PROSES EDIT
        if ($aksi == 'edit') {

            $id = (int) ($_GET['id'] ?? 0);

            if ($id <= 0) {
                die("ID penghuni tidak valid.");
            }

            $penghuni = Penghuni::cari($id);

            if (!$penghuni) {
                die("Data penghuni tidak ditemukan.");
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $nama = trim($_POST['nama'] ?? '');

                if ($nama == '') {
                    die("Nama penghuni wajib diisi.");
                }

                if (Penghuni::ubah($id, $nama)) {
                    header("Location: index.php?page=penghuni");
                    exit;
                }

                die("Gagal mengubah data penghuni.");
            }

            require_once __DIR__ . '/../views/penghuni/edit.php';
            return;
        }

        // PROSES HAPUS
        if ($aksi == 'hapus') {

            $id = (int) ($_GET['id'] ?? 0);

            if ($id <= 0) {
                die("ID penghuni tidak valid.");
            }

            if (Penghuni::hapus($id)) {
                header("Location: index.php?page=penghuni");
                exit;
            }

            die("Gagal menghapus penghuni. Data mungkin masih terhubung dengan tagihan atau pembayaran.");
        }

        // MENAMPILKAN DATA
        $dataPenghuni = Penghuni::semua();

        require_once __DIR__ . '/../views/penghuni/index.php';
    }
}

?>
