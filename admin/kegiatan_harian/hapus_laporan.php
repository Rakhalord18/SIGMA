<?php
include('../../config.php');

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Ambil nama file untuk dihapus dari server
    $getFile = mysqli_query($koneksi, "SELECT file FROM laporan_akhir WHERE id = '$id'");
    $fileData = mysqli_fetch_assoc($getFile);

    // Hapus data dari tabel ketidakhadiran
    $query = "DELETE FROM laporan_akhir WHERE id = '$id'";
    $delete = mysqli_query($koneksi, $query);

    // Hapus file jika ada
    if ($delete && $fileData && !empty($fileData['file'])) {
        $filePath = '../../uploads/laporan_akhir/' . $fileData['file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Redirect kembali ke halaman utama dengan pesan sukses
    header('Location: laporan_akhir.php?pesan=delete_sukses');
    exit;
} else {
    // Redirect jika tidak ada id
    header('Location: laporan_akhir.php?pesan=delete_gagal');
    exit;
}

?>
