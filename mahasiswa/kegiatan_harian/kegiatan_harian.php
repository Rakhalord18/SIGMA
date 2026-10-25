<?php
// filepath: c:\xampp\htdocs\Presensi\mahasiswa\fitur\kegiatan_harian.php
include('../layout/header.php');
require_once('../../config.php');

// Redirect berdasarkan role
if ($_SESSION['role'] === 'mahasiswa' || $_SESSION['role'] === 'siswa') {
    // Lanjutkan ke halaman mahasiswa
} else {
    // Jika role tidak dikenali, redirect ke halaman login
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

$id_users = $_SESSION['id'];

// Ambil data kegiatan harian user
$result = mysqli_query($koneksi, "SELECT * FROM kegiatan_harian WHERE id_users='$id_users' ORDER BY tanggal DESC");
?>
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <br>
          <h2 class="page-title">
          Data Kegiatan Harian
          </h2><br>
          <div class="d-flex align-items-center">
             <!-- Tombol Tambah Data -->
            <a href="#" class="btn btn-primary d-none d-sm-inline-block me-3" data-bs-toggle="modal" data-bs-target="#modalTambahKegiatanHarian"><span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>   
            <br>
            <!-- Tombol Export PDF -->
            <a href="kegiatan_harian_pdf.php" class="btn btn-success d-none d-sm-inline-block me-3"><span class="text"><i class="fa-solid fa-file-arrow-down"></i>
                Export PDF
            </span></a>
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
            <th>Hari</th>
            <th>Tanggal</th>
            <th>Deskripsi</th>
            </tr>
        <?php if (mysqli_num_rows($result) === 0): ?>
            <tr><td colspan="5" class="text-center">Belum ada data kegiatan harian</td></tr>
        <?php else: $no=1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr class="text-center">
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['hari']) ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
            </tr>
        <?php endwhile; endif; ?>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.bg-danger').forEach(a => {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            const userId = this.getAttribute('data-id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus data!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to delete.php with the user ID
                    window.location.href = "hapus.php?id=" + userId;
                }
            });
        });
    });
</script>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'simpan_sukses'): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data Kegiatan Kamu Berhasil Disimpan",
        showConfirmButton: false,
        timer: 1500
    });
</script>
<?php endif; ?>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'periode'): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Tanggal di Luar Periode Magang Anda!'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'tanggal_tidak_valid'): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Anda Harus Mengisi Tanggal Sesuai Dengan Sekarang!'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'duplikat'): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Anda Sudah Mengisi Kegiatan Pada Hari dan Tanggal ini!'
});
</script>

<?php endif; ?>
<?php include('../layout/footer.php'); ?>