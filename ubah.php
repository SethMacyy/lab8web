<?php
error_reporting(E_ALL);
include_once 'koneksi.php';

// --- 1. Fungsi Bantuan ---
// Fungsi untuk membuat opsi 'selected' pada tag <select>
function is_select($var, $val) {
    if ($var == $val) {
        return 'selected="selected"';
    }
    return '';
}

// --- 2. LOGIKA UPDATE DATA (Saat form disubmit) ---
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id         = $_POST['id'];
    $nama       = $_POST['nama'];
    $kategori   = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $harga_beli = $_POST['harga_beli'];
    $stok       = $_POST['stok'];
    $file_gambar = $_FILES['file_gambar'];
    
    $gambar = null; // Inisialisasi variabel gambar

    // Proses upload gambar baru
    if ($file_gambar['error'] == 0) {
        // Hapus file lama jika ada, ini adalah praktik baik.
        // Logika hapus file lama perlu ditambahkan di sini.

        $filename = str_replace(' ', '_', $file_gambar['name']);
        $destination = dirname(__FILE__) . '/gambar/' . $filename;
        
        if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
            $gambar = 'gambar/' . $filename;
        }
    }

    // Menyiapkan Prepared Statement untuk UPDATE
    if (!empty($gambar)) {
        // Query jika ada gambar baru
        $sql = "UPDATE data_barang SET nama = ?, kategori = ?, harga_jual = ?, 
                harga_beli = ?, stok = ?, gambar = ? WHERE id_barang = ?";
        $stmt = mysqli_prepare($conn, $sql);
        // Bind parameter: ssiissi (s=string, i=int)
        mysqli_stmt_bind_param($stmt, 'ssiissi', 
                                $nama, $kategori, $harga_jual, 
                                $harga_beli, $stok, $gambar, $id);
    } else {
        // Query jika TIDAK ada gambar baru
        $sql = "UPDATE data_barang SET nama = ?, kategori = ?, harga_jual = ?, 
                harga_beli = ?, stok = ? WHERE id_barang = ?";
        $stmt = mysqli_prepare($conn, $sql);
        // Bind parameter: ssiis (s=string, i=int)
        mysqli_stmt_bind_param($stmt, 'ssiisi', 
                                $nama, $kategori, $harga_jual, 
                                $harga_beli, $stok, $id);
    }

    // Eksekusi statement
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect ke halaman index setelah penyimpanan
    if ($result) {
        header('location: index.php');
        exit;
    } else {
        die('Gagal mengubah data: ' . mysqli_error($conn));
    }
}

// --- 3. MENGAMBIL DATA LAMA (Untuk ditampilkan di form) ---
// Memastikan ada ID yang dikirim melalui URL
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit;
}

$id = $_GET['id'];

// Menggunakan Prepared Statement untuk SELECT (lebih aman)
$sql = "SELECT * FROM data_barang WHERE id_barang = ?";
$stmt = mysqli_prepare($conn, $sql);

// Bind parameter: i (integer)
mysqli_stmt_bind_param($stmt, 'i', $id);

// Eksekusi dan ambil hasilnya
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    die('Error: Data barang tidak ditemukan.');
}
$data = mysqli_fetch_array($result);
mysqli_stmt_close($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Ubah Barang</title>
</head>
<body>
    <div class="container">
        <h1>Ubah Barang</h1>
        <div class="main">
            <form method="post" enctype="multipart/form-data">
                <div class="input">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']);?>" required />
                </div>
                <div class="input">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option <?= is_select($data['kategori'], 'Komputer');?> value="Komputer">Komputer</option>
                        <option <?= is_select($data['kategori'], 'Elektronik');?> value="Elektronik">Elektronik</option>
                        <option <?= is_select($data['kategori'], 'Hand Phone');?> value="Hand Phone">Hand Phone</option>
                    </select>
                </div>
                <div class="input">
                    <label>Harga Jual</label>
                    <input type="text" name="harga_jual" value="<?= $data['harga_jual'];?>" required />
                </div>
                <div class="input">
                    <label>Harga Beli</label>
                    <input type="text" name="harga_beli" value="<?= $data['harga_beli'];?>" required />
                </div>
                <div class="input">
                    <label>Stok</label>
                    <input type="text" name="stok" value="<?= $data['stok'];?>" required />
                </div>
                <div class="input">
                    <label>File Gambar (Saat ini: <?= $data['gambar'];?>)</label>
                    <input type="file" name="file_gambar" />
                </div>
                <div class="submit">
                    <input type="hidden" name="id" value="<?= $data['id_barang'];?>" />
                    <input type="submit" name="submit" value="Simpan Perubahan" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>