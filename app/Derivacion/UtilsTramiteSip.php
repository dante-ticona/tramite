<?php
namespace App\Derivacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Derivacion\UtilsTramiteSip;
use App\Derivacion\Tools;


class UtilsTramiteSip
{
    public static function arrayCodigoTipos($vjsonDataArray)
    {
        foreach ($vjsonDataArray as $item) {
            $codigoTipoReferencia = null;
            $codigoTipoReferenciaTitular = null;
            $datoReferencia = null;
            $datoReferenciaTitular = null;

            // Imprime el campo actual para depurar posibles duplicados
            // dump($item['frm_campo'] . ': ' . $item['frm_value']);

            // Llenar valores según el campo frm_campo
            switch ($item['frm_campo']) {
                case "TIPO_PROCESO":
                    $tipoproceso = $item['frm_value'] ?? "";
                    break;
                case "SOL_CELULAR":
                    $codigoTipoReferencia = "TELEF";
                    $datoReferencia = $item['frm_value'] ?? "";
                    break;
                case "SOL_CORREO":
                    $codigoTipoReferencia = "EMAIL";
                    $datoReferencia = $item['frm_value'] ?? "";
                    break;
                case "SOL_IDPERSONA":
                    $SOL_IDPERSONA = $item['frm_value'] ?? "";
                    break;
                case "AS_CELULAR":
                    $codigoTipoReferenciaTitular = "TELEF";
                    $datoReferenciaTitular = $item['frm_value'] ?? "";
                    break;
                case "AS_CORREO":
                    $codigoTipoReferenciaTitular = "EMAIL";
                    $datoReferenciaTitular = $item['frm_value'] ?? "";
                    break;
                case "AS_IDPERSONA":
                    $AS_IDPERSONA = $item['frm_value'] ?? "";
                    break;
                case "SOL_DIRECCION":
                    $codigoTipoReferencia = "DIREC-DOM";
                    $datoReferencia = $item['frm_value'] ?? "";
                    break;
                case "AS_ENTE_GESTOR":
                    $AS_ENTE_GESTOR = $item['frm_value'] ?? "";
                    break;
                default:
                    // Ignorar otros casos
                    break;
            }

            // Agregar el objeto al array varReferenciales si codigoTipoReferencia no es nulo
            if (!empty($codigoTipoReferencia)) {
                $varReferenciales[] = [
                    "codigoTipoReferencia" => $codigoTipoReferencia,
                    "datoReferencia" => $datoReferencia
                ];
            }

            // Agregar el objeto al array varReferencialesTitular si codigoTipoReferenciaTitular no es nulo
            if (!empty($codigoTipoReferenciaTitular)) {
                $varReferencialesTitular[] = [
                    "codigoTipoReferencia" => $codigoTipoReferenciaTitular,
                    "datoReferencia" => $datoReferenciaTitular
                ];
            }
        }

        return [
            'varReferenciales' => $varReferenciales ?? [],
            'varReferencialesTitular' => $varReferencialesTitular ?? []
        ];
    }

