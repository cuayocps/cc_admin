<?php

require_once dirname(dirname(__DIR__)) . '/modelos/Departamento.php';
require_once dirname(dirname(__DIR__)) . '/modelos/Tipousuario.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$cols = 'abcdefghijklmnopqrstuvwxyz';
$columns = [
    'Tipo usuario',
    'Departamento',
    'Nombre',
    'Apellidos',
    'Email',
    'Login',
    'Clave de ingreso',
    'Clave de asistencia'
];
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Nómina');
foreach($columns as $col => $title) {
    $sheet->setCellValue("{$cols[$col]}1", $title);
}

$sheet_2 = $spreadsheet->createSheet();
$sheet_2->setTitle('Tipos de Usuario');
$tipousuario = new Tipousuario();
$tipos = $tipousuario->select();
$col = 0;
while ($tipo = $tipos->fetch_assoc()) {
    ++$col;
    $sheet_2->setCellValue("a{$col}", "{$tipo['idtipousuario']}:{$tipo['nombre']}");
}

$sheet_3 = $spreadsheet->createSheet();
$sheet_3->setTitle('Departamentos');
$departamento = new Departamento();
$departamentos = $departamento->select();
$col = 0;
while ($dpto = $departamentos->fetch_assoc()) {
    ++$col;
    $sheet_3->setCellValue("a{$col}", "{$dpto['iddepartamento']}:{$dpto['nombre']}");
}

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="nomina_usuarios.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
