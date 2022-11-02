<?php
	$servidor = 'localhost';
	$nombreUsuario = 'root';
	$bd = 'pm';

	$prueba = "SELECT FechaFin FROM cursos WHERE Codigo = '2'";
	$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

	$prueba = $conexion->query($prueba);
	$prueba = $prueba->fetch_assoc();
	$fechaFin = $prueba['FechaFin'];
	
	$fechaActual = strtotime(date('Y/m/d'));
	$fechaFin = strtotime($fechaFin);

	echo "Fecha actual:  ".$fechaActual;
	echo "<br>Fecha de fin del curso:  ".$fechaFin;

	if($fechaFin < $fechaActual){
		echo "<br><br>EL LIMITE DE LA FECHA YA HA PASADO";
	}
?>