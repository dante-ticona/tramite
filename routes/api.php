<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('token', 'ApiController@login');
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('generarPDF1582', 'documentos\documentosPrevisualizacionController@generarPDF1582');
    Route::post('crearOV1582', 'ApiCreacionTramitesController@crearOV1582');
});

Route::post('/tramitesBuenos',  'soporte\sqlController@tramitesBuenos');
Route::post('login', 'ApiController@login');
///Route::post('register', 'ApiController@register');
Route::post('loginApp/', 'ApiController@loginApp');
Route::get('obtenerUsuario/{usrId}', 'ApiController@obtenerUsuario');
Route::post('actualizarUsuario', 'ApiController@actualizarUsuario');
Route::get('roles/', 'ApiController@listarRoles');
Route::get('users/', 'ApiController@listarUsers');
Route::get('usersATC/', 'ApiController@listarUsersATC');
Route::put('usersATC/{id}', 'ApiController@actualizarUserATC');
Route::get('users/{id}', 'ApiController@buscarUser');
Route::post('users/', 'ApiController@grabarUser');
Route::put('users/{id}', 'ApiController@actualizarUser');
Route::post('users/{id}', 'ApiController@eliminarUser');
//Route::get('users/jrs/{jrs_id}', 'ApiController@listarUsersXJrs');

Route::get('tactividades/', 'ApiVySController@listarTActividades');
Route::post('tactividades/', 'ApiVySController@grabarTActividades');
Route::put('tactividades/{tact_id}', 'ApiVySController@actualizarTActividades');
Route::post('tactividades/{tact_id}', 'ApiVySController@eliminarTActividades');

Route::get('tformularios/', 'ApiVySController@listarTFormularios');
Route::post('tformularios/', 'ApiVySController@grabarTFormularios');
Route::put('tformularios/{tfrm_id}', 'ApiVySController@actualizarTFormularios');
Route::post('tformularios/{tfrm_id}', 'ApiVySController@eliminarTFormularios');

Route::get('formularios/{act_id}', 'ApiVySController@listarFormularios');
Route::post('formularios/', 'ApiVySController@grabarFormularios');
Route::put('formularios/{frm_id}', 'ApiVySController@actualizarFormularios');
Route::put('formularios_campos/{frm_id}', 'ApiVySController@actualizarFormulariosCampos');
Route::post('formularios/{frm_id}', 'ApiVySController@eliminarFormularios');

Route::get('nodos/', 'ApiVySController@listarNodos');
Route::get('nodosUsuario/{usr_id}', 'ApiVySController@listarNodosUsuario');
Route::post('nodos/', 'ApiVySController@grabarNodos');
Route::put('nodos/{nodo_id}', 'ApiVySController@actualizarNodos');
Route::post('nodos/{nodo_id}', 'ApiVySController@eliminarNodos');

Route::get('nodosProcesos/', 'ApiVySController@listarNodosProcesos');
Route::post('nodosProcesos/', 'ApiVySController@grabarNodosProcesos');
Route::put('nodosProcesos/{nopr_id}', 'ApiVySController@actualizarNodosProcesos');
Route::post('nodosProcesos/{nopr_id}', 'ApiVySController@eliminarNodosProcesos');

Route::get('catalogos/', 'ApiVySController@listarCatalogos');
Route::post('catalogos/', 'ApiVySController@grabarCatalogos');
Route::put('catalogos/{cat_id}', 'ApiVySController@actualizarCatalogos');
Route::post('catalogos/{cat_id}', 'ApiVySController@eliminarCatalogos');

Route::get('procesos/todos', 'ApiVySController@listarTodosProcesos');
Route::get('procesos/{cat_id}', 'ApiVySController@listarProcesos');
Route::post('procesos/', 'ApiVySController@grabarProcesos');
Route::put('procesos/{prc_id}', 'ApiVySController@actualizarProcesos');
Route::post('procesos/{prc_id}', 'ApiVySController@eliminarProcesos');
Route::get('procesoXPrcId/{prc_id}', 'ApiVySController@listarProcesoXPrcId');
Route::get('procesosXNodoId/{nodo_id}', 'ApiVySController@listarProcesosXNodoId');

Route::get('procesosTodos', 'ApiVySController@listarProcesosTodos');
Route::get('listadoProcesos', 'ApiVySController@getListarProcesos');

Route::post('procesosXUsrId', 'ApiVySController@listarProcesosXUsrId');

Route::get('notificaionesMC/{cas_id}', 'ApiVySController@notificaionesMC');

Route::get('actividades/{prc_id}', 'ApiVySController@listarActividades');
Route::post('actividades/', 'ApiVySController@grabarActividades');
Route::put('actividades/{act_id}', 'ApiVySController@actualizarActividades');
Route::post('actividades/{act_id}', 'ApiVySController@eliminarActividades');

Route::get('actividad/{prc_id}/{act_orden}', 'ApiVySController@listarActividadXPrcIdXOrden');

Route::get('casos/{usr_id}/{nodo_id}/{RegistrosXPagina}/{PaginaActual}', 'ApiVySController@listarCasos');
Route::put('casos/{cas_id}', 'ApiVySController@actualizarCasos');

