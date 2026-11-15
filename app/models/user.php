
<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    public static function login($username)
    {
        global $conn;

        if (!$conn) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }

        $query = mysqli_prepare(
            $conn,
            "SELECT * FROM users WHERE username = ? LIMIT 1"
        );

        if (!$query) {
            die("Query login gagal: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($query, "s", $username);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        return mysqli_fetch_assoc($result);
    }
}

?>
