<?php
$fruits = array("Avocado", "Blueberry", "Cherry");
echo "I like " . $fruits[0] . ", " . $fruits[1] . " and " . $fruits[2] . ".";

// 3.1.1 Menambahkan 5 data baru ke dalam array $fruits
array_push($fruits, "Durian", "Apple", "Banana", "Manggo", "Orange");

echo "<br><br><b>3.1.1</b><br>";
echo "Isi array \$fruits setelah ditambah 5 data:<br>";
print_r($fruits);
echo "<br><br>";

$indeksTertinggi = count($fruits) - 1;
echo "Nilai pada indeks tertinggi adalah: $fruits[$indeksTertinggi] <br>";
echo "Indeks tertinggi dari array \$fruits adalah: $indeksTertinggi <br><br>";

// 3.1.2 Hapus 1 data tertentu
unset($fruits[7]);

echo "<B>3.1.2</b><br>";
echo "Isi array \$fruits setelah 1 data dihapus:<br>";
print_r($fruits);
echo "<br><br>";

$indeksTertinggiBaru = max(array_keys($fruits));
echo "Nilai pada indeks tertinggi sekarang: $fruits[$indeksTertinggiBaru] <br>";
echo "Indeks tertinggi dari array \$fruits setelah dihapus: $indeksTertinggiBaru <br><br>";

// 3.1.3 Buat array baru $veggies
$veggies = array("Bayam", "Wortel", "Kangkung");

echo "<b>3.1.3</b> <br> Isi array \$veggies:<br>";
foreach ($veggies as $sayur) {
    echo $sayur . "<br>";
}
?>
