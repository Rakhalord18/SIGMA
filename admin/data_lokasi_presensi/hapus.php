<?php
include('../../config.php');

// Periksa apakah parameter `id` ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

// Ambil ID dari URL
$id_lokasi = $_GET['id'];

// Hapus data dari tabel `lokasi_presensi`
$query = "DELETE FROM lokasi_presensi WHERE id = '$id_lokasi'";
if (mysqli_query($koneksi, $query)) {
    // Redirect kembali ke halaman utama dengan pesan sukses
    header('Location: lokasi_presensi.php?pesan=delete_sukses');
    exit;
} else {
    echo "Error: " . mysqli_error($koneksi);
    exit;
}
?>