<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "penjual";

$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

$tgl_awal  = $_POST['tgl_awal']  ?? '';
$tgl_akhir = $_POST['tgl_akhir'] ?? '';

$show_result = (!empty($tgl_awal) && !empty($tgl_akhir));

function formatTanggal($tgl) {
    return date("d-M-y", strtotime($tgl));
}

if ($show_result) {

    // Grafik per tanggal
    $grafik = mysqli_query($conn,"
        SELECT t.waktu_transaksi AS tanggal, SUM(t.total) AS total_harian
        FROM transaksi t
        WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
        GROUP BY t.waktu_transaksi
        ORDER BY t.waktu_transaksi
    ");

    // Tabel rekap
    $rekap = mysqli_query($conn,"
        SELECT t.waktu_transaksi AS tanggal, SUM(t.total) AS total_harian
        FROM transaksi t
        WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
        GROUP BY t.waktu_transaksi
        ORDER BY t.waktu_transaksi
    ");

    // Total keseluruhan
    $total = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT COUNT(DISTINCT t.pelanggan_id) AS jml_pelanggan,
        SUM(t.total) AS jml_pendapatan
        FROM transaksi t
        WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
    "));
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
            background: #333;
            padding: 14px;
            display: flex;
            justify-content: space-between;
            color: white;
        }        
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
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
        <?php if (!$show_result): ?>
            <div class="header">Rekap Laporan Penjualan</div>

            <a href="transaksi.php" class="btn btn-biru">‹ Kembali</a>
            <br><br>

            <form method="POST">
                <input type="date" name="tgl_awal" class="date-input" required>
                <input type="date" name="tgl_akhir" class="date-input" required>
                <button type="submit" class="btn btn-hijau">Tampilkan</button>
            </form>

        <?php else: ?>
            <div class="header">
                Rekap Laporan Penjualan <?= $tgl_awal ?> sampai <?= $tgl_akhir ?>
            </div>

            <a href="report_transaksi.php" class="btn btn-biru">‹ Kembali</a>
            <br><br>

            <!-- Tombl -->
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
            mysqli_data_seek($grafik,0);
            while ($g = mysqli_fetch_assoc($grafik)) $grafik_data[] = $g;

            $labels = array_column($grafik_data, 'tanggal');
            $values = array_column($grafik_data, 'total_harian');
            ?>

            <div style="max-width:700px;">
                <canvas id="grafik"></canvas>
            </div>

            <!-- Tabel rekap -->
            <table>
                <tr>
                    <th>No</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
                <?php $no = 1; while($r = mysqli_fetch_assoc($rekap)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>Rp<?= number_format($r['total_harian'],0,',','.') ?></td>
                    <td><?= formatTanggal($r['tanggal']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Tabel total -->
            <table>
                <tr>
                    <th>Jumlah Pelanggan</th>
                    <th>Jumlah Pendapatan</th>
                </tr>
                <tr>
                    <td><?= $total['jml_pelanggan'] ?> Orang</td>
                    <td>Rp<?= number_format($total['jml_pendapatan'],0,',','.') ?></td>
                </tr>
            </table>

            <!-- Chart.jsS -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            new Chart(document.getElementById('grafik'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: "Total Harian",
                        data: <?= json_encode($values) ?>,
                        borderWidth: 1
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
    </div>
</body>
</html>