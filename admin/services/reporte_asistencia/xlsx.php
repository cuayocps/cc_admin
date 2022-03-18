<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Excel;
use Carbon\Carbon;

class Xlsx
{

  public function download($nombre, $data)
  {
    $file = $this->getFile($nombre, $data);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"{$nombre}.xlsx\"");
    header('Cache-Control: max-age=0');
    file_put_contents('php://output', file_get_contents($file));
    unlink($file);
  }

  public function getFile($nombre, $data)
  {
    $writer = $this->makeWriter($data);
    $tmp = TMP_DIR;
    $filename = "{$tmp}/{$nombre}.xlsx";
    $writer->save($filename);
    return $filename;
  }

  protected function makeWriter($data)
  {

    $spreadsheet = new Spreadsheet();
    $cols = 'abcdefghijklmnopqrstuvwxyz';
    $columns = [
      'Colaborador',
      'Fecha',
      'Día',
      'Entrada',
      'Salida',
      'Horas Normales',
      'Horas Extra'
    ];
    $x = 0;
    foreach ($data as $departamento) {
      if ($spreadsheet->getSheetCount() == 1 && $x++ == 0) {
        $sheet = $spreadsheet->getActiveSheet();
      } else {
        $sheet = $spreadsheet->createSheet();
      }
      $sheet->setTitle($departamento['nombre']);
      foreach ($columns as $col => $title) {
        $sheet->setCellValue("{$cols[$col]}1", $title);
      }
      foreach ($departamento['horas'] as $i => $hora) {
        $row = $i + 2;
        $sheet->setCellValue("{$cols[0]}{$row}", $hora['nombre_completo']);
        $sheet->setCellValue("{$cols[1]}{$row}", $hora['fecha']->format('d-m-Y'));
        $sheet->setCellValue("{$cols[2]}{$row}", Schedule::DAYS_OF_WEEK_NAMES[$hora['fecha']->dayOfWeek]);
        $sheet->setCellValue("{$cols[3]}{$row}", Carbon::parse($hora['hora_entrada'])->format('H:i'));
        $sheet->setCellValue("{$cols[4]}{$row}", Carbon::parse($hora['hora_salida'])->format('H:i'));
        $sheet->setCellValue("{$cols[5]}{$row}", round($hora['total'], 2));
        $sheet->setCellValue("{$cols[6]}{$row}", round($hora['extra'], 2));
      }
    }
    return new Excel($spreadsheet);
  }
}
