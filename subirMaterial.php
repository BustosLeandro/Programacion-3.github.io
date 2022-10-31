<?php
	session_start();
	if(!isset($_GET['curso']) || !isset($_SESSION['Codigo'])){
		header("Location: index.php");
	}else{
		$curso = $_GET['curso'];
		$codigo = $_SESSION['Codigo'];
		$servidor = 'localhost';
		$nombreUsuario = 'root';
		$bd = 'pm';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Subir material</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php include 'cabecera-logeado.php'; ?>
		<div class="container-fluid">
			<div class="row mt-2">
				<div class="col-12">
					<a href="curso.php?curso=<?php echo $curso; ?>" class="text-decoration-none">Volver</a>
				</div>
			</div>
			<div class="m-0 row justify-content-center align-items-center mt-5">
				<div class="col-6">
					<form id="formArchivo" action="<?php echo $_SERVER['PHP_SELF']."?curso=".$curso; ?>" method="POST" enctype="multipart/form-data">
						<div class="mb-3">
					    	<label for="inpuTitulo" class="form-label">Título </label>
						    <input type="text" class="form-control" id="inpuTitulo" name="inpuTitulo">
						    <label id="alertaTitulo" class="text-danger visually-hidden">Debe ingresar un título, no mayor a 50 caracteres.</label>
					  	</div>
					  	<div class="mb-3">
					    	<label for="inputDesc" class="form-label">Descripción</label>
						    <input type="text" class="form-control" id="inputDesc" name="inputDesc" placeholder="OPCIONAL">
					  	</div>
					  	<div class="mb-3">
						  	<label for="material" class="form-label">Seleecione archivos (pdf o docx)</label>
						  	<input id="inputMaterial" class="form-control" type="file" name="material" style="display: block;">
						  	<label id="alertaMaterial" class="text-danger visually-hidden">Debe seleccionar un archivo</label>
						</div>
						<div class="card-body d-flex justify-content-between align-items-center">
							<span></span>
							<input class="btn boton-primario" type="submit" value="Subir">
						</div>
					</form>
				</div>
			</div>
		</div>

		<?php include 'footer.html' ?>
		<script src="js/subirMaterial.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>

<?php
	if(isset($_FILES['material'])){
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

		//VALIDACIÓN DE USUARIO PRO
		$esPro = "SELECT u.Codigo FROM usuarios u,tipousuarios t WHERE u.Codigo = '$codigo' AND Es = t.Codigo AND Tipo = 'Usuario Pro'";

		$esPro = $conexion->query($esPro);

		if(!($esPro->num_rows > 0)){
			//SINO ES PRO VALIDA QUE TENGA SUBIDOS MENOS DE 5 ARCHIVOS
			$cantArchivos = "SELECT COUNT(m.Codigo) AS Cantidad FROM materiales m, usuarios u, cursos c WHERE m.CodigoCurso = c.Codigo AND c.CodigoProfesor = u.Codigo AND u.Codigo = '$codigo'";

			$cantArchivos = $conexion->query($cantArchivos);
			$cantArchivos = $cantArchivos->fetch_assoc();
			if($cantArchivos['Cantidad'] >= 5){
				echo "<script>alert(\"Para poder subir más de cinco archivos debe poseer una cuenta pro\")</script>";
			}else{
				//SI TIENE MENOS DE 10mb EN TOTAL VALIDA QUE EL ARCHIVO A SUBIR NO PASE LOS 5MB
				$size = $_FILES['material']['size'];
				if($size > 5242880){
					echo "<script>alert(\"Para poder subir archivos de más de 5mb debe poseer una cuenta pro\")</script>";
				}else{
					//SI EL ARCHIVO PESA MENOS DE 5MB VALIDA QUE SUS ARCHIVOS NO SOBREPASEN LOS 10MB
					$pesoArchivos = "SELECT SUM(m.Peso) AS PesoTotal FROM materiales m, cursos c, usuarios u WHERE m.CodigoCurso = c.Codigo AND c.CodigoProfesor = u.Codigo AND u.Codigo = '$codigo'";

					$pesoArchivos = $conexion->query($pesoArchivos);
					$pesoArchivos = $pesoArchivos->fetch_assoc();

					$pesoArchivos = intval($pesoArchivos['PesoTotal']); 
					$pesoArchivos += $size;
					if($pesoArchivos > 10485760){
						echo "<script>alert(\"Para poder subir más de 10mb en archivos debe poseer una cuenta pro\")</script>";
					}else{
						//SI PASA ESTAS VALIDACIONES SE VALIDA QUE EL ARCHIVO NO EXISTA
						$archivo = 'materiales/'.basename($_FILES['material']['name']);
						$existe = "SELECT Archivo FROM Materiales WHERE Archivo = '$archivo'";
						
						$existe = $conexion->query($existe);
						if($existe->num_rows > 0){
							echo "<script>alert(\"El archivo que intenta subir ya existe\")</script>";
						}else{
							//VALIDA QUE EL ARCHIVO SEA PDF O DOC
							$tipoArchivo = $_FILES['material']['type'];
							if($tipoArchivo == "application/pdf" || $tipoArchivo == "application/msword" || $tipoArchivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
								$titulo = $_POST['inpuTitulo'];
								if(move_uploaded_file($_FILES['material']['tmp_name'], $archivo)){
									if($_POST['inputDesc'] == ""){
										$sqlCargar = "INSERT INTO materiales(Titulo,Archivo,Tipo,Peso,CodigoCurso) VALUES ('$titulo','$archivo','$tipoArchivo','$size','$curso')";
									}else{
										$descripcion = $_POST['inputDesc'];
										$sqlCargar = "INSERT INTO materiales(Titulo,Archivo,Tipo,Peso,Descripcion,CodigoCurso) VALUES ('$titulo','$archivo','$tipoArchivo','$size','$descripcion','$curso')";
									}

									$resultado = $conexion->query($sqlCargar);
									if(!$resultado || $conexion->affected_rows == 0){
										echo "<script>alert(\"Error al subir el archivo al sistema.\")</script>";	
									}
									$conexion->close();
									echo "<script type=\"text/javascript\">window.location.href = \"curso.php?curso=".$curso."\";</script>";
								}								
							}else{
								echo "<script>alert(\"Solo se admiten archivos pdf y doc\")</script>";
							}
						}
					}
				}
			}
		}else{
			//VALIDA QUE EL ARCHIVO NO EXISTA
			$archivo = 'materiales/'.basename($_FILES['material']['name']);
			$existe = "SELECT Archivo FROM Materiales WHERE Archivo = '$archivo'";
			
			$existe = $conexion->query($existe);
			if($existe->num_rows > 0){
				echo "<script>alert(\"El archivo que intenta subir ya existe\")</script>";
			}else{
				//VALIDA QUE EL ARCHIVO SEA PDF O DOC
				$tipoArchivo = $_FILES['material']['type'];
				if($tipoArchivo == "application/pdf" || $tipoArchivo == "application/msword" || $tipoArchivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
					$titulo = $_POST['inpuTitulo'];
					if(move_uploaded_file($_FILES['material']['tmp_name'], $archivo)){
						if($_POST['inputDesc'] == ""){
							$sqlCargar = "INSERT INTO materiales(Titulo,Archivo,Tipo,Peso,CodigoCurso) VALUES ('$titulo','$archivo','$tipoArchivo','$size','$curso')";
						}else{
							$descripcion = $_POST['inputDesc'];
							$sqlCargar = "INSERT INTO materiales(Titulo,Archivo,Tipo,Peso,Descripcion,CodigoCurso) VALUES ('$titulo','$archivo','$tipoArchivo','$size','$descripcion','$curso')";
						}

						$resultado = $conexion->query($sqlCargar);
						if(!$resultado || $conexion->affected_rows == 0){
							echo "<script>alert(\"Error al subir el archivo al sistema.\")</script>";	
						}
						$conexion->close();
						echo "<script type=\"text/javascript\">window.location.href = \"curso.php?curso=".$curso."\";</script>";
					}								
				}else{
					echo "<script>alert(\"Solo se admiten archivos pdf y doc\")</script>";
				}
			}
		}
	}
?>