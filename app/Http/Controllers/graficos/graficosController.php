<?php

namespace App\Http\Controllers\graficos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class graficosController extends Controller
{
    const OCCIDENTE = ['LA PAZ', 'ORURO', 'POTOSI'];
    const ORIENTE = ['SANTA CRUZ', 'BENI', 'PANDO'];
    const VALLES = ['COCHABAMBA', 'CHUQUISACA', 'TARIJA'];

    const CODES = ["RMIN", "MAHER", "PM", "INV", "PAGCC", "JUB", "GFU", "JUB1582"];

    const FECHA_INICIO_TRAMITE = '2024-05-16';

    public function listadoProcesos(Request $request)
    {
        $success = ["code" => 200, "mensaje" => 'OK'];
        $error = ["message" => "Error de instancia", "code" => 500];

        try {
            $data_procesos = Cache::remember('listado_procesos', 60, function () {
                return \DB::table('rmx_vys_procesos')
                    ->select(
                        'prc_id',
                        \DB::raw("prc_data->>'prc_codigo' as codigo"),
                        \DB::raw("prc_data->>'prc_descripcion' as descripcion")
                    )
                    ->where('prc_estado', '<>', 'X')
                    ->get();
            });

            return response()->json(["data" => $data_procesos, "codigoRespuesta" => $success]);
        } catch (\Exception $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function cantidadTramite(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $selectedIds = $request->input('selectedIds');
        $selectedCodes = $request->input('selectedCodes');

        $hoy = $request->input('fechaHoy');

        $fecha_inicial = $request->input('fechaInicial');
        $fecha_final = $request->input('fechaFinal');

        try {
            $resultados = [];

            foreach ($selectedCodes as $code) {
                if (!is_null($fecha_inicial) && !is_null($fecha_final)) {
                    $total = DB::selectOne("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE rvc.cas_data->>'cas_cod_id' LIKE ?
                        AND rvc.cas_registrado >= ?::date
                        AND rvc.cas_registrado < ?::date + INTERVAL '1 day'
                    ", ["$code%", $fecha_inicial, $fecha_final]);

                } elseif (!is_null($hoy) && $hoy !== '') {
                    $total = DB::selectOne("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE rvc.cas_data->>'cas_cod_id' LIKE ?
                        AND rvc.cas_registrado >= ?::date
                        AND rvc.cas_registrado < ?::date + INTERVAL '1 day'
                    ", ["$code%", $hoy, $hoy]);
                } else {
                    $total = DB::selectOne("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE rvc.cas_data->>'cas_cod_id' LIKE ?
                        AND rvc.cas_registrado >= date_trunc('month', current_date)
                        AND rvc.cas_registrado < date_trunc('month', current_date) + INTERVAL '1 month'
                    ", ["$code%"]);
                }

                $resultados[] = [
                    "codigo" => $code,
                    "total" => $total->total ?? 0
                ];
            }

            $data = $resultados;

            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (\Exception $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function datosGenerales(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $dataHoy = DB::select("
                SELECT COUNT(*) AS total
                FROM rmx_vys_casos rvc
                WHERE cas_registrado >= CURRENT_DATE
                AND cas_registrado < CURRENT_DATE + INTERVAL '1 day'
                AND cas_data->>'cas_cod_id' IS NOT NULL;
            ");

            $occidenteLista = implode("', '", self::OCCIDENTE);

            $dataOccidente = DB::select("
                SELECT COUNT(*) AS total_occidente
                FROM rmx_vys_casos rvc
                WHERE cas_data->>'cas_regional' IN ('$occidenteLista')
                AND cas_registrado BETWEEN DATE_TRUNC('month', CURRENT_DATE) AND CURRENT_DATE
            ");

            $orienteLista = implode("', '", self::ORIENTE);

            $dataOriente = DB::select("
                SELECT COUNT(*) AS total_oriente
                FROM rmx_vys_casos rvc
                WHERE (cas_data->>'cas_regional' IN ('$orienteLista'))
                AND cas_registrado BETWEEN DATE_TRUNC('month', CURRENT_DATE) AND CURRENT_DATE
            ");

            $vallesLista = implode("', '", self::VALLES);

            $dataValles = DB::select("
                SELECT COUNT(*) AS total_valles
                FROM rmx_vys_casos rvc
                WHERE (cas_data->>'cas_regional' IN ('$vallesLista'))
                AND cas_registrado BETWEEN DATE_TRUNC('month', CURRENT_DATE) AND CURRENT_DATE
            ");

        return response()->json([
            "data" => $dataHoy[0]->total,
            "data_occidente" => $dataOccidente[0]->total_occidente,
            "data_oriente" => $dataOriente[0]->total_oriente,
            "data_valles" => $dataValles[0]->total_valles,
            "codigoRespuesta" => $success
        ]);

        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function metricasDepartamentos(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $hoyDep = $request->input('fechaHoyDep');
        $fecha_inicialDep = $request->input('fechaInicialDep');
        $fecha_finalDep = $request->input('fechaFinalDep');
        $selectedCodes = getCodigoTramites();

        try {
            $departamentos = ['LA PAZ', 'ORURO', 'POTOSI', 'COCHABAMBA', 'CHUQUISACA', 'TARIJA', 'SANTA CRUZ', 'BENI', 'PANDO'];
            $dataDepartamentos = [];

            foreach ($departamentos as $departamento) {
                $total = 0;

                foreach ($selectedCodes as $code) {
                    if (!is_null($fecha_inicialDep) && !is_null($fecha_finalDep)) {
                        $data = DB::selectOne("
                            SELECT COUNT(*) AS total
                            FROM rmx_vys_casos rvc
                            WHERE (cas_data->>'cas_departamento' LIKE ?)
                            AND (cas_data->>'cas_cod_id' LIKE ?)
                            AND DATE(cas_registrado) BETWEEN DATE(?) AND DATE(?)
                        ", ["$departamento%", "$code%", $fecha_inicialDep, $fecha_finalDep]);
                    } elseif (!is_null($hoyDep)) {
                        $data = DB::selectOne("
                            SELECT COUNT(*) AS total
                            FROM rmx_vys_casos rvc
                            WHERE (cas_data->>'cas_departamento' LIKE ?)
                            AND (cas_data->>'cas_cod_id' LIKE ?)
                            AND DATE(cas_registrado) = DATE(?)
                        ", ["$departamento%", "$code%", $hoyDep]);
                    } else {
                        $data = DB::selectOne("
                            SELECT COUNT(*) AS total
                            FROM rmx_vys_casos rvc
                            WHERE (cas_data->>'cas_departamento' LIKE ?)
                            AND (cas_data->>'cas_cod_id' LIKE ?)
                            AND DATE(cas_registrado) >= DATE_TRUNC('month', CURRENT_DATE)
                            AND DATE(cas_registrado) < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month'
                        ", ["$departamento%", "$code%"]);
                    }

                    $total += $data->total ?? 0;
                }

                $dataDepartamentos[$departamento] = $total;
            }

            return response()->json([
                "data" => $dataDepartamentos,
                "codigoRespuesta" => $success
            ]);
        } catch (\Exception $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function metricasOccidente(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            $dataOccidente = DB::select("
                    SELECT *
                    FROM rmx_vys_casos rvc
                    WHERE (cas_data->>'cas_regional' LIKE 'LA PAZ%'
                        OR cas_data->>'cas_regional' LIKE 'ORURO%'
                        OR cas_data->>'cas_regional' LIKE 'POTOSI%')
                        AND date_trunc('month', cas_registrado) = date_trunc('month', current_date)
                ");

             $cantidadOccidente = DB::select("
                                SELECT
                                    cas_regional,
                                    COUNT(*) AS total
                                FROM (
                                    SELECT
                                        cas_data->>'cas_regional' AS cas_regional
                                    FROM
                                        rmx_vys_casos rvc
                                    WHERE
                                        (cas_data->>'cas_regional' LIKE 'LA PAZ%'
                                        OR cas_data->>'cas_regional' LIKE 'ORURO%'
                                        OR cas_data->>'cas_regional' LIKE 'POTOSI%')
                                        AND date_trunc('month', cas_registrado) = date_trunc('month', current_date)
                                ) AS subquery
                                GROUP BY
                                    cas_regional
                                UNION ALL
                                SELECT
                                    'TOTAL' AS cas_regional,
                                    COUNT(*) AS total
                                FROM
                                    rmx_vys_casos rvc
                                WHERE
                                    (cas_data->>'cas_regional' LIKE 'LA PAZ%'
                                    OR cas_data->>'cas_regional' LIKE 'ORURO%'
                                    OR cas_data->>'cas_regional' LIKE 'POTOSI%')
                                    AND date_trunc('month', cas_registrado) = date_trunc('month', current_date)
                                ");

                $mensaje = "Datos de la regional occidente";
                $results = estado::getCasRegionalCounts($mensaje);

            return response()->json([
                "data" => $dataOccidente,
                "cantidad" => $cantidadOccidente,
                "codigoRespuesta" => $success
            ]);

        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }

    }

    public function etapaPreviaCalculo(Request $request)
    {
        // Tramites pendientes de calculo al 25/11/2024 e indice de variacion respecto al dia anterior (t -1 22/11/2024)
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);

        $requestData = $request->all();

        $accion = $requestData['comparativa'] ?? null;

        $selectedIds = $requestData['selectedIds'] ?? null;
        $selectedCodes = $requestData['selectedCodes'] ?? null;

        $hoy = $requestData['fechaHoy'] ?? null;
        $fecha_inicial = $requestData['fechaInicial'] ?? null;
        $fecha_final = $requestData['fechaFinal'] ?? null;

        try {
            $occidenteLista = implode("', '", self::OCCIDENTE);
            $orienteLista = implode("', '", self::ORIENTE);
            $vallesLista = implode("', '", self::VALLES);

            if (!is_null($fecha_inicial) && !is_null($fecha_final)) {
                $dataOccidente = DB::select("
                    SELECT
                        COUNT(*) AS total_occidente_count
                    FROM
                        rmx_vys_historico_casos h
                    INNER JOIN
                        rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                    INNER JOIN
                        rmx_vys_actividades a ON c.cas_act_id = a.act_id
                    WHERE
                        htc_cas_nodo_id = 82
                        AND htc_cas_estado = 'E'
                        AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                        AND (" . implode(' OR ', array_map(function($code) {
                            return "htc_cas_cod_id LIKE '$code%'";
                        }, $selectedCodes)) . ")
                        AND DATE(htc_cas_registrado) BETWEEN DATE(?) AND DATE(?)
                        AND act_data->>'act_orden' = '61'
                        AND c.cas_data->>'cas_regional' IN ('$occidenteLista');
                ", [$fecha_inicial, $fecha_final]);

            } elseif (!is_null($hoy) && $hoy !== '') {
                $dataOccidente = DB::select("
                    SELECT
                        COUNT(*) AS total_occidente_count
                    FROM
                        rmx_vys_historico_casos h
                    INNER JOIN
                        rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                    INNER JOIN
                        rmx_vys_actividades a ON c.cas_act_id = a.act_id
                    WHERE
                        htc_cas_nodo_id = 82
                        AND htc_cas_estado = 'E'
                        AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                        AND (" . implode(' OR ', array_map(function($code) {
                            return "htc_cas_cod_id LIKE '$code%'";
                        }, $selectedCodes)) . ")
                        AND htc_cas_registrado = ?
                        AND act_data->>'act_orden' = '61'
                        AND (c.cas_data->>'cas_regional' IN ('$occidenteLista'));
                ", [$hoy]);
            } else {
                $codes = self::CODES;
                $codesToUse = !empty($selectedCodes) ? $selectedCodes : $codes;

                $dataOccidente = DB::select("
                    SELECT COUNT(*) AS total_occidente_count
                    FROM rmx_vys_historico_casos h
                    INNER JOIN rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                    INNER JOIN rmx_vys_actividades a ON c.cas_act_id = a.act_id
                    WHERE
                        htc_cas_nodo_id = 82
                        AND htc_cas_estado = 'E'
                        AND htc_cas_registrado BETWEEN '" . self::FECHA_INICIO_TRAMITE . "' AND CURRENT_DATE
                        AND (" . implode(' OR ', array_map(function($code) {
                            return "htc_cas_cod_id LIKE '$code%'";
                        }, $codesToUse)) . ")
                        AND act_data->>'act_orden' = '61'
                        AND (c.cas_data->>'cas_regional' IN ('$occidenteLista'))
                ");

            }

            $dataOriente = DB::select("
                SELECT
                    COUNT(*) AS total_oriente_count
                FROM
                    rmx_vys_historico_casos h
                INNER JOIN
                    rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                INNER JOIN
                    rmx_vys_actividades a ON c.cas_act_id = a.act_id
                WHERE
                    htc_cas_nodo_id = 82
                    AND htc_cas_estado = 'E'
                    AND (
                        htc_cas_cod_id LIKE 'RMIN%'
                        OR htc_cas_cod_id LIKE 'MAHER%'
                        OR htc_cas_cod_id LIKE 'PM%'
                        OR htc_cas_cod_id LIKE 'INV%'
                        OR htc_cas_cod_id LIKE 'PAGCC%'
                        OR htc_cas_cod_id LIKE 'JUB%'
                        OR htc_cas_cod_id LIKE 'GFU%'
                        OR htc_cas_cod_id LIKE 'JUB1582%'
                    )
                    AND htc_cas_registrado BETWEEN '" . self::FECHA_INICIO_TRAMITE . "' AND CURRENT_DATE
                    AND a.act_data->>'act_orden' = '61'
                    AND (c.cas_data->>'cas_regional' IN ('$orienteLista'));
                ");

            $dataValles = DB::select("
                SELECT
                    COUNT(*) AS total_valles_count
                FROM
                    rmx_vys_historico_casos h
                INNER JOIN
                    rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                INNER JOIN
                    rmx_vys_actividades a ON c.cas_act_id = a.act_id
                WHERE
                    htc_cas_nodo_id = 82
                    AND htc_cas_estado = 'E'
                    AND (
                        htc_cas_cod_id LIKE 'RMIN%'
                        OR htc_cas_cod_id LIKE 'MAHER%'
                        OR htc_cas_cod_id LIKE 'PM%'
                        OR htc_cas_cod_id LIKE 'INV%'
                        OR htc_cas_cod_id LIKE 'PAGCC%'
                        OR htc_cas_cod_id LIKE 'JUB%'
                        OR htc_cas_cod_id LIKE 'GFU%'
                        OR htc_cas_cod_id LIKE 'JUB1582%'
                    )
                    AND htc_cas_registrado BETWEEN '" . self::FECHA_INICIO_TRAMITE . "' AND CURRENT_DATE
                    AND a.act_data->>'act_orden' = '61'
                    AND (c.cas_data->>'cas_regional' IN ('$vallesLista'))
                ");

                    $ayer = date('Y-m-d', strtotime('-1 day', strtotime($hoy)));

                        $dataOccidenteAyer = DB::select("
                            SELECT
                                COUNT(*) AS total_occidente_count
                            FROM
                                rmx_vys_historico_casos h
                            INNER JOIN
                                rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                            INNER JOIN
                                rmx_vys_actividades a ON c.cas_act_id = a.act_id
                            WHERE
                                htc_cas_nodo_id = 82
                                AND htc_cas_estado = 'E'
                                AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                                AND (
                                    htc_cas_cod_id LIKE 'RMIN%'
                                    OR htc_cas_cod_id LIKE 'MAHER%'
                                    OR htc_cas_cod_id LIKE 'PM%'
                                    OR htc_cas_cod_id LIKE 'INV%'
                                    OR htc_cas_cod_id LIKE 'PAGCC%'
                                    OR htc_cas_cod_id LIKE 'JUB%'
                                    OR htc_cas_cod_id LIKE 'GFU%'
                                    OR htc_cas_cod_id LIKE 'JUB1582%'
                                )
                                AND htc_cas_registrado = ?
                                AND act_data->>'act_orden' = '61'
                                AND (c.cas_data->>'cas_regional' IN ('$occidenteLista'));
                        ", [$ayer]);

                    $diferenciaOccidente = $dataOccidente[0]->total_occidente_count - $dataOccidenteAyer[0]->total_occidente_count;

                $dataOrienteAyer = DB::select("
                    SELECT
                        COUNT(*) AS total_oriente_count
                    FROM
                        rmx_vys_historico_casos h
                    INNER JOIN
                        rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                    INNER JOIN
                        rmx_vys_actividades a ON c.cas_act_id = a.act_id
                    WHERE
                        htc_cas_nodo_id = 82
                        AND htc_cas_estado = 'E'
                        AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                        AND (
                            htc_cas_cod_id LIKE 'RMIN%'
                            OR htc_cas_cod_id LIKE 'MAHER%'
                            OR htc_cas_cod_id LIKE 'PM%'
                            OR htc_cas_cod_id LIKE 'INV%'
                            OR htc_cas_cod_id LIKE 'PAGCC%'
                            OR htc_cas_cod_id LIKE 'JUB%'
                            OR htc_cas_cod_id LIKE 'GFU%'
                            OR htc_cas_cod_id LIKE 'JUB1582%'
                        )
                        AND htc_cas_registrado = ?
                        AND act_data->>'act_orden' = '61'
                        AND (c.cas_data->>'cas_regional' IN ('$orienteLista'));
                ", [$ayer]);

            $diferenciaOriente = $dataOriente[0]->total_oriente_count - $dataOrienteAyer[0]->total_oriente_count;

                $dataVallesAyer = DB::select("
                    SELECT
                        COUNT(*) AS total_valles_count
                    FROM
                        rmx_vys_historico_casos h
                    INNER JOIN
                        rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                    INNER JOIN
                        rmx_vys_actividades a ON c.cas_act_id = a.act_id
                    WHERE
                        htc_cas_nodo_id = 82
                        AND htc_cas_estado = 'E'
                        AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                        AND (
                            htc_cas_cod_id LIKE 'RMIN%'
                            OR htc_cas_cod_id LIKE 'MAHER%'
                            OR htc_cas_cod_id LIKE 'PM%'
                            OR htc_cas_cod_id LIKE 'INV%'
                            OR htc_cas_cod_id LIKE 'PAGCC%'
                            OR htc_cas_cod_id LIKE 'JUB%'
                            OR htc_cas_cod_id LIKE 'GFU%'
                            OR htc_cas_cod_id LIKE 'JUB1582%'
                        )
                        AND htc_cas_registrado = ?
                        AND act_data->>'act_orden' = '61'
                        AND (c.cas_data->>'cas_regional' IN ('$vallesLista'));
                ", [$ayer]);

            $diferenciaValles = $dataValles[0]->total_valles_count - $dataVallesAyer[0]->total_valles_count;

            return response()->json([
                "dataOccidente" => $dataOccidente,
                "dataOriente" => $dataOriente,
                "dataValles" => $dataValles,

                "diferenciaOccidenteAyer" => $diferenciaOccidente ?? null,
                "diferenciaOrienteAyer" => $diferenciaOriente ?? null,
                "diferenciaVallesAyer" => $diferenciaValles ?? null,

                "codigoRespuesta" => $success
            ]);

        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function estadoAvanceTramite(Request $request)
    {
        // REGISTRO DE TRAMITE - 10,20,30
        // VERIFICACION CVEAP  - 40
        // OBS. UCPP - 41
        // NOTIFICACION CVEAP - 50
        // VALIDACION ANALISTA - 61
        // PENDIENTES DE CALCULO SIN VALOR DE COUTA
        // PENDIENTES DE CALCULO CON VALOR DE CUOTA - 61, 50
        // PENDIENTES DE REVISION/APROBACION Y FIRMA - 67, 67 , 69
        // CONTRATOS POR NOTIFICAR - 70, 75
        // CONTRATOS FIRMADOS - 80
        // ENMIENDA - 100

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            $occidenteLista = implode("', '", self::OCCIDENTE);
            $orienteLista = implode("', '", self::ORIENTE);
            $vallesLista = implode("', '", self::VALLES);

            $dataEstadoAvanceOccidente =
                DB::select("
                    SELECT
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('10', '20', '30')) AS registro_de_tramite,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '40') AS verificacion_cveap,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '41') AS obs_ucpp,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '50') AS notificacion_cveap,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '61') AS validacion_analista,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '61' AND htc_cas_estado = 'E') AS pendientes_de_calculo_sin_valor_de_cuota,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('61', '50')) AS pendientes_de_calculo_con_valor_de_cuota,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('67', '69')) AS pendientes_de_revision_aprobacion_y_firma,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('70', '75')) AS contratos_por_notificar,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '80') AS contratos_firmados,
                        COUNT(*) FILTER (WHERE act_data->>'act_orden' = '100') AS enmienda
                    FROM
                        rmx_vys_historico_casos h
                    INNER JOIN
                        rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                    INNER JOIN
                        rmx_vys_actividades a ON c.cas_act_id = a.act_id
                    WHERE
                        htc_cas_nodo_id = 82
                        AND htc_cas_estado = 'E'
                        AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                        AND (
                            htc_cas_cod_id LIKE 'RMIN%'
                            OR htc_cas_cod_id LIKE 'MAHER%'
                            OR htc_cas_cod_id LIKE 'PM%'
                            OR htc_cas_cod_id LIKE 'INV%'
                            OR htc_cas_cod_id LIKE 'PAGCC%'
                            OR htc_cas_cod_id LIKE 'JUB%'
                            OR htc_cas_cod_id LIKE 'GFU%'
                            OR htc_cas_cod_id LIKE 'JUB1582%'
                        )
                        AND htc_cas_registrado BETWEEN '" . self::FECHA_INICIO_TRAMITE . "' AND CURRENT_DATE
                        AND (c.cas_data->>'cas_regional' IN ('$occidenteLista'));
                ");

                $dataEstadoAvanceOriente =
                    DB::select("
                            SELECT
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('10', '20', '30')) AS registro_de_tramite,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '40') AS verificacion_cveap,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '41') AS obs_ucpp,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '50') AS notificacion_cveap,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '61') AS validacion_analista,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '61' AND htc_cas_estado = 'E') AS pendientes_de_calculo_sin_valor_de_cuota,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('61', '50')) AS pendientes_de_calculo_con_valor_de_cuota,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('67', '69')) AS pendientes_de_revision_aprobacion_y_firma,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('70', '75')) AS contratos_por_notificar,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '80') AS contratos_firmados,
                                COUNT(*) FILTER (WHERE act_data->>'act_orden' = '100') AS enmienda
                            FROM
                                rmx_vys_historico_casos h
                            INNER JOIN
                                rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                            INNER JOIN
                                rmx_vys_actividades a ON c.cas_act_id = a.act_id
                            WHERE
                                htc_cas_nodo_id = 82
                                AND htc_cas_estado = 'E'
                                AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                                AND (
                                    htc_cas_cod_id LIKE 'RMIN%'
                                    OR htc_cas_cod_id LIKE 'MAHER%'
                                    OR htc_cas_cod_id LIKE 'PM%'
                                    OR htc_cas_cod_id LIKE 'INV%'
                                    OR htc_cas_cod_id LIKE 'PAGCC%'
                                    OR htc_cas_cod_id LIKE 'JUB%'
                                    OR htc_cas_cod_id LIKE 'GFU%'
                                    OR htc_cas_cod_id LIKE 'JUB1582%'
                                )
                                AND htc_cas_registrado BETWEEN '" . self::FECHA_INICIO_TRAMITE . "' AND CURRENT_DATE
                                AND (c.cas_data->>'cas_regional' LIKE 'SANTA CRUZ%'
                                    OR c.cas_data->>'cas_regional' LIKE 'BENI%'
                                    OR c.cas_data->>'cas_regional' LIKE 'PANDO%');
                        ");

                $dataEstadoAvanceValles =
                DB::select("
                        SELECT
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('10', '20', '30')) AS registro_de_tramite,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '40') AS verificacion_cveap,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '41') AS obs_ucpp,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '50') AS notificacion_cveap,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '61') AS validacion_analista,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '61' AND htc_cas_estado = 'E') AS pendientes_de_calculo_sin_valor_de_cuota,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('61', '50')) AS pendientes_de_calculo_con_valor_de_cuota,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('67', '69')) AS pendientes_de_revision_aprobacion_y_firma,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' IN ('70', '75')) AS contratos_por_notificar,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '80') AS contratos_firmados,
                            COUNT(*) FILTER (WHERE act_data->>'act_orden' = '100') AS enmienda
                        FROM
                            rmx_vys_historico_casos h
                        INNER JOIN
                            rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                        INNER JOIN
                            rmx_vys_actividades a ON c.cas_act_id = a.act_id
                        WHERE
                            htc_cas_nodo_id = 82
                            AND htc_cas_estado = 'E'
                            AND date(htc_cas_registrado) <> date(htc_cas_modificado)
                            AND (
                                htc_cas_cod_id LIKE 'RMIN%'
                                OR htc_cas_cod_id LIKE 'MAHER%'
                                OR htc_cas_cod_id LIKE 'PM%'
                                OR htc_cas_cod_id LIKE 'INV%'
                                OR htc_cas_cod_id LIKE 'PAGCC%'
                                OR htc_cas_cod_id LIKE 'JUB%'
                                OR htc_cas_cod_id LIKE 'GFU%'
                                OR htc_cas_cod_id LIKE 'JUB1582%'
                            )
                            AND htc_cas_registrado BETWEEN '2024-05-16' AND CURRENT_DATE
                            AND (c.cas_data->>'cas_regional' IN ('$vallesLista'));
                    ");

            return response()->json([
                "dataEstadoAvanceOccidente" => $dataEstadoAvanceOccidente,
                "dataEstadoAvanceOriente" => $dataEstadoAvanceOriente,
                "dataEstadoAvanceValles" => $dataEstadoAvanceValles,
                "codigoRespuesta" => $success
            ]);

        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function listadoTramitesCasos(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $actividades = [];
        $departamento = [];
        $desEstado = [];
        $funcionario = [];
        $gestion = [];
        $mes = [];
        $nroCaso = [];
        $nodo = [];
        $estado = [];
        $situacion = [];
        $orden = [];

        try {
            $data = \DB::select("SELECT c.cas_data->>'cas_nro_caso' as \"NoCaso\",
					p.prc_data->>'prc_descripcion' as \"Proceso\",
					concat(a.act_data->>'act_orden', ' - ', a.act_data->>'act_descripcion') as \"Actividad\",
					no.nodo_descripcion as \"Nodo\",
					u.name as \"Funcionario\",
                    c.cas_data->>'cas_departamento' as \"Departamento\",
                    c.cas_data->>'cas_gestion' as \"Gestión\",
                    (a.act_data->>'act_orden')::int as \"orden\",
					EXTRACT(MONTH FROM c.cas_registrado)::integer as \"Mes\",
                    CASE
                    WHEN c.cas_estado = 'T' THEN 'Tomado'
                    WHEN c.cas_estado = 'A' THEN 'Libre'
                    WHEN c.cas_estado = 'E' THEN 'Enviado'
                    WHEN c.cas_estado = 'H' THEN 'Archivado'
                    END as \"Situación\",
                    concat(g.est_orden, '-' , g.est_codigo) as \"Estado\", g.est_descripcion as \"DescEstado\",
                    c.cas_data->>'ESTADO_DERIVACION' as \"SUB Estado\"
				FROM rmx_vys_casos c
					INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					left JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id and c.cas_usr_id = n.usn_user_id
                    left JOIN rmx_vys_nodos no ON no.nodo_id = a.act_nodo_id
                    left JOIN users u ON u.id = c.cas_usr_id
                    left join gp_estados_avance g on g.est_id = a.act_est_id
				WHERE c.cas_estado not in ( 'X', 'W')
				ORDER BY \"Proceso\", \"Estado\" ASC ");

            foreach ($data as $item) {
                $actividad[] = $item->Actividad;
                $departamento[] = $item->Departamento;
                $desEstado[] = $item->DescEstado;
                $funcionario[] = $item->Funcionario;
                $gestion[] = $item->Gestión;
                $mes[] = $item->Mes;
                $nroCaso[] = $item->NoCaso;
                $nodo[] = $item->Nodo;
                $estado[] = $item->Estado;
                $situacion[] = $item->Situación;
                $orden[] = $item->orden;
            }

            $actividadCount = array_count_values($actividad);

            $departamentoCount = array_count_values($departamento);

            $desEstado = array_count_values($desEstado);

            $funcionario = array_filter($funcionario, function($value) {
                return is_string($value) || is_int($value);
            });
            $funcionarioCount = array_count_values($funcionario);

            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function casosPendientesBandeja(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $payload = $request->query('gerencias');

            if ($payload !== null) {
                $selectedIds = json_decode($payload, true)['selectedIds'];
                $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
                $data = \DB::select("SELECT c.cas_data->>'cas_nro_caso' as NoCaso,
                        p.prc_data->>'prc_descripcion' as Proceso,
                        concat(a.act_data->>'act_orden', ' - ', a.act_data->>'act_descripcion') as Actividad,
                        concat(no.nodo_descripcion, ' - ', a.act_data->>'act_descripcion') as gtalento,
                        no.nodo_descripcion as Nodo,
                        u.name as Funcionario,
                        c.cas_data->>'cas_departamento' as Departamento,
                        c.cas_data->>'cas_gestion' as Gestión,
                        (a.act_data->>'act_orden')::int as orden,
                        EXTRACT(MONTH FROM c.cas_registrado)::integer as Mes,
                        CASE
                        WHEN c.cas_estado = 'T' THEN 'Tomado'
                        WHEN c.cas_estado = 'A' THEN 'Libre'
                        WHEN c.cas_estado = 'E' THEN 'Enviado'
                        WHEN c.cas_estado = 'H' THEN 'Archivado'
                        END as Situación,
                        concat(g.est_estado, '-' , g.est_codigo) as Estado, g.est_descripcion as DescEstado,
                        c.cas_data->>'ESTADO_DERIVACION' as SubEstado
                    FROM rmx_vys_casos c
                        INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                        INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                        left JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id and c.cas_usr_id = n.usn_user_id
                        left JOIN rmx_vys_nodos no ON no.nodo_id = a.act_nodo_id
                        left JOIN users u ON u.id = c.cas_usr_id
                        left join gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado not in ( 'X', 'W')
                    AND c.cas_data->>'EM_AREA_DEPENDIENTE' in ($placeholders)
                    ORDER BY orden, Estado desc", $selectedIds);
            } else {
                $data = \DB::select("SELECT c.cas_data->>'cas_nro_caso' as NoCaso,
                        p.prc_data->>'prc_descripcion' as Proceso,
                        concat(a.act_data->>'act_orden', ' - ', a.act_data->>'act_descripcion') as Actividad,
                        concat(no.nodo_descripcion, ' - ', a.act_data->>'act_descripcion') as gtalento,
                        no.nodo_descripcion as Nodo,
                        u.name as Funcionario,
                        c.cas_data->>'cas_departamento' as Departamento,
                        c.cas_data->>'cas_gestion' as Gestión,
                        (a.act_data->>'act_orden')::int as orden,
                        EXTRACT(MONTH FROM c.cas_registrado)::integer as Mes,
                        CASE
                        WHEN c.cas_estado = 'T' THEN 'Tomado'
                        WHEN c.cas_estado = 'A' THEN 'Libre'
                        WHEN c.cas_estado = 'E' THEN 'Enviado'
                        WHEN c.cas_estado = 'H' THEN 'Archivado'
                        END as Situación,
                        concat(g.est_estado, '-' , g.est_codigo) as Estado, g.est_descripcion as DescEstado,
                        c.cas_data->>'ESTADO_DERIVACION' as SubEstado
                    FROM rmx_vys_casos c
                        INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                        INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                        left JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id and c.cas_usr_id = n.usn_user_id
                        left JOIN rmx_vys_nodos no ON no.nodo_id = a.act_nodo_id
                        left JOIN users u ON u.id = c.cas_usr_id
                        left join gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado not in ( 'X', 'W')
                    ORDER BY orden, Estado desc");
            }

            $gtalento = [];

            foreach ($data as $item) {
                $gtalento[] = $item->gtalento;
            }

            $gtalentoCount = array_count_values($gtalento);

            return response()->json([ "data" => $gtalentoCount, "codigoRespuesta" => $success ]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function ultimosRegistros()
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $data = \DB::select("
                    select rvc.cas_id , rvc.cas_cod_id, rvc.cas_data->>'NOMBRE_PROCESO' as nom_proceso , cas_data_valores, rvc.cas_registrado
                    from rmx_vys_casos rvc
                    where rvc.cas_cod_id IS NOT null
                    and rvc.cas_data_valores <> '[]'
                    order by rvc.cas_id desc
                    limit 6
                ");
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function datosComplementarios()
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $totalUsers = \DB::select("
                    select count(*) as total
                    from users
                    where users.status = 'A'
                ");

            $totalRegional = DB::select("
                    select count(*) as total
                    from gp_regional
                    where gp_regional.doc_estado = 'A'
                ");

            $totalAgencia = DB::select("
                    select count(*) as total
                    from gp_agencia
                    where gp_agencia.doc_estado = 'A'
                ");

            return response()->json(
                [
                    "totalUsers" => $totalUsers,
                    "totalRegional" => $totalRegional,
                    "totalAgencia" => $totalAgencia,
                    "codigoRespuesta" => $success
                ]
            );
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function registrosXAgencia(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $hoyAgen = $request->input('fechaHoyAgen');
            $fecha_inicialAgen = $request->input('fechaInicialAgen');
            $fecha_finalAgen = $request->input('fechaFinalAgen');

            $nombreAgencias = \DB::select("
                    select gpa.id_sip_agencia, gpa.descripcion_agencia
                    from gp_agencia gpa
                    where gpa.doc_estado = 'A'
                ");

            $dataAgencias = [];

            foreach ($nombreAgencias as $agencia) {
                if (!is_null($fecha_inicialAgen) && !is_null($fecha_finalAgen)) {
                    $data = DB::select("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE (cas_data->>'cas_agencia' LIKE ?)
                            AND DATE(cas_registrado) BETWEEN DATE(?) AND DATE(?)
                    ", ["$agencia->descripcion_agencia%", $fecha_inicialAgen, $fecha_finalAgen]);
                } elseif (!is_null($hoyAgen) && $hoyAgen !== '') {
                    $data = DB::select("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE (cas_data->>'cas_agencia' LIKE ?)
                            AND DATE(cas_registrado) = CURRENT_DATE
                    ", ["$agencia->descripcion_agencia%"]);
                } else {
                    $data = DB::select("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE (cas_data->>'cas_agencia' LIKE ?)
                            AND DATE(cas_registrado) >= DATE_TRUNC('month', CURRENT_DATE)
                            AND DATE(cas_registrado) < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month'
                    ", ["$agencia->descripcion_agencia%"]);
                }

                $dataAgencias[$agencia->descripcion_agencia] = $data[0]->total;
            }

            return response()->json(["data" => $dataAgencias, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function cantidadRegistrosXMes(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $gestion = $request->input('gestion', date('Y'));

            $data = \DB::select("
                WITH months AS (
                    SELECT generate_series(1, 12) AS month
                )
                SELECT
                    COALESCE(COUNT(rmx_vys_casos.cas_registrado), 0) AS total,
                    TO_CHAR(TO_DATE(months.month::text, 'MM'), 'TMMonth') AS mes
                FROM
                    months
                LEFT JOIN
                    rmx_vys_casos
                ON
                    EXTRACT(MONTH FROM rmx_vys_casos.cas_registrado) = months.month
                    AND EXTRACT(YEAR FROM rmx_vys_casos.cas_registrado) = ?
                    AND cas_data->>'cas_cod_id' IS NOT NULL
                GROUP BY
                    months.month
                ORDER BY
                    months.month
            ", [$gestion]);

            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (\Exception $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function cantidadRegistrosXRegional(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $hoyAgen = $request->input('fechaHoyAgen');
            $fecha_inicialAgen = $request->input('fechaInicialAgen');
            $fecha_finalAgen = $request->input('fechaFinalAgen');

            $nombreRegionales = \DB::select("
                    select gpr.id_sip_regional, gpr.descripcion_doc
                    from gp_regional gpr
                    where gpr.doc_estado = 'A'
                ");

            $dataRegional = [];

            foreach ($nombreRegionales as $regional) {
                if (!is_null($fecha_inicialAgen) && !is_null($fecha_finalAgen)) {
                    $data = DB::select("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE (cas_data->>'cas_regional' LIKE ?)
                            AND DATE(cas_registrado) BETWEEN DATE(?) AND DATE(?)
                    ", ["$regional->descripcion_doc%", $fecha_inicialAgen, $fecha_finalAgen]);
                } elseif (!is_null($hoyAgen) || $hoyAgen === '') {
                    $data = DB::select("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE (cas_data->>'cas_regional' LIKE ?)
                            AND DATE(cas_registrado) = CURRENT_DATE
                    ", ["$regional->descripcion_doc%"]);
                } else {
                    $data = DB::select("
                        SELECT COUNT(*) AS total
                        FROM rmx_vys_casos rvc
                        WHERE (cas_data->>'cas_regional' LIKE ?)
                            AND DATE_TRUNC('month', cas_registrado) = DATE_TRUNC('month', CURRENT_DATE)
                    ", ["$regional->descripcion_doc%"]);
                }

                $dataRegional[$regional->descripcion_doc] = $data[0]->total;
            }
            return response()->json(["data" => $dataRegional, "codigoRespuesta" => $success]);
        } catch (\Exception $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function cantidadTramiteXDepto(Request $request)
    {
        $success = 200;
        $error = 500;

        $request->validate([
            'selectedCodes' => 'required|array',
            'departamento' => 'required|string',
            'fechaHoy' => 'nullable|date',
            'fechaInicial' => 'nullable|date',
            'fechaFinal' => 'nullable|date|after_or_equal:fechaInicial',
        ]);

        try {
            $selectedCodes = $request->input('selectedCodes');
            $departamento = $request->input('departamento');
            $fechaHoy = $request->input('fechaHoy');
            $fechaInicial = $request->input('fechaInicial');
            $fechaFinal = $request->input('fechaFinal');

            $rangoFechas = $this->determinarRangoFechas($fechaHoy, $fechaInicial, $fechaFinal);

            $resultados = array_map(function($code) use ($departamento, $rangoFechas) {
                $total = $this->contarTramites($code, $departamento, $rangoFechas);
                return [
                    "codigo" => $code,
                    "total" => $total
                ];
            }, $selectedCodes);

            $totalGeneral = array_reduce($resultados, function($carry, $item) {
                return $carry + $item['total'];
            }, 0);

            return response()->json([
                "data" => $resultados,
                "totalGeneral" => $totalGeneral,
                "codigoRespuesta" => ["code" => $success, "mensaje" => 'OK']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
                "codigoRespuesta" => [
                    "message" => "Error: " . $e->getMessage(),
                    "code" => $error
                ]
            ], $error);
        }
    }

    protected function determinarRangoFechas($fechaHoy, $fechaInicial, $fechaFinal): array
    {
        if (!is_null($fechaInicial)) {
            return [
                'inicio' => $fechaInicial,
                'fin' => $fechaFinal,
                'tipo' => 'rango'
            ];
        }

        if (!is_null($fechaHoy)) {
            return [
                'inicio' => $fechaHoy,
                'fin' => $fechaHoy,
                'tipo' => 'dia'
            ];
        }

        return [
            'inicio' => now()->startOfMonth()->toDateString(),
            'fin' => now()->endOfMonth()->toDateString(),
            'tipo' => 'mes'
        ];
    }

    protected function contarTramites(string $code, string $departamento, array $rangoFechas): int
    {
        $inicio = Carbon::parse($rangoFechas['inicio'])->startOfDay()->toDateTimeString();
        $fin = Carbon::parse($rangoFechas['fin'])->endOfDay()->toDateTimeString();

        $query = DB::table('rmx_vys_casos', 'rvc')
            ->whereBetween('rvc.cas_registrado', [$inicio, $fin])
            ->whereRaw("rvc.cas_data->>'cas_departamento' LIKE ?", ["$departamento%"])
            ->whereRaw("rvc.cas_data->>'cas_cod_id' LIKE ?", ["$code%"]);

        return $query->count();
    }
}

