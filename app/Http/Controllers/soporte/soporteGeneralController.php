<?php
namespace App\Http\Controllers\soporte;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Complex\Functions;
use Illuminate\Http\Request;


class soporteGeneralController extends Controller
{ 

    public function actualizar_deptos(Request $request)
        {
            $request->validate([
                'cas_cod_id' => 'required|string',
                'origen' => 'required|integer',
                'destino' => 'required|integer',
            ]);

            $cas_cod_id = $request["cas_cod_id"];
            $origen = $request["origen"];
            $destino = $request["destino"];

            $success = ["code" => 200, "mensaje" => 'OK'];
            $error = ["message" => "error de instancia", "code" => 500];

            try {
                $data = \DB::select("SELECT actualizar_campos2(?, ?, ?)", [
                    $cas_cod_id,
                    $origen,
                    $destino
                ]);

                return response()->json(["data" => $data, "success" => $success]);

            } catch (\Exception $e) {
                return response()->json([
                    "error" => $error,
                    "detalle" => $e->getMessage()
                ]);
            }
        }

    public function actualizarCampos_cas_data_valores(Request $request)
        {
            $frm_campo = $request->input("frm_campo");
            $frm_value = $request->input("frm_value");
            $cas_id = $request->input("cas_id");

            // Validación robusta: cas_id debe ser un entero positivo, frm_campo no vacío
            if (empty($frm_campo) || !is_numeric($cas_id) || intval($cas_id) <= 0) {
                return response()->json([
                    "error" => ["message" => "Faltan o son inválidos los parámetros requeridos", "code" => 400]
                ]);
            }

            // Convertimos dinámicamente el valor a su tipo real (int, float, bool o string)
            if (is_numeric($frm_value)) {
                $frm_value = $frm_value + 0;
            } elseif (is_string($frm_value) && in_array(strtolower($frm_value), ['true', 'false'])) {
                $frm_value = strtolower($frm_value) === 'true';
            }

            $success = ["code" => 200, "mensaje" => 'OK'];
            $error = ["message" => "error de instancia", "code" => 500];

            try {
                $sql = "
                    WITH updated_json AS (
                        SELECT 
                            cas_id,
                            jsonb_agg(
                                CASE
                                    WHEN elem->>'frm_campo' = :frm_campo THEN
                                        jsonb_set(elem, '{frm_value}', (:frm_value_json)::jsonb)
                                    ELSE
                                        elem
                                END
                            ) AS updated_json
                        FROM 
                            public.rmx_vys_casos,
                            jsonb_array_elements(cas_data_valores) AS elem
                        WHERE 
                            cas_id = :cas_id
                        GROUP BY 
                            cas_id
                    )
                    UPDATE public.rmx_vys_casos
                    SET 
                        cas_data_valores = u.updated_json
                    FROM 
                        updated_json u
                    WHERE 
                        public.rmx_vys_casos.cas_id = u.cas_id;
                ";

                \DB::statement($sql, [
                    'frm_campo' => $frm_campo,
                    'frm_value_json' => json_encode($frm_value),
                    'cas_id' => $cas_id,
                ]);

                return response()->json(["success" => $success]);
            } catch (\Exception $e) {
                return response()->json([
                    "error" => $error,
                    "detalle" => $e->getMessage()
                ]);
            }
        }

  
    public function actualizarEnte_gestor(Request $request)
        {
            $cas_act_id = $request->input("cas_act_id");
            $cas_cod_id = $request->input("cas_cod_id");

            $success = ["code" => 200, "mensaje" => "OK"];
            $error = ["message" => "Error de instancia", "code" => 500];

            // Validar parámetros requeridos
            if (empty($cas_act_id) || empty($cas_cod_id)) {
                return response()->json(["error" => [
                    "message" => "Parámetros requeridos ausentes: cas_act_id y cas_cod_id",
                    "code" => 400
                ]]);
            }

            try {
                $sql = "
                    UPDATE public.rmx_vys_casos
                    SET cas_data_valores = (
                        SELECT jsonb_agg(
                            CASE 
                                WHEN elemento->>'frm_campo' = 'AS_ENTE_GESTOR' THEN
                                    jsonb_set(elemento, '{frm_value_label}', 
                                        to_jsonb(
                                            CASE elemento->>'frm_value'
                                                WHEN 'CNS' THEN 'CAJA NACIONAL DE SALUD'
                                                WHEN 'CPS' THEN 'CAJA PETROLERA DE SALUD'
                                                WHEN 'CBE' THEN 'CAJA DE SALUD DE LA BANCA ESTATAL'
                                                WHEN 'CBP' THEN 'CAJA DE SALUD DE LA BANCA PRIVADA'
                                                WHEN 'CCA' THEN 'CAJA DE SALUD DE CAMINOS Y R.A.'
                                                WHEN 'CCO' THEN 'CAJA DE SALUD CORDES'
                                                WHEN 'SIN' THEN 'CAJA DE SEGURIDAD SOCIAL SINEC'
                                                WHEN 'SSM' THEN 'CORPORACION DE SEGURO SOCIAL MILITAR'
                                                WHEN 'SSU' THEN 'SEGURO SOCIAL UNIVERSITARIO'
                                                WHEN 'SD-CNS' THEN 'SEGURO DELEGADO (CNS)'
                                                ELSE 'DESCONOCIDO'
                                            END
                                        )
                                    )
                                ELSE elemento
                            END
                        )
                        FROM jsonb_array_elements(cas_data_valores) AS elemento
                    )
                    WHERE cas_act_id = :cas_act_id
                    AND cas_estado = 'T'
                    AND cas_cod_id = :cas_cod_id
                    AND EXISTS (
                        SELECT 1
                        FROM jsonb_array_elements(cas_data_valores) AS elemento
                        WHERE elemento->>'frm_campo' = 'AS_ENTE_GESTOR'
                            AND elemento->>'frm_tipo' = 'DROPDOWNLIST'
                    );
                ";

                \DB::update($sql, [
                    'cas_act_id' => $cas_act_id,
                    'cas_cod_id' => $cas_cod_id
                ]);

                return response()->json(["success" => $success]);
            } catch (\Exception $e) {
                return response()->json([
                    "error" => $error,
                    "detalle" => $e->getMessage()
                ]);
            }
        }



