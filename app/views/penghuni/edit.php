
<?php
$namaPenghuni = htmlspecialchars($penghuni['nama'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penghuni</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h2 {
            color: #1e293b;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button, .back {
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #2563eb;
        }

        .back {
            background: #64748b;
            display: inline-block;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Edit Data Penghuni</h2>

    <form method="POST" action="">
        <label>Nama Penghuni</label>

        <input
            type="text"
            name="nama"
            value="<?= $namaPenghuni ?>"
            required
        >

        <button type="submit">Simpan Perubahan</button>

        <a href="index.php?page=penghuni" class="back">
            Kembali
        </a>
    </form>

</div>

</body>
</html>
