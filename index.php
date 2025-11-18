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