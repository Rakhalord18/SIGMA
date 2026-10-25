<?php
session_start();
include_once('../../config.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Redirect berdasarkan role
if ($_SESSION['role'] === 'mahasiswa' || $_SESSION['role'] === 'siswa') {
    // Lanjutkan ke halaman mahasiswa
} else {
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

if (isset($_POST['tombol_keluar'])) {
    $id_users = $_SESSION['id'];
    $zona_waktu = $_POST['zona_waktu'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $jam_keluar = $_POST['jam_keluar'];

    // CEK KETIDAKHADIRAN SUDAH DI ACC
    $cek_izin = mysqli_query($koneksi, "SELECT * FROM ketidakhadiran 
        WHERE id_users='$id_users' 
        AND tanggal='$tanggal_keluar' 
        AND status_pengajuan='Disetujui'");
    if (mysqli_num_rows($cek_izin) > 0) {
        $_SESSION['gagal'] = "Anda tidak dapat presensi keluar karena sudah ada izin/sakit yang disetujui pada tanggal ini. GWS ya :)";
        header('Location: ../home/home.php');
        exit();
    }

    // Ambil data jam masuk dari database
    $query_presensi = "SELECT jam_masuk FROM presensi_detail WHERE id_users = '$id_users' AND tanggal_masuk = '$tanggal_keluar'";
    $result_presensi = mysqli_query($koneksi, $query_presensi);

    if (mysqli_num_rows($result_presensi) > 0) {
        $data_presensi = mysqli_fetch_assoc($result_presensi);
        $jam_masuk = $data_presensi['jam_masuk'];

        // Hitung total waktu
        $total_detik = strtotime($jam_keluar) - strtotime($jam_masuk);
        $total_waktu = gmdate('H:i:s', $total_detik);

        // Update presensi keluar dan simpan total waktu
        $query_update = "UPDATE presensi_detail 
                         SET tanggal_keluar = '$tanggal_keluar',
                             jam_keluar = '$jam_keluar',
                             total_waktu = '$total_waktu'
                         WHERE id_users = '$id_users' AND tanggal_masuk = '$tanggal_keluar'";
        $result_update = mysqli_query($koneksi, $query_update);

        if ($result_update) {
            $_SESSION['sukses'] = "Presensi keluar berhasil disimpan.";
        } else {
            $_SESSION['gagal'] = "Presensi keluar gagal disimpan.";
        }
    } else {
        $_SESSION['gagal'] = "Data presensi masuk tidak ditemukan.";
    }

    header('Location: ../home/home.php');
    exit();
}
?>