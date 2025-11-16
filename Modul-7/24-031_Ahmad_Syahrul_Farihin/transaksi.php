<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "penjual";

$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
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
            background: #333;
            padding: 14px;
            color: white;
            display: flex;
            justify-content: space-between;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
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
    </style>
</head>
<body>
    <div class="navbar">
        <div>Penjualan XYZ</div>
        <div>
            <a href="#">Supplier</a>
            <a href="#">Barang</a>
            <a href="transaksi.php">Transaksi</a>
        </div>
    </div>

    <div class="container">
        <div class="header-bar">Data Master Transaksi</div>

        <div class="button-container">
            <a href="report_transaksi.php" class="btn-biru">Lihat Laporan Penjualan</a>
            <a href="#" class="btn-hijau">Tambah Transaksi</a>
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
                    <a href="#" class="btn-detail">Lihat Detail</a>
                    <a onclick="return confirm('Yakin hapus?')" href="#" class="btn-hapus">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>