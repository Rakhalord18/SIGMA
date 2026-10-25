<?php

include('../layout/header.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

// Redirect berdasarkan role
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=akses_ditolak');
    exit();
}

include_once('../../config.php');

// Periksa apakah filter tanggal digunakan
if (empty($_GET['tanggal_dari']) || empty($_GET['tanggal_sampai'])) {
    $tanggal_hari_ini = (date('Y-m-d'));
    // Jika filter tanggal kosong, ambil semua data
    $result = mysqli_query($koneksi, "SELECT pd.*, md.nama, lp.nama_lokasi, lp.jam_masuk AS jam_masuk_kantor 
    FROM presensi_detail pd 
    LEFT JOIN lokasi_presensi lp ON pd.nama_lokasi = lp.nama_lokasi 
    LEFT JOIN mahasiswa_detail md ON pd.id_users = md.id_users 
    WHERE pd.tanggal_masuk = '$tanggal_hari_ini' ORDER BY pd.tanggal_masuk DESC");
} else {
    // Jika filter tanggal digunakan, ambil data berdasarkan rentang tanggal
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $result = mysqli_query($koneksi, "SELECT pd.*, md.nama, lp.nama_lokasi, lp.jam_masuk AS jam_masuk_kantor 
    FROM presensi_detail pd 
    LEFT JOIN lokasi_presensi lp ON pd.nama_lokasi = lp.nama_lokasi 
    LEFT JOIN mahasiswa_detail md ON pd.id_users = md.id_users 
    WHERE pd.tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
    ORDER BY pd.tanggal_masuk DESC");
}

if (empty($_GET['tanggal_dari']) || empty($_GET['tanggal_sampai'])) {
    $tanggal_filter = date('Y-m-d');
} else {
    $tanggal_filter = $_GET['tanggal_dari'] . '-' . $_GET['tanggal_sampai'];
}

// Debugging: Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
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
            <div class="col"><br>
                <h2 class="page-title">Rekap Presensi Harian</h2>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#pdfModalharian">
                    <span class="text"><i class="fa-solid fa-file-pdf"></i> Export PDF </span>
                </button>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#excelModalharian">
                    <span class="text"><i class="fas fa-file-excel"></i> Export Excel </span>
                </button>
                <a href="rekap_harian.php" class="btn btn-warning mb-3">
                    <i class="fa-solid fa-trash-can-arrow-up"></i> Reset
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
        <?php  if (empty($_GET['tanggal_dari'])): { ?>
            <span >Rekap Presensi Tanggal: <?= date(' d F Y')?> </span>
            <?php } else: { ?>
            <span >Rekap Presensi Tanggal: <?= date(' d F Y', strtotime($_GET['tanggal_dari'])) . '
             sampai ' . date('d F Y', strtotime($_GET['tanggal_sampai']))?> </span>
            <?php } endif; ?>
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>ID Users</th>
                <th>Nama </th>
                <th>Nama Lokasi</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Total Waktu</th>
                <th>Total Terlambat</th>
            </tr>

            <?php if (mysqli_num_rows($result) == 0) { ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data presensi</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($rekap = mysqli_fetch_array($result)) :
                    $jam_masuk = date('H:i:s', strtotime($rekap['jam_masuk']));
                    $jam_masuk_kantor = date('H:i:s', strtotime($rekap['jam_masuk_kantor']));
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
                        <td><?= $rekap['id_users'] ?></td>
                        <td><?= $rekap['nama'] ?></td>
                        <td><?= $rekap['nama_lokasi'] ?></td>
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

<!-- Modal Export PDF -->
<div class="modal" id="pdfModalharian" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export PDF Rekap Presensi Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="rekap_harian_pdf.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal_dari">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari" id="tanggal_dari" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_sampai">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai" id="tanggal_sampai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal untuk Export Excel -->
<div class="modal" id="excelModalharian" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Rekap Presensi Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/presensi/rekap_harian_excel.php')?>">
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

<?php include('../layout/footer.php'); ?>