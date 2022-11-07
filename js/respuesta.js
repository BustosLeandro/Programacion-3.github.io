function validarRespuesta(){
	var inputRespuesta = document.getElementById("inputRespuesta").value;
	var valido = Boolean(true);
	console.log(inputRespuesta.length);
	if(inputRespuesta.length < 5 || inputRespuesta> 200){
		document.getElementById("inputRespuesta").classList.add("border");
		document.getElementById("inputRespuesta").classList.add("border-danger");
		document.getElementById("alertaRespuesta").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inputRespuesta").classList.remove("border");
		document.getElementById("inputRespuesta").classList.remove("border-danger");
		document.getElementById("alertaRespuesta").classList.add("visually-hidden");
	}
	return valido;
}

formRespuesta = document.getElementById("formRespuesta");
formRespuesta.addEventListener("submit",function(evt){
	evt.preventDefault();
	if(validarRespuesta()){
		console.log("ENTRA");
		document.getElementById("formRespuesta").submit();
	}
});

inputRespuesta = document.getElementById("inputRespuesta");
inputRespuesta.addEventListener("click",function(){
	document.getElementById("botonRespuesta").classList.remove("visually-hidden");
});