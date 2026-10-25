<?php
// filepath: c:\xampp\htdocs\Presensi\admin\ketidakhadiran\hapus.php
session_start();
require_once('../../config.php');

// Hanya admin yang boleh akses
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Ambil nama file untuk dihapus dari server
    $getFile = mysqli_query($koneksi, "SELECT file FROM ketidakhadiran WHERE id = '$id'");
    $fileData = mysqli_fetch_assoc($getFile);

    // Hapus data dari tabel ketidakhadiran
    $query = "DELETE FROM ketidakhadiran WHERE id = '$id'";
    $delete = mysqli_query($koneksi, $query);

    // Hapus file jika ada
    if ($delete && $fileData && !empty($fileData['file'])) {
        $filePath = '../../assets/file/' . $fileData['file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $_SESSION['sukses'] = "Data berhasil dihapus!";
    header('Location: ketidakhadiran.php');
    exit;
} else {
    $_SESSION['gagal'] = "ID tidak ditemukan!";
    header('Location: ketidakhadiran.php');
    exit;
}