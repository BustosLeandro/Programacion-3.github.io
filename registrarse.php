<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CursoLandia-Registrarse</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
	</head>
	<body>
		<?php
			session_start();
			if(isset($_SESSION['Codigo'])){
    			header("Location:index.php");
  			}
			include 'cabecera-visitante.html';
			$nombre = "";
			$apellido = "";
			$dni = "";
			$telefono = "";
			$email = "";

			//Validación php
			if(isset($_POST['inputNombre'])){
	  				$valido = true;
	  				$caracteresInvalidos = "[\]^_`";
	  				if(strlen($_POST['inputNombre']) < 3 || strlen($_POST['inputNombre']) > 20){
	  					$valido = false;
	  				}else{
	  					if(!preg_match("/^[a-zA-Z'-]+$/",$_POST['inputNombre'])){
	  						$valido = false;
  						}
	  				}
	  				if(strlen($_POST['inputApellido']) < 3 || strlen($_POST['inputApellido']) > 20){
	  					$valido = false;
	  				}else{
	  					if(!preg_match("/^[a-zA-Z'-]+$/",$_POST['inputApellido'])){
	  						$valido = false;
	  					}
	  				}
	  				if($_POST['inputDNI']<6000000 || $_POST['inputDNI']>50000000){
	  					$valido = false;
	  				}
	  				if(!isset($_POST['selectSexo'])){
	  					$valido = false;
	  				}
	  				if(!preg_match('/^[0-9]{10}+$/',$_POST['inputTelefono'])){
	  					$valido = false;
	  				}
	  				if(!preg_match('/^[a-zA-Z0-9._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/',$_POST['inputEmail'])){
	  					$valido = false;
	  				}
	  				if(strlen($_POST['inputPassword']) < 8 || strlen($_POST['inputPassword']) > 20){
	  					$valido = false;
	  				}
	  				if($_POST['inputPassword'] != $_POST['inputPassword2']){
	  					$valido = false;
	  				}
	  				if(!$valido){
	  					$nombre = $_POST['inputNombre'];
						$apellido = $_POST['inputApellido'];
						$dni = $_POST['inputDNI'];
						$telefono = $_POST['inputTelefono'];
						$email = $_POST['inputEmail'];
						echo "<script>alert(\"Error con los campos ingresados\")</script>";
	  				}else{
	  					//Guardado en la BD
	  					try{
	  						//Conexion
	  						$servidor = 'localhost';
	  						$nombreUsuario = 'root';
	  						$bd = 'pm';

	  						$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
	  						if($conexion->connect_error){
	  							die("Error al conectarse con la base de datos: ".$conexion->connect_error);
	  						}

	  						$nombre = $_POST['inputNombre'];
	  						$apellido = $_POST['inputApellido'];
	  						$telefono = $_POST['inputTelefono'];
	  						$dni = $_POST['inputDNI'];
	  						$sexo = $_POST['selectSexo'];
	  						$email = $_POST['inputEmail'];
	  						$password = $_POST['inputPassword'];
	  						$password = password_hash($password, PASSWORD_DEFAULT,['cost' => 10]);

	  						//Validación de ingreso de una persona con el mismo sexo y dni
	  						if($sexo == 'M'){
	  							$sexo = true;
	  							$consultaDniSexo = "SELECT Codigo AS Sexo FROM usuarios WHERE Dni = '$dni' AND Sexo = '$sexo'";
	  						}else{
	  							$sexo = false;
	  							$consultaDniSexo = "SELECT Codigo AS Sexo FROM usuarios WHERE Dni = '$dni' AND Sexo = '$sexo'";
	  						}

	  						//Validacion de ingreso de una persona con el mismo email
	  						$consultaEmail ="SELECT Codigo AS Email FROM usuarios WHERE Email = '$email'";
	  						
	  						//Realizacion de las consultas anteriores
	  						$resultadoSexo = $conexion->query($consultaDniSexo);
	  						$resultadoEmail = $conexion->query($consultaEmail);
	  						
							//Si no hay personas con el mismo sexo y dni o con el mismo email ya registradas
	  						if($resultadoSexo->num_rows > 0 || $resultadoEmail->num_rows >0){
	  							echo "<script>alert(\"La persona ingresada ya tiene una cuenta creada\")</script>";
	  						}else{
	  							//Realiza el insert
	  							$insertar = "INSERT INTO usuarios (Nombre,Apellido,Dni,Sexo,Telefono,Email,Password,Es) VALUES ('$nombre','$apellido','$dni','$sexo','$telefono','$email','$password','3')";
	  							$resultado = $conexion->query($insertar);
	  							//Redirige al home
	  							if($resultado && $conexion->affected_rows > 0){
	  								$sqlCodigo = "SELECT MAX(Codigo) AS Codigo FROM usuarios";
	  								$codigo = $conexion->query($sqlCodigo);
	  								$codigo = $codigo->fetch_assoc();
	  								$_SESSION['Codigo'] = $codigo['Codigo'];
	  							}
	  							echo "<script type=\"text/javascript\">window.location.href = \"index.php\";</script>";
	  						}

	  					}catch(Exception $e){
	  						echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
	  					}
	  				}
	  			}
		?>

		<div class="container-fluid mt-5">
			<div class="container-fluid col-8 bg-principal text-center">
				<label class="form-label text-white">Formulario de registro</label>
			</div>
			<form id="formRegistro" class="container-fluid col-8 bg-light mb-5" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<div class="row">
					<div class="col-sm-6 mb-3">
						<label for="inputNombre" class="form-label">Nombre</label>
						<input type="text" class="form-control" id="inputNombre" name="inputNombre" value="<?php echo $nombre; ?>" placeholder="Ingrese su nombre...">
						<div id="alertaNombre" class="form-text text-danger visually-hidden">
		    				El nombre ingresado es inválido
		    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El nombre debe tener entre 3 y 20 caracteres."></i>
		    			</div>
					</div>
					<div class="col-sm-6 mb-3">
						<label for="inputApellido" class="form-label">Apellido</label>
						<input type="text" class="form-control" id="inputApellido" name="inputApellido" value="<?php echo $apellido; ?>" placeholder="Ingrese su apellido...">
						<div id="alertaApellido" class="form-text text-danger visually-hidden">
		    				El apellido ingresado es inválido
		    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="El apellido debe tener entre 3 y 20 caracteres."></i>
		    			</div>
					</div>
					<div class="col-sm-6 mb-3">
						<label for="inputDNI" class="form-label">DNI</label>
						<input type="number" class="form-control" id="inputDNI" name="inputDNI" value="<?php echo $dni; ?>" placeholder="Ingrese su DNI...">
						<div id="alertaDNI" class="form-text text-danger visually-hidden">
		    				El DNI ingresado es inválido
		    				<i class="bi bi-info-circle-fill"></i>
		    			</div>
					</div>
					<div class="col-sm-6 mb-3">
						<label for="selectSexo" class="form-label">Sexo</label>
						<select id="selectSexo" name="selectSexo" class="form-select" aria-label="Default select example">
  							<option disabled selected value="N">Seleccione su sexo...</option>
  							<option value="M">Masculino</option>
  							<option value="F">Femenino</option>
  						</select>
  						<div id="alertaSexo" class="form-text text-danger visually-hidden">
		    				Debe seleccionar un sexo
		    				<i class="bi bi-info-circle-fill"></i>
		    			</div>
					</div>
					<div class="col-sm-6 mb-3">
						<label for="inputTelefono" class="form-label">Teléfono</label>
						<input type="number" class="form-control" id="inputTelefono" name="inputTelefono" value="<?php echo $telefono; ?>" placeholder="Ingrese su número de teléfono">
						<div id="alertaTelefono" class="form-text text-danger visually-hidden">
		    				El número de teléfono ingresado es inválido
		    				<i class="bi bi-info-circle-fill"></i>
		    			</div>
					</div>
		  			<div class="col-sm-6 mb-3">
		    			<label for="inputEmail" class="form-label">Correo electrónico</label>
		    			<input type="email" class="form-control" id="inputEmail" name="inputEmail" value="<?php echo $email; ?>" placeholder="nombre@ejemplo.com">
		    			<div id="alertaEmail" class="form-text text-danger visually-hidden">
		    				El correo electrónico ingresado es inválido
		    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Un nombre de usuario de entre 8 y 20 caracteres, un '@' y un '.com'"></i>
							</svg>
		    			</div>
		  			</div>
		  			<div class="col-sm-6 mb-3">
		    			<label for="inputPassword" class="form-label">Contraseña</label>
		    			<input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Ingrese su contraseña...">
		    			<div id="alertaPassword" class="form-text text-danger visually-hidden">
		    				La contraseña ingresada es inválida
		    				<i class="bi bi-info-circle-fill" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="La contraseña debe tener entre 8 y 20 caracteres."></i>
		    			</div>
		  			</div>
		  			<div class="col-sm-6 mb-3">
		    			<label for="inputPassword2" class="form-label">Contraseña</label>
		    			<input type="password" class="form-control" id="inputPassword2" name="inputPassword2" placeholder="Repita su contraseña...">
		    			<div id="alertaPassword2" class="form-text text-danger visually-hidden">
		    				Las contraseñas deben coincidir
		    				<i class="bi bi-info-circle-fill"></i>
		    			</div>
		  			</div>
	  			</div>
	  			<div class="row justify-content-center">
			    	<div class="col-sm-2">
			      		<input type="submit" id="botonRegistro" name="botonRegistro" value="Registrarse" class="btn boton-primario">
				    </div>
				</div>
	  		</form>
		</div>
		
		<?php 
	 		include 'footer.html';
	 	?>

		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
		<script>
			const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
			const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
		</script>
		<script src="js/registrarse.js"></script>
	</body>
</html>