<?php
$conn = mysqli_connect("localhost", "root", "", "penjual");

$tgl_awal  = $_POST['tgl_awal']  ?? '';
$tgl_akhir = $_POST['tgl_akhir'] ?? '';

if (!$tgl_awal || !$tgl_akhir) {
    die("Tanggal tidak lengkap");
}

// Ambil data grafik per tanggal
$grafik = mysqli_query($conn,"
    SELECT t.waktu_transaksi AS tanggal, SUM(t.total) AS total_harian
    FROM transaksi t
    WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
    GROUP BY t.waktu_transaksi
    ORDER BY t.waktu_transaksi
");

// Ambil data tabel
$rekap = mysqli_query($conn,"
    SELECT t.waktu_transaksi AS tanggal, SUM(t.total) AS total_harian
    FROM transaksi t
    WHERE t.waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
    GROUP BY t.waktu_transaksi
    ORDER BY t.waktu_transaksi
");

// Total pelanggan & pendapatan
$total = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
           SUM(total) AS jml_pendapatan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_penjualan</title>
    <style>
        body { 
            font-family: Arial; 
            margin: 20px;
            background: white;
        }
        .header {
            background: #0d6efd;
            color: white;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }       
        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 14px;
        }
        .chart-container {
            width: 100%;
            max-width: 900px;
            margin-top: 25px;
        }
        canvas {
            width: 900px !important;
            height: 350px !important;
        }
    </style>

    <script>
        window.onload = function() {
            setTimeout(() => window.print(), 800);
        };
    </script>
</head>
<body>
    <div class="header">
        Rekap Laporan Penjualan <?= $tgl_awal ?> sampai <?= $tgl_akhir ?>
    </div>

    <?php
    $grafik_data = [];
    mysqli_data_seek($grafik,0);
    while ($g = mysqli_fetch_assoc($grafik)) $grafik_data[] = $g;

    $labels = array_column($grafik_data, 'tanggal');
    $values = array_column($grafik_data, 'total_harian');
    ?>

    <!-- Grafik -->
    <div class="chart-container">
        <canvas id="grafik"></canvas>
    </div>

    <!-- Tabel rekap -->
    <table>
        <tr>
            <th>No</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>
        <?php $no=1; while($r = mysqli_fetch_assoc($rekap)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td>Rp<?= number_format($r['total_harian'],0,',','.') ?></td>
            <td><?= $r['tanggal'] ?></td>
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.addEventListener("load", function () {
            const ctx = document.getElementById('grafik').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: "Total Harian",
                        data: <?= json_encode($values) ?>,
                        backgroundColor: "rgba(54,162,235,0.7)",
                        borderColor: "rgba(54,162,235,1)",
                        borderWidth: 1
                    }]
                },
                options: { 
                    responsive: false,
                    animation: false,
                    scales: { 
                        y: { beginAtZero: true }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</body>
</html>