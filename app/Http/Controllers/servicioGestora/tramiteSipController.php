<?php

namespace App\Http\Controllers\servicioGestora;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class tramiteSipController extends Controller
{
    public function buscarCasos(Request $request)
    {
        $cua = $request["cua"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $qry = " SELECT    cas_cod_id, act_data->>'act_orden' as actividad , act_data->>'act_descripcion' as descripcion, date(cas_registrado) as  fecha_solicitud
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"AS_CUA\": \"$cua\" }' 
                    and c.cas_padre_id = 0 
                    --and cas_data->>'TIPO_PROCESO' = 'RMIN'
                    ORDER BY c.cas_modificado desc";
            $data = \DB::select(
                $qry
            );
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
    public function buscarCasosGFU(Request $request)
    {
        $cua = $request["cua"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $qry = " SELECT    cas_cod_id, act_data->>'act_orden' as actividad , act_data->>'act_descripcion' as descripcion, date(cas_registrado) as  fecha_solicitud
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"AS_CUA\": \"$cua\" }' 
                    and c.cas_padre_id = 0 
                    and act_data->>'act_descripcion' != 'RECHAZADO'
                    and cas_data->>'TIPO_PROCESO' = 'GFU'
                    ORDER BY c.cas_modificado desc";
            $data = \DB::select(
                $qry
            );
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function buscarCasosJUB(Request $request)
    {
        $cua = $request["cua"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $qry = " SELECT    cas_cod_id, act_data->>'act_orden' as actividad , act_data->>'act_descripcion' as descripcion, date(cas_registrado) as  fecha_solicitud
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"AS_CUA\": \"$cua\" }' 
                    and c.cas_padre_id = 0 
                    and cas_data->>'TIPO_PROCESO' = 'JUB'
                    ORDER BY c.cas_modificado desc";
            $data = \DB::select(
                $qry
            );
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
    public function dias_festivos(Request $request)
    {
        $fecha = $request["fecha"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $qry = " select *
                from  dias_no_habiles 
                where fecha = '$fecha'
                ";
            $data = \DB::select(
                $qry
            );
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
    public function obtenerSolicitante(Request $request)
    {
        $nro_tramite = $request["nro_tramite"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('mensaje' => 'error de instancia', 'code' => 500);
        try {
            $qry = " select     
                        
                         (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_CI'
                        ) AS SOL_CI,
                        
                         (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_COMPLEMENTO'
                        ) AS SOL_COMPLEMENTO,
                         (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_NACIMIENTO'
                        ) AS SOL_NACIMIENTO,
                        
                           (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_CUA'
                        ) AS SOL_CUA,
                        
                           (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_APELLIDO'
                        ) AS SOL_PRIMER_APELLIDO,
                        
                           (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_SEGUNDO_APELLIDO'
                        ) AS SOL_SEGUNDO_APELLIDO,
                        
                        (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_APELLIDO_CASADA'
                        ) AS SOL_APELLIDO_CASADA,
                        
                           (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_NOMBRE'
                        ) AS SOL_PRIMER_NOMBRE,
                           (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_SEGUNDO_NOMBRE'
                        ) AS SOL_SEGUNDO_NOMBRE,
                          (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_ESTADO_CIVIL'
                        ) AS SOL_ESTADO_CIVIL,
                        
                         (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'SOL_GENERO'
                        ) AS SOL_GENERO
                        from rmx_vys_casos
                        where cas_cod_id = '$nro_tramite'
                    ";

            $data = \DB::select(
                $qry
            );

            if ($data == []) {


            }
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }


    public function buscarTramiteAsegurado(Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];

        $cas_cod_id = $cas_nro_caso;
        $cas_tipo = $request["cas_tipo"] === null ? null : $request["cas_tipo"];
        $num_identificacion = $request["num_identificacion"] === null ? '' : $request["num_identificacion"];
        $cas_correlativo = $request["cas_correlativo"] === null ? '' : 'and c.cas_correlativo = ' . $request["cas_correlativo"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            if ($cas_tipo != null) {

                $dataTotalRegistros = \DB::select(
                    "SELECT count(*) as total_registros
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    --LEFT JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id  AND n.usn_estado <> 'X' --and n.usn_user_id = c.cas_usr_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"$cas_tipo\": \"$num_identificacion\" }' $cas_correlativo
                    AND c.cas_cod_id = '$cas_cod_id' and c.cas_padre_id = 0"
                );


                $sql = "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                    ,(
                        SELECT datos.valor->>'frm_value'
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor,cas_cod_id
                            FROM public.rmx_vys_casos
                        ) datos
                        WHERE datos.valor->>'frm_campo' = 'MENSAJE_RECHAZO_DESISTIDO'
                        AND datos.cas_cod_id = c.cas_cod_id
                        LIMIT 1
                    ) AS MENSAJE_RECHAZO_DESISTIDO
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    --LEFT JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id  AND n.usn_estado <> 'X' --and n.usn_user_id = c.cas_usr_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"$cas_tipo\": \"$num_identificacion\" }' $cas_correlativo
                    AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                    ORDER BY c.cas_modificado desc
                    ";

                ///dd($sql);
                $data = \DB::select(
                    "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                    ,(
                        SELECT datos.valor->>'frm_value'
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor,cas_cod_id
                            FROM public.rmx_vys_casos
                        ) datos
                        WHERE datos.valor->>'frm_campo' = 'MENSAJE_RECHAZO_DESISTIDO'
                        AND datos.cas_cod_id = c.cas_cod_id
                        LIMIT 1
                    ) AS MENSAJE_RECHAZO_DESISTIDO
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    --LEFT JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id  AND n.usn_estado <> 'X' --and n.usn_user_id = c.cas_usr_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"$cas_tipo\": \"$num_identificacion\" }' $cas_correlativo
                    AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                    ORDER BY c.cas_modificado desc
                   "

                );
                $ip = request()->ip();
                $data_json = json_encode([], 0);
                $data_log = \DB::select(
                    "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                    array($sql, $data_json, $usuario, $pro, $tramite, $ip)
                );
            } else {
                $dataTotalRegistros = \DB::select(
                    "SELECT count(*) as total_registros
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X'
                    AND c.cas_cod_id like '$cas_cod_id' $cas_correlativo and c.cas_padre_id = 0
                    --AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
              "
                );


                $sql = "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                ,(
                        SELECT datos.valor->>'frm_value'
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor,cas_cod_id
                            FROM public.rmx_vys_casos
                        ) datos
                        WHERE datos.valor->>'frm_campo' = 'MENSAJE_RECHAZO_DESISTIDO'
                        AND datos.cas_cod_id = c.cas_cod_id
                        LIMIT 1
                    ) AS MENSAJE_RECHAZO_DESISTIDO
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X'
                    AND c.cas_cod_id like '$cas_cod_id' $cas_correlativo and c.cas_padre_id = 0
                    --AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                    ORDER BY c.cas_modificado desc";
                $data = \DB::select($sql);
                $ip = request()->ip();
                $data_json = json_encode([], 0);
                $data_log = \DB::select(
                    "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                    array($sql, $data_json, $usuario, $pro, $tramite, $ip)
                );
            }

            return response()->json(['data' => $data, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

}
