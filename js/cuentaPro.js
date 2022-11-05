function validarPago(){
	var valido = Boolean(true);
	var inputPago = document.getElementById("inputPago");
	var pago = inputPago.value;
	var pdf = /(.pdf)$/i;
	if(pago.length <= 0){
		document.getElementById("inputPago").classList.add("border");
		document.getElementById("inputPago").classList.add("border-danger");
		document.getElementById("alertaPago").classList.remove("visually-hidden");
		valido = Boolean(false);
	}else{
		if(!pdf.exec(pago)){
			document.getElementById("inputPago").classList.add("border");
			document.getElementById("inputPago").classList.add("border-danger");
			document.getElementById("alertaPago").classList.remove("visually-hidden");
			valido = Boolean(false);
		}	
	}
	return valido;
}

btn = document.getElementById("btnEnviar");
btn.addEventListener("click",function(){
	if(validarPago()){
		document.getElementById("formPago").submit();
	}
});