Route::post('casos/', 'ApiVySController@grabarCasos');
Route::get('casosUsuario/{usr_id}/{nodo_id}/{RegistrosXPagina}/{PaginaActual}', 'ApiVySController@listarCasosUsuario');
Route::get('casosArchivados/{usr_id}', 'ApiVySController@listarCasosArchivados');
Route::get('caso/{cas_id}', 'ApiVySController@listarCasoXId');
Route::put('casosArchivar/{cas_id}', 'ApiVySController@archivarCasos');
Route::put('casosDerivar/{cas_id}', 'ApiVySController@derivarCasos');
Route::put('casosDerivarMultiple/{cas_id}', 'ApiVySController@casosDerivarMultiple'); //

Route::put('casosDerivarParalelo/{cas_id}', 'ApiVySController@derivarCasosParalelo');
Route::put('casosDerivarUnion/{cas_id}', 'ApiVySController@derivarCasosUnion');
Route::get('casosHistorico/{cas_id}', 'ApiVySController@historicoCasos');
Route::put('casosDerivarINVPMSgte/{cas_id}', 'ArchivoPdfController@derivacionTramiteINVPMSgte');

Route::get('listadolegal/{cas_id}', 'ApiVySController@listadoLegal');

Route::get('casosHistoricoCandadito/{cas_id}', 'ApiVySController@candadito');
Route::post('v2/casosHistoricoCandidato/', 'ApiVySController@candidato_v2');
Route::get('recuperarDatosParaAnular/{cas_id}', 'ApiVySController@recuperarDatosParaAnular');
Route::put('anularDesistirCaso', 'ApiVySController@anularDesistirCasos');

Route::post('tomarcaso', 'ApiVySController@tomarcaso');
Route::post('tomarcasoOv', 'ApiVySController@tomarcasoOv');

Route::post('nroCorrelativo', 'ApiVySController@nroCorrelativo');

Route::post('casosXNodo/{RegistrosXPagina}/{PaginaActual}', 'ApiVySController@listarCasosXNodo');
Route::post('casosXNodoXUsuario/{RegistrosXPagina}/{PaginaActual}', 'ApiVySController@listarCasosXNodoXUsuario');

Route::post('casosDerivacionMple', 'ApiVySController@derivarCasosMultiple');
Route::post('enmienda', 'ApiVySController@enmienda');
Route::post('derivarenmienda', 'ApiVySController@derivarenmienda');

Route::post('buscarCasos/{RegistrosXPagina}/{PaginaActual}', 'ApiVySController@buscarCasos');
Route::post('buscarTramiteAsegurado', 'servicioGestora\tramiteSipController@buscarTramiteAsegurado');

Route::get('buscarCasos1/', 'ApiVySController@buscarCasosBusqueda');

Route::get('buscarCasosTodos', 'ApiVySController@buscarCasosTodos');
Route::put('estadoCaso/{cas_id}', 'ApiVySController@actualizarEstadoCaso');
Route::post('subirAdjunto', 'ApiVySController@subirAdjunto');

Route::get('dashboard', 'ApiVySController@dashboardCasos');
Route::post('buscarDocumentos', 'ApiVySController@buscarDocumentosCasos');

// Impresiones
Route::get('impresiones/{act_id}', 'ApiVySController@listarImpresiones');
Route::get('impresionesCasos/{act_id}/{cas_id}', 'ApiVySController@listarImpresionesCasos');
Route::post('impresiones/{imp_id}', 'ApiVySController@eliminarImpresion');
Route::post('impresiones/', 'ApiVySController@grabarImpresion');
Route::put('impresiones/{imp_id}', 'ApiVySController@actualizarImpresion');
Route::post('reasignarImpresion/', 'ApiVySController@reasignarImpresionActividad');

Route::post('generatepdf', 'documentos\documentosPrevisualizacionController@generatePdf');

Route::post('generatePdf1', 'ApiVySController@generatePdf1');
Route::post('generateFormRescepcionDocumento', 'ApiVySController@generateFormRescepcionDocumento');
Route::get('downloadpdf', 'ApiVySController@descargarPdf');
Route::post('SolicitudPrestacion', 'ApiVySController@dataSolicitudPrestacionES');
Route::post('SetIDSolitudPrestacion', 'ApiVySController@setIDSolitudPrestacion');

Route::post('cambioEstadoEap', 'legal\LegalController@cambioEstadoEap');

Route::post('GetIdsolicitudprestacion', 'ApiVySController@getId_solicitudprestacion');
Route::post('SetSeguimientoTramites', 'ApiVySController@SeguimientoTramites');
Route::post('VerificacionNodoSiguiente', 'ApiVySController@VerificacionNodo');
Route::post('VerificacionNodoSiguienteParalelo', 'ApiVySController@VerificacionNodoParalelo');
// Usuarios nodos
Route::get('usrnodos', 'ApiVySController@listarUsrNodos');
Route::post('usrnodos', 'ApiVySController@grabarUsrNodos');
Route::delete('usrnodos/{usn_id}', 'ApiVySController@eliminarUsrNodos');
Route::put('usrnodos/{usn_id}', 'ApiVySController@actualizarUsrNodos');

