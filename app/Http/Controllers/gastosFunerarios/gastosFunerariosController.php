<?php

namespace App\Http\Controllers\gastosFunerarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class gastosFunerariosController extends Controller
{
    public function datosSolicitanteCobrador(Request $request)
    {
        $nro_tramite = $request["nro_tramite"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancias", "code" => 500);
        try {
            $data = \DB::select("select * from sp_datos_solicitante_cobrador('$nro_tramite')");
            $data = json_decode($data[0]->sp_datos_solicitante_cobrador);
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function datosParentesco(Request $request)
    {
        $nro_tramite = $request["nro_tramite"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancias", "code" => 500);
        try {
            $data = \DB::select("select * from sp_datos_solicitante_cobrador('$nro_tramite')");
            $data = json_decode($data[0]->sp_datos_solicitante_cobrador);
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
}