function esSolicitante() {
	var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
	console.log("SOL_DIFERENTE_AS: ", valor);
	if (!valor) {
		console.log('por verdad');
		document.querySelector('#NRO_PODER_SOL_1').required = true;
		document.querySelector('#TIENE_PODER_SOL_1').required = true;
		document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
		document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
		_enable("TIENE_PODER_SOL_1");
		//_disable("TIENE_PODER_SOL_1");
		_show("SOL_BUSCAR");
		_enable("SOL_TIPO_DOCUMENTO");
		_enable("SOL_CI");
		_setValue("SOL_CI", "");
		document.getElementById("SOL_CI").dispatchEvent(new Event('input'));
		_enable("SOL_COMPLEMENTO");
		_setValue("SOL_COMPLEMENTO", "");
		document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
		_enable("SOL_NACIMIENTO");
		_setValue("SOL_NACIMIENTO", "");
		document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
		_enable("SOL_CUA");
		_setValue("SOL_CUA", "");
		document.getElementById("SOL_CUA").dispatchEvent(new Event('input'));
		_enable("SOL_PRIMER_APELLIDO");
		_setValue("SOL_PRIMER_APELLIDO", "");
		document.getElementById("SOL_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
		_enable("SOL_SEGUNDO_APELLIDO");
		_setValue("SOL_SEGUNDO_APELLIDO", "");
		document.getElementById("SOL_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
		_enable("SOL_APELLIDO_CASADA");
		_setValue("SOL_APELLIDO_CASADA", "");
		document.getElementById("SOL_APELLIDO_CASADA").dispatchEvent(new Event('input'));
		_enable("SOL_PRIMER_NOMBRE");
		_setValue("SOL_PRIMER_NOMBRE", "");
		document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
		_setValue("SOL_SEGUNDO_NOMBRE", "");
		document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
		_enable("SOL_PARENTESCO");
		_setValue("SOL_PARENTESCO", '0-TIT');
		document.getElementById("SOL_PARENTESCO").dispatchEvent(new Event('input'));
		_enable("SOL_ESTADO_CIVIL");
		_setValue("SOL_ESTADO_CIVIL", "");
		document.getElementById("SOL_ESTADO_CIVIL").dispatchEvent(new Event('input'));
		_setValue("SOL_GENERO");
		_setValue("SOL_GENERO", "");
		document.getElementById("SOL_GENERO").dispatchEvent(new Event('input'));
		_enable("SOL_ZONA");
		_setValue("SOL_ZONA", "");
		document.getElementById("SOL_ZONA").dispatchEvent(new Event('input'));
		_enable("SOL_DIRECCION");
		_setValue("SOL_DIRECCION", "");
		document.getElementById("SOL_DIRECCION").dispatchEvent(new Event('input'));
		_enable("SOL_NUM");
		_setValue("SOL_NUM", "");
		document.getElementById("SOL_NUM").dispatchEvent(new Event('input'));
		_enable("SOL_TELEFONO");
		_setValue("SOL_TELEFONO", "");
		document.getElementById("SOL_TELEFONO").dispatchEvent(new Event('input'));
		_enable("SOL_CELULAR");
		_setValue("SOL_CELULAR", "");
		document.getElementById("SOL_CELULAR").dispatchEvent(new Event('input'));
		_enable("SOL_POSTAL");
		_setValue("SOL_POSTAL", "");
		document.getElementById("SOL_POSTAL").dispatchEvent(new Event('input'));
		_enable("SOL_CORREO");
		_setValue("SOL_CORREO", "");
		document.getElementById("SOL_CORREO").dispatchEvent(new Event('input'));
		_enable("SOL_IDPERSONA");
		_setValue("SOL_IDPERSONA", "");
		document.getElementById("SOL_IDPERSONA").dispatchEvent(new Event('input'));
		_enable("SOL_CIUDAD");
		_setValue("SOL_CIUDAD", "");
		document.getElementById("SOL_CIUDAD").dispatchEvent(new Event('input'));
		_enable("SOL_PROVINCIA");
		_setValue("SOL_PROVINCIA", "");
		document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
		_enable("SOL_DEPARTAMENTO");
		_setValue("SOL_DEPARTAMENTO", "");
		document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
		_enable("NRO_PODER_SOL_1");
		_enable("NRO_NOTARIA_SOL_1");
		_enable("NOMBRE_NOTARIO_SOL_1");

		_setValue("SOL_TIPO_DOCUMENTO", "I");
		document.getElementById("SOL_TIPO_DOCUMENTO").dispatchEvent(new Event('input'));


		// var selectPoder = document.getElementById("TIENE_PODER_SOL_1");
		// selectPoder.innerHTML = "";
		// var option = document.createElement("option");
		// option.value = "1";
		// option.text = "1 - SIIIIIIIIIIIIIIIII";
		// //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
		// selectPoder.appendChild(option);
		// var option = document.createElement("option");
		// option.value = "2";
		// option.text = "2 - NO";
		// //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
		// selectPoder.appendChild(option);

		_enable("SOL_TIPO_DOCUMENTO");
		var selectCedulaIdentidad = document.getElementById("SOL_TIPO_DOCUMENTO");
		selectCedulaIdentidad.innerHTML = "";
		var option = document.createElement("option");
		option.value = "I";
		option.text = "I - CEDULA IDENTIDAD";
		//   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
		selectCedulaIdentidad.appendChild(option);
		var option = document.createElement("option");
		option.value = "E";
		option.text = "E - EXTRANJERO";
		//   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
		selectCedulaIdentidad.appendChild(option);
		var option = document.createElement("option");
		option.value = "P";
		option.text = "P - PASAPORTE";
		//   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
		selectCedulaIdentidad.appendChild(option);
		solicitanteCiudad();
	} else {
		console.log('por falso');
		console.log('entro por falso ');
		document.querySelector('#NRO_PODER_SOL_1').required = false;
		document.querySelector('#TIENE_PODER_SOL_1').required = false;
		document.querySelector('#NRO_NOTARIA_SOL_1').required = false;
		document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = false;
		_hide("SOL_BUSCAR");

		document.getElementById("SOL_PARENTESCO").value = "-";
		document.getElementById("SOL_PARENTESCO").dispatchEvent(new Event('change'));
		_disable("SOL_PARENTESCO");
		_disable("SOL_CI");
		_setValue("SOL_CI", _getValue("AS_CI"));
		document.getElementById("SOL_CI").dispatchEvent(new Event('input'));
		_disable("SOL_COMPLEMENTO");
		_setValue("SOL_COMPLEMENTO", _getValue("AS_COMPLEMENTO"));
		document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
		_disable("SOL_NACIMIENTO");
		_setValue("SOL_NACIMIENTO", _getValue("AS_NACIMIENTO"));
		document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
		_disable("SOL_CUA");
		_setValue("SOL_CUA", _getValue("AS_CUA"));
		document.getElementById("SOL_CUA").dispatchEvent(new Event('input'));
		_disable("SOL_PRIMER_APELLIDO");
		_setValue("SOL_PRIMER_APELLIDO", _getValue("AS_PRIMER_APELLIDO"));
		document.getElementById("SOL_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
		_disable("SOL_SEGUNDO_APELLIDO");
		_setValue("SOL_SEGUNDO_APELLIDO", _getValue("AS_SEGUNDO_APELLIDO"));
		document.getElementById("SOL_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
		_disable("SOL_APELLIDO_CASADA");
		_setValue("SOL_APELLIDO_CASADA", _getValue("AS_APELLIDO_CASADA"));
		document.getElementById("SOL_APELLIDO_CASADA").dispatchEvent(new Event('input'));
		_disable("SOL_PRIMER_NOMBRE");
		_setValue("SOL_PRIMER_NOMBRE", _getValue("AS_PRIMER_NOMBRE"));
		document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
		_disable("SOL_SEGUNDO_NOMBRE");
		_setValue("SOL_SEGUNDO_NOMBRE", _getValue("AS_SEGUNDO_NOMBRE"));
		document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
		_disable("SOL_ESTADO_CIVIL");
		_setValue("SOL_ESTADO_CIVIL", _getValue("AS_ESTADO_CIVIL"));
		document.getElementById("SOL_ESTADO_CIVIL").dispatchEvent(new Event('input'));
		_disable("SOL_GENERO");
		_setValue("SOL_GENERO", _getValue("AS_GENERO"));
		document.getElementById("SOL_GENERO").dispatchEvent(new Event('input'));
		_disable("SOL_ZONA");
		_setValue("SOL_ZONA", _getValue("AS_ZONA"));
		document.getElementById("SOL_ZONA").dispatchEvent(new Event('input'));
		_disable("SOL_DIRECCION");
		_setValue("SOL_DIRECCION", _getValue("AS_DIRECCION"));
		document.getElementById("SOL_DIRECCION").dispatchEvent(new Event('input'));
		_disable("SOL_NUM");
		_setValue("SOL_NUM", _getValue("AS_NUM"));
		document.getElementById("SOL_NUM").dispatchEvent(new Event('input'));
		_disable("SOL_TELEFONO");
		_setValue("SOL_TELEFONO", _getValue("AS_TELEFONO"));
		document.getElementById("SOL_TELEFONO").dispatchEvent(new Event('input'));
		_disable("SOL_CELULAR");
		_setValue("SOL_CELULAR", _getValue("AS_CELULAR"));
		document.getElementById("SOL_CELULAR").dispatchEvent(new Event('input'));
		_disable("SOL_POSTAL");
		_setValue("SOL_POSTAL", _getValue("AS_POSTAL"));
		document.getElementById("SOL_POSTAL").dispatchEvent(new Event('input'));
		_disable("SOL_CORREO");
		_setValue("SOL_CORREO", _getValue("AS_CORREO"));
		document.getElementById("SOL_CORREO").dispatchEvent(new Event('input'));
		_disable("SOL_IDPERSONA");
		_setValue("SOL_IDPERSONA", _getValue("AS_IDPERSONA"));
		document.getElementById("SOL_IDPERSONA").dispatchEvent(new Event('input'));

		var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
		var selectElement = document.getElementById("AS_TIPO_DOCUMENTO");
		var selectedIndex = selectElement.selectedIndex;
		var selectedText = selectElement.options[selectedIndex].innerText;
		var selectTipoDocumento = document.getElementById("SOL_TIPO_DOCUMENTO");
		selectTipoDocumento.innerHTML = "";
		var option = document.createElement("option");
		option.value = tipoDocumento;
		option.text = selectedText;
		selectTipoDocumento.appendChild(option);
		document.getElementById("SOL_TIPO_DOCUMENTO").value = tipoDocumento;
		document.getElementById("SOL_TIPO_DOCUMENTO").dispatchEvent(new Event('change'));
		_disable("SOL_TIPO_DOCUMENTO");

		var tipo = document.getElementById("AS_CIUDAD").value;
		var selectElement = document.getElementById("AS_CIUDAD");
		var selectedIndex = selectElement.selectedIndex;
		var selectedText = selectElement.options[selectedIndex].innerText;
		var selectTipo = document.getElementById("SOL_CIUDAD");
		selectTipo.innerHTML = "";
		var option = document.createElement("option");
		option.value = tipo;
		option.text = selectedText;
		selectTipo.appendChild(option);
		document.getElementById("SOL_CIUDAD").value = tipo;
		document.getElementById("SOL_CIUDAD").dispatchEvent(new Event('change'));
		_disable("SOL_CIUDAD");

		var tipo = document.getElementById("PROVINCIA").value;
		var selectElement = document.getElementById("PROVINCIA");
		var selectedIndex = selectElement.selectedIndex;
		var selectedText = selectElement.options[selectedIndex].innerText;
		var selectTipo = document.getElementById("SOL_PROVINCIA");
		selectTipo.innerHTML = "";
		var option = document.createElement("option");
		option.value = tipo;
		option.text = selectedText;
		selectTipo.appendChild(option);
		document.getElementById("SOL_PROVINCIA").value = tipo;
		document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('change'));
		_disable("SOL_PROVINCIA");

		var tipo = document.getElementById("AS_DEPARTAMENTO").value;
		var selectElement = document.getElementById("AS_DEPARTAMENTO");
		var selectedIndex = selectElement.selectedIndex;
		var selectedText = selectElement.options[selectedIndex].innerText;
		var selectTipo = document.getElementById("SOL_DEPARTAMENTO");
		selectTipo.innerHTML = "";
		var option = document.createElement("option");
		option.value = tipo;
		option.text = selectedText;
		selectTipo.appendChild(option);
		document.getElementById("SOL_DEPARTAMENTO").value = tipo;
		document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('change'));
		_disable("SOL_DEPARTAMENTO");
		var selectTipo = document.getElementById("SOL_PARENTESCO");
		selectTipo.innerHTML = "";
		var option = document.createElement("option");
		option.value = "-";
		option.text = "-";
		selectTipo.appendChild(option);

		var selectTipo = document.getElementById("TIENE_PODER_SOL_1");
		selectTipo.innerHTML = "";
		var option = document.createElement("option");
		option.value = "2";
		option.text = "2 - NO";
		selectTipo.appendChild(option);
		document.getElementById("TIENE_PODER_SOL_1").value = "2";
		document.getElementById("TIENE_PODER_SOL_1").dispatchEvent(new Event('change'));
		_disable("TIENE_PODER_SOL_1");
		_disable("NRO_PODER_SOL_1");
		_setValue("NRO_PODER_SOL_1", "");
		document.getElementById("NRO_PODER_SOL_1").dispatchEvent(new Event('input'));
		_disable("NRO_NOTARIA_SOL_1");
		_setValue("NRO_NOTARIA_SOL_1", "");
		document.getElementById("NRO_NOTARIA_SOL_1").dispatchEvent(new Event('input'));
		_disable("NOMBRE_NOTARIO_SOL_1");
		_setValue("NOMBRE_NOTARIO_SOL_1", "");
		document.getElementById("NOMBRE_NOTARIO_SOL_1").dispatchEvent(new Event('input'));
	}
}

(function() {
	cargarEnteGestor();
	getCiudades();
	cargarParentesco();
	fechaActual();
	setDate();
})();

function setDate() {
	try {
		const fechaInput = document.getElementById('FECHA_INICIO_TRAMITE').value;

		if (fechaInput) {
			console.log("VALOR DE FECHA > ", fechaInput.value);
			console.log("tiene fecha ");
			return;
		} else {
			console.log("no tiene fecha");
			var fecha = new Date();
			var dia = fecha.getDate();
			var mes = fecha.getMonth() + 1;
			var anio = fecha.getFullYear();

			if (dia < 10) {
				dia = '0' + dia;
			}
			if (mes < 10) {
				mes = '0' + mes;
			}

			var fechaHoy = anio + '-' + mes + '-' + dia;

			document.getElementById("FECHA_INICIO_TRAMITE").value = fechaHoy;
			document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));
		}
	} catch (error) {
		console.log("error");
	}

}

