<?php
namespace App\Derivacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Tools
{
    // funcion para guardar documentos de los jsons que son de salida del debug usados con el dump
    public static function GuardarJsonDebugDev($nombre, $jsonContent){
        $LOGFILE = base_path('dumps/' . $nombre . '.json');
        $jsonEncodedContent = json_encode($jsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($LOGFILE, $jsonEncodedContent, FILE_APPEND);
        // dump("Archivo creado >>> ". $LOGFILE);
    }

    public static function respuesta5000FakeAdad(){
        // en ocaciones cuando el servicio de ADAD falla nos muestra un error de este tipo con codigo de error 5000
        // 1) se vio que sale el error cuando no se cuenta con los permisos necesario en tablas para las consultas por el servicio
        // dump("SIMULANDO EL ERROR DE RESPUESTA 5000 DEL SERVICIO DE ADAD");
        // justamente el fin de esta funcion es para simular ese error y poderlo controlar y mostrar un mensaje mas amigable al usuario
        $responseBody = [
            'ok' => false,
            'mensaje' => [
                'codigo' => 5000,
                'descripcion' => 'Se ha producido un error interno en el servicio.',
                'sugerencia' => 'Comunicar el error',
                'detalles' => [
                    "Excepcion al Crear Solicitud Prestacion"
                ]
            ],
            'data' => null
        ];

        return $responseBody;

    }

}
