<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "penjual";

$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

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

// Proses simpan detail transaksi
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validasi Barang
    if (empty($barang_id)) {
        $error[] = "Barang harus dipilih.";
    }

    // Validasi Transaksi
    if (empty($transaksi_id)) {
        $error[] = "Transaksi harus dipilih.";
    }

    // Validasi Quantity
    if (!is_numeric($qty) || $qty <= 0) {
        $error[] = "Quantity harus berupa angka positif.";
    }

    // Cek apakah barang sudah pernah ditambahkan pada transaksi tersebut
    if (empty($error)) {
        $cek = mysqli_query($conn, "
            SELECT * FROM transaksi_detail 
            WHERE transaksi_id = '$transaksi_id' AND barang_id = '$barang_id'
        ");
        if (mysqli_num_rows($cek) > 0) {
            $error[] = "Barang yang dipilih sudah ada di detail transaksi ini.";
        }
    }

    // Jika validasi lolos, simpan data
    if (empty($error)) {
        $ambil_harga = mysqli_query($conn, "SELECT harga FROM barang WHERE id = $barang_id");
        $harga_data = mysqli_fetch_assoc($ambil_harga);
        $harga_satuan = $harga_data['harga'];

        // Simpan harga satuan (bukan total)
        $query = "
            INSERT INTO transaksi_detail (transaksi_id, barang_id, qty, harga)
            VALUES ('$transaksi_id', '$barang_id', '$qty', '$harga_satuan')
        ";

        if (mysqli_query($conn, $query)) {
            // Update total transaksi berdasarkan jumlah qty × harga
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

            header("Location: index.php");
            exit;
        } else {
            $error[] = "Gagal menambahkan detail transaksi: " . mysqli_error($conn);
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
            margin: 30px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #2c3e50;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            width: 420px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type=text], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Tambah Detail Transaksi</h2>

    <?php if (!empty($error)): ?>
        <div class="error">
            <?php foreach ($error as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Pilih Barang:</label>
        <select name="barang_id">
            <option value="">Pilih Barang</option>
            <?php mysqli_data_seek($barang, 0); while ($row = mysqli_fetch_assoc($barang)): ?>
                <option value="<?= $row['id'] ?>" <?= ($barang_id == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama_barang']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>ID Transaksi:</label>
        <select name="transaksi_id">
            <option value="">Pilih Transaksi</option>
            <?php mysqli_data_seek($transaksi, 0); while ($row = mysqli_fetch_assoc($transaksi)): ?>
                <option value="<?= $row['id'] ?>" <?= ($transaksi_id == $row['id']) ? 'selected' : '' ?>>
                    Transaksi #<?= $row['id'] ?> - <?= htmlspecialchars($row['nama_pelanggan']) ?> (<?= htmlspecialchars($row['waktu_transaksi']) ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Quantity:</label>
        <input type="text" name="qty" placeholder="Masukkan jumlah barang" value="<?= htmlspecialchars($qty) ?>">

        <button type="submit" class="btn">Tambah Detail Transaksi</button>
    </form>
</body>
</html>