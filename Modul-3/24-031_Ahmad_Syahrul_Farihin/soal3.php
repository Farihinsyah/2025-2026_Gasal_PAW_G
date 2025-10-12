<?php
$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");
echo "Andy is " . $height['Andy'] . " cm tall.";

// 3.3.1 Tambahkan 5 data baru
$height["Eric"] = 180;
$height["Farih"] = 172;
$height["Farhan"] = 169;
$height["Zamria"] = 167;
$height["Gina"] = 160;

echo "<br><br><b>3.3.1</b> <br> Data tinggi badan:<br>";
foreach ($height as $nama => $tinggi) {
    echo "$nama : $tinggi cm<br>";
}

$indeksTerakhir = array_key_last($height);
echo "<br>Indeks terakhir dari array \$height adalah: $indeksTerakhir <br>";
echo "Nilai pada indeks terakhir adalah: $height[$indeksTerakhir] cm<br><br>";

// 3.3.2 Hapus 1 data tertentu
unset($height["Charlie"]);
echo "<b>3.3.2</b> <br> Data setelah satu elemen dihapus:<br>";
foreach ($height as $nama => $tinggi) {
    echo "$nama : $tinggi cm<br>";
}

$indeksTerakhirBaru = array_key_last($height);
echo "<br>Indeks terakhir sekarang: $indeksTerakhirBaru <br>";
echo "Nilai pada indeks terakhir sekarang: $height[$indeksTerakhirBaru] cm<br><br>";

// 3.3.3 Array baru $weight
$weight = array(
    "Fadil" => 65,
    "Barny" => 60,
    "Alexander" => 58
);

$keys = array_keys($weight);
$dataKedua = $keys[1];
echo "<b>3.3.3</b> <br> Data ke-2 dari array \$weight:<br>";
echo "$dataKedua memiliki berat " . $weight[$dataKedua] . " kg<br>";
?>