function esSolicitante(){
    var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
    console.log("SOL_DIFERENTE_AS: ", valor);
    if(!valor){
       // getCiudades(f);
        document.querySelector('#NRO_PODER_SOL_1').required = true;
        document.querySelector('#TIENE_PODER_SOL_1').required = true;
        document.querySelector('#NRO_NOTARIA_SOL_1').required = true;
        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = true;
        _enable("TIENE_PODER_SOL_1");

  solicitanteCiudad();
        _show("SOL_BUSCAR");
        _enable("SOL_TIPO_DOCUMENTO");
        _setValue("SOL_TIPO_DOCUMENTO", "I"); 
        document.getElementById("SOL_TIPO_DOCUMENTO").dispatchEvent(new Event('input'));
        _enable("SOL_CI");
        _setValue("SOL_CI",  "");
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
        _setValue("SOL_PRIMER_APELLIDO",  "");
        document.getElementById("SOL_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
        _enable("SOL_SEGUNDO_APELLIDO");
        _setValue("SOL_SEGUNDO_APELLIDO",  "");
        document.getElementById("SOL_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
        _enable("SOL_PRIMER_NOMBRE");
        _setValue("SOL_PRIMER_NOMBRE",  "");
        document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
        _setValue("SOL_SEGUNDO_NOMBRE", "");
        document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
        _enable("SOL_PARENTESCO");
        _setValue("SOL_PARENTESCO", '0-TIT');
        document.getElementById("SOL_PARENTESCO").dispatchEvent(new Event('input'));
        _enable("SOL_ESTADO_CIVIL");
        _setValue("SOL_ESTADO_CIVIL",  "");
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
        _setValue("SOL_TELEFONO",  "");
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
        _setValue("SOL_PROVINCIA",  "");
        document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
        _enable("SOL_DEPARTAMENTO");
        _setValue("SOL_DEPARTAMENTO",  "");
        document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
        _enable("NRO_PODER_SOL_1");
        _enable("NRO_NOTARIA_SOL_1");
        _enable("NOMBRE_NOTARIO_SOL_1");
        
        var selectPoder = document.getElementById("TIENE_PODER_SOL_1");
        selectPoder.innerHTML = "";
        var option = document.createElement("option");
        option.value = "1";
        option.text = "1 - SI";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectPoder.appendChild(option);
        var option = document.createElement("option");
        option.value = "2" ;
        option.text = "2 - NO";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectPoder.appendChild(option);
  
        _enable("SOL_TIPO_DOCUMENTO");
        var selectCedulaIdentidad = document.getElementById("SOL_TIPO_DOCUMENTO");
        selectCedulaIdentidad.innerHTML = "";
        var option = document.createElement("option");
        option.value = "I";
        option.text = "I - CEDULA IDENTIDAD";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectCedulaIdentidad.appendChild(option);
        var option = document.createElement("option");
        option.value = "E" ;
        option.text = "E - EXTRANJERO";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectCedulaIdentidad.appendChild(option);

        var option = document.createElement("option");
        option.value = "P" ;
        option.text = "P - PASAPORTE";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectCedulaIdentidad.appendChild(option);
    } else {
        console.log('entro por falso ');
        document.querySelector('#NRO_PODER_SOL_1').required = false;
        document.querySelector('#TIENE_PODER_SOL_1').required = false;
        document.querySelector('#NRO_NOTARIA_SOL_1').required = false;
        document.querySelector('#NOMBRE_NOTARIO_SOL_1').required = false;
        _hide("SOL_BUSCAR");
  
        console.log('entro por falso11111111111111111 ');
        _disable("SOL_CI");
        console.log('11111111111 ');
        _setValue("SOL_CI", _getValue("AS_CI"));
        console.log('222222222222 ');
        document.getElementById("SOL_CI").dispatchEvent(new Event('input'));
        console.log('3333333333333 ');
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
        _disable("SOL_PRIMER_NOMBRE");
        _setValue("SOL_PRIMER_NOMBRE", _getValue("AS_PRIMER_NOMBRE"));
        document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
        _disable("SOL_SEGUNDO_NOMBRE");
        _setValue("SOL_SEGUNDO_NOMBRE", _getValue("AS_SEGUNDO_NOMBRE"));
        document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
    
        _enable("SOL_DEPARTAMENTO");
        _setValue("SOL_DEPARTAMENTO",  "");
        document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
  
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
        var selectedText = selectElement.options[selectedIndex].innerText ;
        var selectTipoDocumento = document.getElementById("SOL_TIPO_DOCUMENTO");
        selectTipoDocumento.innerHTML = "";
        var option = document.createElement("option");
        option.value = tipoDocumento;
        option.text = selectedText;
        selectTipoDocumento.appendChild(option);
        document.getElementById("SOL_TIPO_DOCUMENTO").value =  tipoDocumento;
        document.getElementById("SOL_TIPO_DOCUMENTO").dispatchEvent(new Event('change'));
        _disable("SOL_TIPO_DOCUMENTO");
  
        var tipo = document.getElementById("AS_CIUDAD").value;
        var selectElement = document.getElementById("AS_CIUDAD");
        var selectedIndex = selectElement.selectedIndex;
        var selectedText = selectElement.options[selectedIndex].innerText ;
        var selectTipo = document.getElementById("SOL_CIUDAD");
        selectTipo.innerHTML = "";
        var option = document.createElement("option");
        option.value = tipo;
        option.text = selectedText;
        selectTipo.appendChild(option);
        document.getElementById("SOL_CIUDAD").value =  tipo;
        document.getElementById("SOL_CIUDAD").dispatchEvent(new Event('change'));
        _disable("SOL_CIUDAD");
  
        var tipo = document.getElementById("PROVINCIA").value;
        var selectElement = document.getElementById("PROVINCIA");
        var selectedIndex = selectElement.selectedIndex;
        var selectedText = selectElement.options[selectedIndex].innerText ;
        var selectTipo = document.getElementById("SOL_PROVINCIA");
        selectTipo.innerHTML = "";
        var option = document.createElement("option");
        option.value = tipo;
        option.text = selectedText;
        selectTipo.appendChild(option);
        document.getElementById("SOL_PROVINCIA").value =  tipo;
        document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('change'));
        _disable("SOL_PROVINCIA");
  
        var tipo = document.getElementById("AS_DEPARTAMENTO").value;
        var selectElement = document.getElementById("AS_DEPARTAMENTO");
        var selectedIndex = selectElement.selectedIndex;
        var selectedText = selectElement.options[selectedIndex].innerText ;
        var selectTipo = document.getElementById("SOL_DEPARTAMENTO");
        selectTipo.innerHTML = "";
        var option = document.createElement("option");
        option.value = tipo;
        option.text = selectedText;
        selectTipo.appendChild(option);
        document.getElementById("SOL_DEPARTAMENTO").value =  tipo;
        document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('change'));
        _disable("SOL_DEPARTAMENTO");
        
        var selectTipo = document.getElementById("SOL_PARENTESCO");
        selectTipo.innerHTML = "";
        var option = document.createElement("option");
        option.value =  "-";
        option.text =  "-";
        selectTipo.appendChild(option);
        document.getElementById("SOL_PARENTESCO").value =   "-";
        document.getElementById("SOL_PARENTESCO").dispatchEvent(new Event('change'));
        _disable("SOL_PARENTESCO");

        var selectTipo = document.getElementById("TIENE_PODER_SOL_1");
        selectTipo.innerHTML = "";
        var option = document.createElement("option");
        option.value =  "2";
        option.text =  "2 - NO";
        selectTipo.appendChild(option);
        document.getElementById("TIENE_PODER_SOL_1").value =   "2";
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
  
  function solicitanteCiudad(){
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
            option.text = ciudades.datos[i].descripcion +' - '+ciudades.datos[i].provincia +' - '+ciudades.datos[i].departamento;
            var datosProvinciaDepartamentoSOL = ciudades.datos[i].provinciaId+";"
                            +ciudades.datos[i].provincia+";"
                            +ciudades.datos[i].departamentoId+";"
                            +ciudades.datos[i].departamento+";";
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
    var b = document.getElementById("DH_PARENTESCO" + rowIndex).value;
    const palabras = b.split("-");    
    const dh_grado = document.getElementById("DH_GRADO" + rowIndex);
    const dh_estado_civil = document.getElementById("DH_ESTADO_CIVIL" + rowIndex);

    if(palabras[0]=="3"){
        dh_grado.style.display = "block";
        console.log("so");      
    } else{
        dh_grado.style.display = "none";
    }
    if(palabras[0] =="1" && palabras[1] =="CONV"){
        dh_estado_civil.style.display = "block";        
        console.log("mensaje True");
    } else {
        dh_estado_civil.style.display = "none";
        dh_grado.style.display = "none";
        console.log("false");
    }
  }  
  
  function verDatosDh(rowIndex) {
	var overlay = document.getElementById("overlay");
    overlay.style.display = 'flex';
    var tipoDocumento = document.getElementById("DH_TIPO_DOCUMENTO" + rowIndex) ? document.getElementById("DH_TIPO_DOCUMENTO" + rowIndex).value : '';
    var numeroDocumento = document.getElementById("DH_CI_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).value : '';
    var complemento = document.getElementById("DH_COMP_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_COMP_GRILLA_PROP" + rowIndex).value : '';
    if (tipoDocumento !== "" && numeroDocumento !== "") {
        var requestData = {
            "tipoDocumento": tipoDocumento,
            "numeroDocumento": numeroDocumento,
            "complemento": complemento
        };
        $.ajax({
            dataType: 'json',
            contentType: 'application/json',
            type: 'POST',
            data: JSON.stringify(requestData),
            url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
            success: function (datares) {
                if (datares.codigoRespuesta == 0) {
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
            error: function (err) {
				overlay.style.display = 'none';
                // Manejar errores de la solicitud AJAX
            }
        });
    } else {
		overlay.style.display = 'none';
        alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
    }
  }

  function verDatos() {
    console.log("consultado los datos ")
    var overlay = document.getElementById("overlay");
    overlay.style.display = 'flex';
    var f = new Date();
    var year = f.getFullYear();
    var month = (f.getMonth() +1)+''; 
    var day = (f.getDate())+'';
    if (month.length < 2)  month = '0' + month; 
    if (day.length < 2) day = '0' + day;
    fecha = year + '-' + month + '-' + day;
      document.getElementById("SOL_DIFERENTE_AS").checked = false;
    //esSolicitante();
    var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
    var numeroDocumento = document.getElementById("AS_CI").value;
    var complemento = document.getElementById("AS_COMPLEMENTO").value;
    var fechaNacimiento = document.getElementById("AS_NACIMIENTO").value;
    var subClasificacion = document.getElementById("AS_TIPO_EAP").value; 
  
    if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {
        if (subClasificacion === ""){
            overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");
            modalTitulo.textContent = "Datos del Asegurado";
            modalMensaje.textContent = "Debe seleccionar el Subclasificación - trámite Retiro Mínimo para continuar la búsqueda.";
            modal.style.display = "block";
            return;
          } else {
            const tipo_busqueda = (subClasificacion === 'CVEAP-A9') ? 'NRF' : 'T';
            // Construir el cuerpo de la solicitud
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
                url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
                success: function (datares) {
                    if (datares.codigoRespuesta == 0) {
                            console.log("DATARES > ", datares);
                            const date = new Date(datares.data.fechaNacimiento);
                            const today  = new Date(); 
                            let diffYears = today.getFullYear() - date.getFullYear();


if(subClasificacion === 'CVEAP-A15'){
 overlay.style.display = 'none';
                                // Actualizar campos del formulario con los datos recibidos
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
                                document.getElementById("AS_CUA").value = datares.data.cua || "";
                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));  

                                document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));

                                document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));       

                                // setEnteGestorNombre();

                                if (datares.data.idGenero === 'M') {
                                    document.getElementById("AS_GENERO").value = 'MASCULINO';
                                } else {
                                    document.getElementById("AS_GENERO").value = 'FEMENINO';
                                }
                                document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                if (datares.data.idEstadoCivil === 'C') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                }  else if (datares.data.idEstadoCivil === 'V') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'VIUDO(A)';
                                }  else if (datares.data.idEstadoCivil === 'D') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'DIVORCIADO(A)';
                                }  else {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                }

                                // console.log("datares.data.apiEstado >", datares.data.apiEstado);
                                if (["FALLECIDO"].includes(datares.data.apiEstado)) {
                                    quitarRequerido();
                                } else {
                                    volverRequerido();
                                }

                                   document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));  

                                    document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                    document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input')); 
                                                            
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
                                    matricula(datares);
                                    if (datares.data.complemento !== null) {
                                        document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                        document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                    } else {
                                        document.getElementById("AS_COMPLEMENTO").value = "";
                                        document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                    }  
                                    document.getElementById("AS_ENTE_HERRAMIENTA").value = datares.data.asEnteHerramienta || "";                  
                                    document.getElementById("AS_ENTE_HERRAMIENTA").dispatchEvent(new Event('input')); 
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
                                    // document.getElementById("EM_TIPO_AS").value = datares.data.tipoAsegurado || "";
                                    // document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));  
                                    //document.getElementById("EM_NOMBRE").value = "holas";//datares.data.nombreEmpleador || "";
                                    // document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
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
                                    document.getElementById("AS_CERT_INSALUBRIDAD").value = datares.data.certifInsalubridad || "";
                                    document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));                                                            
                                    document.getElementById("CERT_INSALUBRIDAD").value = datares.data.documentSalubridad || "";
                                    document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));   


    }  else  if (subClasificacion === 'CVEAP-A9'){
        
                        console.log("Datares=========>>>", datares);
                        const cua = datares.data.cua;


                        $.ajax({
                            dataType: 'json',
                            type: 'GET',
                            url: 'https://test.sgg.gestora.bo/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c='+cua+'&user=fernando.flores@gestora.bo',
                            success: function(response) {
                                console.log('response.dasdasdasdasdsadsaddasddsadasdasdasdas ======>>>>>', response.data);
                                data = response.data;
                               if (data.CCG) {

                                    document.getElementById("AS_CC").value = data.CCG.monto;
                                    document.getElementById("AS_CC").dispatchEvent(new Event('input'));
                                        _disable("AS_CC");
                                    if(diffYears >= 58 ){

                                        console.log('es mayor a 58 =======>>>'); 
                                        var listaMensajes = `
                                            <ul>
                                                <li>Es mayor de 58 años</li>
                                                <li>Edad del Asegurado : ${diffYears} años </li>
                                                <li>Fecha de Fallecimiento : ${datares.data.fechaDefuncion} años </li>
                                            </ul>
                                        `;
                                        overlay.style.display = 'none';
                                        var modal = document.getElementById("modalGenerico");
                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                        modalTitulo.textContent = "Datos del Asegurado";
                                        modalMensaje.innerHTML = listaMensajes;
                                        modal.style.display = "block";
                                        overlay.style.display = 'none';
                                        // Actualizar campos del formulario con los datos recibidos
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
                                        document.getElementById("AS_CUA").value = datares.data.cua || "";
                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));  

                                        document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                        document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));

                                        document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                        document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));       

                                        // setEnteGestorNombre();

                                        if (datares.data.idGenero === 'M') {
                                            document.getElementById("AS_GENERO").value = 'MASCULINO';
                                        } else {
                                            document.getElementById("AS_GENERO").value = 'FEMENINO';
                                        }
                                        document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                        if (datares.data.idEstadoCivil === 'C') {
                                            document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                        } else {
                                            document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                        }

                                        // console.log("datares.data.apiEstado >", datares.data.apiEstado);
                                        if (["FALLECIDO"].includes(datares.data.apiEstado)) {
                                            quitarRequerido();
                                        } else {
                                            volverRequerido();
                                        }

                                        document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));  

                                            document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                            document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input')); 
                                                                    
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
                                            matricula(datares);
                                            if (datares.data.complemento !== null) {
                                                document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                                document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                            } else {
                                                document.getElementById("AS_COMPLEMENTO").value = "";
                                                document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                            }  
                                            document.getElementById("AS_ENTE_HERRAMIENTA").value = datares.data.asEnteHerramienta || "";                  
                                            document.getElementById("AS_ENTE_HERRAMIENTA").dispatchEvent(new Event('input')); 
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
                                            // document.getElementById("EM_TIPO_AS").value = datares.data.tipoAsegurado || "";
                                            // document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));  
                                            //document.getElementById("EM_NOMBRE").value = "holas";//datares.data.nombreEmpleador || "";
                                            // document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
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
                                            document.getElementById("AS_CERT_INSALUBRIDAD").value = datares.data.certifInsalubridad || "";
                                            document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));                                                            
                                            document.getElementById("CERT_INSALUBRIDAD").value = datares.data.documentSalubridad || "";
                                            document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));   

                                    } else {
                                        console.log('es mayor a 58 =======>>>'); 
                                        var listaMensajes = `
                                            <ul>
                                                <li>Es menor de 58 años</li>
                                                <li>Edad del Asegurado : ${diffYears} años </li>
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




                                } else {


                                    var listaMensajes = `
                                        <ul>
                                            <li> No Tiene CCG </li>
                                        </ul>
                                    `;
                                    overlay.style.display = 'none';
                                    var modal = document.getElementById("modalGenerico");
                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                    modalTitulo.textContent = "No Tiene CCG";
                                    modalMensaje.innerHTML = listaMensajes;
                                    modal.style.display = "block";
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                // Manejar el error aquí
                            }
                        });








                       

} else {
 overlay.style.display = 'none';
                                // Actualizar campos del formulario con los datos recibidos
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
                                document.getElementById("AS_CUA").value = datares.data.cua || "";
                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));  

                                document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));

                                document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));       

                                // setEnteGestorNombre();

                                if (datares.data.idGenero === 'M') {
                                    document.getElementById("AS_GENERO").value = 'MASCULINO';
                                } else {
                                    document.getElementById("AS_GENERO").value = 'FEMENINO';
                                }
                                document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                if (datares.data.idEstadoCivil === 'C') {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                } else {
                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                }

                                // console.log("datares.data.apiEstado >", datares.data.apiEstado);
                                if (["FALLECIDO"].includes(datares.data.apiEstado)) {
                                    quitarRequerido();
                                } else {
                                    volverRequerido();
                                }

                                   document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));  

                                    document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                    document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input')); 
                                                            
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
                                    matricula(datares);
                                    if (datares.data.complemento !== null) {
                                        document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                        document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                    } else {
                                        document.getElementById("AS_COMPLEMENTO").value = "";
                                        document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                    }  
                                    document.getElementById("AS_ENTE_HERRAMIENTA").value = datares.data.asEnteHerramienta || "";                  
                                    document.getElementById("AS_ENTE_HERRAMIENTA").dispatchEvent(new Event('input')); 
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
                                    // document.getElementById("EM_TIPO_AS").value = datares.data.tipoAsegurado || "";
                                    // document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));  
                                    //document.getElementById("EM_NOMBRE").value = "holas";//datares.data.nombreEmpleador || "";
                                    // document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
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
                                    document.getElementById("AS_CERT_INSALUBRIDAD").value = datares.data.certifInsalubridad || "";
                                    document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));                                                            
                                    document.getElementById("CERT_INSALUBRIDAD").value = datares.data.documentSalubridad || "";
                                    document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));   


    
} 



                        







                                    
                    } else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {
                        if (complemento === "") {
                            document.getElementById("AS_NACIMIENTO").disabled = false;
                            if (fechaNacimiento !== "") {
                                document.getElementById("AS_NACIMIENTO").value = fechaNacimiento;
                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                            }
                            else {
                                //alert("Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.");
                                //return;
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
                            document.getElementById("AS_COMPLEMENTO").value =datares.data.complemento;                           
                        }
                    } else {
                        if(datares.codigoRespuesta === "5") {
                            overlay.style.display = 'none';
                            var modal = document.getElementById("modalGenerico");
                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                            modalTitulo.textContent = "Resultados ";
                            modalMensaje.textContent ="El numero del documento ingresado no corresponde a NRF, Favor revisar";
                            modal.style.display = "block";
                        }
                        limpiarFormulario();
                    }
                    console.log(datares);
                },
                error: function (err) {
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

  function consultarMuerte(){
    try{
        const estadoApi = document.getElementById('AS_API_ESTADO').value;

        if (estadoApi == 'FALLECIDO'){
            quitarRequerido();
        } else {
            console.log("El otro estado de la api >>", estadoApi);
            const _fecha = document.getElementById("AS_FECHA_FALLECIMIENTO").value;
    
            if(_fecha){
                quitarRequerido();
            } else {
                volverRequerido();
            }
        }
    }catch(e){

    }
  }

  function volverRequerido(){
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

  function quitarRequerido(){
    const div = document.getElementById('AS_CORREO_idd');
    const as_celular = document.getElementById('AS_CELULAR_idd');

    document.getElementById("AS_CORREO").removeAttribute("required");
    document.getElementById("AS_CELULAR").removeAttribute("required");

    const label = div.querySelector('label');
    const label_celular = as_celular.querySelector('label');
    
    if (label) {
        label.textContent = label.textContent.replace(' (*)', '');
    }  
    
    if (label_celular){
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
    var fechaNacimiento= document.getElementById("SOL_NACIMIENTO").value;
  
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
            url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
            success: function (datares) {
                if (datares.codigoRespuesta == 0) {
					overlay.style.display = 'none';
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
                    document.getElementById("FECHA_REVISION").dispatchEvent(new Event('input'));
                } 
                else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {
                    if (complemento === "") {
                        document.getElementById("SOL_NACIMIENTO").disabled = false;
                        if (fechaNacimiento !== "") {
                            document.getElementById("SOL_NACIMIENTO").value = fechaNacimiento
                            document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
                        }
                        else {
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
            error: function (err) {
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
    document.getElementById("EM_TIPO_AS").value =  "";
    document.getElementById("EM_TIPO_AS").dispatchEvent(new Event('input'));  
    document.getElementById("EM_NOMBRE").value =  "";
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
    document.getElementById("AS_OBS_CVAP_1").value = "";
    document.getElementById("AS_OBS_CVAP_1").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_2").value = "";
    document.getElementById("AS_OBS_CVAP_2").dispatchEvent(new Event('input'));  
    document.getElementById("AS_OBS_CVAP_3").value = "";
    document.getElementById("AS_OBS_CVAP_3").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_4").value =  "";
    document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));  
    document.getElementById("AS_OBS_CVAP_5").value = "";
    document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));     
    document.getElementById("AS_CERT_INSALUBRIDAD").value = "";
    document.getElementById("AS_CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));                                     
    document.getElementById("CERT_INSALUBRIDAD").value = "";
    document.getElementById("CERT_INSALUBRIDAD").dispatchEvent(new Event('input'));     
    
    document.getElementById("AS_FECHA_FALLECIMIENTO").value = "";
    document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));

    volverRequerido();
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
    document.getElementById("SOL_IDPERSONA").value =  "";
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
            elemento.addEventListener('input', function () {
                document.getElementById(idElemento).disabled = true;
                limpiarFuncion();
            });
        } else {
            // console.error("Elemento " + idInput + " no encontrado en el DOM.");
        }
    }
    
    agregarEventListener("AS_CI", "AS_NACIMIENTO", limpiarFormulario);
    agregarEventListener("SOL_CI", "SOL_NACIMIENTO", limpiarFormularioSol);
    
    (function() {
        getCiudades();
        camposCopiaSolicitante();
        consultarMuerte();
    })();
    function getCiudades() {
        console.log('ciudades');
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'https://oficinavirtualservicios.gestora.bo/api/General/Ciudad',
            success: function(ciudades) {
                var selectCiudades = document.getElementById("AS_CIUDAD");
                if (selectCiudades.options.length === 1) {
                    var cantidadCiudades = ciudades.datos.length;
                    for (var i = 0; i < cantidadCiudades; i++) {
                        var option = document.createElement("option");
                        option.value = ciudades.datos[i].codigoGeograficoId;
                        option.text = ciudades.datos[i].descripcion +' - '+ciudades.datos[i].provincia +' - '+ciudades.datos[i].departamento;
                        var datosProvinciaDepartamento = ciudades.datos[i].provinciaId+";"
                            +ciudades.datos[i].provincia+";"
                            +ciudades.datos[i].departamentoId+";"
                            +ciudades.datos[i].departamento+";";
                        option.setAttribute("data-provincia-departamento", datosProvinciaDepartamento);
                        selectCiudades.appendChild(option);
                    }
                var dataFrmValue = selectCiudades.getAttribute("data-frm-value");
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
                            option.text = ciudades.datos[i].descripcion +' - '+ciudades.datos[i].provincia +' - '+ciudades.datos[i].departamento;
                            var datosProvinciaDepartamentoEM = ciudades.datos[i].provinciaId+";"
                                            +ciudades.datos[i].provincia+";"
                                            +ciudades.datos[i].departamentoId+";"
                                            +ciudades.datos[i].departamento+";";
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
                            option.text = ciudades.datos[i].descripcion +' - '+ciudades.datos[i].provincia +' - '+ciudades.datos[i].departamento;
                            var datosProvinciaDepartamentoSOL = ciudades.datos[i].provinciaId+";"
                                            +ciudades.datos[i].provincia+";"
                                            +ciudades.datos[i].departamentoId+";"
                                            +ciudades.datos[i].departamento+";";
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
    if(ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
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
    if(ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
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
        
        if(ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
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
            document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('change'));//
        } else {
            document.getElementById("SOL_PROVINCIA").value = "";
            document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
            document.getElementById("SOL_DEPARTAMENTO").value = "";
            document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
        }
}
  
    (function() {
        cargarEnteGestor();
    })();
    
    function cargarEnteGestor() {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'https://sipre.gestora.bo/spr-tram-rest/clasificador/entesGestoresSalud',
            success: function(response) {
                var selectEnteGestor  = document.getElementById("AS_ENTE_GESTOR");
                if (selectEnteGestor .options.length === 1) {
                    var datos = response.data;
                    for (var i = 0; i < datos.length; i++) {
                        var option = document.createElement("option");
                        option.value = datos[i].codigo;
                        option.text = datos[i].descripcion;
                        selectEnteGestor.appendChild(option);
                    }
                    var dataFrmValue = selectGestSalud.getAttribute("data-frm-value");
                    if (dataFrmValue !== undefined && dataFrmValue !== null) {
                        selectGestSalud.value = dataFrmValue;
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Manejar el error aquí
            }
        });
    }
     
    (function() {
        cargarParentesco();
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
                    var dataFrmValue1 = selectParentesco.getAttribute("data-frm-value");
                    if (dataFrmValue1 !== undefined && dataFrmValue1 !== null) {
                        selectParentesco.value = dataFrmValue1;
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
        console.log("LA MATRICULA >>>",matricula);
        document.getElementById("AS_MATRICULA_ASEGURADO").value = matricula;  
        document.getElementById("AS_MATRICULA_ASEGURADO").dispatchEvent(new Event('input'));
    }
    
    (function() {
       // setEnteGestorNombre();
    })();  
    function setEnteGestorNombre()
    {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'https://sgg.gestora.bo/otorgamiento-prestaciones-cpp/api/cppSeguimientoTramite/afiliadoHistorialLaboral?cua='+document.getElementById("AS_CUA").value,
            success: function(response) {
                if(response.codigo=="200")
                {
                    var selectElement = document.getElementById("EM_TIPO_AS");
                    var valorASeleccionar = response.data.tipoAportante; // Supongamos que esto devuelve "DEPENDIENTE" o "INDEPENDIENTE"
                    // Mapear los valores "DEPENDIENTE" e "INDEPENDIENTE" a sus índices correspondientes
                    var valorAMapear = valorASeleccionar === "DEPENDIENTE" ? "D" : "I";
                    if (valorAMapear == "D") {
                        var razon = response.data.razonSocial;
                        document.getElementById("EM_NOMBRE").value =razon; //response.data.razonSocial? null : "";                       
                        document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
                    } else {
                        var razon1 = response.data.razonSocial;
                        document.getElementById("EM_NOMBRE").value = razon1;//response.data.nombreCompleto ? null : "";                        
                        document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));                       
                    }
                    // Establecer el valor del select
                    selectElement.value = valorAMapear;
                    var dataFrmValueTP = selectElement.getAttribute("data-frm-value");                    
                    if (dataFrmValueTP !== undefined && dataFrmValueTP !== null) {
                        selectElement.value = dataFrmValueTP;                        
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Manejar el error aquí
            }
        });
    }

    function obligarPoder()
    {
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
  
    //************************RECALIFICACIÓN
    
    function llenaCVEAP(){
        var a = document.getElementById("AS_TIPO_EAP").value;
        if ( a.includes('CVEAP-A') ) {
            _show("AS_OBS_CVAP_1_idd");
            _show("AS_OBS_CVAP_2_idd");
            _show("AS_OBS_CVAP_3_idd");
            _show("AS_OBS_CVAP_4_idd");
            _show("AS_OBS_CVAP_5_idd");
            _show("SUBTITULO_8_idd");
            ///////////////////
            _hide("RECHAZO_JUB_idd");
        } else {
            _hide("AS_OBS_CVAP_1_idd");
            _hide("AS_OBS_CVAP_2_idd");
            _hide("AS_OBS_CVAP_3_idd");
            _hide("AS_OBS_CVAP_4_idd");
            _hide("AS_OBS_CVAP_5_idd");
            _hide("SUBTITULO_8_idd");
            ///////////////////
            _show("RECHAZO_JUB_idd"); // Mostrar el campo si el valor es "SI"
        }
        
        if ( a.includes('CVEAP-A8') ||  a.includes('CVEAP-B1') ) {
            _show("SUBTITLE_4_idd");
            _show("RMI_OPCION_idd");
            _show("RMI_REF_SALARIAL_idd");
            _show("RMI_SALARIO_MINIMO_idd");
        } else {
            _hide("SUBTITLE_4_idd");
            _hide("RMI_OPCION_idd");
            _hide("RMI_REF_SALARIAL_idd");
            _hide("RMI_SALARIO_MINIMO_idd");
        }
    }
  
    function retiroOpcion(){
        var a = document.getElementById("RMI_OPCION").value;
        if ( a == 'RM' ) {
            _enable("RMI_REF_OPCION");
            _show("RMI_REF_OPCION");
            _show("RMI_REF_OPCION_idd");


            
        } else {
              _hide("RMI_REF_OPCION");
              _hide("RMI_REF_OPCION_idd");

            var selectCiudadesEM = document.getElementById("RMI_REF_OPCION");
            var option = document.createElement("option");
            option.value = 1;
            option.text = '';
            selectCiudadesEM.appendChild(option);
            console.log('entro aqui RMI_REF_OPCION hdhdhdfhdfhdfh');
            _disable("RMI_REF_OPCION");
            document.getElementById("RMI_REF_OPCION").value = "";
            document.getElementById("RMI_REF_OPCION").dispatchEvent(new Event('input'));
            _setValue("RMI_REF_OPCION", "");
            document.getElementById("RMI_REF_OPCION").value =  "holasd";
            document.getElementById("RMI_REF_OPCION").dispatchEvent(new Event('input'));
        }
    }

    function setDate() {
       var fecha = new Date();
       var dia = addCero(fecha.getDate());
       var mes = addCero(fecha.getMonth() + 1);
       var anio = fecha.getFullYear();

       var fechaHoy = anio + '-' + mes + '-' + dia;

       var inputFechaInicio = document.getElementById("FECHA_INICIO_TRAMITE");
       inputFechaInicio.value = fechaHoy;
       inputFechaInicio.dispatchEvent(new Event('input')); 
    }

    function addCero(numero) {
       return numero < 10 ? '0' + numero : numero;
    }



    //**************************
    document.getElementById("AS_NUM_CUOTAS").addEventListener("change", function() {

    const  numero_cotas =     document.getElementById("AS_NUM_CUOTAS").value;
    var overlay = document.getElementById("overlay");
    overlay.style.display = 'flex';


                            var listaMensajes = `
                                 
                                   
                                               <strong> <h1  style="font-size: 5rem; font-weight: bold; margin: 0;" >${numero_cotas} </h1></strong> 
                                  
                                `;
                                overlay.style.display = 'none';
                                var modal = document.getElementById("modalGenerico");
                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                modalTitulo.textContent = "Esta Seguro del Nro de Cuotas";
                                modalMensaje.innerHTML = listaMensajes;
                                modal.style.display = "block";
                                overlay.style.display = 'none';





        var AS_NUM_CUOTAS = document.getElementById("AS_NUM_CUOTAS").value;
        var AS_VALOR_CUOTA = document.getElementById("AS_VALOR_CUOTA").value;
        var AS_SALDO_ACUMULADO = AS_NUM_CUOTAS * AS_VALOR_CUOTA;
        document.getElementById("AS_SALDO_ACUMULADO").value = AS_SALDO_ACUMULADO;
        document.getElementById("AS_SALDO_ACUMULADO").dispatchEvent(new Event('input'));  
        
    });

    document.getElementById("AS_VALOR_CUOTA").addEventListener("change", function() {
        var AS_NUM_CUOTAS = document.getElementById("AS_NUM_CUOTAS").value;
        var AS_VALOR_CUOTA = document.getElementById("AS_VALOR_CUOTA").value;
        var AS_SALDO_ACUMULADO = AS_NUM_CUOTAS * AS_VALOR_CUOTA;
        document.getElementById("AS_SALDO_ACUMULADO").value = AS_SALDO_ACUMULADO;
        document.getElementById("AS_SALDO_ACUMULADO").dispatchEvent(new Event('input'));  
    });

    document.getElementById("AS_SALDO_ACUMULADO").addEventListener("change", function() {
        var AS_NUM_CUOTAS = document.getElementById("AS_SALDO_ACUMULADO").value;
        var AS_VALOR_CUOTA = document.getElementById("AS_VALOR_CUOTA").value;
        var AS_SALDO_ACUMULADO =AS_VALOR_CUOTA /AS_NUM_CUOTAS ;
        document.getElementById("AS_NUM_CUOTAS").value = AS_SALDO_ACUMULADO;
        document.getElementById("AS_NUM_CUOTAS").dispatchEvent(new Event('input'));  
    });

    (function() {
        esconderadjunto();
        setDate();
        
        })(); 
    function esconderadjunto()
    {
        if (document.getElementById("AS_CERT_INSALUBRIDAD").value === "SI") {
            _show("CERT_INSALUBRIDAD_idd"); // Mostrar el campo si el valor es "SI"
        } else if (document.getElementById("AS_CERT_INSALUBRIDAD").value === "NO"){
            _hide("CERT_INSALUBRIDAD_idd"); // Ocultar el campo si el valor es distinto de "SI"
        }
    }

function camposCopiaSolicitante() {
    try {
        var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
        console.log('camposCopiaSolicitante ',valor);
        if(valor) {
            _disable("SOL_TIPO_DOCUMENTO");
            _disable("SOL_PARENTESCO");
            _disable("SOL_CI");
            _disable("SOL_COMPLEMENTO");
            _disable("SOL_NACIMIENTO");
            _disable("SOL_CUA");
            _disable("SOL_PRIMER_APELLIDO");
            _disable("SOL_SEGUNDO_APELLIDO");
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
            _disable("SOL_CIUDAD");
            _disable("SOL_PROVINCIA");
            _disable("SOL_DEPARTAMENTO");
            _disable("SOL_APELLIDO_CASADA");
            _hide("SOL_BUSCAR");
        }
    }catch(e){

    }
}

function datosTipoAsegurado() {
	var cua = document.getElementById("AS_CUA").value;

    console.log("EJECUTANDOME PARA EL EDIT");
	$.ajax({
        dataType: 'json',
        type: 'GET',
		url: 'https://sgg.gestora.bo/otorgamiento-prestaciones-cpp/api/cppSeguimientoTramite/afiliadoHistorialLaboral?cua=' + cua,
        success: function(response) {
			if(response.codigo == '200') {
				document.getElementById("EM_NOMBRE").value = '';
				document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));

				var primerNombre = document.getElementById("AS_PRIMER_NOMBRE") ? document.getElementById("AS_PRIMER_NOMBRE").value : '';
				var segundoNombre = document.getElementById("AS_SEGUNDO_NOMBRE") ? document.getElementById("AS_SEGUNDO_NOMBRE").value : '';
				var primerApellido = document.getElementById("AS_PRIMER_APELLIDO") ? document.getElementById("AS_PRIMER_APELLIDO").value : '';
				var segundoApellido = document.getElementById("AS_SEGUNDO_APELLIDO") ? document.getElementById("AS_SEGUNDO_APELLIDO").value : '';
				var apellidoCasada = document.getElementById("AS_APELLIDO_CASADA") ? document.getElementById("AS_APELLIDO_CASADA").value : '';
				var nombreCompleto = primerNombre + ' ' + segundoNombre + ' ' + primerApellido + ' ' + segundoApellido + ' ' + apellidoCasada;
				var tipo = document.getElementById("EM_TIPO_AS").value;
				if(tipo == 'D') {
					if(response.data.tipoAportante == 'DEPENDIENTE') {
						document.getElementById("EM_NOMBRE").value = response.data.razonSocial;
						document.getElementById("EM_NOMBRE").dispatchEvent(new Event('input'));
					}
				}
				if(tipo == 'I') {
					if(response.data.tipoAportante == 'INDEPENDIENTE') {
						if(response.data.razonSocial != null && response.data.razonSocial != '') {
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

function getDatosRechazoRMIN() {
    console.log('AS_TIPO_EAP =========> getDatosRechazoRMIN();');
}

function validacionCombo() {
    
    
    _disable("AS_VALOR_CUOTA");

    try {
        var tipo_combo = document.getElementById("AS_TIPO_EAP").value;
        console.log('AS_TIPO_EAPDSADASDASDSADA  =========> dsadasdasd', tipo_combo);
        if (tipo_combo == 'CVEAP-B8') {
            _show("CASO_RECHAZADO_idd");
            _show("CASO_RECHAZADO");
            _show("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = true;
            
            quitarRequeridResolucionRentista();
valorCuotaDesavilitar();
        }   if (tipo_combo == 'CVEAP-A9') {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = false ;
            
            quitarRequeridResolucionRentista();
            valorCuota();


        }  if (tipo_combo == 'CVEAP-A10') {
              _show("CASO_RECHAZADO_idd");
            _show("CASO_RECHAZADO");
            _show("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = false;
            
            quitarRequeridResolucionRentista();
            quitarRequeridResolucionRentista();
             valorCuotaDesavilitar();
        } if (tipo_combo == 'CVEAP-A11')  {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = true;
            
            quitarRequeridResolucionRentista();
             valorCuotaDesavilitar();
        }if (tipo_combo == 'CVEAP-A12')  {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = true;
            quitarRequeridResolucionRentista();
             valorCuotaDesavilitar();

        }if (tipo_combo == 'CVEAP-A13')  {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = false;
            quitarRequeridResolucionRentista();
             valorCuotaDesavilitar();

        }if (tipo_combo == 'CVEAP-A14')  {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = false;
            quitarRequeridResolucionRentista();
             valorCuotaDesavilitar();

        }if (tipo_combo == 'CVEAP-A15')  {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = true;
            volverRequeridoResolucionRentista();
           valorCuotaDesavilitar();
        }
        if (tipo_combo == 'CVEAP-A17')  {
            _hide("CASO_RECHAZADO_idd");
            _hide("CASO_RECHAZADO");
            _hide("REC_BUSCAR_RMIN");
            document.getElementById("AS_FECHA_FALLECIMIENTO").disabled = true;
            quitarRequeridResolucionRentista();
           valorCuotaDesavilitar();
        }

    } catch (e) {

    }
}

    function volverRequeridoResolucionRentista(){
        document.getElementById("RESOL_RENTISTA").setAttribute("required", "required");
        const nota_desistido = document.getElementById('RESOL_RENTISTA_idd');
        const label = nota_desistido.querySelector('label');
        label.style.display = "block";
        document.getElementById("CERT_RENTISTA_ID").setAttribute("required", "required");
        if (label && !label.textContent.includes(' (*)')) {
            label.textContent += ' (*)';
        }
    }


    
    function quitarRequeridResolucionRentista(){
        const nota_desistido = document.getElementById('RESOL_RENTISTA_idd');
        document.getElementById("RESOL_RENTISTA").removeAttribute("required");
        const label = nota_desistido.querySelector('label');
        if (label) {
            label.textContent = label.textContent.replace(' (*)', '');
        }  
        document.getElementById("CERT_RENTISTA_ID").removeAttribute("required");
    }

    function valorCuota(){
          $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'https://sgg.gestora.bo/otorgamiento-prestaciones-retiros/api/v1/reportesRetiro/getValorCuota',
            success: function(valor) {
                document.getElementById("AS_VALOR_CUOTA").value = valor.valorCuota;
                document.getElementById("AS_VALOR_CUOTA").dispatchEvent(new Event('input'));
                console.log('valor del estado v2 ',valor);
                _disable("AS_NUM_CUOTAS");
                _disable("AS_VALOR_CUOTA");
                _enable("AS_SALDO_ACUMULADO");
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function valorCuotaDesavilitar(){
        _enable("AS_NUM_CUOTAS");
        _enable("AS_VALOR_CUOTA");
        _enable("AS_SALDO_ACUMULADO");
            
    }