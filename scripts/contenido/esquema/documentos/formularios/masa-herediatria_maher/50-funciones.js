(function () {
    estadoDerivacion();
})();

(function () {
    mostrarPensiones2();   
})();
function mostrarPensiones2() {
    document.querySelector('#AS_TIPO_EAP').required = false;
    _disable("AS_TIPO_EAP");
  
}
(function() {
    fechaActual2();
    })();  
    
(function() {
    cargarParentesco();
    })();    
  
function esSolicitante(){
    var valor = document.getElementById("SOL_DIFERENTE_AS").checked;
    console.log("SOL_DIFERENTE_AS: ", valor);
    if(!valor){}
}
   
function esTercerGrado(rowIndex) {
  
    var b= document.getElementById("DH_PARENTESCO" + rowIndex).value;
  const palabras = b.split("-");	
  const elemento = document.getElementById("DH_GRADO" + rowIndex);
  if(palabras[0]=="3")
  {
    elemento.style.display = "block"; // Ocultar el elemento
    console.log("so");		
  
  }
  else
  {
    elemento.style.display = "none"; // Ocultar el elemento
  }
    
  } 
  function verDatosDh(rowIndex) {
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
                    //   document.getElementById("DH_DIRECCION"+ rowIndex).value = datares.data.direccion ;
                    // document.getElementById("DH_DIRECCION"+ rowIndex).dispatchEvent(new Event('input'));
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
                        // document.getElementById("DH_DIRECCION"  + rowIndex).value = "";
                        // document.getElementById("DH_DIRECCION" + rowIndex).dispatchEvent(new Event('input'));
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
  
  
  function verDatos() {
    document.getElementById("SOL_DIFERENTE_AS").checked = false;
    esSolicitante();
  
    var tipoDocumento = document.getElementById("AS_TIPO_DOCUMENTO").value;
    var numeroDocumento = document.getElementById("AS_CI").value;
    var complemento = document.getElementById("AS_COMPLEMENTO").value;
    var fechaNacimiento = document.getElementById("AS_NACIMIENTO").value;
  
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
                    // Actualizar campos del formulario con los datos recibidos
                document.getElementById("TIENE_PODER_SOL_1").value = "2";
                controlarVisibilidadCampos();  
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
  
                    if (datares.data.idGenero === 'M') {
                        document.getElementById("AS_GENERO").value = 'MASCULINO';

                    } else {
                        document.getElementById("AS_GENERO").value = 'FEMENINO';
          
                    }
                    document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
                    // document.getElementById("AS_ESTADO_CIVIL").value = datares.data.idEstadoCivil;
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
                    document.getElementById("FECHA_DE_REGISTRO").value = datares.data.fechaRegistroTramite || "";
                    document.getElementById("FECHA_DE_REGISTRO").dispatchEvent(new Event('input'));  
                    document.getElementById("FECHA_INICIO_TRAMITE").value = datares.data.fechaInicioTramite || "";
                    document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));                                                         
               
                }
                
                else if (datares.codigoRespuesta == 1 || datares.codigoRespuesta == 3) {
                    
                    if (complemento === "") {
                        document.getElementById("AS_NACIMIENTO").disabled = false;
                        if (fechaNacimiento !== "") {
                            document.getElementById("AS_NACIMIENTO").value = fechaNacimiento;
                            document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
                        }
                        else {
                            alert("Debe ingresar el complemento o la fecha de nacimiento para continuar la búsqueda.");
                            return;
                        }
                    } else {
  
                        document.getElementById("AS_COMPLEMENTO").value =datares.data.complemento;      
                     
                    }
                } 
                //  else if (datares.codigoRespuesta === "2000") {
                //     alert("No se encontraron registros con la información proporcionada.");
                //     return;
                // } 
                else {
  
                    limpiarFormulario();
  
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
  
                    if (datares.data.apiEstado === 'FALLECIDO') {
                        alert('!ALERTA!. El solicitante es registrado como FALLECIDO.');                     
                        limpiarFormularioSol();
                        return; // Salir de la función para evitar más procesamiento
                    }else{} } 
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
                // Manejar errores de la solicitud AJAX
            }
        });
    } else {
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
    document.getElementById("AS_FECHA_FALLECIMIENTO").value = "";
    document.getElementById("AS_FECHA_FALLECIMIENTO").dispatchEvent(new Event('input'));
    document.getElementById("AS_NACIMIENTO").value = "";
    document.getElementById("AS_NACIMIENTO").dispatchEvent(new Event('input'));
    document.getElementById("AS_CUA").value = "";
    document.getElementById("AS_CUA").dispatchEvent(new Event('input'));
    document.getElementById("AS_GENERO").value = "";
    document.getElementById("AS_GENERO").dispatchEvent(new Event('input'));
    document.getElementById("AS_ESTADO_CIVIL").value = "";
    document.getElementById("AS_ESTADO_CIVIL").dispatchEvent(new Event('input'));
    // document.getElementById("AS_ENTE_GESTOR").value = "";
    // document.getElementById("AS_ENTE_GESTOR").dispatchEvent(new Event('input'));
    
   
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
    document.getElementById("AS_OBS_CVAP_4").value =  "";
    document.getElementById("AS_OBS_CVAP_4").dispatchEvent(new Event('input'));  
    document.getElementById("AS_OBS_CVAP_5").value = "";
    document.getElementById("AS_OBS_CVAP_5").dispatchEvent(new Event('input'));     
    document.getElementById("FECHA_DE_REGISTRO").value = "";
    document.getElementById("FECHA_DE_REGISTRO").dispatchEvent(new Event('input')); 
    document.getElementById("FECHA_INICIO_TRAMITE").value =  "";
    document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));  
  
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
}


