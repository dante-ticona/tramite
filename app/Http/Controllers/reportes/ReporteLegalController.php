<?php

namespace App\Http\Controllers\reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteLegalController extends Controller
{
    public function generarReporteLegal(Request $request)
    {
        $success = ["codigoRespuesta" => 200, "mensaje" => 'OK', "fecha" => '', "data" => ''];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        $prc_codigo = $request["prc_codigo"] ?? '%';
        $cas_nro_caso = $request["cas_nro_caso"] ?? '%';
        $cas_gestion = $request["cas_gestion"] ?? '%';
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] ?? '';
        $fecha_fin = $request["fecha_fin"] ?? '';
        $id_departamento = $request["id_departamento"] ?? 0;
        $id_regional = $request["id_regional"] ?? 0;
        $id_agencia = $request["id_agencia"] ?? 0;
        $id_area = $request["id_area"] ?? 0;

        $sip_dep = $id_departamento ? '"id_cas_departamento":' . $id_departamento : '';
        $sip_reg = $id_regional ? ',"id_cas_regional":' . $id_regional : '';
        $sip_age = $id_agencia ? ',"id_cas_agencia":' . $id_agencia : '';
        $sip_nodo = $id_area ? 'and c.cas_nodo_id = ' . $id_area : '';

        try {
            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                COALESCE(c.cas_data->>'cas_departamento', '') as cas_departamento,
                c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_data->>'ABOGADO_REVISOR' as abogado_revisor,
                fecha_hc1.htc_cas_registrado as fecha_derivacion_legal,
                fecha_hc2.htc_cas_registrado as fecha_derivacion_gnl,
                fecha_hc3.htc_cas_registrado as fecha_derivacion_abg,
                c.cas_cod_id,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_SUB_SOLICITUD'
                    LIMIT 1) AS as_sub_solicitud_value,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                    LIMIT 1) AS as_tipo_eap_value,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_PODER_SOL_1'
                    LIMIT 1) AS nro_poder_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_NOTARIA_SOL_1'
                    LIMIT 1) AS nro_notaria_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NOMBRE_NOTARIO_SOL_1'
                    LIMIT 1) AS nombre_notario_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'EXTRANGERO_PODER'
                    LIMIT 1) AS extranjero_poder,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_CI'
                    LIMIT 1) AS sol_ci,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_APELLIDO'
                    LIMIT 1) AS sol_primer_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_SEGUNDO_APELLIDO'
                    LIMIT 1) AS sol_segundo_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_NOMBRE'
                    LIMIT 1) AS sol_primer_nombre,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_DERIVACION'
                    LIMIT 1) AS estado_derivacion,
                (SELECT
                    json_agg(
                        json_build_object(
                            'tipo_documento', item.value->>'col_value',
                            'id_persona', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DACO_CI_GRILLA_PROP'),
                            'complemento', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_COMP_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_CORREO'),
                            'parentesco', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_PARENTESCO')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DACO'
                ) AS grilla_daco,
                (SELECT
                    json_agg(
                        json_build_object(
                            'id_persona_sip', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DAHE_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_CI_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_CORREO'),
                            'funcionario_gestora', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_FUNCIONARIO_GESTORA')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DAHE'
                ) AS grilla_dahe,
                REPLACE(
                    REPLACE(c.cas_data->>'cas_nombre_caso', '|', '<br>'),
                    'undefined', '-'
                ) as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_prestaciones,
                valores.tipo_eap_legal,
                valores.testimonio_judicial,
                gea.est_codigo
            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_estado NOT IN ('X', 'H', 'W')
                AND cas_cod_id LIKE '%LEGAL%'
                AND cas_registrado::date BETWEEN '$fecha_ini' AND '$fecha_fin'
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.id = c.cas_usr_id
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value_label' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'TESTIMONIO_JUDICIAL' THEN datos.valor->>'frm_value' END) AS testimonio_judicial
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 264
            ) fecha_hc1 ON fecha_hc1.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 266
            ) fecha_hc2 ON fecha_hc2.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 265
            ) fecha_hc3 ON fecha_hc3.htc_cas_cod_id = c.cas_cod_id
            ORDER BY c.cas_modificado DESC";

            $data = \DB::select($sql);

            // \Log::info('CONSULTA >>> ', ['data' => $data]);

            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function generarExcel(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] ?? '%';
        $cas_nro_caso = $request["cas_nro_caso"] ?? '%';
        $cas_gestion = $request["cas_gestion"] ?? '%';
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] ?? '';
        $fecha_fin = $request["fecha_fin"] ?? '';
        $id_departamento = $request["id_departamento"] ?? 0;
        $id_regional = $request["id_regional"] ?? 0;
        $id_agencia = $request["id_agencia"] ?? 0;
        $id_area = $request["id_area"] ?? 0;

        $sip_dep = $id_departamento ? '"id_cas_departamento":' . $id_departamento : '';
        $sip_reg = $id_regional ? ',"id_cas_regional":' . $id_regional : '';
        $sip_age = $id_agencia ? ',"id_cas_agencia":' . $id_agencia : '';
        $sip_nodo = $id_area ? 'and c.cas_nodo_id = ' . $id_area : '';

        try {
            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                COALESCE(c.cas_data->>'cas_departamento', '') as cas_departamento,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_data->>'ABOGADO_REVISOR' as abogado_revisor,
                fecha_hc1.htc_cas_registrado as fecha_derivacion_legal,
                fecha_hc2.htc_cas_registrado as fecha_derivacion_gnl,
                fecha_hc3.htc_cas_registrado as fecha_derivacion_abg,
                c.cas_cod_id,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_SUB_SOLICITUD'
                    LIMIT 1) AS as_sub_solicitud_value,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                    LIMIT 1) AS as_tipo_eap_value,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_PODER_SOL_1'
                    LIMIT 1) AS nro_poder_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_NOTARIA_SOL_1'
                    LIMIT 1) AS nro_notaria_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NOMBRE_NOTARIO_SOL_1'
                    LIMIT 1) AS nombre_notario_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'EXTRANGERO_PODER'
                    LIMIT 1) AS extranjero_poder,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_CI'
                    LIMIT 1) AS sol_ci,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_APELLIDO'
                    LIMIT 1) AS sol_primer_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_SEGUNDO_APELLIDO'
                    LIMIT 1) AS sol_segundo_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_NOMBRE'
                    LIMIT 1) AS sol_primer_nombre,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_DERIVACION'
                    LIMIT 1) AS estado_derivacion,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_LEGAL'
                    LIMIT 1) AS estado_legal,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'DESCRIPCION_FUNDAMENTACION'
                    LIMIT 1) AS descripcion_fundamentacion,
                (SELECT
                    json_agg(
                        json_build_object(
                            'tipo_documento', item.value->>'col_value',
                            'id_persona', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DACO_CI_GRILLA_PROP'),
                            'complemento', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_COMP_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_CORREO'),
                            'parentesco', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_PARENTESCO')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DACO'
                ) AS grilla_daco,
                (SELECT
                    json_agg(
                        json_build_object(
                            'id_persona_sip', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DAHE_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_CI_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_CORREO'),
                            'funcionario_gestora', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_FUNCIONARIO_GESTORA')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DAHE'
                ) AS grilla_dahe,
                REPLACE(
                    REPLACE(c.cas_data->>'cas_nombre_caso', '|', '<br>'),
                    'undefined', '-'
                ) as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_prestaciones,
                valores.tipo_eap_legal,
                valores.testimonio_judicial,
                gea.est_codigo
            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_estado NOT IN ('X', 'H', 'W')
                AND cas_cod_id LIKE '%LEGAL%'
                AND cas_registrado::date BETWEEN '$fecha_ini' AND '$fecha_fin'
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.id = c.cas_usr_id
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value_label' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'TESTIMONIO_JUDICIAL' THEN datos.valor->>'frm_value' END) AS testimonio_judicial
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 264
            ) fecha_hc1 ON fecha_hc1.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 266
            ) fecha_hc2 ON fecha_hc2.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 265
            ) fecha_hc3 ON fecha_hc3.htc_cas_cod_id = c.cas_cod_id
            ORDER BY c.cas_modificado DESC";

            $titulo = "ReporteLegal.xlsx";
            $nombreArchivo = uniqid(md5(session_id()) . $titulo) . '.xlsx';

            set_time_limit(300);

            $spreadsheet = new Spreadsheet();
            $hojaActiva = $spreadsheet->getActiveSheet();

            $styleTitulos1 = array(
                'font' => array(
                    'bold' => true,
                    'size' => 12,
                    'name' => 'Arial'
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                )
            );

            $styleTitulos2 = array(
                'font' => array(
                    'bold' => true,
                    'size' => 11,
                    'name' => 'Arial'
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                )
            );

            $estiloSubtitulo = [
                'font' => [
                    'bold' => true,
                    'size' => 8,
                    'name' => 'Arial',
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => [
                        'rgb' => 'B06218'
                    ]
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ];

            $estiloCuerpo = [
                'font' => [
                    'bold' => false,
                    'size' => 8,
                    'name' => 'Arial'
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ];


            $hojaActiva->setCellValue('A2', "REPORTE LEGAL");
            $hojaActiva->getStyle('A2:R2')->applyFromArray($styleTitulos1);
            $hojaActiva->mergeCells('A2:R2');

            $hojaActiva->setCellValue('A3', "Corresponde al periodo " . $fecha_ini . " - " . $fecha_fin);
            $hojaActiva->getStyle('A3:R3')->applyFromArray($styleTitulos2);
            $hojaActiva->mergeCells('A3:R3');

            $titulosColumnas = array(
                'NRO. TRÁMITE ',
                'CASO CORRELATIVO',
                'TIPO SUB SOLICITUD',
                'ACTIVIDAD',
                'NO. CASO',
                'UNIDAD ACTUAL',
                'USUARIO',
                'FECHA REGISTRO TRÁMITE',
                'FECHA DE DERIVACIÓN',
                'ASEGURADO',
                'NRO DOCUMENTO',
                'CUA',
                'REGIONAL',
                'DEPARTAMENTO',
                'ESTADO DERIVACION',

                'PROCESO', //
                'SUB SOLICITUD',
                'VALIDACIÓN',

                'NRO. PODER',
                'NRO. NOTARIA',
                'NOMBRE NOTARIO',

                'TIPO TESTIMONIO',
                'DATOS SOLICITANTE',
                'DATOS BENEFICIARIO',
                'DATOS APODERADO',

                'AGENCIA',
                'FECHA DERIVACIÓN ASIG-LE',
                'FECHA DERIVACIÓN ABOGADO',
                'FECHA DERIVACIÓN GNL',
                'MOTIVO DE RECHAZO',
                'ESTADO LEGAL',
            );

            for ($i = 0; $i < count($titulosColumnas); $i++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
                $hojaActiva->setCellValue($columnLetter . '5', $titulosColumnas[$i]);
                $hojaActiva->getStyle($columnLetter . '5')->applyFromArray($estiloSubtitulo);
                $hojaActiva->getColumnDimension($columnLetter)->setAutoSize(true);
            };

            $fila = 6;
            $page = 0;
            $pageSize = 1000;

            do {
                $offset = $page * $pageSize;
                $sqlPaginated = $sql . " LIMIT $pageSize OFFSET $offset";
                $data = \DB::select($sqlPaginated);

                foreach ($data as $row) {
                    $asegurado = trim($row->as_primer_apellido . ' ' . $row->as_segundo_apellido . ' ' . $row->as_primer_nombre . ' ' . $row->as_segundo_nombre);

                    $estado = match ($row->cas_estado) {
                        'A' => 'Libre',
                        'T' => 'Tomado',
                        'E' => 'ENVIADO UCPP',
                        default => 'Sin Estado',
                    };

                    $hojaActiva->setCellValue('A' . $fila, $row->cas_id);
                    $hojaActiva->setCellValue('B' . $fila, $row->cas_correlativo);
                    $hojaActiva->setCellValue('C' . $fila, $row->nombre_proceso);
                    $hojaActiva->setCellValue('D' . $fila, $row->act_orden . '-' . $row->act_descripcion);
                    $hojaActiva->setCellValue('E' . $fila, $row->cas_cod_id);
                    $hojaActiva->setCellValue('F' . $fila, $row->nodo_descripcion);
                    $hojaActiva->setCellValue('G' . $fila, $row->nom_usuario);
                    $hojaActiva->setCellValue('H' . $fila, substr($row->cas_registrado,0,18));
                    $hojaActiva->setCellValue('I' . $fila, substr($row->cas_modificado,0,18));
                    $hojaActiva->setCellValue('J' . $fila, $asegurado);
                    $hojaActiva->setCellValue('K' . $fila, $row->as_ci);
                    $hojaActiva->setCellValue('L' . $fila, $row->as_cua);
                    $hojaActiva->setCellValue('M' . $fila, $row->cas_regional);
                    $hojaActiva->setCellValue('N' . $fila, $row->cas_departamento);
                    $hojaActiva->setCellValue('O' . $fila, $row->estado_derivacion);

                    $hojaActiva->setCellValue('P' . $fila, $row->tipo_eap_legal);
                    $hojaActiva->setCellValue('Q' . $fila, $row->as_sub_solicitud_value);
                    $hojaActiva->setCellValue('R' . $fila, $row->as_tipo_eap_value);

                    $hojaActiva->setCellValue('S' . $fila, $row->nro_poder_sol);
                    $hojaActiva->setCellValue('T' . $fila, $row->nro_notaria_sol);
                    $hojaActiva->setCellValue('U' . $fila, $row->nombre_notario_sol);
                    $extranjeroPoder = match ($row->extranjero_poder) {
                        '1' => 'EXTRANJERO',
                        '2' => 'NACIONAL',
                        '3' => 'PROTOCOLIZADO',
                        default => 'N/A',
                    };
                    $hojaActiva->setCellValue('V' . $fila, $extranjeroPoder);

                    $solicitante = trim($row->sol_ci . ' - ' . $row->sol_primer_apellido . ' ' . $row->sol_segundo_apellido . ' ' . $row->sol_primer_nombre);
                    $hojaActiva->setCellValue('W' . $fila, $solicitante);

                    $beneficiario = '';
                    if (isset($row->grilla_daco)) {
                        $grillaDaco = json_decode($row->grilla_daco);
                        foreach ($grillaDaco as $index => $item) {
                            $beneficiario .= trim(
                                $item->ci . ' ' .
                                $item->nombres . ' - ' .
                                $item->primer_apellido . ' ' .
                                $item->segundo_apellido . ' ' .
                                $item->apellido_casada . ' '
                            );
                            if ($index < count($grillaDaco) - 1) {
                                $beneficiario .= "\n---\n";
                            }
                        }
                    }
                    $hojaActiva->setCellValue('X' . $fila, $beneficiario);

                    $apoderado = '';
                    if (isset($row->grilla_dahe)) {
                        $grillaDahe = json_decode($row->grilla_dahe);
                        foreach ($grillaDahe as $index => $item) {
                            $apoderado .= trim(
                                $item->ci . ' - ' .
                                $item->nombres . ' - ' .
                                $item->primer_apellido . ' ' .
                                $item->segundo_apellido
                            );
                            if ($index < count($grillaDahe) - 1) {
                                $apoderado .= "\n---\n";
                            }
                        }
                    }
                    $hojaActiva->setCellValue('Y' . $fila, $apoderado);

                    $hojaActiva->setCellValue('Z' . $fila, $row->cas_agencia);
                    $hojaActiva->setCellValue('AA' . $fila, substr($row->fecha_derivacion_legal,0,18));
                    $hojaActiva->setCellValue('AB' . $fila, substr($row->fecha_derivacion_abg,0, 18));
                    $hojaActiva->setCellValue('AC' . $fila, substr($row->fecha_derivacion_gnl, 0, 18));

                    if ($row->estado_legal === 'RCHZ_LEGAL') {
                        $descripcionConSaltos = wordwrap($row->descripcion_fundamentacion, 50, "\n", true);
                        $hojaActiva->setCellValue('AD' . $fila, $descripcionConSaltos);
                    } else {
                        $hojaActiva->setCellValue('AD' . $fila, '');
                    }

                    $hojaActiva->setCellValue('AE' . $fila, $row->estado_legal);


                    $hojaActiva->getStyle('A' . $fila . ':AE' . $fila)->applyFromArray($estiloCuerpo);
                    $fila++;
                }

                $page++;
            } while (count($data) > 0);

            try {
                if (ob_get_length()) {
                    ob_end_clean();
                }

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            } catch (\Exception $e) {
                \Log::error('Error al general el Excel: ' . $e->getMessage());
                return response()->json(['error' => 'Error al generar el archivo Excel.'], 500);
            }

            exit;
        } catch (\Exception $e) {
            \Log::error('Error en generarExcel: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
            return response()->json(['error' => 'Error al generar el reporte.', 'details' => $e->getMessage()], 500);
        }
    }

    public function generarCsv(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] ?? '%';
        $cas_nro_caso = $request["cas_nro_caso"] ?? '%';
        $cas_gestion = $request["cas_gestion"] ?? '%';
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] ?? '';
        $fecha_fin = $request["fecha_fin"] ?? '';
        $id_departamento = $request["id_departamento"] ?? 0;
        $id_regional = $request["id_regional"] ?? 0;
        $id_agencia = $request["id_agencia"] ?? 0;
        $id_area = $request["id_area"] ?? 0;

        $sip_dep = $id_departamento ? '"id_cas_departamento":' . $id_departamento : '';
        $sip_reg = $id_regional ? ',"id_cas_regional":' . $id_regional : '';
        $sip_age = $id_agencia ? ',"id_cas_agencia":' . $id_agencia : '';
        $sip_nodo = $id_area ? 'and c.cas_nodo_id = ' . $id_area : '';

        try {
            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_data->>'ABOGADO_REVISOR' as abogado_revisor,
                c.cas_data->>'cas_departamento' as cas_departamento, -- Agregado aquí
                fecha_hc1.htc_cas_registrado as fecha_derivacion_legal,
                fecha_hc2.htc_cas_registrado as fecha_derivacion_gnl,
                c.cas_cod_id,
                (SELECT datos.valor->>'frm_value_label'
                 FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                 WHERE datos.valor->>'frm_campo' = 'AS_SUB_SOLICITUD'
                 LIMIT 1) AS as_sub_solicitud_value,
                (SELECT datos.valor->>'frm_value_label'
                 FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                 WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                 LIMIT 1) AS as_tipo_eap_value,
                REPLACE(
                    REPLACE(c.cas_data->>'cas_nombre_caso', '|', '<br>'),
                    'undefined', '-'
                ) as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_prestaciones,
                valores.tipo_eap_legal,
                valores.testimonio_judicial,
                gea.est_codigo,
                c.cas_data->>'caso_heredado' as caso_heredado
            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_estado NOT IN ('X', 'H', 'W')
                AND cas_cod_id LIKE '%LEGAL%'
                AND cas_registrado::date BETWEEN '$fecha_ini' AND '$fecha_fin'
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.id = c.cas_usr_id
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value_label' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'TESTIMONIO_JUDICIAL' THEN datos.valor->>'frm_value' END) AS testimonio_judicial
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 264
            ) fecha_hc1 ON fecha_hc1.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 266
            ) fecha_hc2 ON fecha_hc2.htc_cas_cod_id = c.cas_cod_id
            ORDER BY c.cas_modificado DESC";

            $data = \DB::select($sql);

            $filename = 'ReporteLegal_' . now()->format('Ymd_His') . '.csv';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');

            $headers = [
                'Nro. Trámite', 'Caso Correlativo', 'Código Nodo', 'Descripción Nodo', 'Descripción Proceso',
                'Orden Actividad', 'Descripción Actividad', 'Primer Apellido', 'Segundo Apellido',
                'Primer Nombre', 'Segundo Nombre', 'Nombre Proceso', 'Regional', 'Departamento',
                'Estado Derivación', 'CI', 'CUA', 'Agencia', 'Código Caso', 'Usuario',
                'Fecha Registrado', 'Fecha Modificado', 'Estado', 'Tipo Prestaciones', 'Nombre Prestación',
                'Tipo EAP Legal', 'Caso Heredado','Fecha derivación Legal', 'Fecha derivación GNL' ,'Estado Código'
            ];
            fputcsv($output, $headers);

            foreach ($data as $row) {
                fputcsv($output, [
                    $row->cas_id,
                    $row->cas_correlativo,
                    $row->nodo_codigo,
                    $row->nodo_descripcion,
                    $row->prc_descripcion,
                    $row->act_orden,
                    $row->act_descripcion,
                    $row->as_primer_apellido,
                    $row->as_segundo_apellido,
                    $row->as_primer_nombre,
                    $row->as_segundo_nombre,
                    $row->nombre_proceso,
                    $row->cas_regional,
                    $row->cas_departamento,
                    $row->estado_derivacion,
                    $row->as_ci,
                    $row->as_cua,
                    $row->cas_agencia,
                    $row->cas_cod_id,
                    $row->nom_usuario,
                    $row->cas_registrado,
                    $row->cas_modificado,
                    $row->cas_estado,
                    $row->tipo_prestaciones,
                    $row->tipo_eap_legal,
                    $row->caso_heredado,
                    $row->tipo_eap_legal,
                    $row->caso_heredado,
                    $row->fecha_derivacion_legal,
                    $row->fecha_derivacion_gnl,
                    $row->est_codigo
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            \Log::error('Error al generar el CSV: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte en CSV.'], 500);
        }
    }

    public function GetUserLegal()
    {
        $success = ["codigoRespuesta" => 200, "mensaje" => 'OK', "fecha" => '', "data" => ''];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $data = \DB::select("SELECT u.id,u.name ,u.email, n.nodo_descripcion
            FROM public.users u
            inner join public.rmx_usr_nodos un on  u.id =un.usn_user_id
            inner join public.rmx_vys_nodos n on un.usn_nodo_id = n.nodo_id
            where un.usn_nodo_id = 15 and un.usn_estado <>'X' and u.status <>'X' ");

            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function generarReporteTramiteXUsuario(Request $request)
    {
        $success = ["codigoRespuesta" => 200, "mensaje" => 'OK', "fecha" => '', "data" => ''];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $usuarios = $request["usuario"] ?? '';

            $fecha_ini_personal = $request["fecha_ini_personal"] ?? '';
            $fecha_fin_personal = $request["fecha_fin_personal"] ?? '';

            $usuariosArray = array_map('trim', explode(',', $usuarios));

            $placeholders = implode(',', array_fill(0, count($usuariosArray), '?'));

            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                c.cas_data->>'cas_departamento' as cas_departamento,
                c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_cod_id,
                c.cas_data->>'cas_nombre_caso' as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                c.cas_padre_id,
                valores.tipo_prestaciones,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_eap_legal,
                valores.caso_heredado,
                gea.est_codigo,
                fecha_hc1.htc_cas_registrado as fecha_derivacion_legal,
                fecha_hc2.htc_cas_registrado as fecha_derivacion_gnl,
                fecha_hc3.htc_cas_registrado as fecha_derivacion_abg,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_SUB_SOLICITUD'
                    LIMIT 1) AS as_sub_solicitud_value,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                    LIMIT 1) AS as_tipo_eap_value,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_PODER_SOL_1'
                    LIMIT 1) AS nro_poder_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_NOTARIA_SOL_1'
                    LIMIT 1) AS nro_notaria_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NOMBRE_NOTARIO_SOL_1'
                    LIMIT 1) AS nombre_notario_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'EXTRANGERO_PODER'
                    LIMIT 1) AS extranjero_poder,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_CI'
                    LIMIT 1) AS sol_ci,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_APELLIDO'
                    LIMIT 1) AS sol_primer_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_SEGUNDO_APELLIDO'
                    LIMIT 1) AS sol_segundo_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_NOMBRE'
                    LIMIT 1) AS sol_primer_nombre,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_DERIVACION'
                    LIMIT 1) AS estado_derivacion,
                (SELECT
                    json_agg(
                        json_build_object(
                            'tipo_documento', item.value->>'col_value',
                            'id_persona', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DACO_CI_GRILLA_PROP'),
                            'complemento', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_COMP_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_CORREO'),
                            'parentesco', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_PARENTESCO')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DACO'
                ) AS grilla_daco,
                (SELECT
                    json_agg(
                        json_build_object(
                            'id_persona_sip', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DAHE_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_CI_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_CORREO'),
                            'funcionario_gestora', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_FUNCIONARIO_GESTORA')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DAHE'
                ) AS grilla_dahe

            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_registrado::date >= ? AND cas_registrado::date <= ?
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.nom_usuario = c.cas_data->>'ABOGADO_REVISOR'
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'CASO_HEREDARO' THEN datos.valor->>'frm_value' END) AS caso_heredado
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            LEFT JOIN (
            SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 264
            ) fecha_hc1 ON fecha_hc1.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
            SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 266
            ) fecha_hc2 ON fecha_hc2.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 265
            ) fecha_hc3 ON fecha_hc3.htc_cas_cod_id = c.cas_cod_id
            WHERE c.cas_data->>'ABOGADO_REVISOR' IN ($placeholders)
            AND c.cas_cod_id LIKE '%LEGAL%'
            ORDER BY c.cas_modificado DESC";

            $params = array_merge([$fecha_ini_personal, $fecha_fin_personal], $usuariosArray);

            $data = \DB::select($sql, $params);

            $nroAtendidos = count($data);

            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['nroAtendidos'] = $nroAtendidos;
            $success['data'] = $data;
            return response()->json($success);
        }
        catch (\Exception $e) {
            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function generarExcelTramiteXUsuario(Request $request)
    {
        try {
            $usuarios = $request["usuario"] ?? '';
            $fecha_ini_personal = $request["fecha_ini_personal"] ?? '';
            $fecha_fin_personal = $request["fecha_fin_personal"] ?? '';

            $usuariosArray = array_map('trim', explode(',', $usuarios));
            $placeholders = implode(',', array_fill(0, count($usuariosArray), '?'));

            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                c.cas_data->>'cas_departamento' as cas_departamento,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_cod_id,
                c.cas_data->>'cas_nombre_caso' as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                c.cas_padre_id,
                valores.tipo_prestaciones,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_eap_legal,
                valores.caso_heredado,
                gea.est_codigo,
                fecha_hc1.htc_cas_registrado as fecha_derivacion_legal,
                fecha_hc2.htc_cas_registrado as fecha_derivacion_gnl,
                fecha_hc3.htc_cas_registrado as fecha_derivacion_abg,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_SUB_SOLICITUD'
                    LIMIT 1) AS as_sub_solicitud_value,
                (SELECT datos.valor->>'frm_value_label'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                    LIMIT 1) AS as_tipo_eap_value,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_PODER_SOL_1'
                    LIMIT 1) AS nro_poder_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NRO_NOTARIA_SOL_1'
                    LIMIT 1) AS nro_notaria_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'NOMBRE_NOTARIO_SOL_1'
                    LIMIT 1) AS nombre_notario_sol,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'EXTRANGERO_PODER'
                    LIMIT 1) AS extranjero_poder,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_CI'
                    LIMIT 1) AS sol_ci,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_APELLIDO'
                    LIMIT 1) AS sol_primer_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_SEGUNDO_APELLIDO'
                    LIMIT 1) AS sol_segundo_apellido,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'SOL_PRIMER_NOMBRE'
                    LIMIT 1) AS sol_primer_nombre,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_DERIVACION'
                    LIMIT 1) AS estado_derivacion,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_LEGAL'
                    LIMIT 1) AS estado_legal,
                (SELECT datos.valor->>'frm_value'
                    FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
                    WHERE datos.valor->>'frm_campo' = 'DESCRIPCION_FUNDAMENTACION'
                    LIMIT 1) AS descripcion_fundamentacion,
                (SELECT
                    json_agg(
                        json_build_object(
                            'tipo_documento', item.value->>'col_value',
                            'id_persona', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DACO_CI_GRILLA_PROP'),
                            'complemento', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_COMP_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DACO_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DACO_CORREO'),
                            'parentesco', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DACO_PARENTESCO')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DACO'
                ) AS grilla_daco,
                (SELECT
                    json_agg(
                        json_build_object(
                            'id_persona_sip', (SELECT subitem.value->>'col_value'
                                FROM jsonb_array_elements(item.value) AS subitem
                                WHERE subitem.value->>'col_campo' = 'DAHE_IDPERSONA_GRILLA_PROP'),
                            'ci', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_CI_GRILLA_PROP'),
                            'fecha_nacimiento', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_FECHA_NAC'),
                            'nombres', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_NOMBRES'),
                            'primer_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_PRIMER_APELLIDO'),
                            'segundo_apellido', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DAHE_SEGUNDO_APELLIDO'),
                            'apellido_casada', (SELECT subitem.value->>'col_value'
                                                FROM jsonb_array_elements(item.value) AS subitem
                                                WHERE subitem.value->>'col_campo' = 'DACO_APELLIDO_CASADA'),
                            'genero', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_GENERO'),
                            'nro_celular', (SELECT subitem.value->>'col_value'
                                            FROM jsonb_array_elements(item.value) AS subitem
                                            WHERE subitem.value->>'col_campo' = 'DAHE_NRO_CELULAR'),
                            'correo', (SELECT subitem.value->>'col_value'
                                    FROM jsonb_array_elements(item.value) AS subitem
                                    WHERE subitem.value->>'col_campo' = 'DAHE_CORREO'),
                            'funcionario_gestora', (SELECT subitem.value->>'col_value'
                                        FROM jsonb_array_elements(item.value) AS subitem
                                        WHERE subitem.value->>'col_campo' = 'DAHE_FUNCIONARIO_GESTORA')
                        )
                    )
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor),
                    jsonb_array_elements(datos.valor->'frm_value') AS item
                WHERE datos.valor->>'frm_campo' = 'GRILLA_DAHE'
                ) AS grilla_dahe

            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_registrado::date >= ? AND cas_registrado::date <= ?
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.nom_usuario = c.cas_data->>'ABOGADO_REVISOR'
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'CASO_HEREDARO' THEN datos.valor->>'frm_value' END) AS caso_heredado
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            LEFT JOIN (
            SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 264
            ) fecha_hc1 ON fecha_hc1.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
            SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 266
            ) fecha_hc2 ON fecha_hc2.htc_cas_cod_id = c.cas_cod_id
            LEFT JOIN (
                SELECT htc_cas_cod_id, htc_cas_registrado FROM rmx_vys_historico_casos WHERE htc_cas_act_id = 265
            ) fecha_hc3 ON fecha_hc3.htc_cas_cod_id = c.cas_cod_id
            WHERE c.cas_data->>'ABOGADO_REVISOR' IN ($placeholders)
            AND c.cas_cod_id LIKE '%LEGAL%'
            ORDER BY c.cas_modificado DESC";

            $params = array_merge([$fecha_ini_personal, $fecha_fin_personal], $usuariosArray);
            $data = \DB::select($sql, $params);

            $titulo = "ReporteLegalUsuarios.xlsx";
            $nombreArchivo = uniqid(md5(session_id()) . $titulo) . '.xlsx';

            set_time_limit(300);

            $spreadsheet = new Spreadsheet();
            $hojaActiva = $spreadsheet->getActiveSheet();

            $styleTitulos1 = [
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ];

            $styleTitulos2 = [
                'font' => ['bold' => true, 'size' => 11, 'name' => 'Arial'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ];

            $estiloSubtitulo = [
                'font' => ['bold' => true, 'size' => 8, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'B06218']],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
            ];

            $estiloCuerpo = [
                'font' => ['bold' => false, 'size' => 8, 'name' => 'Arial'],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
            ];

            $hojaActiva->setCellValue('A2', "REPORTE LEGAL TRÁMITES POR ABOGADO");
            $hojaActiva->getStyle('A2:R2')->applyFromArray($styleTitulos1);
            $hojaActiva->mergeCells('A2:R2');

            $hojaActiva->setCellValue('A3', "Corresponde al periodo " . $fecha_ini_personal . " - " . $fecha_fin_personal);
            $hojaActiva->getStyle('A3:R3')->applyFromArray($styleTitulos2);
            $hojaActiva->mergeCells('A3:R3');

            $titulosColumnas = [
                'NRO. TRÁMITE',
                'CASO CORRELATIVO',
                'TIPO SUB SOLICITUD',
                'ACTIVIDAD',
                'NO. CASO',
                'UNIDAD ACTUAL',
                'USUARIO',
                'FECHA REGISTRO TRÁMITE',
                'FECHA DE DERIVACIÓN',
                'ASEGURADO',
                'NRO DOCUMENTO',
                'CUA',
                'REGIONAL',
                'DEPARTAMENTO',

                'ESTADO DERIVACIÓN',

                'PROCESO', //
                'SUB SOLICITUD',
                'VALIDACIÓN',

                'SUB. CLASIFICACIÓN',

                'NRO. PODER',
                'NRO. NOTARIA',
                'NOMBRE NOTÁRIO',

                'TIPO TESTIMONIO',

                'DATOS SOLICITANTE',
                'DATOS BENEFICIARIO',
                'DATOS APODERADO',

                'ESTADO',
                'AGENCIA',

                'FECHA DERIVACIÓN ASIG-LE',
                'FECHA DERIVACIÓN ABOGADO',
                'FECHA DERIVACIÓN GNL',

                'MOTIVO DE RECHAZO',
                'ESTADO LEGAL',
            ];

            for ($i = 0; $i < count($titulosColumnas); $i++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
                $hojaActiva->setCellValue($columnLetter . '5', $titulosColumnas[$i]);
                $hojaActiva->getStyle($columnLetter . '5')->applyFromArray($estiloSubtitulo);
                $hojaActiva->getColumnDimension($columnLetter)->setAutoSize(true);
            };

            $fila = 6;
            foreach ($data as $row) {
                $asegurado = trim($row->as_primer_apellido . ' ' . $row->as_segundo_apellido . ' ' . $row->as_primer_nombre . ' ' . $row->as_segundo_nombre);
                $estado = match ($row->cas_estado) {
                    'A' => 'Libre',
                    'T' => 'Tomado',
                    'E' => 'ENVIADO UCPP',
                    default => 'Sin Estado',
                };

                $hojaActiva->setCellValue('A' . $fila, $row->cas_id);
                $hojaActiva->setCellValue('B' . $fila, $row->cas_correlativo);
                $hojaActiva->setCellValue('C' . $fila, $row->nombre_proceso);
                $hojaActiva->setCellValue('D' . $fila, $row->act_orden . '-' . $row->act_descripcion);
                $hojaActiva->setCellValue('E' . $fila, $row->cas_cod_id);
                $hojaActiva->setCellValue('F' . $fila, $row->nodo_descripcion);
                $hojaActiva->setCellValue('G' . $fila, $row->nom_usuario);
                $hojaActiva->setCellValue('H' . $fila, substr($row->cas_registrado,0,18));
                $hojaActiva->setCellValue('I' . $fila, substr($row->cas_modificado,0,18));
                $hojaActiva->setCellValue('J' . $fila, $asegurado);
                $hojaActiva->setCellValue('K' . $fila, $row->as_ci);
                $hojaActiva->setCellValue('L' . $fila, $row->as_cua);
                $hojaActiva->setCellValue('M' . $fila, $row->cas_regional);
                $hojaActiva->setCellValue('N' . $fila, $row->cas_departamento);
                $hojaActiva->setCellValue('O' . $fila, $row->estado_derivacion);

                $hojaActiva->setCellValue('P' . $fila, $row->tipo_eap_legal);
                $hojaActiva->setCellValue('Q' . $fila, $row->as_sub_solicitud_value);
                $hojaActiva->setCellValue('R' . $fila, $row->as_tipo_eap_value);

                $hojaActiva->setCellValue('S' . $fila, $row->tipo_eap_legal);

                $hojaActiva->setCellValue('T' . $fila, $row->nro_poder_sol);
                $hojaActiva->setCellValue('U' . $fila, $row->nro_notaria_sol);
                $hojaActiva->setCellValue('V' . $fila, $row->nombre_notario_sol);

                $extranjeroPoder = match ($row->extranjero_poder) {
                    '1' => 'EXTRANJERO',
                    '2' => 'NACIONAL',
                    '3' => 'PROTOCOLIZADO',
                    default => 'N/A',
                };
                $hojaActiva->setCellValue('W' . $fila, $extranjeroPoder);

                $solicitante = trim($row->sol_ci . ' - ' . $row->sol_primer_apellido . ' ' . $row->sol_segundo_apellido . ' ' . $row->sol_primer_nombre);
                $hojaActiva->setCellValue('X' . $fila, $solicitante);

                $beneficiario = '';
                if (isset($row->grilla_daco)) {
                    $grillaDaco = json_decode($row->grilla_daco);
                    foreach ($grillaDaco as $index => $item) {
                        $beneficiario .= trim(
                            $item->ci . ' ' .
                            $item->nombres . ' - ' .
                            $item->primer_apellido . ' ' .
                            $item->segundo_apellido
                        );
                        if ($index < count($grillaDaco) - 1) {
                            $beneficiario .= "\n---\n";
                        }
                    }
                }
                $hojaActiva->setCellValue('Y' . $fila, $beneficiario);

                $apoderado = '';
                if (isset($row->grilla_dahe)) {
                    $grillaDahe = json_decode($row->grilla_dahe);
                    foreach ($grillaDahe as $index=> $item) {

                        $apoderado .= trim(
                            $item->ci . ' - ' .
                            $item->nombres . ' - ' .
                            $item->primer_apellido . ' ' .
                            $item->segundo_apellido
                        );
                        if ($index < count($grillaDahe) - 1) {
                            $apoderado .= "\n---\n";
                        }
                    }
                }
                $hojaActiva->setCellValue('Z' . $fila, $apoderado);

                $hojaActiva->setCellValue('AA' . $fila, $estado);
                $hojaActiva->setCellValue('AB' . $fila, $row->cas_agencia);
                $hojaActiva->setCellValue('AC' . $fila, substr($row->fecha_derivacion_legal,0,18));
                $hojaActiva->setCellValue('AD' . $fila, substr($row->fecha_derivacion_abg,0,18));
                $hojaActiva->setCellValue('AE' . $fila, substr($row->fecha_derivacion_gnl,0,18));

                if ($row->estado_legal === 'RCHZ_LEGAL') {
                    $descripcionConSaltos = wordwrap($row->descripcion_fundamentacion, 50, "\n", true); //  DESCRIPCION_FUNDAMENTACION
                    $hojaActiva->setCellValue('AF' . $fila, $descripcionConSaltos);
                } else {
                    $hojaActiva->setCellValue('AF' . $fila, '');
                }

                $hojaActiva->setCellValue('AG' . $fila,$row->estado_legal);

                $hojaActiva->getStyle('A' . $fila . ':AG' . $fila)->applyFromArray($estiloCuerpo);
                $fila++;
            }

            if (ob_get_length()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            \Log::error('Error al generar el Excel reporte: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte en Excel.'], 500);
        }
    }

    public function generarCsvTramiteXUsuario(Request $request)
    {
        try {
            $usuarios = $request["usuario"] ?? '';
            $fecha_ini_personal = $request["fecha_ini_personal"] ?? '';
            $fecha_fin_personal = $request["fecha_fin_personal"] ?? '';

            $usuariosArray = array_map('trim', explode(',', $usuarios));
            $placeholders = implode(',', array_fill(0, count($usuariosArray), '?'));

            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                c.cas_data->>'cas_departamento' as cas_departamento,
                c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_cod_id,
                c.cas_data->>'cas_nombre_caso' as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                c.cas_padre_id,
                valores.tipo_prestaciones,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_eap_legal,
                valores.caso_heredado,
                gea.est_codigo
                , (
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_registrado::date >= ? AND cas_registrado::date <= ?
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.nom_usuario = c.cas_data->>'ABOGADO_REVISOR'
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'CASO_HEREDARO' THEN datos.valor->>'frm_value' END) AS caso_heredado
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            WHERE c.cas_data->>'ABOGADO_REVISOR' IN ($placeholders)
            AND c.cas_cod_id LIKE '%LEGAL%'
            ORDER BY c.cas_modificado DESC";

            $params = array_merge([$fecha_ini_personal, $fecha_fin_personal], $usuariosArray);
            $data = \DB::select($sql, $params);

            $filename = 'Reporte_Tramite_Usuario_' . now()->format('Ymd_His') . '.csv';

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $output = fopen('php://output', 'w');

            $headers = [
                'ID Caso', 'Correlativo', 'Código Nodo', 'Descripción Nodo', 'Descripción Proceso',
                'Orden Actividad', 'Descripción Actividad', 'Primer Apellido', 'Segundo Apellido',
                'Primer Nombre', 'Segundo Nombre', 'Nombre Proceso', 'Regional', 'Departamento',
                'Estado Derivación', 'CI', 'CUA', 'Agencia', 'Código Caso', 'Usuario',
                'Fecha Registrado', 'Fecha Modificado', 'Estado', 'Días Hábiles Transcurridos'
            ];
            fputcsv($output, $headers);

            foreach ($data as $row) {
                fputcsv($output, [
                    $row->cas_id,
                    $row->cas_correlativo,
                    $row->nodo_codigo,
                    $row->nodo_descripcion,
                    $row->prc_descripcion,
                    $row->act_orden,
                    $row->act_descripcion,
                    $row->as_primer_apellido,
                    $row->as_segundo_apellido,
                    $row->as_primer_nombre,
                    $row->as_segundo_nombre,
                    $row->nombre_proceso,
                    $row->cas_regional,
                    $row->cas_departamento,
                    $row->estado_derivacion,
                    $row->as_ci,
                    $row->as_cua,
                    $row->cas_agencia,
                    $row->cas_cod_id,
                    $row->nom_usuario,
                    $row->cas_registrado,
                    $row->cas_modificado,
                    $row->cas_estado,
                    $row->dias_habiles_transcurridos
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            \Log::error('Error al generar el CSV: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte en CSV.'], 500);
        }
    }

    public function generarPendientesGNL(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] ?? '%';
        $cas_nro_caso = $request["cas_nro_caso"] ?? '%';
        $cas_gestion = $request["cas_gestion"] ?? '%';
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] ?? '';
        $fecha_fin = $request["fecha_fin"] ?? '';
        $id_departamento = $request["id_departamento"] ?? 0;
        $id_regional = $request["id_regional"] ?? 0;
        $id_agencia = $request["id_agencia"] ?? 0;
        $id_area = $request["id_area"] ?? 0;

        $sip_dep = $id_departamento ? '"id_cas_departamento":' . $id_departamento : '';
        $sip_reg = $id_regional ? ',"id_cas_regional":' . $id_regional : '';
        $sip_age = $id_agencia ? ',"id_cas_agencia":' . $id_agencia : '';
        $sip_nodo = $id_area ? 'and c.cas_nodo_id = ' . $id_area : '';

        try {
            $sql = "SELECT
                c.cas_id,
                c.cas_correlativo,
                nnn.nodo_codigo,
                nnn.nodo_descripcion,
                p.prc_data ->>'prc_descripcion' as prc_descripcion,
                a.act_data->>'act_orden' as act_orden,
                a.act_data->>'act_descripcion' as act_descripcion,
                c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                c.cas_data->>'cas_regional' as cas_regional,
                COALESCE(c.cas_data->>'cas_departamento', '') as cas_departamento,
                c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                c.cas_data->>'AS_CI' as as_ci,
                c.cas_data->>'AS_CUA' as as_cua,
                c.cas_data->>'cas_agencia' as cas_agencia,
                c.cas_data->>'ABOGADO_REVISOR' as abogado_revisor,
                c.cas_cod_id,
                REPLACE(
                    REPLACE(c.cas_data->>'cas_nombre_caso', '|', '<br>'),
                    'undefined', '-'
                ) as cas_nombre_caso,
                u.nom_usuario,
                c.cas_registrado,
                c.cas_modificado,
                c.cas_estado,
                lgp.lgp_nombre AS nombre_prestacion,
                valores.tipo_prestaciones,
                valores.tipo_eap_legal,
                valores.testimonio_judicial,
                gea.est_codigo,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario
            FROM (
                SELECT *
                FROM rmx_vys_casos
                WHERE cas_estado NOT IN ('X', 'H', 'W')
                AND cas_cod_id LIKE '%LEGAL%'
                AND cas_act_id = 266 -- Condición agregada
            ) c
            LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id AND a.act_estado = 'A'
            LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
            LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
            LEFT JOIN users u ON u.id = c.cas_usr_id
            LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id AND gea.est_estado = 'A'
            LEFT JOIN LATERAL (
                SELECT
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_PRESTACIONES' THEN datos.valor->>'frm_value_label' END) AS tipo_prestaciones,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'AS_TIPO_EAP_LEGAL' THEN datos.valor->>'frm_value_label' END) AS tipo_eap_legal,
                    MAX(CASE WHEN datos.valor->>'frm_campo' = 'TESTIMONIO_JUDICIAL' THEN datos.valor->>'frm_value' END) AS testimonio_judicial
                FROM jsonb_array_elements(c.cas_data_valores) AS datos(valor)
            ) valores ON true
            LEFT JOIN lgprestaciones lgp ON valores.tipo_prestaciones = lgp.lgp_id
            ORDER BY c.cas_modificado DESC";

            $titulo = "ReportePendientesLegalGNL.xlsx";
            $nombreArchivo = uniqid(md5(session_id()) . $titulo) . '.xlsx';

            set_time_limit(300);

            $spreadsheet = new Spreadsheet();
            $hojaActiva = $spreadsheet->getActiveSheet();

            $styleTitulos1 = array(
                'font' => array(
                    'bold' => true,
                    'size' => 12,
                    'name' => 'Arial'
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                )
            );

            $styleTitulos2 = array(
                'font' => array(
                    'bold' => true,
                    'size' => 11,
                    'name' => 'Arial'
                ),
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                )
            );

            $estiloSubtitulo = [
                'font' => [
                    'bold' => true,
                    'size' => 8,
                    'name' => 'Arial'
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => [
                        'rgb' => 'c5d9f1'
                    ]
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ];

            $estiloCuerpo = [
                'font' => [
                    'bold' => false,
                    'size' => 8,
                    'name' => 'Arial'
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ];

            $hojaActiva->setCellValue('A2', "REPORTE PENDIENTES LEGAL GNL");
            $hojaActiva->getStyle('A2:R2')->applyFromArray($styleTitulos1);
            $hojaActiva->mergeCells('A2:R2');

            $hojaActiva->setCellValue('A3', "TRAMITESIP");
            $hojaActiva->getStyle('A3:R3')->applyFromArray($styleTitulos2);
            $hojaActiva->mergeCells('A3:R3');

            $titulosColumnas = array(
                'Nro. Trámite ',
                'Caso Correlativo',
                'Tipo Sub Solicitud',
                'Actividad',
                'No. Caso',
                'Unidad Actual',
                'Usuario',
                'Fecha de Asignación',
                'Fecha de Derivación',
                'Asegurado',
                'Nro Documento',
                'Cua',
                'Regional',
                'Departamento',
                'Estado Derivacion',
                'Sub. Clasificacion',
                'Estado',
                'Agencia',
                'De usuario',
            );

            for ($i = 0; $i < count($titulosColumnas); $i++) {
                $hojaActiva->setCellValue(chr(65 + $i) . '5', $titulosColumnas[$i]); // Set column titles
                $hojaActiva->getStyle(chr(65 + $i) . '5')->applyFromArray($estiloSubtitulo);
                $hojaActiva->getColumnDimension(chr(65 + $i))->setAutoSize(true);
            };

            $fila = 6;
            $page = 0;
            $pageSize = 1000;

            do {
                $offset = $page * $pageSize;
                $sqlPaginated = $sql . " LIMIT $pageSize OFFSET $offset";
                $data = \DB::select($sqlPaginated);

                foreach ($data as $row) {
                    $asegurado = trim($row->as_primer_apellido . ' ' . $row->as_segundo_apellido . ' ' . $row->as_primer_nombre . ' ' . $row->as_segundo_nombre);

                    $estado = match ($row->cas_estado) {
                        'A' => 'Libre',
                        'T' => 'Tomado',
                        'E' => 'ENVIADO UCPP',
                        default => 'Sin Estado',
                    };

                    $hojaActiva->setCellValue('A' . $fila, $row->cas_id);
                    $hojaActiva->setCellValue('B' . $fila, $row->cas_correlativo);
                    $hojaActiva->setCellValue('C' . $fila, $row->nombre_proceso);
                    $hojaActiva->setCellValue('D' . $fila, $row->act_orden . '-' . $row->act_descripcion);
                    $hojaActiva->setCellValue('E' . $fila, $row->cas_cod_id);
                    $hojaActiva->setCellValue('F' . $fila, $row->nodo_descripcion);
                    $hojaActiva->setCellValue('G' . $fila, $row->nom_usuario);
                    $hojaActiva->setCellValue('H' . $fila, $row->cas_registrado);
                    $hojaActiva->setCellValue('I' . $fila, $row->cas_modificado);
                    $hojaActiva->setCellValue('J' . $fila, $asegurado);
                    $hojaActiva->setCellValue('K' . $fila, $row->as_ci);
                    $hojaActiva->setCellValue('L' . $fila, $row->as_cua);
                    $hojaActiva->setCellValue('M' . $fila, $row->cas_regional);
                    $hojaActiva->setCellValue('N' . $fila, $row->cas_departamento);
                    $hojaActiva->setCellValue('O' . $fila, $row->estado_derivacion);
                    $hojaActiva->setCellValue('P' . $fila, $row->tipo_eap_legal);
                    $hojaActiva->setCellValue('Q' . $fila, $row->est_codigo);
                    $hojaActiva->setCellValue('R' . $fila, $row->cas_agencia);
                    $hojaActiva->setCellValue('S' . $fila, $row->antepenultimo_usuario);

                    $hojaActiva->getStyle('A' . $fila . ':S' . $fila)->applyFromArray($estiloCuerpo);
                    $fila++;
                }

                $page++;
            } while (count($data) > 0);
            try {
                if (ob_get_length()) {
                    ob_end_clean();
                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            } catch (\Exception $e) {
                \Log::error('Error al general el Excel: ' . $e->getMessage());
                return response()->json(['error' => 'Error al generar el archivo Excel.'], 500);
            }

            exit;
        } catch (\Exception $e) {
            \Log::error('Error en generarExcel: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
            return response()->json(['error' => 'Error al generar el reporte.', 'details' => $e->getMessage()], 500);
        }
    }
}
