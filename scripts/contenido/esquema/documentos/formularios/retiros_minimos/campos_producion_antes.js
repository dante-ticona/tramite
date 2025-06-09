[{"act_regla": "(`#AS_TIPO_EAP#` == `CVEAP-A8` ||  `#AS_TIPO_EAP#` == `CVEAP-A9` ||  `#AS_TIPO_EAP#` == `CVEAP-A10`) && (`#ESTADO_DERIVACION#` == `REVISADO` || `#ESTADO_DERIVACION#` == `OBSERVADO - AVANCE`) ", "act_siguiente": "40"}, {"act_regla": "`#AS_TIPO_EAP#` == `CVEAP-B1` && `#ESTADO_DERIVACION#` == `REVISADO`  ", "act_siguiente": "65"}, {"act_regla": "`#ESTADO_DERIVACION#` == `OBSERVADO`", "act_siguiente": "20"}]



[
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_TIPO_EAP",
		"frm_class": "col-md-6",
		"frm_items": [
			{
				"frm_value": "CVEAP-A8",
				"frm_etiqueta": "1. SOLICITUD DE RETIROS MINIMOS/RETIRO FINAL (1ra. Solicitud)"
			},
			{
				"frm_value": "CVEAP-A9",
				"frm_etiqueta": "2. SOLICITUD DE RETIROS TEMPORALES"
			},
			{
				"frm_value": "CVEAP-A10",
				"frm_etiqueta": "3. CASOS NRF (NRO DE REGISTRO FALLECIDO)"
			},
			{
				"frm_value": "CVEAP-B1",
				"frm_etiqueta": "3. RETIROS MINIMOS/RETIRO FINAL (DERIVADA DE RECHAZO DE JUBILACION/CON FORMULARIO DE SELECCIÓN DE BENEFICIOS (R.A. 567 ó R.A. 168)"
			}
		],
		"frm_funcion": "llenaCVEAP()",
		"frm_etiqueta": "SUBCLASIFICACIÓN - TRÁMITE DE RETIROS MÍNIMOS FINAL Y TEMPORAL",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "FECHA_INICIO_TRAMITE",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Fecha de Inicio de Trámite",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TITLE",
		"frm_campo": "SUBTITLE_1",
		"frm_etiqueta": "SOLICITUD DE RETIROS",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_1",
		"frm_etiqueta": "I. IDENTIFICACION DEL ASEGURADO",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_TIPO_DOCUMENTO",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "I",
				"frm_etiqueta": "CEDULA IDENTIDAD"
			},
			{
				"frm_value": "E",
				"frm_etiqueta": "EXTRANJERO"
			},
			{
				"frm_value": "P",
				"frm_etiqueta": "PASAPORTE"
			}
		],
		"frm_etiqueta": "Tipo Documento",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_CI",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Número de Documento",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_COMPLEMENTO",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Complemento",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "BUTTON",
		"frm_campo": "AS_BUSCAR",
		"frm_class": "col-md-1",
		"frm_funcion": "verDatos();",
		"frm_etiqueta": "BUSCAR",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "AS_NACIMIENTO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Fecha Nacimiento",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_CUA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Cua",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_PRIMER_APELLIDO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Primer Apellido",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_SEGUNDO_APELLIDO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Segundo Apellido",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_APELLIDO_CASADA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Apellido casada",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_PRIMER_NOMBRE",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Primer Nombre",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_SEGUNDO_NOMBRE",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Segundo Nombre",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_ESTADO_CIVIL",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "SOLTERO(A)",
				"frm_etiqueta": "SOLTERO(A)"
			},
			{
				"frm_value": "CASADO(A)",
				"frm_etiqueta": "CASADO(A)"
			},
			{
				"frm_value": "DIVORCIADO(A)",
				"frm_etiqueta": "DIVORCIADO(A)"
			},
			{
				"frm_value": "VIUDO(A)",
				"frm_etiqueta": "VIUDO(A)"
			},
			{
				"frm_value": "CONVIVIENTE",
				"frm_etiqueta": "CONVIVIENTE"
			}
		],
		"frm_etiqueta": "Estado Civil",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_GENERO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Género",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_ENTE_GESTOR",
		"frm_class": "col-md-3",
		"frm_items": [
			{}
		],
		"frm_funcion": "cargarEnteGestor()",
		"frm_etiqueta": "Ente Gestor de Salud",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_MATRICULA_ASEGURADO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Matrícula de Asegurado",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_CIUDAD",
		"frm_class": "col-md-2",
		"frm_items": [
			{}
		],
		"frm_funcion": "setProvinciaDepartamento();",
		"frm_etiqueta": "Ciudad",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "PROVINCIA",
		"frm_class": "col-md-2",
		"frm_items": [
			{}
		],
		"frm_etiqueta": "Provincia",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_DEPARTAMENTO",
		"frm_class": "col-md-2",
		"frm_items": [
			{}
		],
		"frm_etiqueta": "Departamento",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_ZONA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Zona/Villa/Barrio/Sector",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_DIRECCION",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Dirección",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_NUM",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Nro.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_CELULAR",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Celular",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_TELEFONO",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Teléfono",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_POSTAL",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Casilla Postal",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "MAIL",
		"frm_campo": "AS_CORREO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Correo Electrónico",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "HIDDEN",
		"frm_campo": "AS_IDPERSONA",
		"frm_etiqueta": "",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "HIDDEN",
		"frm_campo": "ID_SOLICITUDPRESTACION",
		"frm_etiqueta": "",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "HIDDEN",
		"frm_campo": "AS_API_ESTADO",
		"frm_etiqueta": "",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "AS_FECHA_FALLECIMIENTO",
		"frm_class": "col-md-3",
		"frm_funcion": "consultarMuerte();",
		"frm_etiqueta": "Fecha de Fallecimiento",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_2",
		"frm_etiqueta": "II. DATOS LABORALES DEL ASEGURADO",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "EM_TIPO_AS",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "D",
				"frm_etiqueta": "DEPENDIENTE"
			},
			{
				"frm_value": "I",
				"frm_etiqueta": "INDEPENDIENTE"
			}
		],
		"frm_funcion": "datosTipoAsegurado()",
		"frm_etiqueta": "Tipo Asegurado",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "EM_NOMBRE",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Nom.y Ap. o Razón Social del Empleador",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "EM_FECHA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Fecha inicio Rel. Lab",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "EM_OCUPACION_AS",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Ocupación",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "EM_CIUDAD",
		"frm_class": "col-md-3",
		"frm_items": [
			{}
		],
		"frm_funcion": "setProvinciaDepartamentoEM();",
		"frm_etiqueta": "Ciudad",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "EM_PROVINCIA",
		"frm_class": "col-md-3",
		"frm_items": [
			{}
		],
		"frm_etiqueta": "Provincia",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "EM_DEPARTAMENTO",
		"frm_class": "col-md-3",
		"frm_items": [
			{}
		],
		"frm_etiqueta": "Departamento",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "EM_ZONA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Zona/Villa/Barrio/Sector",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "EM_DIRECCION",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Dirección",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "EM_NUM",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Nro.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "EM_TELEFONO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Teléfono",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "EM_CELULAR",
		"frm_class": "col-md-4",
		"frm_etiqueta": "Celular",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "EM_POSTAL",
		"frm_class": "col-md-4",
		"frm_etiqueta": "Casilla Postal",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "MAIL",
		"frm_campo": "EM_CORREO",
		"frm_class": "col-md-4",
		"frm_etiqueta": "Correo Electrónico",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_3",
		"frm_etiqueta": "III. DATOS DEL SOLICITANTE",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "CHECKBOX",
		"frm_campo": "SOL_DIFERENTE_AS",
		"frm_class": "col-md-2",
		"frm_funcion": "esSolicitante();",
		"frm_etiqueta": "Copiar datos del asegurado",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "SOL_TIPO_DOCUMENTO",
		"frm_class": "col-md-2",
		"frm_items": [
			{
				"frm_value": "I",
				"frm_etiqueta": "CEDULA IDENTIDAD"
			},
			{
				"frm_value": "E",
				"frm_etiqueta": "EXTRANJERO"
			},
			{
				"frm_value": "P",
				"frm_etiqueta": "PASAPORTE"
			}
		],
		"frm_etiqueta": "Tipo Documento",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "SOL_CI",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Número de Documento",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_COMPLEMENTO",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Complemento",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "BUTTON",
		"frm_campo": "SOL_BUSCAR",
		"frm_class": "col-md-1",
		"frm_funcion": "verDatosSol();",
		"frm_etiqueta": "BUSCAR",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "SOL_NACIMIENTO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Fecha Nacimiento",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "SOL_CUA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Cua",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_PRIMER_APELLIDO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Primer Apellido",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_SEGUNDO_APELLIDO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Segundo Apellido",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_PRIMER_NOMBRE",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Primer Nombre",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_SEGUNDO_NOMBRE",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Segundo Nombre",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_APELLIDO_CASADA",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Apellido casada",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "SOL_PARENTESCO",
		"frm_class": "col-md-3",
		"frm_items": [
			{}
		],
		"frm_funcion": "cargarParentesco();",
		"frm_etiqueta": "Parentesco",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "SOL_ESTADO_CIVIL",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "SOLTERO(A)",
				"frm_etiqueta": "SOLTERO(A)"
			},
			{
				"frm_value": "CASADO(A)",
				"frm_etiqueta": "CASADO(A)"
			},
			{
				"frm_value": "DIVORCIADO(A)",
				"frm_etiqueta": "DIVORCIADO(A)"
			},
			{
				"frm_value": "VIUDO(A)",
				"frm_etiqueta": "VIUDO(A)"
			},
			{
				"frm_value": "CONVIVIENTE",
				"frm_etiqueta": "CONVIVIENTE"
			}
		],
		"frm_etiqueta": "Estado Civil",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_GENERO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Género",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "SOL_CIUDAD",
		"frm_class": "col-md-2",
		"frm_items": [
			{}
		],
		"frm_funcion": "setProvinciaDepartamentoSOL();",
		"frm_etiqueta": "Ciudad",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "SOL_PROVINCIA",
		"frm_class": "col-md-2",
		"frm_items": [
			{}
		],
		"frm_etiqueta": "Provincia",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "SOL_DEPARTAMENTO",
		"frm_class": "col-md-2",
		"frm_items": [
			{}
		],
		"frm_etiqueta": "Departamento",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_ZONA",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Zona/Villa/Barrio/Sector",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_DIRECCION",
		"frm_class": "col-md-9",
		"frm_etiqueta": "Dirección",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_NUM",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Nro.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "SOL_TELEFONO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Teléfono",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "SOL_CELULAR",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Celular",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "SOL_POSTAL",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Casilla Postal",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "MAIL",
		"frm_campo": "SOL_CORREO",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Correo Electrónico",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "HIDDEN",
		"frm_campo": "SOL_IDPERSONA",
		"frm_etiqueta": "",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "TIENE_PODER_SOL_1",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "1",
				"frm_etiqueta": "SI"
			},
			{
				"frm_value": "2",
				"frm_etiqueta": "NO"
			}
		],
		"frm_funcion": "obligarPoder()",
		"frm_etiqueta": "Tiene Poder",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "NRO_PODER_SOL_1",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Nro. Poder",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "NRO_NOTARIA_SOL_1",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Nro. Notaría",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "NOMBRE_NOTARIO_SOL_1",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Nombre Notario",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITLE_11",
		"frm_etiqueta": "DATOS DEL PODER",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "VALIDAR_PODER",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "-",
				"frm_etiqueta": "-"
			},
			{
				"frm_value": "APR",
				"frm_etiqueta": "Aprobado"
			},
			{
				"frm_value": "REC",
				"frm_etiqueta": "Rechazado"
			}
		],
		"frm_etiqueta": "Validación de Poder",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "FECHA_REVISION",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Fecha de Revisión",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITLE_4",
		"frm_etiqueta": "IV. SELECCIÓN DE RETIROS MÍNIMOS Y MODALIDAD DE PAGO",
		"frm_deshabilitado": "",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "RMI_OPCION",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "RM",
				"frm_etiqueta": "RETIROS MÍNIMOS"
			},
			{
				"frm_value": "RF",
				"frm_etiqueta": "RETIRO FINAL"
			}
		],
		"frm_funcion": "retiroOpcion();",
		"frm_etiqueta": "Elija una opción",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "RMI_REF_SALARIAL",
		"frm_class": "col-md-4",
		"frm_etiqueta": "- 60% del Referente Salarial (Bs.)",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "RMI_SALARIO_MINIMO",
		"frm_class": "col-md-5",
		"frm_etiqueta": "- 60% del Salario Mínimo Nacional (Bs.)",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_4",
		"frm_etiqueta": "V. DERECHOHABIENTES",
		"frm_deshabilitado": ""
	},
	{
		"frm_cols": [
			{
				"col_tipo": "DROPDOWNLIST",
				"col_campo": "DH_TIPO_DOCUMENTO",
				"col_items": [
					{
						"itm_value": "I",
						"itm_etiqueta": "CEDULA IDENTIDAD"
					},
					{
						"itm_value": "E",
						"itm_etiqueta": "EXTRANJERO"
					},
					{
						"itm_value": "P",
						"itm_etiqueta": "PASAPORTE"
					},
					{
						"itm_value": "T",
						"itm_etiqueta": "TEMPORAL"
					}
				],
				"col_etiqueta": "TIPO DOCUMENTO",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "HIDDEN",
				"col_campo": "DH_IDPERSONA_GRILLA_PROP",
				"col_etiqueta": "DH_IDPERSONA_GRILLA_PROP",
				"col_deshabilitado": "false"
			},
			{
				"col_tipo": "NUMBER",
				"col_campo": "DH_CI_GRILLA_PROP",
				"col_etiqueta": "NRO DOCUMENTO",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_COMP_GRILLA_PROP",
				"col_etiqueta": "COMPLEMENTO",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "BUTTON",
				"col_campo": "DH_BUSCAR",
				"col_funcion": "verDatosDh(rowIndex);",
				"col_etiqueta": "BUSCAR"
			},
			{
				"col_tipo": "DATE",
				"col_campo": "DH_FECHA_NAC",
				"col_etiqueta": "Fecha Nacimiento",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_NOMBRES",
				"col_etiqueta": "Nombres",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_PRIMER_APELLIDO",
				"col_etiqueta": "Primer Apellido",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_SEGUNDO_APELLIDO",
				"col_etiqueta": "Segundo Apellido",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_APELLIDO_CASADA",
				"col_etiqueta": "Ap. Casada",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "DROPDOWNLIST",
				"col_campo": "DH_GENERO",
				"col_items": [
					{
						"itm_value": "M",
						"itm_etiqueta": "MASCULINO"
					},
					{
						"itm_value": "F",
						"itm_etiqueta": "FEMENINO"
					}
				],
				"col_etiqueta": "Género",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "NUMBER",
				"col_campo": "DH_NRO_CELULAR",
				"col_etiqueta": "Nro Celular",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_CORREO",
				"col_etiqueta": "Correo",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "DROPDOWNLIST",
				"col_campo": "DH_INVALIDEZ",
				"col_items": [
					{
						"itm_value": false,
						"itm_etiqueta": "NO"
					},
					{
						"itm_value": true,
						"itm_etiqueta": "SI"
					}
				],
				"col_etiqueta": "ESTADO DE INVALIDEZ",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "DROPDOWNLIST",
				"col_campo": "DH_PARENTESCO",
				"col_items": [
					{
						"itm_value": "1-HIJ",
						"itm_etiqueta": "Hija o Hijo"
					},
					{
						"itm_value": "1-CONY",
						"itm_etiqueta": "Cónyugue"
					},
					{
						"itm_value": "1-CONV",
						"itm_etiqueta": "Conviviente"
					},
					{
						"itm_value": "2-HER",
						"itm_etiqueta": "Hermano o Hermana"
					},
					{
						"itm_value": "2-PAD",
						"itm_etiqueta": "Padre"
					},
					{
						"itm_value": "2-MAD",
						"itm_etiqueta": "Madre"
					}
				],
				"col_funcion": "esTercerGrado(rowIndex);",
				"col_etiqueta": "PARENTESCO",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "DROPDOWNLIST",
				"col_campo": "DH_ESTADO_CIVIL",
				"col_items": [
					{
						"itm_value": "--------",
						"itm_etiqueta": "--------"
					},
					{
						"itm_value": "SOLTERO(A)",
						"itm_etiqueta": "SOLTERO(A)"
					},
					{
						"itm_value": "CASADO(A)",
						"itm_etiqueta": "CASADO(A)"
					},
					{
						"itm_value": "DIVORCIADO(A)",
						"itm_etiqueta": "DIVORCIADO(A)"
					},
					{
						"itm_value": "VIUDO(A)",
						"itm_etiqueta": "VIUDO(A)"
					},
					{
						"itm_value": "CONVIVIENTE",
						"itm_etiqueta": "CONVIVIENTE"
					}
				],
				"col_etiqueta": "ESTADO CIVIL",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "TEXT",
				"col_campo": "DH_GRADO",
				"col_etiqueta": "PORCENTAJE",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			},
			{
				"col_tipo": "MODAL",
				"col_campo": "DH_DOCUMENTOS",
				"col_etiqueta": "DOCUMENTOS",
				"col_deshabilitado": "false",
				"col_deshabilitadoo": false
			}
		],
		"frm_rows": [],
		"frm_tipo": "GRID",
		"frm_campo": "GRILLA_DERECHOHABIENTES",
		"frm_value": [],
		"frm_etiqueta": "DERECHOHABIENTES",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_6",
		"frm_etiqueta": "VI. CUENTA PERSONAL PREVISIONAL",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_TIENE_CC",
		"frm_class": "col-md-2",
		"frm_items": [
			{
				"frm_value": "SI",
				"frm_etiqueta": "SI"
			},
			{
				"frm_value": "NO",
				"frm_etiqueta": "NO"
			}
		],
		"frm_etiqueta": "Tiene Compensación de Cotizaciones (CC)",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_CC",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Monto CCG",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DATE",
		"frm_campo": "AS_FECHA_INICIO_COTIZACION",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Fecha de inicio de Cotización al SSO/SIP",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_NUM_CUOTAS",
		"frm_class": "col-md-2",
		"frm_etiqueta": "N° de Cuotas correspondientes al Saldo Acumulado",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "NUMBER",
		"frm_campo": "AS_VALOR_CUOTA",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Valor Cuota a la fecha del día anterior a la recepción de la Solicitud",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "AS_SALDO_ACUMULADO",
		"frm_class": "col-md-2",
		"frm_etiqueta": "Saldo Acumulado en la Cuenta Personal Previsional en Bolivianos",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_7",
		"frm_etiqueta": "REFERENCIA",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "TEXT",
		"frm_campo": "REFERENCIA",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Referencia",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_8",
		"frm_etiqueta": "OBSERVACIONES DE CVEAP",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "TEXTAREA",
		"frm_campo": "AS_OBS_CVAP_1",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Periodo faltantes que le fueron descontados y no figuran en el estado de  Ahorro Previsional.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXTAREA",
		"frm_campo": "AS_OBS_CVAP_4",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Cotizaciones Adicionales o Depósitos Voluntarios de Beneficios Sociales que no se consigna en el Estado de Ahorro.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXTAREA",
		"frm_campo": "AS_OBS_CVAP_2",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Monto pagado de cotización mensual diferente al 10% del Total Ganado.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXTAREA",
		"frm_campo": "AS_OBS_CVAP_3",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Monto de Total Ganado Diferente al que el Asegurado percibió en un mes respectivo.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "TEXTAREA",
		"frm_campo": "AS_OBS_CVAP_5",
		"frm_class": "col-md-6",
		"frm_etiqueta": "Cualquier otra diferencia.",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "SUBTITLE",
		"frm_campo": "SUBTITULO_9",
		"frm_etiqueta": "DOCUMENTOS ADICIONALES",
		"frm_deshabilitado": ""
	},
	{
		"frm_tipo": "DROPDOWNLIST",
		"frm_campo": "AS_CERT_INSALUBRIDAD",
		"frm_class": "col-md-3",
		"frm_items": [
			{
				"frm_value": "SI",
				"frm_etiqueta": "SI"
			},
			{
				"frm_value": "NO",
				"frm_etiqueta": "NO"
			}
		],
		"frm_funcion": "esconderadjunto()",
		"frm_etiqueta": "Tiene Certificado de Insalubridad",
		"frm_obligatorio": "true",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DOCUMENT",
		"frm_campo": "CERT_INSALUBRIDAD",
		"frm_class": "col-md-3",
		"frm_etiqueta": "Certificado de Insalubridad",
		"frm_id_campo": "CERT_INSALUBRIDAD_ID",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "false",
		"frm_deshabilitadoo": false
	},
	{
		"frm_tipo": "DOCUMENT",
		"frm_campo": "RECHAZO_JUB",
		"frm_class": "col-md-4",
		"frm_etiqueta": "Nota de rechazo por Jubilación",
		"frm_id_campo": "RECHAZO_JUB_ID",
		"frm_obligatorio": "false",
		"frm_deshabilitado": "true",
		"frm_deshabilitadoo": true
	}
]