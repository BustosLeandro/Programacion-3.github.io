//FUNCIONES PARA SELECCIONAR PANELES

function selCuenta(){
	document.getElementById("pnlCuenta").classList.remove("visually-hidden");
	document.getElementById("pnlPassword").classList.add("visually-hidden");
	document.getElementById("pnlIntereses").classList.add("visually-hidden");

	document.getElementById("aCuenta").classList.remove("text-secondary");
	document.getElementById("aCuenta").classList.add("active");
	document.getElementById("aPassword").classList.add("text-secondary");
	document.getElementById("aPassword").classList.remove("active");
	document.getElementById("aIntereses").classList.add("text-secondary");
	document.getElementById("aIntereses").classList.remove("active");
}

function selPassword(){
	document.getElementById("pnlPassword").classList.remove("visually-hidden");
	document.getElementById("pnlCuenta").classList.add("visually-hidden");
	document.getElementById("pnlIntereses").classList.add("visually-hidden");

	document.getElementById("aPassword").classList.remove("text-secondary");
	document.getElementById("aPassword").classList.add("active");
	document.getElementById("aCuenta").classList.add("text-secondary");
	document.getElementById("aCuenta").classList.remove("active");
	document.getElementById("aIntereses").classList.add("text-secondary");
	document.getElementById("aIntereses").classList.remove("active");
}

function selIntereses(){
	document.getElementById("pnlIntereses").classList.remove("visually-hidden");
	document.getElementById("pnlCuenta").classList.add("visually-hidden");
	document.getElementById("pnlPassword").classList.add("visually-hidden");

	document.getElementById("aIntereses").classList.remove("text-secondary");
	document.getElementById("aIntereses").classList.add("active");
	document.getElementById("aCuenta").classList.add("text-secondary");
	document.getElementById("aCuenta").classList.remove("active");
	document.getElementById("aPassword").classList.add("text-secondary");
	document.getElementById("aPassword").classList.remove("active");
}

//VALIDACION DE MODIFICAR PERFIL

function validarNombre(){
	var nombre = document.getElementById("inputNombre").value;
	const caracteresInvalidos = "[\]^_`";
  	var valido = Boolean(true);
  	if(nombre.length < 3 || nombre.length > 20){
    	document.getElementById("alertaNombre").classList.remove("visually-hidden");document.getElementById("inputNombre").classList.add("border");
    	document.getElementById("inputNombre").classList.add("border-danger");
	    valido = Boolean(false);
	  }else{
	    for(let caracter of nombre){
	      if(caracter < 'A' || caracter > 'z' || caracteresInvalidos.includes(caracter)){
	        document.getElementById("alertaNombre").classList.remove("visually-hidden");
	        document.getElementById("inputNombre").classList.add("border");
	        document.getElementById("inputNombre").classList.add("border-danger");
	        valido = Boolean(false);
	    }
	    if(valido){
	      document.getElementById("alertaNombre").classList.add("visually-hidden");
	      document.getElementById("inputNombre").classList.remove("border");
	      document.getElementById("inputNombre").classList.remove("border-danger");
	    }
	  }
	}
  	return valido;
}

function validarApellido(){
	var apellido = document.getElementById("inputApellido").value;
	const caracteresInvalidos = "[\]^_`";
  	var valido = Boolean(true);
  	if(apellido.length < 3 || apellido.length > 20){
    	document.getElementById("alertaApellido").classList.remove("visually-hidden");
    	document.getElementById("inputApellido").classList.add("border");
    	document.getElementById("inputApellido").classList.add("border-danger");
	    valido = Boolean(false);
	  }else{
	    for(let caracter of apellido){
	      if(caracter < 'A' || caracter > 'z' || caracteresInvalidos.includes(caracter)){
	        document.getElementById("alertaApellido").classList.remove("visually-hidden");
	        document.getElementById("inputApellido").classList.add("border");
	        document.getElementById("inputApellido").classList.add("border-danger");
	        valido = Boolean(false);
	    }
	    if(valido){
	      document.getElementById("alertaApellido").classList.add("visually-hidden");
	      document.getElementById("inputApellido").classList.remove("border");
	      document.getElementById("inputApellido").classList.remove("border-danger");
	    }
	  }
	}
  	return valido;
}

