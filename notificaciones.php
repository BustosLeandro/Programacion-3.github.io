<?php
	session_start();
	if(!isset($_SESSION['Codigo'])){
		header("Location:index.php");
	}
	$codigo = $_SESSION['Codigo'];
	$servidor = "localhost";
	$nombreUsuario = "root";
	$bd = "pm";

	$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

	$sqlNotificaciones = "SELECT n.Codigo, n.Notificacion, n.Vista, n.Fecha FROM recibe r,usuarios u, notificaciones n WHERE r.CodigoUsuario = u.Codigo AND u.Codigo = '$codigo' AND r.CodigoNotificacion = n.Codigo";
	$tablaNotificaciones = $conexion->query($sqlNotificaciones);	
	$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Notificaciones</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php include 'cabecera-logeado.php';?>
		<h2 class="text-principal mt-5 mb-4">Notificiones:</h2>
		<div class="container-fluid">
			<div class="m-0 row justify-content-center align-items-center">
				<div class="col-8">
					<table class="table">
					  	<thead>
					    	<tr>
					      		<th scope="col">Notificacion</th>
					      		<th scope="col">Fecha</th>
					      		<th scope="col">Vista</th>
					    	</tr>
					  	</thead>
					  	<tbody>
					  		<?php
					  			while($notificacion = $tablaNotificaciones->fetch_assoc()){
					  				echo "<tr><td>".$notificacion['Notificacion']."</td><td>".$notificacion['Fecha']."</td><td>";
				  					if($notificacion['Vista'] == 1){
				  						echo "<i class=\"bi bi-eye-fill\"></i></td></tr>";
				  					}else{
				  						echo "<i class=\"bi bi-eye\"></i></td><td><a href=\"notificaciones.php?nCodigo=".$notificacion['Codigo']."\">Marcar como vista</a></td></tr>";
				  					}
					  			}
					  		?>
				 	 	</tbody>
					</table>
				</div>				
			</div>
		</div>
		

		<?php include 'footer.html';?>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>

<?php
	if(isset($_GET['nCodigo'])){
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

		$codigoNotificacion	= $_GET['nCodigo'];
		$vista = "UPDATE notificaciones SET Vista = '1' WHERE Codigo = '$codigoNotificacion'";
		$vista = $conexion->query($vista);
		if($conexion->affected_rows == 0){
			echo "<script>alert(\"Error al marcar como vista, intentelo nuevamente\")</script>";
		}
		$conexion->close();
		echo "<script type=\"text/javascript\">window.location.href = \"notificaciones.php\";</script>";
	}
?>