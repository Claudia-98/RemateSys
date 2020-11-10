<?php



Route::get('/', function () {
    return view('contenido/contenido');
});

// RUTAS DE CATEGORIAS DE PRODUCTO
Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store');
Route::put('/categoria/actualizar', 'CategoriaController@update');
Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
Route::put('/categoria/activar', 'CategoriaController@activar');

// RUTAS DE MEDIDAS DE PRODUCTO
Route::get('/medida', 'MedidaProductoController@index');
Route::post('/medida/registrar', 'MedidaProductoController@store');
Route::put('/medida/actualizar', 'MedidaProductoController@update');
Route::put('/medida/desactivar', 'MedidaProductoController@desactivar');
Route::put('/medida/activar', 'MedidaProductoController@activar');

// RUTAS DE PRODUCTO
Route::get('/producto', 'ProductoController@index');
Route::post('/producto/registrar', 'ProductoController@store');
Route::put('/producto/actualizar', 'ProductoController@update');
Route::put('/producto/desactivar', 'ProductoController@desactivar');
Route::put('/producto/activar', 'ProductoController@activar');
Route::get('/producto/obtenerMedida', 'ProductoController@obtenerMedida');
Route::get('/producto/obtenerPersona', 'ProductoController@obtenerPersona');
Route::get('/producto/obtenerTipo', 'ProductoController@obtenerTipo');