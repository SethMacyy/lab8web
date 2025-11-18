<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "latihan1"; // Pastikan nama database ini sesuai dengan yang Anda buat sebelumnya

$conn = mysqli_connect($host, $user, $pass, $db);

if ($conn == false) {
    echo "Koneksi ke server gagal.";
    die();
} 
// Jika koneksi berhasil, tampilkan pesan ini:
else { 
    // Hapus atau beri tanda komentar (//) pada baris di bawah ini setelah pengujian
    echo "Koneksi berhasil.";
}
?>