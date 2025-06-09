<?php

    Route::prefix('v1/kpi')->group(function () {
        Route::get('listadoProcesosG', 'graficos\graficosController@listadoProcesos');
        Route::post('cantidadTramite', 'graficos\graficosController@cantidadTramite');
        Route::get('datosGenerales', 'graficos\graficosController@datosGenerales'); //ok
        Route::get('metricasDepartamentos', 'graficos\graficosController@metricasDepartamentos');
        Route::get('metricasOccidente', 'graficos\graficosController@metricasOccidente');
        Route::get('etapaPreviaCalculo', 'graficos\graficosController@etapaPreviaCalculo');
        Route::get('estadoAvanceTramite', 'graficos\graficosController@estadoAvanceTramite');
        Route::get('listadoTramitesCasos', 'graficos\graficosController@listadoTramitesCasos');
        Route::get('casosPendientesBandeja', 'graficos\graficosController@casosPendientesBandeja');
        Route::get('ultimosRegistros', 'graficos\graficosController@ultimosRegistros');
        Route::get('cantidadUsuarios', 'graficos\graficosController@cantidadUsuarios');
        Route::get('datosComplementarios', 'graficos\graficosController@datosComplementarios');
        Route::get('registrosPorAgencia', 'graficos\graficosController@registrosXAgencia');
        Route::get('cantidadRegistrosPorMes', 'graficos\graficosController@cantidadRegistrosXMes');
        Route::get('cantidadRegistrosPorRegional', 'graficos\graficosController@cantidadRegistrosXRegional');

        Route::get('estadoProcesosTramite', 'graficos\graficosController@estadoProcesosTramite');

        // para filtros por departamentos
        Route::post('cantidadTramiteXDepto', 'graficos\graficosController@cantidadTramiteXDepto');
    });