    public function actualizar_guion(Request $request)
        {
            $doc_id = $request["doc_id"];

            $success = array("code" => 200, "mensaje" => 'OK', );
            $error = array("message" => "error de instancia", "code" => 500);
            try {
                $data = \DB::select("update _gp_documentos set doc_descripcion = concat('-',doc_descripcion) where doc_id in ($doc_id)");
                return response()->json(["data" => $data, "success" => $success]);
            } catch (error $e) {
                return response()->json(["error" => $error]);
            }
        }
 

    public function tomarCasoNube(Request $request)
        {
            $request->validate([
                'cas_id' => 'required|integer',
                'cas_estado' => 'required|string|max:5', // Ajusta el max según lo que esperes
            ]);

            $cas_id = $request->input("cas_id");
            $cas_estado = $request->input("cas_estado");

            $success = ["code" => 200, "mensaje" => 'OK'];
            $error = ["message" => "error de instancia", "code" => 500];

            try {
                $data = \DB::select(
                    "update rmx_vys_casos SET cas_estado = ? WHERE cas_id = ?",
                    [$cas_estado, $cas_id]
                );
                return response()->json(["data" => $data, "success" => $success]);
            } catch (\Exception $e) {
                return response()->json(["error" => $error, "detalle" => $e->getMessage()]);
            }
        }

  
public function cambiarNodoActividadUsuario(Request $request)
    {
        $success = ["code" => 200, "mensaje" => 'OK'];
        $error = ["message" => "error de instancia", "code" => 500];

        try {
            // Validación explícita
            $validated = $request->validate([
                'cas_id' => 'required|integer', // O "array" si esperas varios IDs
                'cas_estado' => 'required|string|max:5',
                'cas_usr_id' => 'required|integer',
                'cas_act_id' => 'required|integer',
                'cas_nodo_id' => 'required|integer',
            ]);

            $cas_id = $validated['cas_id'];
            $cas_estado = $validated['cas_estado'];
            $cas_usr_id = $validated['cas_usr_id'];
            $cas_act_id = $validated['cas_act_id'];
            $cas_nodo_id = $validated['cas_nodo_id'];

            // Sentencia segura
            $data = \DB::update("
                UPDATE public.rmx_vys_casos 
                SET 
                    cas_usr_id = ?, cas_act_id = ?,  cas_nodo_id = ?, cas_estado = ?
                WHERE cas_id = ?
            ", [
                $cas_usr_id,
                $cas_act_id,
                $cas_nodo_id,
                $cas_estado,
                $cas_id
            ]);

            return response()->json(["data" => $data, "success" => $success]);

        }  catch (\Exception $e) {
            return response()->json(["error" => $error, "detalle" => $e->getMessage()]);
        }
    }


    public function cambiarNodoActividadUsuariHistoric(Request $request)
    {
        $validated = $request->validate([
            'htc_id' => 'required|integer',
            'htc_cas_estado' => 'required|string',
            'htc_cas_usr_id' => 'required|integer',
            'htc_cas_act_id' => 'required|integer',
            'htc_cas_nodo_id' => 'required|integer',
        ]);

        $success = ["code" => 200, "mensaje" => 'OK'];
        $error = ["message" => "error de instancia", "code" => 500];

        try {
            $data = \DB::select("
                UPDATE public.rmx_vys_historico_casos 
                SET 
                    htc_cas_estado = ?,
                    htc_cas_usr_id = ?,
                    htc_cas_act_id = ?,
                    htc_cas_nodo_id = ?
                WHERE htc_id = ?
            ", [
                $validated['htc_cas_estado'],
                $validated['htc_cas_usr_id'],
                $validated['htc_cas_act_id'],
                $validated['htc_cas_nodo_id'],
                $validated['htc_id'],
            ]);

            return response()->json(["data" => $data, "success" => $success]);

        } catch (\Exception $e) {
            return response()->json(["error" => $error, "exception" => $e->getMessage()]);
        }
    }


    // public function borrarHistorico (Request $request)
    // {
    //     $htc_id = $request["htc_id"];
      
    //     $success = array("code" => 200, "mensaje" => 'OK', );
    //     $error = array("message" => "error de instancia", "code" => 500);
    //     try {
    //         $data = \DB::select(" UPDATE public.rmx_vys_historico_casos 
    //         SET htc_cas_id=1,htc_cas_cod_id ='', htc_cas_estado='X'
    //        WHERE htc_id in ($htc_id)");
    //         return response()->json(["data" => $data, "success" => $success]);
    //     } catch (error $e) {
    //         return response()->json(["error" => $error]);
    //     }

    // }
    
    public function borrarHistorico(Request $request)
        {
            $htc_id = $request->input('htc_id');

            $success = ["code" => 200, "mensaje" => 'OK'];
            $error = ["message" => "Error de instancia", "code" => 500];

            try {
                // Validación básica
                if (empty($htc_id)) {
                    return response()->json(["error" => ["message" => "Parámetro htc_id requerido", "code" => 400]], 400);
                }

                // Aseguramos que sea array o lo convertimos
                $ids = is_array($htc_id) ? $htc_id : [$htc_id];

                // Filtramos solo valores numéricos
                $ids = array_filter($ids, fn($id) => is_numeric($id));
                if (empty($ids)) {
                    return response()->json(["error" => ["message" => "ID(s) inválido(s)", "code" => 422]], 422);
                }

                // Construimos el string de placeholders (?, ?, ?, ...)
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                // Ejecutamos la consulta con parámetros enlazados (previene inyección SQL)
                $query = "UPDATE public.rmx_vys_historico_casos 
                        SET htc_cas_id = 1, htc_cas_cod_id = '', htc_cas_estado = 'X'
                        WHERE htc_id IN ($placeholders)";

                \DB::update($query, $ids);

                return response()->json(["success" => $success]);
            } catch (\Exception $e) {
                return response()->json(["error" => ["message" => $e->getMessage(), "code" => 500]], 500);
            }
        }

