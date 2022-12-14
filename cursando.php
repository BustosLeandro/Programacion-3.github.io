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
		if($conexion->connect_error){
			die("Error al conectarse con la base de datos");
		}

		$sqlCursos = "SELECT c.Codigo, pro.Nombre AS Profesor, Titulo, FechaInicio, FechaFin, Imagen FROM cursos c, usuarios alu, usuarios pro, cursa ca WHERE alu.COdigo = '$codigo' AND alu.Codigo = ca.CodigoEstudiante AND ca.CodigoCurso = c.Codigo AND c.CodigoProfesor = pro.Codigo";

		$cursos = $conexion->query($sqlCursos);

	}catch(Exeption $e){
		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Cursando</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php
			include 'cabecera-logeado.php';
		?>
		<h2 class="text-principal mt-5 mb-4">Estas cursando:</h2>
		<div class="container">
			<?php
				if($cursos->num_rows > 0){
					echo "<div class=\"row\">";
					while($curso = $cursos->fetch_assoc()){
						if($curso['Imagen'] == ""){
								echo "<div class=\"col-md-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"imagenes/cursos/default.png\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Profesor: ".$curso['Profesor']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p></div></a></div>";
							}else{
								echo "<div class=\"col-md-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"".$curso['Imagen']."\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Profesor: ".$curso['Profesor']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p></div></a></div>";
							}
					}
					echo "</div>";
				}else{
					echo "<p class=\"text-muted\">Aun no estas inscripto a ningún curso.</p>";
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