<?php

  session_start();
  date_default_timezone_set('America/Los_Angeles');
  if(isset($_SESSION['usuario'])) {
    if($_SESSION['usuario']['tipo'] != 'jefe') {
      header('Location: ../empleado/');
    }
  }
  else {
    header('Location: ../../includes/logout.php');
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
$miId = $_GET['variable'];
$fecha = date('Y-m-d');

$resultEnc = $conexion->query("SELECT fecha_inicio, fecha_fin FROM periodo_encuesta WHERE '{$fecha}' BETWEEN fecha_inicio AND fecha_fin")->fetch_assoc();
$fechaInicio = $resultEnc['fecha_inicio'];
$fechaFin = $resultEnc['fecha_fin'];
$result = $conexion->query("SELECT * FROM empleados WHERE id = '{$miId}'")->fetch_assoc();
$nombre = $result['nombre'] . ' ' . $result['appaterno'] . ' ' . $result['apmaterno'];
$departamento = $result['departamento'];

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
->setCellValue('A1', 'Jefe: ')
->setCellValue('B1', $nombre)
->setCellValue('A2', 'Departamento: ')
->setCellValue('B2', $departamento)
->setCellValue('A3', 'Periodo de evaluación: ')
->setCellValue('B3', $fechaInicio)
->setCellValue('C3', 'al')
->setCellValue('D3', $fechaFin)
->setCellValue('A5', 'Apellido Paterno')
->setCellValue('B5', 'Apellido Materno')
->setCellValue('C5', 'Nombre')
->setCellValue('D5', 'Nivel')
->setCellValue('E5', 'Total de evaluaciones')
->setCellValue('F5', 'Encuesta Posiciones')
->setCellValue('G5', 'Evaluación Objetivos')
->setCellValue('H5', 'Total');
//->setCellValue('C2', '=sum(A2:B2)');

$resultSubordinados = $conexion->query("SELECT e.id, e.nivel FROM empleado_encargado ee INNER JOIN empleados e ON ee.id_empleado = e.id WHERE id_encargado = '{$miId}' ORDER BY e.id");

$i = 6;
while($tempEmp = $resultSubordinados->fetch_array(MYSQLI_ASSOC)) {
	$idEmp = $tempEmp['id'];
	$tempNivel = $tempEmp['nivel'];
	if($tempNivel > 1 && $tempNivel <= 3) {
		$encuesta = "operativas";
		$resultIdEncuestaP =$conexion->query("SELECT e.id, e.valor_encuesta, e.periodo_encuesta_id FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion = '{$encuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
	  	$nivelEncuesta = $resultIdEncuestaP['id'];
	  	$valorE = $resultIdEncuestaP['valor_encuesta'];
	  	$periodoE = $resultIdEncuestaP['periodo_encuesta_id'];		
		$result60 = $conexion->query("SELECT e.nombre, e.appaterno, e.apmaterno, e.nivel, (COUNT(DISTINCT r.empleado_evalua_id)) AS veces_evaluado, (((SUM(r.resultado)) / (COUNT(DISTINCT(r.empleado_evalua_id))) * '{$valorE}') / 92) AS total60 FROM empleado_encargado ee INNER JOIN empleados e ON ee.id_empleado = e.id INNER JOIN resultados_posiciones r ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON en.periodo_encuesta_id = p.id WHERE r.fecha_captura BETWEEN p.fecha_inicio AND p.fecha_fin AND en.periodo_encuesta_id = '{$periodoE}' AND ee.id_encargado = '{$miId}' AND r.encuesta_id = '{$nivelEncuesta}' AND e.id = '{$idEmp}' GROUP BY e.id");
	}
	else if($tempNivel >= 4 && $tempNivel <= 5) {
		$encuesta = "tecnicas especializadas";
		$resultIdEncuesta =$conexion->query("SELECT e.* FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$encuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
	  	$nivelEncuesta = $resultIdEncuesta['id'];
	  	$valorE = $resultIdEncuesta['valor_encuesta'];
	  	$periodoE = $resultIdEncuesta['periodo_encuesta_id'];			
		$result60 = $conexion->query("SELECT e.nombre, e.appaterno, e.apmaterno, e.nivel, (COUNT(DISTINCT r.empleado_evalua_id)) AS veces_evaluado, (((SUM(r.resultado)) / (COUNT(DISTINCT(r.empleado_evalua_id))) * '{$valorE}') / 124) AS total60 FROM empleado_encargado ee INNER JOIN empleados e ON ee.id_empleado = e.id INNER JOIN resultados_posiciones r ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON en.periodo_encuesta_id = p.id WHERE r.fecha_captura BETWEEN p.fecha_inicio AND p.fecha_fin AND en.periodo_encuesta_id = '{$periodoE}' AND ee.id_encargado = '{$miId}' AND r.encuesta_id = '{$nivelEncuesta}' AND e.id = '{$idEmp}' GROUP BY e.id");
	}
	else if($tempNivel >= 6 && $tempNivel <= 9) {
		$encuesta = "tecnicas profesionales";
		$resultIdEncuesta =$conexion->query("SELECT e.* FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$encuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
	  	$nivelEncuesta = $resultIdEncuesta['id'];
	  	$valorE = $resultIdEncuesta['valor_encuesta'];
	  	$periodoE = $resultIdEncuesta['periodo_encuesta_id'];			
		$result60 = $conexion->query("SELECT e.nombre, e.appaterno, e.apmaterno, e.nivel, (COUNT(DISTINCT r.empleado_evalua_id)) AS veces_evaluado, (((SUM(r.resultado)) / (COUNT(DISTINCT(r.empleado_evalua_id))) * '{$valorE}') / 196) AS total60 FROM empleado_encargado ee INNER JOIN empleados e ON ee.id_empleado = e.id INNER JOIN resultados_posiciones r ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON en.periodo_encuesta_id = p.id WHERE r.fecha_captura BETWEEN p.fecha_inicio AND p.fecha_fin AND en.periodo_encuesta_id = '{$periodoE}' AND ee.id_encargado = '{$miId}' AND r.encuesta_id = '{$nivelEncuesta}' AND e.id = '{$idEmp}' GROUP BY e.id");	
	}
	else {
		echo "Error";
	}
	
	$resultIdEncuestaO =$conexion->query("SELECT e.* FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='objetivos' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
  	$nivelEncuestaO = $resultIdEncuestaO['id'];
  	$valorEO = $resultIdEncuestaO['valor_encuesta'];
  	$periodoO  = $resultIdEncuestaO['periodo_encuesta_id'];
  	$result40 = $conexion->query("SELECT e.nombre, ((SUM((r.ponderacion / r.consecucion) * '{$valorEO}')) / 100) AS total40 FROM resultados_objetivos r INNER JOIN empleados e ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE e.id = '{$idEmp}' AND r.encuesta_id = '{$nivelEncuestaO}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin GROUP BY e.nombre")->fetch_assoc();
	$total40 = number_format($result40['total40'],2);

	while($row = $result60->fetch_array(MYSQLI_ASSOC)) {
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$i, $row['appaterno'])
		->setCellValue('B'.$i, $row['apmaterno'])
		->setCellValue('C'.$i, $row['nombre'])
		->setCellValue('D'.$i, $row['nivel'])
		->setCellValue('E'.$i, $row['veces_evaluado'])
		->setCellValue('F'.$i, number_format($row['total60'],2))
		->setCellValue('G'.$i, $total40)
		->setCellValue('H'.$i, number_format(($row['total60'] + $total40),2));		
	}
	$i++;
}
 
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Empleados ' . $departamento);
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="informe-encuestaISSSTESON.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
$conexion->close();
?>	
