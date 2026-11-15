
<?php

require_once __DIR__ . '/../../config/database.php';

class Laporan
{
    // Mengambil ringkasan seluruh tagihan
    public static function ringkasan()
    {
        global $conn;

        $query = mysqli_query(
            $conn,
            "SELECT
                COUNT(*) AS total_tagihan,
                SUM(CASE WHEN status = 'Lunas' THEN 1 ELSE 0 END) AS tagihan_lunas,
                SUM(CASE WHEN status = 'Belum Bayar' THEN 1 ELSE 0 END) AS tagihan_belum,
                SUM(CASE WHEN status = 'Menunggu Konfirmasi' THEN 1 ELSE 0 END) AS tagihan_menunggu
             FROM tagihan"
        );

        return mysqli_fetch_assoc($query);
    }

    // Menghitung total pembayaran yang sudah diterima
    public static function totalPembayaran()
    {
        global $conn;

        $query = mysqli_query(
            $conn,
            "SELECT COALESCE(SUM(nominal), 0) AS total_pemasukan
             FROM pembayaran
             WHERE status_verifikasi = 'Diterima'"
        );

        return mysqli_fetch_assoc($query);
    }

    // Mengambil seluruh riwayat pembayaran
    public static function semuaPembayaran()
    {
        global $conn;

        $query = mysqli_query(
            $conn,
            "SELECT
                pembayaran.*,
                penghuni.nama AS nama_penghuni,
                tagihan.jenis,
                tagihan.bulan,
                tagihan.tahun
             FROM pembayaran
             JOIN tagihan ON pembayaran.tagihan_id = tagihan.id
             JOIN penghuni ON tagihan.penghuni_id = penghuni.id
             ORDER BY pembayaran.created_at DESC"
        );

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
}
?>
