<?php

  session_start();
  date_default_timezone_set('America/Los_Angeles');
  if(isset($_SESSION['usuario'])) {
    if($_SESSION['usuario']['tipo'] != 'root') {
      header('../../includes/logout.php');
    }
    else {
      header('../../index.php');
    }
  }
  

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
//require_once dirname(__FILE__) . '../../includes/Classes/PHPExcel.php';
require_once '../../includes/Classes/PHPExcel.php';

include_once '../../includes/conexion.php';
$conexion = Conexion::conn();
$departamento = mysqli_real_escape_string($conexion, $_GET['departamento']);
$periodo = mysqli_real_escape_string($conexion, $_GET['periodo']);
$year = mysqli_real_escape_string($conexion, $_GET['year']);
$fecha = date('Y-m-d');

if($periodo == 1) {
	$fechaInicio = $year . "-01-01";
	$fechaFin = $year . "-03-31"; 
}
elseif($periodo == 2) {
	$fechaInicio = $year . "-04-01";
	$fechaFin = $year . "-06-30";        
}
elseif($periodo == 3) {
	$fechaInicio = $year . "-07-01";
	$fechaFin = $year . "-09-30";        
}
elseif($periodo == 4) {
	$fechaInicio = $year . "-10-01";
	$fechaFin = $year . "-12-31";        
}

$resultEmp = $conexion->query("SELECT e.* FROM empleados e WHERE e.departamento = '{$departamento}'");

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("Sistema de Encuestas ISSSTESON")
->setSubject("Sistema de Encuestas ISSSTESON")
->setDescription("Informe de empleados evaluados con encuesta ISSSTESON.")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Sistema de Encuestas ISSSTESON");

// Agregar Informacion
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Reporte de Resultados Encuesta ISSSTESON')
->setCellValue('A2', 'Departamento: ')
->setCellValue('B2', $departamento)
->setCellValue('A3', 'Periodo de evaluación: ')
->setCellValue('B3', $periodo)
->setCellValue('C3', 'Fecha')
->setCellValue('D3', $fechaInicio . " a " . $fechaFin)
->setCellValue('A5', 'Apellido Paterno')
->setCellValue('B5', 'Apellido Materno')
->setCellValue('C5', 'Nombre')
->setCellValue('D5', 'Nivel')
->setCellValue('E5', 'Total de evaluaciones')
->setCellValue('F5', 'Encuesta Posiciones')
->setCellValue('G5', 'Evaluación Objetivos')
->setCellValue('H5', 'Total');

$i = 6;
while($rowEmp = $resultEmp->fetch_array(MYSQLI_ASSOC)) {
	$idEmp = $rowEmp['id'];
	$nivelEmp = $rowEmp['nivel'];
	$appaternoEmp = $rowEmp['appaterno'];
	$apmaternoEmp = $rowEmp['apmaterno'];
	$nombreEmp = $rowEmp['nombre'];

	if($nivelEmp > 1 && $nivelEmp <= 3) {
	  $div = 92;
	}
	elseif($nivelEmp >= 4 && $nivelEmp <=5) {
	  $div = 124;
	}
	elseif($nivelEmp >= 6 && $nivelEmp <= 9) {
	  $div = 192;
	}

	//Obtener ID del periodo actual
	$resultIdPeriodo = $conexion->query("SELECT p.id, p.periodo, p.descripcion FROM periodo_encuesta p WHERE p.periodo = '{$periodo}' AND p.fecha_inicio = '{$fechaInicio}' AND p.fecha_fin = '{$fechaFin}'")->fetch_assoc();
	$idPeriodo = $resultIdPeriodo['id'];
	$descripcion = $resultIdPeriodo['descripcion'] . $year;
	//$periodoP = $resultIdPeriodo['periodo'];

	//Obtener valores de encuestas
	$resultValorEO = $conexion->query("SELECT e.valor_encuesta FROM encuestas e INNER JOIN periodo_encuesta p ON p.id = e.periodo_encuesta_id WHERE e.descripcion = 'objetivos' AND p.fecha_inicio = '{$fechaInicio}' AND p.fecha_fin = '{$fechaFin}'")->fetch_assoc();
	$valorO = $resultValorEO['valor_encuesta'];

	$resultValorEP = $conexion->query("SELECT e.valor_encuesta FROM encuestas e INNER JOIN periodo_encuesta p ON p.id = e.periodo_encuesta_id WHERE e.descripcion = 'operativas' AND p.fecha_inicio = '{$fechaInicio}' AND p.fecha_fin = '{$fechaFin}'")->fetch_assoc();
	$valorP = $resultValorEP['valor_encuesta'];

	$resultDeptoP = $conexion->query("SELECT (COUNT(DISTINCT r.empleado_evalua_id)) AS veces_evaluado, (((SUM(r.resultado)) / (COUNT(DISTINCT(r.empleado_evalua_id))) * '{$valorP}') / '{$div}') AS  total60 FROM empleado_encargado ee INNER JOIN empleados e ON ee.id_empleado = e.id INNER JOIN resultados_posiciones r ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON en.periodo_encuesta_id = p.id WHERE e.id = '{$idEmp}' AND r.fecha_captura BETWEEN p.fecha_inicio AND p.fecha_fin AND en.periodo_encuesta_id = '{$idPeriodo}' AND e.departamento = '{$departamento}' GROUP BY e.id")->fetch_assoc();
	$resultDeptoO = $conexion->query("SELECT ((SUM((r.ponderacion / r.consecucion) * '{$valorO}')) / 100) AS total40 FROM resultados_objetivos r INNER JOIN empleados e ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE e.id = '{$idEmp}' AND r.fecha_captura BETWEEN p.fecha_inicio AND p.fecha_fin AND en.periodo_encuesta_id = '{$idPeriodo}' AND e.departamento = '{$departamento}' GROUP BY e.nombre")->fetch_assoc();

	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$i, $apmaternoEmp)
	->setCellValue('B'.$i, $apmaternoEmp)
	->setCellValue('C'.$i, $nombreEmp)
	->setCellValue('D'.$i, $nivelEmp)
	->setCellValue('E'.$i, $resultDeptoP['veces_evaluado'])
	->setCellValue('F'.$i, number_format($resultDeptoP['total60'],2))
	->setCellValue('G'.$i, number_format($resultDeptoO['total40'],2))
	->setCellValue('H'.$i, number_format(($resultDeptoP['total60'] + $resultDeptoO['total40']),2));

	$i++;

}
 
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Empleados ' . $departamento);
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="informe-deptoISSSTESON.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
$conexion->close();
?>	
