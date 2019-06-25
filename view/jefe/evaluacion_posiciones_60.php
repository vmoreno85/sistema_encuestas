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
    <form action="" method="POST" class="form-horizontal" id="generar_encuesta" name="generar_encuesta">
      <div class="form-group">
      <label for="" class="control-label">
          Empleado 
          <input placeholder="numero empleado" name="numE" id="numE" required> 
          <button type="submit" class="btn btn-success" id="btn_generar_po60" name="btn_generar_encuesta">Generar Encuesta</button>   
      </label>
      </div>    
    </form><hr>
<?php  
  if(isset($_POST['btn_generar_encuesta'])) {
    
    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    $usuario = mysqli_real_escape_string($conexion, $_POST['numE']);
    $miDepto = mysqli_real_escape_string($conexion, $_SESSION['usuario']['departamento']);
    $miNivel = mysqli_real_escape_string($conexion, $_SESSION['usuario']['nivel']);
    $miTipo = mysqli_real_escape_string($conexion, $_SESSION['usuario']['tipo']);
    $miId = mysqli_real_escape_string($conexion, $_SESSION['usuario']['id']);

    $resultEmp = $conexion->query("SELECT * FROM empleados WHERE num_e = '{$usuario}'")->fetch_assoc();
    $nombreEmp = $resultEmp['nombre'] . " " . $resultEmp['appaterno'] . " " . $resultEmp['apmaterno'];
    $nivelEmp = $resultEmp['nivel'];  
    $deptoEmp = $resultEmp['departamento'];
    $idEmp = $resultEmp['id'];
    $tipoEmp = $resultEmp['tipo'];
    $_SESSION['idEmp'] = $resultEmp['id'];
    $_SESSION['deptoEmp'] = $resultEmp['departamento'];

    if($nivelEmp > 1 && $nivelEmp <= 3 && $deptoEmp == $miDepto) {
      require_once 'po60.php';
      //require_once '../../includes/request.php';
    }
    elseif($nivelEmp >= 4 && $nivelEmp <=5 && $miDepto == $deptoEmp) {
      require_once 'te60.php';        
    }
    elseif($nivelEmp >= 6 && $nivelEmp <= 9 && $miDepto == $deptoEmp) {
      require_once 'tp60.php';
    }
    else {
      echo "<script>
          swal('Encuesta ISSSTESON', 'Error, el empleado que desea evaluar no pertenece a su departamento.', 'error');
        </script>";      
    }
    $conexion->close();
  }
?>
      </div>
    </div><br>   
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
</body>
</html>

<?php  

