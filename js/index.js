function validarEmailLogIn(){
	const exp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
	var email = document.getElementById("inputEmailLogIn").value;
	var valido = Boolean(true);
	if(!exp.test(email)){
		document.getElementById("alertaEmailLogIn").classList.remove("visually-hidden");
    	document.getElementById("inputEmailLogIn").classList.add("border");
    	document.getElementById("inputEmailLogIn").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaEmailLogIn").classList.add("visually-hidden");
      	document.getElementById("inputEmailLogIn").classList.remove("border");
      	document.getElementById("inputEmailLogIn").classList.remove("border-danger");
	}
	return valido;
}

function validarPasswordLogIn(){
	var password = document.getElementById("inputPasswordLogIn").value;
	var valido = Boolean(true);
	if(password.length < 8 || password.length > 20){
		document.getElementById("alertaPasswordLogIn").classList.remove("visually-hidden");
    	document.getElementById("inputPasswordLogIn").classList.add("border");
    	document.getElementById("inputPasswordLogIn").classList.add("border-danger");
	    valido = Boolean(false);
	}else{
		document.getElementById("alertaPasswordLogIn").classList.add("visually-hidden");
      	document.getElementById("inputPasswordLogIn").classList.remove("border");
      	document.getElementById("inputPasswordLogIn").classList.remove("border-danger");
	}
	return valido;
}

formLogIn = document.getElementById("formLogIn");
formLogIn.addEventListener("click",function(evt){
		evt.preventDefault();
		email = validarEmailLogIn();
		password = validarPasswordLogIn();
		if(email && password){
			document.getElementById("formLogIn").submit();
		}
	}
)