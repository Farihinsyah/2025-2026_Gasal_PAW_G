<?php
// KONEKSI DATABASE
$server = "localhost";
$user = "root";
$password = "";
$db = "store";
$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

// AMBIL DATA SUPPLIER
$result = mysqli_query($conn, "SELECT * FROM supplier");

// HAPUS DATA POPUP
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM supplier WHERE id=$id");
    echo "<script>alert('Data supplier berhasil dihapus!'); window.location='index.php';</script>";
    exit;
}
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
            margin: 30px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h2 {
            color: #3498db;
            margin: 0;
        }
        a.button {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
        }
        a.add {
            background-color: #27ae60;
        }
        a.edit {
            background-color: #e84008ff;
        }
        a.delete {
            background-color: #d11501ff;
        }
        a.button:hover {
            opacity: 0.85;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #cde7fa;
            color: black;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
        }
        td:last-child {
            width: 200px;
        }
        tr:hover {
            background-color: #f2f2f2;
            transition: background-color 0.2s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Data Master Supplier</h2>
            <a href="tambah.php" class="button add">Tambah Data</a>
        </div>

        <table border="1">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Tindakan</th>
            </tr>
            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['telp']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="button edit">Edit</a>
                    <a href="?hapus=<?= $row['id'] ?>" class="button delete"
                    onclick="return confirm('Anda yakin akan menghapus supplier ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
