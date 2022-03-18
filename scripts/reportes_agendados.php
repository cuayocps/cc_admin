<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', true);

require_once(dirname(__DIR__) . '/admin/config/Conexion.php');
require_once(dirname(__DIR__) . '/admin/modelos/Usuario.php');
require_once(dirname(__DIR__) . '/admin/modelos/AgendaReporteGrupo.php');
require_once(dirname(__DIR__) . '/admin/services/reporte_asistencia.php');

use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;

class ReporteAgendado
{
  public function main()
  {
    $agendados = $this->agendados();
    foreach ($agendados as $agendado) {
      $reporte = $this->reporte($agendado);
      $this->enviar($reporte, $agendado);
    }
  }

  protected function reporte($agendado)
  {
    $codigo_persona = null;
    $id_departamento = null;
    if (!empty($agendado['id_usuario'])) {
      $usuario = new Usuario;
      $codigo_persona = $usuario->codigo($agendado['id_usuario']);
    }
    if (!empty($agendado['id_departamento'])) {
      $id_departamento = $agendado['id_departamento'];
    }

    $desde = $this->date($agendado['desde']);
    $hasta = $this->date($agendado['hasta'], '23:59:59');
    $reporteAsistencia = new ReporteAsistencia;
    return $reporteAsistencia->getXlsx($desde, $hasta, $id_departamento, $codigo_persona);
  }

  protected function agendados()
  {
    $now = Carbon::now();
    $today = $now->day;
    $week_day = strtoupper($now->localeDayOfWeek);
    $last_day = true || $now->isLastOfMonth() ? 'LAST_DAY' : null;
    $hora = $now->format('H:i');
    $dias = array_filter(compact('week_day', 'last_day', 'today'));
    $lista_dias = implode("', '", $dias);
    $sql = "SELECT * FROM agenda_reportes WHERE dia IN ('{$lista_dias}') -- hora = '$hora'";
    return consultaEnArray(ejecutarConsulta($sql));
  }

  protected function date($string, $time = '00:00:00')
  {
    $date = Carbon::now();
    list($sm, $day) = explode(',', $string);

    switch ($sm) {
      case 'LAST_MONTH':
        $date->subMonth();
        break;
      case 'LAST_WEEK':
        $date->subWeek();
        break;
    }
    switch ($day) {
      case 'MONDAY':
      case 'TUESDAY':
      case 'WEDNESDAY':
      case 'THURSDAY':
      case 'FRIDAY':
      case 'SATURDAY':
      case 'SUNDAY':
        $date->startOfWeek();
        $ref = new ReflectionClass('Carbon\Carbon');
        $dow = $ref->getConstant($day);
        while ($date->dayOfWeek != $dow) {
          $date->addDay();
        }
        break;
      case 'TODAY':
        break;
      case 'LAST_DAY':
        $date->endOfMonth();
        break;
      default:
        $date->day($day);
    }
    return $date->modify($time);
  }

  protected function enviar($reporte, $agendado)
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
    $mail->Subject = 'Reporte agendado';
    $mail->Body = 'Se adjunta reporte agendado.';

    if (!empty($reporte)) {
      $mail->addAttachment($reporte, basename($reporte));
    }

    $destinatarios = array_unique(AgendaReporteGrupo::destinatarios($agendado['id']));
    foreach ($destinatarios as $destinatario) {
      $mail->AddAddress($destinatario);
    }

    $mail->Send();
  }

}

$c = new ReporteAgendado;
$c->main();
