
  
  function mas() {
        'los derechos ambientes'
  }
  



  function esTercerGrado(rowIndex) {
    var b = document.getElementById("DH_PARENTESCO" + rowIndex).value;
    const palabras = b.split("-");
    const elemento = document.getElementById("DH_GRADO" + rowIndex);
  
    if (palabras[0] == "3") {
        elemento.style.display = "block";
        console.log("so");
    }
    else {
        elemento.style.display = "none";
    }
  }
function verDatosTutores(rowIndex) {
    
    var numeroDocumento = document.getElementById("DH_CI_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).value : '';
    var id_prsona_sip = document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value : '';
    console.log('numeroDocumento ',id_prsona_sip );
    console.log('id_prsona_sip ',id_prsona_sip );
    document.getElementById("id_persona_sip").value = id_prsona_sip;
    document.getElementById("numero_documento").value = numeroDocumento;
}

  function verDatosDh(rowIndex) {
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
            url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
            success: function (datares) {
                if (datares.codigoRespuesta == 0) {
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
                    document.getElementById("DH_DIRECCION" + rowIndex).value = datares.data.direccion;
                    document.getElementById("DH_DIRECCION" + rowIndex).dispatchEvent(new Event('input'));
  
                    if (datares.data.idGenero === 'M') {
                        document.getElementById("DH_GENERO" + rowIndex).value = 'M';
                        document.getElementById("DH_GENERO" + rowIndex).dispatchEvent(new Event('change'));
  
                    } else {
                        document.getElementById("DH_GENERO" + rowIndex).value = 'F';
                        document.getElementById("DH_GENERO" + rowIndex).dispatchEvent(new Event('change'));
                    }
                    document.getElementById("DH_FECHA_FALLECIDO" + rowIndex).value = datares.data.fechaDefuncion;
                    document.getElementById("DH_FECHA_FALLECIDO" + rowIndex).dispatchEvent(new Event('input'));
                    document.getElementById("DH_NRO_CELULAR" + rowIndex).value = datares.data.telefonoCelular;
                    document.getElementById("DH_NRO_CELULAR" + rowIndex).dispatchEvent(new Event('input'));
                    document.getElementById("DH_NUMERO" + rowIndex).value = datares.data.numero || "";
                    document.getElementById("DH_NUMERO" + rowIndex).dispatchEvent(new Event('input'));
                    document.getElementById("ES_DH_FALLECIDO" + rowIndex).value = datares.data.esDhFallecido || "";
                    document.getElementById("ES_DH_FALLECIDO" + rowIndex).dispatchEvent(new Event('input'));
                    document.getElementById("DH_CORREO" + rowIndex).value = datares.data.correoElectronico || "";
                    document.getElementById("DH_CORREO" + rowIndex).dispatchEvent(new Event('input'));
                } else {
                    if (datares.codigoRespuesta == 1) {
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
                        document.getElementById("DH_FECHA_FALLECIDO" + rowIndex).value = datares.data.fechaDefuncion;
                        document.getElementById("DH_FECHA_FALLECIDO" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DH_NRO_CELULAR" + rowIndex).value = datares.data.telefonoCelular;
                        document.getElementById("DH_NRO_CELULAR" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DH_NUMERO" + rowIndex).value = datares.data.numero || "";
                        document.getElementById("DH_NUMERO" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("ES_DH_FALLECIDO" + rowIndex).value = datares.data.esDhFallecido || "";
                        document.getElementById("ES_DH_FALLECIDO" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DH_CORREO" + rowIndex).value = datares.data.correoElectronico || "";
                        document.getElementById("DH_CORREO" + rowIndex).dispatchEvent(new Event('input'));
                    }
                }
            },
            error: function (err) {
                // Manejar errores de la solicitud AJAX
            }
        });
    } else {
        alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
    }
  }
function obtenerNombreDelMes(numeroMes) {
    const meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    return meses[numeroMes - 1] || 'Mes inválido'; 
}

function funcionPrueba(a) {
}
  function verDatos(a) {


    console.log('window.location.href', window.location.href);
        let fullUrl = window.location.href;
        const arrayUrl = fullUrl.split("/");
        const url_local = arrayUrl[0]+'//'+arrayUrl[2]; 
        console.log('arrayUrl',arrayUrl);
        console.log('url_local',url_local);

    var overlay = document.getElementById("overlay");
    overlay.style.display = 'flex';
    
    const dateParts = a.split('/');
    const year = dateParts[2];
    const month = dateParts[1].padStart(2, '0');
    const day = dateParts[0].padStart(2, '0');
    var fechaHoy = year + '-' + month + '-' + day;
    document.getElementById("FECHA_DE_REGISTRO").value = fechaHoy;
    document.getElementById("FECHA_DE_REGISTRO").dispatchEvent(new Event('input'));
    document.getElementById("SOL_DIFERENTE_AS").checked = false;

    var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
    var numeroDocumento = document.getElementById("AS_CI").value;
    var complemento = document.getElementById("AS_COMPLEMENTO").value;
    var fechaNacimiento = document.getElementById("AS_NACIMIENTO").value;
    var subClasificacion = document.getElementById("AS_TIPO_EAP").value;
    var pensNoCobradas = document.getElementById("PENS_NO_COBRADAS").value;
  
    if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {
        if (subClasificacion === ""){
            overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
              var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");
            modalTitulo.textContent = "Datos del Asegurado";
            modalMensaje.textContent = "Debe seleccionar Subclasificación - trámite de Masa Hereditaria , para continuar la búsqueda.";
            modal.style.display = "block";
            return;
        } else if (pensNoCobradas === "" && subClasificacion == 'CVEAP-B14'){
            overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
              var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");
            modalTitulo.textContent = "Datos del Asegurado";
            modalMensaje.textContent = "Debe seleccionar Pensiones no cobradas Asegurado/Derechohabiente Fallecido";
            modal.style.display = "block";
            return;
         } else {
            const tipo_busqueda = (subClasificacion === 'CVEAP-A11') ? 'NRF' : 'T';
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

                            console.log("CVEAP-A14 ===V3", subClasificacion);
                            console.log("DATARES > ", datares);
                            const date = new Date(datares.data.fechaNacimiento);
                            const today  = new Date(); 
                            let diffYears = today.getFullYear() - date.getFullYear();
                            if(subClasificacion === 'CVEAP-A10'){



                                overlay.style.display = 'none';
                                var requestData = {
                                    "cua": datares.data.cua
                                };
                                $.ajax({
                                    dataType: 'json',
                                    contentType: 'application/json',
                                    type: 'POST',
                                    data: JSON.stringify(requestData),
                                    url:url_local+'/api/buscarTramites',
                                    success: function (datares1) {
                                        console.log('datares.data.fechaDefuncion ======>>>' ,datares.data.fechaDefuncion );
                                        if (datares.data.fechaDefuncion != null){
                                            console.log('tiene fecha de fallecimiento ');
                                            const date = new Date(datares.data.fechaDefuncion);
                                            let Total_meses = calculateDetailedDateDifference(date);
                                            const meses = (Total_meses.years*12) + Total_meses.months ;
                                            if (meses >= 36 &&  meses < 156 ){
                                                const cantidad = datares1.data.length;
                                                console.log('cantidad==================>>>',cantidad);
                                                if(cantidad > 0){
                                                    console.log('registros de datos del cua ===============>>>>>',datares1.data);
                                                        $.ajax({
                                                        url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-cpp/api/cppSeguimientoTramite/matrizSaldosDiarios?cua='+datares.data.cua,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        contentType: 'application/json',
                                                        data: {},
                                                        success: function(datares3) {
                                                            console.log('datares3 ===saldoCuotasTotal>', datares3);

                                                            if(datares3.codigo == 201){

                                                                console.log('datos de EAP ===saldoCuotasTotal>', datares3.mensaje );



                                                             } else  {


                                                            }

                                                            


                                                            
                                                            const tam = datares3.data.length;
                                                            console.log('datos tam === >',tam);
                                                            for (var i = 0; i < tam; i++) {
                                                                if(datares3.data[i].idSubcuenta == '1'){
                                                                    if (datares3.data[i].saldoCuotasTotal > 0){
                                                                        console.log('datos de EAP ===saldoCuotasTotal>');
                                                                        console.log(datares3.data[i].saldoCuotasTotal);
                                                                            var listado = '';
                                                                            for (var j = 0; j < cantidad; j++) {
                                                                                var li =  `<li><b> ${datares1.data[j].cas_cod_id}</b>, Estado : <b> ${datares1.data[j].descripcion}</b></li>`
                                                                                listado = listado + li;
                                                                            }
                                                                                var listaMensajes = `    
                                                                                    <ul>
                                                                                      <li>  Nro de Cuotas <b>${datares3.data[i].valTotalTernaUnidades}</b>  </li>
                                                                                     <li> <b> Fecha de Fallecimiento : </b> ${datares.data.fechaDefuncion }</li>
                                                                                     <li> <b> Trámites registrados : </b></li>
                                                                                        ${listado}
                                                                                    </ul>
                                                                                `;
                                                                                var modal = document.getElementById("modalGenerico");
                                                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                var modalCerrarBtn = document.getElementById("modalGenerico-boton");

                                                                                modalTitulo.textContent = "INFORMACIÓN IMPORTANTE";
                                                                                modalMensaje.innerHTML = listaMensajes ;
                                                                                modalCerrarBtn.textContent = "Continuar";
                                                                                var icono = document.querySelector(".icono-advertencia");
                                                                                icono.src = "img/aviso_inportante.jpg"; 
                                                                                modal.style.display = "block";
                                                                                modalCerrarBtn.addEventListener('click', function() {
                                                                                    var requestDataValidarMasa = {
                                                                                        "cua": datares.data.cua
                                                                                    };
                                                                                    $.ajax({
                                                                                            dataType: 'json',
                                                                                            contentType: 'application/json',
                                                                                            type: 'POST',
                                                                                            data: JSON.stringify(requestDataValidarMasa),
                                                                                            url:url_local+'/api/validarLegalMasa',
                                                                                            success: function (dataLegal) {
                                                                                                console.log('datos legal=========>>',dataLegal);
                                                                                                if(dataLegal.data[0].r_val_codigo == 'VALIDO'){
                                                                                                    console.log('CASO_RECHAZADO  java script validarDatosMasaHereditaria');
                                                                                                    /// document.getElementById('app').__vue__.$refs.componenteHerencia.validarDatosMasaHereditaria();
                                                                                                    
                                                                                                    console.log(this.$refs);
                                                                                                    this.$refs.atenderCaso.validarDatosMasaHereditaria();
                                                                                                    this.$refs.atenderCaso.validarDatosMasaHereditaria();


                                                                                                    if (window.vueApp && typeof window.vueApp.validarDatosMasaHereditaria === 'function') {
                                                                                                        window.vueApp.validarDatosMasaHereditaria();
                                                                                                      } else {
                                                                                                       // window.vueApp.$refs.miComponente.validarDatosMasaHereditaria();
                                                                                                        window.vueApp.$refs.atenderCaso.validarDatosMasaHereditaria();
        
                                                                                                        console.log('no entre asdasdsada ');
                                                                                                        window.vueApp.validarDatosMasaHereditaria();
                                                                                                      }
                                                                                                    
                                                                                                    var listaMensajes = ` <h1>VALIDO</h1>`;
                                                                                                    overlay.style.display = 'none';
                                                                                                    var modal = document.getElementById("modalGenerico");
                                                                                                    var icono = document.querySelector(".icono-advertencia");
                                                                                                    icono.src = "img/aviso_inportante.jpg";
                                                                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                                    modalTitulo.textContent = "Datos del Asegurado";
                                                                                                    modalMensaje.innerHTML = listaMensajes 
                                                                                                    modal.style.display = "block";
                                                                                                    const cas_cod_id = document.getElementById("_CASO_NOMBRE").value;
                                                                                                    var requestData = {
                                                                                                        "cua": datares.data.cua,
                                                                                                        "cas_cod_id": cas_cod_id,
                                                                                                        "apellidoCasada": datares.data.apellidoCasada, 
                                                                                                        "apiEstado": datares.data.apiEstado,
                                                                                                        "complemento": datares.data.complemento, 
                                                                                                        "correoElectronico": datares.data.correoElectronico, 
                                                                                                        "cua": datares.data.cua,
                                                                                                        "direccion": datares.data.direccion, 
                                                                                                        "documentoIdentidad": datares.data.documentoIdentidad, 
                                                                                                        "estadoDefuncion": datares.data.estadoDefuncion, 
                                                                                                        "fechaDefuncion": datares.data.fechaDefuncion, 
                                                                                                        "fechaNacimiento": datares.data.fechaNacimiento, 
                                                                                                        "hbtId": datares.data.hbtId, 
                                                                                                        "idEstadoCivil": datares.data.idEstadoCivil, 
                                                                                                        "idGenero": datares.data.idGenero, 
                                                                                                        "idNacionalidad": datares.data.idNacionalidad, 
                                                                                                        "idPersonaSip": datares.data.idPersonaSip,
                                                                                                        "idSipAsegurados": datares.data.idSipAsegurados, 
                                                                                                        "nrf": datares.data.nrf, 
                                                                                                        "primerApellido": datares.data.primerApellido, 
                                                                                                        "primerNombre": datares.data.primerNombre, 
                                                                                                        "segundoApellido": datares.data.segundoApellido, 
                                                                                                        "segundoNombre": datares.data.segundoNombre, 
                                                                                                        "telefonoCelular": datares.data.telefonoCelular, 
                                                                                                        "telefonoFijo": datares.data.telefonoFijo, 
                                                                                                        "tipoIdentidad": datares.data.tipoIdentidad, 
                                                                                                    };

                                                                                                    $.ajax({
                                                                                                        dataType: 'json',
                                                                                                        contentType: 'application/json',
                                                                                                        type: 'POST',
                                                                                                        data: JSON.stringify(requestData),
                                                                                                        url:url_local+'/api/datosLegalMasaHerederos',
                                                                                                        success: function (dataHeredero) {

                                                                                                        },
                                                                                                        error: function (xhr, status, error) {
                                                                                                            console.error('Error:', error);
                                                                                                            // Manejar el error aquí
                                                                                                        }
                                                                                                    });
                                                                                                } else {
                                                                                                    var descripcion = dataLegal.data[0].r_descripcion;
                                                                                                    var orden  = dataLegal.data[0].r_orden;

                                                                                                    var listaMensajes = `<h1> El tramite de LEGAL esta en curso se encuentra en el nodo  ${orden} - ${descripcion} </h1>`;
                                                                                                    overlay.style.display = 'none';
                                                                                                    var modal = document.getElementById("subModalGenerico");
                                                                                                    var icono = document.querySelector(".sub-icono-advertencia");
                                                                                                    icono.src = "img/aviso_inportante.jpg";
                                                                                                    var modalTitulo = document.getElementById("sub-modalGenerico-titulo");
                                                                                                    var modalMensaje = document.getElementById("sub-modalGenerico-mensaje");
                                                                                                    modalTitulo.textContent = "TRAMITE INVALIDO";
                                                                                                    modalMensaje.innerHTML = listaMensajes 
                                                                                                    modal.style.display = "block";  
        
                                                                                                }
                                                                                            },
                                                                                            error: function (xhr, status, error) {
                                                                                                console.error('Error:', error);
                                                                                                // Manejar el error aquí
                                                                                            }
                                                                                        });






                                                                                        console.log("El usuario hizo clic en continuar dsd sd sd sds ds");
                                                                                   
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
                                                                                        document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                                                                        document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_CUA").value = datares.data.cua;
                                                                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                                                                        if (datares.data.idEstadoCivil === 'C') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                                                                                }  else if (datares.data.idEstadoCivil === 'V') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'VIUDO(A)';
                                                                                                }  else if (datares.data.idEstadoCivil === 'D') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'DIVORCIADO(A)';
                                                                                                }  else {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                                                                                }
                                                                                        document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                                                                                        if (datares.data.idGenero === 'M') {
                                                                                            document.getElementById("AS_GENERO").value = 'MASCULINO';
                                                                                        } else {
                                                                                            document.getElementById("AS_GENERO").value = 'FEMENINO';
                                                                                        }
                                                                                        document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                                                                        document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                                                                        document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                                                                
                                                                                        if (datares.data.complemento !== null) {
                                                                                            document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                                                                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                                                                        } else {
                                                                                            document.getElementById("AS_COMPLEMENTO").value = "";
                                                                                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                                                                        }
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
                                                                                        document.getElementById("REFERENCIA_MAHER").value = datares.data.referenciaMaher || "";
                                                                                        document.getElementById("REFERENCIA_MAHER").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("PENS_NO_COBRADAS").value = datares.data.pensNoCobradas || "";
                                                                                        document.getElementById("PENS_NO_COBRADAS").dispatchEvent(new Event('input'));
                                                                                        guardarDerechoHambientes();
                      
                        
                                                                                });

                                                                    }
                                                                }
                                                            }
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error('Error:', error);
                                                        }
                                                    });
                                                } else {
                                                    $.ajax({
                                                        url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-cpp/api/cppSeguimientoTramite/matrizSaldosDiarios?cua='+datares.data.cua,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        contentType: 'application/json',
                                                        data: {},
                                                        success: function(datares3) {
                                                            console.log('datares3 ===saldoCuotasTotal>2', datares3);
                                                            if(datares3.codigo == '201'){

                                                                const mensaje =  `<h2 style="text-align: center;">  ${datares3.mensaje} </h2>`;
                                                                const icono_imagen = "img/advertencia_1.jpg";
                                                                const cabecera = "ERRO DEL SERVICIO";
                                                                modalMensajeDinamico(mensaje,icono_imagen,cabecera);


                                                            } else {

                                                            }


                                                            const tam = datares3.data.length;
                                                            console.log('datos tam === >',tam);
                                                            for (var i = 0; i < tam; i++) {
                                                                if(datares3.data[i].idSubcuenta == '1'){
                                                                    if (datares3.data[i].saldoCuotasTotal> 0){
                                                                        console.log('datos de EAP ===saldoCuotasTotal>');
                                                                        console.log(datares3.data[i].saldoCuotasTotal);

                                                                        var listaMensajes = `
                                                                                    <h1>
                                                                                        <ul>
                                                                                            <li> <b> Fecha de Fallecimiento : </b> ${datares.data.fechaDefuncion }</li>
                                                                                            <li> <b>   ${meses} </b>Meses de fallecimiento a la  fecha </li>
                                                                                            <li>  Nro de Cuotas <b>${datares3.data[i].valTotalTernaUnidades}</b>  </li>
                                                                                            <li> Trámite  Válido </li>
                                                                                        </ul>
                                                                                        <b>En virtud al D.S 822/2011 en su Artículo Nº 38 (Exigibilidad de las Prestaciones), parágrafo IV </b>
                                                                                      </h1>`;
                                                                        var modal = document.getElementById("modalGenerico");
                                                                        var icono = document.querySelector(".icono-advertencia");
                                                                        icono.src = "img/aviso_inportante.jpg";
                                                                           var modalCerrarBtn = document.getElementById("modalGenerico-boton");
                                                                        
                                                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                        modalCerrarBtn.textContent = "Continuar";
                                                                        modalTitulo.textContent = "INFORMACIÓN IMPORTANTE";
                                                                        modalMensaje.innerHTML = listaMensajes ;
                                                                        modal.style.display = "block";


                                                                        modalCerrarBtn.addEventListener('click', function() {


                                                                            var requestDataValidarMasa = {
                                                                                "cua": datares.data.cua
                                                                            };
                                                                            $.ajax({
                                                                                    dataType: 'json',
                                                                                    contentType: 'application/json',
                                                                                    type: 'POST',
                                                                                    data: JSON.stringify(requestDataValidarMasa),
                                                                                    url:url_local+'/api/validarLegalMasa',
                                                                                    success: function (dataLegal) {
                                                                                        console.log('datos legal=========>>',dataLegal);
                                                                                        if(dataLegal.data[0].r_val_codigo == 'VALIDO'){ 

                                                                                            console.log('CASO_RECHAZADO  java script validarDatosMasaHereditaria');
                                                                                            /// document.getElementById('app').__vue__.$refs.componenteHerencia.validarDatosMasaHereditaria();
                                                                                            var listaMensajes = ` <h1>VALIDO</h1>`;
                                                                                            overlay.style.display = 'none';
                                                                                            var modal = document.getElementById("modalGenerico");
                                                                                            var icono = document.querySelector(".icono-advertencia");
                                                                                            icono.src = "img/aviso_inportante.jpg";
                                                                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                            modalTitulo.textContent = "Datos del Asegurado";
                                                                                            modalMensaje.innerHTML = listaMensajes 
                                                                                            modal.style.display = "block";
                                                                                            const cas_cod_id = document.getElementById("_CASO_NOMBRE").value;
                                                                                            var requestData = {
                                                                                                "cua": datares.data.cua,
                                                                                                "cas_cod_id": cas_cod_id,
                                                                                                "apellidoCasada": datares.data.apellidoCasada, 
                                                                                                "apiEstado": datares.data.apiEstado,
                                                                                                "complemento": datares.data.complemento, 
                                                                                                "correoElectronico": datares.data.correoElectronico, 
                                                                                                "cua": datares.data.cua,
                                                                                                "direccion": datares.data.direccion, 
                                                                                                "documentoIdentidad": datares.data.documentoIdentidad, 
                                                                                                "estadoDefuncion": datares.data.estadoDefuncion, 
                                                                                                "fechaDefuncion": datares.data.fechaDefuncion, 
                                                                                                "fechaNacimiento": datares.data.fechaNacimiento, 
                                                                                                "hbtId": datares.data.hbtId, 
                                                                                                "idEstadoCivil": datares.data.idEstadoCivil, 
                                                                                                "idGenero": datares.data.idGenero, 
                                                                                                "idNacionalidad": datares.data.idNacionalidad, 
                                                                                                "idPersonaSip": datares.data.idPersonaSip,
                                                                                                "idSipAsegurados": datares.data.idSipAsegurados, 
                                                                                                "nrf": datares.data.nrf, 
                                                                                                "primerApellido": datares.data.primerApellido, 
                                                                                                "primerNombre": datares.data.primerNombre, 
                                                                                                "segundoApellido": datares.data.segundoApellido, 
                                                                                                "segundoNombre": datares.data.segundoNombre, 
                                                                                                "telefonoCelular": datares.data.telefonoCelular, 
                                                                                                "telefonoFijo": datares.data.telefonoFijo, 
                                                                                                "tipoIdentidad": datares.data.tipoIdentidad, 
                                                                                            };

                                                                                            $.ajax({
                                                                                                dataType: 'json',
                                                                                                contentType: 'application/json',
                                                                                                type: 'POST',
                                                                                                data: JSON.stringify(requestData),
                                                                                                url:url_local+'/api/datosLegalMasaHerederos',
                                                                                                success: function (dataHeredero) {



                                                                                                },
                                                                                                error: function (xhr, status, error) {
                                                                                                    console.error('Error:', error);
                                                                                                    // Manejar el error aquí
                                                                                                    ///numero_documento in (1297980 , 804627)
                                                                                                }
                                                                                            });
                                                                                        } else {



                                                                                            var listaMensajes = `<h1> No cuenta con un tramite de LEGAL iniciado </h1>`;
                                                                                            overlay.style.display = 'none';
                                                                                            var modal = document.getElementById("subModalGenerico");
                                                                                            var icono = document.querySelector(".sub-icono-advertencia");
                                                                                            icono.src = "img/aviso_inportante.jpg";
                                                                                            var modalTitulo = document.getElementById("sub-modalGenerico-titulo");
                                                                                            var modalMensaje = document.getElementById("sub-modalGenerico-mensaje");
                                                                                            modalTitulo.textContent = "TRAMITE INVALIDO";
                                                                                            modalMensaje.innerHTML = listaMensajes 
                                                                                            modal.style.display = "block"; 

                                                                                            if (window.vueApp && typeof window.vueApp.validarDatosMasaHereditaria === 'function') {
                                                                                                window.vueApp.validarDatosMasaHereditaria();
                                                                                              } else {
                                                                                               // window.vueApp.$refs.miComponente.validarDatosMasaHereditaria();
                                                                                                window.vueApp.$refs.atenderCaso.validarDatosMasaHereditaria();

                                                                                                console.log('no entre asdasdsada ');
                                                                                                window.vueApp.validarDatosMasaHereditaria();
                                                                                              }

                                                                                              console.log('fin ======================');


                                                                                           
                                                                                        }
                                                                                    },
                                                                                    error: function (xhr, status, error) {
                                                                                        console.error('Error:', error);
                                                                                        // Manejar el error aquí
                                                                                    }
                                                                                });
                                                                                formularioHTML(datares);
                                                                                    console.log("El usuario hizo clic en continuar dsd sd sd sds ds");
                        
                                                                                });

                                                                    }
                                                                }
                                                            }
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error('Error:', error);
                                                        }
                                                    });




                                                }

                                            } else {

                                                if(meses>156){
                                                    var listaMensajes = ` <h1><b> El tiempo transcurrido desde la fecha de fallecimiento no es válido, es mayor a 13 años.
                                                            En virtud al D.S 822/2011 en su Artículo Nº 180 (Prescripción), modificado por el D.S 1888/2014 Articulo Nº 2, parágrafo XVIII 

                                                    </h1>
                                                    `;
                                                    overlay.style.display = 'none';
                                                    var modal = document.getElementById("modalGenerico");
                                                    var icono = document.querySelector(".icono-advertencia");
                                                    icono.src = "img/aviso_inportante.jpg";
                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                    modalTitulo.textContent = "Datos del Asegurado";
                                                    modalMensaje.innerHTML = listaMensajes 
                                                    modal.style.display = "block";

                                                 }else{
                                                    var listaMensajes = `<h1><b>El tiempo transcurrido a partir de la fecha fallecimiento, es menor a los 36 meses, corresponde iniciar una solicitud de Pensión por Muerte o Pago de la CC. 
                                                                            En virtud al D.S 822/2011 en su Artículo Nº 38 (Exigibilidad de las Prestaciones), parágrafo III 
 </h1>
                                                `;
                                                overlay.style.display = 'none';
                                                var modal = document.getElementById("modalGenerico");
                                                 var icono = document.querySelector(".icono-advertencia");
                                                icono.src = "img/aviso_inportante.jpg";
                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                modalTitulo.textContent = "Datos del Asegurado";
                                                modalMensaje.innerHTML = listaMensajes 
                                                modal.style.display = "block";

                                                }



                                       

                                            }

                                        } else { // tiene fecha de fallecimiento 
                                    

                                                    var listaMensajes = `
                                                                   <p>No se cuenta con el registro de la fecha de fallecimiento, favor registre la misma de forma previa, a través de registro de Novedades </p>
                                                                `;
                                                    var modal = document.getElementById("modalGenerico");
                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                    var icono = document.querySelector(".icono-advertencia");
                                                    icono.src = "img/advertencia_1.jpg"; 
                                                    modalTitulo.textContent = "DATOS DEL ASEGURADO";
                                                    modalMensaje.innerHTML = listaMensajes ;
                                                    modal.style.display = "block";
                                                }
                                        
                                
                                    },
                                    error: function (xhr, status, error) {
                                        console.error('Error:', error);
                                        // Manejar el error aquí
                                    }
                                });
                            } else if (subClasificacion === 'CVEAP-A11'){

                                    console.log("Datares=========>>>", datares);
                                    const cua = datares.data.cua;
                                    $.ajax({
                                        dataType: 'json',
                                        type: 'GET',
                                        url: 'https://sgg.test.gestora.bo/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c='+cua+'&user=fernando.flores@gestora.bo',
                                        success: function(response) {
                                            console.log('response.dasdasdasdasdsadsaddasddsadasdasdasdas ======>>>>>', response.data);
                                            data = response.data;
                                        if (data.CCG) {
                                               

                                                if(diffYears >= 58 ){

                                                    console.log('es mayor a 58 =======>>>'); 
                                                    var listaMensajes = `
                                                        <ul>
                                                            <li>Fecha de Fallecimiento :<b>  ${datares.data.fechaDefuncion} </b>  </li>
                                                            <li>Fecha de carga de la CCG :<b>  ${data.CCG.fechaCargaOfechaEmision}</b> </li>
                                                            <li>Monto de la CCG:  <b> ${data.CCG.monto} Bs </b> </li>
                                                        </ul>
                                                    `;
                                                    overlay.style.display = 'none';
                                                    var modal = document.getElementById("modalGenerico");
                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                    var icono = document.querySelector(".icono-advertencia");
                                                    var modalCerrarBtn = document.getElementById("modalGenerico-boton");
                                                    icono.src = "img/aviso_inportante.jpg"; 
                                                    modalCerrarBtn.textContent = "Continuar";
                                                    modalTitulo.textContent = "Datos del Asegurado";
                                                    modalMensaje.innerHTML = listaMensajes;
                                                    modal.style.display = "block";
                                                    overlay.style.display = 'none';






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
                                                                                        document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                                                                        document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_CUA").value = datares.data.cua;
                                                                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                                                                        if (datares.data.idEstadoCivil === 'C') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                                                                                }  else if (datares.data.idEstadoCivil === 'V') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'VIUDO(A)';
                                                                                                }  else if (datares.data.idEstadoCivil === 'D') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'DIVORCIADO(A)';
                                                                                                }  else {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                                                                                }

                                                                                                    document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                                                                                        if (datares.data.idGenero === 'M') {
                                                                                            document.getElementById("AS_GENERO").value = 'MASCULINO';
                                                                
                                                                                        } else {
                                                                                            document.getElementById("AS_GENERO").value = 'FEMENINO';
                                                                                        }
                                                                                        document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                                                                        document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                                                                        document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                                                                
                                                                                        if (datares.data.complemento !== null) {
                                                                                            document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                                                                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                                                                        } else {
                                                                                            document.getElementById("AS_COMPLEMENTO").value = "";
                                                                                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                                                                        }
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
                                                                                        document.getElementById("REFERENCIA_MAHER").value = datares.data.referenciaMaher || "";
                                                                                        document.getElementById("REFERENCIA_MAHER").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("PENS_NO_COBRADAS").value = datares.data.pensNoCobradas || "";
                                                                                        document.getElementById("PENS_NO_COBRADAS").dispatchEvent(new Event('input'));
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
                                                    var icono = document.querySelector(".icono-advertencia");
                                                    icono.src = "img/aviso_inportante.jpg";
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
                                                var icono = document.querySelector(".icono-advertencia");
                                                icono.src = "img/aviso_inportante.jpg";
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




                            } else if (subClasificacion === 'CVEAP-B14') {
                                console.log("CVEAP-A14-entro aquiiiiiiiiiiiiiiiiiiiiiiiii -----------------------  CVEAP-B14");
                                console.log("CVEAP-A14", subClasificacion);
                                var requestData = {
                                    "cas_cua": datares.data.cua
                                };
                                $.ajax({
                                    dataType: 'json',
                                    contentType: 'application/json',
                                    type: 'POST',
                                    data: JSON.stringify(requestData),
                                    url:url_local+'/api/boletasPendientesCobro',
                                    success: function (dataBoletos ) {
                                        

                                            console.log('datares1', dataBoletos); 
                                            const cantidad = dataBoletos.data.length;

                                            console.log('cantidad ',cantidad);
                                            if(cantidad > 0){
                                                 var listado = '';
                                                for (var j = 0; j < cantidad; j++) {
                                                    const mes_carga = dataBoletos.data[j].periodo;
                                                    let result = mes_carga.substring(4);
                                                     const mes = obtenerNombreDelMes(result);
                                                    var li =  `<li><b>estado: </b> ${dataBoletos.data[j].descripcion} <b> periodo: </b> ${mes} <br><b> liquido_pagable: </b> ${dataBoletos.data[j].liquido_pagable}   </li>`
                                                        listado = listado + li;
                                                    }

                                                            var listaMensajes = `    
                                                                <ul>
                                                        
                                                                  ${listado}
                                                                    
                                                                </ul>
                                                            `;
                                                            var modal = document.getElementById("modalGenerico");
                                                            var icono = document.querySelector(".icono-advertencia");
                                                            icono.src = "img/aviso_inportante.jpg";
                                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                            var modalCerrarBtn = document.getElementById("modalGenerico-boton");

                                                            modalTitulo.textContent = "LISTA DE PENDIENTES DE PAGO";
                                                            modalMensaje.innerHTML = listaMensajes ;
                                                            modalCerrarBtn.textContent = "Continuar";
                                                            modal.style.display = "block";
                                                            overlay.style.display = 'none';

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
                                                                                        document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                                                                                        document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_CUA").value = datares.data.cua;
                                                                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                                                                        if (datares.data.idEstadoCivil === 'C') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                                                                                                }  else if (datares.data.idEstadoCivil === 'V') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'VIUDO(A)';
                                                                                                }  else if (datares.data.idEstadoCivil === 'D') {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'DIVORCIADO(A)';
                                                                                                }  else {
                                                                                                    document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                                                                                                }

                                                                                                    document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                                                                                        if (datares.data.idGenero === 'M') {
                                                                                            document.getElementById("AS_GENERO").value = 'MASCULINO';
                                                                
                                                                                        } else {
                                                                                            document.getElementById("AS_GENERO").value = 'FEMENINO';
                                                                                        }
                                                                                        document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                                                                                        document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                                                                                        document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                                                                
                                                                                        if (datares.data.complemento !== null) {
                                                                                            document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                                                                                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                                                                        } else {
                                                                                            document.getElementById("AS_COMPLEMENTO").value = "";
                                                                                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                                                                                        }
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
                                                                                        document.getElementById("REFERENCIA_MAHER").value = datares.data.referenciaMaher || "";
                                                                                        document.getElementById("REFERENCIA_MAHER").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("PENS_NO_COBRADAS").value = datares.data.pensNoCobradas || "";
                                                                                        document.getElementById("PENS_NO_COBRADAS").dispatchEvent(new Event('input'));
                                                                                


                                            } else {
                                                
                                                            var listaMensajes = `    
                                                          No cuenta con pagos pendientes
                                                            `;
                                                  var modal = document.getElementById("modalGenerico");
                                                            var icono = document.querySelector(".icono-advertencia");
                                                            icono.src = "img/advertencia_1.jpg";
                                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                            var modalCerrarBtn = document.getElementById("modalGenerico-boton");

                                                            modalTitulo.textContent = "NO VALIDO";
                                                            modalMensaje.innerHTML = listaMensajes ;
                                                            modalCerrarBtn.textContent = "Continuar";
                                                            modal.style.display = "block";
                                                            overlay.style.display = 'none';

                                            }
                                               

                                    },
                                    error: function (xhr, status, error) {
                                        console.error('Error:', error);
                                    }
                                });


                                // $.ajax({
                                //     dataType: 'json',
                                //     contentType: 'application/json',
                                //     type: 'POST',
                                //     data: JSON.stringify(requestData),
                                //     url:url_local+'/api/boletasPago',
                                //     success: function (dataBoletos ) {

                                //             console.log('datares1', dataBoletos); 
                                //             const cantidad = dataBoletos.data.length;
                                //                 var listado = '';
                                //                 for (var j = 0; j < cantidad; j++) {
                                //                     const mes_carga = dataBoletos.data[j].mesCarga;
                                //                     console.log('boletas de PAGO ',mes_carga);
                                //                     const mes = obtenerNombreDelMes(mes_carga);

                                //                     var li =  `<li><b>estado: </b> ${dataBoletos.data[j].estado} <b> mesCarga :</b> ${mes} <br><b> cuaAsegurado: </b> ${dataBoletos.data[j].cuaAsegurado}   </li>`
                                //                         listado = listado + li;
                                //                     }



                                //                             var listaMensajes = `    
                                //                                 <ul>
                                                        
                                //                                   ${listado}
                                                                    
                                //                                 </ul>
                                //                             `;
                                //                             var modal = document.getElementById("modalGenerico");
                                //                             var icono = document.querySelector(".icono-advertencia");
                                //                             icono.src = "img/aviso_inportante.jpg";
                                //                             var modalTitulo = document.getElementById("modalGenerico-titulo");
                                //                             var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                //                             var modalCerrarBtn = document.getElementById("modalGenerico-boton");

                                //                             modalTitulo.textContent = "LISTA DE PENSIONES";
                                //                             modalMensaje.innerHTML = listaMensajes ;
                                //                             modalCerrarBtn.textContent = "Continuar";
                                //                             modal.style.display = "block";
                                //                             overlay.style.display = 'none';
                                                                                


                                //     },
                                //     error: function (xhr, status, error) {
                                //         console.error('Error:', error);
                                //     }
                                // });

                                // $.ajax({
                                //     dataType: 'json',
                                //     contentType: 'application/json',
                                //     type: 'POST',
                                //     data: JSON.stringify(requestData),
                                //     url:url_local+'/api/validarBoleta',
                                //     success: function (dataValidarBoletos ) {

                                //         console.log('datares1', dataValidarBoletos.data.estado); 

                                //               var listaMensajes = `    
                                //                                 <ul>
                                //                                     <li>  cuaAsegurado:<b>${dataValidarBoletos.data.cuaAsegurado}</b></li>
                                //                                     <li>  estado:<b>${dataValidarBoletos.data.estado}</b></li>
                                //                                     <li>  fechaNacimientoTitular:<b>${dataValidarBoletos.data.fechaNacimientoTitular}</b></li>
                                //                                     <li>  numeroIdentificacionTitular:<b>${dataValidarBoletos.data.numeroIdentificacionTitular}</b></li>
                                //                                     <li>  periodo:<b>${dataValidarBoletos.data.periodo}</b></li>
                                //                                     <li>  tipoIdentificacionTitular:<b>${dataValidarBoletos.data.tipoIdentificacionTitular}</b></li>
                                //                                 </ul>
                                //                             `;
                                //                             var modal = document.getElementById("modalGenerico");
                                //                             var modalTitulo = document.getElementById("modalGenerico-titulo");
                                //                             var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                //                             var modalCerrarBtn = document.getElementById("modalGenerico-boton");

                                //                             modalTitulo.textContent = "BOLETA";
                                //                             modalMensaje.innerHTML = listaMensajes ;
                                //                             modalCerrarBtn.textContent = "Continuar";
                                //                             modal.style.display = "block";
                                //                             overlay.style.display = 'none';

                                //     },
                                //     error: function (xhr, status, error) {
                                //         console.error('Error:', error);
                                //     }
                                // });

                            }

                        

                 
                        // calcularDiferenciaYAlerta(datares.data.fechaDefuncion);
                    }
  
                    else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {
  
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
                                 var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
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
                        if(datares.codigoRespuesta === "5") {
                            overlay.style.display = 'none';
                            var modal = document.getElementById("modalGenerico");
                            var icono = document.querySelector(".icono-advertencia");
                            icono.src = "img/aviso_inportante.jpg";
                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                            modalTitulo.textContent = "Datos del Asegurado";
                            modalMensaje.textContent = datares.mensaje;
                            modal.style.display = "block";
                            }
                            limpiarFormulario();
                        }
                        console.log(datares);
                },
                error: function (err) {
                    // Manejar errores de la solicitud AJAX
                }
            });
        }
    } else {
        //alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
        overlay.style.display = 'none';
        var modal = document.getElementById("modalGenerico");
        var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
        var modalTitulo = document.getElementById("modalGenerico-titulo");
        var modalMensaje = document.getElementById("modalGenerico-mensaje");
        modalTitulo.textContent = "Datos del Asegurado";
        modalMensaje.textContent = "Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.";
        modal.style.display = "block";
  }
  }
  
  function guardarDerechoHambientes(){
        console.log('guardar derechos hambientes function ');

  }

  function modalMensajeDinamico(mensaje,icono_imagen, cabecera) {
    console.log ('icono de la inafgenge',icono_imagen);
    console.log ('mensaje',mensaje);
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
function formularioHTML(datares) {
    esSolicitanteCobrador();
    console.log('datares',datares);

                    
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
                        } else {
                            document.getElementById("AS_GENERO").value = 'FEMENINO';
                        }
                        document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                        document.getElementById("AS_ESTADO_CIVIL").value = datares.data.idEstadoCivil;
                        if (datares.data.idEstadoCivil === 'C') {
                            document.getElementById("AS_ESTADO_CIVIL").value = 'CASADO(A)';
                        } else {
                            document.getElementById("AS_ESTADO_CIVIL").value = 'SOLTERO(A)';
                        }
                        document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
                        document.getElementById("AS_API_ESTADO").value = datares.data.apiEstado || "";
                        document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
                        document.getElementById("AS_IDPERSONA").value = datares.data.idPersonaSip || "";
                        document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
                        document.getElementById("AS_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                        document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                        document.getElementById("AS_FECHA_FALLECIMIENTO").value = datares.data.fechaDefuncion || "";
                        document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));

                        if (datares.data.complemento !== null) {
                            document.getElementById("AS_COMPLEMENTO").value = datares.data.complemento || "";
                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
                        } else {
                            document.getElementById("AS_COMPLEMENTO").value = "";
                            document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
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
            url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
            success: function (datares) {
                if (datares.codigoRespuesta == 0) {
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
                    document.getElementById("VALIDAR_PODER").value = datares.data.vaidarPoder || "";
                    document.getElementById("VALIDAR_PODER").dispatchEvent(new Event('input'));
                    document.getElementById("FECHA_REVISION").value = datares.data.fechaRevision || "";
                    document.getElementById("FECHA_REVISION").dispatchEvent(new Event('input'));
  
                    if (datares.data.apiEstado === 'FALLECIDO') {
                        alert('!ALERTA!. El solicitante es registrado como FALLECIDO.');
                        limpiarFormularioSol();
                        return; // Salir de la función para evitar más procesamiento
                    } else { }
                }
                else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {
  
                    if (complemento === "") {
                        document.getElementById("SOL_NACIMIENTO").disabled = false;
                        if (fechaNacimiento !== "") {
                            document.getElementById("SOL_NACIMIENTO").value = fechaNacimiento
                            document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
                        }
                        else {
                            alert("Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.");
                            return;
                        }
                    } else {
                        document.getElementById("SOL_COMPLEMENTO").value = complemento;
                    }
                    // calcularDiferenciaYAlerta();
                }
                
                else {
                    limpiarFormularioSol();
                }
                console.log(datares);
            },
            error: function (err) {
                // Manejar errores de la solicitud AJAX
            }
        });
    } else {
        alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
    }
  }
  
  function limpiarFormulario() {
    // Limpiar todos los campos del formulario según sea necesario
    document.getElementById("AS_PRIMER_NOMBRE").value = null;
    document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
    document.getElementById("AS_SEGUNDO_NOMBRE").value = "";
    document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
    document.getElementById("AS_PRIMER_APELLIDO").value = "";
    document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
    document.getElementById("AS_SEGUNDO_APELLIDO").value = "";
    document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
    document.getElementById("AS_APELLIDO_CASADA").value = "";
    document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
    document.getElementById("AS_FECHA_FALLECIMIENTO").value = null;
    document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
    document.getElementById("AS_CUA").value = "";
    document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
    document.getElementById("AS_GENERO").value = "";
    document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
    document.getElementById("AS_ESTADO_CIVIL").value = "";
    document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
    document.getElementById("AS_IDPERSONA").value = "";
    document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
    document.getElementById("AS_API_ESTADO").value = "";
    document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
    document.getElementById("AS_COMPLEMENTO").value = "";
    document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
    document.getElementById("AS_NACIMIENTO").value = "";
    document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_1").value = "";
    document.getElementById("AS_OBS_CVAP_1").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_2").value = "";
    document.getElementById("AS_OBS_CVAP_2").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_3").value = "";
    document.getElementById("AS_OBS_CVAP_3").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_4").value = "";
    document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));
    document.getElementById("AS_OBS_CVAP_5").value = "";
    document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));
    document.getElementById("REFERENCIA_MAHER").value = "";
    document.getElementById("REFERENCIA_MAHER").dispatchEvent(new Event('input'));
   
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
  
  function agregarEventListener(idInput, idElemento, limpiarFuncion) {
    var elemento = document.getElementById(idInput);
    if (elemento) {
        elemento.addEventListener('input', function () {
            document.getElementById(idElemento).disabled = true;
            limpiarFuncion();
            //  mostrarPensiones();
        });
    } else {
        // console.error("Elemento " + idInput + " no encontrado en el DOM.");
    }
  }
  
  // agregarEventListener("AS_CI", "AS_NACIMIENTO", limpiarFormulario);
 //  agregarEventListener("SOL_CI", "SOL_NACIMIENTO", limpiarFormularioSol);
  
  (function () {
    getCiudades();
  })();
  
  function getCiudades() {
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: 'https://oficinavirtualservicios.gestora.bo/api/General/Ciudad',
        success: function (ciudades) {
            var selectCiudadesSOL = document.getElementById("SOL_CIUDAD");
            if (selectCiudadesSOL.options.length === 1) {
                var cantidadCiudadesSOL = ciudades.datos.length;
                for (var i = 0; i < cantidadCiudadesSOL; i++) {
                    var option = document.createElement("option");
                    option.value = ciudades.datos[i].codigoGeograficoId;
                   option.text = ciudades.datos[i].descripcion +' - '+ciudades.datos[i].provincia +' - '+ciudades.datos[i].departamento;
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
        error: function (xhr, status, error) {
            console.error('Error:', error);
            // Manejar el error aquí
        }
    });
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
        document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('change'));
    } else {
        document.getElementById("SOL_PROVINCIA").value = "";
        document.getElementById("SOL_PROVINCIA").dispatchEvent(new Event('input'));
        document.getElementById("SOL_DEPARTAMENTO").value = "";
        document.getElementById("SOL_DEPARTAMENTO").dispatchEvent(new Event('input'));
    }
  }
  
  (function () {
    cargarParentesco();
  })();
  
  function cargarParentesco() {
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: 'https://sipre.gestora.bo/spr-tram-rest/clasificador/tiposParentesco',
        success: function (response) {
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
        error: function (xhr, status, error) {
            console.error('Error:', error);
            // Manejar el error aquí
        }
    });
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


  function mostrarPensiones() {
 cargarParentesco();
    getCiudades();
        console.log("funcion mostrarPensiones >>> ");
        const pensNoCobradas = document.getElementById("PENS_NO_COBRADAS");
        const pensNoCobradasIdd = document.getElementById("PENS_NO_COBRADAS_idd");
        const asTipoEapValue = document.getElementById("AS_TIPO_EAP").value;
        const obsCvapIds = [
            "AS_OBS_CVAP_1_idd",
            "AS_OBS_CVAP_2_idd",
            "AS_OBS_CVAP_3_idd",
            "AS_OBS_CVAP_4_idd",
            "AS_OBS_CVAP_5_idd",
            "SUBTITULO_6_idd"
        ];

        if (asTipoEapValue === "CVEAP-B14") {
            console.log("funcion mostrarPensiones >>> CVEAP-B14");
            pensNoCobradas.style.display = "block";
            pensNoCobradasIdd.style.display = "block";
            toggleDisplay(obsCvapIds, "none");
        } else {
            console.log("funcion mostrarPensiones x else>>> ");
            pensNoCobradas.style.display = "none";
            pensNoCobradasIdd.style.display = "none";
            toggleDisplay(obsCvapIds, "block");
        }
    }

    function toggleDisplay(ids, displayStyle) {
        ids.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.style.display = displayStyle;
            }
        });
    }

  
  function esDhFallecido(rowIndex) {
    var b = document.getElementById("ES_DH_FALLECIDO" + rowIndex).value;
    const palabras = b.split("-");
    const elemento = document.getElementById("DH_FECHA_FALLECIDO" + rowIndex);
    if (palabras[0] == "1") {
        elemento.style.display = "block"; // Ocultar el elemento
    }
    else {
        elemento.style.display = "none"; // Ocultar el elemento
    }
  }
 
  
  function calcularDiferenciaYAlerta(fechaFallecimiento = null) {
    fechaFallecimiento = fechaFallecimiento || document.getElementById("AS_FECHA_FALLECIMIENTO").value;
    console.log("Fecha de fallecimiento en la funcion >> ", fechaFallecimiento);
  
    let fechaInicioTramite = new Date(document.getElementById("FECHA_DE_REGISTRO").value);
    let fechaFallecimiento1 = new Date(fechaFallecimiento);
  
    let diferenciaAnios = fechaInicioTramite.getFullYear() - fechaFallecimiento1.getFullYear();
    let diferenciaMeses = fechaInicioTramite.getMonth() - fechaFallecimiento1.getMonth();
    let diferenciaDias = fechaInicioTramite.getDate() - fechaFallecimiento1.getDate();
  
    if (diferenciaDias < 0) {
        diferenciaMeses--;
    }
  
    if (diferenciaMeses < 0) {
        diferenciaAnios--;
        diferenciaMeses += 12;
    }
  
    let totalMeses = Math.abs(diferenciaAnios * 12 + diferenciaMeses);
    console.log("totalMeses >>> ", totalMeses);
  
    let tipoEAP = document.getElementById("AS_TIPO_EAP").value;
    let modal = document.getElementById("modalGenerico");
     var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
    let modalTitulo = document.getElementById("modalGenerico-titulo");
    let modalMensaje = document.getElementById("modalGenerico-mensaje");
    let submitBtn = document.getElementById("submitBtn");
  
    const elementosAfectados = [
        "FECHA_DE_REGISTRO", "REFERENCIA_MAHER", "SOL_TIPO_DOCUMENTO", "SOL_CI",
        "SOL_COMPLEMENTO", "SOL_BUSCAR", "SOL_NACIMIENTO", "SOL_CUA", "SOL_PARENTESCO",
        "SOL_ESTADO_CIVIL", "SOL_CIUDAD", "SOL_ZONA", "SOL_DIRECCION", "SOL_NUM",
        "SOL_CELULAR", "SOL_TELEFONO", "SOL_POSTAL", "SOL_CORREO", "TIENE_PODER_SOL_1",
        "NRO_PODER_SOL_1", "NRO_NOTARIA_SOL_1", "NOMBRE_NOTARIO_SOL_1", "DECLARATORIA_HEREDEROS",
        "VALIDAR_PODER", "FECHA_REVISION", "GRILLA_DERECHOHABIENTES_idd"
    ];
  
    function toggleElementos(estado) {
        elementosAfectados.forEach(id => {
            if (estado === "disable") {
                _disable(id);
            } else {
                _enable(id);
            }
        });
        if (estado === "disable") {
            _hide("GRILLA_DERECHOHABIENTES_idd");
            submitBtn.disabled = true;
        } else {
            _show("GRILLA_DERECHOHABIENTES_idd");
            submitBtn.disabled = false;
        }
    }
  
    if (totalMeses <= 36 && diferenciaDias !== 0 && tipoEAP === "CVEAP-A10") {
        modalTitulo.textContent = "Datos del Asegurado";
        modalMensaje.textContent = "No puede iniciar el trámite. No ha transcurrido más de TRES AÑOS desde la fecha de fallecimiento hasta la fecha de registro del trámite.";
        modal.style.display = "block";
        toggleElementos("disable");
    } else if (isNaN(totalMeses) && tipoEAP === "CVEAP-B14") {
        console.log("ISNAN");
        document.getElementById("AS_FECHA_FALLECIMIENTO").value = null;
        document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
    } else {
        toggleElementos("enable");
    }
    return;
  }


    function mostrarValidaFecha(estado) {
        var overlay = document.getElementById("overlay");
        overlay.style.display = 'flex';
        const  fecha_fallecimiento  = document.getElementById("AS_FECHA_FALLECIMIENTO").value;
        const date = new Date(fecha_fallecimiento);
        const today  = new Date();
        let Total_meses = calculateDetailedDateDifference(date);
        console.log(`Diferencia: ${Total_meses.years} años, ${Total_meses.months} meses, y ${Total_meses.days} días.`);
        // let diffYears = today.getFullYear() - date.getFullYear();

        const meses = (Total_meses.years*12) + Total_meses.months ;
        

        if (meses >= 36 &&  meses < 156 ){

            var listaMensajes = `<b> Los datos son validos </b>
                En virtud al D.S 822/2011 en su Artículo Nº 38 (Exigibilidad de las Prestaciones), parágrafo IV
              
            `;


            overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
             var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");
            modalTitulo.textContent = "Datos del Asegurado";
            modalMensaje.innerHTML = listaMensajes ;
            modal.style.display = "block";


        } else {
            var listaMensajes = `<b> No Cumple con la Edad de fallecimeinto de los 36 meses </b>
                <ul>
                    
                    <li>    cuenta con  ${meses} meses </li>
                
                </ul>
            `;


            overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
             var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg";
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");
            modalTitulo.textContent = "Datos del Asegurado";
            modalMensaje.innerHTML = listaMensajes ;
            modal.style.display = "block";


        }






    

      }

      
function calculateDetailedDateDifference(startDate) {
    const today = new Date();
    const start = new Date(startDate);

    let years = today.getFullYear() - start.getFullYear();
    let months = today.getMonth() - start.getMonth();
    let days = today.getDate() - start.getDate();
    let hours = today.getHours() - start.getHours();
    let minutes = today.getMinutes() - start.getMinutes();
    let seconds = today.getSeconds() - start.getSeconds();

    // Ajuste para segundos negativos
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
