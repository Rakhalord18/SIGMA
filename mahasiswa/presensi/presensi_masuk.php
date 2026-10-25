<?php

include ('../layout/header.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Redirect berdasarkan role
if  ($_SESSION['role'] === 'mahasiswa' || $_SESSION['role'] === 'siswa') {
    // Lanjutkan ke halaman mahasiswa
} else {
    // Jika role tidak dikenali, redirect ke halaman login
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

include_once('../../config.php');

if(isset($_POST['tombol_masuk'])) {
    $nama_lokasi = $_POST['nama_lokasi']; // Ambil nama lokasi dari form
    $zona_waktu = $_POST['zona_waktu'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jam_masuk = $_POST['jam_masuk'];
    $id_users = $_SESSION['id'];

    // CEK KETIDAKHADIRAN SUDAH DI ACC
    $cek_izin = mysqli_query($koneksi, "SELECT * FROM ketidakhadiran 
        WHERE id_users='$id_users' 
        AND tanggal='$tanggal_masuk' 
        AND status_pengajuan='Disetujui'");
    if (mysqli_num_rows($cek_izin) > 0) {
        $_SESSION['gagal'] = "Anda tidak dapat presensi karena sudah ada izin/sakit yang disetujui pada tanggal ini.";
        header('Location: ../home/home.php');
        exit();
    }


    // Simpan total_waktu sebagai '00:00:00'
    $query = "INSERT INTO presensi_detail (id_users, nama_lokasi, tanggal_masuk, jam_masuk, total_waktu) 
              VALUES ('$id_users', '$nama_lokasi', '$tanggal_masuk', '$jam_masuk',  '00:00:00')";
    $result = mysqli_query($koneksi, $query);
    
    if ($result) {
        $_SESSION['sukses'] = "Presensi masuk berhasil";
        header('Location: ../home/home.php');
        exit();
    } else {
        $_SESSION['gagal'] = "Presensi masuk gagal";
        header('Location: ../home/home.php');
        exit();
    }
} else {
    // Jika akses langsung ke file ini tanpa tombol presensi
    header('Location:../home/home.php');
}
?>

<?php include ('../layout/footer.php'); ?>