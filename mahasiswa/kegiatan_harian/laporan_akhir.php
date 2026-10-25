<?php
// filepath: c:\xampp\htdocs\Presensi\mahasiswa\kegiatan_harian\laporan_akhir.php
include('../layout/header.php');
require_once('../../config.php');

if (!isset($_SESSION['login']) || ($_SESSION['role'] !== 'mahasiswa' && $_SESSION['role'] !== 'siswa')) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

$id_users = $_SESSION['id'];

// Ambil data laporan yang sudah diupload + nama admin yang menyetujui
$data = mysqli_query($koneksi, "
    SELECT la.*, ad.nama AS nama_admin
    FROM laporan_akhir la
    LEFT JOIN admin_detail ad ON la.disetujui_oleh = ad.id_users
    WHERE la.id_users='$id_users'
    ORDER BY la.tanggal DESC
");
?>

<div class="container-xl"><br>
    <h2>Riwayat Upload Laporan Akhir</h2>
    <!-- Tombol Tambah Data menggunakan Modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahLaporanAkhir">
        <i class="fa-solid fa-circle-plus"></i> Tambah Data
    </button>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'sukses'): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Laporan akhir berhasil diupload.'
        });
        </script>
    <?php endif; ?>

    <table class="table table-bordered">
        <tr class="text-center">
            <th>No</th>
            <th>Keterangan</th>
            <th>Tanggal Upload</th>
            <th>File</th>
            <th>Status</th>
            <th>Disetujui oleh</th>
        </tr>
        <?php if (mysqli_num_rows($data) === 0): ?>
            <tr><td colspan="5" class="text-center">Belum ada laporan diupload</td></tr>
        <?php else: $no=1; while($row = mysqli_fetch_assoc($data)): ?>
            <tr class="text-center">
                <td><?= $no++ ?></td>
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
            </tr>
        <?php endwhile; endif; ?>
    </table>
</div>

<!-- SweetAlert & FontAwesome (pastikan fontawesome sudah ada di header layout) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include('../layout/footer.php'); ?>
