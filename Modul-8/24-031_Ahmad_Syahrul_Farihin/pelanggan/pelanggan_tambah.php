<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "penjual");

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php");
    exit;
}

$username_login = $_SESSION["username"];
$q = mysqli_query($conn, "SELECT * FROM user WHERE username='$username_login'");
$userdata = mysqli_fetch_assoc($q);

$nama1 = $userdata["nama"];
$level = $userdata["level"];

include 'validate_pelanggan.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f5f6fa;
            margin: 0;
        }

        .navbar {
            background: #0d47a1;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left-title {
            font-size: 20px;
            font-weight: bold;
            margin-right: 30px;
        }

        .menu {
            display: flex;
            align-items: center;
        }

        .menu a {
            color: white;
            margin-right: 18px;
            text-decoration: none;
            font-weight: bold;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-btn {
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background: white;
            min-width: 180px;
            border-radius: 4px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.25);
            z-index: 99;
        }

        .dropdown-content a {
            color: black;
            padding: 10px 14px;
            display: block;
            text-decoration: none;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }

        .dropdown-content a:hover {
            background: #f2f2f2;
        }

        .user-dropdown {
            position: relative;
        }

        .user-btn {
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .user-menu {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            min-width: 150px;
            border-radius: 4px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.25);
        }

        .user-menu a {
            color: black;
            padding: 10px 14px;
            display: block;
            text-decoration: none;
            border-bottom: 1px solid #eee;
        }

        .user-menu a:hover {
            background: #f4f4f4;
        }

        .container {
            padding: 20px;
            margin: 20px;
        }

        h2 { 
            color: #3498db; 
            margin-top: 0;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            width: 500px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        label {
            width: 120px;
            font-weight: bold;
            color: #333;
        }
        input[type=text], select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input:hover, select:hover {
            background-color: #d8e6f4ff;
            transition: 0.2s;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .simpan, .batal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 40px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        .simpan {
            background: #27ae60;
            color: white;
            margin-left: 120px;
            border: none;
        }
        .simpan:hover { 
            background: #1f8a4d; 
        }

        .batal {
            background: #e74c3c;
            color: white;
            margin-left: 10px;
        }
        .batal:hover { 
            background: #c0392b; 
        }

        .error {
            color: red;
            font-size: 13px;
            margin-left: 120px;
            margin-bottom: 10px;
        }
    </style>

    <script>
        function toggleDropdown(id) {
            let box = document.getElementById(id);
            box.style.display = (box.style.display === "block") ? "none" : "block";
        }
    </script>
</head>
<body>

    <div class="navbar">
        <div class="menu">
            <div class="left-title">Sistem Penjualan</div>

            <a href="../home.php">Home</a>

            <?php if($level == 1): ?>
            <div class="dropdown">
                <span class="dropdown-btn" onclick="toggleDropdown('dm')">Data Master ▼</span>
                <div class="dropdown-content" id="dm">
                    <a href="../supplier/supplier.php">Supplier</a>
                    <a href="pelanggan.php">Pelanggan</a>
                    <a href="../barang/barang.php">Barang</a>
                    <a href="../user/user.php">User</a>
                </div>
            </div>
            <?php endif; ?>

            <a href="../transaksi/transaksi.php">Transaksi</a>
            <a href="../laporan/laporan.php">Laporan</a>
        </div>

        <div class="user-dropdown">
            <span class="user-btn" onclick="toggleDropdown('userMenu')">
                <?= $nama1 ?> ▼
            </span>
            <div class="user-menu" id="userMenu">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Tambah Data Pelanggan Baru</h2>

        <?php if (isset($error['db'])): ?>
            <div class="error"><?= $error['db'] ?></div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="form-group">
                <label>Kode Pelanggan</label>
                <input type="text" name="id" placeholder="Kode Pelanggan"
                       value="<?= htmlspecialchars($_POST['id'] ?? '') ?>">
            </div>
            <div class="error"><?= $error['id'] ?? '' ?></div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" placeholder="Nama Pelanggan"
                       value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
            </div>
            <div class="error"><?= $error['nama'] ?? '' ?></div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" name="jenis_kelamin" value="L" 
                            <?= (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'L') ? 'checked' : '' ?>>
                        <span>Laki-laki</span>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="jenis_kelamin" value="P"
                            <?= (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'P') ? 'checked' : '' ?>>
                        <span>Perempuan</span>
                    </div>
                </div>
            </div>
            <div class="error"><?= $error['jenis_kelamin'] ?? '' ?></div>

            <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="telp" placeholder="Nomor Telepon"
                       value="<?= htmlspecialchars($_POST['telp'] ?? '') ?>">
            </div>
            <div class="error"><?= $error['telp'] ?? '' ?></div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" placeholder="Alamat"
                       value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>">
            </div>
            <div class="error"><?= $error['alamat'] ?? '' ?></div>

            <button type="submit" class="simpan">Simpan</button>
            <a href="pelanggan.php" class="batal">Batal</a>
        </form>
    </div>

</body>
</html>