public function reponerHistorico(Request $request)
    {
        $htc_id = $request->input('htc_id');
        $htc_cas_id = $request->input('htc_cas_id');
        $htc_cas_cod_id = $request->input('htc_cas_cod_id');
        $htc_cas_estado = $request->input('htc_cas_estado');

        $success = ["code" => 200, "mensaje" => 'Registro(s) actualizado(s) con éxito'];
        $error = ["message" => "Error al actualizar", "code" => 500];

        // Validaciones mínimas
        if (empty($htc_id) || !isset($htc_cas_id) || $htc_cas_cod_id === null || $htc_cas_estado === null) {
            return response()->json([
                "error" => ["message" => "Parámetros incompletos o inválidos", "code" => 400]
            ], 400);
        }

        // Asegurar que htc_id sea un array válido de números
        $htc_ids = is_array($htc_id) ? $htc_id : explode(',', $htc_id);
        $htc_ids = array_filter($htc_ids, fn($id) => is_numeric($id));

        if (empty($htc_ids)) {
            return response()->json([
                "error" => ["message" => "htc_id no válido", "code" => 400]
            ], 400);
        }

        try {
            // Preparar placeholders
            $placeholders = implode(',', array_fill(0, count($htc_ids), '?'));

            $sql = "UPDATE public.rmx_vys_historico_casos 
                    SET htc_cas_id = ?, 
                        htc_cas_cod_id = ?, 
                        htc_cas_estado = ?
                    WHERE htc_id IN ($placeholders)";

            $bindings = array_merge([$htc_cas_id, $htc_cas_cod_id, $htc_cas_estado], $htc_ids);

            $updated = \DB::update($sql, $bindings);

            return response()->json([
                "updated_rows" => $updated,
                "success" => $success
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $error,
                "exception" => $e->getMessage()
            ], 500);
        }
    }


    public function actualizarCampoCasDataCasos(Request $request)
        {
            $frm_campo = $request->input('frm_campo');
            $frm_value = $request->input('frm_value');
            $cas_id = $request->input('cas_id');

            $success = ["code" => 200, "mensaje" => 'Campo actualizado con éxito'];
            $error = ["message" => "Error en la actualización", "code" => 500];

            // Validación: campo y cas_id son obligatorios
            if (empty($frm_campo) || empty($cas_id)) {
                return response()->json(["error" => ["message" => "Parámetros inválidos", "code" => 400]], 400);
            }

            // Convertir cas_id a array y validar
            $cas_ids = is_array($cas_id) ? $cas_id : explode(',', $cas_id);
            $cas_ids = array_filter($cas_ids, fn($id) => is_numeric($id));

            if (empty($cas_ids)) {
                return response()->json(["error" => ["message" => "cas_id no válido", "code" => 400]], 400);
            }

            try {
                // Preparar valores
                $json_path = '{' . $frm_campo . '}';

                // Si es nulo o vacío, usar "" explícitamente como JSON string
                if ($frm_value === null || $frm_value === '') {
                    $json_value = json_encode(""); // devuelve "\"\""
                } else {
                    // Detectar tipo (número o texto)
                    $json_value = is_numeric($frm_value) ? $frm_value : json_encode($frm_value);
                }

                // Placeholder para cas_id
                $placeholders = implode(',', array_fill(0, count($cas_ids), '?'));

                $sql = "
                    UPDATE public.rmx_vys_casos 
                    SET cas_data = jsonb_set(cas_data, ?, ?::jsonb, false)
                    WHERE cas_id IN ($placeholders)
                ";

                // Combinar parámetros
                $bindings = array_merge([$json_path, $json_value], $cas_ids);

                // Ejecutar UPDATE
                $updated = \DB::update($sql, $bindings);

                if ($updated > 0) {
                    return response()->json(["updated_rows" => $updated, "success" => $success]);
                } else {
                    return response()->json(["message" => "No se actualizó ningún registro", "code" => 204]);
                }

            } catch (\Exception $e) {
                return response()->json([
                    "error" => $error,
                    "exception" => $e->getMessage()
                ], 500);
            }
        }

    public function actualizarCampoHistorico(Request $request)
        {
            $frm_campo = $request->input('frm_campo');
            $frm_value = $request->input('frm_value');
            $htc_id = $request->input('htc_id');

            $success = ["code" => 200, "mensaje" => 'Campo actualizado con éxito'];
            $error = ["message" => "Error al actualizar", "code" => 500];

            // Validar campos requeridos
            if (empty($frm_campo) || empty($htc_id)) {
                return response()->json([
                    "error" => ["message" => "Parámetros inválidos", "code" => 400]
                ], 400);
            }

            // Convertir htc_id en array si es string separado por comas
            $htc_ids = is_array($htc_id) ? $htc_id : explode(',', $htc_id);
            $htc_ids = array_filter($htc_ids, fn($id) => is_numeric($id));

            if (empty($htc_ids)) {
                return response()->json([
                    "error" => ["message" => "ID(s) no válidos", "code" => 400]
                ], 400);
            }

            try {
                // Preparar JSON path
                $json_path = '{' . $frm_campo . '}';

                // Si es string, codificarlo; si es numérico, usar directo
                $json_value = is_numeric($frm_value) ? $frm_value : json_encode($frm_value);

                // Construir SQL
                $placeholders = implode(',', array_fill(0, count($htc_ids), '?'));
                $sql = "
                    UPDATE public.rmx_vys_historico_casos
                    SET htc_cas_data = jsonb_set(htc_cas_data, ?, ?::jsonb, false)
                    WHERE htc_id IN ($placeholders)
                ";

                // Combinar bindings
                $bindings = array_merge([$json_path, $json_value], $htc_ids);

                // Ejecutar actualización
                $updated = \DB::update($sql, $bindings);

                return response()->json([
                    "updated_rows" => $updated,
                    "success" => $success
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    "error" => $error,
                    "exception" => $e->getMessage()
                ], 500);
            }
        }

 
    public function actualizarURLdocumentos(Request $request)
        {
            $validated = $request->validate([
                'doc_id' => 'required|integer|min:1',
                'doc_url' => 'required|string|max:500'
            ]);

            $doc_id = $validated['doc_id'];
            $doc_url = $validated['doc_url'];

            try {
                $updated = \DB::update(
                    'UPDATE public."_gp_documentos" SET doc_url = ? WHERE doc_id = ?',
                    [$doc_url, $doc_id]
                );

                if ($updated === 0) {
                    return response()->json([
                        "message" => "No se encontró el documento o no hubo cambios.",
                        "code" => 404
                    ], 404);
                }

                return response()->json([
                    "success" => [
                        "code" => 200,
                        "mensaje" => "URL actualizada correctamente"
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "error" => [
                        "message" => "Error al actualizar la URL",
                        "detalle" => $e->getMessage(),
                        "code" => 500
                    ]
                ], 500);
            }
        }

    public function quitaDocumentosConHistorico(Request $request)
        {
            // Permitir que 'doc_id' sea int o array
            $request->validate([
                'doc_id' => ['required'],
            ]);

            $doc_ids_raw = $request->input('doc_id');

            // Convertir a array si es un solo valor
            $doc_ids = is_array($doc_ids_raw) ? $doc_ids_raw : [$doc_ids_raw];

            // Validar que todos sean enteros y no vacíos
            $doc_ids = array_filter($doc_ids, fn($id) => is_numeric($id) && $id > 0);

            if (empty($doc_ids)) {
                return response()->json([
                    "error" => [
                        "message" => "Parámetro 'doc_id' inválido o vacío",
                        "code" => 422
                    ]
                ], 422);
            }

            try {
                // Armar placeholders (?, ?, ?)
                $placeholders = implode(',', array_fill(0, count($doc_ids), '?'));

                \DB::update(
                    'UPDATE public."_gp_documentos" 
                    SET doc_cas_id = 0, doc_his_id = 1, doc_estado = ? 
                    WHERE doc_id IN (' . $placeholders . ')',
                    array_merge(['X'], $doc_ids)
                );

                return response()->json(["success" => ["code" => 200, "mensaje" => "OK"]]);
            } catch (\Exception $e) {
                return response()->json([
                    "error" => [
                        "message" => "error de instancia",
                        "code" => 500
                    ],
                    "exception" => $e->getMessage()
                ], 500);
            }
        }

        // actualizar casos e historico Estado
    public function tomarCasoNube2(Request $request)
        {
            $request->validate([
                'cas_id' => 'required|integer',
                'cas_estado' => 'required|string|max:5',
            ]);
        
            $cas_id = $request->input("cas_id");
            $cas_estado = $request->input("cas_estado");
        
            $success = ["code" => 200, "mensaje" => 'OK'];
            $error = ["message" => "error de instancia", "code" => 500];
        
            try {
                \DB::beginTransaction();
        
                // Actualizar la tabla principal
                \DB::update(
                    "UPDATE rmx_vys_casos SET cas_estado = ? WHERE cas_id = ?",
                    [$cas_estado, $cas_id]
                );
        
                // Obtener el último htc_id para el cas_id
                $ultimoHistorico = \DB::selectOne(
                    "SELECT htc_id FROM rmx_vys_historico_casos 
                     WHERE htc_cas_id = ? 
                     ORDER BY htc_id DESC 
                     LIMIT 1",
                    [$cas_id]
                );
        
                // Si existe registro histórico, actualizarlo
                if ($ultimoHistorico) {
                    \DB::update(
                        "UPDATE rmx_vys_historico_casos 
                         SET htc_cas_estado = ? 
                         WHERE htc_id = ?",
                        [$cas_estado, $ultimoHistorico->htc_id]
                    );
                }
        
                \DB::commit();
                return response()->json(["success" => $success]);
        
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->json(["error" => $error, "detalle" => $e->getMessage()]);
            }
        }

        // actualizar data values con Historico

    public function cambiarNodoActividadConHistorico(Request $request)
        {
            $validated = $request->validate([
                'cas_id' => 'required|integer',
                'cas_estado' => 'required|string|max:5',
                'cas_usr_id' => 'required|integer',
                'cas_act_id' => 'required|integer',
                'cas_nodo_id' => 'required|integer',
            ]);
        
            $success = ["code" => 200, "mensaje" => 'OK'];
            $error = ["message" => "error de instancia", "code" => 500];
        
            try {
                \DB::beginTransaction();
        
                // 1. Actualizar la tabla principal rmx_vys_casos
                \DB::update("
                    UPDATE public.rmx_vys_casos 
                    SET 
                        cas_usr_id = ?, 
                        cas_act_id = ?,  
                        cas_nodo_id = ?, 
                        cas_estado = ?
                    WHERE cas_id = ?
                ", [
                    $validated['cas_usr_id'],
                    $validated['cas_act_id'],
                    $validated['cas_nodo_id'],
                    $validated['cas_estado'],
                    $validated['cas_id']
                ]);
        
                // 2. Buscar el último htc_id para ese cas_id
                $ultimoHistorico = \DB::selectOne("
                    SELECT htc_id FROM public.rmx_vys_historico_casos 
                    WHERE htc_cas_id = ? 
                    ORDER BY htc_id DESC 
                    LIMIT 1
                ", [$validated['cas_id']]);
        
                // 3. Si existe histórico, actualizar también
                if ($ultimoHistorico) {
                    \DB::update("
                        UPDATE public.rmx_vys_historico_casos 
                        SET 
                            htc_cas_estado = ?, 
                            htc_cas_usr_id = ?, 
                            htc_cas_act_id = ?, 
                            htc_cas_nodo_id = ?
                        WHERE htc_id = ?
                    ", [
                        $validated['cas_estado'],
                        $validated['cas_usr_id'],
                        $validated['cas_act_id'],
                        $validated['cas_nodo_id'],
                        $ultimoHistorico->htc_id
                    ]);
                }
        
                \DB::commit();
                return response()->json(["success" => $success]);
        
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->json([
                    "error" => $error,
                    "detalle" => $e->getMessage()
                ]);
            }
        }


