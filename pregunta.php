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

	$pregunta = "SELECT p.Codigo, Pregunta, Fecha, Nombre, FotoIcono, esDestacada FROM preguntas p, cursos c, usuarios u WHERE CodigoUsuario = u.Codigo AND CodigoCurso = c.Codigo AND p.Codigo = '$codigoPregunta'";
	$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
	$pregunta = $conexion->query($pregunta);
	if($pregunta->num_rows == 0){
		echo "<script>alert(\"No pudimos encontrar la pregunta.\")</script>";
		header("Location: index.php");
	}
	$pregunta = $pregunta->fetch_assoc();

	$respuestas = "SELECT r.Codigo, Respuesta, r.Fecha, u.Nombre, u.FotoIcono, r.esDestacada FROM respuestas r, preguntas p, usuarios u WHERE r.CodigoPregunta = p.Codigo AND r.CodigoUsuario = u.Codigo AND p.Codigo = '$codigoPregunta'";
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
								echo "<label>".$respuesta['Nombre']." - ".$respuesta['Fecha']."</label><p class=\"ms-5 mt-2\">".$respuesta['Respuesta']."</p></div>";
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
							echo "<label>".$valor['Nombre']." - ".$valor['Fecha']."</label><p class=\"ms-5 mt-2\">".$valor['Respuesta']."</p></div>";
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
	if(isset($_POST['inputRespuesta'])){
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		$inputRespuesta = $_POST['inputRespuesta'];

		$ultimoResp = "SELECT u.Codigo FROM respuestas r, usuarios u WHERE r.CodigoPregunta = '$codigoPregunta' AND u.Codigo = r.CodigoUsuario AND r.Codigo = (SELECT MAX(Codigo) FROM respuestas)";
		$insertarRespuesta = "INSERT INTO respuestas(Respuesta, CodigoPregunta, CodigoUsuario) VALUES ('$inputRespuesta','$codigoPregunta','$codigo')";

		$ultimoResp = $conexion->query($ultimoResp);
		if($ultimoResp->num_rows == 0){
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
	}
?>