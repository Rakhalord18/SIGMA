<?php
include('../layout/header.php');

if (!isset($_SESSION['login'])) {
    base_url('../../auth/login.php?pesan=belum_login');
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    base_url('../../auth/login.php?pesan=Akun_belum_aktif');
}

// Query untuk mendapatkan data detail lokasi presensi
$id = $_GET['id']; // Pastikan ID dikirim melalui URL
$result = mysqli_query($koneksi, "SELECT * FROM lokasi_presensi WHERE id = '$id'");

// Periksa apakah data ditemukan
if (mysqli_num_rows($result) === 0) {
    echo "Data tidak ditemukan!";
    exit;
}

$detail = mysqli_fetch_assoc($result);
?>
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <!-- Bagian Detail Lokasi -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-center">Detail Lokasi Presensi</h3>
                            <div class="row justify-content-center">
                                 <!-- Kolom Tengah -->
                                  <div class="col-md-6">
                                     <div class="card">
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Nama Lokasi</th>
                                                    <td><?= $detail['nama_lokasi'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Alamat Lokasi</th>
                                                    <td><?= $detail['alamat_lokasi'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Zona Waktu</th>
                                                    <td><?= $detail['zona_waktu'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Jam Masuk</th>
                                                    <td><?= $detail['jam_masuk'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Jam Keluar</th>
                                                    <td><?= $detail['jam_keluar'] ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <!-- Tombol Back -->
                            <div class="text-center">
                                <a href="javascript:history.back()" class="btn btn-secondary mb-3">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../layout/footer.php'); ?>
</div>