
<?php

require_once __DIR__ . '/../../config/database.php';

class Penghuni
{
    public static function semua()
    {
        global $conn;

        $query = mysqli_query(
            $conn,
            "SELECT * FROM penghuni ORDER BY id DESC"
        );

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

    public static function tambah($nama, $username, $password)
    {
        global $conn;

        mysqli_begin_transaction($conn);

        try {
            $query = mysqli_prepare(
                $conn,
                "INSERT INTO penghuni (nama) VALUES (?)"
            );

            mysqli_stmt_bind_param($query, "s", $nama);
            mysqli_stmt_execute($query);

            $penghuni_id = mysqli_insert_id($conn);

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $queryUser = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (penghuni_id, nama, username, password, role)
                VALUES (?, ?, ?, ?, 'penghuni')"
            );

            mysqli_stmt_bind_param(
                $queryUser,
                "isss",
                $penghuni_id,
                $nama,
                $username,
                $passwordHash
            );

            mysqli_stmt_execute($queryUser);

            mysqli_commit($conn);
            return true;

        } catch (Throwable $e) {
            mysqli_rollback($conn);
            return false;
        }
    }

    public static function cari($id)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "SELECT * FROM penghuni WHERE id = ?"
        );

        mysqli_stmt_bind_param($query, "i", $id);
        mysqli_stmt_execute($query);

        return mysqli_fetch_assoc(
            mysqli_stmt_get_result($query)
        );
    }

    public static function ubah($id, $nama)
    {
        global $conn;

        mysqli_begin_transaction($conn);

        try {
            $query = mysqli_prepare(
                $conn,
                "UPDATE penghuni SET nama = ? WHERE id = ?"
            );

            mysqli_stmt_bind_param($query, "si", $nama, $id);
            mysqli_stmt_execute($query);

            $queryUser = mysqli_prepare(
                $conn,
                "UPDATE users SET nama = ?
                 WHERE penghuni_id = ? AND role = 'penghuni'"
            );

            mysqli_stmt_bind_param($queryUser, "si", $nama, $id);
            mysqli_stmt_execute($queryUser);

            mysqli_commit($conn);
            return true;

        } catch (Throwable $e) {
            mysqli_rollback($conn);
            return false;
        }
    }

    public static function hapus($id)
    {
        global $conn;

        // Cek apakah penghuni masih memiliki tagihan
        $query = mysqli_prepare(
            $conn,
            "SELECT COUNT(*) AS total
             FROM tagihan
             WHERE penghuni_id = ?"
        );

        mysqli_stmt_bind_param($query, "i", $id);
        mysqli_stmt_execute($query);

        $hasil = mysqli_fetch_assoc(
            mysqli_stmt_get_result($query)
        );

        if ($hasil['total'] > 0) {
            return false;
        }

        mysqli_begin_transaction($conn);

        try {
            // Hapus akun login penghuni
            $queryUser = mysqli_prepare(
                $conn,
                "DELETE FROM users
                 WHERE penghuni_id = ? AND role = 'penghuni'"
            );

            mysqli_stmt_bind_param($queryUser, "i", $id);
            mysqli_stmt_execute($queryUser);

            // Hapus data penghuni
            $query = mysqli_prepare(
                $conn,
                "DELETE FROM penghuni WHERE id = ?"
            );

            mysqli_stmt_bind_param($query, "i", $id);
            mysqli_stmt_execute($query);

            mysqli_commit($conn);
            return true;

        } catch (Throwable $e) {
            mysqli_rollback($conn);
            return false;
        }
    }
}
