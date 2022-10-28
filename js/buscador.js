let buscador = document.getElementById("buscador");
buscador.addEventListener("submit",function(evt){
	evt.preventDefault();
	input = document.getElementById("inputBuscador").value;
	if(input.length > 0){
		document.getElementById("buscador").submit();
	}	
})