function controlarVisibilidadCampos() {
    var tienePoder = document.getElementById("TIENE_PODER_SOL_1").value;
    var camposAocultar = ["NRO_PODER_SOL_1", "NRO_NOTARIA_SOL_1", "NOMBRE_NOTARIO_SOL_1"];

    if (tienePoder === "2") { // Si la opción seleccionada es "NO"
        camposAocultar.forEach(function(campo) {
            document.getElementById(campo).style.display = "none"; // Ocultar el campo
            document.getElementById(campo).removeAttribute("required"); // Quitar la propiedad "required"
        });
    } else { // Si la opción seleccionada es "SI" u otra
        camposAocultar.forEach(function(campo) {
            document.getElementById(campo).style.display = "block"; // Mostrar el campo
            document.getElementById(campo).setAttribute("required", "true"); // Establecer la propiedad "required"
        });
    }
}

 

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
   
      
    function fechaActual()
    {
    
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
     
      document.getElementById("FECHA_DE_SOLICITUD").value = fechaHoy;  
    document.getElementById("FECHA_DE_SOLICITUD").dispatchEvent(new Event('input'));
    }
    
      
    function fechaActual2()
    {
    
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
    
      var fechaHoy2 = anio  + '-' + mes + '-' +dia ;
     
      document.getElementById("FECHA_INICIO_TRAMITE").value = fechaHoy2;  
    document.getElementById("FECHA_INICIO_TRAMITE").dispatchEvent(new Event('input'));
    }
    
    (function () {
        mostrarPensiones();   
    })();
function mostrarPensiones() {
    if (document.getElementById("AS_TIPO_EAP").value === "CVEAP-B14") {
        _show("PENS_NO_COBRADAS_idd");
        _hide("AS_OBS_CVAP_1_idd");
        _hide("AS_OBS_CVAP_2_idd");
        _hide("AS_OBS_CVAP_3_idd");
        _hide("AS_OBS_CVAP_4_idd");
        _hide("AS_OBS_CVAP_5_idd");
        _hide("SUBTITULO_6_idd");
        // _hide("ES_DH_FALLECIDO_idd");

    }
    else {
        _hide("PENS_NO_COBRADAS_idd");
        _show("AS_OBS_CVAP_1_idd");
        _show("AS_OBS_CVAP_2_idd");
        _show("AS_OBS_CVAP_3_idd");
        _show("AS_OBS_CVAP_4_idd");
        _show("AS_OBS_CVAP_5_idd");
        _show("SUBTITULO_6_idd");
        // _show("ES_DH_FALLECIDO_idd");

    }
}
// (function () {
//     mostrarFuncion();
// })();
// function mostrarFuncion() {
//     // _hide("PENS_NO_COBRADAS_idd");
//     document.getElementById("AS_TIPO_EAP").addEventListener("change", function () {
//         mostrarPensiones();
//         // mostrarDHfallecido(rowIndex);
//     });
// }

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
// **********++++++++



