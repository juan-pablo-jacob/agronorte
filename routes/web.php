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
Route::get("cliente/getArchivo", 'ClienteController@getArchivo');
Route::get("cliente/upload_xls", 'ClienteController@getUploadXLS');
Route::post("cliente/creacion_agil", 'ClienteController@addCliente');
Route::resource("cliente", "ClienteController");
Route::get('as/cliente','ClienteController@autosuggest');
Route::get("cliente/showJSON/{id}", 'ClienteController@showJSON');



Route::post("cliente/agregar_clientes", 'ClienteController@parserClienteFromXLS');
Route::post("cliente/upload_xls", 'ClienteController@uploadXLS');

Route::get("test_xls", 'ClienteController@parserClienteFromXLS');


/**
 * Rutas productos
 */
Route::post("producto/creacion_agil", 'ProductoController@addProducto');
Route::get("producto/multi-upload", "ProductoController@multiUpload");
Route::get("archivo/producto/{id}", "ProductoController@getArchivo");
Route::post("delete_archivo/producto/{id}", "ProductoController@deleteArchivo");
Route::post("producto/multi-upload", "ProductoController@multiUploadSave");
Route::resource("producto", "ProductoController");
Route::get("producto/showJSON/{id}", 'ProductoController@showJSON');
Route::get("as/productosNuevos", 'ProductoController@autosuggestNuevos');
Route::get("as/productosUsados", 'ProductoController@autosuggestUsados');


/**
 * Rutas incentivos
 */
Route::resource("incentivo", "IncentivoController");
Route::get("incentivo/{id}/productos", "IncentivoController@verProductos");
Route::post("incentivo/agregar_productos", "IncentivoController@agregarProductos");
Route::post("incentivo/quitar_productos", "IncentivoController@quitarProductos");
Route::get("getIncetivosProductos/{id}", "IncentivoController@getListIncentivosXproducto");
Route::get("getIncetivosProductosEdit/{id}-{cotizacion_id}", "IncentivoController@getListIncentivosXproductoEdit");

Route::get("incentivo/showJSON/{id}", 'incentivoController@showJSON');


/**
 * Rutas propuestas
 */
Route::get("propuesta/multi-upload", "PropuestaController@multiUpload");
Route::get("archivo/propuesta/{id}", "PropuestaController@getArchivo");
Route::post("delete_archivo/propuesta/{id}", "PropuestaController@deleteArchivo");
Route::post("propuesta/multi-upload", "PropuestaController@multiUploadSave");
Route::resource("propuesta", "PropuestaController");
Route::get("propuesta/cotizacion/{id}", "PropuestaController@editCotizacion");



/**
 * Rutas cotizaciones
 */

Route::get("cotizacion/getTablaCotizaciones", "CotizacionController@getDatosCotizacionesTabla");
Route::resource("cotizacion", "CotizacionController");

/**
 * Mail propuesta Negocio
 */
Route::get("mail_propuesta_negocio/test", "MailPropuestaNegocioController@testSendMail");
Route::get("mail_propuesta_negocio/send/{id}", "MailPropuestaNegocioController@sendMail");
Route::resource("mail_propuesta_negocio", "MailPropuestaNegocioController");


Route::get("actualizar_costos_reales", "CotizacionController@cronActualizarCostoRealProductos");