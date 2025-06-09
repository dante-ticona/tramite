<?php

namespace App\Http\Controllers\documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use TCPDF;
use Illuminate\Support\Facades\File;
use Config;
use App\Http\Controllers\documentos\documentosPrevisualizacionController;
use App\Http\Controllers\ApiVySController;
use Illuminate\Support\Facades\Log;


class documentosCotroller extends Controller
{
    public function guardarDocumentoPdf($pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documento (0,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo','$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,'$pdoc_descripcion','$pdoc_estado_preparacion',1)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerDocumentoPdf(Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');
        $htc_id = $request["htc_id"];

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $respDocumento = [];
        try {
            $sql = "SELECT * FROM  public._gp_documentos
                    inner join public.dominio on subdominio = doc_referencia
                    where doc_his_id = $htc_id and doc_estado='A' order by orden, doc_doc_id  asc ";

            $dataRespon = \DB::select($sql);

            $ip = request()->ip();
            $data_json = json_encode([], 0);
            $data_log = \DB::select(
                "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                array($sql, $data_json, $usuario, $pro, $tramite, $ip)
            );

            for ($i = 0; $i < sizeof($dataRespon); $i++) {
                if ($dataRespon[$i]->doc_url == 'Hubo un error al mover el archivo.' || $dataRespon[$i]->doc_url == 'Hubo un error al crear el archivo temporal.' || $dataRespon[$i]->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $descripcionRespaldo = $dataRespon[$i]->doc_url;
                    $url = Config::get('var.urlDocumento') . '';
                    $nombre = '';
                } else {
                    $descripcionRespaldo = $dataRespon[$i]->doc_descripcion;
                    $url = Config::get('var.urlDocumento') . $dataRespon[$i]->doc_url;
                    $nombre = $dataRespon[$i]->doc_url;
                }
                $respDocumento[$i] = array(
                    'doc_id' => $dataRespon[$i]->doc_id,
                    'url_documento' => $url,
                    'nombre' => $nombre,
                    'descripcion' => $descripcionRespaldo,
                    'tipo' => $dataRespon[$i]->nombre,
                    'id_doc_base64' => $dataRespon[$i]->id_doc_base64
                );
            }

            return response()->json(["data" => $respDocumento, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerDocumentoPdflegal(Request $request)
    {
        $htc_cas_cod_id = $request["htc_cas_cod_id"];
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $respDocumento = [];
        try {
            $sql = "SELECT * FROM  public._gp_documentos
                                        inner join public.dominio on subdominio = doc_referencia
                                        where doc_codigo = '$htc_cas_cod_id' order by orden, doc_doc_id  asc";
            $dataRespon = \DB::select($sql);

            $ip = request()->ip();
            $data_json = json_encode([], 0);
            $data_log = \DB::select(
                "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                array($sql, $data_json, $usuario, $pro, $htc_cas_cod_id, $ip)
            );

            for ($i = 0; $i < sizeof($dataRespon); $i++) {
                if ($dataRespon[$i]->doc_url == 'Hubo un error al mover el archivo.' || $dataRespon[$i]->doc_url == 'Hubo un error al crear el archivo temporal.' || $dataRespon[$i]->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $descripcionRespaldo = $dataRespon[$i]->doc_url;
                    $url = Config::get('var.urlDocumento') . '';
                    $nombre = '';
                } else {
                    $descripcionRespaldo = $dataRespon[$i]->doc_descripcion;
                    $url = Config::get('var.urlDocumento') . $dataRespon[$i]->doc_url;
                    $nombre = $dataRespon[$i]->doc_url;
                }
                $respDocumento[$i] = array(
                    'doc_id' => $dataRespon[$i]->doc_id,
                    'url_documento' => $url,
                    'nombre' => $nombre,
                    'descripcion' => $descripcionRespaldo,
                    'tipo' => $dataRespon[$i]->nombre
                );
            }

            return response()->json(["data" => $respDocumento, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerDocumentoPdfAdjunto(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $respDocumento = [];

        try {
            $dataRespon = \DB::select("SELECT * FROM  public._gp_documentos
                                        inner join public.dominio on subdominio = doc_referencia
                                        where doc_cas_id = $cas_id  and nombre = 'ADJUNTO' and doc_estado ='A' order by doc_id asc ");
            for ($i = 0; $i < sizeof($dataRespon); $i++) {
                $respDocumento[$i] = array(
                    'doc_id' => $dataRespon[$i]->doc_id,
                    'url_documento' => Config::get('var.urlDocumento') . $dataRespon[$i]->doc_url,
                    'nombre' => $dataRespon[$i]->doc_url,
                    'descripcion' => $dataRespon[$i]->doc_descripcion,
                    'tipo' => $dataRespon[$i]->nombre
                );
            }
            return response()->json(["data" => $respDocumento, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerDocumentoPdfAdjuntoMedico(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $respDocumento = [];
        $sql = "SELECT * FROM  public._gp_documentos
                                        inner join public.dominio on subdominio = doc_referencia
                                        where doc_cas_id = $cas_id  and nombre = 'ADJUNTO_MEDICOS' and doc_estado ='A' order by doc_id asc ";
        try {
            $dataRespon = \DB::select($sql);
            for ($i = 0; $i < sizeof($dataRespon); $i++) {
                $respDocumento[$i] = array(
                    'doc_id' => $dataRespon[$i]->doc_id,
                    'url_documento' => Config::get('var.urlDocumento') . $dataRespon[$i]->doc_url,
                    'nombre' => $dataRespon[$i]->doc_url,
                    'descripcion' => $dataRespon[$i]->doc_descripcion,
                    'tipo' => $dataRespon[$i]->nombre
                );
            }
            return response()->json(["data" => $respDocumento, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
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
                            'url_documento' => Config::get('var.urlDocumento') . $dataRespon[$i]->doc_url,
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
    public function obtenerDocumentoPdfEdit(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        $respDocumento = [];
        try {
            $dataRespon = \DB::select("SELECT *, doc_copia_original:: bool as rdoc_copia_original FROM  public._gp_documentos
                                        inner join public.dominio on subdominio = doc_referencia
                                        where doc_cas_id = $cas_id and ((doc_url is null or doc_url = '') or doc_id_observacion != 1) order by orden, doc_doc_id  asc ");

            for ($i = 0; $i < sizeof($dataRespon); $i++) {
                $respDocumento[$i] = array(
                    'doc_id' => $dataRespon[$i]->doc_id,
                    'url_documento' => Config::get('var.urlDocumento') . $dataRespon[$i]->doc_url,
                    'nombre' => $dataRespon[$i]->doc_url,
                    'descripcion' => $dataRespon[$i]->doc_descripcion,
                    'tipo' => $dataRespon[$i]->nombre,
                    'doc_copia_original' => $dataRespon[$i]->rdoc_copia_original,
                    'doc_id_onbservacion' => $dataRespon[$i]->doc_id_observacion
                );
            }
            return response()->json(["data" => $respDocumento, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarImpresiones($act_id)
    {
        if (!empty($act_id)) {
            $where = 'AND impact_act_id = ' . $act_id;
        }
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT imp_id, imp_nombre, imp_data, imp_estado
				FROM rmx_vys_impresiones
                inner join rmx_vys_impresiones_actividades on impact_imp_id = imp_id
				WHERE imp_estado = 'A' and impact_estado='A' $where
				ORDER BY imp_nombre");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
    public function generatePdf(Request $request)
    {
        $act_id = $request["act_id"];

        $cas_id = $request["cas_id"];
        $act_usr_id = $request["act_usr_id"];



        $respImpreciones = $this->listarImpresiones($act_id);

        $data = \DB::select("SELECT *
				FROM rmx_vys_impresiones
				WHERE imp_estado = 'A'
                AND imp_id = ANY(public.impresiones($cas_id,$act_id))
				ORDER BY imp_nombre");


        $contentImpreciones = $respImpreciones->getContent();
        $dataImpreciones = json_decode($contentImpreciones, true);


        $dataHistorico = \DB::select(
            "SELECT * from  public.rmx_vys_casos
            inner join public.rmx_vys_historico_casos on cas_id = htc_cas_id
            where  cas_id =  $cas_id    order by htc_cas_id desc limit 1"
        );

        for ($i = 0; $i < count($data); $i++) {

            $datos = array(
                'act_id' => $act_id,
                'act_usr_id' => $act_usr_id,
                'cas_id' => $cas_id,
                'impid' => $data[$i]->imp_id,
                'nombre_doc' => $data[$i]->imp_nombre
            );

            if (isset($data[$i]->imp_tipo) && !empty($data[$i]->imp_tipo) && $data[$i]->imp_tipo == "2") {
                $apiVySController = new ApiVySController;
                $requestEnvio = new Request($datos);
                $resultado = $apiVySController->generateFormRescepcionDocumento($requestEnvio);
            } else {
                $newPrevisualizacion = new documentosPrevisualizacionController;
                $requestEnvio = new Request($datos);
                $resultado = $newPrevisualizacion->generatePdf($requestEnvio);
            }

            $fechactual = date("Y-m-d H:i:s");
            $anio = date('Y');
            $strExtensionArch = ".pdf";
            $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $cas_id . "/";
            $strDirectorioVirtual = "archivos_pdf/" . $anio . "/" . $cas_id . "/";

            if (!file_exists($strDirectorioFisico)) {
                @mkdir($strDirectorioFisico, 0777, true);
            }
            $strNombreArch = date('dmY_His') . $strExtensionArch;
            $fichero_subido = $strDirectorioFisico . '_' . $i . '_' . $strNombreArch;
            if (file_put_contents($fichero_subido, $resultado['mensaje'])) {
                // Return the number of bytes saved, or false on failure
            }
            //$dataHistorico[0]->cas_cod_id
            // ($pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,$pdoc_codigo,$pdoc_referencia,$pdoc_categoria,$fichero_subido,$pdoc_doc_id,$pdoc_usuario)
            // dd('data para guardar historicos   ', $dataImpreciones);

            // dd('data para guardar historicos   ', $dataImpreciones['data'][$i]['imp_nombre']);
            $success = array("code" => 200, "mensaje" => 'OK', );
            $error = array("message" => "error de instancia", "code" => 500);
            try {
                $respuesta = $this->guardarDocumentoPdf(
                    $dataHistorico[0]->cas_id,
                    $dataHistorico[0]->cas_usr_id,
                    $dataHistorico[0]->htc_id,
                    $dataHistorico[0]->cas_cod_id,
                    'referencia',
                    'categoria',
                    $fichero_subido,
                    9,
                    1,

                    $data[$i]->imp_nombre,
                    99
                );
            } catch (error $e) {
                return response()->json(["error" => $error]);
            }
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        return response()->json(['success' => $success]);
    }


    public function listarCasosXImpresion(Request $request)
    {
        $usr_id = $request["usr_id"];
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT c.cas_data_valores
				FROM rmx_vys_casos c JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id
                    join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                    join rmx_vys_nodos on nodo_id = a.act_nodo_id
				WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                    AND n.usn_estado <> 'X'
					AND n.usn_user_id = $usr_id
                    and c.cas_id= $cas_id
                    and c.cas_act_id=$cas_act_id
				ORDER BY c.cas_modificado desc");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }


    public function listarImpresion(Request $request)
    {
        $_id = $request['act_id'];

        if (!empty($_id)) {
            $where = 'AND impact_act_id = ' . $_id;
        }
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT imp_id, imp_nombre, imp_data, imp_estado
				FROM rmx_vys_impresiones
                inner join rmx_vys_impresiones_actividades on impact_imp_id = imp_id
				WHERE imp_estado = 'A' and impact_estado='A' $where
				ORDER BY imp_nombre");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
}
