<?php
include('../../config.php');

// Periksa apakah parameter id ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

// Ambil ID dari URL
$id_lokasi = $_GET['id'];

// Ambil data dari database berdasarkan ID
$query = "SELECT * FROM lokasi_presensi WHERE id = '$id_lokasi'";
$result = mysqli_query($koneksi, $query);

// Periksa apakah data ditemukan
if (mysqli_num_rows($result) === 0) {
    echo "Data tidak ditemukan!";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi   = $_POST['nama_lokasi'];
    $alamat_lokasi = $_POST['alamat_lokasi'];
    $zona_waktu    = $_POST['zona_waktu'];
    $jam_masuk     = $_POST['jam_masuk'];
    $jam_keluar    = $_POST['jam_keluar'];

    // Update tabel lokasi_presensi (tanpa latitude, longitude, radius)
    $query_update = "UPDATE lokasi_presensi 
                     SET nama_lokasi = '$nama_lokasi', 
                         alamat_lokasi = '$alamat_lokasi', 
                         zona_waktu = '$zona_waktu', 
                         jam_masuk = '$jam_masuk', 
                         jam_keluar = '$jam_keluar' 
                     WHERE id = '$id_lokasi'";
    mysqli_query($koneksi, $query_update);

    // Redirect kembali ke halaman utama dengan pesan sukses
    header('Location: lokasi_presensi.php?pesan=edit_sukses');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Lokasi Presensi</title>
    <!-- Tambahkan CSS Bootstrap -->
    <link href="<?= base_url('assets/css/tabler.min.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Data Lokasi Presensi</h2>
        <form action="edit.php?id=<?= $id_lokasi ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                        <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" value="<?= $data['nama_lokasi'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat_lokasi" class="form-label">Alamat Lokasi</label>
                        <textarea class="form-control" id="alamat_lokasi" name="alamat_lokasi" rows="3" required><?= $data['alamat_lokasi'] ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="zona_waktu" class="form-label">Zona Waktu</label>
                        <select class="form-select" id="zona_waktu" name="zona_waktu" required>
                            <option value="WIB" <?= $data['zona_waktu'] === 'WIB' ? 'selected' : '' ?>>WIB</option>
                            <option value="WITA" <?= $data['zona_waktu'] === 'WITA' ? 'selected' : '' ?>>WITA</option>
                            <option value="WIT" <?= $data['zona_waktu'] === 'WIT' ? 'selected' : '' ?>>WIT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jam_masuk" class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= $data['jam_masuk'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_keluar" class="form-label">Jam Keluar</label>
                        <input type="time" class="form-control" id="jam_keluar" name="jam_keluar" value="<?= $data['jam_keluar'] ?>" required>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="lokasi_presensi.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'edit_sukses'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Data Berhasil Disimpan",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php endif; ?>
</body>
</html>