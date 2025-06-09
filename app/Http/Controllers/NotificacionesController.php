<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class NotificacionesController extends Controller
{
    public function ListarNotificaciones(Request $request)
    {
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Mensaje Enviado ...', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "message" => "Error de instancia", "data" => 'Error'];

        $userId = $request->input('userId');

        $user = User::find($userId);

        if ($user) {
            $unreadNotificationsCount = $user->unreadNotifications->count();

            $user->unread_notifications_count = $unreadNotificationsCount;

            $notificaciones = \DB::table('notifications')
                ->where(\DB::raw("CAST(jsonb_extract_path_text(data::jsonb, 'user_id') AS INTEGER)"), $userId)
                ->where('notifiable_id', '!=', $userId)
                ->where('notifiable_type', 'App\User')
                ->whereNull('read_at')
                ->get();

            $notificacionesCount = $notificaciones->count();

        } else {
            return response()->json($noencontrado);
        }

        $fechaActual = new \DateTime();
        $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
        $success['fecha'] = $fechaFormateada;
        $success['cantidad'] = $notificacionesCount;
        $success['data'] = $notificaciones;
        return response()->json($success);
    }

    public function marcarLeidoNoti(Request $request){
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "mensaje" => "Error de instancia", "data" => 'Error'];

        try {
            $idNotification = $request->input('id');

            if (empty($idNotification)) {
                throw new \Exception("El ID de la notificación es requerido");
            }

            $notificacion = DB::table('notifications')->where('id', $idNotification)->first();

            if (!$notificacion) {
                throw new \Exception("Notificación no encontrada");
            }

            DB::table('notifications')
                ->where('id', $idNotification)
                ->update(['read_at' => now()]);

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = "Mensaje marcado como leído";
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
