<?php 
  
  session_start();
  date_default_timezone_set('America/Los_Angeles');
  if(isset($_SESSION['usuario'])) {
    if($_SESSION['usuario']['tipo'] != 'empleado') {
      header('Location: ../jefe/');
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
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="shortcut icon" href="view/favicon.ico" type="image/x-icon"/>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../css/estilos.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Encode+Sans+Expanded" rel="stylesheet">
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
    <h2 align="center">evaluacion de desempeño isssteson</h2><hr>
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
        <a class="dropdown-item" href="encuestas.php">Realizar Encuesta</a>
      </div>
    </li>      
  </ul>
<!-- Tab panes -->
  <div class="container"><br>
    <h4>Instrucciones</h4><br>
    <p>
      Con la intención de fortalecer nuestro sistema de evaluación de desempeño e incentivar a los servidores públicos para que sigan laborando con responsabilidad, hemos integrado este documento con los lineamientos de un nuevo modelo de evaluación por objetivos y competencias 360°, que permite desarrollar las capacidades de nuestros servidores y tener una medición más certera en cuanto al logro de nuestros objetivos como institución.
    </p>
    <p>
      Esperando este esfuerzo sea para beneficio del instituto, trabajadores, sindicato y sociedad.
    </p>
    <p>
      <strong>¿Qué es una evaluación de desempeño 360?</strong>
    </p>
    <p>
      Es un sistema de evaluación de desempeño sofisticado utilizado en general por grandes corporaciones. La persona es evaluada por todo su entorno: jefes, pares y subordinados. Por ejemplo, a una persona la evalúa su jefe <i> - como su esquema tradicional -</i> y además el jefe de jefe, dos o tres pares y dos o tres subordinados. Puede incluir a personas como usuarios de sus servicios. Cuanto mayor sea el número de evaluadores, mayor será el grado de fiabilidad del sistema.
    </p>
  </div>    

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
  
</body>
</html>
