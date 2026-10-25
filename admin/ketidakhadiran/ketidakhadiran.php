<?php

include('../layout/header.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Redirect berdasarkan role
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

include_once('../../config.php');

// Ambil data ketidakhadiran join nama user
$result = mysqli_query($koneksi, "
    SELECT k.*, u.id AS id_users, md.nama, a.nama AS nama_admin
    FROM ketidakhadiran k
    LEFT JOIN users u ON k.id_users = u.id
    LEFT JOIN mahasiswa_detail md ON u.id = md.id_users
    LEFT JOIN admin_detail a ON k.disetujui_oleh = a.id_users
    ORDER BY k.id DESC
");

?>

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col"><br>
          <h2 class="page-title">
            Data Ketidakhadiran Mahasiswa/Siswa
          </h2><br>
          <div class="d-flex align-items-center">
             <!-- Tombol Tambah Data -->
            <a href="#" class="btn btn-primary d-none d-sm-inline-block md-3" data-bs-toggle="modal" data-bs-target="#modalTambahDataketidakhadiran">
                <span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>
            </a>
          </div>
        </div>
        
      </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No</th>
                <th>ID Users</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Status Pengajuan</th>
                <th>Disetujui Oleh</th>
                <th>Aksi</th>
            </tr>
            <?php if (mysqli_num_rows($result) === 0){ ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data ketidakhadiran</td>
                </tr>
            <?php } else {
                $no = 1;
                while($data = mysqli_fetch_array($result)) : ?>
                <tr class="text-center">
                    <td><?= $no++ ?></td>
                    <td><?= $data['id_users'] ?></td>
                    <td><?= htmlspecialchars($data['nama']) ?></td>
                    <td><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                    <td><?= $data['keterangan'] ?></td>
                    <td><?= $data['deskripsi'] ?></td>
                    <td>
                        <?php if (!empty($data['file']) && $data['file'] !== 'Tidak Ada File'): ?>
                            <a href="../../assets/file/<?= $data['file'] ?>" target="_blank" class="badge badge-pill bg-primary">Lihat File</a>
                        <?php else: ?>
                            <span class="text-muted">Tidak Ada File</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $data['status_pengajuan'] ?></td>
                    <td>
                        <?= $data['nama_admin'] ? $data['nama_admin'] : '<span class="text-muted">-</span>' ?>
                    </td>
                    <td>
                        <?php if ($data['status_pengajuan'] == 'Menunggu'): ?>
                            <a href="update_status.php?id=<?= $data['id'] ?>&status=Disetujui" class="badge badge-pill bg-success" onclick="return confirm('ACC pengajuan ini?')">ACC</a>
                            <a href="update_status.php?id=<?= $data['id'] ?>&status=Ditolak" class="badge badge-pill bg-warning" onclick="return confirm('Tolak pengajuan ini?')">Tolak</a>
                        <?php endif; ?>
                        <a href="hapus.php?id=<?= $data['id'] ?>" class="badge badge-pill bg-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; } ?>
        </table>
    </div>
</div>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'simpan_sukses'): ?>
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

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'duplikat'): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Anda sudah meminta izin pada hari dan tanggal ini!'
});
</script>

<?php endif; ?>

<?php include('../layout/footer.php'); ?>