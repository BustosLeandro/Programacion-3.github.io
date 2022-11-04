function validarTitulo(){
	var titulo = document.getElementById("inpuTitulo").value;
	var valido = Boolean(true);
	if(titulo.length < 5 || titulo.length > 50){
		document.getElementById("inpuTitulo").classList.add("border");
		document.getElementById("inpuTitulo").classList.add("border-danger");
		document.getElementById("alertaTitulo").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inpuTitulo").classList.remove("border");
		document.getElementById("inpuTitulo").classList.remove("border-danger");
		document.getElementById("alertaTitulo").classList.add("visually-hidden");
	}
	return valido;
}

function validarCosto(){
	var costo = document.getElementById("inputCosto").value;
	var valido = Boolean(true);
	if(costo.length<=0){
		document.getElementById("inputCosto").classList.add("border");
		document.getElementById("inputCosto").classList.add("border-danger");
		document.getElementById("alertaCosto").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		if(costo < 0){
			document.getElementById("inputCosto").classList.add("border");
			document.getElementById("inputCosto").classList.add("border-danger");
			document.getElementById("alertaCosto").classList.remove("visually-hidden");
			valido = Boolean(false);
		}else{
			document.getElementById("inputCosto").classList.remove("border");
			document.getElementById("inputCosto").classList.remove("border-danger");
			document.getElementById("alertaCosto").classList.add("visually-hidden");
		}
	}
	return valido;
}

function validarDesc(){
	var desc = document.getElementById("inputDesc").value;
	var valido = Boolean(true);
	if(desc.length < 10 || desc.length > 200){
		document.getElementById("inputDesc").classList.add("border");
		document.getElementById("inputDesc").classList.add("border-danger");
		document.getElementById("alertaDesc").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inputDesc").classList.remove("border");
		document.getElementById("inputDesc").classList.remove("border-danger");
		document.getElementById("alertaDesc").classList.add("visually-hidden");
	}
	return valido;
}

function validarFechaInicio(){
	var valido = Boolean(true);
	//Obtengo la fecha actual
	const fecha = new Date();
	var dia = fecha.getDate();
	if(dia<=9){
		dia.toString();
		dia = "0" + dia;
	}
	var mes = fecha.getMonth()+1;
	if(mes<=9){
		mes.toString();
		mes = "0" + mes;
	}
	const hoy = fecha.getFullYear() + "-" + mes + "-" + dia;

	//Valido que la fecha de inicio no sea menor a la fecha actual
	var fechaIn = document.getElementById("inputFechaInicio").value;
	if(fechaIn < hoy){
		document.getElementById("inputFechaInicio").classList.add("border");
		document.getElementById("inputFechaInicio").classList.add("border-danger");
		document.getElementById("alertaFechaInicio").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inputFechaInicio").classList.remove("border");
		document.getElementById("inputFechaInicio").classList.remove("border-danger");
		document.getElementById("alertaFechaInicio").classList.add("visually-hidden");
	}
	return valido;
}

function validarFechaFin(){
	var valido = Boolean(true);
	var fechaIn = document.getElementById("inputFechaInicio").value;
	var fechaFin = document.getElementById("inputFechaFin").value;
	if(fechaFin < fechaIn){
		document.getElementById("inputFechaFin").classList.add("border");
		document.getElementById("inputFechaFin").classList.add("border-danger");
		document.getElementById("alertaFechaFin").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inputFechaFin").classList.remove("border");
		document.getElementById("inputFechaFin").classList.remove("border-danger");
		document.getElementById("alertaFechaFin").classList.add("visually-hidden");
	}
	return valido;
}

formulario = document.getElementById("crearCurso");
formulario.addEventListener("submit",function(evt){
	evt.preventDefault();
	var titulo = validarTitulo();
	var costo = validarCosto();
	var descripcion = validarDesc();
	var fechaIn = validarFechaInicio();
	var fechaFin = validarFechaFin();
	if(titulo && costo && descripcion && fechaIn && fechaFin){
		document.getElementById("crearCurso").submit();
	}
})