function solicitanteCiudad() {
	var selectCiudadesSOL = document.getElementById("SOL_CIUDAD");
	$.ajax({
		dataType: 'json',
		type: 'GET',
		url: 'https://oficinavirtualservicios.gestora.bo/api/General/Ciudad',
		success: function(ciudades) {
			//CIUDADES SOL
			if (selectCiudadesSOL.options.length === 1) {
				var cantidadCiudadesSOL = ciudades.datos.length;
				for (var i = 0; i < cantidadCiudadesSOL; i++) {
					var option = document.createElement("option");
					option.value = ciudades.datos[i].codigoGeograficoId;
					option.text = ciudades.datos[i].descripcion + ' - ' + ciudades.datos[i].provincia + ' - ' + ciudades.datos[i].departamento;
					var datosProvinciaDepartamentoSOL = ciudades.datos[i].provinciaId + ";" +
						ciudades.datos[i].provincia + ";" +
						ciudades.datos[i].departamentoId + ";" +
						ciudades.datos[i].departamento + ";";
					option.setAttribute("data-provincia-departamento", datosProvinciaDepartamentoSOL);
					selectCiudadesSOL.appendChild(option);
				}
				var dataFrmValue = selectCiudadesSOL.getAttribute("data-frm-value");
				if (dataFrmValue !== undefined && dataFrmValue !== null) {
					selectCiudadesSOL.value = dataFrmValue;
					setProvinciaDepartamentoSOL();
				}
			}

		},
		error: function(xhr, status, error) {
			console.error('Error:', error);
			// Manejar el error aquí
		}
	});

}


function esTercerGrado(rowIndex) {
	var overlay = document.getElementById("overlay");
	overlay.style.display = 'flex';

	var b = document.getElementById("DH_PARENTESCO" + rowIndex).value;
	const palabras = b.split("-");
	const elemento = document.getElementById("DH_GRADO" + rowIndex);

	var fecha_nacimiento = document.getElementById("DH_FECHA_NAC" + rowIndex).value;

	if (palabras[1] == "HIJ") {


		console.log('es hijo ');

		console.log('fecha de nacimiento === ', fecha_nacimiento);
		const date = new Date(fecha_nacimiento);
		console.log('date ==  ', date);

		const today = new Date();
		let diffYears = today.getFullYear() - date.getFullYear();
		console.log('diffYears ====== ==  ', diffYears);

		if (diffYears >= 25) {
			console.log('es mayor ==  ');

			var listaMensajes = `
                                                        <ul>
                                                            <li>tiene    ${diffYears}  Años  </li>
                                                              <li> <b>El Derechohambiente no procede</b> </li>
                                                                    <b> Tiene Invalidez ?</b><br>
                                                                    <button id="btnEstadoSI">Sí</button>
                                                                    <button id="btnEstadoNO">No</button>
                                                        </ul>
                                                    `;


			overlay.style.display = 'none';
			var modal = document.getElementById("modalGenerico");
			var modalTitulo = document.getElementById("modalGenerico-titulo");
			var modalMensaje = document.getElementById("modalGenerico-mensaje");
			modalTitulo.textContent = "Datos del Derechohambiente";
			modalMensaje.innerHTML = listaMensajes;
			modal.style.display = "block";




			document.getElementById("btnEstadoSI").addEventListener("click", function() {
				// Lógica para continuar con la búsqueda
				console.log("El usuario eligió continuar. 1");
				modal.style.display = "none";

				var selectTipo = document.getElementById("DH_INVALIDEZ" + rowIndex);
				///selectTipo.innerHTML = "";
				var option = document.createElement("option");
				option.value = "true";
				option.text = " SI ";
				selectTipo.appendChild(option);
				document.getElementById("DH_INVALIDEZ" + rowIndex).value = "true";
				document.getElementById("DH_INVALIDEZ" + rowIndex).dispatchEvent(new Event('change'));
				selectTipo.required = true;
				_disable("DH_INVALIDEZ" + rowIndex);
				overlay.style.display = 'none';


			});

			document.getElementById("btnEstadoNO").addEventListener("click", function() {

				document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).value = '';
				document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).dispatchEvent(new Event('input'));


				document.getElementById("DH_NOMBRES" + rowIndex).value = '';
				document.getElementById("DH_NOMBRES" + rowIndex).dispatchEvent(new Event('input'));
				document.getElementById("DH_PRIMER_APELLIDO" + rowIndex).value = '';
				document.getElementById("DH_PRIMER_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
				document.getElementById("DH_SEGUNDO_APELLIDO" + rowIndex).value = '';
				document.getElementById("DH_SEGUNDO_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
				document.getElementById("DH_FECHA_NAC" + rowIndex).value = '';
				document.getElementById("DH_FECHA_NAC" + rowIndex).dispatchEvent(new Event('input'));
				document.getElementById("DH_APELLIDO_CASADA" + rowIndex).value = '';
				document.getElementById("DH_APELLIDO_CASADA" + rowIndex).dispatchEvent(new Event('input'));
				document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value = '';
				document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).dispatchEvent(new Event('input'));
				document.getElementById("DH_GENERO" + rowIndex).value = "";
				document.getElementById("DH_GENERO" + rowIndex).dispatchEvent(new Event('change'));

				document.getElementById("DH_INVALIDEZ" + rowIndex).value = "";
				document.getElementById("DH_INVALIDEZ" + rowIndex).dispatchEvent(new Event('change'));
				_enable("DH_INVALIDEZ" + rowIndex);

				console.log("El usuario eligió no continuar.");
				modal.style.display = "none";
				overlay.style.display = 'none';

			});


		} else {

			console.log('es menor');

			overlay.style.display = 'none';

		}



	} else {
		console.log(' noes hijo ');
		overlay.style.display = 'none';

	}



	if (palabras[0] == "3") {
		elemento.style.display = "block"; // Ocultar el elemento
		console.log("so");
	} else {
		elemento.style.display = "none"; // Ocultar el elemento
	}
}

function obligarPoder() {
	if (document.getElementById("TIENE_PODER_SOL_1").value === "1") {
		document.querySelector('#NRO_PODER_SOL_1').required = true;
		document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
		document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
		_enable("NRO_PODER_SOL_1");
		_enable("NRO_NOTARIA_SOL_1");
		_enable("NOMBRE_NOTARIO_SOL_1");
	} else {
		document.querySelector('#NRO_PODER_SOL_1').required = false;
		document.querySelector('#NRO_NOTARIA_SOL_1').required = false;
		document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = false;
		_disable("NRO_PODER_SOL_1");
		_disable("NRO_NOTARIA_SOL_1");
		_disable("NOMBRE_NOTARIO_SOL_1");
	}
}


