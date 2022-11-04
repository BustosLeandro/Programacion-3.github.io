<?php
	session_start();
	if(!isset($_SESSION['Codigo'])){
		header("Location: index.php");
	}
	$servidor = 'localhost';
	$nombreUsuario = 'root';
	$bd = 'pm';
	$codigo = $_SESSION['Codigo'];

	$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

	$esPro = "SELECT t.Tipo FROM usuarios u, tipousuarios t WHERE u.Codigo = '$codigo' AND u.Es = t.Codigo AND t.Tipo = 'Usuario pro'";
	$esPro = $conexion->query($esPro);
	//VALIDO QUE EL USUARIO SEA PRO
	if($conexion->affected_rows == 0){
		//SI NO ES PRO VALIDO QUE SOLO TENGA 3 CURSOS ACTIVOS
		$cantCursos = 0;
		$sqlCursos = "SELECT Titulo, FechaFin FROM cursos c, usuarios u WHERE CodigoProfesor = u.Codigo AND u.Codigo = '$codigo'";

		$cursos = $conexion->query($sqlCursos);

		//CONTEO DE CURSOS ACTIVOS
		$hoy = date('Y-m-d');
		$hoy = date_create($hoy);

		while($curso = $cursos->fetch_assoc()){
			$fecha = $curso['FechaFin'];
			$fecha = date_create($fecha);		
			$intervalo = date_diff($hoy,$fecha);

			if($intervalo->invert == 0){
				$cantCursos++;
			}
		}
		if($cantCursos >= 3){
			echo "<script>alert(\"Para crear más cursos debe adquirir una cuenta PRO\");window.location.href = \"index.php\"</script>";
		}
	}
	$conexion->close();
	$titulo = "";
	$costo = 0;
	$descripcion = "";
	$fechaIn = date('Y-m-d');
	$fechaFin = "";
	$cupos = "";
	if(isset($_POST['inpuTitulo'])){
		$titulo = $_POST['inpuTitulo'];
		$costo = $_POST['inputCosto'];
		$descripcion = $_POST['inputDesc'];
		$fechaIn = $_POST['inputFechaInicio'];
		$fechaFin = $_POST['inputFechaFin'];
		if(isset($_POST['inputCupo'])){
			$cupos = $_POST['inputCupo'];
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Crear curso</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php
			include 'cabecera-logeado.php';
		?>
		<div class="container-fluid">
			<div class="row mt-2">
				<div class="col-12">
					<a href="dictando.php" class="text-decoration-none">Volver</a>
				</div>
			</div>
		</div>
		<div class="m-0 row justify-content-center align-items-center mt-5">
			<div class="col-6">
				<form id="crearCurso" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
					<div class="row mb-3">
					  	<div class="col">
					    	<label for="inpuTitulo" class="form-label">Título</label>
						    <input type="text" class="form-control" id="inpuTitulo" name="inpuTitulo" value="<?php echo $titulo; ?>">
						    <label id="alertaTitulo" class="text-danger visually-hidden">Debe ingresar un título, no mayor a 50 caracteres.</label>
					  	</div>
					  	<div class="col">
					    	<label for="inputCosto" class="form-label">Costo</label>
						    <input type="number" class="form-control" id="inputCosto" name="inputCosto" value="<?php echo $costo; ?>">
						    <label id="alertaCosto" class="text-danger visually-hidden">Debe ingresar un costo, si es un curso gratuito ingresar costo 0.</label>
					  	</div>
					</div>
				  	<div class="mb-3">
				    	<label for="inputDesc" class="form-label">Descripción</label>
					    <div class="form-floating">
					  		<textarea class="form-control" id="inputDesc" name="inputDesc"><?php echo $descripcion; ?></textarea>
						</div>
					    <label id="alertaDesc" class="text-danger visually-hidden">Debe ingresar una descripción, no mayor a 200 caracteres.</label>
				  	</div>
				  	<div class="row mb-3">
					  	<div class="col">
					    	<label for="inputFechaInicio" class="form-label">Fecha de inicio</label>
						    <input type="date" class="form-control" id="inputFechaInicio" name="inputFechaInicio" value="<?php echo $fechaIn; ?>">
						    <label id="alertaFechaInicio" class="text-danger visually-hidden">Debe ingresar la fecha de inicio del curso.</label>
					  	</div>
					  	<div class="col">
					    	<label for="inputFechaFin" class="form-label">Fecha de fin del curso</label>
						    <input type="date" class="form-control" id="inputFechaFin" name="inputFechaFin" value="<?php echo $fechaFin; ?>">
						    <label id="alertaFechaFin" class="text-danger visually-hidden">Debe ingresar la fecha de fin del curso.</label>
					  	</div>
					</div>
					<div class="row mb-3">
					  	<div class="col">
					    	<label for="inputImagen" class="form-label">Seleccione imagen del curso (opcional)</label>
						  	<input id="inputImagen" class="form-control" type="file" name="imagen" style="display: block;">
					  	</div>
					  	<div class="col">
					    	<label for="inputCupo" class="form-label">Cupos</label>
						    <input type="number" class="form-control" id="inputCupo" name="inputCupo" placeholder="OPCIONAL" value="<?php echo $cupos; ?>">
						</div>
					</div>
					<div class="card-body d-flex justify-content-between align-items-center">
						<span></span>
						<input class="btn boton-primario" type="submit" value="Crear">
					</div>
				</form>
			</div>
		<?php
			include 'footer.html';
		?>
		<script src="js/crearCurso.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>	

<?php
	if(isset($_POST['inpuTitulo'])){
		$valido = true;
		if(strlen($titulo) < 5 || strlen($titulo) > 50){
			echo "<script>document.getElementById(\"inpuTitulo\").classList.add(\"border\");document.getElementById(\"inpuTitulo\").classList.add(\"border-danger\");document.getElementById(\"alertaTitulo\").classList.remove(\"visually-hidden\");</script>";
			$valido = false;
		}
		if($costo < 0){
			echo "<script>document.getElementById(\"inputCosto\").classList.add(\"border\");document.getElementById(\"inputCosto\").classList.add(\"border-danger\");document.getElementById(\"alertaCosto\").classList.remove(\"visually-hidden\");</script>";
			$valido = false;
		}
		if(strlen($descripcion) < 10 || strlen($descripcion) > 200){
			echo "<script>document.getElementById(\"inputDesc\").classList.add(\"border\");document.getElementById(\"inputDesc\").classList.add(\"border-danger\");document.getElementById(\"alertaDesc\").classList.remove(\"visually-hidden\");</script>";
			$valido = false;
		}
		$hoy = date('Y-m-d');
		$hoy = date_create($hoy);
		$fechaInAux = date_create($fechaIn);
		$intervalo1 = date_diff($hoy,$fechaInAux);
		if($intervalo1->invert != 0){
			echo "<script>document.getElementById(\"inputFechaInicio\").classList.add(\"border\");document.getElementById(\"inputFechaInicio\").classList.add(\"border-danger\");document.getElementById(\"alertaFechaInicio\").classList.remove(\"visually-hidden\");</script>";
			$valido = false;
		}
		$fechaFinAux = date_create($fechaFin);
		$intervalo2 = date_diff($fechaInAux,$fechaFinAux);
		if(($intervalo2->invert != 0) || $fechaFin == ""){
			echo "<script>document.getElementById(\"inputFechaFin\").classList.add(\"border\");document.getElementById(\"inputFechaFin\").classList.add(\"border-danger\");document.getElementById(\"alertaFechaFin\").classList.remove(\"visually-hidden\");</script>";
			$valido = false;
		}
		if($valido){
			$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
			if($_FILES['imagen']['name'] != ""){
				try{
					$codigoNuevo = "SELECT MAX(Codigo) AS Codigo FROM cursos";
					$codigoNuevo = $conexion->query($codigoNuevo);
					if($codigoNuevo->num_rows > 0){
						$codigoNuevo = $codigoNuevo->fetch_assoc();
						$codigoNuevo = $codigoNuevo['Codigo'];
						$codigoNuevo++;
					}else{
						$codigoNuevo = 1;
					}
				}catch(Exception $e){
		      		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
		      	}
		      	$valido = true;
				$anchoFinal = 288;
				$dirImagen = "imagenes/cursos/";
				$tmpNombre = $_FILES['imagen']['tmp_name'];


				if($_FILES['imagen']['type'] == 'image/jpeg'){
					$nombreFinal = $dirImagen . $codigoNuevo . ".jpg";
				}else if ($_FILES['imagen']['type'] == 'image/png') {
					$nombreFinal = $dirImagen . $codigoNuevo . ".png";
				}else if ($_FILES['imagen']['type'] == 'image/gif') {
					$nombreFinal = $dirImagen . $codigoNuevo . ".gif";
				}

				//creo la miniatura
				$im = null;

				if($_FILES['imagen']['type'] == 'image/jpeg'){
					$im = imagecreatefromjpeg($tmpNombre);
				}else if ($_FILES['imagen']['type'] == 'image/png') {
					$im = imagecreatefrompng($tmpNombre);
				}else if ($_FILES['imagen']['type'] == 'image/gif') {
					$im = imagecreatefromgif($tmpNombre);
				}else{
					$valido = false;	
				}

				if($valido){
					$ancho = imagesx($im);
					$alto = imagesy($im);

					$anchoMin = $anchoFinal;
					$altoMin = floor($alto * ($anchoFinal / $ancho));

					$imageTrueColor = imagecreatetruecolor($anchoMin, $altoMin);

					imagecopyresized($imageTrueColor, $im, 0, 0, 0, 0, $anchoMin, $altoMin, $ancho, $alto);

					if(!file_exists($dirImagen)){
						if(!mkdir($dirImagen)){
							die("Hubo un problema con la miniatura");
						}
					}
					imagejpeg($imageTrueColor, $nombreFinal);
				}
				if($cupos != ""){
					$crearCurso = "INSERT INTO cursos(Titulo, Descripcion, Costo, FechaInicio, FechaFin, Cupos, Imagen, CodigoProfesor) VALUES ('$titulo','$descripcion','$costo','$fechaIn','$fechaFin','$cupos','$nombreFinal','$codigo')";
				}else{
					$crearCurso = "INSERT INTO cursos(Titulo, Descripcion, Costo, FechaInicio, FechaFin, Imagen, CodigoProfesor) VALUES ('$titulo','$descripcion','$costo','$fechaIn','$fechaFin','$nombreFinal','$codigo')";
				}
	      	}else{
	      		if($cupos != ""){
	      			$crearCurso = "INSERT INTO cursos(Titulo, Descripcion, Costo, FechaInicio, FechaFin, Cupos, CodigoProfesor) VALUES ('$titulo','$descripcion','$costo','$fechaIn','$fechaFin','$cupos','$codigo')";
	      		}else{
	      			$crearCurso = "INSERT INTO cursos(Titulo, Descripcion, Costo, FechaInicio, FechaFin, CodigoProfesor) VALUES ('$titulo','$descripcion','$costo','$fechaIn','$fechaFin','$codigo')";
	      		}
	      	}
	      	$resultado = $conexion->query($crearCurso);
	      	if(!$resultado || $conexion->affected_rows == 0){
	      		echo "<script>alert(\"Error al crear el curso\")</script>";
	      	}
	      	$conexion->close();
	      	echo "<script type=\"text/javascript\">window.location.href = \"dictando.php\";</script>";
		}
	}
?>