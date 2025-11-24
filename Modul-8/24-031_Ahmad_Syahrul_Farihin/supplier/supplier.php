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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus'])) {
    $id = $_POST['hapus'];
    $query = "DELETE FROM supplier WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data supplier berhasil dihapus');</script>";
        echo "<script>window.location.href='supplier.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$result = mysqli_query($conn, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Master Supplier</title>
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
            box-shadow: 0px 2px6px rgba(0,0,0,0.1);
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
        td:last-child {
            width: 200px;
        }
        tr:hover {
            background-color: #f2f2f2;
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

        function confirmDelete(id, nama) {
            if (confirm('Apakah Anda yakin ingin menghapus supplier "' + nama + '"?')) {
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
                    <a href="supplier.php">Supplier</a>
                    <a href="../pelanggan/pelanggan.php">Pelanggan</a>
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
                <?= $nama ?> ▼
            </span>
            <div class="user-menu" id="userMenu">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h2>Data Master Supplier</h2>
            <a href="supplier_tambah.php" class="button add">Tambah Data</a>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Tindakan</th>
            </tr>

            <?php $no=1; while($row=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['telp'] ?></td>
                <td><?= $row['alamat'] ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="supplier_edit.php?id=<?= $row['id'] ?>" class="button edit">Edit</a>
                        
                        <form method="POST" class="delete-form" id="delete-form-<?= $row['id'] ?>">
                            <input type="hidden" name="hapus" value="<?= $row['id'] ?>">
                            <button type="button" 
                                    class="button delete" 
                                    onclick="confirmDelete(<?= $row['id'] ?>, '<?= $row['nama'] ?>')">
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