function verDatosDh(rowIndex) {
	var overlay = document.getElementById("overlay");
	overlay.style.display = 'flex';
	var tipoDocumento = document.getElementById("DH_TIPO_DOCUMENTO" + rowIndex) ? document.getElementById("DH_TIPO_DOCUMENTO" + rowIndex).value : '';
	var numeroDocumento = document.getElementById("DH_CI_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).value : '';
	var complemento = document.getElementById("DH_COMP_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_COMP_GRILLA_PROP" + rowIndex).value : '';
	var DH_FECHA_NAC = document.getElementById("DH_FECHA_NAC" + rowIndex) ? document.getElementById("DH_FECHA_NAC" + rowIndex).value : '';

	if (tipoDocumento !== "" && numeroDocumento !== "") {
		var requestData = {
			"tipoDocumento": tipoDocumento,
			"numeroDocumento": numeroDocumento,
			"complemento": complemento,
			"fechaNacimiento": DH_FECHA_NAC
		};
		$.ajax({
			dataType: 'json',
			contentType: 'application/json',
			type: 'POST',
			data: JSON.stringify(requestData),
			url: 'https://cenpersona.gestora.bo/api/v1/personasip/buscaPersonaRegistroDirectoSip',
			success: function(datares) {

				if (datares.codigoRespuesta == 0) {

					console.log('fecha de fallecieminto ', datares.data.fechaDefuncion);
					console.log('fecha de idGenero ', datares.data.idGenero);
					console.log('fecha de fechaNacimiento ', datares.data.fechaNacimiento);




					overlay.style.display = 'none';
					// Actualizar campos del formulario con los datos recibidos
					document.getElementById("DH_NOMBRES" + rowIndex).value = datares.data.primerNombre + " " + (datares.data.segundoNombre != null ? datares.data.segundoNombre : "");
					document.getElementById("DH_NOMBRES" + rowIndex).dispatchEvent(new Event('input'));
					document.getElementById("DH_PRIMER_APELLIDO" + rowIndex).value = datares.data.primerApellido;
					document.getElementById("DH_PRIMER_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
					document.getElementById("DH_SEGUNDO_APELLIDO" + rowIndex).value = datares.data.segundoApellido;
					document.getElementById("DH_SEGUNDO_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
					document.getElementById("DH_FECHA_NAC" + rowIndex).value = datares.data.fechaNacimiento;
					document.getElementById("DH_FECHA_NAC" + rowIndex).dispatchEvent(new Event('input'));
					document.getElementById("DH_APELLIDO_CASADA" + rowIndex).value = datares.data.apellidoCasada;
					document.getElementById("DH_APELLIDO_CASADA" + rowIndex).dispatchEvent(new Event('input'));
					document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value = datares.data.idPersonaSip;
					document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).dispatchEvent(new Event('input'));
					if (datares.data.idGenero === 'M') {
						document.getElementById("DH_GENERO" + rowIndex).value = 'M';
						document.getElementById("DH_GENERO" + rowIndex).dispatchEvent(new Event('change'));

					} else {
						document.getElementById("DH_GENERO" + rowIndex).value = 'F';
						document.getElementById("DH_GENERO" + rowIndex).dispatchEvent(new Event('change'));
					}







				} else {
					if (datares.codigoRespuesta == 1) {
						overlay.style.display = 'none';
						alert(datares.mensaje);
					} else {
						document.getElementById("DH_NOMBRES" + rowIndex).value = "";
						document.getElementById("DH_NOMBRES" + rowIndex).dispatchEvent(new Event('input'));
						document.getElementById("DH_PRIMER_APELLIDO" + rowIndex).value = "";
						document.getElementById("DH_PRIMER_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
						document.getElementById("DH_SEGUNDO_APELLIDO" + rowIndex).value = "";
						document.getElementById("DH_SEGUNDO_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
						document.getElementById("DH_FECHA_NAC" + rowIndex).value = "";
						document.getElementById("DH_FECHA_NAC" + rowIndex).dispatchEvent(new Event('input'));
						document.getElementById("DH_APELLIDO_CASADA" + rowIndex).value = "";
						document.getElementById("DH_APELLIDO_CASADA" + rowIndex).dispatchEvent(new Event('input'));
						document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value = "";
						document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).dispatchEvent(new Event('input'));
						document.getElementById("DH_GENERO" + rowIndex).value = "";
						document.getElementById("DH_GENERO" + rowIndex).dispatchEvent(new Event('change'));
					}

				}
			},
			error: function(err) {
				overlay.style.display = 'none';
				// Manejar errores de la solicitud AJAX
			}
		});
	} else {
		overlay.style.display = 'none';
		alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
	}
}

function modalMensajeDinamico(mensaje,icono_imagen, cabecera) {
    console.log ('icono de la inafgenge',icono_imagen);
    console.log ('mensaje',mensaje);
    console.log ('mensaje cabecera');

    console.log ('cabecera',cabecera);
    var overlay = document.getElementById("overlay");
    overlay.style.display = 'flex';
    overlay.style.display = 'none';
    overlay.style.display = 'none';
    var modal = document.getElementById("modalGenerico");
    var icono = document.querySelector(".icono-advertencia");
    icono.src = icono_imagen;
    var modalTitulo = document.getElementById("modalGenerico-titulo");
    var modalMensaje = document.getElementById("modalGenerico-mensaje");
    modalTitulo.textContent = cabecera;
    modalMensaje.innerHTML = mensaje;
    modal.style.display = "block";
    overlay.style.display = 'none';
}
function calcularFechaDiferenciaMuerte(startDate) {
    const today = new Date();
    const start = new Date(startDate);
    let years = today.getFullYear() - start.getFullYear();
    let months = today.getMonth() - start.getMonth();
    let days = today.getDate() - start.getDate();
    let hours = today.getHours() - start.getHours();
    let minutes = today.getMinutes() - start.getMinutes();
    let seconds = today.getSeconds() - start.getSeconds();
  
    if (seconds < 0) {
        seconds += 60;
        minutes--;
    }

    // Ajuste para minutos negativos
    if (minutes < 0) {
        minutes += 60;
        hours--;
    }

    // Ajuste para horas negativas
    if (hours < 0) {
        hours += 24;
        days--;
    }

    // Ajuste para días negativos
    if (days < 0) {
        months--;
        // Obtiene los días del mes anterior
        const previousMonth = new Date(today.getFullYear(), today.getMonth(), 0);
        days += previousMonth.getDate();
    }

    // Ajuste para meses negativos
    if (months < 0) {
        months += 12;
        years--;
    }

    return { years, months, days, hours, minutes, seconds };
}

function verDatos() {

	const AS_FECHA_FALLECIMIENTO = document.getElementById("AS_FECHA_FALLECIMIENTO").value;
	console.log('AS_FECHA_FALLECIMIENTO', AS_FECHA_FALLECIMIENTO);
	var overlay = document.getElementById("overlay");
	overlay.style.display = 'flex';
	document.getElementById("SOL_DIFERENTE_AS").checked = false;
	esSolicitante();
	var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
	var numeroDocumento = document.getElementById("AS_CI").value;
	var complemento = document.getElementById("AS_COMPLEMENTO").value;
	var fechaNacimiento = document.getElementById("AS_NACIMIENTO").value;
	var subClasificacion = document.getElementById("AS_TIPO_EAP").value;

	if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {

		if (subClasificacion === "") {
			overlay.style.display = 'none';
			var modal = document.getElementById("modalGenerico");
			var modalTitulo = document.getElementById("modalGenerico-titulo");
			var modalMensaje = document.getElementById("modalGenerico-mensaje");
			modalTitulo.textContent = "Datos del Asegurado";
			modalMensaje.textContent = "Debe seleccionar la Subclasificación - trámite de pago de CCM para continuar la búsqueda.";
			modal.style.display = "block";
			return;
		} else {
			const tipo_busqueda = (subClasificacion === 'CVEAP-B2') ? 'NRF' : 'T';
			var requestData = {
				"tipoDocumento": tipoDocumento,
				"numeroDocumento": numeroDocumento,
				"complemento": complemento,
				"fechaNacimiento": fechaNacimiento,
				"tipoBusqueda": tipo_busqueda
			};
			$.ajax({
				dataType: 'json',
				contentType: 'application/json',
				type: 'POST',
				data: JSON.stringify(requestData),
				//url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
				url: 'https://cenpersona.gestora.bo/api/v1/personasip/buscaPersonaRegistroDirectoSip',
				success: function(datares) {
					console.log("Datares=========>>>", datares);
					if (datares.codigoRespuesta == 0) {
						if (subClasificacion === 'CVEAP-B6') {
                            if (datares.data.fechaDefuncion != null) {
                                const cua = datares.data.cua;
                                $.ajax({
                                      dataType: 'json',
                                      type: 'GET',
                                      url: 'https://sgg.gestora.bo/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c='+cua+'&user=fernando.flores@gestora.bo',
                                      success: function(response) {
                                        console.log('response pagoos ccm  ======>>>>>', response);
                                        if(response.codigoRespuesta == 200 ){
                                            const fecha = new Date(datares.data.fechaDefuncion);
                                            let Total_meses = calcularFechaDiferenciaMuerte(fecha);

                                            let  tmes =( Total_meses.years*12) + Total_meses.months

                                            if(tmes<= 36 ){
                                                
                                                console.log('Total_meses  ======>>>>>', Total_meses);
                                                const date = new Date(datares.data.fechaNacimiento);
                                                const today = new Date();
                                                let diffYears = today.getFullYear() - date.getFullYear();
                                                if (datares.data.idGenero == 'M') {
                                                    console.log('es masculino ====>>>');
                                                    if (diffYears >= 55) {
                                                        console.log('es mayor a 55 =======>>>');
                                                        const mensaje =  `  <ul>
                                                                <li>    <b>Genero :  </b>MASCULINO  </li>
                                                                <li>    Es mayor de 55 años  </b></li>
                                                                <li>    <b>Edad del Asegurado  :  </b>${diffYears} años </li>
                                                                <li>    <b>Fecha de registro del CCM  : </b> ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                                <li>    <b>Meses  transcuridos: </b> ${tmes}  </li>

                                                            </ul>`;
                                                        overlay.style.display = 'none';
                                                        var modal = document.getElementById("modalGenerico");
                                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                        var modalBoton = document.getElementById("modalGenerico-boton")
                                                        var icono = document.querySelector(".icono-advertencia");
                                                        icono.src = "img/valido.png";
                                                        modalTitulo.textContent = "Datos del Asegurado";
                                                        modalMensaje.innerHTML = mensaje;
                                                        modalBoton.textContent = "Continuar";
                                                        modal.style.display = "block";


                                                        
                                overlay.style.display = 'none';
                                document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_NOMBRE").value = datares.data.primerNombre;
                                document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_NOMBRE").value = datares.data.segundoNombre;
                                document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_APELLIDO").value = datares.data.primerApellido;
                                document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_APELLIDO").value = datares.data.segundoApellido || "";
                                document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NACIMIENTO").value = datares.data.fechaNacimiento;
                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CUA").value = datares.data.cua;
                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));

                                if (datares.data.idGenero === 'M') {
                                    document.getElementById("AS_GENERO").value = 'MASCULINO';
                                    matricula(datares);
                                } else {
                                    document.getElementById("AS_GENERO").value = 'FEMENINO';
                                    matricula(datares);
                                }
                                document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                // document.getElementById("AS_ESTADO_CIVIL").value = datares.data.idEstadoCivil;
                                if (datares.data.idEstadoCivil === 'C') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                } else {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                }

                                if (["FALLECIDO"].includes(datares.data.apiEstado)) {
                                    quitarRequerido();
                                } else {
                                    volverRequerido();
                                }

                                document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                                document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_ZONA").value = datares.data.zona || "";
                                document.getElementById("AS_ZONA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_DIRECCION").value = datares.data.direccion || "";
                                document.getElementById("AS_DIRECCION").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NUM").value = datares.data.numero || "";
                                document.getElementById("AS_NUM").dispatchEvent(new Event('input'));
                                document.getElementById("AS_TELEFONO").value = datares.data.telefonoFijo || "";
                                document.getElementById("AS_TELEFONO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CELULAR").value = datares.data.telefonoCelular || "";
                                document.getElementById("AS_CELULAR").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CORREO").value = datares.data.correoElectronico || "";
                                document.getElementById("AS_CORREO").dispatchEvent(new Event('input'));
                                if (datares.data.complemento !== null) {
                                    document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                } else {
                                    document.getElementById("AS_COMPLEMENTO").value = "";
                                    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                }

                                document.getElementById("AS_ENTE_GESTOR").value = datares.data.descripcion || "";
                                document.getElementById("AS_ENTE_GESTOR").dispatchEvent(new Event('input'));
                                //document.getElementById("AS_MATRICULA_ASEGURADO").value = datares.data.matriculaAsegurado || "";
                                //document.getElementById("AS_MATRICULA_ASEGURADO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_POSTAL").value = datares.data.casillaPostal || "";
                                document.getElementById("AS_POSTAL").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CIUDAD").value = datares.data.ciudad || "";
                                document.getElementById("AS_CIUDAD").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PROVINCIA").value = datares.data.provincia || "";
                                document.getElementById("AS_PROVINCIA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_DEPARTAMENTO").value = datares.data.departamento || "";
                                document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_TIPO_AS").value = datares.data.tipoAsegurado || "";
                                document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));
                                document.getElementById("EM_NOMBRE").value = datares.data.nombreEmpleador || "";
                                document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("EM_FECHA").value = datares.data.fechaContrato || "";
                                document.getElementById("EM_FECHA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_OCUPACION_AS").value = datares.data.ocupacion || "";
                                document.getElementById("EM_OCUPACION_AS").dispatchEvent(new Event('input'));
                                document.getElementById("EM_DEPARTAMENTO").value = datares.data.departamento || "";
                                document.getElementById("EM_DEPARTAMENTO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_PROVINCIA").value = datares.data.provincia || "";
                                document.getElementById("EM_PROVINCIA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CIUDAD").value = datares.data.ciudad || "";
                                document.getElementById("EM_CIUDAD").dispatchEvent(new Event('input'));
                                document.getElementById("EM_ZONA").value = datares.data.zona || "";
                                document.getElementById("EM_ZONA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_DIRECCION").value = datares.data.direccionEmpleador || "";
                                document.getElementById("EM_DIRECCION").dispatchEvent(new Event('input'));
                                document.getElementById("EM_NUM").value = datares.data.numEmpleador || "";
                                document.getElementById("EM_NUM").dispatchEvent(new Event('input'));
                                document.getElementById("EM_TELEFONO").value = datares.data.telefonoEmpleador || "";
                                document.getElementById("EM_TELEFONO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CELULAR").value = datares.data.celularEmpleador || "";
                                document.getElementById("EM_CELULAR").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CORREO").value = datares.data.correoEmpleador || "";
                                document.getElementById("EM_CORREO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_1").value = datares.data.asObsCveap1 || "";
                                document.getElementById("AS_OBS_CVAP_1").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_2").value = datares.data.asObsCveap2 || "";
                                document.getElementById("AS_OBS_CVAP_2").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_3").value = datares.data.asObsCveap3 || "";
                                document.getElementById("AS_OBS_CVAP_3").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_4").value = datares.data.asObsCveap4 || "";
                                document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_5").value = datares.data.asObsCveap5 || "";
                                document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));

                                try {
                                    document.getElementById("AS_CERT_INSALUBRIDAD").value = datares.data.certifInsalubridad || "";
                                    document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));
                                } catch (error) {
                                    console.log("#");
                                }
                                document.getElementById("CERT_INSALUBRIDAD").value = datares.data.documentSalubridad || "";
                                document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));

a
                                                    }
                                                } else {
                                                    if (diffYears >= 50) {
                                                        const mensaje =  `   <ul>
                                                                <li> La fecha de fallecimiento anterior al 10 de diciembre del 2010 </li>
                                                                <li> Genero : FEMENINO  </li>
                                                                <li> Es mayor de 50 años</li>
                                                                <li> Edad del Asegurado  : ${diffYears} años </li>
                                                                <li> Fecha de registro del CCM  : ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                            </ul>`;
                                                        const icono_imagen = "img/advertencia_1.jpg";
                                                        const cabecera = "DATOS DEL ASEGURADO";
                                                         modalMensajeDinamico(mensaje,icono_imagen,cabecera);
                                                    }
                                                }
    
                                            } else {
                                                const mensaje =  ` <p> <h1>No se puede registrar la solicitud, debido a que trancurrió mas de 36 meses desde el fallecimiento  </h1> </p>`;
                                                const icono_imagen = "img/advertencia_1.jpg";
                                                const cabecera = "DATOS DEL ASEGURADO";
                                                modalMensajeDinamico(mensaje,icono_imagen,cabecera);
                                            }


                                          
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error:', error);
                                        // Manejar el error aquí
                                    }
                                });
                            } else {
                                ///3580413
                                const mensaje =  ` <p> <h1>No se cuenta con el registro de la fecha de fallecimiento, favor registre la misma de forma previa, a través del Registro de Novedades</h1> </p>`;
                                const icono_imagen = "img/advertencia_1.jpg";
                                const cabecera = "DATOS DEL ASEGURADO";
                                 modalMensajeDinamico(mensaje,icono_imagen,cabecera);
                             }

						} else {
                            




                            if (tipo_busqueda == 'NRF') {
                                console.log("Datares =========>>>inicio", datares);
                                const cua = datares.data.cua;
                                $.ajax({
                                    dataType: 'json',
                                    type: 'GET',
                                    url: 'https://sgg.gestora.bo/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c=' + cua + '&user=fernando.flores@gestora.bo',
                                    success: function(response) {
                                        console.log('response ======>>>>>', response);
                                        if (response.codigoRespuesta == 200) {
                                            const date1 = new Date('2010-12-10'); // '10-12-2010' en formato yyyy-mm-dd
                                            const date2 = new Date(datares.data.fechaDefuncion); // '1998-06-04' en formato yyyy-mm-dd
                                            if (date2 < date1) {
                                                const date = new Date(datares.data.fechaNacimiento);
                                                const today = new Date();
                                                let diffYears = today.getFullYear() - date.getFullYear();
                                                if (datares.data.idGenero == 'M') {
                                                    console.log('es masculino ====>>>');
                                                    if (diffYears >= 55) {
                                                        console.log('es mayor a 55 =======>>>');
                                                        var listaMensajes = `
                                                            <ul>
                                                                <li>    La fecha de fallecimiento anterior al 10 de diciembre del 2010 </li>
                                                                <li>    <b>Genero :  </b>MASCULINO  </li>
                                                                <li>    Es mayor de 55 años </li>
                                                                <li>    <b>Edad del Asegurado  :  </b>${diffYears} años </li>
                                                                <li>    <b>Fecha de registro del CCM  : </b> ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                            </ul>
                                                        `;
                                                        overlay.style.display = 'none';
                                                        var modal = document.getElementById("modalGenerico");
                                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                        var modalBoton = document.getElementById("modalGenerico-boton")
                                                        modalTitulo.textContent = "Datos del Asegurado";
                                                        modalMensaje.innerHTML = listaMensajes;
                                                        modalBoton.textContent = "Continuar";
                                                        modal.style.display = "block";
                                                    }
                                                } else {
                                                    if (diffYears >= 50) {
                                                        var listaMensajes = `
                                                            <ul>
                                                                <li> La fecha de fallecimiento anterior al 10 de diciembre del 2010 </li>
                                                                <li> Genero : FEMENINO  </li>
                                                                <li> Es mayor de 50 años</li>
                                                                <li> Edad del Asegurado  : ${diffYears} años </li>
                                                                <li> Fecha de registro del CCM  : ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                            </ul>
                                                        `;
                                                        overlay.style.display = 'none';
                                                        var modal = document.getElementById("modalGenerico");
                                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                        modalTitulo.textContent = "Datos del Asegurado";
                                                        modalMensaje.innerHTML = listaMensajes;
                                                        modal.style.display = "block";
                                                    }
                                                }
                                            } else {
                                                const date = new Date(datares.data.fechaNacimiento);
                                                const today = new Date();
                                                let diffYears = today.getFullYear() - date.getFullYear();
                                                if (diffYears >= 58) {
                                                    console.log('es mayor a 58 =======>>>');
                                                    var listaMensajes = `
                                                        <ul>
                                                            <li>La fecha de fallecimiento es posterior al 10 de diciembre del 2010 </li>
                                                            <li>Es mayor de 58 años</li>
                                                            <li>Edad del Asegurado : ${diffYears} años </li>
                                                            <li> Fecha de registro del CCM: ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                        </ul>
                                                    `;
                                                    overlay.style.display = 'none';
                                                    var modal = document.getElementById("modalGenerico");
                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                    modalTitulo.textContent = "Datos del Asegurado";
                                                    modalMensaje.innerHTML = listaMensajes;
                                                    modal.style.display = "block";

                                                    if (response.codigoRespuesta == 200) {
                                                        const date_ccm = new Date(response.data.CCM.fechaCargaOfechaEmision);
                                                        const day = new Date();
                                                        const yearsDiff = day.getFullYear() - datdate_ccme1.getFullYear(); // Diferencia de años
                                                        const monthsDiff = day.getMonth() - datdate_ccme1.getMonth(); // Diferencia de meses
                                                        // Total de meses considerando tanto los años como los meses
                                                        const totalMonths = (yearsDiff * 12) + monthsDiff;
                                                        console.log(totalMonths);
                                                        if (totalMonths <= 36) {
                                                            console.log('totalMonths =======>>>>', totalMonths);
                                                            var listaMensajes = `
                                                                    <ul>
                                                                        <li> La fecha de fallecimiento es posterior al 10 de diciembre del 2010 </li>
                                                                        <li> Es mayor de 58 años</li>
                                                                        <li> Edad del Asegurado : ${diffYears} años </li>
                                                                        <li> Fecha de registro del CCM sdas  : ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                                        <li> Fecha menor de 36 meses de Siniestro  </li>
                                                                    </ul>
                                                                `;
                                                            overlay.style.display = 'none';
                                                            var modal = document.getElementById("modalGenerico");
                                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                            modalTitulo.textContent = "Datos del Asegurado";
                                                            modalMensaje.innerHTML = listaMensajes;
                                                            modal.style.display = "block";
                                                        }
                                                    }
                                                } else {
                                                    // console.log('es mayor a 58 =======>>>'); 
                                                    var listaMensajes = `
                                                        <ul>
                                                            <li> La fecha de fallecimiento es posterior al 10 de diciembre del 2010 </li>
                                                            <li> Es menor  de 58 años</li>
                                                            <li> <b>Edad del Asegurado :</b> ${diffYears} años </li>
                                                            <li>  <b>Fecha de registro del CCM  : </b> ${response.data.CCM.fechaCargaOfechaEmision}  </li>
                                                            <li> <b>El tramite no procede</b> </li>
                                                        </ul>
                                                        <b>Cuenta con Certificado de Insalubridad ?</b><br>
                                                            <button id="btnSi">Sí</button>
                                                            <button id="btnNo">No</button>
                                                    `;
                                                    overlay.style.display = 'none';
                                                    var modal = document.getElementById("modalGenerico");
                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                    modalTitulo.textContent = "Datos del Asegurado";
                                                    modalMensaje.innerHTML = listaMensajes;
                                                    modal.style.display = "block";

                                                    document.getElementById("btnSi").addEventListener("click", function() {
                                                        // Lógica para continuar con la búsqueda
                                                        console.log("El usuario eligió continuar. 1");
                                                        modal.style.display = "none";
                                                        var selectTipo = document.getElementById("AS_CERT_INSALUBRIDAD");
                                                        selectTipo.innerHTML = "";
                                                        var option = document.createElement("option");
                                                        option.value = "SI";
                                                        option.text = "SI";
                                                        selectTipo.appendChild(option);
                                                        document.getElementById("AS_CERT_INSALUBRIDAD").value = "SI";
                                                        document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('change'));
                                                        selectTipo.required = true;
                                                        _disable("AS_CERT_INSALUBRIDAD");
                                                        document.getElementById("CERT_INSALUBRIDAD_idd").setAttribute("required", "required");
                                                        document.querySelector('#CERT_INSALUBRIDAD_idd').required = true;
                                                        console.log("El usuario eligió continuar. 2dsdsdsds1");
                                                        modal.style.display = "none";

                                                    });

                                                    document.getElementById("btnNo").addEventListener("click", function() {
                                                        // Lógica para cancelar la búsqueda
                                                        document.getElementById("AS_CI").value = "";
                                                        console.log("El usuario eligió no continuar.");
                                                        modal.style.display = "none";
                                                        limpiarFormulario();
                                                    });

                                                }

                                            }

                                        } else {

                                            // Lógica para cancelar la búsqueda


                                            var listaMensajes = `
                                                            <ul>
                                                                
                                                                <li>  <b>No Procede  por  no contar con CCM</b></li>
                                                                <li>  <b>Fecha de registro del CCM  : </b> ${response.data}  </li>
                                                            </ul>
                                                        `;
                                            overlay.style.display = 'none';
                                            var modal = document.getElementById("modalGenerico");
                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                            modalTitulo.textContent = "Datos del Asegurado";
                                            modalMensaje.innerHTML = listaMensajes;
                                            modal.style.display = "block";

                                            document.getElementById("AS_CI").value = "";
                                            console.log("El usuario eligió no continuar.");

                                            limpiarFormulario();
                                        }



                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error:', error);
                                        // Manejar el error aquí
                                    }
                                });







                                console.log('fecha de fallecieminto ', datares.data.fechaDefuncion);
                                console.log('fecha de idGenero ', datares.data.idGenero);
                                console.log('fecha de fechaNacimiento ', datares.data.fechaNacimiento);
                                //fallecido  <10/12/2010


                                overlay.style.display = 'none';
                                document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_NOMBRE").value = datares.data.primerNombre;
                                document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_NOMBRE").value = datares.data.segundoNombre;
                                document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_APELLIDO").value = datares.data.primerApellido;
                                document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_APELLIDO").value = datares.data.segundoApellido || "";
                                document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NACIMIENTO").value = datares.data.fechaNacimiento;
                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CUA").value = datares.data.cua;
                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));

                                if (datares.data.idGenero === 'M') {
                                    document.getElementById("AS_GENERO").value = 'MASCULINO';
                                    matricula(datares);
                                } else {
                                    document.getElementById("AS_GENERO").value = 'FEMENINO';
                                    matricula(datares);
                                }
                                document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                // document.getElementById("AS_ESTADO_CIVIL").value = datares.data.idEstadoCivil;
                                if (datares.data.idEstadoCivil === 'C') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                } else {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                }

                                if (["FALLECIDO"].includes(datares.data.apiEstado)) {
                                    quitarRequerido();
                                } else {
                                    volverRequerido();
                                }

                                document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                                document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_ZONA").value = datares.data.zona || "";
                                document.getElementById("AS_ZONA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_DIRECCION").value = datares.data.direccion || "";
                                document.getElementById("AS_DIRECCION").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NUM").value = datares.data.numero || "";
                                document.getElementById("AS_NUM").dispatchEvent(new Event('input'));
                                document.getElementById("AS_TELEFONO").value = datares.data.telefonoFijo || "";
                                document.getElementById("AS_TELEFONO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CELULAR").value = datares.data.telefonoCelular || "";
                                document.getElementById("AS_CELULAR").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CORREO").value = datares.data.correoElectronico || "";
                                document.getElementById("AS_CORREO").dispatchEvent(new Event('input'));
                                if (datares.data.complemento !== null) {
                                    document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                } else {
                                    document.getElementById("AS_COMPLEMENTO").value = "";
                                    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                }

                                document.getElementById("AS_ENTE_GESTOR").value = datares.data.descripcion || "";
                                document.getElementById("AS_ENTE_GESTOR").dispatchEvent(new Event('input'));
                                //document.getElementById("AS_MATRICULA_ASEGURADO").value = datares.data.matriculaAsegurado || "";
                                //document.getElementById("AS_MATRICULA_ASEGURADO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_POSTAL").value = datares.data.casillaPostal || "";
                                document.getElementById("AS_POSTAL").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CIUDAD").value = datares.data.ciudad || "";
                                document.getElementById("AS_CIUDAD").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PROVINCIA").value = datares.data.provincia || "";
                                document.getElementById("AS_PROVINCIA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_DEPARTAMENTO").value = datares.data.departamento || "";
                                document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_TIPO_AS").value = datares.data.tipoAsegurado || "";
                                document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));
                                document.getElementById("EM_NOMBRE").value = datares.data.nombreEmpleador || "";
                                document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("EM_FECHA").value = datares.data.fechaContrato || "";
                                document.getElementById("EM_FECHA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_OCUPACION_AS").value = datares.data.ocupacion || "";
                                document.getElementById("EM_OCUPACION_AS").dispatchEvent(new Event('input'));
                                document.getElementById("EM_DEPARTAMENTO").value = datares.data.departamento || "";
                                document.getElementById("EM_DEPARTAMENTO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_PROVINCIA").value = datares.data.provincia || "";
                                document.getElementById("EM_PROVINCIA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CIUDAD").value = datares.data.ciudad || "";
                                document.getElementById("EM_CIUDAD").dispatchEvent(new Event('input'));
                                document.getElementById("EM_ZONA").value = datares.data.zona || "";
                                document.getElementById("EM_ZONA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_DIRECCION").value = datares.data.direccionEmpleador || "";
                                document.getElementById("EM_DIRECCION").dispatchEvent(new Event('input'));
                                document.getElementById("EM_NUM").value = datares.data.numEmpleador || "";
                                document.getElementById("EM_NUM").dispatchEvent(new Event('input'));
                                document.getElementById("EM_TELEFONO").value = datares.data.telefonoEmpleador || "";
                                document.getElementById("EM_TELEFONO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CELULAR").value = datares.data.celularEmpleador || "";
                                document.getElementById("EM_CELULAR").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CORREO").value = datares.data.correoEmpleador || "";
                                document.getElementById("EM_CORREO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_1").value = datares.data.asObsCveap1 || "";
                                document.getElementById("AS_OBS_CVAP_1").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_2").value = datares.data.asObsCveap2 || "";
                                document.getElementById("AS_OBS_CVAP_2").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_3").value = datares.data.asObsCveap3 || "";
                                document.getElementById("AS_OBS_CVAP_3").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_4").value = datares.data.asObsCveap4 || "";
                                document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_5").value = datares.data.asObsCveap5 || "";
                                document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));

                                try {
                                    document.getElementById("AS_CERT_INSALUBRIDAD").value = datares.data.certifInsalubridad || "";
                                    document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));
                                } catch (error) {
                                    console.log("#");
                                }
                                document.getElementById("CERT_INSALUBRIDAD").value = datares.data.documentSalubridad || "";
                                document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));




                            } else {





                                console.log('fecha de fallecieminto ', datares.data.fechaDefuncion);
                                console.log('fecha de idGenero ', datares.data.idGenero);
                                console.log('fecha de fechaNacimiento ', datares.data.fechaNacimiento);
                                //fallecido  <10/12/2010


                                overlay.style.display = 'none';
                                document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_NOMBRE").value = datares.data.primerNombre;
                                document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_NOMBRE").value = datares.data.segundoNombre;
                                document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_APELLIDO").value = datares.data.primerApellido;
                                document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_APELLIDO").value = datares.data.segundoApellido || "";
                                document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NACIMIENTO").value = datares.data.fechaNacimiento;
                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CUA").value = datares.data.cua;
                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));

                                if (datares.data.idGenero === 'M') {
                                    document.getElementById("AS_GENERO").value = 'MASCULINO';
                                    matricula(datares);
                                } else {
                                    document.getElementById("AS_GENERO").value = 'FEMENINO';
                                    matricula(datares);
                                }
                                document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                // document.getElementById("AS_ESTADO_CIVIL").value = datares.data.idEstadoCivil;
                                if (datares.data.idEstadoCivil === 'C') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                } else {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                }

                                if (["FALLECIDO"].includes(datares.data.apiEstado)) {
                                    quitarRequerido();
                                } else {
                                    volverRequerido();
                                }

                                document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                                document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_ZONA").value = datares.data.zona || "";
                                document.getElementById("AS_ZONA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_DIRECCION").value = datares.data.direccion || "";
                                document.getElementById("AS_DIRECCION").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NUM").value = datares.data.numero || "";
                                document.getElementById("AS_NUM").dispatchEvent(new Event('input'));
                                document.getElementById("AS_TELEFONO").value = datares.data.telefonoFijo || "";
                                document.getElementById("AS_TELEFONO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CELULAR").value = datares.data.telefonoCelular || "";
                                document.getElementById("AS_CELULAR").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CORREO").value = datares.data.correoElectronico || "";
                                document.getElementById("AS_CORREO").dispatchEvent(new Event('input'));
                                if (datares.data.complemento !== null) {
                                    document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                } else {
                                    document.getElementById("AS_COMPLEMENTO").value = "";
                                    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                }

                                document.getElementById("AS_ENTE_GESTOR").value = datares.data.descripcion || "";
                                document.getElementById("AS_ENTE_GESTOR").dispatchEvent(new Event('input'));
                                //document.getElementById("AS_MATRICULA_ASEGURADO").value = datares.data.matriculaAsegurado || "";
                                //document.getElementById("AS_MATRICULA_ASEGURADO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_POSTAL").value = datares.data.casillaPostal || "";
                                document.getElementById("AS_POSTAL").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CIUDAD").value = datares.data.ciudad || "";
                                document.getElementById("AS_CIUDAD").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PROVINCIA").value = datares.data.provincia || "";
                                document.getElementById("AS_PROVINCIA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_DEPARTAMENTO").value = datares.data.departamento || "";
                                document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_TIPO_AS").value = datares.data.tipoAsegurado || "";
                                document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));
                                document.getElementById("EM_NOMBRE").value = datares.data.nombreEmpleador || "";
                                document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("EM_FECHA").value = datares.data.fechaContrato || "";
                                document.getElementById("EM_FECHA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_OCUPACION_AS").value = datares.data.ocupacion || "";
                                document.getElementById("EM_OCUPACION_AS").dispatchEvent(new Event('input'));
                                document.getElementById("EM_DEPARTAMENTO").value = datares.data.departamento || "";
                                document.getElementById("EM_DEPARTAMENTO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_PROVINCIA").value = datares.data.provincia || "";
                                document.getElementById("EM_PROVINCIA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CIUDAD").value = datares.data.ciudad || "";
                                document.getElementById("EM_CIUDAD").dispatchEvent(new Event('input'));
                                document.getElementById("EM_ZONA").value = datares.data.zona || "";
                                document.getElementById("EM_ZONA").dispatchEvent(new Event('input'));
                                document.getElementById("EM_DIRECCION").value = datares.data.direccionEmpleador || "";
                                document.getElementById("EM_DIRECCION").dispatchEvent(new Event('input'));
                                document.getElementById("EM_NUM").value = datares.data.numEmpleador || "";
                                document.getElementById("EM_NUM").dispatchEvent(new Event('input'));
                                document.getElementById("EM_TELEFONO").value = datares.data.telefonoEmpleador || "";
                                document.getElementById("EM_TELEFONO").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CELULAR").value = datares.data.celularEmpleador || "";
                                document.getElementById("EM_CELULAR").dispatchEvent(new Event('input'));
                                document.getElementById("EM_CORREO").value = datares.data.correoEmpleador || "";
                                document.getElementById("EM_CORREO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_1").value = datares.data.asObsCveap1 || "";
                                document.getElementById("AS_OBS_CVAP_1").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_2").value = datares.data.asObsCveap2 || "";
                                document.getElementById("AS_OBS_CVAP_2").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_3").value = datares.data.asObsCveap3 || "";
                                document.getElementById("AS_OBS_CVAP_3").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_4").value = datares.data.asObsCveap4 || "";
                                document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));
                                document.getElementById("AS_OBS_CVAP_5").value = datares.data.asObsCveap5 || "";
                                document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));

                                try {
                                    document.getElementById("AS_CERT_INSALUBRIDAD").value = datares.data.certifInsalubridad || "";
                                    document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));
                                } catch (error) {
                                    console.log("#");
                                }
                                document.getElementById("CERT_INSALUBRIDAD").value = datares.data.documentSalubridad || "";
                                document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));



                            }




						}






					} else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {

						if (complemento === "") {
							document.getElementById("AS_NACIMIENTO").disabled = false;
							if (fechaNacimiento !== "") {
								document.getElementById("AS_NACIMIENTO").value = fechaNacimiento;
								document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
							} else {
								overlay.style.display = 'none';
								var modal = document.getElementById("modalGenerico");
								var modalTitulo = document.getElementById("modalGenerico-titulo");
								var modalMensaje = document.getElementById("modalGenerico-mensaje");
								modalTitulo.textContent = "Datos del Asegurado";
								modalMensaje.textContent = "Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.";
								modal.style.display = "block";
								return;
							}
						} else {
							document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento;
						}
					} else {
						if (datares.codigoRespuesta === "5") {
							overlay.style.display = 'none';
							var modal = document.getElementById("modalGenerico");
							var modalTitulo = document.getElementById("modalGenerico-titulo");
							var modalMensaje = document.getElementById("modalGenerico-mensaje");
							modalTitulo.textContent = "Resultados   ";
							modalMensaje.textContent = "El numero del documento ingresado no corresponde a NRF, Favor revisar";
							modal.style.display = "block";
						}
						limpiarFormulario();
					}

				},
				error: function(err) {
					overlay.style.display = 'none';
					// Manejar errores de la solicitud AJAX
				}
			});
		}
	} else {
		overlay.style.display = 'none';
		//alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
		var modal = document.getElementById("modalGenerico");
		var modalTitulo = document.getElementById("modalGenerico-titulo");
		var modalMensaje = document.getElementById("modalGenerico-mensaje");
		modalTitulo.textContent = "Datos del Asegurado";
		modalMensaje.textContent = "Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.";
		modal.style.display = "block";
	}
}


