<?php
// filepath: c:\xampp\htdocs\Presensi\admin\kegiatan_harian\update_statuslaporan.php

require_once('../../config.php');
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

if (isset($_GET['id']) && isset($_GET['STATUS'])) {
    $id = $_GET['id'];
    $statusBaru = $_GET['STATUS'];

    // Validasi status yang diizinkan
    $allowed_status = ['Disetujui', 'Ditolak', 'Pending'];
    if (!in_array($statusBaru, $allowed_status)) {
        $_SESSION['gagal'] = "Status tidak valid.";
        header('Location: laporan_akhir.php');
        exit();
    }

    // Ambil ID admin yang sedang login
    $admin_id = $_SESSION['id'];

    // Query untuk update status dan menyimpan siapa yang menyetujui
    $query = "UPDATE laporan_akhir SET STATUS = '$statusBaru', disetujui_oleh = '$admin_id' WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['sukses'] = "Status berhasil diubah menjadi '$statusBaru'.";
    } else {
        $_SESSION['gagal'] = "Gagal mengubah status.";
    }
} else {
    $_SESSION['gagal'] = "Data tidak lengkap.";
}

header('Location: laporan_akhir.php');
exit();
