    function esSolicitante() {
        var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
        if (!valor){}
    
    }

    
function esSolicitanteCobrador() {
        console.log('esSolicitanteCobrador');
        var valor = document.getElementById("SOL_COBRADOR").checked;
        console.log('valor del checked =>', valor);
        var ids = [
            'COB_BUSCAR',
            'SUBTITULO_COBRADOR_idd',
            'COB_TIPO_DOCUMENTO_idd', 'COB_TIPO_DOCUMENTO',
            'COB_CI_idd', 'COB_CI',
            'COB_COMPLEMENTO_idd', 'COB_COMPLEMENTO',
            'COB_NACIMIENTO_idd', 'COB_NACIMIENTO',
            'COB_PRIMER_APELLIDO_idd', 'COB_PRIMER_APELLIDO',
            'COB_SEGUNDO_APELLIDO_idd', 'COB_SEGUNDO_APELLIDO',
            'COB_APELLIDO_CASADA_idd', 'COB_APELLIDO_CASADA',
            'COB_PRIMER_NOMBRE_idd', 'COB_PRIMER_NOMBRE',
            'COB_SEGUNDO_NOMBRE_idd', 'COB_SEGUNDO_NOMBRE',
            'COB_PARENTESCO_idd', 'COB_PARENTESCO',
            'COB_GENERO_idd', 'COB_GENERO',
            'COB_CIUDAD_idd', 'COB_CIUDAD',
            'COB_PROVINCIA', 'COB_PROVINCIA_idd',
            'COB_DEPARTAMENTO_idd', 'COB_DEPARTAMENTO',
            'COB_ZONA_idd', 'COB_ZONA',
            'COB_DIRECCION_idd', 'COB_DIRECCION',
            'COB_NUM_idd', 'COB_NUM',
            'COB_CELULAR_idd', 'COB_CELULAR',
            'COB_TELEFONO_idd', 'COB_TELEFONO',
            'COB_POSTAL_idd', 'COB_POSTAL',
            'COB_CORREO_idd', 'COB_CORREO'
        ];

        if (!valor){
            ids.forEach(function(id) {
                var el = document.querySelector('#' + id);
                if (el) {
                    el.required = false;
                }
                _hide(id);
            });
        } else {
            ids.forEach(function(id) {
                var el = document.querySelector('#' + id);
                if (el) {
                    if (id !== 'COB_COMPLEMENTO_idd' && id !== 'COB_COMPLEMENTO') {
                        el.required = true;
                    } else {
                        el.required = false;
                    }
                }
                _show(id);
            });
        }
}
    function respuestaAsegurado(){
        const AS_TIPO_DOCUMENTO = document.getElementById("AS_TIPO_DOCUMENTO").value 
        if (AS_TIPO_DOCUMENTO =='C') {
            document.getElementById('AS_CUA').disabled = false;
            document.getElementById('AS_CI').disabled = true;
        } else {
            document.getElementById('AS_CUA').disabled = true;
            document.getElementById('AS_CI').disabled = false;
        }
    }

    function verDatosDt(rowIndex) {
        var overlay = document.getElementById("overlay");
        overlay.style.display = 'flex';
        var tipoDocumento = document.getElementById("DT_TIPO_DOCUMENTO" + rowIndex) ? document.getElementById("DT_TIPO_DOCUMENTO" + rowIndex).value : '';
        var numeroDocumento = document.getElementById("DH_CI_GRILLA_PROP" + rowIndex) ? document.getElementById("DH_CI_GRILLA_PROP" + rowIndex).value : '';
        var complemento = document.getElementById("DT_COMP_GRILLA_PROP" + rowIndex) ? document.getElementById("DT_COMP_GRILLA_PROP" + rowIndex).value : '';

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
                        document.getElementById("DT_NOMBRES" + rowIndex).value = datares.data.primerNombre + " " + (datares.data.segundoNombre != null ? datares.data.segundoNombre : "");
                        document.getElementById("DT_NOMBRES" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_PRIMER_APELLIDO" + rowIndex).value = datares.data.primerApellido;
                        document.getElementById("DT_PRIMER_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_SEGUNDO_APELLIDO" + rowIndex).value = datares.data.segundoApellido;
                        document.getElementById("DT_SEGUNDO_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_FECHA_NAC" + rowIndex).value = datares.data.fechaNacimiento;
                        document.getElementById("DT_FECHA_NAC" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_APELLIDO_CASADA" + rowIndex).value = datares.data.apellidoCasada;
                        document.getElementById("DT_APELLIDO_CASADA" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value = datares.data.idPersonaSip;
                        document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_DIRECCION" + rowIndex).value = datares.data.direccion;
                        document.getElementById("DT_DIRECCION" + rowIndex).dispatchEvent(new Event('input'));
                        if (datares.data.idGenero === 'M') {
                            document.getElementById("DT_GENERO" + rowIndex).value = 'M';
                            document.getElementById("DT_GENERO" + rowIndex).dispatchEvent(new Event('change'));

                        } else {
                            document.getElementById("DT_GENERO" + rowIndex).value = 'F';
                            document.getElementById("DT_GENERO" + rowIndex).dispatchEvent(new Event('change'));                        
                        }
                        document.getElementById("DT_CELULAR" + rowIndex).value = datares.data.telefonoCelular || "";
                        document.getElementById("DT_CELULAR" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_NUMERO" + rowIndex).value = datares.data.numero || "";
                        document.getElementById("DT_NUMERO" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_FAC_REC" + rowIndex).value = datares.data.dtFacturaRecibo || "";
                        document.getElementById("DT_FAC_REC" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_CIUDAD" + rowIndex).value = datares.data.dtCiudad || "";
                        document.getElementById("DT_CIUDAD" + rowIndex).dispatchEvent(new Event('input'));
                        document.getElementById("DT_ZONA" + rowIndex).value = datares.data.dtZona || "";
                        document.getElementById("DT_ZONA" + rowIndex).dispatchEvent(new Event('input'));
                    } else {
                        if (datares.codigoRespuesta == 1) {
                            overlay.style.display = 'none';
                            alert(datares.mensaje);
                        } else {
                            document.getElementById("DT_NOMBRES" + rowIndex).value = "";
                            document.getElementById("DT_NOMBRES" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_PRIMER_APELLIDO" + rowIndex).value = "";
                            document.getElementById("DT_PRIMER_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_SEGUNDO_APELLIDO" + rowIndex).value = "";
                            document.getElementById("DT_SEGUNDO_APELLIDO" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_FECHA_NAC" + rowIndex).value = "";
                            document.getElementById("DT_FECHA_NAC" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_APELLIDO_CASADA" + rowIndex).value = "";
                            document.getElementById("DT_APELLIDO_CASADA" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).value = "";
                            document.getElementById("DH_IDPERSONA_GRILLA_PROP" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_GENERO" + rowIndex).value = "";
                            document.getElementById("DT_GENERO" + rowIndex).dispatchEvent(new Event('change'));
                            document.getElementById("DT_DIRECCION").value = value = "";
                            document.getElementById("DT_DIRECCION").dispatchEvent(new Event('input'));
                            document.getElementById("DT_NUMERO").value = "";
                            document.getElementById("DT_NUMERO").dispatchEvent(new Event('input'));
                            document.getElementById("DT_FAC_REC" + rowIndex).value = "";
                            document.getElementById("DT_FAC_REC" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_CIUDAD" + rowIndex).value ="";
                            document.getElementById("DT_CIUDAD" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_ZONA" + rowIndex).value = "";
                            document.getElementById("DT_ZONA" + rowIndex).dispatchEvent(new Event('input'));
                            document.getElementById("DT_CELULAR" + rowIndex).value = "";;
                            document.getElementById("DT_CELULAR" + rowIndex).dispatchEvent(new Event('input'));
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


    function calcularMesesTranscurridos(ApiEstado = null ,fechaFallecimiento = null, edad ) {
            if (!fechaFallecimiento) {
                fechaFallecimiento = document.getElementById("AS_FECHA_FALLECIMIENTO").value;
                //return;
            }
            const AS_NACIMIENTO = document.getElementById("AS_NACIMIENTO").value;
            const  AS_CUA = document.getElementById("AS_CUA").value;
            if(AS_CUA != ''){
                        console.log('tiene CUA');
                        console.log("Fecha de fallecimiento en la funcion >> ",fechaFallecimiento);
                        let fechaInicioTramite = document.getElementById("FECHA_INICIO_TRAMITE").value;
                        fechaInicioTramite = new Date(fechaInicioTramite);
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
                        const date = new Date(fechaFallecimiento);
                        let Total_meses = calcularFechaDiferenciaMuerte(date);
                        console.log("### Total_meses >>> ", Total_meses);
                        const totalMeses = (Total_meses.years*12) + Total_meses.months ;
                        console.log("### TOTAL >>> ", totalMeses);
                        console.log("TOTAL DE MESES >> ", totalMeses);
                        document.getElementById("AS_MESES_FALLECIMIENTO").value = totalMeses;
                        document.getElementById("AS_MESES_FALLECIMIENTO").dispatchEvent(new Event('input'));
                        if(totalMeses > 6 && totalMeses < 18 ){
                            document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                            document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                            console.log("MAYOR A 6");
                            document.getElementById("FECHA_SUPERA_6").value = "true";
                            document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                            _hide("SOL_FAC_REC_idd");
                            _hide("SOL_NRO_FAC_REC_idd");
                            _hide("DOC_FACTURA_RECIBO_idd");
                            _hide("SOL_CODIGO_VER_FAC_REC_idd");
                            _hide("NOTA_ACLARATORIA_idd");
                            _hide("SUBTITULO_4_idd");
                            _hide("GRILLA_DERECHOHABIENTES_idd");

                            let modal = document.getElementById("modalGenerico");
                            var icono = document.querySelector(".icono-advertencia");
                            icono.src = "img/aviso_inportante.jpg";
                            let modalTitulo = document.getElementById("modalGenerico-titulo");
                            let modalMensaje = document.getElementById("modalGenerico-mensaje");
                            modalTitulo.textContent = "Trámite válido";
                            modalMensaje.innerHTML = ` <h2 style="text-align: center;">  El Pago de Gastos Funerarios deberá ser solicitado por Derechohabientes de primer o segundo grado. (RA. Nro. 467/2019, Art. 62, parágrafo III). </h2>`;
                            modal.style.display = "block";   
                            return totalMeses;
                        }  
                        if(totalMeses <= 5  ){

                                    document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                                    document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                                    
                                    console.log("MAYOR A 6");
                                    document.getElementById("FECHA_SUPERA_6").value = "true";
                                    document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));

                                    _show("SOL_FAC_REC_idd");
                                    _show("SOL_NRO_FAC_REC_idd");
                                    _show("DOC_FACTURA_RECIBO_idd");
                                    _show("SOL_CODIGO_VER_FAC_REC_idd");
                                    _show("NOTA_ACLARATORIA_idd");
                                    _show("SUBTITULO_4_idd");
                                    _show("GRILLA_DERECHOHABIENTES_idd");

                                    let modal = document.getElementById("modalGenerico");
                                    var icono = document.querySelector(".icono-advertencia");
                                    icono.src = "img/valido.png";
                                    let modalTitulo = document.getElementById("modalGenerico-titulo");
                                    let modalMensaje = document.getElementById("modalGenerico-mensaje");
                                    var modalCerrarBtn = document.getElementById("modalGenerico-boton");
                                    modalCerrarBtn.textContent = "Continuar";
                                    modalTitulo.textContent = "Trámite valido";
                                    modalMensaje.innerHTML = ` <h1 style="text-align: center;">El pago de gastos funerarios podrá ser solicitado por  cualquier persona que acredite haber pagado los mismos. (RA. Nro. 467/2019, Art. 62, parágrafo II). </h1>`;
                                    modal.style.display = "block";   
                                    return totalMeses;
                                    //no es beneficiario del pago de gastos funerales ni gastos funerarios 
                                } if (totalMeses > 18 ) {

                                    if (edad == 'MAYOR'){
                                        document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                                        document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                                        console.log("POR  MAS DE  18 MESES ");
                                        let modal = document.getElementById("modalGenerico");
                                        var icono = document.querySelector(".icono-advertencia");
                                        icono.src = "img/advertencia_1.jpg";
                                        let modalTitulo = document.getElementById("modalGenerico-titulo");
                                        let modalMensaje = document.getElementById("modalGenerico-mensaje");
                                        modalTitulo.textContent = "Trámite No  válido ";
                                        modalMensaje.innerHTML = ` <h1 style="text-align: center;">Corresponde iniciar su trámite en Renta Dignidad (FRUV), toda vez, que su edad es mayor o igual 60 años</h1>`;
                                        modal.style.display = "block"; 
                                        document.getElementById("FECHA_SUPERA_6").value = "true";
                                        document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                        document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                        document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                        document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                        document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_NACIMIENTO").value = '';
                                        document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_APELLIDO_CASADA").value = '';
                                        document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_CUA").value = value = '';
                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                        return totalMeses;

                                    } else {
                                        document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                                        document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                                        console.log("POR  MAS DE  18 MESES ");
                                        let modal = document.getElementById("modalGenerico");
                                        var icono = document.querySelector(".icono-advertencia");
                                        icono.src = "img/advertencia_1.jpg";
                                        let modalTitulo = document.getElementById("modalGenerico-titulo");
                                        let modalMensaje = document.getElementById("modalGenerico-mensaje");
                                        modalTitulo.textContent = "Trámite No  válido ";
                                        modalMensaje.innerHTML = ` <h1 style="text-align: center;">Su solicitud no procede porque transcurrieron más de 18 meses desde su fallecimiento. (RA. Nro. 467/2019, Art. 62, parágrafo III). vercion3 </h1>`;
                                        modal.style.display = "block"; 
                                        document.getElementById("FECHA_SUPERA_6").value = "true";
                                        document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                        document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                        document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                        document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                        document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_NACIMIENTO").value = '';
                                        document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_APELLIDO_CASADA").value = '';
                                        document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                        document.getElementById("AS_CUA").value = value = '';
                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                        return totalMeses;
                                    }




                                

                                } 
                            if (totalMeses = 18 ) {
                                let fecha_un_dia =  new Date();
                                fecha_un_dia.setDate(fecha_un_dia.getDate() - 1);
                                console.log("Fecha después de sumar un día:", fecha_un_dia.toISOString().split('T')[0]);
                                fecha_un_dia =  fecha_un_dia.toISOString().split('T')[0];
                                const dia_validar = new Date(fecha_un_dia);
                                                let fullUrl = window.location.href;
                                                const arrayUrl = fullUrl.split("/");
                                                const url_local = arrayUrl[0]+'//'+arrayUrl[2]; 
                                                var requestData = {
                                                    "fecha": dia_validar
                                                };
                                                $.ajax({
                                                    dataType: 'json',
                                                    contentType: 'application/json',
                                                    type: 'POST',
                                                    data: JSON.stringify(requestData),
                                                    url:url_local+'/api/dias_festivos',
                                                    success: function (datares1) {
                                                        if(datares1.data.length > 0){
                                                            console.log('es valido por feriado');
                                                            document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                                                            document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                                                            document.getElementById("FECHA_SUPERA_6").value = "true";
                                                            document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                                                            _show("SOL_FAC_REC_idd");
                                                            _show("SOL_NRO_FAC_REC_idd");
                                                            _show("DOC_FACTURA_RECIBO_idd");
                                                            _show("SOL_CODIGO_VER_FAC_REC_idd");
                                                            _show("NOTA_ACLARATORIA_idd");
                                                            _show("SUBTITULO_4_idd");
                                                            _show("GRILLA_DERECHOHABIENTES_idd");
                                                            // let modal = document.getElementById("modalGenerico");
                                                            // var icono = document.querySelector(".icono-advertencia");
                                                            // icono.src = "img/aviso_inportante.jpg";
                                                            // let modalTitulo = document.getElementById("modalGenerico-titulo");
                                                            // let modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                            // modalTitulo.textContent = "Trámite válido";
                                                            // modalMensaje.innerHTML = ` <h2 style="text-align: center;">  El Pago de Gastos Funerarios deberá ser solicitado por Derechohambientes de primer o segundo grado. (RA. Nro. 467/2019, Art. 62, parágrafo III). </h2>`;
                                                            // modal.style.display = "block"; 
                                                            return totalMeses-1;
                                                        } else {
                                                            console.log('datares1.data.esta vacio : ');
                                                        }
                                                    },
                                                    error: function(xhr, status, error) {
                                                        console.error('Error:', error);
                                                        // Manejar el error aquí
                                                    }
                                                });
                                        let valido = '';
                                        if(Total_meses.days <= 2){
                                                fecha_un_dia.setDate(fecha_un_dia.getDate() - 1);
                                                console.log("Fecha después de sumar un día:", fecha_un_dia.toISOString().split('T')[0]);
                                                fecha_un_dia =  fecha_un_dia.toISOString().split('T')[0];
                                                const dia_validar = new Date(fecha_un_dia);
                                                let diasSemana = [ "lunes", "martes", "miércoles", "jueves", "viernes", "sábado","domingo"];
                                                let diaSemana = diasSemana[dia_validar.getDay()];
                                                console.log('diaSemana ',diaSemana);
                                                if( diaSemana == 'sábado' ){
                                                valido = 'valido';
                                                } else {
                                                    console.log('  no es jueves ');
                                                }

                                                let fecha_un_dia2 =  new Date();
                                                fecha_un_dia2.setDate(fecha_un_dia2.getDate() - 2);
                                                console.log("Fecha después de sumar un día:", fecha_un_dia2.toISOString().split('T')[0]);
                                                fecha_un_dia2 =  fecha_un_dia2.toISOString().split('T')[0];
                                                const dia_validar2 = new Date(fecha_un_dia2);
                                                let diasSemana2 = [ "lunes", "martes", "miércoles", "jueves", "viernes", "sábado","domingo"];
                                                let diaSemana2 = diasSemana2[dia_validar2.getDay()];
                                                console.log('diaSemana ',diaSemana2);
                                                if( diaSemana2 == 'domingo' ){
                                                    valido = 'valido';
                                                } else {
                                                    console.log('  no es jueves ');
                                                }
                                

                                    console.log('valido es esa ewe as ',valido);

                                    if (valido =='valido'){
                                        console.log('es valido');
                                        document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                                        document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                                        console.log("MAYOR A 6");
                                        document.getElementById("FECHA_SUPERA_6").value = "true";
                                        document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                                        _show("SOL_FAC_REC_idd");
                                        _show("SOL_NRO_FAC_REC_idd");
                                        _show("DOC_FACTURA_RECIBO_idd");
                                        _show("SOL_CODIGO_VER_FAC_REC_idd");
                                        _show("NOTA_ACLARATORIA_idd");
                                        _show("SUBTITULO_4_idd");
                                        _show("GRILLA_DERECHOHABIENTES_idd");
                                        // let modal = document.getElementById("modalGenerico");
                                        // var icono = document.querySelector(".icono-advertencia");
                                        // icono.src = "img/aviso_inportante.jpg";
                                        // let modalTitulo = document.getElementById("modalGenerico-titulo");
                                        // let modalMensaje = document.getElementById("modalGenerico-mensaje");
                                        // modalTitulo.textContent = "Trámite válido";
                                        // modalMensaje.innerHTML = ` <h2 style="text-align: center;">  El Pago de Gastos Funerarios deberá ser solicitado por Derechohambientes de primer o segundo grado. (RA. Nro. 467/2019, Art. 62, parágrafo III). </h2>`;
                                        // modal.style.display = "block"; 
                                        return totalMeses-1;
                                    } else {
                                        console.log('no eses valido sa sasas as ');
                                    }
                            
                                    console.log('validacion', valido);
                                } else {
                                console.log('no ingreso por ser menor a 3 dias '); 
                                }
                                document.getElementById("AS_MESES_FALLECIMIENTO").style.visibility  = "visible";
                                document.getElementById("AS_MESES_FALLECIMIENTO_idd").style.visibility  = "visible";
                                console.log("POR MES 18");
                                let modal = document.getElementById("modalGenerico");
                                var icono = document.querySelector(".icono-advertencia");
                                icono.src = "img/advertencia_1.jpg";
                                let modalTitulo = document.getElementById("modalGenerico-titulo");
                                let modalMensaje = document.getElementById("modalGenerico-mensaje");
                                modalTitulo.textContent = "Trámite No  válido ";
                                modalMensaje.innerHTML = ` <h1 style="text-align: center;">Su solicitud no procede porque transcurrieron más de 18 meses desde su fallecimiento. (RA. Nro. 467/2019, Art. 62, parágrafo III).  vesrion 2 </h1>`;
                                modal.style.display = "block"; 
                                document.getElementById("FECHA_SUPERA_6").value = "true";
                                document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_NACIMIENTO").value = '';
                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                document.getElementById("AS_APELLIDO_CASADA").value = '';
                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                document.getElementById("AS_CUA").value = value = '';
                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                return totalMeses;

                    } else {

                        console.log("POR EL ELSE");
                        document.getElementById("FECHA_SUPERA_6").value = "false";
                        document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                        _hide("SOL_FAC_REC_idd");
                        _hide("SOL_NRO_FAC_REC_idd");
                        _hide("DOC_FACTURA_RECIBO_idd");
                        _hide("SOL_CODIGO_VER_FAC_REC_idd");
                        _hide("NOTA_ACLARATORIA_idd");
                        let modal = document.getElementById("modalGenerico");
                        var icono = document.querySelector(".icono-advertencia");
                        icono.src = "img/aviso_inportante.jpg";
                        let modalTitulo = document.getElementById("modalGenerico-titulo");
                        let modalMensaje = document.getElementById("modalGenerico-mensaje");
                        var modalCerrarBtn = document.getElementById("modalGenerico-boton");
                        modalCerrarBtn.textContent = "Continuar";
                        modalTitulo.textContent = "Aviso";
                        modalMensaje.textContent = `De acuerdo a la RA. Nro. 467/2019, Art. 62, deberá presentar factura comercial o recibo a nombre del solicitante, en original `;
                        modal.style.display = "block";   
                        if (isNaN(totalMeses)) {
                            totalMeses = 0;
                        }
                        console.log("el totalde meses > ",totalMeses);
                        document.getElementById("AS_MESES_FALLECIMIENTO").value = totalMeses;
                        document.getElementById("AS_MESES_FALLECIMIENTO").dispatchEvent(new Event('input'));
                        return totalMeses;
                    } 
            } else {
                    console.log('notiene AS_NACIMIENTO',AS_NACIMIENTO);
                    console.log('notiene CUA', fechaFallecimiento);
                    console.log('notiene CUA');
                    const nac = new Date(AS_NACIMIENTO);
                    const fall = new Date(fechaFallecimiento);
                    let diffYears = fall.getFullYear()- nac.getFullYear() ;
                    console.log('tiene CUA',diffYears);


                    if(diffYears>= 60){
                        var modal = document.getElementById("modalGenerico");
                        var icono = document.querySelector(".icono-advertencia");
                        icono.src = "img/aviso_inportante.jpg";
                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                        modalTitulo.textContent = "Datos del Asegurado";
                        //  modalMensaje.textContent = "no es beneficiario del pago de gastos funerales ni gastos funerarios ";
                        modalMensaje.innerHTML = `cuenta con ${diffYears} años <br>
                        <h2 style="text-align: center;">  Corresponde iniciar su trámite en Renta Dignidad  (FRUV),  toda vez que es ≥ 60 años, y no es Asegurado de este Sistema. </h2>`;
                        modal.style.display = "block";
                    } else {
                        var modal = document.getElementById("modalGenerico");
                        var icono = document.querySelector(".icono-advertencia");
                        icono.src = "img/advertencia_1.jpg";
                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                        modalTitulo.textContent = "Datos del Asegurado";
                        modalMensaje.innerHTML = ` cuenta con ${diffYears} años <br>
                        <h2 style="text-align: center;"> No es Beneficiario del pago de Gastos Funerarios,  ni Gastos Funerales, toda vez que es < 60 años y no es Asegurado de este Sistema  </h2>
                        `;
                        modal.style.display = "block";
                    }                       
            }
    }


    async function mas18(fechaFallecimiento) {
    // Simulamos una espera (por ejemplo, una llamada a una API)
    return new Promise(resolve => {
        setTimeout(() => {
        let valido = '';
        let fecha_un_dia =  new Date();
        fecha_un_dia.setDate(fecha_un_dia.getDate() - 1);
        console.log("Fecha después de sumar un día:", fecha_un_dia.toISOString().split('T')[0]);
        fecha_un_dia =  fecha_un_dia.toISOString().split('T')[0];
        const dia_validar = new Date(fecha_un_dia);
        let diasSemana = [ "lunes", "martes", "miércoles", "jueves", "viernes", "sábado","domingo"];
        let diaSemana = diasSemana[dia_validar.getDay()];
        console.log('diaSemana ',diaSemana);
        if( diaSemana == 'domingo' ){
        valido = 'valido';
        } else {
            console.log('  no es jueves ');
        }

        let fecha_un_dia2 =  new Date();
        fecha_un_dia2.setDate(fecha_un_dia2.getDate() - 2);
        console.log("Fecha después de sumar un día:", fecha_un_dia2.toISOString().split('T')[0]);
        fecha_un_dia2 =  fecha_un_dia2.toISOString().split('T')[0];
        const dia_validar2 = new Date(fecha_un_dia2);
        let diasSemana2 = [ "lunes", "martes", "miércoles", "jueves", "viernes", "sábado","domingo"];
        let diaSemana2 = diasSemana2[dia_validar2.getDay()];
        console.log('diaSemana ',diaSemana2);
        if( diaSemana2 == 'sábado' ){
            valido = 'valido';
        } else {
            console.log('  no es jueves ');
        }

        let fullUrl = window.location.href;
        const arrayUrl = fullUrl.split("/");
        const url_local = arrayUrl[0]+'//'+arrayUrl[2]; 
        var requestData = {
            "fecha": fecha_un_dia
        };
        $.ajax({
            dataType: 'json',
            contentType: 'application/json',
            type: 'POST',
            data: JSON.stringify(requestData),
            url:url_local+'/api/dias_festivos',
            success: function (datares1) {
                console.log('mostrar datos de informacion de la fecha  ',datares1);
                console.log('datares1.data dasdasdasdsa',    datares1.data);
                console.log('datares1.data.length',    datares1.data.length);
                if(datares1.data.length > 0){
                    valido = 'valido';
                } else {
                    console.log('datares1.data.esta vacio : ');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Manejar el error aquí
            }
        });
    resolve(valido);  // Resolvemos la promesa con el resultado
        }, 5000);  // Simulamos 1 segundo de espera
    });
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

    function verDatos(a) {

        _funcion_prueba('holas');
        console.log("buscar datos >>> ");
        var overlay = document.getElementById("overlay");
        overlay.style.display = 'flex';
        const dateParts = a.split('/');
        const year = dateParts[2];
        const month = dateParts[1].padStart(2, '0');
        const day = dateParts[0].padStart(2, '0');
        var fechaHace6Meses = year + '-' + month + '-' + day;
        document.getElementById("FECHA_INICIO_TRAMITE").value = fechaHace6Meses;
        document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));
        esSolicitante();
        var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
        var numeroDocumento = document.getElementById("AS_CI").value;
        var complemento = document.getElementById("AS_COMPLEMENTO").value;
        var fechaNacimiento = document.getElementById("AS_NACIMIENTO").value;
        if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {
            var requestData = {
                "tipoDocumento": tipoDocumento,
                "numeroDocumento": numeroDocumento,
                "complemento": complemento,
                "fechaNacimiento": fechaNacimiento,
            };
            $.ajax({
                dataType: 'json',
                contentType: 'application/json',
                type: 'POST',
                data: JSON.stringify(requestData),
                url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
                success: function(datares) {
                    if (datares.codigoRespuesta == 0) {
                        if (datares.data.cua != null) {
                            console.log('TIENE CUA ');
                            if (datares.data.fechaDefuncion != null) {
                                ///validacion de fallecido 
                                console.log('TIENE FECHA FALLECIMIENTO ');
                                $.ajax({
                                    dataType: 'json',
                                    contentType: 'application/json',
                                    type: 'GET',
                                    url: 'https://sgg.gestora.bo/reportes-gesip/api/v1/prestaciones/solicitud?cua=' + datares.data.cua,
                                    success: function(dataPrestaciones) {
                                        console.log('dataPrestaciones',dataPrestaciones.data[0]);
                                        console.log('dataPrestaciones',dataPrestaciones.data[0].codAseguradora);
                                        const arrayTipos = dataPrestaciones.data[0].codAseguradora.split("-");
                                        console.log('arrayTipos',arrayTipos[0]);
                                        const numero = +arrayTipos[0];
                                        console.log('numero',numero); 
                                        if (numero === 1 || numero === 2 ){
                                            if(dataPrestaciones.data[0].codBeneficio == 'INV - INVALIDEZ'){
                                                const listaMensajes = `<h1>  ${dataPrestaciones.data[0].codAseguradora}   </h1>
                                            <p><h1>El asegurado fallecido percibia una pension de invalidez con una Entidad Aseguradora, el registro del trámite se realizará en Gestora, sin embargo, el pago será realizado por dicha entidad.</h1></p>`;
                                                const icono_imagen = "img/advertencia_1.jpg";
                                                const cabecera = "Trámites registrados";
                                                modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                            } else {
                                                const listaMensajes = `<h1>  ${dataPrestaciones.data[0].codAseguradora}   </h1>
                                                <p><h1> El solicitante debe apersonarse a la Entidad Aseguradora a fin de verificar si cuenta con la reserva de Gasto Funerario. Si NO cuenta con Reserva el solicitante deberá presentar una copia de la nota de respuesta emitida por la aseguradora para dar continuidad al trámite</h1> </p>`;
                                                const icono_imagen = "img/advertencia_1.jpg";
                                                const cabecera = "Trámites registrados";
                                                modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                formularioHTML(datares);



                                            }
                                        } else {
                                            $.ajax({
                                                dataType: 'json',
                                                contentType: 'application/json',
                                                type: 'GET',
                                                url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-retiros/api/v1/tipoPago/verificacionPrestacion?cua=' + datares.data.cua,
                                                success: function(dataPlanilla) {
                                                    if (dataPlanilla.codigo == '200') {
                                                        console.log('DATA PLANILLA CODIGO ==>>',dataPlanilla.codigo );
                                                        console.log('PLANILLA', dataPlanilla.data.tipo);
                                                        const arrayTipos = dataPlanilla.data.tipo.split("/");
                                                        console.log(arrayTipos);
                                                        const buscar1 = 'PAGO CC';
                                                        const buscar2 = 'GASTOS FUNERARIOS';
                                                        const buscar3 = 'INVALIDEZ';
                                                        const buscar4 = 'JUBILACION';
                                                        const buscar5 = 'MUERTE';
                                                        if (arrayTipos.includes(buscar2)) {
                                                            const listaMensajes = `<h1> El CUA: ${datares.data.cua} ya cuenta con el pago de Gastos Funerarios  </h1>`;
                                                            const icono_imagen = "img/advertencia_1.jpg";
                                                            const cabecera = "Trámites registrados";
                                                            modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                        } else {
                                                            let fullUrl = window.location.href;
                                                            const arrayUrl = fullUrl.split("/");
                                                            const url_local = arrayUrl[0] + '//' + arrayUrl[2];
                                                            var requestData = {
                                                                "cua": datares.data.cua
                                                            };
                                                            $.ajax({
                                                                dataType: 'json',
                                                                contentType: 'application/json',
                                                                type: 'POST',
                                                                data: JSON.stringify(requestData),
                                                                url: url_local + '/api/buscarTramitesGFU',
                                                                success: function(datares1) {
                                                                    const cantidad = datares1.data.length;
                                                                    if (cantidad > 0) {
                                                                        var listado = '';
                                                                        for (var j = 0; j < cantidad; j++) {
                                                                            var li = `<li> 
                                                                            <b> ${datares1.data[j].cas_cod_id}</b>,<br> Estado : <b> ${datares1.data[j].descripcion}</b>    </li>`
                                                                            listado = listado + li;
                                                                        }
                                                                        const listaMensajes = `<h1>No puede registrar trámite, debido a que cuenta con un trámite de Gastos Funerarios registrado previamente  <h1> <ul>     ${listado} </ul>`;
                                                                        const icono_imagen = "img/advertencia_1.jpg";
                                                                        const cabecera = "Trámites registrados";
                                                                        modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                                    } else {
                                                                        const fecha_nacimiento = new Date(datares.data.fechaNacimiento);
                                                                        const fecha_fallecimiento = new Date(datares.data.fechaDefuncion);
                                                                        let diferencia_gestiones = fecha_fallecimiento.getFullYear() - fecha_nacimiento.getFullYear();
                                                                            
                                                                        if (diferencia_gestiones >= 60) {

                                                                            console.log("es mayor a los 60 años  4",diferencia_gestiones);
                                                                            console.log('Fecha de ==>', dataPrestaciones.data[0].fecSolicitud);

                                                                            if (arrayTipos.includes(buscar4) ) {
                                                                                
                                                                                    console.log('JUBILACION' );
                                                                                    //const listaMensajes = `<h1>Corresponde iniciar su trámite en Renta Dignidad (FRUV), toda vez que es ≥ 60 años, cuenta con  ${diferencia_gestiones} años de fallecido </h1> `;
                                                                                    //const listaMensajes = `<h1>Consulta con el responsable de Oficina Nacional si cuenta con reserva de Gasto Funerario</h1>`;
                                                                                    const icono_imagen = "img/advertencia_1.jpg";
                                                                                    const cabecera = "Trámites No Válido JUBILACION";   
                                                                                    var listaMensajes = `
                                                                                
                                                                                    <h1>Consulta con el responsable de Oficina Nacional si cuenta con reserva de Gasto Funerario</h1><br>
                                                                                        <button id="btnSi">Sí cuenta con reserva </button>
                                                                                        <button id="btnNo">No cuenta con reserva</button>
                                                                                `;
                                                                                overlay.style.display = 'none';
                                                                                var modal = document.getElementById("modalGenerico");
                                                                                var icono = document.querySelector(".icono-advertencia");
                                                                                icono.src = icono_imagen;
                                                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                modalTitulo.textContent = "Datos del Asegurado";
                                                                                modalMensaje.innerHTML = listaMensajes;
                                                                                modal.style.display = "block";
                            
                                                                                document.getElementById("btnSi").addEventListener("click", function() {
                                                                                    // Lógica para continuar con la búsqueda
                                                                                    console.log("El usuario eligió continuar. 1");
                                                                                    formularioHTML(datares);

                                                
                                                                                    
                                                                                    let respuesta = calcularMesesTranscurridos(datares.data.apiEstado, datares.data.fechaDefuncion, 'NO');   
                                                                                    console.log("Respuesta contador >>> >>> ", respuesta); 
                                                                                    if( respuesta > 18  ){
                                                                                    } else {
                                                                                        formularioHTML(datares);
                                                                                    }
                                                                              
                            
                                                                                });
                            
                                                                                document.getElementById("btnNo").addEventListener("click", function() {
                                                                                    // Lógica para cancelar la búsqueda
                                                                                    const listaMensajes = `<h1>Corresponde iniciar su trámite en Renta Dignidad (FRUV), toda vez que es ≥ 60 años y no cuenta con la Reserva de gasto Funerario (Trámite Ley 1732), cuenta con  ${diferencia_gestiones} años de fallecido </h1> `;
                                                                                    const icono_imagen = "img/advertencia_1.jpg";
                                                                                    const cabecera = "Trámites No VAlido";
                                                                                    modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);

                                                                                    limpiarFormulario();
                                                                                });

                                                                                    
                                                                               



                                                                            }   else if ( arrayTipos.includes(buscar1)){

                                                                                console.log('PAGOS CC' );
                                                                                const listaMensajes = `<h1>Corresponde iniciar su trámite en Renta Dignidad (FRUV), toda vez que es ≥ 60 años, cuenta con  ${diferencia_gestiones} años de fallecido </h1> `;
                                                                                const icono_imagen = "img/advertencia_1.jpg";
                                                                                const cabecera = "Trámites No VAlido";
                                                                                modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);


                                                                            }   else if (arrayTipos.includes(buscar3)) {
                                                                                if(diferencia_gestiones > 65){
                                                                                    console.log('INVALIDEZ mayor de 65',diferencia_gestiones);
                                                                                    const listaMensajes = `<h1 style="text-align: center;">Corresponde iniciar su trámite en Renta Dignidad (FRUV), toda vez, que su edad es mayor o igual 65 años</h1> `;
                                                                                    const icono_imagen = "img/advertencia_1.jpg";
                                                                                    const cabecera = "Trámite No  válido INVALIDEZ";
                                                                                    modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                                                } else  {
                                                                                    console.log('INVALIDEZ es menor 65 años ');
                                                                                }
                                                                            } 
                                                                        } else if(diferencia_gestiones < 60){
                                                                            console.log("es < a los 60 años 3",diferencia_gestiones);
                                                                            if (arrayTipos.includes(buscar4)) {
                                                                                console.log('JUBILACION' );
                                                                                // const listaMensajes = `<h1>dataPlanilla.codigo  ${dataPlanilla.data.tipo} </h1> ${dataPlanilla.data.tipo} `;
                                                                                // const icono_imagen = "img/advertencia_1.jpg";
                                                                                // const cabecera = "Trámites registrados";
                                                                                // modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                                                formularioHTML(datares);
                                                                                let respuesta = calcularMesesTranscurridos(datares.data.apiEstado, datares.data.fechaDefuncion, 'NO');   
                                                                                console.log("Respuesta contador >>> >>> ", respuesta); 
                                                                                if( respuesta > 18  ){
                                                                                } else {
                                                                                    formularioHTML(datares);
                                                                                }
                                                                            }
                                                                            if (arrayTipos.includes(buscar1)) {
                                                                                console.log('PAGO CC' );
                                                                                const listaMensajes = `<h1>dataPlanilla.codigo  ${dataPlanilla.data.tipo} </h1> ${dataPlanilla.data.tipo} `;
                                                                                const icono_imagen = "img/advertencia_1.jpg";
                                                                                const cabecera = "Trámites registrados";
                                                                                modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                                                formularioHTML(datares);
                                                                                let respuesta = calcularMesesTranscurridos(datares.data.apiEstado, datares.data.fechaDefuncion,'NO');   
                                                                                console.log("Respuesta contador >>> >>> ", respuesta); 
                                                                                if( respuesta > 18  ){
                                                                                        console.log('mayor a 18 meses ');
                                                                                        document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                                                                        document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                                                                        document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                                                                        document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                                                                        document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_NACIMIENTO").value = '';
                                                                                        document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_APELLIDO_CASADA").value = '';
                                                                                        document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                                                                        document.getElementById("AS_CUA").value = value = '';
                                                                                        document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                                                                        const listaMensajes = `   <h1>No se puede registrar la solicitud, debido a que trancurrió mas de 18 meses desde el fallecimiento <h1> `;
                                                                                        overlay.style.display = 'none';
                                                                                        var modal = document.getElementById("modalGenerico");
                                                                                        var icono = document.querySelector(".icono-advertencia");
                                                                                        icono.src = "img/advertencia_1.jpg"; 
                                                                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                        modalTitulo.textContent = "Trámites registrados";
                                                                                        modalMensaje.innerHTML = listaMensajes ; 
                                                                                        modal.style.display = "block";
                                                                                        overlay.style.display = 'none';
                
                                                                                } else {
                                                                                    formularioHTML(datares);
                                                                                }
                                                                            }
                                                                            if (arrayTipos.includes(buscar1)) {
                                                                                console.log('PAGO CC' );
                                                                        
                                                                            }
                                                                            if (arrayTipos.includes(buscar3) && arrayTipos.includes(buscar4)  ) {
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
                                                    } else {
                
                                                        const fecha_nacimiento = new Date(datares.data.fechaNacimiento);
                                                        const fecha_fallecimiento = new Date(datares.data.fechaDefuncion);
                                                        let diferencia_gestiones = fecha_fallecimiento.getFullYear() - fecha_nacimiento.getFullYear();
                                                        console.log('NO CUENTA CON NINGUN TIPO DE PAGO ' );
                                                        const listaMensajes = `<h1>  </h1> ${diferencia_gestiones} `;
                                                        const icono_imagen = "img/advertencia_1.jpg";
                                                        const cabecera = "Trámites registrados";
                                                        modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);
                                                        if (diferencia_gestiones >= 60) {



                                                            const listaMensajes = `<h1>Iniciar su trámite en Renta Dignidad (FRUV), toda vez que es ≥ 60 años, cuenta con  ${diferencia_gestiones} años de fallecido </h1> `;
                                                            const icono_imagen = "img/advertencia_1.jpg";
                                                            const cabecera = "Trámites registrados";
                                                            modalMensajeDinamico(listaMensajes,icono_imagen,cabecera);




                                                            limpiarCamposAsegurado();




                                                        } else if(diferencia_gestiones < 60){
                                                            formularioHTML(datares);
                                                            let respuesta = calcularMesesTranscurridos(datares.data.apiEstado, datares.data.fechaDefuncion,'NO');   
                                                            console.log("Respuesta contador >>> >>> ", respuesta); 
                                                            if( respuesta > 18  ){
                                                                    console.log('mayor a 18 meses ');
                                                                    limpiarCamposAsegurado();
                                                                    const listaMensajes = `   <h1>No se puede registrar la solicitud, debido a que trancurrió mas de 18 meses desde el fallecimiento <h1> `;
                                                                    overlay.style.display = 'none';
                                                                    var modal = document.getElementById("modalGenerico");
                                                                    var icono = document.querySelector(".icono-advertencia");
                                                                    icono.src = "img/advertencia_1.jpg"; 
                                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                    modalTitulo.textContent = "Trámites registrados";
                                                                    modalMensaje.innerHTML = listaMensajes ; 
                                                                    modal.style.display = "block";
                                                                    overlay.style.display = 'none';
                
                                                            } else {
                                                                formularioHTML(datares);
                                                            }
                                                        }
                                                    }
                                                    //////////////////////////////////////////////////
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Error:', error);
                                                    // Manejar el error aquí
                                                }
                                            });
                                            
                                        }




                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error:', error);
                                        // Manejar el error aquí
                                    }
                                });







                            
                                ///////-----------------------------
                            } else { // tiene fecha de fallecimiento 
                                const mensaje =  ` <p> <h1>No se cuenta con el registro de la fecha de fallecimiento, favor registre la misma de forma previa, a través del Registro de Novedades</h1> </p>`;
                                const icono_imagen = "img/advertencia_1.jpg";
                            const cabecera = "DATOS DEL ASEGURADO";
                                modalMensajeDinamico(mensaje,icono_imagen,cabecera);
                            }
                        } else {
                            console.log('no tiene CUA');
                            formularioHTML(datares);
                            const mensaje =  ` <h2 style="text-align: center;"> Causante no tiene CUA., ingrese fecha de fallecimiento  </h2>`;
                            const icono_imagen = "img/aviso_inportante.jpg";
                            const cabecera = "Datos del Asegurado";
                            modalMensajeDinamico(mensaje,icono_imagen,cabecera);
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
                        if (datares.codigoRespuesta === "5") {
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
                },
                error: function(err) {
                    overlay.style.display = 'none';
                }
            });

        } else {
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

    function limpiarCamposAsegurado() {

                                                                            document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                                                            document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                                                            document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                                                            document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                                                            document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                                                            document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                                                            document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                                                            document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                                                            document.getElementById("AS_NACIMIENTO").value = '';
                                                                            document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                                                            document.getElementById("AS_APELLIDO_CASADA").value = '';
                                                                            document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                                                            document.getElementById("AS_CUA").value = value = '';
                                                                            document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
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


    function formularioHTML(datares) {
        esSolicitanteCobrador();
        console.log('datares', datares);

        const setValue = (id, value) => {
            const el = document.getElementById(id);
            if (el) {
                el.value = value ?? '';
                el.dispatchEvent(new Event('input'));
            }
        };

        const d = datares.data;

        const values = {
            AS_PRIMER_NOMBRE: d.primerNombre,
            AS_SEGUNDO_NOMBRE: d.segundoNombre,
            AS_PRIMER_APELLIDO: d.primerApellido,
            AS_SEGUNDO_APELLIDO: d.segundoApellido,
            AS_NACIMIENTO: d.fechaNacimiento,
            AS_APELLIDO_CASADA: d.apellidoCasada,
            AS_CUA: d.cua,
            AS_GENERO: d.idGenero === 'M' ? 'MASCULINO' : 'FEMENINO',
            AS_ESTADO_CIVIL: d.idEstadoCivil === 'C' ? 'CASADO(A)' : 'SOLTERO(A)',
            AS_API_ESTADO: d.apiEstado,
            AS_IDPERSONA: d.idPersonaSip,
            AS_FECHA_FALLECIMIENTO: d.fechaDefuncion,
            AS_COMPLEMENTO: d.complemento !== null ? d.complemento : ''
        };

        Object.entries(values).forEach(([id, value]) => setValue(id, value));

    }


    function verDatos2(a) {
        const AS_TIPO_DOCUMENTO = document.getElementById("AS_TIPO_DOCUMENTO").value;
        if (AS_TIPO_DOCUMENTO =='C') {
            const AS_CUA = document.getElementById("AS_CUA").value;
            $.ajax({
                dataType: 'json',
                contentType: 'application/json',
                type: 'GET',
                data: JSON.stringify(requestData),
                url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-retiros/api/v1/controlTramites/searchCua?cua='+AS_CUA,
                success: function (datares) {
                                    console.log("EL DATA >>> ", datares.data.militar);
                                    let fullUrl = window.location.href;
                                    const arrayUrl = fullUrl.split("/");
                                    const url_local = arrayUrl[0]+'//'+arrayUrl[2]; 
                                    var requestData = {
                                        "cua": datares.data.cua
                                    };
                                    $.ajax({
                                        dataType: 'json',
                                        contentType: 'application/json',
                                        type: 'POST',
                                        data: JSON.stringify(requestData),
                                        url:url_local+'/api/buscarTramitesGFU',
                                        success: function (datares1) {
                                            const cantidad = datares1.data.length;
                                            if(cantidad>0){
                                                var listado = '';
                                                for (var j = 0; j < cantidad; j++) {
                                                    var li =  `<li>    <b> ${datares1.data[j].cas_cod_id}</b>, Estado : <b> ${datares1.data[j].descripcion}</b>    </li>`
                                                    listado = listado + li;
                                                }
                                                var listaMensajes = `   
                                                    <h1><h1> 
                                                        <ul>   
                                                            ${listado}  
                                                        </ul>
                                                    `;
                                                var overlay = document.getElementById("overlay");
                                                overlay.style.display = 'flex';
                                                overlay.style.display = 'none';
                                                var modal = document.getElementById("modalGenerico");
                                                var icono = document.querySelector(".icono-advertencia");
                                                icono.src = "img/advertencia_1.jpg"; 
                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                modalTitulo.textContent = "Trámites registrados";
                                                modalMensaje.innerHTML = listaMensajes ; 
                                                modal.style.display = "block";

                                            } else {
                                                if(datares.data.militar){
                                                    var listaMensajes = `<h1> ASEGURADO MILITAR. <br> Deberá  realizar el procedimiento establecido para la desclasificación ante el Misterio de Defensa<h1>`;
                                                    var overlay = document.getElementById("overlay");
                                                    overlay.style.display = 'flex';
                                                    overlay.style.display = 'none';
                                                    var modal = document.getElementById("modalGenerico");
                                                    var icono = document.querySelector(".icono-advertencia");
                                                    icono.src = "img/advertencia_1.jpg"; 
                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                    modalTitulo.textContent = "Asegurado Militar";
                                                    modalMensaje.innerHTML = listaMensajes ; 
                                                    modal.style.display = "block";

                                                    document.getElementById('AS_CI').value = datares.data.documentoIdentidad;
                                                    document.getElementById("AS_CI").dispatchEvent(new Event('input'));
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




                                                    
                                                } else {
                                                    document.getElementById('AS_CI').value = datares.data.documentoIdentidad;
                                                    document.getElementById("AS_CI").dispatchEvent(new Event('input'));
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
                                                }
                                            }
                                        },
                                        error: function (xhr, status, error) {
                                            console.error('Error:', error);
                                            // Manejar el error aquí
                                        }
                                    });
                },
                error: function (err) {
                    overlay.style.display = 'none';
                }
            });
        }  else {

            console.log("buscar datos >>> ");
            var overlay = document.getElementById("overlay");
            overlay.style.display = 'flex';
            const dateParts = a.split('/');
            const year = dateParts[2];
            const month = dateParts[1].padStart(2, '0'); 
            const day = dateParts[0].padStart(2, '0'); 
            var fechaHace6Meses = year + '-' + month + '-' + day;
            document.getElementById("FECHA_INICIO_TRAMITE").value = fechaHace6Meses;
            document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));
            esSolicitante();
            var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
            var numeroDocumento = document.getElementById("AS_CI").value;
            var complemento = document.getElementById("AS_COMPLEMENTO").value;
            var fechaNacimiento = document.getElementById("AS_NACIMIENTO").value;
            if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {

                var requestData = {
                    "tipoDocumento": tipoDocumento,
                    "numeroDocumento": numeroDocumento,
                    "complemento": complemento,
                    "fechaNacimiento": fechaNacimiento,
                    
                };

                $.ajax({
                    dataType: 'json',
                    contentType: 'application/json',
                    type: 'POST',
                    data: JSON.stringify(requestData),
                    url: 'https://pruebas.gestora.bo/servicios/cenpersonas/api/v1/personasip/buscaPersonaRegistroDirectoSip',
                    success: function (datares) {
                        console.log("EL DATA >>> ", datares);
                        if (datares.codigoRespuesta == 0) {
                            if(datares.data.cua != null){
                                if (datares.data.fechaDefuncion != null){ 
                                    console.log('tiene cua ');
                                    ///////////------- validacion planilla 
                                    $.ajax({
                                        dataType: 'json',
                                        contentType: 'application/json',
                                        type: 'GET',
                                        url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-retiros/api/v1/tipoPago/verificacionPrestacion?cua='+datares.data.cua,
                                        success: function (dataPlanilla) {


                                            if(dataPlanilla.codigo !='204'){
                                            console.log('planillaas',dataPlanilla.data.tipo );
                                            const arrayTipos = dataPlanilla.data.tipo.split("/");
                                            console.log(arrayTipos);
                                            const buscar1 = 'PAGO CC';
                                            const buscar2 = 'GASTOS FUNERARIOS';
                                            const buscar3= 'INVALIDEZ';
                                            const buscar4= 'JUBILACION';
                                            const buscar5= 'MUERTE';
                                            $.ajax({
                                                dataType: 'json',
                                                contentType: 'application/json',
                                                type: 'GET',
                                                url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-calculos/api/v1/consultaPrevisionFuturo/solicitud?cua='+datares.data.cua,
                                                success: function (dataFuturo) {
                                                    console.log('dataFuturo ======>',dataFuturo);

                                                },
                                                error: function (xhr, status, error) {
                                                console.error('Error:', error);
                                                }
                                            });


                                            if (arrayTipos.includes(buscar2)){

                                                var listaMensajes = `<h1> El CUA: ${datares.data.cua} ya cuenta con el  de pago de Gastos Funerarios  <h1>`;
                                                overlay.style.display = 'none';
                                                var modal = document.getElementById("modalGenerico");
                                                var icono = document.querySelector(".icono-advertencia");
                                                icono.src = "img/advertencia_1.jpg"; 
                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                modalTitulo.textContent = "Trámites registrados";
                                                modalMensaje.innerHTML = listaMensajes ; 
                                                modal.style.display = "block";

                                            } else    if (arrayTipos.includes(buscar1)){ 

                                            
                                            $.ajax({
                                                dataType: 'json',
                                                contentType: 'application/json',
                                                type: 'GET',
                                                url: 'https://sgg.test.gestora.bo/otorgamiento-prestaciones-calculos/api/v1/consultaPrevisionFuturo/solicitud?cua='+datares.data.cua,
                                                success: function (dataFuturo) {
                                                    console.log('dataFuturo ======>',dataFuturo);

                                                },
                                                error: function (xhr, status, error) {
                                                console.error('Error:', error);
                                                }
                                            });

                                                var listaMensajes = `
                                                <h1> No puede registrar el trámite debido a que cuenta PAGO DE CC y es ≥ 60 años, corresponde que realice el trámite por el FRUV  </h1>
                                                    
                                                `;
                                                overlay.style.display = 'none';
                                                    var icono = document.querySelector(".icono-advertencia");
                                                    icono.src = "img/advertencia_1.jpg"; 
                                                var modal = document.getElementById("modalGenerico");
                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                var modalBoton = document.getElementById("modalGenerico-boton")
                                                modalTitulo.textContent = "Datos del Asegurado";
                                                modalMensaje.innerHTML = listaMensajes ;
                                                modalBoton.textContent = "Continuar"; 
                                                modal.style.display = "block";
                                            } else {
                                                console.log('tiene cua ');
                                                let fullUrl = window.location.href;
                                                const arrayUrl = fullUrl.split("/");
                                                const url_local = arrayUrl[0]+'//'+arrayUrl[2]; 
                                                var requestData = {
                                                    "cua": datares.data.cua
                                                };
                                                $.ajax({
                                                    dataType: 'json',
                                                    contentType: 'application/json',
                                                    type: 'POST',
                                                    data: JSON.stringify(requestData),
                                                    url:url_local+'/api/buscarTramitesGFU',
                                                    success: function (datares1) {
                                                        const cantidad = datares1.data.length;
                                                        if(cantidad>0){
                                                            var listado = '';
                                                            for (var j = 0; j < cantidad; j++) {
                                                                var li =  `<li> 
                                                                <b> ${datares1.data[j].cas_cod_id}</b>,<br> Estado : <b> ${datares1.data[j].descripcion}</b>    </li>`
                                                                listado = listado + li;
                                                            }
                                                            var listaMensajes = `<h1>No puede registrar trámite, debido a que cuenta con un trámite de Gastos Funerarios registrado previamente  <h1> <ul>     ${listado} </ul>`;
                                                            overlay.style.display = 'none';
                                                            var modal = document.getElementById("modalGenerico");
                                                            var icono = document.querySelector(".icono-advertencia");
                                                            icono.src = "img/advertencia_1.jpg"; 
                                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                            modalTitulo.textContent = "Trámites registrados";
                                                            modalMensaje.innerHTML = listaMensajes ; 
                                                            modal.style.display = "block";
                                                        } else {
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

                                                            console.log("volviendo de la funcion calcular >");
                                                                                                        
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



                                                            let respuesta = calcularMesesTranscurridos(datares.data.apiEstado, datares.data.fechaDefuncion,'NO');   
                                                            console.log("Respuesta contador >>> >>> ", respuesta);  
                                                            if( respuesta > 18  ){
                                                                console.log('mayor a 18 meses ');
                                                                document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                                                document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                                                document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                                                document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                                                document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_NACIMIENTO").value = '';
                                                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_APELLIDO_CASADA").value = '';
                                                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_CUA").value = value = '';
                                                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                                                const date = new Date(datares.data.fechaNacimiento);
                                                                const today  = new Date(); 
                                                                let diffYears = today.getFullYear() - date.getFullYear();
                                                                const cua = datares.data.cua;
                                                                if( diffYears > 60 ){
                                                                    let fullUrl2 = window.location.href;
                                                                    const arrayUrl2 = fullUrl2.split("/");
                                                                    const url_local2 = arrayUrl2[0]+'//'+arrayUrl2[2]; 
                                                                    var requestData = {
                                                                        "cas_cua": datares.data.cua
                                                                    };
                                                                    $.ajax({
                                                                        dataType: 'json',
                                                                        contentType: 'application/json',
                                                                        type: 'POST',
                                                                        data: JSON.stringify(requestData),
                                                                        url:url_local+'/api/boletasPendientesCobro',
                                                                        success: function (dataBoletosPago ) {
                                                                                console.log('datos de jub',dataBoletosPago);
                                                                            },
                                                                            error: function(xhr, status, error) {
                                                                                console.error('Error:', error);
                                                                            }
                                                                        });

                                                                        $.ajax({
                                                                        dataType: 'json',
                                                                        type: 'GET',
                                                                        url: 'https://sgg.test.gestora.bo/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c='+cua+'&user=fernando.flores@gestora.bo',
                                                                        success: function(response) {
                                                                            console.log('response ======>>>>>', response);
                                                                            if(response.codigoRespuesta == 200  ){
                                                                                console.log('es mayor a 55 =======>>>'); 
                                                                                var listaMensajes = `
                                                                                No puede registrar el trámite debido a que el Asegurado es mayor de 60 años de edad, corresponde que realice el trámite por el FRUV
                                                                                    <ul>
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
                                                                                modalMensaje.innerHTML = listaMensajes ;
                                                                                modalBoton.textContent = "Continuar"; 
                                                                                modal.style.display = "block";
                                                                            } else {
                                                                                var listaMensajes = `
                                                                                    <ul>
                                                                                    No puede registrar el trámite debido a que el Asegurado es mayor de 60 años de edad, corresponde que realice el trámite por el FRUV </li>
                                                                                    </ul>
                                                                                `;
                                                                                overlay.style.display = 'none';
                                                                                var modal = document.getElementById("modalGenerico");
                                                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                modalTitulo.textContent = "Datos del Asegurado";
                                                                                modalMensaje.innerHTML = listaMensajes ;
                                                                                modal.style.display = "block";
                                                                            }
                                                                        },
                                                                        error: function(xhr, status, error) {
                                                                            console.error('Error:', error);
                                                                            // Manejar el error aquí
                                                                        }
                                                                    });
                                                                } else {
                                                                    var listaMensajes = `   <h1>No se puede registrar la solicitud, debido a que trancurrió mas de 18 meses desde el fallecimiento <h1> `;
                                                                    overlay.style.display = 'none';
                                                                    var modal = document.getElementById("modalGenerico");
                                                                    var icono = document.querySelector(".icono-advertencia");
                                                                    icono.src = "img/advertencia_1.jpg"; 
                                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                    modalTitulo.textContent = "Trámites registrados";
                                                                    modalMensaje.innerHTML = listaMensajes ; 
                                                                    modal.style.display = "block";
                                                                    // <h1> Verifique que no cuente con una Jubilación pagada en una Entidad Aseguradora <h1> `; es para menor de 60 años 
                                                                }
                                                                overlay.style.display = 'none';
                                                            } else {
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

                                                                console.log("volviendo de la funcion calcular >");
                                                                                                            
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
                                                            }


                    
                                                
                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                console.error('Error:', error);
                                                // Manejar el error aquí
                                            }
                                        });


                                            }

                                            }else {
                                                console.log('tiene cua ');
                                                let fullUrl = window.location.href;
                                                const arrayUrl = fullUrl.split("/");
                                                const url_local = arrayUrl[0]+'//'+arrayUrl[2]; 
                                                var requestData = {
                                                    "cua": datares.data.cua
                                                };
                                                $.ajax({
                                                    dataType: 'json',
                                                    contentType: 'application/json',
                                                    type: 'POST',
                                                    data: JSON.stringify(requestData),
                                                    url:url_local+'/api/buscarTramitesGFU',
                                                    success: function (datares1) {
                                                        const cantidad = datares1.data.length;
                                                        if(cantidad>0){
                                                            var listado = '';
                                                            for (var j = 0; j < cantidad; j++) {
                                                                var li =  `<li> 
                                                                <b> ${datares1.data[j].cas_cod_id}</b>,<br> Estado : <b> ${datares1.data[j].descripcion}</b>    </li>`
                                                                listado = listado + li;
                                                            }
                                                            var listaMensajes = `<h1>No puede registrar trámite, debido a que cuenta con un trámite de Gastos Funerarios registrado previamente  <h1> <ul>     ${listado} </ul>`;
                                                            overlay.style.display = 'none';
                                                            var modal = document.getElementById("modalGenerico");
                                                            var icono = document.querySelector(".icono-advertencia");
                                                            icono.src = "img/advertencia_1.jpg"; 
                                                            var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                            var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                            modalTitulo.textContent = "Trámites registrados";
                                                            modalMensaje.innerHTML = listaMensajes ; 
                                                            modal.style.display = "block";
                                                        } else {
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

                                                            console.log("volviendo de la funcion calcular >");
                                                                                                        
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



                                                            let respuesta = calcularMesesTranscurridos(datares.data.apiEstado, datares.data.fechaDefuncion, 'NO');   
                                                            console.log("Respuesta contador >>> >>> ", respuesta);  
                                                            if( respuesta > 18  ){
                                                                console.log('mayor a 18 meses ');
                                                                document.getElementById("AS_PRIMER_NOMBRE").value = '';
                                                                document.getElementById("AS_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_SEGUNDO_NOMBRE").value = '';
                                                                document.getElementById("AS_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_PRIMER_APELLIDO").value = '';
                                                                document.getElementById("AS_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_SEGUNDO_APELLIDO").value = '';
                                                                document.getElementById("AS_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_NACIMIENTO").value = '';
                                                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_APELLIDO_CASADA").value = '';
                                                                document.getElementById("AS_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                                                                document.getElementById("AS_CUA").value = value = '';
                                                                document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
                                                                const date = new Date(datares.data.fechaNacimiento);
                                                                const today  = new Date(); 
                                                                let diffYears = today.getFullYear() - date.getFullYear();
                                                                const cua = datares.data.cua;
                                                                if( diffYears > 60 ){
                                                                    let fullUrl2 = window.location.href;
                                                                    const arrayUrl2 = fullUrl2.split("/");
                                                                    const url_local2 = arrayUrl2[0]+'//'+arrayUrl2[2]; 
                                                                    var requestData = {
                                                                        "cas_cua": datares.data.cua
                                                                    };
                                                                    $.ajax({
                                                                        dataType: 'json',
                                                                        contentType: 'application/json',
                                                                        type: 'POST',
                                                                        data: JSON.stringify(requestData),
                                                                        url:url_local+'/api/boletasPendientesCobro',
                                                                        success: function (dataBoletosPago ) {
                                                                                console.log('datos de jub',dataBoletosPago);
                                                                            },
                                                                            error: function(xhr, status, error) {
                                                                                console.error('Error:', error);
                                                                            }
                                                                        });

                                                                        $.ajax({
                                                                        dataType: 'json',
                                                                        type: 'GET',
                                                                        url: 'https://sgg.test.gestora.bo/compensacion-cotizacion/api/v1/calculosPrevios/obtenerFechaCargaCC?c='+cua+'&user=fernando.flores@gestora.bo',
                                                                        success: function(response) {
                                                                            console.log('response ======>>>>>', response);
                                                                            if(response.codigoRespuesta == 200  ){
                                                                                console.log('es mayor a 55 =======>>>'); 
                                                                                var listaMensajes = `
                                                                                No puede registrar el trámite debido a que el Asegurado es mayor de 60 años de edad, corresponde que realice el trámite por el FRUV
                                                                                    <ul>
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
                                                                                modalMensaje.innerHTML = listaMensajes ;
                                                                                modalBoton.textContent = "Continuar"; 
                                                                                modal.style.display = "block";
                                                                            } else {
                                                                                var listaMensajes = `
                                                                                    <ul>
                                                                                    No puede registrar el trámite debido a que el Asegurado es mayor de 60 años de edad, corresponde que realice el trámite por el FRUV </li>
                                                                                    </ul>
                                                                                `;
                                                                                overlay.style.display = 'none';
                                                                                var modal = document.getElementById("modalGenerico");
                                                                                var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                                var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                                modalTitulo.textContent = "Datos del Asegurado";
                                                                                modalMensaje.innerHTML = listaMensajes ;
                                                                                modal.style.display = "block";
                                                                            }
                                                                        },
                                                                        error: function(xhr, status, error) {
                                                                            console.error('Error:', error);
                                                                            // Manejar el error aquí
                                                                        }
                                                                    });
                                                                } else {
                                                                    var listaMensajes = `   <h1>No se puede registrar la solicitud, debido a que trancurrió mas de 18 meses desde el fallecimiento <h1> `;
                                                                    overlay.style.display = 'none';
                                                                    var modal = document.getElementById("modalGenerico");
                                                                    var icono = document.querySelector(".icono-advertencia");
                                                                    icono.src = "img/advertencia_1.jpg"; 
                                                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                                                    modalTitulo.textContent = "Trámites registrados";
                                                                    modalMensaje.innerHTML = listaMensajes ; 
                                                                    modal.style.display = "block";
                                                                    // <h1> Verifique que no cuente con una Jubilación pagada en una Entidad Aseguradora <h1> `; es para menor de 60 años 
                                                                }
                                                                overlay.style.display = 'none';
                                                            } else {
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

                                                                console.log("volviendo de la funcion calcular >");
                                                                                                            
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
                                                            }


                    
                                                
                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                console.error('Error:', error);
                                                // Manejar el error aquí
                                            }
                                        });

                                            }


                                        //////////////////////////////////////////////////
                                        },
                                        error: function (xhr, status, error) {
                                            console.error('Error:', error);
                                            // Manejar el error aquí
                                        }
                                    });

                                    ///////-----------------------------

                                        





                            


                                } else { // tiene fecha de fallecimiento 
                                    overlay.style.display = 'none';
                                    var listaMensajes = `<p> <h1>No se cuenta con el registro de la fecha de fallecimiento, favor registre la misma de forma previa, a través del Registro de Novedades</h1> </p>`;
                                    var modal = document.getElementById("modalGenerico");
                                    var modalTitulo = document.getElementById("modalGenerico-titulo");
                                    var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                    var icono = document.querySelector(".icono-advertencia");
                                    icono.src = "img/advertencia_1.jpg"; 
                                    modalTitulo.textContent = "DATOS DEL ASEGURADO";
                                    modalMensaje.innerHTML = listaMensajes ;
                                    modal.style.display = "block";
                                }
                        


                            } else {
                                    console.log('nortnes cua');

                                    const date = new Date(datares.data.fechaNacimiento);
                                        overlay.style.display = 'none';
                                        var modal = document.getElementById("modalGenerico");
                                        var icono = document.querySelector(".icono-advertencia");
                                        icono.src = "img/aviso_inportante.jpg";
                                        var modalTitulo = document.getElementById("modalGenerico-titulo");
                                        var modalMensaje = document.getElementById("modalGenerico-mensaje");
                                        modalTitulo.textContent = "Datos del Asegurado";
                                        //  modalMensaje.textContent = "no es beneficiario del pago de gastos funerales ni gastos funerarios ";
                                        modalMensaje.innerHTML = `
                                        <h2 style="text-align: center;">
                                        
                                        Causante no tiene CUA., ingrese fecha de fallecimiento  </h2>`;
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














                    } else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {

                        if (complemento === "") {
                            document.getElementById("AS_NACIMIENTO").disabled = false;
                            if (fechaNacimiento !== "") {
                                document.getElementById("AS_NACIMIENTO").value = fechaNacimiento;
                                document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                            }
                            else {
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
                    overlay.style.display = 'none';
                    // Manejar errores de la solicitud AJAX
                }
            });

        } else {
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



                        $.ajax({
                            dataType: 'json',
                            contentType: 'application/json',
                            type: 'GET',
                            data: {},
                            url: 'https://sgg.test.gestora.bo/reportes-gesip/api/v1/prestaciones/solicitudGF?numero_documento='+numeroDocumento,
                            success: function (datares2) {

    if (!datares2.data){
        const mensaje =  `<h2 style="text-align: center;">  ${datares2.mensaje} </h2>`;
        const icono_imagen = "img/aviso_inportante.jpg";
        const cabecera = "Datos del Asegurado";
        modalMensajeDinamico(mensaje,icono_imagen,cabecera);

    } else {
    

        

    }

                            


                            },  
                            error: function (err) {
                                overlay.style.display = 'none';
                                // Manejar errores de la solicitud AJAX
                            }
                        });
                        overlay.style.display = 'none';
                        //document.getElementById("TIENE_PODER_SOL_1").addEventListener("change", controlarVisibilidadCampos);
                        //controlarVisibilidadCampos();                
                        document.getElementById("SOL_PRIMER_NOMBRE").value = datares.data.primerNombre;
                        document.getElementById("SOL_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                        document.getElementById("SOL_SEGUNDO_NOMBRE").value = datares.data.segundoNombre || "";
                        document.getElementById("SOL_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                        document.getElementById("SOL_PRIMER_APELLIDO").value = datares.data.primerApellido;
                        document.getElementById("SOL_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                        document.getElementById("SOL_SEGUNDO_APELLIDO").value = datares.data.segundoApellido || "";
                        document.getElementById("SOL_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                        document.getElementById("SOL_APELLIDO_CASADA").value = datares.data.solApellidoCasada || "";
                        document.getElementById("SOL_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                        document.getElementById("SOL_NACIMIENTO").value = datares.data.fechaNacimiento;
                        document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));                 
                        document.getElementById("SOL_IDPERSONA").value = datares.data.idPersonaSip || "";
                        document.getElementById("SOL_IDPERSONA").dispatchEvent(new Event('input'));
                        if (datares.data.idGenero === 'M') {
                            document.getElementById("SOL_GENERO").value = 'MASCULINO';
                        } else {
                            document.getElementById("SOL_GENERO").value = 'FEMENINO';
                        }
                        document.getElementById("SOL_GENERO").dispatchEvent(new Event('input'));
                                    
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
                        document.getElementById("SOL_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                        document.getElementById("SOL_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                        if (datares.data.complemento !== null) {
                            document.getElementById("SOL_COMPLEMENTO").value = datares.data.complemento || "";
                            document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
                        } else {
                            document.getElementById("SOL_COMPLEMENTO").value = "";
                            document.getElementById("SOL_COMPLEMENTO").dispatchEvent(new Event('input'));
                        }
                        if (datares.data.apiEstado === 'FALLECIDO') {
                            overlay.style.display = 'none';
                            alert('!ALERTA!. El solicitante es registrado como FALLECIDO.');                     
                            limpiarFormularioSol();
                            return; // Salir de la función para evitar más procesamiento
                        }else{} 
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
                        document.getElementById("SOL_FAC_REC").value = datares.data.solFacturaRecibo || "";
                        document.getElementById("SOL_FAC_REC").dispatchEvent(new Event('input'));
                        document.getElementById("SOL_NRO_FAC_REC").value = datares.data.solNroFacturaRecibo || "";
                        document.getElementById("SOL_NRO_FAC_REC").dispatchEvent(new Event('input'));                  
                        document.getElementById("SOL_CODIGO_VER_FAC_REC").value = datares.data.solCodFacRec || "";
                        document.getElementById("SOL_CODIGO_VER_FAC_REC").dispatchEvent(new Event('input'));
                        document.getElementById("DOC_FACTURA_RECIBO").value = datares.data.docFacturaRecibo || "";
                        document.getElementById("DOC_FACTURA_RECIBO").dispatchEvent(new Event('input'));
                        document.getElementById("NOTA_ACLARATORIA").value = datares.data.notaAclaratoria || "";
                        document.getElementById("NOTA_ACLARATORIA").dispatchEvent(new Event('input'));
                        document.getElementById("VALIDAR_PODER").value = datares.data.validarPoder || "";
                        document.getElementById("VALIDAR_PODER").dispatchEvent(new Event('input'));
                        document.getElementById("FECHA_REVISION").value = datares.data.fechaRevision || "";
                        document.getElementById("FECHA_REVISION").dispatchEvent(new Event('input'));                  

                        // document.getElementById("SOL_FAC_REC").addEventListener("change", ocultarGrilla);

                    

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
                            //     var modal = document.getElementById("modalGenerico");
    var icono = document.querySelector(".icono-advertencia");
                icono.src = "img/aviso_inportante.jpg";
                            //     var modalTitulo = document.getElementById("modalGenerico-titulo");
                            //     var modalMensaje = document.getElementById("modalGenerico-mensaje");
                            //       modalTitulo.textContent = "Datos del Solicitante";
                            //       modalMensaje.textContent = "Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.";
                            //     modal.style.display = "block";
                            //     return;
                            }
                        } else {

                            document.getElementById("SOL_COMPLEMENTO").value = complemento;

                        }
                    }
                    
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

    function verDatosCob() {
        var overlay = document.getElementById("overlay");
        overlay.style.display = 'flex';
            var tipoDocumento = document.getElementById("COB_TIPO_DOCUMENTO").value;
            var numeroDocumento = document.getElementById("COB_CI").value;
            var complemento = document.getElementById("COB_COMPLEMENTO").value;
            var fechaNacimiento = document.getElementById("COB_NACIMIENTO").value;
            if (tipoDocumento !== "" && numeroDocumento !== "" || complemento !== "" || fechaNacimiento !== "") {
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
                            document.getElementById("COB_PRIMER_NOMBRE").value = datares.data.primerNombre;
                            document.getElementById("COB_PRIMER_NOMBRE").dispatchEvent(new Event('input'));
                            document.getElementById("COB_SEGUNDO_NOMBRE").value = datares.data.segundoNombre || "";
                            document.getElementById("COB_SEGUNDO_NOMBRE").dispatchEvent(new Event('input'));
                            document.getElementById("COB_PRIMER_APELLIDO").value = datares.data.primerApellido;
                            document.getElementById("COB_PRIMER_APELLIDO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_SEGUNDO_APELLIDO").value = datares.data.segundoApellido || "";
                            document.getElementById("COB_SEGUNDO_APELLIDO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_APELLIDO_CASADA").value = datares.data.solApellidoCasada || "";
                            document.getElementById("COB_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                            document.getElementById("COB_NACIMIENTO").value = datares.data.fechaNacimiento;
                            document.getElementById("COB_NACIMIENTO").dispatchEvent(new Event('input'));                 
                            if (datares.data.idGenero === 'M') {
                                document.getElementById("COB_GENERO").value = 'MASCULINO';
                            } else {
                                document.getElementById("COB_GENERO").value = 'FEMENINO';
                            }
                            document.getElementById("COB_IDPERSONA").value = datares.data.idPersonaSip || "";
                            document.getElementById("COB_IDPERSONA").dispatchEvent(new Event('input'));

                            document.getElementById("COB_GENERO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_DIRECCION").value = datares.data.direccion || "";
                            document.getElementById("COB_DIRECCION").dispatchEvent(new Event('input'));
                            document.getElementById("COB_NUM").value = datares.data.numero || "";
                            document.getElementById("COB_NUM").dispatchEvent(new Event('input'));
                            document.getElementById("COB_TELEFONO").value = datares.data.telefonoFijo || "";
                            document.getElementById("COB_TELEFONO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_CELULAR").value = datares.data.telefonoCelular || "";
                            document.getElementById("COB_CELULAR").dispatchEvent(new Event('input'));
                            document.getElementById("COB_POSTAL").value = datares.data.casillaPostal || "";
                            document.getElementById("COB_POSTAL").dispatchEvent(new Event('input'));
                            document.getElementById("COB_CORREO").value = datares.data.correoElectronico || "";
                            document.getElementById("COB_CORREO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_APELLIDO_CASADA").value = datares.data.apellidoCasada || "";
                            document.getElementById("COB_APELLIDO_CASADA").dispatchEvent(new Event('input'));
                            if (datares.data.complemento !== null) {
                                document.getElementById("COB_COMPLEMENTO").value = datares.data.complemento || "";
                                document.getElementById("COB_COMPLEMENTO").dispatchEvent(new Event('input'));
                            } else {
                                document.getElementById("COB_COMPLEMENTO").value = "";
                                document.getElementById("COB_COMPLEMENTO").dispatchEvent(new Event('input'));
                            }
                            if (datares.data.apiEstado === 'FALLECIDO') {
                                overlay.style.display = 'none';
                                alert('!ALERTA!. El solicitante es registrado como FALLECIDO.');                     
                                limpiarFormularioSol();
                                return; // Salir de la función para evitar más procesamiento
                            }else{} 
                            document.getElementById("COB_PARENTESCO").value = datares.data.descripcion || "";
                            document.getElementById("COB_PARENTESCO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_CIUDAD").value = datares.data.ciudad || "";
                            document.getElementById("COB_CIUDAD").dispatchEvent(new Event('input'));
                            document.getElementById("COB_PROVINCIA").value = datares.data.provincia || "";
                            document.getElementById("COB_PROVINCIA").dispatchEvent(new Event('input'));
                            document.getElementById("COB_DEPARTAMENTO").value = datares.data.departamento || "";
                            document.getElementById("COB_DEPARTAMENTO").dispatchEvent(new Event('input'));
                            document.getElementById("COB_ZONA").value = datares.data.zona || "";
                            document.getElementById("COB_ZONA").dispatchEvent(new Event('input'));                 
                            document.getElementById("COB_FAC_REC").value = datares.data.solFacturaRecibo || "";
                            document.getElementById("COB_FAC_REC").dispatchEvent(new Event('input'));
                            document.getElementById("COB_NRO_FAC_REC").value = datares.data.solNroFacturaRecibo || "";
                            document.getElementById("COB_NRO_FAC_REC").dispatchEvent(new Event('input'));                  
                        }
                        else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {
                            if (complemento === "") {
                                document.getElementById("SOL_NACIMIENTO").disabled = false;
                                if (fechaNacimiento !== "") {
                                    document.getElementById("SOL_NACIMIENTO").value = fechaNacimiento
                                    document.getElementById("SOL_NACIMIENTO").dispatchEvent(new Event('input'));
                                } else {
                                    overlay.style.display = 'none';
                                    alert("Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.");
                                    return;
                                        var icono = document.querySelector(".icono-advertencia");
                                        icono.src = "img/aviso_inportante.jpg";
                                }
                            } else {
                                document.getElementById("SOL_COMPLEMENTO").value = complemento;
                            }
                        } else {
                            limpiarFormularioSol();
                        }
                        console.log(datares);
                    },
                    error: function (err) {
                        overlay.style.display = 'none';
                    }
                });
            } else {
                overlay.style.display = 'none';
                alert("Debe seleccionar tipo documento y completar el campo CI para realizar la búsqueda.");
            }
        }

    function limpiarFormulario() {

        console.log('genera limpiar ');
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
        document.getElementById("AS_IDPERSONA").value = "";
        document.getElementById("AS_IDPERSONA").dispatchEvent(new Event('input'));
        document.getElementById("AS_API_ESTADO").value = "";
        document.getElementById("AS_API_ESTADO").dispatchEvent(new Event('input'));
        document.getElementById("AS_COMPLEMENTO").value = "";
        document.getElementById("AS_COMPLEMENTO").dispatchEvent(new Event('input'));
        document.getElementById("AS_NACIMIENTO").value = "";
        document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));  


        document.getElementById("AS_MESES_FALLECIMIENTO").value = "";
        document.getElementById("AS_MESES_FALLECIMIENTO").dispatchEvent(new Event('input'));  

        
        document.getElementById("AS_FECHA_FALLECIMIENTO").value = "";
        document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
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
        document.getElementById("SOL_GENERO").value = "";
        document.getElementById("SOL_GENERO").dispatchEvent(new Event('input'));  
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
        document.getElementById("SOL_FAC_REC").value = "";
        document.getElementById("SOL_FAC_REC").dispatchEvent(new Event('input'));
        document.getElementById("SOL_NRO_FAC_REC").value = "";
        document.getElementById("SOL_NRO_FAC_REC").dispatchEvent(new Event('input'));  
        document.getElementById("SOL_CODIGO_VER_FAC_REC").value = "";
        document.getElementById("SOL_CODIGO_VER_FAC_REC").dispatchEvent(new Event('input'));
        document.getElementById("NOTA_ACLARATORIA").value = "";
        document.getElementById("NOTA_ACLARATORIA").dispatchEvent(new Event('input'));
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
                console.log(idInput,'entro aqui V2 ',idElemento );
                limpiarFuncion();
            });
        } else {
            // console.error("Elemento " + idInput + " no encontrado en el DOM.");
        }
    }

    ///agregarEventListener("AS_CI", "AS_NACIMIENTO", limpiarFormulario);
    ///agregarEventListener("SOL_CI", "SOL_NACIMIENTO", limpiarFormularioSol);

    (function () {
        cargarParentesco();
        cargarParentescoCobrador();


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
                        option.value = datos[i].codigo;
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

    function cargarParentescoCobrador() {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'https://sipre.gestora.bo/spr-tram-rest/clasificador/tiposParentesco',
            success: function (response) {
                var selectParentesco = document.getElementById("COB_PARENTESCO");
                if (selectParentesco.options.length === 1) {
                    var datos = response.data;
                    for (var i = 0; i < datos.length; i++) {
                        var option = document.createElement("option");
                        option.value = datos[i].codigo;
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

    (function () {
        getCiudades();
        getCiudadesCobrador();

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

    function getCiudadesCobrador() {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'https://oficinavirtualservicios.gestora.bo/api/General/Ciudad',
            success: function (ciudades) {
                var selectCiudadesCOB = document.getElementById("COB_CIUDAD");
                if (selectCiudadesCOB.options.length === 1) {
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
                        selectCiudadesCOB.appendChild(option);
                    }
                    var dataFrmValue = selectCiudadesCOB.getAttribute("data-frm-value");
                    if (dataFrmValue !== undefined && dataFrmValue !== null) {
                        console.log('ingreso por la ocion ====>  setProvinciaDepartamentoCOB' );

                        selectCiudadesCOB.value = dataFrmValue;
                        setProvinciaDepartamentoCOB();
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                // Manejar el error aquí
            }
        });
    }

    function setProvinciaDepartamentoCOB() {
        console.log('ingreso por la ocion ====>  setProvinciaDepartamentoCOB' );

        var ciudadSeleccionada = $("#COB_CIUDAD").val();
        console.log('ingreso por la ocion ====>',ciudadSeleccionada );
        console.log('ingreso por la ocion ====>' );


    if(ciudadSeleccionada !== null && ciudadSeleccionada !== '') {
        console.log('ingreso por la ocion ====>',ciudadSeleccionada );
        var datosProvinciaDepartamento = $("#COB_CIUDAD option:selected").data("provincia-departamento");
        var datos = datosProvinciaDepartamento.split(";");
        //PROVINCIA
        var selectCiudades = document.getElementById("COB_PROVINCIA");
        selectCiudades.innerHTML = "";
        var option = document.createElement("option");
        option.value = datos[0];
        option.text = datos[1];
        selectCiudades.appendChild(option);
        document.getElementById("COB_PROVINCIA").value = datos[0];
        document.getElementById("COB_PROVINCIA").dispatchEvent(new Event('change'));
        //DEPARTAMENTO
        var selectCiudades = document.getElementById("COB_DEPARTAMENTO");
        selectCiudades.innerHTML = "";
        var option = document.createElement("option");
        option.value = datos[2];
        option.text = datos[3];
        selectCiudades.appendChild(option);
        document.getElementById("COB_DEPARTAMENTO").value = datos[2];
        document.getElementById("COB_DEPARTAMENTO").dispatchEvent(new Event('change'));
    } else {
        document.getElementById("COB_PROVINCIA").value = "";
        document.getElementById("COB_PROVINCIA").dispatchEvent(new Event('input'));
        document.getElementById("COB_DEPARTAMENTO").value = "";
        document.getElementById("COB_DEPARTAMENTO").dispatchEvent(new Event('input'));
    }
    }
    function setDate() {
        var fecha = new Date();
        var dia = addCero(fecha.getDate());
        var mes = addCero(fecha.getMonth() + 1);
        var anio = fecha.getFullYear();
    
        var fechaHoy = anio + '-' + mes + '-' + dia;
    
        var inputFechaInicio = document.getElementById("FECHA_INICIO_TRAMITE");
        console.log('fechaHoy',fechaHoy); 
        //inputFechaInicio.value = fechaHoy;
            document.getElementById("FECHA_INICIO_TRAMITE").value = fechaHoy;
        inputFechaInicio.dispatchEvent(new Event('input')); 
    }
    
    function addCero(numero) {
        return numero < 10 ? '0' + numero : numero;
    }

    function obligarPoder()
    {
    console.log("obligarPoder");
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

    (function () {
    // fechaActual();
    setDate();
    })();

    function fechaActual()
    {
    console.log("aquii");
    var fecha = new Date();
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1; // Se agrega 1 porque los meses comienzan desde 0
    var anio = fecha.getFullYear();

    // Agregar ceros a la izquierda si el día o el mes es menor que 10
    if (dia < 10) {
        dia = '0' + dia;
    }
    if (mes < 10) {
        mes = '0' + mes;
    }

    var fechaHoy = anio  + '-' + mes + '-' +dia ;
    
    //document.getElementById("FECHA_INICIO_TRAMITE").value = fechaHoy;  
    //document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));

    }

    // Esta función se llama cada vez que cambia la selección en el componente "SOL_FAC_REC"
    function ocultarGrilla() {

    let modal = document.getElementById("modalGenerico");
            
        
        var seleccion = document.getElementById("SOL_FAC_REC").value;
        var grilla = document.getElementById("GRILLA_DERECHOHABIENTES_idd");

        if (seleccion === "FACTURA") {
            _hide("SUBTITULO_4_idd");
            _hide("GRILLA_DERECHOHABIENTES_idd");

                    
            // Oculta la grilla si se selecciona "FACTURA"
        } else {
            _show("SUBTITULO_4_idd");
            _show("GRILLA_DERECHOHABIENTES_idd");


                    var icono = document.querySelector(".icono-advertencia");
                        icono.src = "img/valido.png";
                        let modalTitulo = document.getElementById("modalGenerico-titulo");
                        let modalMensaje = document.getElementById("modalGenerico-mensaje");
                        var modalCerrarBtn = document.getElementById("modalGenerico-boton");
                                                        
                        modalCerrarBtn.textContent = "Continuar";
                        modalTitulo.textContent = "Trámite valido";
                        modalMensaje.innerHTML = `
                        
                        <h1>De acuerdo a la RA. Nro. 467/2019, Art. 62, parágrafo II, se requiere la declaracion jurada de  dos testigos</h1>`;
                        modal.style.display = "block";  
            // Muestra la grilla para cualquier otra opción
        }
    }

    function calcularDiferenciaYAlerta() {

        console.log("calendario >>>");
        let fechaInicioTramite = document.getElementById("FECHA_INICIO_TRAMITE").value;
        fechaInicioTramite = new Date(fechaInicioTramite);

        // Obtener la fecha actual
        let fechaFallecimiento = document.getElementById("AS_FECHA_FALLECIMIENTO");
        let fechaFallecimiento1 = new Date(fechaFallecimiento.value);

        fechaFallecimiento.onblur = terminaSeleccion;

        function terminaSeleccion() {

                console.log("ya dentro de la funcion de calcular");
                // Calcular la diferencia en meses
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
                let totalMeses = diferenciaAnios * 12 + diferenciaMeses;
                
                if (totalMeses < 0) {
                    totalMeses *= -1;
                }
                console.log("### TOTAL >>> ", totalMeses);

                if (totalMeses >= 18 && diferenciaDias != 0) {
                    document.getElementById("FECHA_SUPERA_6").value = "true";
                    document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                    alert(" No puede registrar el tramite debido  Ha transcurrido más de 18 meses desde la fecha de fallecimiento.");
                    _hide("#tabla_solicitante_idd");
                    _show("");
                    return;
                }
                if(totalMeses >= 6) {
                    document.getElementById("FECHA_SUPERA_6").value = "true";
                    document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));
                    _hide("SOL_FAC_REC_idd");
                    _hide("SOL_NRO_FAC_REC_idd");
                    _hide("DOC_FACTURA_RECIBO_idd");
                    _hide("SOL_CODIGO_VER_FAC_REC_idd");
                    _hide("NOTA_ACLARATORIA_idd");
                    _hide("SUBTITULO_4_idd");
                    _hide("GRILLA_DERECHOHABIENTES_idd");

                    let modal = document.getElementById("modalGenerico");
                    var icono = document.querySelector(".icono-advertencia");
                    icono.src = "img/aviso_inportante.jpg";
                    let modalTitulo = document.getElementById("modalGenerico-titulo");
                    let modalMensaje = document.getElementById("modalGenerico-mensaje");
                    modalTitulo.textContent = "Datos del Asegurado";
                    modalMensaje.textContent = "Han transcurrido más de 6 meses desde la fecha de fallecimiento hasta la fecha de inicio de trámite.";
                    modal.style.display = "block";

                } else {
                    document.getElementById("FECHA_SUPERA_6").value = "false";
                    document.getElementById("FECHA_SUPERA_6").dispatchEvent(new Event('input'));

                    _show("SOL_FAC_REC_idd");
                    _show("SOL_NRO_FAC_REC_idd");
                    _show("DOC_FACTURA_RECIBO_idd");
                    _show("SOL_CODIGO_VER_FAC_REC_idd");
                    _show("NOTA_ACLARATORIA_idd");
                } 
        }
    }

    function llenaCVEAP()
    {
    var a = document.getElementById("AS_TIPO_EAP").value;
    if ( a.includes('CVEAP-A') ) {
        _show("AS_OBS_CVAP_1_id");
        _show("AS_OBS_CVAP_2_id");
        _show("AS_OBS_CVAP_3_id");
        _show("AS_OBS_CVAP_4_id");
        _show("AS_OBS_CVAP_5_id");
        _show("SUBTITULO_6_id");
    } else {
        _hide("AS_OBS_CVAP_1_id");
        _hide("AS_OBS_CVAP_2_id");
        _hide("AS_OBS_CVAP_3_id");
        _hide("AS_OBS_CVAP_4_id");
        _hide("AS_OBS_CVAP_5_id");
        _hide("SUBTITULO_6_id");
        _hide("PENS_NO_COB_AS_FALLECIDO_id");
        _hide("PENS_NO_COB_DH_FALLECIDO_id");

    }
    }