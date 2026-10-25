<?php
require_once('../../config.php');
require_once('../../vendor/autoload.php'); // Pastikan MPDF sudah terinstal dengan Composer

$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    die('ID tidak ditemukan');
}

// Ambil data mahasiswa berdasarkan ID
$query = "SELECT m.*, u.role, u.email, u.status, m.foto
          FROM mahasiswa_detail m
          JOIN users u ON m.id_users = u.id
          WHERE m.id_users = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) { die('Data tidak ditemukan'); }

$html = '
<h2 style="text-align:center;">Data Pribadi Mahasiswa/Siswa</h2>
<table border="1" cellpadding="7" cellspacing="0" width="100%">
    <tr><th>Foto Profil</th><td><img src="../../uploads/foto_mahasiswa/' . $data['foto'] . '" width="100" height="100"></td></tr>
    <tr><th width="40%">Nama</th><td>'.$data['nama'].'</td></tr>
    <tr><th>NIS/NIM</th><td>'.$data['nis_nim'].'</td></tr>
    <tr><th>Jenis Kelamin</th><td>'.$data['jenis_kelamin'].'</td></tr>
    <tr><th>Jurusan</th><td>'.$data['jurusan'].'</td></tr>
    <tr><th>Role</th><td>'.$data['role'].'</td></tr>
    <tr><th>Asal Instansi</th><td>'.$data['asal_instansi'].'</td></tr>
    <tr><th>Alamat</th><td>'.$data['alamat'].'</td></tr>
    <tr><th>No Telepon</th><td>'.$data['notelpon'].'</td></tr>
    <tr><th>Email</th><td>'.$data['email'].'</td></tr>
    <tr><th>Tanggal Mulai</th><td>'.$data['tanggal_mulai'].'</td></tr>
    <tr><th>Tanggal Selesai</th><td>'.$data['tanggal_selesai'].'</td></tr>
    <tr><th>Status</th><td>'.$data['status'].'</td></tr>
    <tr><th>Lokasi Presensi</th><td>'.$data['lokasi_presensi'].'</td></tr>';

if (!empty($data['foto']) && file_exists('../../uploads/foto_mahasiswa/' . $data['foto'])) {
    $html .= '';
} else {
    $html .= '<tr><th>Foto Profil</th><td><span class="text-muted">Belum ada foto</span></td></tr>';
}

$html .= '</table>';

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 12,
    'margin_bottom' => 12,
]);

// Menulis HTML ke PDF
$mpdf->WriteHTML($html);

// Nama file otomatis berdasarkan nama mahasiswa
$nama_file = 'Data_Pribadi_' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $data['nama']) . '.pdf';

// Output otomatis download
$mpdf->Output($nama_file, \Mpdf\Output\Destination::DOWNLOAD);
exit;
?>
