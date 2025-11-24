<?php
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $telp = trim($_POST['telp']);
    $alamat = trim($_POST['alamat']);

    if (empty($nama)) {
        $error['nama'] = "Nama wajib diisi.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $error['nama'] = "Nama hanya boleh huruf & spasi.";
    }

    if (empty($telp)) {
        $error['telp'] = "Telepon wajib diisi.";
    } elseif (!preg_match("/^[0-9]+$/", $telp)) {
        $error['telp'] = "Telepon hanya boleh angka.";
    }

    if (empty($alamat)) {
        $error['alamat'] = "Alamat wajib diisi.";
    } elseif (!preg_match("/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9\s,.\-]+$/", $alamat)) {
        $error['alamat'] = "Alamat harus mengandung huruf dan angka.";
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $query = "UPDATE supplier 
                      SET nama='$nama', telp='$telp', alamat='$alamat' 
                      WHERE id=$id";
        } else {
            $query = "INSERT INTO supplier(nama,telp,alamat)
                      VALUES('$nama','$telp','$alamat')";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: supplier.php");
            exit;
        } else {
            $error['db'] = "Gagal menyimpan: " . mysqli_error($conn);
        }
    }
}
?>