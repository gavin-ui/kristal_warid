<?php
// ===============================
// EXPORT EXCEL PRODUKSI MESIN
// ===============================

// JANGAN ADA SPASI / OUTPUT DI ATAS INI
ob_start();

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../koneksi.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM produksi_mesin ORDER BY id_produksi ASC");

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$headers = [
    "ID",
    "Mesin",
    "Jam Mulai",
    "Durasi (Menit)",
    "Defrost",
    "Pack",
    "Qty",
    "Kristal (Kg)",
    "Serut (Kg)",
    "Keterangan"
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Isi data
$row = 2;
while ($d = mysqli_fetch_assoc($query)) {
    $sheet->setCellValue("A$row", $d['id_produksi']);
    $sheet->setCellValue("B$row", $d['mesin']);
    $sheet->setCellValue("C$row", $d['jam_mulai']);
    $sheet->setCellValue("D$row", $d['menit']);
    $sheet->setCellValue("E$row", $d['defroz']);
    $sheet->setCellValue("F$row", $d['pack']);
    $sheet->setCellValue("G$row", $d['qty']);
    $sheet->setCellValue("H$row", $d['kristal']);
    $sheet->setCellValue("I$row", $d['serut']);
    $sheet->setCellValue("J$row", $d['keterangan']);
    $row++;
}

// Auto width kolom
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Nama file
$filename = "Produksi_Mesin_" . date("Y-m-d_H-i-s") . ".xlsx";

// Bersihkan buffer TOTAL
while (ob_get_level()) {
    ob_end_clean();
}

// Header download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Pragma: public');

// Output file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
