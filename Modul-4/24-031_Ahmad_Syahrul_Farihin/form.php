<?php
require 'validate.inc';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['surname']) || !empty($_POST['umur']) || !empty($_POST['email'])) {

        validateSurname($_POST, 'surname', $errors);
        validateUmur($_POST, 'umur', $errors);
        validateEmail($_POST, 'email', $errors);

        // Jika ada error tampilkan pesan dan form isi ulang
        if (!empty($errors)) {
            echo "<p style='color:red;'>Terjadi kesalahan pada isian:</p><ul>";
            foreach ($errors as $err) {
                echo "<li>" . htmlspecialchars($err) . "</li>";
            }
            echo "</ul>";
            include 'form.inc';
        } else {
            echo "<p style='color:green;'>Form submitted successfully with no errors</p>";
            echo "<p>Data yang diterima:</p>";
            echo "<ul>";
            echo "<li>Surname: " . htmlspecialchars($_POST['surname']) . "</li>";
            echo "<li>Umur: " . htmlspecialchars($_POST['umur']) . "</li>";
            echo "<li>Email: " . htmlspecialchars($_POST['email']) . "</li>";
            echo "</ul>";
        }

    } else {
        include 'form.inc';
    }

} else {
    include 'form.inc';
}
?>