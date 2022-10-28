function validarEmailLogIn(){
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

function validarPasswordLogIn(){
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

formLogIn = document.getElementById("formLogIn");
formLogIn.addEventListener("submit",function(evt){
		evt.preventDefault();
		email = validarEmailLogIn();
		password = validarPasswordLogIn();
		if(email && password){
			document.getElementById("formLogIn").submit();
		}
	}
)