Route::get('usrnodosXId/{id}', 'ApiVySController@listarUsrNodosXId');
// para Round-robin
Route::get('usrNodosXNodoId/{nodo_id}', 'ApiVySController@listarUsrNodosXNodoId');
Route::get('usrNodosRR/{nodo_id}', 'ApiVySController@listarUltUsrNodo');
Route::put('usrNodosRR', 'ApiVySController@modificarUltUsrNodo');

Route::get('prc_exportar/{prc_id}', 'ApiVySController@exportarProceso');
Route::post('prc_importar/{cat_id}', 'ApiVySController@importarProceso');

// Correspondencia
Route::get('tiposCorrespondencia', 'ApiCrrController@listarTiposCorrespondencia');

Route::get('correspondencia/{usr_id}', 'ApiCrrController@listarCorrespondencia');
Route::get('correspondenciaXId/{crr_id}', 'ApiCrrController@listarCorrespondenciaXId');
Route::post('correspondencia/', 'ApiCrrController@grabarCorrespondencia');
Route::put('estadoCorrespondencia/{crr_id}', 'ApiCrrController@actualizarEstadoCorrespondencia');

Route::get('copiasXCrrId/{crr_id}', 'ApiCrrController@listarCopiasXCrrId');

Route::get('actuaciones/{crr_id}', 'ApiCrrController@listarActuacionesXCRR');

Route::get('actuaciones', 'ApiCrrController@listarActuaciones');
Route::post('actuaciones', 'ApiCrrController@grabarActuaciones');
Route::put('actuaciones/{act_id}', 'ApiCrrController@actualizarActuaciones');
Route::post('actuaciones/{act_id}', 'ApiCrrController@eliminarActuaciones');

Route::post('crrSetHistorico/{crr_id}', 'ApiCrrController@setHistorico');
Route::post('crrDerivar/{crr_id}/{nodo_id}', 'ApiCrrController@derivar');
Route::post('crrSetCopia/{crr_id}/{nodo_id}', 'ApiCrrController@setCopia');
Route::post('crrDelCopia/{cp_id}', 'ApiCrrController@delCopia');

Route::get('subtiposDoc/{doc_id}', 'ApiCrrController@listarSubtiposDocXDocID');

//////Ws
Route::get('tipows/', 'ApiWsController@listarTipoWs');
Route::post('tipows/', 'ApiWsController@grabarTipoWs');
Route::put('tipows/{tws_id}', 'ApiWsController@actualizarTipoWs');
Route::post('tipows/{tws_id}', 'ApiWsController@eliminarTipoWs');

Route::get('ws/', 'ApiWsController@listarWs');
Route::post('ws/', 'ApiWsController@grabarWs');
Route::put('ws/{ws_id}', 'ApiWsController@actualizarWs');
Route::post('ws/{ws_id}', 'ApiWsController@eliminarWs');

//-- Archivos nfs --\\
Route::post('guardarDocumentosRequisitosNfs', 'documentos\documentosNfsController@guardarDocumento');
Route::post('guardarDocumentosRequisitosNfsLegal', 'documentos\documentosNfsController@guardarDocumentosRequisitosNfsLegal');

Route::get('verDocumetos10fallidoValidar/{id}', 'documentos\documentosNfsController@verDocumetos10fallidoValidar');
Route::get('verDocumentoPdfNfs/{id}', 'documentos\documentosNfsController@verDocumentoPdf');
Route::get('verFirmaDocumentoPdfNfs/{id}', 'documentos\documentosNfsController@verFirmaDocumentoPdfNfs');
Route::get('verDocumentoPdfNfs_aud/{id}', 'documentos\documentosNfsController@verDocumentoPdf_aud');

Route::get('verDocumentoPdfNotiAdjuntos/{id}', 'documentos\documentosNfsController@verDocumentoPdfNotiAdjuntos');
Route::post('verDocumentoPdfNfsRuta', 'documentos\documentosNfsController@verDocumentoPdfRuta');
Route::get('obtenerDocumentoPdf64/{id}', 'documentos\documentosNfsController@obtenerDocumentoPdf64');
Route::post('obtenerDocumentoFirmantes', 'documentos\documentosNfsController@obtenerDocumentoFirmantes');

