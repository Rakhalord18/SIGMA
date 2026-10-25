<?php

include ('../layout/header.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Redirect berdasarkan role
if ($_SESSION['role'] === 'mahasiswa' || $_SESSION['role'] === 'siswa') {
    // Lanjutkan ke halaman mahasiswa
} else {
    // Jika role tidak dikenali, redirect ke halaman login
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}
include_once('../../config.php');

$id_users = $_SESSION['id'];

// Periksa apakah filter tanggal digunakan
if (empty($_GET['tanggal_dari']) || empty($_GET['tanggal_sampai'])) {
    // Jika filter tanggal kosong, ambil semua data
    $result = mysqli_query($koneksi, "SELECT * FROM presensi_detail 
    WHERE id_users = '$id_users' ORDER BY tanggal_masuk DESC");
} else {
    // Jika filter tanggal digunakan, ambil data berdasarkan rentang tanggal
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $result = mysqli_query($koneksi, "SELECT * FROM presensi_detail 
    WHERE id_users = '$id_users' AND tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC");
}

// Debugging: Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Debugging: Periksa hasil query
// var_dump(mysqli_fetch_all($result, MYSQLI_ASSOC));

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$lokasi = mysqli_query($koneksi, "SELECT * FROM lokasi_presensi
WHERE nama_lokasi ='$lokasi_presensi'");

while ($lokasi_result = mysqli_fetch_array($lokasi)) {
    $jam_masuk_kantor = date('H:i:s', strtotime($lokasi_result['jam_masuk']));
}

function formatTotalWaktu($total_waktu) {
    list($jam, $menit, $detik) = explode(':', $total_waktu);
    return intval($jam) . ' jam ' . intval($menit) . ' menit';
}

?>

<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <br>
                <h2 class="page-title">Rekap Presensi</h2>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                   <span class="text"><i class="fa-solid fa-file-arrow-down"></i> Export PDF </span>
                </button>
                <a href="rekap_presensi.php" class="btn btn-warning mb-3">
                    <span class="text" ><i class="fa-solid fa-trash-can-arrow-up"></i> Reset
                    </span>
                </a>
            </div>
            <div class="col-md-6">
                <form method="GET" action="" class="d-flex">
                    <input type="date" class="form-control me-2" name="tanggal_dari" placeholder="Tanggal Dari">
                    <input type="date" class="form-control me-2" name="tanggal_sampai" placeholder="Tanggal Sampai">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>

        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Total Waktu</th>
                <th>Total Terlambat</th>
            </tr>

            <?php if (mysqli_num_rows($result) == 0) { ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data presensi</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($rekap = mysqli_fetch_array($result)) :
                    $jam_masuk = date('H:i:s', strtotime($rekap['jam_masuk']));
                    $timestamp_jam_masuk_real = strtotime($jam_masuk);
                    $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

                    $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
                    $total_jam_terlambat = floor(abs($terlambat) / 3600);
                    $terlambat -= $total_jam_terlambat * 3600;
                    $selisih_menit_terlambat = floor(abs($terlambat) / 60);
                    $status_terlambat = $terlambat < 0 ? '-' : '';
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                        <td class="text-center"><?= $rekap['jam_masuk'] ?></td>
                        <td class="text-center"><?= $rekap['jam_keluar'] ?></td>
                        <td class="text-center"><?= formatTotalWaktu($rekap['total_waktu']) ?></td>
                        <td class="text-center">
                            <?php if ($terlambat < 0) : ?>
                                <span class="badge bg-success">On Time</span>
                            <?php else : ?>
                                <?= $status_terlambat . $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Pdf Rekap Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url('mahasiswa/presensi/rekap_presensi_pdf.php')?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal_dari" > Tanggal Awal </label>
                        <input type="date" class="form-control" name="tanggal_dari" id="tanggal_dari">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_sampai" > Tanggal Akhir </label>
                        <input type="date" class="form-control" name="tanggal_sampai" id="tanggal_sampai">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php include ('../layout/footer.php'); ?>