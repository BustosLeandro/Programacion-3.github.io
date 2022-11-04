<?php
	session_start();
	if(!isset($_SESSION['Codigo'])){
		header("Location: index.php");
	}

	$servidor = 'localhost';
	$nombreUsuario = 'root';
	$bd = 'pm';
	$codigo = $_SESSION['Codigo'];

	try{
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		
		$cursos = "SELECT c.Codigo, Costo, Titulo, FechaInicio, FechaFin, Imagen FROM usuarios u, cursos c WHERE CodigoProfesor = u.Codigo AND u.Codigo = '$codigo'";
		
		$cursos = $conexion->query($cursos); 

		$conexion->close();
	}catch(Exeption $e){
		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Dictando</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php
			include 'cabecera-logeado.php';
		?>
		<div class="card-body d-flex justify-content-between align-items-center">
			<h2 class="text-principal mt-5 mb-4">Tus cursos:</h2>
			<a href="crearCurso.php" class="btn btn-primary btn-sm me-5">Crear curso</a>
		</div>
		<div class="container">
			<?php
				if($cursos->num_rows > 0){
					echo "<div class=\"row\">";
					while($curso = $cursos->fetch_assoc()){
						if($curso['Imagen'] == ""){
							echo "<a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"col-sm-3  me-4 card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"imagenes/cursos/default.png\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p><p class=\"card-text\">Costo: ".$curso['Costo']."</p></div></a>";
						}else{
							echo "<a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"col-sm-3 me-4 card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"".$curso['Imagen']."\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p><p class=\"card-text\">Costo: ".$curso['Costo']."</p></div></a>";
						}
					}
					echo "</div>";
				}else{
					echo "<p class=\"text-muted\">Aun no has creado ningún curso.</p>";
				}
			?>
		</div>
		<?php
			include 'footer.html';
		?>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>