<?php
include('../layout/header.php');
require_once('../../config.php');

// Cek login dan role admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Ambil keyword pencarian jika ada
$searchName = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';

// Query dasar
$query = "SELECT kh.*, md.nama 
FROM kegiatan_harian kh
JOIN mahasiswa_detail md ON md.id_users = kh.id_users
WHERE 1=1";

// Tambahkan filter search jika ada
if ($searchName) {
    $search_esc = mysqli_real_escape_string($koneksi, $searchName);
    $query .= " AND (
        kh.id_users LIKE '%$search_esc%' OR
        md.nama LIKE '%$search_esc%' OR
        kh.hari LIKE '%$search_esc%' OR
        kh.tanggal LIKE '%$search_esc%' OR
        kh.deskripsi LIKE '%$search_esc%'
    )";
}
$query .= " ORDER BY kh.tanggal DESC";
$result = mysqli_query($koneksi, $query);
?>

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          
          <h2 class="page-title me-3"><br> 
          Data Kegiatan Harian
          </h2><br>
          <div class="d-flex align-items-center">
             
            <!-- Tombol Export Excel dan PDF (tanpa filter jika search_name kosong) -->
            <a href="kegiatan_harian_excel.php?search_name=<?= htmlspecialchars($searchName) ?>" class="btn btn-success d-none d-sm-inline-block me-3">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="kegiatan_harian_pdf.php?search_name=<?= htmlspecialchars($searchName) ?>" class="btn btn-primary d-none d-sm-inline-block me-3">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
            <!-- Tombol Reset (hanya muncul jika search aktif) -->
            <?php if ($searchName) { ?>
                <a href="kegiatan_harian.php" class="btn btn-warning d-none d-sm-inline-block me-3">
                <span class="text"><i class="fa-solid fa-trash-can-arrow-up"></i> Reset</span>
                </a>
            <?php } ?>
            <!-- Search -->
            <form class="d-flex ms-auto" method="GET" action="">
                <input type="text" name="search_name" class="form-control me-2" placeholder="Cari Data Apapun..." value="<?= htmlspecialchars($searchName) ?>">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Cari</button>
            </form>
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
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr><td colspan="7" class="text-center">Belum ada data kegiatan harian</td></tr>
                <?php else: $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['id_users']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['hari']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>
                            <?php if ($row['file']): ?>
                                <a href="../../uploads/kegiatan_harian/<?= $row['file'] ?>" target="_blank" class="badge bg-primary">Lihat File</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </tr>
                <?php endwhile; endif; ?>
            </table>
        </div>
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
<?php include('../layout/footer.php'); ?>