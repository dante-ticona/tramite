<?php

namespace App\Http\Controllers\documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;

class urlController extends Controller
{
    function cambioUrl(Request $request)
    {
        $tipo = Config::get('var.tipo');
        try {
            $dataResponProduccion = \DB::select("select * from   public.dominio  where dominio = 'manejo_url'  and  codigo= 'PRO' ");
            $dataResponPruebas = \DB::select("select * from   public.dominio  where dominio = 'manejo_url'  and  codigo= 'PRU' ");
            $dataRespon = \DB::select("select frm_funciones, frm_id from   public.rmx_vys_formularios");

            if ($tipo == 'PRUEBA') {
                print_r('PRUEBA');
                foreach ($dataRespon as $item) {
                    if ($item->frm_funciones != null) {
                        foreach ($dataResponProduccion as $produccion) {
                            foreach ($dataResponPruebas as $prueba) {
                                $tablaContenido = str_replace($produccion->descripcion, $prueba->descripcion, $item->frm_funciones);
                                $imp = str_replace('\'', '\'\'', $tablaContenido);
                                $dataRespon = \DB::select("update  rmx_vys_formularios set   frm_funciones = '$imp' where frm_id = 1 ");
                                dd($tablaContenido);
                            }

                        }
                    }
                }
            } else {
                print_r('PRODUCION');
                foreach ($dataRespon as $item) {
                    if ($item->frm_funciones != null) {
                        foreach ($dataResponPruebas as $prueba) {
                            foreach ($dataResponProduccion as $produccion) {
                                $tablaContenido = str_replace($prueba->descripcion, $produccion->descripcion, $item->frm_funciones);
                                $imp = str_replace('\'', '\'\'', $tablaContenido);
                                $dataRespon = \DB::select("update  rmx_vys_formularios set   frm_funciones = '$imp'
                                                        where frm_id = 1 ");
                                dd($tablaContenido);
                            }
                        }
                    }
                }
            }

            dd('auiq');
            foreach ($dataRespon as $item) {
                if ($item->frm_funciones != null) {
                    foreach ($dataResponPruebas as $prueba) {
                        foreach ($dataResponProduccion as $produccion) {
                            $tablaContenido = str_replace($prueba->descripcion, $produccion->descripcion, $item->frm_funciones);

                            $imp = str_replace('\'', '\'\'', $tablaContenido);



                            $dataRespon = \DB::select("update  rmx_vys_formularios set   frm_funciones = '$imp'
                                                        where frm_id = 1 ");


                            dd($tablaContenido);
                        }
                        dd($prueba->descripcion);
                    }
                    dd($dataResponPruebas);
                    $tablaContenido = str_replace('https://oficinavirtual.gestora.bo/api/General/Ciudad', 'https://oficinavirtual.gestora.bo/api/General/Ciudad', $$item->frm_funciones);
                    dd($item);
                }
            }


            $valor_ambiar = 'https://oficinavirtual.gestora.bo/api/General/Ciudad';
            $valor_original = $dataRespon[5]->frm_funciones;
            $tablaContenido = str_replace($valor_ambiar, 'https://oficinavirtual.gestora.bo/api/General/Ciudad', $valor_original);

            // $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);


            dd($tablaContenido);

            $result = $dataRespon[0]->sp_existe_documento_inicial;
            return $result;
        } catch (Exception $error) {
            print_r(json_encode(["error" => $error]));
        }
    }
}