function validarTelefono(){
	const exp = /^\(?\d{2}\)?[\s\.-]?\d{4}[\s\.-]?\d{4}$/;
	var telefono = document.getElementById("inputTelefono").value;
	var valido = Boolean(true);
	if(!exp.test(telefono)){
		document.getElementById("alertaTelefono").classList.remove("visually-hidden");
    	document.getElementById("inputTelefono").classList.add("border");
    	document.getElementById("inputTelefono").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaTelefono").classList.add("visually-hidden");
      	document.getElementById("inputTelefono").classList.remove("border");
      	document.getElementById("inputTelefono").classList.remove("border-danger");
	}
	return valido;
}

function validarEmail(){
	const exp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
	var email = document.getElementById("inputEmail").value;
	var valido = Boolean(true);
	if(!exp.test(email)){
		document.getElementById("alertaEmail").classList.remove("visually-hidden");
    	document.getElementById("inputEmail").classList.add("border");
    	document.getElementById("inputEmail").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaEmail").classList.add("visually-hidden");
      	document.getElementById("inputEmail").classList.remove("border");
      	document.getElementById("inputEmail").classList.remove("border-danger");
	}
	return valido;
}

formPerfil = document.getElementById("formPerfil");
formPerfil.addEventListener("submit",function(evt){
	evt.preventDefault();
	nombre = validarNombre();
	apellido = validarApellido();
	email = validarEmail();
	telefono = validarTelefono();
	if(nombre && apellido && email && telefono){
		document.getElementById("formPerfil").submit();
	}
})

//VALIDACION DE MODIFICAR CONTRASEÑA

function validarPasswordA(){
	var pAnterior = document.getElementById("inputPasswordA").value;
	var valido = Boolean(true);
	if(pAnterior.length < 8 || pAnterior > 20){
		document.getElementById("alertaPasswordA").classList.remove("visually-hidden");
    	document.getElementById("inputPasswordA").classList.add("border");
    	document.getElementById("inputPasswordA").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaPasswordA").classList.add("visually-hidden");
    	document.getElementById("inputPasswordA").classList.remove("border");
    	document.getElementById("inputPasswordA").classList.remove("border-danger");
	}
	return valido;
}

function validarPasswordN(){
	var pNueva = document.getElementById("inputPasswordN").value;
	var valido = Boolean(true);
	if(pNueva.length < 8 || pNueva > 20){
		document.getElementById("alertaPasswordN").classList.remove("visually-hidden");
    	document.getElementById("inputPasswordN").classList.add("border");
    	document.getElementById("inputPasswordN").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaPasswordN").classList.add("visually-hidden");
    	document.getElementById("inputPasswordN").classList.remove("border");
    	document.getElementById("inputPasswordN").classList.remove("border-danger");
	}
	return valido;
}

function validarPasswordC(){
	var pConfirmada = document.getElementById("inputPasswordC").value;
	var pNueva = document.getElementById("inputPasswordN").value;
	var valido = Boolean(true);
	if(pNueva != pConfirmada){
		document.getElementById("alertaPasswordC").classList.remove("visually-hidden");
    	document.getElementById("inputPasswordC").classList.add("border");
    	document.getElementById("inputPasswordC").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaPasswordC").classList.add("visually-hidden");
    	document.getElementById("inputPasswordC").classList.remove("border");
    	document.getElementById("inputPasswordC").classList.remove("border-danger");
	}
	return valido;
}

formPassword = document.getElementById("formPassword");
formPassword.addEventListener("submit",function(evt){
	evt.preventDefault();
	pAnterior = validarPasswordA();
	pNueva = validarPasswordN();
	pConfirmada = validarPasswordC();
	if(pAnterior && pNueva && pConfirmada){
		document.getElementById("formPassword").submit();
	}
})