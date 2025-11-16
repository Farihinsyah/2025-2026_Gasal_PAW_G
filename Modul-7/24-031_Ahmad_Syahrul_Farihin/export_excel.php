<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

$conn = mysqli_connect("localhost", "root", "", "penjual");

$tgl_awal  = $_POST['tgl_awal']  ?? '';
$tgl_akhir = $_POST['tgl_akhir'] ?? '';

function formatTanggalExcel($tgl) {
    $bulan = [
        "01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun",
        "07"=>"Jul","08"=>"Agu","09"=>"Sep","10"=>"Okt","11"=>"Nov","12"=>"Des"
    ];

    $tahun = substr($tgl, 2, 2);
    $bln   = $bulan[substr($tgl, 5, 2)];
    $hari  = substr($tgl, 8, 2);

    return "$hari-$bln-$tahun";
}

function rupiahExcel($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Ambil data tabel rekap
$rekap = mysqli_query($conn,"
    SELECT waktu_transaksi AS tanggal, SUM(total) AS total_harian
    FROM transaksi
    WHERE waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
    GROUP BY waktu_transaksi
    ORDER BY waktu_transaksi ASC
");

// Ambil data total pelanggan & pendapatan
$total = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan,
    SUM(total) AS jml_pendapatan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));
?>

<b>Rekap Laporan Penjualan <?= $tgl_awal ?> sampai <?= $tgl_akhir ?></b>

<table border="1">
    <tr>
        <th>No</th>
        <th>Total</th>
        <th>Tanggal</th>
    </tr>

    <?php $no=1; while($r=mysqli_fetch_assoc($rekap)): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= rupiahExcel($r['total_harian']) ?></td>
        <td><?= formatTanggalExcel($r['tanggal']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<br>

<table border="1">
    <tr>
        <th>Jumlah Pelanggan</th>
        <th>Jumlah Pendapatan</th>
    </tr>
    <tr>
        <td><?= $total['jml_pelanggan'] ?> Orang</td>
        <td><?= rupiahExcel($total['jml_pendapatan']) ?></td>
    </tr>
</table>