<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Config;
use App\Derivacion\EnviarSeguimientoTramite;
use App\Derivacion\EnviarSeguimientoTramiteINVPMSgte;
use App\Http\Controllers\ApiVySController;
use App\Derivacion\UtilsTramiteSip;
use App\Derivacion\Tools;

class ArchivoPdfController extends Controller
{
    public function listarArchivosPDF($gestion, $mes)
    {
        try {
            $ubicacion = public_path("archivos_pdf/{$gestion}/{$mes}");

            if (!file_exists($ubicacion) || !is_dir($ubicacion)) {
                return response()->json(['error' => 'La ubicación de archivos no existe (' . $ubicacion . ')'], 404);
            }
            $archivos = scandir($ubicacion);
            $archivosCodificados = [];
            foreach ($archivos as $archivo) {
                if ($archivo === '.' || $archivo === '..') {
                    continue;
                }
                $rutaArchivo = $ubicacion . DIRECTORY_SEPARATOR . $archivo;
                if (is_file($rutaArchivo)) {
                    $contenidoArchivo = file_get_contents($rutaArchivo);
                    $contenidoBase64 = base64_encode($contenidoArchivo);
                    $archivosCodificados[] = [
                        'descripcion' => $archivo,
                        'documento' => $contenidoBase64
                    ];
                }
            }
            return response()->json([
                'cantidad' => count($archivosCodificados),
                'archivosPDF' => $archivosCodificados
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function subirArchivoPDF(Request $request)
    {
        try {
            $rutaDestino = $request->input('rutaDestino');
            if ($request->has('documento') && $request->has('descripcion')) {
                $base64data = $request->input('documento');
                $base64data = base64_decode($base64data);
                $nombreArchivo = $request->input('descripcion');
                if ($base64data !== false) {
                    $archivoTemporal = tmpfile();
                    if ($archivoTemporal !== false) {
                        fwrite($archivoTemporal, $base64data);
                        $rutaArchivoTemporal = stream_get_meta_data($archivoTemporal)['uri'];
                        $directorioDestino = $rutaDestino;
                        if (!file_exists($directorioDestino)) {
                            File::makeDirectory($directorioDestino, 0777, true, true);
                        }
                        if (rename($rutaArchivoTemporal, $directorioDestino . $nombreArchivo)) {
                            return $directorioDestino . $nombreArchivo;
                        } else {
                            echo "Hubo un error al mover el archivo.";
                        }
                        fclose($archivoTemporal);
                    } else {
                        echo "Hubo un error al crear el archivo temporal.";
                    }
                } else {
                    echo "Hubo un error al decodificar la cadena base64.";
                }
                if ($directorioDestino !== false) {
                    return response()->json(['mensaje' => 'Archivo subido correctamente (' . $nombreArchivo . ')'], 200);
                } else {
                    return response()->json(['error' => 'Error al guardar el archivo en la ruta especificada'], 500);
                }
            } else {
                return response()->json(['error' => 'No se proporcionó el archivo en base64 o el nombre del archivo'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verificarEstadoTramite(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "data" => $request->input('nroTramite'));

        try {
            $nroTramite = $request->input('nroTramite');

            $data = \DB::select("SELECT *
                                FROM (
                                    SELECT htc_cas_usr_id, htc_cas_estado, htc_cas_cod_id, htc_cas_act_id, htc_cas_nodo_id,
                                        cas_usr_id, cas_estado, cas_cod_id, cas_act_id, cas_nodo_id,
                                        act_data->>'act_orden' AS orden,
                                        (SELECT count(*) FROM _gp_documentos gd WHERE h.htc_id = gd.doc_his_id) AS doc
                                    FROM rmx_vys_historico_casos h
                                    INNER JOIN rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                                    INNER JOIN rmx_vys_actividades a ON c.cas_act_id = a.act_id
                                    WHERE htc_cas_nodo_id = 82
                                        AND htc_cas_estado = 'E'
                                        AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                                        AND htc_cas_cod_id = '$nroTramite'
                                ) AS tmp
                                WHERE doc <> 0;");

            if (!empty($data)) {
                $success['data'] = "Si existe";
                $success['nroTramite'] = $data[0]->cas_cod_id;
                return response()->json([$success]);
            } else {
                $error['data'] = "No existe";
                $error['nroTramite'] = $nroTramite;
                return response()->json([$error]);
            }
        } catch (\Exception $e) {
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function adjuntarContrato(Request $request)
    {
        $casCodId = $request->input('nroTramite');
        $data = DB::table('rmx_vys_casos as rvc')
            ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
            ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
            ->where('rvc.cas_estado', '=', 'T')
            ->where('rvc.cas_cod_id', '=', $casCodId)
            ->select(
                'rvc.cas_id as casId',
                'rvc.cas_act_id as casActId',
                'rvc.cas_nodo_id as casNodoId',
                'rva.act_prc_id as actPrcId',
                DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
            )
            ->get();

        $casId = $data->first()->casId;
        $casData = $data->first()->casData;
        $casDataValores = $data->first()->casDataValores;
        $actPrcId = $data->first()->actPrcId;
        $gestion = $data->first()->casGestion;
        $usuarioRegistro = $data->first()->usuarioRegistro;
        $casNodoId = $data->first()->casNodoId;


        $documentos = $request->input('documentos');
        $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';

        $fechaHoraActual = new \DateTime();
        $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
        $row = 200;
        foreach ($documentos as $indice => $valor) {
            $uuid = Str::uuid();
            $fecha = (new \DateTime())->format('dmY_His');
            $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
            $datosSubirArchivo = [
                'rutaDestino' => $docUrl,
                'documento' => $valor['documento'],
                'descripcion' => $nombreArch
            ];
            $requestSubirArchivo = new Request($datosSubirArchivo);
            $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);
            $frmCampo = $valor['descripcion'];
            $frmValue = $docUrl . $nombreArch;


            // Paso 7: Verificar y actualizar estado de motivo de rechazo
            try {
                // Consultar si ya existe el campo "ESTADO_GENERACION_EAP" en cas_data_valores
                $countValor = \DB::select("
                SELECT *
                FROM (
                    SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                    FROM public.rmx_vys_casos
                    WHERE cas_id = ?
                ) AS tmp
                WHERE tmp.valor->>'frm_campo' = 'DECLARACION_DE_PENSION'
            ", [$casId]);

                // Si existe, actualizamos el valor

                if (count($countValor) > 0) {

                    $dataInv1 = \DB::update("
                    UPDATE public._gp_documentos
                    SET doc_his_id = 0,

                        doc_codigo = '0',
                        doc_referencia = '0',
                        doc_categoria = '0',
                        doc_doc_id = 0,
                        doc_descripcion = '0'
                    WHERE doc_cas_id = ?
                    AND doc_descripcion = 'CONTRATO DECLARACION DE PENSION';
                ", [$casId]);

                    // Verificar si alguna fila fue afectada
                    /* if ($dataInv1 === 0) {
                    throw new \Exception("No se encontró el caso con el número de trámite proporcionado: " . $casId);
                }*/
                    $dataInv = \DB::select("WITH updated_json AS (
                    SELECT cas_id,
                        jsonb_agg(
                            CASE
                                WHEN elem->>'frm_campo' = 'DECLARACION_DE_PENSION' THEN jsonb_set(elem, '{frm_value}', '\"$frmValue\"'::jsonb)
                                ELSE elem
                            END
                        ) AS updated_json
                    FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                    WHERE cas_id = $casId
                    GROUP BY cas_id)
                    UPDATE public.rmx_vys_casos
                    SET  cas_data_valores = updated_json.updated_json
                    FROM  updated_json
                    WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
                } else {
                    // Si no existe, añadimos un nuevo valor
                    DB::table('rmx_vys_casos')
                        ->where('cas_id', $casId)

                        ->update([
                            'cas_data_valores' => DB::raw("
                            cas_data_valores ||
                            '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"DECLARACION_DE_PENSION\", \"frm_value\": \"$frmValue\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                        ")
                        ]);
                }
            } catch (\Exception $e) {
                throw new \Exception("Error al verificar/actualizar el documento: " . $e->getMessage());
            }


            $dataUsuario = DB::table('users')
                ->where('email', '=', $usuarioRegistro)
                ->select(
                    'id'
                )
                ->get();
            $dataHistorico = DB::select("SELECT * FROM rmx_vys_historico_casos WHERE htc_cas_id = ? AND htc_cas_nodo_id = ? ORDER BY htc_id DESC LIMIT 1", [$casId, $casNodoId]);

            $casUsrId = $dataUsuario->first()->id;
            DB::insert('insert into public._gp_documentos (doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_usuario, doc_descripcion) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $casId,
                $casUsrId,
                $dataHistorico[0]->htc_id,
                $casCodId,
                'documento_PRES',
                'documento_PRES',
                $docUrl . $nombreArch,
                $row,
                $casUsrId,
                "CONTRATO DECLARACION DE PENSION" //$valor['descripcion']
            ]);
            $row++;
        }

        $fechaActual = new \DateTime();
        $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
        //$success['fecha'] = $fechaFormateada;
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => $fechaFormateada, "data" => $docUrl . $nombreArch);
        return response()->json([$success]);
    }

    public function derivacionTramiteINVPMSgte(Request $request)
    {
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('nroTramite')];
        $error = ["codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('nroTramite')];

        try {
            // Validar entrada


            $casCodId = $request["cas_id"];
            $emailUser = $request["emailUser"];

            // Obtener datos del caso
            $data = DB::table('rmx_vys_casos as rvc')
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                ->where('rvc.cas_estado', '=', 'T')
                ->where('rvc.cas_id', '=', $casCodId)
                ->select(
                    'rvc.cas_id as casId',
                    'rvc.cas_act_id as casActId',
                    'rvc.cas_nodo_id as casNodoId',
                    'rva.act_prc_id as actPrcId',
                    DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                    DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                    DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                    DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                )
                ->get();

            if ($data->isEmpty()) {
                throw new \Exception("No se encontró el caso con el número de trámite proporcionado2: " . $casCodId);
            }

            // Extraer campos necesarios
            $casId = $data->first()->casId;
            $casData = $data->first()->casData;
            $casDataValores = $data->first()->casDataValores;
            $actPrcId = $data->first()->actPrcId;

            // Obtener la siguiente actividad
            $dataOrden = DB::table('rmx_vys_actividades')
                ->where('act_id', '=', $data->first()->casActId)
                ->where('act_nodo_id', '=', $data->first()->casNodoId)
                ->where('act_prc_id', '=', $actPrcId)
                ->select(DB::raw("act_data->>'act_siguiente' as act_siguiente"), 'act_data_reglas')
                ->first();

            if (!$dataOrden) {
                throw new \Exception("No se encontró la información de la siguiente actividad.");
            }

            // Decodificar valores JSON
            $act_data_reglas = json_decode($dataOrden->act_data_reglas, true);
            $cas_data_valores = json_decode($casDataValores, true);

            //print_r($act_data_reglas);
            //print_r($cas_data_valores);

            // Inicializar $paralelos como un array vacío
            $paralelos = [];
            if (is_array($act_data_reglas) && is_array($cas_data_valores)) {
                foreach ($act_data_reglas as $regla) {
                    $reg = $regla['act_regla'];
                    // Reemplazar los placeholders de #frm_campo# con frm_value
                    foreach ($cas_data_valores as $campo) {

                        /* if (isset($campo['frm_campo']) && isset($campo['frm_value'])) {
                            $valor = is_array($campo['frm_value']) ? implode(', ', $campo['frm_value']) : strval($campo['frm_value']);
                            $reg = str_replace('#' . $campo['frm_campo'] . '#', $valor, $reg);
                        }*/

                        if (isset($campo['frm_campo'])) {
                            // Verificar si frm_value está presente
                            if (array_key_exists('frm_value', $campo)) {
                                // Si frm_value es un array, revisa si tiene arrays anidados
                                if (is_array($campo['frm_value'])) {
                                    // Verifica si alguno de los elementos del array es a su vez un array
                                    $anidado = array_filter($campo['frm_value'], 'is_array');

                                    if (!empty($anidado)) {
                                        // Si hay arrays anidados, los convertimos en JSON o puedes usar otro formato
                                        $valor = json_encode($campo['frm_value']);
                                    } else {
                                        // Si no hay arrays anidados, utilizamos implode
                                        $valor = implode(', ', $campo['frm_value']);
                                    }
                                } else {
                                    // Si no es un array, simplemente lo convertimos en cadena
                                    $valor = strval($campo['frm_value']);
                                }
                                // Reemplazamos el valor en la regla
                                $reg = str_replace('#' . $campo['frm_campo'] . '#', $valor, $reg);
                            } else {
                                // Imprimir el campo que no tiene frm_value
                                // echo "Falta frm_value para el campo: " . $campo['frm_campo'] . "\n";
                                // Puedes reemplazar el valor por una cadena vacía o manejarlo de otra manera
                                $reg = str_replace('#' . $campo['frm_campo'] . '#', '', $reg); // Asigna un valor vacío si frm_value no está definido
                            }
                        }
                    }

                    $reg = str_replace('`', "'", $reg);


                    // Evaluar la expresión
                    try {
                        $evaluacion = eval ("return ($reg);");
                        /*if ($evaluacion === false) {
                            throw new \Exception("La evaluación devolvió un valor falso o inválido.");
                        }*/
                    } catch (\ParseError $e) {
                        throw new \Exception("Error en la evaluación de la regla: " . $e->getMessage());
                    }

                    // Si no quedan placeholders y la evaluación es verdadera
                    if (strpos($reg, '#') === false && $evaluacion) {
                        $registro['act_data']['act_siguiente'] = $regla['act_siguiente'];

                        $paralelos[] = ['siguiente' => $regla['act_siguiente']];
                    }
                }
            }


            // Obtener detalles de la siguiente actividad
            $orden = isset($registro['act_data']['act_siguiente']) ? $registro['act_data']['act_siguiente'] : null;

            if (!$orden) {
                throw new \Exception("No se pudo determinar la actividad siguiente.");
            }
            $dataActividad = DB::table('rmx_vys_actividades')
                ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                ->where('act_estado', '=', 'A')
                ->where('act_prc_id', '=', $actPrcId)
                ->whereRaw("act_data->>'act_orden' = ?", [$orden])
                ->select('act_nodo_id as actNodoId', 'act_id as actId')
                ->get();

            if ($dataActividad->isEmpty()) {
                throw new \Exception("No se encontró la actividad siguiente para el caso con id: " . $casId);
            }

            $casActId = $dataActividad->first()->actId;
            $casNodoId = $dataActividad->first()->actNodoId;

            // Obtener datos del usuario
            $dataUsuario = DB::table('users')
                ->where('email_verified_at', '=', $emailUser)
                ->select(
                    'id',
                    'role_id as roleId',
                    'branch_id as branchId',
                    'name',
                    'email',
                    'email_verified_at as emailVerifiedAt',
                    'status',
                    'nom_usuario as nomUsuario',
                    'id_regional as idRegional',
                    'id_departamento as idDepartamento',
                    'id_agencia as idAgencia',
                    'es_atc as esAtc',
                    'es_supervisor as esSupervisor'
                )
                ->get();

            if ($dataUsuario->isEmpty()) {
                throw new \Exception("No se encontró un usuario disponible para la actividad siguiente: " . $emailUser);
            }

            $UsrId = $dataUsuario->first()->id;
            $nameUser = $dataUsuario->first()->name;

            // Derivar el caso
            $casEstado = 'E';

            $dataDerivar = DB::select(
                "SELECT * FROM public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?)",
                [$casId, $casActId, $casNodoId, $casData, $casDataValores, $UsrId, $casEstado]
            );

            if (empty($dataDerivar) || !isset($dataDerivar[0]->sp_derivar_caso)) {
                throw new \Exception("No se obtuvo una respuesta válida al derivar el caso.");
            }

            $docHisId = $dataDerivar[0]->sp_derivar_caso;

            $fechaActual = new \DateTime();
            $success['fecha'] = $fechaActual->format('Y-m-d H:i:s');
            $success['casData'] = $casData;
            $success['nameUser'] = $nameUser;
            $success['userID'] = $UsrId;
            $success['casId'] = $casId;
            $success['act_siguiente'] = $orden;
            $success['dataDerivar'] = $docHisId;

            return response()->json($success);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $error['fecha'] = $fechaActual->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }
    public function actualizarDatosContrato(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('nroTramite'));
        try {
            $opcion = $request->input('opcion'); //****
            $casCodId = $request->input('nroTramite'); //****

            if ($opcion == 'M') {

                /* $datosSubirArchivo = [
                    'nroTramite' => $casCodId
                ];

                $requestSubirArchivo = new Request($datosSubirArchivo);
                $this->derivacionCalculoINVPM($requestSubirArchivo);*/


                // Paso 2: Obtener datos del caso
                try {
                    $data = DB::table('rmx_vys_casos as rvc')
                        ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                        ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                        ->where('rvc.cas_estado', '=', 'E')
                        ->where('rvc.cas_cod_id', '=', $casCodId)
                        ->select(
                            'rvc.cas_id as casId',
                            'rvc.cas_act_id as casActId',
                            'rvc.cas_nodo_id as casNodoId',
                            'rva.act_prc_id as actPrcId',
                            DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                            DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                            DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                            DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                        )
                        ->get();

                    if ($data->isEmpty()) {
                        throw new \Exception("No se encontró el caso con el número de trámite proporcionado5.");
                    }
                } catch (\Exception $e) {
                    throw new \Exception("Error al obtener datos del caso: " . $e->getMessage());
                }
                // Paso 3: Validar los datos obtenidos
                try {
                    $casId = $data->first()->casId;
                    $casData = $data->first()->casData;
                    $casDataValores = $data->first()->casDataValores;
                    $actPrcId = $data->first()->actPrcId;
                    $gestion = $data->first()->casGestion;
                    $usuarioRegistro = $data->first()->usuarioRegistro;

                    if (!$casData || !$casDataValores) {
                        throw new \Exception("Datos del caso incompletos.");
                    }
                } catch (\Exception $e) {
                    throw new \Exception("Error al validar los datos obtenidos: " . $e->getMessage());
                }
                // Paso 4: Determinar el tipo de trámite
                try {
                    $tipoTramite = explode('/', $casCodId)[0];
                    if ($tipoTramite == 'PM') {
                        $orden = "109";
                    } elseif ($tipoTramite == 'INV') {
                        $orden = "109";
                    } else {
                        throw new \Exception("Tipo de trámite desconocido.");
                    }
                } catch (\Exception $e) {
                    throw new \Exception("Error al determinar el tipo de trámite: " . $e->getMessage());
                }
                // Paso 5: Obtener la siguiente actividad
                try {
                    $dataActividad = DB::table('rmx_vys_actividades')
                        ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                        ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                        ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                        ->where('act_estado', '=', 'A')
                        ->where('act_prc_id', '=', $actPrcId)
                        ->whereRaw("act_data->>'act_orden' = '$orden'")
                        ->select('act_nodo_id as actNodoId', 'act_id as actId')
                        ->get();

                    if ($dataActividad->isEmpty()) {
                        throw new \Exception("No se encontró la siguiente actividad.");
                    }

                    $casActId = $dataActividad->first()->actId;
                    $casNodoId = $dataActividad->first()->actNodoId;
                } catch (\Exception $e) {
                    throw new \Exception("Error al obtener la siguiente actividad: " . $e->getMessage());
                }

                // Paso 6: Obtener usuario de la actividad siguiente
                try {
                    $data = \DB::select("select usn_user_id from rmx_usr_nodos run where usn_nodo_id = ? order by random() limit 1", [$casNodoId]);
                    if (empty($data)) {
                        throw new \Exception("No se encontró un usuario disponible para la actividad siguiente.");
                    }
                    $UsrId = $data[0]->usn_user_id;
                } catch (\Exception $e) {
                    throw new \Exception("Error al obtener el usuario de la actividad siguiente: " . $e->getMessage());
                }


                // Paso 7: Verificar y actualizar estado de motivo de rechazo
                try {
                    // Consultar si ya existe el campo "ESTADO_GENERACION_EAP" en cas_data_valores
                    $estadoMotivoRechazo = \DB::select("
                    SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_cod_id = ? AND cas_estado = 'E'
                    ) AS tmp
                    WHERE tmp.valor->>'frm_campo' = 'ESTADO_GENERACION_EAP'
                ", [$casCodId]);
                    $estadoRespuestaCalculo = "M";
                    // Si existe, actualizamos el valor

                    if (count($estadoMotivoRechazo) > 0) {


                        $dataInv = \DB::select("WITH updated_json AS (
                        SELECT cas_id,
                            jsonb_agg(
                                CASE
                                    WHEN elem->>'frm_campo' = 'ESTADO_GENERACION_EAP' THEN jsonb_set(elem, '{frm_value}', '\"M\"'::jsonb)
                                    ELSE elem
                                END
                            ) AS updated_json
                        FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                        WHERE cas_id = $casId
                        GROUP BY cas_id)
                        UPDATE public.rmx_vys_casos
                        SET  cas_data_valores = updated_json.updated_json
                        FROM  updated_json
                        WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
                    } else {
                        // Si no existe, añadimos un nuevo valor
                        DB::table('rmx_vys_casos')
                            ->where('cas_cod_id', $casCodId)
                            ->where('cas_estado', 'E')
                            ->update([
                                'cas_data_valores' => DB::raw("
                                cas_data_valores ||
                                '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"ESTADO_GENERACION_EAP\", \"frm_value\": \"$estadoRespuestaCalculo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                            ")
                            ]);
                    }
                } catch (\Exception $e) {
                    throw new \Exception("Error al verificar/actualizar el estado del motivo de rechazo: " . $e->getMessage());
                }

                // Paso 2: Obtener datos del caso
                try {
                    $data = DB::table('rmx_vys_casos as rvc')
                        ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                        ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                        ->where('rvc.cas_estado', '=', 'E')
                        ->where('rvc.cas_cod_id', '=', $casCodId)
                        ->select(
                            'rvc.cas_id as casId',
                            'rvc.cas_act_id as casActId',
                            'rvc.cas_nodo_id as casNodoId',
                            'rva.act_prc_id as actPrcId',
                            DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                            DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                            DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                            DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                        )
                        ->get();

                    if ($data->isEmpty()) {
                        throw new \Exception("No se encontró el caso con el número de trámite proporcionado4.");
                    }
                } catch (\Exception $e) {
                    throw new \Exception("Error al obtener datos del caso: " . $e->getMessage());
                }

                // Paso 8: Derivar el caso
                try {
                    $casData = $data->first()->casData;
                    $casDataValores = $data->first()->casDataValores;
                    $casEstado = 'T';
                    $dataDerivar = DB::select(
                        "SELECT * FROM public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?)",
                        [$casId, $casActId, $casNodoId, $casData, $casDataValores, $UsrId, $casEstado]
                    );

                    if (empty($dataDerivar) || !isset($dataDerivar[0]->sp_derivar_caso)) {
                        throw new \Exception("No se obtuvo una respuesta válida al derivar el caso.");
                    }

                    $docHisId = $dataDerivar[0]->sp_derivar_caso;
                } catch (\Exception $e) {
                    throw new \Exception("Error al derivar el caso: " . $e->getMessage());
                }

                // Paso 9: Retornar respuesta exitosa
                $fechaActual = new \DateTime();
                $success['fecha'] = $fechaActual->format('Y-m-d H:i:s');
                $success['userID'] = $UsrId;
                $success['casId'] = $casId;
                $success['act_siguiente'] = $orden;
                $success['dataDerivar'] = $docHisId;

                return response()->json($success);
            } else {

                $valorRecorrido = "";
                $documentos = $request->input('documentos');
                if (empty($request->input('documentos'))) {
                    throw new \Exception("No se obtuvo archivos adjuntos.");
                }
                $usuarioDerivacion = $request->input('usuario'); //****
                $usuarioRevision = $request->input('usuarioRevision'); //****
                $origenEapPres = $request->input('origen'); //****
                $tipoDocumento = $request->input('generacionEAP'); //****
                $estadoDerivacion = $request->input('estadoDerivacion') ?? 'REVISADO';
                $estadoDerivacionJson = json_encode($estadoDerivacion);
                DB::table('rmx_vys_casos')
                    ->where('cas_cod_id', $casCodId)
                    ->where('cas_estado', 'E')
                    ->update([
                        'cas_data' => DB::raw("cas_data || jsonb_build_object('ESTADO_DERIVACION', '$estadoDerivacionJson'::jsonb)")
                    ]);

                $data = DB::table('rmx_vys_casos as rvc')
                    ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                    ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                    ->where('rvc.cas_estado', '=', 'E')
                    ->where('rvc.cas_cod_id', '=', $casCodId)
                    ->select(
                        'rvc.cas_id as casId',
                        'rvc.cas_act_id as casActId',
                        'rvc.cas_nodo_id as casNodoId',
                        'rva.act_prc_id as actPrcId',
                        DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                        DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                        DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                        DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                    )
                    ->get();
                if ($data->isEmpty()) {
                    throw new \Exception("No se encontró el caso con el número de trámite proporcionado3.");
                }
                $casId = $data->first()->casId;
                // $casData = $data->first()->casData;
                // $casDataValores = $data->first()->casDataValores;
                $actPrcId = $data->first()->actPrcId;
                $gestion = $data->first()->casGestion;
                $usuarioRegistro = $data->first()->usuarioRegistro;
                $dataOrden = DB::table('rmx_vys_actividades')
                    ->where('act_id', '=', $data->first()->casActId)
                    ->where('act_nodo_id', '=', $data->first()->casNodoId)
                    ->where('act_prc_id', '=', $actPrcId)
                    ->select(DB::raw("act_data->>'act_siguiente' as act_siguiente"))
                    ->first();

                $orden = $dataOrden->act_siguiente;
                $dataActividad = DB::table('rmx_vys_actividades')
                    ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                    ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                    ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                    ->where('act_estado', '=', 'A')
                    ->where('act_prc_id', '=', $actPrcId)
                    ->whereRaw("act_data->>'act_orden' = '$orden'")
                    ->select('act_nodo_id as actNodoId', 'act_id as actId')
                    ->get();

                $casActId = $dataActividad->first()->actId;
                $casNodoId = $dataActividad->first()->actNodoId;



                // Obtener usuario de la actividad siguiente
                $data = \DB::select("select usn_user_id from rmx_usr_nodos run where usn_nodo_id = ? order by random() limit 1", [$casNodoId]);
                if (empty($data)) {
                    throw new \Exception("No se encontró un usuario disponible para la actividad siguiente.");
                }
                $casUsrId = $data[0]->usn_user_id;




                $tipoTramite = explode('/', $casCodId)[0];
                if ($tipoTramite == 'INV') {
                    $estadoRespuestaCalculo = $request->input('estadoRespuestaCalculo'); //****
                    $referenciaCalculo = $request->input('referenciaCalculo'); //****
                    $monto = $request->input('monto'); //****
                    $montoReferente = $request->input('montoReferente'); //****

                    // preguntamos SI EL ESTADO_RESPUESTA_CALCULO ESTA EN EL JSON DE CAS_DATA_VALORES
                    // y como vienen todos los datos por eso estoy preguntando solo por uno de los campos
                    $estadoRespuestaCal = \DB::select(" SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$casId'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' IN ('ESTADO_RESPUESTA_CALCULO', 'Referencia_calculo', 'MONTO', 'MONTO_REFERENTE');");
                    if (empty($estadoRespuestaCal)) {
                        $valorRecorrido = "1 ingreso INV";
                        // throw new \Exception("Sin valores.");

                        // Evaluar la expresión
                        try {
                            DB::table('rmx_vys_casos')
                                ->where('cas_id', $casId)
                                ->update([
                                    'cas_data_valores' => DB::raw("
                 cas_data_valores ||
                 '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"ESTADO_RESPUESTA_CALCULO\", \"frm_value\": \"$estadoRespuestaCalculo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                 '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"Referencia_calculo\", \"frm_value\": \"$referenciaCalculo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                 '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"MONTO\", \"frm_value\": \"$monto\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                 '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"MONTO_REFERENTE\", \"frm_value\": \"$montoReferente\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
             ")
                                ]);
                        } catch (\ParseError $e) {
                            throw new \Exception("NO SE REGISTRO: " . $e->getMessage());
                        }
                    } else {
                        $valorRecorrido = "2 ingreso INV";
                        try {
                            $dataInv = \DB::select("WITH updated_json AS (
                            SELECT  cas_id,
                                jsonb_agg(
                                    CASE WHEN elem->>'frm_campo' = 'ESTADO_RESPUESTA_CALCULO' THEN jsonb_set(elem, '{frm_value}', '\"$estadoRespuestaCalculo\"')
                                    WHEN elem->>'frm_campo' = 'Referencia_calculo' THEN jsonb_set(elem, '{frm_value}', '\"$referenciaCalculo\"')
                                    WHEN elem->>'frm_campo' = 'MONTO' THEN jsonb_set(elem, '{frm_value}', '\"$monto\"')
                                    WHEN elem->>'frm_campo' = 'MONTO_REFERENTE' THEN jsonb_set(elem, '{frm_value}', '\"$montoReferente\"')
                                    ELSE elem
                                END ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_id = '$casId'
                                GROUP BY  cas_id)
                            UPDATE public.rmx_vys_casos
                            SET  cas_data_valores = updated_json.updated_json
                            FROM  updated_json
                            WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
                            if (empty($dataInv)) {
                                throw new \Exception("NO SE ACTUALIZO NADA DE INV.");
                            }
                        } catch (\ParseError $e) {
                            throw new \Exception("NO SE REGISTRO: " . $e->getMessage());
                        }
                    }
                    $estadoRespuestaCal2 = \DB::select(" SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$casId'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' IN ('ESTADO_RESPUESTA_CALCULO', 'Referencia_calculo', 'MONTO', 'MONTO_REFERENTE');");
                    if (empty($estadoRespuestaCal2)) {
                        throw new \Exception("Sin valores. INV");
                    }
                } else if ($tipoTramite == 'PM') {
                    $estadoRespuestaCalculo = $request->input('estadoRespuestaCalculo'); //****
                    $referenciaCalculo = $request->input('referenciaCalculo'); //****
                    $monto = $request->input('monto'); //****
                    $montoReferente = $request->input('montoReferente'); //****
                    $cumple_riesgo = $request->input('cumple_riesgo'); //****
                    $cumple_jubilacion = $request->input('cumple_jubilacion'); //****
                    $mayor_riesgo = $request->input('mayor_riesgo'); //****
                    $mayor_jubilacion = $request->input('mayor_jubilacion'); //****



                    $estadoRespuestaCal = \DB::select(" SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$casId'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' IN ('ESTADO_RESPUESTA_CALCULO', 'Referencia_calculo', 'MONTO', 'MONTO_REFERENTE', 'cumple_riesgo', 'cumple_jubilacion', 'mayor_riesgo', 'mayor_jubilacion');");
                    if (empty($estadoRespuestaCal)) {
                        $valorRecorrido = "1 ingreso PM";
                        try {
                            DB::table('rmx_vys_casos')
                                ->where('cas_id', $casId)
                                ->update([
                                    'cas_data_valores' => DB::raw("
                    cas_data_valores ||
                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"ESTADO_RESPUESTA_CALCULO\", \"frm_value\": \"$estadoRespuestaCalculo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"Referencia_calculo\", \"frm_value\": \"$referenciaCalculo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                    '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"MONTO\", \"frm_value\": \"$monto\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                     '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"MONTO_REFERENTE\", \"frm_value\": \"$montoReferente\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb||
                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"cumple_riesgo\", \"frm_value\": \"$cumple_riesgo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb||
                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"cumple_jubilacion\", \"frm_value\": \"$cumple_jubilacion\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb||
                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"mayor_riesgo\", \"frm_value\": \"$mayor_riesgo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb||
                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"mayor_jubilacion\", \"frm_value\": \"$mayor_jubilacion\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                ")
                                ]);
                        } catch (\ParseError $e) {
                            throw new \Exception("NO REGISTRO LA PRIMERA PM: " . $e->getMessage());
                        }
                    } else {
                        $valorRecorrido = "2 ingreso PM";
                        try {
                            $dataPm = \DB::select("WITH updated_json AS (
                            SELECT  cas_id,
                                jsonb_agg(
                                    CASE WHEN elem->>'frm_campo' = 'ESTADO_RESPUESTA_CALCULO' THEN jsonb_set(elem, '{frm_value}', '\"$estadoRespuestaCalculo\"')
                                    WHEN elem->>'frm_campo' = 'Referencia_calculo' THEN jsonb_set(elem, '{frm_value}', '\"$referenciaCalculo\"')
                                    WHEN elem->>'frm_campo' = 'MONTO' THEN jsonb_set(elem, '{frm_value}', '\"$monto\"')
                                    WHEN elem->>'frm_campo' = 'MONTO_REFERENTE' THEN jsonb_set(elem, '{frm_value}', '\"$montoReferente\"')
                                    WHEN elem->>'frm_campo' = 'cumple_riesgo' THEN jsonb_set(elem, '{frm_value}', '\"$cumple_riesgo\"')
                                    WHEN elem->>'frm_campo' = 'cumple_jubilacion' THEN jsonb_set(elem, '{frm_value}', '\"$cumple_jubilacion\"')
                                    WHEN elem->>'frm_campo' = 'mayor_riesgo' THEN jsonb_set(elem, '{frm_value}', '\"$mayor_riesgo\"')
                                    WHEN elem->>'frm_campo' = 'mayor_jubilacion' THEN jsonb_set(elem, '{frm_value}', '\"$mayor_jubilacion\"')
                                    ELSE elem
                                END ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_id = '$casId'
                                GROUP BY  cas_id)
                            UPDATE public.rmx_vys_casos
                            SET  cas_data_valores = updated_json.updated_json
                            FROM  updated_json
                            WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");

                            if (empty($dataPm)) {
                                throw new \Exception("NO SE ACTUALIZO NADA DE PM.");
                            }
                        } catch (\ParseError $e) {
                            throw new \Exception("NO ACTUALIZO PM 2: " . $e->getMessage());
                        }
                    }

                    $estadoRespuestaCal2 = \DB::select(" SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$casId'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' IN ('ESTADO_RESPUESTA_CALCULO', 'Referencia_calculo', 'MONTO', 'MONTO_REFERENTE', 'cumple_riesgo', 'cumple_jubilacion', 'mayor_riesgo', 'mayor_jubilacion');");
                    if (empty($estadoRespuestaCal2)) {
                        throw new \Exception("Sin valores. PM");
                    }
                }

                //******************aqui derivamos******************** */
                $data = DB::table('rmx_vys_casos as rvc')
                    ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                    ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                    ->where('rvc.cas_estado', '=', 'E')
                    ->where('rvc.cas_cod_id', '=', $casCodId)
                    ->select(
                        'rvc.cas_id as casId',
                        'rvc.cas_act_id as casActId',
                        'rvc.cas_nodo_id as casNodoId',
                        'rva.act_prc_id as actPrcId',
                        DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                        DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                        DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                        DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                    )
                    ->get();
                if ($data->isEmpty()) {
                    throw new \Exception("No se encontró el caso con el número de trámite proporcionado3.");
                }
                $casData = $data->first()->casData;
                $casDataValores = $data->first()->casDataValores;
                // Derivar el caso
                $casEstado = 'T';
                try {
                    $dataDerivar = \DB::select("select * from public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $casId,
                        $casActId,
                        $casNodoId,
                        $casData,
                        $casDataValores,
                        $casUsrId,
                        $casEstado,
                        $usuarioDerivacion,
                        $usuarioRevision,
                        $origenEapPres
                    ]);

                    if (empty($dataDerivar) || !isset($dataDerivar[0]->sp_derivar_caso)) {
                        throw new \Exception("No se obtuvo una respuesta válida al derivar el caso.");
                    }

                    $docHisId = $dataDerivar[0]->sp_derivar_caso;
                } catch (\Exception $e) {
                    throw new \Exception("Error en la derivación del caso: " . $e->getMessage());
                }
                //******************fin de derivacion****************** */




                $row = 200;
                $documentos = $request->input('documentos');
                $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';

                $fechaHoraActual = new \DateTime();
                $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
                foreach ($documentos as $indice => $valor) {
                    $uuid = Str::uuid();
                    $fecha = (new \DateTime())->format('dmY_His');
                    $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
                    $datosSubirArchivo = [
                        'rutaDestino' => $docUrl,
                        'documento' => $valor['documento'],
                        'descripcion' => $nombreArch
                    ];
                    $requestSubirArchivo = new Request($datosSubirArchivo);
                    $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);
                    if ($tipoTramite == 'INV' || $tipoTramite == 'PM') {
                        $frmCampo = $valor['descripcion'];
                        $frmValue = $docUrl . $nombreArch;

                        $archivo = \DB::select(" SELECT *
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                            FROM public.rmx_vys_casos
                            WHERE cas_id = '$casId'
                        ) tmp
                        WHERE tmp.valor->>'frm_campo' IN ('$frmCampo');");
                        if (empty($archivo)) {



                            DB::table('rmx_vys_casos')
                                ->where('cas_id', $casId)
                                ->update([
                                    'cas_data_valores' => DB::raw("
                            cas_data_valores ||
                            '{\"frm_campo\": \"$frmCampo\", \"frm_value\": \"$frmValue\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                        ")
                                ]);
                        } else {
                            $descripdoc = $valor['descripcion'];
                            $dataInv1 = \DB::update("
                            UPDATE public._gp_documentos
                            SET doc_his_id = 0,
                                doc_codigo = '0',
                                doc_referencia = '0',
                                doc_categoria = '0',
                                doc_doc_id = 0,
                                doc_descripcion = '0'
                            WHERE doc_cas_id = ?
                            AND doc_descripcion = '$descripdoc';
                        ", [$casId]);
                            $dataPm = \DB::select("WITH updated_json AS (
                                SELECT  cas_id,
                                    jsonb_agg(
                                        CASE WHEN elem->>'frm_campo' = '$frmCampo' THEN jsonb_set(elem, '{frm_value}', '\"$frmValue\"')

                                        ELSE elem
                                    END ) AS updated_json
                                    FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                    WHERE cas_id = '$casId'
                                    GROUP BY  cas_id)
                                UPDATE public.rmx_vys_casos
                                SET  cas_data_valores = updated_json.updated_json
                                FROM  updated_json
                                WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
                        }
                    }
                    DB::insert('insert into public._gp_documentos (doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_usuario, doc_descripcion) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $casId,
                        $casUsrId,
                        $docHisId,
                        $casCodId,
                        'documento_' . $request->input('origen'),
                        'documento_' . $request->input('origen'),
                        $docUrl . $nombreArch,
                        $row,
                        $casUsrId,
                        $valor['descripcion']
                    ]);
                    $row++;
                }

                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $success['casDataValores'] = $casDataValores;
                $success['usuarioRevision'] = $usuarioRevision;
                $success['estado'] = $casEstado;
                $success['usuarioDerivacion'] = $usuarioDerivacion;
                $success['observacionesValoresSI'] = $estadoRespuestaCal2;
                $success['descripcionDerivacionJson'] = "ROSv0 <=>" . $valorRecorrido;
                return response()->json([$success]);
            }
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function actualizarDatosTramite(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('nroTramite'));
        DB::beginTransaction(); // Iniciar la transacción
        try {
            $casCodId = $request->input('nroTramite'); //****
            $observaciones = $request->input('observaciones') ?? [];
            $estadoDerivacion = $request->input('estadoDerivacion') ?? 'REVISADO';
            $descripcionDerivacion = $request->input('descripcionDerivacion') ?? '';
            $observacion = [];
            $request_body = json_encode( $request->all());
            $url = urlGestora() . "/api/actualizarTramite";
            $headers = '{"Content-Type": "application/json"}';
            $ip = request()->ip();
            $usuario = $request->input('origen');
            foreach ($observaciones as $index => $valor) {
                $observacion[] = [
                    'unidad' => $valor['unidad'],
                    'descripcion' => $valor['descripcion']
                ];
            }
            $observacionJson = json_encode($observacion);
            $estadoDerivacionJson = json_encode($estadoDerivacion);
            $descripcionDerivacionJson = json_encode($descripcionDerivacion);
            $estadoDerivacionText = $estadoDerivacion;
            DB::table('rmx_vys_casos')
                ->where('cas_cod_id', $casCodId)
                ->where('cas_estado', 'E')
                ->update([
                    'cas_data' => DB::raw("
                    cas_data || jsonb_build_object(
                        'ESTADO_DERIVACION', '$estadoDerivacionJson'::jsonb
                    )
                ")
                ]);
            if ($estadoDerivacion == 'OBSERVADO' || $estadoDerivacion == 'REGULARIZADO') {
                if ($estadoDerivacion == 'OBSERVADO') {
                    DB::table('rmx_vys_casos')
                        ->where('cas_cod_id', $casCodId)
                        ->where('cas_estado', 'E')
                        ->update([
                            'cas_data' => DB::raw("
                            cas_data || jsonb_build_object(
                                'OBSERVACIONES', '$observacionJson'::jsonb
                            )
                        ")
                        ]);
                }
                DB::table('rmx_vys_casos')
                    ->where('cas_cod_id', $casCodId)
                    ->where('cas_estado', 'E')
                    ->update([
                        'cas_data' => DB::raw("
                            cas_data || jsonb_build_object(
                                'OBSERVACIONES', '$observacionJson'::jsonb,
                                'DESCRIPCION_DERIVACION', '$descripcionDerivacionJson'::jsonb,
                                'ESTADO_DERIVACION', '$estadoDerivacionJson'::jsonb
                            )
                        ")
                    ]);
                $data = \DB::select("WITH updated_json AS (
                    SELECT  cas_id,
                        jsonb_agg(
                            CASE WHEN elem->>'frm_campo' = 'ESTADO_DERIVACION' THEN jsonb_set(elem, '{frm_value}', '\"$estadoDerivacionText\"')
                            ELSE elem
                        END ) AS updated_json
                        FROM  public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                        WHERE  cas_cod_id = '$casCodId' and cas_estado = 'E'
                        GROUP BY  cas_id)
                    UPDATE public.rmx_vys_casos
                    SET  cas_data_valores = updated_json.updated_json
                    FROM  updated_json
                    WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
            }
            $tipoDocumento = $request->input('generacionEAP');
            $tipoTramite = explode('/', $casCodId)[0];

            if ($request->input('origen') != 'PRES') {
                $estadoGeneracionEap = \DB::select("select *
                                            from (select jsonb_array_elements(cas_data_valores) AS valor, cas_id
                                            FROM public.rmx_vys_casos
                                            where cas_cod_id = '$casCodId' and cas_estado='E'
                                            ) tmp
                                            where tmp.valor->>'frm_campo' = 'ESTADO_GENERACION_EAP'");
                if (count($estadoGeneracionEap) > 0) {
                    $data3 = \DB::select("WITH updated_json AS (
                        SELECT  cas_id,
                            jsonb_agg(
                                CASE WHEN elem->>'frm_campo' = 'ESTADO_GENERACION_EAP' THEN jsonb_set(elem, '{frm_value}', '\"$tipoDocumento\"')
                                ELSE elem
                            END ) AS updated_json
                            FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                            WHERE cas_cod_id = '$casCodId' and cas_estado = 'E'
                            GROUP BY  cas_id)
                        UPDATE public.rmx_vys_casos
                        SET  cas_data_valores = updated_json.updated_json
                        FROM  updated_json
                        WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
                } else {
                    DB::table('rmx_vys_casos')
                        ->where('cas_cod_id', $casCodId)
                        ->where('cas_estado', 'E')
                        ->update([
                            'cas_data_valores' => DB::raw('cas_data_valores || \'{"frm_tipo": "HIDDEN", "frm_campo": "ESTADO_GENERACION_EAP", "frm_value": "' . $tipoDocumento . '", "frm_deshabilitado": "false", "frm_deshabilitadoo": false}\'::jsonb')
                        ]);
                }
            } else {
                if ($tipoTramite == 'JUB') {
                    $motivoRechazoJub = $request->input('motivoRechazoJub') ?? '';
                    $montoCc = $request->input('montoCc') ?? '';
                    $estadoMotivoRechazo = \DB::select("select *
                    from (select jsonb_array_elements(cas_data_valores) AS valor, cas_id
                    FROM public.rmx_vys_casos
                    where cas_cod_id = '$casCodId' and cas_estado='E'
                    ) tmp
                    where tmp.valor->>'frm_campo' = 'MOTIVO_RECHAZO_JUB'");
                    if (count($estadoMotivoRechazo) > 0) {
                        $data4 = \DB::select("WITH updated_json AS (
                            SELECT  cas_id,
                                jsonb_agg(
                                    CASE WHEN elem->>'frm_campo' = 'MOTIVO_RECHAZO_JUB' THEN jsonb_set(elem, '{frm_value}', '\"$motivoRechazoJub\"')
                                    ELSE elem
                                END ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_cod_id = '$casCodId' and cas_estado = 'E'
                                GROUP BY  cas_id)
                            UPDATE public.rmx_vys_casos
                            SET  cas_data_valores = updated_json.updated_json
                            FROM  updated_json
                            WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");

                        $data5 = \DB::select("WITH updated_json AS (
                            SELECT  cas_id,
                                jsonb_agg(
                                    CASE WHEN elem->>'frm_campo' = 'MONTO_CC' THEN jsonb_set(elem, '{frm_value}', '\"$montoCc\"')
                                    ELSE elem
                                END ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_cod_id = '$casCodId' and cas_estado = 'E'
                                GROUP BY  cas_id)
                            UPDATE public.rmx_vys_casos
                            SET  cas_data_valores = updated_json.updated_json
                            FROM  updated_json
                            WHERE  public.rmx_vys_casos.cas_id = updated_json.cas_id;");
                    } else {
                        DB::table('rmx_vys_casos')
                            ->where('cas_cod_id', $casCodId)
                            ->where('cas_estado', 'E')
                            ->update([
                                'cas_data_valores' => DB::raw("
                                cas_data_valores ||
                                '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"MOTIVO_RECHAZO_JUB\", \"frm_value\": \"$motivoRechazoJub\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                                '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"MONTO_CC\", \"frm_value\": \"$montoCc\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                            ")
                            ]);
                    }
                }
            }
            $usuarioDerivacion = $request->input('usuario'); //****
            $usuarioRevision = $request->input('usuarioRevision'); //****
            $unidad = $request->input('unidad');
            $data = DB::table('rmx_vys_casos as rvc')
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                ->where('rvc.cas_estado', '=', 'E')
                ->where('rvc.cas_cod_id', '=', $casCodId)
                ->select(
                    'rvc.cas_id as casId',
                    'rvc.cas_act_id as casActId',
                    'rvc.cas_nodo_id as casNodoId',
                    'rva.act_prc_id as actPrcId',
                    DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                    DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                    DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                    DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"'),
                    DB::raw('rvc.cas_data ->> \'USUARIO_CALCULADOR\' AS "usuarioCalculo"')
                )
                ->get();
            if (count($data) > 0) {
                $casId = $data->first()->casId;
                $casData = $data->first()->casData;
                $casDataValores = $data->first()->casDataValores;
                $actPrcId = $data->first()->actPrcId;
                $gestion = $data->first()->casGestion;
                $usuarioRegistro = $data->first()->usuarioRegistro;
                $usuarioCalculo = $data->first()->usuarioCalculo;
                $estadoDescripcion = $request->input('estadoDescripcion');
                $dataOrden = DB::table('rmx_vys_actividades')
                    ->where('act_id', '=', $data->first()->casActId)
                    ->where('act_nodo_id', '=', $data->first()->casNodoId)
                    ->where('act_prc_id', '=', $actPrcId)
                    ->select(DB::raw("act_data->>'act_siguiente' as act_siguiente, act_data->>'act_orden' as act_orden"))
                    ->first();
                $orden = $dataOrden->act_siguiente;
                $act_orden = $dataOrden->act_orden;
                $dataActividad = DB::table('rmx_vys_actividades')
                    ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                    ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                    ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                    ->where('act_estado', '=', 'A')
                    ->where('act_prc_id', '=', $actPrcId)
                    ->whereRaw("act_data->>'act_orden' = '$orden'")
                    ->select('act_nodo_id as actNodoId', 'act_id as actId', 'act_data as act_data')
                    ->get();
                $casActId = $dataActividad->first()->actId;
                $casNodoId = $dataActividad->first()->actNodoId;

                $datos_json = json_decode($data->first()->casDataValores);
                $fecha = array_values(array_filter($datos_json, function ($item) {
                    return $item->frm_campo === "AS_TIPO_EAP";
                }));
                $valor_tipo_eap = $fecha[0]->frm_value ?? null;

                $actTipo = json_decode($dataActividad->first()->act_data, true)['act_tipo_derivacion'];
                if ($request->input('origen') == 'EAP' && $act_orden == '41' && $tipoDocumento == 'A' && $tipoTramite == 'JUB' && $valor_tipo_eap == 'CVEAP-A1') {
                    $casActId = 199;
                    $casNodoId = 108;
                }
                $casUsrId;
                if ($casNodoId == 97) {
                    $dataUsuario = DB::table('users')
                        ->where('email', '=', $usuarioRegistro)
                        ->select(
                            'id',
                            'role_id as roleId',
                            'branch_id as branchId',
                            'name',
                            'email',
                            'email_verified_at as emailVerifiedAt',
                            'status',
                            'nom_usuario as nomUsuario',
                            'id_regional as idRegional',
                            'id_departamento as idDepartamento',
                            'id_agencia as idAgencia',
                            'es_atc as esAtc',
                            'es_supervisor as esSupervisor'
                        )
                        ->get();
                    $casUsrId = $dataUsuario->first()->id;
                } else {
                    if ($usuarioCalculo == null) {
                        $data = \DB::select("select run.usn_user_id
                                        from rmx_usr_nodos run
                                        inner join users u ON run.usn_user_id = u.id
                                        where run.usn_nodo_id = $casNodoId
                                        and u.status = 'A'
                                        and run.usn_estado = 'A'
                                        order by random()
                                        limit 1");
                        $casUsrId = $data[0]->usn_user_id;
                    } else {
                        $data_user = \DB::select("select id
                            from users
                            where (email = ? OR email_verified_at = ?)
                            and status = 'A'
                            limit 1", [$usuarioCalculo, $usuarioCalculo]);

                        if (empty($data_user)) {
                            $data = \DB::select("select run.usn_user_id
                                        from rmx_usr_nodos run
                                        inner join users u ON run.usn_user_id = u.id
                                        where run.usn_nodo_id = $casNodoId
                                        and u.status = 'A'
                                        and run.usn_estado = 'A'
                                        order by random()
                                        limit 1");
                            $casUsrId = $data[0]->usn_user_id;
                        } else {
                            $casUsrId = $data_user[0]->id;
                        }
                    }
                }
                $casEstado = 'T';
                if ($actTipo == 'UNION') {
                    $dataDerivar = \DB::select(
                        "select * from public.sp_derivar_caso_regreso(?, ?, ?, ?, ?, ?, ?) ",
                        [$casId, $casActId, $casNodoId, $casData, $casDataValores, $casUsrId, $casEstado]
                    );
                    $docHisId = $dataDerivar[0]->sp_derivar_caso_regreso;
                } else {
                    $origenEapPres = $request->input('origen'); //****
                    //$dataDerivar = \DB::select("select * from public.sp_derivar_caso($casId, $casActId, $casNodoId, '$casData', '$casDataValores', $casUsrId, '$casEstado', '$usuarioDerivacion', '$usuarioRevision', '$origenEapPres') ");
                    $dataDerivar = \DB::select(
                        "select * from public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ",
                        [$casId, $casActId, $casNodoId, $casData, $casDataValores, $casUsrId, $casEstado, $usuarioDerivacion, $usuarioRevision, $origenEapPres]
                    );
                    $docHisId = $dataDerivar[0]->sp_derivar_caso;
                }
                $row = 200;
                $documentos = $request->input('documentos');
                $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';
                $fechaHoraActual = new \DateTime();
                $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');

                ////reiniciar documentos
                $desc_doc = 'documento_' . $request->input('origen');
                $data = \DB::select("UPDATE public._gp_documentos
                                SET doc_descripcion = concat('-',doc_descripcion)
                                where doc_cas_id = $casId AND doc_referencia = '$desc_doc' AND doc_categoria = '$desc_doc'");

                if (trim($request->input('origen')) == 'EAP') {
                    foreach ($documentos as $indice => $valor) {

                        $inserted = DB::insert('insert into public._gp_documentos (
                                doc_cas_id,
                                doc_usr_id,
                                doc_his_id,
                                doc_codigo,
                                doc_referencia,
                                doc_categoria,
                                doc_url,
                                doc_doc_id,
                                doc_usuario,
                                doc_descripcion,
                                doc_detalle_documento,
                                id_doc_base64
                            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                            $casId,
                            $casUsrId,
                            $docHisId,
                            $casCodId,
                            'documento_' . $request->input('origen'),
                            'documento_' . $request->input('origen'),
                            $valor['documento'],
                            $row,
                            $casUsrId,
                            $valor['descripcion'],
                            $valor['documento'],
                            1
                        ]);

                        $row++;

                    }
                } else {
                    foreach ($documentos as $indice => $valor) {
                        $uuid = Str::uuid();
                        $fecha = (new \DateTime())->format('dmY_His');
                        $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
                        $datosSubirArchivo = [
                            'rutaDestino' => $docUrl,
                            'documento' => $valor['documento'],
                            'descripcion' => $nombreArch
                        ];
                        $requestSubirArchivo = new Request($datosSubirArchivo);
                        $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);
                        DB::insert('insert into public._gp_documentos (doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_usuario, doc_descripcion) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                            $casId,
                            $casUsrId,
                            $docHisId,
                            $casCodId,
                            'documento_' . $request->input('origen'),
                            'documento_' . $request->input('origen'),
                            $docUrl . $nombreArch,
                            $row,
                            $casUsrId,
                            $valor['descripcion']
                        ]);
                        $row++;
                    }
                }

                DB::commit(); // Confirmar transacción si todo es exitoso
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $responseBody = json_encode($success);
                $decodedResponseBody = json_decode($responseBody, true);
                $codigo = isset($decodedResponseBody['codigoRespuesta']) ? $decodedResponseBody['codigoRespuesta'] : '200';
                guardarServiceLog('PUT', $url, $request_body, $responseBody, $codigo, $headers, $ip, $usuario, $casCodId, 'EAP');
                return response()->json([$success]);
            } else {
                DB::rollBack();
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $error['fecha'] = $fechaFormateada;
                $error['data'] = 'El trámite ya fué devuelto y se encuentra en TramiteSip';
                $responseBody = json_encode($error);
                $decodedResponseBody = json_decode($responseBody, true);
                $codigo = isset($decodedResponseBody['codigoRespuesta']) ? $decodedResponseBody['codigoRespuesta'] : '200';
                guardarServiceLog('PUT', $url, $request_body, $responseBody, $codigo, $headers, $ip, $usuario, $casCodId, 'EAP');
                return response()->json([$error]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            $codigo = '500';
            $responseBody = json_encode($error);
            guardarServiceLog('PUT', $url, $request_body, $responseBody, $codigo, $headers, $ip, $usuario, $casCodId, 'EAP');
            return response()->json([$error]);
        }
    }

    public function getDocumentosByIdApiUcpp(Request $request)
    {
        try {
            $idDocumento = $request->input('id_documento');

            $token = GetTokenDocumentosUcpp();

            $fileUrl = urlDocumentosUcpp() . $idDocumento;

            $response = Http::withHeaders([
                'Authorization' => $token,
                'User-Agent' => 'PostmanRuntime/7.44.0',
                'Accept' => '*/*',
                'Cache-Control' => 'no-cache',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
            ])->withoutVerifying()->get($fileUrl);

            if (!$response->successful()) {
                \Log::error('Error al obtener el archivo: ' . $response->body());
                throw new \Exception('error : ' . $response->status() . ', Response: ' . $response->body());
            }

            return response($response->body(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="documento.pdf"'
            );

        } catch (\Exception $e) {
            \Log::error('Excepción: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function derivarDatosTramite(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error en servicio', "fecha" => '', "data" => $request->input('nroTramite'));
        try {

            $casCodId = $request->input('nroTramite');
            $emailUser = $request->input('email');
            $data = DB::table('rmx_vys_casos as rvc')
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                ->where('rvc.cas_estado', '=', 'E')
                ->where('rvc.cas_cod_id', '=', $casCodId)
                ->select(
                    'rvc.cas_id as casId',
                    'rvc.cas_act_id as casActId',
                    'rvc.cas_nodo_id as casNodoId',
                    'rva.act_prc_id as actPrcId',
                    DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                    DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                    DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                    DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                )
                ->get();

            $casId = $data->first()->casId;
            $casData = $data->first()->casData;
            $casDataValores = $data->first()->casDataValores;
            $actPrcId = $data->first()->actPrcId;
            $gestion = $data->first()->casGestion;
            $dataUsuario = DB::table('users')
                ->where('email_verified_at', '=', $emailUser)
                ->select(
                    'id',
                    'role_id as roleId',
                    'branch_id as branchId',
                    'name',
                    'email',
                    'email_verified_at as emailVerifiedAt',
                    'status',
                    'nom_usuario as nomUsuario',
                    'id_regional as idRegional',
                    'id_departamento as idDepartamento',
                    'id_agencia as idAgencia',
                    'es_atc as esAtc',
                    'es_supervisor as esSupervisor'
                )
                ->get();


            $UsrId = $dataUsuario->first()->id;
            $nameUSer = $dataUsuario->first()->name;



            $parts = explode('/', $casCodId);

            $codigo = $parts[0]; // 'RMIN'
            $cas_id = $parts[1];   // '10961'
            $gestion = $parts[2]; // '2024'
            $casActId = 0;
            $casNodoId = 0;
            $estado = '0';

            if ($codigo == 'RMIN') {
                $casActId = 73;
                $casNodoId = 22;
                $estado = 'T';
            } else if ($codigo == 'JUB') {
                $casActId = 53;
                $casNodoId = 16;
                $estado = 'T';
            } else if ($codigo == 'PAGCC') {
                $casActId = 102;
                $casNodoId = 16;
                $estado = 'T';
            }

            //$dataDerivar = \DB::select("select * from public.sp_derivar_caso($casId, $casActId, $casNodoId, '$casData', '$casDataValores', $UsrId, '$estado') ");                                                                    // public.sp_derivar_caso(int4, int4, int4, text, text, int4, text);
            $dataDerivar = \DB::select(
                "select * from public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?) ",
                [$casId, $casActId, $casNodoId, $casData, $casDataValores, $UsrId, $estado]
            );
            // $dataDerivar = \DB::select("select * from public.sp_derivar_caso($casId, $casActId, $casNodoId, '$casData', '$casDataValores', $casUsrId, '$casEstado') ");

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['casData'] = $casData;
            $success['nameUser'] = $nameUSer;
            $success['userID'] = $UsrId;
            $success['nameUSer'] = $nameUSer;
            $success['casId'] = $casId;
            $success['dataDerivar'] = $dataDerivar;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            $error['userId'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function derivacionTramite(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('nroTramite'));
        try {
            $casCodId = $request->input('nroTramite');
            $usuario = $request->input('usuario');

            $data = DB::table('rmx_vys_casos as rvc')
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                ->where('rvc.cas_estado', '=', 'E')
                ->where('rvc.cas_cod_id', '=', $casCodId)
                ->select(
                    'rvc.cas_id as casId',
                    'rvc.cas_act_id as casActId',
                    'rvc.cas_nodo_id as casNodoId',
                    'rva.act_prc_id as actPrcId',
                    DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                    DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                    DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                    DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                )
                ->get();

            $casId = $data->first()->casId;
            $casData = $data->first()->casData;
            $casDataValores = $data->first()->casDataValores;
            $actPrcId = $data->first()->actPrcId;
            $gestion = $data->first()->casGestion;

            $dataOrden = DB::table('rmx_vys_actividades')
                ->where('act_id', '=', $data->first()->casActId)
                ->where('act_nodo_id', '=', $data->first()->casNodoId)
                ->where('act_prc_id', '=', $actPrcId)
                ->select(DB::raw("act_data->>'act_siguiente' as act_siguiente"))
                ->first();

            $orden = $dataOrden->act_siguiente;

            $dataActividad = DB::table('rmx_vys_actividades')
                ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                ->where('act_estado', '=', 'A')
                ->where('act_prc_id', '=', $actPrcId)
                ->whereRaw("act_data->>'act_orden' = '$orden'")
                ->select('act_nodo_id as actNodoId', 'act_id as actId')
                ->get();

            $casActId = $dataActividad->first()->actId;
            $casNodoId = $dataActividad->first()->actNodoId;

            $casUsrId;
            if ($casNodoId == 97) {
                $dataUsuario = DB::table('users')
                    ->where('email_verified_at', '=', $usuario)
                    ->select(
                        'id',
                        'role_id as roleId',
                        'branch_id as branchId',
                        'name',
                        'email',
                        'email_verified_at as emailVerifiedAt',
                        'status',
                        'nom_usuario as nomUsuario',
                        'id_regional as idRegional',
                        'id_departamento as idDepartamento',
                        'id_agencia as idAgencia',
                        'es_atc as esAtc',
                        'es_supervisor as esSupervisor'
                    )
                    ->get();
                $casUsrId = $dataUsuario->first()->id;
            } else {
                $data = \DB::select("select usn_user_id
                                from rmx_usr_nodos run
                                where usn_nodo_id = $casNodoId
                                order by random()
                                limit 1");
                $casUsrId = $data[0]->usn_user_id;
            }
            $casEstado = 'E';

            //$dataDerivar = \DB::select("select * from public.sp_derivar_caso($casId, $casActId, $casNodoId, '$casData', '$casDataValores', $casUsrId, '$casEstado') ");
            $dataDerivar = \DB::select(
                "select * from public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?) ",
                [$casId, $casActId, $casNodoId, $casData, $casDataValores, $casUsrId, $casEstado]
            );

            $docHisId = $dataDerivar[0]->sp_derivar_caso;

            $tipoDocumento = $request->input('generacionEAP');

            if ($request->input('origen') != 'PRES') {
                DB::table('rmx_vys_casos')
                    ->where('cas_id', $casId)
                    ->update([
                        'cas_data_valores' => DB::raw('cas_data_valores || \'{"frm_tipo": "HIDDEN", "frm_campo": "ESTADO_GENERACION_EAP", "frm_value": "' . $tipoDocumento . '", "frm_deshabilitado": "false", "frm_deshabilitadoo": false}\'::jsonb')
                    ]);
            }

            $row = 200;

            $documentos = $request->input('documentos');

            $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';

            $fechaHoraActual = new \DateTime();
            $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');

            foreach ($documentos as $indice => $valor) {
                $uuid = Str::uuid();
                $fecha = (new \DateTime())->format('dmY_His');
                $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
                $datosSubirArchivo = [
                    'rutaDestino' => $docUrl,
                    'documento' => $valor['documento'],
                    'descripcion' => $nombreArch
                ];
                $requestSubirArchivo = new Request($datosSubirArchivo);
                $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);

                DB::insert(
                    '
                    insert into
                        public._gp_documentos (
                                doc_cas_id, doc_usr_id, doc_his_id, doc_codigo,
                                doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_usuario, doc_descripcion
                            )
                        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $casId,
                        $casUsrId,
                        $docHisId,
                        $casCodId,
                        'documento_' . $request->input('origen'),
                        'documento_' . $request->input('origen'),
                        $docUrl . $nombreArch,
                        $row,
                        $casUsrId,
                        $valor['descripcion']
                    ]
                );
                $row++;
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['dataDerivar'] = $dataDerivar;
            $success['usuario >>'] = $casUsrId;
            $success['casId'] = $casId;

            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function verificaPdfFirmadoToken(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('docCodigo'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('docCodigo'));
        try {
            $docCodigo = $request->input('docCodigo');
            $docDescripcion = $request->input('docDescripcion');

            $data = DB::table('_gp_documentos')
                ->where('doc_codigo', '=', $docCodigo)
                ->where('doc_descripcion', '=', $docDescripcion)
                ->count('doc_doc_id');

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function registraNotificacion(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('nroTramite'));
        try {
            $casCodId = $request->input('nroTramite');

            $data = DB::table('rmx_vys_casos as rvc')
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                ->where('rvc.cas_estado', '=', 'E')
                ->where('rvc.cas_cod_id', '=', $casCodId)
                ->select(
                    'rvc.cas_id as casId',
                    'rvc.cas_act_id as casActId',
                    'rvc.cas_nodo_id as casNodoId',
                    'rva.act_prc_id as actPrcId',
                    DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                    DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                    DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                    DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                )
                ->get();

            $casId = $data->first()->casId;
            $gestion = $data->first()->casGestion;
            $casNodoId = $data->first()->casNodoId;

            $documento = $request->input('documento');

            // Insercion de documento
            $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';
            $fechaHoraActual = new \DateTime();
            $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
            $uuid = Str::uuid();
            $fecha = (new \DateTime())->format('dmY_His');
            $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
            $datosSubirArchivo = [
                'rutaDestino' => $docUrl,
                'documento' => base64_decode($documento),
                'descripcion' => $nombreArch
            ];

            $requestSubirArchivo = new Request($datosSubirArchivo);
            $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);

            $usuarioRegistro = $request->input('usuarioNotificador');

            $dataUsuario = DB::table('users')
                ->where('email_verified_at', '=', $usuarioRegistro)
                ->select(
                    'id',
                    'role_id as roleId',
                    'branch_id as branchId',
                    'name',
                    'email',
                    'email_verified_at as emailVerifiedAt',
                    'status',
                    'nom_usuario as nomUsuario',
                    'id_regional as idRegional',
                    'id_departamento as idDepartamento',
                    'id_agencia as idAgencia',
                    'es_atc as esAtc',
                    'es_supervisor as esSupervisor'
                )->get();

            if ($dataUsuario->isNotEmpty()) {
                $casUsrId = $dataUsuario->first()->id;
            } else {
                throw new \Exception('Usuario no encontrado - ' . $usuarioRegistro);
            }

            $casUsrId = $dataUsuario->first()->id;

            $dataHistorico = DB::select("SELECT * FROM rmx_vys_historico_casos WHERE htc_cas_id = ? AND htc_cas_nodo_id = ? ORDER BY htc_id DESC LIMIT 1", [$casId, $casNodoId]);

            /*DB::insert('insert into public._gp_documentos (doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_usuario, doc_descripcion) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $casId,
                $casUsrId,
                $dataHistorico[0]->htc_id,
                $casCodId,
                'documento_notificacion_envio',
                'documento_notificacion',
                $docUrl . $nombreArch,
                300,
                $casUsrId,
                $request->input('descripcion')
            ]);*/

            $idGpDocumentos = DB::table('public._gp_documentos')->insertGetId([
                'doc_cas_id' => $casId,
                'doc_usr_id' => $casUsrId,
                'doc_his_id' => $dataHistorico[0]->htc_id,
                'doc_codigo' => $casCodId,
                'doc_referencia' => 'documento_notificacion_envio',
                'doc_categoria' => 'documento_notificacion',
                'doc_url' => $docUrl . $nombreArch,
                'doc_doc_id' => 300,
                'doc_usuario' => $casUsrId,
                'doc_descripcion' => $request->input('descripcion')
            ], 'doc_id');

            $usuario = $request->input('usuario');

            if ($usuario != null) {
                $dataUsuario1 = DB::table('users')
                    ->where('email', '=', $usuario)
                    ->select(
                        'id',
                        'role_id as roleId',
                        'branch_id as branchId',
                        'name',
                        'email',
                        'email_verified_at as emailVerifiedAt',
                        'status',
                        'nom_usuario as nomUsuario',
                        'id_regional as idRegional',
                        'id_departamento as idDepartamento',
                        'id_agencia as idAgencia',
                        'es_atc as esAtc',
                        'es_supervisor as esSupervisor'
                    )
                    ->get();

                $usuario = $dataUsuario1->first()->id;
            }

            $departamento = $request->input('departamento');

            if ($departamento != null) {
                $dataDepartamento = DB::table('gp_departamento')
                    ->where('descripcion_dep', '=', $departamento)
                    ->select('id_sip_departamento')
                    ->get();

                if ($dataDepartamento->isNotEmpty()) {
                    $departamento = $dataDepartamento->first()->id_sip_departamento;
                } else {
                    throw new \Exception('Departamento no validado o no encontrado - ' . $departamento);
                }
            }

            DB::insert('INSERT INTO public.notificaciones(nro_solicitud, nro_notificacion, doc_id_envio, descripcion_envio, usuario, departamento, estado, usu_cre, fec_cre) VALUES(?, ?, ?, ?, ?, ?, ?, ?, now());', [
                $casCodId,
                $request->input('nroNotificacion'),
                $idGpDocumentos,
                $request->input('descripcion'),
                $usuario,
                $departamento,
                'NOTIFICADO',
                $casUsrId
            ]);

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $casId;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function listaNotificaciones(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => "Correcto");
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => "Error");

        $PaginaActual = $request["PaginaActual"];
        $RegistrosXPagina = $request["RegistrosXPagina"];
        $buscar = $request["buscar"];

        try {
            $limit = 10;
            $offset = ($PaginaActual - 1) * $limit;

            $notificaciones = \DB::select("
                SELECT n.id
                , n.nro_solicitud
                , n.doc_id_envio
                , (SELECT gd.doc_url from _gp_documentos gd where gd.doc_id = n.doc_id_envio) as doc_url_envio
                , n.descripcion_envio
                , n.doc_id_respuesta
                , (SELECT gd.doc_url from _gp_documentos gd where gd.doc_id = n.doc_id_respuesta) as doc_url_respuesta
                , n.descripcion_respuesta
                , (SELECT u.email from users u WHERE u.id = n.usuario) as usuario
                , n.departamento
                , n.estado
                , (SELECT u.email from users u WHERE u.id = n.usu_cre) as usu_cre
                , to_char(n.fec_cre, 'dd/MM/yyyy HH24:MI:ss') as fec_cre
                , to_char(n.fec_mod, 'dd/MM/yyyy HH24:MI:ss') as fec_mod,
                rvc.*
                FROM notificaciones n
                INNER JOIN
                    rmx_vys_casos rvc on n.nro_solicitud = rvc.cas_cod_id
                WHERE n.nro_solicitud LIKE ? OR n.descripcion_envio LIKE ? OR n.descripcion_respuesta LIKE ? OR n.estado LIKE ?
                ORDER BY n.id desc
                LIMIT ? OFFSET ?;", ["%$buscar%", "%$buscar%", "%$buscar%", "%$buscar%", $RegistrosXPagina, $offset]);

            $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $success['data'] = $notificaciones;
            return response()->json($success);
        } catch (\Exception $e) {
            $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function subirRespuestaPDF(Request $request)
    {
        $id_seguimiento_tramite = $request['id_seguimiento_tramite'];

        $descripcion = $request['descripcion'];
        $id_respaldos = $request['id_respaldos'];
        $not_id = $request['id_notificacion'];

        $casCodId = $request['nroTramite'];

        $data = DB::table('rmx_vys_casos as rvc')
            ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
            ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
            ->where('rvc.cas_estado', '=', 'E')
            ->where('rvc.cas_cod_id', '=', $casCodId)
            ->select(
                'rvc.cas_id as casId',
                'rvc.cas_act_id as casActId',
                'rvc.cas_nodo_id as casNodoId',
                'rva.act_prc_id as actPrcId',
                DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
            )
            ->get();

        $casId = $data->first()->casId;
        $gestion = $data->first()->casGestion;
        $casNodoId = $data->first()->casNodoId;

        $documento = $request['documento'];

        $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';
        $fechaHoraActual = new \DateTime();
        $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
        $uuid = Str::uuid();
        $fecha = (new \DateTime())->format('dmY_His');
        $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
        $datosSubirArchivo = [
            'rutaDestino' => $docUrl,
            'documento' => $documento,
            'descripcion' => $nombreArch
        ];

        $dataHistorico = DB::select("SELECT * FROM rmx_vys_historico_casos WHERE htc_cas_id = ? AND htc_cas_nodo_id = ? ORDER BY htc_id DESC LIMIT 1", [$casId, $casNodoId]);

        $casUsrId = $request['usrId'];

        $idGpDocumentos = DB::table('public._gp_documentos')->insertGetId([
            'doc_cas_id' => $casId,
            'doc_usr_id' => $casUsrId,
            'doc_his_id' => $dataHistorico[0]->htc_id,
            'doc_codigo' => $casCodId,
            'doc_referencia' => 'documento_notificacion_envio',
            'doc_categoria' => 'documento_notificacion',
            'doc_url' => $docUrl . $nombreArch,
            'doc_doc_id' => 300,
            'doc_usuario' => $casUsrId,
            'doc_descripcion' => $request['descripcion']
        ], 'doc_id');

        \DB::table('notificaciones')
            ->where('id', $not_id)
            ->update([
                'estado' => 'ENVIADO',
                'fec_mod' => DB::raw('now()')
            ]);

        $url = Config::get('var.url_sgg_test') . "/otorgamiento-prestaciones-cpp/api/respaldos/subirRespuestaPDFR";

        $data = [
            'numero_tramite' => $id_seguimiento_tramite,
            'id_respaldos' => $id_respaldos,
            'descripcion' => $descripcion,
            'base64' => $documento
        ];

        $response = Http::withOptions(['verify' => false])->post($url, $data);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Solicitud enviada exitosamente',
                'data' => $response->json()
            ]);
        } else {
            return response()->json([
                'message' => 'Error al enviar la solicitud',
                'error' => $response->body()
            ], $response->status());
        }
    }

    public function subirPDF1582(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => "Correcto");
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => "Error");

        $documento = $request->input('documento');
        if (empty($documento)) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = 'El documento no puede estar vacío';
            return response()->json($error);
        }
        $apiuuid = $request->input('apiuuid');
        $cua = $request->input('cua');
        $usucre = $request->input('usucre');

        $gestion = (new \DateTime())->format('Y');

        $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . 'JUB1582-' . $cua . '/';

        $fechaHoraActual = new \DateTime();
        $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
        $uuid = Str::uuid();
        $fecha = (new \DateTime())->format('dmY_His');
        $nombreArch = $cua . '_1582_' . $fechaHoraFormato . '.pdf';

        try {
            $id1582Documentos = DB::table('public._gp_documentos_1582')->insertGetId([
                'cua' => $cua,
                'apiuuid' => $apiuuid,
                'doc_referencia' => 'documento_1582',
                'doc_url' => $docUrl . $nombreArch,
                'estado' => 'E',
                'usu_cre' => $usucre,
                'fec_cre' => DB::raw('now()')
            ], 'id');

        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }

        $datosSubirArchivo = [
            'rutaDestino' => $docUrl,
            'documento' => $documento,
            'descripcion' => $nombreArch
        ];

        $requestSubirArchivo = new Request($datosSubirArchivo);
        $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);

        if ($id1582Documentos) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $docUrl . $nombreArch;
            return response()->json($success);
        } else {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = 'No se pudo insertar el documento';
            return response()->json($error, 500);
        }
    }
    public function grabarRechazo1582(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => "Correcto");
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => "Error");
        $apiuuid = $request->input('apiuuid');
        $cua = $request->input('cua');
        $usucre = $request->input('usucre');

        try {
            $id1582Documentos = DB::table('public._gp_documentos_1582')->insertGetId([
                'cua' => $cua,
                'apiuuid' => $apiuuid,
                'doc_referencia' => 'documento_1582',
                'doc_url' => '',
                'estado' => 'E',
                'usu_cre' => $usucre,
                'fec_cre' => DB::raw('now()')
            ], 'id');

        } catch (\Exception $e) {
            return response()->json($error);
        }
    }


    public function actualizarEstadoNotificacion(Request $request, $id)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => "Correcto");
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => "Error");
        try {
            $estado = $request->input('estado');
            $usuario = $request->input('usuario');

            \DB::table('notificaciones')
                ->where('id', $id)
                ->update([
                    'estado' => $estado,
                    'fec_mod' => DB::raw('now()'),
                    'usuario' => $usuario
                ]);

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $id;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function observarTramite(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => "Correcto");
        $alert = array("codigoRespuesta" => 304, "mensaje" => 'Alerta', "fecha" => '', "data" => "Alerta");
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => "Error");

        try {
            $casCodId = $request->input('nroTramite');
            $tipoTramite = explode('/', $casCodId)[0];

            if ($tipoTramite == 'JUB' || $tipoTramite == 'RMIN' || $tipoTramite == 'GFU' || $tipoTramite == 'MAHER') {

                $usuarioDerivacion = $request->input('usuarioDerivacion');
                $sn_arroba_usuarioDerivacion = explode('@', $usuarioDerivacion)[0];

                $data = DB::table('rmx_vys_casos as rvc')
                    ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                    ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                    ->where('rvc.cas_estado', '=', 'E')
                    ->where('rvc.cas_cod_id', '=', $casCodId)
                    ->select(
                        'rvc.cas_id as casId',
                        'rvc.cas_act_id as casActId',
                        'rvc.cas_nodo_id as casNodoId',
                        'rva.act_prc_id as actPrcId',
                        DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                        DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                        DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                        DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                    )
                    ->get();

                if (collect($data)->isEmpty()) {
                    throw new \Exception("El trámite no se encuentra en estado disponible - " . $casCodId);
                }

                $casId = $data->first()->casId;
                $gestion = $data->first()->casGestion;
                $casNodoId = $data->first()->casNodoId;
                $actPrcId = $data->first()->actPrcId;

                $casData = $data->first()->casData;
                $casDataValores = $data->first()->casDataValores;

                $casDataArray = json_decode($casData, true);

                if ($data->isEmpty()) {
                    $alert['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $alert['data'] = "No se puede observar el trámite - . $casCodId";
                    return response()->json($alert);
                }

                $dataOrden = DB::table('rmx_vys_actividades')
                    ->where('act_id', '=', $data->first()->casActId)
                    ->where('act_nodo_id', '=', $data->first()->casNodoId)
                    ->where('act_prc_id', '=', $actPrcId)
                    ->select(DB::raw("act_data->>'act_orden' as act_orden"))
                    ->first();

                $act_orden = $dataOrden->act_orden;

                $dataActividad = DB::table('rmx_vys_actividades')
                    ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                    ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                    ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                    ->where('act_estado', '=', 'A')
                    ->whereRaw("prc_data->>'prc_codigo' = ?", [$tipoTramite])
                    ->whereRaw("act_data->>'act_orden' = '$act_orden'")
                    ->select('act_nodo_id as actNodoId', 'act_id as actId', 'act_data')
                    ->get();

                if ($dataActividad->isEmpty()) {
                    throw new \Exception("El trámite no se encuentra en estado disponible - " . $casCodId);
                }

                $data_user = \DB::select("select id
                    from users
                    where (email = ? OR email_verified_at = ?)
                    and status = 'A'
                    limit 1", [$sn_arroba_usuarioDerivacion, $usuarioDerivacion]);

                if (empty($data_user)) {
                    $alert['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $alert['data'] = "No se encontro al usuario - . $usuarioDerivacion";
                    return response()->json($alert);
                } else {
                    $casUsrId = $data_user[0]->id;
                }

                if ($act_orden == "41") {
                    $casActId = 18;
                    $casNodoId = 73;
                    $casDataArray['ESTADO_DERIVACION'] = 'REGULARIZADO';
                    $casDataArray['DESCRIPCION_DERIVACION'] = '';
                    $casDataModificado = json_encode($casDataArray);
                    $estadoDerivacion = 'REGULARIZADO';

                    $documento = $request->input('documento');

                    if (trim($request->input('origen')) == 'EAP') {
                        foreach ($documento as $indice => $valor) {
                            $inserted = DB::insert('insert into public._gp_documentos (
                                    doc_cas_id,
                                    doc_usr_id,
                                    doc_his_id,
                                    doc_codigo,
                                    doc_referencia,
                                    doc_categoria,
                                    doc_url,
                                    doc_doc_id,
                                    doc_usuario,
                                    doc_descripcion,
                                    doc_detalle_documento,
                                    id_doc_base64
                                ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                                $casId,
                                $casUsrId,
                                $dataHistorico[0]->htc_id,
                                $casCodId,
                                'documento_' . $request->input('origen'),
                                'documento_' . $request->input('origen'),
                                $valor['documento'],
                                $row,
                                $casUsrId,
                                $valor['descripcion'],
                                $valor['documento'],
                                1
                            ]);
                            $row++;
                        }
                    }
                } else if ($act_orden == "60") {
                    $casActId = 198;
                    $casNodoId = 82;
                    $casDataArray['ESTADO_DERIVACION'] = 'OBSERVADO';
                    $casDataArray['DESCRIPCION_DERIVACION'] = '';
                    $casDataModificado = json_encode($casDataArray);
                    $estadoDerivacion = 'OBSERVADO';

                    $dataHistorico = DB::select("SELECT * FROM rmx_vys_historico_casos WHERE htc_cas_id = ? AND htc_cas_nodo_id = ? ORDER BY htc_id DESC LIMIT 1", [$casId, $casNodoId]);

                    $documento = $request->input('documento');

                    //INSERTAR DOCUMENTO
                    $docUrl = '/opt/documental/tramitesip/' . $gestion . '/' . $casId . '/';
                    $fechaHoraActual = new \DateTime();
                    $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
                    $fecha = (new \DateTime())->format('dmY_His');
                    $nombreArch = Str::uuid() . '_' . $fechaHoraFormato . '.pdf';

                    $datosSubirArchivo = [
                        'rutaDestino' => $docUrl,
                        'documento' => $documento,
                        'descripcion' => $nombreArch
                    ];

                    $requestSubirArchivo = new Request($datosSubirArchivo);
                    $responseSubirArchivo = $this->subirArchivoPDF($requestSubirArchivo);

                    $idGpDocumentos = DB::table('public._gp_documentos')->insertGetId([
                        'doc_cas_id' => $casId,
                        'doc_usr_id' => $casUsrId,
                        'doc_his_id' => $dataHistorico[0]->htc_id,
                        'doc_codigo' => $casCodId,
                        'doc_referencia' => 'documento_observar_tramite',
                        'doc_categoria' => 'documento_observar',
                        'doc_url' => $docUrl . $nombreArch,
                        'doc_doc_id' => 300,
                        'doc_usuario' => $casUsrId,
                        'doc_descripcion' => $request->input('descripcion')
                    ], 'doc_id');
                }

                $dataDerivar = \DB::select(
                    "select * from public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?) ",
                    [$casId, $casActId, $casNodoId, $casDataModificado, $casDataValores, $casUsrId, 'E']
                );

            } else {
                throw new \Exception('Tramite no valido - ' . $casCodId);
            }

            $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $success['data'] = "Operación realizada correctamente - " . $casCodId;
            $success['mensaje'] = "El trámite ha sido marcado como - " . $estadoDerivacion;
            return response()->json($success);
        } catch (\Exception $e) {
            $error['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function deriva1582_62(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => "Correcto");
        $alert = array("codigoRespuesta" => 304, "mensaje" => 'Alerta', "fecha" => '', "data" => "Alerta");
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => "Error");

        try {
            DB::beginTransaction();
            $casCodId = $request->input('nroTramite');
            $data = DB::table('rmx_vys_casos as rvc')
                ->where('rvc.cas_estado', '=', 'T')
                ->where('rvc.cas_act_id', '=', 249)
                ->where('rvc.cas_cod_id', '=', $casCodId)
                ->select(
                    'rvc.cas_id as casId',
                    DB::raw('rvc.cas_data ->> \'AS_CUA\' AS "cua"'),
                )
                ->get();

            if (collect($data)->isEmpty()) {
                throw new \Exception("El trámite no se encuentra en estado disponible para derivar o ya fue derivado - " . $casCodId);
            }

            $casId = $data->first()->casId;
            $cua = $data->first()->cua;
            $datosTram = array(
                'cas_id' => $casId,
                'cua' => $cua
            );
            $apiVySController = new ApiVySController;
            $requestEnvio = new Request($datosTram);
            $resultadoPres = $apiVySController->actualizarDatosContrato($requestEnvio);

            $tipoTramite = explode('/', $casCodId)[0];

            if ($tipoTramite == 'JUB1582') {
                $data = DB::table('rmx_vys_casos as rvc')
                    ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                    ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                    ->where('rvc.cas_estado', '=', 'T')
                    ->where('rvc.cas_cod_id', '=', $casCodId)
                    ->select(
                        'rvc.cas_id as casId',
                        'rvc.cas_act_id as casActId',
                        'rvc.cas_nodo_id as casNodoId',
                        'rva.act_prc_id as actPrcId',
                        DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                        DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                        DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                        DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"')
                    )
                    ->get();

                if (collect($data)->isEmpty()) {
                    throw new \Exception("El trámite no se encuentra en estado disponible para derivar o ya fue derivado - " . $casCodId);
                }

                $casId = $data->first()->casId;
                $gestion = $data->first()->casGestion;
                $casNodoId = $data->first()->casNodoId;
                $actPrcId = $data->first()->actPrcId;

                $casData = $data->first()->casData;
                $casDataValores = $data->first()->casDataValores;

                $casDataArray = json_decode($casData, true);

                if ($data->isEmpty()) {
                    $alert['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $alert['data'] = "No se puede observar el trámite - . $casCodId";
                    return response()->json($alert);
                }

                $dataOrden = DB::table('rmx_vys_actividades')
                    ->where('act_id', '=', $data->first()->casActId)
                    ->where('act_nodo_id', '=', $data->first()->casNodoId)
                    ->where('act_prc_id', '=', $actPrcId)
                    ->select(DB::raw("act_data->>'act_orden' as act_orden"))
                    ->first();

                $act_orden = $dataOrden->act_orden;

                $dataActividad = DB::table('rmx_vys_actividades')
                    ->join('rmx_vys_procesos', 'prc_id', '=', 'act_prc_id')
                    ->join('rmx_vys_tipos_actividad', 'tact_id', '=', 'act_tact_id')
                    ->join('rmx_vys_nodos', 'nodo_id', '=', 'act_nodo_id')
                    ->where('act_estado', '=', 'A')
                    ->whereRaw("prc_data->>'prc_codigo' = ?", [$tipoTramite])
                    ->whereRaw("act_data->>'act_orden' = '$act_orden'")
                    ->select('act_nodo_id as actNodoId', 'act_id as actId', 'act_data')
                    ->get();

                if ($dataActividad->isEmpty()) {
                    throw new \Exception("El trámite no se encuentra en estado disponible - " . $casCodId);
                }
                $UsrId = 0;
                if ($act_orden == "62") {
                    $casActId = 253;
                    $casNodoId = 97;

                    $dataDerivar = \DB::select(
                        "select * from public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?) ",
                        [$casId, $casActId, $casNodoId, $casData, $casDataValores, $UsrId, 'A']
                    );
                }
            } else {
                throw new \Exception('Tramite no valido para derivar - ' . $casCodId);
            }

            DB::commit();
            $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $success['data'] = "Operación realizada correctamente - " . $casCodId;
            $success['mensaje'] = "El trámite ha sido marcado como - " . 'DERIVADO';
            return response()->json($success);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function estadoDerivacionTramite(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'No se encontro el Trámite', "data" => $request->input('nroTramite'));
        /*
            W = Paralelo
            T = Tomado
            X = Eliminado
            A = Asignado
            E = Enviado
            H = Archivado
        */

        try {
            $nroTramite = $request->input('nroTramite');

            if (empty($nroTramite)) {
                throw new \Exception("El número de trámite es obligatorio.");
            }

            $sqlTramite = DB::table('rmx_vys_casos as rvc')
                ->where('rvc.cas_cod_id', '=', $nroTramite)
                ->select('rvc.cas_id as casId', 'rvc.cas_estado as casEstado')
                ->orderBy('cas_id', 'desc')
                ->first();

            $tipoTramite = explode('/', $nroTramite)[0];

            if ($tipoTramite === 'PM' || $tipoTramite === 'INV') {
                $data = DB::table('rmx_vys_casos as rvc')
                    ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                    ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                    ->where('rvc.cas_estado', '!=', 'X')
                    ->where('rvc.cas_cod_id', '=', $nroTramite)
                    // ->where('rvc.cas_padre_id', '=', 0)
                    ->select(
                        'rvc.cas_id as casId',
                        'rvc.cas_act_id as casActId',
                        'rvc.cas_nodo_id as casNodoId',
                        'rva.act_prc_id as actPrcId',
                        'rvc.cas_estado as casEstado',
                        DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                        DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                        DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                        DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"'),
                        DB::raw('rva.act_data ->> \'act_descripcion\' AS "actDescripcion"')
                    )->get();
                if (collect($data)->isEmpty()) {
                    throw new \Exception("NO SE ENCONTRO EL TRÁMITE - " . $nroTramite);
                }
            } else {
                $data = DB::table('rmx_vys_casos as rvc')
                    ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                    ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                    ->where('rvc.cas_estado', '!=', 'X')
                    ->where('rvc.cas_cod_id', '=', $nroTramite)
                    ->select(
                        'rvc.cas_id as casId',
                        'rvc.cas_act_id as casActId',
                        'rvc.cas_nodo_id as casNodoId',
                        'rva.act_prc_id as actPrcId',
                        'rvc.cas_estado as casEstado',
                        DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
                        DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
                        DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                        DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"'),
                        DB::raw('rva.act_data ->> \'act_descripcion\' AS "actDescripcion"')
                    )->get();
                if (collect($data)->isEmpty()) {
                    throw new \Exception("NO SE ENCONTRO EL TRÁMITE - " . $nroTramite);
                }
            }

            $casId = $data->first()->casId;

            $gestion = $data->first()->casGestion;
            $casNodoId = $data->first()->casNodoId;
            $actPrcId = $data->first()->actPrcId;
            $casData = $data->first()->casData;

            $casActId = $data->first()->casActId;

            $dataOrden = DB::table('rmx_vys_actividades')
                ->where('act_id', '=', $data->first()->casActId)
                ->where('act_nodo_id', '=', $data->first()->casNodoId)
                ->where('act_prc_id', '=', $actPrcId)
                ->select(DB::raw("act_data->>'act_orden' as act_orden"), DB::raw("act_data->>'act_descripcion' as act_descripcion"))
                ->first();

            $nodoOrder = DB::table('rmx_vys_nodos')
                ->where('nodo_id', '=', $casNodoId)
                ->select(DB::raw("nodo_codigo , nodo_descripcion"))
                ->first();

            $act_orden = $dataOrden->act_orden;
            $act_descripcion = $dataOrden->act_descripcion;

            $success['data'] = $nroTramite;
            $success['nodo'] = $act_orden;
            $success['descripcion - nodo'] = $act_descripcion . ' - ' . $nodoOrder->nodo_codigo . ' - ' . $nodoOrder->nodo_descripcion;
            return response()->json($success);
        } catch (\Exception $e) {
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function retrocederTramite(Request $request)
    {
        //enviar un tramite de la 50 a la 40
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "data" => $request->input('nroTramite'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'No se encontro el Trámite', "data" => $request->input('nroTramite'));
        DB::beginTransaction(); // Iniciar la transacción
        try {
            $nroTramite = $request->input('nroTramite');
            $emailUser = $request->input('emailUser');

            if (empty($nroTramite) || empty($emailUser)) {
                throw new \Exception("El número de trámite y el correo electrónico del usuario son obligatorios.");
            }

            $data = DB::table('rmx_vys_casos as c')
            ->join('rmx_vys_actividades as a', 'c.cas_act_id', '=', 'a.act_id')
            ->whereNotNull(DB::raw('a.act_data->>\'act_anterior\''))
            ->where('c.cas_estado', 'T')
            ->where('c.cas_nodo_id', 97)
            ->where('c.cas_cod_id', $nroTramite)
            ->select(
                'c.cas_id as casId',
                'c.cas_act_id as casActId',
                'c.cas_nodo_id as casNodoId',
                'a.act_prc_id as actPrcId',
                'c.cas_estado as casEstado',
                DB::raw('c.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                DB::raw('a.act_data ->> \'act_descripcion\' AS "actDescripcion"'),
                DB::raw('c.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"'),
                DB::raw('a.act_data ->> \'act_orden\' AS "act_orden"') 
            )
            ->get();
           // echo($data);die();

            

            if ($data->isEmpty()) {
                $data = DB::table('rmx_vys_casos as c')
                            ->join('rmx_vys_actividades as a', 'c.cas_act_id', '=', 'a.act_id')
                            //->whereNotNull(DB::raw('a.act_data->>\'act_anterior\''))
                            ->where('c.cas_estado', 'E')
                            ->where('c.cas_nodo_id', 82)
                            ->where('c.cas_cod_id', $nroTramite)
                            ->whereRaw("a.act_data->>'act_orden' = '40'")
                            ->select(
                                'c.cas_id as casId',
                                'c.cas_act_id as casActId',
                                'c.cas_nodo_id as casNodoId',
                                'a.act_prc_id as actPrcId',
                                'c.cas_estado as casEstado',
                                DB::raw('c.cas_data ->> \'cas_gestion\' AS "casGestion"'),
                                DB::raw('a.act_data ->> \'act_descripcion\' AS "actDescripcion"'),
                                DB::raw('c.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"'),
                                DB::raw('a.act_data ->> \'act_orden\' AS "act_orden"') 
                            )
                            ->get();
                if ($data->isEmpty()) {
                    throw new \Exception("YA NO ES POSIBLE EL ENVIO A UCPP - ".$nroTramite);
                }
            }

            $userGet = getUser($emailUser);

            if ($userGet->isEmpty()) {
                throw new \Exception("Usuario no encontrado para correo electrónico " . $emailUser);
            }

            $UsrId = $userGet->first()->id;
            $nameUSer = $userGet->first()->name;

            $casId = $data->first()->casId;
            $gestion = $data->first()->casGestion;
            $casNodoId = $data->first()->casNodoId;
            $actPrcId = $data->first()->actPrcId;
            // $casData = $data->first()->casData;
            // $casDataValores = $data->first()->casDataValores;
            $casActId = $data->first()->casActId;
            $act_orden = $data->first()->act_orden;

            $dataDerivar = DB::table('rmx_vys_actividades as a')
                ->where('a.act_prc_id', 3)
                ->where('a.act_data->act_orden', '=', DB::raw(
                    '(SELECT b.act_data->>\'act_anterior\' FROM rmx_vys_actividades b WHERE b.act_id = 149)'
                ))
                ->select(
                    DB::raw('a.act_data->>\'act_orden\' AS act_orden'),
                    'a.act_id as actId',
                    'a.act_prc_id as actPrcId',
                    'a.act_nodo_id as actNodoId'
                )
                ->get();

            if ($casNodoId == "97") {
                $campos_pg ='{"ESTADO_DERIVACION", "DESCRIPCION_DERIVACION"}';
                $valores_pg = '{"REVERTIDO_UCPP", "SOLICITUD EJECUTADA POR PERSONAL DE UCPP"}';

                $resultado = \DB::select("SELECT sp_derivar_caso1(?, ?, ?, ?, ?, ?, ?)", [
                    $casId,
                    $dataDerivar->first()->actId,
                    $dataDerivar->first()->actNodoId,
                    $UsrId,
                    'E',
                    $campos_pg,
                    $valores_pg
                ]);
                $desc_doc = 'documento_EAP';
                $data3 = \DB::select("UPDATE public._gp_documentos
                                SET doc_descripcion = concat('-',doc_descripcion), doc_estado='E'
                                where doc_cas_id = $casId AND doc_referencia = '$desc_doc' AND doc_categoria = '$desc_doc'");

                $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                $success['data'] = "Operación realizada correctamente - " . $nroTramite;
                $success['mensaje'] = "El trámite ha sido enviado a la UCPP";
                DB::commit(); // Confirmar transacción si todo es exitoso
                return response()->json($success);
            } else if ($act_orden == "40") {
                $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                $success['data'] = "Operación realizada correctamente - " . $nroTramite;
                $success['mensaje'] = "El trámite se encuentra en la UCPP";
                DB::commit(); // Confirmar transacción si todo es exitoso
                return response()->json($success);
            }
            else {
                throw new \Exception("No se puede enviar el trámite a la UCPP - " . $nroTramite);
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }
}
