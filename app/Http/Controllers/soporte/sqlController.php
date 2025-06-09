<?php

namespace App\Http\Controllers\soporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class sqlController extends Controller
{
    public function tramitesBuenos(Request $request)
    {
        // Validar que la solicitud tenga la sentencia SQL
        $request->validate([
            'sql' => 'required|string',
        ]);

        try {
            // Ejecutar la sentencia SQL que viene en la solicitud
            $sql = $request->input('sql');
            // dd('$sql', $sql);
            $result = \DB::select($sql);


            return response()->json([
                'status' => 'success',
                'data' => $result
            ], 200);
        } catch (QueryException $e) {
            // Si ocurre un error en la consulta SQL, devolver un mensaje de error
            return response()->json([
                'status' => 'error',
                'message' => 'Error al ejecutar la consulta: ' . $e->getMessage(),
            ], 500);
        }
    }
}