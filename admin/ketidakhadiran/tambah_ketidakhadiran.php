<?php
require_once('../../config.php');
session_start(); // Pastikan session_start() ada jika pakai $_SESSION

// Validasi input
if (isset($_POST['id_users'], $_POST['tanggal'], $_POST['keterangan'], $_POST['deskripsi'])) {
    $id_users = $_POST['id_users'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $deskripsi = $_POST['deskripsi'];
    $status_pengajuan = 'Menunggu'; // default status

    // === CEK DUPLIKAT TANGGAL ===
    $cek = mysqli_query($koneksi, "SELECT id FROM ketidakhadiran WHERE id_users='$id_users' AND tanggal='$tanggal'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['gagal'] = "Mahasiswa/Siswa ini sudah mengajukan izin/sakit untuk tanggal ini!";
        header("Location: ketidakhadiran.php");
        exit();
    }

    $nama_file = null; // default jika tidak ada file

    // Cek jika file diunggah
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi ekstensi file
        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['gagal'] = "File harus PDF, JPG, atau PNG!";
            header("Location: ketidakhadiran.php");
            exit();
        }

        // Validasi ukuran file
        if ($file_size > $max_size) {
            $_SESSION['gagal'] = "Ukuran file maksimal 2MB!";
            header("Location: ketidakhadiran.php");
            exit();
        }

        // Simpan file ke folder uploads/ketidakhadiran/
        $upload_dir = '../../assets/file/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $new_file_name = uniqid('bukti_', true) . '.' . $file_ext;
        $file_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $nama_file = $new_file_name; // simpan nama file
        } else {
            $_SESSION['gagal'] = "Gagal upload file!";
            header("Location: ketidakhadiran.php");
            exit();
        }
    }

    // Simpan data ke database
    $query = "INSERT INTO ketidakhadiran (id_users, tanggal, keterangan, deskripsi, file, status_pengajuan)
    VALUES ('$id_users', '$tanggal', '$keterangan', '$deskripsi', " . 
    ($nama_file ? "'$nama_file'" : "'Tidak Ada File'") . ", '$status_pengajuan')";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['sukses'] = "Data ketidakhadiran berhasil ditambahkan!";
    } else {
        $_SESSION['gagal'] = "Gagal menambah data ke database!";
        if ($nama_file && file_exists($file_path)) unlink($file_path);
    }
} else {
    $_SESSION['gagal'] = "Data tidak lengkap!";
}

// Redirect kembali ke halaman ketidakhadiran
header("Location: ketidakhadiran.php?pesan=simpan_sukses");
exit();
?>
