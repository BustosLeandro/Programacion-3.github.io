<?php
	session_start();
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

		$sqlFoto = "SELECT FotoPerfil FROM Usuarios WHERE Codigo = '$codigo'";
		$fotoPerfil = $conexion->query($sqlFoto);
		if($fotoPerfil && $conexion->affected_rows > 0){
			$fotoPerfil = $fotoPerfil->fetch_assoc();
	    	if($fotoPerfil['FotoPerfil'] == ""){
				$fotoPerfil = "";
			}
		}

		$sql = "SELECT * FROM tags";
		$tags = $conexion->query($sql);
	}catch(Exception $e){
		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Dashboard</title>
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
									echo "<div class=\"blanco-t\"><img class=\"img-miniatura\" src=\"".$fotoPerfil['FotoPerfil']."\"></i></div>";
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
		<body>
			<ul class="nav nav-tabs">
			  	<li class="nav-item">
			    	<a class="nav-link active" id="aPagos" onclick="selPagos()" href="#">Pagos</a>
			  	</li>
			  	<li class="nav-item">
			    	<a class="nav-link" id="aEtiquetas" onclick="selEtiquetas()" href="#">Etiquetas</a>
		 	 	</li>
			</ul>
			<div class="container">
				<div class="row">
					<div class="col-12 mt-3" id="pnlPagos">
						<table class="table">
					  		<thead>
						    	<tr>
						      		<th scope="col">#</th>
						      		<th scope="col">Email</th>
						      		<th scope="col">Nombre</th>
						      		<th scope="col">Pago</th>
						      		<th></th>
						      		<th></th>
					    		</tr>
						  	</thead>
						  	<tbody>
						  		<?php
						  			$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
						  			$solicitudes = "SELECT u.Codigo, Nombre, Email, Adjunto FROM mensajes m, usuarios u WHERE m.CodigoEmisor = u.Codigo";

						  			$solicitudes = $conexion->query($solicitudes);
						  			while($solicitud = $solicitudes->fetch_assoc()){
						  				echo "<tr><td>".$solicitud['Codigo']."</td><td>".$solicitud['Email']."</td><td>".$solicitud['Nombre']."</td><td><a href=\"".$solicitud['Adjunto']."\" target=\"blank\"><i class=\"bi bi-filetype-pdf\"></i></a></td><td></td><td><a href=\"dashboard.php?aUsuario=".$solicitud['Codigo']."\"><i class=\"bi bi-check-circle\"></i></a></td><td><a class=\"text-danger\" href=\"dashboard.php?rUsuario=".$solicitud['Codigo']."\"><i class=\"bi bi-x-circle\"></i></a></td></tr>";
						  			}
						  		?>
						  	</tbody>
						</table>
					</div>
					<div class="col-12 mt-3 visually-hidden" id="pnlEtiquetas">
						<form id="formCrear" class="mb-5 pb-5 border-bottom" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						  	<div class="mb-3">
						    	<label for="inputEtiqueta" class="form-label">Crear etiqueta</label>
						    	<input type="text" class="form-control" id="inputEtiqueta" name="inputEtiqueta">
						  	</div>
						  	<div id="alertaCrear" class="form-text text-danger visually-hidden">
				    				DEBE LLENAR EL CAMPO CREAR
				    				<i class="bi bi-info-circle-fill"></i>
				    			</div>
						  	<div class="card-body d-flex justify-content-between align-items-center">
						  		<span></span>
								<input type="submit" class="btn btn-primary" value="Crear">
							</div>						  	
						</form>
						<form id="formModificar" class="mt-5" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
				  			<div class="mb-3">
				  				<label class="form-label">Seleccione una etiqueta a modificar:</label><br>
				  				<?php
				  					while($tag = $tags->fetch_assoc()){
				  						echo "<input type=\"radio\" class=\"btn-check\" name=\"tags\" value=\"".$tag['Tag']."\" id=\"e".$tag['Codigo']."\"><label class=\"btn btn-secondary me-2\" for=\"e".$tag['Codigo']."\">".$tag['Tag']."</label>";
				  					}
				  				?>		
				  			</div>
				  			<div class="mb-3">
				  				<label for="modificarE" class="form-label">Modificar etiqueta</label>
				    			<input type="text" class="form-control" id="modificarE" name="modificarE">
				    			<div id="alertaModificar" class="form-text text-danger visually-hidden">
				    				DEBE LLENAR EL CAMPO MODIFICAR
				    				<i class="bi bi-info-circle-fill"></i>
				    			</div>
				  			</div>
							<input class="btn btn-primary" type="submit" value="Modificar">
				  		</form>
					</div>
				</div>
			</div>
		</body>

		<?php
			if(isset($_POST['cerrarSesion'])){
				session_destroy();
				echo "<script> window.location.href = \"".$_SERVER['PHP_SELF']."\"</script>";
			}

			if(isset($_POST['inputEtiqueta'])){
				if($_POST['inputEtiqueta'] == null){
					echo "<script>document.getElementById(\"alertaCrear\").classList.remove(\"visually-hidden\");</script>";
				}else{
					$etiqueta = $_POST['inputEtiqueta'];

					$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

					$sqlCrear = "INSERT INTO tags (Tag) VALUES ('$etiqueta')";
					$validar = "SELECT Tag FROM tags WHERE tags.Tag = '$etiqueta'";

					$valido = $conexion->query($validar);
					if($valido->num_rows == 0){
						$conexion->query($sqlCrear);
					}else{
						echo "<script>alert(\"Ya existe una etiqueta con ese nombre.\")</script>";
					}

					$conexion->close();
					echo "<script> window.location.href = \"".$_SERVER['PHP_SELF']."\"</script>";
				}
			}

			if(isset($_POST['modificarE'])){
				if(!isset($_POST['tags'])){
					echo "<script>alert(\"Debe seleccionar una etiqueta a modificar.\")</script>";
				}else{
					if($_POST['modificarE'] == null){
						echo "<script>document.getElementById(\"alertaModificar\").classList.remove(\"visually-hidden\");</script>";
					}else{
						$servidor = 'localhost';
						$nombreUsuario = 'root';
						$bd = 'pm';
						$modificacion = $_POST['modificarE'];
						$modificado = $_POST['tags'];

						$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
						$validar = "SELECT Tag FROM tags WHERE tags.Tag = '$modificacion'";
						$sqlModificar = "UPDATE tags SET Tag = '$modificacion' WHERE tags.Tag = '$modificado'";

						$valido = $conexion->query($validar);
						if($valido->num_rows == 0){
							$conexion->query($sqlModificar);
						}else{
							echo "<script>alert(\"Ya existe una etiqueta con ese nombre.\")</script>";
						}
						
						$conexion->close();
						echo "<script> window.location.href = \"".$_SERVER['PHP_SELF']."\"</script>";
					}
				}	
			}

			if(isset($_GET['aUsuario'])){
				$usuario = $_GET['aUsuario'];
				$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
				$hacerPro = "UPDATE usuarios SET Es = (SELECT Codigo FROM tipousuarios WHERE Tipo = 'Usuario Pro') WHERE Codigo = '$usuario'";

				$hacerPro = $conexion->query($hacerPro);
				if($hacerPro && $conexion->affected_rows > 0){
					$pago = "SELECT Adjunto FROM mensajes WHERE CodigoEmisor = '$usuario'";

					$pago = $conexion->query($pago);
					if($pago->num_rows > 0){
						$pago = $pago->fetch_assoc();
						$pago = $pago['Adjunto'];
						$borrar = "DELETE FROM mensajes WHERE mensajes.CodigoEmisor = '$usuario'";

						$borrar = $conexion->query($borrar);
						if($borrar && $conexion->affected_rows > 0){
							unlink($pago);
						}
					}
				}
				$conexion->close();
				echo "<script>window.location.href = \"".$_SERVER['PHP_SELF']."\"</script>";
			}

			if(isset($_GET['rUsuario'])){
				$usuario = $_GET['rUsuario'];
				$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
				$pago = "SELECT Adjunto FROM mensajes WHERE CodigoEmisor = '$usuario'";

				$pago = $conexion->query($pago);
				if($pago->num_rows > 0){
					$pago = $pago->fetch_assoc();
					$pago = $pago['Adjunto'];
					$borrar = "DELETE FROM mensajes WHERE mensajes.CodigoEmisor = '$usuario'";

					$borrar = $conexion->query($borrar);
					if($borrar && $conexion->affected_rows > 0){
						unlink($pago);
					}
				}
				$conexion->close();
				echo "<script>window.location.href = \"".$_SERVER['PHP_SELF']."\"</script>";
			}
		?>
		<script src="js/dashboard.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="	sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
	</body>
</html>