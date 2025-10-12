<?php
$fruits = array("Avocado", "Blueberry", "Cherry");
$arrlength = count($fruits);

for($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}

// 3.2.1 Tambah 5 data baru ke array $fruits
$buah_baru = array("Durian", "Apple", "Banana", "Mango", "Orange");

for ($i = 0; $i < count($buah_baru); $i++) {
    $fruits[] = $buah_baru[$i];
}

$arrlength = count($fruits);

echo "<br><b>3.2.1</b><br>";
echo "Daftar buah dalam array \$fruits:<br>";
for ($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x] . "<br>";
}

echo "<br>Jumlah data dalam array \$fruits saat ini: $arrlength <br><br>";

echo "Tidak perlu mengubah struktur FOR di baris 5–8, karena variabel arrlength sudah otomatis menyesuaikan jumlah data terbaru. <br><br>";

// 3.2.2 Array baru $veggies
$veggies = array("Bayam", "Kangkung", "Wortel");

$jumlah_veggies = count($veggies);

echo "<b>3.2.1</b><br>";
echo "Daftar sayuran dalam array \$veggies:<br>";
for ($i = 0; $i < $jumlah_veggies; $i++) {
    echo $veggies[$i] . "<br>";
}

echo "<br> cukup memodifikasi sedikit bagian nama array, isi dan panjangnya.";
?>