function estadoDerivacion() {
    console.log("eseeeenenenenenenenkekmnokmnjn ");
    console.log("esaod: ", estado);
    var estado = document.getElementById("ESTADO_DERIVACION").value.trim(); 
    var tipo = document.getElementById("AS_TIPO_EAP").value;
    if (((estado === "REGULARIZADO") ||( estado === "FIRMADO" )||( estado === "REVISADO") || (estado === "DESISTIDO"))&& ((tipo === "CVEAP-A10" )|| ( tipo === "CVEAP-A11"))) {
        document.getElementById("ESTADO_DERIVACION").disabled = false;
        console.log("primer if : ", estado);
        if ((estado === "REGULARIZADO") ||(estado === "FIRMADO") ||( estado === "REVISADO") ) {
            console.log("2do if: ", estado);
            volverRequeridoCVEAP();     
            quitarRequeridoNotaDesistido();            
        } else if (estado === "DESISTIDO"){
            console.log("else if : ", estado);
            volverRequeridoNotaDesistido();
            quitarRequeridoCVEAP();
            quitarRequeridoEAP();
        } else if ((tipo === "CVEAP-B14") && ((estado === "REGULARIZADO") || (estado === "FIRMADO")||( estado === "REVISADO"))){
            document.getElementById("ESTADO_DERIVACION").disabled = false;
            console.log("else if 2 : ", estado);
            quitarRequeridoCVEAP();
            quitarRequeridoNotaDesistido();
            volverRequeridoValLegal();
            quitarRequeridoNotaDesistido();
        } else if (estado === "DESISTIDO"){
            console.log("else if 3 : ", estado);
            volverRequeridoNotaDesistido();
            quitarRequeridoValLegal();
            quitarRequeridoCVEAP();
        } 
        var selectPoder = document.getElementById("ESTADO_DERIVACION");
        selectPoder.innerHTML = "";
        var option = document.createElement("option");
        option.value = "REGULARIZADO";
        option.text = "REGULARIZADO - REGULARIZADO";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectPoder.appendChild(option);
        var option = document.createElement("option");
        option.value = "FIRMADO" ;
        option.text = "FIRMADO - FIRMADO";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectPoder.appendChild(option);
        var option = document.createElement("option");
        option.value = "DESISTIDO" ;
        option.text = "DESISTIDO - DESISTIDO";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectPoder.appendChild(option);
        _setValue("ESTADO_DERIVACION",  estado);

    } else if (estado === "OBSERVADO"){
        console.log("else if Afuera : ", estado);
        var selectPoder = document.getElementById("ESTADO_DERIVACION");
        selectPoder.innerHTML = "";
        var option = document.createElement("option");
        option.value = "OBSERVADO" ;
        option.text = "OBSERVADO - OBSERVADO";
        //   option.setAttribute("data-indice-grado", datos[i].indiceGrado); // Agregar el atributo indiceGrado
        selectPoder.appendChild(option);
        document.getElementById("ESTADO_DERIVACION").disabled = true;
        quitarRequeridoCVEAP();       
        quitarRequeridoNotaDesistido();
        volverRequeridoValLegal();  
          _setValue("ESTADO_DERIVACION",  estado); 
    } 
}

function volverRequeridoCVEAP(){
    document.getElementById("CVEAP_FIRMADO").setAttribute("required", "required");
    const nota_aceptado = document.getElementById('CVEAP_FIRMADO_idd');
    const label = nota_aceptado.querySelector('label');
    label.style.display = "block";
    document.getElementById("CVEAP_FIRMADO_ID").setAttribute("required", "required");
    if (label && !label.textContent.includes(' (*)')) {
        label.textContent += ' (*)';
    }
}
  
function quitarRequeridoCVEAP(){
    const nota_aceptado = document.getElementById('CVEAP_FIRMADO_idd');
    document.getElementById("CVEAP_FIRMADO").removeAttribute("required");
    const label = nota_aceptado.querySelector('label');
    if (label) {
        label.textContent = label.textContent.replace(' (*)', '');
    }  
    document.getElementById("CVEAP_FIRMADO_ID").removeAttribute("required");
}

