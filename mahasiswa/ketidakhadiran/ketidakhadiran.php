<?php

include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    // Jika belum login, redirect
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
} elseif ($_SESSION['role'] !== 'mahasiswa' && $_SESSION['role'] !== 'siswa') {
    // Jika role bukan mahasiswa atau siswa, redirect
    header('Location: ../../auth/login.php?pesan=Akun_belum_aktif');
    exit();
}
include_once('../../config.php');

$id_users = $_SESSION['id'];
$result = mysqli_query($koneksi, "
    SELECT k.*, a.nama AS nama_admin
    FROM ketidakhadiran k
    LEFT JOIN admin_detail a ON k.disetujui_oleh = a.id_users
    WHERE k.id_users = '$id_users'
    ORDER BY k.id DESC
");
?>

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col"><br>

          
          <h3 class="page-title">
          Ketidakhadiran
          </h3><br>
          <div class="d-flex align-items-center">
             <!-- Tombol Tambah Data -->
            <a href="#" class="btn btn-primary d-none d-sm-inline-block md-3" data-bs-toggle="modal" data-bs-target="#modalTambahDataketidakhadiran"><span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>
                
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
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Status Pengajuan</th>
                    <th>Disetujui Oleh</th>
                </tr>
                <?php if (mysqli_num_rows($result) === 0){ ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data ketidakhadiran</td>
                    </tr>
                    <?php } else {?>
                        <?php while($data = mysqli_fetch_array($result)) : ?>
<tr class="text-center">
    <td><?= $data['id'] ?></td>
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
    <td>
        <?php if ($data['status_pengajuan'] == 'Disetujui'): ?>
            <span class="badge bg-success">Disetujui</span>
        <?php elseif ($data['status_pengajuan'] == 'Ditolak'): ?>
            <span class="badge bg-danger">Ditolak</span>
        <?php else: ?>
            <span class="badge bg-warning text-dark">Menunggu</span>
        <?php endif; ?>
    </td>
    <td>
        <?= $data['nama_admin'] ? $data['nama_admin'] : '<span class="text-muted">-</span>' ?>
    </td>
</tr>
<?php endwhile; ?>

                    <?php }?>
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

 <?php include('../layout/footer.php');?>