// public function mantis(Request $request)
//     {
//         $request->validate([
//             'cas_id'      => 'required|integer',
//             'cas_act_id'  => 'required|integer',
//             'cas_nodo_id' => 'required|integer',
//             'cas_usr_id'  => 'required|integer',
//             'cas_estado'  => 'required|string',
//             'campo'       => 'required|string',
//             'valor'       => 'required|string'
//         ]);

//         // Parámetros del request
//         $cas_id      = $request->input('cas_id');
//         $cas_act_id  = $request->input('cas_act_id');
//         $cas_nodo_id = $request->input('cas_nodo_id');
//         $cas_usr_id  = $request->input('cas_usr_id');
//         $cas_estado  = $request->input('cas_estado');
//         $campo       = $request->input('campo');
//         $valor       = $request->input('valor');

//         $success = ["code" => 200, "mensaje" => 'Actualización exitosa'];
//         $error   = ["message" => "Error al ejecutar la función", "code" => 500];

//         try {
//             $resultado = \DB::select("SELECT sp_derivar_caso1(?, ?, ?, ?, ?, ?, ?)", [
//                 $cas_id,
//                 $cas_act_id,
//                 $cas_nodo_id,
//                 $cas_usr_id,
//                 $cas_estado,
//                 $campo,
//                 $valor
//             ]);

