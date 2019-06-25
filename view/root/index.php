<?php 
  
  session_start();
  date_default_timezone_set('America/Los_Angeles');
  if(isset($_SESSION['usuario'])) {
    if($_SESSION['usuario']['tipo'] != 'root') {
      header('Location: ../../includes/logout.php');
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
    <h2 align="center">panel de administración</h2><hr>
    <p align="right" style="font-size: .9em;">
      Sesión de: 

      <?php 
        if((time() - $_SESSION['last_login_timestamp']) > 1500) {

          header("Location: ../../includes/logout.php");
        }

        else {

          $_SESSION['last_login_timestamp'] = time();
          echo "<span class='text-success'>" .$_SESSION['usuario']['appaterno'] . "</span> | " . date("Y/m/d") . " | <a href='../../includes/logout.php'>Salir</a>";
        } 
      ?>
  </p>    
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="index.php">Configuración</a>
    </li>
  </ul>
<!-- Tab panes -->
  <div class="container"><br>
    <h4>periodos de evaluación</h4><br>
    <p>
      Las encuestas que ofrece el sistema, se manejan por periodos trimestrales, por lo tanto, es importante crear nuevos periodos para volver a evaluar a los empleados, en caso de no darse de alta nuevos periodos, el sistema no permitirá la captura de nuevas evaluaciones por parte del personal.
    </p>
    <p>
      <strong>¿Cómo crear nuevos periodos de evaluación?</strong>
    </p>
    <table class="table table-bordered" style="font-size: .9em; text-align: center;">
      <thead>
        <tr>
          <th>Año de evaluación</th>
          <th>Número periodo</th>
          <th>Rango de fecha (trimestre)</th>
          <th>Posiciones Operativas</th>
          <th>Encuesta Objetivos</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo date("Y"); ?></td>
          <td>1</td>
          <td>01 de Enero al 31 de Marzo <?php echo date("Y"); ?></td>
          <th>60% (sugerido)</th>
          <th>40% (sugerido)</th>
        </tr>
        <tr>
          <td><?php echo date("Y"); ?></td>
          <td>2</td>
          <td>01 de Abril al 30 de Junio <?php echo date("Y"); ?></td>
          <th>60% (sugerido)</th>
          <th>40% (sugerido)</th>
        </tr>
        <tr>
          <td><?php echo date("Y"); ?></td>
          <td>3</td>
          <td>01 de Julio al 30 de Septiembre <?php echo date("Y"); ?></td>
           <th>60% (sugerido)</th>         
           <th>40% (sugerido)</th>
        </tr>
        <tr>
          <td><?php echo date("Y"); ?></td>
          <td>4</td>
          <td>01 de Octubre al 31 de Diciembre <?php echo date("Y"); ?></td>
        <th>60% (sugerido)</th>
        <th>40% (sugerido)</th>
        </tr>        
      </tbody>
    </table><br>
    <p>
      En la tabla anterior, se muestran los rangos de fechas para cada uno de los periodos, al crear un nuevo periodo, el sistema le pedirá la fecha de inicio y la fecha final, las cuales deben cumplir con los criterios de la tabla. En caso de no evaluar un determinado periodo, puede omitir la creación del periodo y crear el siguiente, aunque, es importante recordar, que si la fecha de la encuesta a capturar no cumple los requisitos de rango de fecha, no podrá realizarla. Actualmente, se encuentran registrados los siguientes periodos para el año <?php echo date('Y'); ?>:
    </p>
    <div style="background-color: #EEE;">
      <?php  
        include_once '../../includes/conexion.php';
        $conexion = Conexion::conn();

        $fecha = date('Y-m-d');
        $fechaYear = date('Y');

        $resultPeriodos = $conexion->query("SELECT *, YEAR(fecha_inicio) FROM periodo_encuesta WHERE YEAR(fecha_inicio) = '{$fechaYear}'");
        echo '<p style="font-size: 1.2em;">';
        while($row = $resultPeriodos -> fetch_array(MYSQLI_ASSOC)) {
          echo 'Periodo ' . $row['periodo'] . ' | Fecha de inicio: <span style="color: #990000;"> ' . $row['fecha_inicio'] . '</span> | Fecha final: <span style="color: #990000;">' . $row['fecha_fin'] . '</span><br>';
        }
        echo "</p>";
      ?>
    </div>
    <p>
      Si desea crear un nuevo periodo para el año en curso, puede especificar los rangos de fecha segun lo establecido en la tabla anterior.
    </p>
    <hr>
    <div class="ac-container">
      <div><input id="ac-1" name="accordion-1" type="checkbox" />
          <label for="ac-1">Crear periodo</label>
          <article class="ac-xl">
            <form action="" method="POST" class="form-horizontal" name="genera_periodo" style="font-size: .9em;">
              <div class="form-check-inline">
                <label class="form-check-label" for="periodo">
                  Fecha Inicio: 
                  <select name="periodo" required>
                    <option value="1">Enero - Marzo</option>
                    <option value="2">Abril - Junio</option>
                    <option value="3">Julio - Septiembre</option>
                    <option value="4">Octubre - Diciembre</option>
                  </select>
                </label>
              </div>
              <div class="form-check-inline">
                <label for="valorPosiciones" class="form-check-inline">
                  Valor encuesta posiciones:
                  <input type="text" name="valorPosiciones" placeholder="- valor de 0 a 100" maxlength="2" value="60" required>
                </label>
              </div>
              <div class="form-check-inline">
                <label for="valorObjetivos" class="form-check-inline">
                  Valor encuesta objetivos:
                  <input type="text" name="valorObjetivos" placeholder="- valor de 0 a 100" maxlength="2" value="40" required>
                </label>
              </div>              
              <div class="form-check-inline">
                <label>
                  <input type="submit" name="generarP" class="btn btn-info" value="Nuevo Periodo">
                </label>                 
              </div>
            </form><br>
          </article>
      </div>
    </div><br><hr>
  
    <h4>Generación de reportes</h4><br>
    <p>
      Si desea puede descargar un archivo de Excel con todos los empleados evaluados, este documento incluye tanto empleados y jefes. Puede elegir entre las dos opciones de generacion de archivo de Excel. 
    </p>  
    <div class="ac-container">
      <div><input id="ac-2" name="accordion-2" type="checkbox" />
          <label for="ac-2">Crear Reporte</label>
          <article class="ac-xl">
            <form action="" method="POST" class="form-horizontal" name="genera_periodo" style="font-size: .9em;">
              <div class="form-check-inline">
              <div class="form-check-inline">
                <label class="form-check-label" for="departamento">
                  Año:
                  <select name="year" required>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                  </select>
                </label>
              </div>                  
                <label class="form-check-label" for="periodo">
                  Periodo: 
                  <select name="periodo" required>
                    <option value="1">Enero - Marzo</option>
                    <option value="2">Abril - Junio</option>
                    <option value="3">Julio - Septiembre</option>
                    <option value="4">Octubre - Diciembre</option>
                  </select>
                </label>
              </div>    
              <div class="form-check-inline">
                <label class="form-check-label" for="departamento">
                  Departamento:
                  <select name="departamento" required>
                    <option value="recursos humanos">Recursos Humanos</option>
                    <option value="sistemas">Sistemas</option>
                    <option value="administracion">Administración</option>
                    <option value="intendencia">Intendencía</option>
                    <option value="todos">Todos</option>
                  </select>
                </label>
              </div>                         
              <div class="form-check-inline">
                <label>
                  <input type="submit" name="generarR" class="btn btn-info" value="Generar Reporte">
                </label>                 
              </div>
            </form><br>
          </article>
      </div>
    </div>
    <div><br>
    <?php  
      if(isset($_POST['generarR'])) {
        include_once '../../includes/conexion.php';
        $conexion = Conexion::conn();

        $departamento = mysqli_real_escape_string($conexion, $_POST['departamento']);
        $periodo = mysqli_real_escape_string($conexion, $_POST['periodo']);
        $year = mysqli_real_escape_string($conexion, $_POST['year']);   

        $resultConsulta = $conexion->query("SELECT en.* FROM empleados e INNER JOIN resultados_posiciones r ON r.empleado_evaluado_id = e.id INNER JOIN encuestas en ON en.id = r.encuesta_id INNER JOIN periodo_encuesta p ON p.id = en.periodo_encuesta_id WHERE e.departamento = '{$departamento}' GROUP BY en.id")->fetch_array(MYSQLI_ASSOC);

        if($resultConsulta > 0) {
          $url = mysqli_real_escape_string($conexion, 'reporteExcel.php?departamento=' . $departamento . '&periodo=' . $periodo . '&year=' . $year);

          echo '<p>
            Puede descargar un reporte en Excel dando clic en el siguiente enlace. <a href="'.$url.'" target="_blank">DESCARGAR ARCHIVO</a>
          </p>';          
        }
        else {
          echo "<script>
              swal('Encuesta ISSSTESON', 'Error, No existen registros almacenados para el departamento seleccionado.', 'error');
            </script>";                    
        }

        $conexion->close();
      }
    ?>
    </div><br><hr>    
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

  <?php  
    if(isset($_POST['generarP'])) {

      require_once '../../includes/conexion.php';
      $conexion = Conexion::conn();

      $nuevoPeriodo = mysqli_real_escape_string($conexion, $_POST['periodo']);
      $valorPosiciones = mysqli_real_escape_string($conexion, $_POST['valorPosiciones']);
      $valorObjetivos = mysqli_real_escape_string($conexion, $_POST['valorObjetivos']);

      if($nuevoPeriodo == 1) {
        $fechaInicio = date("Y") . '-01-01';
        $fechaFin = date("Y") . '-03-31';
        $descripcion = "enero a marzo";
      }
      elseif($nuevoPeriodo == 2) {
        $fechaInicio = date("Y") . '-04-01';
        $fechaFin = date("Y") . '-06-30';
        $descripcion = "abril a junio";        
      }
      elseif($nuevoPeriodo == 3) {
        $fechaInicio = date("Y") . '-07-01';
        $fechaFin = date("Y") . '-09-30'; 
        $descripcion = "julio a septiembre";               
      }
      elseif($nuevoPeriodo == 4) {
        $fechaInicio = date("Y") . '-10-01';
        $fechaFin = date("Y") . '-12-31'; 
        $descripcion = "octubre a diciembre";      
      }
      //$fechaFin = mysqli_real_escape_string($conexion, $_POST['periodoFin']);

      //Validar que el periodo que se desea registrar no exista actualmente en la BD
      $resultValidarP = $conexion->query("SELECT * FROM periodo_encuesta WHERE periodo = '{$nuevoPeriodo}' AND fecha_inicio = '{$fechaInicio}' AND fecha_fin = '{$fechaFin}'")->fetch_assoc();
      $resultValidarE = $conexion->query("SELECT * FROM encuestas WHERE periodo_encuesta_id = '{$nuevoPeriodo}'")->fetch_assoc();
      if($resultValidarP > 0 && $resultValidarE > 0) {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error, No puede registrar un periodo ya existente.', 'error');
          </script>";        
        }       
      else {
        /*
        //Obtener el ultimo registro
        $resultObtenerP = $conexion->query("SELECT MAX(id) AS id FROM periodo_encuesta")->fetch_assoc();
        $iD = $resultObtenerP['id'];
        
        $resultObtenerPeriodo = $conexion->query("SELECT periodo FROM periodo_encuesta WHERE id = '{$iD}'")->fetch_assoc();
        $periodo = $resultObtenerPeriodo['periodo'];

        if($periodo == 1) {
          $periodo++;
        }
        else if($periodo == 2) {
          $periodo++;
        }
        else if($periodo == 3) {
          $periodo++;
        }
        else if($periodo == 4) {
          $periodo -=3;
        }
        */
        $queryIns = $conexion->query("INSERT INTO periodo_encuesta (periodo, descripcion, fecha_inicio, fecha_fin) 
          VALUES ('{$nuevoPeriodo}', '{$descripcion}', '{$fechaInicio}', '{$fechaFin}')");

        $id=mysqli_insert_id($conexion);

        $queryInsE = $conexion->query("INSERT INTO encuestas (periodo_encuesta_id, descripcion, valor_encuesta, nivel_min, nivel_max) VALUES 
          ('{$id}', 'operativas', '{$valorPosiciones}', 1, 3), 
          ('{$id}', 'tecnicas especializadas', '{$valorPosiciones}', 4, 5), 
          ('{$id}', 'tecnicas profesionales', '{$valorPosiciones}', 6, 9), 
          ('{$id}', 'objetivos', '{$valorObjetivos}', 5, 9)");

        if($queryIns && $queryInsE) {
          echo "<script>
              swal('Encuesta ISSSTESON','Periodo almacenado con éxito', 'success');
            </script>";        
        }
        else {
        echo "<script>
            swal('Encuesta ISSSTESON', 'Error al almacenar periodo, intente de nuevo', 'error');
          </script>";        
        }
      }
      $conexion->close();   
    }
  ?>
  
</body>
</html>
