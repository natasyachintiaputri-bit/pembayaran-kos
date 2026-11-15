
<?php

session_start();

require_once __DIR__ . '/config/database.php';

// Logout
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();

    header("Location: index.php?page=login");
    exit;
}

$page = $_GET['page'] ?? 'login';
$role = $_SESSION['role'] ?? '';

// Dashboard Admin
if ($page == 'dashboard') {
    if (isset($_SESSION['id']) && $role == 'admin') {
        require_once __DIR__ . '/app/views/dashboard/index.php';
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Data Penghuni
} elseif ($page == 'penghuni') {
    if (isset($_SESSION['id']) && $role == 'admin') {
        require_once __DIR__ . '/app/controllers/penghuni.php';
        $controller = new PenghuniController();
        $controller->index();
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Data Tagihan
} elseif ($page == 'tagihan') {
    if (isset($_SESSION['id']) && $role == 'admin') {
        require_once __DIR__ . '/app/controllers/tagihan.php';
        $controller = new TagihanController();
        $controller->index();
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Data Pembayaran
} elseif ($page == 'pembayaran') {
    if (isset($_SESSION['id']) && $role == 'admin') {
        require_once __DIR__ . '/app/controllers/pembayaran.php';
        $controller = new PembayaranController();
        $controller->index();
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Laporan Admin
} elseif ($page == 'laporan') {
    if (isset($_SESSION['id']) && $role == 'admin') {
        require_once __DIR__ . '/app/controllers/laporan.php';
        $controller = new LaporanController();
        $controller->index();
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Dashboard Penghuni
} elseif ($page == 'penghuni-dashboard') {
    if (isset($_SESSION['id']) && $role == 'penghuni') {
        require_once __DIR__ . '/app/views/penghuni_dashboard/index.php';
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Tagihan Saya
} elseif ($page == 'tagihan-saya') {
    if (isset($_SESSION['id']) && $role == 'penghuni') {
        require_once __DIR__ . '/app/views/penghuni_dashboard/tagihan.php';
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Pembayaran Penghuni
} elseif ($page == 'pembayaran-penghuni') {
    if (isset($_SESSION['id']) && $role == 'penghuni') {
        require_once __DIR__ . '/app/controllers/pembayaran.php';
        $controller = new PembayaranController();
        $controller->index();
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Riwayat Pembayaran Penghuni
} elseif ($page == 'riwayat-pembayaran') {
    if (isset($_SESSION['id']) && $role == 'penghuni') {
        require_once __DIR__ . '/app/controllers/pembayaran.php';
        $controller = new PembayaranController();
        $controller->riwayat();
    } else {
        header("Location: index.php?page=login");
        exit;
    }

// Login
} else {
    if (isset($_SESSION['id'])) {
        if ($role == 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }

        if ($role == 'penghuni') {
            header("Location: index.php?page=penghuni-dashboard");
            exit;
        }
    }

    require_once __DIR__ . '/app/controllers/auth.php';
    $auth = new AuthController();
    $auth->login();

    require_once __DIR__ . '/app/views/auth/login.php';
}

?>
