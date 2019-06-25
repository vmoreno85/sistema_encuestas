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

    //Consulta para saber si empleado a evaluar es subordinado de evaluador
    $resultSubordinado = $conexion->query("SELECT * FROM empleado_encargado WHERE id_encargado = '{$miId}' AND id_empleado = '{$idEmp}'")->fetch_assoc();

    if($resultSubordinado > 0) {
      if($deptoEmp == $miDepto) {
        require_once 'ob40.php';
      }
      else {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, el empleado que desea evaluar no pertenece a su departamento.', 'error');
          </script>";         
      }
    }
    else {
      echo "<script>
          swal('Encuesta ISSSTESON', 'Error, no es posible evaluar a empleado que no esta a su cargo.', 'error');
        </script>";      
    }
    $conexion->close();
  }
?>
    </div><br>    
    <hr>
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
<script type="text/javascript">
/* Funcion suma. */
  function SumarAutomatico (valor) {
      var TotalSuma = 0;  
      valor = parseInt(valor); // Convertir a numero entero (número).
      TotalSuma = document.getElementById('MiTotal').innerHTML;
      // Valida y pone en cero "0".
      TotalSuma = (TotalSuma == null || TotalSuma == undefined || TotalSuma == "") ? 0 : TotalSuma;
      /* Variable genrando la suma. */
      TotalSuma = (parseInt(TotalSuma) + parseInt(valor));
        // Escribir el resultado en una etiqueta "span".
      document.getElementById('MiTotal').innerHTML = TotalSuma;
  }

  function validarTotal(valor) {
    valor = parseInt(valor);
    total = document.getElementById('MiTotal');

    if(total < 99 && total > 101) {
      alert("Ponderación total debe ser igual a 100");
    }
  }
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

</body>
</html>

<?php  

  if(isset($_POST['btn_pos_op40'])) {
    
    include_once '../../includes/conexion.php';
    $conexion = Conexion::conn();
    $fecha = date('Y-m-d');
    $descEncuesta = "objetivos";
    $empEvalua = mysqli_real_escape_string($conexion, $_SESSION['usuario']['id']);
    $idEmp = mysqli_real_escape_string($conexion, $_SESSION['idEmp']);
    $deptoEmp = mysqli_real_escape_string($conexion, $_SESSION['deptoEmp']);
    $miDepto = mysqli_real_escape_string($conexion, $_SESSION['usuario']['departamento']);
    $miTipo = mysqli_real_escape_string($conexion, $_SESSION['usuario']['tipo']);
    $objetivo1 = mysqli_real_escape_string($conexion, $_POST['objetivo1']); 
    $ponderacion1 = mysqli_real_escape_string($conexion, $_POST['ponderacion1']);
    $consecucion1 = mysqli_real_escape_string($conexion, $_POST['consecucion1']);
    $comentario1 = mysqli_real_escape_string($conexion, $_POST['comentario1']);
    $objetivo2 = mysqli_real_escape_string($conexion, $_POST['objetivo2']); 
    $ponderacion2 = mysqli_real_escape_string($conexion, $_POST['ponderacion2']);
    $consecucion2 = mysqli_real_escape_string($conexion, $_POST['consecucion2']);
    $comentario2 = mysqli_real_escape_string($conexion, $_POST['comentario2']);
    $objetivo3 = mysqli_real_escape_string($conexion, $_POST['objetivo3']); 
    $ponderacion3 = mysqli_real_escape_string($conexion, $_POST['ponderacion3']);
    $consecucion3 = mysqli_real_escape_string($conexion, $_POST['consecucion3']);
    $comentario3 = mysqli_real_escape_string($conexion, $_POST['comentario3']);
    $objetivo4 = mysqli_real_escape_string($conexion, $_POST['objetivo4']); 
    $ponderacion4 = mysqli_real_escape_string($conexion, $_POST['ponderacion4']);
    $consecucion4 = mysqli_real_escape_string($conexion, $_POST['consecucion4']);
    $comentario4 = mysqli_real_escape_string($conexion, $_POST['comentario4']);    
    $objetivo5 = mysqli_real_escape_string($conexion, $_POST['objetivo5']); 
    $ponderacion5 = mysqli_real_escape_string($conexion, $_POST['ponderacion5']);
    $consecucion5 = mysqli_real_escape_string($conexion, $_POST['consecucion5']);
    $comentario5 = mysqli_real_escape_string($conexion, $_POST['comentario5']);            

    //Consulta para obtener el ID de la encuesta a aplicar
    $resultIdEncuesta =$conexion->query("SELECT e.id FROM encuestas e INNER JOIN periodo_encuesta p ON e.periodo_encuesta_id = p.id WHERE e.descripcion ='{$descEncuesta}' AND '{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin")->fetch_assoc();
    $nivelEncuesta = $resultIdEncuesta['id'];

    if($deptoEmp == $miDepto && $miTipo == 'jefe' ) {

      $queryRevDup = "SELECT r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id  FROM resultados_objetivos r INNER JOIN empleados e ON r.empleado_evalua_id = e.id INNER JOIN encuestas en ON en.id = r.encuesta_id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE ('{$fecha}' BETWEEN p.fecha_inicio AND p.fecha_fin) AND (r.empleado_evaluado_id = '{$idEmp}') AND r.encuesta_id = '{$nivelEncuesta}' AND r.empleado_evalua_id = '{$empEvalua}' GROUP BY r.empleado_evaluado_id, r.empleado_evalua_id, r.encuesta_id";

      $resultRevDup = $conexion->query($queryRevDup)->fetch_assoc();
      if($resultRevDup > 0) {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, usted ya asigno objetivos a este empleado.', 'error');
          </script>";       
      }
      else {
        $queryIns = $conexion->query("INSERT INTO resultados_objetivos (encuesta_id, objetivo, ponderacion, consecucion, comentarios, fecha_captura, empleado_evaluado_id, empleado_evalua_id) VALUES 
          ('{$nivelEncuesta}', '{$objetivo1}', '{$ponderacion1}', '{$consecucion1}', '{$comentario1}', '{$fecha}', '{$idEmp}', '{$empEvalua}'), 
          ('{$nivelEncuesta}', '{$objetivo2}', '{$ponderacion2}', '{$consecucion2}', '{$comentario2}', '{$fecha}', '{$idEmp}', '{$empEvalua}'),
          ('{$nivelEncuesta}', '{$objetivo3}', '{$ponderacion3}', '{$consecucion3}', '{$comentario3}', '{$fecha}', '{$idEmp}', '{$empEvalua}'), 
          ('{$nivelEncuesta}', '{$objetivo4}', '{$ponderacion4}', '{$consecucion4}', '{$comentario4}', '{$fecha}', '{$idEmp}', '{$empEvalua}'), 
          ('{$nivelEncuesta}', '{$objetivo5}', '{$ponderacion5}', '{$consecucion5}', '{$comentario5}', '{$fecha}', '{$idEmp}', '{$empEvalua}')");
        
        if($queryIns) {
          echo "<script>
              swal('Encuesta ISSSTESON','Encuesta almacenada con éxito.', 'success');
            </script>";        
          }
        else {
          echo "<script>
              swal('Encuesta ISSSTESON', 'Error al almacenar encuesta, intente de nuevo.', 'error');
            </script>";        
        }
      }
    }
    else {
      echo "<script>
          swal('Encuesta ISSSTESON', 'Error, usted no tiene privilegios para realizar esta encuesta.', 'error');
        </script>"; 
    }
    $conexion->close();
  }

?>
