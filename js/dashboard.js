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