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
if (empty($_GET['filter_bulan']) || empty($_GET['filter_tahun'])) {
    $bulan_sekarang = date('Y-m');
    // Jika filter tanggal kosong, ambil semua data
    $result = mysqli_query($koneksi, "SELECT pd.*, md.nama, lp.nama_lokasi, lp.jam_masuk AS jam_masuk_kantor 
    FROM presensi_detail pd 
    LEFT JOIN lokasi_presensi lp ON pd.nama_lokasi = lp.nama_lokasi 
    LEFT JOIN mahasiswa_detail md ON pd.id_users = md.id_users 
    WHERE DATE_FORMAT(pd.tanggal_masuk, '%Y-%m') = '$bulan_sekarang' ORDER BY pd.tanggal_masuk DESC");
} else {
     // Jika filter tanggal digunakan, ambil data berdasarkan filter bulan dan tahun
    $filter_tahun_bulan = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
    $result = mysqli_query($koneksi, "SELECT pd.*, md.nama, lp.nama_lokasi, lp.jam_masuk AS jam_masuk_kantor 
    FROM presensi_detail pd 
    LEFT JOIN lokasi_presensi lp ON pd.nama_lokasi = lp.nama_lokasi 
    LEFT JOIN mahasiswa_detail md ON pd.id_users = md.id_users 
    WHERE DATE_FORMAT(pd.tanggal_masuk, '%Y-%m') = '$filter_tahun_bulan' 
    ORDER BY pd.tanggal_masuk DESC");
}
if (empty($_GET['filter_bulan']) || empty($_GET['filter_tahun'])) {
    $bulan_filter = date('Y-m');
} else {
   $bulan_filter = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
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
                <h2 class="page-title">Rekap Presensi Bulanan</h2>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#pdfModalbulan">
                    <span class="text"><i class="fa-solid fa-file-pdf"></i> Export PDF </span>
                </button>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#excelModalbulan">
                    <span class="text"><i class="fas fa-file-excel"></i> Export Excel </span>
                </button>
                <a href="rekap_bulanan.php" class="btn btn-warning mb-3">
                    <i class="fa-solid fa-trash-can-arrow-up"></i> Reset
                </a>
            </div>
            <div class="col-md-6">
                <form method="GET" action="" class="d-flex">
                    <select name="filter_bulan" class=" form-control ">
                        <option value="">-- Pilih Bulan --</option>
                        <?php
                        $bulan = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        foreach ($bulan as $key => $value) {
                            echo "<option value='$key'>$value</option>";
                        }
                        ?>
                    </select>
                    <select name="filter_tahun" class="form-control ms-2">
                        <option value="">-- Pilih Tahun --</option>
                        <?php
                        $tahun = date('Y');
                        for ($i = 2022; $i <= $tahun; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <span >Rekap Presensi Bulan: <?= date(' F Y', strtotime($bulan_filter))?> </span><br>
        <table class="table table-bordered mb-3">
            <tr class="text-center">
                <th>No.</th>
                <th>ID Users</th>
                <th>Nama</th>
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

<!-- Modal Export PDF Rekap Bulanan -->
<div class="modal" id="pdfModalbulan" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export PDF Rekap Presensi Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="rekap_bulanan_pdf.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter_bulan">Bulan</label>
                        <select name="filter_bulan" class="form-control">
                            <option value="">-- Pilih Bulan --</option>
                            <?php
                            $bulan = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];
                            foreach ($bulan as $key => $value) {
                                echo "<option value='$key'>$value</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter_tahun">Tahun</label>
                        <select name="filter_tahun" class="form-control">
                            <option value="">-- Pilih Tahun --</option>
                            <?php
                            $tahun = date('Y');
                            for ($i = 2022; $i <= $tahun; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
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

<!-- Modal Export Excel Rekap Bulanan -->
<div class="modal" id="excelModalbulan" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Rekap Presensi Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <form method="POST" action="../presensi/rekap_bulanan_excel.php"onsubmit="closeModal();">
                <div class="modal-body">
                    <div class="mb-3">
                       <select name="filter_bulan" class=" form-control">
                        <option value="">-- Pilih Bulan --</option>
                        <?php
                        $bulan = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        foreach ($bulan as $key => $value) {
                            echo "<option value='$key'>$value</option>";
                        }
                        ?>
                    </select>
                    </div>
                    <div class="mb-3">
                        <select name="filter_tahun" class="form-control">
                        <option value="">-- Pilih Tahun --</option>
                        <?php
                        $tahun = date('Y');
                        for ($i = 2022; $i <= $tahun; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
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

<?php include('../layout/footer.php'); ?>