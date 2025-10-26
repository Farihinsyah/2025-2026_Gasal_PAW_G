<?php
require 'validate.inc';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = validateName($_POST, 'surname');

    if ($result['is_valid']) {
        echo "<h3>Data OK!</h3>";
        echo "Nama yang kamu masukkan: <b>" . htmlspecialchars($_POST['surname']) . "</b>";
    } else {
        echo "<h3>Data invalid!</h3>";
        echo "Terjadi kesalahan berikut:<br><ul>";
        foreach ($result['errors'] as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "Silakan isi formulir terlebih dahulu.";
}
?>