<?php
// 3.6.1 Implementasi array_push()
echo "<b>3.6.1</b> <br>";
$buah = ["Apel", "Jeruk", "Mangga"];
echo "<b>Sebelum array_push():</b><br>";
print_r($buah);
echo "<br><br>";

array_push($buah, "Pisang", "Melon");
echo "<b>Setelah array_push():</b><br>";
print_r($buah);
echo "<br><br>";

// 3.6.2 Implementasi array_merge()
echo "<b>3.6.2</b> <br>";
$buah_lain = ["Semangka", "Pepaya"];
echo "<b>Sebelum array_merge():</b><br>";
print_r($buah);
echo "<br>";
print_r($buah_lain);
echo "<br><br>";

$gabung = array_merge($buah, $buah_lain);
echo "<b>Setelah array_merge():</b><br>";
print_r($gabung);
echo "<br><br>";

// 3.6.3 Implementasi array_values()
echo "<b>3.6.3</b> <br>";
$buah_values = ["a" => "Durian", "b" => "Nangka", "c" => "Rambutan"];
echo "<b>Sebelum array_values():</b><br>";
print_r($buah_values);
echo "<br><br>";

$nilai = array_values($buah_values);
echo "<b>Setelah array_values():</b><br>";
print_r($nilai);
echo "<br><br>";

// 3.6.4 Implementasi array_search()
echo "<b>3.6.4</b> <br>";
echo "<b>Sebelum array_search():</b><br>";
print_r($gabung);
echo "<br><br>";

$posisi = array_search("Melon", $gabung);
echo "<b>Hasil array_search():</b><br>";
echo "Posisi 'Melon' dalam array \$gabung adalah indeks ke-$posisi";
echo "<br><br>";

// 3.6.5 Implementasi array_filter()
echo "<b>3.6.5</b> <br>";
$angka = [10, 25, 30, 45, 50, 60];
echo "<b>Sebelum array_filter():</b><br>";
print_r($angka);
echo "<br><br>";

$hasil_filter = array_filter($angka, function($n) {
    return $n > 30;
});
echo "<b>Setelah array_filter():</b><br>";
print_r($hasil_filter);
echo "<br><br>";

// 3.6.6 Implementasi fungsi sorting
echo "<b>3.6.6</b> <br>";
$buah_sort = ["Pisang", "Apel", "Jeruk", "Melon", "Semangka"];
echo "<b>Sebelum sort():</b><br>";
print_r($buah_sort);
echo "<br><br>";

// sort()
sort($buah_sort);
echo "<b>Setelah sort() (A-Z):</b><br>";
print_r($buah_sort);
echo "<br><br>";

// rsort()
rsort($buah_sort);
echo "<b>Setelah rsort() (Z-A):</b><br>";
print_r($buah_sort);
echo "<br><br>";

// asort()
$nilai_buah = ["Apel" => 50, "Jeruk" => 30, "Mangga" => 60, "Melon" => 20];
echo "<b>Sebelum asort():</b><br>";
print_r($nilai_buah);
echo "<br><br>";

asort($nilai_buah);
echo "<b>Setelah asort() (berdasarkan nilai, urut naik):</b><br>";
print_r($nilai_buah);
echo "<br><br>";

// ksort()
ksort($nilai_buah);
echo "<b>Setelah ksort() (berdasarkan key, urut naik):</b><br>";
print_r($nilai_buah);
echo "<br><br>";

// arsort()
arsort($nilai_buah);
echo "<b>Setelah arsort() (berdasarkan nilai, urut turun):</b><br>";
print_r($nilai_buah);
echo "<br><br>";

// krsort()
krsort($nilai_buah);
echo "<b>Setelah krsort() (berdasarkan key, urut turun):</b><br>";
