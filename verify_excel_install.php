<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// creating a simple spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');
$sheet->setCellValue('B1', 'Test Excel');

$writer = new Xlsx($spreadsheet);
$writer->save('hello_world.xlsx');

echo "Created hello_world.xlsx successfully.\n";

// Reading it back
$spreadsheet2 = IOFactory::load('hello_world.xlsx');
$data = $spreadsheet2->getActiveSheet()->toArray();

echo "Read back data:\n";
print_r($data);

unlink('hello_world.xlsx');
