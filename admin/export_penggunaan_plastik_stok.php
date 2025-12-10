<?php
require "../vendor/autoload.php";
include "../koneksi.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// kolom stok
$cols = [
  'total_barel',
  'stok_cs_kemarin',
  'repack_stok',
  'jumlah_total_stok_kemarin_retur_repack',
  'stok_cs_setelah_dikurangi_barel',
  'total_produksi_hari_ini_final'
];

// ambil data
$selectCols = implode(",", $cols);
$q = mysqli_query($conn, "SELECT $selectCols FROM penggunaan_plastik ORDER BY id_plastik DESC");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// header kolom (A,B,C,D,...)
$columnLetter = 'A';
foreach ($cols as $header) {
    $sheet->setCellValue($columnLetter . "1", strtoupper(str_replace("_", " ", $header)));
    $columnLetter++;
}

// isi data
$rowNumber = 2;
while ($row = mysqli_fetch_assoc($q)) {

    $columnLetter = 'A';

    foreach ($cols as $c) {
        $sheet->setCellValue($columnLetter . $rowNumber, $row[$c]);
        $columnLetter++;
    }

    $rowNumber++;
}

// filename
$filename = "stok_penggunaan_plastik_" . date('Ymd_His') . ".xlsx";

// output ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
