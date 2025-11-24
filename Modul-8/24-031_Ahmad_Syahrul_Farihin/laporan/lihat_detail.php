<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "penjual");

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php");
    exit;
}

$username_login = $_SESSION["username"];
$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username_login'");
$userdata = mysqli_fetch_assoc($query);

$nama  = $userdata["nama"];
$level = $userdata["level"];

$transaksi_id = $_GET['id'] ?? 0;

$transaksi_query = mysqli_query($conn, "
    SELECT t.*, p.nama AS nama_pelanggan, p.alamat AS alamat_pelanggan
    FROM transaksi t
    INNER JOIN pelanggan p ON t.pelanggan_id = p.id
    WHERE t.id = $transaksi_id
");

if (!$transaksi_query || mysqli_num_rows($transaksi_query) == 0) {
    die("Transaksi tidak ditemukan");
}

$transaksi = mysqli_fetch_assoc($transaksi_query);

$detail_query = mysqli_query($conn, "
    SELECT td.*, b.nama_barang, b.kode_barang
    FROM transaksi_detail td
    INNER JOIN barang b ON td.barang_id = b.id
    WHERE td.transaksi_id = $transaksi_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
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
            background: white;
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #333;
            margin: 0;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
        }

        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #cde7fa;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .total {
            background: #cde7fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>

    <script>
        function toggleDropdown() {
            let box = document.getElementById('userMenu');
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
                <a href="#" style="cursor: pointer;">Data Master ▼</a>
                <div class="user-menu">
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
            <span class="user-btn" onclick="toggleDropdown()">
                <?= $nama ?> ▼
            </span>
            <div class="user-menu" id="userMenu">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h2>Detail Transaksi #<?= $transaksi['id'] ?></h2>
            <a href="laporan.php" class="btn">Kembali</a>
        </div>

        <div class="info-section">
            <div class="info-grid">
                <div>
                    <div class="info-label">ID Transaksi</div>
                    <div><?= $transaksi['id'] ?></div>
                </div>
                <div>
                    <div class="info-label">Tanggal</div>
                    <div><?= $transaksi['waktu_transaksi'] ?></div>
                </div>
                <div>
                    <div class="info-label">Pelanggan</div>
                    <div><?= $transaksi['nama_pelanggan'] ?></div>
                </div>
                <div>
                    <div class="info-label">Alamat</div>
                    <div><?= $transaksi['alamat_pelanggan'] ?></div>
                </div>
            </div>
            <div>
                <div class="info-label">Keterangan</div>
                <div><?= $transaksi['keterangan'] ?></div>
            </div>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            
            <?php if (mysqli_num_rows($detail_query) > 0): ?>
                <?php 
                $no = 1;
                while($detail = mysqli_fetch_assoc($detail_query)): 
                    $subtotal = $detail['harga'] * $detail['qty'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $detail['kode_barang'] ?></td>
                    <td><?= $detail['nama_barang'] ?></td>
                    <td>Rp<?= number_format($detail['harga'], 0, ',', '.') ?></td>
                    <td><?= $detail['qty'] ?></td>
                    <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-data">Tidak ada data barang</td>
                </tr>
            <?php endif; ?>
        </table>

        <div class="total">
            Total: Rp<?= number_format($transaksi['total'], 0, ',', '.') ?>
        </div>
    </div>

</body>
</html>