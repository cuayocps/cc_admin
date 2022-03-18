<?php

use Dompdf\Dompdf;

class Pdf
{

  public function download($nombre, $data, $subtitle)
  {
    $pdf = $this->makePdf($data, $subtitle);
    $pdf->stream($nombre);
  }

  public function getFile($nombre, $data, $subtitle)
  {
    $pdf = $this->makePdf($data, $subtitle);
    $output = $pdf->output();
    $tmp = TMP_DIR;
    $filename = "{$tmp}/{$nombre}.pdf";
    file_put_contents($filename, $output);
    return $filename;
  }

  protected function makePdf($data, $subtitle)
  {
    $content = '';
    while (true) {
      $departamento = array_shift($data);
      $content .= $this->templateToHtml('departamento', ['nombre' => $departamento['nombre']]);
      foreach ($departamento['usuarios'] as $usuario) {
        $content .= $this->templateToHtml('reporte_asistencia_usuario', $usuario);
      }
      if (empty($data)) {
        break;
      } else {
        $content .= '<div class="page_break"></div>';
      }
    }

    $html = $this->templateToHtml('html', compact('content', 'subtitle'));

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    return $dompdf;
  }

  protected function templateToHtml($template, $data)
  {
    $template = dirname(__DIR__) . "/vistas/templates/{$template}.php";
    ob_start();
    extract($data);
    include $template;
    return ob_get_clean();
  }

}
