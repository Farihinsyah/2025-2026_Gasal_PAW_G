<?php
// Data input
$username   = "farihinsyah06";
$email      = "farihinsyah06@gmail.com";
$url        = "https://www2.trunojoyo.ac.id";
$umur       = "19";
$nim        = "240411100031";
$nilai      = "81.3";
$tanggal    = 6; 
$bulan      = 2; 
$tahun      = 2025;

// 1. Regular Expression
echo "<h3>1. Regular Expression</h3>";
if (preg_match("/^[a-zA-Z0-9_]+$/", trim($username))) {
    echo "Username valid: $username<br>";
} else {
    echo "Username tidak valid<br>";
}

// 2. String Functions
echo "<h3>2. String Functions</h3>";
echo "Lowercase: " . strtolower(trim($username)) . "<br>";
echo "Uppercase: " . strtoupper(trim($username)) . "<br>";
echo "Panjang username: " . strlen($username) . " karakter<br>";
echo "Huruf pertama kapital: " . ucfirst($username) . "<br>";

// 3. Filter Functions
echo "<h3>3. Filter Functions</h3>";
echo (filter_var($email, FILTER_VALIDATE_EMAIL)) ? 
    "Email valid: $email<br>" : "Email tidak valid<br>";

echo (filter_var($url, FILTER_VALIDATE_URL)) ? 
    "URL valid: $url<br>" : "URL tidak valid<br>";

echo (filter_var($nilai, FILTER_VALIDATE_FLOAT)) ? 
    "Nilai valid (float): $nilai<br>" : "Nilai tidak valid<br>";

echo (filter_var($nim, FILTER_VALIDATE_IP)) ? 
    "NIM valid (IP Address format)<br>" : "NIM bukan IP Address<br>";

// 4. Type Testing
echo "<h3>4. Type Testing</h3>";
echo (is_numeric($umur)) ? "Umur berupa angka<br>" : "Umur bukan angka<br>";
echo (is_float((float)$nilai)) ? "Nilai bertipe float<br>" : "Nilai bukan float<br>";
echo (is_string($username)) ? "Username berupa string<br>" : "Username bukan string<br>";

// 5. Date Validation
echo "<h3>5. Date Validation</h3>";
if (checkdate($bulan, $tanggal, $tahun)) {
    echo "Tanggal valid: $tanggal-$bulan-$tahun<br>";
} else {
    echo "Tanggal tidak valid<br>";
}
?>