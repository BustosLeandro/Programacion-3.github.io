<?php
	session_start();

	if(isset($_POST['cerrarSesion'])){
		session_destroy();
		header("Location: index.php");
		//echo "<script type=\"text/javascript\">window.location.href = \"index.php\";</script>";
	}

	try{
		$servidor = 'localhost';
		$nombreUsuario = 'root';
		$bd = 'pm';
		$codigo = $_SESSION['Codigo'];

		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		if($conexion->connect_error){
			die("Error al conectarse a la base de datos.");
		}
		$sql = "SELECT Tipo FROM usuarios u, tipousuarios t WHERE t.Codigo = Es AND u.Codigo = '$codigo'";
		$resultado = $conexion->query($sql);
		if($resultado && $conexion->affected_rows > 0){
			$resultado = $resultado->fetch_assoc();
			$resultado = $resultado['Tipo'];
			if($resultado !== "Admin"){
				header("Location:index.php");
			}
		}else{
			header("Location:index.php");
		}

		$sqlFoto = "SELECT * FROM Usuarios WHERE Codigo = '$codigo'";
		$usuario = $conexion->query($sqlFoto);
		if($usuario && $conexion->affected_rows > 0){
			$usuario = $usuario->fetch_assoc();
			$fotoPerfil = 1;
	    	if($usuario['FotoPerfil'] == ""){
				$fotoPerfil = "";
			}
		}
	}catch(Exception $e){
		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Perfil - <?php echo $usuario['Nombre']; ?></title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<header>
			<nav class="navbar text-bg-principal">
  				<div class="container-fluid">
    				<a class="navbar-brand text-white" href="dashboard.php"><img class="logoImg" src="iconos/logo.png">CursoLandia</a>
    				<div class="dropdown-center">
	  					<a type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<?php
								if($fotoPerfil == ""){
									echo "<div class=\"blanco-t\"><img class=\"img-miniatura\" src=\"imagenes/usuarios/usuario.png\"></i></div>";
								}else{
									echo "<div class=\"blanco-t\"><img class=\"img-miniatura\" src=\"".$usuario['FotoPerfil']."\"></i></div>";
								}
							?>
						</a>
						<ul class="dropdown-menu dropdown-menu-lg-end dropdown-menu-dark">
					    	<li>
					    		<a class="dropdown-item" href="perfilAdmin.php">Perfil</a>
							</li>
					    	<li>
					    		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					    			<input type="submit" name="cerrarSesion" class="dropdown-item" value="Cerrar sesión">
					    		</form>
					    	</li>
					  	</ul>
					</div>
  				</div>
			</nav>
		</header>
		<div class="container mt-5">
			<div class="row">
				<div class="col-sm-2">	
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
						<label class="custom-file-upload position-relative">
      						<input type="file" name="fotoPerfil" onchange="this.form.submit();" >
      						<?php
								if($fotoPerfil == ""){
									echo "<img src=\"imagenes/usuarios/usuario.png\" class=\"img-perfil\" alt=\"Foto de perfil\">";
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
					</ul>
				</div>	
				<div class="col-sm-10 mt-5" id="pnlCuenta">
					<form id="formPerfil" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
  						<div class="mb-3">
    						<label for="inputNombre" class="form-label">Nombre</label>
    						<input type="text" class="form-control" name="inputNombre" id="inputNombre" value="<?php echo $usuario['Nombre']; ?>">
    						<div id="alertaNombre" class="form-text text-danger visually-hidden">
			    				El nombre ingresado es inválido
			    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener entre 3 y 20 caracteres."></i>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputApellido" class="form-label">Apellido</label>
    						<input type="text" class="form-control" name="inputApellido" id="inputApellido" value="<?php echo $usuario['Apellido']; ?>">
    						<div id="alertaApellido" class="form-text text-danger visually-hidden">
			    				El apellido ingresado es inválido
			    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener entre 3 y 20 caracteres."></i>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputEmail" class="form-label">Email</label>
    						<input type="email" class="form-control" name="inputEmail" id="inputEmail" value="<?php echo $usuario['Email']; ?>">
    						<div id="alertaEmail" class="form-text text-danger visually-hidden">
			    				El correo electrónico ingresado es inválido
			    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Un nombre de usuario de entre 8 y 20 caracteres, un '@' y un '.com'"></i>
								</svg>
			    			</div>
  						</div>
  						<div class="mb-3">
    						<label for="inputTelefono" class="form-label">Teléfono</label>
    						<input type="number" class="form-control" name="inputTelefono" id="inputTelefono" value="<?php echo $usuario['Telefono']; ?>">
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
			</div>
		</div>


		<?php

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
						//echo "<script type=\"text/javascript\">window.location.href = \"dashboard.php\";</script>";

					}catch(Exception $e){
						echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
					}
				}else{
					echo "<script>alert(\"Solo se pueden subir archivos jpg, png y gif.\")</script>";
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
							echo "<script type=\"text/javascript\">window.location.href = \"dashboard.php\";</script>";
						}else{
							echo "<script>Alert(\"Error al modificar los datos\");</script>";
						}
					}

					$conexion->close();
				}catch(Exception $e){
					echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
				}
			}

			//Modificar contraseña
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
								echo "<script type=\"text/javascript\">window.location.href = \"dashboard.php\";</script>";
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
		?>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="	sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
		<script src="js/perfilAdmin.js"></script>
	</body>
</html>