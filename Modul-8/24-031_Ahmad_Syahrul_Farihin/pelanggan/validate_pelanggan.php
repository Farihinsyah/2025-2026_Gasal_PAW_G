<?php
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode = trim($_POST['id']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $telp = trim($_POST['telp']);
    $alamat = trim($_POST['alamat']);

    // Tentukan apakah ini edit atau tambah
    $is_edit = isset($_POST['id_lama']);
    $id_lama = $_POST['id_lama'] ?? '';

    if (empty($kode)) {
        $error['id'] = "Kode pelanggan wajib diisi.";
    } elseif (!preg_match("/^[A-Z0-9]+$/", $kode)) {
        $error['id'] = "Kode pelanggan hanya boleh huruf kapital & angka.";
    } else {
        // Cek duplikasi kode
        if ($is_edit) {
            $check_query = "SELECT id FROM pelanggan WHERE id='$kode' AND id != '$id_lama'";
        } else {
            $check_query = "SELECT id FROM pelanggan WHERE id='$kode'";
        }
        
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error['id'] = "Kode pelanggan '$kode' sudah digunakan.";
        }
    }

    if (empty($nama)) {
        $error['nama'] = "Nama wajib diisi.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $error['nama'] = "Nama hanya boleh huruf & spasi.";
    }

    if (empty($jenis_kelamin)) {
        $error['jenis_kelamin'] = "Jenis kelamin wajib dipilih.";
    } elseif (!in_array($jenis_kelamin, ['L', 'P'])) {
        $error['jenis_kelamin'] = "Jenis kelamin tidak valid.";
    }

    if (empty($telp)) {
        $error['telp'] = "Telepon wajib diisi.";
    } elseif (!preg_match("/^[0-9]+$/", $telp)) {
        $error['telp'] = "Telepon hanya boleh angka.";
    } elseif (strlen($telp) < 10 || strlen($telp) > 15) {
        $error['telp'] = "Telepon harus 10-15 digit.";
    }

    if (empty($alamat)) {
        $error['alamat'] = "Alamat wajib diisi.";
    } elseif (!preg_match("/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9\s,.\-]+$/", $alamat)) {
        $error['alamat'] = "Alamat harus mengandung huruf dan angka.";
    }

    if (empty($error)) {
        if ($is_edit) {
            // UPDATE untuk edit
            $query = "UPDATE pelanggan 
                      SET id='$kode', 
                          nama='$nama', 
                          jenis_kelamin='$jenis_kelamin', 
                          telp='$telp', 
                          alamat='$alamat' 
                      WHERE id='$id_lama'";
        } else {
            // INSERT untuk tambah
            $query = "INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat)
                      VALUES ('$kode', '$nama', '$jenis_kelamin', '$telp', '$alamat')";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: pelanggan.php");
            exit;
        } else {
            $error['db'] = "Gagal menyimpan: " . mysqli_error($conn);
        }
    }
}