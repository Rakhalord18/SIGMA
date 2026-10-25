<?php
include('../layout/header.php');
require_once('../../config.php');

$id = $_SESSION['id'];

// Ambil data mahasiswa
$query = "SELECT d.id_users, d.nama, u.email, d.nis_nim, d.jenis_kelamin, d.jurusan, d.asal_instansi, d.alamat, d.notelpon, d.tanggal_mulai, d.tanggal_selesai, d.lokasi_presensi, d.foto, u.role, u.status 
          FROM mahasiswa_detail d 
          JOIN users u ON d.id_users = u.id 
          WHERE d.id_users = '$id' LIMIT 1";
$result = mysqli_query($koneksi, $query);
$mahasiswa = mysqli_fetch_assoc($result);

if (!$mahasiswa) {
    echo "<div class='alert alert-danger'>Data pegawai tidak ditemukan.</div>";
    exit;
}
?>

<div class="container-xl mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center">
                    <!-- Foto Profil -->
                    <img src="<?= base_url('uploads/foto_mahasiswa/' . $mahasiswa['foto']) ?>" 
                         class="mb-3" 
                         style="border-radius: 100%; width: 150px; height: 150px; object-fit: cover;"
                         alt="Foto Profil">
                         
                    <h3><?= htmlspecialchars($mahasiswa['nama']) ?></h3>
                    <span class="badge bg-primary"><?= htmlspecialchars($mahasiswa['role']) ?></span>
                    <span class="badge <?= $mahasiswa['status']=='Aktif' ? 'bg-success' : 'bg-danger' ?>">
                        <?= htmlspecialchars($mahasiswa['status']) ?>
                    </span>

                    <table class="table table-borderless text-start mt-4">
                        <tr><th>Email</th><td><?= htmlspecialchars($mahasiswa['email']) ?></td></tr>
                        <tr><th>NIS/NIM</th><td><?= htmlspecialchars($mahasiswa['nis_nim']) ?></td></tr>
                        <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($mahasiswa['jenis_kelamin']) ?></td></tr>
                        <tr><th>Jurusan</th><td><?= htmlspecialchars($mahasiswa['jurusan']) ?></td></tr>
                        <tr><th>Asal Instansi</th><td><?= htmlspecialchars($mahasiswa['asal_instansi']) ?></td></tr>
                        <tr><th>Alamat</th><td><?= htmlspecialchars($mahasiswa['alamat']) ?></td></tr>
                        <tr><th>No Telpon</th><td><?= htmlspecialchars($mahasiswa['notelpon']) ?></td></tr>
                        <tr><th>Tanggal Mulai</th><td><?= htmlspecialchars($mahasiswa['tanggal_mulai']) ?></td></tr>
                        <tr><th>Tanggal Selesai</th><td><?= htmlspecialchars($mahasiswa['tanggal_selesai']) ?></td></tr>
                        <tr><th>Alamat Lokasi</th><td><?= htmlspecialchars($mahasiswa['lokasi_presensi']) ?></td></tr>
                    </table>

                    <div class="mt-4">
                        <a href="<?= base_url('mahasiswa/home/home.php')?>" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('mahasiswa/fitur/edit.php?id=' . $mahasiswa['id_users']) ?>" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
