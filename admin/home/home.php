<?php 

include ('../layout/header.php');
require_once('../../config.php');

// Validasi login dan role
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit;
} elseif ($_SESSION['role'] != 'admin' && $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../../auth/login.php?pesan=Akun_belum_aktif');
    exit;
}

// Ambil bulan sekarang atau bulan sebelumnya berdasarkan parameter URL
$bulan_ini = date('Y-m'); // Bulan sekarang
$bulan_sebelumnya = date('Y-m', strtotime("-1 month")); // Bulan sebelumnya

// Tentukan mana yang akan ditampilkan (bulan sebelumnya atau bulan ini)
$bulan_terpilih = isset($_GET['bulan']) && $_GET['bulan'] == 'sebelumnya' ? $bulan_sebelumnya : $bulan_ini;

// Query untuk menghitung total mahasiswa/siswa aktif
$query_mahasiswa = "SELECT * FROM mahasiswa_detail 
                    JOIN users ON mahasiswa_detail.id_users = users.id 
                    WHERE users.status = 'Aktif'";
$result_mahasiswa = mysqli_query($koneksi, $query_mahasiswa);
$total_mahasiswa_aktif = mysqli_num_rows($result_mahasiswa);

// Total semua data mahasiswa
$query_total_semua = "SELECT COUNT(*) as total_semua FROM mahasiswa_detail";
$result_total_semua = mysqli_query($koneksi, $query_total_semua);
$row_total_semua = mysqli_fetch_assoc($result_total_semua);
$total_semua_mahasiswa = $row_total_semua['total_semua'];

// Query gabungan status mahasiswa sesuai kondisi 6 status (Disetujui, Pending, Ditolak, tanggal selesai)
$sql_status = "
SELECT
    COUNT(*) as total_mahasiswa,
    SUM(CASE 
        WHEN la.status = 'Disetujui' AND md.tanggal_selesai < CURDATE() THEN 1 ELSE 0 END) AS total_lulus,
    SUM(CASE 
        WHEN la.status = 'Disetujui' AND md.tanggal_selesai >= CURDATE() THEN 1 ELSE 0 END) AS total_magang,
    SUM(CASE 
        WHEN la.status IS NULL AND md.tanggal_selesai >= CURDATE() THEN 1 ELSE 0 END) AS total_belum_upload,
    SUM(CASE 
        WHEN la.status = 'Pending'  AND md.tanggal_selesai >= CURDATE() THEN 1 ELSE 0 END) AS total_pending_belum_selesai,
    SUM(CASE 
        WHEN la.status = 'Ditolak' AND md.tanggal_selesai >= CURDATE() THEN 1 ELSE 0 END) AS total_magang_ditolak
FROM mahasiswa_detail md
LEFT JOIN laporan_akhir la ON md.id_users = la.id_users
";

$result_status = mysqli_query($koneksi, $sql_status);
$status_counts = mysqli_fetch_assoc($result_status);

$total_lulus = $status_counts['total_lulus'] ?? 0;
$total_magang = ($status_counts['total_magang'] ?? 0) + ($status_counts['total_magang_ditolak'] ?? 0) + ($status_counts['total_belum_upload'] ?? 0);
$total_pending = $status_counts['total_pending_belum_selesai'] ?? 0;

// Data Bulanan Presensi (Masuk & Alpha per hari)
$days = [];
$masuk = [];
$alpha = [];

for ($i = 1; $i <= date('t', strtotime($bulan_terpilih)); $i++) {
    $tgl = sprintf('%s-%02d', $bulan_terpilih, $i);
    $days[] = date('d-m-Y', strtotime($tgl));

    $q_masuk = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_users) as jml FROM presensi_detail WHERE tanggal_masuk = '$tgl'");
    $r_masuk = mysqli_fetch_assoc($q_masuk);
    $masuk[] = (int)$r_masuk['jml'];

    $q_alpha = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM mahasiswa_detail WHERE id_users NOT IN (SELECT DISTINCT id_users FROM presensi_detail WHERE tanggal_masuk = '$tgl')");
    $r_alpha = mysqli_fetch_assoc($q_alpha);
    $alpha[] = (int)$r_alpha['jml'];
}

// Data Bulanan Kegiatan Harian (isi & tidak isi per hari)
$kegiatan_isi = [];
$kegiatan_tidak_isi = [];

for ($i = 1; $i <= date('t', strtotime($bulan_terpilih)); $i++) {
    $tgl = sprintf('%s-%02d', $bulan_terpilih, $i);

    $q_isi = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_users) as jml FROM kegiatan_harian WHERE tanggal = '$tgl'");
    $r_isi = mysqli_fetch_assoc($q_isi);
    $isi = (int)$r_isi['jml'];
    $kegiatan_isi[] = $isi;
    $kegiatan_tidak_isi[] = $total_mahasiswa_aktif - $isi;
}

