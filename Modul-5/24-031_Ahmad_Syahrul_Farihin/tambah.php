<?php
// KONEKSI DATABASE
$server = "localhost";
$user = "root";
$password = "";
$db = "store";
$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

include 'validate.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Supplier</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background-color: #f5f6fa;
        }
        h2 {
            color: #3498db;
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
            width: 100px;
            font-weight: bold;
            color: #333;
        }
        input[type=text] {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input:hover {
            background-color: #d8e6f4ff;
            transition: background-color 0.2s ease-in-out;
        }
        .simpan, .batal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 40px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            border: none;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .simpan {
            background-color: #27ae60;
            color: white;
            margin-left: 100px;
        }
        .simpan:hover {
            background-color: #1f8a4d;
        }
        .batal {
            background-color: #e74c3c;
            color: white;
            margin-left: 10px;
        }
        .batal:hover {
            background-color: #c0392b;
        }
        .error {
            color: red;
            font-size: 13px;
            margin-left: 100px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h2>Tambah Data Master Supplier Baru</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Nama" value="<?= $_POST['nama'] ?? '' ?>">
        </div>
        <div class="error"><?= $error['nama'] ?? '' ?></div>

        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" placeholder="Telp" value="<?= $_POST['telp'] ?? '' ?>">
        </div>
        <div class="error"><?= $error['telp'] ?? '' ?></div>

        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" placeholder="Alamat" value="<?= $_POST['alamat'] ?? '' ?>">
        </div>
        <div class="error"><?= $error['alamat'] ?? '' ?></div>

        <button type="submit" class="simpan">Simpan</button>
        <a href="index.php" class="batal">Batal</a>
    </form>
</body>
</html>
