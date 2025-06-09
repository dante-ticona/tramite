<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

// ##############################################################
// #                                                            #
// #                CREDENCIALES PARA PRODUCCION                #
// #                                                            #
// ##############################################################

    function entorno()
    {
        $entorno = "PRODUCCION";
        return $entorno;
    }

    function version() { $version = "051/2025"; // VERSION DE LANZAMIENTO -> CODIGO DE LANZAMIENTO * NRO SOLICITUD /SISTEMA DE REQUERIMIENTO SGG
        return $version;
    }

    function urlGestora()
    {
        $url_gestora = "https://sipre.gestora.bo";
        return $url_gestora;
    }

    function urlApiGestora()
    {
        $url_gestora = "https://api.gestora.bo";
        return $url_gestora;
    }

    function urlsggTest()
    {
        $url_gestora_sgg = "https://sgg.gestora.bo";
        return $url_gestora_sgg;
    }

    function urlsggGestora1()
    {
        $url_gestora_sgg1 = "https://sgg.gestora.bo";
        return $url_gestora_sgg1;
    }

    function urlPersonas()
    {
        $url_personas = "https://cenpersona.gestora.bo";
        return $url_personas;
    }

    function urlPlanillas()
    {
        $url_planillas = "https://planilla-prestacion.gestora.bo";
        return $url_planillas;
    }

    function urlautenticarPlanillas()
    {
        $url_autenticar_planillas = "https://seguridadapis.gestora.bo/api/autenticar";
        return $url_autenticar_planillas;
    }

    function obtenerCredencialesPlanillas()
    {
        $credenciales = [
            "username" => "WsPrestacionKsk",
            "password" => "Pr3st4cione32023Ksk"
        ];

        return json_decode(json_encode($credenciales), true);
    }

    // CREDENCIALES DE PRODUCCION
    function obtenerCredenciales() { $credenciales = ["login" => "liliana.ramos@gestora.bo", "password" => "Tempo.12"];
        return json_decode(json_encode($credenciales), true);
    }

    function colorTema()
    {
        $tema = "sidebar-dark-primary";
        return $tema;
    }

    function appUrl()
    {
        $url = "https://tramitesip.gestora.bo";
        return $url;
    }

    function gtalentoQuarkusUrlBase(){
        $url_gtalento_sgg = "https://sgg.gestora.bo";
        return $url_gtalento_sgg;
    }

    function authenticateUrlRegistrarPoder()
    {
        $credenciales = [
            "url"=>"https://sgg.test.gestora.bo/api/auth/authorize_password",
            "credenciales"=> [
                "username" => "operador7",
                "password" => "operador7"
            ]
        ];

        return json_decode(json_encode($credenciales), true);
    }

    function userLdap() { $credenciales_ldap = ["login" => "liliana.ramos@gestora.bo", "password" => "Tempo.12"];
        return json_decode(json_encode($credenciales_ldap), true);
    }

    function rutaLogoTramiteSIP(){
        $ruta = '/img/logoEmpresaProd.png';
        return $ruta;
    }

    function GetTokenDocumentosUcpp()
    {
        $token = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJNYXJpbyBSb2RvbGZvIFNpbHZhIiwianRpIjoiQjhMQ0YiLCJpYXQiOjE2NzQzOTMwNjYsInJlYWQiOnRydWUsIndyaXRlIjp0cnVlLCJ1cGRhdGUiOmZhbHNlfQ.Dfpl8qNc_X-exHWtSC18R4U_7gtBdU1k1roio1wWYmo';
        return $token;
    }

    function urlDocumentosUcpp()
    {
        $url_documentos = "https://archivos.gestora.bo/api/files/";
        return $url_documentos;
    }

