let principal = document.querySelector("main")
let navegacion = document.querySelector("ul")
var supercorreos;
	
	fetch("back.php")
	.then(function(response){
		return response.json()
	})
	.then(function(datos){
		console.log(datos)
		let clientes = datos.unique_contacts
		clientes.forEach(function(cliente){
			let elementolista = document.createElement("li")
			elementolista.textContent = cliente
			elementolista.onclick = function(){
				console.log("click en elemento lista")
				filtrar(cliente)
			}
			navegacion.appendChild(elementolista)
			let seccion = document.createElement("section")
			let titulo = document.createElement("h3")
			titulo.textContent = cliente
			seccion.appendChild(titulo)
			principal.appendChild(seccion)
			let correos = datos.emails.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
			supercorreos = correos
			correos.forEach(function(email){
				if(email.from == cliente){
					let articulo = document.createElement("article")
					articulo.innerHTML =
					"<span>" + email.fecha + "</span>" +
					"<span>" + email.to + "</span>" +
					"<span><img src='src/send.png' alt='←' class='flecha-icon'></span>" +
					"<span>" + email.from + "</span>" +
					"<span>" + email.asunto + "</span>"
					seccion.appendChild(articulo)
				}
				if(email.to == cliente){
					let articulo = document.createElement("article")
					articulo.innerHTML =
					"<span>" + email.fecha + "</span>" +
					"<span>" + email.from + "</span>" +
					"<span><img src='src/received.png' alt='→' class='flecha-icon'></span>" +
					"<span>" + email.to + "</span>" +
					"<span>" + email.asunto + "</span>"
					seccion.appendChild(articulo)
				}
			})
		})
	})
	
	function filtrar(cliente){
		let seccion = document.createElement("section")
		let titulo = document.createElement("h3")
		titulo.textContent = cliente
		seccion.appendChild(titulo)
		principal.innerHTML = ""
		supercorreos.forEach(function(email){
			if(email.from == cliente){
				let articulo = document.createElement("article")
				articulo.innerHTML =
					"<span>" + email.fecha + "</span>" +
					"<span>" + email.to + "</span>" +
					"<span><img src='src/send.png' alt='←' class='flecha-icon'></span>" +
					"<span>" + email.from + "</span>" +
					"<span>" + email.asunto + "</span>"
				seccion.appendChild(articulo)
			}
			if(email.to == cliente){
				let articulo = document.createElement("article")
				articulo.innerHTML =
					"<span>" + email.fecha + "</span>" +
					"<span>" + email.from + "</span>" +
					"<span><img src='src/received.png' alt='→' class='flecha-icon'></span>" +
					"<span>" + email.to + "</span>" +
					"<span>" + email.asunto + "</span>"
				seccion.appendChild(articulo)
			}
		})
		principal.appendChild(seccion)
	}