<?php

namespace App\Annotations;


class ApiAnnotationsEstadoTramite
{
/**
 * @OA\Put(
 *     path="/api/estadoTramite",
 *     summary="Ver el estado del Trámite",
 *     tags={"Tramites"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nroTramite", type="string", example="JUB/5885/2024")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="El estado del Trámite",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="object",
 *                 @OA\Property(property="codigoRespuesta", type="integer", example=200),
 *                 @OA\Property(property="mensaje", type="string", example="Correcto"),
 *                 @OA\Property(property="data", type="string", example="Si existe"),
 *                 @OA\Property(property="nroTramite", type="string", example="JUB/5885/2024")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error al verificar el estado del Trámite",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="error"),
 *                 @OA\Property(property="code", type="integer", example=400)
 *             )
 *         )
 *     )
 * )
 */
    public function verificarEstadoTramiteAnnotation() {}
}
