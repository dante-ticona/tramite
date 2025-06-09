<?php

namespace App\Annotations;


class ApiAnnotationsActualizarDatosTramite
{
/**
 * @OA\Put(
 *     path="/api/actualizarTramite",
 *     summary="Actualizar el Trámite",
 *     tags={"Tramites"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nroTramite", type="string", example="JUB/3956/2024"),
 *             @OA\Property(property="usuario", type="email", example="usuario.prueba@gestora.bo"),
 *            @OA\Property(property="estadoDerivacion", type="string", example="OBSERVADO"),
 *           @OA\Property(property="descripcionDerivacion", type="string", example="Se actualizo el trámite"),
 *           @OA\Property(
 *               property="observaciones",
 *               type="array",
 *               @OA\Items(
 *                   type="object",
 *                   @OA\Property(property="descripcion", type="string", example="DERIVADO1 ....."),
 *                   @OA\Property(property="unidad", type="string", example="DERIVADO PARA LA UNIDAD1")
 *               ),
 *               example={
 *                   {
 *                       "descripcion": "DERIVADO1 .....",
 *                       "unidad": "DERIVADO PARA LA UNIDAD1"
 *                   },
 *                   {
 *                       "descripcion": "DERIVADO2 .....",
 *                       "unidad": "DERIVADO PARA LA UNIDAD2"
 *                   },
 *                   {
 *                       "descripcion": "DERIVADO3 .....",
 *                       "unidad": "DERIVADO PARA LA UNIDAD3"
 *                   }
 *               }
 *           ),
 *           @OA\Property(
 *               property="documentos",
 *               type="array",
 *               @OA\Items(
 *                   type="object",
 *                   @OA\Property(property="documento", type="string", example="documento1 ....."),
 *                   @OA\Property(property="descripcion", type="string", example="descripcion del documento")
 *               ),
 *               example={
 *                   {
 *                       "documento": "Documento 1 .....",
 *                       "descripcion": "descripción documento 1"
 *                   },
 *                   {
 *                       "documento": "Documento 2 .....",
 *                       "descripcion": "descripción documento 2"
 *                   },
 *                   {
 *                       "documento": "Documento 3 .....",
 *                       "descripcion": "descripción documento 3"
 *                   }
 *               }
 *           ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="La actualización del Trámite",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="codigoRespuesta", type="integer", example=88579)
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="success",
 *                 type="object",
 *                 @OA\Property(property="code", type="integer", example=200),
 *                 @OA\Property(property="mensaje", type="string", example="OK")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error en la actualización del Trámite",
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
    public function actualizarDatosTramiteAnnotation() {}
}
