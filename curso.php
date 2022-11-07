<?php
	if(!isset($_GET['curso'])){
		header("Location: index.php");
	}

	session_start();
	$curso = $_GET['curso'];
	$servidor = 'localhost';
	$nombreUsuario = 'root';
	$bd = 'pm';

	if(isset($_SESSION['Codigo'])){
		$codigo = $_SESSION['Codigo'];
	}else{
		$codigo = 0;
	}
	

	try{
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		if($conexion->connect_error){
			die("Error al conectarse con la base de datos");
		}		
		$sqlCurso = "SELECT c.*,nombre,apellido FROM cursos c, usuarios u WHERE c.Codigo = '$curso' AND codigoprofesor = u.Codigo";
		$resultadoCurso = $conexion->query($sqlCurso);
		$resultadoCurso = $resultadoCurso->fetch_assoc();
		
		$esProfesor = "SELECT u.Codigo FROM usuarios u, cursos c WHERE u.Codigo = CodigoProfesor AND c.Codigo = '$curso' AND u.Codigo = '$codigo'";
		$esProfesor = $conexion->query($esProfesor);
		if($esProfesor->num_rows == 0){
			$esProfesor = false;
		}

		$esAlumno = "SELECT u.Codigo FROM usuarios u,cursos c, cursa ca WHERE u.Codigo = CodigoEstudiante AND c.Codigo = CodigoCurso AND c.Codigo = '$curso' AND u.Codigo = '$codigo'";
		$esAlumno = $conexion->query($esAlumno);
		if($esAlumno->num_rows == 0){
			$esAlumno = false;
		}
	}catch(Exeption $e){
		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
	}
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $resultadoCurso['Titulo'] ?></title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">
		<script defer src="js/curso.js"></script>
	</head>
	<body>
		<?php
			if(isset($_SESSION['Codigo'])){
				include 'cabecera-logeado.php';
				$codigo = $_SESSION['Codigo'];
			}else{
				include 'cabecera-visitante.html';
				$codigo = 0;
			}
			include 'buscador.html';	
		?>
		<div class="container-fluid text-bg-secundario banner">
			<div class="row">
				<div class="col-12 mt-5">
					<?php 
						echo "<h2 class=\"mb-5\">".$resultadoCurso['Titulo']."</h2><form action=\"curso.php?curso=".$curso."\" method=\"POST\"><p>Profesor: ".$resultadoCurso['apellido']." ".$resultadoCurso['nombre']; 
						if($codigo > 0 && !$esProfesor && !$esAlumno){
							echo"<input class=\"btn boton-primario ms-5\" type=\"submit\" name=\"inscribirme\" value=\"Quiero inscribirme\">";
						}
						echo "</p></form>";
					?>
				</div>
				<ul class="nav justify-content-center bg-principal">
		  			<li class="nav-item">
		    			<a class="nav-link active" href="#" id="aInfo" onclick="selInformacion()">Información</a>
		  			</li>
		  			<li class="nav-item">
		    			<a class="nav-link blanco-t" href="#" id="aMaterial" onclick="selMaterial()">Material</a>
		  			</li>
		  			<li class="nav-item">
		    			<a class="nav-link blanco-t" href="#" id="aPreguntas" onclick="selPreguntas()">Preguntas</a>
		  			</li>
		  			<li class="nav-item">
		    			<a class="nav-link blanco-t" href="#" id="aPersonas" onclick="selPersonas()">Personas</a>
		  			</li>
				</ul>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 ps-3 pt-3 text-dark mb-3" id="Informacion">
							<p>Fecha de inicio: <?php echo $resultadoCurso['FechaInicio'] ?></p>
							<p>Fecha de fin del curso: <?php echo $resultadoCurso['FechaFin'] ?></p>
							<?php
								if(!$resultadoCurso['Cupos'] == NULL){
									$sqlCantEstudiantes = "SELECT COUNT(*) AS cantEstudiantes FROM cursa WHERE CodigoCurso = '$curso'";
									$resultadoCantEstudiantes = $conexion->query($sqlCantEstudiantes);
									$resultadoCantEstudiantes = $resultadoCantEstudiantes->fetch_assoc();

									echo "<p>Cupos: ".$resultadoCantEstudiantes['cantEstudiantes']."/".$resultadoCurso['Cupos']."</p>";
								}
							?>
							<p>Costo: $<?php echo $resultadoCurso['Costo'] ?></p>
						</div>
						<div class="col-12 text-dark visually-hidden mb-3" id="Material">
							<?php
								$sqlMateriales = "SELECT * FROM materiales WHERE codigocurso = '$curso'";
								$materiales = $conexion->query($sqlMateriales);

								if($esProfesor){
									echo "<a class=\"mt-1 btn btn-primary\" href=\"subirMaterial.php?curso=".$curso."\">Subir Material</a>";
								}
								
								if($materiales && $materiales->num_rows > 0){
									echo "<div class=\"container\"><div class=\"row\">";
									while($material = $materiales->fetch_assoc()){
										echo "<a class=\"col-sm-6 container border border-dark mt-3 nav-link\" href=\"{$material['Archivo']}\" target=\"blank\"><div class=\"row\">";
										if($material['Tipo'] == 'application/pdf'){
											echo "<div class=\"col-4 pt-3 text-center\"><i class=\"bi bi-filetype-pdf fs-1\"></i></div>";
										}else{
											echo "<div class=\"col-4 pt-3 text-center\"><i class=\"bi bi-filetype-docx fs-1\"></i></div>";
										}
										//postscript
										echo "<div class=\"col-8 border-start\"><p>Titulo: ".$material['Titulo']."</p><p>Fecha de publicación: ".date("d/m/Y",strtotime($material['FechaSubido']))."</p><p>Descripción: ".$material['Descripcion']."</p></div></div></a>";
									}
									echo "</div></div>";
								}else{
									echo "<div class=\"alert mt-2 text-center\" role=\"alert\">Este curso no tiene materiales aún</div>";
								}

							?>
						</div>
						<div class="col-12 text-dark visually-hidden mb-3" id="Preguntas">
							<form class="col-12 mt-2" id="formPregunta" action="curso.php?curso=<?php echo $curso; ?>" method="POST">
								<div class="form-floating">
							  		<textarea class="form-control" placeholder="Leave a comment here" name="inputPregunta" id="inputPregunta"></textarea>
								  	<label for="inputPregunta">Haz tu pregunta...</label>
								</div>
								<label id="alertaPregunta" class="text-danger visually-hidden">Debe ingresar una pregunta.</label>
								<div  id="botonPregunta" class="card-body d-flex justify-content-between align-items-center mt-2 visually-hidden">
									<span></span>
									<input class="btn btn-secondary btn-sm" type="submit" name="preguntar" value="Preguntar">
								</div>
							</form>
							<?php
								$preguntas = "SELECT p.Codigo, Pregunta, Fecha, Nombre, FotoIcono, esDestacada FROM preguntas p, cursos c, usuarios u WHERE CodigoUsuario = u.Codigo AND CodigoCurso = c.Codigo AND c.Codigo = '$curso'";

								$preguntas = $conexion->query($preguntas);

								while($pregunta = $preguntas->fetch_assoc()){
									if($pregunta['esDestacada']){
										echo "<div class=\"col-12 mt-2 alert alert-info\">";
									}else{
										echo "<div class=\"col-12 mt-2 alert alert-light\">";	
									}									
									if($pregunta['FotoIcono'] == ''){
										echo "<img src=\"imagenes/usuarios/usuario-icono.png\" class=\"img-miniatura\">";
									}else{
										echo "<img src=\"".$pregunta['FotoIcono']."\" class=\"img-miniatura\">";
									}
									echo "<label>".$pregunta['Nombre']." - ".$pregunta['Fecha']."</label><p class=\"ms-2 mt-2\">".$pregunta['Pregunta']."</p>";
									if($esProfesor){
										if($pregunta['esDestacada']){
											echo "<div class=\"card-body d-flex justify-content-between align-items-center\"><div><a href=\"pregunta.php?pregunta=".$pregunta['Codigo']."\" class=\"ms-2\">Ver respuestas/responder</a></div><div><a class=\"me-2\"><i class=\"bi bi-heart-fill\"></i></a><a href=\"curso.php?curso=".$curso."&borrarPregunta=".$pregunta['Codigo']."\"><i class=\"bi bi-trash3\"></i></a></div></div></div>";
										}else{
											echo "<div class=\"card-body d-flex justify-content-between align-items-center\"><div><a href=\"pregunta.php?pregunta=".$pregunta['Codigo']."\" class=\"ms-2\">Ver respuestas/responder</a></div><div><a class=\"me-2\" href=\"curso.php?curso=".$curso."&destacar=".$pregunta['Codigo']."\"><i class=\"bi bi-heart\"></i></a><a href=\"curso.php?curso=".$curso."&borrarPregunta=".$pregunta['Codigo']."\"><i class=\"bi bi-trash3\"></i></a></div></div></div>";
										}
									}else{
										echo "<a href=\"pregunta.php?pregunta=".$pregunta['Codigo']."\" class=\"ms-2\">Ver respuestas/responder</a></div>";
									}
								}
								
							?>
						</div>
						<div class="col-12 ps-3 pt-3 text-dark visually-hidden mb-3" id="Personas">
							<?php
								$sqlProfesor = "SELECT FotoIcono, Nombre, Apellido FROM usuarios u,cursos c WHERE CodigoProfesor = u.Codigo AND c.Codigo = '$curso'";
								$profesor = $conexion->query($sqlProfesor);
								$profesor = $profesor->fetch_assoc();

								$sqlAlumnos = "SELECT u.Codigo, FotoIcono, Nombre, Apellido, PagoPendiente FROM usuarios u,cursos c, cursa ca WHERE CodigoEstudiante = u.Codigo AND CodigoCurso = c.Codigo AND c.Codigo = '$curso'";
								$alumnos = $conexion->query($sqlAlumnos);
							?>
							<h4 class="text-primary border-bottom border-primary border-opacity-50">Profesor</h4>
							<?php
								if($profesor['FotoIcono'] == ""){
									echo "<img class=\"img-miniatura\" src=\"imagenes/usuarios/usuario.png\"></i>";
								}else{
									echo "<img class=\"img-miniatura\" src=\"".$profesor['FotoIcono']."\"></i>";
								}
							?>
							<label> <?php echo $profesor['Nombre']." ".$profesor['Apellido']; ?></label>
							<h4 class="mt-5 text-primary">Alumnos</h4>
							<p class="text-primary border-bottom border-primary border-opacity-50"><?php echo $alumnos->num_rows ?> alumno/s</p>
							<?php
								while($alumno = $alumnos->fetch_assoc()){
									if($alumno['FotoIcono'] == ""){
										echo "<img class=\"img-miniatura\" src=\"imagenes/usuarios/usuario-icono.png\"></i>";
									}else{
										echo "<img class=\"img-miniatura\" src=\"".$alumno['FotoIcono']."\"></i>";
									}
									echo "<label class=\"me-5\">".$alumno['Nombre']." ".$alumno['Apellido']."</label>";
									if($alumno['PagoPendiente'] == 1 && $esProfesor){
										echo "<a class=\"btn boton-primario btn-sm me-2\" href=\"curso.php?curso=$curso&confirmar=".$alumno['Codigo']."\">Confirmar alumno</a><a class=\"btn btn-secondary btn-sm\" href=\"curso.php?curso=".$curso."&eliminar=".$alumno['Codigo']."\">Eliminar alumno</a><br>";
									}else{
										if($esProfesor){
											echo "<a class=\"btn btn-secondary btn-sm\" href=\"curso.php?curso=".$curso."&eliminar=".$alumno['Codigo']."\">Eliminar alumno</a><br>";
										}										
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		if(isset($_POST['inputPregunta'])){
			$inputPregunta = $_POST['inputPregunta'];
			if($esAlumno){
				$insertarPregunta = "INSERT INTO preguntas(Pregunta,CodigoUsuario,CodigoCurso) values('$inputPregunta','$codigo','$curso')";

				$conexion->query($insertarPregunta);
				if($insertarPregunta && $conexion->affected_rows > 0){
					echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
				}else{
					echo "<script>alert(\"Error al subir la pregunta, por favor intentelo de nuevo\");window.location.href = \"curso.php?curso=".$curso."\"</scipt>";
				}
			}else{
				echo "<script>alert(\"Debe ser alumno para poder realizar una pregunta\")</script>";
			}			
		}

		if(isset($_GET['borrarPregunta'])){
			$codigoPregunta = $_GET['borrarPregunta'];
			try{
				$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

				$sqlEliminar = "DELETE FROM preguntas WHERE Codigo = '$codigoPregunta'";
				$resultado = $conexion->query($sqlEliminar);
				if(!$resultado || $conexion->affected_rows == 0){
					echo "<script>alert(\"Error al eliminar la pregunta.\")</script>";
				}
				$conexion->close();
				echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
			}catch(Exception $e){
				echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
			}
		}

		if(isset($_GET['destacar'])){
			$codigoPregunta = $_GET['destacar'];
			try{
				$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

				$sqlDestacar  = "UPDATE	preguntas SET esDestacada = '1' WHERE Codigo = '$codigoPregunta'";
				$resultado = $conexion->query($sqlDestacar);
				if(!$resultado || $conexion->affected_rows == 0){
					echo "<script>alert(\"Error al destacar la pregunta.\")</script>";
				}

				$conexion->close();
				echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
			}catch(Exception $e){
				echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
			}
		}

		if(isset($_GET['eliminar'])){
			$codigoAlumno = $_GET['eliminar'];
			try{
				$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

				$sqlEliminar = "DELETE FROM cursa WHERE CodigoCurso = '$curso' AND CodigoEstudiante = '$codigoAlumno'";
				$resultado = $conexion->query($sqlEliminar);
				if(!$resultado || $conexion->affected_rows == 0){
					echo "<script>alert(\"Error al eliminar el alumno.\")</script>";
				}
				$conexion->close();
				echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
			}catch(Exception $e){
				echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
			}
		}

			if(isset($_POST['inscribirme'])){
				try{
					$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

					//VALIDAR SI ES PRO
					$esPro = "SELECT Tipo FROM usuarios u,tipousuarios t WHERE Es = t.Codigo AND u.Codigo = '$codigo'";
					$esPro = $conexion->query($esPro);
					$esPro = $esPro->fetch_assoc();
					if($esPro['Tipo'] == 'Usuario Pro'){
						if($resultadoCurso['Costo'] == 0){
							$sqlInscribir = "INSERT INTO cursa(CodigoCurso,CodigoEstudiante) VALUES('$curso','$codigo')";
						}else{
							$sqlInscribir = "INSERT INTO cursa(CodigoCurso,CodigoEstudiante,PagoPendiente) VALUES('$curso','$codigo','1')";
						}
						
						$resultado = $conexion->query($sqlInscribir);
						if(!$resultado || $conexion->affected_rows == 0){
							echo "<script>alert(\"Error al prosesar la inscripción.\")</script>";		
						}
						$conexion->close();
						echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
					}else{
						//VALIDAR CANTIDAD DE CURSOS ACTIVOS
						$cantCursos = 0;
						$cursos = "SELECT Titulo,FechaFin FROM cursos c, usuarios u, cursa ca WHERE CodigoCurso = c.Codigo AND CodigoEstudiante = u.Codigo AND u.Codigo = '$codigo'";

						$hoy = $hoy = date('Y-m-d');
						$hoy = date_create($hoy);
						$cursos = $conexion->query($cursos);
						while($unCurso = $cursos->fetch_assoc()){
							$fecha = $unCurso['FechaFin'];
							$fecha = date_create($fecha);		
							$intervalo = date_diff($hoy,$fecha);

							if($intervalo->invert == 0){
								$cantCursos++;
							}							
						}
						if($cantCursos < 3){
							//VALIDAR QUE EL CURSO ESTE ACTIVO
							$fechaFin = "SELECT FechaFin FROM cursos WHERE Codigo = '$curso'";

							$unCurso = $conexion->query($fechaFin);
							$unCurso = $unCurso->fetch_assoc();
							$fechaFin = $unCurso['FechaFin'];
							$fechaFin = date("jS F, Y", strtotime( $fechaFin));
							$fechaFin = strtotime($fechaFin);
							if($fechaFin < $hoy){
								echo "<script>alert(\"El curso ha finalizado\")</script>";
							}else{
								//APTO PARA INSCRIBIRSE
								if($resultadoCurso['Costo'] == 0){
									$sqlInscribir = "INSERT INTO cursa(CodigoCurso,CodigoEstudiante) VALUES('$curso','$codigo')";
								}else{
									$sqlInscribir = "INSERT INTO cursa(CodigoCurso,CodigoEstudiante,PagoPendiente) VALUES('$curso','$codigo','1')";
								}
								
								$resultado = $conexion->query($sqlInscribir);
								if(!$resultado || $conexion->affected_rows == 0){
									echo "<script>alert(\"Error al prosesar la inscripción.\")</script>";		
								}
								$conexion->close();
								echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
								}							
						}else{
							echo "<script>alert(\"Para anotarse a más de 3 cursos en simultaneo debe tener una cuenta PRO\")</script>";
						}				
					}
				}catch(Exception $e){
	          		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
		        }
			}

			if(isset($_FILES['material'])){
				$archivo = 'archivos/materiales/'.basename($_FILES['material']['name']);
				$tipoArchivo = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
				$size = $_FILES['material']['size'];
				if($size > 5242880){
					echo "<script>alert(\"No puede subir archivos de más de 5mb\");</script>";
				}else{
					if($tipoArchivo == "docx" || $tipoArchivo == "pdf"){
						if(move_uploaded_file($_FILES['material']['tmp_name'], $archivo)){
							$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

							$sqlCargar = "INSERT INTO materiales(Archivo,CodigoCurso) VALUES ('$archivo','$curso')";

							$resultado = $conexion->query($sqlCargar);
							if(!$resultado || $conexion->affected_rows == 0){
								echo "<script>alert(\"Error al subir el archivo al sistema.\")</script>";	
							}
							$conexion->close();
							echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
						}else{
							echo "<script>alert(\"Error al subir el archivo al sistema.\")</script>";
						}
					}
				}
			}

			if(isset($_GET['confirmar'])){
				$codigoAlumno = $_GET['confirmar'];
				$sqlConfirmar = "UPDATE cursa SET PagoPendiente = '0' WHERE CodigoEstudiante = '$codigoAlumno' AND CodigoCurso = '$curso'";
				try{
					$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

					$resultado = $conexion->query($sqlConfirmar);
					if(!$resultado || $conexion->affected_rows == 0){
						echo "<script>alert(\"Error al confirmar el usuario.\")</script>";
					}
					$conexion->close();
					echo "<script>window.location.href = \"curso.php?curso=".$curso."\";</script>";
				}catch(Exception $e){
	          		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
		        }
			}

			if(!$esAlumno && !$esProfesor){
				echo"<script defer>document.getElementById(\"aMaterial\").classList.add(\"disabled\"); document.getElementById(\"aPreguntas\").classList.add(\"disabled\");document.getElementById(\"aPersonas\").classList.add(\"disabled\");</script>";
			}else{
				try{
					$sqlPago = "SELECT ca.PagoPendiente FROM cursos c, usuarios u,cursa ca WHERE CodigoCurso = c.Codigo AND CodigoEstudiante = u.Codigo AND u.Codigo = '$codigo' AND c.Codigo = '$curso'";
					$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);

					$pagoPendiente = $conexion->query($sqlPago);
					if($pagoPendiente && $conexion->affected_rows > 0){
						$pagoPendiente = $pagoPendiente->fetch_assoc();
						if($pagoPendiente['PagoPendiente'] == 1){
							echo"<script defer>document.getElementById(\"aMaterial\").classList.add(\"disabled\"); document.getElementById(\"aPreguntas\").classList.add(\"disabled\");document.getElementById(\"aPersonas\").classList.add(\"disabled\");alert(\"Podras acceder al curso completo cuando el profesor confirme tu pago\")</script>";
						}
					}

				}catch(Exception $e){
	          		echo "<script>alert(\"Surgio una excepción del tipo: ".$e."\")</script>";
		        }
			}
			include 'footer.html';
		?>
		<script src="js/preguntas.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	</body>
</html>