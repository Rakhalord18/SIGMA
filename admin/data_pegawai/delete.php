<?php
include('../../config.php');

// Validasi parameter `id`
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID tidak ditemukan.');
}

$id_users = mysqli_real_escape_string($koneksi, $_GET['id']);

// Hapus data dari tabel `admin_detail`
$query_mahasiswa = "DELETE FROM admin_detail WHERE id_users = '$id_users'";
if (!mysqli_query($koneksi, $query_mahasiswa)) {
    die('Gagal menghapus data dari tabel admin_detail: ' . mysqli_error($koneksi));
}

// Hapus data dari tabel `users`
$query_users = "DELETE FROM users WHERE id = '$id_users'";
if (!mysqli_query($koneksi, $query_users)) {
    die('Gagal menghapus data dari tabel users: ' . mysqli_error($koneksi));
}

// Redirect kembali ke halaman utama dengan pesan sukses
header('Location: pegawai.php?pesan=delete_sukses');
exit;
?>