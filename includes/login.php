	<?php

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
		require_once('conexion.php');
		$conexion = Conexion::conn();
		
		session_start();
	
		$user = mysqli_real_escape_string($conexion, $_POST['user']);
		$pass = mysqli_real_escape_string($conexion, $_POST['pass']);

		$newQ = $conexion->query("SELECT * FROM empleados WHERE num_e = '{$user}' AND pass = '{$pass}'");
		$result= mysqli_num_rows($newQ);
		$row = mysqli_fetch_array($newQ, MYSQLI_ASSOC);

		if($result == 1) {
			$tipo = $row['tipo'];
			$_SESSION['usuario'] = $row;
			echo json_encode(array('error'=>false, 'tipo'=>$tipo));
		}
		else {
			echo json_encode(array('error'=>true));
		}

		$newQ->close();
		$conexion->close();
	}
?>