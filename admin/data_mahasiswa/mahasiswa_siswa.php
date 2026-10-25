<?php 
include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    base_url('../../auth/login.php?pesan=belum_login');
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    base_url('../../auth/login.php?pesan=Akun_belum_aktif');
}

require_once('../../config.php');

// Periksa tanggal selesai dan ubah status jika sudah lewat
$current_date = date('Y-m-d'); // Tanggal hari ini
$query_update_status = "UPDATE users u 
                        JOIN mahasiswa_detail m ON u.id = m.id_users 
                        SET u.status = 'Tidak Aktif' 
                        WHERE m.tanggal_selesai < '$current_date' AND u.status = 'Aktif'";
mysqli_query($koneksi, $query_update_status);

// Ambil data filter
$role = isset($_GET['role']) ? $_GET['role'] : '';
$searchName = isset($_GET['search_name']) ? $_GET['search_name'] : '';

// Query untuk mengambil data berdasarkan filter
$query = "SELECT m.id_users, m.nama, m.nis_nim, m.jurusan, m.jenis_kelamin, u.role, m.asal_instansi, m.notelpon, u.email, m.tanggal_mulai, m.tanggal_selesai, u.status, m.lokasi_presensi
          FROM mahasiswa_detail m 
          JOIN users u ON m.id_users = u.id 
          WHERE 1=1";

if ($role) {
    $query .= " AND u.role = '$role'";
}
if ($searchName) {
    $search = mysqli_real_escape_string($koneksi, $searchName);
    $query .= " AND (
        m.nama LIKE '%$search%' OR
        m.nis_nim LIKE '%$search%' OR
        m.jurusan LIKE '%$search%' OR
        m.jenis_kelamin LIKE '%$search%' OR
        u.role LIKE '%$search%' OR
        m.asal_instansi LIKE '%$search%' OR
        m.notelpon LIKE '%$search%' OR
        u.email LIKE '%$search%' OR
        m.tanggal_mulai LIKE '%$search%' OR
        m.tanggal_selesai LIKE '%$search%' OR
        u.status LIKE '%$search%' OR
        m.lokasi_presensi LIKE '%$search%'
    )";
}


$result = mysqli_query($koneksi, $query);
?>
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Mahasiswa dan Siswa
                    </h2><br>
                    <div class="d-flex align-items-center">
                        <!-- Tombol Tambah Data -->
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block me-3" data-bs-toggle="modal" data-bs-target="#modalTambahData">
                            <span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span>
                        </a>

                        <!-- Tombol Export PDF -->
                        <a href="export_pdf.php?role=<?= $role ?>&search_name=<?= $searchName ?>" class="btn btn-success d-none d-sm-inline-block me-3">
                            <span class="text"><i class="fa-solid fa-file-pdf"></i> Export PDF</span>
                        </a>

                        <!-- Tombol Reset (hanya muncul jika search aktif) -->
                        <?php if ($searchName) { ?>
                            <a href="mahasiswa_siswa.php" class="btn btn-warning d-none d-sm-inline-block me-3">
                                <span class="text"><i class="fa-solid fa-trash-can-arrow-up"></i> Reset</span>
                            </a>
                        <?php } ?>

                        <!-- Form Search & Filter Role -->
                        <form method="GET" class="d-flex ms-auto" action="">
                            <div class="me-2">
                                <select class="form-select" name="role" onchange="this.form.submit()">
                                    <option value="">Pilih Role</option>
                                    <option value="mahasiswa" <?= $role === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                                    <option value="siswa" <?= $role === 'siswa' ? 'selected' : '' ?>>Siswa</option>
                                </select>
                            </div>
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
            <div class="row row-deck row-cards">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr class="text-center">
                            <th class="w-1">No</th>
                            <th>Nama</th>
                            <th>NIM/NIS</th>
                            <th>Jenis Kelamin</th>
                            <th>Jurusan</th>
                            <th>Role</th>
                            <th>Asal Instansi</th>
                            <th>Status</th>
                            <th>Lokasi Presensi</th>
                            <th colspan="3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($presensi = mysqli_fetch_array($result)) {
                            $nama = $presensi['nama'];
                            $nis_nim = $presensi['nis_nim'];
                            $jeniskelamin = $presensi['jenis_kelamin'];
                            $jurusan = $presensi['jurusan'];
                            $role = $presensi['role'];
                            $asalinstansi = $presensi['asal_instansi'];
                            $status = $presensi['status'];
                            $lokasipresensi = $presensi['lokasi_presensi'];
                        ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td><?= $nama ?></td>
                                <td><?= $nis_nim ?></td>
                                <td><?= $jeniskelamin ?></td>
                                <td><?= $jurusan ?></td>
                                <td><?= $role ?></td>
                                <td><?= $asalinstansi ?></td>
                                <td><?= $status ?></td>
                                <td><?= $lokasipresensi ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/data_mahasiswa/edit.php?id=' . $presensi['id_users']) ?>" class="btn btn-primary"><span class="text"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-danger btn-delete" data-id="<?= $presensi['id_users'] ?>"><span class="text"><i class="fa-solid fa-trash-can"></i></span></a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/data_mahasiswa/detail.php?id=' . $presensi['id_users']) ?>" class="btn btn-info"><span class="text"><i class="fa-solid fa-eye"></i></span></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?= include('../layout/footer.php') ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
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
                    window.location.href = "delete.php?id=" + userId;
                }
            });
        });
    });
</script>