<?php
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password'] ?? '');
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $hp = trim($_POST['hp']);
    $level = trim($_POST['level']);

    if (empty($username)) {
        $error['username'] = "Username wajib diisi.";
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        $error['username'] = "Username hanya boleh huruf & angka.";
    } else {
        // PERBAIKAN: Gunakan id_user sesuai struktur tabel
        $check_query = "SELECT id_user FROM user WHERE username = '$username'";
        if (isset($_POST['id'])) {
            $check_query .= " AND id_user != " . $_POST['id'];
        }
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error['username'] = "Username sudah digunakan.";
        }
    }

    if (!isset($_POST['id']) && empty($password)) {
        $error['password'] = "Password wajib diisi.";
    } elseif (!empty($password) && strlen($password) < 4) {
        $error['password'] = "Password minimal 4 karakter.";
    }

    if (empty($nama)) {
        $error['nama'] = "Nama wajib diisi.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $error['nama'] = "Nama hanya boleh huruf & spasi.";
    }

    if (empty($alamat)) {
        $error['alamat'] = "Alamat wajib diisi.";
    }

    if (empty($hp)) {
        $error['hp'] = "Nomor HP wajib diisi.";
    } elseif (!preg_match("/^[0-9]+$/", $hp)) {
        $error['hp'] = "Nomor HP hanya boleh angka.";
    }

    if (empty($level)) {
        $error['level'] = "Level wajib dipilih.";
    } elseif (!in_array($level, ['1', '2'])) {
        $error['level'] = "Level tidak valid.";
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            
            if (!empty($password)) {
                $password_md5 = md5($password);
                // PERBAIKAN: Gunakan id_user
                $query = "UPDATE user 
                         SET username = '$username', 
                             password = '$password_md5',
                             nama = '$nama', 
                             alamat = '$alamat', 
                             hp = '$hp', 
                             level = $level 
                         WHERE id_user = $id";
            } else {
                // PERBAIKAN: Gunakan id_user
                $query = "UPDATE user 
                         SET username = '$username', 
                             nama = '$nama', 
                             alamat = '$alamat', 
                             hp = '$hp', 
                             level = $level 
                         WHERE id_user = $id";
            }
        } else {
            $password_md5 = md5($password);
            $query = "INSERT INTO user (username, password, nama, alamat, hp, level)
                     VALUES ('$username', '$password_md5', '$nama', '$alamat', '$hp', $level)";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: user.php");
            exit;
        } else {
            $error['db'] = "Gagal menyimpan: " . mysqli_error($conn);
        }
    }
}
?>