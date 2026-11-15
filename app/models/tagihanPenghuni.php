
<?php

require_once __DIR__ . '/../../config/database.php';

class TagihanPenghuni
{
    // Mengambil tagihan milik penghuni yang sedang login
    public static function semua($penghuni_id)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "SELECT *
             FROM tagihan
             WHERE penghuni_id = ?
             ORDER BY tahun DESC, bulan DESC"
        );

        mysqli_stmt_bind_param($query, "i", $penghuni_id);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Mengambil informasi rekening pemilik kos
    public static function rekening()
    {
        global $conn;

        $query = mysqli_query(
            $conn,
            "SELECT * FROM rekening ORDER BY id DESC"
        );

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
}
