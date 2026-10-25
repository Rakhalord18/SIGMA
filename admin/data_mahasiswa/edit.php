<?php
include('../../config.php');
$id_users = $_GET['id'];

$query = "SELECT u.id, u.role, u.email, u.status, m.nama, m.nis_nim, m.jenis_kelamin, m.jurusan, m.asal_instansi, m.alamat, m.notelpon, m.tanggal_mulai, m.tanggal_selesai, m.lokasi_presensi, m.foto
          FROM users u
          JOIN mahasiswa_detail m ON u.id = m.id_users
          WHERE u.id = '$id_users'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

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
    $lokasi_id = $_POST['lokasi_presensi'];
    $role = $_POST['role'];
    $status = $_POST['status'];


    // Ambil nama lokasi dari tabel lokasi_presensi
    $lokasi_query = "SELECT nama_lokasi FROM lokasi_presensi WHERE id = '$lokasi_id'";
    $lokasi_result = mysqli_query($koneksi, $lokasi_query);
    $lokasi_data = mysqli_fetch_assoc($lokasi_result);
    $nama_lokasi = $lokasi_data['nama_lokasi'] ?? '';

    // Update tabel `users`
    $query_users = "UPDATE users SET email = '$email', role = '$role',status = '$status' WHERE id = '$id_users'";
    mysqli_query($koneksi, $query_users);

    // Update tabel `mahasiswa_detail` (dengan menyimpan nama lokasi)
    $query_mahasiswa = "UPDATE mahasiswa_detail 
                        SET nama = '$nama', nis_nim = '$nis_nim', jenis_kelamin = '$jenis_kelamin', jurusan = '$jurusan',
                            asal_instansi = '$asal_instansi', alamat = '$alamat', notelpon = '$notelpon', 
                            tanggal_mulai = '$tanggal_mulai', tanggal_selesai = '$tanggal_selesai', lokasi_presensi = '$nama_lokasi' 
                        WHERE id_users = '$id_users'";
    mysqli_query($koneksi, $query_mahasiswa);

    $fotoBaru = $data['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_name = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($foto_tmp, '../../uploads/foto_mahasiswa/' . $foto_name);
        $fotoBaru = $foto_name;

        // Hapus foto lama
        if (!empty($data['foto']) && file_exists('../../uploads/foto_mahasiswa/' . $data['foto'])) {
            unlink('../../uploads/foto_mahasiswa/' . $data['foto']);
        }
    }

    mysqli_query($koneksi, "UPDATE users SET email='$email', role='$role', status='$status' WHERE id='$id_users'");
    mysqli_query($koneksi, "UPDATE mahasiswa_detail SET 
        nama='$nama', nis_nim='$nis_nim', jenis_kelamin='$jenis_kelamin', jurusan='$jurusan',
        asal_instansi='$asal_instansi', alamat='$alamat', notelpon='$notelpon', foto='$fotoBaru'
        WHERE id_users='$id_users'");

header('Location: mahasiswa_siswa.php?id=' . $id_users . '&pesan=edit_sukses');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil Mahasiswa</title>
    <link href="<?= base_url('assets/css/tabler.min.css') ?>" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Profil Mahasiswa</h2>

    <?php if ($data['foto']) : ?>
        <div class="text-center mb-4">
            <img src="<?= base_url('uploads/foto_mahasiswa/' . $data['foto']) ?>" 
                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;" alt="Foto Saat Ini">
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3"><label class="form-label">Email</label>
                    <input name="email" type="email" value="<?= $data['email'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label class="form-label">Nama</label>
                    <input name="nama" value="<?= $data['nama'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label class="form-label">NIS/NIM</label>
                    <input name="nis_nim" value="<?= $data['nis_nim'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label class="form-label">Jenis Kelamin</label>
                    <input name="jenis_kelamin" value="<?= $data['jenis_kelamin'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="tanggal_mulai" value="<?= $data['tanggal_mulai'] ?>" required>
                </div>
                <div class="mb-3"><label class="form-label">Jurusan</label>
                    <input name="jurusan" value="<?= $data['jurusan'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="lokasi_presensi" class="form-label">Lokasi Presensi</label>
                    <select class="form-select" name="lokasi_presensi" required>
                        <option value="">Pilih Lokasi Presensi</option>
                        <?php
                        $query_lokasi = "SELECT id, nama_lokasi FROM lokasi_presensi";
                        $result_lokasi = mysqli_query($koneksi, $query_lokasi);
                        while ($lokasi = mysqli_fetch_assoc($result_lokasi)) {
                            $selected = ($data['lokasi_presensi'] === $lokasi['nama_lokasi']) ? 'selected' : '';
                            echo "<option value='{$lokasi['id']}' $selected>{$lokasi['nama_lokasi']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3"><label class="form-label">Asal Instansi</label>
                    <input name="asal_instansi" value="<?= $data['asal_instansi'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label class="form-label">Alamat</label>
                    <input name="alamat" value="<?= $data['alamat'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label class="form-label">No Telpon</label>
                    <input name="notelpon" value="<?= $data['notelpon'] ?>" class="form-control" required>
                </div>
                <div class="mb-3"><label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="mahasiswa" <?= $data['role'] === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="siswa" <?= $data['role'] === 'siswa' ? 'selected' : '' ?>>Siswa</option>
                    </select>
                </div>
                <div class="mb-3"><label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" name="tanggal_selesai" value="<?= $data['tanggal_selesai'] ?>" required>
                </div>
                <div class="mb-3"><label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif" <?= $data['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Tidak Aktif" <?= $data['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Profil (PNG/JPG)</label>
                    <input type="file" name="foto" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="javascript:history.back()" class="btn btn-secondary">Batal</a>
            <a href="edit_password.php?id=<?= $id_users ?>" class="btn btn-warning">Edit Password</a>
        </div>
    </form>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'edit_sukses'): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Data berhasil disimpan!",
        showConfirmButton: false,
        timer: 1500
    });
</script>
<?php endif; ?>
</body>
</html>
