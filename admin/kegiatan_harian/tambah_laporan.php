<?php
include('../layout/header.php');
require_once('../../config.php');

if (!isset($_SESSION['login']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing')) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Cek jika ada ID user yang dipilih
$id_users = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id_users) {
    header('Location: laporan_akhir.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keterangan = $_POST['keterangan'];
    $file = '';

    if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
        $allowed_ext = ['zip'];
        $max_size = 100 * 1024 * 1024; // 100MB
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext) && $file_size <= $max_size) {
            $new_file_name = uniqid('laporan_', true) . '.' . $file_ext;
            $upload_dir = '../../uploads/laporan_akhir/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($file_tmp, $upload_dir . $new_file_name);
            $file = $new_file_name;

            // Insert data ke database
            $query = "INSERT INTO laporan_akhir (id_users, keterangan, file, tanggal)
                      VALUES ('$id_users', '$keterangan', '$file', NOW())";
            if (mysqli_query($koneksi, $query)) {
                header('Location: laporan_akhir.php?pesan=sukses');
                exit();
            } else {
                echo 'Gagal mengupload laporan.';
            }
        } else {
            echo 'File tidak sesuai ketentuan atau terlalu besar.';
        }
    }
}
?>

<div class="container-xl mt-4">
    <h3>Tambah Laporan Akhir</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Laporan Akhir Magang" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">File Laporan (ZIP, max 100MB)</label>
            <input type="file" class="form-control" id="file" name="file" accept=".zip" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
        <a href="laporan_akhir.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include('../layout/footer.php'); ?>
