
<?php

require_once __DIR__ . '/../../config/database.php';

class Tagihan
{
    public static function semua()
    {
        global $conn;

        $query = mysqli_query(
            $conn,
            "SELECT tagihan.*, penghuni.nama AS nama_penghuni
             FROM tagihan
             JOIN penghuni ON tagihan.penghuni_id = penghuni.id
             ORDER BY tagihan.tahun DESC, tagihan.bulan DESC"
        );

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

    public static function tambah($penghuni_id, $jenis, $bulan, $tahun, $nominal)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "INSERT INTO tagihan
            (penghuni_id, jenis, bulan, tahun, nominal)
            VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $query,
            "isiid",
            $penghuni_id,
            $jenis,
            $bulan,
            $tahun,
            $nominal
        );

        return mysqli_stmt_execute($query);
    }

    public static function cari($id)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "SELECT * FROM tagihan WHERE id = ?"
        );

        mysqli_stmt_bind_param($query, "i", $id);
        mysqli_stmt_execute($query);

        return mysqli_fetch_assoc(mysqli_stmt_get_result($query));
    }

    public static function ubah($id, $penghuni_id, $jenis, $bulan, $tahun, $nominal)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "UPDATE tagihan
             SET penghuni_id = ?, jenis = ?, bulan = ?,
                 tahun = ?, nominal = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $query,
            "isiidi",
            $penghuni_id,
            $jenis,
            $bulan,
            $tahun,
            $nominal,
            $id
        );

        return mysqli_stmt_execute($query);
    }

    public static function hapus($id)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "DELETE FROM tagihan WHERE id = ?"
        );

        mysqli_stmt_bind_param($query, "i", $id);

        return mysqli_stmt_execute($query);
    }
}

?>
