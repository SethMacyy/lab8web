<?php
// Mengaktifkan laporan error untuk debugging
error_reporting(E_ALL);

// Menyertakan file koneksi.php
include_once 'koneksi.php';

// Memeriksa apakah form sudah disubmit
if (isset($_POST['submit'])) {
    // 1. Mengambil data dari form
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $harga_beli = $_POST['harga_beli'];
    $stok = $_POST['stok'];
    $file_gambar = $_FILES['file_gambar'];

    $gambar = null; // Inisialisasi variabel gambar

    // 2. Proses upload file gambar
    if ($file_gambar['error'] == 0) {
        // Mengganti spasi pada nama file dengan underscore
        $filename = str_replace(' ', '_', $file_gambar['name']);
        // Menentukan lokasi tujuan
        $destination = dirname(__FILE__) . '/gambar/' . $filename;
        
        // Memindahkan file yang di-upload
        if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
            $gambar = 'gambar/' . $filename;
        }
    }

    // 3. Menyiapkan Prepared Statement (Pencegahan SQL Injection)
    $sql = "INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar) 
            VALUES (?, ?, ?, ?, ?, ?)";
            
    // Mempersiapkan statement
    $stmt = mysqli_prepare($conn, $sql);

    // Mengikat parameter (s = string, i = integer)
    // Asumsi harga jual, harga beli, dan stok adalah integer (i)
    mysqli_stmt_bind_param($stmt, 'ssiiss', 
                            $nama, 
                            $kategori, 
                            $harga_jual, 
                            $harga_beli, 
                            $stok, 
                            $gambar);

    // Mengeksekusi statement
    $result = mysqli_stmt_execute($stmt);

    // Menutup statement
    mysqli_stmt_close($stmt);

    // 4. Redirect ke halaman index setelah penyimpanan
    if ($result) {
        header('Location: index.php');
        exit;
    } else {
        // Tambahkan penanganan error jika INSERT gagal
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Tambah Barang</title>
</head>
<body>
    <div class="container">
        <h1>Tambah Barang</h1>
        <div class="main">
            <form method="post" enctype="multipart/form-data">
                <div class="input">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" required />
                </div>
                <div class="input">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="Komputer">Komputer</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Hand Phone">Hand Phone</option>
                    </select>
                </div>
                <div class="input">
                    <label>Harga Jual</label>
                    <input type="text" name="harga_jual" required />
                </div>
                <div class="input">
                    <label>Harga Beli</label>
                    <input type="text" name="harga_beli" required />
                </div>
                <div class="input">
                    <label>Stok</label>
                    <input type="text" name="stok" required />
                </div>
                <div class="input">
                    <label>File Gambar</label>
                    <input type="file" name="file_gambar" />
                </div>
                <div class="submit">
                    <input type="submit" name="submit" value="Simpan" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>