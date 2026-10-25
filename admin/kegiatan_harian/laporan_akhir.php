<?php
include('../layout/header.php');
require_once('../../config.php');

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Ambil semua data laporan akhir dan join ke mahasiswa_detail untuk nama
$data = mysqli_query($koneksi, "
    SELECT la.*, md.nama, ad.nama AS nama_admin
    FROM laporan_akhir la
    LEFT JOIN mahasiswa_detail md ON la.id_users = md.id_users
    LEFT JOIN admin_detail ad ON la.disetujui_oleh = ad.id_users
    ORDER BY la.tanggal DESC
");

$belum_upload = mysqli_query($koneksi, "
    SELECT md.id_users, md.nama, u.status AS status_user
    FROM mahasiswa_detail md 
    LEFT JOIN laporan_akhir la ON md.id_users = la.id_users 
    LEFT JOIN users u ON md.id_users = u.id
    WHERE la.id_users IS NULL
");

?>

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col"><br>
          <h2 class="page-title">
            Data Laporan Akhir Mahasiswa
          </h2>
        </div>  
      </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <h4>Mahasiswa Belum Mengirimkan Laporan Akhir</h4>
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No</th>
                <th>ID Users</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if (mysqli_num_rows($belum_upload) === 0): ?>
                <tr><td colspan="5" class="text-center">Semua mahasiswa sudah mengirimkan laporan</td></tr>
            <?php else: $no=1; while($row = mysqli_fetch_assoc($belum_upload)): ?>
                <tr class="text-center">
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['id_users']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['status_user']) ?></td>
                    <td>
                        <!-- Tombol untuk menambah laporan -->
                        <a href="tambah_laporan.php?id=<?= $row['id_users'] ?>" class="btn btn-primary btn-sm">Tambah Laporan</a>
                    </td>
                </tr>
            <?php endwhile; endif; ?>
        </table>

        <br>
        <h4>Data Laporan Akhir Mahasiswa</h4>
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No</th>
                <th>ID Users</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>Tanggal Upload</th>
                <th>File</th>
                <th>Status</th>
                <th>Disetujui Oleh</th>
                <th>Aksi</th>
            </tr>
            <?php if (mysqli_num_rows($data) === 0): ?>
                <tr><td colspan="8" class="text-center">Belum ada laporan diupload</td></tr>
            <?php else: $no=1; while($row = mysqli_fetch_assoc($data)): ?>
                <tr class="text-center">
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['id_users']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td>
                        <?php if ($row['file']): ?>
                            <a href="../../uploads/laporan_akhir/<?= htmlspecialchars($row['file']) ?>" target="_blank" class="badge bg-primary">Lihat File</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['STATUS']) ?></td>
                    <td>
                        <?= $row['nama_admin'] ? htmlspecialchars($row['nama_admin']) : '<span class="text-muted">-</span>' ?>
                    </td>
                    <td>
                        <?php if ($row['STATUS'] == 'Pending' || $row['STATUS'] == 'Ditolak'): ?>
                            <!-- Tombol edit status dengan icon pensil -->
                            <a href="#" class="btn btn-warning btn-edit-status" data-id="<?= $row['id'] ?>" title="Edit Status">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        <?php endif; ?>
                        <a href="hapus_laporan.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-delete" title="Hapus Laporan">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; endif; ?>
        </table>
    </div>
</div>


<!-- Modal Tambah Data Laporan Akhir -->
<div class="modal fade" id="modalTambahLaporan" tabindex="-1" aria-labelledby="modalTambahLaporanAkhirLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="tambah_laporan_admin.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLaporanAkhirLabel">Upload Laporan Akhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Laporan Akhir Magang" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Laporan (ZIP, max 100MB)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".zip" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert & FontAwesome (pastikan fontawesome sudah ada di header layout) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.btn-edit-status').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');

        Swal.fire({
            title: 'Pilih status baru:',
            input: 'select',
            inputOptions: {
                'Disetujui': 'Disetujui',
                'Ditolak': 'Ditolak',
                'Pending': 'Pending'
            },
            inputPlaceholder: 'Pilih status',
            showCancelButton: true,
            confirmButtonText: 'Ubah Status',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus memilih status!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke update_status.php dengan status baru
                window.location.href = `update_status.php?id=${id}&STATUS=${result.value}`;
            }
        });
    });
});
</script>

<?php include('../layout/footer.php'); ?>
