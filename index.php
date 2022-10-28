<!DOCTYPE html>
<html lang="es">
	<head lang="es">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CursoLandia</title>
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

			try{
				//CONEXIÓN AL SERVIDOR
				$servidor = 'localhost';
				$nombreUsuario = 'root';
				$bd = 'pm';

				$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
				if($conexion->connect_error){
					die("Error al conectarse con la base de datos");
				}

				//CONSULTA (TRAE LOS CURSOS DE LOS USUARIOS PRO)
				$sqlDestacados = "SELECT c.Codigo, Titulo, Nombre, fechaInicio, fechaFin FROM cursos c, usuarios u, tipousuarios t WHERE u.Codigo = CodigoProfesor AND Es = t.Codigo AND Tipo = 'Usuario pro'";
				$cursosDestacados = $conexion->query($sqlDestacados);
				$cantDestacados = $cursosDestacados->num_rows;
				

				//CONSULTA (TRAE LOS CURSOS QUE LE PUEDEN INTERESAR AL USUARIO)
				$sqlIntereses = "SELECT c.Codigo,Titulo,fechaInicio,fechaFin,Nombre FROM cursos c, usuarios u, etiquetas e, intereses i WHERE CodigoProfesor = u.Codigo AND i.CodigoUsuario = '$codigo' AND c.Codigo = e.CodigoCurso AND Interes = Etiqueta";
				$cursosIntereses = $conexion->query($sqlIntereses);
				$cantIntereses = $cursosIntereses->num_rows;

				

			}catch(Exeption $e){
				echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
			}
		?>

		
		<div class="container mt-5 mb-5">
			<?php
				if(isset($_SESSION['Codigo'])){
					$codigo = $_SESSION['Codigo'];
					$sqlTipo = "SELECT Tipo FROM usuarios u, tipousuarios t WHERE Es = t.Codigo AND u.Codigo = '$codigo'";
					$tipo = $conexion->query($sqlTipo);
					$tipo = $tipo->fetch_assoc();
					if($tipo['Tipo'] == "Usuario gratuito"){
						include 'tiposCuentas.html';
					}
				}

				$conexion->close();
			?>
			<h2 class="text-principal mt-5 mb-4">Cursos destacados</h2>
			<?php
				if($cantDestacados > 0){
					echo "<div class=\"row\">";
					while($curso = $cursosDestacados->fetch_assoc()) {
						echo "<div class=\"col-sm-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"max-width: 18rem;\"><div class=\"card-header text-bg-secondary\">".$curso['Titulo']."</div><div class=\"card-body text-bg-light\"><p class=\"card-text\">Profesor: ".$curso['Nombre']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['fechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['fechaFin']."</p></div></a></div>";
					}
					echo "</div>";
				}else{
					echo "<p class=\"text-muted\">Aun no tenemos cursos para ofrecerte.</p>";
				}
			?>
		</div>

		<div class="container">
			<h2 class="text-principal mt-5 mb-4">Cursos que te podrían interesar</h2>
			<?php

				if($cantIntereses > 0){
					echo "<div class=\"row\">";
					while($curso = $cursosIntereses->fetch_assoc()){
						echo "<div class=\"col-sm-3\"><a href=\"curso.php?curso=".$curso['Codigo']."\" class=\"card border-principal mb-3 nav-link\" style=\"max-width: 18rem;\"><div class=\"card-header text-bg-secondary\">".$curso['Titulo']."</div><div class=\"card-body text-bg-light\"><p class=\"card-text\">Profesor: ".$curso['Nombre']."</p><p class=\"card-text\">Fecha de inicio: ".$curso['fechaInicio']."</p><p class=\"card-text\">Fecha de fin del curso: ".$curso['fechaFin']."</p></div></a></div>";
					}
					echo "</div>";
				}else{
					echo "<p class=\"text-muted\">Aun no tenemos cursos para ofrecerte.</p>";
				}
			?>
		</div>

		<?php
			include 'footer.html';
		?>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
		<script src="js/buscador.js"></script>
	</body>
</html>