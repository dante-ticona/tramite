<?php

namespace App\Annotations;


class ApiAnnotationsDerivarDatosTramite
{
/**
 * @OA\Put(
 *     path="/api/derivarDatosTramite",
 *     summary="Derivar Datos Trámite",
 *     tags={"Tramites"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nroTramite", type="string", example="JUB/7430/2024"),
 *             @OA\Property(property="email", type="email", example="usuario.tramites@gestora.bo"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Derivación Trámite",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="codigoRespuesta", type="integer", example=200),
 *                     @OA\Property(property="mensaje", type="string", example="OK"),
 *                     @OA\Property(property="data", type="string", example="JUB/7430/2024"),
 *                      @OA\Property(property="casData", type="object",
 *                         @OA\Property(property="AS_CI", type="string", example="2641661"),
 *                         @OA\Property(property="AS_CUA", type="string", example="42248450"),
 *                         @OA\Property(property="AS_CORREO", type="string", example="romualda181163@gmail.com"),
 *                         @OA\Property(property="AS_CELULAR", type="string", example="73771064"),
 *                         @OA\Property(property="cas_cod_id", type="string", example="JUB/7430/2024"),
 *                         @OA\Property(property="AS_TIPO_EAP", type="string", example="CVEAP-A1"),
 *                         @OA\Property(property="cas_agencia", type="string", example="25 DE MAYO"),
 *                         @OA\Property(property="cas_gestion", type="string", example="2024"),
 *                         @OA\Property(property="AS_DIRECCION", type="string", example="INNOMINADA"),
 *                         @OA\Property(property="TIPO_PROCESO", type="string", example="JUB"),
 *                         @OA\Property(property="cas_nro_caso", type="string", example="23229"),
 *                         @OA\Property(property="cas_regional", type="string", example="COCHABAMBA"),
 *                         @OA\Property(property="AS_COMPLEMENTO", type="string", example=null),
 *                         @OA\Property(property="NOMBRE_PROCESO", type="string", example="PENSIÓN POR JUBILACIÓN"),
 *                         @OA\Property(property="cas_registrado", type="string", example="8/8/2024"),
 *                         @OA\Property(property="id_cas_agencia", type="string", example="30"),
 *                         @OA\Property(property="cas_nombre_caso", type="string", example="2641661<br/>ROMUALDA<br/>INOCENTE<br/>ALANEZ"),
 *                         @OA\Property(property="id_cas_regional", type="string", example="9"),
 *                         @OA\Property(property="AS_PRIMER_NOMBRE", type="string", example="ROMUALDA"),
 *                         @OA\Property(property="USUARIO_REGISTRO", type="string", example="maria.checa"),
 *                         @OA\Property(property="cas_departamento", type="string", example="COCHABAMBA"),
 *                         @OA\Property(property="AS_SEGUNDO_NOMBRE", type="string", example=null),
 *                         @OA\Property(property="AS_TIPO_DOCUMENTO", type="string", example="I"),
 *                         @OA\Property(property="ESTADO_DERIVACION", type="string", example="FIRMADO"),
 *                         @OA\Property(property="AS_PRIMER_APELLIDO", type="string", example="INOCENTE"),
 *                         @OA\Property(property="USUARIO_SUPERVISOR", type="string", example="mileidy.garcia"),
 *                         @OA\Property(property="AS_SEGUNDO_APELLIDO", type="string", example="ALANEZ"),
 *                         @OA\Property(property="id_cas_departamento", type="string", example="3"),
 *                         @OA\Property(property="DESCRIPCION_DERIVACION", type="string", example="ASEGURADA FIRMO CVEAP Y EAP"),
 *                         @OA\Property(property="ID_SOLICITUDPRESTACION", type="integer", example=894102)
 *                     ),
 *                     @OA\Property(property="nameUser", type="string", example="ISNELDA ADRIANA OLMOS MARISCAL"),
 *                     @OA\Property(property="userID", type="string", example="123456"),
 *                     @OA\Property(property="nameUSer", type="string", example="ISNELDA ADRIANA OLMOS MARISCAL"),
 *                     @OA\Property(property="casId", type="string", example="23229"),
 *                     @OA\Property(
 *                                 property="dataDerivar",
 *                                 type="array",
 *                                 @OA\Items(
 *                                           type="object",
 *                                           @OA\Property(property = "sp_derivar_caso",
 *                                           type = "integer", example = 186004
 *                                   )
 *                                 )
 *                         ),
 *                     ),
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
 *         description="Error en la Derivación del Trámite",
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
    public function derivarDatosTramiteAnnotation() {}
}