/*************************************************************************************************
************************************** POSICIONES OPERATIVAS ************************ 
**************************************************************************************************/

  if(isset($_POST['btn_pos_op60'])) {
    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    $fecha = date('Y-m-d');
    $descEncuesta = "operativas";
    $empEvalua = mysqli_real_escape_string($conexion, $_SESSION['usuario']['id']);
    $idEmp = mysqli_real_escape_string($conexion, $_SESSION['idEmp']);
    $deptoEmp = mysqli_real_escape_string($conexion, $_SESSION['deptoEmp']);
    $miDepto = mysqli_real_escape_string($conexion, $_SESSION['usuario']['departamento']);
    $optradio1 = mysqli_real_escape_string($conexion, $_POST['optradio1']); 
    $optradio2 = mysqli_real_escape_string($conexion, $_POST['optradio2']);
    $optradio3 = mysqli_real_escape_string($conexion, $_POST['optradio3']);
    $optradio4 = mysqli_real_escape_string($conexion, $_POST['optradio4']);
    $optradio5 = mysqli_real_escape_string($conexion, $_POST['optradio5']);
    $optradio6 = mysqli_real_escape_string($conexion, $_POST['optradio6']);
    $optradio7 = mysqli_real_escape_string($conexion, $_POST['optradio7']);
    $optradio8 = mysqli_real_escape_string($conexion, $_POST['optradio8']);
    $optradio9 = mysqli_real_escape_string($conexion, $_POST['optradio9']);
    $optradio10 = mysqli_real_escape_string($conexion, $_POST['optradio10']);
    $optradio11 = mysqli_real_escape_string($conexion, $_POST['optradio11']);
    $optradio12 = mysqli_real_escape_string($conexion, $_POST['optradio12']);
    $optradio13 = mysqli_real_escape_string($conexion, $_POST['optradio13']);
    $optradio14 = mysqli_real_escape_string($conexion, $_POST['optradio14']);
    $optradio15 = mysqli_real_escape_string($conexion, $_POST['optradio15']);
    $optradio16 = mysqli_real_escape_string($conexion, $_POST['optradio16']);
    $optradio17 = mysqli_real_escape_string($conexion, $_POST['optradio17']);
    $optradio18 = mysqli_real_escape_string($conexion, $_POST['optradio18']);
    $optradio19 = mysqli_real_escape_string($conexion, $_POST['optradio19']);
    $optradio20 = mysqli_real_escape_string($conexion, $_POST['optradio20']);
    $optradio21 = mysqli_real_escape_string($conexion, $_POST['optradio21']);
    $optradio22 = mysqli_real_escape_string($conexion, $_POST['optradio22']);
    $optradio23 = mysqli_real_escape_string($conexion, $_POST['optradio23']);

    //Consulta para obtener el ID de la encuesta a aplicar
    $resultIdEncuesta =$conexion->query("SELECT e.id FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$descEncuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
    $nivelEncuesta = $resultIdEncuesta['id'];

    //Conaulta para saber si hay registro de evaluado-evaluador
    if($deptoEmp == $miDepto) {

      $queryRevDup = "SELECT r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id  FROM resultados_posiciones r INNER JOIN empleados e ON r.empleado_evalua_id = e.id INNER JOIN encuestas en ON en.id = r.encuesta_id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE ('{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin) AND (r.empleado_evaluado_id = '{$idEmp}') AND r.encuesta_id = '{$nivelEncuesta}' AND r.empleado_evalua_id = '{$empEvalua}' GROUP BY r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id";

      $resultRevDup = $conexion->query($queryRevDup)->fetch_assoc();
      if($resultRevDup > 0) {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, usted ya evalúo a este empleado.', 'error');
          </script>";       
      }
      else {
        $queryIns = $conexion->query("INSERT INTO resultados_posiciones (encuesta_id, pregunta, resultado, fecha_captura, empleado_evaluado_id, empleado_evalua_id) VALUES 
         ('{$nivelEncuesta}', '1', '{$optradio1}', '{$fecha}', '{$idEmp}', '{$empEvalua}'), 
         ('{$nivelEncuesta}', '2', '{$optradio2}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '3', '{$optradio3}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '4', '{$optradio4}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '5', '{$optradio5}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '6', '{$optradio6}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '7', '{$optradio7}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '8', '{$optradio8}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '9', '{$optradio9}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '10', '{$optradio10}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '11', '{$optradio11}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '12', '{$optradio12}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '13', '{$optradio13}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '14', '{$optradio14}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '15', '{$optradio15}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '16', '{$optradio16}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '17', '{$optradio17}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '18', '{$optradio18}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '19', '{$optradio19}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '20', '{$optradio20}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '21', '{$optradio21}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '22', '{$optradio22}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '23', '{$optradio23}', '{$fecha}', '{$idEmp}', '{$empEvalua}')");
        
        if($queryIns) {
        echo "<script>
            swal('Encuesta ISSSTESON','Encuesta almacenada con éxito', 'success');
          </script>";        
          }
        else {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error al almacenar encuesta, intente de nuevo', 'error');
          </script>";        
        }
      }
    }
    else {
      echo "<script>
          swal('Encuesta ISSSTESON', 'Error, no es posible realizar encuesta, intentelo más.', 'error');
        </script>";        
    }
    $conexion->close();
  }
 
/*************************************************************************************************
************************************** POSICIONES TECNICAS ESPECIALIZADAS ************************ 
**************************************************************************************************/

  if(isset($_POST['btn_pos_te60'])) {
    
    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    $fecha = date('Y-m-d');
    $descEncuesta = "tecnicas especializadas";
    $empEvalua = mysqli_real_escape_string($conexion, $_SESSION['usuario']['id']);
    $idEmp = mysqli_real_escape_string($conexion, $_SESSION['idEmp']);
    $deptoEmp = mysqli_real_escape_string($conexion, $_SESSION['deptoEmp']);
    $miDepto = mysqli_real_escape_string($conexion, $_SESSION['usuario']['departamento']);
    $optradio1 = mysqli_real_escape_string($conexion, $_POST['optradio1']); 
    $optradio2 = mysqli_real_escape_string($conexion, $_POST['optradio2']);
    $optradio3 = mysqli_real_escape_string($conexion, $_POST['optradio3']);
    $optradio4 = mysqli_real_escape_string($conexion, $_POST['optradio4']);
    $optradio5 = mysqli_real_escape_string($conexion, $_POST['optradio5']);
    $optradio6 = mysqli_real_escape_string($conexion, $_POST['optradio6']);
    $optradio7 = mysqli_real_escape_string($conexion, $_POST['optradio7']);
    $optradio8 = mysqli_real_escape_string($conexion, $_POST['optradio8']);
    $optradio9 = mysqli_real_escape_string($conexion, $_POST['optradio9']);
    $optradio10 = mysqli_real_escape_string($conexion, $_POST['optradio10']);
    $optradio11 = mysqli_real_escape_string($conexion, $_POST['optradio11']);
    $optradio12 = mysqli_real_escape_string($conexion, $_POST['optradio12']);
    $optradio13 = mysqli_real_escape_string($conexion, $_POST['optradio13']);
    $optradio14 = mysqli_real_escape_string($conexion, $_POST['optradio14']);
    $optradio15 = mysqli_real_escape_string($conexion, $_POST['optradio15']);
    $optradio16 = mysqli_real_escape_string($conexion, $_POST['optradio16']);
    $optradio17 = mysqli_real_escape_string($conexion, $_POST['optradio17']);
    $optradio18 = mysqli_real_escape_string($conexion, $_POST['optradio18']);
    $optradio19 = mysqli_real_escape_string($conexion, $_POST['optradio19']);
    $optradio20 = mysqli_real_escape_string($conexion, $_POST['optradio20']);
    $optradio21 = mysqli_real_escape_string($conexion, $_POST['optradio21']);
    $optradio22 = mysqli_real_escape_string($conexion, $_POST['optradio22']);
    $optradio23 = mysqli_real_escape_string($conexion, $_POST['optradio23']);
    $optradio24 = mysqli_real_escape_string($conexion, $_POST['optradio24']);
    $optradio25 = mysqli_real_escape_string($conexion, $_POST['optradio25']);
    $optradio26 = mysqli_real_escape_string($conexion, $_POST['optradio26']);
    $optradio27 = mysqli_real_escape_string($conexion, $_POST['optradio27']);
    $optradio28 = mysqli_real_escape_string($conexion, $_POST['optradio28']);
    $optradio29 = mysqli_real_escape_string($conexion, $_POST['optradio29']);
    $optradio30 = mysqli_real_escape_string($conexion, $_POST['optradio30']);
    $optradio31 = mysqli_real_escape_string($conexion, $_POST['optradio31']);  

    //Consulta para obtener el ID de la encuesta a aplicar
    $resultIdEncuesta =$conexion->query("SELECT e.id FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$descEncuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
    $nivelEncuesta = $resultIdEncuesta['id'];

    //Consulta para saber si hay registro de evaluado-evaluador
    if($deptoEmp == $miDepto) {

      $queryRevDup = "SELECT r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id  FROM resultados_posiciones r INNER JOIN empleados e ON r.empleado_evalua_id = e.id INNER JOIN encuestas en ON en.id = r.encuesta_id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE ('{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin) AND (r.empleado_evaluado_id = '{$idEmp}') AND r.encuesta_id = '{$nivelEncuesta}' AND r.empleado_evalua_id = '{$empEvalua}' GROUP BY r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id";

      $resultRevDup = $conexion->query($queryRevDup)->fetch_assoc();
      if($resultRevDup > 0) {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, usted ya evalúo a este empleado.', 'error');
          </script>";       
      }
      else {
        $queryIns = $conexion->query("INSERT INTO resultados_posiciones (encuesta_id, pregunta, resultado, fecha_captura, empleado_evaluado_id, empleado_evalua_id) VALUES 
         ('{$nivelEncuesta}', '1', '{$optradio1}', '{$fecha}', '{$idEmp}', '{$empEvalua}'), 
         ('{$nivelEncuesta}', '2', '{$optradio2}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '3', '{$optradio3}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '4', '{$optradio4}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '5', '{$optradio5}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '6', '{$optradio6}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '7', '{$optradio7}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '8', '{$optradio8}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '9', '{$optradio9}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '10', '{$optradio10}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '11', '{$optradio11}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '12', '{$optradio12}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '13', '{$optradio13}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '14', '{$optradio14}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '15', '{$optradio15}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '16', '{$optradio16}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '17', '{$optradio17}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '18', '{$optradio18}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '19', '{$optradio19}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '20', '{$optradio20}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '21', '{$optradio21}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '22', '{$optradio22}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '23', '{$optradio23}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '24', '{$optradio24}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '25', '{$optradio25}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '26', '{$optradio26}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '27', '{$optradio27}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '28', '{$optradio28}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '29', '{$optradio29}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '30', '{$optradio30}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '31', '{$optradio31}', '{$fecha}', '{$idEmp}', '{$empEvalua}')");
        if($queryIns) {
          echo "<script>
            swal('Encuesta ISSSTESON','Encuesta almacenada con éxito', 'success');
          </script>";        
        }
        else {
          echo "<script>
            swal('Encuesta ISSSTESON', 'Error al almacenar encuesta, intente de nuevo', 'error');
          </script>";        
        }
      }
    }
    else {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, no es posible realizar encuesta, intentelo más.', 'error');
          </script>";        
    }
    $conexion->close();
  }


/*************************************************************************************************
************************************** POSICIONES TECNICAS PROFESIONALES ************************* 
**************************************************************************************************/

  if(isset($_POST['btn_pos_tp60'])) {
    
    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    $fecha = date('Y-m-d');
    $descEncuesta = "tecnicas profesionales";
    $empEvalua = mysqli_real_escape_string($conexion, $_SESSION['usuario']['id']);
    $idEmp = mysqli_real_escape_string($conexion, $_SESSION['idEmp']);
    $deptoEmp = mysqli_real_escape_string($conexion, $_SESSION['deptoEmp']);
    $miDepto = mysqli_real_escape_string($conexion, $_SESSION['usuario']['departamento']);
    $optradio1 = mysqli_real_escape_string($conexion, $_POST['optradio1']); 
    $optradio2 = mysqli_real_escape_string($conexion, $_POST['optradio2']);
    $optradio3 = mysqli_real_escape_string($conexion, $_POST['optradio3']);
    $optradio4 = mysqli_real_escape_string($conexion, $_POST['optradio4']);
    $optradio5 = mysqli_real_escape_string($conexion, $_POST['optradio5']);
    $optradio6 = mysqli_real_escape_string($conexion, $_POST['optradio6']);
    $optradio7 = mysqli_real_escape_string($conexion, $_POST['optradio7']);
    $optradio8 = mysqli_real_escape_string($conexion, $_POST['optradio8']);
    $optradio9 = mysqli_real_escape_string($conexion, $_POST['optradio9']);
    $optradio10 = mysqli_real_escape_string($conexion, $_POST['optradio10']);
    $optradio11 = mysqli_real_escape_string($conexion, $_POST['optradio11']);
    $optradio12 = mysqli_real_escape_string($conexion, $_POST['optradio12']);
    $optradio13 = mysqli_real_escape_string($conexion, $_POST['optradio13']);
    $optradio14 = mysqli_real_escape_string($conexion, $_POST['optradio14']);
    $optradio15 = mysqli_real_escape_string($conexion, $_POST['optradio15']);
    $optradio16 = mysqli_real_escape_string($conexion, $_POST['optradio16']);
    $optradio17 = mysqli_real_escape_string($conexion, $_POST['optradio17']);
    $optradio18 = mysqli_real_escape_string($conexion, $_POST['optradio18']);
    $optradio19 = mysqli_real_escape_string($conexion, $_POST['optradio19']);
    $optradio20 = mysqli_real_escape_string($conexion, $_POST['optradio20']);
    $optradio21 = mysqli_real_escape_string($conexion, $_POST['optradio21']);
    $optradio22 = mysqli_real_escape_string($conexion, $_POST['optradio22']);
    $optradio23 = mysqli_real_escape_string($conexion, $_POST['optradio23']);
    $optradio24 = mysqli_real_escape_string($conexion, $_POST['optradio24']); 
    $optradio25 = mysqli_real_escape_string($conexion, $_POST['optradio25']);
    $optradio26 = mysqli_real_escape_string($conexion, $_POST['optradio26']);
    $optradio27 = mysqli_real_escape_string($conexion, $_POST['optradio27']);
    $optradio28 = mysqli_real_escape_string($conexion, $_POST['optradio28']);
    $optradio29 = mysqli_real_escape_string($conexion, $_POST['optradio29']);
    $optradio30 = mysqli_real_escape_string($conexion, $_POST['optradio30']);
    $optradio31 = mysqli_real_escape_string($conexion, $_POST['optradio31']);
    $optradio32 = mysqli_real_escape_string($conexion, $_POST['optradio32']);
    $optradio33 = mysqli_real_escape_string($conexion, $_POST['optradio33']);
    $optradio34 = mysqli_real_escape_string($conexion, $_POST['optradio34']);
    $optradio35 = mysqli_real_escape_string($conexion, $_POST['optradio35']);
    $optradio36 = mysqli_real_escape_string($conexion, $_POST['optradio36']);
    $optradio37 = mysqli_real_escape_string($conexion, $_POST['optradio37']);
    $optradio38 = mysqli_real_escape_string($conexion, $_POST['optradio38']);
    $optradio39 = mysqli_real_escape_string($conexion, $_POST['optradio39']);
    $optradio40 = mysqli_real_escape_string($conexion, $_POST['optradio40']);
    $optradio41 = mysqli_real_escape_string($conexion, $_POST['optradio41']);
    $optradio42 = mysqli_real_escape_string($conexion, $_POST['optradio42']);
    $optradio43 = mysqli_real_escape_string($conexion, $_POST['optradio43']);
    $optradio44 = mysqli_real_escape_string($conexion, $_POST['optradio44']);
    $optradio45 = mysqli_real_escape_string($conexion, $_POST['optradio45']);
    $optradio46 = mysqli_real_escape_string($conexion, $_POST['optradio46']);   
    $optradio47 = mysqli_real_escape_string($conexion, $_POST['optradio47']);
    $optradio48 = mysqli_real_escape_string($conexion, $_POST['optradio48']);
    $optradio49 = mysqli_real_escape_string($conexion, $_POST['optradio49']);     

    //Consulta para obtener el ID de la encuesta a aplicar
    $resultIdEncuesta =$conexion->query("SELECT e.id FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$descEncuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
    $nivelEncuesta = $resultIdEncuesta['id'];

    //Consulta para saber si hay registro de evaluado-evaluador
    if($deptoEmp == $miDepto) {

      $queryRevDup = "SELECT r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id  FROM resultados_posiciones r INNER JOIN empleados e ON r.empleado_evalua_id = e.id INNER JOIN encuestas en ON en.id = r.encuesta_id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE ('{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin) AND (r.empleado_evaluado_id = '{$idEmp}') AND r.encuesta_id = '{$nivelEncuesta}' AND r.empleado_evalua_id = '{$empEvalua}' GROUP BY r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id";

      $resultRevDup = $conexion->query($queryRevDup)->fetch_assoc();
      if($resultRevDup > 0) {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, usted ya evalúo a este empleado.', 'error');
          </script>";       
      }
      else {
        $queryIns = $conexion->query("INSERT INTO resultados_posiciones (encuesta_id, pregunta, resultado, fecha_captura, empleado_evaluado_id, empleado_evalua_id) VALUES 
         ('{$nivelEncuesta}', '1', '{$optradio1}', '{$fecha}', '{$idEmp}', '{$empEvalua}'), 
         ('{$nivelEncuesta}', '2', '{$optradio2}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '3', '{$optradio3}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '4', '{$optradio4}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '5', '{$optradio5}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '6', '{$optradio6}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '7', '{$optradio7}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '8', '{$optradio8}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '9', '{$optradio9}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '10', '{$optradio10}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '11', '{$optradio11}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '12', '{$optradio12}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '13', '{$optradio13}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '14', '{$optradio14}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '15', '{$optradio15}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '16', '{$optradio16}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '17', '{$optradio17}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '18', '{$optradio18}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '19', '{$optradio19}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '20', '{$optradio20}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '21', '{$optradio21}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '22', '{$optradio22}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '23', '{$optradio23}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '24', '{$optradio24}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '25', '{$optradio25}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '26', '{$optradio26}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '27', '{$optradio27}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '28', '{$optradio28}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '29', '{$optradio29}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '30', '{$optradio30}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '31', '{$optradio31}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '32', '{$optradio32}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '33', '{$optradio33}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '34', '{$optradio34}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '35', '{$optradio35}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '36', '{$optradio36}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '37', '{$optradio37}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '38', '{$optradio38}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '39', '{$optradio39}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '40', '{$optradio40}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '41', '{$optradio41}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '42', '{$optradio42}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '43', '{$optradio43}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '44', '{$optradio44}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '45', '{$optradio45}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '46', '{$optradio46}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '47', '{$optradio47}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '48', '{$optradio48}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
         ('{$nivelEncuesta}', '49', '{$optradio49}', '{$fecha}', '{$idEmp}', '{$empEvalua}')");
        if($queryIns) {
          echo "<script>
              swal('Encuesta ISSSTESON','Encuesta almacenada con éxito', 'success');
            </script>";        
        }
        else {
          echo "<script>
            swal('Encuesta ISSSTESON', 'Error al almacenar encuesta, intente de nuevo', 'error');
          </script>";        
        }
      }
    }
    else {
      echo "<script>
          swal('Encuesta ISSSTESON', 'Error, no es posible realizar encuesta, intentelo más.', 'error');
        </script>";        
    }       
    $conexion->close();
  }
?>