//             return response()->json(["data" => $resultado, "success" => $success]);

//         } catch (\Exception $e) {
//             return response()->json([
//                 "error" => $error,
//                 "detalle" => $e->getMessage()
//             ], 500);
//         }
//     }

public function mantis(Request $request)
{
    $request->validate([
        'cas_id'      => 'required|integer',
        'cas_act_id'  => 'required|integer',
        'cas_nodo_id' => 'required|integer',
        'cas_usr_id'  => 'required|integer',
        'cas_estado'  => 'required|string',
        'campos'      => 'required|array',
        'valores'     => 'required|array',
    ]);

    // Parámetros del request
    $cas_id      = $request->input('cas_id');
    $cas_act_id  = $request->input('cas_act_id');
    $cas_nodo_id = $request->input('cas_nodo_id');
    $cas_usr_id  = $request->input('cas_usr_id');
    $cas_estado  = $request->input('cas_estado');
    $campos      = $request->input('campos');
    $valores     = $request->input('valores');
    // Validación adicional: ambos arrays deben tener la misma longitud
    if (count($campos) !== count($valores)) {
        return response()->json([
            "error" => [
                "message" => "Los arrays 'campos' y 'valores' deben tener la misma longitud",
                "code" => 422
            ]
        ], 422);
    }

    // Convertir los arrays PHP a formato PostgreSQL TEXT[]
    $campos_pg = '{' . implode(',', array_map(fn($v) => '"' . addslashes($v) . '"', $campos)) . '}';
    $valores_pg = '{' . implode(',', array_map(fn($v) => '"' . addslashes($v) . '"', $valores)) . '}';
    $success = ["code" => 200, "mensaje" => 'Actualización exitosa'];
    $error   = ["message" => "Error al ejecutar la función", "code" => 500];

    try {
        $resultado = \DB::select("SELECT sp_derivar_caso1(?, ?, ?, ?, ?, ?::text[], ?::text[])", [
            $cas_id,
            $cas_act_id,
            $cas_nodo_id,
            $cas_usr_id,
            $cas_estado,
            $campos_pg,
            $valores_pg
        ]);

        return response()->json(["data" => $resultado, "success" => $success]);

    } catch (\Exception $e) {
        return response()->json([
            "error" => $error,
            "detalle" => $e->getMessage()
        ], 500);
    }
}


           
}
