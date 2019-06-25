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
  
?>
<!DOCTYPE html>
<html lang="EN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon"/> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="../../js/sweetalert2.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>   
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="../../css/estilos.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Encode+Sans+Expanded" rel="stylesheet">
  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link rel="stylesheet" type="text/css" href="../../css/style_ac.css" /> 
  <title>Evaluación de desempeño ISSSTESON</title>
    <style>
      .container {
        background-color: #F9F9F9;
      }
      span {
        color:#2FE1ED;
      }
    </style>  
</head>
<body>
  <section class="container text-dark"><br>
    <h2 align="center">evaluación de desempeño isssteson</h2><hr>
    <p align="right" style="font-size: .9em;">
      Sesión de: 

      <?php 
        if((time() - $_SESSION['last_login_timestamp']) > 1500) {

          header("Location: ../../includes/logout.php");
        }

        else {

          $_SESSION['last_login_timestamp'] = time();
          echo "<span class='text-success'>" . $_SESSION['usuario']['nombre']." ".$_SESSION['usuario']['appaterno'] . "</span> | " . date("Y/m/d") . " | <a href='../../includes/logout.php'>Salir</a>";
        } 
      ?>
  </p>    
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="index.php">Inicio</a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Evaluaciones</a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="evaluacion_posiciones_60.php">Encuesta de posiciones (60%)</a>
        <a class="dropdown-item" href="evaluacion_objetivos_40.php">Encuesta de objetivos (40%)</a>
      </div>
    </li>     
    <li class="nav-item">
      <a class="nav-link" href="reportes.php">Reportes</a>
    </li>
  </ul>
<!-- Tab panes -->
  <div class="container tab-pane" id="capturar"><br>
    <h4>busqueda de empleado</h4>
        <div class="ac-container">
            <div><input id="ac-1" name="accordion-1" type="checkbox" />
                <label for="ac-1">Busqueda</label>
                <article class="ac-xl">
                  <form method="post">
                    <div class="form-inline">
                      <p>Apellido paterno<input type="text" class="form-control" name="appaterno" id="appaterno">
                          <input type="submit" id="buscarR" name="buscarR" class="btn btn-info" value="Buscar" required>
                      </p>
                    </div>
                  </form><br>
                </article>
            </div>  
<?php 

  if(isset($_POST['buscarR']) && isset($_POST['appaterno'])) {

    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    
    $nivel = mysqli_real_escape_string($conexion, $_SESSION['usuario']['nivel']);
    $departamento = mysqli_real_escape_string($conexion, $_SESSION['usuario']['departamento']);
    $busqueda = mysqli_real_escape_string($conexion, $_POST['appaterno']);

    $result = $conexion->query("SELECT * FROM empleados WHERE appaterno LIKE '%$busqueda%' AND departamento = '{$departamento}'  order by nivel DESC");
    if($result==true) {

      $cont = 0;
      $var = "";

      echo '<div class="ac-container">
              <div><input id="ac-2" name="accordion-2" type="checkbox" />
                  <label for="ac-2" style="text-transform:none; margin-left:10px; color: rgba(0, 0, 51, 1);">Resultados de Busqueda</label>
                      <article class="ac-xl">
                          <table class="table-bordered" style="width:98%; cursor:pointer; font-size:.9em;" id="tableR"><tr>
                              <th>Num. Empleado</th>
                              <th>Nivel</th>
                              <th>Nombre</th>
                              <th>Departamento</th>
                              </tr>';
      while($row = $result -> fetch_array(MYSQLI_BOTH)) {

        $idTemp = $row['num_e'];
        $idEvaluado = $row['id'];
        $_SESSION['id_evaluado'] = $row['id'];
        
        echo '<tr>
            <td id="first">'.$row['num_e'].'</td>
            <td id="first">'.$row['nivel'].'</td>
            <td id="middle">'.$row['nombre'].' '.$row['appaterno'].' '.$row['apmaterno']. '</td>
            <td id="last">'.$row['departamento'].'</td>
            </tr>';
      }
      echo '</table><br></article></div></div><hr>';
    }
    $conexion -> close();
  }
?>            
    </div>
  </div><br>
  <div class="container">
    <form action="" method="POST" class="form-horizontal" id="generar_encuesta" name="generar_reporte">
      <div class="form-group">
      <label for="" class="control-label">
          Empleado 
          <input placeholder="numero empleado" name="numE" id="numE" required> 
          <button type="submit" class="btn btn-success" id="btn_generar_reporte" name="btn_generar_reporte">Generar Reporte</button>   
      </label>
      </div>    
    </form><hr>


  <div class="container">
    <div class="row">
    
