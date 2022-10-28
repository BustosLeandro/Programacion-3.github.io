function selInformacion() {
	document.getElementById("Informacion").classList.remove("visually-hidden");
	document.getElementById("Material").classList.add("visually-hidden");
	document.getElementById("Preguntas").classList.add("visually-hidden");
	document.getElementById("Personas").classList.add("visually-hidden");

	document.getElementById("aInfo").classList.remove("blanco-t");
	document.getElementById("aInfo").classList.add("active");
	document.getElementById("aMaterial").classList.add("blanco-t");
	document.getElementById("aMaterial").classList.remove("active");
	document.getElementById("aPreguntas").classList.add("blanco-t");
	document.getElementById("aPreguntas").classList.remove("active");
	document.getElementById("aPersonas").classList.add("blanco-t");
	document.getElementById("aPersonas").classList.remove("active");
}

function selMaterial() {
	document.getElementById("Material").classList.remove("visually-hidden");
	document.getElementById("Informacion").classList.add("visually-hidden");
	document.getElementById("Preguntas").classList.add("visually-hidden");
	document.getElementById("Personas").classList.add("visually-hidden");

	document.getElementById("aMaterial").classList.remove("blanco-t");
	document.getElementById("aMaterial").classList.add("active");
	document.getElementById("aInfo").classList.add("blanco-t");
	document.getElementById("aInfo").classList.remove("active");
	document.getElementById("aPreguntas").classList.add("blanco-t");
	document.getElementById("aPreguntas").classList.remove("active");
	document.getElementById("aPersonas").classList.add("blanco-t");
	document.getElementById("aPersonas").classList.remove("active");
}

function selPreguntas() {
	document.getElementById("Preguntas").classList.remove("visually-hidden");
	document.getElementById("Informacion").classList.add("visually-hidden");
	document.getElementById("Material").classList.add("visually-hidden");
	document.getElementById("Personas").classList.add("visually-hidden");

	document.getElementById("aPreguntas").classList.remove("blanco-t");
	document.getElementById("aPreguntas").classList.add("active");
	document.getElementById("aInfo").classList.add("blanco-t");
	document.getElementById("aInfo").classList.remove("active");
	document.getElementById("aMaterial").classList.add("blanco-t");
	document.getElementById("aMaterial").classList.remove("active");
	document.getElementById("aPersonas").classList.add("blanco-t");
	document.getElementById("aPersonas").classList.remove("active");
}

function selPersonas(){
	document.getElementById("Personas").classList.remove("visually-hidden");
	document.getElementById("Informacion").classList.add("visually-hidden");
	document.getElementById("Material").classList.add("visually-hidden");
	document.getElementById("Preguntas").classList.add("visually-hidden");

	document.getElementById("aPersonas").classList.remove("blanco-t");
	document.getElementById("aPersonas").classList.add("active");
	document.getElementById("aPreguntas").classList.add("blanco-t");
	document.getElementById("aPreguntas").classList.remove("active");
	document.getElementById("aInfo").classList.add("blanco-t");
	document.getElementById("aInfo").classList.remove("active");
	document.getElementById("aMaterial").classList.add("blanco-t");
	document.getElementById("aMaterial").classList.remove("active");
}