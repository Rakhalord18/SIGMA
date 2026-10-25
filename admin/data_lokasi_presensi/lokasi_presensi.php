<?php 
require_once('../../config.php');
include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    base_url('../../auth/login.php?pesan=belum_login');
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    base_url('../../auth/login.php?pesan=Akun_belum_aktif');
}

$result = mysqli_query($koneksi, "SELECT * FROM lokasi_presensi ORDER BY id DESC");
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Data Lokasi Presensi</h2><br>
                    <div class="d-flex align-items-center">
                        <!-- Tombol Tambah Data -->
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block me-3" data-bs-toggle="modal" data-bs-target="#modalTambahDatalokasi">
                            <span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <table class="table table-bordered mt-3">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Lokasi</th>
                        <th>Alamat Lokasi</th>
                        <th>Zona Waktu</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php $no = 1; ?>
                    <?php if(mysqli_num_rows($result) === 0): ?>
                        <tr>
                            <td colspan="7">Data Kosong, Silahkan Tambah Data Baru</td>
                        </tr>
                    <?php else: ?>
                        <?php while($lokasi = mysqli_fetch_array($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $lokasi['nama_lokasi'] ?></td>
                                <td><?= $lokasi['alamat_lokasi'] ?></td>
                                <td><?= $lokasi['zona_waktu'] ?></td>
                                <td><?= $lokasi['jam_masuk'] ?></td>
                                <td><?= $lokasi['jam_keluar'] ?></td>
                                <td>
                                    <a href="<?= base_url('admin/data_lokasi_presensi/edit.php?id=' . $lokasi['id']) ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="<?= base_url('admin/data_lokasi_presensi/hapus.php') ?>" class="btn btn-danger btn-sm btn-delete" data-id="<?= $lokasi['id'] ?>"><i class="fa-solid fa-trash-can"></i></a>
                                    <a href="<?= base_url('admin/data_lokasi_presensi/detail.php?id=' . $lokasi['id']) ?>" class="btn btn-info btn-sm"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= include('../layout/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const lokasiId = this.getAttribute('data-id');
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "hapus.php?id=" + lokasiId;
                    }
                });
            });
        });
    </script>