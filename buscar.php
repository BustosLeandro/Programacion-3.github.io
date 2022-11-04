<?php
	$buscador = $_GET['buscador'];
	$servidor = 'localhost';
	$nombreUsuario = 'root';
	$bd = 'pm';

	$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
	if($conexion->connect_error){
		die("Error al conectarse con la base de datos");
	}
	
	$sqlTitulo = "SELECT c.Codigo, Titulo, FechaInicio, FechaFin, Imagen, Nombre AS Profesor FROM cursos c, usuarios u WHERE CodigoProfesor = u.Codigo AND titulo LIKE '%$buscador%'";
	$resultadoTitulos = $conexion->query($sqlTitulo);

	$sqlEtiquetas = "SELECT c.Codigo, Titulo, FechaInicio, FechaFin, Imagen, Nombre AS Profesor FROM cursos c, usuarios u, etiquetas e, tags t WHERE CodigoProfesor = u.Codigo AND CodigoCurso = c.Codigo AND Etiqueta = t.Codigo AND Tag LIKE '%$buscador%'";
	$resultadoEtiquetas = $conexion->query($sqlEtiquetas);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $buscador ?> - Buscar</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php
			session_start();
			if(isset($_SESSION['Codigo'])){
				include 'cabecera-logeado.php';
				$codigo = $_SESSION['Codigo'];
			}else{
				include 'cabecera-visitante.html';
				$codigo = 0;
			}
			include 'buscador.html';
		?>
		<div class="container mt-2">
			<div class="row">
				<div class="col-12">
					<h2><?php echo $buscador ?>...</h2>
					<hr class="border border-secondary border-2 opacity-75">
				</div>
				<?php
					if($resultadoTitulos && $resultadoTitulos->num_rows > 0){
						while($curso = $resultadoTitulos->fetch_assoc()){
							if($curso['Imagen'] == ""){
								echo "<div class=\"col-sm-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"imagenes/cursos/default.png\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Profesor: ".$curso['Profesor']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p></div></a></div>";
							}else{
								echo "<div class=\"col-sm-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"".$curso['Imagen']."\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Profesor: ".$curso['Profesor']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p></div></a></div>";
							}
						}
					}else{
						if($resultadoEtiquetas && $resultadoEtiquetas->num_rows > 0){
							while($curso = $resultadoEtiquetas->fetch_assoc()){
								if($curso['Imagen'] == ""){
									echo "<div class=\"col-sm-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"imagenes/cursos/default.png\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Profesor: ".$curso['Profesor']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p></div></a></div>";
								}else{
									echo "<div class=\"col-sm-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"width: 18rem;\"><img src=\"".$curso['Imagen']."\" class=\"card-img-top img-curso\" alt=\"Imagen del curso\"><div class=\"card-body\"><h4 class=\"card-title\">".$curso['Titulo']."</h4><p class=\"card-text\">Profesor: ".$curso['Profesor']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['FechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['FechaFin']."</p></div></a></div>";
								}
							}
						}else{
							echo "<div class=\"alert text-center\" role=\"alert\">No hemos encontrado cursos para: ".$buscador.".</div>";
						}
					}

				?>
			</div>
		</div>
		<?php
			$conexion->close();
			include 'footer.html';
		?>

		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>