<?php
	session_start();
	if(!isset($_SESSION['Codigo'])){
		header("Location: index.php");
	}
	$codigo = $_SESSION['Codigo'];
	$codigoPregunta = $_GET['pregunta'];
	$servidor = 'localhost';
	$nombreUsuario = 'root';
	$bd = 'pm';

	$pregunta = "SELECT p.Codigo, Pregunta, Fecha, u.Codigo as codigoUsuario, Nombre, FotoIcono, esDestacada FROM preguntas p, cursos c, usuarios u WHERE CodigoUsuario = u.Codigo AND CodigoCurso = c.Codigo AND p.Codigo = '$codigoPregunta'";
	$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
	$pregunta = $conexion->query($pregunta);
	if($pregunta->num_rows == 0){
		echo "<script>alert(\"No pudimos encontrar la pregunta.\")</script>";
		header("Location: index.php");
	}
	$pregunta = $pregunta->fetch_assoc();

	$respuestas = "SELECT r.Codigo, Respuesta, r.Fecha, r.CodigoUsuario AS dRespuesta, u.Nombre, u.FotoIcono, r.esDestacada FROM respuestas r, preguntas p, usuarios u WHERE r.CodigoPregunta = p.Codigo AND r.CodigoUsuario = u.Codigo AND p.Codigo = '$codigoPregunta' ORDER BY r.Fecha DESC";
	$respuestas = $conexion->query($respuestas);

	$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Pregunta - <?php echo "Nombre del usuario dueño de la pregunta"; ?></title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php
			include 'cabecera-logeado.php';
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 alert alert-secondary">
					<?php
						if($pregunta['FotoIcono'] == ''){
							echo "<img src=\"imagenes/usuarios/usuario-icono.png\" class=\"img-miniatura\">";
						}else{
							echo "<img src=\"".$pregunta['FotoIcono']."\" class=\"img-miniatura\">";
						}
					?>
					<label><?php echo $pregunta['Nombre']; ?> pregunto:</label>
					<p class="text-end">Formulada el: <?php echo $pregunta['Fecha']; ?></p>
					<p class="ms-5"><?php echo $pregunta['Pregunta']; ?></p>
				</div>
				<form class="mt-5" id="formRespuesta" action="pregunta.php?pregunta=<?php echo $codigoPregunta; ?>" method="POST">
					<div class="form-floating">
				  		<textarea class="form-control" placeholder="Leave a comment here" name="inputRespuesta" id="inputRespuesta"></textarea>
					  	<label for="inputRespuesta">Responder...</label>
					</div>
					<label id="alertaRespuesta" class="text-danger visually-hidden">Debe ingresar una respuesta.</label>
					<div  id="botonRespuesta" class="card-body d-flex justify-content-between align-items-center mt-2 visually-hidden">
						<span></span>
						<input class="btn btn-secondary btn-sm" type="submit" name="responder" value="Responder">
					</div>
				</form>

				<h4 class="mt-5">Respuestas</h4>
				<div class="col-12">
					<?php
						$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
						$sinDestacar = [];
						
						$esProfesor = "SELECT u.Codigo FROM usuarios u, preguntas p, cursos c WHERE p.CodigoCurso = c.Codigo AND u.Codigo = c.CodigoProfesor AND p.Codigo = '$codigoPregunta'";
						$esProfesor = $conexion->query($esProfesor);
						$esProfesor = $esProfesor->fetch_assoc();

						while($respuesta = $respuestas->fetch_assoc()){
							if($respuesta['esDestacada']){
								$destaco = "SELECT p.CodigoUsuario AS dPregunta, d.CodigoUsuario AS destaco FROM respuestas r, destaca d, preguntas p WHERE r.Codigo = '".$respuesta['Codigo']."' AND d.CodigoRespuesta = r.Codigo AND r.CodigoPregunta = p.Codigo";								

								$destaco = $conexion->query($destaco);
								$destaco = $destaco->fetch_assoc();
								if($destaco['dPregunta'] == $destaco['destaco']){
									echo "<div class=\"alert alert-secondary\">";		
								}else{
									echo "<div class=\"alert alert-primary\">";								
								}
								if($respuesta['FotoIcono'] == ''){
									echo "<img src=\"imagenes/usuarios/usuario-icono.png\" class=\"img-miniatura\">";
								}else{
									echo "<img src=\"".$respuesta['FotoIcono']."\" class=\"img-miniatura\">";
								}
								echo "<label>".$respuesta['Nombre']." - ".$respuesta['Fecha']."</label><p class=\"ms-5 mt-2\">".$respuesta['Respuesta']."</p>";
								if($respuesta['dRespuesta'] == $codigo){
									echo "<div class=\"card-body d-flex justify-content-between align-items-center\"><span></span><a href=\"#\" class=\"me-2\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal".$respuesta['Codigo']."\" data-bs-whatever=\"@mdo\"><i class=\"bi bi-pencil-square\"></i></a></div><div class=\"modal fade\" id=\"exampleModal".$respuesta['Codigo']."\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\"><div class=\"modal-dialog\"><div class=\"modal-content\"><div class=\"modal-header\"><h1 class=\"modal-title fs-5\" id=\"exampleModalLabel\">Modificar respuesta</h1><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button></div><div class=\"modal-body\"><form action=\"pregunta.php?pregunta=$codigoPregunta&eRespuesta=".$respuesta['Codigo']."\" method=\"POST\"><div class=\"mb-3\"><label for=\"message-text\" class=\"col-form-label\">Respuesta:</label><textarea class=\"form-control\" id=\"message-text\" name=\"editarRespuesta\"></textarea></div><div class=\"card-body d-flex justify-content-between align-items-center\"><span></span><div><button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cerrar</button><input type=\"submit\" class=\"btn btn-primary ms-2\" value=\"Editar\"></div></div></form></div></div></div></div>";
								}
								echo "</div>";
							}else{
								$sinDestacar[] = $respuesta;
							}
						}
						foreach ($sinDestacar as $valor){
							echo "<div class=\"alert alert-light\">";
							if($valor['FotoIcono'] == ''){
								echo "<img src=\"imagenes/usuarios/usuario-icono.png\" class=\"img-miniatura\">";
							}else{
								echo "<img src=\"".$valor['FotoIcono']."\" class=\"img-miniatura\">";
							}
							echo "<label>".$valor['Nombre']." - ".$valor['Fecha']."</label><p class=\"ms-5 mt-2\">".$valor['Respuesta']."</p>";
							if($codigo == $pregunta['codigoUsuario'] || $esProfesor['Codigo'] == $codigo){
								echo "<div class=\"card-body d-flex justify-content-between align-items-center\"><span></span><div><a href=\"pregunta.php?pregunta=$codigoPregunta&respuesta=".$valor['Codigo']."\" class=\"me-2\"><i class=\"bi bi-heart\"></i></a>";
							}
							if($valor['dRespuesta'] == $codigo){
								echo "<a href=\"#\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal".$valor['Codigo']."\" data-bs-whatever=\"@mdo\"><i class=\"bi bi-pencil-square\"></i></a><div class=\"modal fade\" id=\"exampleModal".$valor['Codigo']."\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\"><div class=\"modal-dialog\"><div class=\"modal-content\"><div class=\"modal-header\"><h1 class=\"modal-title fs-5\" id=\"exampleModalLabel\">Editar respuesta:</h1><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button></div><div class=\"modal-body\"><form action=\"pregunta.php?pregunta=$codigoPregunta&eRespuesta=".$valor['Codigo']."\" method=\"POST\"><div class=\"mb-3\"><label for=\"message-text\" class=\"col-form-label\">Respuesta:</label><textarea class=\"form-control\" id=\"message-text\" name=\"editarRespuesta\"></textarea></div><div class=\"card-body d-flex justify-content-between align-items-center\"><span></span><div><button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cerrar</button><input type=\"submit\" class=\"btn btn-primary ms-2\" value=\"Editar\"></div></div></form></div></div></div></div>";
							}
							echo "</div></div></div>";
						}												
						$conexion->close();						
					?>
				</div>
			</div>
		</div>
		<?php
			include 'footer.html';
		?>
		<script src="js/respuesta.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>

