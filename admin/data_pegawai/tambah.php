<?php
include('../../config.php');

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $nip = $_POST['nip'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $notelpon = $_POST['notelpon'];
    $role = $_POST['role']; // Default: admin
    $status = $_POST['status']; // Default: Aktif
    $password = password_hash('123456', PASSWORD_DEFAULT); // Password default

    // Periksa apakah email sudah ada di database
    $query_check_email = "SELECT id FROM users WHERE email = '$email'";
    $result_check_email = mysqli_query($koneksi, $query_check_email);

    if (mysqli_num_rows($result_check_email) > 0) {
        // Tampilkan pesan error menggunakan SweetAlert2
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email sudah terdaftar!! Silahkan Gunakan Email lain.',
            }).then(() => {
                window.history.back(); // Kembali ke halaman sebelumnya
            });
        </script>";
        exit;
    }

    // Simpan ke tabel `users`
    $query_users = "INSERT INTO users (email, password, role, status) VALUES ('$email', '$password', '$role', '$status')";
    if (!mysqli_query($koneksi, $query_users)) {
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }

    // Ambil ID user yang baru saja disimpan
    $id_users = mysqli_insert_id($koneksi);

    // Simpan ke tabel `pegawai`
    $query_pegawai = "INSERT INTO admin_detail (id_users, nama, nip, jenis_kelamin, jabatan, notelpon) 
                      VALUES ('$id_users', '$nama', '$nip', '$jenis_kelamin', '$jabatan', '$notelpon')";
    if (!mysqli_query($koneksi, $query_pegawai)) {
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }

    // Redirect kembali ke halaman utama dengan pesan sukses
    header('Location: pegawai.php?pesan=simpan_sukses');
    exit;
}
?>