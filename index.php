<?php 
  
  session_start();
  date_default_timezone_set('America/Los_Angeles');
  $_SESSION['last_login_timestamp'] = time();

?>
<!DOCTYPE html>
<html lang="ES">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/estilos.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Encode+Sans+Expanded" rel="stylesheet">
  <title>Evaluación de desempeño ISSSTESON</title>
  <style>
    .container {
      background-color: #F4F4F4;
    }
  </style>
</head>
<body>
  <div class="error">
    <span>Datos de ingreso no validos. Intentelo de nuevo</span>
  </div>
  <section class="container"><br>
    <div align="center">
      <img src="images/isssteson.png" width="20%" alt="">
    </div>
    <h3 class="text-center">Evaluación del desempeño laboral</h3>
    <p class="text-center">
      Favor de ingresar sus datos para iniciar sesion.
    </p>
    <form id="formLogin" action="" autocomplete="off">
      <div class="form-group">
        <label for="usuario">Numero de empleado:</label>
        <input id="user" type="text" class="form-control" placeholder="Ejemplo: 10225" name="user" pattern="[A-Za-z0-9 -]{1,15}" required="">
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input id="pass" type="password" class="form-control" placeholder="Ingrese contraseña" name="pass" pattern="[A-Za-z0-9 -]{1,15}" required="">
      </div>
      <button type="submit" id="botonlogin" name="botoncitologin" class="btn btn-danger">Iniciar Sesión</button>
    </form><br>
  </section>
  <script type="text/javascript" src="js/main.js"></script>  
</body>
</html>
