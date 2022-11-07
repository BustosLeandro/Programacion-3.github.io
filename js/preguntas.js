function validarPregunta(){
	var inputPregunta = document.getElementById("inputPregunta").value;
	var valido = Boolean(true);
	if(inputPregunta.length < 5 || inputPregunta> 200){
		document.getElementById("inputPregunta").classList.add("border");
		document.getElementById("inputPregunta").classList.add("border-danger");
		document.getElementById("alertaPregunta").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		document.getElementById("inputPregunta").classList.remove("border");
		document.getElementById("inputPregunta").classList.remove("border-danger");
		document.getElementById("alertaPregunta").classList.add("visually-hidden");
	}
	return valido;
}

formPregunta = document.getElementById("formPregunta");
formPregunta.addEventListener("submit",function(evt){
	evt.preventDefault();
	if(validarPregunta()){
		document.getElementById("formPregunta").submit();
	}
})

inputPregunta = document.getElementById("inputPregunta");
inputPregunta.addEventListener("click",function(){
	document.getElementById("botonPregunta").classList.remove("visually-hidden");
})