function consultarMuerte() {
	try {
		const estadoApi = document.getElementById('AS_API_ESTADO').value;

		if (estadoApi == 'FALLECIDO') {
			quitarRequerido();
		} else {
			console.log("El otro estado de la api >>", estadoApi);
			const _fecha = document.getElementById("AS_FECHA_FALLECIMIENTO").value;

			if (_fecha) {
				quitarRequerido();
			} else {
				volverRequerido();
			}
		}

	} catch (e) {
		//
	}
}

function volverRequerido() {
	document.getElementById("AS_CORREO").setAttribute("required", "required");
	document.getElementById("AS_CELULAR").setAttribute("required", "required");

	const div = document.getElementById('AS_CORREO_idd');
	const as_celular = document.getElementById('AS_CELULAR_idd');

	const label = div.querySelector('label');
	const label_celular = as_celular.querySelector('label');

	if (label && !label.textContent.includes(' (*)')) {
		label.textContent += ' (*)';
	}
	if (label_celular && !label_celular.textContent.includes(' (*)')) {
		label_celular.textContent += ' (*)';
	}
}

function quitarRequerido() {
	const div = document.getElementById('AS_CORREO_idd');
	const as_celular = document.getElementById('AS_CELULAR_idd');

	document.getElementById("AS_CORREO").removeAttribute("required");
	document.getElementById("AS_CELULAR").removeAttribute("required");

	const label = div.querySelector('label');
	const label_celular = as_celular.querySelector('label');

	if (label) {
		label.textContent = label.textContent.replace(' (*)', '');
	}

	if (label_celular) {
		label_celular.textContent = label.textContent.replace(' (*)', '');
	}
}

