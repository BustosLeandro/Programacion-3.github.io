<?php
	session_start();
	if(!isset($_SESSION['Codigo'])){
    	header("Location:index.php");
  	}else{
  		try{
			$servidor = 'localhost';
	      	$userName = 'root';
	      	$bd = 'pm';
	      	$codigo = $_SESSION['Codigo'];

	      	$conexion = new mysqli($servidor,$userName,"",$bd);
          	if($conexion->connect_error){
            	die("Error al conectarse con la base de datos");
            }

            //FOTO DE PERFIL
         	$sqlLogo = "SELECT * FROM usuarios WHERE Codigo ='$codigo'";
            $usuario = $conexion->query($sqlLogo);
            if($usuario && $conexion->affected_rows > 0){
            	$usuario = $usuario->fetch_assoc();
            	if($usuario['FotoPerfil'] == ""){
					$fotoPerfil = "";
				}
            }

            //INTERESES
            $sqlTags = "SELECT * FROM tags";
			$sqlIntereses = "SELECT Interes FROM intereses WHERE CodigoUsuario = '$codigo'";

			$intereses = $conexion->query($sqlIntereses);
			$arrayIntereses = array();
			while($interes = $intereses->fetch_assoc()){
				$arrayIntereses[] = $interes['Interes'];
			}
			$tags = $conexion->query($sqlTags);
      	}catch(Exception $e){
      		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
      	}
  	}
