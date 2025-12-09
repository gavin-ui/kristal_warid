<?php
// HAPUS semua output sebelum header
ob_start();
require "../vendor/autoload.php";
include "../koneksi.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 0);

// Ambil data
$data = mysqli_query($conn, "SELECT * FROM produksi_mesin");

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = ["ID","Mesin","Jam Mulai","Durasi (Menit)","Defroz","Pack","Qty","Kristal","Serut","Keterangan"];

// Set Header Excel Table
$col = "A";
foreach ($headers as $h) {
    $sheet->setCellValue($col . "1", $h);
    $col++;
}

// Isi data
$row = 2;
while ($d = mysqli_fetch_assoc($data)) {
    $sheet->setCellValue("A$row", $d['id']);
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
foreach(range('A','J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$filename = "Produksi_Mesin_" . date("Y-m-d") . ".xlsx";

// Bersihkan output buffer sebelum kirim file
ob_end_clean();

// Header yang benar untuk XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