function autosubmitFormulario() {
	// Selecciona el formulario por su ID
	var formulario = document.getElementById("form1");

	// Verifica si se encontró el formulario
	if (formulario) {
		// Llama al método submit() para enviar el formulario automáticamente
		formulario.submit();
	} else {
		// Si no se encuentra el formulario, muestra un mensaje de error en la consola
		console.error("El formulario con el ID 'miFormulario' no se encontró.");
	}
}

function verDatosSol() {
	var overlay = document.getElementById("overlay");
	overlay.style.display = 'flex';
	console.log('solicitante dsad asd asd sad as');
	var tipoDocumento = document.getElementById("SOL_TIPO_DOCUMENTO").value;
	var numeroDocumento = document.getElementById("SOL_CI").value;
	var complemento = document.getElementById("SOL_COMPLEMENTO").value;
	var fechaNacimiento = document.getElementById("SOL_NACIMIENTO").value;

	if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {
		// Construir el cuerpo de la solicitud
		var requestData = {
			"tipoDocumento": tipoDocumento,
			"numeroDocumento": numeroDocumento,
			"complemento": complemento,
			"fechaNacimiento": fechaNacimiento
		};

		$.ajax({
			dataType: 'json',
			contentType: 'application/json',
			type: 'POST',
			data: JSON.stringify(requestData),
			url: 'https://cenpersona.gestora.bo/api/v1/personasip/buscaPersonaRegistroDirectoSip',
			success: function(datares) {
				if (datares.codigoRespuesta == 0) {



					overlay.style.display = 'none';
					if (document.getElementById("AS_FECHA_DEFUNCION").value.trim() === "") {
						if (document.getElementById("AS_CI").value !== numeroDocumento) {

							// var selectTipo = document.getElementById("TIENE_PODER_SOL_1");
							// selectTipo.innerHTML = "";
							// var option = document.createElement("option");
							// option.value = "1";
							// option.text = "1 - SI";
							// selectTipo.appendChild(option);
							// document.getElementById("TIENE_PODER_SOL_1").value = "1";
							// document.getElementById("TIENE_PODER_SOL_1").dispatchEvent(new Event('change'));
							// _disable("TIENE_PODER_SOL_1");

							_enable("NRO_PODER_SOL_1");
							_enable("NRO_NOTARIA_SOL_1");
							_enable("NOMBRE_NOTARIO_SOL_1");
						} else {
							// var selectTipo = document.getElementById("TIENE_PODER_SOL_1");
							// selectTipo.innerHTML = "";
							// var option = document.createElement("option");
							// option.value = "2";
							// option.text = "2 - NO";
							// selectTipo.appendChild(option);
							// document.getElementById("TIENE_PODER_SOL_1").value = "2";
							// document.getElementById("TIENE_PODER_SOL_1").dispatchEvent(new Event('change'));
							// _disable("TIENE_PODER_SOL_1");

							_disable("NRO_PODER_SOL_1");
							_setValue("NRO_PODER_SOL_1", "");
							document.getElementById("NRO_PODER_SOL_1").dispatchEvent(new Event('input'));

							_disable("NRO_NOTARIA_SOL_1");
							_setValue("NRO_NOTARIA_SOL_1", "");
							document.getElementById("NRO_NOTARIA_SOL_1").dispatchEvent(new Event('input'));

							_disable("NOMBRE_NOTARIO_SOL_1");
							_setValue("NOMBRE_NOTARIO_SOL_1", "");
							document.getElementById("NOMBRE_NOTARIO_SOL_1").dispatchEvent(new Event('input'));
						}

					}
					document.getElementById("SOL_PRIMER_NOMBRE").value = datares.data.primerNombre;
					document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
					document.getElementById("SOL_SEGUNDO_NOMBRE").value = datares.data.segundoNombre || "";
					document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
					document.getElementById("SOL_PRIMER_APELLIDO").value = datares.data.primerApellido;
					document.getElementById("SOL_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
					document.getElementById("SOL_SEGUNDO_APELLIDO").value = datares.data.segundoApellido || "";
					document.getElementById("SOL_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
					document.getElementById("SOL_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
					document.getElementById("SOL_APELLIDO_CASADA").dispatchEvent(new Event('input'));
					document.getElementById("SOL_NACIMIENTO").value = datares.data.fechaNacimiento;
					document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
					document.getElementById("SOL_CUA").value = datares.data.cua;
					document.getElementById("SOL_CUA").dispatchEvent(new Event('input'));
					document.getElementById("SOL_IDPERSONA").value = datares.data.idPersonaSip || "";
					document.getElementById("SOL_IDPERSONA").dispatchEvent(new Event('input'));
					if (datares.data.idGenero === 'M') {
						document.getElementById("SOL_GENERO").value = 'MASCULINO';
					} else {
						document.getElementById("SOL_GENERO").value = 'FEMENINO';
					}
					document.getElementById("SOL_GENERO").dispatchEvent(new Event('input'));
					// document.getElementById("AS_ESTADO_CIVIL").value = datares.data.idEstadoCivil;
					if (datares.data.idEstadoCivil === 'C') {
						document.getElementById("SOL_ESTADO_CIVIL").value = 'CASADO(A)';
					} else {
						document.getElementById("SOL_ESTADO_CIVIL").value = 'SOLTERO(A)';
					}
					document.getElementById("SOL_ESTADO_CIVIL").dispatchEvent(new Event('input'));
					document.getElementById("SOL_DIRECCION").value = datares.data.direccion || "";
					document.getElementById("SOL_DIRECCION").dispatchEvent(new Event('input'));
					document.getElementById("SOL_NUM").value = datares.data.numero || "";
					document.getElementById("SOL_NUM").dispatchEvent(new Event('input'));
					document.getElementById("SOL_TELEFONO").value = datares.data.telefonoFijo || "";
					document.getElementById("SOL_TELEFONO").dispatchEvent(new Event('input'));
					document.getElementById("SOL_CELULAR").value = datares.data.telefonoCelular || "";
					document.getElementById("SOL_CELULAR").dispatchEvent(new Event('input'));
					document.getElementById("SOL_POSTAL").value = datares.data.casillaPostal || "";
					document.getElementById("SOL_POSTAL").dispatchEvent(new Event('input'));
					document.getElementById("SOL_CORREO").value = datares.data.correoElectronico || "";
					document.getElementById("SOL_CORREO").dispatchEvent(new Event('input'));
					if (datares.data.complemento !== null) {
						document.getElementById("SOL_COMPLEMENTO").value = datares.data.complemento || "";
						document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
					} else {
						document.getElementById("SOL_COMPLEMENTO").value = "";
						document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
					}
					document.getElementById("SOL_PARENTESCO").value = datares.data.descripcion || "";
					document.getElementById("SOL_PARENTESCO").dispatchEvent(new Event('input'));
					document.getElementById("SOL_CIUDAD").value = datares.data.ciudad || "";
					document.getElementById("SOL_CIUDAD").dispatchEvent(new Event('input'));
					document.getElementById("SOL_PROVINCIA").value = datares.data.provincia || "";
					document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
					document.getElementById("SOL_DEPARTAMENTO").value = datares.data.departamento || "";
					document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
					document.getElementById("SOL_ZONA").value = datares.data.zona || "";
					document.getElementById("SOL_ZONA").dispatchEvent(new Event('input'));
					document.getElementById("VALIDAR_PODER").value = datares.data.validarPoder || "";
					document.getElementById("VALIDAR_PODER").dispatchEvent(new Event('input'));
					document.getElementById("FECHA_REVISION").value = datares.data.fechaRevision || "";
					document.getElementById("FECHA_REVISION").dispatchEvent(new Event('input'))
				} else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {

					if (complemento === "") {
						document.getElementById("SOL_NACIMIENTO").disabled = false;
						if (fechaNacimiento !== "") {
							document.getElementById("SOL_NACIMIENTO").value = fechaNacimiento
							document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
						} else {
							overlay.style.display = 'none';
							alert("Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.");
							return;
						}
					} else {
						document.getElementById("SOL_COMPLEMENTO").value = complemento;
					}
				}
				//  else if (datares.codigoRespuesta === 2000) {
				//     alert("No se encontraron registros con la información proporcionada.");
				//     // return;

				// }
				else {
					limpiarFormularioSol();
				}
				console.log(datares);
			},
			error: function(err) {
				overlay.style.display = 'none';
				// Manejar errores de la solicitud AJAX
			}
		});
	} else {
		overlay.style.display = 'none';
		alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
	}
}

function limpiarFormulario() {
	// Limpiar todos los campos del formulario según sea necesario

	volverRequerido();

	document.getElementById("AS_FECHA_FALLECIMIENTO").value = "";
	document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));

	document.getElementById("AS_PRIMER_NOMBRE").value = "";
	document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
	document.getElementById("AS_SEGUNDO_NOMBRE").value = "";
	document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
	document.getElementById("AS_PRIMER_APELLIDO").value = "";
	document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
	document.getElementById("AS_SEGUNDO_APELLIDO").value = "";
	document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
	document.getElementById("AS_APELLIDO_CASADA").value = "";
	document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
	document.getElementById("AS_NACIMIENTO").value = "";
	document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
	document.getElementById("AS_CUA").value = "";
	document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
	document.getElementById("AS_GENERO").value = "";
	document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
	document.getElementById("AS_ESTADO_CIVIL").value = "";
	document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
	document.getElementById("AS_ENTE_GESTOR").value = "";
	document.getElementById("AS_ENTE_GESTOR").dispatchEvent(new Event('input'));
	document.getElementById("AS_MATRICULA_ASEGURADO").value = "";
	document.getElementById("AS_MATRICULA_ASEGURADO").dispatchEvent(new Event('input'));
	document.getElementById("AS_DEPARTAMENTO").value = "";
	document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('input'));
	document.getElementById("PROVINCIA").value = "";
	document.getElementById("PROVINCIA").dispatchEvent(new Event('input'));
	document.getElementById("AS_CIUDAD").value = "";
	document.getElementById("AS_CIUDAD").dispatchEvent(new Event('input'));
	document.getElementById("AS_ZONA").value = "";
	document.getElementById("AS_ZONA").dispatchEvent(new Event('input'));
	document.getElementById("AS_DIRECCION").value = "";
	document.getElementById("AS_DIRECCION").dispatchEvent(new Event('input'));
	document.getElementById("AS_NUM").value = "";
	document.getElementById("AS_NUM").dispatchEvent(new Event('input'));
	document.getElementById("AS_TELEFONO").value = "";
	document.getElementById("AS_TELEFONO").dispatchEvent(new Event('input'));
	document.getElementById("AS_CELULAR").value = "";
	document.getElementById("AS_CELULAR").dispatchEvent(new Event('input'));
	document.getElementById("AS_POSTAL").value = "";
	document.getElementById("AS_POSTAL").dispatchEvent(new Event('input'));
	document.getElementById("AS_CORREO").value = "";
	document.getElementById("AS_CORREO").dispatchEvent(new Event('input'));
	document.getElementById("AS_IDPERSONA").value = "";
	document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
	document.getElementById("AS_API_ESTADO").value = "";
	document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
	document.getElementById("AS_COMPLEMENTO").value = "";
	document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
	document.getElementById("AS_NACIMIENTO").value = "";
	document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
	document.getElementById("EM_TIPO_AS").value = "";
	document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));
	document.getElementById("EM_NOMBRE").value = "";
	document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
	document.getElementById("EM_FECHA").value = "";
	document.getElementById("EM_FECHA").dispatchEvent(new Event('input'));
	document.getElementById("EM_OCUPACION_AS").value = "";
	document.getElementById("EM_OCUPACION_AS").dispatchEvent(new Event('input'));
	document.getElementById("EM_DEPARTAMENTO").value = "";
	document.getElementById("EM_DEPARTAMENTO").dispatchEvent(new Event('input'));
	document.getElementById("EM_PROVINCIA").value = "";
	document.getElementById("EM_PROVINCIA").dispatchEvent(new Event('input'));
	document.getElementById("EM_CIUDAD").value = "";
	document.getElementById("EM_CIUDAD").dispatchEvent(new Event('input'));
	document.getElementById("EM_ZONA").value = "";
	document.getElementById("EM_ZONA").dispatchEvent(new Event('input'));
	document.getElementById("EM_DIRECCION").value = "";
	document.getElementById("EM_DIRECCION").dispatchEvent(new Event('input'));
	document.getElementById("EM_NUM").value = "";
	document.getElementById("EM_NUM").dispatchEvent(new Event('input'));
	document.getElementById("EM_TELEFONO").value = "";
	document.getElementById("EM_TELEFONO").dispatchEvent(new Event('input'));
	document.getElementById("EM_CELULAR").value = "";
	document.getElementById("EM_CELULAR").dispatchEvent(new Event('input'));
	document.getElementById("EM_CORREO").value = "";
	document.getElementById("EM_CORREO").dispatchEvent(new Event('input'));

	document.getElementById("AS_OBS_CVAP_2").value = "";
	document.getElementById("AS_OBS_CVAP_2").dispatchEvent(new Event('input'));
	document.getElementById("AS_OBS_CVAP_3").value = "";
	document.getElementById("AS_OBS_CVAP_3").dispatchEvent(new Event('input'));
	document.getElementById("AS_OBS_CVAP_4").value = "";
	document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));
	document.getElementById("AS_OBS_CVAP_5").value = "";
	document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));

	try {
		document.getElementById("AS_CERT_INSALUBRIDAD").value = "";
		document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));
		document.getElementById("AS_OBS_CVAP_1").value = "";
		document.getElementById("AS_OBS_CVAP_1").dispatchEvent(new Event('input'));
	} catch (error) {
		console.log("#");
	}

	document.getElementById("CERT_INSALUBRIDAD").value = "";
	document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));


}

