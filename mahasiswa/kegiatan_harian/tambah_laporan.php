<?php
// filepath: c:\xampp\htdocs\Presensi\mahasiswa\kegiatan_harian\tambah_laporan.php
include('../layout/header.php');
require_once('../../config.php');

if (!isset($_SESSION['login']) || ($_SESSION['role'] !== 'mahasiswa' && $_SESSION['role'] !== 'siswa')) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

$id_users = $_SESSION['id'];
// Cek apakah user sudah pernah upload laporan
$cek_laporan = mysqli_query($koneksi, "SELECT id FROM laporan_akhir WHERE id_users='$id_users' LIMIT 1");
$sudah_upload = mysqli_num_rows($cek_laporan) > 0;

// Proses upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$sudah_upload) {
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

            // Simpan ke database, hanya id_users yang diambil
            mysqli_query($koneksi, "INSERT INTO laporan_akhir (id_users, keterangan, file, tanggal) VALUES ('$id_users', '$keterangan', '$file', NOW())");
            header('Location: laporan_akhir.php?pesan=sukses');
            exit;
        } else {
            header('Location: tambah_laporan.php?pesan=gagal');
            exit;
        }
    }
}
?>

<div class="container-xl mt-4">
    <h3>Upload Tugas Laporan Akhir</h3>
    <?php if ($sudah_upload): ?>
        <?php header('Location: laporan_akhir.php?pesan=duplikat'); ?>
    <?php else: ?>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Laporan Akhir Magang" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">File Laporan (ZIP, max 100MB)</label>
            <input type="file" class="form-control" id="file" name="file" accept=".zip" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <?php endif; ?>
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'gagal'): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'File tidak sesuai ketentuan atau terlalu besar.'
        });
        </script>
    <?php endif; ?>
</div>

<?php include('../layout/footer.php'); ?>
