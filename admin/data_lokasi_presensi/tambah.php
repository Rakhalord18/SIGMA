<?php
include('../../config.php');

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi   = $_POST['nama_lokasi'];
    $alamat_lokasi = $_POST['alamat_lokasi'];
    $zona_waktu    = $_POST['zona_waktu'];
    $jam_masuk     = $_POST['jam_masuk'];
    $jam_keluar    = $_POST['jam_keluar'];

    // Simpan ke tabel lokasi_presensi
    $query_lokasi = "INSERT INTO lokasi_presensi (nama_lokasi, alamat_lokasi, zona_waktu, jam_masuk, jam_keluar) 
                     VALUES ('$nama_lokasi', '$alamat_lokasi', '$zona_waktu', '$jam_masuk', '$jam_keluar')";
                     
    if (!mysqli_query($koneksi, $query_lokasi)) {
        // Tangani error jika terjadi
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }

    // Redirect kembali ke halaman utama dengan pesan sukses
    header('Location: lokasi_presensi.php?pesan=simpan_sukses');
    exit;
}
?>