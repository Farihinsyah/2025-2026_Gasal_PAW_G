<?php
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = trim($_POST['kode_barang']);
    $nama_barang = trim($_POST['nama_barang']);
    $harga = trim($_POST['harga']);
    $stok = trim($_POST['stok']);
    $supplier_id = trim($_POST['supplier_id']);

    if (empty($kode_barang)) {
        $error['kode_barang'] = "Kode barang wajib diisi.";
    } elseif (!preg_match("/^[A-Z0-9]+$/", $kode_barang)) {
        $error['kode_barang'] = "Kode barang hanya boleh huruf kapital & angka.";
    } else {
        $check_query = "SELECT id FROM barang WHERE kode_barang='$kode_barang'";
        if (isset($_POST['id'])) {
            $check_query .= " AND id != " . $_POST['id'];
        }
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error['kode_barang'] = "Kode barang sudah digunakan.";
        }
    }

    if (empty($nama_barang)) {
        $error['nama_barang'] = "Nama barang wajib diisi.";
    } elseif (!preg_match("/^[a-zA-Z0-9\s\-]+$/", $nama_barang)) {
        $error['nama_barang'] = "Nama barang hanya boleh huruf, angka, spasi & strip.";
    }

    if (empty($harga)) {
        $error['harga'] = "Harga wajib diisi.";
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error['harga'] = "Harga harus angka positif.";
    }

    if (empty($stok)) {
        $error['stok'] = "Stok wajib diisi.";
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error['stok'] = "Stok harus angka positif.";
    }

    if (empty($supplier_id)) {
        $error['supplier_id'] = "Supplier wajib dipilih.";
    } elseif (!is_numeric($supplier_id)) {
        $error['supplier_id'] = "Supplier tidak valid.";
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $query = "UPDATE barang 
                      SET kode_barang='$kode_barang', 
                          nama_barang='$nama_barang', 
                          harga=$harga, 
                          stok=$stok, 
                          supplier_id=$supplier_id 
                      WHERE id=$id";
        } else {
            $query = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
                      VALUES ('$kode_barang', '$nama_barang', $harga, $stok, $supplier_id)";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: barang.php");
            exit;
        } else {
            $error['db'] = "Gagal menyimpan: " . mysqli_error($conn);
        }
    }
}
?>