<?php  

  if(isset($_POST['btn_generar_reporte'])) {
    
    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    $fecha = date('Y-m-d');
    $empleado = mysqli_real_escape_string($conexion, $_POST['numE']);
    $miId = mysqli_real_escape_string($conexion, $_SESSION['usuario']['id']);          

    //Consulta para obtener el ID de el empleado que será evaluado
    $resultIdE = $conexion->query("SELECT * FROM empleados WHERE num_e = '{$empleado}'")->fetch_assoc();
    $empEvaluado = $resultIdE['id'];
    $empEvaluadoNivel = $resultIdE['nivel'];
    $empEvaluadoDepto = $resultIdE['departamento'];
    $encuestaObjetivos = "objetivos";

    //Consulta para saber si empleado a evaluar es subordinado de evaluador
    $resultSubordinado = $conexion->query("SELECT * FROM empleado_encargado WHERE id_encargado = '{$miId}' AND id_empleado = '{$empEvaluado}'")->fetch_assoc();

    if($resultSubordinado > 0) {
    //Consulta para obtener el ID de la encuesta Objetivos
      $resultIdEncuesta =$conexion->query("SELECT e.* FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$encuestaObjetivos}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
      $nivelEncuesta = $resultIdEncuesta['id'];
      $valorE = $resultIdEncuesta['valor_encuesta'];
      $periodoE = $resultIdEncuesta['periodo_encuesta_id'];

      if($empEvaluadoNivel > 1 && $empEvaluadoNivel <= 3) {
        $div = 92;
        $encuestaPosiciones = "operativas";
        //Consulta para obtener el ID de la encuesta posiciones
        $resultIdEncuestaP = $conexion->query("SELECT e.id, e.valor_encuesta FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion = '{$encuestaPosiciones}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
        $encuesta60 =$resultIdEncuestaP['id']; 
        $valorPos = $resultIdEncuestaP['valor_encuesta'];
      }
      else if($empEvaluadoNivel >= 4 && $empEvaluadoNivel <= 5) {
        $div = 124; 
        $encuestaPosiciones = "tecnicas especializadas";
        //Consulta para obtener el ID de la encuesta posiciones
        $resultIdEncuestaP = $conexion->query("SELECT e.id, e.valor_encuesta FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion = '{$encuestaPosiciones}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
        $encuesta60 =$resultIdEncuestaP['id'];  
        $valorPos = $resultIdEncuestaP['valor_encuesta'];
      }
      else if($empEvaluadoNivel >= 6 && $empEvaluadoNivel <= 9) {
        $div = 196;    
        $encuestaPosiciones = "tecnicas profesionales";
        //Consulta para obtener el ID de la encuesta posiciones
        $resultIdEncuestaP = $conexion->query("SELECT e.id, e.valor_encuesta FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion = '{$encuestaPosiciones}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
        $encuesta60 =$resultIdEncuestaP['id'];  
        $valorPos = $resultIdEncuestaP['valor_encuesta'];
      }
      else {
        echo "Error";
      }

      $resultRepo60 = $conexion->query("SELECT e.nombre, e.appaterno, e.apmaterno, e.departamento, e.nivel, AVG(r.resultado) AS promedio, SUM(r.resultado) AS total, (COUNT(DISTINCT r.empleado_evalua_id)) AS evaluador, (((SUM(r.resultado)) / (COUNT(DISTINCT(r.empleado_evalua_id))) * '{$valorPos}') / '{$div}') AS final FROM empleados e INNER JOIN resultados_posiciones r ON e.id = r.empleado_evaluado_id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON en.periodo_encuesta_id = p.id WHERE r.fecha_captura BETWEEN p.fecha_inicio AND p.fecha_fin AND r.encuesta_id = '{$encuesta60}' AND e.id = '{$empEvaluado}' GROUP BY e.id")->fetch_assoc();

      $resultRepo40 = $conexion->query("SELECT e.nombre, r.objetivo, r.ponderacion, r.consecucion, r.comentarios  FROM resultados_objetivos r INNER JOIN empleados e ON r.empleado_evaluado_id = e.id WHERE e.id = '{$empEvaluado}' AND r.encuesta_id = '{$nivelEncuesta}' ORDER BY e.nombre");

      /*         SELECT e.nombre, ((SUM(r.consecucion) * '{$valorE}') / 25) AS total  FROM resultados_objetivos r INNER JOIN empleados e ON r.empleado_evaluado_id = e.id WHERE e.id = '{$empEvaluado}' AND r.encuesta_id = '{$nivelEncuesta}' GROUP BY e.nombre*/

      $resultRepo40Total = $conexion->query("SELECT e.nombre, ((SUM((r.ponderacion / r.consecucion) * '{$valorE}')) / 100) AS total FROM resultados_objetivos r INNER JOIN empleados e ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON r.encuesta_id = en.id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE e.id = '{$empEvaluado}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin GROUP BY e.nombre")->fetch_assoc();

      $rNombre60 = $resultRepo60['nombre'] . " " . $resultRepo60['appaterno'] . " " . $resultRepo60['apmaterno'] . "<br>";
      $rDepto60 = $resultRepo60['departamento'] . "<br>";
      $rNivel60 = $resultRepo60['nivel'] . "<br>";
      $rPromedio60 = $resultRepo60['promedio'] . "<br>";
      $rResultado60 = $resultRepo60['total'] . "<br>";
      $rEvaluador60 = $resultRepo60['evaluador'] . "<br>";
      $rTotal60 = number_format((float)$resultRepo60['final'],2) . "%<br>";
      $rTotal40 = number_format((float)$resultRepo40Total['total'],2) . "%<br>";
      $total = number_format(((float)$rTotal60 + (float)$rTotal40),2);

  //    $resultRepo40Rows = $conexion->query($queryRepo40);

      echo '<div class="col">
        <h4>Datos del empleado</h4>
          <p style="font-size: 1.2em;">
            <span style="color:#333;">Nombre: ' . $rNombre60 . '</span>
            <span style="color:#333;">Nivel: ' . $rNivel60 . '</span>
            <span style="color:#333;">Cantidad de veces evaluado: ' . $rEvaluador60 . '</span>
            <span style="color:#333;">Porcentaje de evaluación posiciones: ' . $rTotal60 . '</span>
            <span style="color:#333;">Porcentaje de evaluación objetivos: ' . $rTotal40 . '</span>
            Total: <span style="font-size: 1.4em; color: #990000;">' . $total . '%</span>
          </p></div>';

      //Datos para generar Grafico Pastel
      $total100 = 100 - $total;
      $dataPoints = array(
        array("label"=> "Posiciones Operativas", "y"=> number_format((float)$resultRepo60['final'],2)),
        array("label"=> "Evaluación por Objetivos", "y"=> number_format((float)$resultRepo40Total['total'],2)),
        array("label"=> "Residuo", "y"=>$total100)
      );
      echo '<div class="col">
              <div id="chartContainer" style="width: 100%;"></div>
          </div>
      </div>';
      echo '<div><h5>Detalles Evaluación por Objetivos</h5><table class=" table table-bordered" style="width:98%; font-size: .9em;"><tr>
            <th>Objetivos de gestión</th>
            <th>Ponderación</th>
            <th>Nivel de consecución</th>
            <th>Comentarios</th>
            </tr>';
      $totalConsecucion = 0;
      while($row = $resultRepo40->fetch_array(MYSQLI_BOTH)) {
        echo '<tr>
              <td>' .$row['objetivo'] . '</td>
              <td>' .$row['ponderacion'] . '</td>
              <td>' .$row['consecucion'] . '</td>
              <td>' .$row['comentarios']. ' </td>
            </tr>';
        $totalConsecucion += $row['consecucion'];
      }
      echo '</table></div><br>';
    $url = 'reporteExcel.php?variable=' . $miId;
    echo '<p>
      Si desea generar un archivo Excel con los resultados de todos sus empleados a cargo, de clic <a href="'.$url.'" target="_blank">AQUÍ</a>
    </p><br>';
    }
    else {
      echo "<script>
          swal('Encuesta ISSSTESON', 'Error, no puede generar reporte de este empleado.', 'error');
        </script>";       
    }
    $conexion->close();   
  }
?>
  
  </section>
  <!-- Initialize Bootstrap functionality -->
  <script>
  // Initialize tooltip component
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

  // Initialize popover component
  $(function () {
    $('[data-toggle="popover"]').popover()
  })
  </script>
  <script>
  
  $("#tableR tr").click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
     var value1=$(this).find('td#first').html();
     $("#numE").val(value1);
     
  });
</script> 
  <!-- Initialize Bootstrap functionality -->
  <script>
  // Initialize tooltip component
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

  // Initialize popover component
  $(function () {
    $('[data-toggle="popover"]').popover()
  })
  </script>
  <script>
    window.onload = function () {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: false,
        backgroundColor: "#F9F9F9",
        title:{
          text: "Evaluación de Desempeño ISSSTESON"
        },
        subtitles: [{
          text: "Resultados de encuesta por empleado"
        }],
        data: [{
          type: "pie",
          showInLegend: "false",
          legendText: "{label}",
          indexLabelFontSize: 14,
          indexLabel: "{label} - #percent%",
          dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();
    }
  </script> 
  <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>