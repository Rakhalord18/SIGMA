<?php
require_once('../../config.php');
require_once('../../vendor/autoload.php'); // Pastikan library MPDF sudah diinstal dengan Composer

// Ambil filter bulan dan tahun dari form
$filter_bulan = isset($_POST['filter_bulan']) ? $_POST['filter_bulan'] : '';
$filter_tahun = isset($_POST['filter_tahun']) ? $_POST['filter_tahun'] : '';

// Validasi input bulan dan tahun
if (empty($filter_bulan) || empty($filter_tahun)) {
    die("Bulan dan tahun filter tidak valid.");
}

// Gabungkan bulan dan tahun untuk filter
$filter_tahun_bulan = $filter_tahun . '-' . $filter_bulan;

// Query untuk mengambil data presensi berdasarkan filter bulan dan tahun
$query = "SELECT pd.*, md.nama, lp.nama_lokasi, lp.jam_masuk AS jam_masuk_kantor 
          FROM presensi_detail pd
          LEFT JOIN lokasi_presensi lp ON pd.nama_lokasi = lp.nama_lokasi
          LEFT JOIN mahasiswa_detail md ON pd.id_users = md.id_users
          WHERE DATE_FORMAT(pd.tanggal_masuk, '%Y-%m') = '$filter_tahun_bulan'
          ORDER BY pd.tanggal_masuk DESC";

$result = mysqli_query($koneksi, $query);
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Buat objek MPDF
$mpdf = new \Mpdf\Mpdf([ 
    'tempDir' => __DIR__ . '/tmp' // Direktori sementara di folder 'admin/mahasiswa/tmp'
]);

// CSS untuk mempercantik tabel
$style = '
<style>
    h2 { text-align: center; font-family: Arial, Helvetica, sans-serif; }
    table { border-collapse: collapse; inline-size: 100%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
    thead tr { background-color: #4CAF50; color: white; }
    th, td { border: 1px solid #888; padding: 6px 8px; text-align: center; }
    tbody tr:nth-child(even) { background-color: #f2f2f2; }
    tbody tr:nth-child(odd) { background-color: #fff; }
</style>
';

$html = $style;
$html .= '<h2>Rekap Presensi Bulanan</h2>';
$html .= '<h4>Periode: ' . date('d F Y', strtotime($filter_tahun . '-' . $filter_bulan . '-01')) . ' - ' . date('d F Y', strtotime($filter_tahun . '-' . $filter_bulan . '-01')) . '</h4>';
$html .= '<table>
<thead>
    <tr>
        <th>No</th>
        <th>ID Users</th>
        <th>Nama</th>
        <th>Nama Lokasi</th>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Jam Keluar</th>
        <th>Total Waktu</th>
        <th>Total Terlambat</th>
    </tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Hitung total waktu
    $jam_masuk = strtotime($row['jam_masuk']);
    $jam_keluar = strtotime($row['jam_keluar']);
    $total_detik = $jam_keluar - $jam_masuk;

    $total_jam = floor($total_detik / 3600);
    $total_detik -= $total_jam * 3600;
    $total_menit = floor($total_detik / 60);
    $total_waktu = $total_jam . ' Jam ' . $total_menit . ' Menit';

    // Hitung total terlambat
    $jam_masuk_kantor = strtotime($row['jam_masuk_kantor']);
    $terlambat = $jam_masuk - $jam_masuk_kantor;

    $total_jam_terlambat = floor(abs($terlambat) / 3600);
    $terlambat -= $total_jam_terlambat * 3600;
    $selisih_menit_terlambat = floor(abs($terlambat) / 60);
    $status_terlambat = $terlambat < 0 ? 'On Time' : $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit';

    // Isi data ke baris
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . $row['id_users'] . '</td>
        <td>' . $row['nama'] . '</td>
        <td>' . (!empty($row['nama_lokasi']) ? $row['nama_lokasi'] : 'Lokasi Tidak Diketahui') . '</td>
        <td>' . date('d F Y', strtotime($row['tanggal_masuk'])) . '</td>
        <td>' . $row['jam_masuk'] . '</td>
        <td>' . $row['jam_keluar'] . '</td>
        <td>' . $total_waktu . '</td>
        <td>' . $status_terlambat . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Output PDF
$mpdf->WriteHTML($html);
$mpdf->Output('rekap_presensi_bulanan.pdf', 'D'); // 'D' untuk mendownload
?>
