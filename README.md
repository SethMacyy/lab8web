# PRAKTIKUM-8

```
NAMA : SURYA PUTRA DARMA JAYA
NIM : 312410405
KELAS : TI.24.A3
```


# 1. Menjalankan MySQL Server
<img width="600" height="600" alt="runsql" src="https://github.com/user-attachments/assets/62e28dcf-b0d4-4be3-b1f8-d452995df214" />

# 2. Membuat Database : latihan1

### _Setelah membuat database latihan1, lanjut create table_

:<img width="600" height="600" alt="create" src="https://github.com/user-attachments/assets/3a87cae1-1451-477f-9a81-8a8b446063a6" />

### _Setelah itu insert untuk menambahkan data_
<img width="600" height="600" alt="insert" src="https://github.com/user-attachments/assets/35885ea8-eca5-4a94-9911-725539d36ce8" />

### _Tampilan Data Barang_
<img width="600" height="600" alt="table" src="https://github.com/user-attachments/assets/d246e847-d241-4a77-96be-34608991c090" />

# 3. Membuat file koneksi database
### _Buat file baru dengan nama koneksi.php_

<img width="600" height="600" alt="koneksi" src="https://github.com/user-attachments/assets/3f6159bb-364f-4ae9-a475-16bba7ad9ace" />

### _Buka melalui browser untuk menguji koneksi database (untuk menyampilkan pesan koneksi berhasil, uncomment pada perintah echo “koneksi berhasil”;_
<img width="600" height="600" alt="koneksi2" src="https://github.com/user-attachments/assets/ad68c478-ae66-43ad-9deb-631bfa239de5" />

# 4. Membuat file index untuk menampilkan data (Read) Buat file baru dengan nama index.php
```
<?php
// File ini berfungsi untuk menampilkan semua data dari tabel data_barang.

// Menyertakan file koneksi database
include("koneksi.php");

// Query untuk mengambil semua data dari tabel data_barang
$sql = 'SELECT * FROM data_barang';
$result = mysqli_query($conn, $sql);

// Fungsi untuk format angka agar mudah dibaca (opsional, tapi disarankan)
function format_angka($angka){
    return number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Data Barang</title>
</head>
<body>
    <div class="container">
        <h1>Data Barang</h1>
        
        <a href="tambah.php" class="link-tambah">Tambah Barang</a> 
        
        <div class="main">
            <table>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Harga Beli</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>

                <?php 
                // Memeriksa apakah ada data hasil query
                if($result && mysqli_num_rows($result) > 0): 
                ?>
                    <?php 
                    // Looping untuk menampilkan setiap baris data
                    while($row = mysqli_fetch_array($result)): 
                    ?>
                        <tr>
                            <td>
                                <img src="<?= $row['gambar'];?>" alt="<?= $row['nama'];?>" style="width: 100px;">
                            </td>
                            
                            <td><?= htmlspecialchars($row['nama']);?></td>
                            <td><?= htmlspecialchars($row['kategori']);?></td>
                            
                            <td><?= format_angka($row['harga_jual']);?></td>
                            <td><?= format_angka($row['harga_beli']);?></td>
                            <td><?= $row['stok'];?></td>
                            
                            <td>
                                <a href="ubah.php?id=<?= $row['id_barang'];?>">Ubah</a> 
                                <a href="hapus.php?id=<?= $row['id_barang'];?>">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Belum ada data barang.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
```

### _Tampilan index_
<img width="600" height="600" alt="databarang" src="https://github.com/user-attachments/assets/37e52ab2-fe5a-476a-a3d1-73bcc006f9d9" />

# 5. Menambah Data (Create) Buat file baru dengan nama tambah.php
```
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
```

### _Tambah Barang_
<img width="600" height="600" alt="tambah" src="https://github.com/user-attachments/assets/7a026d03-bf5b-4aaa-bd15-84b5c1fde069" />

# 6. Mengubah Data (Update) Buat file baru dengan nama ubah.php
```
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
```

### _Ubah Barang_

<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/d1a9492e-7b0a-455c-864d-102f4f26c69a" />

# 7. Mengahapus Barang/Data
```
<?php
include_once 'koneksi.php';
$id = $_GET['id'];
$sql = "DELETE FROM data_barang WHERE id_barang = '{$id}'";
$result = mysqli_query($conn, $sql);
header('location: index.php');
?>
```
### _Hapus_
<img width="600" height="600" alt="hapus" src="https://github.com/user-attachments/assets/4d745e48-80c3-4d1e-be4f-ef65d2b861a2" />



