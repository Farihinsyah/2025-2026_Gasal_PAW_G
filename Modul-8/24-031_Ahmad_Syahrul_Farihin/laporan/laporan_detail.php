<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "penjual");

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php");
    exit;
}

$username_login = $_SESSION["username"];
$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username_login'");

if (!$query) {
    die("Error dalam query: " . mysqli_error($conn));
}

$userdata = mysqli_fetch_assoc($query);

if (!$userdata) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$nama  = $userdata["nama"];
$level = $userdata["level"];

$tgl_awal  = $_POST['tgl_awal']  ?? '';
$tgl_akhir = $_POST['tgl_akhir'] ?? '';

$show_result = (!empty($tgl_awal) && !empty($tgl_akhir));
$no_data = false;

function formatTanggal($tgl) {
    return date("d-M-y", strtotime($tgl));
}

if ($show_result) {
    if ($tgl_akhir < $tgl_awal) {
        $error = "Tanggal akhir tidak boleh lebih kecil dari tanggal awal.";
        $show_result = false;
    } else {
        $grafik = mysqli_query($conn,"
            SELECT t.waktu_transaksi AS tanggal, SUM(t.total) AS total_harian
            FROM transaksi t
            WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
            GROUP BY t.waktu_transaksi
            ORDER BY t.waktu_transaksi
        ");

        $rekap = mysqli_query($conn,"
            SELECT t.waktu_transaksi AS tanggal, SUM(t.total) AS total_harian
            FROM transaksi t
            WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
            GROUP BY t.waktu_transaksi
            ORDER BY t.waktu_transaksi
        ");

        $total = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT COUNT(DISTINCT t.pelanggan_id) AS jml_pelanggan,
            SUM(t.total) AS jml_pendapatan
            FROM transaksi t
            WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
        "));

        $check_data = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT COUNT(*) AS total_data 
            FROM transaksi 
            WHERE waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
        "));

        $no_data = ($check_data['total_data'] == 0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Penjualan</title>   
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
            margin: 28px auto;
            background: white;
            padding: 18px;
            border-radius: 6px;
        }

        .header {
            background: #0d6efd;
            padding: 10px;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .btn {
            padding: 7px 13px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-right: 6px;
            font-size: 14px;
        }      

        .btn-biru { 
            background: #0d6efd; 
            color: white; 
        }       

        .btn-hijau { 
            background: #28a745; 
            color: white; 
        }     

        .btn-kuning { 
            background: #ffc107; 
            color: black; 
        }

        .date-input {
            padding: 7px;
            border: 1px solid #bbb;
            border-radius: 4px;
            margin-right: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }       

        th {
            background: #e6f0ff;
            padding: 8px;
            border: 1px solid #ccc;
        }      

        td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .chart-container {
            max-width: 700px;
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
            text-align: center;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
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

            <a href="../transaksi/transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </div>

        <div class="user-dropdown">
            <span class="user-btn" onclick="toggleDropdown('userMenu')">
                <?= htmlspecialchars($nama) ?> ▼
            </span>
            <div class="user-menu" id="userMenu">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (!$show_result): ?>
            <div class="header">Rekap Laporan Penjualan</div>

            <a href="laporan.php" class="btn btn-biru">‹ Kembali</a>
            <br><br>

            <?php if (isset($error)): ?>
                <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="date" name="tgl_awal" class="date-input" value="<?= htmlspecialchars($tgl_awal) ?>" required>
                <input type="date" name="tgl_akhir" class="date-input" value="<?= htmlspecialchars($tgl_akhir) ?>" required>
                <button type="submit" class="btn btn-hijau">Tampilkan</button>
            </form>

        <?php else: ?>
            <div class="header">
                Rekap Laporan Penjualan <?= $tgl_awal ?> sampai <?= $tgl_akhir ?>
            </div>

            <a href="laporan_detail.php" class="btn btn-biru">‹ Kembali</a>
            <br><br>

            <?php if ($no_data): ?>
                <div class="alert alert-info">
                    Tidak ada data transaksi pada rentang tanggal <?= $tgl_awal ?> sampai <?= $tgl_akhir ?>
                </div>
            <?php else: ?>
                <!-- Tombol -->
                <form action="cetak_laporan.php" method="POST" style="display:inline;">
                    <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
                    <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
                    <button type="submit" class="btn btn-kuning">Cetak</button>
                </form>

                <form action="export_excel.php" method="POST" style="display:inline;">
                    <input type="hidden" name="tgl_awal" value="<?= $tgl_awal ?>">
                    <input type="hidden" name="tgl_akhir" value="<?= $tgl_akhir ?>">
                    <button type="submit" class="btn btn-kuning">Excel</button>
                </form>

                <br><br>

                <!-- Grafik -->
                <?php
                $grafik_data = [];
                if ($grafik) {
                    mysqli_data_seek($grafik,0);
                    while ($g = mysqli_fetch_assoc($grafik)) $grafik_data[] = $g;
                }

                $labels = array_column($grafik_data, 'tanggal');
                $values = array_column($grafik_data, 'total_harian');
                ?>

                <?php if (!empty($grafik_data)): ?>
                <div class="chart-container">
                    <canvas id="grafik"></canvas>
                </div>
                <?php endif; ?>

                <!-- Tabel rekap -->
                <table>
                    <tr>
                        <th>No</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                    <?php 
                    if ($rekap) {
                        mysqli_data_seek($rekap, 0);
                        $no = 1; 
                        while($r = mysqli_fetch_assoc($rekap)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>Rp<?= number_format($r['total_harian'],0,',','.') ?></td>
                        <td><?= formatTanggal($r['tanggal']) ?></td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='3' style='text-align:center;'>Tidak ada data transaksi</td></tr>";
                    }
                    ?>
                </table>

                <!-- Tabel total -->
                <table>
                    <tr>
                        <th>Jumlah Pelanggan</th>
                        <th>Jumlah Pendapatan</th>
                    </tr>
                    <tr>
                        <td><?= $total['jml_pelanggan'] ?? 0 ?> Orang</td>
                        <td>Rp<?= number_format($total['jml_pendapatan'] ?? 0,0,',','.') ?></td>
                    </tr>
                </table>

                <!-- Chart.js -->
                <?php if (!empty($grafik_data)): ?>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                new Chart(document.getElementById('grafik'), {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($labels) ?>,
                        datasets: [{
                            label: "Total Harian",
                            data: <?= json_encode($values) ?>,
                            borderWidth: 1,
                            backgroundColor: '#0d6efd'
                        }]
                    },
                    options: { 
                        scales: { 
                            y: { beginAtZero: true } 
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
                </script>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>