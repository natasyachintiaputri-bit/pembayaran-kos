
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penghuni</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <style>
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        .btn-simpan {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-kembali {
            display: inline-block;
            margin-left: 10px;
            color: #64748b;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <h2>Pembayaran Kos</h2>
        <p>Sistem Pengelolaan Kos</p>

        <a href="index.php?page=dashboard">Dashboard</a>
        <a href="index.php?page=penghuni" class="active">Data Penghuni</a>
        <a href="#">Tagihan</a>
        <a href="#">Pembayaran</a>
        <a href="#">Laporan</a>
    </aside>

    <main class="main-content">

        <div class="header">
            <div>
                <h1>Tambah Penghuni</h1>
                <p>Masukkan data penghuni kos dan akun login.</p>
            </div>
        </div>

        <div class="form-box">

            <form action="index.php?page=penghuni&aksi=tambah" method="POST">

                <div class="form-group">
                    <label>Nama Penghuni</label>
                    <input type="text" name="nama"
                           placeholder="Masukkan nama penghuni" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username"
                           placeholder="Buat username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password"
                           placeholder="Buat password" required>
                </div>

                <button type="submit" class="btn-simpan">
                    Simpan Data
                </button>

                <a href="index.php?page=penghuni" class="btn-kembali">
                    Kembali
                </a>

            </form>

        </div>

    </main>

</div>

</body>
</html>
