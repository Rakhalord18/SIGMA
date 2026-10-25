<?php

require_once('../../config.php');
session_start();

// Ambil tanggal mulai & selesai dari mahasiswa_detail
$id_users = $_SESSION['id'];
$nama = $_SESSION['nama'];
$q = mysqli_query($koneksi, "SELECT tanggal_mulai, tanggal_selesai, nama FROM mahasiswa_detail WHERE id_users='$id_users' LIMIT 1");
$periode = mysqli_fetch_assoc($q);
$nama = $periode['nama'];
$tanggal_mulai = $periode['tanggal_mulai'];
$tanggal_selesai = $periode['tanggal_selesai'];

// Ambil tanggal sekarang
$tanggal_sekarang = date('Y-m-d');

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hari = $_POST['hari'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    
    // Validasi apakah tanggal yang dimasukkan sesuai dengan periode
    if ($tanggal < $tanggal_mulai || $tanggal > $tanggal_selesai) {
        header('Location: kegiatan_harian.php?pesan=periode');
        exit;
    } elseif ($tanggal > $tanggal_sekarang) {
        // Validasi tanggal agar tidak lebih dari tanggal sekarang
        header('Location: kegiatan_harian.php?pesan=tanggal_tidak_valid');
        exit;
    } else {
        // Cek apakah sudah ada data di tanggal dan hari yang sama untuk user ini
        $cek = mysqli_query($koneksi, "SELECT id FROM kegiatan_harian WHERE id_users='$id_users' AND tanggal='$tanggal' AND hari='$hari' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            header('Location: kegiatan_harian.php?pesan=duplikat');
            exit;
        }

        // Simpan ke database
        $query = "INSERT INTO kegiatan_harian (id_users, hari, tanggal, deskripsi, file)
                  VALUES ('$id_users', '$hari', '$tanggal', '$deskripsi', '$file')";
        mysqli_query($koneksi, $query);
        header('Location: kegiatan_harian.php?pesan=simpan_sukses');
        exit;
    }
}
?>
