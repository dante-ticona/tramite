<?php
namespace App\Derivacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class EnviarseguimientoTramite
{
    /**
     * ___TramiteSIP___
     * Deriva el trámite por primera ves.
     * Envio del Tramite a UCPP -> https://sgg.gestora.bo/otorgamiento-prestaciones/api/v2/seguimientoTramites/registrar"
     * Luego realiza la derivacion del caso -> public.sp_derivar_caso
     */
    public static function derivarPrimera($resultado, $casCodId)
    {
        // dump("DERIVAR PRIMERA");

        $casEstado = isset($resultado['cas_estado']) ? $resultado['cas_estado'] : 'default_value';
        $tipoDerivacion = isset($resultado['tipoDerivacion']) ? $resultado['tipoDerivacion'] : 'default_value';
        $cas_nodo_id = isset($resultado['cas_nodo_id']) ? $resultado['cas_nodo_id'] : 'default_value';
        $act_actual = isset($resultado['act_actual']) ? $resultado['act_actual'] : 'default_value';

        $cas_cod_id = isset($resultado['cas_cod_id']) ? $resultado['cas_cod_id'] : 'default_value';
        $cas_act_id = isset($resultado['cas_act_id']) ? $resultado['cas_act_id'] : 'default_value';
        $cas_usr_id = isset($resultado['cas_usr_id']) ? $resultado['cas_usr_id'] : 'default_value';

        $resultado = \DB::select("SELECT public.sp_datos_seguimiento_tramite(?) AS datos", [$casCodId]);

        //--> dump("RESULTADO: " . print_r($resultado, true));

        $area = 'EAP';

        $resultadoArray = json_decode(json_encode($resultado), true);
        $datos = json_decode($resultadoArray[0]['datos'], true);
        $usuario_registro = $datos['usuario_registro'];

        $dataInf = [];

        foreach ($datos as $key => $value) {
            $dataInf[$key] = $value;
        }

        // exit();

        // dump("DATAINF: " . print_r($dataInf, true));

        // ->> LO QUE ERA en el front const url = `${this.urlGestoraSgg}/otorgamiento-prestaciones/api/v2/seguimientoTramites/registrar?area=${area}&usuReg=${user_envio}`;
        $url = urlsggTest() . "/otorgamiento-prestaciones/api/v2/seguimientoTramites/registrar";

        if($area == 'EAP'){
            $user_envio = $usuario_registro;
        } else {
            // Sin funcionar
            $user_envio = getUserEmail();
        }

        // dump("USUARIO ENVIO: ". $user_envio);

        $queryParams = [
            'area' => 'EAP',
            'usuReg' => $user_envio
        ];

        // dump($casCodId);

        $response = Http::withOptions(['verify' => false])
                        ->post($url . '?' . http_build_query($queryParams), $dataInf);

        if ($response->status() == 200) {
            $responseData = $response->json();
            // Estructura de Respuesta OK 200: [codigo] => 200 , [mensaje] => Proceso Correcto, [fecha] => 2024-10-23 14:36:02, [data] => jhoel.rodriguez@gestora.bo
            // dump("RESPONSE DATA 200:" . print_r($responseData, true));

            $user_asignado = $responseData['data'];
            // dump("USUARIO ASIGNADO  :". $user_asignado);

            // ->> lo que era this.tomarCasoUsuario(userAsignado, cas_id) en el front -> let url = "api/asignarUsuarioResp/" + casId;
            $data = \DB::select("select * from public.sp_asignar_caso($casCodId,'$user_asignado','E')");

            // dump("DATA del sp_asignar_caso:". print_r($data));

            // ->> lo que era this.derivarCaso(cas_id) en el front -> let url = "api/casosDerivar/" + this.registro.cas_id;
            $cas_data = json_encode($data, 0);

            // dump("CAS_DATA json_encode :". print_r($cas_data));

            $results = \DB::select("SELECT c.cas_data_valores
                FROM rmx_vys_casos c
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                    AND c.cas_id = $casCodId
                ORDER BY c.cas_modificado DESC");
                // dump("RESULTS cas_data_valores :". print_r($results));

            if (!empty($results)) {
                // dump("ENTRO A LOS RESULTADOS");
                $cas_data_valores = $results[0]->cas_data_valores;
                $cas_data_valores = json_decode($cas_data_valores, true);
                // dump("CAS_DATA_VALORES DENTRO DEL IF:". print_r($cas_data_valores));
            }

            // $cas_data_valores = json_encode($cas_data_valores, 0); //->> REVISAR>>>>>>>>>
            // dump("CAS_DATA_VALORES FUERA DEL IF:". print_r($cas_data_valores));

            //cuando se deriva por el SP
            $data_respuesta = \DB::select(
                "SELECT * FROM public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?)",
                    //DROP FUNCTION public.sp_derivar_caso(int4, int4, int4, text, text, int4, text);
                    [$casCodId, $cas_act_id, $cas_nodo_id, $cas_data, json_encode($cas_data_valores), $cas_usr_id, $casEstado]
            );

            // dump("DATA_RESPUESTA >> ", print_r($data_respuesta));

            //ESTA OPCION PERMITE EL REGISTRO DE FECHA DE DERIVACION HACIA EL MEDICO EN PM Y INV
            if($cas_act_id == 91 || $cas_act_id == 97) {
                // Asignar la fecha actual en el formato YYYY-MM-DD
                $FECHA_DE_RESEPCION = date('Y-m-d');

                // Consultar si ya existe la fecha de recepción
                $estadoRespuestaCal = \DB::select("SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$cas_id'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' = 'FECHA_DE_RESEPCION';");

                if (empty($estadoRespuestaCal)) {
                    // Si no existe la fecha, insertar el campo en el JSON
                    try {
                        DB::table('rmx_vys_casos')
                            ->where('cas_id', $cas_id)
                            ->update([
                                'cas_data_valores' => DB::raw("
                                    cas_data_valores ||
                                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"FECHA_DE_RESEPCION\", \"frm_value\": \"$FECHA_DE_RESEPCION\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
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
                                            WHEN elem->>'frm_campo' = 'FECHA_DE_RESEPCION' THEN jsonb_set(elem, '{frm_value}', '\"$FECHA_DE_RESEPCION\"')
                                            ELSE elem
                                        END
                                    ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_id = '$cas_id'
                                GROUP BY cas_id
                            )
                            UPDATE public.rmx_vys_casos
                            SET cas_data_valores = updated_json.updated_json
                            FROM updated_json
                            WHERE public.rmx_vys_casos.cas_id = updated_json.cas_id;
                        ");

                        if (empty($dataInv)) {
                            throw new \Exception("NO SE ACTUALIZÓ LA FECHA.");
                        }
                    } catch (\Exception $e) {
                        throw new \Exception("NO SE ACTUALIZÓ: " . $e->getMessage());
                    }
                }
            }

            // dump("por el 200 de la funcion EnviarSEguimientoTramite");
            return [
                'data' => $data_respuesta,
                'user_asignado' => $user_asignado
            ];
        } else {
            // dump("existe un error >>>> ");
            // dump("ERROR: " . print_r($response->json(), true));
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public static function tramitesvolver($resultado, $casCodId){
        // dump("DERIVAR OBSERVADOR VOLVER DELA 65 O LA 61");

        $respuesta_sp = \DB::select('SELECT public.sp_datos_seguimiento_tramite(?) AS datos', [$casCodId]);

        if (!empty($respuesta_sp)) {
            // dump("RESULTADO DEL SP>> ", $respuesta_sp);
            // $success['data'] = $resultado[0]->datos;
            // return $resultado[0]->datos;

            $casEstado = isset($resultado['cas_estado']) ? $resultado['cas_estado'] : 'default_value';
            $tipoDerivacion = isset($resultado['tipoDerivacion']) ? $resultado['tipoDerivacion'] : 'default_value';
            $cas_nodo_id = isset($resultado['cas_nodo_id']) ? $resultado['cas_nodo_id'] : 'default_value';
            $act_actual = isset($resultado['act_actual']) ? $resultado['act_actual'] : 'default_value';

            $cas_cod_id = isset($resultado['cas_cod_id']) ? $resultado['cas_cod_id'] : 'default_value';
            $cas_act_id = isset($resultado['cas_act_id']) ? $resultado['cas_act_id'] : 'default_value';
            $cas_usr_id = isset($resultado['cas_usr_id']) ? $resultado['cas_usr_id'] : 'default_value';

            $resultado = DB::select('SELECT public.sp_datos_seguimiento_tramite(?) AS datos', [$casCodId]);
            $area = 'EAP';

            $resultadoArray = json_decode(json_encode($resultado), true);
            $datos = json_decode($resultadoArray[0]['datos'], true);
            $usuario_registro = $datos['usuario_registro'];
            $observacion = $datos['observacion'];

            $data = [];

            foreach ($datos as $key => $value) {
                $data[$key] = $value;
            }

            $url = urlsggTest() ."/otorgamiento-prestaciones/api/v1/devolucionTramites/tramiteObservado";

            $queryParams = [
                'area' => 'EAP',
                'usuario' => $usuario_registro,
                'observacion' => $observacion
            ];

            $response = Http::withOptions(['verify' => false])
            ->post($url . '?' . http_build_query($queryParams), $data);

            if ($response->status() == 200) {
                $responseData = $response->json();
                // dump("DATA ->>>>> RESPONSE PARA CUANDO VUELVE POR OBSERVADO >>>>>>> ", $responseData);
                // return [
                //     'data' => $responseData,
                //     'user_asignado' => $user_asignado
                // ];

            } else {
                // dump("existe un error >>>> ");
                $error['data'] = $e->getMessage();
                return response()->json($error);
            }
        } else {
            // dump("NO SE ENCONTRARON DATOS PARA EL CASO ID: ", $casCodId);
            return 'No se encontraron datos para el caso ID: ' . $casCodId;
        }

    }
}
