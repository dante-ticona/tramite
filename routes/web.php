<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\soporte\soporteController;
use App\Http\Controllers\documentos\archivoController;
use App\Http\Controllers\ApiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('firmaSolicitante/{cas_id}', 'documentos\firmarController@vista');


Route::get('documentosAdjunto/{cas_id}', 'documentos\archivoController@guardarAdjuntos');
Route::post('/guardar-documento', 'documentos\archivoController@guardarAdjuntos')->name('guardar_documento');

Route::post('/files', [archivoController::class, 'store'])->name('files.store');


Route::post('/guardar-archivo', [ArchivoController::class, 'guardar'])->name('guardar_archivo');
//Route::post('/guardar-archivo', [archivoController::class, 'guardar']);
///Route::post('login', 'ApiController@login');

Route::get('tramitesSoporte', ['as' => 'adminSoporte.index', 'uses' => '\App\Http\Controllers\soporte\soporteController@index']);
Route::post('contact_post', [soporteController::class, 'contact_post'])->name('contri.habilita');

Route::get('/get-images', 'soporte\soporteController@indexImagenes')->name('get-images');
Route::post('/api/upload', 'documentos\archivoController@upload');



Route::post('token', [ApiController::class, 'login2'])->name('token');

Route::get('/clear/{cod}', function ($cod) {
    if ($cod == 'TramiteSIP.2024+') {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return '<h1> caches limpiados </h1>';
    } else {
        return back();
    }
});
