<?php
	if(isset($_SESSION['Codigo'])){
		try{
			$servidor = 'localhost';
	      	$userName = 'root';
	      	$bd = 'pm';
	      	$codigo = $_SESSION['Codigo'];

	      	$conexion = new mysqli($servidor,$userName,"",$bd);
          	if($conexion->connect_error){
            	die("Error al conectarse con la base de datos");
            }

            //Notificaciones
            $notificaciones = "SELECT COUNT(u.Codigo) AS Codigo FROM recibe r,usuarios u, notificaciones n WHERE r.CodigoUsuario = u.Codigo AND u.Codigo = '$codigo' AND r.CodigoNotificacion = n.Codigo AND r.Vista ='0'";
            $notificaciones = $conexion->query($notificaciones);
            if($notificaciones->num_rows > 0){
            	$notificaciones = $notificaciones->fetch_assoc();
            	$notificaciones = $notificaciones['Codigo'];
            }

            //Foto de perfil
         	$sqlLogo = "SELECT FotoPerfil FROM usuarios WHERE Codigo ='$codigo'";
            $fotoPerfil = $conexion->query($sqlLogo);
            if($fotoPerfil && $conexion->affected_rows > 0){
            	$fotoPerfil = $fotoPerfil->fetch_assoc();
            	if($fotoPerfil['FotoPerfil'] == ""){
					$fotoPerfil = "";
				}
            }

            //Control para evitar que admins creen y cursen cursos
            $sqlAdmin = "SELECT u.Codigo FROM usuarios u, tipousuarios t WHERE u.Codigo = '$codigo' AND Es = t.Codigo AND Tipo = 'Admin'";
            $esAdmin = $conexion->query($sqlAdmin);
            if($esAdmin->num_rows > 0){
            	header("Location: dashboard.php");
            }

      	}catch(Exception $e){
      		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
      	}
	}else{
		$fotoPerfil = "";
	}
?>
<header>
	<nav class="navbar navbar-expand-md navbar-dark bg-principal">
    	<div class="container-fluid">
      		<a class="navbar-brand text-white" href="index.php"><img class="logoImg" src="iconos/logo.png">CursoLandia</a>
	      	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	        	<span class="navbar-toggler-icon"></span>
	      	</button>
	      	<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<?php
							if($_SERVER['PHP_SELF'] == "/PM/index.php"){
								echo "<a class=\"nav-link blanco-t\" aria-current=\"page\" href=\"index.php\">Inicio</a>";
							}else{
								echo "<a class=\"nav-link gris-t\" href=\"index.php\">Inicio</a>";
							}
						?>
					</li>
					<li class="nav-item">
						<?php
							if($_SERVER['PHP_SELF'] == "/PM/cursando.php"){
								echo "<a class=\"nav-link blanco-t\" aria-current=\"page\" href=\"cursando.php\">Cursando</a>";
							}else{
								echo "<a class=\"nav-link gris-t\" href=\"cursando.php\">Cursando</a>";
							}
						?>  						
					</li>        					
					<li class="nav-item">
						<?php
							if($_SERVER['PHP_SELF'] == "/PM/dictando.php"){
								echo "<a class=\"nav-link blanco-t\" aria-current=\"page\" href=\"dictando.php\">Dictando</a>";
							}else{
								echo "<a class=\"nav-link gris-t\" href=\"dictando.php\">Dictando</a>";
							}
						?>
  						
					</li>
				</ul>
				<?php
					if($_SERVER['PHP_SELF'] != '/PM/notificaciones.php'){
						echo "<div class=\"me-5 mt-2\"><a href=\"notificaciones.php\" class=\"position-relative text-white\"><i class=\"bi bi-bell-fill\"></i><span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger\">";
		                if($notificaciones > 0){ 
		                	echo $notificaciones;
		                } 
		                echo "</span></a></div>";
					}
				?>				
				<div class="dropdown-center">
  					<a type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<?php
							if($fotoPerfil == ""){
								echo "<div class=\"blanco-t\"><img class=\"img-miniatura\" src=\"imagenes/usuarios/usuario-icono.png\"></i></div>";
							}else{
								echo "<div class=\"blanco-t\"><img class=\"img-miniatura\" src=\"".$fotoPerfil['FotoPerfil']."\"></i></div>";
							}
						?>
					</a>
					<ul class="dropdown-menu dropdown-menu-lg-end dropdown-menu-dark">
				    	<li><a class="dropdown-item" href="perfil.php">Tu perfil</a></li>
				    	<li><a class="dropdown-item" href="notificaciones.php">Notificaciones</a></li>
				    	<li>
				    		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				    			<input type="submit" name="cerrarSesion" class="dropdown-item" value="Cerrar sesión">
				    		</form>
				    	</li>
				  	</ul>
				</div>
			</div>
		</div>
  	</nav>
</header>

<?php
	if(isset($_POST['cerrarSesion'])){
		session_destroy();
		header("Location: index.php");
	}
?>