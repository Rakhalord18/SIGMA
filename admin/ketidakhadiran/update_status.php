<?php
// filepath: c:\xampp\htdocs\Presensi\admin\ketidakhadiran\update_status.php
session_start();
require_once('../../config.php');

// Hanya admin yang boleh akses
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;

if ($id && ($status == 'Disetujui' || $status == 'Ditolak')) {
    if ($status == 'Disetujui') {
        $admin_id = $_SESSION['id']; // ID admin dari session
        $query = "UPDATE ketidakhadiran SET status_pengajuan = 'Disetujui', disetujui_oleh = '$admin_id' WHERE id = '$id'";
    } else {
        $query = "UPDATE ketidakhadiran SET status_pengajuan = 'Ditolak', disetujui_oleh = NULL WHERE id = '$id'";
    }

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        $_SESSION['sukses'] = "Status pengajuan berhasil diupdate!";
    } else {
        $_SESSION['gagal'] = "Gagal update status!";
    }
} else {
    $_SESSION['gagal'] = "Data tidak lengkap!";
}

header("Location: ketidakhadiran.php");
exit;