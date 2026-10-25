<?php 
include ('../layout/header.php');

if (!isset($_SESSION['login'])) {
    base_url('../../auth/login.php?pesan=belum_login');
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    base_url('../../auth/login.php?pesan=Akun_belum_aktif');
}

require_once('../../config.php');

// Ambil data filter
$role = isset($_GET['role']) ? $_GET['role'] : '';
$searchName = isset($_GET['search_name']) ? $_GET['search_name'] : '';

// Query untuk mengambil data pegawai berdasarkan filter
$query = "SELECT d.id_users, d.nama, u.email, d.nip, d.jenis_kelamin, d.jabatan, u.role, u.status 
          FROM admin_detail d 
          JOIN users u ON d.id_users = u.id 
          WHERE 1=1";

if ($role) {
    $query .= " AND u.role = '$role'";
}
if ($searchName) {
    $search = mysqli_real_escape_string($koneksi, $searchName);
    $query .= " AND (
        d.nama LIKE '%$search%' OR
        u.email LIKE '%$search%' OR
        d.nip LIKE '%$search%' OR
        d.jenis_kelamin LIKE '%$search%' OR
        d.jabatan LIKE '%$search%' OR
        u.role LIKE '%$search%' OR
        u.status LIKE '%$search%'
    )";
}


$result = mysqli_query($koneksi, $query);
?>
<div class="page-wrapper">
    <!-- Page header -->
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Pegawai
                    </h2><br>
                    <div class="d-flex align-items-center">
                        <!-- Tombol Tambah Data -->
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block me-3" data-bs-toggle="modal" data-bs-target="#modalTambahDatapegawai">
                            <span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span>
                        </a>
                        <!-- Tombol Reset (hanya muncul jika search aktif) -->
                        <?php if ($searchName) { ?>
                            <a href="pegawai.php" class="btn btn-warning d-none d-sm-inline-block me-3">
                                <span class="text"><i class="fa-solid fa-trash-can-arrow-up"></i> Reset</span>
                            </a>
                        <?php } ?>
                        <!-- Input Pencarian di kanan -->
                        <div class="ms-auto">
                            <form class="d-flex ms-auto" method="GET" action="">
                                <input type="text" name="search_name" class="form-control me-2" placeholder="Cari Data Apapun..." value="<?= htmlspecialchars($searchName) ?>">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Cari</button>
                            </form>
                                        

                        </div>
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
                            <th>Email</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th colspan="3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($pegawai = mysqli_fetch_array($result)) {
                            $id_users = $pegawai['id_users'];
                            $nama = $pegawai['nama'];
                            $email = $pegawai['email'];
                            $nip = $pegawai['nip'];
                            $jabatan = $pegawai['jabatan'];
                            $role = $pegawai['role'];
                            $status = $pegawai['status'];
                        ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td><?= $nama ?></td>
                                <td><?= $email ?></td>
                                <td><?= $nip ?></td>
                                <td><?= $jabatan ?></td>
                                <td><?= $role ?></td>
                                <td><?= $status ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/data_pegawai/edit.php?id=' . $id_users) ?>" class="btn btn-primary"><span class="text"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/data_pegawai/delete.php?id=' . $id_users) ?>" class="btn btn-danger btn-delete" data-id="<?= $id_users ?>"><span class="text"><i class="fa-solid fa-trash-can"></i></span></a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/data_pegawai/detail.php?id=' . $id_users) ?>" class="btn btn-info"><span class="text"><i class="fa-solid fa-eye"></i></span></a>
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
            const pegawaiId = this.getAttribute('data-id');
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
                    // Redirect to delete.php with the pegawai ID
                    window.location.href = "delete.php?id=" + pegawaiId;
                }
            });
        });
    });
</script>