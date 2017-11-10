<?php

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

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Auth::routes();


/**
 * Rutas creación USERS
 */

Route::resource("users", "UsersController");

/**
 * Rutas creación Marca
 */
Route::resource("marca", "MarcaController");

Route::get("marca/showJSON/{id}", 'MarcaController@showJSON');

/**
 * Rutas creación Tipo productos
 */
Route::resource("tipo_producto", "TipoProductoController");

Route::get("tipo_producto/showJSON/{id}", 'TipoProductoController@showJSON');


/**
 * Rutas creación Clientes
 */
Route::resource("cliente", "ClienteController");
Route::get('as/cliente','ClienteController@autosuggest');
Route::get("cliente/showJSON/{id}", 'ClienteController@showJSON');

Route::get("test_xls", 'ClienteController@parserClienteFromXLS');