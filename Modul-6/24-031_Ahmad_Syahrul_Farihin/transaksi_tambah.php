<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "penjual";

$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Ambil data pelanggan untuk dropdown
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");

// Inisialisasi variabel agar tetap tampil saat validasi gagal
$waktu_transaksi = $_POST['waktu_transaksi'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$total = 0;
$pelanggan_id = $_POST['pelanggan_id'] ?? '';

$error = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hari_ini = date('Y-m-d');

    // Validasi tanggal
    if ($waktu_transaksi < $hari_ini) {
        $error[] = "Tanggal transaksi tidak boleh lebih kecil dari tanggal hari ini.";
    }

    // Validasi keterangan
    if (strlen(trim($keterangan)) < 3) {
        $error[] = "Keterangan transaksi minimal harus 3 karakter.";
    }

    // Validasi pelanggan
    if (empty($pelanggan_id)) {
        $error[] = "Silakan pilih pelanggan terlebih dahulu.";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($error)) {
        $query = "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
                  VALUES ('$waktu_transaksi', '$keterangan', 0, '$pelanggan_id')";

        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit;
        } else {
            $error[] = "Gagal menyimpan data transaksi: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
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
            padding: 20px 25px;
            border-radius: 6px;
            width: 420px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>Tambah Transaksi</h2>

    <!-- Pesan error tampil di atas form -->
    <?php if (!empty($error)): ?>
        <div class="error">
            <?php foreach ($error as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Waktu Transaksi:</label>
        <input type="date" name="waktu_transaksi" min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($waktu_transaksi) ?>">

        <label>Keterangan:</label>
        <textarea name="keterangan" rows="3" placeholder="Masukkan keterangan transaksi"><?= htmlspecialchars($keterangan) ?></textarea>

        <label>Total:</label>
        <input type="text" name="total" value="<?= htmlspecialchars($total) ?>" readonly>

        <label>Pelanggan:</label>
        <select name="pelanggan_id">
            <option value="">Pilih Pelanggan</option>
            <?php while ($row = mysqli_fetch_assoc($pelanggan)): ?>
                <option value="<?= $row['id'] ?>" <?= ($pelanggan_id == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="btn">Tambah Transaksi</button>
    </form>
</body>
</html>