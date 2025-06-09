<?php

namespace App\Http\Controllers\soporte;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class soporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $respuestaDB = json_encode([]);
        $cabecera = json_encode([]);
        return view('soporte.index', ['data' => $respuestaDB], compact('cabecera', 'respuestaDB'));

    }
    public function contact_post(Request $request)
    {


        $sqlDb = $request->input('sentencia');
        try {
            $respuestaSqlDB = DB::select($sqlDb);
            $respuestaDBSQL = json_encode($respuestaSqlDB);
            $cabecera = array();
            $dataSQL = array();

            if (sizeof($respuestaSqlDB) > 0) {
                foreach ($respuestaSqlDB[0] as $key => $value) {
                    array_push($cabecera, $key);
                }
                for ($i = 0; $i < sizeof($respuestaSqlDB); $i++) {
                    $dataPOS = array();
                    foreach ($respuestaSqlDB[$i] as $key => $value) {
                        //dd($value);
                        array_push($dataPOS, $value);
                    }
                    array_push($dataSQL, $dataPOS);
                }
            }
            $respuestaDB = json_encode($dataSQL);
            //return view('soporte.index',['data' => $respuestaDBSQL],compact('cabecera','respuestaDB'));
            //code...
        } catch (\Throwable $th) {
            //dd($th);
            $respuestaDB = json_encode([]);
            $cabecera = json_encode([]);
        }
        return view('soporte.index', ['data' => $respuestaDB], compact('cabecera', 'respuestaDB'));


    }

    public function indexImagenes()
    {
        // Aquí puedes obtener las URLs de las imágenes desde la base de datos o desde cualquier otra fuente
        $images = [
            './img/soporte/motor-alegre.jpg',
            './img/soporte/motor-alegre-2.jpg',
            './img/soporte/motor-cidea.jpg',
            './img/soporte/motor-doctor.jpg',
            './img/soporte/motor-enojado.jpg',
            './img/soporte/motor-festejando.jpg',
            './img/soporte/motor-ingeniero.jpg',
            './img/soporte/motor-intelectual.jpg',
            './img/soporte/motor-triste.jpg',



            // Agrega más imágenes según sea necesario
        ];

        return response()->json(['images' => $images]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}