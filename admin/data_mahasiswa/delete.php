<?php
include('../../config.php');

// Ambil ID dari URL
$id_users = $_GET['id'];

// Hapus data dari tabel `mahasiswa_detail`
$query_mahasiswa = "DELETE FROM mahasiswa_detail WHERE id_users = '$id_users'";
mysqli_query($koneksi, $query_mahasiswa);

// Hapus data dari tabel `users`
$query_users = "DELETE FROM users WHERE id = '$id_users'";
mysqli_query($koneksi, $query_users);

// Redirect kembali ke halaman utama dengan pesan sukses
header('Location: mahasiswa_siswa.php?pesan=delete_sukses');
exit;
?>