function limpiarFormularioSol() {
	document.getElementById("SOL_PRIMER_NOMBRE").value = "";
	document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
	document.getElementById("SOL_SEGUNDO_NOMBRE").value = "";
	document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
	document.getElementById("SOL_PRIMER_APELLIDO").value = "";
	document.getElementById("SOL_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_SEGUNDO_APELLIDO").value = "";
	document.getElementById("SOL_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_APELLIDO_CASADA").value = "";
	document.getElementById("SOL_APELLIDO_CASADA").dispatchEvent(new Event('input'));
	document.getElementById("SOL_NACIMIENTO").value = "";
	document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_CUA").value = "";
	document.getElementById("SOL_CUA").dispatchEvent(new Event('input'));
	document.getElementById("SOL_GENERO").value = "";
	document.getElementById("SOL_GENERO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_ESTADO_CIVIL").value = "";
	document.getElementById("SOL_ESTADO_CIVIL").dispatchEvent(new Event('input'));
	document.getElementById("SOL_DEPARTAMENTO").value = "";
	document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_PROVINCIA").value = "";
	document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
	document.getElementById("SOL_CIUDAD").value = "";
	document.getElementById("SOL_CIUDAD").dispatchEvent(new Event('input'));
	document.getElementById("SOL_ZONA").value = "";
	document.getElementById("SOL_ZONA").dispatchEvent(new Event('input'));
	document.getElementById("SOL_DIRECCION").value = "";
	document.getElementById("SOL_DIRECCION").dispatchEvent(new Event('input'));
	document.getElementById("SOL_NUM").value = "";
	document.getElementById("SOL_NUM").dispatchEvent(new Event('input'));
	document.getElementById("SOL_TELEFONO").value = "";
	document.getElementById("SOL_TELEFONO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_CELULAR").value = "";
	document.getElementById("SOL_CELULAR").dispatchEvent(new Event('input'));
	document.getElementById("SOL_POSTAL").value = "";
	document.getElementById("SOL_POSTAL").dispatchEvent(new Event('input'));
	document.getElementById("SOL_CORREO").value = "";
	document.getElementById("SOL_CORREO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_IDPERSONA").value = "";
	document.getElementById("SOL_IDPERSONA").dispatchEvent(new Event('input'));
	document.getElementById("SOL_COMPLEMENTO").value = "";
	document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_NACIMIENTO").value = "";
	document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
	document.getElementById("SOL_PARENTESCO").value = "";
	document.getElementById("SOL_PARENTESCO").dispatchEvent(new Event('input'));
	document.getElementById("VALIDAR_PODER").value = "";
	document.getElementById("VALIDAR_PODER").dispatchEvent(new Event('input'));
	document.getElementById("FECHA_REVISION").value = "";
	document.getElementById("FECHA_REVISION").dispatchEvent(new Event('input'));
}

