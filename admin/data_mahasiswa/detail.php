<?php 
include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    base_url('../../auth/login.php?pesan=belum_login');
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    base_url('../../auth/login.php?pesan=Akun_belum_aktif');
}

// Query untuk mendapatkan data detail
$id = $_GET['id']; // Pastikan ID dikirim melalui URL
$result = mysqli_query($koneksi, "SELECT m.id_users, m.nama, m.nis_nim, m.jenis_kelamin, m.jurusan, u.role, m.asal_instansi, m.alamat, m.notelpon, u.email, u.password, m.tanggal_mulai, m.tanggal_selesai, u.status, m.lokasi_presensi, m.foto
    FROM mahasiswa_detail m 
    JOIN users u ON m.id_users = u.id 
    WHERE m.id_users = '$id'");
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
                <!-- Bagian Kiri -->
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="card w-100 h-100">
                        <div class="card-body">
                            <h3 class="card-title text-center">Informasi Mahasiswa/Siswa</h3>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Foto Profil</th> 
                                    <td>
                                        <?php if (!empty($detail['foto'])): ?>
                                            <img src="../../uploads/foto_mahasiswa/<?= htmlspecialchars($detail['foto']) ?>" alt="Foto Mahasiswa" width="120" height="150">
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada foto</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td><?= htmlspecialchars($detail['nama']) ?></td>
                                </tr>
                                <tr>
                                    <th>NIM/NIS</th>
                                    <td><?= htmlspecialchars($detail['nis_nim']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?= htmlspecialchars($detail['jenis_kelamin']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td><?= htmlspecialchars($detail['jurusan']) ?></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td><?= ucfirst(htmlspecialchars($detail['role'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Asal Instansi</th>
                                    <td><?= htmlspecialchars($detail['asal_instansi']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Bagian Kanan -->
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="card w-100 h-100">
                        <div class="card-body">
                            <h3 class="card-title text-center">Informasi Tambahan</h3>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Alamat</th>
                                    <td><?= htmlspecialchars($detail['alamat']) ?></td>
                                </tr>
                                <tr>
                                    <th>No Telepon</th>
                                    <td><?= htmlspecialchars($detail['notelpon']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= htmlspecialchars($detail['email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <td><?= htmlspecialchars($detail['tanggal_mulai']) ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td><?= htmlspecialchars($detail['tanggal_selesai']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?= ucfirst(htmlspecialchars($detail['status'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Lokasi Presensi</th>
                                    <td><?= htmlspecialchars($detail['lokasi_presensi']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <!-- Button -->
            <div class="text-center">
                <a href="javascript:history.back()" class="btn btn-secondary mb-3">Kembali</a>
                <a href="export_pdf_detail.php?id=<?= $id ?>" target="_blank" class="btn btn-success mb-3">
                    <i class="fa-solid fa-file-arrow-down"></i> Export PDF Data Pribadi
                </a>
            </div>  
        </div>
    </div>
    <?php include ('../layout/footer.php'); ?>
</div>