function selPagos(){
	document.getElementById("aPagos").classList.add("active");
	document.getElementById("aEtiquetas").classList.remove("active");

	document.getElementById("pnlPagos").classList.remove("visually-hidden");
	document.getElementById("pnlEtiquetas").classList.add("visually-hidden");
}
function selEtiquetas(){
	document.getElementById("aPagos").classList.remove("active");
	document.getElementById("aEtiquetas").classList.add("active");

	document.getElementById("pnlPagos").classList.add("visually-hidden");
	document.getElementById("pnlEtiquetas").classList.remove("visually-hidden");
}

function validarCrear(){
	let crear = document.getElementById("inputEtiqueta").value;
	const exp = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
	let valido = Boolean(true);
	if(!exp.test(crear)){
		document.getElementById("alertaCrear").classList.remove("visually-hidden");
		document.getElementById("inputEtiqueta").classList.add("border");
		document.getElementById("inputEtiqueta").classList.add("border-danger");
		valido = Boolean(false);
	}else{
		if(crear.length>30){
			document.getElementById("alertaCrear").classList.remove("visually-hidden");
			document.getElementById("inputEtiqueta").classList.add("border");
			document.getElementById("inputEtiqueta").classList.add("border-danger");
			valido = Boolean(false);
		}else{
			document.getElementById("alertaCrear").classList.add("visually-hidden");
			document.getElementById("inputEtiqueta").classList.remove("border");
			document.getElementById("inputEtiqueta").classList.remove("border-danger");
		}
	}
	return valido;
}


function validarSeleccionar(){
	var form = document.getElementById("formModificar");
	var valido = Boolean(false);

	for(var i=0;i < form.tags.length; i++){
		if(form.tags[i].checked){
			valido = Boolean(true);
		}
	}
	if(!valido){
		alert("Debe seleccionar una etiqueta a modificar.");
	}
	return valido;
}

function validarModificar(){
	let modificar = document.getElementById("modificarE").value;
	const exp = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
	let valido = Boolean(true);
	if(!exp.test(modificar)){
		document.getElementById("alertaModificar").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		if(modificar.length>30){
			document.getElementById("alertaCrear").classList.remove("visually-hidden");
			valido = Boolean(false);
		}else{
			document.getElementById("alertaCrear").classList.add("visually-hidden");
		}
	}
	return valido;
}

formModificar = document.getElementById("formModificar");
formModificar.addEventListener("submit",function(evt){
	evt.preventDefault();
	seleccionar = validarSeleccionar();
	modificar = validarModificar();
	if(seleccionar && modificar){
		document.getElementById("formModificar").submit();
	}
})

function validarFecha(){
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
	inputFecha = document.getElementById("fechaVencimiento").value;

	//Valido que la fecha de inicio no sea menor a la fecha actual
	if(inputFecha < hoy){
		document.getElementById("fechaVencimiento").classList.add("border");
		document.getElementById("fechaVencimiento").classList.add("border-danger");
		document.getElementById("alertaFecha").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("fechaVencimiento").classList.remove("border");
		document.getElementById("fechaVencimiento").classList.remove("border-danger");
		document.getElementById("alertaFecha").classList.add("visually-hidden");
	}
	return valido;
}

formFecha = document.getElementById("formFecha");
formFecha.addEventListener("submit",function(evt){
	evt.preventDefault();
	if(validarFecha()){
		document.getElementById("formFecha").submit();
	}
})