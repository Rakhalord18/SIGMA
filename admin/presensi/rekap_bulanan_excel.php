<?php
require_once('../../config.php');
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

// Buat Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set judul dan header kolom
$sheet->setCellValue('A1', 'Rekap Presensi Bulanan');
$sheet->mergeCells('A1:I1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'Periode: ' . date('F Y', strtotime($filter_tahun_bulan)));
$sheet->mergeCells('A2:I2');
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A4', 'No');
$sheet->setCellValue('B4', 'ID Users');
$sheet->setCellValue('C4', 'Nama');
$sheet->setCellValue('D4', 'Nama Lokasi');
$sheet->setCellValue('E4', 'Tanggal');
$sheet->setCellValue('F4', 'Jam Masuk');
$sheet->setCellValue('G4', 'Jam Keluar');
$sheet->setCellValue('H4', 'Total Waktu');
$sheet->setCellValue('I4', 'Total Terlambat');

// Isi data ke dalam spreadsheet
$rowNumber = 5;
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
    $sheet->setCellValue('A' . $rowNumber, $no++);
    $sheet->setCellValue('B' . $rowNumber, $row['id_users']);
    $sheet->setCellValue('C' . $rowNumber, $row['nama']);
    $sheet->setCellValue('D' . $rowNumber, !empty($row['nama_lokasi']) ? $row['nama_lokasi'] : 'Lokasi Tidak Diketahui');
    $sheet->setCellValue('E' . $rowNumber, date('d F Y', strtotime($row['tanggal_masuk'])));
    $sheet->setCellValue('F' . $rowNumber, $row['jam_masuk']);
    $sheet->setCellValue('G' . $rowNumber, $row['jam_keluar']);
    $sheet->setCellValue('H' . $rowNumber, $total_waktu);
    $sheet->setCellValue('I' . $rowNumber, $status_terlambat);

    $rowNumber++;
}

// Tambahkan border untuk header dan data
$lastRow = $rowNumber - 1;
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// Tambahkan warna dan tebal pada header kolom (A4:I4)
$sheet->getStyle('A4:I4')->getFont()->setBold(true);
$sheet->getStyle('A4:I4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFB6D7A8'); // Hijau muda, bisa diganti sesuai kebutuhan
$sheet->getStyle("A4:I$lastRow")->applyFromArray($styleArray);

// Set auto width untuk kolom
foreach (range('A', 'I') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Buat file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'rekap_presensi_bulanan.xlsx';

// Kirim file ke browser untuk diunduh
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
