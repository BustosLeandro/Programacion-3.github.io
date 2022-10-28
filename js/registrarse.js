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

function validarDNI(){
	var dni = document.getElementById("inputDNI").value;
	var valido = Boolean(true);
	if(dni < 6000000 || dni > 50000000){
		document.getElementById("alertaDNI").classList.remove("visually-hidden");
    	document.getElementById("inputDNI").classList.add("border");
    	document.getElementById("inputDNI").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaDNI").classList.add("visually-hidden");
      	document.getElementById("inputDNI").classList.remove("border");
      	document.getElementById("inputDNI").classList.remove("border-danger");
	}
	return valido;
}

function validarSexo(){
	var sexo = document.getElementById("selectSexo").value;
	var valido = Boolean(true);
	if(sexo == 'N'){
		document.getElementById("alertaSexo").classList.remove("visually-hidden");
    	document.getElementById("selectSexo").classList.add("border");
    	document.getElementById("selectSexo").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaSexo").classList.add("visually-hidden");
      	document.getElementById("selectSexo").classList.remove("border");
      	document.getElementById("selectSexo").classList.remove("border-danger");
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

function validarPassword(){
	var password = document.getElementById("inputPassword").value;
	var valido = Boolean(true);
	if(password.length < 8 || password.length > 20){
		document.getElementById("alertaPassword").classList.remove("visually-hidden");
    	document.getElementById("inputPassword").classList.add("border");
    	document.getElementById("inputPassword").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaPassword").classList.add("visually-hidden");
      	document.getElementById("inputPassword").classList.remove("border");
      	document.getElementById("inputPassword").classList.remove("border-danger");
	}
	return valido;
}

function validarPassword2(){
	var password = document.getElementById("inputPassword").value;
	var password2 = document.getElementById("inputPassword2").value;
	var valido = Boolean(true);
	if(password != password2){
		document.getElementById("alertaPassword2").classList.remove("visually-hidden");
    	document.getElementById("inputPassword2").classList.add("border");
    	document.getElementById("inputPassword2").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaPassword2").classList.add("visually-hidden");
      	document.getElementById("inputPassword2").classList.remove("border");
      	document.getElementById("inputPassword2").classList.remove("border-danger");
	}
	return valido;
}

formRegistro = document.getElementById("formRegistro");
formRegistro.addEventListener("submit",function(evt){
		evt.preventDefault();
		nombre = validarNombre();
		apellido = validarApellido();
		dni = validarDNI();
		sexo = validarSexo();
		telefono = validarTelefono();
		email = validarEmail();
		password = validarPassword();
		password2 = validarPassword2();
		if(nombre && apellido && dni && sexo && telefono && email && password && password2){
			document.getElementById("formRegistro").submit();
		}
	}
)