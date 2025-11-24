<?php
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 0);
session_start();

$conn = mysqli_connect("localhost", "root", "", "penjual");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_SESSION["login"])) {
    header("Location: home.php");
    exit;
}

// Proses login
$error = '';
$username_error = '';
$password_error = '';
$username_value = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $username_value = htmlspecialchars($username);

    // Validasi input kosong
    if (empty($username) && empty($password)) {
        $error = "Username dan password harus diisi!";
    } elseif (empty($username)) {
        $username_error = "Username harus diisi!";
    } elseif (empty($password)) {
        $password_error = "Password harus diisi!";
    } else {
        // Cek username di database
        $q = "SELECT * FROM user WHERE username='$username'";
        $r = mysqli_query($conn, $q);

        if (mysqli_num_rows($r) === 1) {
            $u = mysqli_fetch_assoc($r);
            $hashed_password = md5($password);

            if ($hashed_password == $u['password']) {
                // Login berhasil
                $_SESSION["login"] = true;
                $_SESSION["username"] = $u['username'];
                $_SESSION["nama"] = $u['nama'];
                $_SESSION["level"] = $u['level'];
                $_SESSION["user_id"] = $u['id_user'];

                header("Location: home.php");
                exit;
            } else {
                $password_error = "Password salah!";
            }
        } else {
            $username_error = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2f3;
            padding-top: 80px;
            margin: 0;
        }
        .card {
            width: 340px;
            background: white;
            padding: 25px 28px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            color: #3498db;
            margin-bottom: 25px;
            font-size: 22px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 5px;
            border: 1px solid #ccd1d1;
            border-radius: 5px;
            background: #f8fbff;
            box-sizing: border-box;
            font-size: 14px;
        }
        input.error-input {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            border: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background: #2980b9;
        }
        .error {
            color: #e74c3c;
            font-size: 13px;
            margin-bottom: 10px;
            display: block;
        }
        .error-global {
            text-align: center;
            color: #e74c3c;
            margin-top: 12px;
            font-size: 14px;
            padding: 8px;
            background: #fdf2f2;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Login</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" value="<?= $username_value ?>" 
               class="<?= !empty($username_error) ? 'error-input' : '' ?>">
        <?php if (!empty($username_error)): ?>
            <span class="error"><?= $username_error ?></span>
        <?php endif; ?>

        <input type="password" name="password" placeholder="Password" 
               class="<?= !empty($password_error) ? 'error-input' : '' ?>">
        <?php if (!empty($password_error)): ?>
            <span class="error"><?= $password_error ?></span>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-global"><?= $error ?></div>
    <?php endif; ?>
</div>

</body>
</html>