Route::post('guardarDocumentosAdjuntosNfs', 'documentos\documentosNfsController@guardarAdjuntos');
Route::post('guardarDocumentosAdjuntosNfsBC', 'documentos\documentosNfsController@guardarAdjuntosBC');
Route::post('guardarDocumentosAdjuntosNfsMedicos', 'documentos\documentosNfsController@guardarAdjuntosMedicos');
Route::post('obtenerListadoDocumentos', 'documentos\documentosNfsController@obtenerListadoDocumentos');
Route::post('nfsAcceso', 'documentos\archivoController@verificarAccesoNFS');
Route::post('guardarDocumentosRequisitos', 'documentos\documentosNfsController@guardarDocumentoUno');
Route::post('obtenerDocumentoPdf64', 'documentos\documentosNfsController@obtenerDocumentoPdf64CodigoRegistro');
Route::post('obtenerDocumentoPdf64Codigo', 'documentos\documentosNfsController@obtenerDocumentoPdf64Codigo');
Route::get('obtenerRegistroDiferencia/{cua}', 'documentos\documentosNfsController@registroDiferencia');
Route::post('guardarDocumentosPost', 'documentos\documentosNfsController@guardarDocumentosPost');
Route::post('limpiarAdjunto', 'documentos\documentosNfsController@limpiarAdjunto');
Route::post('limpiarDocumentoAdjunto', 'documentos\documentosNfsController@limpiarDocumentoAdjunto');
Route::post('limpiarDerechohabiente', 'documentos\documentosNfsController@limpiarDerechohabiente');

Route::get('listarRegisComplementario/{RegistrosXPagina}/{PaginaActual}', 'documentos\documentosNfsController@listarRegisComplementario');

Route::post('actualizarRegisComplementario', 'documentos\documentosNfsController@actualizarRegisComplementario');
Route::get('obtenerDocumetacionObservada/{cas_id}', 'documentos\documentosNfsController@DocumetacionObservada');

// 1582 NFS
Route::get('guardarDocumentosRequisitosNfs1582/{id_doc}', 'documentos\documentosNfsController@verDocumentoPdf1582');
Route::get('verDocumentoPdf1582Doc/{id_doc}', 'documentos\documentosNfsController@verDocumentoPdf1582Doc');

//////Archivos
Route::get('tiposArchivo/', 'ApiArchController@listarTiposArchivo');
Route::post('tiposArchivo/', 'ApiArchController@grabarTiposArchivo');
Route::put('tiposArchivo/{tarch_id}', 'ApiArchController@actualizarTiposArchivo');
Route::post('tiposArchivo/{tarch_id}', 'ApiArchController@eliminarTiposArchivo');

Route::get('archivos/', 'ApiArchController@listarArchivos');
Route::post('archivos/', 'ApiArchController@grabarArchivos');
Route::put('archivos/{arch_id}', 'ApiArchController@actualizarArchivos');
Route::post('archivos/{arch_id}', 'ApiArchController@eliminarArchivos');

Route::get('tiposDoc/', 'ApiArchController@listarTiposDoc');
Route::post('tiposDoc/', 'ApiArchController@grabarTiposDoc');
Route::put('tiposDoc/{tdoc_id}', 'ApiArchController@actualizarTiposDoc');
Route::post('tiposDoc/{tdoc_id}', 'ApiArchController@eliminarTiposDoc');

Route::get('subtiposDoc/', 'ApiArchController@listarSubtiposDoc');
Route::post('subtiposDoc/', 'ApiArchController@grabarSubtiposDoc');
Route::put('subtiposDoc/{stdoc_id}', 'ApiArchController@actualizarSubtiposDoc');
Route::post('subtiposDoc/{stdoc_id}', 'ApiArchController@eliminarSubtiposDoc');

// soporte
Route::post('actualizar_deptos', 'soporte\soporteGeneralController@actualizar_deptos');
Route::post('actualizarCampos_cas_data_valores', 'soporte\soporteGeneralController@actualizarCampos_cas_data_valores');
Route::post('actualizarEnte_gestor', 'soporte\soporteGeneralController@actualizarEnte_gestor');
Route::post('actualizar_guion', 'soporte\soporteGeneralController@actualizar_guion');
Route::post('tomarCasoNube', 'soporte\soporteGeneralController@tomarCasoNube');
Route::post('tomarCasoNube2', 'soporte\soporteGeneralController@tomarCasoNube2');
Route::post('cambiarNodoActividadUsuario', 'soporte\soporteGeneralController@cambiarNodoActividadUsuario');
Route::post('cambiarNodoActividadUsuariHistoric', 'soporte\soporteGeneralController@cambiarNodoActividadUsuariHistoric');
Route::post('borrarHistorico', 'soporte\soporteGeneralController@borrarHistorico');
Route::post('reponerHistorico', 'soporte\soporteGeneralController@reponerHistorico');
Route::post('actualizarCampoCasDataCasos', 'soporte\soporteGeneralController@actualizarCampoCasDataCasos');
Route::post('actualizarCampoHistorico', 'soporte\soporteGeneralController@actualizarCampoHistorico');
Route::post('actualizarURLdocumentos', 'soporte\soporteGeneralController@actualizarURLdocumentos');
Route::post('quitaDocumentosConHistorico', 'soporte\soporteGeneralController@quitaDocumentosConHistorico');
Route::post('cambiarNodoActividadConHistorico', 'soporte\soporteGeneralController@cambiarNodoActividadConHistorico');
Route::post('mantis', 'soporte\soporteGeneralController@mantis');


// casos externos
Route::post('casoexterno', 'ApiVySIntegracionController@recibirCaso');

// urls

Route::post('cambioUrl', 'documentos\urlController@cambioUrl');

