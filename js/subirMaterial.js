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

function validarMaterial(){
	var material = document.getElementById("inputMaterial").value;
	var valido = Boolean(true);
	if(material == ""){
		document.getElementById("inputMaterial").classList.add("border");
		document.getElementById("inputMaterial").classList.add("border-danger");
		document.getElementById("alertaMaterial").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inputMaterial").classList.remove("border");
		document.getElementById("inputMaterial").classList.remove("border-danger");
		document.getElementById("alertaMaterial").classList.add("visually-hidden");
	}
	return valido;
}

formArchivo = document.getElementById("formArchivo");
formArchivo.addEventListener("submit",function(evt){
	evt.preventDefault();
	titulo = validarTitulo();
	material = validarMaterial();
	if(titulo && material){
		document.getElementById("formArchivo").submit();
	}
})