<?php
	if(isset($_POST['editarRespuesta'])){
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		$eRespuesta = $_POST['editarRespuesta'];
		$codigoRespuesta = $_GET['eRespuesta'];
		$modRespuesta = "UPDATE respuestas SET Respuesta = '$eRespuesta' WHERE Codigo = '$codigoRespuesta'";

		$modRespuesta = $conexion->query($modRespuesta);
		if(!$modRespuesta || $conexion->affected_rows == 0){
			echo "<script>alert(\"Error al modificar su respuesta.\")</script>";
		}

		$conexion->close();
		echo "<script>window.location.href = \"pregunta.php?pregunta=".$codigoPregunta."\"</script>";
	}

	if(isset($_GET['respuesta'])){
		$codigoRespuesta = $_GET['respuesta'];
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

		//VALIDO QUE EL USUARIO NO HAYA DESTACADO OTRA RESPUESTA
		$aDestacado = "SELECT u.Codigo FROM respuestas r, usuarios u, destaca d WHERE d.CodigoUsuario = u.Codigo AND d.CodigoRespuesta = r.Codigo AND u.Codigo = '$codigo' AND r.CodigoPregunta = '$codigoPregunta'";
		$aDestacado = $conexion->query($aDestacado);

		if($aDestacado->num_rows > 0){
			echo "<script>alert(\"Solo puede destacar una respuesta.\");window.location.href = \"pregunta.php?pregunta=".$codigoPregunta."\"</script>";
		}else{
			$esDestacada = "UPDATE respuestas SET EsDestacada = '1' WHERE Codigo = $codigoRespuesta";
			$destaca = "INSERT INTO destaca (CodigoUsuario, CodigoRespuesta) VALUES ('$codigo', '$codigoRespuesta')";
			$cerrarTema = "UPDATE preguntas SET estaAbierta = '0' WHERE Codigo = $codigoPregunta";

			$conexion->query($esDestacada);
			$conexion->query($destaca);
			$conexion->query($cerrarTema);

			$conexion->close();
			echo "<script>window.location.href = \"pregunta.php?pregunta=".$codigoPregunta."\"</script>";
		}		
	}

	if(isset($_POST['inputRespuesta'])){
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		$inputRespuesta = $_POST['inputRespuesta'];
		//VALIDO QUE EL TEMA SIGA ABIERTO
		$estaAbierta = "SELECT estaAbierta FROM preguntas p WHERE Codigo = '$codigoPregunta'";
		$estaAbierta = $conexion->query($estaAbierta);
		$estaAbierta = $estaAbierta->fetch_assoc();

		if($estaAbierta['estaAbierta']){
			$ultimoResp = "SELECT u.Codigo FROM respuestas r, usuarios u WHERE r.CodigoPregunta = '$codigoPregunta' AND u.Codigo = r.CodigoUsuario AND r.Codigo = (SELECT MAX(Codigo) FROM respuestas)";
			$insertarRespuesta = "INSERT INTO respuestas(Respuesta, CodigoPregunta, CodigoUsuario) VALUES ('$inputRespuesta','$codigoPregunta','$codigo')";

			$ultimoResp = $conexion->query($ultimoResp);
			if($ultimoResp->num_rows == 0){
				$ultimoResp = $ultimoResp->fetch_assoc();
				if($ultimoResp['Codigo'] == $codigo){
					echo "<script>alert(\"No puede responder dos veces seguidas una pregunta.\")</script>";
				}else{
					$insertarPregunta = $conexion->query($insertarRespuesta);
					if(!$insertarRespuesta || $conexion->affected_rows == 0){
						echo "<script>alert(\"Error al subir la respuetsa.\")</script>";
					}				
				}
			}else{
				$insertarPregunta = $conexion->query($insertarRespuesta);
				if(!$insertarRespuesta || $conexion->affected_rows == 0){
					echo "<script>alert(\"Error al subir la respuetsa.\")</script>";
				}
			}
			echo "<script>window.location.href = \"pregunta.php?pregunta=".$codigoPregunta."\"</script>";
		}else{
			echo "<script>alert(\"El tema ya ha sido cerrado.\")</script>";
		}		
	}
?>