    public static function arrayCodigoDH($vjsonDoc, $jsonDataDHArray)
    {
        $participantesSolicituddh = [];

        foreach ($jsonDataDHArray as $subArray) {
            // dump("SUBARRAY >>> : " . json_encode($subArray));
            $dh_parentesco = collect($subArray)->firstWhere('col_campo', 'DH_PARENTESCO')['col_value'] ?? null;
            $dh_idpersona_grilla_prop = collect($subArray)->firstWhere('col_campo', 'DH_IDPERSONA_GRILLA_PROP')['col_value'] ?? null;

            // dump($dh_idpersona_grilla_prop);
            // dump("vjsonDoc". $vjsonDoc);

            Tools::GuardarJsonDebugDev("8-dh_idpersona_grilla_prop-8",$dh_idpersona_grilla_prop);
            Tools::GuardarJsonDebugDev("9-vjsonDoc-9",$vjsonDoc);

            $respaldosArray = UtilsTramiteSip::buscarContenidoPorIdPersonaSip(json_decode($vjsonDoc, true),$dh_idpersona_grilla_prop);

            // dump("EN LA FUNCION PRINCIPAL >>> ".json_encode($respaldosArray));

            Tools::GuardarJsonDebugDev("10-respaldosArray-10",$respaldosArray);

            // dump("dh_parentesco: " . $dh_parentesco);

            $partes = explode('-', $dh_parentesco);

            // dump("partes Nuevo >>> : " . $partes[1]);

            $valorDeseado = null;

            if (count($partes) === 2) {
                $valorDeseado = $partes[1];
            }

            $participante = [
                "codigoTipoParentesco" => $valorDeseado,
                "idPersonaSip" => collect($subArray)->firstWhere('col_campo', 'DH_IDPERSONA_GRILLA_PROP')['col_value'] ?? null,
                "porcentajeBeneficiario" => collect($subArray)->firstWhere('col_campo', 'DH_GRADO')['col_value'] ?? null,
                "estadoInvalidez" => collect($subArray)->firstWhere('col_campo', 'DH_INVALIDEZ')['col_value'] ?? null,
                "datosReferenciales" => [
                    [
                        "codigoTipoReferencia" => "TELEF",
                        "datoReferencia" => collect($subArray)->firstWhere('col_campo', 'DH_NRO_CELULAR')['col_value'] ?? null
                    ],
                    [
                        "codigoTipoReferencia" => "EMAIL",
                        "datoReferencia" => collect($subArray)->firstWhere('col_campo', 'DH_CORREO')['col_value'] ?? null
                    ]
                ],
                "respaldos" => $respaldosArray
            ];

            $participantesSolicituddh[] = $participante;

            // dump(" <<<<<<<<< PARTICIPANTESSOLICITUDDH 11111111111111111 >>>>>>> : " . json_encode($participantesSolicituddh));

            Tools::GuardarJsonDebugDev("11-participantesSolicituddh-11",$participantesSolicituddh);

            // dump("PARTICIPANTES >>> : " . json_encode($participante));
        }

        return $participantesSolicituddh;
    }

    public static function buscarContenidoPorIdPersonaSip($data, $idPersonaSip)
    {
        // dump("la data >>>>" . json_encode($data));
        if(count(array_filter($data, function($item) { return !is_null($item); })) === 0){
            // dump("RETORNAMOS VACIO POR QUE ESTA SIN DATOS");
            return [];
        }

        $result = [];

        foreach ($data as $item) {
            foreach($item as $key => $value){
                if (is_object($value)) {
                    $value = (array) $value;
                }

                if (isset($value['doc_id_persona_sip']) && $value['doc_id_persona_sip'] == $idPersonaSip) {
                    $result[] = [
                        'idTipoDocumentoSolicitud' => $value['idTipoDocumentoSolicitud'] ?? null,
                        'tipoDocumentoGuardado' => $value['tipoDocumentoGuardado'] ?? null,
                        'idCodigoArchivo' => $value['idCodigoArchivo'] ?? null,
                    ];
                }
            }
        }

        return $result > 0 ? $result : [];
    }

