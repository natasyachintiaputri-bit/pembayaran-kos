
<?php

require_once __DIR__ . '/../models/user.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = User::login($username);
            $validPassword = false;

            if ($user) {

                if (password_verify($password, $user['password'])) {
                    $validPassword = true;
                } elseif (hash_equals($user['password'], md5($password))) {
                    $validPassword = true;
                } elseif (hash_equals($user['password'], $password)) {
                    $validPassword = true;
                }
            }

            if ($user && $validPassword) {

                $_SESSION['id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['penghuni_id'] = $user['penghuni_id'] ?? null;

                if ($user['role'] === 'admin') {
                    header("Location: index.php?page=dashboard");
                    exit;
                }

                if ($user['role'] === 'penghuni') {
                    header("Location: index.php?page=penghuni-dashboard");
                    exit;
                }

            } else {
                echo "<script>alert('Username atau password salah!');</script>";
            }
        }
    }
}
