<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "penjual";

$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Hapus barang
if (isset($_GET['hapus_barang'])) {
    $id = $_GET['hapus_barang'];

    // Cek apakah barang digunakan di transaksi_detail
    $cek = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM transaksi_detail WHERE barang_id = $id");
    $data = mysqli_fetch_assoc($cek);

    if ($data['jml'] > 0) {
        // Jika barang masih dipakai di transaksi detail
        echo "<script>alert('Barang tidak dapat dihapus karena digunakan dalam transaksi detail'); window.location='index.php';</script>";
        exit;
    } else {
        // Jika tidak digunakan langsung hapus
        mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
        echo "<script>alert('Barang berhasil dihapus!'); window.location='index.php';</script>";
        exit;
    }
}

// Ambil data barang
$sql_barang = "
    SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok, s.nama AS nama_supplier
    FROM barang b
    LEFT JOIN supplier s ON b.supplier_id = s.id
";
$result_barang = mysqli_query($conn, $sql_barang);

// Ambil data transaksi
$sql_transaksi = "
    SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama AS nama_pelanggan
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
";
$result_transaksi = mysqli_query($conn, $sql_transaksi);

// Ambil data transaksi detail
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
    <title>Pengelolaan Master Detail Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            background-color: #fafafa;
        }
        h1, h2 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-right: 5px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-delete {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 4px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .container {
            margin-bottom: 35px;
        }
    </style>
</head>
<body>
<!-- TABEL BARANG -->
<div class="container">
    <h1>Pengelolaan Master Detail</h1>
    <h2>Data Barang</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Nama Supplier</th>
            <th>Action</th>
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
                <a href="?hapus_barang=<?= $row['id'] ?>" class="btn-delete"
                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- TABEL TRANSAKSI -->
<div class="container">
    <h2>Data Transaksi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Waktu Transaksi</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Nama Pelanggan</th>
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

    <div style="margin-top:10px;">
        <a href="transaksi_tambah.php" class="btn">Tambah Transaksi</a>
        <a href="detail_tambah.php" class="btn">Tambah Transaksi Detail</a>
    </div>
</div>

<!-- TABEL TRANSAKSI DETAIL -->
<div class="container">
    <h2>Data Transaksi Detail</h2>
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

</body>
</html>