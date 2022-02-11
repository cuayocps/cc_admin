<?php

require_once '../modelos/Asistencia.php';
require_once '../modelos/AsistenciaResumen.php';
require_once '../modelos/Usuario.php';
require_once '../modelos/Departamento.php';

use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
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
    $this->departamento = new Departamento();
  }

  public function pdf()
  {
    $fecha_inicio = Carbon::parse(limpiarCadena($_POST['fecha_inicio']));
    $fecha_fin = Carbon::parse(limpiarCadena($_POST['fecha_fin']));
    $idDepartamento = limpiarCadena($_POST['iddepartamento']);
    $codigoPersona = limpiarCadena($_POST['idcliente']);

    $data = $this->getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);

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

    $html = $this->templateToHtml('html', compact('content', 'fecha_inicio', 'fecha_fin'));

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $nombre = $this->nombreReporte($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin);
    $dompdf->stream($nombre);
    exit;
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

  protected function nombreReporte($idDepartamento, $codigoPersona, Carbon $fecha_inicio, Carbon $fecha_fin)
  {
    $nombre = 'reporte-asistencia';
    if (!empty($codigoPersona)) {
      $usuario = $this->usuario->info($codigoPersona, ["CONCAT_WS(' ', nombre, apellidos) AS nombre_completo"]);
      $nombre .= '-' . slugify($usuario['nombre_completo']);
    } else if (!empty($idDepartamento)) {
      $nombre .= '-' . slugify($this->departamento->nombre($idDepartamento));
    }
    $nombre .= '-' . $fecha_inicio->format('dmY') . '-' . $fecha_fin->format('dmY');
    return $nombre;
  }

  protected function getReportData($idDepartamento, $codigoPersona, $fecha_inicio, $fecha_fin)
  {
    if (!empty($codigoPersona)) {
      $usuario = $this->usuario->info($codigoPersona);
      $usuarios = [$usuario];
    } else if (!empty($idDepartamento)) {
      $usuarios = $this->usuario->allInfo($idDepartamento);
    } else {
      $usuarios = $this->usuario->allInfo();
    }
    $data = $this->groupUsersByAppartement($usuarios);
    $data = $this->addAppartementName($data);
    $data = $this->addHoursToUsers($data, $fecha_inicio, $fecha_fin);
    return array_filter($data, function ($reg) {
      return !empty($reg['usuarios']);
    });
  }

  protected function groupUsersByAppartement($usuarios)
  {
    $departamentos = [];
    foreach ($usuarios as $usuario) {
      $id = $usuario['iddepartamento'];
      if (!isset($departamentos[$id])) {
        $departamentos[] = [
          'nombre' => '',
          'usuarios' => []
        ];
      }
      $departamentos[$id]['usuarios'][] = $usuario;
    }
    return $departamentos;
  }

  protected function addAppartementName($data)
  {
    $ids = array_keys($data);
    $nombres = $this->departamento->nombres($ids);
    foreach ($nombres as $id => $nombre) {
      $data[$id]['nombre'] = $nombre;
    }
    return $data;
  }

  protected function addHoursToUsers($data, $fecha_inicio, $fecha_fin)
  {
    foreach ($data as $departamento => $datos) {
      foreach ($datos['usuarios'] as $i => $usuario) {
        $data[$departamento]['usuarios'][$i]['horas'] = $this->horasUsuario($usuario, $fecha_inicio, $fecha_fin);
      }
    }
    return $data;
  }

  protected function horasUsuario($usuario, $fecha_inicio, $fecha_fin)
  {
    $horas = $this->asistencia->reporte($usuario['codigo_persona'], $fecha_inicio, $fecha_fin);
    foreach ($horas as &$hora) {
      $resumen = $this->asistenciaResumen->buscar($usuario['idusuario'], $hora['fecha'], ['fecha', 'normal', 'extra']);
      $total = 0;
      $extra = 0;
      $fecha = Carbon::parse($hora['fecha']);
      if (!empty($resumen)) {
        $total = $resumen['normal'];
        $extra = $resumen['extra'];
      }
      $hora['fecha'] = $fecha;
      $hora['total'] = $total;
      $hora['extra'] = $extra;
    }
    return $horas;
  }
}

$method = $_GET['op'] ?: 'e404';
$result = (new ReporteController)->$method();
echo json_encode($result, JSON_PRETTY_PRINT);
