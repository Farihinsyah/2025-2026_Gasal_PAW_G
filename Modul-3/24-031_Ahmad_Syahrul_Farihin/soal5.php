<?php
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665"),
);
for($row = 0; $row < 3; $row++) {
    echo "<p><b>Row number $row</b></p>";
    echo "<ul>";
    for($col = 0; $col < 3; $col++) {
        echo "<li>" . $students[$row][$col] . "</li>";
    }
    echo "</ul>";
}

// 3.5.1 Tambahkan 5 data baru
$students[] = array("Eric", "220404", "0812345699");
$students[] = array("Farih", "220405", "0812345611");
$students[] = array("Farhan", "220406", "0812345622");
$students[] = array("Zamria", "220407", "0812345633");
$students[] = array("Gina", "220408", "0812345644");

// 3.5.2 Tampilkan dalam bentuk tabel
echo "<h3>Daftar Data Mahasiswa</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>
        <th>Nama</th>
        <th>NIM</th>
        <th>Mobile</th>
      </tr>";

for ($row = 0; $row < count($students); $row++) {
    echo "<tr>";
    echo "<td>" . $students[$row][0] . "</td>";
    echo "<td>" . $students[$row][1] . "</td>";
    echo "<td>" . $students[$row][2] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>