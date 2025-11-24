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
$current_user_id = $userdata["id_user"];

if (isset($_GET['hapus_barang'])) {
    $id = $_GET['hapus_barang'];
    $cek = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM transaksi_detail WHERE barang_id = $id");
    $data = mysqli_fetch_assoc($cek);

    if ($data['jml'] > 0) {
        echo "<script>alert('Barang tidak dapat dihapus karena digunakan dalam transaksi detail'); window.location='transaksi.php';</script>";
        exit;
    } else {
        mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
        echo "<script>alert('Barang berhasil dihapus!'); window.location='transaksi.php';</script>";
        exit;
    }
}

$sql_barang = "
    SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok, s.nama AS nama_supplier
    FROM barang b
    LEFT JOIN supplier s ON b.supplier_id = s.id
";
$result_barang = mysqli_query($conn, $sql_barang);

$sql_transaksi = "
    SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama AS nama_pelanggan
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
";
$result_transaksi = mysqli_query($conn, $sql_transaksi);

$sql_detail = "
    SELECT td.transaksi_id, b.nama_barang AS nama_barang, td.harga, td.qty
    FROM transaksi_detail td
    JOIN barang b ON td.barang_id = b.id
";
$result_detail = mysqli_query($conn, $sql_detail);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
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
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #3498db;
            margin: 0;
        }

        a.button, button.button {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            border: none;
            cursor: pointer;
            font-family: Arial;
            font-size: 14px;
        }
        a.add, button.add {
            background-color: #27ae60;
        }
        a.edit, button.edit {
            background-color: #e84008ff;
        }
        a.delete, button.delete {
            background-color: #d11501ff;
        }
        a.button:hover, button.button:hover {
            opacity: 0.88;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #cde7fa;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .section {
            margin-bottom: 30px;
        }
        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 15px;
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
        <div class="header">
            <h2>Data Transaksi</h2>
            <div class="action-buttons">
                <a href="tambah_transaksi.php" class="button add">Tambah Transaksi</a>
                <a href="tambah_detail.php" class="button add">Tambah Detail</a>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Data Barang</h3>
            <table>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Supplier</th>
                    <th>Tindakan</th>
                </tr>
                <?php $no = 1; while($row = mysqli_fetch_assoc($result_barang)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['stok']) ?></td>
                    <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                    <td>
                        <a href="?hapus_barang=<?= $row['id'] ?>" class="button delete"
                        onclick="return confirm('Apakah anda yakin ingin menghapus barang <?= htmlspecialchars($row['nama_barang']) ?>?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="section">
            <h3 class="section-title">Data Transaksi</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Waktu Transaksi</th>
                    <th>Keterangan</th>
                    <th>Total</th>
                    <th>Pelanggan</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result_transaksi)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['waktu_transaksi'] ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="section">
            <h3 class="section-title">Data Transaksi Detail</h3>
            <table>
                <tr>
                    <th>Transaksi ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result_detail)): ?>
                <tr>
                    <td><?= $row['transaksi_id'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['qty']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

</body>
</html>