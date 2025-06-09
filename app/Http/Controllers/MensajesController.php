<?php

namespace App\Http\Controllers;
use App\Mensajes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Notifications\NotifiMensajeria;
use Illuminate\Support\Facades\Log;

class MensajesController extends Controller
{
    // Función para enviar mensajes TRAMITESIP
    public function mensajeriaTramiteSip(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Mensaje Enviado ...', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $usuario = $request->input('usuario');
            $nroTramite = $request->input('nroTramite');
            $mensaje = $request->input('mensaje');

            if (empty($nroTramite)) {
                throw new \Exception("El número de trámite es requerido");
            }

            if (empty($mensaje)) {
                throw new \Exception("El mensaje es requerido");
            }

            $dataTramite = DB::table('rmx_vys_casos as rvc')
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
                ->where('rvc.cas_estado', '=', 'E')
                ->where('rvc.cas_cod_id', '=', $nroTramite)
                ->select(
                    'rvc.cas_id as casId',
                    'rvc.cas_act_id as casActId',
                    'rvc.cas_nodo_id as casNodoId',
                    'rva.act_prc_id as actPrcId',
                    'rvc.cas_usr_id as casUsrId'
                )->get();

            if ($dataTramite->isEmpty()) {
                throw new \Exception("Trámite no encontrado " . $nroTramite);
            }

            $user = getUser($usuario);

            if ($user->isEmpty()) {
                throw new \Exception("Usuario no encontrado " . $usuario);
            }

            $userId = User::find($user[0]->id);

            if (!$userId) {
                throw new \Exception("Usuario no encontrado en la base de datos");
            }

            $conversacion = DB::table('gp_conversaciones')
                ->where('cas_cod_id', '=', $nroTramite)
                ->first();

            if ($conversacion) {
                Log::info("Conversación encontrada con ID: " . $conversacion->id);
                $idConversacion = $conversacion->id;
            } else {
                Log::info("No se encontró conversación, creando una nueva");
                $idConversacion = DB::table('gp_conversaciones')->insertGetId([
                    'cas_cod_id' => $nroTramite,
                    'user1_id' => $dataTramite[0]->casUsrId,
                    'user2_id' => $user[0]->id,
                    'tipo_conversacion' => 'UCPP'
                ]);
            }

            if (strlen($mensaje) <= 7) {
                throw new \Exception("El mensaje debe tener al menos 7 caracteres");
            }

            if (strlen($mensaje) >= 250) {
                throw new \Exception("El mensaje no debe tener más de 250 caracteres");
            }

            $ultimoMensaje = DB::table('gp_mensajes as gm')
                ->join('users as u', 'gm.user_id', '=', 'u.id')
                ->where('gm.id_conversacion', '=', $idConversacion)
                ->orderBy('gm.created_at', 'desc')
                ->select('gm.mensaje', 'u.email_verified_at as userName', 'u.id as userId')
                ->first();

            if ($ultimoMensaje) {
                    if ($usuario != $ultimoMensaje->userName) {
                        $userId->notify(new NotifiMensajeria([
                            'cas_cod_id' => $nroTramite,
                            'tiene_mensaje_de' => $user[0]->id,
                            'user_id' => $ultimoMensaje->userId,
                            'tipo_conversacion' => 'UCPP',
                            'id_caso' => $dataTramite[0]->casId,
                            'mensaje' => $mensaje,
                            'estado' => 'registrado',
                            'estadosis' => 'ACTIVO',
                            'id_conversacion' => $idConversacion
                        ]));
                    }
                    Log::info("Último mensaje: " . $ultimoMensaje->mensaje . " escrito por: " . $ultimoMensaje->userName);
            } else {
                Log::info("No se encontró ningún mensaje en la conversación con ID: " . $idConversacion);
            }

            $dataMensajes = DB::table('gp_mensajes')->insert([
                'id_caso' => $dataTramite[0]->casId,
                'cas_cod_id' => $nroTramite,
                'user_id' => $user[0]->id,
                'mensaje' => $mensaje,
                'estado' => 'registrado',
                'estadosis' => 'ACTIVO',
                'id_conversacion' => $idConversacion
            ]);

            if ($dataMensajes) {
                Log::info("Mensaje registrado correctamente: " . json_encode($dataMensajes));
            } else {
                throw new \Exception("Error al registrar el mensaje");
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = "Mensaje Registrado";
            return response()->json($success);

        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function listarMensajesSip(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "cantidadMsg" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $usuario = $request->input('usuario');
            $nroTramite = $request->input('nroTramite');

            if (empty($nroTramite)) {
                throw new \Exception("El número de trámite es requerido");
            }

            $idGpconversacion = \DB::table('gp_conversaciones')
                ->where('cas_cod_id', '=', $nroTramite)
                ->select(
                    'id',
                )->get();

            if ($idGpconversacion->isEmpty()) {
                throw new \Exception("No se encontró la conversación");
            }

            $mensajes = DB::table('gp_mensajes as gm')
                ->join('gp_conversaciones as gc', 'gm.id_conversacion', '=', 'gc.id')
                ->join('users as u', 'gm.user_id', '=', 'u.id')
                ->join('gp_regional as gr', 'u.id_regional', '=', 'gr.id_sip_regional')
                ->where('gc.id', '=', $idGpconversacion[0]->id)
                ->select(
                    'gm.id',
                    'gm.id_caso as idCaso',
                    'gm.cas_cod_id as casCodId',
                    'gm.user_id as userId',
                    'u.email_verified_at as userName',
                    'gr.descripcion_doc as regional',
                    'gm.mensaje',
                    'gm.estado',
                    'gm.estadosis',
                    'gm.id_conversacion as idConversacion',
                    'gm.created_at as createdAt',
                    'gm.updated_at as updatedAt'
                )
                ->orderBy('gm.created_at', 'asc')
                ->get();

            $cantidadMensajes = DB::table('gp_mensajes as gm')
                ->join('gp_conversaciones as gc', 'gm.cas_cod_id', '=', 'gc.cas_cod_id')
                ->where('gm.cas_cod_id', '=', $nroTramite)
                ->count();

            if ($mensajes->isEmpty()) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $noencontrado['fecha'] = $fechaFormateada;
                $noencontrado['data'] = "No se encontraron mensajes";
                return response()->json($noencontrado);
            }

            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['cantidadMsg'] = $cantidadMensajes;
            $success['data'] = $mensajes;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function listarNotifiPanel(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $gp_conversaciones = DB::table('gp_conversaciones as gpc')
                ->orderBy('gpc.created_at', 'desc')
                ->get();

            $mensajes = DB::table('gp_mensajes as gm')
                ->join('gp_conversaciones as gc', 'gm.id_conversacion', '=', 'gc.id')
                ->join('users as u', 'gm.user_id', '=', 'u.id')
                ->join('gp_regional as gr', 'u.id_regional', '=', 'gr.id_sip_regional')
                ->where('gc.id', '=', $gp_conversaciones[0]->id)
                ->select(
                    'gm.id',
                    'gm.id_caso as idCaso',
                    'gm.cas_cod_id as casCodId',
                    'gm.user_id as userId',
                    'u.email_verified_at as userName',
                    'gr.descripcion_doc as regional',
                    'gm.mensaje',
                    'gm.estado',
                    'gm.estadosis',
                    'gm.id_conversacion as idConversacion',
                    'gm.created_at as createdAt',
                    'gm.updated_at as updatedAt'
                )->get();

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $gp_conversaciones;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function verificarHistorico(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $nroTramite = $request->input('nroTramite');

            $gp_respuesta = DB::table('gp_conversaciones as gpc')
                ->where('gpc.cas_cod_id', '=', $nroTramite)
                ->get();

            if ($gp_respuesta->isNotEmpty() && $gp_respuesta[0]->cas_cod_id) {
                $respuesta = 1;
            } else {
                $respuesta = 0;
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $respuesta;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    // Función para enviar mensajes EXTERNOS leerMensajes
    public function leerMensajes(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $numeroTramite = $request->input('numeroTramite');
            $usuarioTramite = $request->input('usuarioTramite');

            $url = urlsggTest()."/otorgamiento-prestaciones/api/v1/toolsCveapPres/MensajesRecibidosTramites";
            $response = Http::withOptions(['verify' => false])->get($url, [
                'numeroTramite' => $numeroTramite,
                'usuarioTramite' => $usuarioTramite
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['codigo'] == "200") {
                    $mensajes = $responseData['data'];
                } else {
                    throw new \Exception("Error en la respuesta: " . $responseData['mensaje']);
                }
            } else {
                throw new \Exception("Error en la petición: " . $response->body());
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $mensajes;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    // Función para enviar mensajes EXTERNOS enviarMensajes
    public function enviarMensajes(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];
        $alerta = ["codigoRespuesta" => 201, "mensaje" => 'Alerta', "fecha" => '', "data" => 'Error'];

        try {
            $numeroTramite = $request->input('numeroTramite');
            $usuarioTramite = $request->input('usuarioTramite');
            $mensaje = $request->input('mensaje');

            $url = urlsggTest()."/otorgamiento-prestaciones/api/v1/toolsCveapPres/MensajesTramitesSip";

            $response = Http::withOptions(['verify' => false])->post($url, [
                'numeroTramite' => $numeroTramite,
                'usuario' => $usuarioTramite,
                'mensaje' => $mensaje
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseBody = json_decode($responseBody);
            $codigo = '200';
            $request_body = json_encode([
                'numeroTramite' => $numeroTramite,
                'usuario' => $usuarioTramite,
                'mensaje' => $mensaje
            ]);

            $headers = '{"Content-Type": "application/json"}';
            $ip = request()->ip();
            $responseBody_log = json_encode($responseBody);

            $qry_log = "select *  from public.sp_create_service_logs('POST','$url', '$request_body',
                '$responseBody_log','$codigo', '$headers', '$ip','$usuarioTramite', '$numeroTramite', 'UCPP')";
            $data_log = \DB::select($qry_log);

            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['codigo'] == "200") {
                    $mensajes = $responseData['data'];
                } elseif ($responseData['codigo'] == "201") {
                    $fechaActual = new \DateTime();
                    $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                    $alerta['fecha'] = $fechaFormateada;
                    $alerta['data'] = $responseData['data'];
                    return response()->json($alerta);
                } else {
                    throw new \Exception("Error en la respuesta: " . $responseData['mensaje']);
                }
            } else {
                throw new \Exception("Error en la petición: " . $response->body());
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $mensajes;
            return response()->json($success);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }
}
