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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus'])) {
    $id = $_POST['hapus'];
    
    // Hapus detail transaksi terlebih dahulu
    $delete_detail = mysqli_query($conn, "DELETE FROM transaksi_detail WHERE transaksi_id = $id");
    
    // Kemudian hapus transaksi
    $query = "DELETE FROM transaksi WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: laporan.php");
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$sql = "
    SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama AS nama_pelanggan
    FROM transaksi t
    INNER JOIN pelanggan p ON t.pelanggan_id = p.id
    ORDER BY t.id ASC
";
$data = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Master Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
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
            width: 90%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 6px;
        }

        .header-bar {
            background: #0d6efd;
            color: white;
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-biru {
            background: #0d6efd;
            color: white;
            padding: 8px 13px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
        }

        .btn-hijau {
            background: #28a745;
            color: white;
            padding: 8px 13px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-detail {
            background: #0dcaf0;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-hapus {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: Arial;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #e6f0ff;
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 14px;
        }

        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .button-container {
            margin-bottom: 20px;
            text-align: right;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .delete-form {
            display: inline;
        }
    </style>

    <script>
        function toggleDropdown(id) {
            let box = document.getElementById(id);
            box.style.display = (box.style.display === "block") ? "none" : "block";
        }

        function confirmDelete(id, keterangan) {
            if (confirm('Apakah Anda yakin ingin menghapus transaksi "' + keterangan + '"?')) {
                document.getElementById('delete-form-' + id).submit();
            }
            return false;
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

            <a href="../transaksi/transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
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
        <div class="header-bar">Data Master Transaksi</div>

        <div class="button-container">
            <a href="laporan_detail.php" class="btn-biru">Lihat Laporan Penjualan</a>
            <a href="../transaksi/tambah_transaksi.php" class="btn-hijau">Tambah Transaksi</a>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Waktu Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Keterangan</th>
                <th>Total</th>
                <th>Tindakan</th>
            </tr>

            <?php $no = 1; while($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['id'] ?></td>
                <td><?= $row['waktu_transaksi'] ?></td>
                <td><?= $row['nama_pelanggan'] ?></td>
                <td><?= $row['keterangan'] ?></td>
                <td>Rp<?= number_format($row['total'],0,',','.') ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="lihat_detail.php?id=<?= $row['id'] ?>" class="btn-detail">Lihat Detail</a>
                        
                        <form method="POST" class="delete-form" id="delete-form-<?= $row['id'] ?>">
                            <input type="hidden" name="hapus" value="<?= $row['id'] ?>">
                            <button type="button" 
                                    class="btn-hapus" 
                                    onclick="confirmDelete(<?= $row['id'] ?>, '<?= $row['keterangan'] ?>')">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>