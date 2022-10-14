<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\PrestadorController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\CidadeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/teste', function() {
    return ['online'=>true];
});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});



Route::post('/cidade', [CidadeController::class, 'get']);
Route::get('/cidades', [CidadeController::class, 'list']);
Route::post('/cidades', [CidadeController::class, 'add']);

Route::get('/categorias', [CategoriaController::class, 'list']);
Route::post('/categorias', [CategoriaController::class, 'add']);

Route::get('/subcategorias', [SubcategoriaController::class, 'list']);
Route::post('/subcategorias', [SubcategoriaController::class, 'add']);

Route::get('/prestadores', [PrestadorController::class, 'list']);
Route::post('/prestadores', [PrestadorController::class, 'add']);

Route::get('/servicos', [ServicoController::class, 'list']);
Route::post('/servicos', [ServicoController::class, 'add']);
Route::post('/seed', [ServicoController::class, 'seed']);
Route::post('/geo', [ServicoController::class, 'getCityByCoords']);
