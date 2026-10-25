<?php
require_once('../../config.php');
require_once('../../vendor/autoload.php');

// Ambil filter pencarian dari URL
$searchName = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';

// Query untuk mengambil data kegiatan harian dengan filter pencarian nama
$query = "SELECT kh.*, md.nama 
          FROM kegiatan_harian kh
          JOIN mahasiswa_detail md ON md.id_users = kh.id_users
          WHERE 1=1";

// Menambahkan filter pencarian jika ada
if (!empty($searchName)) {
    $search_esc = mysqli_real_escape_string($koneksi, $searchName);
    $query .= " AND (
        kh.id_users LIKE '%$search_esc%' OR
        md.nama LIKE '%$search_esc%' OR
        kh.hari LIKE '%$search_esc%' OR
        kh.tanggal LIKE '%$search_esc%' OR
        kh.deskripsi LIKE '%$search_esc%'
    )";
}

$query .= " ORDER BY kh.tanggal DESC";
$result = mysqli_query($koneksi, $query);

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
$html .= '<h2>Rekap Kegiatan Harian Magang</h2>';
$html .= '<h4></h4>';
$html .= '<table>
<thead>
    <tr>
        <th>No</th>
        <th>ID Users</th>
        <th>Nama</th>
        <th>Hari</th>
        <th>Tanggal</th>
        <th>Deskripsi</th>
        <th>File</th>
    </tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Isi data ke baris
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . $row['id_users'] . '</td>
        <td>' . $row['nama'] . '</td>
        <td>' . $row['hari'] . '</td>
        <td>' . date('d F Y', strtotime($row['tanggal'])) . '</td>
        <td>' . $row['deskripsi'] . '</td>
        <td>' . ($row['file'] ? 'Ada' : '-') . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Output PDF
$mpdf->WriteHTML($html);
$mpdf->Output('rekap_kegiatan_harian.pdf', 'D'); // 'D' untuk mendownload
?>