// firmas
Route::post('guardarDocumentosRequisitoFirmado', 'documentos\firmarController@pdfGuardadoFirmadoSolicitante');
Route::post('guardarFirma', 'documentos\firmarController@guardarFirmapng');
Route::post('obtenerFirma', 'documentos\firmarController@obtenerFirma');
// Documentos

Route::post('guardarDocumento', 'documentos\documentosCotroller@generatePdf');
Route::post('obtenerDocumento', 'documentos\documentosCotroller@obtenerDocumentoPdf');
Route::post('obtenerDocumentoLegal', 'documentos\documentosCotroller@obtenerDocumentoPdfLegal');
Route::post('obtenerDocumento1582', 'documentos\documentosCotroller@obtenerDocumentoPdf1582');

Route::post('obtenerDocumentoAdjunto', 'documentos\documentosCotroller@obtenerDocumentoPdfAdjunto');
Route::post('obtenerDocumentoAdjuntoMedico', 'documentos\documentosCotroller@obtenerDocumentoPdfAdjuntoMedico');

Route::post('obtenerDocumentoEdit', 'documentos\documentosCotroller@obtenerDocumentoPdfEdit');

Route::post('existeDocumentosRequisitos', 'documentos\archivoController@existeDocumentosRequisitos');
Route::post('existeDocumentosRequisitosLegal', 'documentos\archivoController@existeDocumentosRequisitosLegal');

Route::post('obtenerDocumentosRequisitos', 'documentos\archivoController@obtenerDocumentosRequisitos');
Route::post('obtenerDocumentosRequisitosLegal', 'documentos\archivoController@obtenerDocumentosRequisitosLegal');

Route::post('guardarDocumentosCompletarPdf', 'documentos\archivoController@guardarDocumentosCompletarPdf');
Route::post('obtenerObservacion', 'documentos\archivoController@obtenerObservacion');

//GPControlers derivacion forzada obtenerSupervisoresAgencia
Route::get('obtenerUsuario/{user}', 'ApiGPSIPController@obtenerUsuario');
Route::get('obtenerSupervisoresAgencia/{id_agencia}', 'ApiGPSIPController@obtenerSupervisoresAgencia');
Route::get('obtenerUsuariosDelNodo/{id_nodo}', 'ApiGPSIPController@obtenerUsuariosDelNodo');
Route::get('obtenerUsuarioDerivar/{user}', 'ApiGPSIPController@obtenerUsuarioDerivar');
Route::post('obtenerDepartamento', 'ApiVySController@obtenerDepartamento');

//Asignar tramite a quien le llego un CVEAP
Route::post('asignarUsuarioResp/{casId}', 'ApiVySController@asignarUsuarioResp');
Route::post('asignarUsuarioRespEnvio/{casId}', 'ApiVySController@asignarUsuarioRespEnvio');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'ApiController@logout');
    Route::post('listUsers', 'ApiController@listUsers');
});

//Rutas para los archivosa grabados en "archivos_pdf"
Route::get('archivos-pdf/{gestion}/{mes}', 'ArchivoPdfController@listarArchivosPDF');
Route::post('archivos-pdf', 'ArchivoPdfController@subirArchivoPDF');
Route::put('actualizarTramite', 'ArchivoPdfController@actualizarDatosTramite');
Route::put('actualizarDatosContrato', 'ArchivoPdfController@actualizarDatosContrato');
Route::put('adjuntarContrato', 'ArchivoPdfController@adjuntarContrato');
Route::get('archivoFirmaToken', 'ArchivoPdfController@verificaPdfFirmadoToken');

Route::put('estadoTramite', 'ArchivoPdfController@verificarEstadoTramite');

Route::put('derivarDatosTramite', 'ArchivoPdfController@derivarDatosTramite');
Route::put('derivacionTramite', 'ArchivoPdfController@derivacionTramite');

//Rutas para generar Reportes
Route::post('buscarCasosReporte', 'reportes\ReporteVySController@buscarCasosReporte');
Route::get('buscarDepartamento', 'reportes\ReporteVySController@buscarDepartamento');
Route::post('buscarRegional', 'reportes\ReporteVySController@buscarRegional');
Route::post('buscarAgencia', 'reportes\ReporteVySController@buscarAgencia');
Route::post('generarReporte', 'reportes\ReporteVySController@generarReporte');
Route::post('generarReporteCsv', 'reportes\ReporteVySController@generarReporteCsv');
Route::post('GetCorrelativo', 'reportes\ReporteVySController@GetCorrelativo');
Route::get('GetTmc', 'reportes\ReporteVySController@GetTmc');
Route::get('listarAreas', 'reportes\ReporteVySController@listarAreas');
Route::post('GetNotificacionFechas', 'reportes\ReporteVySController@GetAlertaFechaNotificacionPublicacion');
Route::post('GetFuncionPublicacionPM', 'reportes\ReporteVySController@GetFuncionPublicacionPM');

