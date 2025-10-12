<?php
$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");

foreach($height as $x => $x_value) {
    echo "Key=" . $x . ", Value=" . $x_value;
    echo "<br>";
}

// 3.4.1 Tambahkan 5 data baru
$height["Eric"] = 180;
$height["Farih"] = 172;
$height["Farhan"] = 168;
$height["Zamria"] = 160;
$height["Gina"] = 166;

echo "<br><b>3.4.1</b> <br> Data tinggi badan:<br>";
foreach ($height as $nama => $tinggi) {
    echo "Nama: " . $nama . ", Tinggi: " . $tinggi . " cm<br>";
}

echo "<br> Tidak perlu mengubah struktur perulangan foreach (baris 4–7) karena foreach secara otomatis menyesuaikan jumlah elemen dalam array. <br><br>";

// 3.4.2 Buat array baru $weight
$weight = array(
    "Andy" => 65,
    "Barry" => 58,
    "Charlie" => 62
);

echo "<b>3.4.2</b> <br> Data berat badan:<br>";
foreach ($weight as $nama => $berat) {
    echo "Nama: " . $nama . ", Berat: " . $berat . " kg<br>";
}

echo "<br> Cukup sedikit memodifikasi skrip yang sudah ada (mengganti nama variabel array dan isi datanya)";
?>