<?php
include('../layout/header.php');
require_once('../../config.php');

$id = $_SESSION['id'];

// Ambil data admin dari database
$query = "SELECT a.id_users, a.nama, u.email, a.notelpon, a.foto, u.role, u.status 
          FROM admin_detail a 
          JOIN users u ON a.id_users = u.id 
          WHERE a.id_users = '$id' LIMIT 1";
$result = mysqli_query($koneksi, $query);
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
    echo "<div class='alert alert-danger'>Data admin tidak ditemukan.</div>";
    exit;
}
?>

<div class="container-xl mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center">
                    <!-- Foto Profil -->
                    <img src="<?= base_url('uploads/foto_admin/' . $admin['foto']) ?>" 
                         class="mb-3" 
                         style="border-radius: 100%; width: 150px; height: 150px; object-fit: cover;"
                         alt="Foto Profil Admin">
                         
                    <h3><?= htmlspecialchars($admin['nama']) ?></h3>
                    <span class="badge bg-primary"><?= htmlspecialchars($admin['role']) ?></span>
                    <span class="badge <?= $admin['status']=='Aktif' ? 'bg-success' : 'bg-danger' ?>">
                        <?= htmlspecialchars($admin['status']) ?>
                    </span>

                    <table class="table table-borderless text-start mt-4">
                        <tr><th>Email</th><td><?= htmlspecialchars($admin['email']) ?></td></tr>
                        <tr><th>No Telpon</th><td><?= htmlspecialchars($admin['notelpon']) ?></td></tr>
                    </table>

                    <div class="mt-4">
                        <a href="<?= base_url('admin/home/home.php') ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('admin/data_pegawai/edit.php?id=' . $admin['id_users']) ?>" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
