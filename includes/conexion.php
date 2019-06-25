<?php  

class Conexion {

	public static function conn() {
		
		$host = 'localhost';
		$user = 'root';
		$pass = '';
		$db = 'dbe_isssteson';
		/*
		$host = 'localhost';
		$user = 'sitcecyt_issste';
		$pass = 'a1b2c3d4e5/*-+';
		$db = 'sitcecyt_isssteson';
		*/
		$conexion = new mysqli($host, $user, $pass, $db);
		mysqli_set_charset($conexion, 'UTF8');

		if($conexion -> connect_errno) {

			die("Error al conectar" . $conexion -> mysqli_connect_errno() . ")" . $conexion -> mysqli_connect_errno());
		}		

		return $conexion;
	}
}

?>