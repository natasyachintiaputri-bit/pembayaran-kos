
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pembayaran Kos</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            width: 380px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #1e293b;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .login-box label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 20px;
            outline: none;
        }

        .login-box input:focus {
            border-color: #2563eb;
        }

        .login-box button {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .login-box button:hover {
            background: #1d4ed8;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <div class="login-box">

        <h2>Pembayaran Kos</h2>

        <p class="subtitle">
            Sistem Pembayaran Listrik dan WiFi Bulanan
        </p>

        <form action="" method="POST">

            <label>Username</label>
            <input type="text" name="username"
                   placeholder="Masukkan username" required>

            <label>Password</label>
            <input type="password" name="password"
                   placeholder="Masukkan password" required>

            <button type="submit">Login</button>

        </form>

        <div class="footer">
            Sistem Pembayaran Kos &copy; 2026
        </div>

    </div>

</body>
</html>