?>
<!DOCTYPE html>
<html lang="es">
	<head lang="es">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Perfil - <?php echo $usuario['Nombre']; ?></title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">

	</head>
	<body>
		<?php
			include 'cabecera-logeado.php';
		?>
		<div class="container mt-5">
			<div class="row">
				<div class="col-sm-2">	
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
						<label class="custom-file-upload position-relative">
      						<input type="file" name="fotoPerfil" onchange="this.form.submit();" >
      						<?php
								if($fotoPerfil == ""){
									echo "<img src=\"imagenes/usuarios/usuario-icono.png\" class=\"img-perfil\" alt=\"Foto de perfil\">";
								}else{
									echo "<img src=\"".$usuario['FotoPerfil']."\" class=\"img-perfil\" alt=\"Foto de perfil\">";
								}
							?>	
	  						<span class="position-absolute top-0 start-100 translate-middle badge bg-primary">
	    						<i class="bi bi-pencil-square"></i>
	  						</span>
    					</label>
					</form>
				</div>
				<div class="col-sm-10">
					<h3><?php echo $usuario['Apellido']." ".$usuario['Nombre']; ?></h3>
					<p>Se unio el <?php echo date("d/m/Y",strtotime($usuario['FechaRegistro'])); ?></p>
				</div>
				<div class="col-sm-2 mt-5 border-end">
					<ul class="nav flex-column">
  						<li class="nav-item">
    						<a class="nav-link active" onclick="selCuenta()" href="#" id="aCuenta">Cuenta</a>
  						</li>
  						<li class="nav-item">
    						<a class="nav-link text-secondary" onclick="selPassword()" href="#" id="aPassword">Contraseña</a>
  						</li>
  						<li class="nav-item">
    						<a class="nav-link text-secondary" onclick="selIntereses()" href="#" id="aIntereses">Intereses</a>
  						</li>
					</ul>
				</div>
				<div class="col-sm-10 mt-5" id="pnlCuenta">
					<form id="formPerfil" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
  						<div class="mb-3">
    						<label for="inputNombre" class="form-label">Nombre</label>
    						<input type="text" name="inputNombre" class="form-control" id="inputNombre" value="<?php echo $usuario['Nombre']; ?>">
    						<div id="alertaNombre" class="form-text text-danger visually-hidden">
			    				El nombre ingresado es inválido
			    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener entre 3 y 20 caracteres."></i>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputApellido" class="form-label">Apellido</label>
    						<input type="text" name="inputApellido" class="form-control" id="inputApellido" value="<?php echo $usuario['Apellido']; ?>">
    						<div id="alertaApellido" class="form-text text-danger visually-hidden">
			    				El apellido ingresado es inválido
			    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener entre 3 y 20 caracteres."></i>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputEmail" class="form-label">Email</label>
    						<input type="email" name="inputEmail" class="form-control" id="inputEmail" value="<?php echo $usuario['Email']; ?>">
    						<div id="alertaEmail" class="form-text text-danger visually-hidden">
			    				El correo electrónico ingresado es inválido
			    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Un nombre de usuario de entre 8 y 20 caracteres, un '@' y un '.com'"></i>
								</svg>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputTelefono" class="form-label">Teléfono</label>
    						<input type="number" name="inputTelefono" class="form-control" id="inputTelefono" value="<?php echo $usuario['Telefono']; ?>">
    						<div id="alertaTelefono" class="form-text text-danger visually-hidden">
			    				El número de teléfono ingresado es inválido
			    				<i class="bi bi-info-circle-fill"></i>
			    			</div>
  						</div>
  						<div class="row justify-content-center">
					    	<div class="col-sm-2">
					      		<input type="submit" id="modPerfil" name="modPerfil" value="Guardar cambios" class="btn boton-primario">
						    </div>
						</div>
					</form>
				</div>
				<div class="col-sm-10 mt-5 visually-hidden" id="pnlPassword">
					<form id="formPassword" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						<div class="mb-3">
    						<label for="inputPasswordA" class="form-label">Contraseña actual</label>
    						<input type="password" class="form-control" id="inputPasswordA" name="inputPasswordA">
    						<div id="alertaPasswordA" class="form-text text-danger visually-hidden">
			    				Debe ingresar la contraseña actual.
			    				<i class="bi bi-info-circle-fill"></i>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputPasswordN" class="form-label">Nueva contraseña</label>
    						<input type="password" class="form-control" id="inputPasswordN" name="inputPasswordN">
    						<div id="alertaPasswordN" class="form-text text-danger visually-hidden">
			    				Debe ingresar una nueva contraseña.
			    				<i class="bi bi-info-circle-fill"></i>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputPasswordC" class="form-label">Confirmar contraseña</label>
    						<input type="password" class="form-control" id="inputPasswordC" name="inputPasswordC">
    						<div id="alertaPasswordC" class="form-text text-danger visually-hidden">
			    				Debe confirmar la contraseña.
			    				<i class="bi bi-info-circle-fill"></i>
			    			</div>
  						</div>
  						<div class="row justify-content-center">
					    	<div class="col-sm-2">
					      		<input type="submit" id="modPassword" name="modPassword" value="Cambiar contraseña" class="btn boton-primario">
						    </div>
						</div>
					</form>
				</div>
				<div class="col-sm-10 mt-5 visually-hidden" id="pnlIntereses">
					<p>Seleecione sus intereses:</p>
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" id="formTags">
						<?php
							while($tag = $tags->fetch_assoc()){
								$valido = false;
								foreach($arrayIntereses as $interes){
									if($tag['Codigo'] == $interes){
										$valido = true;
									}
								}
								if($valido){
									echo "<input checked class=\"cb m-2\" type=\"checkbox\" id=\"cb".$tag['Tag']."\" value=\"".$tag['Codigo']."\" name=\"cb".$tag['Tag']."\"><label class=\"tag m-2\" for=\"cb".$tag['Tag']."\">".$tag['Tag']."</label>";	
								}else{
									echo "<input class=\"cb m-2\" type=\"checkbox\" id=\"cb".$tag['Tag']."\" value=\"".$tag['Codigo']."\" name=\"cb".$tag['Tag']."\"><label class=\"tag m-2\" for=\"cb".$tag['Tag']."\">".$tag['Tag']."</label>";	
								}
								
							}
						?>
						<div class="card-body d-flex justify-content-between align-items-center">
							<span></span>
							<input class="btn btn-primary btn-sm" type="submit" name="guardar" value="Guardar">
						</div>
					</form>
				</div>
			</div>
		</div>

		<?php
			include 'footer.html';

			//Modificar intereses
			if(isset($_POST['guardar'])){
				$limpiarIntereses = "DELETE FROM intereses WHERE CodigoUsuario = '$codigo'";
				$conexion->query($limpiarIntereses);

				foreach ($_POST as $valor) {
				    if($valor != "Guardar"){
				 		$sqlIntereses = "INSERT INTO intereses (CodigoUsuario,Interes) VALUES ('$codigo','$valor')";
				 		$conexion->query($sqlIntereses);
				    }
				}
				echo "<script>window.location.href = \"".$_SERVER['PHP_SELF']."\"</script>";
			}

			//Modificar foto de perfil
			if(isset($_FILES['fotoPerfil'])){	
				$valido = true;
				$anchoFinal = 200;
				$dirFullImage = 'imagenes/usuarios/original/';
				$dirMiniatura = 'imagenes/usuarios/miniatura/';
				$tmpNombre = $_FILES['fotoPerfil']['tmp_name'];

				if($_FILES['fotoPerfil']['type'] == 'image/jpeg'){
					$nombreFinal = $dirFullImage . $codigo . ".jpg";
				}else if ($_FILES['fotoPerfil']['type'] == 'image/png') {
					$nombreFinal = $dirFullImage . $codigo . ".png";
				}else if ($_FILES['fotoPerfil']['type'] == 'image/gif') {
					$nombreFinal = $dirFullImage . $codigo . ".gif";
				}

				//Mueve la imagen original a la carpeta full
				move_uploaded_file($tmpNombre, $nombreFinal);

				//creo la miniatura
				$im = null;
				
				if($_FILES['fotoPerfil']['type'] == 'image/jpeg'){
					$im = imagecreatefromjpeg($nombreFinal);
					$foto = $dirFullImage . $codigo . ".jpg";
					$miniatura = $dirMiniatura . $codigo . ".jpg";
				}else if ($_FILES['fotoPerfil']['type'] == 'image/png') {
					$im = imagecreatefrompng($nombreFinal);
					$foto = $dirFullImage . $codigo . ".png";
					$miniatura = $dirMiniatura . $codigo . ".png";
				}else if ($_FILES['fotoPerfil']['type'] == 'image/gif') {
					$im = imagecreatefromgif($nombreFinal);
					$foto = $dirFullImage . $codigo . ".gif";
					$miniatura = $dirMiniatura . $codigo . ".gif";
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

					if(!file_exists($dirMiniatura)){
						if(!mkdir($dirMiniatura)){
							die("Hubo un problema con la miniatura");
						}
					}
		
					imagejpeg($imageTrueColor, $miniatura);

					try{
						$servidor = 'localhost';
						$nombreUsuario = 'root';
						$bd = 'pm';
						$codigo = $_SESSION['Codigo'];
						$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

						$insertarFoto = "UPDATE usuarios SET FotoPerfil = '$foto' WHERE Codigo = '$codigo'";
						$insertarMiniatura = "UPDATE usuarios SET FotoIcono = '$miniatura' WHERE Codigo = '$codigo'";

						$conexion->query($insertarFoto);
						$conexion->query($insertarMiniatura);
						echo "<script type=\"text/javascript\">window.location.href = \"dashboard.php\";</script>";

						$conexion->close();
					}catch(Exception $e){
						echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
					}
				}else{
					echo "<script>alert(\"Solo se pueden subir archivos jpg, png y gif.\")</script>";
				}
			}


			//Modificar contraseñas
			if(isset($_POST['inputPasswordA'])){
				try{
					$servidor = 'localhost';
					$nombreUsuario = 'root';
					$bd = 'pm';
					$pActual = $_POST['inputPasswordA'];
					$pNueva = $_POST['inputPasswordN'];
					$codigo = $_SESSION['Codigo'];

					$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
					if($conexion->connect_error){
						die("Error al conectarse a la base de datos");
					}
					
					$sqlPassword = "SELECT Password FROM usuarios WHERE Codigo = '$codigo'";
					$password = $conexion->query($sqlPassword);
					if($password && $conexion->affected_rows > 0){
						$password = $password->fetch_assoc();
						if(password_verify($pActual, $password['Password'])){
							$pNueva = password_hash($pNueva, PASSWORD_DEFAULT,['cost' => 10]);
							$modificar = "UPDATE usuarios SET Password = '$pNueva' WHERE Codigo = '$codigo'";
							$resultado = $conexion->query($modificar);
							if($resultado && $conexion->affected_rows > 0){
								echo "<script type=\"text/javascript\">window.location.href = \"index.php\";</script>";
							}else{
								echo "<script>Alert(\"Error al modificar la contraseña\");</script>";
							}
						}else{
							echo "<script>alert(\"La contraseña actual es incorrecta.\")</script>";
						}
					}else{
						echo "<script>alert(\"La contraseña actual es incorrecta.\")</script>";
					}					

					$conexion->close();
				}catch(Exception $e){
					echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
				}
			}


			//Modificar perfil
			if(isset($_POST['inputNombre'])){
				try{
					$servidor = 'localhost';
					$nombreUsuario = 'root';
					$bd = 'pm';
					$nombre = $_POST['inputNombre'];
					$apellido = $_POST['inputApellido'];
					$email = $_POST['inputEmail'];
					$telefono = $_POST['inputTelefono'];
					$codigo = $_SESSION['Codigo'];

					$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
					if($conexion->connect_error){
						die("Error al conectarse a la base de datos");
					}

					$sqlEmail = "SELECT Email FROM usuarios WHERE Email = '$email'";

					$resultado = $conexion->query($sqlEmail);

					if($resultado->num_rows > 0 && $email != $usuario['Email']){
						echo "<script>alert(\"Ya hay una cuenta con ese correos electrónico.\")</script>";
					}else{
						$modificar = "UPDATE usuarios SET Nombre = '$nombre', Apellido = '$apellido', Telefono = '$telefono', Email = '$email' WHERE Codigo = '$codigo'";
						$resultado = $conexion->query($modificar);
						if($resultado && $conexion->affected_rows > 0){
							echo "<script type=\"text/javascript\">window.location.href = \"index.php\";</script>";
						}else{
							echo "<script>Alert(\"Error al modificar los datos\");</script>";
						}
					}

					$conexion->close();
				}catch(Exception $e){
					echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
				}
			}
		?>

		<script src="js/perfil.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>