<div class="m-0 row justify-content-center">
	<div class="col-sm-4 me-2 border border-primary card pt-2">
		<i class="bi bi-patch-check-fill fs-5 text-center"></i>
	  	<div class="card-body">
	    	<h5 class="card-title text-center">Cuenta PRO</h5>
	    	<h6 class="text-center text-primary">2.000 ARS</h6>
	    	<ul class="list-group list-group-flush">
		  		<li class="list-group-item"><i class="bi bi-check-circle"></i> Sin límite de cursos creados activos.</li>
			  	<li class="list-group-item"><i class="bi bi-check-circle"></i> Sin límite de archivos adjuntos por curso.</li>
			  	<li class="list-group-item"><i class="bi bi-check-circle"></i> Sin límite de tamaño por archivo.</li>
			  	<li class="list-group-item"><i class="bi bi-check-circle"></i> Sin límite de inscripciones a cursos.</li>
			  	<li class="list-group-item"><i class="bi bi-check-circle"></i> Tus cursos aparecerán de forma destacada.</li>
			</ul>
			<div class="text-center mt-2">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal" data-bs-whatever="@mdo">Hazte PRO</button>
			</div>
			<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h1 class="modal-title fs-5 fw-bold" id="modalLabel">Pago adjunto:</h1>
			        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			      		</div>
			      		<div class="modal-body">
				        	<form id="formPago" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
				          		<div class="mb-3">
				            		<input id="inputPago" class="form-control" type="file" name="inputPago" style="display: block;">
				            		<label id="alertaPago" class="text-danger visually-hidden">Debe seleccionar un archivo pdf.</label>
				          		</div>			          
				        	</form>
			      		</div>
			      		<div class="modal-footer">
			        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			        		<button type="button" id="btnEnviar" class="btn btn-primary">Solicitar cuenta</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
	  	</div>
	</div>
	<div class="col-sm-4 ms-2 border border-secondary card pt-2">
		<i class="bi bi-patch-minus-fill fs-5 text-center"></i>
	  	<div class="card-body">
	    	<h5 class="card-title text-center">Cuenta gratuita</h5>
	    	<h6 class="text-center text-secondary">0,0 ARS</h6>
	    	<ul class="list-group list-group-flush">
			  <li class="list-group-item"><i class="bi bi-x-circle"></i> Hasta 3 cursos creados activos.</li>
			  <li class="list-group-item"><i class="bi bi-x-circle"></i> Hasta 5 archivos adjuntos por curso.</li>
			  <li class="list-group-item"><i class="bi bi-x-circle"></i> Hasta 5mb por archivo y 10mb en total.</li>
			  <li class="list-group-item"><i class="bi bi-x-circle"></i> Hasta 3 inscripciones a cursos simultáneos.</li>	  
			</ul>
	  	</div>
	</div>
</div>

<?php
	if(isset($_FILES['inputPago'])){
		$conexion = new mysqli($servidor,$nombreUsuario,"",$bd);
		$sqlSolicitudes = "SELECT Codigo FROM mensajes WHERE CodigoEmisor = '$codigo'";

		$resultado = $conexion->query($sqlSolicitudes);
		if($resultado->num_rows > 0){
			echo "<script>alert(\"Usted ya solicito la cuenta pro, si no lo responden en 24hs vuelvalo a intentar.\")</script>";
		}else{
			if($_FILES['inputPago']['type'] == 'application/pdf'){			

				$ubicacion = "archivos/pagos/comprobante-Codigo=".$codigo.".pdf";		

				$sqlPago = "INSERT INTO mensajes(Adjunto, CodigoEmisor) VALUES ('$ubicacion','$codigo')";
				$resultado = $conexion->query($sqlPago);
				if($resultado && $conexion->affected_rows > 0){
					move_uploaded_file($_FILES['inputPago']['tmp_name'], $ubicacion);
					echo "<script>alert(\"Un administrador revisara tu solicitud pronto, gracias.\")</script>";
				}
			}else{
				echo "<script>alert(\"Solo se acepta formato pdf\")</script>";
			}
		}		
	}
?>