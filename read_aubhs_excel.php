<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = '/Users/apple/Downloads/Anglo Urdu Boys High School & Junior College.xlsx';
$spreadsheet = IOFactory::load($inputFileName);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

echo json_encode($sheetData, JSON_PRETTY_PRINT);
?>