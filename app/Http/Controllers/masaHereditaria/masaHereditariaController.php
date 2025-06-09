<?php

namespace App\Http\Controllers\masaHereditaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class masaHereditariaController extends Controller
{

    public function validarLegalMasa(Request $request)
    {
        //dd('esa aqui ');
        $cua = $request["cua"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from sp_legal_masa_validar('$cua')");
            // dd('datos ', $data);
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
    public function datosLegalMasaHerederos(Request $request)
    {
        // dd('esa aqui ');
        $cua = $request["cua"];
        $cas_cod_id = $request["cas_cod_id"];
        $apellidoCasada = $request["apellidoCasada"] ?? '';
        $complemento = $request["complemento"] ?? '';
        $correoElectronico = $request["correoElectronico"] ?? '';
        $direccion = $request["direccion"] ?? '';
        $documentoIdentidad = $request["documentoIdentidad"] ?? '';
        $fechaDefuncion = $request["fechaDefuncion"] ?? '';
        $fechaNacimiento = $request["fechaNacimiento"] ?? '';
        $idEstadoCivil = $request["idEstadoCivil"] ?? '';
        $idGenero = $request["idGenero"] ?? '';
        $idNacionalidad = $request["idNacionalidad"] ?? '';
        $idPersonaSip = $request["idPersonaSip"] ?? '';
        $idSipAsegurados = $request["idSipAsegurados"] ?? '';
        $primerApellido = $request["primerApellido"] ?? '';
        $primerNombre = $request["primerNombre"] ?? '';
        $segundoApellido = $request["segundoApellido"] ?? '';
        $segundoNombre = $request["segundoNombre"] ?? '';
        $telefonoCelular = $request["telefonoCelular"] ?? '';
        $telefonoFijo = $request["telefonoFijo"] ?? '';
        $tipoIdentidad = $request["tipoIdentidad"] ?? '';




        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from sp_legal_masa_datos_herederos('$cua')");

            $data_herederos = json_decode($data[0]->r_datos_herederos);
            $data_herederos_array_2 = [];

            $i = 1;

            foreach ($data_herederos as $heredero) {

                $data_herederos_array = [];

                $result = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_TIPO_DOCUMENTO";
                }));

                $tipoDocumento = $result[0]->col_value ?? null;

                $result2 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_IDPERSONA_GRILLA_PROP";
                }));
                $idpersona = $result2[0]->col_value ?? null;

                $result4 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_CI_GRILLA_PROP";
                }));
                $ci = $result4[0]->col_value ?? null;

                $result7 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_FECHA_NAC";
                }));
                $fecha_nacimiento = $result7[0]->col_value ?? null;

                $result8 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_NOMBRES";
                }));
                $nombres = $result8[0]->col_value ?? null;

                $result9 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_PRIMER_APELLIDO";
                }));
                $primer_apellido = $result9[0]->col_value ?? null;

                $result10 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_SEGUNDO_APELLIDO";
                }));
                $segundo_apellido = $result10[0]->col_value ?? null;

                $result11 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_APELLIDO_CASADA";
                }));
                $casado_apellido = $result11[0]->col_value ?? null;

                $result12 = array_values(array_filter($heredero, function ($item) {
                    return $item->col_campo === "DAHERDERO_GENERO";
                }));
                $genero = $result12[0]->col_value ?? null;


                $item0 = new \stdClass();
                $item0->col_campo = "DH_ESTADO_HEREDERO";
                $item0->col_value = null;


                $item1 = new \stdClass();
                $item1->col_campo = "ES_DH_FALLECIDO";
                $item1->col_value = "2";

                $item2 = new \stdClass();
                $item2->col_campo = "DH_TIPO_DOCUMENTO";
                $item2->col_value =  $tipoDocumento;

                $item3 = new \stdClass();
                $item3->col_campo = "DH_IDPERSONA_GRILLA_PROP";
                $item3->col_value =  $idpersona;

                $item4 = new \stdClass();
                $item4->col_campo = "DH_CI_GRILLA_PROP";
                $item4->col_value =  $ci;

                $item5 = new \stdClass();
                $item5->col_campo = "DH_COMP_GRILLA_PROP";
                $item5->col_value =  null;

                $item6 = new \stdClass();
                $item6->col_campo = "DH_BUSCAR";
                $item6->col_value =  null;

                $item7 = new \stdClass();
                $item7->col_campo = "DH_FECHA_NAC";
                $item7->col_value =  $fecha_nacimiento;

                $item8 = new \stdClass();
                $item8->col_campo = "DH_NOMBRES";
                $item8->col_value =   $nombres;

                $item9 = new \stdClass();
                $item9->col_campo = "DH_PRIMER_APELLIDO";
                $item9->col_value =   $primer_apellido;
                $item10 = new \stdClass();
                $item10->col_campo = "DH_SEGUNDO_APELLIDO";
                $item10->col_value =  $segundo_apellido;
                $item11 = new \stdClass();
                $item11->col_campo = "DH_APELLIDO_CASADA";
                $item11->col_value =  $casado_apellido;

                $item12 = new \stdClass();
                $item12->col_campo = "DH_GENERO";
                $item12->col_value =  $genero;

                $item13 = new \stdClass();
                $item13->col_campo = "DH_DIRECCION";
                $item13->col_value = null;

                $item14 = new \stdClass();
                $item14->col_campo = "DH_NUMERO";
                $item14->col_value = null;

                $item15 = new \stdClass();
                $item15->col_campo = "DH_NRO_CELULAR";
                $item15->col_value = null;

                $item16 = new \stdClass();
                $item16->col_campo = "DH_CORREO";
                $item16->col_value = null;

                $item17 = new \stdClass();
                $item17->col_campo = "DH_FECHA_FALLECIDO";
                $item17->col_value =  null;

                $item18 = new \stdClass();
                $item18->col_campo = "DH_INVALIDEZ";
                $item18->col_value = null;

                $item19 = new \stdClass();
                $item19->col_campo = "DH_PARENTESCO";
                $item18->col_value =  null;

                $item20 = new \stdClass();
                $item20->col_campo = "DH_GRADO";
                $item20->col_value = null;

                $item21 = new \stdClass();
                $item21->col_campo = "DH_DOCUMENTOS";
                $item21->col_value =  null;

                $item22 = new \stdClass();
                $item22->col_campo = "DH_TUTOR";
                $item22->col_value =  null;



                $data_herederos_array[] =  $item0;
                $data_herederos_array[] =  $item1;
                $data_herederos_array[] =  $item2;
                $data_herederos_array[] =  $item3;
                $data_herederos_array[] =  $item4;
                $data_herederos_array[] =  $item5;
                $data_herederos_array[] =  $item6;
                $data_herederos_array[] =  $item7;
                $data_herederos_array[] =  $item8;
                $data_herederos_array[] =  $item9;
                $data_herederos_array[] =  $item10;
                $data_herederos_array[] =  $item11;
                $data_herederos_array[] =  $item12;
                $data_herederos_array[] =  $item13;
                $data_herederos_array[] =  $item14;
                $data_herederos_array[] =  $item15;
                $data_herederos_array[] =  $item16;
                $data_herederos_array[] =  $item17;
                $data_herederos_array[] =  $item18;
                $data_herederos_array[] =  $item19;
                $data_herederos_array[] =  $item20;
                $data_herederos_array[] =  $item21;
                $data_herederos_array[] =  $item22;


                $data_herederos_array_2[] = $data_herederos_array;
                $i++;
            }

            $data_herederos_array_2 = json_encode($data_herederos_array_2);

            $data = \DB::select("select * from sp_legal_masa_guardar_herederos('$data_herederos_array_2','$cas_cod_id','$apellidoCasada', '$complemento', '$correoElectronico', '$direccion', '$documentoIdentidad', '$fechaDefuncion', '$fechaNacimiento', '$idEstadoCivil', '$idGenero', '$idNacionalidad', '$idPersonaSip', '$idSipAsegurados', '$primerApellido', '$primerNombre', '$segundoApellido', '$segundoNombre', '$telefonoCelular', '$telefonoFijo', '$tipoIdentidad','$cua')");


            dd($data_herederos_array_2);

            // dd('datos ', $data);
            return response()->json(["data" => $data, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }
}
