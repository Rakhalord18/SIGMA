<?php
include('../../config.php');
$id_users = $_GET['id'];

$query = "SELECT u.id, u.role, u.email, a.nama, a.nip, a.jabatan, a.notelpon, a.foto
          FROM users u
          JOIN admin_detail a ON u.id = a.id_users
          WHERE u.id = '$id_users'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nip = $_POST['nip'];
    $jabatan = $_POST['jabatan'];
    $notelpon = $_POST['notelpon'];
    $role = $_POST['role'];

    $fotoBaru = $data['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_name = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($foto_tmp, '../../uploads/foto_admin/' . $foto_name);
        $fotoBaru = $foto_name;

        if (!empty($data['foto']) && file_exists('../../uploads/foto_admin/' . $data['foto'])) {
            unlink('../../uploads/foto_admin/' . $data['foto']);
        }
    }

    mysqli_query($koneksi, "UPDATE users SET email='$email', role='$role' WHERE id='$id_users'");
    mysqli_query($koneksi, "UPDATE admin_detail SET 
        nama='$nama', nip='$nip', jabatan='$jabatan', notelpon='$notelpon', foto='$fotoBaru'
        WHERE id_users='$id_users'");

    header('Location: ../data_pegawai/pegawai.php?pesan=edit_sukses');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil Admin</title>
    <link href="<?= base_url('assets/css/tabler.min.css') ?>" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Profil Admin</h2>
    <?php if ($data['foto']) : ?>
        <div class="text-center mb-4">
                <img src="<?= base_url('uploads/foto_admin/' . $data['foto']) ?>"                             
                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;" alt="Foto Saat Ini">
        </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" value="<?= $data['email'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input name="nama" value="<?= $data['nama'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input name="nip" value="<?= $data['nip'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input name="jabatan" value="<?= $data['jabatan'] ?>" class="form-control" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">No Telpon</label>
                    <input name="notelpon" value="<?= $data['notelpon'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option value="admin" <?= $data['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="pembimbing" <?= $data['role'] === 'pembimbing' ? 'selected' : '' ?>>Pembimbing</option>
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
            <a href="../data_pegawai/pegawai.php" class="btn btn-secondary">Batal</a>
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
