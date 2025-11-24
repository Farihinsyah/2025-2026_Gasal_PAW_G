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

$nama  = $userdata["nama"];
$level = $userdata["level"];

// Ambil data barang dan transaksi untuk dropdown
$barang = mysqli_query($conn, "SELECT * FROM barang");
$transaksi = mysqli_query($conn, "
    SELECT t.id, t.waktu_transaksi, p.nama AS nama_pelanggan
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
");

// Inisialisasi variabel agar tetap tampil saat validasi gagal
$barang_id = $_POST['barang_id'] ?? '';
$transaksi_id = $_POST['transaksi_id'] ?? '';
$qty = $_POST['qty'] ?? '';

$error = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($barang_id)) {
        $error['barang'] = "Barang harus dipilih.";
    }

    if (empty($transaksi_id)) {
        $error['transaksi'] = "Transaksi harus dipilih.";
    }

    if (empty($qty)) {
        $error['qty'] = "Quantity harus diisi.";
    } elseif (!is_numeric($qty) || $qty <= 0) {
        $error['qty'] = "Quantity harus berupa angka positif.";
    }

    if (empty($error)) {
        $cek = mysqli_query($conn, "
            SELECT * FROM transaksi_detail 
            WHERE transaksi_id = '$transaksi_id' AND barang_id = '$barang_id'
        ");
        if (mysqli_num_rows($cek) > 0) {
            $error['barang'] = "Barang yang dipilih sudah ada di detail transaksi ini.";
        }
    }

    if (empty($error)) {
        $ambil_harga = mysqli_query($conn, "SELECT harga FROM barang WHERE id = $barang_id");
        $harga_data = mysqli_fetch_assoc($ambil_harga);
        $harga_satuan = $harga_data['harga'];

        $query = "
            INSERT INTO transaksi_detail (transaksi_id, barang_id, qty, harga)
            VALUES ('$transaksi_id', '$barang_id', '$qty', '$harga_satuan')
        ";

        if (mysqli_query($conn, $query)) {
            $updateTotal = "
                UPDATE transaksi 
                SET total = (
                    SELECT SUM(qty * harga) 
                    FROM transaksi_detail 
                    WHERE transaksi_id = '$transaksi_id'
                )
                WHERE id = '$transaksi_id'
            ";
            mysqli_query($conn, $updateTotal);

            header("Location: transaksi.php");
            exit;
        } else {
            $error['db'] = "Gagal menambahkan detail transaksi: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Detail Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
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
            width: 150px;
            font-weight: bold;
            color: #333;
        }

        input[type=text], select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: Arial;
        }

        input:hover, select:hover {
            background-color: #d8e6f4;
            transition: 0.2s;
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
            border: none;
        }

        .simpan {
            background: #27ae60;
            color: white;
            margin-left: 150px;
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
            margin-left: 150px;
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
                    <a href="../pelanggan/pelanggan.php">Pelanggan</a>
                    <a href="../barang/barang.php">Barang</a>
                    <a href="../user/user.php">User</a>
                </div>
            </div>
            <?php endif; ?>

            <a href="transaksi.php">Transaksi</a>
            <a href="../laporan/laporan.php">Laporan</a>
        </div>

        <div class="user-dropdown">
            <span class="user-btn" onclick="toggleDropdown('userMenu')">
                <?= $nama ?> ▼
            </span>
            <div class="user-menu" id="userMenu">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Tambah Detail Transaksi</h2>

        <?php if (isset($error['db'])): ?>
            <div class="error"><?= $error['db'] ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Transaksi</label>
                <select name="transaksi_id">
                    <option value="">Pilih Transaksi</option>
                    <?php mysqli_data_seek($transaksi, 0); while ($row = mysqli_fetch_assoc($transaksi)): ?>
                        <option value="<?= $row['id'] ?>" <?= ($transaksi_id == $row['id']) ? 'selected' : '' ?>>
                            Transaksi #<?= $row['id'] ?> - <?= htmlspecialchars($row['nama_pelanggan']) ?> (<?= htmlspecialchars($row['waktu_transaksi']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="error"><?= $error['transaksi'] ?? '' ?></div>

            <div class="form-group">
                <label>Barang</label>
                <select name="barang_id">
                    <option value="">Pilih Barang</option>
                    <?php mysqli_data_seek($barang, 0); while ($row = mysqli_fetch_assoc($barang)): ?>
                        <option value="<?= $row['id'] ?>" <?= ($barang_id == $row['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['nama_barang']) ?> - Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="error"><?= $error['barang'] ?? '' ?></div>

            <div class="form-group">
                <label>Quantity</label>
                <input type="text" name="qty" placeholder="Masukkan jumlah barang" value="<?= htmlspecialchars($qty) ?>">
            </div>
            <div class="error"><?= $error['qty'] ?? '' ?></div>

            <button type="submit" name="submit" class="simpan">Simpan</button>
            <a href="transaksi.php" class="batal">Batal</a>
        </form>
    </div>
</body>
</html>