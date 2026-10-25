<?php
require_once('../../config.php');
require_once('../../vendor/autoload.php'); // Pastikan library MPDF sudah diinstal dengan Composer
if (!class_exists('\Mpdf\Mpdf')) {
    die('MPDF library is not installed. Please run "composer require mpdf/mpdf".');
}

// Ambil filter dari URL
$role = isset($_GET['role']) ? $_GET['role'] : '';
$searchName = isset($_GET['search_name']) ? $_GET['search_name'] : '';

// Query untuk mengambil data berdasarkan filter
$query = "SELECT m.nama, m.nis_nim, m.jenis_kelamin, m.jurusan, u.role, m.asal_instansi, m.notelpon, u.email, m.tanggal_mulai, m.tanggal_selesai, u.status, m.lokasi_presensi
          FROM mahasiswa_detail m 
          JOIN users u ON m.id_users = u.id 
          WHERE 1=1";

if ($role) {
    $query .= " AND u.role = '$role'";
}
if ($searchName) {
    $query .= " AND (
        m.nama LIKE '%$searchName%' OR
        m.nis_nim LIKE '%$searchName%' OR
        m.jurusan LIKE '%$searchName%' OR
        m.jenis_kelamin LIKE '%$searchName%' OR
        u.role LIKE '%$searchName%' OR
        m.asal_instansi LIKE '%$searchName%' OR
        m.notelpon LIKE '%$searchName%' OR
        u.email LIKE '%$searchName%' OR
        m.tanggal_mulai LIKE '%$searchName%' OR
        m.tanggal_selesai LIKE '%$searchName%' OR
        u.status LIKE '%$searchName%' OR
        m.lokasi_presensi LIKE '%$searchName%'
    )";
}

$result = mysqli_query($koneksi, $query);

// Mulai output PDF
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
$html .= '<h2>Data Mahasiswa dan Siswa</h2>';
$html .= '<table>
<thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIM/NIS</th>
        <th>Jenis Kelamin</th>
        <th>Jurusan</th>
        <th>Role</th>
        <th>Asal Instansi</th>
        <th>No Telpon</th>
        <th>Email</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Status</th>
        <th>Lokasi Presensi</th>
    </tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($row['nama']) . '</td>
        <td>' . htmlspecialchars($row['nis_nim']) . '</td>
        <td>' . htmlspecialchars($row['jenis_kelamin']) . '</td>
        <td>' . htmlspecialchars($row['jurusan']) . '</td>
        <td>' . htmlspecialchars($row['role']) . '</td>
        <td>' . htmlspecialchars($row['asal_instansi']) . '</td>
        <td>' . htmlspecialchars($row['notelpon']) . '</td>
        <td>' . htmlspecialchars($row['email']) . '</td>
        <td>' . htmlspecialchars($row['tanggal_mulai']) . '</td>
        <td>' . htmlspecialchars($row['tanggal_selesai']) . '</td>
        <td>' . htmlspecialchars($row['status']) . '</td>
        <td>' . htmlspecialchars($row['lokasi_presensi']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Output PDF
$mpdf->WriteHTML($html);
$mpdf->Output('data_mahasiswa_siswa.pdf', 'D');
?>