//  document.addEventListener("DOMContentLoaded", function() {
//   var asCi = document.getElementById("AS_CI");
//   if (asCi) {
//     asCi.addEventListener('input', function () {
//       document.getElementById("AS_NACIMIENTO").disabled = true;
//       limpiarFormulario();
//     });
//   } else {
//     console.error("Elemento AS_CI no encontrado en el DOM.");
//   }

//   var solCi = document.getElementById("SOL_CI");
//   if (solCi) {
//     solCi.addEventListener('input', function () {
//       document.getElementById("SOL_NACIMIENTO").disabled = true;
//       limpiarFormularioSol();
//     });
//   } else {
//     console.error("Elemento SOL_CI no encontrado en el DOM.");
//   }
//  });

function agregarEventListener(idInput, idElemento, limpiarFuncion) {
	var elemento = document.getElementById(idInput);
	if (elemento) {
		elemento.addEventListener('input', function() {
			document.getElementById(idElemento).disabled = true;
			limpiarFuncion();
		});
	} else {
		// console.error("Elemento " + idInput + " no encontrado en el DOM.");
	}
}

agregarEventListener("AS_CI", "AS_NACIMIENTO", limpiarFormulario);
agregarEventListener("SOL_CI", "SOL_NACIMIENTO", limpiarFormularioSol);


function formatoFecha(fecha, formato) {
	const map = {
		dd: fecha.getDate(),
		mm: fecha.getMonth() + 1,
		yy: fecha.getFullYear().toString().slice(-2),
		yyyy: fecha.getFullYear()
	}

	return formato.replace(/dd|mm|yy|yyy/gi, matched => map[matched])
}



function getCiudades() {
	$.ajax({
		dataType: 'json',
		type: 'GET',
		url: 'https://oficinavirtualservicios.gestora.bo/api/General/Ciudad',
		success: function(ciudades) {
			var selectCiudades = document.getElementById("AS_CIUDAD");
			console.log('');
			if (selectCiudades.options.length === 1) {
				var cantidadCiudades = ciudades.datos.length;
				for (var i = 0; i < cantidadCiudades; i++) {
					var option = document.createElement("option");
					option.value = ciudades.datos[i].codigoGeograficoId;
					option.text = ciudades.datos[i].descripcion + ' - ' + ciudades.datos[i].provincia + ' - ' + ciudades.datos[i].departamento;
					var datosProvinciaDepartamento = ciudades.datos[i].provinciaId + ";" +
						ciudades.datos[i].provincia + ";" +
						ciudades.datos[i].departamentoId + ";" +
						ciudades.datos[i].departamento + ";";
					// console.log('datosProvinciaDepartamento ',datosProvinciaDepartamento);
					option.setAttribute("data-provincia-departamento", datosProvinciaDepartamento);
					selectCiudades.appendChild(option);
				}
				// console.log(selectCiudades);
				var dataFrmValue = selectCiudades.getAttribute("data-frm-value");
				// console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> llego hasta aqui')
				// console.log('>>>> ', dataFrmValue);
				if (dataFrmValue !== undefined && dataFrmValue !== null) {
					selectCiudades.value = dataFrmValue;
					setProvinciaDepartamento();
				}
				//CIUDADES EM
				var selectCiudadesEM = document.getElementById("EM_CIUDAD");
				if (selectCiudadesEM.options.length === 1) {
					var cantidadCiudadesEM = ciudades.datos.length;
					for (var i = 0; i < cantidadCiudadesEM; i++) {
						var option = document.createElement("option");
						option.value = ciudades.datos[i].codigoGeograficoId;
						option.text = ciudades.datos[i].descripcion + ' - ' + ciudades.datos[i].provincia + ' - ' + ciudades.datos[i].departamento;
						var datosProvinciaDepartamentoEM = ciudades.datos[i].provinciaId + ";" +
							ciudades.datos[i].provincia + ";" +
							ciudades.datos[i].departamentoId + ";" +
							ciudades.datos[i].departamento + ";";
						option.setAttribute("data-provincia-departamento", datosProvinciaDepartamentoEM);
						selectCiudadesEM.appendChild(option);
					}
					var dataFrmValue = selectCiudadesEM.getAttribute("data-frm-value");
					if (dataFrmValue !== undefined && dataFrmValue !== null) {
						selectCiudadesEM.value = dataFrmValue;
						setProvinciaDepartamentoEM();
					}
				}
				//CIUDADES SOL
				var selectCiudadesSOL = document.getElementById("SOL_CIUDAD");
				if (selectCiudadesSOL.options.length === 1) {
					var cantidadCiudadesSOL = ciudades.datos.length;
					for (var i = 0; i < cantidadCiudadesSOL; i++) {
						var option = document.createElement("option");
						option.value = ciudades.datos[i].codigoGeograficoId;
						option.text = ciudades.datos[i].descripcion + ' - ' + ciudades.datos[i].provincia + ' - ' + ciudades.datos[i].departamento;
						var datosProvinciaDepartamentoSOL = ciudades.datos[i].provinciaId + ";" +
							ciudades.datos[i].provincia + ";" +
							ciudades.datos[i].departamentoId + ";" +
							ciudades.datos[i].departamento + ";";
						option.setAttribute("data-provincia-departamento", datosProvinciaDepartamentoSOL);
						selectCiudadesSOL.appendChild(option);
					}
					var dataFrmValue = selectCiudadesSOL.getAttribute("data-frm-value");
					if (dataFrmValue !== undefined && dataFrmValue !== null) {
						selectCiudadesSOL.value = dataFrmValue;
						setProvinciaDepartamentoSOL();
					}
				}
			}
		},
		error: function(xhr, status, error) {
			console.error('Error:', error);
			// Manejar el error aquí
		}
	});
}

function setProvinciaDepartamento() {
	var ciudadSeleccionada = $("#AS_CIUDAD").val();
	if (ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
		var datosProvinciaDepartamento = $("#AS_CIUDAD option:selected").data("provincia-departamento");
		var datos = datosProvinciaDepartamento.split(";");
		//PROVINCIA
		var selectCiudades = document.getElementById("PROVINCIA");
		selectCiudades.innerHTML = "";
		var option = document.createElement("option");
		option.value = datos[0];
		option.text = datos[1];
		selectCiudades.appendChild(option);
		document.getElementById("PROVINCIA").value = datos[0];
		document.getElementById("PROVINCIA").text = datos[1];
		document.getElementById("PROVINCIA").dispatchEvent(new Event('change'));
		//DEPARTAMENTO
		var selectCiudades = document.getElementById("AS_DEPARTAMENTO");
		selectCiudades.innerHTML = "";
		var option = document.createElement("option");
		option.value = datos[2];
		option.text = datos[3];
		selectCiudades.appendChild(option);
		document.getElementById("AS_DEPARTAMENTO").value = datos[2];
		document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('change'));
		document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('input'));
	} else {
		document.getElementById("PROVINCIA").value = "";
		document.getElementById("PROVINCIA").dispatchEvent(new Event('input'));
		document.getElementById("AS_DEPARTAMENTO").value = "";
		document.getElementById("AS_DEPARTAMENTO").dispatchEvent(new Event('input'));
	}
}

function setProvinciaDepartamentoEM() {
	var ciudadSeleccionada = $("#EM_CIUDAD").val();
	if (ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
		var datosProvinciaDepartamento = $("#EM_CIUDAD option:selected").data("provincia-departamento");
		var datos = datosProvinciaDepartamento.split(";");
		//PROVINCIA
		var selectCiudades = document.getElementById("EM_PROVINCIA");
		selectCiudades.innerHTML = "";
		var option = document.createElement("option");
		option.value = datos[0];
		option.text = datos[1];
		selectCiudades.appendChild(option);
		document.getElementById("EM_PROVINCIA").value = datos[0];
		document.getElementById("EM_PROVINCIA").dispatchEvent(new Event('change'));
		//DEPARTAMENTO
		var selectCiudades = document.getElementById("EM_DEPARTAMENTO");
		selectCiudades.innerHTML = "";
		var option = document.createElement("option");
		option.value = datos[2];
		option.text = datos[3];
		selectCiudades.appendChild(option);
		document.getElementById("EM_DEPARTAMENTO").value = datos[2];
		document.getElementById("EM_DEPARTAMENTO").dispatchEvent(new Event('change'));
	} else {
		document.getElementById("EM_PROVINCIA").value = "";
		document.getElementById("EM_PROVINCIA").dispatchEvent(new Event('input'));
		document.getElementById("EM_DEPARTAMENTO").value = "";
		document.getElementById("EM_DEPARTAMENTO").dispatchEvent(new Event('input'));
	}
}

function setProvinciaDepartamentoSOL() {
	var ciudadSeleccionada = $("#SOL_CIUDAD").val();
	if (ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
		var datosProvinciaDepartamento = $("#SOL_CIUDAD option:selected").data("provincia-departamento");
		var datos = datosProvinciaDepartamento.split(";");
		//PROVINCIA
		var selectCiudades = document.getElementById("SOL_PROVINCIA");
		selectCiudades.innerHTML = "";
		var option = document.createElement("option");
		option.value = datos[0];
		option.text = datos[1];
		selectCiudades.appendChild(option);
		document.getElementById("SOL_PROVINCIA").value = datos[0];
		document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('change'));
		//DEPARTAMENTO
		var selectCiudades = document.getElementById("SOL_DEPARTAMENTO");
		selectCiudades.innerHTML = "";
		var option = document.createElement("option");
		option.value = datos[2];
		option.text = datos[3];
		selectCiudades.appendChild(option);
		document.getElementById("SOL_DEPARTAMENTO").value = datos[2];
		document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('change'));
	} else {
		document.getElementById("SOL_PROVINCIA").value = "";
		document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
		document.getElementById("SOL_DEPARTAMENTO").value = "";
		document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
	}
}
//   (function() {
//     fetch('https://sipre.gestora.bo/spr-tram-rest/clasificador/tiposParentesco')
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Hubo un problema con la solicitud');
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log(data.data[0].descripcion); // Hacer algo con los datos recibidos
//             var tam = data.data.length;
//             for (var i = 0; i < tam; i++) {
//                 document.getElementById("SOL_PARENTESCO").innerHTML += "<option value=" + data.data[i].descripcion + ">" + data.data[i].descripcion + "</option>";
//             };
//             document.getElementById("SOL_PARENTESCO").value = "";
//         })
//         .catch(error => {
//             console.error('Error:', error);
//         });
//   })();


function cargarEnteGestor() {
	$.ajax({
		dataType: 'json',
		type: 'GET',
		url: 'https://sipre.gestora.bo/spr-tram-rest/clasificador/entesGestoresSalud',
		success: function(response) {
			var selectEnteGestor = document.getElementById("AS_ENTE_GESTOR");
			if (selectEnteGestor.options.length === 1) {
				var datos = response.data;
				for (var i = 0; i < datos.length; i++) {
					var option = document.createElement("option");
					option.value = datos[i].codigo;
					option.text = datos[i].descripcion;
					selectEnteGestor.appendChild(option);
				}
				// var dataFrmValue = selectGestSalud.getAttribute("data-frm-value");
				// // if (dataFrmValue !== undefined && dataFrmValue !== null) {
				// //   selectGestSalud.value = dataFrmValue;

				// // }

			}
		},
		error: function(xhr, status, error) {
			console.error('Error:', error);
			// Manejar el error aquí
		}
	});
}

