<?php
session_start();
require './vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

require_once('./conexion/cone.php');

// Validar tabla recibida
if (!isset($_GET['tabla']) || empty($_GET['tabla'])) {
    die("Error: Parámetro 'tabla' requerido.");
}

$tabla = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['tabla']);

// Verificar que exista la tabla
$check = mysqli_query($con, "SHOW TABLES LIKE '$tabla'");
if (mysqli_num_rows($check) === 0) {
    die("Error: La tabla '$tabla' no existe.");
}

// Obtener columnas reales
$columns = [];
$columnsResult = mysqli_query($con, "SHOW COLUMNS FROM $tabla");
while ($col = mysqli_fetch_assoc($columnsResult)) {
    $columns[] = $col['Field'];
}

// Excluir columnas sensibles si es tb_usuario
if ($tabla === 'tb_usuario') {
    $columns = array_filter($columns, function($col) {
        return $col !== 'usu_pass';
    });
    $columns = array_values($columns); // Reindexar
}

// Definir encabezados personalizados y título
$nombresColumnas = [];
$tituloTabla = '';

switch ($tabla) {
    case 'categoria':
        $tituloTabla = 'Listado de Categorías';
        $nombresColumnas = ['Id', 'Nombre'];
        break;
    case 'subcategoria':
        $tituloTabla = 'Listado de Subcategorías';
        $nombresColumnas = ['Id', 'Nombre', 'Categoría'];
        break;
    case 'producto':
        $tituloTabla = 'Listado de Productos';
        $nombresColumnas = [
            'Id', 'Nombre', 'Descripción', 'Modelo', 'Marca',
            'Subcategoría', 'Precio', 'Stock',
            'Fecha Creación', 'Última Actualización'
        ];
        break;
    case 'tb_cliente':
        $tituloTabla = 'Listado de Clientes';
        $nombresColumnas = [
            'Id', 'Nombres', 'Apellidos', 'Dirección',
            'Teléfono', 'Email', 'Fecha Registro'
        ];
        break;
    case 'tb_usuario':
        $tituloTabla = 'Listado de Usuarios';
        $nombresColumnas = [
            'Id', 'Usuario', 'Email', 'Rol',
            'Estado', 'Fecha Creación', 'Última Actualización'
        ];
        break;
    default:
        $tituloTabla = 'Listado de ' . ucfirst($tabla);
        $nombresColumnas = $columns;
        break;
}

// Obtener usuario actual desde sesión
$usuarioActual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Desconocido';

// Fecha y hora
date_default_timezone_set('America/Lima');
$fechaGeneracion = date('d/m/Y H:i:s');

// Obtener datos
$sql = "SELECT * FROM $tabla";
$result = mysqli_query($con, $sql);

// Generar HTML para el PDF
$html = '
    <h2 style="text-align: center;">' . htmlspecialchars($tituloTabla) . '</h2>
    <p style="text-align: center; font-size: 12px; margin-top: -10px;">
        Generado el: ' . $fechaGeneracion . ' | Usuario: ' . htmlspecialchars($usuarioActual) . '
    </p>';

$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead><tr style="background-color: #f2f2f2;">';

foreach ($nombresColumnas as $nombre) {
    $html .= '<th>' . htmlspecialchars($nombre) . '</th>';
}

$html .= '</tr></thead><tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>';
    foreach ($columns as $col) {
        $html .= '<td>' . htmlspecialchars($row[$col]) . '</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Configurar DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Export_$tabla.pdf", ["Attachment" => false]);
