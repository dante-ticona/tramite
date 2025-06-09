<?php

namespace App\Annotations;

class ApiAnnotationsNroTramite
{
/**
 * @OA\Put(
 *     path="/api/nroCorrelativo",
 *     summary="Obtener número correlativo",
 *     tags={"Tramites"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="cas_cod_id", type="string", example="RMIN/4368/2024")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Número correlativo obtenido",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="cas_correlativo", type="integer", example=88579),
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="success",
 *                 type="object",
 *                 @OA\Property(property="code", type="integer", example=200),
 *                 @OA\Property(property="mensaje", type="string", example="OK"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="error"),
 *                 @OA\Property(property="code", type="integer", example=500)
 *             )
 *         )
 *     )
 * )
 */
    public function nroCorrelativoAnnotation() {}
}
