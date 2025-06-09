<?php

namespace App\Http\Controllers\documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use TCPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Http\Services\DocumentoService;

class documentosNfsController extends Controller
{
    protected $documentService;

    public function __construct(DocumentoService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function guardarDocumento(Request $request)
    {
        $cantidadElementos = count($request->all());
        $pfrm_value = '';
        $success = array("code" => 200, "mensaje" => 'OK', );
        $parentesco = isset($request[0]) && isset($request[0]['parentesco']) ? $request[0]['parentesco'] : '';
        if (!empty($parentesco)) {
            $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?,?)', array($request[0]['caso'], $parentesco, $request[0]['ci'], $request[0]['valor_descripcion']));
            $result = $dataRespon[0]->sp_existe_documento_inicial;
        } else {
            $result = false;
        }
        //$parentesco = $request[0]['parentesco'] === null ? '' : $request[0]['parentesco'];
        if ($result) {
            for ($i = 0; $i < $cantidadElementos; $i++) {
                $tam = $request[$i]['tam'];
                $valor_id = $request[$i]['valor_id'];
                $valor_descripcion = $request[$i]['valor_descripcion'];
                $pdfAsBase64 = $request[$i]['pdf'];
                $caso = $request[$i]['caso'];
                $id_caso = $request[$i]['id_caso'];
                $parentesco = $request[$i]['parentesco'] === null ? '' : $request[$i]['parentesco'];
                $ci = $request[$i]['ci'];
                $documentoOriginalObligatorio = $request[$i]['documentoOriginalObligatorio'];
                $presentacionObligatoria = $request[$i]['presentacionObligatoria'];
                $switch = $request[$i]['switch'];
                $id_persona_sip = $request[$i]['id_persona_sip'];
                $id_observacion = $request[$i]['id_observacion'] === null ? 1 : $request[$i]['id_observacion'];
                $detalle_documento = $request[$i]['detalle_documento'];
                $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?,?)', array($request[$i]['caso'], $parentesco, $request[$i]['ci'], $request[$i]['valor_descripcion']));
                $result = $dataRespon[0]->sp_existe_documento_inicial;
                $usr_id = $request[$i]['usr_id'];
                if ($tam == 2000) {
                    $pfrm_value = $request[$i]['pfrm_value'];
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                } else {
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                }
                if ($result) {
                    $dataRespon = \DB::select("select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id and doc_descripcion='$valor_descripcion' ");
                    $doc_id = $dataRespon[0]->doc_id;
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                } else {
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->guardarDocumentoPdf($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->guardarDocumentoPdf($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                }
            }
        } else {
            $this->guardarInicio($request->all());
            for ($i = 0; $i < $cantidadElementos; $i++) {
                $tam = $request[$i]['tam'];
                $valor_id = $request[$i]['valor_id'];
                $valor_descripcion = $request[$i]['valor_descripcion'];
                $pdfAsBase64 = $request[$i]['pdf'];
                $caso = $request[$i]['caso'];
                $id_caso = $request[$i]['id_caso'];
                $parentesco = $request[$i]['parentesco'] === null ? '' : $request[$i]['parentesco'];
                $ci = $request[$i]['ci'];
                $documentoOriginalObligatorio = $request[$i]['documentoOriginalObligatorio'];
                $presentacionObligatoria = $request[$i]['presentacionObligatoria'];
                $switch = $request[$i]['switch'];
                $id_persona_sip = $request[$i]['id_persona_sip'];
                $id_observacion = $request[$i]['id_observacion'] === null ? 1 : $request[$i]['id_observacion'];
                $detalle_documento = $request[$i]['detalle_documento'];
                $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?,?)', array($request[$i]['caso'], $parentesco, $request[$i]['ci'], $request[$i]['valor_descripcion']));
                $result = $dataRespon[0]->sp_existe_documento_inicial;
                $usr_id = $request[$i]['usr_id'];
                if ($tam == 2000) {
                    $pfrm_value = $request[$i]['pfrm_value'];
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                } else {
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                }
                if ($result) {
                    $dataRespon = \DB::select("select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id and doc_descripcion='$valor_descripcion' ");
                    $doc_id = $dataRespon[0]->doc_id;
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                } else {
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->guardarDocumentoPdf($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->guardarDocumentoPdf($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                }
            }
        }
        return response()->json(["data" => [], "codigoRespuesta" => $success]);
    }

    public function guardarDocumento__CPE_estaticos(Request $request)
    {
        $documents = $request->all(); //comentario
        $success = array("code" => 200, "mensaje" => 'OK', );
        $estadoActivo = 'A';
        $result = "";
        // print_r($documents);
        foreach ($documents as $document) {
            //a) guardamoso el documento en el file-system.
            $urlDocumento = $this->documentService->getDocumento($document['cmp__file_base64data'], $document['cmp__cas_id'], $document['cmp__cas_cod_id']);

            if (isset($document['docPersistido__doc_id'])) {
                //path update and hitorico
                $dataRespon = \DB::select(
                    'CALL public.fn_actualizar_documento_estaticos(?, 	?, 	?, 	?, 	?, 	?, 	?::varchar, ?, 	?, 	?, 	?, 	?, 	?, 	?, 	? , ?)',
                    array(
                        $document['docPersistido__doc_id'],
                        $document['cmp__cas_id'],
                        $document['cmp__user_id'],
                        $document['cmp__cas_cod_id'],
                        $document['cmp__categoria'],
                        $urlDocumento,
                        $document['cmp__user_name'],
                        $estadoActivo,
                        $document['config__nombre'],
                        $document['cmp__id_persona_sip'],
                        $document['cmp__cite'],
                        $document['docPersistido__doc_value_es_original'],
                        10,
                        null,
                        $document['config__id'],
                        $document['cmp__cas_act_id']
                    )
                );
            } else {
                /*dd(
                    $document['cmp__cas_id'],
                            $document['cmp__user_id'],
                            $document['cmp__cas_cod_id'],
                            $document['cmp__categoria'],
                            $urlDocumento,
                            $document['cmp__user_name'],
                            $estadoActivo,
                            $document['config__nombre'],
                            $document['cmp__id_persona_sip'],
                            $document['cmp__cite'],
                            $document['docPersistido__doc_value_es_original'],
                            10,
                            null,
                            $document['config__id'],
                            $document['cmp__cas_act_id']
                );*/
                //path simple insert
                $dataRespon = \DB::select(
                    'CALL public.fn_guardar_insert_documento( 	?, 	?, 	?, 	?, 	?, 	?::varchar, ?, 	?, 	?, 	?, 	?, 	?, 	?, 	? , ?)',
                    array(
                        $document['cmp__cas_id'],
                        $document['cmp__user_id'],
                        $document['cmp__cas_cod_id'],
                        $document['cmp__categoria'],
                        $urlDocumento,
                        $document['cmp__user_id'],
                        $estadoActivo,
                        $document['config__nombre'],
                        $document['cmp__id_persona_sip'],
                        $document['cmp__cite'],
                        $document['docPersistido__doc_value_es_original'],
                        10,
                        null,
                        $document['config__id'],
                        $document['cmp__cas_act_id']
                    )
                );


            }
        }
        return response()->json(["data" => [$result], "codigoRespuesta" => $success]);
    }


    public function guardarDocumentosRequisitosNfsLegal(Request $request)
    {
        //dd('legal esta ');selectedValue
        $cantidadElementos = count($request->all());
        $pfrm_value = '';
        $success = array("code" => 200, "mensaje" => 'OK', );
        $parentesco = isset($request[0]) && isset($request[0]['parentesco']) ? $request[0]['parentesco'] : '';
        if (!empty($parentesco)) {
            $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial_legal(?,?,?,?,?)', array($request[0]['caso'], $parentesco, $request[0]['ci'], $request[0]['valor_descripcion'], $request[0]['selectedValue']));
            $result = $dataRespon[0]->sp_existe_documento_inicial_legal;
        } else {
            $result = false;
        }
        // dd($cantidadElementos);
        //$parentesco = $request[0]['parentesco'] === null ? '' : $request[0]['parentesco'];
        if ($result) {
            for ($i = 0; $i < $cantidadElementos; $i++) {
                $tam = $request[$i]['tam'];
                $valor_id = $request[$i]['valor_id'];
                $valor_descripcion = $request[$i]['valor_descripcion'];
                $pdfAsBase64 = $request[$i]['pdf'];
                $caso = $request[$i]['caso'];
                $id_caso = $request[$i]['id_caso'];
                $parentesco = $request[$i]['parentesco'] === null ? '' : $request[$i]['parentesco'];
                $ci = $request[$i]['ci'];
                $documentoOriginalObligatorio = $request[$i]['documentoOriginalObligatorio'];
                $presentacionObligatoria = $request[$i]['presentacionObligatoria'];
                $switch = $request[$i]['switch'];
                $id_persona_sip = $request[$i]['id_persona_sip'];
                $id_observacion = $request[$i]['id_observacion'] === null ? 1 : $request[$i]['id_observacion'];
                $detalle_documento = $request[$i]['detalle_documento'];
                $doc_prestacion = $request[$i]['selectedValue'];
                $usr_id = $request[$i]['usr_id'];
                $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?,?)', array($request[$i]['caso'], $parentesco, $request[$i]['ci'], $request[$i]['valor_descripcion']));
                $result = $dataRespon[0]->sp_existe_documento_inicial;
                if ($tam == 2000) {
                    $pfrm_value = $request[$i]['pfrm_value'];
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                } else {
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                }
                if ($result) {
                    $sql = "select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id and doc_descripcion='$valor_descripcion' ";
                    $dataRespon = \DB::select("select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id and doc_descripcion='$valor_descripcion' ");
                    $doc_id = $dataRespon[0]->doc_id;
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->actualizarDocumentoPdfLegal($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->actualizarDocumentoPdfLegal($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                } else {
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->guardarDocumentoPdfLegal($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->guardarDocumentoPdfLegal($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                }
            }
        } else {
            $this->guardarInicio($request->all());
            for ($i = 0; $i < $cantidadElementos; $i++) {
                $tam = $request[$i]['tam'];
                $valor_id = $request[$i]['valor_id'];
                $valor_descripcion = $request[$i]['valor_descripcion'];
                $pdfAsBase64 = $request[$i]['pdf'];
                $caso = $request[$i]['caso'];
                $id_caso = $request[$i]['id_caso'];
                $parentesco = $request[$i]['parentesco'] === null ? '' : $request[$i]['parentesco'];
                $ci = $request[$i]['ci'];
                $documentoOriginalObligatorio = $request[$i]['documentoOriginalObligatorio'];
                $presentacionObligatoria = $request[$i]['presentacionObligatoria'];
                $switch = $request[$i]['switch'];
                $id_persona_sip = $request[$i]['id_persona_sip'];
                $id_observacion = $request[$i]['id_observacion'] === null ? 1 : $request[$i]['id_observacion'];
                $detalle_documento = $request[$i]['detalle_documento'];
                $doc_prestacion = $request[$i]['selectedValue'];
                $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?,?)', array($request[$i]['caso'], $parentesco, $request[$i]['ci'], $request[$i]['valor_descripcion']));
                $usr_id = $request[$i]['usr_id'];
                $result = $dataRespon[0]->sp_existe_documento_inicial;
                if ($tam == 2000) {
                    $pfrm_value = $request[$i]['pfrm_value'];
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                } else {
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                }
                if ($result) {
                    $dataRespon = \DB::select("select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id and doc_descripcion='$valor_descripcion' ");
                    $doc_id = $dataRespon[0]->doc_id;
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->actualizarDocumentoPdfLegal($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->actualizarDocumentoPdfLegal($doc_id, $id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                } else {
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->guardarDocumentoPdfLegal($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->guardarDocumentoPdfLegal($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento, $doc_prestacion);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                }
            }
        }
        return response()->json(["data" => [], "codigoRespuesta" => $success]);
    }
    public function guardarInicio($request)
    {
        $cantidadElementos = count($request);
        for ($i = 0; $i < $cantidadElementos; $i++) {
            $tam = $request[$i]['tam'];
            $valor_id = $request[$i]['valor_id'];
            $valor_descripcion = $request[$i]['valor_descripcion'];
            $pdfAsBase64 = $request[$i]['pdf'];
            $caso = $request[$i]['caso'];
            $id_caso = $request[$i]['id_caso'];
            $parentesco = $request[$i]['parentesco'] === null ? '' : $request[$i]['parentesco'];
            $ci = $request[$i]['ci'];
            $documentoOriginalObligatorio = $request[$i]['documentoOriginalObligatorio'];
            $presentacionObligatoria = $request[$i]['presentacionObligatoria'];
            $switch = $request[$i]['switch'];
            $id_persona_sip = $request[$i]['id_persona_sip'];
            $id_observacion = $request[$i]['id_observacion'] === null ? 1 : $request[$i]['id_observacion'];
            $detalle_documento = $request[$i]['detalle_documento'];
            $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?,?)', array($request[$i]['caso'], $parentesco, $request[$i]['ci'], $request[$i]['valor_descripcion']));
            $result = $dataRespon[0]->sp_existe_documento_inicial;
            if ($tam == 2000) {
                $pfrm_value = $request[$i]['pfrm_value'];
                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                /// dd($dataHistorico);
            } else {
                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
            }

            $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
        }
    }

    public function guardarDocumentoUno(Request $request)
    {
        try {
            $pfrm_value = $request['pfrm_value'];
            $tam = $request['tam'];
            $valor_id = $request['valor_id'];
            $valor_descripcion = $request['valor_descripcion'];
            $pdfAsBase64 = $request['pdf'];
            $caso = $request['caso'];
            $id_caso = $request['id_caso'];
            $parentesco = $request['parentesco'];
            $ci = $request['ci'];
            $documentoOriginalObligatorio = $request['documentoOriginalObligatorio'];
            $presentacionObligatoria = $request['presentacionObligatoria'];
            $switch = $request['switch'];
            $id_persona_sip = $request['id_persona_sip'];
            $id_observacion = $request['id_observacion'] === '' ? 1 : $request['id_observacion'];
            $detalle_documento = $request['detalle_documento'];
            $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?)', array($request['caso'], $request['parentesco'], $request['ci']));
            //$usrId = $request['usr_id'];
            $usrId = $request->get('usr_id', 1);
            $result = $dataRespon[0]->sp_existe_documento_inicial;
            if ($result) {
                if ($tam == 2000) {
                    /* $dataHistorico = \DB::select("SELECT *
                        from rmx_vys_historico_casos
                        inner join rmx_vys_actividades on act_id = htc_cas_act_id
                        inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                        inner join users on id = htc_cas_usr_id
                    where htc_cas_id =  $id_caso order by htc_id desc limit 1");*/
                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");
                } else {
                    $dataHistorico = \DB::select("SELECT * from  public.rmx_vys_casos inner join public.rmx_vys_historico_casos on cas_id = htc_cas_id where  cas_id =  $id_caso    order by htc_cas_id desc limit 1");
                }
                $success = array('code' => 200, 'mensaje' => 'OK');

                $dataRespon = \DB::select("select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id  and doc_descripcion='$valor_descripcion'  ");
                if (sizeof($dataRespon) > 0) {
                    $doc_id = $dataRespon[0]->doc_id;
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '',  $id_caso)");
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                    return response()->json(['data' => '{}', 'success' => $success]);
                } else {
                    if ($pdfAsBase64 == '') {
                        $respuesta = $this->guardarDocumentoPdf($id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '',  $id_caso)");
                    } else {
                        $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                        $respuesta = $this->guardarDocumentoPdf($id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                        $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$resGetDocumento',  $id_caso)");
                    }
                    return response()->json(['data' => '{}', 'success' => $success]);
                }
            } else {
                if ($tam == 2000) {
                    $dataHistorico = \DB::select("SELECT *
	                    from rmx_vys_historico_casos
	                    inner join rmx_vys_actividades on act_id = htc_cas_act_id
	                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
	                    inner join users on id = htc_cas_usr_id
                    where htc_cas_id =  $id_caso order by htc_id desc limit 1");
                } else {
                    $dataHistorico = \DB::select("SELECT * from  public.rmx_vys_casos inner join public.rmx_vys_historico_casos on cas_id = htc_cas_id where  cas_id =  $id_caso    order by htc_cas_id desc limit 1");
                }
                $success = array('code' => 200, 'mensaje' => 'OK');
                if ($pdfAsBase64 == '') {

                    $respuesta = $this->guardarDocumentoPdf($id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '',  $id_caso)");
                } else {
                    $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso, $caso);
                    $respuesta = $this->guardarDocumentoPdf($id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $resGetDocumento, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', ' $resGetDocumento',  $id_caso)");
                }
                return response()->json(['data' => '{}', 'success' => $success]);
            }
        } catch (\Exception $e) {
            //Log::error('Error en guardarDocumentoUno: ' . $e->getMessage());
            return response()->json(['data' => '{}', 'success' => ['code' => 500, 'mensaje' => 'Error interno del servidor']], 500);
        }
    }

    public function limpiarAdjunto(Request $request)
    {
        $caso = $request['caso'];
        $id_caso = $request['id_caso'];
        $parentesco = $request['parentesco'];
        $doc_id = $request['doc_id'];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $respuesta = \DB::select("UPDATE public._gp_documentos  set      doc_estado = 'B', doc_url = '',
            doc_detalle_documento = '', doc_id_observacion = 1, doc_codigo = '',doc_cas_id = 0, doc_his_id = 0, doc_copia_original = false where doc_id = $doc_id;");
            return response()->json(["data" => $respuesta, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function limpiarDerechohabiente(Request $request)
    {
        $cas_id = $request->input('cas_id');
        $id_persona_sip = $request->input('id_persona_sip');
        $ci_dh = $request->input('ci_dh');
        //$usrId = $request->input('usrId');
        $usrId = $request->get('usr_id', 1);
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $respuesta = \DB::select("UPDATE _gp_documentos
                    SET doc_cas_id = 0, doc_his_id = 0, doc_codigo = '',doc_categoria = '' ,doc_id_persona_sip= 0, doc_estado = 'X', doc_usr_id = $usrId
                    WHERE doc_id_persona_sip = $id_persona_sip
                    AND doc_categoria = $ci_dh
                    AND doc_codigo = '$cas_id';");
            return response()->json(["data" => $respuesta, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function limpiarDocumentoAdjunto(Request $request)
    {
        $doc_id_adjunto = $request['doc_id_adjunto'];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $respuesta = \DB::select("UPDATE public._gp_documentos  set doc_estado = 'B', doc_url = '' where doc_id = $doc_id_adjunto ");
            return response()->json(["data" => $respuesta, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function guardarDocumentoPdf($pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $pdoc_detalle_documento)
    {
        $id_persona_sip = str_replace(' ', '', $id_persona_sip);
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documento (0,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo',
                                '$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,
                                '$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria','$switch1',
                                '$id_persona_sip','$id_observacion','$pdoc_detalle_documento' ,1)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function guardarDocumentoPdfLegal($pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $pdoc_detalle_documento, $pdoc_prestacion)
    {
        $id_persona_sip = str_replace(' ', '', $id_persona_sip);
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documento_legal(0,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo',
                                '$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,
                                '$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria','$switch1',
                                '$id_persona_sip','$id_observacion','$pdoc_detalle_documento','$pdoc_prestacion' ,1)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }


    public function guardarDocumentoPdfBC($pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $palerta, $id_observacion, $pdoc_detalle_documento)
    {
        $id_persona_sip = str_replace(' ', '', $id_persona_sip);
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documentacioncomplementaria(0,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo',
                                '$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,
                                '$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria','$switch1',
                                '$id_persona_sip', '$id_observacion',  '$pdoc_detalle_documento', '$palerta', 1)");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function actualizarDocumentoPdf($doc_id, $pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $pdoc_detalle_documento)
    {
        // dd($doc_id, $pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch);

        $id_persona_sip = str_replace(' ', '', $id_persona_sip);
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $sql = "SELECT * FROM  public.crud_documento ($doc_id,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo',
                                '$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,
                                '$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria', '$switch1',
                                '$id_persona_sip','$id_observacion','$pdoc_detalle_documento' ,2)  ";
            // dd($sql);
            $data = \DB::select("SELECT * FROM  public.crud_documento ($doc_id,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo',
                                '$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,
                                '$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria', '$switch1',
                                '$id_persona_sip','$id_observacion','$pdoc_detalle_documento' ,2)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function actualizarDocumentoPdfLegal($doc_id, $pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $pdoc_detalle_documento, $pdoc_prestacion)
    {
        $id_persona_sip = str_replace(' ', '', $id_persona_sip);
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documento_legal ($doc_id,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo',
                                '$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,
                                '$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria', '$switch1',
                                '$id_persona_sip','$id_observacion','$pdoc_detalle_documento','$pdoc_prestacion',2)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function verDocumentoPdf($id, Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');
        $sql = "select * from public._gp_documentos where doc_id = $id";
        $data = \DB::select($sql);

        $ip = request()->ip();
        $data_json = json_encode($data, 0);
        $data_log = \DB::select(
            "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
            array($sql, $data_json, $usuario, $pro, $tramite, $ip)
        );


        if (empty($data)) {
            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'>No Existe Registro!!!</div>";
            echo "</body></html>";
            exit(1);

        }
        $arraycontent = explode('/', $data[0]->doc_url);

        if (isset($arraycontent[6])) {
            $serv = '/opt/documental/';
            $nomfile = $arraycontent[6];
        } else {
            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
          .error-message {
                background-color: #f2eedf;
                color: #d46e00;
                border: 1px solid #ffa500;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'> Documento No registrado !!!</div>";
            echo "<div class='error-message'> Registrar porfavor </div>";
            echo "</body></html>";
            exit(1);
        }

        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data[0]->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                globalGetPdfDocument($rutaCompleta, $nomfile);
                exit(0);
            } else {
                echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
                echo "<div class='error-message'>No Existe Documento!!!</div>";
                echo "</body></html>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("Location: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento3333!!!</b>";
            }
            exit(1);
        }
    }
    public function verDocumentoPdf_aud($id, Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');
        $sql = "select registros from public.gp_documentos_aud where id_aud = $id";
        $data = \DB::select($sql);
        $data = $data[0]->registros;
       // dd($data);
        $ip = request()->ip();
        $data = json_decode($data, 0);
       // dd($data->doc_url);

        if (empty($data)) {
            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'>No Existe Registro!!!</div>";
            echo "</body></html>";
            exit(1);

        }
        $arraycontent = explode('/', $data->doc_url);

        if (isset($arraycontent[6])) {
            $serv = '/opt/documental/';
            $nomfile = $arraycontent[6];
        } else {
            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
          .error-message {
                background-color: #f2eedf;
                color: #d46e00;
                border: 1px solid #ffa500;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'> Documento No registrado !!!</div>";
            echo "<div class='error-message'> Registrar porfavor </div>";
            echo "</body></html>";
            exit(1);
        }

        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                // header('Content-Description: File Transfer');
                // header('Content-Type: application/pdf');
                // header('Content-Disposition: inline; filename="' . basename($nomfile) . '"');
                // header('Expires: 0');
                // header('Cache-Control: must-revalidate');
                // header('Pragma: public');
                // header('Content-Length: ' . filesize($rutaCompleta));
                // readfile($rutaCompleta);
                globalGetPdfDocument($rutaCompleta, $nomfile);
                exit(0);
            } else {
                echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
                echo "<div class='error-message'>No Existe Documento!!!</div>";
                echo "</body></html>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("Location: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento3333!!!</b>";
            }
            exit(1);
        }
    }

    public function verDocumentoPdf1582($id_doc)
    {
        $data = \DB::select("select * from public._gp_documentos_1582 where id = $id_doc");

        if (empty($data)) {
            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'>No Existe Registro!!!</div>";
            echo "</body></html>";
            exit(1);
        }

        $arraycontent = explode('/', $data[0]->doc_url);

        if (isset($arraycontent[6])) {
            $serv = '/opt/documental/';
            $nomfile = $arraycontent[6];
        } else {

            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
          .error-message {
                background-color: #f2eedf;
                color: #d46e00;
                border: 1px solid #ffa500;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'> Documento No registrado !!!</div>";
            echo "<div class='error-message'> Registrar porfavor </div>";
            echo "</body></html>";
            exit(1);
        }

        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data[0]->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                // header('Content-Description: File Transfer');
                // header('Content-Type: application/pdf');
                // header('Content-Disposition: inline; filename="' . basename($nomfile) . '"');
                // header('Expires: 0');
                // header('Cache-Control: must-revalidate');
                // header('Pragma: public');
                // header('Content-Length: ' . filesize($rutaCompleta));
                // readfile($rutaCompleta);
                globalGetPdfDocument($rutaCompleta, $nomfile);
                exit(0);
            } else {
                echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
                echo "<div class='error-message'>No Existe Documento!!!</div>";
                echo "</body></html>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("Location: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento!!!</b>";
            }
            exit(1);
        }
    }


    public function verDocumentoPdf1582Doc($apiuuid)
    {
        $data = DB::select("select * from public._gp_documentos_1582 where apiuuid = ? order by id desc limit 1", [$apiuuid]);

        if (empty($data)) {
            return response()->json([
                "data" => $data,
                "codigoRespuesta" => 500,
            ]);
        } else {
            $rutaCompleta = $data[0]->doc_url;

            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                $fileContent = file_get_contents($rutaCompleta);
                $base64Content = base64_encode($fileContent);

                return response()->json(
                    [
                        "data" => $base64Content,
                        "url" => $rutaCompleta,
                        "codigoRespuesta" => 200
                    ]
                );
            } else {
                return response()->json(
                    [
                        "codigoRespuesta" => 404,
                        "data" => $data,
                    ]
                );
            }
        }
    }

    //encontrado 623189 encontrado 617576 encontrado 623632 encontrado 623494
    public function verDocumetos10fallidoValidar($id)
    {
        // $numeros = [623423, 617576, 623494];
        // $contadorExistentes = 0;

        // foreach ($numeros as $numero) {
        //     $url = "https://tramitesip.gestora.bo/api/verDocumentoPdfNfs/" . $numero;
        //     echo $url;
        //     echo '   ';


        //     $response = Http::get($url);
        //     dd($response);
        //     if ($response->successful()) {
        //         $contadorExistentes++;
        //     }
        // }

        // // Retorna el contador de documentos que existen
        // return response()->json([
        //     'documentos_existentes' => $contadorExistentes,
        //     'total_documentos' => count($numeros),
        // ]);


        $numeros = [];
        $documentosExistentes = [];
        $documentosnoExistentes = [];


        foreach ($numeros as $numero) {
            $url = "https://tramitesip.gestora.bo/api/verDocumentoPdfNfs/" . $numero;
            $response = Http::get($url);



            if ($response->successful()) {
                $contenidoBase64 = base64_encode($response->body());

                // Verificar si el contenido base64 es válido

                if ($contenidoBase64 == 'PGI+Tm8gRXhpc3RlIERvY3VtZW50byEhITwvYj4=') {
                    $documentosnoExistentes[] = [
                        'numero' => $numero,
                        'contenido' => $contenidoBase64

                    ];
                } else {
                    $documentosExistentes[] = [
                        'numero' => $numero,
                        // 'contenido' => $contenidoBase64

                    ];

                }
            }
        }

        if (count($documentosExistentes) > 0) {
            return response()->json([
                'documentos_existentes' => count($documentosExistentes),


                'total_documentos' => count($numeros),
                'documentosexistentes' => $documentosExistentes,
                'documentos_no_existentes' => count($documentosnoExistentes),
                'documentonosexistentes' => $documentosnoExistentes,
            ]);
        } else {
            return response()->json(['error' => 'No se pudo obtener ningún documento'], 404);
        }




        // $numeros = [623423, 617576, 623494];
        // $documentosExistentes = [];

        // foreach ($numeros as $numero) {
        //     $url = "https://tramitesip.gestora.bo/api/verDocumentoPdfNfs/" . $numero;
        //     $response = Http::get($url);

        //     if ($response->successful()) {
        //         // Agrega el contenido del documento al array junto con el número
        //         $documentosExistentes[] = [
        //             'numero' => $numero,
        //             'contenido' => base64_encode($response->body()) // Codificar en base64 para enviar de forma segura
        //         ];
        //     }
        // }

        // if (count($documentosExistentes) > 0) {
        //     return response()->json([
        //         'documentos_existentes' => count($documentosExistentes),
        //         'total_documentos' => count($numeros),
        //         'documentos' => $documentosExistentes
        //     ]);
        // } else {
        //     return response()->json(['error' => 'No se pudo obtener ningún documento'], 404);
        // }


        // $url = "https://tramitesip.gestora.bo/api/verDocumentoPdfNfs/" . $id;
        // $response = Http::get($url);
        // if ($response->successful()) {
        //     return response($response->body())
        //         ->header('Content-Type', 'application/pdf');
        // } else {
        //     return response()->json(['error' => 'No se pudo obtener el documento'], $response->status());
        // }
    }

    private function isValidBase64($string)
    {
        // Verificar si el string es válido en base64 decodificándolo
        $decoded = base64_decode($string, true);
        // Si la decodificación falla, retornará false; si tiene éxito, es válido
        return $decoded !== false && base64_encode($decoded) === $string;
    }

    public function verDocumentoPdfNotiAdjuntos($id)
    {
        $data = \DB::select("SELECT * FROM public._gp_documentos WHERE doc_id = ? AND alerta = 'R'", [$id]);

        if (count($data) === 0) {
            return response()->json(['error' => 'Documento no encontrado o no tiene alerta R'], 404);
        }

        $document = $data[0];
        $rutaCompleta = $document->doc_url;

        if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
            return response()->file($rutaCompleta, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($rutaCompleta) . '"'
            ]);
        } else {
            return response()->json(['error' => 'No Existe Documento!!!'], 404);
        }
    }

    public function verDocumentoPdfRuta(Request $request)
    {
        $ruta = $request['ruta'];
        $arraycontent = explode('/', $ruta);
        $serv = '/opt/documental/';
        $nomfile = $arraycontent[6];
        if (strstr($serv, "/opt/documental/")) {
            if (file_exists($ruta) && is_file($ruta)) {
                // header('Content-Description: File Transfer');
                // header('Content-Type: application/pdf');
                // header('Content-Disposition: inline; filename="' . basename($nomfile) . '"');
                // header('Expires: 0');
                // header('Cache-Control: must-revalidate');
                // header('Pragma: public');
                // header('Content-Length: ' . filesize($ruta));
                // readfile($ruta);
                globalGetPdfDocument($ruta, $nomfile);
                exit(0);
            } else {
                echo "<b>No Existe Documento!!!</b>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("LOCATION: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento!!!</b>";
            }
            exit(1);
        }
    }
    public function guardarDocumentosPost(Request $request)
    {
        /// dd($request->hasFile('archivo'));
        if ($request->hasFile('file')) {
            // Almacenar el archivo en el sistema de archivos
            $nombrefile = $request->file('file')->getClientOriginalName();
            $rutaTemporal = $request->file('file')->getPathName();
            $fileExtension = $request->file('file')->getClientOriginalExtension();
            $taman = $request->file('file')->getSize();

            $directorioDestino = '/opt/documental/';
            $anio = date('Y');
            $id_caso = $request['id_caso'];
            $caso = $request['caso'];
            $ci = $request['ci'];
            $valor_descripcion = $request['valor_descripcion'];
            $id_persona_sip = $request['id_persona_sip'];
            $uuid = Str::uuid();
            $strDirectorioFisico = "juan/" . $anio . "/";
            if (!file_exists($directorioDestino . $strDirectorioFisico)) {
                File::makeDirectory($directorioDestino . $strDirectorioFisico, 0777, true, true);
            }

            if (move_uploaded_file($rutaTemporal, $directorioDestino . $strDirectorioFisico . $uuid . '.' . $fileExtension)) {

                return response()->json(['mensaje' => 'El file se ha subido correctamente.', 'tan' => $taman], 200);
            } else {
                return "Hubo un error al subir el file.";
            }
        } else {
            return response()->json(['mensaje' => 'No se proporcionó ningún archivo'], 400);
        }
        //dd('juand');

    }
    public function guardarAdjuntos(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $nombrefile = $request->file('file')->getClientOriginalName();
            $rutaTemporal = $request->file('file')->getPathName();
            $fileExtension = $request->file('file')->getClientOriginalExtension();
            $directorioDestino = '/opt/documental/';
            $anio = date('Y');
            $id_caso = $request['id_caso'];
            $caso = $request['caso'];
            $arraycontent = explode('/', $caso);
            $ci = $request['ci'];
            $valor_descripcion = $request['valor_descripcion'];
            $id_persona_sip = $request['id_persona_sip'];
            $uuid = Str::uuid();
            $strDirectorioFisico = "tramitesip/" . $arraycontent[2] . "/" . $id_caso . "/";
            $switch = $request['switch'] === 'true';
            $usr_id = $request['usr_id'];

            if (!file_exists($directorioDestino . $strDirectorioFisico)) {
                File::makeDirectory($directorioDestino . $strDirectorioFisico, 0777, true, true);
            }
            if (move_uploaded_file($rutaTemporal, $directorioDestino . $strDirectorioFisico . $uuid . '.' . $fileExtension)) {

                /*
                $dataHistorico = \DB::select("  SELECT *
                        from rmx_vys_historico_casos
                        inner join rmx_vys_actividades on act_id = htc_cas_act_id
                        inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                        inner join users on id = htc_cas_usr_id
                    where htc_cas_id =  $id_caso order by htc_id desc limit 1 ");

                    */

                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");

                $respuesta = $this->guardarDocumentoPdf($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, 'ADJUNTOS', $ci, ($directorioDestino . $strDirectorioFisico . $uuid . '.' . $fileExtension), 0, 1, $valor_descripcion, 'pendiente', '', '', $switch, $id_persona_sip, 1, '');


                return "El file se ha subido correctamente.";
            } else {
                return "Hubo un error al subir el file.";
            }
        } else {
            return "No se ha subido ningún file válido.";
        }
    }

    public function guardarAdjuntosBC(Request $request)
    {
        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $nombrefile = $file->getClientOriginalName();
                $rutaTemporal = $file->getPathName();
                $fileExtension = $file->getClientOriginalExtension();
                $directorioDestino = '/opt/documental/';
                $anio = date('Y');
                $id_caso = $request->input('id_caso');
                $caso = $request->input('caso');
                $ci = $request->input('ci');
                $valor_descripcion = $request->input('valor_descripcion');
                $id_persona_sip = $request->input('id_persona_sip');
                $alerta = $request->input('alerta');
                $uuid = Str::uuid();
                $strDirectorioFisico = "tramitesip/{$anio}/{$id_caso}/";
                $switch = $request->input('switch') === 'true';
                $TipoRechaDesistido = $request->input('SelecRef');

                $usrId = $request->input('usrId');

                if (!file_exists($directorioDestino . $strDirectorioFisico)) {
                    File::makeDirectory($directorioDestino . $strDirectorioFisico, 0777, true, true);
                }

                $destinoPath = $directorioDestino . $strDirectorioFisico . $uuid . '.' . $fileExtension;
                if (move_uploaded_file($rutaTemporal, $destinoPath)) {

                    $data_caso_real = \DB::select("SELECT CASE WHEN cas_padre_id = 0 THEN cas_id ELSE cas_padre_id END AS cas_padre_id FROM rmx_vys_casos WHERE cas_id = ?", [$id_caso]);
                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                    $data_id_nodo = \DB::select("SELECT cas_nodo_id FROM rmx_vys_casos WHERE cas_id = ?", [$id_caso]);
                    $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                    $dataHistorico = \DB::select("SELECT * FROM rmx_vys_historico_casos WHERE htc_cas_id = ? AND htc_cas_nodo_id = ? ORDER BY htc_id DESC LIMIT 1", [$id_caso_real, $id_nodo]);

                    if ($TipoRechaDesistido == "Desistido" or $TipoRechaDesistido == "RCH Por Legal" or $TipoRechaDesistido == "RCH Por Fallecimiento" or $TipoRechaDesistido == "RCH_2DA_SOLICITUD" or $TipoRechaDesistido == "2_RCH_POR_GRP_FAM") {

                        $this->setEstadoRechazo($caso, $TipoRechaDesistido);
                    }

                    $respuesta = $this->guardarDocumentoPdfBC($id_caso, $usrId, $dataHistorico[0]->htc_id, $caso, $TipoRechaDesistido, $ci, $destinoPath, 0, 1, $valor_descripcion, 'pendiente', '', '', $switch, $id_persona_sip, 'R', 1, '');

                    return response()->json(['message' => 'El archivo se ha subido correctamente.'], 200);
                } else {
                    return response()->json(['message' => 'Hubo un error al subir el archivo.'], 500);
                }
            } else {
                return response()->json(['message' => 'No se ha subido ningún archivo válido.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en el servidor.', 'error' => $e->getMessage()], 500);
        }
    }


    public function setEstadoRechazo($codigocaso, $opcion)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {

            $estado = "ERROR";
            if ($opcion == "Desistido") {
                $estado = "DESISTIDO";
            } else if ($opcion == "RCH Por Fallecimiento") {
                $estado = "RCH_FALLECIMIENTO";
            } else if ($opcion == "RCH Por Legal") {
                $estado = "RCH_POR_LEGAL";
            } else if ($opcion == "RCH_2DA_SOLICITUD") {
                $estado = "RCH_2DA_SOLICITUD";
            } else if ($opcion == "2_RCH_POR_GRP_FAM") {
                $estado = "2_RCH_POR_GRP_FAM";
            }
            $results = \DB::select("
                SELECT c.cas_data_valores, c.cas_id as caso
                FROM rmx_vys_casos c
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                    AND c.cas_cod_id = ?
                ORDER BY c.cas_modificado DESC", [$codigocaso]);

            // Verificar si se obtuvieron resultados de la consulta
            if (count($results) === 0) {
                return response()->json(["message" => "No se encontraron casos para el código proporcionado."]);
            }

            foreach ($results as $result) {


                //*********************ADICION DE CAMPO POR RECHAZO MUERTE LEGAL */
                // Consultar si ya existe la fecha de recepción
                $estadoRespuestaCal = \DB::select("SELECT *
                 FROM (
                     SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                     FROM public.rmx_vys_casos
                     WHERE cas_id = '$result->caso'
                 ) tmp
                 WHERE tmp.valor->>'frm_campo' = 'AS_RECHAZO_DESISTIDO';");

                if (empty($estadoRespuestaCal)) {
                    ;
                    // Si no existe la fecha, insertar el campo en el JSON
                    try {
                        DB::table('rmx_vys_casos')
                            ->where('cas_id', $result->caso)
                            ->update([
                                'cas_data_valores' => DB::raw("
                                 cas_data_valores ||
                                 '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_RECHAZO_DESISTIDO\", \"frm_value\": \"$estado\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                             ")
                            ]);


                    } catch (\Exception $e) {
                        throw new \Exception("NO SE REGISTRÓ: " . $e->getMessage());
                    }
                } else {

                    // Si ya existe la fecha, actualizarla
                    try {
                        $dataInv = \DB::select("
                         WITH updated_json AS (
                             SELECT cas_id,
                                 jsonb_agg(
                                     CASE
                                         WHEN elem->>'frm_campo' = 'AS_RECHAZO_DESISTIDO' THEN jsonb_set(elem, '{frm_value}', '\"$estado\"')
                                         ELSE elem
                                     END
                                 ) AS updated_json
                             FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                             WHERE cas_id = '$result->caso'
                             GROUP BY cas_id
                         )
                         UPDATE public.rmx_vys_casos
                         SET cas_data_valores = updated_json.updated_json
                         FROM updated_json
                         WHERE public.rmx_vys_casos.cas_id = updated_json.cas_id;
                     ");

                        if (empty($dataInv)) {
                            throw new \Exception("NO SE ACTUALIZÓ LA ESTADO.");
                        }
                    } catch (\Exception $e) {
                        throw new \Exception("NO SE ACTUALIZÓ: " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            // Mensaje si ocurre una excepción
            echo "Error: " . $e->getMessage() . "\n";
            return response()->json(["error" => $error]);
        }
    }

    public function listarRegisComplementario(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);

        $PaginaActual = $request["PaginaActual"];
        $RegistrosXPagina = $request["RegistrosXPagina"];
        $buscar = $request["buscar"];

        try {

            $limit = 10;
            $offset = ($PaginaActual - 1) * $limit;

            $data = \DB::select("
            select
                gd.*,
                rvc.*,
                u1.name as htc_user_name,
                u1.email as htc_user_email,
                u2.name as doc_user_name,
                u2.email as doc_user_email,
                htc.*,
                act.*
            from _gp_documentos gd
            inner join rmx_vys_casos rvc on rvc.cas_id = gd.doc_cas_id
            inner join rmx_vys_historico_casos htc on htc.htc_id = gd.doc_his_id
            inner join users u1 on u1.id = htc.htc_cas_usr_id
            left join users u2 on u2.id = gd.doc_usr_id
            inner join rmx_vys_actividades act on htc.htc_cas_act_id = act.act_id
            where gd.alerta = 'R' AND
            (gd.doc_codigo LIKE ? OR gd.doc_referencia LIKE ? OR gd.doc_descripcion LIKE ?)
            order by gd.doc_registrado desc
            LIMIT ? OFFSET ?;", ["%$buscar%", "%$buscar%", "%$buscar%", $RegistrosXPagina, $offset]);

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarRegisComplementario(Request $request)
    {
        $doc_id = $request->input('doc_id');

        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("
                            update _gp_documentos
                            SET alerta='V'
                            WHERE doc_id = $doc_id;
                            ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }


    public function guardarAdjuntosMedicos(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $nombrefile = $request->file('file')->getClientOriginalName();
            $rutaTemporal = $request->file('file')->getPathName();
            $fileExtension = $request->file('file')->getClientOriginalExtension();
            $directorioDestino = '/opt/documental/';
            $anio = date('Y');
            $id_caso = $request['id_caso'];
            $caso = $request['caso'];
            $ci = $request['ci'];
            $valor_descripcion = $request['valor_descripcion'];
            $id_persona_sip = $request['id_persona_sip'];
            $uuid = Str::uuid();
            $arraycontent = explode('/', $caso);
            $strDirectorioFisico = "tramitesip/" . $arraycontent[2] . "/" . $id_caso . "/";
            $switch = $request['switch'] === 'true';
            $usr_id = $request['usr_id'];
            if (!file_exists($directorioDestino . $strDirectorioFisico)) {
                File::makeDirectory($directorioDestino . $strDirectorioFisico, 0777, true, true);
            }
            if (move_uploaded_file($rutaTemporal, $directorioDestino . $strDirectorioFisico . $uuid . '.' . $fileExtension)) {

                /*
                $dataHistorico = \DB::select("  SELECT *
                        from rmx_vys_historico_casos
                        inner join rmx_vys_actividades on act_id = htc_cas_act_id
                        inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                        inner join users on id = htc_cas_usr_id
                    where htc_cas_id =  $id_caso order by htc_id desc limit 1 ");

                    */

                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $id_caso");
                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                $data_id_nodo = \DB::select("select cas_nodo_id from rmx_vys_casos where cas_id =  $id_caso");
                $id_nodo = $data_id_nodo[0]->cas_nodo_id;
                $dataHistorico = \DB::select("select * from rmx_vys_historico_casos where htc_cas_id=$id_caso_real and htc_cas_nodo_id= $id_nodo order by htc_id desc limit 1");

                $respuesta = $this->guardarDocumentoPdf($id_caso, $usr_id, $dataHistorico[0]->htc_id, $caso, 'ADJUNTO_MEDICOS', $ci, ($directorioDestino . $strDirectorioFisico . $uuid . '.' . $fileExtension), 0, 1, $valor_descripcion, 'pendiente', '', '', $switch, $id_persona_sip, 1, '');


                return "El file se ha subido correctamente.";
            } else {
                return "Hubo un error al subir el file.";
            }
        } else {
            return "No se ha subido ningún file válido.";
        }
    }
    public function obtenerDocumentoPdf64($id)
    {
        // gestora
        $data = \DB::select("select * from  public._gp_documentos  where doc_id = $id ");
        $arraycontent = explode('/', $data[0]->doc_url);
        $serv = '/opt/documental/';

        if (isset($arraycontent[6])) {
            $serv = '/opt/documental/';
            $nomfile = $arraycontent[6];
            if (strstr($serv, "/opt/documental/")) {
                $rutaCompleta = $data[0]->doc_url;
                if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                    $fileContent = file_get_contents($rutaCompleta);
                    $base64Content = base64_encode($fileContent);
                    $base64Content = str_replace(array("\r", "\n", " "), '', $base64Content);
                    return $base64Content;
                } else {
                    echo "<b>No Existe Documento!!!</b>";
                    exit(1);
                }
            } else {
                if (file_exists("../../$serv" . "$nomfile")) {
                    header("LOCATION: ../../$serv" . "$nomfile");
                } else {
                    echo "<b>No Existe Documento!!!</b>";
                }
                exit(1);
            }
        } else {
            echo "<b>No Existe Documento!!!</b>";
            exit(1);
        }

    }

    public function obtenerDocumentoFirmantes(Request $request)
    {


        $id_documento = $request->input('id_documento');



        $response = Http::post('https://localhost:9000/api/validar_pdf', [
            'pdf' => '', // Parámetro a enviar en el cuerpo de la solicitud
        ]);


        $data = \DB::select("select * from  public._gp_documentos  where doc_id = $id_documento");
        $arraycontent = explode('/', $data[0]->doc_url);
        $serv = '/opt/documental/';
        $nomfile = $arraycontent[6];
        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data[0]->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                $fileContent = file_get_contents($rutaCompleta);
                $base64Content = base64_encode($fileContent);


                dd($id_documento);


                return $base64Content;
            } else {
                echo "<b>No Existe Documento!!!</b>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("LOCATION: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento!!!</b>";
            }
            exit(1);
        }
    }
    public function obtenerDocumentoPdf64CodigoRegistro(Request $request)
    {
        $cas_cod_id = $request['cas_cod_id'];
        $nombreDocumento = $request['nombreDocumento'];
        $docId = $request["doc_id"];
        $sqlQuery = "";
        if($docId !== null){
            $docId = (int)$docId;
            $sqlQuery = " select *
                        from \"_gp_documentos\" gd
                        where gd.doc_id = $docId ";
        } else {
            //original sql-query
            $sqlQuery = "select * from  public._gp_documentos  where  doc_codigo= '$cas_cod_id'  and  doc_descripcion=  '$nombreDocumento'
                            order by doc_id desc limit 1 ";
        }
        $data = \DB::select($sqlQuery);
        $arraycontent = explode('/', $data[0]->doc_url);
        $serv = '/opt/documental/';
        $nomfile = $arraycontent[6];
        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data[0]->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                $fileContent = file_get_contents($rutaCompleta);
                $base64Content = base64_encode($fileContent);
                return $base64Content;
            } else {
                echo "<b>No Existe Documento!!!</b>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("LOCATION: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento!!!</b>";
            }
            exit(1);
        }
    }
    public function obtenerDocumentoPdf64Codigo(Request $request)
    {
        $cas_cod_id = $request['cas_cod_id'];
        $nombreDocumento = $request['nombreDocumento'];
        $codigo = $request['codigo'];

        $data = \DB::select("select * from  public._gp_documentos  where  doc_codigo= '$cas_cod_id'  and  doc_descripcion=  '$nombreDocumento' and doc_doc_id  = $codigo ");
        $arraycontent = explode('/', $data[0]->doc_url);
        $serv = '/opt/documental/';
        $nomfile = $arraycontent[6];
        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data[0]->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                $fileContent = file_get_contents($rutaCompleta);
                $base64Content = base64_encode($fileContent);
                return $base64Content;
            } else {
                echo "<b>No Existe Documento!!!</b>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("LOCATION: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento!!!</b>";
            }
            exit(1);
        }
    }
    public function obtenerListadoDocumentos(Request $request)
    {
        $doc_codigo = $request["doc_codigo"];
        if ($doc_codigo != '') {
            $success = array("code" => 200, "mensaje" => 'OK', );
            $error = array("message" => "error de instancia", "code" => 500);
            try {
                $dataRespon = \DB::select("SELECT * FROM  public._gp_documentos
                                        inner join public.dominio on subdominio = doc_referencia
                                        where doc_codigo = '$doc_codigo' order by orden, doc_doc_id  asc ");
                if ($dataRespon != []) {
                    for ($i = 0; $i < sizeof($dataRespon); $i++) {

                        $respDocumento[$i] = array(
                            'documento_id' => $dataRespon[$i]->doc_id,
                            'nombre' => $dataRespon[$i]->doc_url,
                            'descripcion' => $dataRespon[$i]->doc_descripcion,
                            'tipo' => $dataRespon[$i]->nombre,
                        );
                    }
                    return response()->json(["data" => $respDocumento, "codigoRespuesta" => $success]);
                } else {
                    $success = array("code" => 201, "mensaje" => 'Los datos ingresados no existen en la base de datos.', );
                    return response()->json(["data" => [], "codigoRespuesta" => $success]);
                }
            } catch (error $e) {
                return response()->json(["error" => $error]);
            }
        } else {
            $error = array("message" => "error de instancia doc_codigo Vacia ", "code" => 601);
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function verFirmaDocumentoPdfNfs($id)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        $sql = "select doc_url from public._gp_documentos where doc_id = $id";
        $data = \DB::select($sql);

        if (empty($data)) {
            echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
            echo "<div class='error-message'>No Existe Registro!!!</div>";
            echo "</body></html>";
            exit(1);

        }
        $arraycontent = explode('/', $data[0]->doc_url);

        if (isset($arraycontent[6])) {
            $serv = '/opt/documental/';
            $nomfile = $arraycontent[6];
        } else {
            return response()->json(["data" => $data, "success" => $success]);
            exit(1);
        }

        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data[0]->doc_url;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                $fileContent = file_get_contents($rutaCompleta);
                $base64Content = base64_encode($fileContent);
                $base64Content = str_replace(array("\r", "\n", " "), '', $base64Content);
                $client = new Client();
                try {
                    $response = $client->post('http://10.10.16.120:8080/validar', [
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'json' =>[
                            'base64' => $base64Content
                        ]
                    ]);
                    $data = json_decode($response->getBody(), true);
                    return response()->json($data);
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    throw new \Exception("Error al conectarse al servicio externo: " . $e->getMessage());
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            } else {
                echo "<html><head><title>Error</title><style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 20px;
                border-radius: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            </style></head><body>";
                echo "<div class='error-message'>No Existe Firmas!!!</div>";
                echo "</body></html>";
                exit(1);
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("Location: ../../$serv" . "$nomfile");
            } else {
                echo "<b>No Existe Documento!!!</b>";
            }
            exit(1);
        }
    }

    public function registroDiferencia($cua)
    {
        $response = Http::get('https://sgg.gestora.bo/compensacion-cotizacion/api/cargaFile/consulta', [
            'cua' => $cua,
        ]);
        $data = $response->json();
        $respuesta_202 = 'no coresponde';
        if ($data['codigoRespuesta'] == 200) {
            $dataOriginal = $data['data']['original'];
            $dataSenac = $data['data']['historico'][0];
            $bandera = 0;

            $primer_apellido = '';
            $segundo_apellido = '';
            $primer_nombre = '';
            $segundo_nombre = '';
            $cua_ = '';
            $num_identificacion = '';
            $fec_nacimiento = '';

            if ($dataOriginal['primer_apellido'] == $dataSenac['primer_apellido']) {
                $bandera++;
            } else {
                $primer_apellido = $dataSenac['primer_apellido'];
            }
            if ($dataOriginal['segundo_apellido'] == $dataSenac['segundo_apellido']) {
                $bandera++;
            } else {
                $segundo_apellido = $dataSenac['segundo_apellido'];
            }
            if ($dataOriginal['primer_nombre'] == $dataSenac['primer_nombre']) {
                $bandera++;
            } else {
                $primer_nombre = $dataSenac['primer_nombre'];
            }
            if ($dataOriginal['segundo_nombre'] == $dataSenac['segundo_nombre']) {
                $bandera++;
            } else {
                $segundo_nombre = $dataSenac['segundo_nombre'];
            }
            if ($dataOriginal['cua'] == $dataSenac['cua']) {
                $bandera++;
            } else {
                $cua_ = $dataSenac['cua_'];
            }
            if ($dataOriginal['num_identificacion'] == $dataSenac['num_identificacion']) {
                $bandera++;
            } else {
                $num_identificacion = $dataSenac['num_identificacion'];
            }
            if ($dataOriginal['fec_nacimiento'] == $dataSenac['fec_nacimiento']) {
                $bandera++;
            } else {
                $fec_nacimiento = $dataSenac['fec_nacimiento'];
            }


            if ($bandera == 7) {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Author');
                $pdf->SetTitle('Hello PDF');
                $pdf->SetSubject('Simple PDF');
                $pdf->SetKeywords('TCPDF, PDF, hello, world');
                $pdf->AddPage();
                $pdf->SetFont('dejavusans', '', 12);
                $html = '';
                $html .= '
                <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
                    </head>
                    <body>
                       No hay Direfencia
                    </body>
                </html>';
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdfAsBase64 = $pdf->Output('', 'S');
                $base64Content = base64_encode($pdfAsBase64);
                $error = array("message" => "No hay Direfencia", "code" => 201);
                return response()->json(["data" => [], "codigoRespuesta" => $error]);
            } else {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Author');
                $pdf->SetTitle('Hello PDF');
                $pdf->SetSubject('Simple PDF');
                $pdf->SetKeywords('TCPDF, PDF, hello, world');
                $pdf->AddPage();
                $pdf->SetFont('dejavusans', '', 12);
                $html = '';
                $html .= '
        <style type="text/css">
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
            <style type="text/css">
        td.style14 {
                vertical-align: bottom;
                text-align: center;
                border-bottom: 2px solid #000000 !important;
                border-top: 2px solid #000000 !important;
                border-left: 2px solid #000000 !important;
                border-right: none #000000;
                color: #000000;
                font-family: "Calibri";
                font-size: 11pt;
                background-color: white
            }
            td.style17 {
                vertical-align: middle;
                text-align: center;
                border: 1px solid black;
                color: #000000;
                font-family: "Calibri";
                font-size: 11pt;
                background-color: white;
            }
        td.style40 {

            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border: none;
            color: #000000;
            font-size: 9pt;
            background-color: white
        }
        td.style41 {
            border-bottom: 2px solid #000000 !important;
            border: none;
            color: #000000;
            font-size: 9pt;
            background-color: white
        }
        td.style21 {

            border: none;
            border-bottom: none;
            border-top: none;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            background-color: white;
               font-size: 9pt;
        }

        td.style22 {
            text-align: center;
            border: none;
            border-bottom: none;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            background-color: white;
            vertical-align: middle;
            font-weight: bold;
               font-size: 9pt;

        }

        td.style23 {

            border: none;
            border-bottom: none;
            border-top: none;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            background-color: white;
            font-family: "Calibri";
            vertical-align: middle;
               font-size: 9pt;
        }

        td.style71 {
            vertical-align: bottom;
            text-align: left;
            border-bottom: none;
            border: none;
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 2px solid #000000 !important;
            background-color: white;
        }

        td.style69 {
            border: none;
            vertical-align: bottom;
            text-align: center;
            font-weight: Light;
            color: #000000;
            font-family: "Verdana", sans-serif;
            font-size: 20pt;
            background-color: white;
        }

        th.style85 {
            text-align: center;
            border-left: 1px solid #000000 !important;
            color: #000000;
            font-family: "Calibri";
            font-size: 11pt;
            font-weight: bold;
            background-color: #FEF2CB;
             border: none;
        }
        </style>
    </head>
    <body>
            <table border="1">
            <thead>
                <tr>
                <th colspan="4"><p><span><img src="img/logo_gestora.jpg" /></span></p></th>
                <th colspan="8" > <p style="text-align:center; font-size:14px;" >
                <span><strong>FORMULARIO DE REGISTRO DE DIFERENCIA</strong></span>
                </p></th>
                <th colspan="4">CC-M-002
                </th>
                </tr>
            </thead>
            <tbody>
                <tr class="row23"><td class="style40" colspan="28"></td></tr>
                <tr style="font-size:8px; text-align:center;">
                    <th colspan="8"> A) INFORMACION REGISTRADA EN EL CERTIFICADO DE CC </th>
                    <th colspan="8"> B) INFORMACION DEL ASEGURADO A MODIFICARSE</th>
                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> PRIMER APELLIDO</strong></th>
                     <th colspan="6"> ' . $dataOriginal['primer_apellido'] . '</th>
                    <th colspan="6"> ' . $primer_apellido . '</th>

                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> SEGUNDO APELLIDO</strong></th>
                    <th colspan="6"> ' . $dataOriginal['segundo_apellido'] . '</th>
                    <th colspan="6"> ' . $segundo_apellido . '</th>
                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> PRIMER NOMBRE</strong></th>
                    <th colspan="6"> ' . $dataOriginal['primer_nombre'] . '</th>
                    <th colspan="6"> ' . $primer_nombre . '</th>
                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> SEGUNDO NOMBRE</strong></th>
                    <th colspan="6"> ' . $dataOriginal['segundo_nombre'] . '</th>
                    <th colspan="6"> ' . $segundo_nombre . '</th>
                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> N° CUA</strong></th>
                    <th colspan="6"> ' . $dataOriginal['cua'] . '</th>
                    <th colspan="6"> ' . $cua_ . '</th>
                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> N° DOCUMENTO</strong></th>
                    <th colspan="6"> ' . $dataOriginal['num_identificacion'] . '</th>
                    <th colspan="6"> ' . $num_identificacion . '</th>
                </tr>
                <tr style="font-size:8px;">
                    <th colspan="4"><strong> FECHA DE NACIMIENTO</strong></th>
                    <th colspan="6"> ' . $dataOriginal['fec_nacimiento'] . '</th>
                    <th colspan="6"> ' . $fec_nacimiento . '</th>
                </tr>
                <tr class="row23"><td class="style40" colspan="28"></td></tr>
                <tr class="row35"><td class="style22" colspan="28"> DOCUMENTO QUE ADJUNTA </td></tr>
                <tr class="row35"><td class="style23" colspan="28"> FOROCOPIA DE CERT. DE NACIMIENTO        [ ]  CERTIFICADO GESTORA                          [ ]</td></tr>
                <tr class="row35"><td class="style23" colspan="28"> FOTOCOPIA LEGALIZADA C.I.               [ ] CERTIFICADO CERT. DE DEFUNCIÓN                [ ]</td></tr>
                <tr class="row35"><td class="style23" colspan="28"> CERTIFICACIÓN DE DOC. DE IDENTIDAD      [ ]  DOCUMENTOS DE ACREDITACION DE DERECHOHABIENT [ ] </td></tr>
                <tr class="row35"><td class="style41" colspan="28"> INFORME DE LA ENTIDAD ORIGEN Y DESTINO  [ ] CANTIDAD TOTAL DE FOJAS ADJUNTAS              [ ]</td></tr>
                <tr class="row23"><td class="style40" colspan="28"></td></tr>
                <tr class="row35"><td class="style22" colspan="28"> MODIFICACIÓN DE FECHA DE NACIMINETO VÍA ADMINISTRATIVA </td></tr>
                <tr class="row35"><td class="style23" colspan="28"> FECHA_NAC [ ] CERTIFICADO DE NACIMIENTO, MATRIMONIO, DEFUNCIÓN, LIBRETA DE
                    SERVICIO MILITAR, CERTIACADO DE BAUTIZO O CERTIFICADO DE MATRIMONIO RELIGIOSO PARA NACIDOS ANTES 1940, LIBRETA DE
                    FAMILIA, TÍTULOS PROFESIONALES, CERTIFICADO DE NACIDO VIVO Y OTROS DONDE SE ENCUENTRE SEÑALADA LA FECHA DE NACIMIENTO.

                    </td>
                </tr>
                <tr class="row35"><td class="style22" colspan="28"> MODIFICACIÓN DE FECHA DE NACIMINETO VÍA JUDICIAL </td></tr>
                <tr class="row35"><td class="style41" colspan="28"> FECHA_NAC [ ] FOTOCOPIAS SIMPLES DEL EXPEDIENTE JUDICIAL Y FOTOCOPIAS LEGALIZADAS DE LA SENTENCIA QUE AUTORIZA</td></tr>
                <tr class="row35"><td class="style41" colspan="28"> Yo ____________________________________________________________, declaro que los datos consignados en el presente formulario son fidedignos, asumiendo plena responsabilidad sobre los mismos. Escuanto declaro en honor a la verdad paralos fines de mi Compensación de Cotizaciones.</td></tr>
                <tr class="row23"><td class="style40" colspan="9"></td>   <td class="style40" colspan="9"></td>                                </tr>
                <tr class="row35"><td class="style22" colspan="9"> DATOS FUNCIONARIO GESTORA </td><td class="style22" colspan="9">TITULAR [ ] DERECHOHABIENTE [ ] </td>         </tr>
                <tr class="row35"><td class="style23" colspan="9">NOMBRE: …………………………………………………………</td> <td class="style23" colspan="9">FIRMA: …………………………………………………… </td>  </tr>
                <tr class="row35"><td class="style41" colspan="9">FIRMA: ………………………………………………………………</td>  <td class="style23" colspan="9">Telefono/Celular: ………………………………</td>  </tr>
                ';
                $finmja = '
                            </tbody>
                        </table>
                    </body>
                </html>';
                $html = $html . $finmja;
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdfAsBase64 = $pdf->Output('', 'S');
                $base64Content = base64_encode($pdfAsBase64);
                $success = array("code" => 200, "mensaje" => 'OK', );
                return response()->json(["data" => $base64Content, "codigoRespuesta" => $success]);
            }
        } else {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Author');
            $pdf->SetTitle('Hello PDF');
            $pdf->SetSubject('Simple PDF');
            $pdf->SetKeywords('TCPDF, PDF, hello, world');
            $pdf->AddPage();
            $pdf->SetFont('dejavusans', '', 12);
            $html = '';
            $html .= '
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
                </head>
                <body>
                    no coresponde
                </body>
            </html>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdfAsBase64 = $pdf->Output('', 'S');
            $base64Content = base64_encode($pdfAsBase64);
            $error = array("message" => "no coresponde", "code" => 500);
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function DocumetacionObservada($cas_id)
    {
        $dataDocumentos = \DB::select("select doc_cas_id,doc_codigo, doc_descripcion,codigo ,doc_referencia
				from _gp_documentos gd
				inner join  gp_observacion go2 on go2.id_observacion = gd.doc_id_observacion
				where  doc_id_observacion != 1 and doc_cas_id = $cas_id  ");

        $html_list_documento = '';
        $count = 1;
        foreach ($dataDocumentos as $documento) {
            $html_docuemnto = '     <tr class="row9">
                <td class="style16" colspan="2"></td>
                <td class="style40" colspan="2">' . $count . '</td>
                <td class="style41" colspan="17"> (' . $documento->doc_referencia . ') ' . $documento->doc_descripcion . '</td>
                <td class="style42" colspan="5"> ' . $documento->codigo . ' </td>
                <td class="style16" colspan="2"></td>
            </tr>';
            $html_list_documento .= $html_docuemnto;
            $count++;
        }

        $html = '';
        $html .= '
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
    <style type="text/css">
        td.style69 {
            vertical-align: bottom;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: "Arial Narrow";
            font-size: 11pt;
            background:transparent;
            border: none;
        }
        td.style16 {
            vertical-align: bottom;
            text-align: left;
            border: none;
            color: #000000;
            font-family: "Calibri";
            font-size: 11pt;
        }
        td.style43 {
            vertical-align: middle;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border-bottom: 2px solid #000000 !important;
            border:  2px solid #000000 !important;
            font-weight: bold;
            color: #000000;
            font-family: "Calibri";
            background:transparent;
            font-size: 12pt;
        }
        td.style10 {
            vertical-align: middle;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border: none;
            font-weight: bold;
            color: #000000;
            font-family: "Calibri";
            background:transparent;
            font-size: 12pt;
        }
        td.style11 {
            vertical-align: middle;
            border-top: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border-bottom: 1px dashed #000000 ;
            border: none;
            font-weight: bold;
            color: #000000;
            font-family: "Calibri";
            background:transparent;
            font-size: 12pt;
        }
        td.style12 {
            vertical-align: middle;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border-bottom: 2px solid #000000 !important;
            border: none;
            font-weight: bold;
            color: #000000;
            font-family: "Calibri";
            background:transparent;
            font-size: 12pt;
        }
        td.style13 {
            vertical-align: middle;
            border-left: 2px solid #000000 !important;
            border: none;
            font-weight: bold;
            color: #000000;
            font-family: "Calibri";
            background:transparent;
            font-size: 12pt;
        }
        td.style14 {
            vertical-align: middle;
            border-right: 2px solid #000000 !important;
            border-bottom: 1px dashed #000000 ;
            border: none;
            font-weight: bold;
            color: #000000;
            font-family: "Calibri";
            background:transparent;
            font-size: 12pt;
        }
        td.style40 {
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border: none;
            font-size: 11pt;
            background: transparent;
        }
        td.style41 {
            text-align: left;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border: none;
            font-size: 11pt;
            background: transparent;
        }
        td.style42 {
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            border: none;
            font-size: 11pt;
            background: transparent;
        }
        td.style17 {
            vertical-align: bottom;
            text-align: right;
            border: none;
            color: #000000;
            font-family: "Calibri";
            font-size: 11pt;
        }
        td.style5 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: 2px solid #000000 !important;
            color: #000000;
            font-family: "Calibri";
            font-size: 11pt;
            background-color: white
        }
        td.style85 {
            text-align: center;
            border: none;
            font-family: "Calibri";
            font-size: 11pt;
        }
    td.style86 {
        vertical-align: middle;
        text-align: center;
        font-weight: bold;
        border-top: 4px solid #000000 !important;
        color: #000000;
        font-family: "Calibri";
        font-size: 10pt;
        border: none;
        background: transparent;
    }


    </style>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines">
             <tbody>
            <tr class="row3">
                <td class="column3 style16" colspan="28">#_DEPARTAMENTO#, &nbsp;#FECHA_HOY# </td>
            </tr>
            <tr class="row3">
                <td class="column3 style16" colspan="28" style="border: none;">GP/LPZ/PRES/#_CASO_NOMBRE#</td>
            </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row4"> <td  colspan="28" style="border: none;"></td></tr>
            <tr class="row9">
                <td class="style69" colspan="28"><strong>Señor(a): </strong>
                </td>
            </tr>
            <tr class="row9">
                <td class="column3 style16" colspan="28">#AS_PRIMER_APELLIDO#  #AS_SEGUNDO_APELLIDO#  #AS_APELLIDO_CASADA# #AS_PRIMER_NOMBRE#   #AS_SEGUNDO_NOMBRE# </td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="5"><strong>DIRECCION:</strong> </td>
                <td class="style16" colspan="28">#AS_ZONA#&nbsp;#AS_DIRECCION#&nbsp;#AS_NUM#  </td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="4"><strong>TELEFONO:</strong>
                </td>
                <td class="style16" colspan="28">#AS_CELULAR#   &nbsp; &nbsp;   #AS_TELEFONO#
                </td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="4"><strong>Presente. -</strong>
                </td>
            </tr>
            <tr class="row9">
                <td class="style17" colspan="28">
                    <strong> <u>Ref.: Notificación de Documentación Observada</u></strong>
                </td>
            </tr>
            <tr class="row4"><td  colspan="28" style="border: none;"></td></tr>

            <tr class="row4">
                <td class="style16" colspan="28">De nuestra mayor consideración,</td>
            </tr>
            <tr class="row4"><td  colspan="28" style="border: none;"></td> </tr>
            <tr class="row4">
                <td class="style16" colspan="28"  style="text-align: justify;">Mediante la presente, en cumplimiento a la Resolución Administrativa APS/DJ/DP N° 467/2019, de fecha 20 de marzo de 2019, articulo 10 (Notificación), realizada la verificación de los documentos presentados en su Formulario de Pensión de Jubilación, de acuerdo a lo establecido en artículo 8 del D.S. N° 0822/2011, tenemos a bien comunicarles a usted las siguientes observaciones:
                </td>
            </tr>
            <tr class="row4"> <td  colspan="28" style="border: none;"></td></tr>

            <tr class="row9">
                <td class="style16" colspan="2"></td>
                <td class="style40" colspan="2"><strong>Nro.</strong></td>
                <td class="style41" colspan="17"><strong>Documento</strong></td>
                <td class="style42" colspan="5"><strong>Código de Observación</strong></td>
                <td class="style16" colspan="2"></td>
            </tr>
            ' . $html_list_documento . '
            <tr class="row9">
                <td class="style16" colspan="2"></td>
                <td class="style16" colspan="26"  style="text-align: justify;"><strong>Nota</strong>   Si la Observación es E2 y/o E3, el documento original a presentar deberá ser remitido en fecha posterior a la del Documento entregado junto al FSJ.     </td>
            </tr>
                        <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row4">
                <td class="style16" colspan="28"  style="text-align: justify;">Código de Observación: </td>
            </tr>


            <tr class="row9">
                <td class="style16" colspan="2">
                </td>
                <td class="style16" colspan="26"  style="text-align: justify;">° E1: Se presentó solo fotocopia del documento de identidad y en el Registro Civil existe inscrita más de una partida con la información declarada.<br>° E2: La información de la partida inscrita en el Registro Civil no coincide plenamente con la información del documento presentado.<br>° E3: La partida inscrita en el Registro Civil esta cancelada.<br>										° E4: No existe partida inscrita en el Registro Civil que respalde la información del documento presentado o de la declaración realizada.<br>										° E5: La información de Vivencia Fallecimiento declarada en el Formulario de recepción de Tramite, no se encuentra respaldada por el Registro Civil.<br>										° E6: Fotocopia de documentación no legible.<br>										° E7: Otro.<br>										° G1 Diferencia de datos entre los documentos del Asegurado.<br>										° G2: Diferencia en el documento del derechohabiente respecto a los datos de Titular/Certificado de Nacimiento/Certificado de Matrimonio.<br>										° G3: Documento original no cumple con las condiciones de ley.<br>										° G4: Diferencias entre los documentos y nuestra base de datos de aseguramiento.<br>										° D1: No cuenta Documento de Identidad.	<br>

                </td>
            </tr>


            <tr class="row28">
                <td class="style16" colspan="28"  style="text-align: justify;">El plazo para la regularización de su trámite es de doce (12) meses a partir de la fecha de suscripción del FSJ, como lo establece el artículo 10 de la R.A. APS/DJ/DP N° 467/2019, de fecha 20 de marzo de 2019.
                </td>
            </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row28">
                <td class="style16" colspan="28"  style="text-align: justify;">Ante cualquier duda o consulta, le invitamos cordialmente comunicarse con nuestra línea de Atención al Cliente 800-10-1610, o WhatsApp 671-95524, o apersonarse por nuestras oficinas Regionales de Atención al Cliente a nivel Nacional.
                </td>
            </tr>
            <tr class="row28">
                <td class="style16" colspan="28"  style="text-align: justify;">Sin otro particular, aprovechamos la oportunidad para saludarles(a) cordialmente.
                </td>
            </tr>
            <tr class="row28">
                <td class="style16" colspan="28">Atentamente;</td>
            </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row28">
                <td class="style85" colspan="6"> </td>
                <td class="style86" colspan="15">
                    FUNCIONARIO DE LA GESTORA <br>
                Gestora Publica de la Seguridad Social de Largo Plazo</td>
            </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row7">
                <td class="style16" colspan="2">  </td>
                <td class="style43" colspan="24" style="text-align: center;">CONSTANCIA DE ENTREGA AL SOLICITANTE DEL TRAMITE</td>
                <td class="style16" colspan="2">   </td>
            </tr>
            <tr class="row7">
                <td class="style16" colspan="2">  </td>
                <td class="style10" colspan="8"> Nombre Completo:</td>
                <td class="style11" colspan="16">#SOL_PRIMER_APELLIDO#  #SOL_SEGUNDO_APELLIDO#   #SOL_PRIMER_NOMBRE#   #SOL_SEGUNDO_NOMBRE#</td>
                <td class="style16" colspan="2">  </td>
            </tr>

            <tr class="row7">
                <td class="style16" colspan="2">  </td>
                <td class="style13" colspan="10"> Documento de Identidad:</td>
                <td class="style14" colspan="14"> #SOL_CI#&nbsp; &nbsp;#SOL_COMPLEMENTO# </td>
                <td class="style16" colspan="2">   </td>
            </tr>
            <tr class="row7">
                <td class="style16" colspan="2">  </td>
                <td class="style13" colspan="6"> Lugar y Fecha:</td>
                <td class="style14" colspan="18"> #_DEPARTAMENTO#, #FECHA_HOY# </td>

                <td class="style16" colspan="2">  </td>
            </tr>
            <tr class="row7">
                <td class="style16" colspan="2">  </td>
                <td class="style13" colspan="5"> Telefonos:</td>
                <td class="style14" colspan="19">#SOL_CELULAR# &nbsp; &nbsp;  #SOL_TELEFONO#</td>
                <td class="style16" colspan="2">   </td>
            </tr>
            <tr class="row7">
                <td class="style16" colspan="2">  </td>
                <td class="style13" colspan="8"> Correo Electronico:</td>
                <td class="style14" colspan="16">#SOL_CORREO#</td>

                <td class="style16" colspan="2">   </td>
            </tr>
            <tr class="row7">
                <td class="style16" colspan="2">   </td>
                <td class="style12" colspan="24">  </td>
                <td class="style16" colspan="2">   </td>
            </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
            <tr class="row28">
                <td class="style85" colspan="8"> </td>
                <td class="style85" colspan="12">

                </td>
            </tr>
            <tr class="row28">
                <td class="style85" colspan="8"> </td>
                <td class="style86" colspan="12">
                    FIRMA DE SOLICITANTE <br>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>';







        $data1 = \DB::select("SELECT c.cas_data_valores
				FROM rmx_vys_casos c
				WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H') and c.cas_cod_id like 'JUB/4151/2024' ");
        $content1 = $data1[0]->cas_data_valores;
        $data1 = json_decode($content1, true);





        if (isset($data1)) {
            // dd('primer if', $data1);

            $impData1 = $data1;

            if (is_array($impData1)) {
                //dd('primer if 3', $data1);
                foreach ($impData1 as $item) {
                    if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                        $frmCampo = $item['frm_campo'];
                        $frmValue = $item['frm_value'];
                        if (!empty($frmValue)) {
                            if ($frmCampo != "GRILLA_DERECHOHABIENTES") {
                                if (!empty($item['frm_value_label'])) {
                                    if ($frmCampo == "AS_ENTE_GESTOR") {
                                        $html = str_replace('#' . $frmCampo . '#', $frmValue, $html);
                                        $AS_ENTE_GESTOR = $item['frm_value_label'];
                                        continue;
                                    } else {
                                        $frm_label = $item['frm_value_label'];
                                        $html = str_replace('#' . $frmCampo . '#', $frm_label, $html);
                                        continue;
                                    }
                                } else {
                                    $html = str_replace('#' . $frmCampo . '#', $frmValue, $html);
                                    continue;
                                }
                            }
                        }
                    } else {
                        $frmCampo = $item['frm_campo'];
                        $html = str_replace('#' . $frmCampo . '#', '', $html);
                    }
                }
            }
        }

        date_default_timezone_set('America/La_Paz');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        setlocale(LC_TIME, 'spanish');
        $fecha_literal = strftime('%d de %B del %Y', time());
        $html = str_replace('#FECHA_HOY#', $fecha_literal, $html);


        // dd('estas aqqui ', $cas_codigo);

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Author');
        $pdf->SetTitle('Hello PDF');
        $pdf->SetSubject('Simple PDF');
        $pdf->SetKeywords('TCPDF, PDF, hello, world');

        $pdf->AddPage('P');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetLeftMargin(PDF_MARGIN_LEFT); // Margen izquierdo

        $pdf->SetTopMargin(35); // Margen superior 27 PDF_MARGIN_TOP
        $pdf->SetRightMargin(PDF_MARGIN_RIGHT); // Margen derecho
        $pdf->setPrintFooter(false);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(35);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $auto_page_break = $pdf->getAutoPageBreak();







        $pdf->SetAutoPageBreak($auto_page_break, 35);
        $pdf->setPageMark();

        $pdf->writeHTML($html, true, false, true, false, '');


        // Añadir más páginas automáticamente según sea necesario
        // Generar más contenido HTML para demostrar múltiples páginas

        $pdf->Output('example.pdf', 'I');
        //  $base64Content = base64_encode($pdfAsBase64);
        // $success = array("code" => 200, "mensaje" => 'OK', );
        // return $base64Content;
        //return response()->json(["data" => $base64Content, "codigoRespuesta" => $success]);

    }
}
class MYPDF extends TCPDF
{
    //Page header
    public function Header()
    {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 37);
        // set bacground image
        $img_file = 'img/pp.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, 0);
        // set the starting point for the page content
        $this->setPageMark();
    }
}