/*PARA ESTADOS AVANCE*/
/* Route::post('estadosAvance/{RegistrosXPagina}/{PaginaActual}', 'estados\estadoController@listarEAvance'); */
Route::get('estadosAvance/{RegistrosXPagina}/{PaginaActual}', 'estados\estadoController@listarEAvance');
Route::post('estadosAvance/', 'estados\estadoController@grabarEAvance');
Route::put('estadosAvance/{est_id}', 'estados\estadoController@actualizarEAvance');
Route::post('estadosAvance/{est_id}', 'estados\estadoController@eliminarEAvance');
Route::get('estadosAvance/{prc_id}', 'estados\estadoController@listarEAvanceXProceso');
Route::get('totalRegistros', 'estados\estadoController@getTotalRegistros');
Route::get('estadosAvance/', 'estados\estadoController@BuscarlistarEAvance');
/*PARA ESTADOS AVANCE*/

Route::get('busquedaCasosXNodoXUsuario/{usr_id}/{nodo_id}', 'ApiVySController@busquedaCasosXNodoXUsuario');
Route::get('busquedaCasosXNodo/{usr_id}/{nodo_id}', 'ApiVySController@busquedaCasosXNodo');

//Route::get('casosXNodoXUsuario/{usr_id}/{nodo_id}', 'ApiVySController@busquedaCasosXNodoXUsuario');

//Cierre de tramites
Route::post('tramitesCierre', 'ApiCierreTramites@tramitesCierre');
Route::post('obtenerDocumentoCierre', 'ApiCierreTramites@obtenerDocumentoCierre');
Route::put('anularCierre', 'ApiCierreTramites@anularCierre');
Route::post('verificaCierre', 'ApiCierreTramites@verificaCierre');
Route::post('tramitesCurso', 'ApiCierreTramites@tramitesCurso');

//Ruta Regional, Departamento, Agencia
Route::get('userDepartamento/', 'ApiController@listarDepartamento');
Route::get('userRegional/', 'ApiController@listarRegional');
Route::get('userAgencia/', 'ApiController@listarAgencia');

// CONSULTAS 1582
Route::post('consultaRegistro1582', 'ApiController@consultaRegistro1582');
Route::post('buscarBeneficiario1582', 'ApiVySController@buscarBeneficiario1582');
Route::post('cargar1582', 'ApiVySController@cargar1582');
Route::put('prestaciones1582', 'ApiVySController@prestaciones1582');
Route::post('crearOV1582', 'ApiCreacionTramitesController@crearOV1582');
Route::post('cargar1582c', 'ApiCreacionTramitesController@cargar1582');

Route::post('subirPDF1582/', 'ArchivoPdfController@subirPDF1582');
Route::post('grabarRechazo1582/', 'ArchivoPdfController@grabarRechazo1582');

Route::post('generarPDF1582', 'documentos\documentosPrevisualizacionController@generarPDF1582');
Route::post('generarTokenServicios', 'ApiVySController@generarTokenServicios');
Route::post('consultaPrestacionesConToken', 'ApiVySController@consultaPrestacionesConToken');

Route::prefix('v1')->group(function () {
    Route::put('deriva1582_62', 'ArchivoPdfController@deriva1582_62');
    Route::put('estadoDerivacionTramite', 'ArchivoPdfController@estadoDerivacionTramite');
    Route::put('retrocederTramite', 'ArchivoPdfController@retrocederTramite');
});

Route::get('usuariosNodo/{nodo_id}', 'ApiVySController@listarUsuariosNodo');
Route::post('asignarCaso', 'ApiVySController@asignarCaso');
Route::post('asignarCasoMasivo', 'ApiVySController@asignarCasoMasivo');

Route::get('contratosCasos/{act_id}/{cas_id}', 'ApiVySController@listarContratosCasos');

Route::post('derivarCasosFirma', 'ApiVySController@derivarCasosFirma');

/// --- Reporte Jubilacion---\\\
Route::post('casosReportejubilacion', 'reportesPrestaciones\jubilacionController@casosReporte');

//LEGAL
Route::post('prestacionesRequisitos', 'legal\LegalController@getPrestaciones');
Route::post('guardarDocumentosAdjuntosNfsLegal', 'legal\LegalController@guardarAdjuntosLegal');
Route::post('prestaciones', 'legal\LegalController@getApiPrestaciones');
Route::post('prestacionesProcesos', 'legal\LegalController@getApiPrestacionesIdProcesos');
Route::post('prestacionesSubCategoria', 'legal\LegalController@getApiPrestacionesIdProcesosSubCat');
Route::post('guardarDocumentosRequisitosNfs__CPE', 'documentos\documentosNfsController@guardarDocumento__CPE_estaticos'); //formulario-dinamico, los documentos estaticos
Route::post('guardarDocumentosRequisitosNfs__CPE_dinamico', 'documentos\documentosNfsController@guardarDocumento__CPE_dinamico'); //formulario-dinamico, los documentos-json-dinamicos
Route::get('listarProcesos', 'legal\LegalController@getApiProceso');
Route::post('getApiRequisitosId', 'legal\LegalController@getApiRequisitosId');
Route::post('lst_parentesco', 'legal\LegalController@getApiParentesco');
Route::post('lst_sinparentesco', 'legal\LegalController@getApiSinParentesco');

