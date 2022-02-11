<?php

require_once '../modelos/Asistencia.php';
require_once '../modelos/Usuario.php';

use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;

if (strlen(session_id()) < 1) {
  session_start();
}

class ReporteController
{

  public function __construct()
  {
    $this->asistencia = new Asistencia();
    $this->asistenciaResumen = new AsistenciaResumen();
    $this->usuario = new Usuario();
  }

  public function descargar()
  {
    $correo = limpiarCadena($_POST['correo']);
    $codigoPersona = limpiarCadena($_POST['codigo_persona']);
    $ano = limpiarCadena($_POST['ano']);
    $mes = limpiarCadena($_POST['mes']);
    $id_usuario = $this->usuario->id($codigoPersona, $correo);
    if (empty($id_usuario)) {
      return ['success' => false];
    }

    $horas = $this->asistencia->reporte($codigoPersona, $ano, $mes);
    foreach ($horas as &$hora)
    {
      $resumen = $this->asistenciaResumen->buscar($id_usuario, $hora['fecha'], ['normal']);
      $total = 0;
      if (!empty($resumen)) {
        $total = $resumen['normal'];
      }
      $hora['total'] = $total;
    }
    $nombreMes = mes($mes);
    $data = [
      'fecha' => [
        'ano' => $ano,
        'mes' => $nombreMes
      ],
      'usuario' => $this->usuario->info($id_usuario),
      'horas' => $horas
    ];
    $content = $this->templateToHtml('reporte_asistencia_usuario', $data);
    $html = $this->templateToHtml('html', compact('content'));
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    $pdf = "/tmp/Reporte-Asistencia-{$ano}-{$nombreMes}.pdf";
    file_put_contents($pdf, $output);
    $body = "{$data['usuario']['nombre']}:\n\nSegún lo solicitado, se adjunta su reporte de asistencia de {$nombreMes} de {$ano}.\n\nSaludos.";
    $this->enviar($correo, "Reporte de Asistencia de {$nombreMes} de {$ano}", $body, $pdf);
    return ['success' => true];
  }

  protected function enviar($to, $subject, $message, $file = null)
  {
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->From = 'noreply@prove.cl';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = 'cuayocps@gmail.com';
    $mail->Password = 'xbmzzzegnrbczfaf';

    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;

    if (!empty($file)) {
      $mail->addAttachment($file, basename($file));
    }

    $mail->Send();
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

$method = $_GET['op'] ?: 'e404';
$result = (new ReporteController)->$method();
echo json_encode($result, JSON_PRETTY_PRINT);