(function() {
	fechaActual();
	consultarMuerte();
})();

function cargarParentesco() {
	$.ajax({
		dataType: 'json',
		type: 'GET',
		url: 'https://sipre.gestora.bo/spr-tram-rest/clasificador/tiposParentesco',
		success: function(response) {

			var selectParentesco = document.getElementById("SOL_PARENTESCO");
			if (selectParentesco.options.length === 1) {
				var datos = response.data;
				for (var i = 0; i < datos.length; i++) {
					var option = document.createElement("option");
					option.value = datos[i].id;
					option.text = datos[i].descripcion;
					option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
					selectParentesco.appendChild(option);
				}
			}
		},
		error: function(xhr, status, error) {
			console.error('Error:', error);
			// Manejar el error aquí
		}
	});
}

function matricula(datares) {
	var fecha = datares.data.fechaNacimiento.split("-");
	var gestion = fecha[0].substring(2);
	var mes = "";
	if (datares.data.idGenero == 'M') {
		mes = fecha[1];
	} else {
		if (datares.data.idGenero == 'F') {
			if (fecha[1].charAt(0) == '0') {
				mes = "5" + fecha[1].charAt(1);
			} else {
				if (fecha[1].charAt(0) == '1') {
					mes = "6" + fecha[1].charAt(1);
				} else {
					mes = fecha[1];
				}
			}

		}
	}

	var dia = fecha[2];
	var codigo = "";
	if (datares.data.segundoApellido == null || datares.data.segundoApellido == "") {
		codigo = datares.data.primerApellido.substring(0, 1) + "" + datares.data.primerNombre.substring(0, 2);
	} else {
		codigo = datares.data.primerApellido.substring(0, 1) + "" + datares.data.segundoApellido.substring(0, 1) + "" + datares.data.primerNombre.substring(0, 1);
	}

	var matricula = gestion + "" + mes + "" + dia + " " + codigo;
	console.log(matricula);

	document.getElementById("AS_MATRICULA_ASEGURADO").value = matricula;
	document.getElementById("AS_MATRICULA_ASEGURADO").dispatchEvent(new Event('input'));
}



function bloqueo() {
	var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
	console.log("SOL_DIFERENTE_AS: ", valor);

	if (!valor) {

	} else {
		_hide("SOL_BUSCAR");
		_disable("SOL_TIPO_DOCUMENTO");
		_disable("SOL_CIUDAD");
		_disable("SOL_PROVINCIA");
		_disable("SOL_DEPARTAMENTO");
		_disable("SOL_PARENTESCO");
		_disable("SOL_CI");
		_disable("SOL_COMPLEMENTO");
		_disable("SOL_NACIMIENTO");
		_disable("SOL_CUA");
		_disable("SOL_PRIMER_APELLIDO");
		_disable("SOL_SEGUNDO_APELLIDO");
		_disable("SOL_APELLIDO_CASADA");
		_disable("SOL_PRIMER_NOMBRE");
		_disable("SOL_SEGUNDO_NOMBRE");
		_disable("SOL_ESTADO_CIVIL");
		_disable("SOL_GENERO");
		_disable("SOL_ZONA");
		_disable("SOL_DIRECCION");
		_disable("SOL_NUM");
		_disable("SOL_TELEFONO");
		_disable("SOL_CELULAR");
		_disable("SOL_POSTAL");
		_disable("SOL_CORREO");
		_disable("SOL_IDPERSONA");
		_disable("TIENE_PODER_SOL_1");
		_disable("NRO_PODER_SOL_1");
		_disable("NRO_NOTARIA_SOL_1");
		_disable("NOMBRE_NOTARIO_SOL_1");
	}
}
$(document).ready(function() {
	console.log('como es ');
	bloqueo();
});

function datosTipoAsegurado() {
	var cua = document.getElementById("AS_CUA").value;
	$.ajax({
		dataType: 'json',
		type: 'GET',
		url: 'https://sgg.gestora.bo/otorgamiento-prestaciones-cpp/api/cppSeguimientoTramite/afiliadoHistorialLaboral?cua=' + cua,
		success: function(response) {
			if (response.codigo == '200') {
				document.getElementById("EM_NOMBRE").value = '';
				document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));

				var primerNombre = document.getElementById("AS_PRIMER_NOMBRE") ? document.getElementById("AS_PRIMER_NOMBRE").value : '';
				var segundoNombre = document.getElementById("AS_SEGUNDO_NOMBRE") ? document.getElementById("AS_SEGUNDO_NOMBRE").value : '';
				var primerApellido = document.getElementById("AS_PRIMER_APELLIDO") ? document.getElementById("AS_PRIMER_APELLIDO").value : '';
				var segundoApellido = document.getElementById("AS_SEGUNDO_APELLIDO") ? document.getElementById("AS_SEGUNDO_APELLIDO").value : '';
				var apellidoCasada = document.getElementById("AS_APELLIDO_CASADA") ? document.getElementById("AS_APELLIDO_CASADA").value : '';

				var nombreCompleto = primerNombre + ' ' + segundoNombre + ' ' + primerApellido + ' ' + segundoApellido + ' ' + apellidoCasada;

				var tipo = document.getElementById("EM_TIPO_AS").value;
				if (tipo == 'D') {
					if (response.data.tipoAportante == 'DEPENDIENTE') {
						document.getElementById("EM_NOMBRE").value = response.data.razonSocial;
						document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
					}
				}
				if (tipo == 'I') {
					if (response.data.tipoAportante == 'INDEPENDIENTE') {
						if (response.data.razonSocial != null && response.data.razonSocial != '') {
							document.getElementById("EM_NOMBRE").value = response.data.razonSocial;
							document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
						} else {
							document.getElementById("EM_NOMBRE").value = nombreCompleto;
							document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
						}
					} else {
						document.getElementById("EM_NOMBRE").value = nombreCompleto;
						document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
					}
				}
			}
		},
		error: function(xhr, status, error) {
			console.error('Error:', error);
			// Manejar el error aquí
		}
	});
}

function fechaActual() {
	try {
		var a = document.getElementById("AS_TIPO_EAP").value;
		if (a == 'CVEAP-B1') {

			_show("CASO_RECHAZADO_idd");
			_show("CASO_RECHAZADO");
			_show("REC_BUSCAR");
			_hide("FORM_PAGCC_FECHA_APERSONAMIENTO_idd");
			_hide("FORM_PAGCC_FECHA_APERSONAMIENTO");
			console.log("POR verdad CVEAP-B1 s ss");
			document.getElementById("FECHA_INICIO_TRAMITE").disabled = true;

		} else if (a == 'CVEAP-B4') {

			_show("FORM_PAGCC_FECHA_APERSONAMIENTO_idd");
			_show("FORM_PAGCC_FECHA_APERSONAMIENTO");
			document.getElementById("FORM_PAGCC_FECHA_APERSONAMIENTO").setAttribute("required", "required");
			_hide("CASO_RECHAZADO_idd");
			_hide("CASO_RECHAZADO");
			_hide("REC_BUSCAR");
			document.getElementById("FECHA_INICIO_TRAMITE").disabled = true;

		} else if (a == 'CVEAP-B2') {
			console.log("POR TRUE");
			document.getElementById("FECHA_INICIO_TRAMITE").disabled = true;
			document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = true;
			document.querySelector('#AS_TELEFONO').required = false;
			document.querySelector('#AS_CORREO').required = false;
			_hide("CASO_RECHAZADO_idd");
			_hide("CASO_RECHAZADO");
			_hide("REC_BUSCAR");
			document.getElementById("FECHA_INICIO_TRAMITE").disabled = true;

		} else if (a == 'CVEAP-B5') {

			_show("CASO_RECHAZADO_idd");
			_show("CASO_RECHAZADO");
			_show("REC_BUSCAR");
			_hide("FORM_PAGCC_FECHA_APERSONAMIENTO_idd");
			_hide("FORM_PAGCC_FECHA_APERSONAMIENTO");
			console.log("POR verdad CVEAP-B1 s ss");
			const CASO_RECHAZADO = document.getElementById("CASO_RECHAZADO").value;
			if (CASO_RECHAZADO == '') {
				document.getElementById("FECHA_INICIO_TRAMITE").disabled = false;
				console.log("POR verdad CVEAP-B1 s ss");

			}


		} else {
			_hide("FORM_PAGCC_FECHA_APERSONAMIENTO_idd");
			_hide("FORM_PAGCC_FECHA_APERSONAMIENTO");
			_hide("CASO_RECHAZADO_idd");
			_hide("CASO_RECHAZADO");
			_hide("REC_BUSCAR");
			document.getElementById("FECHA_INICIO_TRAMITE").disabled = true;
		}
	} catch (e) {}
}

function mensajeAlerta() {
	try {
		var a = document.getElementById("AS_TIPO_EAP").value;
		if (a == 'CVEAP-B1') {
			document.querySelector('#CASO_RECHAZADO').required = true;
			document.getElementById("CASO_RECHAZADO").setAttribute("required", "required");
			const div = document.getElementById('CASO_RECHAZADO_idd');
			const label = div.querySelector('label');
			if (label && !label.textContent.includes(' (*)')) {
				label.textContent += ' (*)';
			}
			console.log('esta entrando ');
			var overlay = document.getElementById("overlay");
			overlay.style.display = 'flex';
			var listaMensajes = `<h1>Registre el número de trámite de Jubilacion rechazado, y presione el botón "BUSCAR" <h1>`;
			var overlay = document.getElementById("overlay");
			overlay.style.display = 'flex';
			overlay.style.display = 'none';
			var modal = document.getElementById("modalGenerico");
			var icono = document.querySelector(".icono-advertencia");
			icono.src = "img/aviso_inportante.jpg";
			var modalCerrarBtn = document.getElementById("modalGenerico-boton");
			modalCerrarBtn.textContent = "Continuar";
			var modalTitulo = document.getElementById("modalGenerico-titulo");
			var modalMensaje = document.getElementById("modalGenerico-mensaje");
			modalTitulo.textContent = "INFORMACIÓN IMPORTANTE";
			modalMensaje.innerHTML = listaMensajes;
			modal.style.display = "block";
		} else if (a == 'CVEAP-B4') {
			document.querySelector('#CASO_RECHAZADO').required = false;
			const div = document.getElementById('CASO_RECHAZADO_idd');
			document.getElementById("CASO_RECHAZADO").removeAttribute("required");
			const label = div.querySelector('label');
			if (label) {
				label.textContent = label.textContent.replace(' (*)', '');
			}
		} else if (a == 'CVEAP-B2') {
			document.querySelector('#CASO_RECHAZADO').required = false;
			const div = document.getElementById('CASO_RECHAZADO_idd');
			document.getElementById("CASO_RECHAZADO").removeAttribute("required");
			const label = div.querySelector('label');
			if (label) {
				label.textContent = label.textContent.replace(' (*)', '');
			}
		} else if (a == 'CVEAP-B5') {
			document.querySelector('#CASO_RECHAZADO').required = true;
			document.getElementById("CASO_RECHAZADO").setAttribute("required", "required");
			const div = document.getElementById('CASO_RECHAZADO_idd');
			const label = div.querySelector('label');
			if (label && !label.textContent.includes(' (*)')) {
				label.textContent += ' (*)';
			}
			console.log('esta entrando ');
			var overlay = document.getElementById("overlay");
			overlay.style.display = 'flex';
			var listaMensajes = `<h1>  Registre el número de trámite de Pensión por Muerte rechazado, y presione el botón "BUSCAR"  <h1>`;
			var overlay = document.getElementById("overlay");
			overlay.style.display = 'flex';
			overlay.style.display = 'none';
			var modal = document.getElementById("modalGenerico");
			var icono = document.querySelector(".icono-advertencia");
			icono.src = "img/aviso_inportante.jpg";
			var modalCerrarBtn = document.getElementById("modalGenerico-boton");
			modalCerrarBtn.textContent = "Continuar";
			var modalTitulo = document.getElementById("modalGenerico-titulo");
			var modalMensaje = document.getElementById("modalGenerico-mensaje");
			modalTitulo.textContent = "INFORMACIÓN IMPORTANTE";
			modalMensaje.innerHTML = listaMensajes;
			modal.style.display = "block";
		} else {
			document.querySelector('#CASO_RECHAZADO').required = false;
			const div = document.getElementById('CASO_RECHAZADO_idd');
			document.getElementById("CASO_RECHAZADO").removeAttribute("required");
			const label = div.querySelector('label');
			if (label) {
				label.textContent = label.textContent.replace(' (*)', '');
			}
		}
	} catch (e) {}
}


function getDatosRechazado() {

}