Route::post('prestacionesReq', 'legal\LegalController@getPrestacionesTipo');
Route::post('reasons', 'legal\LegalController@getApiRechazo');
Route::post('datosCaso', 'legal\LegalController@getApiRecuperacionDatos');
Route::post('postApiPrestacionesIdPadre', 'legal\LegalController@postApiPrestacionesIdPadre');
Route::post('datosCasoDocumentos', 'legal\LegalController@postRecuperacionDocumentos');
Route::post('datosJubPcc', 'legal\LegalController@datosJubPcc');
Route::post('datosJubRmin', 'legal\LegalController@datosJubRmin');
Route::post('datosPmPcc', 'legal\LegalController@datosPmPcc');
Route::post('datosRminPm', 'legal\LegalController@datosRminPm');
Route::post('datosJubPccDocumentos', 'legal\LegalController@datosJubPccDocumentos');

///--- listado de procesos menos legal ---\\\
Route::get('listarProcesosId', 'legal\LegalController@listarProcesosId');
Route::post('listarPrestacionesId', 'legal\LegalController@listarPrestacionesId');
Route::post('listarRequisitosId', 'legal\LegalController@listarRequisitosId');
Route::post('ValidarDatosJubPcc', 'legal\LegalController@ValidarDatosJubPcc');
Route::post('ValidarDatosJubRmin', 'legal\LegalController@ValidarDatosJubRmin');
Route::post('ValidarDatosPmPcc', 'legal\LegalController@ValidarDatosPmPcc');
///--- MASA HEREDITARIA ---\\\
Route::post('validarLegalMasa', 'masaHereditaria\masaHereditariaController@validarlegalMasa');
Route::post('datosLegalMasaHerederos', 'masaHereditaria\masaHereditariaController@datosLegalMasaHerederos');


///--- integracion tramites -  legal ---\\\
Route::post('getCasosLegalCua', 'legal\LegalController@getCasosLegalCua');
Route::post('getCasosLegalTramite', 'legal\LegalController@getCasosLegalTramite');
Route::post('obtenerDocumentoLegalGral', 'legal\LegalController@obtenerDocumentoLegalGral');
Route::post('enlazarPrestacionLegal', 'legal\LegalController@enlazarPrestacionLegal');
Route::post('buscarAsegurado', 'legal\LegalController@buscarAsegurado');


//Obtencion de datos para registro de seguimiento tramites
Route::get('datosSeguimientoTramite/{cas_id}', 'ApiVySController@datosSeguimientoTramite');
Route::get('datosDictamenRegistro/{cas_id}', 'ApiVySController@datosDictamenRegistro');

//Notificaciones
Route::post('notificacion', 'ArchivoPdfController@registraNotificacion');
Route::get('listaNotificaciones/{RegistrosXPagina}/{PaginaActual}', 'ArchivoPdfController@listaNotificaciones');
Route::put('actualizarEstadoNotificacion/{id}', 'ArchivoPdfController@actualizarEstadoNotificacion');

// subir PDF servicio de Joaquin para notificaciones
Route::post('subirRespuestaPDF/', 'ArchivoPdfController@subirRespuestaPDF');

// observar Tramite - JUB - RMIN - GFU - MAHER
Route::post('observarTramite', 'ArchivoPdfController@observarTramite');
Route::post('datosSeguimientoTramiteUpdate', 'ApiVySController@datosSeguimientoTramiteUpdate');

// derivarCasoSIP
Route::put('derivarCasoSip/{cas_id}', 'ArchivoPdfController@derivarCasoSip');
// Route::put('SolicitudPrestaciones/{cas_id}', 'ArchivoPdfController@SolicitudPrestaciones');
Route::put('derivarCasos', 'ArchivoPdfController@derivarCasos');
//  GASTOS FUNERARIOS
Route::post('datosSolicitanteCobrador', 'gastosFunerarios\gastosFunerariosController@datosSolicitanteCobrador');
Route::post('datosParentesco', 'gastosFunerarios\gastosFunerariosController@datosParentesco');

// CREAR CLONES DE TRAMITES JUBILACION PARA LA 035
Route::post('duplicarCasoJubilacion', 'ApiVySController@duplicarCasoJubilacion');
Route::post('validarBoleta', 'ApiVySController@validarBoleta');
Route::post('boletasPago', 'ApiVySController@boletasPago');
Route::post('boletasPendientesCobro', 'ApiVySController@boletasPendientesCobro');

///Servicios de fallecidos
Route::post('registroFallecido', 'servicioGestora\fallecidosSslpController@registroFallecido');
Route::post('verificacionFallecido', 'servicioGestora\fallecidosSslpController@verificacionFallecido');

///Servicios de estructura  familiar prestaciones

Route::post('registroActualizacionEstadoPrestacion', 'servicioGestora\estructuraFamiliarPrestacionesController@updateStateTramite');
Route::post('buscarTramites', 'servicioGestora\tramiteSipController@buscarCasos');
Route::post('buscarTramitesGFU', 'servicioGestora\tramiteSipController@buscarCasosGFU');
Route::post('buscarTramitesJUB', 'servicioGestora\tramiteSipController@buscarCasosJUB');

