<?php 
require_once('../../config.php');
require_once('../../vendor/autoload.php');
if (!class_exists('\Mpdf\Mpdf')) {
    die('MPDF library is not installed. Please run "composer require mpdf/mpdf".');
}

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: ../../auth/login.php?pesan=belum_login');
    exit();
}

$id_users = $_SESSION['id'];
$lokasi_presensi = $_SESSION['lokasi_presensi'];

// Ambil jam masuk kantor
$lokasi = mysqli_query($koneksi, "SELECT * FROM lokasi_presensi WHERE nama_lokasi ='$lokasi_presensi'");
$jam_masuk_kantor = '08:00:00'; // default
if ($lokasi_result = mysqli_fetch_array($lokasi)) {
    $jam_masuk_kantor = date('H:i:s', strtotime($lokasi_result['jam_masuk']));
}

// Ambil filter tanggal dari POST (dari modal)
$tanggal_dari = isset($_POST['tanggal_dari']) ? $_POST['tanggal_dari'] : '';
$tanggal_sampai = isset($_POST['tanggal_sampai']) ? $_POST['tanggal_sampai'] : '';

if (!empty($tanggal_dari) && !empty($tanggal_sampai)) {
    $result = mysqli_query($koneksi, "SELECT * FROM presensi_detail 
        WHERE id_users = '$id_users' AND tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC");
    $periode = date('d F Y', strtotime($tanggal_dari)) . " - " . date('d F Y', strtotime($tanggal_sampai));
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM presensi_detail 
        WHERE id_users = '$id_users' ORDER BY tanggal_masuk DESC");
    $periode = "Semua Data";
}

$query_mahasiswa = mysqli_query($koneksi, "
    SELECT m.nama, m.nis_nim, m.jurusan, m.asal_instansi, u.role 
    FROM mahasiswa_detail m 
    JOIN users u ON m.id_users = u.id 
    WHERE m.id_users = '$id_users' LIMIT 1
");

$mahasiswa = mysqli_fetch_assoc($query_mahasiswa);

function formatTotalWaktu($total_waktu) {
    list($jam, $menit, $detik) = explode(':', $total_waktu);
    return intval($jam) . ' jam ' . intval($menit) . ' menit';
}

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/tmp'
]);

$style = '
<style>
    h2 { text-align: center; font-family: Arial, Helvetica, sans-serif; }
    .periode { text-align: center; font-size: 13px; margin-bottom: 10px; }
    table { border-collapse: collapse; width: 100%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
    thead tr { background-color: #4CAF50; color: white; }
    th, td { border: 1px solid #888; padding: 6px 8px; text-align: center; }
    tbody tr:nth-child(even) { background-color: #f2f2f2; }
    tbody tr:nth-child(odd) { background-color: #fff; }
</style>
';

$html = $style;
$html .= '
<div style="width: 100%; margin-bottom: 10px;">
    <div style="float: left; width: 50%;">
        <img src="../../assets/img/skkmigas1.png" style="width:100px;">
    </div>
    <div style="float: right; width: 50%; text-align: right;">
        <img src="../../assets/img/pertamina_hulu_rokan.png" style="width:100px;">
    </div>
</div>
<div style="clear: both;"></div>

<table style="width:100%; font-size:12px; border-collapse:collapse; margin-top:20px; margin-bottom:30px;">
    <tr style="border: none;">
        <td style="width:10%; border: none; padding: 5px 0; text-align: left;"><strong>Nama</strong></td>
        <td style="width:2%; border: none; text-align: center;">:</td>
        <td style="width:28%; border: none; text-align: left; border-bottom: 1px solid #000;">' . htmlspecialchars($mahasiswa['nama']) . '</td>

        <td style="width:10%; border: none; padding: 5px 0; text-align: left;"><strong>Pekerjaan</strong></td>
        <td style="width:2%; border: none; text-align: center;">:</td>
        <td style="width:28%; border: none; text-align: left; border-bottom: 1px solid #000;">' . htmlspecialchars($mahasiswa['role']) . '</td>
    </tr>
    <tr style="border: none;">
        <td style="border: none; padding: 5px 0; text-align: left;"><strong>NIM/NIS</strong></td>
        <td style="border: none; text-align: center;">:</td>
        <td style="border: none; text-align: left; border-bottom: 1px solid #000;">' . htmlspecialchars($mahasiswa['nis_nim']) . '</td>

        <td style="border: none; padding: 5px 0; text-align: left;"><strong>Lokasi</strong></td>
        <td style="border: none; text-align: center;">:</td>
        <td style="border: none; text-align: left; border-bottom: 1px solid #000;">' . htmlspecialchars($lokasi_presensi) . '</td>
    </tr>
    <tr style="border: none;">
        <td style="border: none; padding: 5px 0; text-align: left;"><strong>Jurusan</strong></td>
        <td style="border: none; text-align: center;">:</td>
        <td style="border: none; text-align: left; border-bottom: 1px solid #000;">' . htmlspecialchars($mahasiswa['jurusan']) . '</td>

        <td style="border: none; padding: 5px 0; text-align: left;"><strong>Asal Instansi</strong></td>
        <td style="border: none; text-align: center;">:</td>
        <td style="border: none; text-align: left; border-bottom: 1px solid #000;">' . htmlspecialchars($mahasiswa['asal_instansi']) . '</td>
    </tr>
</table>
';




$html .= '<h2>Rekap Presensi</h2>';
$html .= '<div class="periode">Periode: ' . $periode . '</div>';
$html .= '<table>
<thead>
    <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Jam Keluar</th>
        <th>Total Waktu</th>
        <th>Total Terlambat</th>
    </tr>
</thead>
<tbody>';

$no = 1;
while ($rekap = mysqli_fetch_array($result)) {
    $jam_masuk = date('H:i:s', strtotime($rekap['jam_masuk']));
    $timestamp_jam_masuk_real = strtotime($jam_masuk);
    $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

    $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;
    $total_jam_terlambat = floor(abs($terlambat) / 3600);
    $terlambat_sisa = abs($terlambat) - ($total_jam_terlambat * 3600);
    $selisih_menit_terlambat = floor($terlambat_sisa / 60);
    $status_terlambat = $terlambat < 0 ? 'On Time' : $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit';

    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . date('d F Y', strtotime($rekap['tanggal_masuk'])) . '</td>
        <td>' . htmlspecialchars($rekap['jam_masuk']) . '</td>
        <td>' . htmlspecialchars($rekap['jam_keluar']) . '</td>
        <td>' . formatTotalWaktu($rekap['total_waktu']) . '</td>
        <td>' . ($terlambat < 0 ? '<span style="color:green;font-weight:bold;">On Time</span>' : $status_terlambat) . '</td>
    </tr>';
}
$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output('rekap_presensi_mahasiswa.pdf', 'D');
?>