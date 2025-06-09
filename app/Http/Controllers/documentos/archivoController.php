<?php

namespace App\Http\Controllers\documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use TCPDF;
use Illuminate\Support\Facades\File;
use Config;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;
use App\Http\Services\DocumentoService;

class archivoController extends Controller
{
    protected $documentService;

    public function __construct(DocumentoService $documentService)
    {
        $this->documentService = $documentService;
    }
    public function pdfPreGuardado(Request $request) //funcion en des-uso en las rutas api.php
    {
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
        $result = $dataRespon[0]->sp_existe_documento_inicial;

        if ($result) {
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

            $dataRespon = \DB::select("select * from   public._gp_documentos   where   doc_codigo='$caso' and doc_referencia = '$parentesco' and  doc_categoria = '$ci' and doc_doc_id =   $valor_id  ");
            if (sizeof($dataRespon) > 0) {
                $doc_id = $dataRespon[0]->doc_id;

                if ($pdfAsBase64 == '') {
                    $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '',  $id_caso)");
                } else {
                    $base64data = base64_decode($pdfAsBase64, true);
                    $fechactual = date("Y-m-d H:i:s");
                    $anio = date('Y');
                    $strExtensionArch = ".pdf";
                    $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $id_caso . "/";
                    if (!file_exists($strDirectorioFisico)) {
                        @mkdir($strDirectorioFisico, 0777, true);
                    }
                    $strNombreArch = date('dmY_His') . $strExtensionArch;
                    $fichero_subido = $strDirectorioFisico . $valor_id . '_' . $strNombreArch;
                    if (file_put_contents($fichero_subido, $base64data)) {
                        // Return the number of bytes saved, or false on failure
                    }
                    $respuesta = $this->actualizarDocumentoPdf($doc_id, $id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $fichero_subido, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    $url = Config::get('var.urlDocumento') . $fichero_subido;
                    $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$url',  $id_caso)");
                }
                return response()->json(['data' => '{}', 'success' => $success]);
            } else {
                if ($pdfAsBase64 == '') {
                    $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '',  $id_caso)");
                } else {
                    $base64data = base64_decode($pdfAsBase64, true);
                    $fechactual = date("Y-m-d H:i:s");
                    $anio = date('Y');
                    $strExtensionArch = ".pdf";
                    $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $id_caso . "/";

                    if (!file_exists($strDirectorioFisico)) {
                        //    @mkdir($strDirectorioFisico, 0777, true);
                        File::makeDirectory($strDirectorioFisico, 0777, true, true);
                    }
                    $strNombreArch = date('dmY_His') . $strExtensionArch;
                    $fichero_subido = $strDirectorioFisico . $valor_id . '_' . $strNombreArch;
                    if (file_put_contents($fichero_subido, $base64data)) {
                        // Return the number of bytes saved, or false on failure
                    }
                    $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $fichero_subido, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                    $url = Config::get('var.urlDocumento') . $fichero_subido;
                    $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$url',  $id_caso)");
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

                $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '',  $id_caso)");
            } else {
                $base64data = base64_decode($pdfAsBase64, true);
                $fechactual = date("Y-m-d H:i:s");
                $anio = date('Y');
                $strExtensionArch = ".pdf";
                $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $id_caso . "/";

                if (!file_exists($strDirectorioFisico)) {
                    //@mkdir($strDirectorioFisico, 0777, true);
                    File::makeDirectory($strDirectorioFisico, 0777, true, true);
                }
                $strNombreArch = date('dmY_His') . $strExtensionArch;
                $fichero_subido = $strDirectorioFisico . $valor_id . '_' . $strNombreArch;
                if (file_put_contents($fichero_subido, $base64data)) {
                    // Return the number of bytes saved, or false on failure
                }
                $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, $fichero_subido, $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $detalle_documento);
                $url = Config::get('var.urlDocumento') . $fichero_subido;
                $data_actualizar = \DB::select("select * from  public.sp_actualizar_cas_data_campo('$pfrm_value', '$url',  $id_caso)");
            }
            return response()->json(['data' => '{}', 'success' => $success]);
        }
    }
    public function guardarDocumentoPdf($pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $pdoc_detalle_documento)
    {
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documento (0,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo','$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,'$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria','$switch1', '$id_persona_sip','$id_observacion', '$pdoc_detalle_documento',1)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    function existeDocumentosRequisitos(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $pdoc_referencia = $request['pdoc_referencia'] === null ? '' : $request['pdoc_referencia'];
            $pdoc_categoria = $request['pdoc_categoria'] === null ? '' : $request['pdoc_categoria'];
            if ($request['pdoc_categoria'] == '') {
                return response()->json(['data' => [], 'codigoRespuesta' => $error]);
            } else {
                $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial(?,?,?)', array($request['pdoc_codigo'], $pdoc_referencia, $pdoc_categoria));
                $result = $dataRespon[0]->sp_existe_documento_inicial;
                if ($result) {
                    return response()->json(['data' => [], 'codigoRespuesta' => $success]);
                } else {
                    return response()->json(['data' => [], 'codigoRespuesta' => $error]);
                }
            }


        } catch (Exception $error) {
            print_r(json_encode(["error" => $error]));
        }

    }


    function existeDocumentosRequisitosLegal(Request $request)
    {
        try {
            $pdoc_referencia = $request['pdoc_referencia'] === null ? '' : $request['pdoc_referencia'];
            $dataRespon = \DB::select('select * from   public.sp_existe_documento_inicial_legal(?,?,?,?)', array($request['pdoc_codigo'], $pdoc_referencia, $request['pdoc_categoria'], $request['pdoc_legal']));
            if ($dataRespon) {
                return $dataRespon;
            } else {
                $result = $dataRespon[0]->sp_existe_documento_inicial_legal;
                return $result;
            }
        } catch (Exception $error) {
            print_r(json_encode(["error" => $error]));
        }
    }
    function obtenerDocumentosRequisitos(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $error_info = array("message" => "error de información incompleta", "code" => 700);
        $pdoc_referencia = $request['pdoc_referencia'] === null ? '' : $request['pdoc_referencia'];
        try {
            $dataRespon = \DB::select('select * from   public.sp_obtener_documento_requisito(?,?,?)', array($request['pdoc_codigo'], $pdoc_referencia, $request['pdoc_categoria']));
            if (sizeof($dataRespon) > 0) {
                for ($i = 0; $i < sizeof($dataRespon); $i++) {
                    if ($dataRespon[$i]->rdoc_url == 'Hubo un error al mover el archivo.' || $dataRespon[$i]->rdoc_url == 'Hubo un error al crear el archivo temporal.' || $dataRespon[$i]->rdoc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $descripcionRespaldo = $dataRespon[$i]->rdoc_url;
                    } else {
                        $descripcionRespaldo = $dataRespon[$i]->rdoc_descripcion . '.pdf';
                    }
                    $respRequisitos[$i] = array(
                        'rdoc_id' => $dataRespon[$i]->rdoc_id,
                        'rdoc_codigo' => $dataRespon[$i]->rdoc_codigo,
                        'rdoc_referencia' => $dataRespon[$i]->rdoc_referencia,
                        'rdoc_categoria' => $dataRespon[$i]->rdoc_categoria,
                        'url' => $dataRespon[$i]->rdoc_url,
                        'nombre' => $dataRespon[$i]->rdoc_url,
                        'id' => $dataRespon[$i]->rdoc_doc_id,
                        'descripcionTipoDocumentoRespaldo' => $dataRespon[$i]->rdoc_descripcion,
                        'descripcionRespaldo' => $descripcionRespaldo,
                        'documentoOriginalObligatorio' => $dataRespon[$i]->rdoc_documento_original_obligatorio,
                        'presentacionObligatoria' => $dataRespon[$i]->rdoc_presentacion_obligatoria,
                        'rdoc_copia_original' => $dataRespon[$i]->rdoc_copia_original,
                        'obs_id_observacion' => $dataRespon[$i]->obs_id_observacion !== null ? $dataRespon[$i]->obs_id_observacion : 1,
                        'rdoc_detalle_documento' => $dataRespon[$i]->rdoc_detalle_documento !== null ? $dataRespon[$i]->rdoc_detalle_documento : ''
                    );
                }
                return response()->json(["data" => $respRequisitos, "success" => $success]);
            } else {
                return response()->json(["success" => $error_info]);
            }
        } catch (Exception $error) {
            print_r(json_encode(["error" => $error]));
        }
    }

    function obtenerDocumentosRequisitosLegal(Request $request)
    {

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $error_info = array("message" => "error de información incompleta", "code" => 700);
        $pdoc_referencia = $request['pdoc_referencia'] === null ? '' : $request['pdoc_referencia'];
        try {
            $dataRespon = \DB::select('select * from   public.sp_obtener_documento_requisito(?,?,?)', array($request['pdoc_codigo'], $pdoc_referencia, $request['pdoc_categoria']));
            if (sizeof($dataRespon) > 0) {
                for ($i = 0; $i < sizeof($dataRespon); $i++) {
                    if ($dataRespon[$i]->rdoc_url == 'Hubo un error al mover el archivo.' || $dataRespon[$i]->rdoc_url == 'Hubo un error al crear el archivo temporal.' || $dataRespon[$i]->rdoc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $descripcionRespaldo = $dataRespon[$i]->rdoc_url;
                    } else {
                        $descripcionRespaldo = $dataRespon[$i]->rdoc_descripcion . '.pdf';
                    }
                    $respRequisitos[$i] = array(
                        'rdoc_id' => $dataRespon[$i]->rdoc_id,
                        'rdoc_codigo' => $dataRespon[$i]->rdoc_codigo,
                        'rdoc_referencia' => $dataRespon[$i]->rdoc_referencia,
                        'rdoc_categoria' => $dataRespon[$i]->rdoc_categoria,
                        'url' => $dataRespon[$i]->rdoc_url,
                        'nombre' => $dataRespon[$i]->rdoc_url,
                        'lgreq_id' => $dataRespon[$i]->rdoc_doc_id,
                        'lgreq_descripcion' => $dataRespon[$i]->rdoc_descripcion,
                        'descripcionRespaldo' => $descripcionRespaldo,
                        'documentoOriginalObligatorio' => $dataRespon[$i]->rdoc_documento_original_obligatorio,
                        'presentacionObligatoria' => $dataRespon[$i]->rdoc_presentacion_obligatoria,
                        'rdoc_copia_original' => $dataRespon[$i]->rdoc_copia_original,
                        'obs_id_observacion' => $dataRespon[$i]->obs_id_observacion !== null ? $dataRespon[$i]->obs_id_observacion : 1,
                        'rdoc_detalle_documento' => $dataRespon[$i]->rdoc_detalle_documento !== null ? $dataRespon[$i]->rdoc_detalle_documento : ''
                    );
                }
                return response()->json(["data" => $respRequisitos, "success" => $success]);
            } else {
                return response()->json(["success" => $error_info]);
            }
        } catch (Exception $error) {
            print_r(json_encode(["error" => $error]));
        }
    }

    public function actualizarDocumentoPdf($doc_id, $pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip, $id_observacion, $pdoc_detalle_documento)
    {
        // dd($doc_id, $pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch);
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            $sql = "SELECT * FROM  public.crud_documento ($doc_id,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo','$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,'$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria', '$switch1',' $id_persona_sip', '$pdoc_detalle_documento',2)  ";

            $data = \DB::select("SELECT * FROM  public.crud_documento ($doc_id,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo','$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,'$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria', '$switch1',' $id_persona_sip','$id_observacion', '$pdoc_detalle_documento',2)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function guardarDocumentosCompletarPdf(Request $request)
    {
        $id_doc = $request['id_doc'];
        $valor_id = $request['valor_id'];
        $pdfAsBase64 = $request['pdf'];
        $id_caso = $request['id_caso'];
        $switch = $request['switch'];
        $id_observacion = $request['id_observacion'] === '' ? null : $request['id_observacion'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        if ($pdfAsBase64 != '') {
            $resGetDocumento = $this->documentService->getDocumento($pdfAsBase64, $id_caso);
            $respuesta = $this->guardarDocumentoEditPdf($id_doc, $resGetDocumento, 1, $switch, $id_observacion);
        } else {
            $fichero_subido = '';
            $respuesta = $this->guardarDocumentoEditPdf($id_doc, $fichero_subido, 1, $switch, $id_observacion);
        }
        return response()->json(['data' => '{}', 'success' => $success]);
    }
    
    public function guardarDocumentoEditPdf($id_doc, $fichero_subido, $pdoc_usr_id, $switch, $id_observacion)
    {
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("UPDATE public._gp_documentos
			set
				doc_url = '$fichero_subido',
				doc_usuario = $pdoc_usr_id,
				doc_copia_original = '$switch1',
				doc_modificado= now(),
                doc_id_observacion = $id_observacion::integer
			WHERE
			    doc_id = $id_doc ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerObservacion()
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * from gp_observacion where estado = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function guardarAdjuntos($id)
    {
        return view('documentosAdjuntos.index', ['id' => $id]);
    }
    public function guardarDocumentos(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'RespaldoFile.*' => 'required|file',
        ]);

        // Obtener los archivos enviados desde el formulario
        $archivos = $request->file('RespaldoFile');

        // Iterar sobre los archivos y guardar cada uno
        foreach ($archivos as $archivo) {
            // Guardar el archivo en el sistema de archivos configurado
            Storage::disk('local')->put($archivo->getClientOriginalName(), file_get_contents($archivo));
        }

        // Retornar una respuesta
        return back()->with('success', 'Documentos guardados correctamente.');
    }

    public function store(Request $request)
    {
        // Validar que se hayan enviado archivos
        $request->validate([
            'RespaldoFile.*' => 'required|file',
        ]);

        // Procesar cada archivo enviado
        foreach ($request->file('RespaldoFile') as $file) {
            // Guardar el archivo en la carpeta 'archivos_pdf' en el storage de Laravel

            $path = $file->store('archivos_pdf/2024/21544/', 'public');

            dd($path);
            // Puedes hacer más acciones aquí, como guardar la ruta del archivo en la base de datos
        }

        return "Archivos subidos correctamente.";
    }


    public function guardar(Request $request)
    {
        $numero = $request->input('numero');
        $id = $request->input('id');
        $referencia = $request->input('referencia');
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $numero = $request->input('numero');
            $nombreArchivo = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            /////////////////////////////////////
            $fechactual = date("Y-m-d H:i:s");
            $anio = date('Y');

            $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $id . "/";

            if (!file_exists($strDirectorioFisico)) {
                //@mkdir($strDirectorioFisico, 0777, true);
                File::makeDirectory($strDirectorioFisico, 0777, true, true);
            }
            $strNombreArch = date('dmY_His') . '.' . $extension;
            $fichero_subido = $numero . '_' . $strNombreArch;
            $archivo->move(public_path($strDirectorioFisico), $fichero_subido);
            $archivo_final = $strDirectorioFisico . $fichero_subido;
            $dataHistorico = \DB::select("SELECT *
	                    from rmx_vys_historico_casos
	                    inner join rmx_vys_actividades on act_id = htc_cas_act_id
	                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
	                    inner join users on id = htc_cas_usr_id
                    where htc_cas_id =  $id order by htc_id desc limit 1");
            $respuesta = $this->guardarDocumentoPdf($id, 1, $dataHistorico[0]->htc_id, $dataHistorico[0]->htc_cas_cod_id, 'referencia', 'ADJUNTOS', $archivo_final, $id, 1, $referencia, 'pendiente', '', '', '', '1', '', '1');
            return response()->json(['success' => true]);
        }
    }


    public function guardar2(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');

            // Guardar el archivo en la carpeta 'archivos' dentro de 'storage/app'
            $rutaArchivo = $archivo->store('archivos');

            // Opcional: Guardar la ruta del archivo en la base de datos
            // TuModelo::create(['ruta' => $rutaArchivo]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            // Nombre del file original
            $nombrefile = $request->file('file')->getClientOriginalName();

            // Ruta temporal del file subido
            $rutaTemporal = $request->file('file')->getPathName();

            // Directorio de destino donde deseas mover el file
            $directorioDestino = '/opt/tramites/';

            // Mover el file utilizando move_uploaded_file
            if (move_uploaded_file($rutaTemporal, $directorioDestino . $nombrefile)) {
                return "El file se ha subido correctamente.";
            } else {
                return "Hubo un error al subir el file.";
            }
        } else {
            return "No se ha subido ningún file válido.";
        }
    }


    public function upload2(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $numero = $request->input('numero');
            $id = $request->input('id');
            $referencia = $request->input('referencia');
            $actividad = $request->input('actividad');

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $anio = date('Y');
            $extension = $file->getClientOriginalExtension();
            $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $id . "/";
            if (!file_exists($strDirectorioFisico)) {
                //@mkdir($strDirectorioFisico, 0777, true);
                File::makeDirectory($strDirectorioFisico, 0777, true, true);
            }
            $strNombreArch = date('dmY_His') . '.' . $extension;
            $fichero_subido = $numero . '_' . $strNombreArch;
            $file->move(public_path($strDirectorioFisico), $fichero_subido);
            $archivo_final = $strDirectorioFisico . $fichero_subido;

            if ($actividad == 20) {
                $dataHistorico = \DB::select("SELECT * from  public.rmx_vys_casos inner join public.rmx_vys_historico_casos on cas_id = htc_cas_id where  cas_id =  $id   order by htc_cas_id desc limit 1");
            } else {
                $dataHistorico = \DB::select("SELECT *
	                    from rmx_vys_historico_casos
	                    inner join rmx_vys_actividades on act_id = htc_cas_act_id
	                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
	                    inner join users on id = htc_cas_usr_id
                    where htc_cas_id =  $id order by htc_id desc limit 1");
            }



            $respuesta = $this->guardarDocumentoPdf($id, 1, $dataHistorico[0]->htc_id, $dataHistorico[0]->htc_cas_cod_id, 'ADJUNTOS', 'ADJUNTOS', $archivo_final, $id, 1, $referencia, 'pendiente', '', '', '', '1', '', '1');
            return response()->json(['success' => true, 'message' => 'Documento PDF guardado con éxito.']);
        }
        return response()->json(['success' => false, 'message' => 'Archivo no válido.']);
    }
    public function verificarAccesoNFS2(Request $request)
    {


        // $contents = Storage::disk('nfs')->get('saludo.txt');


        $rutaArchivo = 'saludo.txt';



        if (Storage::disk('nfs')->exists($rutaArchivo)) {
            return 'Acceso NFS exitoso.';
        } else {
            return 'No se pudo acceder al NFS.';
        }
    }
    public function verificarAccesoNFS(Request $request)
    {


        // $contents = Storage::disk('nfs')->get('saludo.txt');


        $serv = '/opt/tramites/';
        $nomfile = 'pdf_form_firmadojuan.pdf';

        if (strstr($serv, "/opt/tramites/")) {
            $rutaCompleta = $serv . $nomfile;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {

                // header('Content-Description: File Transfer');
                // header('Content-Type: application/pdf');
                // header('Content-Disposition: inline; filename="' . basename($nomfile) . '"');
                // header('Expires: 0');
                // header('Cache-Control: must-revalidate');
                // header('Pragma: public');
                // header('Content-Length: ' . filesize($rutaCompleta));
                // // Leemos y enviamos el contenido del archivo al navegador
                // readfile($rutaCompleta);
                globalGetPdfDocument($rutaCompleta,$nomfile);
                exit(0); // Éxito
            } else {
                // Directorio o archivo no encontrado
                echo "<b>No Existe Documento!!!</b>";
                exit(1); // Error
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

}

