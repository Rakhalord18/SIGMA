<?php 
include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    base_url('../../auth/login.php?pesan=belum_login');
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    base_url('../../auth/login.php?pesan=Akun_belum_aktif');
}

// Query untuk mendapatkan data detail pegawai
$id = $_GET['id']; // Pastikan ID dikirim melalui URL
$result = mysqli_query($koneksi, "SELECT p.id_users, p.nama, p.nip, p.jenis_kelamin, p.jabatan, p.notelpon, u.email, u.role, u.status, p.foto
    FROM admin_detail p 
    JOIN users u ON p.id_users = u.id 
    WHERE p.id_users = '$id'");

$detail = mysqli_fetch_assoc($result);

if (!$detail) {
    echo "Data tidak ditemukan!";
    exit;
}
?>
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body">
                            <h3 class="card-title text-center">Detail Pegawai</h3>
                            <table class="table table-borderless">
                                <tr>
                                <th>Foto Profil</th> 
                                    <td>
                                        <?php if (!empty($detail['foto'])): ?>
                                            <img src="../../uploads/foto_admin/<?= htmlspecialchars($detail['foto']) ?>" alt="Foto Admin" width="120" height="150">
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada foto</span>
                                        <?php endif; ?>
                                    </td>
                                        </tr>
                                    <th>Nama</th>
                                    <td><?= htmlspecialchars($detail['nama']) ?></td>
                                </tr>
                                <tr>
                                    <th>NIP</th>
                                    <td><?= htmlspecialchars($detail['nip']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?= htmlspecialchars($detail['jenis_kelamin']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td><?= htmlspecialchars($detail['jabatan']) ?></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td><?= ucfirst(htmlspecialchars($detail['role'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body">
                            <h3 class="card-title text-center">Informasi Tambahan</h3>
                            <table class="table table-borderless">
                                <tr>
                                    <th>No Telepon</th>
                                    <td><?= htmlspecialchars($detail['notelpon']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= htmlspecialchars($detail['email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?= ucfirst(htmlspecialchars($detail['status'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <!-- Tombol Back -->
            <div class="text-center">
                <a href="javascript:history.back()" class="btn btn-secondary mb-3">Kembali</a>
            </div>
        </div>
    </div>
    <?php include ('../layout/footer.php'); ?>
</div>