<?php

namespace App\Http\Controllers\legal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use TCPDF;

class LegalController extends Controller
{
    public function getPrestaciones(Request $request)
    {
        $tipo_proceso = $request["tipo_proceso"];
        $subcategoria = $request["subcategoria"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from lgprestaciones
                    inner join lgrequisitos on lgreq_prest_id=lgp_id
                    where lgp_estado='A' and lgp_prs_id='$tipo_proceso' and lgp_proc_subcategoria='$subcategoria'");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function getPrestacionesTipo(Request $request)
    {

        $tipo_req = $request["tipo_proceso"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from lgrequisitos where lgreq_estado='A' and lgreq_prest_id in ($tipo_req)and lgreq_orden>1");
            //print_r($data);

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function getApiPrestaciones()
    {

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select lgp_id,lgp_nombre
                                from lgprestaciones
                                where lgp_estado='A' and lgp_idpadre=0   order by lgp_nombre asc;");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function postApiPrestacionesIdPadre(Request $request)
    {
        $plgp_idpadre = $request["selectedValue"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select lgp_id,lgp_nombre
                                from lgprestaciones
                                where lgp_estado='A' and lgp_idpadre  = $plgp_idpadre  order by lgp_nombre asc;");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function getApiPrestacionesIdProcesosSubCat(Request $request)
    {
        $id_procesos = $request["pdoc_codigo"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(" select lgpsub_id ,lgpsub_nombre
                                  from lgprestaciones_subclf
                                where lgpsub_prs_id=$id_procesos and lgpsub_estado='A' order by lgpsub_nombre asc;");

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function getApiPrestacionesIdProcesos(Request $request)
    {
        $id_subCategoria = $request["id_subCategoria"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select lgp_id,lgp_nombre from lgprestaciones
                    where lgp_estado='A'   and lgp_lgpsub_id = $id_subCategoria  order by lgp_nombre asc;");

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function getApiParentesco(Request $request)
    {
        //dd($request);
        $relacion = $request["relacion"]; // Recibe la relación desde el request
        $es_fallecido = $request["es_fallecido"]; // Recibe si está fallecido, por defecto false

        $success = ["code" => 200, "mensaje" => 'OK'];
        $error = ["message" => "Error de instancia", "code" => 500];

        try {
            // Consulta usando parámetros seguros para evitar inyección SQL
            $data = \DB::select("
                SELECT ROW_NUMBER() OVER (ORDER BY dr.lgp_dr_id) AS id, tr.lg_tp_nombre AS \"relacion\",
                dr.lgp_dr_documento AS \"descripcionTipoDocumentoRespaldo\",
                dr.lgp_dr_documentoOriginalObligatorio AS \"documentoOriginalObligatorio\",
                dr.lgp_dr_presentacionObligatoria AS \"presentacionObligatoria\",
                dr.lgp_dr_obs_id_observacion AS \"obs_id_observacion\"
                        FROM lgp_documentos_requeridos dr
                JOIN lgp_tipo_relacion tr ON dr.lgp_dr_tipo_relacion_id = tr.lg_tp_id
                WHERE tr.lg_tp_nombre = ? AND dr.lgp_dr_es_fallecido = ?", [$relacion, $es_fallecido]);

            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error, "details" => $e->getMessage()], 500);
        }
    }

    public function getApiSinParentesco(Request $request)
    {
        //dd($request);
        $relacion = '3-OTR'; // Recibe la relación desde el request
        $es_fallecido = false; // Recibe si está fallecido, por defecto false

        $success = ["code" => 200, "mensaje" => 'OK'];
        $error = ["message" => "Error de instancia", "code" => 500];

        try {
            // Consulta usando parámetros seguros para evitar inyección SQL
            $data = \DB::select("
                SELECT   ROW_NUMBER() OVER (ORDER BY dr.lgp_dr_id) AS id,tr.lg_tp_nombre AS \"relacion\",
                dr.lgp_dr_documento AS \"descripcionTipoDocumentoRespaldo\",
                dr.lgp_dr_documentoOriginalObligatorio AS \"documentoOriginalObligatorio\",
                dr.lgp_dr_presentacionObligatoria AS \"presentacionObligatoria\",
                dr.lgp_dr_obs_id_observacion AS \"obs_id_observacion\"
                FROM lgp_documentos_requeridos dr
                JOIN lgp_tipo_relacion tr ON dr.lgp_dr_tipo_relacion_id = tr.lg_tp_id
                WHERE tr.lg_tp_nombre = ? AND dr.lgp_dr_es_fallecido = ?", [$relacion, $es_fallecido]);

            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error, "details" => $e->getMessage()], 500);
        }
    }

    public function getApiRechazo(Request $request)
    {
        $tipo_proceso = $request["tipo_proceso"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select lgmr_id as id, lgmr_descripcion as text from lgmotivosrechazo
                                inner join lgprestaciones on lgmr_req_id=lgp_id
                                where lgmr_estado ='A' and lgmr_req_id=$tipo_proceso ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function listarProcesosId()
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select prc_id,prc_data->>'prc_descripcion' as descripcion
                                from rmx_vys_procesos p
                                where prc_estado <> 'X' and prc_id <> 15");
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function listarPrestacionesId(Request $request)
    {
        $lgp_id_procesos = $request["lgp_id_procesos"];
        $lgp_nombre = $request["lgp_nombre"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);

        try {
            $data = \DB::select("select lgp_id,lgp_nombre from lgprestaciones
                    where lgp_estado='A'  and lgp_id_procesos = $lgp_id_procesos  order by lgp_nombre asc;");
            if (empty($data)) {
                $data = \DB::select("select lgp_id, lgp_nombre
                                             from lgprestaciones
                                             where lgp_estado='A' and lgp_idpadre=0
                                             order by lgp_nombre asc;");
            }

            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function listarRequisitosId(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from public.lgrequisitos
                                    where lgreq_prest_id = 17;");
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function getApiRecuperacionDatos(Request $request)
    {
        $cas_cod = $request["cas_cod"]; ///caso_cod heredado
        $caso_nombre = $request["caso_nombre"]; ///caso_cod legal

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_cod_id =  '$cas_cod'"); //cas_is caso heredadp
            $id_caso_real = $data_caso_real[0]->cas_padre_id;

            $respuesta = \DB::select("UPDATE public.rmx_vys_casos set cas_dependiente_id = $id_caso_real where cas_cod_id = '$caso_nombre' ");
            ///cas_id caso legal
            $data = \DB::select("select  cas_id,cas_data_valores
                            from public.rmx_vys_casos c
                            WHERE cas_estado='T' and  cas_cod_id='$cas_cod'
                            order by 1 desc limit 1 ");
            $id_caso = $data[0]->cas_id;
            $relacion = \DB::select("INSERT INTO public.lg_casos_validaciones (lcv_caso_id, lcv_caso_cod_id, lcv_validacion_id, lcv_validacion_cod_id,
                                                    lcv_registrado, lcv_modificado, lcv_usr_id, lcv_estado)
                                    VALUES($id_caso_real, '$cas_cod', $id_caso, '$caso_nombre', now(), now(), 0, 'A'::bpchar);");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }




    public function postRecuperacionDocumentos(Request $request)
    {
        $cas_cod = $request["cas_cod"];
        $tipo = $request["tipo"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {

            $dataRespon = \DB::select("select * from   public._gp_documentos where doc_codigo = '$cas_cod' and doc_referencia = '$tipo' ");

            for ($i = 0; $i < sizeof($dataRespon); $i++) {
                if ($dataRespon[$i]->doc_url == 'Hubo un error al mover el archivo.' || $dataRespon[$i]->doc_url == 'Hubo un error al crear el archivo temporal.' || $dataRespon[$i]->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $descripcionRespaldo = $dataRespon[$i]->doc_url;
                } else {
                    $descripcionRespaldo = $dataRespon[$i]->doc_descripcion . '.pdf';
                }
                $respRequisitos[$i] = array(
                    'rdoc_id' => $dataRespon[$i]->doc_id,
                    'rdoc_codigo' => $dataRespon[$i]->doc_codigo,
                    'rdoc_referencia' => $dataRespon[$i]->doc_referencia,
                    'rdoc_categoria' => $dataRespon[$i]->doc_categoria,
                    'url' => $dataRespon[$i]->doc_url,
                    'nombre' => $dataRespon[$i]->doc_url,
                    'id' => $dataRespon[$i]->doc_doc_id,
                    'descripcionTipoDocumentoRespaldo' => $dataRespon[$i]->doc_descripcion,
                    'descripcionRespaldo' => $descripcionRespaldo,
                    'documentoOriginalObligatorio' => $dataRespon[$i]->doc_documento_original_obligatorio,
                    'presentacionObligatoria' => $dataRespon[$i]->doc_presentacion_obligatoria,
                    'rdoc_copia_original' => $dataRespon[$i]->doc_copia_original,
                    // 'obs_id_observacion' => $dataRespon[$i]->obs_id_observacion !== null ? $dataRespon[$i]->obs_id_observacion : 1,
                    // 'rdoc_detalle_documento' => $dataRespon[$i]->doc_detalle_documento !== null ? $dataRespon[$i]->doc_detalle_documento : ''
                );
            }


            return response()->json(["data" => $respRequisitos, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => $respRequisitos, "codigoRespuesta" => $error]);
        }
    }


    public function datosJubRmin(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {

            $client = new Client([
                'verify' => false
            ]);
            $response = $client->get(urlsggTest() . '/compensacion-cotizacion/api/v1/numeroCuotas/consulta?nroTramite=' . $cas_cod_jub);
            $dataServ = json_decode($response->getBody(), true);
            if (200 == 200) {
                $dataArray = $dataServ['data'];
                $valorCuotaSolicitud = $dataArray['valorCuotaSolicitud'];
                $valorCuota = $dataArray['valorCuota'];
                $fechaValorCuota = $dataArray['fechaValorCuota'];
                $numeroCuotas = $dataArray['numeroCuotas'];
                $saldoBolivianos = $valorCuota * $numeroCuotas;
                $dataRespon = \DB::select("select * from public.sp_act_cas_rmin_rech_jub_v2('$cas_cod_jub','$cas_cod_pcc','$numeroCuotas','$valorCuota', '$saldoBolivianos') ");

                return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
            } else {

                return response()->json(["data" => [], "codigoRespuesta" => $error]);
            }
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function datosPmPcc(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $descripcion_rechazo = $request["descripcion_rechazo"];


        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_act_cas_pcc_rech_pm('$cas_cod_jub','$cas_cod_pcc','$descripcion_rechazo') ");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function datosJubPcc(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $descripcion_rechazo = $request["descripcion_rechazo"];


        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_act_cas_pcc_rech_jub('$cas_cod_jub','$cas_cod_pcc','$descripcion_rechazo') ");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    /*public function guardarAdjuntosLegal(Request $request)
    {
        return response()->json(["message" => "Todos los archivos se han subido correctamente."]);
    }*/

    public function datosJubPccDocumentos(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {






            $dataResponValidacion = \DB::select("select count(*) from   public._gp_documentos where doc_codigo = '$cas_cod_pcc'  and doc_categoria != '' ");
            if ($dataResponValidacion[0]->count > 0) {
                $dataRespon = \DB::select("UPDATE public._gp_documentos
                                                            SET
                                                            doc_referencia = '',
                                                            doc_categoria = ''
                                                            WHERE   doc_codigo= '$cas_cod_pcc';");
            } else {
                $dataResponHistorial = \DB::select("select *
                                                            from rmx_vys_historico_casos
                                                            where  htc_cas_cod_id ='$cas_cod_pcc' order by htc_id asc limit 1");
                //dd($dataResponHistorial[0]->htc_id);
                $htc_id = $dataResponHistorial[0]->htc_id;
                $dataResponDocumentosGuardados = \DB::select("
                INSERT INTO public._gp_documentos ( doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_registrado, doc_modificado, doc_usuario, doc_estado, doc_descripcion, doc_estado_preparacion, doc_documento_original_obligatorio, doc_presentacion_obligatoria, doc_copia_original, doc_id_persona_sip, doc_id_observacion, doc_detalle_documento, alerta, doc_prestacion)
                    select  doc_cas_id, doc_usr_id,$htc_id, '$cas_cod_pcc', doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_registrado, doc_modificado, doc_usuario, doc_estado, doc_descripcion, doc_estado_preparacion, doc_documento_original_obligatorio, doc_presentacion_obligatoria, doc_copia_original, doc_id_persona_sip, doc_id_observacion, doc_detalle_documento, alerta, doc_prestacion
                    FROM public._gp_documentos
                    WHERE doc_codigo = '$cas_cod_jub'; ");
            }

            return response()->json(["data" => $dataResponValidacion, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function ValidarDatosJubPcc(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_act_cas_pcc_rech_jub_validar('$cas_cod_jub','$cas_cod_pcc') ");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function ValidarDatosJubRmin(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_act_cas_rmin_rech_jub_validar('$cas_cod_jub','$cas_cod_pcc') ");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function ValidarDatosPmPcc(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_act_cas_pcc_rech_pencion_muerto_validar('$cas_cod_jub','$cas_cod_pcc') ");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function guardarAdjuntosLegal(Request $request)
    {
        if ($request->hasFile('archivos')) {
            $files = $request->file('archivos');
            $descripciones = $request->input('descripciones', []);
            $uploadedFiles = [];

            foreach ($files as $index => $file) {
                if ($file->isValid()) {
                    // Genera un nombre único para cada archivo
                    $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads');

                    // Verifica si la carpeta de destino existe, si no, la crea
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }

                    // Mueve el archivo a la carpeta de destino
                    $file->move($destinationPath, $fileName);

                    // Agrega la información del archivo al array $uploadedFiles
                    $uploadedFiles[] = [
                        'nombre_original' => $file->getClientOriginalName(),
                        'nombre_guardado' => $fileName,
                        'ruta' => '/uploads/' . $fileName,
                        'descripcion' => $descripciones[$index] ?? ''
                    ];
                }
            }

            // Devuelve la respuesta con la lista de archivos subidos
            return response()->json(['success' => 'Archivos subidos correctamente', 'files' => $uploadedFiles]);
        } else {
            // Devuelve una respuesta de error si no se subieron archivos
            return response()->json(['error' => 'No se pudieron subir los archivos'], 400);
        }
    }

    public function getApiProceso()
    {
        // Definir las respuestas para éxito y error
        $success = ["code" => 200, "message" => "OK"];
        $error = ["message" => "Error de instancia", "code" => 500];

        try {


            // Realizar la consulta utilizando los parámetros dinámicos
            $data = \DB::select(
                "SELECT prc_id,  prc_data->>'prc_descripcion' AS nombre
                FROM rmx_vys_procesos
                where prc_estado='A' and prc_proceso='P'
                order by prc_id ASC",

            );

            // Retornar la respuesta JSON
            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            // Manejar errores y retornar la respuesta
            $error['message'] = $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }

    public function datosRminPm(Request $request)
    {
        $cas_cod_jub = $request["cas_cod"];
        $cas_cod_pcc = $request["caso_nombre"];
        $descripcion_rechazo = $request["descripcion_rechazo"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_act_cas_rmin_rech_pm('$cas_cod_jub','$cas_cod_pcc','$descripcion_rechazo') ");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function getApiRequisitosId(Request $request)
    {
        // Definir las respuestas para éxito y error
        $success = ["code" => 200, "message" => "OK"];
        $error = ["message" => "Error de instancia", "code" => 500];

        try {
            $pCasoId = $request['id_caso'];
            $pTipoDocumentoIds = $request['req_prest_id']; // Array de IDs

            // Validar que el array no esté vacío
            if (empty($pTipoDocumentoIds) || !is_array($pTipoDocumentoIds)) {
                return response()->json(["error" => "El campo req_prest_id debe ser un array no vacío"], 400);
            }

            // Construir dinámicamente los placeholders `?` para el IN
            $placeholders = implode(',', array_fill(0, count($pTipoDocumentoIds), '?'));

            // Realizar la consulta con los valores expandidos
            $dataDocumentosConfiguradosProceso = \DB::select(
                "SELECT lgreq_id, lgreq_descripcion, lgreq_orden, lgreq_obligatorio,lgreq_original, lgreq_prest_id
            FROM public.lgrequisitos
            WHERE lgreq_estado = 'A' AND lgreq_prest_id IN ($placeholders)",
                $pTipoDocumentoIds
            );


            //dd($placeholders,[$pCasoId], $pTipoDocumentoIds);
            /*"SELECT d.doc_id, d.doc_url, d.doc_descripcion, d.doc_value_es_original
            FROM public._gp_documentos d
            WHERE d.doc_cas_id = ?
              AND d.lgreq_id IN (
                SELECT lgreq_id
                FROM public.lgrequisitos
                WHERE lgreq_estado = 'A' AND lgreq_prest_id IN ($placeholders)
              )
              AND d.doc_estado = 'A'
            ORDER BY d.doc_registrado DESC",
                array_merge([$pCasoId], $pTipoDocumentoIds)*/

            $dataDocumentosRegistrados = \DB::select(

                "SELECT d.doc_id, d.doc_url, d.doc_descripcion, d.doc_value_es_original,d.lgreq_id,d.doc_actdoc_id
            FROM public._gp_documentos d
            WHERE d.doc_cas_id = ?
              AND d.lgreq_id IN (
                SELECT lgreq_id
                FROM public.lgrequisitos
                WHERE lgreq_estado = 'A' AND lgreq_prest_id IN ($placeholders)
              )
              AND d.doc_estado = 'A'
            ORDER BY d.doc_registrado desc",
                array_merge([$pCasoId], $pTipoDocumentoIds)
            );

            //dd($dataDocumentosRegistrados);
            $dataRespon = collect($dataDocumentosConfiguradosProceso)->map(function ($item) use ($dataDocumentosRegistrados) {
                $element = [
                    'ad_actdoc_id' => $item->lgreq_id,
                    'ad_actdoc_config_es_original' => $item->lgreq_original, //ad_actdoc_config_es_original
                    'ad_actdoc_config_es_obligatorio' => $item->lgreq_obligatorio,
                    'ad_doc_nombre' => $item->lgreq_descripcion
                ];
                $foundUploadDocument = collect($dataDocumentosRegistrados)->firstWhere('lgreq_id', $item->lgreq_id);

                if ($foundUploadDocument) {
                    $element['doc_id'] = $foundUploadDocument->doc_id;
                    $element['doc_descripcion'] = $foundUploadDocument->doc_descripcion;
                    $element['doc_url'] = $foundUploadDocument->doc_url;
                    $element['doc_value_es_original'] = $foundUploadDocument->doc_value_es_original;
                }

                return $element;
            });

            return response()->json(["data" => $dataRespon, "success" => $success]);
        } catch (\Exception $e) {
            $error['message'] = $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }

    public function cambioEstadoEap(Request $request)
    {
        $cas_id = $request["cas_id"];
        $estado = $request["estado"];
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            // Obtener los datos originales
            $results = \DB::select(" UPDATE rmx_vys_casos
                                                SET cas_data_valores = jsonb_set(
                                                    cas_data_valores,
                                                    ('{' || sub.idx - 1 || ', frm_value}')::text[],
                                                    '\"$estado\"',
                                                    false)
                                                FROM (
                                                    SELECT cas_id, idx
                                                    FROM rmx_vys_casos,
                                                    jsonb_array_elements(cas_data_valores) WITH ORDINALITY arr(elem, idx)
                                                    WHERE elem->>'frm_campo' = 'ESTADO_GENERACION_EAP'
                                                    AND cas_id =  $cas_id
                                                ) sub
                                            WHERE rmx_vys_casos.cas_id = sub.cas_id;");
            return response()->json(["data" => $results, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function getCasosLegalCua(Request $request)
    {
        $cua = $request["cua"];
        $cas_id = $request["cas_id"];
        //$estado = $request["estado"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data1 = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
            from rmx_vys_casos where cas_id = $cas_id");
            $cas_id = $data1[0]->caso_id;
            $data = \DB::select("select distinct cas_id, cas_act_id, cas_nodo_id, cas_data, cas_usr_id, cas_cod_id,
                                    a.act_data->>'act_orden' as act_orden, a.act_data->>'act_descripcion' as act_descripcion, n.nodo_descripcion,
                                    (select count(*) from lg_casos_validaciones lg where lg.lcv_validacion_id=c.cas_id) as total
                                    ,(select case when lg.lcv_caso_id = $cas_id  then 1 else 0 end from lg_casos_validaciones lg where lg.lcv_validacion_id=c.cas_id order by lcv_id  desc limit 1) as tram
                                from rmx_vys_casos c
                                join rmx_vys_actividades a ON a.act_id = c.cas_act_id
                                join rmx_vys_nodos n on nodo_id = a.act_nodo_id
                                where cas_data->>'AS_CUA'='$cua' and cas_cod_id like 'LEGAL/%'  order by cas_id asc");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function getCasosLegalTramite(Request $request)
    {
        $cas_id = $request["cas_id"];
        //$estado = $request["estado"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data1 = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
            from rmx_vys_casos where cas_id = $cas_id");
            $cas_id = $data1[0]->caso_id;
            // $cas_id = 53546;
            $data = \DB::select("select cas_id, cas_act_id, cas_nodo_id, cas_data, cas_usr_id, cas_cod_id,
                                    a.act_data->>'act_orden' as act_orden, a.act_data->>'act_descripcion' as act_descripcion, n.nodo_descripcion,
                                    (select count(*) from lg_casos_validaciones where lcv_validacion_id=c.cas_id) as total
                                from lg_casos_validaciones lg
                                join rmx_vys_casos c on lcv_validacion_id = c.cas_id
                                join rmx_vys_actividades a ON a.act_id = c.cas_act_id
                                join rmx_vys_nodos n on nodo_id = a.act_nodo_id
                                where lcv_caso_id=$cas_id  order by cas_id asc;");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerDocumentoLegalGral(Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');
        $htc_cas_id = $request["htc_cas_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        $respDocumento = [];
        try {
            $sql = "SELECT * FROM  public._gp_documentos
                    inner join public.dominio on subdominio = doc_referencia
                    where doc_cas_id = $htc_cas_id
                    and doc_descripcion in ('PODER DE INICIO DE TRAMITE RECHAZADO',
                                    'NOTIFICA ACEPTACION  PODER PARA TRAMITE DE INICIO',
                                    'FORMULARIO DE REGISTRO DE DOCUMENTOS (FRDL)',
                                    'FORMULARIO DE REVISION GERENCIA NACIONAL LEGAL',
                                    'NOTIFICA ACEPTACION PODER DE COBRO')
                    order by orden, doc_doc_id  asc ";
            //dd($sql);
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
                    'tipo' => $dataRespon[$i]->nombre
                );
            }
            return response()->json(["data" => $respDocumento, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function enlazarPrestacionLegal(Request $request)
    {
        $cas_prestacion_id = $request["cas_prestacion_id"];
        $cas_prestacion_cod_id = $request["cas_prestacion_cod_id"];
        $cas_legal_id = $request["cas_legal_id"];
        $cas_legal_cod_id = $request["cas_legal_cod_id"];
        $userid = $request["userid"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $data1 = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
            from rmx_vys_casos where cas_id = $cas_prestacion_id");
            $cas_prestacion_id = $data1[0]->caso_id;
            $sql = "INSERT INTO public.lg_casos_validaciones (lcv_caso_id, lcv_caso_cod_id, lcv_validacion_id, lcv_validacion_cod_id,
                                                    lcv_registrado, lcv_modificado, lcv_usr_id, lcv_estado)
                                    VALUES($cas_prestacion_id, '$cas_prestacion_cod_id', $cas_legal_id, '$cas_legal_cod_id', now(), now(), $userid, 'A');";
            //dd($sql);
            $relacion = \DB::select("INSERT INTO public.lg_casos_validaciones (lcv_caso_id, lcv_caso_cod_id, lcv_validacion_id, lcv_validacion_cod_id,
                                                    lcv_registrado, lcv_modificado, lcv_usr_id, lcv_estado)
                                    VALUES($cas_prestacion_id, '$cas_prestacion_cod_id', $cas_legal_id, '$cas_legal_cod_id', now(), now(), $userid, 'A');");
            return response()->json(["data" => $relacion, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function buscarAsegurado(Request $request)
    {
        echo (2222);
        //$cas_cua = $request->input("cas_cua");
        $url = "https://capa.gestora.bo/servicios-ov/api/General/Asegurado/PersonaAseg?&tipoDoc=I&munDoc=1599719&fechaNacimiento=21/07/1956&comple=23";
        try {
            $response2 = Http::withHeaders([
                'Content-Type' => 'application/json' // Aquí agregas el token en el header
            ])->get($url);
            $response3 = $response2->json();
            echo ($response3);
            die();
            return $response2;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}