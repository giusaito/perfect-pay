<?php

use Illuminate\Support\Facades\Route;

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
    return view('pagamento');
});
Route::post('/processar-pagamento-cartao', 'PaymentController@cartao')->name('processar-pagamento');
Route::post('/processar-pagamento-boleto', 'PaymentController@boleto')->name('processar-pagamento');