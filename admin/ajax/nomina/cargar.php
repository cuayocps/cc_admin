<?php
error_reporting(E_ALL);

require_once dirname(dirname(__DIR__)) . '/modelos/Departamento.php';
require_once dirname(dirname(__DIR__)) . '/modelos/Tipousuario.php';
require_once dirname(dirname(__DIR__)) . '/modelos/Usuario.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$rutaArchivo = $_FILES['nomina']['tmp_name'];
$reader = new Xlsx();
$reader->setLoadSheetsOnly(['Nómina']);
$spreadsheet = $reader->load($rutaArchivo);

$nomina = $spreadsheet->getActiveSheet()->toArray();

$columnas = array_shift($nomina);
$usuario = new Usuario();
function idValor($v)
{
    $a = explode(':', $v);
    return array_shift($a);
}
$creados = 0;
$existentes = 0;
$fallidos = 0;
foreach ($nomina as $datos) {
    $fila = array_combine($columnas, $datos);
    $idtipousuario = idValor($fila['Tipo usuario']);
    $iddepartamento = idValor($fila['Departamento']);
    $nombre = $fila['Nombre'];
    $apellidos = $fila['Apellidos'];
    $email = $fila['Email'];
    $login = $fila['Login'];
    $codigo_persona = $fila['Clave de asistencia'];
    $clavehash = hash("SHA256", $fila['Clave de ingreso']);
    $usuariocreado = limpiarCadena($fila['Nombre']);
    $imagen = 'default.jpg';
    $idmensaje = 0;

    if ($usuario->buscar(compact('login') || $usuario->buscar(compact('codigo_persona')))) {
        ++$existentes;
        continue;
    }

    try {
        $rspta = $usuario->insertar($nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $clavehash, $imagen, $usuariocreado, $codigo_persona);
        if (!$rspta) {
            throw new Exception("No se pudo agregar el usuario {$login}");
        }
        ++$creados;
    } catch (Exception $e) {
        ++$fallidos;
    }
}

echo json_encode(compact('creados', 'existentes', 'fallidos'));
