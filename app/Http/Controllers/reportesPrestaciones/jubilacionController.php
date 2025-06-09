<?php

namespace App\Http\Controllers\reportesPrestaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class jubilacionController extends Controller
{

    public function casosReporte(Request $request)
    {




        $sql = "  SELECT 
                        c.cas_data->>'cas_departamento' AS departamento,
                        c.cas_data->>'cas_regional' AS regional,
                        c.cas_data->>'cas_agencia' AS agencia,
                        CONCAT(
                            c.cas_data->>'AS_PRIMER_NOMBRE', ' ', 
                            c.cas_data->>'AS_SEGUNDO_NOMBRE', ' ', 
                            c.cas_data->>'AS_PRIMER_APELLIDO', ' ', 
                            c.cas_data->>'AS_SEGUNDO_APELLIDO'
                        ) AS nombre_completo,
                        c.cas_data->>'AS_CUA' AS cua,
                        (
                            SELECT datos.valor->>'frm_value_label'
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                        ) AS tipo_eap,
                        c.cas_data->>'cas_registrado' AS cas_registrado,
                        historial.result
                    FROM rmx_vys_casos c
                    INNER JOIN (                 
                    SELECT     htc_cas_id as historico_cas_id ,to_json(array_agg(todo.*)) AS result
                    FROM (
                        SELECT 
                        htc_cas_id,htc_cas_registrado,htc_cas_modificado,htc_cas_estado,htc_cas_cod_id, nom_usuario,email, act_data,htc_cas_data
                        FROM rmx_vys_historico_casos hst
                        INNER JOIN rmx_vys_actividades ON act_id = hst.htc_cas_act_id
                        INNER JOIN rmx_vys_nodos ON nodo_id = hst.htc_cas_nodo_id
                        INNER JOIN users ON id = hst.htc_cas_usr_id
                    -- WHERE hst.htc_cas_id = 3893	
                    ORDER BY hst.htc_id
                    ) AS todo
                    group by   htc_cas_id                                 
                    ) AS historial ON historial.historico_cas_id = c.cas_id
                    WHERE c.cas_estado <> 'X'
                    AND c.cas_cod_id LIKE 'JUB/%/2024'
                    AND c.cas_padre_id = 0
                    AND c.cas_data_valores <> '[]'
                    ORDER BY departamento, c.cas_id asc
";


        $dataJubilacion = \DB::select("$sql");

        $respRepJubilacion = [];
        $i = 1;




        foreach ($dataJubilacion as $jubilacion) {

            //  dd('estamos  en reportes', json_decode($jubilacion->result));
            $dataHistorico = json_decode($jubilacion->result);

            foreach ($dataHistorico as $hisJubilacion) {
                $act_data = $hisJubilacion->act_data;
                $htc_cas_data = $hisJubilacion->htc_cas_data;
                //dd('estamos  en reportes dsad as', $act_data->act_orden);
                if ($act_data->act_orden == '50' && $htc_cas_data->ESTADO_DERIVACION == 'FIRMADO') {
                    $fecha_notificacion_verificacion_eap = Carbon::parse($hisJubilacion->htc_cas_modificado);
                    $fecha_notificacion_verificacion_eap = $fecha_notificacion_verificacion_eap->toDateString();
                    $estado_solicitud = $htc_cas_data->ESTADO_DERIVACION;
                }

                if ($act_data->act_orden == '65') {
                    $estado_calculo = $htc_cas_data->ESTADO_DERIVACION;
                    $analista_calculo = $hisJubilacion->email;
                } else if ($act_data->act_orden == '60') {

                }

                if ($act_data->act_orden == '67') {
                    $fecha_derivacion_revisor = Carbon::parse($hisJubilacion->htc_cas_modificado);
                    $fecha_derivacion_revisor = $fecha_derivacion_revisor->toDateString();
                    $analista_revisor = $hisJubilacion->email;
                }

                if ($act_data->act_orden == '68') {
                    $fecha_derivacion = Carbon::parse($hisJubilacion->htc_cas_modificado);
                    $fecha_derivacion = $fecha_derivacion->toDateString();
                    $analista_aprobador = $hisJubilacion->email;
                }
                if ($act_data->act_orden == '69') {
                    $fecha_derivacion_nacional = Carbon::parse($hisJubilacion->htc_cas_modificado);
                    $fecha_derivacion_nacional = $fecha_derivacion_nacional->toDateString();
                    $oficina_nacional = $hisJubilacion->email;
                    $fecha_derivacion_atc = Carbon::parse($hisJubilacion->htc_cas_modificado);
                    $fecha_derivacion_atc = $fecha_derivacion_atc->toDateString();
                }
            }

            $respRepJubilacion[$i] = array(
                'departamento' => $jubilacion->departamento,
                'regional' => $jubilacion->regional,
                'agencia' => $jubilacion->agencia,
                'nombre_asegurado' => $jubilacion->nombre_completo,
                'cua' => $jubilacion->cua,
                'subclasificacion' => $jubilacion->tipo_eap,
                'fecha_solicitud' => $jubilacion->cas_registrado,
                'fecha_notificacion_verificacion_eap' => $fecha_notificacion_verificacion_eap,
                'estado_solicitud' => $estado_solicitud,
                'analista_calculo' => $analista_calculo,
                'estado_calculo' => $estado_calculo,
                'fecha_derivacion_revisor' => $fecha_derivacion_revisor,
                'analista_revisor' => $analista_revisor,
                'fecha_derivacion' => $fecha_derivacion,
                'analista_aprobador' => $analista_aprobador,
                'fecha_derivacion_nacional' => $fecha_derivacion_nacional,
                'oficina_nacional' => $oficina_nacional,
                'fecha_derivacion_atc' => $fecha_derivacion_atc,
                'plazo_calculo_dias' => $analista_revisor,

            );
            $i++;
        }
        $success = array('code' => 200, 'mensaje' => 'OK');
        return response()->json(['data' => $respRepJubilacion, 'codigoRespuesta' => $success]);
    }



}