?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Home</h2><br>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <!-- Total Mahasiswa/Siswa -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar text-white" style="background-color: #3C6DB2;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-user">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">Total Mahasiswa/Siswa</div>
                                            <div class="text-secondary"><?= $total_semua_mahasiswa ?> Aktif</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Lulus -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar text-white" style="background-color: #BA313B;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-off">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M8.18 8.189a4.01 4.01 0 0 0 2.616 2.627m3.507 -.545a4 4 0 1 0 -5.59 -5.552" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.412 0 .81 .062 1.183 .178m2.633 2.618c.12 .38 .184 .785 .184 1.204v2" />
                                                    <path d="M3 3l18 18" /></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">Status Lulus</div>
                                            <div class="text-secondary"><?= $total_lulus ?> Mahasiswa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pending -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-yellow text-white avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-user-question">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                                    <path d="M19 22v.01" />
                                                    <path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">Status Pending</div>
                                            <div class="text-secondary"><?= $total_pending ?> Mahasiswa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Magang -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-green text-white avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-user-check">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                    <path d="M15 19l2 2l4 -4" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">Status Magang</div>
                                            <div class="text-secondary"><?= $total_magang ?> Mahasiswa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <!-- Button untuk melihat bulan sebelumnya -->
                        <a href="?bulan=sebelumnya" class="btn btn-primary">Lihat Bulan Sebelumnya</a>
                        <!-- Button untuk melihat bulan sekarang -->
                        <a href="?bulan=sekarang" class="btn btn-success">Lihat Bulan Sekarang</a>
                    </div>
                </div>          
            </div><br><br>

            <!-- Grafik Presensi dan Kegiatan Harian -->
            <div class="row row-deck row-cards">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Data Bulanan Presensi</h3>
                            <h3 style="text-align: center;"><?= date('F Y', strtotime($bulan_terpilih)) ?></h3>
                            <div id="chart-presensi" class="chart-lg"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Data Bulanan Yang Sudah Mengisi Kegiatan Harian</h3>
                        <h3 style="text-align: center;"><?= date('F Y', strtotime($bulan_terpilih)) ?></h3>
                        <div id="chart-kegiatan" class="chart-lg"></div>
                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Data dari PHP
    const labels = <?= json_encode($days) ?>;
    const dataMasuk = <?= json_encode($masuk) ?>;
    const dataAlpha = <?= json_encode($alpha) ?>;
    const dataKegiatanIsi = <?= json_encode($kegiatan_isi) ?>;
    const dataKegiatanTidakIsi = <?= json_encode($kegiatan_tidak_isi) ?>;

    // Grafik Presensi (Bar)
    new ApexCharts(document.getElementById('chart-presensi'), {
        chart: {
            type: "bar",
            fontFamily: 'inherit',
            height: 240,
            parentHeightOffset: 0,
            toolbar: { show: false },
            animations: { enabled: false },
            stacked: true,
        },
        plotOptions: { bar: { columnWidth: '50%' } },
        dataLabels: { enabled: false },
        fill: { opacity: 1 },
        series: [
            { name: "Masuk", data: dataMasuk },
            { name: "Alpha", data: dataAlpha }
        ],
        tooltip: { theme: 'dark' },
        grid: {
            padding: { top: -20, right: 0, left: -4, bottom: -4 },
            strokeDashArray: 4,
            xaxis: { lines: { show: true } },
        },
        xaxis: {
            categories: labels,
            labels: {
                rotate: -45,
                style: { fontSize: '10px' },
                show: false
            },
            tooltip: { enabled: false },
            axisBorder: { show: false },
            type: 'category',
        },
        yaxis: {
            min: 0,
            labels: { padding: 4 }
        },
        colors: ['#206bc4', 'rgba(214, 57, 57, 0.3)'], // presensi: biru dan merah transparan
        legend: { show: true },
    }).render();

    // Grafik Kegiatan Harian (Bar)
    new ApexCharts(document.getElementById('chart-kegiatan'), {
        chart: {
            type: "bar",
            fontFamily: 'inherit',
            height: 240,
            parentHeightOffset: 0,
            toolbar: { show: false },
            animations: { enabled: false },
            stacked: true,
        },
        plotOptions: { bar: { columnWidth: '50%' } },
        dataLabels: { enabled: false },
        fill: { opacity: 1 },
        series: [
            { name: "Mengisi Kegiatan", data: dataKegiatanIsi },
            { name: "Tidak Mengisi", data: dataKegiatanTidakIsi }
        ],
        tooltip: { theme: 'dark' },
        grid: {
            padding: { top: -20, right: 0, left: -4, bottom: -4 },
            strokeDashArray: 4,
            xaxis: { lines: { show: true } },
        },
        xaxis: {
            categories: labels,
            labels: {
                rotate: -45,
                style: { fontSize: '10px' },
                show: false
            },
            tooltip: { enabled: false },
            axisBorder: { show: false },
            type: 'category',
        },
        yaxis: {
            min: 0,
            labels: { padding: 4 }
        },
        colors: ['#2fb344', 'rgba(214, 57, 57, 0.3)'], // kegiatan: hijau dan merah transparan
        legend: { show: true },
    }).render();
});
</script>

<?php include ('../layout/footer.php') ?>
