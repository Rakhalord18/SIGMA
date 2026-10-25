<?php
include('../../config.php');
include ('../layout/header.php');

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nis_nim = $_POST['nis_nim'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jurusan = $_POST['jurusan'];
    $asal_instansi = $_POST['asal_instansi'];
    $alamat = $_POST['alamat'];
    $notelpon = $_POST['notelpon'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $lokasi_presensi = $_POST['lokasi_presensi'];
    $role = $_POST['role'];

    // Hash password default
    $password = password_hash('123456', PASSWORD_DEFAULT);
    $status = 'Aktif';

    // Simpan ke tabel `users`
    $query_users = "INSERT INTO users (email, password, status, role) VALUES ('$email', '$password', '$status', '$role')";
    if (!mysqli_query($koneksi, $query_users)) {
        // Tangani error duplicate entry
        if (mysqli_errno($koneksi) == 1062) {
            echo "Email sudah terdaftar!";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
        exit;
    }

    // Ambil ID user yang baru saja disimpan
    $id_users = mysqli_insert_id($koneksi);

    // Simpan ke tabel `mahasiswa_detail`
    $query_mahasiswa = "INSERT INTO mahasiswa_detail (id_users, nama, nis_nim, jenis_kelamin, jurusan, asal_instansi, alamat, notelpon, tanggal_mulai, tanggal_selesai, lokasi_presensi) 
                        VALUES ('$id_users', '$nama', '$nis_nim', '$jenis_kelamin', '$jurusan', '$asal_instansi', '$alamat', '$notelpon', '$tanggal_mulai', '$tanggal_selesai', '$lokasi_presensi')";
    if (!mysqli_query($koneksi, $query_mahasiswa)) {
        die('Error: ' . mysqli_error($koneksi));
    }

    // Redirect kembali ke halaman utama dengan pesan sukses
    header('Location: mahasiswa_siswa.php?pesan=simpan_sukses');
    exit;
}
?>

</body>
</html>