Route::post('dias_festivos', 'servicioGestora\tramiteSipController@dias_festivos');
Route::post('obtenerSolicitante', 'servicioGestora\tramiteSipController@obtenerSolicitante');

Route::post('/upload-controller', 'SettingsController@uploadController');
Route::put('/servicioTramiteSIP', 'ACGController@servicioTramiteSIP');
Route::post('/overwrite-controller', 'SettingsController@overwriteController');

///Servicios de tutores
Route::post('/grabarTutor', 'tutor\tutorController@grabarTutor');
Route::post('/getTutor', 'tutor\tutorController@getTutor');
Route::post('actualizarDatosContratoJUB', 'ApiVySController@actualizarDatosContrato');
Route::post('buscarAsegurado1582', 'ApiVySController@buscarAsegurado1582');

// servicio para completar beneficiarios de la 1582
Route::post('beneficiarioParentesco', 'ApiVySController@beneficiarioParentesco');
// servicio consulta cenPersonaSIP
Route::get('cenPersonaSIP', 'ApiVySController@cenPersonaSIP');
Route::post('soporteService1582', 'soporte\Soporte1582Controller@soporteService1582');

require __DIR__ . '/graficas_api.php';

Route::prefix('v1')->group(function () {
    Route::post('/mensajeriatramiteSip', 'MensajesController@mensajeriatramiteSip');
    Route::get('/listarMensajesSip', 'MensajesController@listarMensajesSip');
    Route::get('/notificaciones', 'NotificacionesController@ListarNotificaciones');
    Route::get('/notificationes/{id}', 'NotificacionesController@DetalleNotificacion');
    Route::get('/listarnotifipanel', 'MensajesController@listarNotifiPanel');
    Route::post('/marcarLeido', 'NotificacionesController@marcarLeidoNoti');
    Route::get('/verificarHistorico', 'MensajesController@verificarHistorico');
});

// consumo de servicios externos para leer mensajes
Route::get('/leerMensajes', 'MensajesController@leerMensajes');
Route::post('/enviarMensajes', 'MensajesController@enviarMensajes');

// use-up of quarkus services (like gtalento web services).
Route::post('quarkusVerificarFuncionario', 'externalServices\QuarkusAPIController@quarkusVerificarFuncionario');
Route::post('quarkusTramitesPoder', 'externalServices\QuarkusAPIController@quarkusTramitesPoder');
// servicios-internos asociados a servicios-externos.
Route::post('parametricaDeParametricas', 'externalServices\LegalAPIController@parametricaDeParametricas');
Route::post('fetchConfigDataFromAnyCodProcess', 'externalServices\LegalAPIController@fetchConfigDataFromAnyCodProcess');
Route::post('determinamosElTipoCaso', 'externalServices\LegalAPIController@determinamosElTipoCaso');

// reportes y graficas para tramites legal

Route::get('datosSeguimientoTramLegaldDuplicados', 'ApiVySController@datosSeguimientoTramLegaldDuplicados');

Route::prefix('v1')->group(function () {
    Route::post('reporteLegal', 'reportes\ReporteLegalController@generarReporteLegal');
    Route::get('getuserLegal', 'reportes\ReporteLegalController@GetUserLegal');
    Route::post('generarExcel', 'reportes\ReporteLegalController@generarExcel');
    Route::post('generarCsv', 'reportes\ReporteLegalController@generarCsv');

    Route::post('generarReporteTramiteXUsuario', 'reportes\ReporteLegalController@generarReporteTramiteXUsuario');

    Route::post('generarExcelXUsuarios', 'reportes\ReporteLegalController@generarExcelTramiteXUsuario');
    Route::post('generarCsvXUsuarios', 'reportes\ReporteLegalController@generarCsvTramiteXUsuario');

    Route::get('validacionFecha', 'reportes\ReporteLegalController@validaFechas');

    Route::get('generarPendientesGNL', 'reportes\ReporteLegalController@generarPendientesGNL');
});


// Servicios de calculo semi automaticos en el actividad 65
Route::get('calculosSemiAutomaticos', 'ApiVySController@calculosSemiAutomaticos');
Route::put('guardarDatosServicioSemiAutomaticos', 'ApiVySController@guardarDatosServicioSemiAutomaticos');
Route::put('actualizarEstTramCalculosSemiAutomaticos', 'ApiVySController@actualizarEstTramCalculosSemiAutomaticos');


// Route::get('/ldap', 'ldapController@Conexionldap');
Route::post('/ldap-login', 'ldapAuthController@authenticate');

// Servicios expuestos para otros sistemas de la gestora
Route::post('consultaHbtCasos', 'externalServices\HBTController@consultaHbtCasos');

// Logs api
Route::put('guardarLogApi', 'LogsController@guardarLogsController');

// control firma documentos
Route::post('resetearFirmaDoc', 'ApiVySController@resetearFirmaDoc');
Route::post('resetearFirmaDocDesp', 'ApiVySController@resetearFirmaDocDesp');


// get Documentos por ID
Route::post('getDocumentosIdApiUcpp', 'ArchivoPdfController@getDocumentosByIdApiUcpp');