    public static function setIdSolicitudPrestacion($cas_id, $idsolicitudprestacion)
    {
        try {

            // Obtener los datos originales
            $results = \DB::select("SELECT c.cas_data_valores
            FROM rmx_vys_casos c
            WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                AND c.cas_id = $cas_id
            ORDER BY c.cas_modificado DESC");

            // Verificar si hay resultados
            if (!empty($results)) {
                $jsonOriginal = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $dataJson = json_decode($jsonOriginal, true);

                // Buscar y actualizar el valor si existe
                $foundField = false;
                foreach ($dataJson as &$item) {
                    if ($item['frm_campo'] === 'ID_SOLICITUDPRESTACION') {
                        $foundField = true;
                        // Verificar si el campo tiene 'frm_value'
                        if (array_key_exists('frm_value', $item)) {
                            // Modificar el valor existente
                            $item['frm_value'] = $idsolicitudprestacion;
                        } else {
                            // $foundField = false;
                            // Agregar el campo 'frm_value'
                            $item['frm_value'] = $idsolicitudprestacion;
                        }

                        // Actualizar la base de datos con el dato modificado
                        $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);
                        /*$data = \DB::select("UPDATE rmx_vys_casos SET
                        cas_data_valores = '$jsonActualizado'::json,
                        cas_modificado = now()
                        --,cas_usr_id = $cas_usr_id
                        WHERE cas_id = $cas_id");*/
                        $data = \DB::select(
                            "UPDATE rmx_vys_casos SET
                        cas_data_valores = ?::json,
                        cas_modificado = now()
                        --,cas_usr_id = $cas_usr_id
                        WHERE cas_id = ?",
                            [$jsonActualizado, $cas_id]
                        );

                        return $data;
                        // return response()->json(["data" => $data, "success" => $success, "message" => "El valor ha sido modificado correctamente."]);
                    }
                }

                // Si no se encontró el campo, puedes agregarlo al array
                if (!$foundField) {
                    $dataJson[] = array(
                        "frm_campo" => "ID_SOLICITUDPRESTACION",
                        "frm_value" => $idsolicitudprestacion,
                        "frm_deshabilitado" => "false",
                        "frm_deshabilitadoo" => false
                    );

                    // Actualizar la base de datos con los datos modificados
                    $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);
                    $data = \DB::select("UPDATE rmx_vys_casos SET
                    cas_data_valores = '$jsonActualizado'::json,
                    cas_modificado = now()
                    --,cas_usr_id = $cas_usr_id
                    WHERE cas_id = $cas_id");

                    return response()->json(["data" => $data, "success" => $success, "message" => "Se agregó el campo 'ID_SOLICITUDPRESTACION' correctamente."]);
                }

            } else {
                return $data;
            }
        } catch (error $e) {
            return $e->getMessage();
        }
    }

    public static function crearPorTipoPrestacionOrigen($accessToken, $vTipoProceso, $ouput)
    {
        $url = urlGestora() . "/spr-tram-rest/api/solicitudPrestacion/crearPorTipoPrestacionOrigen?codigoTipoPrestacion=" . $vTipoProceso;

        $response_ = Http::withOptions(['verify' => false])
        ->withToken($accessToken)
        ->post($url, $ouput);

        $responseBody = json_decode($response_->body(), true);
        return $responseBody;
    }

    public static function getIdSolicitudPrestacion($cas_id, $idsolicitudprestacion)
    {
        // seria ajustar el query para guardar el codigo idSolicitudPrestacion en el cas_data_valores
        // dump("idsolicitudprestacion >>>> getIdSolicitudPrestacion ". $idsolicitudprestacion);
        try {
            $data = \DB::select("
                SELECT subquery.frm_campo, subquery.frm_value
                FROM (
                    SELECT
                        jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_campo' AS frm_campo,
                        jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_value' AS frm_value
                    FROM
                        rmx_vys_casos c
                    WHERE
                        c.cas_id = ?
                        AND (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                ) subquery
                WHERE subquery.frm_campo IN ('ID_SOLICITUDPRESTACION');
            ", [$cas_id]);

            // dump("data getIdSolicitudPrestacion >>>> ". json_encode($data));

            if (!empty($data)) {

                // dump("ACTUALIZANDO EL DATA >>>> ID_SOLICITUDPRESTACION ");
                foreach ($data as &$item) {
                    if ($item->frm_campo === 'ID_SOLICITUDPRESTACION' && is_null($item->frm_value)) {
                        $item->frm_value = $idsolicitudprestacion;

                        // Actualizar la base de datos con el dato modificado
                        $jsonActualizado = json_encode($data, JSON_PRETTY_PRINT);
                        \DB::select(
                            "UPDATE rmx_vys_casos SET
                            cas_data_valores = ?::json,
                            cas_modificado = now()
                            WHERE cas_id = ?",
                            [$jsonActualizado, $cas_id]
                        );


                    $data2 = \DB::select("
                        SELECT subquery.frm_campo, subquery.frm_value
                        FROM (
                            SELECT
                                jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_campo' AS frm_campo,
                                jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_value' AS frm_value
                            FROM
                                rmx_vys_casos c
                            WHERE
                                c.cas_id = ?
                                AND (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                        ) subquery
                        WHERE subquery.frm_campo IN ('ID_SOLICITUDPRESTACION');
                    ", [$cas_id]);

                    }
                }
            }

            return $data2;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