function volverRequeridoNotaDesistido(){
    document.getElementById("NOTA_DESISTIDO").setAttribute("required", "required");
    const nota_desistido = document.getElementById('NOTA_DESISTIDO_idd');
    const label = nota_desistido.querySelector('label');
    label.style.display = "block";
    document.getElementById("NOTA_DESISTIDO_ID").setAttribute("required", "required");

    if (label && !label.textContent.includes(' (*)')) {
        label.textContent += ' (*)';
    }
}
function quitarRequeridoNotaDesistido(){
    const nota_desistido = document.getElementById('NOTA_DESISTIDO_idd');
    document.getElementById("NOTA_DESISTIDO").removeAttribute("required");
    const label = nota_desistido.querySelector('label');
    if (label) {
        label.textContent = label.textContent.replace(' (*)', '');
    }  
    document.getElementById("NOTA_DESISTIDO_ID").removeAttribute("required");
}

function volverRequeridoValLegal(){
    document.getElementById("VALIDACION_LEGAL").setAttribute("required", "required");
    const nota_aceptado = document.getElementById('VALIDACION_LEGAL_idd');
    const label = nota_aceptado.querySelector('label');
    label.style.display = "block";
    document.getElementById("VALIDACION_LEGAL_ID").setAttribute("required", "required");

    if (label && !label.textContent.includes(' (*)')) {
        label.textContent += ' (*)';
    }
}
function quitarRequeridoValLegal(){
    const nota_aceptado = document.getElementById('VALIDACION_LEGAL_idd');
    document.getElementById("VALIDACION_LEGAL").removeAttribute("required");
    const label = nota_aceptado.querySelector('label');
    if (label) {
        label.textContent = label.textContent.replace(' (*)', '');
    }  
    document.getElementById("VALIDACION_LEGAL_ID").removeAttribute("required");
}

function validador(rowIndex){


      var estado_heredero = document.getElementById("DH_ESTADO_HEREDERO" + rowIndex).value;
    

if (estado_heredero == 'Heredero') {
   var overlay = document.getElementById("overlay");
            overlay.style.display = 'flex';
            overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalCerrarBtn = document.getElementById("modalGenerico-boton");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");

            modalCerrarBtn.textContent = "Continuar";
            var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg"; 

            modalTitulo.textContent = "HEREDERO";
            modalMensaje.textContent = "Asegúrese que el HEREDERO se encuentre presente en plataforma para la firma de la solicitud";
            modal.style.display = "block";



} else if (estado_heredero == 'Reserva') {

        var overlay = document.getElementById("overlay");
        overlay.style.display = 'flex';
        overlay.style.display = 'none';
        var modal = document.getElementById("modalGenerico");
        var modalTitulo = document.getElementById("modalGenerico-titulo");
        var modalMensaje = document.getElementById("modalGenerico-mensaje");
        var modalCerrarBtn = document.getElementById("modalGenerico-boton");

        modalCerrarBtn.textContent = "Continuar";
        var icono = document.querySelector(".icono-advertencia");
        icono.src = "img/aviso_inportante.jpg"; 

        modalTitulo.textContent = "RESERVA";
        modalMensaje.textContent = "Heredero Ausente, se reservará la parte que le corresponde, para la cual, debe iniciar una nueva solicitud";
        modal.style.display = "block";


} else if (estado_heredero == 'Rechazado') {

  var overlay = document.getElementById("overlay");
    overlay.style.display = 'flex';
      overlay.style.display = 'none';
            var modal = document.getElementById("modalGenerico");
            var modalTitulo = document.getElementById("modalGenerico-titulo");
            var modalMensaje = document.getElementById("modalGenerico-mensaje");
            var modalCerrarBtn = document.getElementById("modalGenerico-boton");
            modalCerrarBtn.textContent = "Continuar";
            var icono = document.querySelector(".icono-advertencia");
            icono.src = "img/aviso_inportante.jpg"; 
            modalTitulo.textContent = "RECHAZADO";
            modalMensaje.textContent = "La Declaratoria de Herederos presenta observaciones y/o fue rechazada por el área legal. El trámite concluye en esta etapa, una vez se corrija las observaciones debe iniciar una nueva solicitud.";
            modal.style.display = "block";


}




 
}