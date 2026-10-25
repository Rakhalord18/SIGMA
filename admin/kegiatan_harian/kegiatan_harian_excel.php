<?php
require_once('../../config.php');
require '../../vendor/autoload.php'; // Pastikan path ini sesuai dengan lokasi autoload Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

// Buat Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set judul dan header kolom
$sheet->setCellValue('A1', 'Rekap Kegiatan Harian Magang');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Set header kolom
$sheet->setCellValue('A4', 'No');
$sheet->setCellValue('B4', 'ID Users');
$sheet->setCellValue('C4', 'Nama');
$sheet->setCellValue('D4', 'Hari');
$sheet->setCellValue('E4', 'Tanggal');
$sheet->setCellValue('F4', 'Deskripsi');
$sheet->setCellValue('G4', 'File');

// Isi data ke dalam spreadsheet
$rowNumber = 5;
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $no++);
    $sheet->setCellValue('B' . $rowNumber, $row['id_users']);
    $sheet->setCellValue('C' . $rowNumber, $row['nama']);
    $sheet->setCellValue('D' . $rowNumber, $row['hari']);
    $sheet->setCellValue('E' . $rowNumber, date('d F Y', strtotime($row['tanggal'])));
    $sheet->setCellValue('F' . $rowNumber, $row['deskripsi']);
    $sheet->setCellValue('G' . $rowNumber, $row['file'] ? 'Ada' : '-');
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

$sheet->getStyle("A4:G$lastRow")->applyFromArray($styleArray);

// Set auto width untuk kolom
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Buat file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'rekap_kegiatan_harian.xlsx';

// Kirim file ke browser untuk diunduh
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
